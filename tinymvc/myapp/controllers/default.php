<?php
/**
 * default.php
 *
 * default application controller
 */

class Default_Controller extends TinyMVC_Controller
{
    function index()
    {
        include TMVC_MYAPPDIR . 'configs' . DS . 'database.php';
        if (!isset($config)) {
            // Redirect to setup screen
            header('Location: /setup');
            exit;
        }

        $this->load->model('Config_Model', 'config');
        $this->load->model('Flock_Model', 'flock');

        // Get config values
        $generation = $this->config->get('generation');

        // Get the sheep in the current generation
        $sheep = $this->flock->get_sheep($generation);

        $this->view->assign('sheep', $sheep);
        $this->view->display('flock_view');
    }

}
