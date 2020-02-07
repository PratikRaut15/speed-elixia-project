<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    $RELATIVE_PATH_DOTS = "../../../../";
    require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
    require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';

    $reportId = speedConstants::REPORT_WEEKLY_COMPLIANCE_REPORT; //17- id
    $today = new DateTime();
    $reportDate = $today->sub(new DateInterval('P1D'));
    $date = $reportDate->format(speedConstants::DEFAULT_DATE);
    $timestamp = strtotime($date);
    $EndDate = $date;

    $sdate = new DateTime($EndDate);
    $sdate->sub(new DateInterval('P7D'));
    $StartDate = $sdate->format('Y-m-d');

    $serverPath = "http://www.speed.elixiatech.com";
    //$serverPath = "http://loalhost/elixiaspeed_test";
    $customerno = 473;
    $cronm = new CronManager();
    $db = new DatabaseManager();
    $objCustomer = new CustomerManager();
    //<editor-fold defaultstate="collapsed" desc="Report & Time Specific Users">
    $objReportUser = new stdClass();
    $objReportUser->reportId = $reportId;
    $objReportUser->reportTime = $today->format('H');
    //$objReportUser->reportTime = 11;
    $objUserManager = new UserManager();
    $users = $objUserManager->getUsersForReport($objReportUser);
    $customerUserArray = cronCustomerUsers($users);
    $todaysdate = date('Y-m-d H:i:s');
    $download = $serverPath . "/modules/download/report.php?q=weeklycompliance-pdf";
    $complianceReport = '';
    if (isset($customerUserArray) && !empty($customerUserArray)) {
        foreach ($customerUserArray as $customer => $customerDetails) {
            $timezone = $objCustomer->timezone_name_cron(speedConstants::IST_TIMEZONE, $customer);
            date_default_timezone_set('' . $timezone . '');
            foreach ($customerDetails as $userDetails) {
                foreach ($userDetails as $user) {
                    if ($user['email'] != '') {
                        $message = "";
                        $tableRows = "";
                        $complianceReport = '';
                        $compliancePdf = '';
                        $link = 'Please Click <a href="' . $download . "-" . $customer . '-' . $user['userkey'] . '-' . $timestamp . '">here</a> to download Weekly Compliance Report.';
                        $message = file_get_contents('../../../emailtemplates/customer/473/weeklycompliance_report_template.html');
                        $reportStartDate = date('d-m-Y', strtotime($StartDate));
                        $reportEndDate = date('d-m-Y', strtotime($EndDate));
                        $reportDate = "(" . $reportStartDate . " To " . $reportEndDate . ")";
                        $message = str_replace("{{REPORT_DATE}}", $reportDate, $message);
                        $message = str_replace("{{REALNAME}}", $user['realname'], $message);
                        $message = str_replace("{{DOWNLOADLINK}}", $link, $message);
                        //echo $message;
                        //die();
                        //$customer['emailid'] = "software@elixiatech.com";
                        if (!empty($user['email'])) {
                            $strCCMailIds = "";
                            $strBCCMailIds = "mrudang.vora@elixiatech.com,sanketsheth@elixiatech.com";
                            $subject = "Weekly Compliance Report - " . $reportDate;
                            $attachmentFilePath = "";
                            $attachmentFileName = "";
                            $isTemplatedMessage = 1;
                            $isemailsent = sendMailUtil(array($user['email']), $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
                            if ($isemailsent == 1) {
                                echo $message . "<br/>";
                            }
                            $objEmail = new stdClass();
                            $objEmail->email = $user['email'];
                            $objEmail->subject = $subject;
                            $objEmail->message = htmlentities(htmlspecialchars($message, ENT_QUOTES));
                            $objEmail->vehicleid = 0;
                            $objEmail->userid = $user['userid'];
                            $objEmail->type = 2;
                            $objEmail->moduleid = speedConstants::MODULE_VTS;
                            $objEmail->customerno = $customerno;
                            $objEmail->isMailSent = $isemailsent;
                            $objEmail->today = $todaysdate;
                            $emailId = $objCustomer->insertCustomerEmailLog($objEmail);
                        }
                    }
                }
            }
        }
    }
?>


