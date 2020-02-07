<?php
 
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");

$response = Array();
$updatedarray = Array();
$response["status"] = "unsuccessful";         
// check for required fields
if (isset($_REQUEST['customerno']) && isset($_REQUEST['devicekey']) && isset($_REQUEST['json']) && isset($_REQUEST['remarkid']) && isset($_REQUEST['serviceid'])) 
{ 
    $devicekey = GetSafeValueString($_REQUEST["devicekey"],"string");
    $customerno = GetSafeValueString($_REQUEST["customerno"],"string");    
    $json = GetSafeValueString($_REQUEST["json"],"string");
    $remarkid = GetSafeValueString($_REQUEST["remarkid"],"string");        
    $serviceid = GetSafeValueString($_REQUEST["serviceid"],"string");                
    $jsonarray = json_decode($json);

    // Service Call Manager
    include_once("../../../lib/wbo/ServiceCallV2Manager.php");
    $scm = new ServiceCallV2Manager($customerno);        
    $data = $scm->getdatafromdevicekey($devicekey);            
    $scm->setremark($remarkid,$serviceid);
    if(isset($jsonarray))
    {
        $response["status"] = "successful";                             
        foreach($jsonarray as $thisarray)
        {
            $scm->pushfeedback($thisarray, $serviceid, $data->trackeeid);            
        }
    }
}
echo json_encode($response);
?>