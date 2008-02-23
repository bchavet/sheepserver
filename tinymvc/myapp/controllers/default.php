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
        if (isset($config)) {

            header('Location: ' . HTTP_BASEDIR . '/cgi');
            exit;

        } else {

            // Redirect to setup screen
            header('Location: ' . HTTP_BASEDIR . '/setup');
            exit;

        }
    }

}
