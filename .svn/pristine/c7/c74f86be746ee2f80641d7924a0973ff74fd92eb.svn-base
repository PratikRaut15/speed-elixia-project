<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(-1);

/*
  Name		-       cron_ledger_details_missing
  Description 	-	To send email to [accounts@elixiatech.com,sanketsheth1@gmail.com] which will contain list of customer who do not have ledger OR ledger not assigned to vehicle.

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
    $detail[$data['customerno']] = $teammgr->getLedgerMapCust($data['customerno']);
    $detail[$data['customerno']]['customercompany'] = $data['customercompany'];
}
$taskcount = 0;

$message = "<table><tr><th>Customer No</th><th>Customer Name</th><th>Count</th><th>Vehicles Not Mapped with Ledger</th></tr>";
foreach ($detail as $key => $data) {
    $Vehicles = array();
    $count1 = 0;
    if (sizeof($data) == 1) {
        $message.="<tr><td>" . $key . "</td><td>" . $data['customercompany'] . "</td><td>1</td><td>No ledger assigned.</td></tr>";
        $count1++;
    } elseif (sizeof($data) > 1) {
        $vehicleids = array();
        $vehicleids = $teammgr->getAllVehicleidForCustomer($key);
        $count = 0;
        foreach ($vehicleids as $data1) {
            $status = $teammgr->isLedgerPerVehicleId($data1['vehicleid'], $key);
            if ($status != TRUE) {
                $Vehicles[] = $data1['vehicleno'];
                $count++;
            }
        }

        $Vehicles['count'] = $count;
        if (!empty($Vehicles)) {
            if ($Vehicles['count'] > 0) {
                $message.="<tr><td>" . $key . "</td><td>" . $data['customercompany'] . "</td><td>" . $Vehicles['count'] . "</td><td>";
                foreach ($Vehicles as $k => $vcl) {
                    if ($k !== 'count') {
                        $message.=$vcl . ", ";
                    }
                }
                $message.="</td></tr>";
            }
        }
    }
    $taskcount+=$count1;
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
//                text-align: center;
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
            <h4>Dear Elixia Team,<span style='margin-left:500px;font-weight:normal;'>Total Number of tasks </span><span style='font-weight:bold'>$taskcount</span></h4>
            <p>Consider following list of Customer who do not have Ledger Details OR ledger not assigned to vehicle :</p>
            <br>";
$display.=$message;
$display.="</div>
    </body>
</html>";

//echo $display;
//die();
$subject = "Pending Ledger Details";

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