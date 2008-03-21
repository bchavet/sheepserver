<?php

class Statistics_Model extends TinyMVC_Model
{

    function getAssignedFrames($flock)
    {
        return $this->db->query_all('select * from sheep, frame where frame.flock_id=? and frame.state=? and sheep.sheep_id=frame.sheep_id order by frame.sheep_id, frame.frame_id asc',
                                    array($flock, 'assigned'));
    }

    function getFramesByNick($flock, $nick)
    {
        return $this->db->query_all('select * from sheep, frame where frame.flock_id=? and frame.nick=? and sheep.sheep_id=frame.sheep_id order by frame.sheep_id, frame.frame_id asc',
                                    array($flock, $nick));
    }

}