<?php

class cgi_Controller extends TinyMVC_Controller
{
    function index()
    {
        $this->view->display('test_view');
    }

    /**
     * Return a list of available sheep to the client.
     */
    function listsheep()
    {
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
    }

}