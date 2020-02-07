<?php
 
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");

$response = Array();
// check for required fields
if (isset($_REQUEST['customerno']) && isset($_REQUEST['devicekey']) && isset($_REQUEST['json'])) 
    {

        $devicekey = GetSafeValueString($_REQUEST["devicekey"],"string");
        $customerno = GetSafeValueString($_REQUEST["customerno"],"string");    
    
        $target_path = $_SERVER['DOCUMENT_ROOT'].$subdir."//customer/".$customerno."/".$devicekey."/";    
        if(!is_dir( $target_path ))
        {
            mkdir( $target_path ,0777, true ) or die ("Could not create directory");
        }    

        $target_path_signature = $target_path . "signature/";

        if(!is_dir( $target_path_signature ))
        {
            mkdir( $target_path_signature ,0777, true ) or die ("Could not create directory");
        }            

        $target_path_service = $target_path_signature . "service/";

        if(!is_dir( $target_path_service ))
        {
            mkdir( $target_path_service ,0777, true ) or die ("Could not create directory");
        }            
        
        $json = GetSafeValueString($_REQUEST["json"],"string");
        $jsonarray = json_decode($json);

        // Service Call Manager
        include_once("../../../lib/wbo/ServiceCallManager.php");
        $scm = new ServiceCallManager($customerno);        
        $data = $scm->getdatafromdevicekey($devicekey);            
        if(isset($jsonarray))
        {
            foreach($jsonarray as $thisarray)
            {
                $response["status"] = "successful";                     
                $image = base64_decode($thisarray->signature);
                file_put_contents($_SERVER['DOCUMENT_ROOT'].$subdir."/customer/".$customerno."/".$devicekey."/signature/service/".$thisarray->serviceid.".jpeg", $image);
                $scm->updatecalls($thisarray->serviceid, $thisarray->closedon, $thisarray->status, $thisarray->starttime, $thisarray->endtime, $thisarray->departtime, $thisarray->iscard);                
//                    $scm->callclosednotification($trackeeid, $serviceid);                    
                $scm->sendclosenotification($thisarray->serviceid, $thisarray->closedon);
            }
        }
        else
        {
            $response["status"] = "unsuccessful";     
        }
    }
    else    
    {
        $response["status"] = "unsuccessful";         
    }    
echo json_encode($response);
?>