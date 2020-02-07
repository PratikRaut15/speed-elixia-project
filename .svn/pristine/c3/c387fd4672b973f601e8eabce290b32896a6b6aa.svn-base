<?php
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");

class json{
// Empty class
}

if(isset($_GET["customerno"])  && isset($_GET["devicekey"]))
{
    $jsonstatus = new json();
    $jsonstatus->calls = Array();
//    $jsonstatus->servicelist = Array();
    $devicekey = GetSafeValueString($_GET["devicekey"],"string");
    $customerno = GetSafeValueString($_GET["customerno"],"string");    
        
    // Service Call Manager
    include_once("../../../lib/wbo/ServiceCallManager.php");
    $scm = new ServiceCallManager($customerno);        
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
else    
{
    $jsonstatus->status = "unsuccessful";        
}    
echo json_encode($jsonstatus);
?>