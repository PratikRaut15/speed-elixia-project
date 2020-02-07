<?php

require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
$reportId = speedConstants::REPORT_GROUPWISE_TEMPERATURE;
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
                    if (isset($userGroups[0]->groupid) && $userGroups[0]->groupid == 0) {
                        $groupManager = new GroupManager($customer);
                        $userGroups = $groupManager->getallgroups_detail();
                    }
//                    prettyPrint($userGroups);
                    
                    if (isset($userGroups) && !empty($userGroups)) {
                        foreach ($userGroups as $group) {
                            $temperaturePdf = $temperatureXls = '';
                            $temperaturePdfUrl = $download . "grp_temperature-pdf-$customer-" . $user['userkey'] . "-$timestamp&forHour=" . $forHour . "&g=";
                            $temperatureXlsUrl = $download . "grp_temperature-xls-$customer-" . $user['userkey'] . "-$timestamp&forHour=" . $forHour . "&g=";
                            $temperaturePdf = '<td><a href="' . $temperaturePdfUrl . $group->groupid . '" target="_blank">Download</a></td>';
                            $temperatureXls = '<td><a href="' . $temperatureXlsUrl . $group->groupid . '" target="_blank">Download</a></td>';
                            if ($customer_details->temp_sensors >= 1) {
                                $temperatureReport .= '<tr><td>' . $group->groupname . '</td>' . $temperaturePdf . $temperatureXls . '</tr>';
                            }
                        }
                    }



                    if ($temperatureReport != '') {
                        if ($temperatureReport == '') {
                            $temperatureReport = "<tr><td colspan='3'>Data Not Available</td></tr>";
                        }
//                        if ($temperatureWarehouseReport == '') {
//                            $temperatureWarehouseReport = "<tr><td colspan='4'>Data Not Available</td></tr>";
//                        }
//                        if ($temperatureHumidityReport == '') {
//                            $humidity_class = " style='display:none' ";
//                        }
                        $html = file_get_contents('../emailtemplates/cronGroupwiseTemperatureReport.html');
                        $placehoders['{{DATA_ROWS}}'] = $temperatureReport;
//                        $placehoders['{{DATA_WAREHOUSE_ROWS}}'] = $temperatureWarehouseReport;
//                        $placehoders['{{WH_CLASS}}'] = $wh_class;
                        $placehoders['{{VEH_CLASS}}'] = $veh_class;

//                        $placehoders['{{DATA_HUMIDITY_ROWS}}'] = $temperatureHumidityReport;
//                        $placehoders['{{HUMIDITY_CLASS}}'] = $humidity_class;
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
