<?php
/*
    Name		-       cron_crm_not_allotted
    Description 	-	To send email to [support@elixiatech.com,sanketsheth1@gmail.com] which will contain list of customer who do not have CRM. 
 
 */
include_once "../../lib/system/utilities.php";
include_once '../../lib/bo/CustomerManager.php';

$customerno = 2;
$support = new CustomerManager($customerno);
$custno = $support->getCustNotAllotMngr();
$message="<!DOCTYPE html>
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
$message.="<p>List of customers who do not have CRM : </p><br>";
$message .= "<table border='1'><tr><th>Customer No.</th><th>Customer Name</th></tr>";
if(isset($custno))
{
    foreach ($custno as $data) {
        $message.="<tr><td>" . $data->customerno . "</td><td>" . $data->customername . "</td></tr>";
    }
}
else
{
        $message.="<tr><td colspan='2'>No Customers Pending</td></tr>";    
}
$message.="</table>";
$message.="<br>
        </div>
    </body>
</html>";
//echo $message; 
//die();

$subject = "CRM Allocation Report ";

$toArr = array('support@elixiatech.com','mihir@elixiatech.com');
$CCEmail = 'sanketsheth1@gmail.com';
$BCCEmail = '';
$attachmentFilePath='';
$attachmentFileName='';
$isSMSSent=sendMailUtil($toArr, $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName,1);
if($isSMSSent==1){
    echo "Sent";
}else{
    echo "Not Sent";
}
?>