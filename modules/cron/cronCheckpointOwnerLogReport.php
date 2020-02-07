<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
$reportId = speedConstants::REPORT_CHECKPOINT_OWNER_LOG;
$todaysDate = new DateTime();
$reportDate = new DateTime();
$reportDate->sub(new DateInterval('P1D'));
$date = $reportDate->format(speedConstants::DEFAULT_DATE);
$serverPath = "http://www.speed.elixiatech.com";
//$serverPath = "http://localhost/speed";
$download = $serverPath . "/modules/download/report.php?q=";
$routehistpath = $serverPath . "/modules/reports/reports.php";


//<editor-fold defaultstate="collapsed" desc="Report & Time Specific Users">
$objReportUser = new stdClass();
$objReportUser->reportId = $reportId;
$objReportUser->reportTime = $reportDate->format('H');
$objUserManager = new UserManager();
$users = $objUserManager->getUsersForReport($objReportUser);
$customerUserArray = cronCustomerUsers($users);
//</editor-fold>
//
//<editor-fold defaultstate="collapsed" desc="Customer-User Loop To Send Mail">
$objCustomerManager = new CustomerManager();
if (isset($customerUserArray) && !empty($customerUserArray)) {
    foreach ($customerUserArray as $customer => $customerDetails) {
        $timezone = $objCustomerManager->timezone_name_cron(speedConstants::IST_TIMEZONE, $customer);
        date_default_timezone_set('' . $timezone . '');

        foreach ($customerDetails as $userDetails) {
            foreach ($userDetails as $user) {
                if ($user['email'] != '') {
                    $message = "";
                    $tableRows = "";
                    $placehoders['{{REALNAME}}'] = $user['realname'];
                    $placehoders['{{REPORT_DATE}}'] = $reportDate->format(speedConstants::DEFAULT_DATE);
                    $placehoders['{{CUSTOMER}}'] = $customer;
                    $placehoders['{{ENCODEKEY}}'] = sha1($user['userkey']);
                    $subject = "Checkpoint Owner SMS / Email Log Report For ". $reportDate->format(speedConstants::DEFAULT_DATE)." (Customer No - ".$customer.")";

                    $timestamp = strtotime($reportDate->format(speedConstants::DEFAULT_DATE));

                    /*Summary Report URL*/
                    $checkpointOwnerLogPdfUrl = $download."checkpointOwnerLog-pdf-".$customer."-".$user['userkey']."-".$timestamp;
                    $checkpointOwnerLogXlsUrl = $download."checkpointOwnerLog-xls-".$customer."-".$user['userkey']."-".$timestamp;

                    /*Report Links*/
                    $checkpointOwnerLogPdf = '<a href="'.$checkpointOwnerLogPdfUrl.'" target="_blank">Download</a>';
                    $checkpointOwnerLogXls = '<a href="' . $checkpointOwnerLogXlsUrl . '" target="_blank">Download</a>';


                    $tableRows .= "<tr>";
                    $tableRows .= "<td>Checkpoint Owner Log Report</td>";
                    $tableRows .= "<td>" . $checkpointOwnerLogPdf . "</td>";
                    $tableRows .= "<td>" . $checkpointOwnerLogXls . "</td>";
                    $tableRows .= "</tr>";

                    if ($tableRows != '') {
                        $html = file_get_contents('../emailtemplates/cronCheckpointOwnerLogReport.html');
                        $placehoders['{{DATA_ROWS}}'] = $tableRows;
                        foreach ($placehoders as $key => $val) {
                            $html = str_replace($key, $val, $html);
                        }
                        $message .= $html;
                        $attachmentFilePath = '';
                        $attachmentFileName = '';
                        $CCEmail = '';
                        $BCCEmail = '';
                        //$user['email'] = 'sshrikanth@elixiatech.com';
                        $isMailSent = sendMailUtil(array($user['email']), $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 1);
                        if (isset($isMailSent)) {
                            echo $message;
                        }
                    }
                }
            }
        }
    }
}
//</editor-fold>
