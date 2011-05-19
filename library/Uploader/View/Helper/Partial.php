<?php
/**
 * Rendert ein Template und gibt es zurück.
 *
 * @author Darky
 */

class Uploader_View_Helper_Partial extends Uploader_View_Helper_Abstract
{
    public function partial($viewScript, array $data = array())
    {
        $oldData = $this->view->getData();
        $this->view->setData($data);
        $htmlOutput = $this->view->render($viewScript);
        $this->view->setData($oldData);
        return $htmlOutput;
    }
}
