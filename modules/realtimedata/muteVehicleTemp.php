<?php
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/bo/VehicleManager.php';

$vehicledetails = new stdClass();

$vehicledetails->vehicleid = $_POST['vehcileid'];
$vehicledetails->temp = $_POST['temp'];
$vehicledetails->condition = $_POST['condition'];
$vehicledetails->userid =  $_POST['userid'];
$customerno = $_POST['customerno'];
$_SESSION['customerno'] = $customerno;

$vehiclemanager = new VehicleManager($_SESSION['customerno']);
$vehiclemanager->muteVehicleTemperature($vehicledetails);
?>
