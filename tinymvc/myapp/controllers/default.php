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
        if (file_exists(TMVC_MYAPPDIR . 'configs' . DS . 'database.php')) {

            //TODO: Check that the apache redirects are in place somehow

            header('Location: ' . $_SERVER['PHP_SELF'] . '/cgi');
            exit;

        } else {
            $this->view->assign('css', dirname($_SERVER['PHP_SELF']) . '/screen.css');
            $this->view->display('setup_view');
        }
    }
}
