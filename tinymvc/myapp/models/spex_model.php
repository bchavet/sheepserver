<?php

class Spex_Model extends TinyMVC_Model
{

    /**
     * Generate a sheep spex file with a random rotation
     */
    function random_rotation($nframes, $parent0 = null, $parent1 = null)
    {
        // Choose the animation param file
        $param_file = 'anim_params.template';
        if (25 < mt_rand(0, 100)) {
            $anim_params = ES_BASEDIR . DS . 'templates' . DS . 'vib.' . $param_file;
        } else {
            $anim_params = ES_BASEDIR . DS . 'templates' . DS . $param_file;
        }
        
        // Generate the spex
        $cmd = "env enclosed=0 template=$anim_params nframes=$nframes /usr/bin/flam3-genome";
        $spex = shell_exec($cmd);
        if (empty($spex)) {
            return false;
        }
        return $spex;
    }

    function symmetry_singularity($nframes)
    {
        $param_file = 'anim_params.template';
        $anim_params = ES_BASEDIR . DS . 'templates' . DS . 'vib.' . $param_file;
        
        $sym = 15 + mt_rand(0, 45);
        if (50 < mt_rand(0, 100)) {
            $sym = 0 - $sym;
        }

        $extras = "singularity=\"spiral $sym\"";
        $cmd = "env enclosed=0 symmetry=$sym template=$anim_params nframes=$nframes 'extras=$extras' /usr/bin/flam3-genome";

        $spex = shell_exec($cmd);
        if (empty($spex)) {
            return false;
        }
        return $spex;
    }

    function edge($flock, $sheep0, $sheep1, $nframes)
    {
        // Make sure the edge does not already exist
        $result = $this->db->query_init('select sheep_id from sheep where first=? and last=? and state!=?',
                                        array($sheep0, $sheep1, 'expunge'));
        if (!empty($result)) {
            return;
        }

        // Get the spex information for sheep0
        $spex0 = file_get_contents(ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $sheep0 . DS . 'spex');

        // Get the spex information for sheep1
        $spex1 = file_get_contents(ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $sheep1 . DS . 'spex');
        $spex1 = str_replace('time="0"', 'time="' . $nframes . '"', $spex1);

        // Return the merged spex file
        return $spex0 . $spex1;
    }

    function mutate_rotation($flock, $parent0, $nframes)
    {
        // Choose the animation param file
        $param_file = 'anim_params.template';
        if (25 < mt_rand(0, 100)) {
            $anim_params = ES_BASEDIR . DS . 'templates' . DS . 'vib.' . $param_file;
        } else {
            $anim_params = ES_BASEDIR . DS . 'templates' . DS . $param_file;
        }

        // Set up parent0 input file
        $parent0_spex = file_get_contents(ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $parent0 . DS . 'spex');
        $parent0_tmp = tempnam('/tmp', 'es');
        $fs = fopen($parent0_tmp, 'w');
        fwrite($fs, '<p0>' . $parent0_spex . "</p0>\n");
        fclose($fs);

        // Generate spex file
        $cmd = "env enclosed=0 template=$anim_params mutate=$parent0_tmp nframes=$nframes 'extras=parents=\"$parent0\"' /usr/bin/flam3-genome";
        $spex = shell_exec($cmd);
        if (empty($spex)) {
            return false;
        }
        return $spex;
    }
    
    function mate_rotation($flock, $parent0, $parent1, $nframes)
    {
        // Choose the animation param file
        $param_file = 'anim_params.template';
        if (25 < mt_rand(0, 100)) {
            $anim_params = ES_BASEDIR . DS . 'templates' . DS . 'vib.' . $param_file;
        } else {
            $anim_params = ES_BASEDIR . DS . 'templates' . DS . $param_file;
        }

        // Set up parent0 input file
        $parent0_spex = file_get_contents(ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $parent0 . DS . 'spex');
        $parent0_tmp = tempnam('/tmp', 'es');
        $fs = fopen($parent0_tmp, 'w');
        fwrite($fs, '<p0>' . $parent0_spex . "</p0>\n");
        fclose($fs);

        // Set up parent1 input file
        $parent1_spex = file_get_contents(ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $parent1 . DS . 'spex');
        $parent1_tmp = tempnam('/tmp', 'es');
        $fs = fopen($parent1_tmp, 'w');
        fwrite($fs, '<p1>' . $parent1_spex . "</p1>\n");
        fclose($fs);

        // Generate spex file
        $cmd = "env enclosed=0 template=$anim_params cross0=$parent0_tmp cross1=$parent1_tmp nframes=$nframes 'extras=parents=\"$parent0 $parent1\"' /usr/bin/flam3-genome";
        $spex = shell_exec($cmd);
        if (empty($spex)) {
            return false;
        }
        return $spex;
    }

}
