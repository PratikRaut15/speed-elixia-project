<?php
include_once("config.inc.php");
define('INCLUDE_ROOT', $_SERVER['DOCUMENT_ROOT'] . $subdir);
include_once("../../lib/system/utilities.php");

class json{
// Empty class
}

if(isset($_GET["devicekey"]))
{
    $jsonstatus = new json();
    $devicekey = GetSafeValueString($_GET["devicekey"],"string");
    $androidid = GetSafeValueString($_GET["androidid"],"string");    

    include_once("../../lib/wbo/RegistrationManager.php");
    $rm = new RegistrationManager();
    
    // Very Critical... Change it ASAP
    $rm->setversion('v3', $androidid);                
    $device = $rm->submitregistration($androidid, $devicekey);
    if(isset($device->customerno) && $device->customerno != "0")
    {
        include_once("../../lib/wbo/GeneralManager.php");
        $gm = new GeneralManager($device->customerno);                    
        $jsonstatus->custom = Array();        
        $jsonstatus->status = "successful";
        $jsonstatus->customerno = $device->customerno;     
        $jsonstatus->devicekey = $device->devicekey; 
        $jsonstatus->version = $device->version;
        if($device->trackeeid == 0)
        {
            $jsonstatus->trackeeid = 0;               
            $jsonstatus->pushservice = 0;   
            $jsonstatus->pushservicelist = 0;   
            $jsonstatus->pushfeedback = 0;               
            $jsonstatus->pushitems = 0;   
            $jsonstatus->pushmessages = 0;                       
        }
        else
        {
            $gm->updatepushes($device->trackeeid);
            $controls = $rm->getcontrols($device->trackeeid, $device->customerno);
            $jsonstatus->trackeeid = $device->trackeeid;   
            $jsonstatus->pushservice = $controls->pushservice;   
            $jsonstatus->pushservicelist = $controls->pushservicelist;               
            $jsonstatus->pushremarks = $controls->pushremarks;               
            $jsonstatus->pushfeedback = $controls->pushfeedback;                           
            $jsonstatus->pushitems = $controls->pushitems;   
            $jsonstatus->pushmessages = $controls->pushmessages;   
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
        $jsonstatus->customerno = 0;                
        $jsonstatus->devicekey = 0;                        
    }
    echo json_encode($jsonstatus);
}
?>