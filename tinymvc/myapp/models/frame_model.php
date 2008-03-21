<?php

class Frame_Model extends TinyMVC_Model
{

    function getFrame($flock, $sheep, $frame)
    {
        return $this->db->query_init('select * from frame where flock_id=? and sheep_id=? and frame_id=?',
                                     array($flock, $sheep, $frame));
    }


}