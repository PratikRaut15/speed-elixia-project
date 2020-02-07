<?php
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");

class json{
// Empty class
}

$jsonstatus = new json();
$jsonstatus->status = "unsuccessful";            
if(isset($_GET["customerno"])  && isset($_GET["trackeeid"]) && isset($_GET["serviceid"]) && isset($_GET["remarkid"]))
{
    $jsonstatus->items = Array();
    $serviceid = GetSafeValueString($_GET["serviceid"],"string");
    $customerno = GetSafeValueString($_GET["customerno"],"string");    
    $trackeeid = GetSafeValueString($_GET["trackeeid"],"string");    
    $remarkid = GetSafeValueString($_GET["remarkid"],"string");        
    include_once("../../../lib/wbo/ServiceCallV3Manager.php");
    $scm = new ServiceCallV3Manager($customerno);
    $scm->modifycall($trackeeid, $serviceid, $remarkid);
    $scm->callclosednotification($trackeeid, $serviceid);
    $jsonstatus->status = "successful";        
}
    echo json_encode($jsonstatus);
?>