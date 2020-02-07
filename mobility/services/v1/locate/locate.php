<?php
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");
require 'files/push_sqlite.php';

class json{
// Empty class
}

if(isset($_REQUEST['devicekey']) && $_REQUEST['devicekey'] != "" && isset($_REQUEST['customerno']))
{
    $devicelat = GetSafeValueString($_REQUEST["latitude"],"string");
    $devicelong = GetSafeValueString($_REQUEST["longitude"],"string");
    $devicekey = GetSafeValueString($_REQUEST["devicekey"],"string");    
    $customerno = GetSafeValueString($_REQUEST["customerno"],"string");
    
    $target_path_customer = $_SERVER['DOCUMENT_ROOT'].$subdir."//customer/".$customerno."/".$devicekey."/";    
    if(!is_dir( $target_path_customer ))
    {
        mkdir( $target_path_customer ,0777, true ) or die ("Could not create directory");
    }    

    $target_path = $target_path_customer."sqlitefiles/";    
    if(!is_dir( $target_path ))
    {
        mkdir( $target_path ,0777, true ) or die ("Could not create directory");
    }    

    $target_path_locate = $target_path."locate/";    
    if(!is_dir( $target_path_locate ))
    {
        mkdir( $target_path_locate ,0777, true ) or die ("Could not create directory");
    }    
    
    include_once("../../../lib/wbo/TrackManager.php");
    $rm = new TrackManager($customerno);
    $data = $rm->getdatafromdevicekey($devicekey);            
    $rm->updatedevicedata($devicelat, $devicelong, $devicekey);
    
    $lastupdated = date("Y-m-d H:i:s");            
    
    ChkSqlite($customerno,$devicekey,$lastupdated,$data->deviceid,$devicelat,$devicelong,$data->trackeeid);
}
?>