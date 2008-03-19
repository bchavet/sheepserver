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

    function nick()
    {
        if (!isset($_GET['nick'])) {
            header('Location: /statistics');
            exit;
        }

        $nick = addslashes($_GET['nick']);

        $frames = $this->stats->getFramesByNick($this->config->generation, $nick);
        $this->view->assign('frames', $frames);
        $frame_block = $this->view->fetch('stats_frames_view');

        $this->view->assign('menu', $this->view->fetch('menu_view'));
        $this->view->assign('blocks', array($frame_block));
        $this->view->display('block_view');
    }
}