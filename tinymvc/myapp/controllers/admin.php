<?php

class admin_Controller extends TinyMVC_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('config_model', 'config');
        $this->load->model('flock_model', 'flock');
        $this->load->model('spex_model', 'spex');
    }

    /**
     * Show default admin page
     */
    function index()
    {
        $this->view->display('admin_view');
    }

    /**
     * Resets the server, creating a new, empty, flock of sheep
     */
    function reset()
    {
        // Initialize new flock
        $this->flock->newFlock();
        
        // Generate spex information for first sheep
        $spex = $this->spex->random_rotation($this->config->nframes);

        // Create new sheep with the spex information
        $this->flock->newSheep($spex, $this->config->nframes);

        // TODO: Display something meaningful
        $this->view->display('admin_view');
    }

    function newsheep()
    {
        // Generate spex information for new sheep
        $spex = $this->spex->random_rotation($this->config->nframes);

        // Create new sheep with the spex information
        $this->flock->newSheep($spex, $this->config->nframes);

        // TODO: Display something meaningful
        $this->view->display('admin_view');
    }

    function newedge()
    {
    }

}