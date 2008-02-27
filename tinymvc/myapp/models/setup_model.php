<?php

class Setup_Model extends TinyMVC_Model
{

    /**
     * Attempt to connect to a database with the given parameters
     *
     * @return true on success, false on failure
     */
    function test_db($type, $host, $name, $user, $pass, $persistent)
    {
        try {
            $pdo = new PDO(
                "{$type}:host={$host};dbname={$name}",
                $user,
                $pass,
                array(PDO::ATTR_PERSISTENT => $persistent)
            );
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    /**
     * Generate the contents of database.php for the given values
     */
    function get_database_config($type, $host, $name, $user, $pass, $persistent)
    {
        $text =
            '<?php' . "\n" .
            '$config[\'plugin\'] = \'TMVC_PDO\'; // plugin for db access' . "\n" .
            '$config[\'type\'] = \'' . $type . '\'; // connection type' . "\n" .
            '$config[\'host\'] = \'' . $host . '\'; // db hostname' . "\n" .
            '$config[\'name\'] = \'' . $name . '\'; // db name' . "\n" .
            '$config[\'user\'] = \'' . $user . '\'; // db username' . "\n" .
            '$config[\'pass\'] = \'' . $pass . '\'; // db password' . "\n" .
            '$config[\'persistent\'] = ' . ($persistent ? 'true' : 'false') . '; // db connection persistence?' . "\n";
        
        return $text;
    }

    /**
     * Generate proper apache configuration
     */
    function get_apache_config()
    {
        $text = 
            'RewriteEngine On' . "\n" .
            'RewriteRule ^/cgi/list$ /index.php/cgi/listsheep [L]' . "\n" .
            'RewriteRule ^/cgi/list[\?/](.*)$ /index.php/cgi/listsheep/$1 [L]' . "\n" .
            'RewriteCond %{REQUEST_FILENAME} !-f' . "\n" .
            'RewriteCond %{REQUEST_FILENAME} !-d' . "\n" .
            'RewriteRule ^/(.*)$ /index.php/$1 [L]' . "\n";
        
        return $text;
    }

    /**
     * Test whether the /gen foler is writable
     */
    function test_gen_folder()
    {
        return is_writable(ES_BASEDIR . '/gen');
    }

    function get_config_table_schema()
    {
        $query = 'CREATE TABLE config (name VARCHAR(255) NOT NULL, value VARCHAR(255))';
        return $query;
    }

    function create_config_table($db_config)
    {
        try {
            $pdo = new PDO(
                "{$db_config['type']}:host={$db_config['host']};dbname={$db_config['name']}",
                $db_config['user'],
                $db_config['pass'],
                array(PDO::ATTR_PERSISTENT => $db_config['persistent'])
            );
        } catch (PDOException $e) {
            return false;
        }

        $result = $pdo->query($this->get_config_table_schema());
        if ($result === false) {
            return false;
        }
        return true;
    }

    function test_config_table($db_config = null)
    {
        if (!is_null($db_config)) {
            try {
                $pdo = new PDO(
                    "{$db_config['type']}:host={$db_config['host']};dbname={$db_config['name']}",
                    $db_config['user'],
                    $db_config['pass'],
                    array(PDO::ATTR_PERSISTENT => $db_config['persistent'])
                );
            } catch (PDOException $e) {
                return false;
            }
        } else {
            $pdo = $this->db->pdo;
        }

        //TODO: do a more exhaustive test

        $result = $pdo->query('select name from config limit 1');
        if ($result === false) {
            return false;
        }
        return true;
    }
}