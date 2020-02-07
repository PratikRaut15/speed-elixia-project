<?php
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
$reportId = speedConstants::REPORT_STOPPAGE_ANALYSIS;
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

//prettyPrint($users);die();
$objCustomerManager = new CustomerManager();
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
                    $stoppageAnalysisReport = '';
                    $placehoders['{{REALNAME}}'] = $user['realname'];
                    $placehoders['{{REPORT_DATE}}'] = $reportDate->format(speedConstants::DEFAULT_DATE);
                    $placehoders['{{CUSTOMER}}'] = $customer;
                    $placehoders['{{ENCODEKEY}}'] = sha1($user['userkey']);
                    $subject = "Stoppage Analysis Report For  " . $reportDate->format(speedConstants::DEFAULT_DATE);
                    $timestamp = strtotime($today->format(speedConstants::DEFAULT_DATE));
                    $userGroups = $objUserManager->get_groups_fromuser($customer, $user['userid']);
                    $arrGroups = array();
                    if (isset($userGroups) && !empty($userGroups)) {
                        foreach ($userGroups as $group) {
                            $arrGroups[] = $group->groupid;
                        }
                    }
                    $allPDF = $download . "stoppage-pdf-$customer-" . $user['userkey'] . "-$timestamp&v=-1";
                    $allXLS = $download . "stoppage-xls-$customer-" . $user['userkey'] . "-$timestamp&v=-1";
                    $stoppageAnalysisReport .= '<tr>
                    <td>All Vehicles</td>
                    <td>Not Allocated</td>
                    <td><a href="' . $allPDF . '" target="_blank">Download</a></td>
                    <td><a href="' . $allXLS . '" target="_blank">Download</a></td>
                    </tr>';
                    if ($customer_details->use_tracking) {
                        $vehicleManager = new VehicleManager($customer);
                        $vehicles = $vehicleManager->get_all_vehicles_by_group($arrGroups, $isWarehouse = 0);
                        if (isset($vehicles) && !empty($vehicles)) {
                            foreach ($vehicles as $vehicle) {
                                $unitNo = $vehicle->unitno;
                                $userdate = $reportDate->format(speedConstants::DATE_Ymd);
                                $stoppageAnalysisPdf = $stoppageAnalysisXls = '';
                                $stoppagePdfUrl = $download . "stoppage-pdf-$customer-" . $user['userkey'] . "-$timestamp&v=";
                                $stoppageXlsUrl = $download . "stoppage-xls-$customer-" . $user['userkey'] . "-$timestamp&v=";

                                $location = "../../customer/$customer/unitno/$unitNo/sqlite/$userdate.sqlite";
                                if (file_exists($location)) {
                                    $stoppagePdf = '<td><a href="' . $stoppagePdfUrl . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                    $stoppageXls = '<td><a href="' . $stoppageXlsUrl . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                } else {
                                    $stoppagePdf = '<td>Vehicle Inactive On - ' . $reportDate->format(speedConstants::DEFAULT_DATE) . '</td>';
                                    $stoppageXls = '<td></td>';
                                }

                                $grp_name = ($vehicle->groupname != null) ? $vehicle->groupname : 'Not Allocated';
                                $stoppageAnalysisReport .= '<tr><td>' . $vehicle->vehicleno . '</td><td>' . $grp_name . '</td>' . $stoppagePdf . $stoppageXls . '</tr>';
                            }
                        }
                    }
                    if ($stoppageAnalysisReport != '') {
                        $html = file_get_contents('../emailtemplates/cronStoppageAnalysisReport.html');
                        $placehoders['{{DATA_ROWS}}'] = $stoppageAnalysisReport;
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