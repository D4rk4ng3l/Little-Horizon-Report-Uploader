<?php

class ReportController extends Uploader_Controller_Action
{
    public function indexAction()
    {
        $reportsPath = Uploader_Controller_Front::getInstance()
            ->getResource('reportConfig');
        $uploaderManager = new Uploader_Report_Manager($reportsPath);
        $this->view->reports = $uploaderManager->getStoredReports();
    }
    public function showAction()
    {
        $reportsPath = Uploader_Controller_Front::getInstance()
            ->getResource('reportConfig');
        $uploaderReport = new Uploader_Report($reportsPath);
        $uploaderReport->load(
            $this->_request->getParam('id'),
            $this->_request->getParam('vis', 'public')
        );
        $this->view->report = $uploaderReport->getReport();
    }
    public function uploadAction()
    {
        if ($this->_request->isPost()) {
            $reportsPath = Uploader_Controller_Front::getInstance()
                ->getResource('reportConfig');
            $visibility = Uploader_Report::$visibilityMap[$this->_request
                ->getParam('vis')];
            $uploaderReport = new Uploader_Report($reportsPath);
            $uploaderReport->parseSource($this->_request->getParam('source'));
            $uploaderReport->setVisibility($visibility);
            $reportId = $uploaderReport->save();
            if ($reportId !== null) {
                $this->_response->setRedirect(
                    $this->view->url(
                        array(
                            'controller' => 'report',
                            'action' => 'show',
                            'id' => $reportId,
                            'vis' => $this->_request->getParam('vis'),
                        ),
                        true
                    )
                );
            }
        }
        
        $this->view->report = $this->_request->getParam('source');
        $this->view->visibility = $this->_request->getParam('vis');
    }
}
