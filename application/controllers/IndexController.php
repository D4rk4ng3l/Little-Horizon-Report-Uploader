<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->view->erroneousReport = $this->_request->getParam('erroneousReport', false);
        $this->view->visibility = $this->_request->getParam('visibility', Uploader_Report::VISIBILITY_PRIVATE);
        $this->view->report = $this->_request->getParam('report');
        $this->view->comment = $this->_request->getParam('comment');
    }

    public function versionAction()
    {
        $config = Uploader_Registry::getConfig();
        $this->view->download = $config->getParam('download');
    }

    public function downloadAction()
    {
        Zend_Controller_Front::getInstance()->setParam('noViewRenderer', true);
        Zend_Layout::getMvcInstance()->disableLayout();

        $config = Uploader_Registry::getConfig();
        $archId = $this->_request->getParam('arch', 'zip');

        $downloadConfig = $config->getParam('download');
        $archInfo = $downloadConfig['archives'][$archId];
        $filename = $downloadConfig['baseName'] . '.' . $archInfo['suffix'];
        $sourceFile = $downloadConfig['sourcePath'] . '/' . $filename;

        $mimeType = 'application/octet-stream';
        if (isset($archInfo['mimeType'])) {
            $mimeType = $archInfo['mimeType'];
        }

        $this->_response->setHeader('Content-Type', $mimeType);
        $this->_response->setHeader('Content-Length', filesize($sourceFile));
        $this->_response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        readfile($sourceFile);
    }
}
