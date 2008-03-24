<?php

class admin_Controller extends TinyMVC_Controller
{

    function __construct()
    {
        parent::__construct();

        // TODO: Check authentication here

        $this->load->model('config_model', 'config');
        $this->load->model('flock_model', 'flock');
        $this->load->model('spex_model', 'spex');

        $this->view->assign('admin', true);
        $this->view->assign('menu', $this->view->fetch('menu_view'));
    }

    /**
     * Show default admin page
     */
    function index()
    {
        if (empty($_SESSION['logged_in'])) {
            $this->view->display('admin_login_view');
            return;
        }

        $this->view->display('admin_view');
    }

    /**
     * Resets the server, creating a new, empty, flock of sheep
     */
    function reset()
    {
        if (empty($_SESSION['logged_in'])) {
            $this->view->display('admin_login_view');
            return;
        }

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
        if (empty($_SESSION['logged_in'])) {
            $this->view->display('admin_login_view');
            return;
        }

        // Generate spex information for new sheep
        $spex = $this->spex->random_rotation($this->config->nframes);

        // Create new sheep with the spex information
        $this->flock->newSheep($spex, $this->config->nframes);

        // TODO: Display something meaningful
        $this->view->display('admin_view');
    }

    function newedge()
    {
        if (empty($_SESSION['logged_in'])) {
            $this->view->display('admin_login_view');
            return;
        }

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
        if (empty($_SESSION['logged_in'])) {
            $this->view->display('admin_login_view');
            return;
        }

        $sheep_id = isset($_GET['sheep']) ? $_GET['sheep'] : null;
        $frame_id = isset($_GET['frame']) ? $_GET['frame'] : null;

        if ($sheep_id !== null && $frame_id !== null) {
            $this->flock->deleteFrame($sheep_id, $frame_id);
            header('Location: /frame?sheep=' . $sheep_id . '&frame=' . $frame_id);
            exit;
        }

        if ($sheep_id !== null) {
            $this->load->model('sheep_model', 'sheep');
            $this->sheep->deleteSheep($this->config->generation, $sheep_id);
            header('Location: /flock');
            exit;
        }

    }

    function upload()
    {
        if (empty($_SESSION['logged_in'])) {
            $this->view->display('admin_login_view');
            return;
        }

        if (is_uploaded_file($_FILES['genome']['tmp_name'])) {
            // Load the spex from the uploaded file
            $spex = file_get_contents($_FILES['genome']['tmp_name']);

            // Make sure the size matches what is expected
            $spex = preg_replace('/size="(\d+) (\d+)"/', 'size="' . $this->config->width . ' ' . $this->config->height . '"', $spex);

            // Create a new sheep with the spex information
            $this->flock->newSheep($spex, $this->config->nframes);
        }

        // TODO: Display something meaningful
        $this->view->display('admin_view');
    }

    function prune()
    {
        if (empty($_SESSION['logged_in'])) {
            $this->view->display('admin_login_view');
            return;
        }

        $this->flock->prune($this->config->generation, 10);
    }

    function login()
    {
        $username = $_POST['username'];
        $password = md5($_POST['password']);

        if ($username == $this->config->admin_username &&
            $password == $this->config->admin_password) {
            $_SESSION['logged_in'] = true;
        }

        header('Location: /admin');
        exit;
    }

    function logout()
    {
        unset($_SESSION['logged_in']);
        header('Location: /flock');
        exit;
    }
}