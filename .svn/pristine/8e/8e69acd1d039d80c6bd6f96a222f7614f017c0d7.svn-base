<?php

$mailTo = array('sanketsheth@elixiatech.com', 'mihir@elixiatech.com');

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../../lib/autoload.php';
require_once '../../lib/system/utilities.php';
$crnmanager = new CronManager();

$yesterday = date("Y-m-d", strtotime('yesterday'));
$details = $crnmanager->getOperationCallLog();
if (isset($details)) {
    $i = 0;
    $table = "<table border='1'><tr><th>Sr No</th><th>Field Engineer Name</th><th>Type</th><th>Status</th><th>Old Unit No</th><th>New Unit No</th><th>Old Simcard No</th><th>New Simcard No</th><th>Old Vehicle No</th><th>New Vehicle No</th><th>Created By</th><th>Customer No</th><th>Company Name</th><th>Remark</th></tr>";
    foreach ($details as $data) {
        $i++;
        $table.="<tr><td>" . $i . "</td><td>" . $data['name'] . "</td><td>" . $data['type'] . "</td><td>" . $data['buckettype'] . "</td><td>" . $data['oldunit'] . "</td><td>" . $data['newunit'] . "</td><td>" . $data['oldsim'] . "</td><td>" . $data['newsim'] . "</td><td>" . $data['oldvehicle'] . "</td><td>" . $data['newvehicle'] . "</td><td>" . $data['createdby'] . "</td><td>" . $data['customerno'] . "</td><td>" . $data['customercompany'] . "</td><td>" . $data['remark'] . "</td></tr>";
    }
    $table.="</table>";
    $display = "<!DOCTYPE html>
<html>
    <head>
        <title>Create Customer</title>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <style type='text/css'>
            body{
                font-family:Arial;
                font-size: 11pt;
            }
            table{
                text-align: center;
                border-right:1px solid black;
                border-bottom:1px solid black;

                border-collapse:collapse;
                font-family:Arial;
                font-size: 10pt;
                width: 60%;
            }

            td, th{
                border-left:1px solid black;
                border-top:1px solid black;
            }
            
            .colHeading{
                background-color: #D6D8EC;
            }

            span{
                font-weight:bold;
            }
        </style>
    </head>
    <body>
        <div style='color: #000000'>
            <h4>Dear Elixia Team,</h4>
            <p>Please find the Operation Call Log for ".$yesterday.":</p><br>";
    $display.=$table;
    $display.="</div></body></html>";

    $strCCMailIds = "";
    $strBCCMailIds = "";
    $subject = "Operation Call Log for ".$yesterday;
    $attachmentFilePath = "";
    $attachmentFileName = "";
    $isTemplatedMessage = 1;
    $isMailSent = sendMailUtil($mailTo, $strCCMailIds, $strBCCMailIds, $subject, $display, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
    if ($isMailSent == 1) {
        echo "Mail sent";
    }
}
?>
