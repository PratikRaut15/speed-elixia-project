<?php
include_once '../../lib/bo/DailyReportManager.php';
include_once '../../lib/bo/GeofenceManager.php';
include_once '../../lib/bo/PointLocationManager.php';
include_once 'files/dailyreport.php';
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
define("DATEFORMAT_DMY", "dmy");
ini_set("max_execution_time", "3000");

$reportId = speedConstants::REPORT_NIGHT_TRAVELLING;
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
$today = new DateTime();
$reportDate = $today->sub(new DateInterval('P1D'));
$serverPath = "http://www.speed.elixiatech.com";
//$serverPath = "http://localhost/speed";
$download = $serverPath . "/modules/download/report.php?q=";
$date = $reportDate->format(speedConstants::DEFAULT_DATE);
$objReportUser = new stdClass();
$objReportUser->reportId = $reportId;
$objReportUser->reportTime = $today->format('H');
$objUserManager = new UserManager();
$users = $objUserManager->getUsersForReport($objReportUser);
$customerUserArray = cronCustomerUsers($users);
$objDeviceManager = new DeviceManager(0);
$drm = new DailyReportManager(0);
$objCustomerManager = new CustomerManager();
$objUserManager = new UserManager();

if (isset($customerUserArray) && !empty($customerUserArray)) {
    foreach ($customerUserArray as $customer => $customerDetails) {
        $timezone = $objCustomerManager->timezone_name_cron(speedConstants::IST_TIMEZONE, $customer);
        date_default_timezone_set('' . $timezone . '');
        $customer_details = $objCustomerManager->getcustomerdetail_byid($customer);
        $vehInactiveReport = $vehInactiveWarehouseReport = '';
        $x = 0;
        $nightTravellReport = '';
        $vehicleManager = new VehicleManager($customer);

        foreach ($customerDetails as $userDetails) {
            foreach ($userDetails as $user) {
                $message = '';
                $sDate = date('Y-m-d');
                $eDate = date('Y-m-d');
                $nightDriverDetails = $objDeviceManager->getNightDriveDetailsForReport($customer);
                $disp_start_date = $sDate . " " . $nightDriverDetails['start_time'];
                $disp_end_date = $eDate . " " . $nightDriverDetails['end_time'];

                if ($user['email'] != '') {
                    $placehoders['{{REALNAME}}'] = $user['realname'];
                    $placehoders['{{REPORT_DATE}}'] = $reportDate->format(speedConstants::DEFAULT_DATE);
                    $placehoders['{{CUSTOMER}}'] = $customer;
                    $placehoders['{{ENCODEKEY}}'] = sha1($user['userkey']);
                    $subject = "Night Travelling Report From " . $disp_start_date . " TO " . $disp_end_date . "";
                    $timestamp = strtotime($today->format(speedConstants::DEFAULT_DATE));
                    $devices = $drm->pulldevices_dailyreport_night_perCustomer($customer);

                    /*    foreach($devices  as $deviceK=>$deviceV){
                    $date       = $reportDate->format(speedConstants::DEFAULT_DATE);
                    $dateParam  = new DateTime($date);
                    $tableName  = 'A' . $dateParam->format(DATEFORMAT_DMY);
                    $location   = "../../customer/$customer/reports/dailyreport.sqlite";
                    $unitId     = $deviceV->uid;
                    }*/

                    $nightTravellPdf = $nightTravellXls = '';
                    $nightTravellPdfUrl = $download . "nightTravel-pdf-$customer-" . $user['userkey'] . "-$timestamp&v=";
                    $nightTravellXlsUrl = $download . "nightTravel-xls-$customer-" . $user['userkey'] . "-$timestamp&v=";
                    $nightTravellPdf = '<td><a href="' . $nightTravellPdfUrl . $user['userid'] . '" target="_blank">Download</a></td>';
                    $nightTravellXls = '<td><a href="' . $nightTravellXlsUrl . $user['userid'] . '" target="_blank">Download</a></td>';
                    $nightTravellReport = '<tr><td>Night Drive Travel Report</td>' . $nightTravellPdf . $nightTravellXls . '</tr>';

                    if ($nightTravellReport != '') {
                        $html = file_get_contents('../emailtemplates/cron_nightDrive_report.html');
                        $placehoders['{{DATA_ROWS}}'] = $nightTravellReport;

                        foreach ($placehoders as $key => $val) {
                            $html = str_replace($key, $val, $html);
                        }
                        $message .= $html;
                        $attachmentFilePath = '';
                        $attachmentFileName = '';
                        $CCEmail = '';
                        $BCCEmail = 'software@elixiatech.com';
                        //  echo $message;
                        $userEmail = array($user['email']);
                        $isMailSent = sendMailUtil($userEmail, $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 1);
                        if (isset($isMailSent) && $isMailSent == 1) {
                            echo $message;
                        }
                    }
                }
            }
        }
    }
}
