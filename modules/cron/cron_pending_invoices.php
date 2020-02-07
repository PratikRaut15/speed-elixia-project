<?php

/*
  Name		-       cron_pending_invoice
  Description 	-	will send list of customer having pending invoices..

 */
include_once "../../lib/system/utilities.php";
include_once '../../lib/bo/CustomerManager.php';
include_once '../../lib/bo/TeamManager.php';

$cstmerno = 2;
$detail = array();
$customerno = new CustomerManager($cstmerno);
$customernos = $customerno->getallcustomerno();
$teammgr = new TeamManager();
foreach ($customernos as $data) {
    $detail[$data['customerno']] = $teammgr->getPendingInvoices($data['customerno']);
    $detail[$data['customerno']]['customercompany'] = $data['customercompany'];
}
$taskcount = 0;
$message = "<table border='1'><tr><th>Customer No</th><th>Customer Name</th><th>Count</th></tr>";
foreach ($detail as $key => $data) {
    if (sizeof($data) > 1 && $data['count'] > 0) {
        $taskcount+=$data['count'];
        $message.="<tr><td>" . $key . "</td><td>" . $data['customercompany'] . "</td><td>" . $data['count'] . "</td>";
        $message.="</tr>";
    }
}
$message.="</table>";

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
                border-right:1px solid black;
                border-bottom:1px solid black;

                border-collapse:collapse;
                font-family:Arial;
                font-size: 10pt;
                width: 60px;
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
            <h4>Dear Elixia Team,<span style='margin-left:500px;font-weight:normal;'>Total Number of tasks </span><span style='font-weight:bold'>$taskcount</span></h4>
            <p>Consider following list of Customer who have pending invoices :</p><br>";
$display.=$message;
$display.="</div>
    </body>
</html>";

//echo ($display);
//die();

$subject = "Pending Device Invoices";

$toArr = array('accounts@elixiatech.com', 'support@elixiatech.com', 'mihir@elixiatech.com');
$CCEmail = 'sanketsheth1@gmail.com';
$BCCEmail = '';
$attachmentFilePath = '';
$attachmentFileName = '';
$isSMSSent = sendMailUtil($toArr, $CCEmail, $BCCEmail, $subject, $display, $attachmentFilePath, $attachmentFileName, 1);
if ($isSMSSent == 1) {
    echo "Sent";
} else {
    echo "Not Sent";
}
?>