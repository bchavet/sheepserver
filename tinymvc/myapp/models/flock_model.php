<?php

class Flock_Model extends TinyMVC_Model
{

    /**
     * Object constructor
     */
    function __construct()
    {
        parent::__construct();

        // Figure out the current flock ID
        $result = $this->db->query_init('select value from config where name=?', array('flock_id'), PDO::FETCH_ASSOC);
        if ($result === false) {
            $this->db->query('insert into config values(?, ?)', array('flock_id', '0'));
            $this->flock_id = 0;
        } else {
            $this->flock_id = $result['value'];
        }
    }

    /**
     * Creates the base directory structure for a new flock
     *
     * @return true on success, false otherwise
     */
    function newFlock()
    {
        $flock_id = $this->flock_id + 1;
        $path = ES_BASEDIR . DS . 'gen' . DS . $flock_id;

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
        gzwrite($gz, '<get gen="' . $flock_id . '"/>' . "\n");
        gzclose($gz);

        $gz = gzopen($path . DS . 'txt' . DS . 'upgrade.txt.gz', 'w9');
        gzwrite($gz, '<get gen="' . $flock_id . '"><message>please upgrade to the latest client from www.electricsheep.org</message></get>' . "\n");
        gzclose($gz);

        $gz = gzopen($path . DS . 'txt' . DS . 'blocked.txt.gz', 'w9');
        gzwrite($gz, '<get gen="' . $flock_id . '"><message>access denied</message></get>' . "\n");
        gzclose($gz);

        $gz = gzopen($path . DS . 'txt' . DS . 'list.txt.gz', 'w9');
        gzwrite($gz, '<list gen="' . $flock_id . '"/>' . "\n");
        gzclose($gz);

        // Everything went well, update flock ID information
        $this->flock_id = $flock_id;
        $result = $this->db->query('update config set value=? where name=?', array($flock_id, 'flock_id'));
        return $result;
    }

    /**
     * Creates a new sheep based on the given spex information
     */
    function newSheep($flock_id, $spex, $nframes, $first = null, $last = null, $extras = null)
    {
        // If this is an edge, make sure it doesn't already exist
        if ($first != $last) {
            $exists = $this->db->query_init('select sheep_id from sheep where flock_id=? and first=? and last=? and state!=?',
                                            array($flock_id, $first, $last, 'expunge'));
            if (is_array($exists)) {
                return;
            }
        }
        
        // Get the next available sheep ID
        $result = $this->db->query_init('select sheep_id from sheep where flock_id=? order by sheep_id desc', array($flock_id));
        $sheep_id = $result['sheep_id'] + 1;
        if ($first === null || $last === null) {
            $first = $last = $sheep_id;
        }
        
        // Create the base directory for the new sheep
        $sheepdir = ES_BASEDIR . DS . 'gen' . DS . $flock_id . DS . $sheep_id;
        mkdir($sheepdir);

        // Add sheep to the database, marked as incomplete
        $this->db->query('insert into sheep (flock_id, sheep_id, first, last, nframes, spex, state, start_time) values (?, ?, ?, ?, ?, ?, ?, ?)',
                         array($flock_id, $sheep_id, $first, $last, $nframes, $spex, 'incomplete', time()));

        // Handle "extras"
        if ($extras !== null) {

            // Add credit link in order to comply with CC license
            if (isset($extras['creditlink'])) {
                $this->db->query('update sheep set credit=? where flock_id=? and sheep_id=?', array($extras['creditlink'], $flock_id, $sheep_id));
            }

            // Keep track of parents if they exist
            if (isset($extras['parent0'])) {
                if (isset($extras['parent1'])) {
                    $this->db->query('update sheep set parent0=?, parent1=? where flock_id=? and sheep_id=?', array($extras['parent0'], $extras['parent1'], $flock_id, $sheep_id));
                } else {
                    $this->db->query('update sheep set parent0=? where flock_id=? and sheep_id=?', array($extras['parent0'], $flock_id, $sheep_id));
                }
            }
        }

        // Create frames in database
        for ($i = 0; $i < $nframes; $i++) {
            $this->db->query('insert into frame (flock_id, sheep_id, frame_id, state) values (?, ?, ?, ?)', array($flock_id, $sheep_id, $i, 'ready'));
        }

        // Return sheep ID
        return $sheep_id;
    }

