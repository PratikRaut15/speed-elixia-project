<?php
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");

class json{
// Empty class
}

if(isset($_GET["customerno"])  && isset($_GET["devicekey"]) && isset($_GET["serviceid"]) && isset($_GET["timestamp"]))
{
    $jsonstatus = new json();
    $devicekey = GetSafeValueString($_GET["devicekey"],"string");
    $customerno = GetSafeValueString($_GET["customerno"],"string");    
    $serviceid = GetSafeValueString($_GET["serviceid"],"string");        
    $departtime = GetSafeValueString($_GET["timestamp"],"string");            
    include_once("../../../lib/wbo/ServiceCallManager.php");
    $scm = new ServiceCallManager($customerno);
    $scm->setdepart($serviceid, $departtime);
    $jsonstatus = new json();    
    $jsonstatus->status = "successful";        
}
 else {
    $jsonstatus->status = "unsuccessful";            
}
echo json_encode($jsonstatus);
?>