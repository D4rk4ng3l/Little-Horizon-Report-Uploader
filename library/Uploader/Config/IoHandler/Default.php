<?php
/**
 * Default config IO-Handler.
 */
class Uploader_Config_IoHandler_Default implements Uploader_Config_IoHandler_Interface
{
    /**
     * Array with directories, where config files are located.
     *
     * @var array
     */
    private $_configDirectories = array();

    /**
     * Handler for .ini files.
     *
     * @var Uploader_Ini
     */
    private $_iniConfig = null;

    /**
     * Name of the .ini file, where the config is stored.
     *
     * @var string
     */
    private $_configFilename = null;

    /**
     * Class constructor
     *
     * @param array $handlerOptions
     *
     * @return Uploader_Config_IoHandler_Default
     */
    public function __construct($handlerOptions = array())
    {
        if (isset($handlerOptions['directories'])) {
            $this->_configDirectories = (array) $handlerOptions['directories'];
        }
    }

    /**
     * Loads and returns a configuration from session or .ini file.
     * If the config is read from .ini file, it is also stored to session.
     *
     * @param string $configFilename Name of the .ini file, where the config is stored.
     *
     * @return array
     */
    public function load($configFilename)
    {
        $this->_configFilename = $configFilename;

        // Search for the config file in the given directories.
        $this->_initIni();
        $config = $this->_iniConfig->getIniData();

        return $config;
    }

    /**
     * Saves the configuration to session and .ini file.
     *
     * @param array  $config Configuration to save.
     *
     * @return void
     */
    public function save($config)
    {
        if ($this->_iniConfig === null) {
            $this->_initIni();
        }

        // Save config to .ini file
        $this->_iniConfig->setIniData($config);
        $this->_iniConfig->saveFile($this->_configFilename);
    }

    /**
     * Initializes the .ini file handler and sets the full filename of the .ini file.
     *
     * @return void
     */
    private function _initIni()
    {
        foreach ($this->_configDirectories as $configDir) {
            $filename = rtrim($configDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->_configFilename;
            if (file_exists($filename)) {
                $this->_configFilename = $filename;
                $this->_iniConfig = new Uploader_Ini($filename);
                break;
            }
        }
    }
}
