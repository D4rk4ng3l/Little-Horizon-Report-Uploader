<?php
require_once 'Uploader/Config.php';
abstract class Uploader_Application_Bootstrap
{
    protected $_config = null;
    
    public function __construct(Uploader_Config $config)
    {
        $this->_config = $config;
    }
    
    public function bootstrap()
    {
        $reflection = new ReflectionClass($this);
        $methods = $reflection->getMethods(
            ReflectionMethod::IS_PRIVATE | ReflectionMethod::IS_PROTECTED
        );
        $resources = array();
        foreach ($methods as $method) {
            $methodName = $method->getName();
            if (substr($methodName, 0, 5) == '_init') {
                $resourceName = lcfirst(
                    str_replace('_init', '', $methodName)
                );
                $resource = $this->$methodName();
                if ($resource !== null) {
                    $resources[$resourceName] = $resource;
                }
            }
        }
        return $resources;
    }
}
