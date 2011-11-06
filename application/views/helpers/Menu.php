<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Darky
 * Date: 17.09.11
 * Time: 16:16
 * To change this template use File | Settings | File Templates.
 */
 
class Zend_View_Helper_Menu extends Zend_View_Helper_Abstract
{
    public function menu()
    {
        $view = clone $this->view;
        $view->clearVars();
        $view->menuEntries = Uploader_Registry::getConfig()->getParam('menu');
        return $view->render('common/menu.phtml');
    }
}
