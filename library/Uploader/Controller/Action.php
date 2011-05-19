<?php
require_once 'Uploader/Controller/Http/Request.php';
require_once 'Uploader/Controller/Http/Response.php';
require_once 'Uploader/View.php';

class Uploader_Controller_Action
{
    /**
     * 
     * @var Uploader_Controller_Http_Request
     */
    protected $_request = null;
    
    /**
     * 
     * @var Uploader_Controller_Http_Response
     */
    protected $_response = null;
    
    public $view = null;
    
    public function __construct(
        Uploader_Controller_Http_Request $request,
        Uploader_Controller_Http_Response $response
    )
    {
        $this->_request = $request;
        $this->_response = $response;
    }
    
    public function setView(Uploader_View $view)
    {
        $this->view = $view;
    }
    
    public function init()
    {
    }
    
    protected function _redirect($url)
    {
        $this->_response->setRedirect($url);
    }
}
