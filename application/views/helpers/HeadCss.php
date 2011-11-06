<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Darky
 * Date: 17.09.11
 * Time: 15:54
 * To change this template use File | Settings | File Templates.
 */
 
class Zend_View_Helper_HeadCss extends Zend_View_Helper_HeadLink
{
    public function headCss($cssFileName = null)
    {
        if ($cssFileName !== null) {
            $realCssFile = $this->view->baseUrl() . '/' . $cssFileName;
            $this->headLink(array('rel' => 'stylesheet', 'type' => 'text/css', 'href' => $realCssFile));
        }
        return $this;
    }
}
