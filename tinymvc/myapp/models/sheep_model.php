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
        $result = $this->db->query_init('select count(sheep_id) from frame where flock_id=? and sheep_id=? and state=?',
                                        array($flock, $sheep, 'done'));
        return $result['count(sheep_id)'];
    }

    function getGenome($flock, $sheep)
    {
        $result = $this->db->query_init('select spex from sheep where flock_id=? and sheep_id=?',
                                        array($flock, $sheep));
        return $result['spex'];
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

    function deleteSheep($flock_id, $sheep_id, $archive = true)
    {
        $sheepdir = ES_BASEDIR . DS . 'gen' . DS . $flock_id . DS . $sheep_id;
        $sheep = $this->getSheep($flock_id, $sheep_id);
        if ($sheep['first'] != $sheep['last']) {
            $archive = false;
        }

        // Delete frames
        $frames = $this->db->query_all('select * from frame where flock_id=? and sheep_id=?', array($flock_id, $sheep_id));
        foreach ($frames as $frame) {
            if (file_exists($sheepdir . DS . $frame['frame_id'] . '.spex.gz')) {
                @unlink($sheepdir . DS . $frame['frame_id'] . '.spex.gz');
            }
            if (file_exists($sheepdir . DS . $frame['frame_id'] . '.jpg')) {
                @unlink($sheepdir . DS . $frame['frame_id'] . '.jpg');
            }
            if (!$archive || ($archive && $frame['frame_id'] != 0)) {
                if (file_exists($sheepdir . DS . $frame['frame_id'] . '.thumbnail.jpg')) {
                    @unlink($sheepdir . DS . $frame['frame_id'] . '.thumbnail.jpg');
                }
            }
        }
        $this->db->query('delete from frame where flock_id=? and sheep_id=?', array($flock_id, $sheep_id));

        // Delete sheep
        if (file_exists($sheepdir . DS . 'sheep.mpg')) {
            @unlink($sheepdir . DS . 'sheep.mpg');
        }
        if (file_exists($sheepdir . DS . 'encode_output')) {
            @unlink($sheepdir . DS . 'encode_output');
        }
        if (!$archive) {
            if (file_exists($sheepdir . DS . '0.image.jpg')) {
                @unlink($sheepdir . DS . '0.image.jpg');
            }
            @rmdir($sheepdir);
            $this->db->query('delete from sheep where flock_id=? and sheep_id=? and state=?', array($flock_id, $sheep_id, 'incomplete'));
            $this->db->query('update sheep set state=?, modified_time=? where flock_id=? and sheep_id=?', array('expunge', time(), $flock_id, $sheep_id));
        } else {
            $this->db->query('update sheep set state=?, modified_time=? where flock_id=? and sheep_id=?', array('archive', time(), $flock_id, $sheep_id));
        }

        // Delete any edges that connect to the deleted sheep
        $edges = $this->db->query_all('select sheep_id from sheep where flock_id=? and first!=last and (first=? or last=?) and state!=?',
                                      array($flock_id, $sheep_id, $sheep_id, 'expunge'));
        foreach ($edges as $edge) {
            $this->deleteSheep($flock_id, $edge['sheep_id'], false);
        }
    }

    function archiveSheep($flock, $sheep)
    {
        $this->deleteSheep($flock, $sheep, true);
    }

    function requeueSheep($flock_id, $sheep_id)
    {
        // Restore state
        $this->db->query('update sheep set state=?, modified_time=? where flock_id=? and sheep_id=?',
                         array('incomplete', time(), $flock_id, $sheep_id));

        // Restore frames in database
        $sheep = $this->getSheep($flock_id, $sheep_id);
        for ($i = 0; $i < $sheep['nframes']; $i++) {
            $this->db->query('insert into frame (flock_id, sheep_id, frame_id, state) values (?, ?, ?, ?)', array($flock_id, $sheep_id, $i, 'ready'));
        }
    }

    function getCredit($flock, $sheep)
    {
        $nicks = $this->db->query_all('select distinct nick from frame where flock_id=? and sheep_id=? and nick is not null',
                                      array($flock, $sheep));
        $count = array();
        foreach ($nicks as $nick) {
            $result = $this->db->query_init('select count(frame_id) from frame where flock_id=? and sheep_id=? and nick=?',
                                            array($flock, $sheep, $nick['nick']));
            $count[$nick['nick']] = $result['count(frame_id)'];
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
        $exists = $this->db->query_init('select sheep_id from sheep where flock_id=? and sheep_id=? and state!=?',
                                        array($flock, $sheep, 'expunge'));
        return is_array($exists);
    }

    function edgeExists($flock_id, $first, $last)
    {
        $exists = $this->db->query_init('select sheep_id from sheep where flock_id=? and first=? and last=? and state!=?',
                                        array($flock_id, $first, $last, 'expunge'));
        return is_array($exists);
    }

    function getAuthorCredit($flock, $sheep)
    {
        $results = $this->db->query_init('select credit from sheep where flock_id=? and sheep_id=?',
                                        array($flock, $sheep));
        return $results['credit'];
    }

    function getMissingEdges($flock_id, $sheep_id, $direction = 'both')
    {
        // Get all other loops
        $loops = $this->db->query_all('select * from sheep where flock_id=? and sheep_id!=? and first=last and state!=? and state!=?',
                                      array($flock_id, $sheep_id, 'expunge', 'archive'));

        // Find missing edges
        $edges = array();
        foreach ($loops as $sheep) {
            // Check outgoing edges
            if ($direction == 'out' || $direction == 'both') {
                if (!$this->edgeExists($flock_id, $sheep_id, $sheep['sheep_id'])) {
                    $edges[] = array('first' => $sheep_id,
                                     'last' => $sheep['sheep_id']);
                }
            }

            // Check incoming edges
            if ($direction == 'in' || $direction == 'both') {
                if (!$this->edgeExists($flock_id, $sheep['sheep_id'], $sheep_id)) {
                    $edges[] = array('first' => $sheep['sheep_id'],
                                     'last' => $sheep_id);
                }
            }
        }

        return $edges;
    }

    function getParents($flock, $sheep)
    {
        return $this->db->query_init('select parent0, parent1 from sheep where flock_id=? and sheep_id=?',
                                     array($flock, $sheep));
    }

    function getSiblings($flock, $sheep)
    {
        $parents = $this->getParents($flock, $sheep);
        if ($parents['parent0'] !== null && $parents['parent1'] !== null) {
            $result = $this->db->query_all('select * from sheep where flock_id=? and (parent0=? or parent0=? or parent1=? or parent1=?)',
                                           array($flock, $parents['parent0'], $parents['parent1'], $parents['parent0'], $parents['parent1']));
        } else if ($parents['parent0'] !== null && $parents['parent1'] === null) {
            $result = $this->db->query_all('select * from sheep where flock_id=? and (parent0=? or parent1=?)',
                                           array($flock, $parents['parent0'], $parents['parent0']));
        } else if ($parents['parent1'] !== null && $parents['parent0'] === null) {
            $result = $this->db->query_all('select * from sheep where flock_id=? and (parent0=? or parent1=?)',
                                           array($flock, $parents['parent1'], $parents['parent1']));
        } else {
            $result = null;
        }

        return $result;
    }

    function getChildren($flock, $sheep)
    {
        return  $this->db->query_all('select * from sheep where flock_id=? and (parent0=? or parent1=?)',
                                     array($flock, $sheep, $sheep));
    }

    function countVotes($ip_address)
    {
        $time = time() - 86400;
        $result = $this->db->query_init('select count(ip_address) from votes where vote_time>?', array($time));
        return $result['count(ip_address)'];
    }

    function castVote($flock_id, $sheep_id, $vote, $ip_address, $count = true)
    {
        // Get current rating information
        $current = $this->db->query_init('select rating, rating_max, rating_min, num_votes, first, last from sheep where flock_id=? and sheep_id=?',
                                         array($flock_id, $sheep_id));
 
        // Make sure we're voting on a sheep, not an edge
        if ($current['first'] == $current['last']) {
            // Calculate new rating values
            $current['rating'] += $vote;
            $current['num_votes'] += abs($vote);
            if ($current['rating'] > $current['rating_max']) {
                $current['rating_max'] = $current['rating'];
            }
            if ($current['rating'] < $current['rating_min']) {
                $current['rating_min'] = $current['rating'];
            }
            
            // Update rating information
            $this->db->query('update sheep set rating=?, rating_max=?, rating_min=?, num_votes=? where flock_id=? and sheep_id=?',
                             array($current['rating'], $current['rating_max'], $current['rating_min'], $current['num_votes'], $flock_id, $sheep_id));
            
            // Update IP Address vote count
            if ($count) {
                for ($i = 0; $i < abs($vote); $i++) {
                    $this->db->query('insert into votes (ip_address, vote_time) values (?, ?)',
                                     array($ip_address, microtime(true)));
                }
            }
        }
    }
}