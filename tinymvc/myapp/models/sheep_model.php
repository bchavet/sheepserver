<?php

class Sheep_Model extends TinyMVC_Model
{

    /**
     * Generates spex files for a new sheep.
     *
     * @return true on success, false otherwise
     */
    function init($generation, $sheep)
    {
        // If the generation dir does not exist, exit
        if (!file_exists(ES_BASEDIR . DS . 'gen' . DS . $generation)) {
            return false;
        }

        // If sheep dir already exists, exit
        if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $generation . DS . $sheep)) {
            return false;
        }

        // Create sheep dir
        mkdir(ES_BASEDIR . DS . 'gen' . DS . $generation . DS . $sheep);

        // Add sheep to database
        $this->db->query('insert into animation (generation, sheep, creation_date, state) values (?, ?, ?, ?)', array($generation, $sheep, time(), 'empty'));

        return true;
    }

    /**
     * Get the next available sheep id for the given generation
     */
    function next_sheep_id($generation)
    {
        $result = $this->db->query_init('select sheep from animation where generation=? order by sheep desc', array($generation));
        return $result['sheep'] + 1;
    }

    /**
     * Get the frames for the given sheep
     */
    function get_frames($generation, $sheep)
    {
        return $this->db->query_all('select * from frame where generation=? and sheep=?', array($generation, $sheep));
    }

    /**
     * Generate a sheep spex file with a random rotation
     */
    function random_rotation($generation, $sheep, $nframes, $p0id = null, $p1id = null)
    {
        $param_file = 'anim_params.template';
        $anim_dir = ES_BASEDIR . DS . 'gen' . DS . $generation . DS . $sheep;

        $mutate_only = !empty($p0id) && empty($p1id);

        // Choose the animation param file
        if (25 < mt_rand(0, 100)) {
            $anim_params = ES_BASEDIR . DS . 'templates' . DS . 'vib.' . $param_file;
        } else {
            $anim_params = ES_BASEDIR . DS . 'templates' . DS . $param_file;
        }
        $spex_file = $anim_dir . DS . 'spex';
        
        // Generate the spex file
        $cmd = "env enclosed=0 template=$anim_params nframes=$nframes /usr/bin/flam3-genome > $spex_file";
        $result = system($cmd);
        if ($result !== false) {
            $this->spex($generation, $sheep, $nframes, $sheep, $sheep);
        }
    }

    /**
     * Generate per-frame spex files
     */
    function spex($generation, $sheep, $nframes, $first, $last)
    {
        $anim_params = ES_BASEDIR . DS . 'templates' . DS . 'anim_params.template';
        $spex_file = ES_BASEDIR . DS . 'gen' . DS . $generation . DS . $sheep . DS . 'spex';

        // Copy contents of spex file into a temp file. 
        $tmp_spex_file = $spex_file . '.tmp';
        system('echo "<spex>" > ' . $tmp_spex_file);
        system('cat ' . $spex_file . ' >> ' . $tmp_spex_file);
        system('echo "</spex>" >> ' . $tmp_spex_file);

        // Generate spex file for each frame
        $anim_dir = ES_BASEDIR . DS . 'gen' . DS . $generation . DS . $sheep;
        for ($i = 0; $i < $nframes; $i++) {
            $gz = gzopen($anim_dir . DS . $i . '.spex', 'w9');
            gzwrite($gz, '<get gen="' . $generation . '" id="' . $sheep . '" type="0" prog="flame" args="bits=32 jpeg=100" frame="' . $i . '">' . "\n");
            if ($first == $last) {
                $spex = shell_exec("env rotate=$tmp_spex_file enclosed=0 frame=$i nframes=$nframes /usr/bin/flam3-genome");
            } else {
                $spex = shell_exec("env inter=$tmp_spex_file enclosed=0 frame=$i nframes=$nframes /usr/bin/flam3-genome");
            }
            gzwrite($gz, $spex);
            gzwrite($gz, '</get>' . "\n");
            gzclose($gz);

            // Add frame to the database
            $this->db->query('insert into frame (generation, sheep, frame, state) values (?, ?, ?, ?)', array($generation, $sheep, $i, 'ready'));
        }

        // Delete temp file
        unlink($tmp_spex_file);
    }

}