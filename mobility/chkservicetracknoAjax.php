<?php
include_once("session/sessionhelper.php");
include_once("db.php");
include_once("lib/system/utilities.php");
include_once("lib/bo/ServiceCallManager.php");

$customerno = GetCustomerno();
$scm = new ServiceCallManager($customerno);
$calls = $scm->gettrackingnos();
$trackno= GetSafeValueString(isset( $_POST["trackno"])? $_POST["trackno"]:"","string");

if(isset($calls))
{
    foreach($calls as $thiscall)
    {
        if($trackno == $thiscall->trackingno)
        {
            echo("notok");
        }
        else 
        {
            // Do nothing
        }
    }
    echo("ok");

}   
else
{
    echo("ok");
}
?>