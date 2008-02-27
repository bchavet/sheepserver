<?php

class setup_Controller extends TinyMVC_Controller
{

    /**
     * Starts the application setup process.
     */
    function index()
    {
        // Security check
        include TMVC_MYAPPDIR . 'configs' . DS . 'database.php';
        if (isset($config)) {
            //TODO: require login if database.php already exists
        }

        //TODO: remove hard-coded path to stylesheet
        $this->view->assign('action', '/setup/database');
        $this->view->assign(array('type' => 'mysql',
                                  'host' => '',
                                  'name' => '',
                                  'user' => '',
                                  'pass' => '',
                                  'persistent' => false));
        $this->view->display('setup_database_view');
    }

    /**
     * Handles setting up the database
     */
    function database()
    {
        // Security check
        include TMVC_MYAPPDIR . 'configs' . DS . 'database.php';
        if (isset($config)) {
            //TODO: require login if database.php already exists
        }

        if (!isset($_POST['button'])) {
            $this->index();
            return;
        }

        // load the setup model
        $this->load->model('Setup_Model', 'setup');

        // Test database parameters
        $db_ok = $this->setup->test_db($_POST['type'], $_POST['host'], $_POST['name'], $_POST['user'], $_POST['pass'], (isset($_POST['persistent']) ? $_POST['persistent'] : false));

        // Save DB connection information in session for use later
        $_SESSION['db_config'] = array('type' => $_POST['type'],
                                       'host' => $_POST['host'],
                                       'name' => $_POST['name'],
                                       'user' => $_POST['user'],
                                       'pass' => $_POST['pass'],
                                       'persistent' => (isset($_POST['persistent']) ? $_POST['persistent'] : false));

        if (!$db_ok || $_POST['button'] == 'Test') {

            $this->view->assign('action', '/setup/database');
            $this->view->assign('db_ok', $db_ok);
            $this->view->assign($_SESSION['db_config']);
            $this->view->display('setup_database_view');

        } else {

            // Attempt to create the database tables
            $config_table = $this->setup->create_config_table($_SESSION['db_config']);

            $this->test();
        }
    }

    /**
     * Final configuration test before launching the app
     */
    function test()
    {

        // load the setup model
        $this->load->model('Setup_Model', 'setup');

        // Check database configuration
        include TMVC_MYAPPDIR . 'configs' . DS . 'database.php';
        $db_configured = isset($config);
        if ($db_configured) {
            $db_ok = $this->setup->db->pdo instanceof PDO;
            $this->view->assign('config_table_ok', $this->setup->test_config_table());
        } else {
            $db_config = $this->setup->get_database_config($_SESSION['db_config']['type'],
                                                           $_SESSION['db_config']['host'],
                                                           $_SESSION['db_config']['name'],
                                                           $_SESSION['db_config']['user'],
                                                           $_SESSION['db_config']['pass'],
                                                           $_SESSION['db_config']['persistent']);
            $this->view->assign('db_config', $db_config);

            $db_ok = $this->setup->test_db($_SESSION['db_config']['type'],
                                           $_SESSION['db_config']['host'],
                                           $_SESSION['db_config']['name'],
                                           $_SESSION['db_config']['user'],
                                           $_SESSION['db_config']['pass'],
                                           $_SESSION['db_config']['persistent']);

            $this->view->assign('config_table_ok', $this->setup->test_config_table($_SESSION['db_config']));
        }

        $this->view->assign('db_configured', $db_configured);
        $this->view->assign('db_ok', $db_ok);
        $this->view->assign('db_config_file', TMVC_MYAPPDIR . 'configs' . DS . 'database.php');

        $this->view->assign('config_table_schema', $this->setup->get_config_table_schema());

        // Check for needed, apache-writable folder(s)
        $gen_folder_ok = $this->setup->test_gen_folder();
        $this->view->assign('gen_folder_ok', $gen_folder_ok);
        $this->view->assign('gen_folder_path', ES_BASEDIR . '/gen');

        $this->view->assign('continue', '/');
        $this->view->assign('retry', '/setup/test');

        // Display test results
        $this->view->display('setup_test_view');
    }

}