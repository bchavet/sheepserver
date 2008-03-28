<?php

class Flock_Controller extends TinyMVC_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('config_model', 'config');
        $this->load->model('flock_model', 'flock');

        $this->flock_id = $this->config->generation;

        $this->view->assign('flock', $this->flock_id);
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
        $this->load->model('sheep_model', 'sheep');
        $queue = $this->flock->getQueue($this->flock_id);
        foreach ($queue as $key => $val) {
            $queue[$key]['complete'] = $this->sheep->countCompletedFrames($this->flock_id, $val['sheep_id']);
        }
        
        $this->view->assign('queue', $queue);
        $this->view->assign('assigned', $this->flock->getAssigned($this->flock_id));
        $this->view->assign('postqueue', $this->flock->getPostQueue($this->flock_id));
        $this->view->assign('nframes', $this->config->nframes);
        $this->view->display('flock_queue_view');
    }

    function credit()
    {
        $this->view->assign('credit', $this->flock->getCredit($this->flock_id));
        $this->view->display('flock_credit_view');
    }

    function stats()
    {
        $this->load->model('stats_model', 'stats');

        $this->view->assign(array('frames_assigned_60' => $this->stats->framesAssigned(60),
                                  'frames_returned_60' => $this->stats->framesReturned(60),
                                  'sheep_completed_60' => $this->stats->sheepCompleted(60),
                                  'frames_assigned_1440' => $this->stats->framesAssigned(1440),
                                  'frames_returned_1440' => $this->stats->framesReturned(1440),
                                  'sheep_completed_1440' => $this->stats->sheepCompleted(1440)));

        $this->view->display('flock_stats_view');
    }
 
}