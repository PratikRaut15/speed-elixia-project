<?php
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");

class json{
// Empty class
}

if(isset($_GET["customerno"])  && isset($_GET["devicekey"]) && isset($_GET["serviceid"]) && isset($_GET["totalbill"]) && isset($_GET["discount"]) && isset($_GET["discountcode"]))
{
    $jsonstatus = new json();
    $devicekey = GetSafeValueString($_GET["devicekey"],"string");
    $customerno = GetSafeValueString($_GET["customerno"],"string");    
    $serviceid = GetSafeValueString($_GET["serviceid"],"string");        
    $totalbill = GetSafeValueString($_GET["totalbill"],"string");            
    $discount = GetSafeValueString($_GET["discount"],"string");            
    $code = GetSafeValueString($_GET["discountcode"],"string");                
    include_once("../../../lib/wbo/ServiceCallManager.php");
    $scm = new ServiceCallManager($customerno);
    $scm->settotalbill($serviceid, $totalbill, $discount, $code);
    $jsonstatus = new json();    
    $jsonstatus->status = "successful";        
}
 else {
    $jsonstatus->status = "unsuccessful";            
}
echo json_encode($jsonstatus);
?>