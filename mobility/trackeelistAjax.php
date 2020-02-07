<?php
include_once("session/sessionhelper.php");
include_once 'lib/bo/TrackeeManager.php';
include_once 'lib/bo/DeviceManager.php';



$customerno = GetCustomerno();

$tm = new TrackeeManager($customerno);
$trackees = $tm->gettrackeesforcustomer();
if(isset($trackees))
{
    echo("<ul class='devicelist'>");
    foreach( $trackees as $thistrackee )
    {
        $dm = new DeviceManager($customerno);
        $selecteddevice = $dm->getdevicefromtrackee($thistrackee->trackeeid);
        echo("<li id='t_" . $thistrackee->trackeeid . "' class='device " . (isset($selecteddevice) && $selecteddevice->trackeeid>0?"assigned":"") . "' onclick='st(" . $thistrackee->trackeeid . ")'>" );
        echo( $thistrackee->tname);
        echo('<input type="hidden" id="tl_' . $thistrackee->trackeeid . '" value="' . $thistrackee->trackeeid . '" /></li>');
    }
    echo("</ul>");
}
?>