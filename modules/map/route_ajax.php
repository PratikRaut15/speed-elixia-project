<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
if (!isset($_SESSION)) {
    session_start();
}
include 'map_functions.php';
if (isset($_REQUEST['vehicleids'])) {
    getvehicle($_REQUEST['vehicleids']);
}
if (isset($_REQUEST['all'])) {
    if (isset($_REQUEST['getvehicleid'])) {
        getvehicle($_REQUEST['getvehicleid']);
    } else {
        if ($_SESSION['switch_to'] == '3') {
            getvehicles_wh();
        } else {
            getvehicles();
        }
    }
} elseif (isset($_REQUEST['monitor'])) {
    if ($_SESSION['switch_to'] == '3') {
        getWarehouseMonitorDetails();
    }
} elseif (isset($_REQUEST['radar'])) {
    //echo $_REQUEST['vehicleId'];
    getRadarDetails($_REQUEST['vehicleId']);
}
?>
