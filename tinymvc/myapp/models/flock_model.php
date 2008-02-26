<?php

class Flock_Model extends TinyMVC_Model
{

    function create($generation)
    {
        // If flock dir already exists, exit
        if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $generation)) {
            return false;
        }

        // Create flock dir
        mkdir(ES_BASEDIR . DS . 'gen' . DS . $generation, '0777', true);

        return true;
    }

}