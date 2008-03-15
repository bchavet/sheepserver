<?php

class Status_Controller extends TinyMVC_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('config_model', 'config');
        $this->load->model('flock_model', 'flock');
    }

    function index()
    {
        $sheep = isset($_GET['sheep']) ? (int)$_GET['sheep'] : false;
        $frame = isset($_GET['frame']) ? (int)$_GET['frame'] : false;

        if ($sheep !== false && $frame !== false) {
            $this->_frame($sheep, $frame);
        } else if ($sheep !== false) {
            $this->_sheep($sheep);
        } else {
            $this->_flock();
        }
    }

    function _flock()
    {
        // Get the sheep in the current generation
        $sheep = $this->flock->getSheep();
        
        $this->view->assign('sheep', $sheep);
        $this->view->display('flock_view');
    }

    function _sheep($sheep)
    {
        $frames = $this->flock->getFrames($sheep);
        $completed = $this->flock->countCompletedFrames($sheep);
        $remaining = $this->config->nframes - $completed;

        $this->view->assign('completed', $completed);
        $this->view->assign('remaining', $remaining);
        $this->view->assign('frames', $frames);
        $this->view->display('sheep_view');
    }

    function _frame($sheep, $frame)
    {
        $frame = $this->flock->getFrame($sheep, $frame);
        $this->view->assign('frame', $frame);
        $this->view->display('frame_view');
    }
}