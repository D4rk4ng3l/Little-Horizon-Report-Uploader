<?php
require_once 'Uploader/Controller/Http/Request.php';
require_once 'Uploader/Controller/Http/Response.php';
require_once 'Uploader/Controller/Router.php';
require_once 'Uploader/Autoloader.php';
require_once 'Uploader/Controller/Action.php';
class Uploader_Controller_Front
{
    /**
     * 
     * @var Uploader_Controller_Front
     */
    private static $_instance = null;
    
    private $_resources = array();
    
    private $_params = array();
    
    /**
     * 
     * @var Uploader_Controller_Router
     */
    private $_router = null;
    
    /**
     * 
     * @var Uploader_Controller_Http_Request
     */
    private $_request = null;
    
    /**
     * 
     * @var Uploader_Controller_Http_Response
     */
    private $_response = null;
    
    private $_baseUrl = '';
    
    private $_controllerDir = '';
    
    private function __construct()
    {
        $baseUrl = dirname($_SERVER['SCRIPT_NAME']);
        if ($baseUrl == '\\' || $baseUrl == '/') {
            $baseUrl = '';
        }
        $this->_baseUrl = $baseUrl;
    }
    
    private function __clone()
    {
    }
    
    /**
     * @return Uploader_Controller_Front
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        
        return self::$_instance;
    }
    
    public function setResources(array $resources)
    {
        $this->_resources = $resources;
    }
    
    public function getResource($name, $default = null)
    {
        if (isset($this->_resources[$name])) {
            return $this->_resources[$name];
        }
        
        return $default;
    }
    
    public function setParams(array $params)
    {
        if (isset($params['path'])) {
            $this->_controllerDir = $params['path'];
            unset($params['path']);
        }
        if (isset($params['baseUrl'])) {
            $this->_baseUrl = $params['baseUrl'];
            unset($params['baseUrl']);
        }
        $this->_params = $params;
    }
    
    public function dispatch(
        Uploader_Controller_Http_Request $request,
        Uploader_Controller_Http_Response $response
    )
    {
        $router = new Uploader_Controller_Router();
        $this->_request = $request;
        $this->_response = $response;
        $this->_router = $router;
        $controllerName = $router->getControllerName();
        $actionName = $router->getActionName();
        $request->setControllerName($controllerName);
        $request->setActionName($actionName);
        $request->setParams($router->getParams());
        
        $controllerClass = ucfirst($controllerName) . 'Controller';
        $this->_loadClass($controllerClass);
        $controller = new $controllerClass($request, $response);
        if (!$controller instanceof Uploader_Controller_Action) {
            throw new Uploader_Controller_Exception(
                'The specified controller must be an instance of ' .
                    '"Uploader_Controller_Action".'
            );
        }
        
        $view = new Uploader_View(APPLICATION_PATH . '/scripts');
        $controller->setView($view);
        
        $controller->init();
        if ($response->isRedirect()) {
            $response->sendResponse();
            return;
        }
        $actionMethod = strtolower($actionName) . 'Action';
        if (!method_exists($controller, $actionMethod)) {
            throw new Uploader_Controller_Exception(
                'The specified action doesn\'t exists.'
            );
        }
        
        $controller->$actionMethod();
        if (!$response->isRedirect()) {
            $content = $view->render(
                $request->getControllerName() . '/' . $request->getActionName() .
                    '.phtml'
            );
            $response->setBody($content);
        }
        $response->sendResponse();
    }
    
    public function getRouter()
    {
        return $this->_router;
    }
    
    public function getRequest()
    {
        return $this->_request;
    }
    
    public function getResponse()
    {
        return $this->_response;
    }
    
    private function _loadClass($className)
    {
        $controllerFile = $this->_controllerDir . '/' . $className . '.php';
        if (realpath($controllerFile) == '') {
            throw new Uploader_Controller_Exception(
                'Unknown controller specified.'
            );
        }
        require_once $controllerFile;
    }
    
    public function getBaseUrl()
    {
        return $this->_baseUrl;
    }
    
}
