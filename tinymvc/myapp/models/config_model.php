<?php

class Config_Model extends TinyMVC_Model
{

    /**
     * Get configuration value
     */
    function get($name)
    {
        static $config;
        if (!is_array($config)) {
            $config = array();
        }

        if (!isset($config[$name])) {
            $result = $this->db->query_init('select value from config where name=?', array($name), PDO::FETCH_ASSOC);
            if ($result === false) {
                return false;
            }
            $config[$name] = $result['value'];
        }

        return $config[$name];
    }

    function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Set configuration value
     */
    function set($name, $value)
    {
        $exists = $this->get($name);
        if ($exists === false) {
            // The value does not exist in the configuration table, insert it
            $result = $this->db->query('insert into config values (?, ?)', array($name, $value));
        } else {
            // The value already exists in the configuration table, update it
            $result = $this->db->query('update config set value=? where name=?', array($value, $name));
        }
        return $result;
    }

    function __set($name, $value)
    {
        return $this->set($name, $value);
    }

}