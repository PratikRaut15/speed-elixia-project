<?php
/**
 * @Author: shrikant
 * @Date:   2018-09-06 11:37:28
 * @Last Modified by:   shrikant
 * @Last Modified time: 2018-10-09 12:14:06
 */
session_start();
include 'rtdDashboardFunctions.php';
extract($_REQUEST);
if (isset($action) && $action == 'dashboard') {
    $vehicleId = '';
    if (isset($_REQUEST['vehicleid']) && $_REQUEST['vehicleid']) {
        $vehicleId = $_REQUEST['vehicleid'];
    }
    $historyData = getRtdDashboardData($vehicleId);
    echo json_encode($historyData);
} elseif (isset($action) && $action == 'updateVehicle') {
    $arrResult = null;
    $objVehicle = new stdClass();
    $objVehicle->vehicleId = $vehicleId;
    $objVehicle->vehicleNo = $vehicleNo;
    $objVehicle->customerNo = $customerNo;
    $arrResult = updateVehicle($objVehicle);
    echo json_encode($arrResult);
}
?>