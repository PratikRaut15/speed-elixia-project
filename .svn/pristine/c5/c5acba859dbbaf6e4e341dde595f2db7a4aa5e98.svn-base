<?php
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");

class json{
// Empty class
}

$jsonstatus = new json(); 
$jsonstatus->status = "unsuccessful";            
if(isset($_GET["customerno"])  && isset($_GET["devicekey"]) && isset($_GET["serviceid"]) && isset($_GET["timestamp"]))
{
    $devicekey = GetSafeValueString($_GET["devicekey"],"string");
    $customerno = GetSafeValueString($_GET["customerno"],"string");    
    $serviceid = GetSafeValueString($_GET["serviceid"],"string");        
    $starttime = GetSafeValueString($_GET["timestamp"],"string");            
    include_once("../../../lib/wbo/ServiceCallV3Manager.php");
    $scm = new ServiceCallV3Manager($customerno);
    $scm->setstart($serviceid, $starttime);
    $services = $scm->pullservicelistids($serviceid);
    if(isset($services))
    {
        $expectedtime = 0;
        foreach($services as $thisarray)
        {
            $newexpectedtime = $scm->pullexpectedtime($thisarray->servicelistid);
            $expectedtime = $expectedtime + $newexpectedtime;
        }
        $scm->setexpectedendtime($serviceid, $expectedtime);
    }
    $jsonstatus->status = "successful";        
}
echo json_encode($jsonstatus);
?>