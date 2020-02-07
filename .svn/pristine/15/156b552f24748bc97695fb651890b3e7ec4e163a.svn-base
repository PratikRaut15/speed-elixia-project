<?php
include_once("session/sessionhelper.php");
include_once("db.php");
include_once("lib/system/utilities.php");
include_once("lib/bo/DeviceManager.php");



$customerno = GetCustomerno();
$dm = new DeviceManager($customerno);
$trackeeid= GetSafeValueString( isset( $_POST["t"])? $_POST["t"]:"","string");

if(isset($trackeeid))
{
    $device = $dm->getdevicefromtrackee($trackeeid);
    if(isset($device) && $device->trackeeid > 0)
    {
        echo("notok");
    }
    else 
    {
        echo("ok");
    }
}   
else
{
    echo("notok");
}
?>