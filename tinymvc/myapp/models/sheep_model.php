<?php

class Sheep_Model extends TinyMVC_Model
{

    function getFrames($flock, $sheep)
    {
        return $this->db->query_all('select * from frame where flock_id=? and sheep_id=? order by sheep_id, frame_id asc',
                                    array($flock, $sheep));
    }

    function countCompletedFrames($flock, $sheep)
    {
        $result = $this->db->query_init('select count(*) from frame where flock_id=? and sheep_id=? and state=?',
                                        array($flock, $sheep, 'done'));
        return $result['count(*)'];
    }

    function getGenome($flock, $sheep)
    {
        $sheepdir = ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $sheep;
        $genome = file_get_contents($sheepdir . DS . 'spex');
        return $genome;
    }
}