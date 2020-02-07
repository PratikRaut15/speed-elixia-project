<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(-1);

/*
  Name		-       cron_additional_data_missing
  Description 	-	To send email to [support@elixiatech.com,sanketsheth1@gmail.com] which will contain list of customer who do not have CRM.

 */

include_once "../../lib/system/utilities.php";
include_once '../../lib/bo/CustomerManager.php';
include_once '../../lib/bo/TeamManager.php';

$cstmerno = 2;
$detail = array();
$customerno = new CustomerManager($cstmerno);
$customernos = $customerno->getallcustomerno();
$teammgr = new TeamManager();
$count=0;
$message = "<table border='1'><tr><th>Customer No</th><th>Customer Name</th><th>Type</th><th>Details Not Filled</th></tr>";
foreach ($customernos as $data) {
    $type1 = $teammgr->getAllContactPerson($data['customerno'],1);
    $type2 = $teammgr->getAllContactPerson($data['customerno'],2);
    $type3 = $teammgr->getAllContactPerson($data['customerno'],3);
    if(isset($type1) || isset($type2) || isset($type3)){
        $message.="<tr><td>".$data['customerno']."</td><td>".$data['customercompany']."</td><td></td><td></td></tr>";
        if(isset($type1)){
            $message.="<tr><td></td><td></td><td>Owner</td><td>";
            foreach ($type1 as $key=>$data){
                if(empty($data)){
                    if($key=='person_name'){
                        $message.="Person Name,";
                        $count++;
                    }elseif ($key=="cp_email1") {
                        $message.="Primary Email Id,";
                        $count++;
                    }elseif ($key=="cp_phone1") {
                        $message.="Primary Phone Number";
                        $count++;
                    }
                }
            }
            $message.="</td></tr>";
        }
        if(isset($type2)){
            $message.="<tr><td></td><td></td><td>Accounts</td><td>";
            foreach ($type2 as $key=>$data){
                if(empty($data)){
                    if($key=='person_name'){
                        $message.="Person Name,";
                        $count++;
                    }elseif ($key=="cp_email1") {
                        $message.="Primary Email Id,";
                        $count++;
                    }elseif ($key=="cp_phone1") {
                        $message.="Primary Phone Number,";
                        $count++;
                    }
                }
            }
            $message.="</td></tr>";
        }
        if(isset($type3)){
            $message.="<tr><td></td><td></td><td>Co-Ordinator</td><td>";
            foreach ($type3 as $key=>$data){
                if(empty($data)){
                    if($key=='person_name'){
                        $message.="Person Name";
                        $count++;
                    }elseif ($key=="cp_email1") {
                        $message.="Primary Email Id";
                        $count++;
                    }elseif ($key=="cp_phone1") {
                        $message.="Primary Phone Number";
                        $count++;
                    }
                }
            }
            $message.="</td></tr>";
        }
        $message.="<tr><td>&nbsp</td></tr>";
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
            <h4>Dear Elixia Team,<span style='margin-left:500px;font-weight:normal;'>Total Number of tasks </span><span style='font-weight:bold'>$count</span></h4>
            <p>Consider following list of Customer with incomplete additional details :</p>
            <br>
            ";
$display.=$message;

$display.="<br>
        </div>
    </body>
</html>";

//echo $display;
//die();


$subject = "Missing Additional Details for Customers";

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
