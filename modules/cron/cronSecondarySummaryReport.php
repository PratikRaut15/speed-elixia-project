<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
require '../sales/sales_function.php';
$reportId = speedConstants::REPORT_SECONDARY_SUMMARY;
$today = new DateTime();
$reportDate = new DateTime();
$reportDate = $reportDate->sub(new DateInterval('P1D'));
$date = $reportDate->format(speedConstants::DEFAULT_DATE);
//<editor-fold defaultstate="collapsed" desc="Report & Time Specific Users">
$objReportUser = new stdClass();
$objReportUser->reportId = $reportId;
$objReportUser->reportTime = $today->format('H');
//$objReportUser->reportTime = 15;
$objUserManager = new UserManager();
$users = $objUserManager->getUsersForReportSecondarySales($objReportUser);
$customerUserArray = cronCustomerUsers($users);
$serverpath = "http://www.speed.elixiatech.com";
//$serverpath = "http://localhost/elixiaspeed_test";
$download = $serverpath . "/modules/download/report.php?q=";
//</editor-fold>
//<editor-fold defaultstate="collapsed" desc="Customer-User Loop To Send Mail">
$objCustomerManager = new CustomerManager();
$objUserManager = new UserManager();
if (isset($customerUserArray) && !empty($customerUserArray)) {
    foreach ($customerUserArray as $customer => $customerDetails) {
        $timezone = $objCustomerManager->timezone_name_cron(speedConstants::IST_TIMEZONE, $customer);
        date_default_timezone_set('' . $timezone . '');
        $customer_details = $objCustomerManager->getcustomerdetail_byid($customer);
        $customercompany = $customer_details->customercompany;

        foreach ($customerDetails as $userDetails){
            $secsummaryXls = '';
            foreach ($userDetails as $user) {
                $realname = $user['realname'];
                if ($user['email'] != '') {
                    $timestamp = strtotime($today->format(speedConstants::DEFAULT_DATE));
                    $userkey = $user['userkey'];
                    $secsummaryXlsUrl = $download . "secsummary-xls-$customer-" . $userkey . "-$timestamp";
                    $secsummaryXls = '<td><a href="' . $secsummaryXlsUrl . '" target="_blank">Download</a></td>';
                    $tableRows = "";
                    $placehoders['{{REALNAME}}'] = $user['realname'];
                    $placehoders['{{CUSTOMER}}'] = $customer;
                    $placehoders['{{REPORT_DATE}}'] = $reportDate->format(speedConstants::DEFAULT_DATE);
                    $subject = "Secondary Sales Summary Report " . $reportDate->format(speedConstants::DEFAULT_DATE);
                    $message = '';
                    $html = file_get_contents('../emailtemplates/cronSecondarysales_summary.html');
                    $tableRows .="";

                    $tableRows .="<table border='1'><tr><th>Customer No</th><th>Customer Name</th><th>Csv Download</th></tr>";
                    $tableRows .="<tr><td>" . $customer . "</td><td>" . $customercompany . "</td>" . $secsummaryXls . "</tr>";
                    $tableRows .="</table>";

                    if ($tableRows != '') {
                        $placehoders['{{DATA_ROWS}}'] = $tableRows;
                        foreach ($placehoders as $key => $val) {
                            $html = str_replace($key, $val, $html);
                        }
                        $message .= $html;
                        if ($message != ''){
                            $attachmentFilePath = '';
                            $attachmentFileName = '';
                            $CCEmail = '';
                            $BCCEmail = '';
                            $isMailSent = sendMailUtil(array($user['email']), $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 1);
                            if ($isMailSent == 1) {
                                echo $message . "<br/>";
                            }

                            $todaysdate = date('Y-m-d H:i:s');
                            $objEmail = new stdClass();
                            $objEmail->email = $user['email'];
                            $objEmail->subject = $subject;
                            $objEmail->message = htmlentities(htmlspecialchars($message, ENT_QUOTES));
                            $objEmail->vehicleid = 0;
                            $objEmail->userid = $user['userid'];
                            $objEmail->type = 2;
                            $objEmail->moduleid = speedConstants::MODULE_VTS;
                            $objEmail->customerno = $customer;
                            $objEmail->isMailSent = $isMailSent;
                            $objEmail->today = $todaysdate;
                            $objCustomerManager = new CustomerManager();
                            $emailId = $objCustomerManager->insertCustomerEmailLog($objEmail);
                        }
                    }
                }
            }
        }
    }
}

