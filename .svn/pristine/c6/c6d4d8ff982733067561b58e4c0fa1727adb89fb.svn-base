<?php
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/bo/VehicleManager.php';

if($_POST['updatedriver']  == '1')
{
    $vehicleid = $_POST['vehicleid'];
    $customerno = $_POST['customerno'];
    $userid = $_POST['userid'];
    $driverid = $_POST['driver'];
    $olddriverid = $_POST['driverid'];
    $_SESSION['customerno'] = $customerno;
    $_SESSION['userid'] = $userid;


    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehiclemanager->addDriver($vehicleid,$userid,$driverid, $olddriverid);
}
else if($_POST['adddriver'] == '1')
{
    $vehicleid = $_POST['vehicleid'];
    $customerno = $_POST['customerno'];
    $userid = $_POST['userid'];
    $dname = $_POST['dname'];
    $dlic = $_POST['dlic'];
    $dphone = $_POST['dphone'];
   $olddriverid = $_POST['driverid'];
    $_SESSION['customerno'] = $customerno;
    $_SESSION['userid'] = $userid;


    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehiclemanager->addNewDriver($vehicleid,$userid,$dname,$dlic,$dphone, $olddriverid);
}

?>
