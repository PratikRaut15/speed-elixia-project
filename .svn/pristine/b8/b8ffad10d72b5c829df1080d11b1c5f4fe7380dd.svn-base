<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$RELATIVE_PATH_DOTS = "../../../../";
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
$reportId = speedConstants::REPORT_DAILY_COMPLIANCE_REPORT; //16- id
$today = new DateTime();
$reportDate = $today->sub(new DateInterval('P1D'));
$date = $reportDate->format(speedConstants::DEFAULT_DATE);
$timestamp = strtotime($date);
$serverPath = "http://www.speed.elixiatech.com";
//$serverPath = "http://localhost/elixiaspeed_test";
$customerno = 473;
$cronm = new CronManager();
$db = new DatabaseManager();
$objCustomer = new CustomerManager();
//<editor-fold defaultstate="collapsed" desc="Report & Time Specific Users">
$objReportUser = new stdClass();
$objReportUser->reportId = $reportId;
$objReportUser->reportTime = $today->format('H');
//$objReportUser->reportTime = 9;
$objUserManager = new UserManager();
$users = $objUserManager->getUsersForReport($objReportUser);
$customerUserArray = cronCustomerUsers($users);
$todaysdate = date('Y-m-d H:i:s');
$download = $serverPath . "/modules/download/report.php?q=dailycompliance-pdf";
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
                    $userGroups = $objUserManager->get_groups_fromuser($customer, $user['userid']);
                    $arrGroups = array();
                    if (isset($userGroups) && !empty($userGroups)) {
                        foreach ($userGroups as $group) {
                            $arrGroups[] = $group->groupid;
                        }
                    }
                    $vehicleManager = new VehicleManager($customer);
                    $vehicles = $vehicleManager->get_all_vehicles_by_group_withusermapping($arrGroups, $isWarehouse = 1, $user['roleid'], $user['userid']);
                    if (isset($vehicles) && !empty($vehicles)) {
                        foreach ($vehicles as $vehicle) {
                            $compliancePdf = '';
                            $PdfUrl = $download . "-" . $customer . "-" . $user['userkey'] . "-$timestamp&v=";
                            $compliancePdf = '<td><a href="' . $PdfUrl . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                            $complianceReport .= '<tr><td>' . $vehicle->vehicleno . '</td>' . $compliancePdf . '</tr>';
                        }
                    }
                    $link = 'Please Click <a href="' . $download . $customer . '-' . $user['userkey'] . '-' . $timestamp . '">here</a> to download Daily Compliance Report.';
                    $message = file_get_contents('../../../emailtemplates/customer/473/dailycompliance_report_template.html');
                    $message = str_replace("{{REPORT_DATE}}", $date, $message);
                    $message = str_replace("{{REALNAME}}", $user['realname'], $message);
                    $message = str_replace("{{DATA_ROWS}}", $complianceReport, $message);
                    //$customer['emailid'] = "software@elixiatech.com";
                    if (!empty($user['email'])) {
                        $strCCMailIds = "";
                        $strBCCMailIds = "sanketsheth@elixiatech.com";
                        $subject = "Daily Compliance Report - " . $date;
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
