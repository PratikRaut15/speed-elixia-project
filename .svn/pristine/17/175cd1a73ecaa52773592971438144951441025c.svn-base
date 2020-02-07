<?php
include 'route_functions.php';
if (isset($_GET['deldid'])) {
    deldriver($_GET['deldid']);
    header('location: driver.php?id=2');
} elseif (isset($_GET['add_new'])) {
    $routename = GetSafeValueString($_POST['routename'], "string");
    $routearray = GetSafeValueString($_POST['routearray'], "string");
    $vehiclearray = GetSafeValueString($_POST['vehiclearray'], "string");
    $thourarray = GetSafeValueString($_POST['thourarray'], "string");
    $tminarray = GetSafeValueString($_POST['tminarray'], "string");
    $distancearray = GetSafeValueString($_POST['distancearray'], "string");
    addroute_enh($routename, $routearray, $vehiclearray, $thourarray, $tminarray, $distancearray);
} elseif (isset($_GET['edit_new']) && isset($_POST['routeid']) && isset($_POST['routearray']) && isset($_POST['vehiclearray']) && isset($_POST['routename'])) {
    $routeid = GetSafeValueString($_POST['routeid'], "string");
    $routename = GetSafeValueString($_POST['routename'], "string");
    $routearray = GetSafeValueString($_POST['routearray'], "string");
    $vehiclearray = GetSafeValueString($_POST['vehiclearray'], "string");
    $thourarray = GetSafeValueString($_POST['thourarray'], "string");
    $tminarray = GetSafeValueString($_POST['tminarray'], "string");
    $distancearray = GetSafeValueString($_POST['distancearray'], "string");
    editroute_enh($routeid, $routename, $routearray, $vehiclearray, $thourarray, $tminarray, $distancearray);
} elseif (isset($_POST['routename']) && isset($_POST['routearray']) && isset($_POST['vehiclearray']) && !isset($_POST['routeid'])) {
    $routename = GetSafeValueString($_POST['routename'], "string");
    $routearray = GetSafeValueString($_POST['routearray'], "string");
    $vehiclearray = GetSafeValueString($_POST['vehiclearray'], "string");
    $chkDetails = json_decode($_POST['chkDetails']);
    $routeTat = GetSafeValueString($_POST['routeTat'], "string");

    $routeType = GetSafeValueString($_POST['routeType'], "string");
    $routeType = isset($routeType) ? $routeType : 0;
    //print_r($chkDetails);die();
    addroute($routename, $routearray, $vehiclearray, $chkDetails, $routeTat, $routeType);
} elseif (isset($_POST['routeid']) && isset($_POST['routearray']) && isset($_POST['vehiclearray']) && isset($_POST['routename'])) {
    $routeid = GetSafeValueString($_POST['routeid'], "string");
    $routename = GetSafeValueString($_POST['routename'], "string");
    $routeTat = GetSafeValueString($_POST['routeTat'], "string");
    $routearray = GetSafeValueString($_POST['routearray'], "string");
    $vehiclearray = GetSafeValueString($_POST['vehiclearray'], "string");
    $chkDetails = json_decode($_POST['chkDetails']);

    $routeType = GetSafeValueString($_POST['routeType'], "string");
    $routeType = isset($routeType) ? $routeType : 0;

    editroute($routeid, $routename, $routearray, $vehiclearray, $chkDetails, $routeTat, $routeType);
} elseif (isset($_POST['routen'])) {
    $routen = GetSafeValueString($_POST['routen'], "string");
    get_route_name($routen);
}elseif(isset($_POST['action']) && $_POST['action'] == "saveFutureRoute"){

    $routearray = GetSafeValueString($_POST['routearray'], "csvi");
    $vehicleid = GetSafeValueString($_POST['vehicleid'], "long"); 
    if(!empty($routearray) && $vehicleid != NULL){

        $data = addFutureRoute($routearray, $vehicleid);
        return $data;

    }
}
?>
