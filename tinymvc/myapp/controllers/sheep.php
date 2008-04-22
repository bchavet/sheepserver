<?php

class Sheep_Controller extends TinyMVC_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('config_model', 'config');
        $this->load->model('sheep_model', 'sheep');

        $this->flock_id = $this->config->flock_id;
        $this->sheep_id = isset($_GET['sheep']) ? (int)$_GET['sheep'] : null;

        if ($this->sheep_id === null) {
            header('Location: /flock');
            exit;
        }

        $author_credit = $this->sheep->getAuthorCredit($this->flock_id, $this->sheep_id);

        $this->view->assign('flock', $this->flock_id);
        $this->view->assign('sheep', $this->sheep->getSheep($this->flock_id, $this->sheep_id));
        $this->view->assign(array('author_credit' => $author_credit));
        $this->view->assign('menu', $this->view->fetch('menu_view'));
    }

    function __call($name, $args)
    {
        $this->index();
    }

    function index()
    {
        $this->view->assign('current', $this->sheep->getSheep($this->flock_id, $this->sheep_id));
        $this->view->assign('before', $this->sheep->getSheepBefore($this->flock_id, $this->sheep_id));
        $this->view->assign('after', $this->sheep->getSheepAfter($this->flock_id, $this->sheep_id));
        $this->view->display('sheep_view');
    }

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
    function lineage()
    {
    }
    */

    function family()
    {
        $parents = $this->sheep->getParents($this->flock_id, $this->sheep_id);
        $children = $this->sheep->getChildren($this->flock_id, $this->sheep_id);

        $this->view->assign('parents', $parents);
        $this->view->assign('children', $children);
        $this->view->display('sheep_family_view');
    }

    function genome()
    {
        $genome = $this->sheep->getGenome($this->flock_id, $this->sheep_id);
        $this->view->assign('genome', $genome);
        $this->view->display('sheep_genome_view');
    }
 
    function credit()
    {
        $credit = $this->sheep->getCredit($this->flock_id, $this->sheep_id);

        $this->view->assign('credit', $credit);
        $this->view->display('sheep_credit_view');
    }

}