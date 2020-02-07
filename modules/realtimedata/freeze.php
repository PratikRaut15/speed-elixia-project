<?php
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/bo/VehicleManager.php';
$unitno = $_POST['unitno'];
$customerno = $_POST['customerno'];
$userid = $_POST['userid'];
$action = $_REQUEST['action'];
if ($action == "unfreeze") {
    $vehiclemanager = new VehicleManager($customerno);
    $vehiclemanager->unfreezedvehicle($unitno, $customerno, $userid);
    echo "Ok";
    exit;
} elseif ($action == 'freezed') {
    $vehiclemanager = new VehicleManager($customerno);
    $vehiclemanager->freezedvehicle($unitno, $customerno, $userid);
    echo "Ok";
    exit;
} else {
    echo "No freeze actions";
    exit;
}
?>