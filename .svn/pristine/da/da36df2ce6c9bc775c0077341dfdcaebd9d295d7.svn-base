<?php

$mailTo = array('sanketsheth@elixiatech.com', 'mihir@elixiatech.com');

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../../lib/autoload.php';
require_once '../../lib/system/utilities.php';
$crnmanager = new CronManager();

$details = $crnmanager->getExpiredTicket();

$i = $total = 0;
$table = "<table><tr><th>Sr No</th><th>Employee Name</th><th>No of Expired Ticket</th></tr>";
foreach ($details as $data) {
    $i++;
    $table.="<tr><td>" . $i . "</td><td>" . $data['name'] . "</td><td>" . $data['count'] . "</td></tr>";
    $total+=$data['count'];
}
$table.="</table>";



$html = file_get_contents('../emailtemplates/cronExpiredTicket.html');
$html = str_replace("{{TABLE}}", $table, $html);
$html = str_replace("{{COUNT}}", $total, $html);

$strCCMailIds = "";
$strBCCMailIds = "";
$subject = "Expired Tickets Report";
$attachmentFilePath = "";
$attachmentFileName = "";
$isTemplatedMessage = 1;
$isMailSent = sendMailUtil($mailTo, $strCCMailIds, $strBCCMailIds, $subject, $html, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
?>