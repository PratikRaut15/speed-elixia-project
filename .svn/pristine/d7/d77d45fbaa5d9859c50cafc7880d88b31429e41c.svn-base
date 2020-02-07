<?php
include_once("session/sessionhelper.php");
include_once 'lib/bo/TrackeeManager.php';
include_once 'lib/bo/GeofenceManager.php';



$customerno = GetCustomerno();

$tm = new TrackeeManager($customerno);
$trackees = $tm->getfilteredtrackeesforcustomer();
if(isset($trackees))
{
    echo("<ul class='devicelist'>");
    foreach( $trackees as $thistrackee )
    {
        $gm = new GeofenceManager($customerno);
        $id = $gm->getfenceid_from_trackeeid($thistrackee->trackeeid);
        
        echo("<li id='t_" . $thistrackee->trackeeid . "' class='device " . (isset($id) && $id>0?"assigned":"") . "' onclick='st(" . $thistrackee->trackeeid . ")'>" );
        echo( $thistrackee->tname);
        echo('<input type="hidden" id="tl_' . $thistrackee->trackeeid . '" value="' . $thistrackee->trackeeid . '" /></li>');
    }
    echo("</ul>");
}
?>