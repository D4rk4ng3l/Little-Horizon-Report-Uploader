<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Darky
 * Date: 17.09.11
 * Time: 19:54
 * To change this template use File | Settings | File Templates.
 */
 
class Application_Model_Report extends Uploader_Model_Db
{
    /**
     * @param $htmlReport
     * @param $visibility
     * @param $comment
     * @return bool|string
     */
    public function save($htmlReport, $visibility, $comment)
    {
        $report = new Uploader_Report(array('visibility' => $visibility));
        $report->extract($htmlReport);

        $extractedReport = $report->getExtracted();

        if (empty($extractedReport)) {
            return false;
        }
        $compressedReport = bzcompress($extractedReport);
        $reportId = $report->getId();
        $sql = "INSERT INTO `" . $this->_tables['metadata']
            . "` (`id`, `visibility`, `creation`, `report`, `subject`, `comment`, `size`) "
            . "VALUES (:ID, :VISIBILITY, :CREATION, :REPORT, :SUBJECT, :COMMENT, :SIZE) ON DUPLICATE KEY UPDATE "
            . "`report` = VALUES(`report`), `visibility` = VALUES(`visibility`), `creation` = VALUES(`creation`),"
            . "`comment` = VALUES(`comment`), `subject` = VALUES(`subject`)";
        $pdoState = $this->_db->prepare($sql);
        $pdoState->execute(
            array(
                'ID' => $reportId,
                'VISIBILITY' => $report->getVisibility(),
                'CREATION' => $report->getCreationTime(),
                'REPORT' => $compressedReport,
                'SUBJECT' => $report->getSubject(),
                'COMMENT' => $comment,
                'SIZE' => strlen($compressedReport),
            )
        );

        return $reportId;
    }

    /**
     *
     * @param $id
     * @return Uploader_Report
     */
    public function load($id)
    {
        $sql = "SELECT `id`, `visibility`, `creation` `creationTime`, `report`, `subject`, `comment` FROM `"
            . $this->_tables['metadata'] . "` WHERE `id` = " . $this->_db->quote($id);
        $pdoState = $this->_db->query($sql);
        if ($pdoState->rowCount() == 0) {
            $pdoState->closeCursor();
            return false;
        }
        $report = $pdoState->fetch(PDO::FETCH_ASSOC);
        $report['report'] = bzdecompress($report['report']);
        $pdoState->closeCursor();
        $report = new Uploader_Report($report);
        return $report;
    }

    public function getList($visibility = Uploader_Report::VISIBILITY_PUBLIC)
    {
        $sql = "SELECT `id`, `creation`, `subject` FROM `" . $this->_tables['metadata'] . "` WHERE `visibility` = "
            . $this->_db->quote($visibility) . " ORDER BY `creation` DESC";
        $pdoState = $this->_db->query($sql);
        return $pdoState->fetchAll(PDO::FETCH_ASSOC);
    }
}
