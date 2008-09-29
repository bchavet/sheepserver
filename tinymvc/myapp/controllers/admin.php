<?php

class admin_Controller extends TinyMVC_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('config_model', 'config');
        $this->load->model('flock_model', 'flock');
        $this->load->model('spex_model', 'spex');

        $this->view->assign('flock', $this->config->flock_id);
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

        $this->view->assign('sheeplist', $sheeplist = $this->flock->getAllSheep($this->config->flock_id));
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

        $confirm = isset($_REQUEST['confirm']) ? true : false;
        $this->view->assign('confirm', $confirm);

        if ($confirm) {
            // Initialize new flock
            $this->flock->newFlock();
        
            // Generate spex information for first sheep
            $spex = $this->spex->random_rotation($this->config->nframes);
        
            // Create new sheep with the spex information
            $this->flock->newSheep($spex, $this->config->nframes);
        }

        $this->view->display('admin_reset_view');
    }

    function newsheep()
    {
        if (empty($_SESSION['logged_in'])) {
            $this->view->display('admin_login_view');
            return;
        }

        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : null;

        switch ($type) {

        case 'random':
            // Generate spex information for new sheep
            $spex = $this->spex->random_rotation($this->config->nframes);
            break;

        case 'upload':
            if (is_uploaded_file($_FILES['genome']['tmp_name'])) {
                // Load the spex from the uploaded file
                $spex = file_get_contents($_FILES['genome']['tmp_name']);

                // Make sure the size matches what is expected
                $spex = preg_replace('/size="(\d+) (\d+)"/', 'size="' . $this->config->width . ' ' . $this->config->height . '"', $spex);
            }
            break;

        case 'url':
            $url = parse_url($_REQUEST['url']);

            // We were given the URL to the spex file
            if (substr($url['path'], -5) == '/spex') {
                $spex_url = $_REQUEST['url'];
                $path = explode('/', $url['path']);
                $sheep_id = $path[count($path) - 2];
                $base_path = substr($url['path'], 0, strpos($url['path'], '/gen'));
                $credit_url = $url['scheme'] . '://' . $url['host'] . $base_path . '/cgi/node.cgi?id=' . $sheep_id;
            }

            // We were given the sheep view URL
            if (false  && (substr($url['path'], -9) == '/node.cgi' || substr($url['path'], -9) == '/dead.cgi')) {
                $gz = gzopen(substr($_REQUEST['url'], 0, strpos($_REQUEST['url'], '/cgi/') + 4) . '/list', 'r');
                $list = gzread($gz, 1000);
                gzclose($gz);
                
            }

            if (isset($spex_url) && isset($credit_url)) {
                $spex = preg_replace('/size="(\d+) (\d+)"/', 'size="' . $this->config->width . ' ' . $this->config->height . '"', file_get_contents($spex_url));
                $extras = array('creditlink' => $credit_url);
            } else {
                header('Location: /admin');
                exit;
            }
            break;

        case 'symmetry':
            $spex = $this->spex->symmetry_singularity($this->config->nframes);
            break;

        case 'mutate':
            $parent0 = isset($_REQUEST['parent0']) ? (int)$_REQUEST['parent0'] : null;
            if ($parent0 !== null) {
                $extras = array('parent0' => $parent0);
                $spex = $this->spex->mutate_rotation($this->config->flock_id, $parent0, $this->config->nframes);
            }
            break;

        case 'mate':
            $parent0 = isset($_REQUEST['parent0']) ? (int)$_REQUEST['parent0'] : null;
            $parent1 = isset($_REQUEST['parent1']) ? (int)$_REQUEST['parent1'] : null;
            if ($parent0 !== null && $parent1 !== null) {
                $extras = array('parent0' => $parent0, 'parent1' => $parent1);
                $spex = $this->spex->mate_rotation($this->config->flock_id, $parent0, $parent1, $this->config->nframes);
            }
            break;
            
        }

        // Create new sheep with the spex information
        if (isset($spex)) {
            $sheep_id = $this->flock->newSheep($this->config->flock_id, $spex, $this->config->nframes, null, null, isset($extras) ? $extras : null);
            $this->_connect($sheep_id);
            header('Location: /sheep?sheep=' . $sheep_id);
            exit;
        }

        $this->index();
    }

    function newedge()
    {
        if (empty($_SESSION['logged_in'])) {
            $this->view->display('admin_login_view');
            return;
        }

        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : null;

        switch ($type) {

        case 'random':
            // Try to find a random edge
            $first = isset($_REQUEST['first']) ? (int)$_REQUEST['first'] : null;
            $last = isset($_REQUEST['last']) ? (int)$_REQUEST['last'] : null;
            $sheep = $this->flock->findRandomEdge($this->config->flock_id, $first, $last);
        
            if (is_array($sheep)) {
                // Generate spex information for the edge
                $spex = $this->spex->edge($this->config->flock_id, $sheep[0], $sheep[1], $this->config->nframes);
            }
            break;

        case 'edge':
            $sheep[0] = isset($_REQUEST['first']) ? (int)$_REQUEST['first'] : null;
            $sheep[1] = isset($_REQUEST['last']) ? (int)$_REQUEST['last'] : null;

            // Get spex file 
            if ($sheep[0] !== null && $sheep[1] !== null) {
                $spex = $this->spex->edge($this->config->flock_id, $sheep[0], $sheep[1], $this->config->nframes);
            }
            break;

        case 'connect':
            $sheep_id = isset($_REQUEST['sheep']) ? (int)$_REQUEST['sheep'] : null;
            $numedges = isset($_REQUEST['numedges']) ? (int)$_REQUEST['numedges'] : 3;
            if ($sheep_id !== null) {
                $this->_connect($sheep_id, $numedges);
            }

            header('Location: /sheep?sheep=' . $sheep_id);
            exit;
            break;
        }

        // Create new sheep with the spex information
        if (isset($spex)) {
            $sheep_id = $this->flock->newSheep($this->config->flock_id, $spex, $this->config->nframes, $sheep[0], $sheep[1]);

            if (isset($_REQUEST['return']) && (int)$_REQUEST['return'] != 0) {
                header('Location: /sheep?sheep=' . (int)$_REQUEST['return']);
                exit;
            }

            header('Location: /sheep?sheep=' . $sheep_id);
            exit;
        }

        $this->index();
    }

    function _connect($sheep_id, $numedges = 3)
    {
        $this->load->model('sheep_model', 'sheep');
        $missing_edges_in = $this->sheep->getMissingEdges($this->config->flock_id, $sheep_id, 'in');
        $missing_edges_out = $this->sheep->getMissingEdges($this->config->flock_id, $sheep_id, 'out');
                
        for ($i = 0; $i < $numedges; $i++) {
            $in = rand(0, count($missing_edges_in) - 1);
            $spex = $this->spex->edge($this->config->flock_id, $missing_edges_in[$in]['first'], $missing_edges_in[$in]['last'], $this->config->nframes);
            $this->flock->newSheep($this->config->flock_id, $spex, $this->config->nframes, $missing_edges_in[$in]['first'], $missing_edges_in[$in]['last']);

            $out = rand(0, count($missing_edges_out) - 1);
            $spex = $this->spex->edge($this->config->flock_id, $missing_edges_out[$out]['first'], $missing_edges_out[$out]['last'], $this->config->nframes);
            $this->flock->newSheep($this->config->flock_id, $spex, $this->config->nframes, $missing_edges_out[$out]['first'], $missing_edges_out[$out]['last']);
        }
    }

    function delete()
    {
        if (empty($_SESSION['logged_in'])) {
            $this->view->display('admin_login_view');
            return;
        }

        $sheep_id = isset($_REQUEST['sheep']) ? $_REQUEST['sheep'] : null;
        $frame_id = isset($_REQUEST['frame']) ? $_REQUEST['frame'] : null;

        if ($sheep_id !== null && $frame_id !== null) {
            $this->load->model('frame_model', 'frame');
            $this->frame->deleteFrame($this->config->flock_id, $sheep_id, $frame_id);
            header('Location: /frame?sheep=' . $sheep_id . '&frame=' . $frame_id);
            exit;
        }
        
        if ($sheep_id !== null) {
            $this->load->model('sheep_model', 'sheep');
            $this->sheep->deleteSheep($this->config->flock_id, $sheep_id, false);
            header('Location: /flock');
            exit;
        }
    }

    function archive()
    {
        if (empty($_SESSION['logged_in'])) {
            $this->view->display('admin_login_view');
            return;
        }

        $sheep_id = isset($_REQUEST['sheep']) ? $_REQUEST['sheep'] : null;

        if ($sheep_id !== null) {
            $this->load->model('sheep_model', 'sheep');
            $this->sheep->archiveSheep($this->config->flock_id, $sheep_id);
            header('Location: /flock/archive');
            exit;
        }
    }

    function requeue()
    {
        if (empty($_SESSION['logged_in'])) {
            $this->view->display('admin_login_view');
            return;
        }

        $sheep_id = isset($_REQUEST['sheep']) ? $_REQUEST['sheep'] : null;

        if ($sheep_id !== null) {
            $this->load->model('sheep_model', 'sheep');
            $this->sheep->requeueSheep($this->config->flock_id, $sheep_id);
            $this->_connect($sheep_id);
            header('Location: /sheep?sheep=' . $sheep_id);
            exit;
        }
    }

    function prune()
    {
        if (empty($_SESSION['logged_in'])) {
            $this->view->display('admin_login_view');
            return;
        }

        $this->load->model('sheep_model', 'sheep');
        $numdays = isset($_REQUEST['numdays']) ? (int)$_REQUEST['numdays'] : 10;
        $this->view->assign('numdays', $numdays);
        $prunelist = $this->flock->getPruneList($this->config->flock_id, $numdays);
        $this->view->assign('prunelist', $prunelist);

        $confirm = isset($_REQUEST['confirm']) ? true : false;
        $this->view->assign('confirm', $confirm);

        if ($confirm) {
            foreach ($prunelist as $prune) {
                if ($this->sheep->sheepExists($this->config->flock_id, $prune['sheep_id'])) {
                    $this->sheep->deleteSheep($this->config->flock_id, $prune['sheep_id']);
                }
            }
        }

        $this->view->display('admin_prune_view');
    }

    /**
     * Handle admin login
     */
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

    /**
     * Handle admin logout
     */
    function logout()
    {
        unset($_SESSION['logged_in']);
        header('Location: /flock');
        exit;
    }

}