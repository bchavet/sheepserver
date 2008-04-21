<?php

class Stats_Model extends TinyMVC_Model
{

    /**
     * Returns the number of frames that have been assigned to clients in the
     * last $minutes minutes.
     */
    function framesAssigned($minutes = 60)
    {
        $time = time() - ($minutes * 60);
        $result = $this->db->query_init('select count(frame_id) from frame where start_time>?', array($time));
        return $result['count(frame_id)'];
    }

    /**
     * Returns the number of rendered frames that have been submitted by
     * clients in the last $minutes minutes.
     */
    function framesReturned($minutes = 60)
    {
        $time = time() - ($minutes * 60);
        $result = $this->db->query_init('select count(frame_id) from frame where end_time>?', array($time));
        return $result['count(frame_id)'];
    }

    /**
     * Returns the number of sheep that have been completed in the last
     * $minutes minutes.
     */
    function sheepCompleted($minutes = 60)
    {
        $time = time() - ($minutes * 60);
        $result = $this->db->query_init('select count(sheep_id) from sheep where time_done>?', array($time));
        return $result['count(sheep_id)'];
    }
}