<?php
include_once("config.inc.php");
define('INCLUDE_ROOT', $_SERVER['DOCUMENT_ROOT'] . $subdir);
include_once("../../lib/system/utilities.php");
class json{
// Empty class
}

if(isset($_GET["deviceid"]))
{    
    $jsonstatus = new json();
    $androidid = GetSafeValueString($_GET["deviceid"],"string");

    include_once("../../lib/wbo/RegistrationManager.php");
    $rm = new RegistrationManager();
    $registered = $rm->checkregistration($androidid);
    if(isset($registered))
    {
        $jsonstatus->custom = Array();                
        $freqdata = $rm->getfreqdata($registered->customerno);
        $jsonstatus->status = "registered";
        $jsonstatus->customerno = $registered->customerno;
        $jsonstatus->devicekey = $registered->devicekey;
        $jsonstatus->trackeeid = $registered->trackeeid;
        $jsonstatus->pushitems = $registered->pushitems;
        $jsonstatus->pushmessages = $registered->pushmessages;
        $jsonstatus->pushservice = $registered->pushservice; 
        $jsonstatus->pushservicelist = $registered->pushservicelist;         
        $jsonstatus->pushremarks = $registered->pushremarks;        
        $jsonstatus->pushfeedback = $registered->pushfeedback;                 
        $jsonstatus->version = $registered->version;     
        if($registered->version != $version)
        {
            $jsonstatus->updatereqd = 1;
            $rm->setversion('v3', $androidid);                                        
        }
        else
        {
            $jsonstatus->updatereqd = 0;
        }
        $jsonstatus->freqdata = $freqdata;
        include_once("../../lib/wbo/GeneralManager.php");
        $gm = new GeneralManager($registered->customerno);       
        $checkcustom = $gm->checkcustom($registered->trackeeid);
        if($checkcustom)
        {        
            $customs = $gm->getcustomfields();
            if(isset($customs))
            {
                foreach($customs as $thiscustom)
                {
                    $jsonstatus->custom[] = $thiscustom;                
                }
                $gm->markcustomread($registered->trackeeid);
            }
        }        
    }
    else
    {
        $jsonstatus->status = "unregistered";
    }
    echo json_encode($jsonstatus);
}
?>