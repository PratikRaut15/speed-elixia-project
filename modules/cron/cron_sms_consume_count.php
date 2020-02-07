<?php

/*
  Name		-       cron_sms_consume_count
  Description 	-	list of sms counsume count of all customers.

 */
include_once "../../lib/system/utilities.php";
include_once '../../lib/bo/CustomerManager.php';

$cstmerno = 2;

$customerno = new CustomerManager($cstmerno);
$customernos = $customerno->getallcustomerno();
$cust_data = array();

foreach ($customernos as $data) {
    $detail['customerno'] = $data['customerno'];
    $detail['customercompany'] = $data['customercompany'];
    $consume = $customerno->getSMSConsumed($data['customerno']);
    $detail['consume'] = $consume;
    $cust_data[] = $detail;
}

$demo = array();
foreach ($cust_data as $key1 => $row) {
    $demo[$key1] = $row['consume'];
}
array_multisort($demo, SORT_DESC, $cust_data);

$count = 0;

$message = "<table border='1'><tr><th colspan='3'>Elixia Speed</th></tr><tr><th>Customer No</th><th>Customer Name</th><th>SMS Consumption</th></tr>";
foreach ($cust_data as $data) {
    if ($data['consume'] > 0) {
        $message.="<tr><td>" . $data['customerno'] . "</td><td>" . $data['customercompany'] . "</td><td>" . $data['consume'] . "</td></tr>";
        $count+=$data['consume'];
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
            <h4>Dear Elixia Team,</h4>";
$display.="<br>Total SMS consumed by all customers : <b>" . $count . "</b><br>" . "<p>List of Customer with SMS consume count:</p><br>";
$display.=$message;

$display.="</div>
    </body>
</html>";

$subject = "SMS Consumption for ".date("d-M-Y");

$toArr = array('support@elixiatech.com');
$CCEmail = 'sanketsheth1@gmail.com';
$BCCEmail = 'software@elixiatech.com';
$attachmentFilePath = '';
$attachmentFileName = '';
$isSMSSent = sendMailUtil($toArr, $CCEmail, $BCCEmail, $subject, $display, $attachmentFilePath, $attachmentFileName, 1);
if ($isSMSSent == 1) {
    echo "Sent";
} else {
    echo "Not Sent";
}
?>