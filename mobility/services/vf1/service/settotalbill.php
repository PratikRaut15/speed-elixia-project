<?php
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");

class json{
// Empty class
}

$jsonstatus = new json();    
$jsonstatus->status = "unsuccessful";            
if(isset($_GET["customerno"])  && isset($_GET["devicekey"]) && isset($_GET["serviceid"]) && isset($_GET["totalbill"]) && isset($_GET["discount"]) && isset($_GET["discountcode"]) && isset($_GET["discountid"]))
{
    $devicekey = GetSafeValueString($_GET["devicekey"],"string");
    $customerno = GetSafeValueString($_GET["customerno"],"string");    
    $serviceid = GetSafeValueString($_GET["serviceid"],"string");        
    $totalbill = GetSafeValueString($_GET["totalbill"],"string");            
    $discount = GetSafeValueString($_GET["discount"],"string");            
    $discountid = GetSafeValueString($_GET["discountid"],"string");                
    $code = GetSafeValueString($_GET["discountcode"],"string");                
    include_once("../../../lib/wbo/SCVF1Manager.php");
    $scm = new SCVF1Manager($customerno);
    $scm->settotalbill($serviceid, $totalbill, $discount, $code, $discountid);
    $jsonstatus->status = "successful";        
}
echo json_encode($jsonstatus);
?>