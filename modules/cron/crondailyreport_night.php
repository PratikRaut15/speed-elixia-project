<?php

include_once '../../lib/bo/DailyReportManager.php';
include_once '../../lib/bo/GeofenceManager.php';
include_once '../../lib/bo/PointLocationManager.php';
include_once 'files/dailyreport.php';

set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
//$dailyReportDeleteDate = date("Y-m-d", strtotime("-2 days"));

$drm = new DailyReportManager(0);

$devices = $drm->pulldevices_dailyreport_night();

if (isset($devices)) {

    $reports = array();
    $update_arr = array();
    $ids = array();
    foreach ($devices as $device) {
        $customerno = $device->customerno;
        $vehicleid = $device->vehicleid;
        $date = date("Y-m-d", strtotime("-1 days"));
        $Data = $drm->get_daily_report_mysql_night($date, $customerno, $vehicleid);
        if ($Data != 0) {
            $reports[$customerno][] = $Data['data'];
            $update_arr[$customerno][$vehicleid] = $Data['update'];
        }
        /**/
    }
    if (!empty($reports)) {
        foreach ($reports as $customerno => $customer_report) {
            $path = "sqlite:../../customer/$customerno/reports/dailyreport.sqlite";
            echo "<br/>$path<br/>";
            try {
                $db = new PDO($path);
                $db->exec('BEGIN IMMEDIATE TRANSACTION');
                foreach ($customer_report as $thisreport) {
                    foreach ($thisreport as $report) {
                        TRANSACTIONG_UPDATE_NIGHT($report, $db);
                    }
                }
                $db->exec('COMMIT TRANSACTION');
            } catch (PDOException $e) {
                $Bad = 0;
            }
        }
        /*Note - DailyReport Table For Perticular Date Is Deleted From crondailyreport_by_limit_new.php hence not require to delete it again*/
        //$drm->reset_dailyreport($update_arr, $dailyReportDeleteDate);
    }
}