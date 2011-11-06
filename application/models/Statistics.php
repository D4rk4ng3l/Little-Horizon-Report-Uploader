<?php
class Application_Model_Statistics extends Uploader_Model_Db
{
    public function getSummary()
    {
        $sql = "SELECT `stats`.*, `meta`.*, `peak`.* FROM
            (SELECT AVG(`count`) `avgCount`, SUM(`count`) `sumCount` FROM `" . $this->_tables['stats'] . "`) `stats`,
            (SELECT SUM(`size`) `sumSize`, AVG(`size`) `avgSize` FROM `" . $this->_tables['metadata'] . "`) `meta`,
            (SELECT `count` `peakCount`, `creation` `peakDate` FROM `" . $this->_tables['stats'] . "`
            ORDER BY `count` DESC LIMIT 1) `peak`";
        $pdoState = $this->_db->query($sql);
        $summary = $pdoState->fetch(PDO::FETCH_ASSOC);
        $pdoState->closeCursor();
        return $summary;
    }

    public function getDailyStats()
    {
        $sql = "SELECT * FROM `" . $this->_tables['stats'] . "` ORDER BY `creation` DESC";
        $pdoState = $this->_db->query($sql);
        return $pdoState->fetchAll(PDO::FETCH_ASSOC);
    }
}
