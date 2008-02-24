<?php

class Config_Model extends TinyMVC_Model
{
    function get($id)
    {
        return $this->db->query_one('select value from config where id=?', array($id));
    }

    function set($id, $value)
    {
    }
}