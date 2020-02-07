<?php
include_once 'location_functions.php';
if(isset($_REQUEST['locN']))
{
    check_loc_name($_REQUEST['locN']);
}
else if(isset ($_REQUEST['d']))
{
    chk_eligibility($_REQUEST['d']);
}
else if(isset($_REQUEST))
{
    addlocation($_REQUEST);
}
else if(isset($_REQUEST['chkid']) && isset($_REQUEST['chkname']) && isset($_REQUEST['chkrad']))
{
    //$vehicleid = GetSafeValueString($_POST['vehicleid'],"string");
    $chkid = GetSafeValueString($_REQUEST['chkid'],"string");
    $chkname = GetSafeValueString($_REQUEST['chkname'],"string");
    $chkrad = GetSafeValueString($_REQUEST['chkrad'],"string");
    editchk($chkid, $chkname, $chkrad);
}
else if(isset($_REQUEST['chkid']) && isset($_REQUEST['vehicleid']))
{
    $vehicleid = GetSafeValueString($_REQUEST['vehicleid'],"string");
    $chkid = GetSafeValueString($_REQUEST['chkid'],"string");
    DelChkByVehicleid($chkid, $vehicleid);
}
?>
