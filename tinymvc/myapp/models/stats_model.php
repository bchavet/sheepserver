<?php

class Stats_Model extends TinyMVC_Model
{

    function framesAssigned($minutes = 60)
    {
        $time = time() - ($minutes * 60);
        $result = $this->db->query_init('select count(frame_id) from frame where start_time>?', array($time));
        return $result['count(frame_id)'];
    }

    function framesReturned($minutes = 60)
    {
        $time = time() - ($minutes * 60);
        $result = $this->db->query_init('select count(frame_id) from frame where end_time>?', array($time));
        return $result['count(frame_id)'];
    }

    function sheepCompleted($minutes = 60)
    {
        $time = time() - ($minutes * 60);
        $result = $this->db->query_init('select count(sheep_id) from sheep where time_done>?', array($time));
        return $result['count(sheep_id)'];
    }
}