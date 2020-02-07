<?php
include_once 'checkpoint_functions_modal.php';
if(isset($_REQUEST['chkN']))
{
    check_chk_name($_REQUEST['chkN']);
}
else if(isset ($_REQUEST['d']))
{
    chk_eligibility($_REQUEST['d']);
}
else if(isset ($_REQUEST['d']) && isset ($_REQUEST['ds']))
{
    mapchk($_REQUEST['d'], $_REQUEST['ds']);
}
else if(isset ($_REQUEST['ds']))
{
    demapchk($_REQUEST['ds']);
}
else if(isset($_GET['chk']) && $_GET['chk']=='all')
{
    chkformapping();
}
else if($_REQUEST['checkpointid']){
	view_checkpoint_by_id($_REQUEST['checkpointid']);
}
else if(isset($_REQUEST['chkid']) && isset($_REQUEST['chkname']) && isset($_REQUEST['chkrad']))
{
    //$vehicleid = GetSafeValueString($_REQUEST['vehicleid'],"string");
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
