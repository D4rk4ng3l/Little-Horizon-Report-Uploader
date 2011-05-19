<?php
/**
 * Klasse zum Rendern von Templates.
 *
 * @author Darky
 */
class Uploader_View
{
    /**
     * @var array Daten für das Templatescript.
     */
    private $_data = array();

    /**
     * @var string Dateiname des zu verwendenen Templates.
     */
    private $_viewScript = null;

    /**
     * @var bool Ausgabeformat in XHTML?
     */
    public $isXhtml = false;
    
    private $_helpers = array();
    
    private $_scriptPath = '';
    /**
     * Klassenkonstruktor
     * @param string $viewScript Dateiname des zu verwendenen Templates.
     */
    public function  __construct($scriptPath)
    {
        $this->_scriptPath = realpath($scriptPath);
    }

    public function getPath()
    {
        return $this->_scriptPath;
    }
    /**
     * Lädt ein Template und stellt Daten für dieses Bereit.
     * Gibt das gerenderte Template zurück.
     * 
     * @param string $viewScript Dateiname des zu verwendenen Templates.
     * 
     * @return string 
     */
    public function render($viewScript = null)
    {
        if($viewScript === null)
        {
            $viewScript = $this->_viewScript;
        }

        $viewScriptFile = $this->_getFileName($viewScript);

        if($viewScriptFile !== false)
        {
            ob_start();
            include $viewScriptFile;
            $rendered = ob_get_contents();
            ob_end_clean();
            return $rendered;
        }
        require_once 'Uploader/View/Exception.php';
        throw new Uploader_View_Exception(
            'View script doesn\'t exists!'
        );
    }

    /**
     * Gibt die Daten für das Template zurück.
     * 
     * @return array Templatedaten
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * Legt die Daten für das Template fest.
     * 
     * @param array $data Templatedaten
     */
    public function setData(array $data)
    {
        $this->_data = $data;
    }

    public function  __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    public function  __get($name)
    {
        return isset($this->_data[$name]) ? $this->_data[$name] : null;
    }

    public function  __isset($name)
    {
        return isset($this->_data[$name]);
    }

    public function __call($func, $arguments)
    {
        if (!isset($this->_helpers[$func])) {
            $helperClass = 'Uploader_View_Helper_' . ucfirst($func);
            require_once 'Uploader/Autoloader.php';
            $loader = Uploader_Autoloader::getInstance();
            $loader->registerNamespace('Uploader_View_Helper');
            $loader->loadClass($helperClass);
            if (!class_exists($helperClass)) {
                require_once 'Uploader/View/Exception.php';
                throw new Uploader_View_Exception(
                    'View helper "' . $func . '" doesn\'t exists.'
                );
            }
            $helper = new $helperClass();
            if (method_exists($helper, 'setView')) {
                $helper->setView($this);
            }
            
            $this->_helpers[$func] = $helper;
        } else {
            $helper = $this->_helpers[$func];
        }
        return call_user_func_array(array($helper, $func), $arguments);
    }

    private function _getFileName($viewScript)
    {
        $filename = realpath($this->_scriptPath . '/' . $viewScript);
        return 
            (is_file($filename) && is_readable($filename)) ? $filename : false;
    }
}
?>
