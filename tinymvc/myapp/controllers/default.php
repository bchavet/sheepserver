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
        } else {
            header('Location: /flock');
        }
        exit;
    }

}
