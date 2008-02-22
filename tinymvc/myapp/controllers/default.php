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

            if (!strncmp(PHP_SAPI, 'cgi', 3)) {
                // When using CGI PHP, SCRIPT_NAME may contain the path to
                // the PHP binary instead of the script being run; use
                // PHP_SELF instead.
                $url = $_SERVER['PHP_SELF'];
            } else {
                $url = isset($_SERVER['SCRIPT_NAME']) ?
                    $_SERVER['SCRIPT_NAME'] :
                    $_SERVER['PHP_SELF'];
            }

            //TODO: Check that the apache redirects are in place somehow

            header('Location: ' . $url . '/cgi');
            exit;

        } else {
            $this->view->display('index_view');
        }
    }
}
