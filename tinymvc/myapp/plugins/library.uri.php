<?php
/**
 * Library to handle a URI sent to tinyMVC
 *
 * Copyright 2008 Ben Chavet
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author Ben Chavet <ben@chavet.net>
 */

class URI
{

    var $uri = array();

    /**
     * Constructor.  Prepares URI for use in other methods.
     */
    function __construct()
    {
        $this->uri = !empty($_SERVER['PATH_INFO']) ? explode('/', $_SERVER['PATH_INFO']) : array();
    }

    /**
     * Returns the requested segment of the URI.  Triggers an error if the
     * segment does not exist.
     *
     * @param int $segment  The segment number to return
     *
     * @return requested segment of the URI
     */
    function segment($segment)
    {
        if (!isset($this->uri[$segment])) {
            return false;
        }

        return $this->uri[$segment];
    }

    /**
     * Returns an associative array of the URI values, such that every segment
     * pair in the URI is a key/value pair.
     *
     * Example: http://www.example.com/a/b/c/d/e/f
     *          returns array('a' => 'b', 'c' => 'd', 'e' => 'f');
     *
     * @return associative array of the URI values
     */
    function to_array()
    {
        static $hash;

        if (!is_array($hash)) {
            for ($i = 1; $i <= count($this->uri); $i+=2) {
                if (isset($this->uri[$i])) {
                    $hash[$this->uri[$i]] = isset($this->uri[$i + 1]) ? $this->uri[$i + 1] : null;
                }
            }
        }

        return $hash;
    }
}