<?php

class ReportsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $reportModel = new Application_Model_Report();
        $this->view->reports = $reportModel->getList();
    }

    public function uploadAction()
    {
        $htmlReport = $this->_request->getParam('report');
        if ($htmlReport !== null) {
            if ($this->_request->isPost()) {
                $visibility = $this->_request->getParam('visibility', Uploader_Report::VISIBILITY_PRIVATE);
                $comment =  $this->_request->getParam('comment');
                $reportModel = new Application_Model_Report();
                $reportId = $reportModel->save($htmlReport, $visibility, $comment);
                if ($reportId === false) {
                    $this->_forward(
                        'index',
                        'index',
                        null,
                        array(
                            'report' => $htmlReport,
                            'visibility' => $visibility,
                            'comment' => $comment,
                            'erroneousReport' => true
                        )
                    );
                } else {
                    $this->_forward('show', 'reports', null, array('id' => $reportId));
                }
            }
        }
    }

    public function showAction()
    {
        $reportModel = new Application_Model_Report();
        $this->view->report = $reportModel->load($this->_request->getParam('id'));
    }
    
    public function statisticsAction()
    {
        $statsModel = new Application_Model_Statistics();
        $this->view->summary = $statsModel->getSummary();
        $this->view->dayStats = $statsModel->getDailyStats();
    }
}





