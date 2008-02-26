<?php

class Sheep_Model extends TinyMVC_Model
{

    /**
     * Generates spex files for a new sheep.
     *
     * @return true on success, false otherwise
     */
    function create($generation, $sheep)
    {
        // If sheep dir already exists, exit
        if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $generation . DS . $sheep)) {
            return false;
        }

        // Create sheep dir
        mkdir(ES_BASEDIR . DS . 'gen' . DS . $generation . DS . $sheep, '0777', true);

        return true;
    }

}