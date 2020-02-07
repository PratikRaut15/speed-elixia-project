<?php
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");

class json{
// Empty class
}

$jsonstatus = new json();
    $jsonstatus->status = "unsuccessful";        
if(isset($_GET["customerno"])  && isset($_GET["devicekey"]))
{
    $jsonstatus->calls = Array();
//    $jsonstatus->servicelist = Array();
    $devicekey = GetSafeValueString($_GET["devicekey"],"string");
    $customerno = GetSafeValueString($_GET["customerno"],"string");    
        
    // Service Call Manager
    include_once("../../../lib/wbo/ServiceCallV2Manager.php");
    $scm = new ServiceCallV2Manager($customerno);        
    $data = $scm->getdatafromdevicekey($devicekey);            
    $calls = $scm->getopencalls($data->trackeeid);
    $jsonstatus->status = "successful"; 
    if(isset($calls))
    {
        foreach($calls as $thiscall)
        {
            if(isset($thiscall->customerno))
            { 
                $servicelist = $scm->getservicelist($thiscall->serviceid);                
                $thiscall->list = Array();
                if(isset($servicelist))
                {
                    foreach($servicelist as $thisservicelist)
                    {
                        $thiscall->list[] = $thisservicelist;
                    }
                }
                $jsonstatus->calls[] = $thiscall;
            }            
        }
    }            
    $scm->updatepushservice($data->trackeeid);            
}
echo json_encode($jsonstatus);
?>