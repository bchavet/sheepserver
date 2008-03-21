<?php

class Frame_Controller extends TinyMVC_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('config_model', 'config');
        $this->load->model('frame_model', 'frame');
        $this->view->assign('menu', $this->view->fetch('menu_view'));

        $this->flock_id = $this->config->generation;
        $this->sheep_id = isset($_GET['sheep']) ? (int)$_GET['sheep'] : null;
        $this->frame_id = isset($_GET['frame']) ? (int)$_GET['frame'] : null;

        if ($this->sheep_id === null || $this->frame_id === null) {
            header('Location: /flock');
            exit;
        }
    }

    function __call($name, $args)
    {
        $this->index();
    }

    function index()
    {
        $this->status();
    }

    function status()
    {
        $this->view->assign('frame', $this->frame->getFrame($this->flock_id, $this->sheep_id, $this->frame_id));
        $this->view->display('frame_status_view');
    }

}