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

    function getFirst($flock, $sheep)
    {
        $result = $this->db->query_init('select first from sheep where flock_id=? and sheep_id=?',
                                        array($flock, $sheep));
        return $result['first'];
    }

    function getLast($flock, $sheep)
    {
        $result = $this->db->query_init('select last from sheep where flock_id=? and sheep_id=?',
                                        array($flock, $sheep));
        return $result['last'];
    }

    function delete($flock, $sheep)
    {
        $this->db->query('delete from sheep where flock_id=? and sheep_id=?', array($flock, $sheep));
        $this->db->query('delete from frame where flock_id=? and sheep_id=?', array($flock, $sheep));

        $edges = $this->db->query_all('select * from sheep where flock_id=? and (first=? or last=?)',
                                      array($flock, $sheep, $sheep));
        foreach ($edges as $edge) {
            $this->delete($flock, $edge['sheep_id']);
        }
    }
}