<?php
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
$reportId = speedConstants::REPORT_SMS_CONSUMED_DETAILS;
$today = new DateTime();
$reportDate = new DateTime();
$reportDate = $reportDate->sub(new DateInterval('P1D'));
$date = $reportDate->format(speedConstants::DEFAULT_DATE);
//<editor-fold defaultstate="collapsed" desc="Report & Time Specific Users">
$objReportUser = new stdClass();
$objReportUser->reportId = $reportId;
$objReportUser->reportTime = $today->format('H');
//$objReportUser->reportTime = 11;
$objUserManager = new UserManager();
$users = $objUserManager->getUsersForReport($objReportUser);
$customerUserArray = cronCustomerUsers($users);
$serverPath = "http://www.speed.elixiatech.com";
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
        $smsleft =$customer_details->smsleft;
        $customercompany = $customer_details->customercompany;
        $consume = $objCustomerManager->getSMSConsumedDetails($customer);
        $smsconsumecount  =count($consume);

        foreach ($customerDetails as $userDetails) {
            $smsconsumePdf ='';  $smsconsumeXls= '';
            foreach ($userDetails as $user) {
                $realname = $user['realname'];
                if ($user['email'] != '') {
                    $timestamp = strtotime($today->format(speedConstants::DEFAULT_DATE));
                    $userkey = $user['userkey'];
                    $smsconsumePdfUrl = $download . "smsconsume-pdf-$customer-" . $userkey. "-$timestamp";
                    $smsconsumeXlsUrl = $download . "smsconsume-xls-$customer-" . $userkey. "-$timestamp";
                    $smsconsumePdf = '<td><a href="' . $smsconsumePdfUrl.'" target="_blank">Download</a></td>';
                    $smsconsumeXls = '<td><a href="' . $smsconsumeXlsUrl.'" target="_blank">Download</a></td>';
                    $tableRows = "";
                    $placehoders['{{REALNAME}}'] = $user['realname'];
                    $placehoders['{{CUSTOMER}}'] = $customer;
                    $placehoders['{{REPORT_DATE}}'] = $reportDate->format(speedConstants::DEFAULT_DATE);
                    $subject = "Send Sms Details  " . $reportDate->format(speedConstants::DEFAULT_DATE);
                    $message = '';
                    $html = file_get_contents('../emailtemplates/cronSendCustomerSmsEmailDetailsReport.html');
                    $tableRows .="";

                    $tableRows .="<table border='1'><tr><th>Customer No</th><th>Customer Name</th><th>SMS Consume</th><th>SMS Left</th><th>Csv Download</th><th>Pdf Download</th></tr>";
                    $tableRows .="<tr><td>" . $customer . "</td><td>".$customercompany."</td><td>".$smsconsumecount."<td>".$smsleft."</td>".$smsconsumeXls." ".$smsconsumePdf."</tr>";
                    $tableRows .="</table>";

                    if ($tableRows != '') {
                        $placehoders['{{DATA_ROWS}}'] = $tableRows;
                        foreach ($placehoders as $key => $val) {
                            $html = str_replace($key, $val, $html);
                        }
                        $message .= $html;

                        if ($message != '') {
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
}
