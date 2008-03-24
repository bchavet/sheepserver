<?php

class Flock_Model extends TinyMVC_Model
{

    /**
     * Object constructor
     */
    function __construct()
    {
        parent::__construct();

        // Figure out what generation this flock is
        $result = $this->db->query_init('select value from config where name=?', array('generation'), PDO::FETCH_ASSOC);
        if ($result === false) {
            $this->db->query('insert into config values(?, ?)', array('generation', '0'));
            $this->generation = 0;
        } else {
            $this->generation = $result['value'];
        }
    }

    /**
     * Creates the base directory structure for a new flock
     *
     * @return true on success, false otherwise
     */
    function newFlock()
    {
        $generation = $this->generation + 1;
        $path = ES_BASEDIR . DS . 'gen' . DS . $generation;

        // If flock dir already exists, exit
        if (file_exists($path)) {
            return false;
        }

        // Make sure some basic directories are present
        if (!file_exists(ES_BASEDIR . DS . 'gen' . DS . 'minithumbs')) {
            mkdir(ES_BASEDIR . DS . 'gen' . DS . 'minithumbs');
        }
        if (!file_exists(ES_BASEDIR . DS . 'gen' . DS . 'designers')) {
            mkdir(ES_BASEDIR . DS . 'gen' . DS . 'designers');
        }
        if (!file_exists(ES_BASEDIR . DS . 'gen' . DS . 'log')) {
            mkdir(ES_BASEDIR . DS . 'gen' . DS . 'log');
        }

        // Create flock dir
        mkdir($path);
        mkdir($path . DS . 'best-ever');
        mkdir($path . DS . 'txt');

        // Generate text files
        $gz = gzopen($path . DS . 'txt' . DS . 'fail.txt.gz', 'w9');
        gzwrite($gz, '<get gen="' . $generation . '"/>' . "\n");
        gzclose($gz);

        $gz = gzopen($path . DS . 'txt' . DS . 'upgrade.txt.gz', 'w9');
        gzwrite($gz, '<get gen="' . $generation . '"><message>please upgrade to the latest client from www.electricsheep.org</message></get>' . "\n");
        gzclose($gz);

        $gz = gzopen($path . DS . 'txt' . DS . 'blocked.txt.gz', 'w9');
        gzwrite($gz, '<get gen="' . $generation . '"><message>access denied</message></get>' . "\n");
        gzclose($gz);

        $gz = gzopen($path . DS . 'txt' . DS . 'list.txt.gz', 'w9');
        gzwrite($gz, '<list gen="' . $generation . '"/>' . "\n");
        gzclose($gz);

        // Everything went well, update generation information
        $this->generation = $generation;
        $result = $this->db->query('update config set value=? where name=?', array($generation, 'generation'));
        return $result;
    }

    /**
     * Returns the requested sheep, or all sheep in the flock if no individual
     * sheep is specified.
     */
    function getSheep($sheep = null)
    {
        if ($sheep === null) {
            return $this->db->query_all('select * from sheep where flock_id=? and first=last order by sheep_id asc', array($this->generation));
        } else {
            return $this->db->query_init('select * from sheep where flock_id=? and sheep_id=?', array($this->generation, $sheep));
        }
    }

    function getEdges()
    {
        return $this->db->query_all('select * from sheep where flock_id=? and first!=last order by sheep_id asc', array($this->generation));
    }

    /**
     * Creates a new sheep based on the given spex information
     */
    function newSheep($spex, $nframes, $first = null, $last = null)
    {
        // Get the next available sheep ID
        $sheep = $this->_getNextSheepId();
        if ($first === null || $last === null) {
            $first = $last = $sheep;
        }
        
        // Create the base directory for the new sheep
        $sheepdir = ES_BASEDIR . DS . 'gen' . DS . $this->generation . DS . $sheep;
        mkdir($sheepdir);

        // Write spex file
        $spex_file = $sheepdir . DS . 'spex';
        $fs = fopen($spex_file, 'w');
        fwrite($fs, $spex);
        fclose($fs);

        // Add sheep to the database, marked as incomplete
        $this->db->query('insert into sheep (flock_id, sheep_id, state, first, last, rating) values (?, ?, ?, ?, ?, ?)', array($this->generation, $sheep, 'incomplete', $first, $last, 0));

        // Create copy of spex file for use by flam3-genome
        $tmp_spex_file = $spex_file . '.tmp';
        system('echo "<spex>" > ' . $tmp_spex_file);
        system('cat ' . $spex_file . ' >> ' . $tmp_spex_file);
        system('echo "</spex>" >> ' . $tmp_spex_file);

        // Generate spex file for each frame
        for ($i = 0; $i < $nframes; $i++) {
            $gz = gzopen($sheepdir . DS . $i . '.spex.gz', 'w9');
            gzwrite($gz, '<get gen="' . $this->generation . '" id="' . $sheep . '" type="0" prog="flame" args="bits=32 jpeg=100" frame="' . $i . '">' . "\n");
            if ($first == $last) {
                $spex = shell_exec("env rotate=$tmp_spex_file enclosed=0 frame=$i nframes=$nframes /usr/bin/flam3-genome");
            } else {
                $spex = shell_exec("env inter=$tmp_spex_file enclosed=0 frame=$i nframes=$nframes /usr/bin/flam3-genome");
            }
            gzwrite($gz, $spex);
            gzwrite($gz, '</get>' . "\n");
            gzclose($gz);

            // Add frame to the database
            $this->db->query('insert into frame (flock_id, sheep_id, frame_id, state) values (?, ?, ?, ?)', array($this->generation, $sheep, $i, 'ready'));
        }

        // Delete temp file
        unlink($tmp_spex_file);
        
    }

