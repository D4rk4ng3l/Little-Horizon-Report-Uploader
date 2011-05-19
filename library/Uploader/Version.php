<?php
class Uploader_Version
{
    const VERSION = '2.0dev';
    
    public function compareVersion($version)
    {
        return version_compare(self::VERSION, $version);
    }
}
