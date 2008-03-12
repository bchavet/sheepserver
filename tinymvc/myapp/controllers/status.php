<?php

class Status_Controller extends TinyMVC_Controller
{
    function index()
    {
        $this->flock();
    }

    function flock()
    {
        $this->load->model('Config_Model', 'config');
        $this->load->model('Flock_Model', 'flock');

        // Get config values
        $generation = $this->config->get('generation');

        // Get the sheep in the current generation
        $sheep = $this->flock->get_sheep($generation);

        $this->view->assign('sheep', $sheep);
        $this->view->display('flock_view');
    }

    function sheep()
    {
        $this->load->model('Config_Model', 'config');
        $generation = $this->config->get('generation');

        // Try to get the sheep ID
        $sheep_id = $this->uri->segment(3);
        if ($sheep_id === false) {
            $this->flock();
            return;
        }

        $this->load->model('Sheep_Model', 'sheep');
        $frames = $this->sheep->get_frames($generation, $sheep_id);
        
        $this->view->assign('frames', $frames);
        $this->view->display('sheep_view');
    }

    function frame()
    {
        $this->load->model('Config_Model', 'config');
        $generation = $this->config->get('generation');

        // Try to get the sheep ID
        $sheep_id = $this->uri->segment(3);
        if ($sheep_id === false) {
            $this->flock();
            return;
        }

        // Try to get the frame ID
        $frame_id = $this->uri->segment(4);
        if ($frame_id === false) {
            $this->sheep();
            return;
        }

        $this->view->assign(array('generation' => $generation,
                                  'sheep' => $sheep_id,
                                  'frame' => $frame_id));
        $this->view->display('frame_view');
    }
}