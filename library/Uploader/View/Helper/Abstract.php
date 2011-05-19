<?php
/**
 * Stellt die Instanz der Template-Engine für Template-Helfer zur Verfügung.
 *
 * @author Darky
 */
require_once 'Uploader/View.php';
abstract class Uploader_View_Helper_Abstract
{
    /**
     *
     * @var Uploader_View Instanz der Template-Engine
     */
    public $view = null;

    /**
     * Setzt die Instanz der Template-Engine zur Verwendung in den
     * Template-Helfern.
     *
     * @param Uploader_View $engine Instanz der Template-Engine
     */
    public function setView(Uploader_View $view)
    {
        $this->view = $view;
    }

}
