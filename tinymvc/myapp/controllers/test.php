<?php

class test_Controller extends TinyMVC_Controller
{

    function index()
    {
        $this->view->display('test_view');
    }
}