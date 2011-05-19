<?php
/**
 * Rendert mehrfach ein Template und gibt es zurück.
 *
 * @author Darky
 */

class Uploader_View_Helper_PartialLoop extends Uploader_View_Helper_Abstract
{
    public function partialLoop($viewScript, array $data = array())
    {
        $result = '';
        foreach($data as $scriptData)
        {
            $oldData = $this->view->getData();
            $this->view->setData($scriptData);
            $result .= $this->view->render($viewScript);
            $this->view->setData($oldData);
        }
        return $result;
    }
}
