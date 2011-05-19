<?php
class Uploader_Report_Manager
{
    private $_sizeUnits = array(
        0 => 'Bytes',
        1 => 'KB',
        2 => 'MB',
        3 => 'GB',
    );
    
    private $_reportPath = null;
    
    public function __construct($reportPath)
    {
        $this->_reportPath = $reportPath;
    }
    
    public function getStoredReports(
        $visibility = Uploader_Report::VISIBILITY_PUBLIC
    )
    {
        $dirIter = new DirectoryIterator($this->_reportPath);
        $files = array();
        foreach ($dirIter as $fileInfo) {
            if ($fileInfo->isDot() || $fileInfo->isDir()) {
                continue;
            }
            if (!preg_match(
                '/(?<id>[0-9a-z]*)\.(?<visibility>[a-z]*)'
                    . '\.report\.(?<compression>[a-z0-9]*)/',
                $fileInfo->getFilename(),
                $args
            )) {
                continue;
            }
            if (
                Uploader_Report::$visibilityMap[$args['visibility']] >=
                    $visibility
            ) {
                $metaData = file_get_contents(
                    $this->_reportPath . '/' . $args['id'] . '.'
                        . $args['visibility'] . '.meta'
                );
                $metaData = unserialize($metaData);
                $fileSize = $this->_formatFilesize($fileInfo->getSize());
                $files[$metaData['timestamp']] = array(
                    'id' => $args['id'],
                    'visibility' => $args['visibility'],
                    'size' => $fileSize,
                    'title' => $metaData['subject'],
                    'date' => date('d.m.Y H:i:s', $metaData['timestamp']),
                    'planet' => implode(':', $metaData['coords']) . ' - ' .
                        $metaData['planet'],
                );
            }
        }
        krsort($files);
        return $files;
    }
    
    private function _formatFilesize($filesize)
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
