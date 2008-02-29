<?php

class admin_Controller extends TinyMVC_Controller
{

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
        // Load the models
        $this->load->model('Config_Model', 'config');
        $this->load->model('Flock_Model', 'flock');
        $this->load->model('Sheep_Model', 'sheep');
        
        // Get configuration values
        $generation = $this->config->get('generation');
        $nframes = $this->config->get('total_frames');

        // Determine the next generation number
        if ($generation === false) {
            $generation = 1;
        } else {
            $generation++;
        }

        // Update generation number
        $this->config->set('generation', $generation);

        // Initialize new flock
        $this->flock->init($generation);
        
        // Initialize first sheep in the flock;
        $this->sheep->init($generation, 0);

        // Generate spex file for first sheep
        $this->sheep->random_rotation($generation, 0, $nframes, 0, 0);
    }

}