<?php
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");

class json{
// Empty class
}

if(isset($_GET["customerno"])  && isset($_GET["trackeeid"]) && isset($_GET["serviceid"]) && isset($_GET["remarkid"]))
{
    $jsonstatus = new json();
    $jsonstatus->items = Array();
    $serviceid = GetSafeValueString($_GET["serviceid"],"string");
    $customerno = GetSafeValueString($_GET["customerno"],"string");    
    $trackeeid = GetSafeValueString($_GET["trackeeid"],"string");    
    $remarkid = GetSafeValueString($_GET["remarkid"],"string");        
    include_once("../../../lib/wbo/ServiceCallManager.php");
    $scm = new ServiceCallManager($customerno);
    $scm->modifycall($trackeeid, $serviceid, $remarkid);
    $scm->callclosednotification($trackeeid, $serviceid);
    $jsonstatus = new json();    
    $jsonstatus->status = "successful";        
}
 else {
    $jsonstatus->status = "unsuccessful";            
}
    echo json_encode($jsonstatus);
?>