<?php

class Flock_Controller extends TinyMVC_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('config_model', 'config');
        $this->load->model('flock_model', 'flock');
        $this->view->assign('menu', $this->view->fetch('menu_view'));

        $this->flock_id = $this->config->generation;
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
        $this->view->assign('sheep', $this->flock->getCompleteSheep($this->flock_id));
        $this->view->display('flock_loops_view');
    }

    function edges()
    {
        $this->view->assign('edges', $this->flock->getCompleteEdges($this->flock_id));
        $this->view->display('flock_edges_view');
    }

    function queue()
    {
        $this->view->assign('assigned', $this->flock->getAssigned($this->flock_id));
        $this->view->assign('queue', $this->flock->getQueue($this->flock_id));
        $this->view->assign('postqueue', $this->flock->getPostQueue($this->flock_id));
        $this->view->display('flock_queue_view');
    }

    function credit()
    {
        $this->view->assign('credit', $this->flock->getCredit($this->flock_id));
        $this->view->display('flock_credit_view');
    }

    /*
    function stats()
    {
    }
    */

}