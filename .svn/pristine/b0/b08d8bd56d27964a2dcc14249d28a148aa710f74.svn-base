<?php

/*
  Name		-       cron_fifty_sms_left
  Description 	-	will send list of customer who have sms left less than 50.

 */
include_once "../../lib/system/utilities.php";
include_once '../../lib/bo/CustomerManager.php';

$cstmerno = 2;
$detail = array();
$customerno = new CustomerManager($cstmerno);
$customernos = $customerno->getLowSmsLeftCust();

$x = 1;
$taskcount=0;
$message = "<table border='1'><tr><th>Sr No</th><th>Customer No</th><th>Customer Name</th><th>SMS Left</th></tr>";
if(isset($customernos))
{
foreach ($customernos as $data) {
        $message.="<tr><td>".$x."</td><td>" . $data['customerno'] . "</td><td>" . $data['customercompany'] . "</td><td>".$data['smsleft']."</td></tr>";
        $x++;
}
}
else
{
    $message.="<tr><td colspan='4'>No Customers Pending</td></tr>";    
}

$message.="</table>";

$display = "<!DOCTYPE html>
<html>
    <head>
        <title></title>
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
            <h4>Dear Elixia Team,<span style='margin-left:500px;font-weight:normal;'>Total Number of tasks </span><span style='font-weight:bold'>$x</span></h4>
            <p>List of customer who have sms left less than 50 :</p><br>";
$display.=$message;
$display.="</div>
    </body>
</html>";

$subject = "Low SMS Count Report";

$toArr = array('support@elixiatech.com','mihir@elixiatech.com');
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