<?php

class cgi_Controller extends TinyMVC_Controller
{
    var $method_map = array('list' => 'listsheep',
                            'vote.cgi' => 'vote');

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('config_model', 'config');
        $this->load->model('server_model', 'server');
    }

    /**
     * Handle undefined method calls
     */
    function __call($name, $args)
    {
        $this->index();
    }

    /**
     * Default controller method
     */
    function index()
    {
        header('Location: /flock/status');
        exit;
    }

    /**
     * Return a list of available sheep to the client.
     */
    function listsheep()
    {
        $list = $this->server->getList($this->config->flock_id);
        echo gzencode($list, 9);
    }

    /**
     * Retrieve the next available job, if there is one, and assign it to the
     * client.
     */
    function get()
    {
        $this->load->model('frame_model', 'frame');

        // Attempt to get a job from the server
        $job = $this->server->get_job($this->config->flock_id);
        if ($job === false) {
            return false;
        }

        // Return the job to the client to start processing
        echo $this->frame->getGenomeGZ($job['flock_id'], $job['sheep_id'], $job['frame_id']);

        // Assign the job to the requesting client
        $this->server->assign($job['flock_id'], $job['sheep_id'], $job['frame_id'], $_SERVER['REMOTE_ADDR'], addslashes($_REQUEST['u']), addslashes($_REQUEST['n']));
    }

    /**
     * Accept a rendered frame from a client.
     */
    function put()
    {
        // Get raw POST data
        $data = file_get_contents('php://input');
 
        // Submit the job
        $result = $this->server->complete($this->config->flock_id, (int)$_GET['id'], (int)$_GET['f'], $_SERVER['REMOTE_ADDR'], addslashes($_GET['u']), $data);
    }

    /**
     * Scheduled maintenance, must be called periodically in order to render
     * sheep that are ready, as well as clean up stale assignments.
     */
    function maintenance()
    {
        // Encode any sheep that have all of their frames rendered
        $sheep = $this->server->encodeSheep($this->config->flock_id);

        // Clean up stale assignments
        $assignments = $this->server->getStaleAssignments();
        foreach ($assignments as $assignment) {
            $this->server->unassign($assignment['flock_id'], $assignment['sheep_id'], $assignment['frame_id']);
        }
    }

    function vote()
    {
        echo 'vote';
    }
}