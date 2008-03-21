<?php

class admin_Controller extends TinyMVC_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('config_model', 'config');
        $this->load->model('flock_model', 'flock');
        $this->load->model('spex_model', 'spex');

        $this->view->assign('menu', $this->view->fetch('menu_view'));
    }

    /**
     * Show default admin page
     */
    function index()
    {
        $this->view->display('admin_view');
    }

    /**
     * Resets the server, creating a new, empty, flock of sheep
     */
    function reset()
    {
        // Initialize new flock
        $this->flock->newFlock();
        
        // Generate spex information for first sheep
        $spex = $this->spex->random_rotation($this->config->nframes);

        // Create new sheep with the spex information
        $this->flock->newSheep($spex, $this->config->nframes);

        // TODO: Display something meaningful
        $this->view->display('admin_view');
    }

    function newsheep()
    {
        // Generate spex information for new sheep
        $spex = $this->spex->random_rotation($this->config->nframes);

        // Create new sheep with the spex information
        $this->flock->newSheep($spex, $this->config->nframes);

        // TODO: Display something meaningful
        $this->view->display('admin_view');
    }

    function newedge()
    {
        // Try to find a random edge
        $sheep = $this->flock->findRandomEdge();
        
        if (is_array($sheep)) {
            // Generate spex information for th edge
            $spex = $this->spex->edge($this->config->generation, $sheep[0], $sheep[1], $this->config->nframes);
            echo '<pre>'; echo htmlspecialchars($spex); echo '</pre>';

            // Create new sheep with the spex information
            $this->flock->newSheep($spex, $this->config->nframes, $sheep[0], $sheep[1]);
        }

        // TODO: Display something meaningful
        $this->view->display('admin_view');
    }

    function delete()
    {
        $sheep = isset($_GET['sheep']) ? $_GET['sheep'] : null;
        $frame = isset($_GET['frame']) ? $_GET['frame'] : null;

        if ($sheep !== null && $frame !== null) {
            $this->flock->deleteFrame($sheep, $frame);
            header('Location: /status?sheep=' . $sheep . '&frame=' . $frame);
            exit;
        }

    }

    function upload()
    {
        if (is_uploaded_file($_FILES['genome']['tmp_name'])) {
            $spex = file_get_contents($_FILES['genome']['tmp_name']);
            $this->flock->newSheep($spex, $this->config->nframes);
        }
        $this->view->display('admin_view');
    }
}