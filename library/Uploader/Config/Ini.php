<?php
require_once 'Uploader/Config.php';
class Uploader_Config_Ini extends Uploader_Config
{
    public function __construct(
        $filename,
        $allowModify = false,
        $processSections = true
    )
    {
        $realFile = realpath($filename);
        if ($realFile == '') {
            require_once 'Uploader/Config/Exception.php';
            throw new Uploader_Config_Exception('INI-File doesn\'t exists!');
        }
        $data = parse_ini_file($realFile, $processSections);
        parent::__construct($data, $allowModify);
    }
}

?>