<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '256M');
set_time_limit(0);
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
include_once 'files/dailyreport.php';
$drm = new DailyReportManager(0);
$customerno = 64;
$date = $_GET['date'];
$unitno = 9803865;
$Bad = 1;
$reports = array();
$ids = array();
class VODatacap {
}
if ($Bad != 0) {
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
    if (file_exists($location)) {
        //echo $location;
        $Data = DataFromSqlite($location);
        if ($Data != 0) {
            if (count($Data) > 0) {
                $um = new UnitManager($customerno);
                $acdata = $um->getacinvertval($unitno);
                $acinvertval = $acdata['0'];
                $acsensor = $acdata['1'];
                $overspeed_limit = 91;
                $device = new VODatacap();
                $device->unitno = $unitno;
                $device->vehicleid = 18895;
                $device->customerno = $customerno;
                $device->uid = 7294;
                $device->fuel_balance = 0;
                $reports[$customerno][] = DailyReport($device, $date, $Data, $overspeed_limit, $acinvertval, $acsensor);
            }
        } else {
            $Bad = 0;
        }
    }
}
if ($Bad != 0 && !empty($reports)) {
    foreach ($reports as $customer_report) {
        $customerno = $customer_report[0]['customerno'];
        $path = "sqlite:../../customer/$customerno/reports/dailyreport.sqlite";
        if ($Bad != 0) {
            try {
                $db = new PDO($path);
                $db->exec('BEGIN IMMEDIATE TRANSACTION');
                $datecheck = array();
                foreach ($customer_report as $report) {
                    TRANSACTIONG($report, $db, 1);
                }
                $db->exec('COMMIT TRANSACTION');
            } catch (PDOException $e) {
                $Bad = 0;
            }
        } else {
            $message = "Error while Pushing Data into sqlite for report ids - " . implode(',', $ids);
            //sendMail($email, $subject, $message);
            break;
        }
    }
    echo "DailyReport Updated ";
} else {
    $message = "Error while fetching data from sqlite for reportids - " . implode(',', $ids);
    //sendMail($email, $subject, $message);
}
?>