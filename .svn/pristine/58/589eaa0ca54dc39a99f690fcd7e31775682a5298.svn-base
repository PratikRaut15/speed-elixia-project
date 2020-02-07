<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
$dailydate = date('Y-m-d', strtotime("- 1 day"));
$reportDate = new DateTime($dailydate);
$firstMailerTime = date('Y-m-d 11:00:00');
$lastMailerTime = date('Y-m-d H:m:s');
$customerno = 64;
$groupid = 0;
$userid = 0;
$useMaintenance = 1;
$useHierarchy = 1;
$objUserManager = new UserManager();
$groupmanager = new GroupManager($customerno);
$objVehicleMgr = new VehicleManager($customerno);
$users = $objUserManager->getAllusersForCustomer($customerno);
//prettyPrint($users);
//die();
//fuelReimbursementAlert.html
if (isset($users) && !empty($users)) {
    $allEmail = implode(',', array_map(function ($entry) {
        if (isset($entry['email']) && $entry['email'] != ' ') {
            return $entry['email'];
        }
    }, $users));
    $allEmail = str_replace(',,', ',', $allEmail);
    //echo $allEmail;die();
    $html = file_get_contents('../emailtemplates/fuelReimbursementAlert.html');
    /* Send Email To Branch User and Regional And Zonal Users in CC*/
    $message = '';
    $message .= $html;
    $attachmentFilePath = '';
    $attachmentFileName = '';
    $subject = 'Fuel Reimbursement Alert';
    $mailid = 'kanade.akash@mahindra.com';
    //$CCEmail = ';
    $CCEmail = 'ANTHONY.MALCOM@mahindra.com, CHOGALE.SAMEER@mahindra.com,mrudang.vora@elixiatech.com,shrikants@elixiatech.com';
    $BCCEmail = $allEmail; //'software@elixiatech.com';
    //$mailid = $thisuser->email;
    //die();
    $isMailSent = sendMailUtil(array($mailid), $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 1);
    if (isset($isMailSent)) {
        echo $message;
    } else {
        echo "Fail";
    }
}
if (strtotime($lastMailerTime) < strtotime($firstMailerTime)) {
    echo "FirstCall";
    if (isset($users) && !empty($users)) {
        foreach ($users as $thisuser) {
            $thisuser = (object) $thisuser;
            //$thisuser->phone = '9021844677';
            if (isset($thisuser->phone) && $thisuser->phone != '') {
                $smsMessage = "New policy for Fuel Reimbursement, All office vehicle shall be allowed a maximum limit of Rs. 9000/- (Nine Thousand Rupees Only) per Month as limit for Fuel Expenses. The claim shall be paid at actual or Rs. 9000/- whichever is lesser, the process remains same. Please check email.\r\nMMFSL- I&S HO";
                $response = '';
                $thisuser->phoneno = $thisuser->phone;
                //echo $thisuser->phoneno;
                if (isset($thisuser->phoneno) && !empty($thisuser->phoneno)) {
                    //echo $smsMessage;
                    $isSMSSent = sendSMSUtil(array($thisuser->phoneno), $smsMessage, $response);
                    $moduleid = 1;
                    if ($isSMSSent == 1) {
                        echo 'SMS Sent';
                        $objCustomer = new CustomerManager();
                        $smsId = $objCustomer->sentSmsPostProcess($customerno, array($thisuser->phoneno), $message, $response, $isSMSSent, $userid, 0, $moduleid);
                    } else {
                        echo "SMS Fail";
                    }
                }
            }
            //die();
        }
    }
} else {
    echo "SecondCall";
}
//</editor-fold>