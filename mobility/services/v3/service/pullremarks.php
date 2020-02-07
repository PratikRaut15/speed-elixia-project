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
    $jsonstatus->remarks = Array();    
    $devicekey = GetSafeValueString($_GET["devicekey"],"string");
    $customerno = GetSafeValueString($_GET["customerno"],"string");    
        
    // Service Call Manager
    include_once("../../../lib/wbo/ServiceCallV3Manager.php");
    $scm = new ServiceCallV3Manager($customerno);        
    $data = $scm->getdatafromdevicekey($devicekey);            
    
    $remarks = $scm->getremarks();
    $jsonstatus->status = "successful"; 

    if(isset($remarks))
    {
        foreach($remarks as $thisremark)
        {
            if(isset($thisremark->customerno))
            {
                $jsonstatus->remarks[] = $thisremark;
            }
        }
        $scm->updatepushremarks($data->trackeeid);        
    }            
}
echo json_encode($jsonstatus);
?>