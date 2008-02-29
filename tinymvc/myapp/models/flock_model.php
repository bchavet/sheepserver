<?php

class Flock_Model extends TinyMVC_Model
{

    /**
     * Creates the base directory structure for a new flock with the given
     * generation number
     *
     * @param int $generation  Generation number
     *
     * @return true on success, false otherwise
     */
    function init($generation)
    {
        $path = ES_BASEDIR . DS . 'gen' . DS . $generation;

        // If flock dir already exists, exit
        if (file_exists($path)) {
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
        mkdir($path);
        mkdir($path . DS . 'best-ever');
        mkdir($path . DS . 'txt');

        // Generate text files
        $gz = gzopen($path . DS . 'txt' . DS . 'fail.txt.gz', 'w9');
        gzwrite($gz, '<get gen="' . $generation . '"/>' . "\n");
        gzclose($gz);

        $gz = gzopen($path . DS . 'txt' . DS . 'upgrade.txt.gz', 'w9');
        gzwrite($gz, '<get gen="' . $generation . '"><message>please upgrade to the latest client from www.electricsheep.org</message></get>' . "\n");
        gzclose($gz);

        $gz = gzopen($path . DS . 'txt' . DS . 'blocked.txt.gz', 'w9');
        gzwrite($gz, '<get gen="' . $generation . '"><message>access denied</message></get>' . "\n");
        gzclose($gz);

        $gz = gzopen($path . DS . 'txt' . DS . 'list.txt.gz', 'w9');
        gzwrite($gz, '<list gen="' . $generation . '"/>' . "\n");
        gzclose($gz);

        return true;
    }

}