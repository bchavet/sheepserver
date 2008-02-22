<?php

class setup_Controller extends TinyMVC_Controller
{

    function index()
    {
        //TODO: remove hard-coded path to stylesheet

        $this->view->assign('css', '/mysheep/screen.css');
        $this->view->assign('setup', $_SERVER['PHP_SELF'] . '/setup');
        $this->view->assign(array('type' => 'mysql',
                                  'host' => '',
                                  'name' => '',
                                  'user' => '',
                                  'pass' => '',
                                  'persistent' => false));
        $this->view->display('setup_view');
    }

    function setup()
    {
        if (file_exists(TMVC_MYAPPDIR . 'configs' . DS . 'database.php')) {

            //TODO: Check that the apache redirects are in place somehow

            header('Location: ' . $_SERVER['PHP_SELF'] . '/cgi');
            exit;
        } else {

            // Generate database config file

            // Go to config confirmation screen
        }

    }

}