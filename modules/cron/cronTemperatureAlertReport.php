<?php

require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
$reportId = speedConstants::REPORT_TEMPERATURE;
$alertId = speedConstants::ALERT_TEMPERATURE;

$intervalArr = array(30, 60);
$today = new DateTime();
$reportDate = $today->sub(new DateInterval('P1D'));
$date = $reportDate->format(speedConstants::DEFAULT_DATE);
$serverPath = "http://speed.elixiatech.com";
$download = $serverPath . "/modules/download/report.php?q=";
$routehistpath = $serverPath . "/modules/reports/reports.php";
//<editor-fold defaultstate="collapsed" desc="Report & Time Specific Users">
$objReportUser = new stdClass();
$objReportUser->reportId = $reportId;
$objReportUser->reportTime = $today->format('H');
$objUserManager = new UserManager();

$users = $objUserManager->getUsersForAlertReport($objReportUser);
$customerUserArray = cronCustomerUsers($users);
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
                    $fetchSpecificVehicles = 0;
                    if ($user['userrole'] == 'Custom') {
                        $fetchSpecificVehicles = 1;
                    }
                    $wh_class = '';
                    $veh_class = '';
                    $humidity_class = "";
                    $message = "";
                    $tableRows = "";
                    $temperatureReportStr = '';
                    $temperatureWarehouseReportStr = '';
                    $temperatureHumidityReportStr = '';
                    $forHour = (isset($user['iterativeReportHour']) && $user['iterativeReportHour'] > 0) ? $user['iterativeReportHour'] : 0;
                    $placehoders['{{REALNAME}}'] = $user['realname'];
                    $placehoders['{{REPORT_DATE}}'] = $reportDate->format(speedConstants::DEFAULT_DATE);
                    $placehoders['{{CUSTOMER}}'] = $customer;
                    $placehoders['{{ENCODEKEY}}'] = sha1($user['userkey']);

                    $today = new DateTime();
                    if ($user['reportTime'] == $objReportUser->reportTime) {
                        $fromTime = $today->modify('-' . $user['reportTime'] . ' hours');
                    } else {
                        $fromTime = $today->modify('-' . $forHour . ' hours');
                    }
                    $subject = "Temperature Compliance Lapse Report || Period: " . $fromTime->format('d-m-Y') . ", " . $fromTime->format('H:00') . " to " . date('H:00');
                    $today = new DateTime();
                    $timestamp = strtotime($today->format('Y-m-d H:i:s'));
                    $placehoders['{{REPORT_DATE}}'] = $fromTime->format('d-m-Y H:00') . ' To ' . date('d-m-Y H:00');

                    $userGroups = $objUserManager->get_groups_fromuser($customer, $user['userid']);
                    $arrGroups = array();
                    if (isset($userGroups) && !empty($userGroups)) {
                        foreach ($userGroups as $group) {
                            $arrGroups[] = $group->groupid;
                        }
                    }
                    $objComQueManager = new ComQueueManager();
                    $requestAlert = new stdClass();
                    $requestAlert->enddate = date('Y-m-d H:i:s');
                    if ($user['reportTime'] == $objReportUser->reportTime) {
                        $fromTime = $today->modify('-' . $user['reportTime'] . ' hour');
                    } else {
                        $fromTime = $today->modify('-' . $user['iterativeReportHour'] . ' hour');
                    }
                    $requestAlert->startdate = $fromTime->format('Y-m-d H:i:s');
                    $requestAlert->customerno = $customer;
                    $requestAlert->type = $alertId;
                    foreach ($intervalArr AS $interval) {
                        $temperatureReport = '';
                        $temperatureWarehouseReport = '';
                        $temperatureHumidityReport = '';
                        $requestAlert->interval = $interval;
                        if ($customer_details->use_tracking) {
                            $vehicleManager = new VehicleManager($customer);
                            $vehicles = $vehicleManager->get_all_vehicles_by_group($arrGroups, $isWarehouse = 0, $fetchSpecificVehicles, $user['userid']);
                            if (isset($vehicles) && !empty($vehicles)) {
                                foreach ($vehicles as $vehicle) {
                                    $requestAlert->vehicleid = $vehicle->vehicleid;
                                    $comQData = $objComQueManager->getTemperatureAlertHist($requestAlert);
                                    $grp_name = ($vehicle->groupname != null) ? $vehicle->groupname : 'Not Allocated';
                                    if ($customer_details->temp_sensors >= 1 && !empty($comQData)) {
                                        $temperatureReport .= '<tr><td>' . $vehicle->vehicleno . '</td><td>' . $comQData['sensorName'] . '</td><td>' . $grp_name . '</td><td>' . $comQData['count'] . '</td></tr>';
                                    }
                                }
                            }
                        } else {
                            $veh_class = " style='display:none' ";
                        }
                        if ($temperatureReport != '') {
                            $temperatureReportInt = '<tr><th colspan="4">Conflicted For ' . $interval . ' Minutes</th></tr>';
                            $temperatureReportStr .= $temperatureReportInt . $temperatureReport;
                        }
                        if ($customer_details->use_warehouse) {
                            $vehicleManager = new VehicleManager($customer);
                            $vehicles = $vehicleManager->get_all_vehicles_by_group($arrGroups, $isWarehouse = 1, $fetchSpecificVehicles, $user['userid']);
                            if (isset($vehicles) && !empty($vehicles)) {
                                foreach ($vehicles as $vehicle) {
                                    $requestAlert->vehicleid = $vehicle->vehicleid;
                                    $comQData = $objComQueManager->getTemperatureAlertHist($requestAlert);
                                    $grp_name = ($vehicle->groupname != null) ? $vehicle->groupname : 'Not Allocated';
                                    if ($customer_details->temp_sensors >= 1 && !empty($comQData)) {
                                        $temperatureWarehouseReport .= '<tr><td>' . $vehicle->vehicleno . '</td><td>' . $comQData['sensorName'] . '</td><td>' . $grp_name . '</td><td>' . $comQData['count'] . '</td></tr>';
                                    }
                                    if ($customer_details->use_humidity == 1 && !empty($comQData)) {
                                        $wh_class = " style='display:none' ";
                                        $temperatureHumidityReport .= '<tr><td>' . $vehicle->vehicleno . '</td><td>' . $comQData['sensorName'] . '</td><td>' . $grp_name . '</td><td>' . $comQData['count'] . '</td></tr>';
                                    }
                                }
                            }
                        } else {
                            $wh_class = " style='display:none' ";
                        }
                        if ($temperatureWarehouseReport != '') {
                            $temperatureWarehouseReportInt = '<tr><th colspan="4">Conflicted For ' . $interval . ' Minutes</th></tr>';
                            $temperatureWarehouseReportStr .= $temperatureWarehouseReportInt . $temperatureWarehouseReport;
                        }
                        if ($temperatureHumidityReport != '') {
                            $temperatureHumidityReportInt = '<tr><th colspan="4">Conflicted For ' . $interval . ' Minutes</th></tr>';
                            $temperatureHumidityReportStr .= $temperatureHumidityReportInt . $temperatureHumidityReport;
                        }
                    }

                    if ($temperatureReportStr != '' || $temperatureWarehouseReportStr != '') {
                        if ($temperatureReportStr == '') {
                            $temperatureReportStr = "<tr><td colspan='4'>Data Not Available</td></tr>";
                        }
                        if ($temperatureWarehouseReportStr == '') {
                            $temperatureWarehouseReportStr = "<tr><td colspan='4'>Data Not Available</td></tr>";
                        }
                        if ($temperatureHumidityReportStr == '') {
                            $humidity_class = " style='display:none' ";
                        }
                        $html = file_get_contents('../emailtemplates/cronTemperatureAlertReport.html');
                        $placehoders['{{DATA_ROWS}}'] = $temperatureReportStr;
                        $placehoders['{{DATA_WAREHOUSE_ROWS}}'] = $temperatureWarehouseReportStr;
                        $placehoders['{{WH_CLASS}}'] = $wh_class;
                        $placehoders['{{VEH_CLASS}}'] = $veh_class;

                        $placehoders['{{DATA_HUMIDITY_ROWS}}'] = $temperatureHumidityReportStr;
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
?>