<?php
require_once ('Uploader/View/Helper/Abstract.php');
class Uploader_View_Helper_HeadScript extends Uploader_View_Helper_Abstract
{
    private $_scriptFiles = array();
    
    private $_scripts = array();
    
    private $_captureLock = false;
    
    public function headScript()
    {
        return $this;
    }
    
    public function addScriptFile($filename)
    {
        if (!in_array($filename, $this->_scriptFiles)) {
            $this->_scriptFiles[] = (string) $filename;
        }
    }
    
    public function addScript($script)
    {
        $this->_scripts[] = (string) $script;
    }
    
    public function captureStart()
    {
        if ($this->_captureLock) {
            require_once 'Uploader/View/Helper/Exception.php';
            throw new Uploader_View_Helper_Exception('Cannot nest captures');
        }
        $this->_captureLock = true;
        ob_start();
    }
    
    public function captureEnd()
    {
        $this->_scripts[] = ob_get_contents();
        ob_end_clean();
        $this->_captureLock = false;
    }
    
    public function __toString()
    {
        $scripts = '';
        foreach ($this->_scriptFiles as $scriptFile) {
            $scripts .= '<script type="text/javascript" src="' . $scriptFile .
                '"></script>' . PHP_EOL;
        }
        
        if (count($this->_scripts) > 0) {
            $scripts .= '<script type="text/javascript">' . PHP_EOL;
            foreach ($this->_scripts as $script) {
                $scripts .= $script . PHP_EOL;
            }
            $scripts .= '</script>';
        }
        return $scripts;
    }
}
