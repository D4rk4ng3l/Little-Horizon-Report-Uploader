<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Darky
 * Date: 20.08.11
 * Time: 14:59
 * To change this template use File | Settings | File Templates.
 */
 
class Uploader_Registry extends Zend_Registry
{
    /**
     * Returns the config instance if it has been registered, returns null otherwise.
     *
     * @return Uploader_Config|null
     */
    public static function getConfig()
    {
        if (self::isRegistered('_config')) {
            return self::get('_config');
        }

        return null;
    }

    /**
     * Register a Msd_Config instance.
     *
     * @static
     *
     * @param Uploader_Config $config Configuration
     *
     * @return void
     */
    public static function setConfig(Uploader_Config $config)
    {
        self::set('_config', $config);
    }

}
