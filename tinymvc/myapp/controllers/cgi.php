<?php

class cgi_Controller extends TinyMVC_Controller
{
    function index()
    {
        $this->view->display('test_view');
    }

    /**
     * Return a list of available sheep to the client.
     */
    function listsheep()
    {
    }

    /**
     * Retrieve the next available job, if there is one, and assign it to the
     * client.
     */
    function get()
    {
    }

    /**
     * Accept a rendered frame from a client.
     */
    function put()
    {
    }

}