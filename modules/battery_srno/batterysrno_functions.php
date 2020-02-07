<?php

require_once '../../lib/system/utilities.php';
require_once '../../lib/bo/BatterySrnoManager.php';

if (!isset($_SESSION)) {
  session_start();
  if (!isset($_SESSION['timezone'])) {
    $_SESSION['timezone'] = 'Asia/Kolkata';
  }
  date_default_timezone_set('' . $_SESSION['timezone'] . '');
}

if (isset($_SESSION['lastvisit']) && (time() - $_SESSION['lastvisit'] > 1800)) {
  // last request was more than 30 minutes ago
  session_unset();     // unset $_SESSION variable for the run-time
  session_destroy();   // destroy session data in storage
  session_regenerate_id(true);
}

function getVehicledata($q) {
  $data = array();
  $devicemanager = new DeviceManager($_SESSION['customerno']);
  $devices = $devicemanager->maintenance_vehicles($q);
  if (isset($devices)) {
    foreach ($devices as $row) {
      $vehicle = new stdClass();
      $vehicle->vehicleno = $row->vehicleno;
      $vehicle->vehicleid = $row->vehicleid;
      $data[] = $vehicle;
    }
  }
  return $data;
}

function getBatteryVehicledata($q) {
  $data = array();
  $battmanager = new BatteryManager($_SESSION['customerno']);
  $devices = $battmanager->getNoBatteryVehicles($q);
  if (isset($devices)) {
    foreach ($devices as $row) {
      $vehicle = new stdClass();
      $vehicle->vehicleno = $row->vehicleno;
      $vehicle->vehicleid = $row->vehicleid;
      $data[] = $vehicle;
    }
  }
  return $data;
}

function getBatteryDetails() {
  $battmanager = new BatteryManager($_SESSION['customerno']);
  $data = $battmanager->getBatteryData();
  return $data;
}

function getsrno_details_byid($bmid) {
  $batt_mapid = GetSafeValueString($bmid, "long");
  $battmanager = new BatteryManager($_SESSION['customerno']);
  $data = $battmanager->getSrnobyid($batt_mapid);
  return $data;
}

function editbatt_srno($form) {
  $battmanager = new BatteryManager($_SESSION['customerno']);
  $data = $battmanager->Editbatt_srno($form);
  return $data;
}

function getFilteredBatteryDetails($vehid) {
  $battmanager = new BatteryManager($_SESSION['customerno']);
  $data = $battmanager->getBatteryData($vehid);
  return $data;
}

function addbatt_srno($form) {
  $battmanager = new BatteryManager($_SESSION['customerno']);
  $data = $battmanager->Addbatt_srno($form);
  return $data;
}

function upload_serialno($all_form) {
  $battmanager = new BatteryManager($_SESSION['customerno']);
  $data = $battmanager->SaveSerialno($all_form);
  return $data;
}
