<?php

class Statistics_Controller extends TinyMVC_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('config_model', 'config');
        $this->load->model('statistics_model', 'stats');
    }

    function index()
    {
        $count = $this->stats->getRendererCount($this->config->generation);
        $assigned = $this->stats->getAssignedFrames($this->config->generation);

        $this->view->assign('menu', $this->view->fetch('menu_view'));
        $this->view->assign('count', $count);
        $this->view->assign('assigned', $assigned);
        $this->view->display('statistics_view');
    }
}