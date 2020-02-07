<?php
error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
require_once '../../lib/bo/CronManager.php';
include_once '../../lib/system/utilities.php';
$cronm = new CronManager();

$ts = $cronm->getPendingTimesheets();
$html = $ts['consolidated'];
$subject = "Elixia Tech - Timesheets";

//$subject = "Elixia Tech - Reminder (Sales Pipeline):" . date('Y-m-d H:i:s');
$to = array("sanketsheth@elixiatech.com","kartik.elixiatech@gmail.com","mihir@elixiatech.com","hr@elixiatech.com","shreya.a@elixiatech.com");
$strCCMailIds = "";
$strBCCMailIds = "";
$attachmentFilePath = "";
$attachmentFileName = "";
$isTemplatedMessage = 1;
echo $html; echo PHP_EOL;
if(!sendMailUtil($to, $strCCMailIds, $strBCCMailIds, $subject, $html, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage)){
    echo "Sending failed.";
}

?>
