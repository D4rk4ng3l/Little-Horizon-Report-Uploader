<?php
require_once ('Uploader/View/Helper/Abstract.php');
class Uploader_View_Helper_Url extends Uploader_View_Helper_Abstract
{
    public function url(array $params, $reset = false)
    {
        $frontController = Uploader_Controller_Front::getInstance();
        $router = $frontController->getRouter();
        return $router->buildUrl($params, $reset);
    }
}
