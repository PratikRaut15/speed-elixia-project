<?php
 
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");

$response = Array();
$response["status"] = "unsuccessful";         
// check for required fields
if (isset($_REQUEST['customerno']) && isset($_REQUEST['devicekey']) && isset($_REQUEST['photo']) && isset($_REQUEST['serviceid']) && isset($_REQUEST['formid']) && isset($_REQUEST['json'])) 
    {

        $devicekey = GetSafeValueString($_REQUEST["devicekey"],"string");
        $customerno = GetSafeValueString($_REQUEST["customerno"],"string");    
        $json = GetSafeValueString($_REQUEST["json"],"string");    
    
        $target_path = $_SERVER['DOCUMENT_ROOT'].$subdir."//customer/".$customerno."/".$devicekey."/";    
        if(!is_dir( $target_path ))
        {
            mkdir( $target_path ,0777, true ) or die ("Could not create directory");
        }    

        $target_path_signature = $target_path . "photos/";

        if(!is_dir( $target_path_signature ))
        {
            mkdir( $target_path_signature ,0777, true ) or die ("Could not create directory");
        }            

        $target_path_service = $target_path_signature . "service/";

        if(!is_dir( $target_path_service ))
        {
            mkdir( $target_path_service ,0777, true ) or die ("Could not create directory");
        }            
        
        $photo = GetSafeValueString($_REQUEST["photo"],"string");
        $formid = GetSafeValueString($_REQUEST["formid"],"string");
        $serviceid = GetSafeValueString($_REQUEST["serviceid"],"string");
        
        $response["status"] = "successful";                     
        $image = base64_decode($photo);
        file_put_contents($_SERVER['DOCUMENT_ROOT'].$subdir."/customer/".$customerno."/".$devicekey."/photos/service/".$serviceid."_".$formid."_".strtotime(date("y-m-d h:i:s")).".jpeg", $image);
        
        // Service Call Manager
        include_once("../../../lib/wbo/SCVF1Manager.php");
        $scm = new SCVF1Manager($customerno);        
        $scm->populated_data($json,$serviceid,$formid);
    }
echo json_encode($response);
?>