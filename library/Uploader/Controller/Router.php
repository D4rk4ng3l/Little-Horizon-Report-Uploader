<?php
require_once 'Uploader/Controller/Front.php';
class Uploader_Controller_Router
{
    private $_requestUri = null;
    
    private $_controllerName = '';
    
    private $_actionName = '';
    
    private $_params = array();
    
    public function __construct()
    {
        $frontController = Uploader_Controller_Front::getInstance();
        $baseUrl = $frontController->getBaseUrl();
        $requestUri = $_SERVER['REQUEST_URI'];
        
        if (strlen($baseUrl) > 0 && strpos($requestUri, $baseUrl) !== false) {
            $requestUri = str_replace($baseUrl, '', $requestUri);
        }
        $requestUri = trim($requestUri, '/');
        $this->_requestUri = $requestUri;
        $urlParts = array();
        if (strlen($requestUri) > 0) {
            $urlParts = explode('/', $requestUri);
        }
        if (count($urlParts) >= 1) {
            $this->_controllerName = urldecode($urlParts[0]);
            unset($urlParts[0]);
        } else {
            $this->_controllerName = 'index';
        }
        if (count($urlParts) >= 1) {
            $this->_actionName = urldecode($urlParts[1]);
            unset($urlParts[1]);
        } else {
            $this->_actionName = 'index';
        }
        $requestArrays = array(
            $_GET,
            $_POST,
            $_COOKIE,
        );
        $params = array();
        foreach ($requestArrays as $requestArray) {
            foreach ($requestArray as $key => $value) {
                $params[$key] = urldecode($value);
            }
        }
        if (count($urlParts) > 0) {
            $urlParts = array_values($urlParts);
            for ($index = 0; $index < count($urlParts); $index = $index + 2) {
                $key = urldecode($urlParts[$index]);
                if (isset($urlParts[$index + 1])) {
                    $value = urldecode($urlParts[$index + 1]);
                } else {
                    $value = null;
                }
                $params[$key] = $value;
            }
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
        return $this->_params;
    }
    public function buildUrl(array $params, $reset = false)
    {
        $frontController = Uploader_Controller_Front::getInstance();
        $url = $frontController->getBaseUrl() . '/';
        if (isset($params['controller'])) {
            $controller = $params['controller'];
            unset($params['controller']);
        } else {
            if ($reset) {
                $controller = 'index';
            } else {
                $controller = urlencode($this->_controllerName);
            }
        }
        if (isset($params['action'])) {
            $action = $params['action'];
            unset($params['action']);
        } else {
            if ($reset) {
                $action = 'index';
            } else {
                $action = urlencode($this->_actionName);
            }
        }
        if (!$reset) {
            $params += $this->_params;
        }
        if (
            !$reset ||
            count($params) > 0 ||
            $controller != 'index' ||
            $action != 'index'
        ) {
            $url .= $controller . '/';
        }
        
        if (
            !$reset ||
            count($params) > 0 ||
            $action != 'index'
        ) {
            $url .= $action . '/';
        }

        foreach ($params as $key => $value) {
            if ($value === null) {
                $url .= urlencode($key) . '/';
            } else {
                $url .= urlencode($key) . '/' . urlencode($value) . '/';
            }
        }
        return $url;
    }
}
