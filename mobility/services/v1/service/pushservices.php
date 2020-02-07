<?php
 
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");

$response = Array();
$updatedarray = Array();
// check for required fields
if (isset($_REQUEST['customerno']) && isset($_REQUEST['devicekey']) && isset($_REQUEST['json']) && isset($_REQUEST['totalbill'])) 
    {
 
        $devicekey = GetSafeValueString($_REQUEST["devicekey"],"string");
        $customerno = GetSafeValueString($_REQUEST["customerno"],"string");    
        $json = GetSafeValueString($_REQUEST["json"],"string");
        $totalbill = GetSafeValueString($_REQUEST["totalbill"],"string");        
        $jsonarray = json_decode($json);
        
        // Service Call Manager
        include_once("../../../lib/wbo/ServiceCallManager.php");
        $scm = new ServiceCallManager($customerno);        
        $data = $scm->getdatafromdevicekey($devicekey);            
        if(isset($jsonarray))
        {
            $expectedtime = 0;
            foreach($jsonarray as $thisarray)
            {
                $updated = $scm->pushservices($thisarray, $data->trackeeid, $totalbill);            
                $updatedarray[] = $updated;
                if($thisarray->isdeleted == 0)
                {
                    $newexpectedtime = $scm->pullexpectedtime($thisarray->servicelistid);
                    $expectedtime = $expectedtime + $newexpectedtime;
                    $serviceid = $thisarray->serviceid;
                }                
            }
            $scm->updateexpectedendtime($serviceid, $expectedtime);
        }
        if($updatedarray)
        {
            $response["status"] = "successful";     
            $response["updatedarray"] = $updatedarray;
        }
        else
        {
            $response["status"] = "unsuccessful";     
            $response["updatedarray"] = $updatedarray;
        }
}
else    
{
$response["status"] = "unsuccessful";         
}    
echo json_encode($response);
?>