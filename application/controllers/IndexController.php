<?php
class IndexController extends Uploader_Controller_Action
{
    public function init()
    {
        $this->_response->setRedirect(
            $this->view->url(
                array( 'controller' => 'report', 'action' => 'index'),
                true
            )
        );
    }
    public function indexAction()
    {
        $this->view->title = 'Foobar';
        $frontController = Uploader_Controller_Front::getInstance();
        $reportsPath = $frontController->getResource('reportConfig');
        $report = new Uploader_Report($reportsPath);
        $report->setReport('foo');
        $report->save();
    }
}
