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

            // Redirect to setup screen
            header('Location: ' . $_SERVER['PHP_SELF'] . '/setup');
            exit;

        }
    }

}
