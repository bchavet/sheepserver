<?php

class Flock_Controller extends TinyMVC_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('config_model', 'config');
        $this->load->model('flock_model', 'flock');
        $this->view->assign('menu', $this->view->fetch('menu_view'));
    }

    function __call($name, $args)
    {
        $this->index();
    }

    function index()
    {
        $this->loops();
    }

    function loops()
    {
        $this->view->assign('sheep', $this->flock->getCompleteSheep());
        $this->view->display('flock_view');
    }

    function edges()
    {
        $this->view->assign('edges', $this->flock->getCompleteEdges());
        $this->view->display('edge_view');
    }

    function queue()
    {
        $this->view->assign('queue', $this->flock->getQueue());
        $this->view->assign('postqueue', $this->flock->getPostQueue());
        $this->view->display('queue_view');
    }

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