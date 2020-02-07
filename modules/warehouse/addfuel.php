<?php
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/bo/VehicleManager.php';

$vehicleid = $_POST['vehicleid'];
$fuelstorrage = $_POST['fuelstorrage'];
$average = $_POST['average'];
$customerno = $_POST['customerno'];
$userid = $_POST['userid'];
$sdate = $_POST['sdate'];
$stime = $_POST['stime'];
$fueltank = $_POST['fueltank'];
$_SESSION['customerno'] = $customerno;
$_SESSION['userid'] = $userid;


$vehiclemanager = new VehicleManager($_SESSION['customerno']);
$vehiclemanager->addFuel($vehicleid,$fuelstorrage,$average,$userid,$sdate,$stime,$fueltank);
?>
