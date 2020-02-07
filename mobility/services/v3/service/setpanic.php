<?php
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");

class json{
// Empty class
}

$jsonstatus = new json();
$jsonstatus->status = "unsuccessful";            
if(isset($_GET["customerno"])  && isset($_GET["devicekey"]) && isset($_GET["timestamp"]) && isset($_GET["trackeeid"]))
{
    $devicekey = GetSafeValueString($_GET["devicekey"],"string");
    $customerno = GetSafeValueString($_GET["customerno"],"string");    
    $panictime = GetSafeValueString($_GET["timestamp"],"string");            
    $trackeeid = GetSafeValueString($_GET["trackeeid"],"string");                
    include_once("../../../lib/wbo/ServiceCallV3Manager.php");
    $scm = new ServiceCallV3Manager($customerno);
    $scm->setpanic($panictime, $trackeeid);
    $jsonstatus->status = "successful";        
}
echo json_encode($jsonstatus);
?>