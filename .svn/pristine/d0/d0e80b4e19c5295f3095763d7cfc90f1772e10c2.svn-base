<?php
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
$reportId = speedConstants::REPORT_TEMPERATURE;
$today = new DateTime();
$reportDate = $today->sub(new DateInterval('P1D'));
$date = $reportDate->format(speedConstants::DEFAULT_DATE);
$serverPath = "http://speed.elixiatech.com";
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
                    $fetchSpecificVehicles = 0;
                    if ($user['userrole'] == 'Custom') {
                        $fetchSpecificVehicles = 1;
                    }
                    $wh_class = '';
                    $veh_class = '';
                    $humidity_class = "";
                    $message = "";
                    $tableRows = "";
                    $temperatureReport = '';
                    $temperatureWarehouseReport = '';
                    $temperatureHumidityReport = '';
                    $forHour = (isset($user['iterativeReportHour']) && $user['iterativeReportHour'] > 0) ? $user['iterativeReportHour'] : 0;
                    $placehoders['{{REALNAME}}'] = $user['realname'];
                    $placehoders['{{REPORT_DATE}}'] = $reportDate->format(speedConstants::DEFAULT_DATE);
                    $placehoders['{{CUSTOMER}}'] = $customer;
                    $placehoders['{{ENCODEKEY}}'] = sha1($user['userkey']);
                    if ($forHour > 0) {
                        $today = new DateTime();
                        if ($user['reportTime'] == $objReportUser->reportTime) {
                            $fromTime = $today->modify('-' . $user['reportTime'] . ' hours');
                            $forHour = $user['reportTime'];
                        } else {
                            $fromTime = $today->modify('-' . $forHour . ' hours');
                        }
                        $subject = "Temperature Report || Period: " . $fromTime->format('d-m-Y') . ", " . $fromTime->format('H:00') . " to " . date('H:00');
                        $today = new DateTime();
                        $timestamp = strtotime($today->format('Y-m-d H:i:s'));
                        $placehoders['{{REPORT_DATE}}'] = $fromTime->format('d-m-Y H:00') . ' To ' . date('d-m-Y H:00');
                    } else {
                        $subject = "Temperature Report For  " . $reportDate->format(speedConstants::DEFAULT_DATE);
                        $timestamp = strtotime($today->format(speedConstants::DEFAULT_DATE));
                    }
                    $userGroups = $objUserManager->get_groups_fromuser($customer, $user['userid']);
                    $arrGroups = array();
                    if (isset($userGroups) && !empty($userGroups)) {
                        foreach ($userGroups as $group) {
                            $arrGroups[] = $group->groupid;
                        }
                    }

                    if ($customer_details->use_tracking) {
                        $vehicleManager = new VehicleManager($customer);
                        $vehicles = $vehicleManager->get_all_vehicles_by_group($arrGroups, $isWarehouse = 0, $fetchSpecificVehicles, $user['userid']);
                        if (isset($vehicles) && !empty($vehicles)) {
                            foreach ($vehicles as $vehicle) {
                                $temperaturePdf = $temperatureXls = '';
                                $temperaturePdfUrl = $download . "temperature-pdf-$customer-" . $user['userkey'] . "-$timestamp&forHour=" . $forHour . "&v=";
                                $temperatureXlsUrl = $download . "temperature-xls-$customer-" . $user['userkey'] . "-$timestamp&forHour=" . $forHour . "&v=";
                                $temperaturePdf = '<td><a href="' . $temperaturePdfUrl . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                $temperatureXls = '<td><a href="' . $temperatureXlsUrl . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                $grp_name = ($vehicle->groupname != null) ? $vehicle->groupname : 'Not Allocated';
                                if ($customer_details->temp_sensors >= 1) {
                                    $temperatureReport .= '<tr><td>' . $vehicle->vehicleno . '</td><td>' . $grp_name . '</td>' . $temperaturePdf . $temperatureXls . '</tr>';
                                }

                            }
                        }
                    } else {
                        $veh_class = " style='display:none' ";
                    }

                    if ($customer_details->use_warehouse) {
                        $vehicleManager = new VehicleManager($customer);
                        $vehicles = $vehicleManager->get_all_vehicles_by_group($arrGroups, $isWarehouse = 1, $fetchSpecificVehicles, $user['userid']);
                        if (isset($vehicles) && !empty($vehicles)) {
                            foreach ($vehicles as $vehicle) {
                                $temperaturePdf = $temperatureXls = '';
                                $temperaturePdfUrl = $download . "wh_temperature-pdf-$customer-" . $user['userkey'] . "-$timestamp&forHour=" . $forHour . "&v=";
                                $temperatureXlsUrl = $download . "wh_temperature-xls-$customer-" . $user['userkey'] . "-$timestamp&forHour=" . $forHour . "&v=";
                                $temperaturePdf = '<td><a href="' . $temperaturePdfUrl . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                $temperatureXls = '<td><a href="' . $temperatureXlsUrl . $vehicle->vehicleid . '" target="_blank">Download</a></td>';

                                $tempHumidityPdfUrl = $download . "wh_temperaturehumidity-pdf-$customer-" . $user['userkey'] . "-$timestamp&v=";
                                $tempHumidityXlsUrl = $download . "wh_temperaturehumidity-xls-$customer-" . $user['userkey'] . "-$timestamp&v=";

                                $temperatureHumidityPdf = '<td><a href="' . $tempHumidityPdfUrl . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                $temperatureHumidityXls = '<td><a href="' . $tempHumidityXlsUrl . $vehicle->vehicleid . '" target="_blank">Download</a></td>';


                                $grp_name = ($vehicle->groupname != null) ? $vehicle->groupname : 'Not Allocated';
                                if ($customer_details->temp_sensors >= 1) {
                                    $temperatureWarehouseReport .= '<tr><td>' . $vehicle->vehicleno . '</td><td>' . $grp_name . '</td>' . $temperaturePdf . $temperatureXls . '</tr>';
                                }
                                if ($customer_details->use_humidity == 1) {
                                    $wh_class = " style='display:none' ";
                                    $temperatureHumidityReport .= '<tr><td>' . $vehicle->vehicleno . '</td><td>' . $grp_name . '</td>' . $temperatureHumidityPdf . $temperatureHumidityXls . '</tr>';
                                }

                            }
                        }
                    } else {
                        $wh_class = " style='display:none' ";
                    }

                    if ($temperatureReport != '' || $temperatureWarehouseReport != '') {
                        if ($temperatureReport == '') {
                            $temperatureReport = "<tr><td colspan='4'>Data Not Available</td></tr>";
                        }
                        if ($temperatureWarehouseReport == '') {
                            $temperatureWarehouseReport = "<tr><td colspan='4'>Data Not Available</td></tr>";
                        }
                        if ($temperatureHumidityReport == '') {
                            $humidity_class = " style='display:none' ";
                        }
                        $html = file_get_contents('../emailtemplates/cronTemperatureReport.html');
                        $placehoders['{{DATA_ROWS}}'] = $temperatureReport;
                        $placehoders['{{DATA_WAREHOUSE_ROWS}}'] = $temperatureWarehouseReport;
                        $placehoders['{{WH_CLASS}}'] = $wh_class;
                        $placehoders['{{VEH_CLASS}}'] = $veh_class;

                        $placehoders['{{DATA_HUMIDITY_ROWS}}'] = $temperatureHumidityReport;
                        $placehoders['{{HUMIDITY_CLASS}}'] = $humidity_class;
                        foreach ($placehoders as $key => $val) {
                            $html = str_replace($key, $val, $html);
                        }
                        $message .= $html;
                        $attachmentFilePath = '';
                        $attachmentFileName = '';

                        if ($customer_details->customerno == '664') {
                            $CCEmail = speedConstants::FERRERO_664_CC_EMAIL;
                        } else {
                            $CCEmail = '';
                        }

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
