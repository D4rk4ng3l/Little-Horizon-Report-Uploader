<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Darky
 * Date: 17.09.11
 * Time: 19:57
 * To change this template use File | Settings | File Templates.
 */
 
abstract class Uploader_Model_Db
{
    /**
     * @var PDO
     */
    protected $_db;

    /**
     * @var PDO
     */
    private static $_defaultDb;

    /**
     * @var array
     */
    protected $_tables;

    /**
     * @var array
     */
    private static $_tableConfig;

    public function __construct()
    {
        $this->_db = self::$_defaultDb;
        $this->_tables = self::$_tableConfig;
        $this->init();
    }

    public function init()
    {
    }

    /**
     * @param \PDO $defaultDb
     */
    public static function setDefaultDb(PDO $defaultDb)
    {
        self::$_defaultDb = $defaultDb;
    }

    /**
     * @return \PDO
     */
    public static function getDefaultDb()
    {
        return self::$_defaultDb;
    }

    /**
     * @param \PDO $db
     */
    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }

    /**
     * @return \PDO
     */
    public function getDb()
    {
        return $this->_db;
    }

    /**
     * @param array $tables
     */
    public function setTables($tables)
    {
        $this->_tables = $tables;
    }

    /**
     * @return array
     */
    public function getTables()
    {
        return $this->_tables;
    }

    /**
     * @param array $tableConfig
     */
    public static function setTableConfig($tableConfig)
    {
        self::$_tableConfig = $tableConfig;
    }

    /**
     * @return array
     */
    public static function getTableConfig()
    {
        return self::$_tableConfig;
    }

}
