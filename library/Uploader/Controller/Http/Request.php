<?php
class Uploader_Controller_Http_Request
{
    private $_controllerName = '';
    
    private $_actionName = '';
    
    private $_params = array();
    
    private $_requestMethod = '';
    
    public function __construct()
    {
        $this->_requestMethod = $_SERVER['REQUEST_METHOD'];
    }
    
    public function setControllerName($controllerName)
    {
        $this->_controllerName = (string) $controllerName;
    }
    
    public function setActionName($actionName)
    {
        $this->_actionName = $actionName;
    }
    
    public function setParams(array $params)
    {
        if (isset($params['controller'])) {
            $this->_controllerName = $params['controller'];
            unset($params['controller']);
        }
        
        if (isset($params['action'])) {
            $this->_actionName = $params['action'];
            unset($params['action']);
        }
        
        $this->_params = $params;
    }
    
    public function getControllerName()
    {
        return $this->_controllerName;
    }
    
    public function getActionName()
    {
        return $this->_actionName;
    }
    
    public function getParams()
    {
        $params = array(
            'controller' => $this->_controllerName,
            'action' => $this->_actionName,
        );
        $params += $this->_params;
        
        return $params;
    }
    
    public function getParam($name, $default = null)
    {
        if ($name == 'controller') {
            return $this->_controllerName;
        }
        
        if ($name == 'action') {
            return $this->_actionName;
        }
        
        if (isset($this->_params[$name])) {
            return $this->_params[$name];
        }
        
        return $default;
    }
    
    public function isPost()
    {
        return $this->_requestMethod == 'POST';
    }
    
    public function isGet()
    {
        return $this->_requestMethod == 'GET';
    }
}
