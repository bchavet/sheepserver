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
    
}
