<?php

class Sheep_Controller extends TinyMVC_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('config_model', 'config');
        $this->load->model('sheep_model', 'sheep');

        $this->flock_id = $this->config->generation;
        $this->sheep_id = isset($_GET['sheep']) ? (int)$_GET['sheep'] : null;

        if ($this->sheep_id === null) {
            header('Location: /flock');
            exit;
        }

        $this->view->assign('flock', $this->flock_id);
        $this->view->assign('sheep', $this->sheep_id);
        $this->view->assign('menu', $this->view->fetch('menu_view'));
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
    function status()
    {
    }
    */

    function frames()
    {
        $frames = $this->sheep->getFrames($this->flock_id, $this->sheep_id);
        $completed = $this->sheep->countCompletedFrames($this->flock_id, $this->sheep_id);
        $remaining = $this->config->nframes - $completed;
        $first = $this->sheep->getFirst($this->flock_id, $this->sheep_id);
        $last = $this->sheep->getLast($this->flock_id, $this->sheep_id);

        $this->view->assign('flock', $this->flock_id);
        $this->view->assign('frames', $frames);
        $this->view->assign('completed', $completed);
        $this->view->assign('remaining', $remaining);
        $this->view->assign('first', $first);
        $this->view->assign('last', $last);
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

    function genome()
    {
        $genome = $this->sheep->getGenome($this->flock_id, $this->sheep_id);

        $this->view->assign('genome', $genome);
        $this->view->display('sheep_genome_view');
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