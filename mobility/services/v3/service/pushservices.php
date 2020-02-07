<?php
 
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");

$response = Array();
$updatedarray = Array();
$response["status"] = "unsuccessful";         
// check for required fields
if (isset($_REQUEST['customerno']) && isset($_REQUEST['devicekey']) && isset($_REQUEST['json']) && isset($_REQUEST['totalbill']) && isset($_REQUEST['discountf'])) 
    {
        $changed = 8;
        $devicekey = GetSafeValueString($_REQUEST["devicekey"],"string");
        $customerno = GetSafeValueString($_REQUEST["customerno"],"string");    
        $json = GetSafeValueString($_REQUEST["json"],"string");
        $totalbill = GetSafeValueString($_REQUEST["totalbill"],"string");        
        $discountf = GetSafeValueString($_REQUEST["discountf"],"string");                
        $jsonarray = json_decode($json);
        
        // Service Call Manager
        include_once("../../../lib/wbo/ServiceCallV3Manager.php");
        $scm = new ServiceCallV3Manager($customerno);        
        $data = $scm->getdatafromdevicekey($devicekey);            
        if(isset($jsonarray))
        {
            $expectedtime = 0;
            foreach($jsonarray as $thisarray)
            {
                $updated = $scm->pushservices($thisarray, $data->trackeeid, $totalbill, $changed, $discountf);            
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
echo json_encode($response);
?>