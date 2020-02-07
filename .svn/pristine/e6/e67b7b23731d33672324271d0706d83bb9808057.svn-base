<?php
 
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");

$response = Array();
$updatedarray = Array();
$response["status"] = "unsuccessful";         
// check for required fields
if (isset($_REQUEST['customerno']) && isset($_REQUEST['devicekey']) && isset($_REQUEST['json']) && isset($_REQUEST['serviceid'])) 
{
        $devicekey = GetSafeValueString($_REQUEST["devicekey"],"string");
        $customerno = GetSafeValueString($_REQUEST["customerno"],"string");    
        $json = GetSafeValueString($_REQUEST["json"],"string");
        $serviceid = GetSafeValueString($_REQUEST["serviceid"],"string");        
        $jsonarray = json_decode($json);
        
        // Service Call Manager
        include_once("../../../lib/wbo/SCVF1Manager.php");
        $scm = new SCVF1Manager($customerno);        
        $data = $scm->getdatafromdevicekey($devicekey);            
        if(isset($jsonarray))
        {
            foreach($jsonarray as $thisarray)
            {
                $paymentresponse = $scm->pushpartialpayment($serviceid, $thisarray);            
            }
        }
        if($paymentresponse)
        {
            $response["status"] = "successful";     
        }
        else
        {
            $response["status"] = "unsuccessful";     
        }
}
echo json_encode($response);
?>