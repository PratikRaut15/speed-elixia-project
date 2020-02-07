<?php
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
$reportId = speedConstants::REPORT_TRANSACTIONAL;
$today = new DateTime();
$reportDate = new DateTime();
$reportDate = $reportDate->sub(new DateInterval('P1D'));
$date = $reportDate->format(speedConstants::DEFAULT_DATE);
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
                if ($user['email'] != '' && $user['userrole'] == "Administrator") {
                    $tableRows = "";
                    $placehoders['{{REALNAME}}'] = $user['realname'];
                    $placehoders['{{REPORT_DATE}}'] = $reportDate->format(speedConstants::DEFAULT_DATE);
                    $subject = "Sms Transactions For  " . $reportDate->format(speedConstants::DEFAULT_DATE);
                    $message = '';
                    $html = file_get_contents('../emailtemplates/cronTransactionalReport.html');
                    $teammgr = new TeamManager();
                    $detail = $teammgr->getComHist($customer);
                    $sr = 1;
                    if (isset($detail) && !empty($detail)) {
                        foreach ($detail as $d) {
                            $tableRows .= "<tr><td>" . $sr . "</td><td>" . $d['phone'] . "</td><td>" . $d['message'] . "</td><td>" . $d['timesent'] . "</td></tr>";
                            $sr++;
                        }
                    } else {
                        $tableRows .= "<tr><td colspan='4'>Data Not Available</td></tr>";
                    }
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
