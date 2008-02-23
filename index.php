<?php
/* PHP error reporting level, if different from default */
error_reporting(E_ALL);

define('TMVC_BASEDIR', 'tinymvc/');
define('ES_BASEDIR', dirname(__FILE__));

/* directory separator alias */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

session_start();
require TMVC_BASEDIR . 'sysfiles' . DS . 'TinyMVC.php';
