<?php

require_once '../../lib/system/utilities.php';
require_once '../../lib/bo/TyreSrnoManager.php';

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

function getTyreData() {
  $tyremanager = new TyreManager($_SESSION['customerno']);
  $data = $tyremanager->getTyreDetails();
  return $data;
}

function getFilterTyreData($vid) {
  $tyremanager = new TyreManager($_SESSION['customerno']);
  $data = $tyremanager->getTyreDetailsById($vid);
  return $data;
}

function EditTyreSrno($form) {
  $tyremanager = new TyreManager($_SESSION['customerno']);
  $data = $tyremanager->EditTyreSrnoDetails($form);
  return $data;
}

function upload_serialno($all_form) {
  $tyremanager = new TyreManager($_SESSION['customerno']);
  $data = $tyremanager->SaveSerialno($all_form);
  return $data;
}
