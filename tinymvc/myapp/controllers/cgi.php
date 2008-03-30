<?php

class cgi_Controller extends TinyMVC_Controller
{
    var $method_map = array('list' => 'listsheep');

    function __construct()
    {
        parent::__construct();
        $this->load->model('config_model', 'config');
    }

    function index()
    {
        $this->view->display('test_view');
    }

    /**
     * Return a list of available sheep to the client.
     */
    function listsheep()
    {
        // Dynamically generate list
        $this->load->model('server_model', 'server');
        $list = $this->server->getList($this->config->flock_id);
        echo gzencode($list, 9);

        /*
        // Output contents of list file
        $file = ES_BASEDIR . DS . 'gen' . DS . $this->config->flock_id . DS . 'txt' . DS . 'list.txt.gz';
        $contents = file_get_contents($file);
        echo $contents;
        */
    }

    /**
     * Retrieve the next available job, if there is one, and assign it to the
     * client.
     */
    function get()
    {
        $this->load->model('server_model', 'server');
        $this->load->model('frame_model', 'frame');

        // Attempt to get a job from the server
        $job = $this->server->get_job($this->config->flock_id);
        if ($job === false) {
            return false;
        }

        // Return the job to the client to start processing
        echo $this->frame->getGenomeGZ($job['flock_id'], $job['sheep_id'], $job['frame_id']);

        // Assign the job to the requesting client
        $this->server->assign($job['flock_id'], $job['sheep_id'], $job['frame_id'], $_SERVER['REMOTE_ADDR'], addslashes($_GET['u']), addslashes($_GET['n']));
    }

    /**
     * Accept a rendered frame from a client.
     */
    function put()
    {
        $this->load->model('server_model', 'server');

        // Get raw POST data
        $data = file_get_contents('php://input');
 
        // Submit the job
        $result = $this->server->complete($this->config->flock_id, (int)$_GET['id'], (int)$_GET['f'], $_SERVER['REMOTE_ADDR'], addslashes($_GET['u']), $data);
    }

    /**
     * Encode any sheep that have all of their frames rendered
     */
    function encode()
    {
        $this->load->model('server_model', 'server');
        $sheep = $this->server->encodeSheep($this->config->flock_id);
    }

    /**
     * Clean up stale assignments
     */
    function cleanup()
    {
        $this->load->model('server_model', 'server');
        $assignments = $this->server->getStaleAssignments();
        foreach ($assignments as $assignment) {
            $this->server->unassign($assignment['flock_id'], $assignment['sheep_id'], $assignment['frame_id']);
        }

        header('Location: /status');
        exit;
    }
}