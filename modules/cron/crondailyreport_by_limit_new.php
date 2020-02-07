<?php

include_once '../../lib/bo/DailyReportManager.php';
include_once '../../lib/bo/GeofenceManager.php';
include_once '../../lib/bo/PointLocationManager.php';
include_once 'files/dailyreport.php';

set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
/* Set Timezone */
date_default_timezone_set("Asia/Calcutta");
$currentDate = new DateTime();
$dailyReportDeleteDate = date("Y-m-d", strtotime("-2 days"));

$drm = new DailyReportManager(0);

$devices = $drm->pulldevices_dailyreport();

if (isset($devices)) {
    $reports = array();
    $update_arr = array();
    $ids = array();
    foreach ($devices as $device) {
        $customerno = $device->customerno;
        $overspeed_limit = $device->overspeed_limit;
        $vehicleid = $device->vehicleid;
        $devicelat = $device->devicelat;
        $devicelong = $device->devicelong;
        // <editor-fold defaultstate="collapsed" desc="Update Ignition Running Time">
        $devicesdata = $drm->getalldevicesforignition_byVehicleid($vehicleid);
        if (isset($devicesdata) && !empty($devicesdata)) {
            $ignchgtime = $currentDate->format('Y-m-d h:i:s');
            if ($devicesdata->ignition == 1) {
                $drm->update_ignchgtime($ignchgtime, $vehicleid, $customerno);
            }
        }

        //</editor-fold>
        $date = date("Y-m-d", strtotime("-1 days"));
        $Data = $drm->get_daily_report_mysql($date, $customerno, $vehicleid, $device->fuel_balance, $devicelat, $devicelong);
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
                        TRANSACTIONG_NEW($report, $db);
                    }
                }
                $db->exec('COMMIT TRANSACTION');
            } catch (PDOException $e) {
                $Bad = 0;
            }
        }
        $drm->reset_dailyreport($update_arr, $dailyReportDeleteDate);
    }
}
