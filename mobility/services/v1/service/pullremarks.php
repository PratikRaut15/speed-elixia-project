<?php
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");

class json{
// Empty class
}

if(isset($_GET["customerno"])  && isset($_GET["devicekey"]))
{
    $jsonstatus = new json();
    $jsonstatus->remarks = Array();    
    $devicekey = GetSafeValueString($_GET["devicekey"],"string");
    $customerno = GetSafeValueString($_GET["customerno"],"string");    
        
    // Service Call Manager
    include_once("../../../lib/wbo/ServiceCallManager.php");
    $scm = new ServiceCallManager($customerno);        
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
else
{
    $jsonstatus->status = "unsuccessful";        
}    
echo json_encode($jsonstatus);
?>