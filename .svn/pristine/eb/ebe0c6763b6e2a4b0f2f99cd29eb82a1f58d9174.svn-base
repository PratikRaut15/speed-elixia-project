<?php
include_once("../../session.php");
include_once("../../lib/system/utilities.php");

class json{
// Empty class
}

if(isset($_GET["devicekey"]) && isset($_GET["customerno"]))
{
    $jsonstatus = new json();
    $devicekey = GetSafeValueString($_GET["devicekey"],"string");
    $customerno = GetSafeValueString($_GET["customerno"],"string");    

    include_once("../../lib/wbo/GeneralManager.php");
    $gm = new GeneralManager($customerno);        
    $device = $gm->getdatafromdevicekey($devicekey);
    
    if(isset($device->customerno))
    {
        $jsonstatus->custom = Array();
        $jsonstatus->status = "successful";
        $jsonstatus->tname = $device->tname;     
        $jsonstatus->lastupdated = $device->lastupdated;  
        $jsonstatus->trackeeid = $device->trackeeid;      
        $jsonstatus->itemdel = $device->itemdel;
        $jsonstatus->messaging = $device->messaging;        
        $jsonstatus->pushitems = $device->pushitems;
        $jsonstatus->pushmessages = $device->pushmessages;
        $jsonstatus->pushservice = $device->pushservice;   
        $jsonstatus->pushservicelist = $device->pushservicelist;           
        $jsonstatus->pushremarks = $device->pushremarks;           
        $jsonstatus->pushfeedback = $device->pushfeedback;                   
        $jsonstatus->service = $device->service;
        $checkcustom = $gm->checkcustom($device->trackeeid);
        if($checkcustom)
        {
            $customs = $gm->getcustomfields();
            if(isset($customs))
            {
                foreach($customs as $thiscustom)
                {
                    $jsonstatus->custom[] = $thiscustom;                
                }
                $gm->markcustomread($device->trackeeid);
            }
        }
        
    }
    else
    {
        $jsonstatus->status = "unsuccessful";
        $jsonstatus->tname = "";                
        $jsonstatus->lastupdated = "";  
        $jsonstatus->trackeeid = 0;                  
        $jsonstatus->itemdel = 0;
        $jsonstatus->messaging = 0;                
    }
    
    echo json_encode($jsonstatus);
}
?>