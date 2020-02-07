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
    $jsonstatus->services = Array();        
    $devicekey = GetSafeValueString($_GET["devicekey"],"string");
    $customerno = GetSafeValueString($_GET["customerno"],"string");    
        
    // Service Call Manager
    include_once("../../../lib/wbo/SCVF1Manager.php");
    $scm = new SCVF1Manager($customerno);        
    $data = $scm->getdatafromdevicekey($devicekey);            

    $services = $scm->getservices();
    $jsonstatus->status = "successful"; 

    if(isset($services))
    {
        foreach($services as $thisservice)
        {
            if(isset($thisservice->customerno))
            {
                $jsonstatus->services[] = $thisservice;
            }
        }
        $scm->updatepushservices($data->trackeeid);        
    }            
}
echo json_encode($jsonstatus);
?>