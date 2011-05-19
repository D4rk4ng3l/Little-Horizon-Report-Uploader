<?php

class Uploader_Autoloader
{
    private static $_instance = null;
    
    private $_namespaces = array();
    
    private function __construct()
    {
        spl_autoload_register(array($this, 'loadClass'), true);
        $this->registerNamespace('Uploader');
    }
    
    private function __clone()
    {
    }
    
    /**
     * @return Uploader_Autoloader
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        
        return self::$_instance;
    }
    
    public function registerNamespace($namespace)
    {
        if (!in_array($namespace, $this->_namespaces)) {
            $this->_namespaces[] = $namespace;
        }
    }
    
    public function loadClass($className)
    {
        $found = false;
        foreach ($this->_namespaces as $namespace) {
            if (substr($className, 0, strlen($namespace)) == $namespace) {
                if (!class_exists($className)) {
                    try {
                        $phpFile = str_replace('_', '/', $className) . '.php';
                        require_once $phpFile;
                    } catch (Exception $e) {
                        require_once 'Uploader/Autoloader/Exception.php';
                        throw new Uploader_Autoloader_Exception(
                            "Can't load PHP-File."
                        );
                    }
                    if (!class_exists($className)) {
                        require_once 'Uploader/Autoloader/Exception.php';
                        throw new Uploader_Autoloader_Exception(
                            "Can't load PHP-File which contains the class '" .
                            $className . "'."
                        );
                    }
                }
            }
        }
    }
}
