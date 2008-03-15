<?php
/**
 * Library to handle a URI sent to tinyMVC
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
     * @return requested segment of the URI if it exists.  False otherwise.
     */
    function segment($segment)
    {
        if (isset($this->uri[$segment])) {
            return $this->uri[$segment];
        }
        return false;

    }

    /**
     * Returns an associative array of the URI values, such that every segment
     * pair in the URI is a key/value pair.
     *
     * Example: http://www.example.com/a/b/c/d/e/f
     *          returns array('a' => 'b', 'c' => 'd', 'e' => 'f');
     *
     * Example: http://www.example.com/a/b/c/d/e
     *          returns array('a' => 'b', 'c' => 'd', 'e' => null);
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

    /**
     * Returns the URI parameter value according to the given key and the
     * key/value grouping done by $this->to_array().
     *
     * @param string $key  Parameter name.
     *
     * @return The value associated with the key if it exists.  False otherwise.
     */
    function get($key)
    {
        $param = $this->to_array();
        if (isset($params[$key])) {
            return $params[$key];
        }
        return false;
    }

}