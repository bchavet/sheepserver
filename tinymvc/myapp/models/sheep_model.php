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

    function deleteSheep($flock, $sheep)
    {
        $sheepdir = ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $sheep;

        // Delete frames
        $frames = $this->db->query_all('select * from frame where flock_id=? and sheep_id=?', array($flock, $sheep));
        foreach ($frames as $frame) {
            @unlink($sheepdir . DS . $frame['frame_id'] . '.spex.gz');
            @unlink($sheepdir . DS . $frame['frame_id'] . '.jpg');
            @unlink($sheepdir . DS . $frame['frame_id'] . '.thumbnail.jpg');
        }
        $this->db->query('delete from frame where flock_id=? and sheep_id=?', array($flock, $sheep));

        // Delete sheep
        @unlink($sheepdir . DS . 'spex');
        @unlink($sheepdir . DS . 'sheep.mpg');
        @unlink($sheepdir . DS . 'encode_output');
        @rmdir($sheepdir);
        $this->db->query('update sheep set state=? where flock_id=? and sheep_id=?', array('expunge', $flock, $sheep));

        // Delete any edges that connect to the deleted sheep
        $this->db->query('delete from sheep where flock_id=? and state=? and (first=? or last=?)',
                         array($flock, 'incomplete', $sheep, $sheep));
        $edges = $this->db->query_all('select * from sheep where flock_id=? and state=? and (first=? or last=?)',
                                      array($flock, 'done', $sheep, $sheep));
        foreach ($edges as $edge) {
            $this->deleteSheep($flock, $edge['sheep_id']);
        }
    }

    function getCredit($flock, $sheep)
    {
        $nicks = $this->db->query_all('select distinct nick from frame where flock_id=? and sheep_id=? and nick is not null',
                                      array($flock, $sheep));
        $count = array();
        foreach ($nicks as $nick) {
            $result = $this->db->query_init('select count(*) from frame where flock_id=? and sheep_id=? and nick=?',
                                            array($flock, $sheep, $nick['nick']));
            $count[$nick['nick']] = $result['count(*)'];
        }
        arsort($count, SORT_NUMERIC);
        return $count;
    }

    function getSheep($flock, $sheep)
    {
        return $this->db->query_init('select * from sheep where flock_id=? and sheep_id=?', array($flock, $sheep));
    }

    function getSheepBefore($flock, $sheep)
    {
        $ends = $this->db->query_init('select first, last from sheep where flock_id=? and sheep_id=? and state!=?',
                                      array($flock, $sheep, 'expunge'));

        if ($ends['first'] == $ends['last']) {
            return $this->db->query_all('select * from sheep where flock_id=? and last=? and first!=last and state!=?',
                                        array($flock, $ends['first'], 'expunge'));
        } else {
            return $this->db->query_all('select * from sheep where flock_id=? and last=? and first=last and state!=?',
                                        array($flock, $ends['first'], 'expunge'));
        }
    }

    function getSheepAfter($flock, $sheep)
    {
        $ends = $this->db->query_init('select first, last from sheep where flock_id=? and sheep_id=? and state!=?',
                                      array($flock, $sheep, 'expunge'));

        if ($ends['first'] == $ends['last']) {
            return $this->db->query_all('select * from sheep where flock_id=? and first=? and first!=last and state!=?',
                                        array($flock, $ends['last'], 'expunge'));
        } else {
            return $this->db->query_all('select * from sheep where flock_id=? and first=? and first=last and state!=?',
                                        array($flock, $ends['last'], 'expunge'));
        }
    }

    function sheepExists($flock, $sheep)
    {
        $exists = $this->db->query_init('select * from sheep where flock_id=? and sheep_id=? and state!=?',
                                        array($flock, $sheep, 'expunge'));
        return is_array($exists);
    }
}