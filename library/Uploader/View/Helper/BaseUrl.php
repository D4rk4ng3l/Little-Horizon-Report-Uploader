<?php
require_once ('Uploader/View/Helper/Abstract.php');
class Uploader_View_Helper_BaseUrl extends Uploader_View_Helper_Abstract
{
    public function baseUrl()
    {
        return Uploader_Controller_Front::getInstance()->getBaseUrl();
    }
}