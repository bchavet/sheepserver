<?php

class Frame_Model extends TinyMVC_Model
{

    function getFrame($flock, $sheep, $frame)
    {
        return $this->db->query_init('select * from frame where flock_id=? and sheep_id=? and frame_id=?',
                                     array($flock, $sheep, $frame));
    }

    function getGenome($flock, $sheep, $frame)
    {
        $sheepdir = ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $sheep;

        ob_start();
        readgzfile($sheepdir . DS . $frame . '.spex.gz');
        $genome = ob_get_contents();
        ob_end_clean();
        
        return $genome;
    }
}