<?php

//  cronInactiveVehicleList


if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../../../";
}

include_once $RELATIVE_PATH_DOTS . "lib/autoload.php";
include_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
$cm = new CronManager();
$vm = new VehicleManager(2);
$customerList = speedConstants::SP_ALLANASONS_TRANSPORTER;
$objCustomer = new stdClass();
$objCustomer->customerno = $customerList;
$customerArr = $cm->getCustomerList($objCustomer);


$message = '';
/*
if (isset($customerArr) && !empty($customerArr)) {
    foreach ($customerArr AS $customerno) {
        $message .= '<br><br><table><tr><th colspan="3">' . $customerno['customercompany'] . ' (' . $customerno['customerno'] . ')</th></tr>';
        $vehicleArr = $vm->team_getvehiclesforrtd_all_inactive(0, 0, $customerno['customerno']);
        $message .= '<tr><th>Sr No</th><th>Vehicle No</th><th>Last Active On</th></tr>';
        if (isset($vehicleArr) && !empty($vehicleArr)) {
            $i = 1;
            foreach ($vehicleArr AS $vehicle) {
                $message .= '<tr><td>' . $i . '</td><td>' . $vehicle->vehicleno . '</td><td>' . date('d-m-Y H:i', strtotime($vehicle->lastupdated)) . '</td></tr>';
                $i++;
            }
        } else {
            $message .= '<tr><td colspan="3">No Vehicle Inactive</td></tr>';
        }
        $message .= '</table>';
    }
} */
$date = strtotime(date('Y-m-d'));
$message.= BASE_PATH.'/modules/download/report.php?q=inactive-xls-984-347702850-'.$date;
//$message.= 'http://localhost/elixia_speed/modules/download/report.php?q=inactive-xls-984-347702850-1573862400'; 
//prettyPrint($message);
//die();
if ($message != '') {
    $email_path = "../../../emailtemplates/customer/984/alertMailTemplate.html";
    if (file_exists($email_path)) {
        $emailmessage = file_get_contents($email_path);
        $emailmessage = str_replace("{{LINK}}", $message, $emailmessage);
    }
//    echo $emailmessage; die();
    $CCEmail = '';
    // $BCCEmail = '';
    $BCCEmail = 'arvindt@elixiatech.com,support@elixiatech.com';
    $attachmentFilePath = '';
    $attachmentFileName = '';
    $subject = 'Inactive Vehicle List For Date : ' . date('d-m-Y');
    // $isMailSent = sendMailUtil(array('arvindt@elixiatech.com'), $CCEmail, $BCCEmail, $subject, $emailmessage, $attachmentFilePath, $attachmentFileName, 1);
   $isMailSent = sendMailUtil(array('reashaikh@allana.com','jmahashabde@allana.com,aarizvi@allana.com'), $CCEmail, $BCCEmail, $subject, $emailmessage, $attachmentFilePath, $attachmentFileName, 1);
    if (isset($isMailSent)) {
        echo $emailmessage;
    }
}
