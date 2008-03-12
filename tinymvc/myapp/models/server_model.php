<?php

class Server_Model extends TinyMVC_Model
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

    function complete_job($job, $uid, $frame)
    {
        // Get who the job was assigned to
        $result = $this->db->query_init('select state from frame where generation=? and sheep=? and frame=? and state like ?', array($job['generation'], $job['sheep'], $job['frame'], 'assigned|%'));
        $assigned = explode('|', $result['state']);

        // If the job assignment matches who submitted it, store it
        if ($assigned[1] == $uid) {
            // Update database status
            $result = $this->db->query('update frame set state=? where generation=? and sheep=? and frame=?', array('done|' . $uid, $job['generation'], $job['sheep'], $job['frame']));

            // Save results to a file
            $file = ES_BASEDIR . DS . 'gen' . DS . $job['generation'] . DS . $job['sheep'] . DS . $job['frame'] . '.jpg';
            $fh = fopen($file, 'w+');
            fwrite($fh, $frame);
            fclose($fh);

            // Generate a thumbnail
            $thumbnail = ES_BASEDIR . DS . 'gen' . DS . $job['generation'] . DS . $job['sheep'] . DS . $job['frame'] . '.thumbnail.jpg';
            $command = '/usr/bin/convert -size 100x100 -resize 100x100 ' . $file . ' +profile "*" jpeg:"' . $thumbnail . '" 2>&1';
            exec($command, $output, $retval);
            
            return true;
        }

        // There was a problem with what was submitted
        return false;
    }

    function get_queue($generation)
    {
        $result = $this->db->query('select * from frame where generation=? and state=? or state like ?', array($generation, 'ready', 'assigned|%'));
    }

}