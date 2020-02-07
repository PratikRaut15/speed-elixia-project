<?php
include_once("../lib/system/utilities.php");

class json{
// Empty class
}

if(isset($_GET["deviceid"]))
{
    $jsonstatus = new json();
    $androidid = GetSafeValueString($_GET["deviceid"],"string");

    include_once("../lib/bo/RegistrationManager.php");
    $rm = new RegistrationManager();
    $registered = $rm->checkregistration($androidid);
    if(isset($registered))
    {
        $freqdata = $rm->getfreqdata($registered->customerno);
        $jsonstatus->status = "registered";
        $jsonstatus->customerno = $registered->customerno;
        $jsonstatus->devicekey = $registered->devicekey;
        $jsonstatus->trackeeid = $registered->trackeeid;
        $jsonstatus->pushitems = $registered->pushitems;
        $jsonstatus->pushmessages = $registered->pushmessages;
        $jsonstatus->freqdata = $freqdata;
    }
    else
    {
        $jsonstatus->status = "unregistered";
    }
    echo json_encode($jsonstatus);
}
?>