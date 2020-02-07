<?php
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");

$response = Array();
$updatedarray = Array();
$response["status"] = "unsuccessful";         
// check for required fields
if (isset($_REQUEST['customerno']) && isset($_REQUEST['devicekey']) && isset($_REQUEST['code']) && isset($_REQUEST['serviceid']) && isset($_REQUEST['timestamp'])) 
{
    $devicekey = GetSafeValueString($_REQUEST["devicekey"],"string");
    $customerno = GetSafeValueString($_REQUEST["customerno"],"string");    
    $code = GetSafeValueString($_REQUEST["code"],"string");
    $serviceid = GetSafeValueString($_REQUEST["serviceid"],"string");    
    $timestamp = GetSafeValueString($_REQUEST["timestamp"],"string");            
    // Service Call Manager
    include_once("../../../lib/wbo/SCVF1Manager.php");
    $scm = new SCVF1Manager($customerno);        
    $data = $scm->getdatafromdevicekey($devicekey);
    $branchid = $scm->getbranchid($data->trackeeid);
    $clientid = $scm->getclientid($serviceid);
    $updatedarray = $scm->validate_discount($code, $clientid, $timestamp, "edit",$serviceid, $branchid);

    if($updatedarray)
    {
        $response["status"] = "successful";     
        $response["updatedarray"] = $updatedarray;
    }
    else
    {
        $response["status"] = "unsuccessful";     
        $response["updatedarray"] = $updatedarray;
    }
}
echo json_encode($response);
?>