<?php
include 'fence_functions_modal.php';
if(isset($_REQUEST['lat']) && isset($_REQUEST['long']) && isset($_REQUEST['fencename']))
{
    createfence($_REQUEST['lat'],$_REQUEST['long'],$_REQUEST['fencename']);
}
else if(isset($_REQUEST['fencetovehicle']))
{
    addfencetovehicle($_REQUEST);
    //header("location: checkpoint.php?id=2");
}
else if(isset($_REQUEST['fencename1']) && isset($_REQUEST['lat1']) && isset($_REQUEST['long1']) && isset($_REQUEST['vehicle_array1']))
{
    $lat = GetSafeValueString($_REQUEST['lat1'],"string");
    $long = GetSafeValueString($_REQUEST['long1'],"string");
    $fencename = GetSafeValueString($_REQUEST['fencename1'],"string");
    $vehicle_array = GetSafeValueString($_REQUEST['vehicle_array1'],"string");
    createfencebyvehicle($lat,$long,$fencename,$vehicle_array);
}
else if(isset($_REQUEST['vehicleid_fence']))
{
    //$lat = GetSafeValueString($_REQUEST['lt'],"string");
    //$long = GetSafeValueString($_REQUEST['lng'],"string");
    //$fencename = GetSafeValueString($_REQUEST['fenceName'],"string");
    //$vehicle_array = GetSafeValueString($_REQUEST['vehiclearray'],"string");
    createfencebyvehiclearray($_REQUEST);
}
else if(isset ($_REQUEST['d']) && isset ($_REQUEST['ds']))
{
    mapfence($_REQUEST['d'],$_REQUEST['ds']);
}
else if(isset ($_REQUEST['ds']))
{
    demapfence($_REQUEST['ds']);
}
else if(isset ($_REQUEST['fenceid']) && $_GET['get']=='vehicle')
{
    getvehicleforfence($_REQUEST['fenceid']);
}
else if (isset ($_REQUEST['fenceid']) && $_GET['get']=='fence') 
{
    fenceonmap($_REQUEST['fenceid']);
}
else if(isset ($_REQUEST['d']))
{
    fence_eligibility($_REQUEST['d']);
}
else if(isset($_REQUEST['fenceid']) && isset($_REQUEST['fencename']))
{
    //$vehicleid = GetSafeValueString($_REQUEST['vehicleid'],"string");
    $fenceid = GetSafeValueString($_REQUEST['fenceid'],"string");
    $fencename = GetSafeValueString($_REQUEST['fencename'],"string");
    editfence($fenceid, $fencename);
}
else if(isset($_REQUEST['fenceid']) && isset($_REQUEST['vehicleid']))
{
    $vehicleid = GetSafeValueString($_REQUEST['vehicleid'],"string");
    $fenceid = GetSafeValueString($_REQUEST['fenceid'],"string");
    DelFenceByVehicleid($fenceid, $vehicleid);
}
?>
