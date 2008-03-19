<?php

class Statistics_Model extends TinyMVC_Model
{

    function getRendererCount($flock)
    {
        $nicks = $this->db->query_all('select distinct nick from frame where nick is not null', array());
        $count = array();
        foreach ($nicks as $nick) {
            $result = $this->db->query_init('select count(*) from frame where nick=?', array($nick['nick']));
            $count[$nick['nick']] = $result['count(*)'];
        }
        arsort($count, SORT_NUMERIC);
        return $count;
    }

    function getAssignedFrames($flock)
    {
        return $this->db->query_all('select * from frame where flock_id=? and state=? order by sheep_id, frame_id asc',
                                    array($flock, 'assigned'));
    }
}