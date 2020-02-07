<?php

include_once '../../lib/bo/DailyReportManager.php';
include_once '../../lib/bo/GeofenceManager.php';
include_once '../../lib/bo/PointLocationManager.php';
include_once 'files/dailyreport.php';
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
$drm = new DailyReportManager(0);
$devices = $drm->pulldevices_dailyreport_night();
if (isset($devices)) {
  $reports = array();
  $update_arr = array();
  $ids = array();
  foreach ($devices as $device) {
    $customerno = $device->customerno;
    $vehicleid = $device->vehicleid;
    $date = date("Y-m-d");
    $Data = $drm->get_daily_report_mysql_night_firstodo($date, $customerno, $vehicleid);
    if ($Data != 0) {
      $update_arr[$customerno][$vehicleid] = $Data['update'];
    }
    /**/
  }
  if (!empty($update_arr)) {
    try {
      $drm->set_daily_report_mysql_night_firstodo($update_arr, $date);
    }
    catch (PDOException $e) {
      $Bad = 0;
    }
  }
}