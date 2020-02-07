<?php
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");

class json{
// Empty class
}

$jsonstatus = new json();    
$jsonstatus->status = "unsuccessful";            
if(isset($_GET["customerno"])  && isset($_GET["devicekey"]) && isset($_GET["serviceid"]) && isset($_GET["status"]))
{
    $devicekey = GetSafeValueString($_GET["devicekey"],"string");
    $customerno = GetSafeValueString($_GET["customerno"],"string");    
    $serviceid = GetSafeValueString($_GET["serviceid"],"string");        
    $status = GetSafeValueString($_GET["status"],"string");            
    include_once("../../../lib/wbo/ServiceCallV2Manager.php");
    $scm = new ServiceCallV2Manager($customerno);
    $scm->setview($serviceid, $status);
    $jsonstatus->status = "successful";        
}
echo json_encode($jsonstatus);
?>