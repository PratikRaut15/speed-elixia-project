<?php
include_once("session/sessionhelper.php");
include_once("db.php");
include_once("lib/system/utilities.php");
include_once("lib/bo/GeofenceManager.php");


$customerno = GetCustomerno();
$gm = new GeofenceManager($customerno);
$trackeeid= GetSafeValueString( isset( $_POST["t"])? $_POST["t"]:"","string");

if(isset($trackeeid))
{
    $id = $gm->getfenceid_from_trackeeid($trackeeid);
    if(isset($id) && $id > 0)
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