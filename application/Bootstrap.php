<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initConfig()
    {
        new Zend_Session_Namespace();
        unset($_SESSION['Config']);
        $config = new Uploader_Config(
            'Default',
            array('directories' => APPLICATION_PATH . '/configs')
        );
        $config->load('uploader.ini');
        Uploader_Registry::setConfig($config);
    }

    protected function _initDb()
    {
        $dbConfig = Uploader_Registry::getConfig()->getParam('database');
        $db = new PDO(
            sprintf("mysql:host=%s;dbname=%s", $dbConfig['host'], $dbConfig['dbName']),
            $dbConfig['user'],
            $dbConfig['pass']
        );
        Uploader_Model_Db::setDefaultDb($db);
        Uploader_Model_Db::setTableConfig($dbConfig['tables']);
    }
}