    function getAllSheep($flock_id)
    {
        return $this->db->query_all('select * from sheep where flock_id=? and first=last order by sheep_id asc',
                                    array($flock_id));
    }

    function getAllEdges($flock_id)
    {
        return $this->db->query_all('select * from sheep where flock_id=? and first!=last order by sheep_id asc',
                                    array($flock_id));
    }

    /**
     * Return the list of completed sheep in the given flock.
     */
    function getCompleteSheep($flock_id)
    {
        return $this->db->query_all('select * from sheep where flock_id=? and state=? and first=last order by sheep_id asc',
                                    array($flock_id, 'done'));
    }

    /**
     * Return the list of completed edges in the given flock.
     */
    function getCompleteEdges($flock_id)
    {
        return $this->db->query_all('select * from sheep where flock_id=? and state=? and first!=last order by sheep_id asc',
                                    array($flock_id, 'done'));
    }

    /**
     * Return the list of archived sheep in the given flock.
     */
    function getArchivedSheep($flock_id)
    {
        return $this->db->query_all('select * from sheep where flock_id=? and state=? and first=last order by sheep_id asc',
                                    array($flock_id, 'archive'));
    }

    /**
     * Return the list of sheep and edges in the rendering queue for the given flock.
     */
    function getQueue($flock_id)
    {
        return $this->db->query_all('select * from sheep where flock_id=? and state=? order by sheep_id asc',
                                    array($flock_id, 'incomplete'));
    }

    /**
     * Return the list of sheep that are done being rendered, and are ready for
     * post-processing
     */
    function getPostQueue($flock_id)
    {
        return $this->db->query_all('select * from sheep where flock_id=? and state=? order by sheep_id asc',
                                    array($flock_id, 'ready'));
    }

    /**
     * Return the list of users that rendered frames, and how many, for the
     * given flock.
     */
    function getCredit($flock_id)
    {
        $nicks = $this->db->query_all('select distinct nick from frame where nick is not null and flock_id=? and state=?',
                                      array($flock_id, 'done'));
        $count = array();
        foreach ($nicks as $nick) {
            $result = $this->db->query_init('select count(nick) from frame where flock_id=? and nick=?',
                                            array($flock_id, $nick['nick']));
            $count[$nick['nick']] = $result['count(nick)'];
        }
        arsort($count, SORT_NUMERIC);
        return $count;
    }

    /**
     * Return the list of frames that are currently assigned to clients to be
     * rendered.
     */
    function getAssigned($flock_id)
    {
        return $this->db->query_all('select * from frame where flock_id=? and state=? order by start_time asc',
                                    array($flock_id, 'assigned'));
    }

    /**
     * Attempts to find a non-existing edge between two random sheep in the
     * given flock.
     */
    function findRandomEdge($flock_id, $first = null, $last = null)
    {
        $loops = $this->db->query_all('select sheep_id from sheep where flock_id=? and first=last and state!=?',
                                      array($flock_id, 'archive'));
        $found = false;
        for ($i = 0; $i < 20 && !$found; $i++) {
            $sheep[0] = $first === null ? (int)$loops[rand(0, count($loops) - 1)]['sheep_id'] : $first;
            $sheep[1] = $last === null ? (int)$loops[rand(0, count($loops) - 1)]['sheep_id'] : $last;
            if ($sheep[0] != $sheep[1]) {
                $result = $this->db->query_init('select sheep_id from sheep where flock_id=? and first=? and last=?',
                                                array($flock_id, $sheep[0], $sheep[1]));
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

}