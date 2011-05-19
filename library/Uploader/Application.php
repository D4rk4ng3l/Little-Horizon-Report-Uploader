<?php
require_once 'Uploader/Config.php';
require_once 'Uploader/Controller/Http/Request.php';
require_once 'Uploader/Controller/Http/Response.php';
class Uploader_Application
{
    /**
     * 
     * @var Uploader_Application_Bootstrap
     */
    private $_bootstrap = null;
    
    private $_resources = array();
    
    private $_config = array();
    
    public function __construct(Uploader_Config $config)
    {
        if (isset($config->bootstrap)) {
            require_once $config->bootstrap->file;
            $bootstrapClass = $config->bootstrap->class;
            $this->_bootstrap = new $bootstrapClass($config);
        }
        $this->_config = $config->toArray();
    }
    
    public function run()
    {
        if ($this->_bootstrap !== null) {
            $this->_resources = $this->_bootstrap->bootstrap();
        }
        
        $frontController = Uploader_Controller_Front::getInstance();
        $frontController->setResources($this->_resources);
        if (isset($this->_config['frontController'])) {
            $frontController->setParams($this->_config['frontController']);
        }

        $request = new Uploader_Controller_Http_Request();
        $response = new Uploader_Controller_Http_Response();
        $this->_resources['frontController'] = $frontController;
        $frontController->dispatch($request, $response);
    }
    

}
