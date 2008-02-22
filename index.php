<?php

/***
 * Name:       TinyMVC
 * About:      An MVC application framework for PHP
 * Copyright:  (C) 2007, New Digital Group Inc.
 * Author:     Monte Ohrt, monte [at] ohrt [dot] com
 * License:    LGPL, see included license file  
 ***/

/* PHP error reporting level, if different from default */
error_reporting(E_ALL);

/* if the /tinymvc/ dir is not up one directory, uncomment and set here */
define('TMVC_BASEDIR', 'tinymvc/');

/* directory separator alias */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

/* tally-ho! */
require TMVC_BASEDIR . 'sysfiles' . DS . 'TinyMVC.php';
