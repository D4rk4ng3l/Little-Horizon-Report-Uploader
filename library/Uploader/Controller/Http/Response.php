<?php
class Uploader_Controller_Http_Response
{
    const BODY_APPEND = 0;
    
    const BODY_PREPEND = 1;
    
    private $_headers = array(
        'Content-Type' => 'text/html',
    );
    
    private $_body = '';
    
    private $_redirectUrl = '';
    
    public function __construct()
    {
    }
    
    public function setHeader($header, $value)
    {
        $this->_headers[$header] = $value;
    }
    
    public function setHeaders(array $headers)
    {
        $this->_headers = $headers;
    }
    
    public function addBody($body, $mode = self::BODY_APPEND)
    {
        if ($mode == self::BODY_PREPEND) {
            $this->_body = $body . $this->_body;
        } else {
            $this->_body .= $body;
        }
    }
    
    public function setBody($body)
    {
        $this->_body = (string) $body;
    }
    
    public function getBody()
    {
        return $this->_body;
    }
    
    public function getHeader($header)
    {
        if (isset($this->_headers[$header])) {
            return $this->_headers[$header];
        }
        return null;
    }
    
    public function getHeaders()
    {
        return $this->_headers;
    }
    public function clearHeaders()
    {
        $this->_headers = array();
    }
    
    public function clearBody()
    {
        $this->_body = '';
    }
    
    public function isRedirect()
    {
        return ($this->_redirectUrl != '');
    }
    public function canSendHeaders()
    {
        if (headers_sent($file, $line)) {
            require_once 'Uploader/Controller/Http/Exception.php';
            throw new Uploader_Controller_Http_Exception(
                'Headers already send in file "' . $file . '" on line ' .
                    $line . '.'
            );
        }
        return true;
    }
    public function sendHeaders()
    {
        if ($this->isRedirect()) {
            header('Location: ' . $this->_redirectUrl);
            return;
        }
        if ($this->canSendHeaders()) {
            foreach ($this->_headers as $header => $value) {
                header($header . ': ' . $value);
            }
        }
    }
    
    public function outputBody()
    {
        echo $this->_body;
    }
    
    public function sendResponse()
    {
        $this->sendHeaders();
        $this->outputBody();
    }
    
    public function setRedirect($url)
    {
        $this->_redirectUrl = (string) $url;
    }
}
