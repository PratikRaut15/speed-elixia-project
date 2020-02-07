<?php

include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/GroupManager.php';
include_once '../../lib/system/utilities.php';
include_once '../../lib/bo/UnitManager.php';
include_once "../../lib/comman_function/reports_func.php";
if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
}

class VODatacap {
    
}

function getdate_IST() {
    $ServerDate_IST = strtotime(date("Y-m-d H:i:s"));
    return $ServerDate_IST;
}

//probity
function getvehicle($vehicleid) {
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicle = $vehiclemanager->get_vehicle_with_driver($vehicleid);
    if ($vehicle->isdeleted == '1')
        header("location:vehicle.php");

    return $vehicle;
}

//probity
function getbatch($vehicleid) {
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicle = $vehiclemanager->get_vehicle_with_batch($vehicleid);
    if(isset($vehicle->isdeleted)){
        if ($vehicle->isdeleted == '1'){
            header("location:vehicle.php");
        }
    }    
    return $vehicle;
}

function getvehicles($kind = '') {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_all_vehicles_with_drivers_by_groupname($kind);
    return $vehicles;
}

function getvehicles_warehouse() {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_all_warehouse();
    return $vehicles;
}

//used
function getworkmaster() {
    $groupmanager = new GroupManager($_SESSION['customerno']);
    $groups = $groupmanager->get_work_master();
    return $groups;
}

function getunits() {
    $unitmanager = new UnitManager($_SESSION['customerno']);
    $units = $unitmanager->getunits();
    return $units;
}?>