<?php

//error_reporting(0);
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
include_once '../../lib/system/DatabaseManager.php';
$db = new DatabaseManager();
$pdo = $db->CreatePDOConn();

$QUERY = 'call team_cron_getsent_knowledgebase_email()';
$result = $pdo->query($QUERY);
if ($result->rowCount() > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $cron = new stdClass();
        $cron->kb_emailid = $row['kb_emailid'];
        $cron->customerno = $row['customerno'];
        $pdo1 = $db->CreatePDOConn();
        $ArchiveQUERY = "call team_cron_archive_knowledgebase_email($cron->kb_emailid,$cron->customerno)";
        $pdo1->query($ArchiveQUERY);
        $db->ClosePDOConn($pdo1);
    }
}
$db->ClosePDOConn($pdo);


