<?php
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/bo/VehicleManager.php';

$unitno = $_POST['unitno'];
$customerno = $_POST['customerno'];
$status = $_POST['status'];
$userid = $_POST['userid'];
if($status == 2)
{
    $command ="STARTV";
}else{
    $command = "STOPV";
}
$_SESSION['customerno'] = $customerno;



$vehiclemanager = new VehicleManager($_SESSION['customerno']);
$vehiclemanager->immobilise_vehicle($unitno,$customerno,$command,$userid);
?>
