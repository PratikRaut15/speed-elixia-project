<?php
include_once("session/sessionhelper.php");
include_once("db.php");
include_once("lib/system/utilities.php");
include_once("lib/bo/DeviceManager.php");


$customerno = GetCustomerno();
if($customerno>0)
{
    $jsondeviceid= GetSafeValueString( isset( $_POST["ds"])? $_POST["ds"]:"","string");
    $deviceid = json_decode($jsondeviceid);
    
    if(isset($deviceid))
    {
        foreach($deviceid as $thisdeviceid)
        {
            $dm = new DeviceManager( $customerno);
            $dm->demapdevice($thisdeviceid);
        }
    }
    exit;
}
?>