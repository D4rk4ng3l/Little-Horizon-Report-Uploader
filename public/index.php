<?php
// Define path to application directory
defined('APPLICATION_PATH') || define(
    'APPLICATION_PATH',
    realpath(dirname(__FILE__) . '/../application')
);

set_include_path(
    implode(
        PATH_SEPARATOR,
        array(
            realpath(APPLICATION_PATH . '/../library'),
            get_include_path(),
        )
    )
);

include 'Uploader/Autoloader.php';
$autoloader = Uploader_Autoloader::getInstance();
$autoloader->registerNamespace('Uploader_');

$config = new Uploader_Config_Ini(APPLICATION_PATH . '/config/application.ini');
$application = new Uploader_Application($config);

$application->run();
