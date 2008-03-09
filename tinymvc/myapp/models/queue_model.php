<?php

class Queue_Model extends TinyMVC_Model
{
    function get_job($generation)
    {
        $result = $this->db->query_init('select * from frame where generation=? and state=? order by sheep,frame asc', array($generation, 'ready'));
        return $result;
    }

    function assign_job($job, $uid)
    {
        $result = $this->db->query('update frame set state=? where generation=? and sheep=? and frame=?', array('assigned|' . $uid, $job['generation'], $job['sheep'], $job['frame']));
    }
}