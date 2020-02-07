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

if (isset($_POST['user_history'])) {
    $historyObj = new stdClass();
    $customer_details = array();
    $historyObj->userId = $_POST['userId'];
    $historyObj->start_date = $_POST['STdate'];
    $historyObj->end_date = $_POST['EDdate'];
    $historyObj->total_records=$_POST['total_records'];
    $um = new UserManager();
    $customer_details= $um->getUserHistoryLogs($historyObj);
    echo json_encode($customer_details);
}
if (isset($_POST['vehicleUserMapping_stoppage_alerts'])) {
    $historyObj = new stdClass();
    $customer_details = array();
    $historyObj->userId = $_POST['userId'];
    $historyObj->start_date = $_POST['STdate'];
    $historyObj->end_date = $_POST['EDdate'];
    $historyObj->total_records=$_POST['total_records'];
    $um = new UserManager();
    $vehicleUserMapping_stoppage_alerts= $um->getUserStoppageAndVehicleMapLogs($historyObj);
    echo json_encode($vehicleUserMapping_stoppage_alerts);
}
if (isset($_POST['groupUserMapping'])) {
    $historyObj = new stdClass();
    $customer_details = array();
    $historyObj->userId = $_POST['userId'];
    $historyObj->start_date = $_POST['STdate'];
    $historyObj->end_date = $_POST['EDdate'];
    $historyObj->total_records=$_POST['total_records'];
    $um = new UserManager();
    $userGroupMapping = $um->getUserGroupMappingLogs($historyObj);
    echo json_encode($userGroupMapping);
}
?>
