<?php

class cgi_Controller extends TinyMVC_Controller
{
    var $method_map = array('list' => 'listsheep');

    function index()
    {
        $this->view->display('test_view');
    }

    /**
     * Return a list of available sheep to the client.
     */
    function listsheep()
    {
        $this->load->model('config_model', 'config');
        $generation = $this->config->get('generation');

        // Output contents of list file
        $file = ES_BASEDIR . DS . 'gen' . DS . $generation . DS . 'txt' . DS . 'list.txt.gz';
        $contents = file_get_contents($file);
        echo $contents;
    }

    /**
     * Retrieve the next available job, if there is one, and assign it to the
     * client.
     */
    function get()
    {
        $this->load->model('config_model', 'config');
        $this->load->model('server_model', 'server');

        $generation = $this->config->get('generation');

        // Attempt to get a job from the server
        $job = $this->server->get_job($generation);
        if ($job === false) {
            return false;
        }

        // Return the job to the client to start processing
        $file = ES_BASEDIR . DS . 'gen' . DS . $job['generation'] . DS . $job['sheep'] . DS . $job['frame'] . '.spex';
        $contents = file_get_contents($file);
        echo $contents;

        // Set the job status to 'assigned'
        $this->server->assign_job($job, $_GET['u']);
    }

    /**
     * Accept a rendered frame from a client.
     */
    function put()
    {
        // Get raw POST data
        $contents = file_get_contents('php://input');
    }

}