<?php
include_once '../../lib/system/utilities.php';
include_once '../../lib/comman_function/reports_func.php';
include_once '../../lib/autoload.php';
if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
}

if (isset($_POST['vehicle_history'])) {
    $historyObj = new stdClass();
    $historyObj->vehicleId = $_POST['vehicleId'];
    $historyObj->start_date = $_POST['STdate'];
    $historyObj->end_date = $_POST['EDdate'];
    $historyObj->total_records=$_POST['total_records'];
    $vm = new VehicleManager($_SESSION['customerno']);
    $vehicle_details= $vm->getVehicleHistoryLogs($historyObj);
    echo json_encode($vehicle_details);
}
if (isset($_POST['checkpoint_fence_mapping'])) {
    $historyObj = new stdClass();
    $historyObj->vehicleId = $_POST['vehicleId'];
    $historyObj->start_date = $_POST['STdate'];
    $historyObj->end_date = $_POST['EDdate'];
    $historyObj->total_records=$_POST['total_records'];
    $vm = new VehicleManager($_SESSION['customerno']);
    $chk_fence_data= $vm->getChk_And_FenceLogs($historyObj);
    echo json_encode($chk_fence_data);
}
if (isset($_POST['unit_history'])) {
    $historyObj = new stdClass();
    $historyObj->unitId = $_POST['unitId'];
    $historyObj->start_date = $_POST['STdate'];
    $historyObj->end_date = $_POST['EDdate'];
    $historyObj->total_records=$_POST['total_records'];
    $vm = new VehicleManager($_SESSION['customerno']);
    $chk_fence_data= $vm->getUnitHistoryLogs($historyObj);
    echo json_encode($chk_fence_data);
}
?>
