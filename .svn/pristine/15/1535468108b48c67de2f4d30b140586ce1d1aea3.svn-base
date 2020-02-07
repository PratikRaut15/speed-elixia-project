<?php

require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
$reportId = speedConstants::REPORT_SUMMARY;
$today = new DateTime();
$reportDate = $today->sub(new DateInterval('P1D'));
$date = $reportDate->format(speedConstants::DEFAULT_DATE);
$serverPath = "http://www.speed.elixiatech.com";
//$serverpath = "http://localhost/speed";
$download = $serverPath . "/modules/download/report.php?q=";
$routehistpath = $serverPath . "/modules/reports/reports.php";
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
        foreach ($customerDetails as $userDetails) {
            foreach ($userDetails as $user) {
                if ($user['email'] != '') {
                    $userGroups = $objUserManager->get_groups_fromuser($customer, $user['userid']);
                    $arrGroups = array();
                    if (isset($userGroups) && !empty($userGroups)) {
                        foreach ($userGroups as $group) {
                            $arrGroups[] = $group->groupid;
                        }
                    }
                    if (!empty($arrGroups)) {
                        $groups = implode(',', $arrGroups);
                    } else {
                        $groups = 0;
                    }
                    $message = "";
                    $tableRows = "";
                    $placehoders['{{REALNAME}}'] = $user['realname'];
                    $placehoders['{{REPORT_DATE}}'] = $reportDate->format(speedConstants::DEFAULT_DATE);
                    $placehoders['{{CUSTOMER}}'] = $customer;
                    $placehoders['{{ENCODEKEY}}'] = sha1($user['userkey']);
                    $subject = "Summary Report For  " . $reportDate->format(speedConstants::DEFAULT_DATE);

                    $timestamp = strtotime($today->format(speedConstants::DEFAULT_DATE));
                    /* Summary Report URL */
                    $summaryPdfUrl = $download . "summary-pdf-" . $customer . "-" . $user['userkey'] . "-" . $timestamp . "-" . $groups;
                    $summaryXlsUrl = $download . "summary-xls-" . $customer . "-" . $user['userkey'] . "-" . $timestamp . "-" . $groups;
                    /* OverSpeed Report URL */
                    $overspeedPdfUrl = $download . "overspeed-pdf-" . $customer . "-" . $user['userkey'] . "-" . $timestamp;
                    $overspeedXlsUrl = $download . "overspeed-xls-" . $customer . "-" . $user['userkey'] . "-" . $timestamp;
                    /* Exception Report URL */
                    $exceptionPdfUrl = $download . "exception-pdf-" . $customer . "-" . $user['userkey'] . "-" . $timestamp;
                    $exceptionXlsUrl = $download . "exception-xls-" . $customer . "-" . $user['userkey'] . "-" . $timestamp;
                    /* Report Links */
                    $summaryPdf = '<a href="' . $summaryPdfUrl . '" target="_blank">Download</a>';
                    $summaryXls = '<a href="' . $summaryXlsUrl . '" target="_blank">Download</a>';
                    $overspeedPdf = '<a href="' . $overspeedPdfUrl . '" target="_blank">Download</a>';
                    $overspeedXls = '<a href="' . $overspeedXlsUrl . '" target="_blank">Download</a>';
                    $exceptionPdf = '<a href="' . $exceptionPdfUrl . '" target="_blank">Download</a>';
                    $exceptionXls = '<a href="' . $exceptionXlsUrl . '" target="_blank">Download</a>';

                    $tableRows .= "<tr>";
                    $tableRows .= "<td>Summary Report</td>";
                    $tableRows .= "<td>" . $summaryPdf . "</td>";
                    $tableRows .= "<td>" . $summaryXls . "</td>";
                    $tableRows .= "</tr>";
                    $tableRows .= "<tr>";
                    $tableRows .= "<td>Exception Report</td>";
                    $tableRows .= "<td>" . $overspeedPdf . "</td>";
                    $tableRows .= "<td>" . $exceptionPdf . "</td>";
                    $tableRows .= "</tr>";
                    $tableRows .= "<tr>";
                    $tableRows .= "<td>Overspeed Report</td>";
                    $tableRows .= "<td>" . $exceptionPdf . "</td>";
                    $tableRows .= "<td>" . $exceptionXls . "</td>";
                    $tableRows .= "</tr>";
                    if ($tableRows != '') {
                        $html = file_get_contents('../emailtemplates/cronOverAllSummaryReport.html');
                        $placehoders['{{DATA_ROWS}}'] = $tableRows;
                        foreach ($placehoders as $key => $val) {
                            $html = str_replace($key, $val, $html);
                        }
                        $message .= $html;
                        $attachmentFilePath = '';
                        $attachmentFileName = '';
                        $CCEmail = '';
                        $BCCEmail = '';
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
