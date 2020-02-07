<?php
$RELATIVE_PATH_DOTS = "../../";
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
require_once "../../lib/comman_function/reports_func.php";
$reportId = speedConstants::REPORT_TEMPERATUR_MIN_MAX_SUMMARY;
$today = new DateTime();
$reportDate = $today->sub(new DateInterval('P1D'));
$date = $reportDate->format(speedConstants::DEFAULT_DATE);
$serverPath = "http://www.speed.elixiatech.com";
//$serverPath = "http://localhost/speed";
$download = $serverPath . "/modules/download/report.php?q=";
//<editor-fold defaultstate="collapsed" desc="Report & Time Specific Users">
$objReportUser = new stdClass();
$objReportUser->reportId = $reportId;
$objReportUser->reportTime = $today->format('H');
$objUserManager = new UserManager();
$users = $objUserManager->getUsersForReport($objReportUser);
$customerUserArray = cronCustomerUsers($users);
//</editor-fold>
//<editor-fold defaultstate="collapsed" desc="Customer-User Loop To Send Mail">
$objCustomerManager = new CustomerManager();
$objUserManager = new UserManager();
if (isset($customerUserArray) && !empty($customerUserArray)) {
    foreach ($customerUserArray as $customer => $customerDetails) {
        $timezone = $objCustomerManager->timezone_name_cron(speedConstants::IST_TIMEZONE, $customer);
        date_default_timezone_set('' . $timezone . '');
        $customer_details = $objCustomerManager->getcustomerdetail_byid($customer);
        foreach ($customerDetails as $userDetails) {
            foreach ($userDetails as $user) {
                if ($user['email'] != '') {
                    $message = "";
                    $tableRows = "";
                    $placehoders['{{REALNAME}}'] = $user['realname'];
                    $placehoders['{{REPORT_DATE}}'] = $today->format(speedConstants::DEFAULT_DATE);
                    $placehoders['{{CUSTOMER}}'] = $customer;
                    $placehoders['{{ENCODEKEY}}'] = sha1($user['userkey']);
                    $subject = "Temperature Min Max Summary Data Report For  " . $today->format(speedConstants::DEFAULT_DATE);
                    $timestamp = strtotime($today->format(speedConstants::DEFAULT_DATE));
                    /* Summary Report URL */
                    $temperatureMinMaxSummaryPdfUrl = $download . "temperatureMinMaxSummary-pdf-" . $customer . "-" . $user['userkey'] . "-" . $timestamp;
                    $temperatureMinMaxSummaryXlsUrl = $download . "temperatureMinMaxSummary-xls-" . $customer . "-" . $user['userkey'] . "-" . $timestamp;
                    /* Report Links */
                    $temperatureMinMaxSummaryPdf = '<a href="' . $temperatureMinMaxSummaryPdfUrl . '" target="_blank">Download</a>';
                    $temperatureMinMaxSummaryXls = '<a href="' . $temperatureMinMaxSummaryXlsUrl . '" target="_blank">Download</a>';
                    $tableRows .= "<tr>";
                    $tableRows .= "<td>Temperature Min Max Summary Data Report</td>";
                    $tableRows .= "<td>" . $temperatureMinMaxSummaryPdf . "</td>";
                    $tableRows .= "<td>" . $temperatureMinMaxSummaryXls . "</td>";
                    $tableRows .= "</tr>";
                    if ($tableRows != '') {
                        $html = file_get_contents('../emailtemplates/cronTemperatureMinMaxSummary.html');
                        $placehoders['{{DATA_ROWS}}'] = $tableRows;
                        foreach ($placehoders as $key => $val) {
                            $html = str_replace($key, $val, $html);
                        }
                        $message .= $html;
                        $attachmentFilePath = '';
                        $attachmentFileName = '';
                        $CCEmail = '';
                        $BCCEmail = 'mrudang.vora@elixiatech.com';
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
