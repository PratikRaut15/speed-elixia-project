<?php
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/bo/VehicleManager.php';

$unitno = $_POST['unitno'];
$customerno = $_POST['customerno'];
$_SESSION['customerno'] = $customerno;
$userid =  $_POST['userid'];


$vehiclemanager = new VehicleManager($_SESSION['customerno']);
$vehiclemanager->addBuzzer($unitno,$customerno,$userid);
?>
