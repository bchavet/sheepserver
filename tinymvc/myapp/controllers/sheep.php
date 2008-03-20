<?php

class Sheep_Controller extends TinyMVC_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('config_model', 'config');
        $this->load->model('sheep_model', 'sheep');
        $this->view->assign('menu', $this->view->fetch('menu_view'));

        $this->flock_id = $this->config->generation;
        $this->sheep_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    }

    function __call($name, $args)
    {
        $this->index();
    }

    function index()
    {
        $this->frames();
    }

    /*
    function view()
    {
    }
    */

    function frames()
    {
        $frames = $this->sheep->getFrames($this->flock_id, $this->sheep_id);
        $completed = $this->sheep->countCompletedFrames($this->flock_id, $this->sheep_id);
        $remaining = $this->config->nframes - $completed;

        $this->view->assign('frames', $frames);
        $this->view->assign('completed', $completed);
        $this->view->assign('remaining', $remaining);
        $this->view->display('sheep_frames_view');
    }

    /*
    function motion()
    {
    }
    */

    /*
    function family()
    {
    }
    */

    /*
    function lineage()
    {
    }
    */

    /*
    function genome()
    {
    }
    */

    /*
    function credit()
    {
    }
    */

    /*
    function stats()
    {
    }
    */

}