<?php

class Server_Model extends TinyMVC_Model
{
    function get_job($generation)
    {
        // First, see if there are any frame 0's that are not assigned.
        // If so, we want them to get assigned first so the UI looks nice.
        $result = $this->db->query_init('select frame.* from frame, sheep where frame.flock_id=? and frame.state=? and frame.frame_id=? and sheep.first=sheep.last and frame.sheep_id=sheep.sheep_id order by frame.sheep_id asc', array($generation, 'ready', 0));
        if ($result !== false) {
            return $result;
        }

        // Fall back to returning the oldest frame
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

    function encodeSheep($flock)
    {
        $result = $this->db->query_all('select * from sheep where flock_id=? and state=? order by sheep_id asc', array($flock, 'ready'));
        if ($result) {
            // mark our list of sheep as being encoded.  This process can take
            // a while, and we don't want the next encoding round to step on
            // our toes.
            foreach ($result as $sheep) {
                $this->db->query('update sheep set state=? where flock_id=? and sheep_id=?', array('encoding', $flock, $sheep['sheep_id']));
            }

            // Encode each sheep
            foreach ($result as $sheep) {
                $sheepdir = ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $sheep['sheep_id'];
                $cmd = 'jpeg2yuv -f 30 -I p -v 0 -j ./%d.jpg | mpeg2enc --format 9 --no-constraints --video-bitrate 9000 --video-buffer 500 --quantisation 1 --frame-rate 5 --aspect 3 -o sheep.mpg --multi-thread 0 > encode_output 2>&1';
                shell_exec('cd ' . $sheepdir . ' && ' . $cmd);
                $this->db->query('update sheep set state=?, size=?, time_done=? where flock_id=? and sheep_id=?', array('done', filesize($sheepdir . DS . 'sheep.mpg'), time(), $flock, $sheep['sheep_id']));
            }
        }
    }

    function getList($flock)
    {
        // Get a list of sheep that are ready to be downloaded
        $sheeplist = $this->db->query_all('select * from sheep where flock_id=? and state=?', array($flock, 'done'));

        // Generate list XML(?)
        $list = "<list gen=\"$flock\">\n";
        foreach ($sheeplist as $sheep) {
            $list .= '<sheep id="' . $sheep['sheep_id'] . '" type="0" state="done" time="' . $sheep['time_done'] . '" size="' . $sheep['size'] . '" rating="' . $sheep['rating'] . '" first="' . $sheep['first'] . '" last="' . $sheep['last'] . '" url="http://sheep.badllama.com/gen/' . $sheep['flock_id'] . '/' . $sheep['sheep_id'] . '/sheep.mpg"/>' . "\n";
        }
        $list .= "</list>\n";
        return $list;
    }

}