<?php
require_once ('Uploader/Application/Bootstrap.php');
class Bootstrap extends Uploader_Application_Bootstrap
{
    protected function _initReportConfig()
    {
        return $this->_config->paths->report;
    }
}
