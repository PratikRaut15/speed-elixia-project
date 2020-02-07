<?php
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
$reportId = speedConstants::REPORT_DIGITAL_HISTORY;
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
                    $custom = $objUserManager->get_custom($customer);
                    $customname = 'Digital';
                    if ($custom != null) {
                        if ($custom->usecustom == 1 && $custom->custom_id == 1 && $custom->customname != '') {
                            $customname = $custom->customname;
                        }
                    }

                    $wh_class = '';
                    $veh_class = '';

                    $message = "";
                    $tableRows = "";
                    $gensetReport = '';
                    $gensetWarehouseReport = '';

                    $placehoders['{{REALNAME}}'] = $user['realname'];
                    $placehoders['{{REPORT_DATE}}'] = $reportDate->format(speedConstants::DEFAULT_DATE);
                    $placehoders['{{CUSTOMER}}'] = $customer;
                    $placehoders['{{ENCODEKEY}}'] = sha1($user['userkey']);
                    $placehoders['{{DIGITAL}}'] = $customname;
                    $subject = "Digital History Report For  " . $reportDate->format(speedConstants::DEFAULT_DATE);
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
                                $gensetPdf = $gensetXls = '';
                                $gensetPdfUrl = $download . "genset-pdf-$customer-" . $user['userkey'] . "-$timestamp&v=";
                                $gensetXlsUrl = $download . "genset-xls-$customer-" . $user['userkey'] . "-$timestamp&v=";
                                $gensetPdf = '<td><a href="' . $gensetPdfUrl . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                $gensetXls = '<td><a href="' . $gensetXlsUrl . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                $grp_name = ($vehicle->groupname != null) ? $vehicle->groupname : 'Not Allocated';
                                if ($customer_details->use_ac_sensor >= 1) {
                                    $gensetReport .= '<tr><td>' . $vehicle->vehicleno . '</td><td>' . $grp_name . '</td>' . $gensetPdf . $gensetXls . '</tr>';
                                }

                            }
                        }
                    } else {
                        $veh_class = " style='display:none' ";
                    }

                    if ($customer_details->use_warehouse) {
                        $vehicleManager = new VehicleManager($customer);
                        $vehicles = $vehicleManager->get_all_vehicles_by_group($arrGroups, $isWarehouse = 1);
                        if (isset($vehicles) && !empty($vehicles)) {
                            foreach ($vehicles as $vehicle) {
                                $gensetPdf = $gensetXls = '';
                                $gensetPdfUrl = $download . "genset-pdf-$customer-" . $user['userkey'] . "-$timestamp&v=";
                                $gensetXlsUrl = $download . "genset-xls-$customer-" . $user['userkey'] . "-$timestamp&v=";
                                $gensetPdf = '<td><a href="' . $gensetPdfUrl . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                $gensetXls = '<td><a href="' . $gensetXlsUrl . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                $grp_name = ($vehicle->groupname != null) ? $vehicle->groupname : 'Not Allocated';
                                if ($customer_details->use_ac_sensor >= 1) {
                                    $gensetWarehouseReport .= '<tr><td>' . $vehicle->vehicleno . '</td><td>' . $grp_name . '</td>' . $gensetPdf . $gensetXls . '</tr>';
                                }


                            }
                        }
                    } else {
                        $wh_class = " style='display:none' ";
                    }

                    if ($gensetReport != '' || $gensetWarehouseReport != '') {
                        if($gensetReport == '') {
                            $gensetReport = "<tr><td colspan='4'>Data Not Available</td></tr>";
                        }
                        if($gensetWarehouseReport == '') {
                            $gensetWarehouseReport = "<tr><td colspan='4'>Data Not Available</td></tr>";
                        }

                        $html = file_get_contents('../emailtemplates/cronDigitalHistoryReport.html');
                        $placehoders['{{DATA_ROWS}}'] = $gensetReport;
                        $placehoders['{{DATA_WAREHOUSE_ROWS}}'] = $gensetWarehouseReport;
                        $placehoders['{{WH_CLASS}}'] = $wh_class;
                        $placehoders['{{VEH_CLASS}}'] = $veh_class;
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
