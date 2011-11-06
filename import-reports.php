<?php
$ini = parse_ini_file('application/configs/uploader.ini', true);

$pdo = new PDO(
    sprintf('mysql:host=%s;dbname=%s', $ini['database']['host'], $ini['database']['dbName']),
    $ini['database']['user'],
    $ini['database']['pass']
);

$sql = "INSERT INTO `ul_reports` (`id`, `visibility`, `creation`, `report`, `subject`, `comment`, `size`) "
    . "VALUES (:ID, :VISIBILITY, :CREATION, :REPORT, :SUBJECT, :COMMENT, :SIZE) ON DUPLICATE KEY UPDATE "
    . "`report` = VALUES(`report`), `visibility` = VALUES(`visibility`), `creation` = VALUES(`creation`),"
    . "`comment` = VALUES(`comment`), `subject` = VALUES(`subject`)";
$pdoState = $pdo->prepare($sql);
$reports = new DirectoryIterator('./reports/');
while ($reports->valid()) {
    if ($reports->isDir() || $reports->isDot()) {
        $reports->next();
        continue;
    }
    $mtime = filemtime($reports->getRealPath());
    $data = array(
        'ID' => $reports->getBasename('.bz2'),
        'VISIBILITY' => 'private',
        'CREATION' => $mtime,
        'REPORT' => file_get_contents($reports->getRealPath()),
        'COMMENT' => 'Importierter Bericht',
        'SUBJECT' => '',
        'SIZE' => filesize($reports->getRealPath()),
    );

    printf(
        "Importing report:\nID: %s\nDate: %s\nSize: %s Bytes\n",
        $data['ID'],
        date("d.m.Y", $data['CREATION']),
        number_format($data['SIZE'], 0, ",", ".")
    );

    $pdoState->execute($data);
    if ($pdoState->errorCode() == "00000") {
        echo "\033[32;1mOK\033[0m";
    } else {
        $errInfo = $pdoState->errorInfo();
        echo "\033[31;1mFAILED\033[0m\n\n";
        echo "MySQL {$errInfo[1]} ({$errInfo[0]}): {$errInfo[2]}";
    }
    echo "\n\n";
    $reports->next();
}
