<?php

require_once '../../lib/bo/CronManager.php';
include_once '../../lib/system/utilities.php';
$cronm = new CronManager();

$ts = $cronm->getTimedMembers();
// echo "<pre>";
// print_r($ts['unlocked']);
// echo "</pre>";
foreach($ts['members'] as $k=>$person){
	$html = "";
	$subject = "Elixia tech - Timesheet reminder";
	$to = array($person['email']);
	$strCCMailIds = "";
	$strBCCMailIds = "";
	$attachmentFilePath = "";
	$attachmentFileName = "";
	$isTemplatedMessage = 1;
	$html .= "Hello ".$person['name'].", Request you to kindly fill and lock and your timesheet by EOD today. <br/>Regards, Elixia Tech";
	echo $html; echo PHP_EOL;
	if(!sendMailUtil($to, $strCCMailIds, $strBCCMailIds, $subject, $html, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage)){
	    echo "Sending failed.";
	}
}
?>