    /**
     * Get the next available sheep ID for this flock
     */
    function _getNextSheepId()
    {
        $result = $this->db->query_init('select sheep_id from sheep where flock_id=? order by sheep_id desc', array($this->generation));
        return $result['sheep_id'] + 1;
    }

    function findRandomEdge()
    {
        $loops = $this->db->query_all('select sheep_id from sheep where flock_id=? and first = last', array($this->generation));
        $found = false;
        for ($i = 0; $i < 20 && !$found; $i++) {
            $sheep[0] = $loops[rand(0, count($loops) - 1)]['sheep_id'];
            $sheep[1] = $loops[rand(0, count($loops) - 1)]['sheep_id'];
            if ($sheep[0] != $sheep[1]) {
                $result = $this->db->query_init('select sheep_id from sheep where flock_id=? and first=? and last=?', array($this->generation, $sheep[0], $sheep[1]));
                if ($result === false) {
                    $found = true;
                }
            }
        }

        if ($found) {
            return $sheep;
        }

        return false;
    }

    function deleteFrame($sheep, $frame)
    {
        $sheepdir = ES_BASEDIR . DS . 'gen' . DS . $this->generation . DS . $sheep;
        
        // Delete jpeg
        if (file_exists($sheepdir . DS . $frame . '.jpg')) {
            unlink($sheepdir . DS . $frame . '.jpg');
        }

        // Delete thumbnail
        if (file_exists($sheepdir . DS . $frame . '.thumbnail.jpg')) {
            unlink($sheepdir . DS . $frame . '.thumbnail.jpg');
        }

        // Reset database entry
        $this->db->query('update frame set state=?, ip=?, uid=?, nick=?, start_time=?, end_time=? where flock_id=? and sheep_id=? and frame_id=?',
                         array('ready', null, null, null, null, null, $this->generation, $sheep, $frame));
        $this->db->query('update sheep set state=? where flock_id=? and sheep_id=?',
                         array('incomplete', $this->generation, $sheep));
    }

    function getCompleteSheep($flock)
    {
        return $this->db->query_all('select * from sheep where flock_id=? and state=? and first=last order by sheep_id asc',
                                    array($flock, 'done'));
    }

    function getCompleteEdges($flock)
    {
        return $this->db->query_all('select * from sheep where flock_id=? and state=? and first!=last order by sheep_id asc',
                                    array($flock, 'done'));
    }

    function getQueue($flock)
    {
        return $this->db->query_all('select * from sheep where flock_id=? and state=? order by sheep_id asc',
                                    array($flock, 'incomplete'));
    }

    function getPostQueue($flock)
    {
        return $this->db->query_all('select * from sheep where flock_id=? and state=? order by sheep_id asc',
                                    array($flock, 'ready'));
    }

    function getCredit($flock)
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

    function getAssigned($flock)
    {
        return $this->db->query_all('select * from sheep, frame where frame.flock_id=? and frame.state=? and sheep.sheep_id=frame.sheep_id order by frame.sheep_id, frame.frame_id asc',
                                    array($flock, 'assigned'));
    }

    function prune($flock, $ndays)
    {
        $date = time() - ($ndays * 86400);
        $sheeplist = $this->db->query_all('select * from sheep where flock_id=? and  time_done < ?', array($flock, $date));
        echo '<pre>'; echo var_dump($sheeplist); echo '</pre>';
    }

}