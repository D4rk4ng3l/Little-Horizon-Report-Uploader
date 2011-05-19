<?php
/**
 * Formatiert eine Zahl für eine besser lesbare Ausgabe.
 *
 * @author Darky
 */

require_once 'Uploader/View/Helper/Abstract.php';
class Uploader_View_Helper_Stylesheet extends Uploader_View_Helper_Abstract
{
    private $_stylesheets = array();
    
    public function stylesheet($stylesheet = null)
    {
        if (
            $stylesheet !== null && !in_array($stylesheet, $this->_stylesheets)
        ) {
            $this->_stylesheets[] = (string) $stylesheet;
        }
        return $this;
    }
    
    public function __toString()
    {
        $stylesheets = '';
        foreach ($this->_stylesheets as $stylesheetName) {
            $stylesheets .= '<link rel="stylesheet" type="text/css" href="' .
                $stylesheetName . '"';
            if($this->view->isXhtml)
            {
                $stylesheets .= '/';
            }
            $stylesheets .= '>' . PHP_EOL;
        }
        return $stylesheets . PHP_EOL;
    }
}
