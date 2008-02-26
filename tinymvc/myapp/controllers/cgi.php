<?php

class cgi_Controller extends TinyMVC_Controller
{
    function index()
    {
        $this->view->display('test_view');
    }

    function reset()
    {
        // Load the models
        $this->load->model('Config_Model', 'config');
        $this->load->model('Flock_Model', 'flock');
        $this->load->model('Sheep_Model', 'sheep');
        
        // Get the current generation number
        $generation = $this->config->get('generation');

        // Determine the next generation number
        if ($generation === false) {
            $generation = 1;
        } else {
            $generation++;
        }

        // Retrieve flock from model
        $sheep = $this->flock->create($generation);

        $result = $this->config->set('generation', $generation);
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
    }

    /**
     * Accept a rendered frame from a client.
     */
    function put()
    {
    }

}