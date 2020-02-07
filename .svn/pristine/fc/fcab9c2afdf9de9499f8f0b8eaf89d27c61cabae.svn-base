<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../../lib/autoload.php';
require_once '../../lib/system/utilities.php';
$crnmanager = new CronManager();
echo '<pre>';
$details = $crnmanager->getSmsLockReport();
//print_r($details); die();
$i = $count = 0;
$table = "<table border='1'><tr><th>Sr No</th><th>Customer No</th><th>Vehicle No</th><th>Time Locked</th></tr>";
foreach ($details as $data) {
    if ($data['userid'] == 0) {
        $i++;
        $table.="<tr><td>" . $i . "</td><td>" . $data['customerno'] . "</td><td>" . $data['vehicleno'] . "</td><td>" . $data['createdon'] . "</td></tr>";
    } else {
        $count++;
    }
}
$table.="</table><br/><br/>";
if ($count > 0) {
    $i = 0;
    $table.="<table border='1'><tr><th>Sr No</th><th>Customer No</th><th>User Name</th><th>Time Locked</th></tr>";
    foreach ($details as $data) {
        if ($data['vehicleid'] == 0) {
            $i++;
            $table.="<tr><td>" . $i . "</td><td>" . $data['customerno'] . "</td><td>" . $data['realname'] . "</td><td>" . $data['createdon'] . "</td></tr>";
        }
    }
    $table.="</table><br/><br/>";
}

$html = file_get_contents('../emailtemplates/smsLockDailyReport.html');
$html = str_replace("{{TABLE}}", $table, $html);
$arrTo = array('support@elixiatech.com','mihir@elixiatech.com');
$strCCMailIds = "";
$strBCCMailIds = "";
$subject = "SMS lock report";
$attachmentFilePath = "";
$attachmentFileName = "";
$isTemplatedMessage = 1;
$isMailSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $html, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);

//print_r($details);
?>
