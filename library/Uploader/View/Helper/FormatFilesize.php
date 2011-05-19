<?php
class Uploader_View_Helper_FormatFilesize extends Uploader_View_Helper_Abstract
{
    private $_sizeUnits = array(
        0 => 'Bytes',
        1 => 'KB',
        2 => 'MB',
        3 => 'GB',
    );
    public function formatFilesize($filesize)
    {
        $index = 0;
        while ($filesize > 768) {
            $index++;
            $filesize /= 1024;
        }
        if ($index > 0) {
            $filesize = number_format($filesize, 2, ',', '.');
        }
        return $filesize . ' ' . $this->_sizeUnits[$index];
    }
}
