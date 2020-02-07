<?php

/**
 * Start page of TMS module
 */
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

require_once '../panels/header.php';
//require_once "tms_function.php";
if (!isset($_SESSION['userid'])) {
    header("location: ../../index.php");
}
if ($_SESSION['use_tms'] == 0) {
    exit('No access to this module');
}

echo '<link href="' . $_SESSION['subdir'] . '/css/secondarytms.css" rel="stylesheet" type="text/css" />';

$pg = isset($_GET['pg']) ? $_GET['pg'] : 'def';
echo "<br/>";

// <editor-fold defaultstate="collapsed" desc="PAGE ACTION URL">
if (!isset($_GET['pg']) || $pg == 'view-shipment') {
    require_once 'pages/shipment.php';
}elseif ($pg == 'view-vehtype') {
    require_once 'pages/vehicletype.php';
}elseif ($pg == 'view-vehicle') {
    require_once 'pages/vehicle.php';
}elseif ($pg == 'view-route') {
    require_once 'pages/route.php';
}elseif ($pg == 'view-routematrix') {
    require_once 'pages/routematrix.php';
}elseif ($pg == 'view-products') {
    require_once 'pages/products.php';
}elseif ($pg == 'view-customer') {
    require_once 'pages/customer.php';
}elseif ($pg == 'view-occupancy') {
    require_once 'pages/occupancy.php';
}elseif ($pg == 'view-trips') {
    require_once 'pages/trips.php';
}elseif ($pg == 'view-tripclose') {
    require_once 'pages/tripclose.php';
}elseif ($pg == 'view-billing') {
    require_once 'pages/billing.php';
}elseif ($pg == 'view-fuelrequest') {
    require_once 'pages/fuelrequest.php';
}elseif ($pg == 'customer') {
    require_once 'pages/viewcustomer.php';
}elseif ($pg == 'editcustomer') {
    require_once 'pages/editcustomer.php';
}elseif ($pg == 'view-fuel') {
    require_once 'pages/fuel.php';
} else {
    header("location: ../../index.php");
}
// </editor-fold>

require_once '../panels/footer.php';
