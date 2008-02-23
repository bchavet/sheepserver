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
            'RewriteRule ^' . HTTP_BASEDIR . '/cgi/list$ ' . HTTP_BASEDIR . '/index.php/cgi/listsheep [L]' . "\n" .
            'RewriteRule ^' . HTTP_BASEDIR . '/cgi/list[\?/](.*)$ ' . HTTP_BASEDIR . '/index.php/cgi/listsheep/$1 [L]' . "\n" .
            'RewriteCond %{REQUEST_FILENAME} !-f' . "\n" .
            'RewriteCond %{REQUEST_FILENAME} !-d' . "\n" .
            'RewriteRule ^' . HTTP_BASEDIR . '/(.*)$ ' . HTTP_BASEDIR . '/index.php/$1 [L]' . "\n";
        
        return $text;
    }

    /**
     * Returns the flock table schema in the form of a CREATE query
     */
    function get_flock_table_schema()
    {
        $query = 'CREATE TABLE flock (id INT NOT NULL)';
        return $query;
    }

    /**
     * Attempt to create flock table
     *
     * @return true on success, false on failure
     */
    function create_flock_table($db_config)
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

        $result = $pdo->query($this->get_flock_table_schema());
        if ($result === false) {
            return false;
        }
        return true;
    }

    /**
     * Test the flock table
     *
     * @return true if table is OK, false otherwise
     */
    function test_flock_table($db_config = null)
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

        $result = $pdo->query('select id from flock limit 1');
        if ($result === false) {
            return false;
        }
        return true;
    }

    /**
     * Returns the sheep table schema in the form of a CREATE query
     */
    function get_sheep_table_schema()
    {
        $query = 'CREATE TABLE sheep (id INT NOT NULL, flock_id INT)';
        return $query;
    }

    /**
     * Attempt to create sheep table
     *
     * @return true on success, false on failure
     */
    function create_sheep_table($db_config)
    {
        try {
            $pdo = new PDO(
                "{$db_config['type']}:host={$db_config['host']};dbname={$db_config['name']}",
                $db_config['user'],
                $db_config['pass'],
                array(PDO::ATTR_PERSISTENT => $db_config['persistent'])
            );
        } catch (PDOException $e) {
            print_r($result);
        }

        $result = $pdo->query($this->get_sheep_table_schema());
        if ($result === false) {
            return false;
        }
        return true;
    }

    /**
     * Test the sheep table
     *
     * @return true if the table is OK, false otherwise
     */
    function test_sheep_table($db_config = null)
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

        $result = $pdo->query('select id from sheep limit 1');
        if ($result === false) {
            return false;
        }
        return true;
    }

    /**
     * Returns the frame table schema in the form of a CREATE query
     */
    function get_frame_table_schema()
    {
        $query = 'CREATE TABLE frame (id INT NOT NULL, sheep_id INT)';
        return $query;
    }

    /**
     * Attempt to create frame table
     *
     * @return true on success, false on failure
     */
    function create_frame_table($db_config)
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

        $result = $pdo->query($this->get_frame_table_schema());
        if ($result === false) {
            return false;
        }
        return true;
    }

    /**
     * Test the frame table
     *
     * @return true if table is OK, false otherwise
     */
    function test_frame_table($db_config = null)
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

        $result = $pdo->query('select id from frame limit 1');
        if ($result === false) {
            return false;
        }
        return true;
    }

    /**
     * Test whether the /gen foler is writable
     */
    function test_gen_folder()
    {
        return is_writable(ES_BASEDIR . '/gen');
    }

    /**
     * Check that apache is configured properly
     */
    function test_apache()
    {
        if (ini_get('allow_url_fopen')) {
            $headers = get_headers('http://' . $_SERVER['SERVER_NAME'] . HTTP_BASEDIR . '/setup/test');
            if (strpos($headers[0], '404') !== false) {
                return false;
            }
        }
        return true;
    }

}