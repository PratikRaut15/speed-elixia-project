<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include 'fence_functions.php';
if(isset($_REQUEST['lat']) && isset($_REQUEST['long']) && isset($_REQUEST['fencename']) && isset($_REQUEST['vehiclearray']) && !isset($_REQUEST['fenceid']))
{
    createfence($_REQUEST['lat'],$_REQUEST['long'],$_REQUEST['fencename'],$_REQUEST['vehiclearray']);
}
else if(isset ($_REQUEST['d']) && isset ($_REQUEST['ds']))
{
    mapfence($_REQUEST['d'],$_REQUEST['ds']);
}
else if(isset ($_REQUEST['ds']))
{
    demapfence($_REQUEST['ds']);
}
else if(isset ($_REQUEST['fenceid']) && isset($_REQUEST['get']) && $_REQUEST['get']=='vehicle')
{
    getvehicleforfence($_REQUEST['fenceid']);
}
else if (isset ($_REQUEST['fenceid']) && isset($_REQUEST['get']) &&  $_REQUEST['get']=='fence')
{
    fenceonmap($_REQUEST['fenceid']);
}
else if(isset ($_REQUEST['d']))
{
    fence_eligibility($_REQUEST['d']);
}
else if(isset($_REQUEST['fenceid']) && isset($_REQUEST['fencename']) && !isset($_REQUEST['lat']))
{
    //$vehicleid = GetSafeValueString($_REQUEST['vehicleid'],"string");
    $fenceid = GetSafeValueString($_REQUEST['fenceid'],"string");
    $fencename = GetSafeValueString($_REQUEST['fencename'],"string");
    editfence($fenceid, $fencename);
}
else if(isset($_REQUEST['fenceid']) && isset($_REQUEST['fencename']) && isset($_REQUEST['lat']) && isset($_REQUEST['long']) && isset($_REQUEST['vehiclearray']))
{
    //$vehicleid = GetSafeValueString($_REQUEST['vehicleid'],"string");
    $fenceid = GetSafeValueString($_REQUEST['fenceid'],"string");
    $fencename = GetSafeValueString($_REQUEST['fencename'],"string");
    $lat = GetSafeValueString($_REQUEST['lat'],"string");
    $long = GetSafeValueString($_REQUEST['long'],"string");
    $vehiclearray = GetSafeValueString($_REQUEST['vehiclearray'],"string");
    editfencing($lat,$long,$fencename,$fenceid,$vehiclearray);
}
else if(isset($_REQUEST['fenceid']) && isset($_REQUEST['vehicleid']))
{
    $vehicleid = GetSafeValueString($_REQUEST['vehicleid'],"string");
    $fenceid = GetSafeValueString($_REQUEST['fenceid'],"string");
    DelFenceByVehicleid($fenceid, $vehicleid);
}
?>
