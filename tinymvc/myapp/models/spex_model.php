<?php

class Spex_Model extends TinyMVC_Model
{

    /**
     * Generate a sheep spex file with a random rotation
     */
    function random_rotation($nframes)
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

    function edge($flock, $sheep0, $sheep1, $nframes)
    {
        // Get the spex information for sheep0
        $spex0 = file_get_contents(ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $sheep0 . DS . 'spex');

        // Get the spex information for sheep1
        $spex1 = file_get_contents(ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $sheep1 . DS . 'spex');
        $spex1 = str_replace('time="0"', 'time="' . $nframes . '"', $spex1);

        // Return the merged spex file
        return $spex0 . $spex1;
    }
    
}
