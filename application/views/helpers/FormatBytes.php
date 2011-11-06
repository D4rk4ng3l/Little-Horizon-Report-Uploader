<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Darky
 * Date: 18.09.11
 * Time: 13:42
 * To change this template use File | Settings | File Templates.
 */
 
class Zend_View_Helper_FormatBytes
{
    private $_unitMap = array(
        0 => 'B',
        1 => 'KiB',
        2 => 'MiB',
        3 => 'GiB',
        4 => 'TiB',
        5 => 'PiB',
        6 => 'EiB',
        7 => 'ZiB',
        8 => 'YiB',
    );

    public function formatBytes($filesize, $format = 'short', $decimals = 0, $dec_point = '.' , $thousands_sep = ',')
    {
        $i = 0;
        while ($filesize > 1024) {
            $i++;
            $filesize = $filesize / 1024;
        }
        $formattedSize = number_format($filesize, $decimals, $dec_point, $thousands_sep);
        return $formattedSize . ' ' . $this->_unitMap[$i] . (($format == 'long') ? 'ytes' : '');
    }
}
