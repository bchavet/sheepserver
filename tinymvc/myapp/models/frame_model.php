<?php

class Frame_Model extends TinyMVC_Model
{

    function getFrame($flock_id, $sheep_id, $frame_id)
    {
        return $this->db->query_init('select * from frame where flock_id=? and sheep_id=? and frame_id=?',
                                     array($flock_id, $sheep_id, $frame_id));
    }

    function getGenome($flock_id, $sheep_id, $frame_id)
    {
        $frame_file = ES_BASEDIR . DS . 'gen' . DS . $flock_id . DS . $sheep_id . DS . $frame_id . '.spex.gz';

        // Make sure file exists
        if (!file_exists($frame_file)) {
            $this->writeGenomeFile($flock_id, $sheep_id, $frame_id);
        }

        ob_start();
        readgzfile($frame_file);
        $genome = ob_get_contents();
        ob_end_clean();
        
        return $genome;
    }

    function getGenomeGZ($flock_id, $sheep_id, $frame_id)
    {
        $frame_file = ES_BASEDIR . DS . 'gen' . DS . $flock_id . DS . $sheep_id . DS . $frame_id . '.spex.gz';

        // Make sure file exists
        if (!file_exists($frame_file)) {
            $this->writeGenomeFile($flock_id, $sheep_id, $frame_id);
        }

        $contents = file_get_contents($frame_file);
        return $contents;
    }

    function writeGenomeFile($flock_id, $sheep_id, $frame_id)
    {
        // Find out if this sheep is a loop or an edge
        $result = $this->db->query_init('select first, last, nframes, spex from sheep where flock_id=? and sheep_id=?', array($flock_id, $sheep_id));

        // Create temp spex file
        $tmp_spex_file = tempnam('/tmp', 'spex');
        $fs = fopen($tmp_spex_file, 'w');
        fwrite($fs, "<spex>\n");
        fwrite($fs, $result['spex'] . "\n");
        fwrite($fs, "</spex>\n");
        fclose($fs);

        // Generate frame spex information
        $frame_file = ES_BASEDIR . DS . 'gen' . DS . $flock_id . DS . $sheep_id . DS . $frame_id . '.spex.gz';
        $gz = gzopen($frame_file, 'w9');
        gzwrite($gz, '<get gen="' . $flock_id . '" id="' . $sheep_id . '" type="0" prog="flame" args="bits=32 jpeg=100" frame="' . $frame_id . '">' . "\n");
        if ($result['first'] == $result['last']) {
            $spex = shell_exec('env rotate=' . $tmp_spex_file . ' enclosed=0 frame=' . $frame_id . ' nframes=' . $result['nframes'] . ' /usr/bin/flam3-genome');
        } else {
            $spex = shell_exec('env inter=' . $tmp_spex_file . ' enclosed=0 frame=' . $frame_id . ' nframes=' . $result['nframes'] . ' /usr/bin/flam3-genome');
        }
        gzwrite($gz, $spex);
        gzwrite($gz, '</get>' . "\n");
        gzclose($gz);
        
        // Cleanup temp file
        unlink($tmp_spex_file);
    }
}