<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
   $RELATIVE_PATH_DOTS = "../../";
}
include_once $RELATIVE_PATH_DOTS.'lib/bo/UserManager.php';
include_once '../reports/reports_route_functions.php';
include_once 'drivermapping_function.php';

$searchString = '';
$action = '';
$action = $_REQUEST['action'];

if (isset($action)) {
    if ($action == 'driverauto') {
        if ($_REQUEST['term'] != '') {
            $searchString = '%' . $_REQUEST['term'] . '%';
        }
        $driver = new DriverManager($_SESSION['customerno']);
        $result = $driver->drivermappinglist($searchString);
        echo json_encode($result);
        exit;
    }
    if ($action == 'userauto') {
        if ($_REQUEST['term'] != '') {
            $searchString = '%' . $_REQUEST['term'] . '%';
        }
        $userids = $_REQUEST['userids'];
        $driver = new UserManager();
        $result = $driver->getuserlistformap($searchString, $userids);
        echo json_encode($result);
        exit;
    }
    if ($action == 'addmapping') {
        $userid = '0';
        $vehicleid = '0';
        $driverid = '0';
        $drivername_param = '';
        $userid = $_REQUEST['userid'];
        $vehicleid = $_REQUEST['vehicleid'];
        $driverid = isset($_REQUEST['driverid'])? $_REQUEST['driverid']:'';
        $drivername = $_REQUEST['drivername'];
        $drivername_other = $_REQUEST['newdrivername'];
        if(!empty($drivername)){
           $drivername_param = $drivername;
        }
        if(!empty($drivername_other)){
            $drivername_param = $drivername_other;
        }

        $apiObj = new drivermap();
        $result = $apiObj->map_vehicle_driver_user($vehicleid, $userid, $driverid, $drivername_param);
        echo $result;
        exit;
    }
}
