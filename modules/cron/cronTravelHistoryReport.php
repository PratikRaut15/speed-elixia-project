<?php
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
$reportId = speedConstants::REPORT_TRAVEL_HISTORY;
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
        $customer_details = $objCustomerManager->getcustomerdetail_byid($customer);
        $vehicleManager = new VehicleManager($customer);
        foreach ($customerDetails as $userDetails) {
            foreach ($userDetails as $user) {
                if ($user['email'] != '') {
                    $message = "";
                    $tableRows = "";
                    $travelHistoryReport = '';
                    $placehoders['{{REALNAME}}'] = $user['realname'];
                    $placehoders['{{REPORT_DATE}}'] = $reportDate->format(speedConstants::DEFAULT_DATE);
                    $placehoders['{{CUSTOMER}}'] = $customer;
                    $placehoders['{{ENCODEKEY}}'] = sha1($user['userkey']);
                    $subject = "Travel History Report For  " . $reportDate->format(speedConstants::DEFAULT_DATE);
                    $timestamp = strtotime($today->format(speedConstants::DEFAULT_DATE));
                    $userGroups = $objUserManager->get_groups_fromuser($customer, $user['userid']);
                    $arrGroups = array();
                    if (isset($userGroups) && !empty($userGroups)) {
                        foreach ($userGroups as $group) {
                            $arrGroups[] = $group->groupid;
                        }
                    }

                    if ($customer_details->use_tracking) {
                        $vehicleManager = new VehicleManager($customer);
                        $vehicles = $vehicleManager->get_all_vehicles_by_group($arrGroups, $isWarehouse = 0);
                        if (isset($vehicles) && !empty($vehicles)) {
                            foreach ($vehicles as $vehicle) {
                                $travelHistoryPdf = $travelHistoryXls = '';
                                $travelHistoryPdfUrl = $download . "travel-pdf-$customer-" . $user['userkey'] . "-$timestamp&v=";
                                $travelHistoryXlsUrl = $download . "travel-xls-$customer-" . $user['userkey'] . "-$timestamp&v=";
                                $travelHistoryPdf = '<td><a href="' . $travelHistoryPdfUrl . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                $travelHistoryXls = '<td><a href="' . $travelHistoryXlsUrl . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                $grp_name = ($vehicle->groupname != null) ? $vehicle->groupname : 'Not Allocated';
                                $routehisttd = "<td style='text-align:center;'><a href='$routehistpath?userkey=" . SHA1($user['userkey']) . "&vehicleid=$vehicle->vehicleid&date=$date&report=view' target='_blank'>View</a></td>";
                                $travelHistoryReport .= '<tr><td>' . $vehicle->vehicleno . '</td><td>' . $grp_name . '</td>' . $travelHistoryPdf . $travelHistoryXls . $routehisttd . '</tr>';
                            }
                        }
                    }

                    if ($travelHistoryReport != '') {
                        $html = file_get_contents('../emailtemplates/cronTravelHistoryReport.html');
                        $placehoders['{{DATA_ROWS}}'] = $travelHistoryReport;
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
