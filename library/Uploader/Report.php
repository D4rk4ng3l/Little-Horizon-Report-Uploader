<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Darky
 * Date: 17.09.11
 * Time: 16:33
 * To change this template use File | Settings | File Templates.
 */
 
class Uploader_Report
{
    const VISIBILITY_PUBLIC = 'public';

    const VISIBILITY_PRIVATE = 'private';

    /**
     * @var string
     */
    private $_extracted;

    /**
     * @var int
     */
    private $_creationTime;

    /**
     * @var string
     */
    private $_visibility = self::VISIBILITY_PUBLIC;

    /**
     * @var string
     */
    private $_id;

    /**
     * @var string
     */
    private $_comment;

    /**
     * @var string
     */
    private $_subject;
    
    public function __construct($reportData)
    {
        $this->_creationTime = mktime(0, 0, 0);
        if (isset($reportData['creationTime'])) {
            $this->_creationTime = (int) $reportData['creationTime'] - ($reportData['creationTime'] % 86400);
        }

        if (isset($reportData['visibility'])) {
            $reflection = new ReflectionClass($this);
            if (!in_array($reportData['visibility'], $reflection->getConstants())) {
                throw new Uploader_Report_Exception(
                    'Unknown visibility specified. Please use Uploader_Report::VISIBILITY_* class constants.'
                );
            }
            $this->_visibility = (string) $reportData['visibility'];
        }

        if (isset($reportData['report'])) {
            $this->_extracted = (string) $reportData['report'];
        }

        if (isset($reportData['id'])) {
            $this->_id = (string) $reportData['id'];
        }

        if (isset($reportData['comment'])) {
            $this->_comment = (string) $reportData['comment'];
        }

        if (isset($reportData['subject'])) {
            $this->_subject = (string) $reportData['subject'];
        }
    }

    public function extract($htmlReport)
    {
        /**
         * @var DOMNodeList $nodes
         * @var DOMNodeList $anchorNodes
         * @var DOMNodeList $subjectNodes
         */
        $htmlReport = iconv('UTF-8', 'ISO-8859-1', $htmlReport);
        $domDocument = new DOMDocument();
        @$domDocument->loadHTML($htmlReport);
        $xpath = new DOMXPath($domDocument);
        $nodes = $xpath->evaluate("//div[@class='MessageContainer']");
        $extractedReport = '';
        if ($nodes->length == 1) {
            $report = $nodes->item(0);
            $anchorNodes = $xpath->evaluate("//a[@title='zurück zur Übersicht']");
            if ($anchorNodes->length > 0) {
                $anchorNodes->item(0)->parentNode->removeChild($anchorNodes->item(0));
                $anchorNodes = $xpath->evaluate("//img[@class='MessageButtons']/../a", $report);
                $actionCell = $anchorNodes->item(0)->parentNode;
                while ($actionCell->hasChildNodes()) {
                    $actionCell->removeChild($actionCell->firstChild);
                }
                $extractedReport = $domDocument->saveXML($nodes->item(0));

                $subjectNodes = $xpath->evaluate("//td[@class='MessageTableCell']/a");
                $this->_subject = $domDocument->saveXML($subjectNodes->item(0));
                $this->_subject = str_replace(
                    array('<a', '</a', '&#13;'),
                    array('<span', '</span', ''),
                    $this->_subject
                );
                $extractedReport = str_replace(
                    array('<a', '</a', '&#13;'),
                    array('<span', '</span', ''),
                    $extractedReport
                );
                $this->_extracted = $extractedReport;
                $this->_id = md5($extractedReport);
            }
        }
        return $extractedReport;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->_comment = $comment;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->_comment;
    }

    /**
     * @param int $creationTime
     */
    public function setCreationTime($creationTime)
    {
        $this->_creationTime = $creationTime;
    }

    /**
     * @return int
     */
    public function getCreationTime()
    {
        return $this->_creationTime;
    }

    /**
     * @param string $extracted
     */
    public function setExtracted($extracted)
    {
        $this->_extracted = $extracted;
    }

    /**
     * @return string
     */
    public function getExtracted()
    {
        return $this->_extracted;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param string $visibility
     */
    public function setVisibility($visibility)
    {
        $this->_visibility = $visibility;
    }

    /**
     * @return string
     */
    public function getVisibility()
    {
        return $this->_visibility;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->_subject = $subject;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->_subject;
    }

}
