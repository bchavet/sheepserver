<?php

class Server_Model extends TinyMVC_Model
{
    function get_job($generation)
    {
        $result = $this->db->query_init('select * from frame where flock_id=? and state=? order by sheep_id, frame_id asc', array($generation, 'ready'));
        return $result;
    }

    function assign($flock, $sheep, $frame, $ip, $uid, $nick)
    {
        $result = $this->db->query('update frame set state=?, ip=?, uid=?, nick=?, start_time=?, end_time=? where flock_id=? and sheep_id=? and frame_id=?',
                                   array('assigned', $ip, $uid, $nick, time(), 0, $flock, $sheep, $frame));
    }

    function unassign($flock, $sheep, $frame)
    {
        $result = $this->db->query('update frame set state=?, ip=?, uid=?, nick=?, start_time=?, end_time=? where flock_id=? and sheep_id=? and frame_id=?',
                                   array('ready', null, null, null, null, null, $flock, $sheep, $frame));
    }

    function complete($flock, $sheep, $frame, $ip, $uid, $data)
    {
        // Make sure the job is submitted by the assigned client
        $result = $this->db->query_init('select state from frame where flock_id=? and sheep_id=? and frame_id=? and state=? and ip=? and uid=?',
                                        array($flock, $sheep, $frame, 'assigned', $ip, $uid));

        if ($result) {
            // Save results to a file
            $file = ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $sheep . DS . $frame . '.jpg';
            $fh = fopen($file, 'w+');
            fwrite($fh, $data);
            fclose($fh);

            // Generate a thumbnail
            $thumbnail = ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $sheep . DS . $frame . '.thumbnail.jpg';
            $command = '/usr/bin/convert -size 100x100 -resize 100x100 ' . $file . ' +profile "*" jpeg:"' . $thumbnail . '" 2>&1';
            exec($command, $output, $retval);
            
            // Update database status
            $result = $this->db->query('update frame set state=?, end_time=? where flock_id=? and sheep_id=? and frame_id=?',
                                       array('done', time(), $flock, $sheep, $frame));

            // See if there are any more frames to be rendered
            $result = $this->db->query_init('select count(frame_id) from frame where flock_id=? and sheep_id=? and state != ?',
                                            array($flock, $sheep, 'done'));
            if ($result['count(frame_id)'] == 0) {
                // Mark this sheep as ready to be compressed
                $this->db->query('update sheep set state=? where flock_id=? and sheep_id=?',
                                 array('ready', $flock, $sheep));
            }

            return true;
        }

        // Something was wrong with what was submitted
        return false;
    }

    function get_queue($generation)
    {
        $result = $this->db->query('select * from frame where generation=? and state=? or state like ?', array($generation, 'ready', 'assigned|%'));
    }

}