<?php

class Flock_Model extends TinyMVC_Model
{

    function create($generation)
    {
        // If flock dir already exists, exit
        if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $generation)) {
            return false;
        }

        // Make sure some basic directories are present
        if (!file_exists(ES_BASEDIR . DS . 'gen' . DS . 'minithumbs')) {
            mkdir(ES_BASEDIR . DS . 'gen' . DS . 'minithumbs');
        }
        if (!file_exists(ES_BASEDIR . DS . 'gen' . DS . 'designers')) {
            mkdir(ES_BASEDIR . DS . 'gen' . DS . 'designers');
        }
        if (!file_exists(ES_BASEDIR . DS . 'gen' . DS . 'log')) {
            mkdir(ES_BASEDIR . DS . 'gen' . DS . 'log');
        }

        // Create flock dir
        mkdir(ES_BASEDIR . DS . 'gen' . DS . $generation, '0777');
        mkdir(ES_BASEDIR . DS . 'gen' . DS . $generation . DS . 'best-ever', '0777');
        mkdir(ES-BASEDIR . DS . 'gen' . DS . $generation . DS . 'txt', '0777');

        // Generate text files

        // Fill the flock dir
        return true;
    }

}