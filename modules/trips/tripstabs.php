<?php
if (!isset($_SESSION['userid'])) {
    header("location: ../../index.php");
}
echo "<div id='pageloaddiv' style='display:none;'></div>";
$statusid = 1;
$pg = isset($_GET['pg']) ? $_GET['pg'] : 'def';
$statusid = isset($_GET['statusid']) ? $_GET['statusid'] : '';
$consid = isset($_GET['consid']) ? $_GET['consid'] : ''; //consinee id
$consrid = isset($_GET['consrid']) ? $_GET['consrid'] : ''; //consineer id
$tripid = isset($_GET['tripid']) ? $_GET['tripid'] : ''; //consineer id
$tid = isset($_GET['tid']) ? $_GET['tid'] : ''; //tripid id
require_once 'pages/trip_menu.php';
require_once 'trips_function.php';
if ($pg == 'def') {
    if (!isset($_SESSION['userid'])) {
        header("location: ../../index.php");
    } else {
        header("location: " . "trips.php?pg=tripview");
    }
} elseif ($pg == 'tripview') {
    $gettriprecords = get_viewtriprecords_list($_SESSION['customerno'], $_SESSION['userid']); //get triprecord in json format
    require_once 'pages/viewtrips.php';
} elseif ($pg == 'tripreport') {
    require_once 'pages/tripreport.php'; //trip report
} elseif ($pg == 'statusview') {
    //view tripstatus
    require_once 'pages/tripstatusview.php';
} elseif ($pg == 'tripstatus') {
    //add status
    require_once 'pages/tripstatus.php';
} elseif ($pg == 'tripstatusedit' && $statusid != "") {
    //edit status
    require_once 'pages/tripstatusedit.php';
} elseif ($pg == 'consigneview') {
    //view consignee
    require_once 'pages/consignee.php';
} elseif ($pg == 'addconsignee') {
    //add consignee
    require_once 'pages/addconsignee.php';
} elseif ($pg == 'editconsignee' && $consid != "") {
    //edit consignee
    require_once 'pages/consigneedit.php';
} elseif ($pg == 'consignerview') {
    // consigneer view
    require_once 'pages/consigner.php';
} elseif ($pg == 'addconsigneer') {
    //add consigneer
    require_once 'pages/addconsigneer.php';
} elseif ($pg == 'editconsigneer' && $consrid != "") {
    //edit consigneer
    require_once 'pages/consigneerdit.php';
} elseif ($pg == 'tripviewdata') {
    require_once 'pages/closetriphist.php';
} elseif ($pg == 'mgmtDashboard') {
    require_once 'pages/mgmtDashboard.php';
} elseif ($pg == 'oprtDashboard') {
    require_once 'pages/oprtDashboard.php';
} elseif ($pg == 'volumeDispatched' || $pg == 'lrDelayed' || $pg == 'yardDetentionDeviation' || $pg == 'emptyReturnDeviation' || $pg == "inTransitStoppage") {
    require_once 'pages/viewDetails.php';
} elseif ($pg == 'trip_mapview') {
    require_once 'pages/mapView.php';
} else {
    header("location: " . "trips.php?pg=tripview");
}
?>