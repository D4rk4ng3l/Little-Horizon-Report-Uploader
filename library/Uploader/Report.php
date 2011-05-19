<?php
class Uploader_Report
{
    const COMPRESS_BZIP2 = 'compress.bzip2';
    const COMPRESS_NONE = 'file';
    const COMPRESS_GZIP = 'compress.zlib';
    const COMPRESS_ZIP = 'zip';
    
    const VISIBILITY_PRIVATE = 0;
    const VISIBILITY_PUBLIC = 1;
    
    public static $visibilityMap = array(
        'private' => self::VISIBILITY_PRIVATE,
        'public' => self::VISIBILITY_PUBLIC,
    );
    
    private $_storePath = '';
    
    private $_compressionMap = array(
        'compress.bzip2' => '.report.bz2',
        'compress.zlib' => '.report.gz',
        'zip' => '.report.zip',
        'file' => '.report.none',
    );
    
    private $_compressionMethod = self::COMPRESS_BZIP2;
    
    private $_report = null;
    
    private $_reportId = null;
    
    private $_meta = '';
    
    private $_visibility = self::VISIBILITY_PRIVATE;
    
    public function __construct($storePath, $compression = self::COMPRESS_BZIP2)
    {
        $storePath = realpath($storePath);
        if ($storePath == '') {
            require_once 'Uploader/Exception.php';
            throw new Uploader_Exception('The given path doesn\'t exist.');
        }
        $this->_storePath = $storePath;
        $this->_compressionMethod = $compression;
    }
    
    public function load($reportId, $visibility)
    {
        $fileName = $this->_storePath . '/' . $reportId . '.'
            . $visibility . $this->_getReportExt();
        if (realpath($fileName) == '' || !is_readable($fileName)) {
            require_once 'Uploader/Exception.php';
            throw new Uploader_Exception(
                'The specified report doesn\'t exist.'
            );
        }
        
        $this->_reportId = $reportId;
        $filePointer = fopen(
            $this->_compressionMethod . '://' . $fileName,
            'r'
        );
        $this->_report = '';
        while (!feof($filePointer)) {
            $this->_report .= fread($filePointer, 4096);
        }
        $this->_visibility = $visibility;
        $this->_meta = unserialize(
            file_get_contents(
                $this->_storePath . '/' . $reportId . '.' . $visibility .
                    '.meta'
            )
        );
        fclose($filePointer);
    }
    
    public function save()
    {
        if ($this->_report === null) {
            require_once 'Uploader/Exception.php';
            throw new Uploader_Exception(
                'No report to save. Set or load a report first.'
            );
        }
        
        if ($this->_reportId === null) {
            $this->_reportId = md5($this->_report);
        }
        $visibilityMap = $this->_switchArrayKeys(self::$visibilityMap);
        $fileName = $this->_storePath . '/' . $this->_reportId
            . '.' . $visibilityMap[$this->_visibility] . $this->_getReportExt();
        $fileName = str_replace('\\', '/', $fileName);
        if (file_exists($fileName)) {
            return $this->_reportId;
        }
        $filePointer = fopen(
            $this->_compressionMethod . '://' . $fileName,
            'w+'
        );
        fwrite($filePointer, $this->_report);
        fclose($filePointer);
        
        file_put_contents(
            $this->_storePath . '/' . $this->_reportId . '.'
                . $visibilityMap[$this->_visibility] . '.meta',
            serialize($this->_meta)
        );
        return $this->_reportId;
    }
    
    public function getReport()
    {
        return $this->_report;
    }
    
    public function setReport($report)
    {
        $this->_report = (string) $report;
    }
    
    public function parseSource($html, $options = array())
    {
        if (
            strpos(
                $html,
                '../pix/skins/default/cnt/reiter_1_aktiv.gif'
            ) === false
        ) {
            return false;
        }
        $html = str_replace("\r\n", "\n", $html);
        $start = strpos($html, '<div class="MessageContainer">');
        $end = strrpos($html, '</div>');
        $completeReport = substr($html, $start, $end - $start);
        $baseUrl = Uploader_Controller_Front::getInstance()->getBaseUrl();
        $completeReport = str_replace(
            '../pix/skins/default/cnt',
            $baseUrl . '/images',
            $completeReport
        );
        $completeReport = preg_replace(
            '/<a[^>]*class="SystemGfxButton[^"]*">[^<]*<\/a>/s',
            '',
            $completeReport
        );
        $completeReport = preg_replace(
            '/<a[^>]*class="([^"]*)">([^<]*)<\/a>/s',
            '<span class="$1">$2</span>',
            $completeReport
        );
        $completeReport = preg_replace(
            '/<a[^>]*>(<img[^>]*>[^<]*)<\/a>/s',
            '$1',
            $completeReport
        );
        preg_match(
            '/<span class="Message[^"]*">([^<]*)<\/span>/s',
            $completeReport,
            $subject
        );
        preg_match(
            '/(?<d>\d+)\.(?<m>\d+)\.(?<Y>\d+)' . "\n" . 
                '(?<H>\d+):(?<i>\d+):(?<s>\d+)/s',
            $completeReport,
            $time
        );
        preg_match(
            '/<td class="MessageTableCell" width="185" valign="top">' .
                '(?<sender>[^<]*)<br><div style="font: 10px Verdana;">' .
                '(?<planet>.*) \((?<G>\d):(?<S>\d+):(?<P>\d+)\)<\/div><\/td>/',
            $completeReport,
            $sender
        );
        $this->_meta = array(
            'subject' => $subject[1],
            'timestamp' => mktime(
                $time['H'],
                $time['i'],
                $time['s'],
                $time['m'],
                $time['d'],
                $time['Y']
            ),
            'sender' => $sender['sender'],
            'planet' => $sender['planet'],
            'coords' => array($sender['G'], $sender['S'], $sender['P']),
        );
        $this->setReport($completeReport);
    }
    
    public function setVisibility($visibility)
    {
        $this->_visibility = $visibility;
    }
    
    public function getVisibility($returnAsString = false)
    {
        if ($returnAsString) {
            $visibilityMap = $this->_switchArrayKeys(self::$visibilityMap);
            return $visibilityMap[$this->_visibility];
        }
        return $this->_visibility;
    }
    
    public function getMetaData()
    {
        return $this->_meta;
    }
    
    private function _getReportExt()
    {
        return $this->_compressionMap[$this->_compressionMethod];
    }
    
    private function _switchArrayKeys(array $array)
    {
        $result = array();
        foreach ($array as $key => $value)
        {
            $result[$value] = $key;
        }
        return $result;
    }
}
