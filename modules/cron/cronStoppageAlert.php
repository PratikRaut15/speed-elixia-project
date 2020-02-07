<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';
echo "<br/> Cron Start On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";
$cronManager = new CronManager();
$objUserManager = new UserManager();
$objReportUser = new stdClass();
$reportId = speedConstants::REPORT_STOPPAGE_ALERT;
$today = new DateTime();
$reportDate = $today;
$date = $reportDate->format(speedConstants::DEFAULT_DATE);
$objReportUser->reportId = $reportId;
$objReportUser->reportTime = $today->format('H');
$objUserManager = new UserManager();
$users = $objUserManager->getUsersForReport($objReportUser);
$customerUserArray = cronCustomerUsers($users);
//print_r($customerUserArray);
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
                        $subject = "Stoppage Alert Report From " . $fromTime->format('d-m-Y H:00') . ' To ' . date('d-m-Y H:00');
                        $today = new DateTime();
                        $timestamp = strtotime($today->format('Y-m-d H:i:s'));
                        $placehoders['{{REPORT_DATE}}'] = $fromTime->format('d-m-Y H:00') . ' To ' . date('d-m-Y H:00');
                    } else {
                        $subject = "Stoppage Alert Report For  " . $reportDate->format(speedConstants::DEFAULT_DATE);
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
                        $vehicles = $vehicleManager->getVehiclesForStoppageReport($arrGroups, $isWarehouse = 0, $fetchSpecificVehicles, $user['userid']);
                        if (isset($vehicles) && !empty($vehicles)) {
                            foreach ($vehicles as $vehicle) {
                                $grp_name = ($vehicle->groupname != null) ? $vehicle->groupname : 'Not Allocated';
                                //if ($vehicle->vehicleid != '11896') {
                                //continue;
                                //}
                                $travKm = 0;
                                $addToReport = 0;
                                $totalRunningTime = '';
                                $totalKmTravelled = '';
                                if (getstatus($vehicle->stoppage_flag, $vehicle->stoppage_transit_time, $vehicle->lastupdated_store) > 180) {
                                    $addToReport = 1;
                                    $totalRunningTime = getstatus($vehicle->stoppage_flag, $vehicle->stoppage_transit_time, $vehicle->lastupdated_store);
                                }
                                /*
                                if ($addToReport == 0 && getstatus($vehicle->stoppage_flag, $vehicle->ignontime, $vehicle->lastupdated_store) > 180) {
                                    $addToReport = 1;
                                    $totalRunningTime = getstatus($vehicle->stoppage_flag, $vehicle->ignontime, $vehicle->lastupdated_store);
                                }
                                */
                                $firstOdometer = getOdometer($fromTime->format('d-m-Y H:00'), $vehicle->unitno, $customer);
                                $lastOdometer = getOdometer(date('d-m-Y H:00'), $vehicle->unitno, $customer);
                                $travKm = calculateOdometerDistance($firstOdometer, $lastOdometer);
                                if (isset($travKm) && $travKm > 150) {
                                    $addToReport = 1;
                                    $totalKmTravelled = $travKm;
                                }
                                /* Dont send alert when odometer conflicts the reading. 400 (assumed value for max distance travelled)*/
                                if (isset($travKm) && abs($travKm) > 400) {
                                    $addToReport = 0;
                                }
                                if ($addToReport == 1) {
                                    $temperatureReport .= '<tr><td>' . $vehicle->vehicleno . '</td><td>' . $grp_name . '</td><td>' . $totalRunningTime . '</td><td>' . $travKm . '</td></tr>';
                                }
                            }
                        }
                    } else {
                        $veh_class = " style='display:none' ";
                    }
                    if ($temperatureReport != '') {
                        if ($temperatureReport == '') {
                            $temperatureReport = "<tr><td colspan='4'>Data Not Available</td></tr>";
                        }
                        $html = file_get_contents('../emailtemplates/cronStoppageAlerts.html');
                        $placehoders['{{DATA_ROWS}}'] = $temperatureReport;
                        $placehoders['{{VEH_CLASS}}'] = $veh_class;
                        foreach ($placehoders as $key => $val) {
                            $html = str_replace($key, $val, $html);
                        }
                        $message .= $html;
                        $attachmentFilePath = '';
                        $attachmentFileName = '';
                        $CCEmail = 'altafs@elixiatech.com,support@elixiatech.com';
                        $BCCEmail = '';
                        $user['email'] = 'shrikants@elixiatech.com';
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
function minutediff($StartTime, $EndTime) {
    $idleduration = strtotime($EndTime) - strtotime($StartTime);
    $minutes = floor($idleduration / 60);
    return $minutes;
}

function getstatus($stoppage_flag, $stoppage_transit_time, $lastupdated_store, $gpsfixed = null) {
    $isRunning = 0;
    $ServerIST_less1 = new DateTime();
    $ServerIST_less1->modify('-60 minutes');
    $lastupdated = new DateTime($lastupdated_store);
    if ($lastupdated < $ServerIST_less1) {
        $isRunning = 0;
    } elseif (isset($gpsfixed) && $gpsfixed == 'V') {
        $isRunning = 0;
    } else {
        $diff = minutediff($stoppage_transit_time, $lastupdated_store);
        if ($stoppage_flag == '0') {
            $isRunning = 0;
        } else {
            $isRunning = $diff;
        }
    }
    return $isRunning;
}

function getOdometer($date, $unitNo, $customerNo) {
    $datedmy = date('Y-m-d', strtotime($date));
    $date = date('Y-m-d H:s:i', strtotime($date));
    $location = "../../customer/$customerNo/unitno/$unitNo/sqlite/$datedmy.sqlite";
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $Query = "SELECT * FROM vehiclehistory where lastupdated >= '$date' Order by lastupdated ASC Limit 1 ";
        $result = $db->query($Query);
        if (isset($result) && $result != '') {
            foreach ($result as $row) {
                return $row['odometer'];
            }
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}

function calculateOdometerDistance($startOdometer, $endOdometer) {
    $distance = 0;
    $distance = (($endOdometer - $startOdometer) / 1000);
    return $distance;
}

echo "<br/> Cron Completed On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";
?>
