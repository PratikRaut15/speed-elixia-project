<?php

$devices = getdevicelist($_SESSION['customerno'],$_SESSION['userid']);

if(isset($devices))
{
    echo("<ul class='devicelist'>");
    foreach( $devices as $thisdevice )
    {
        echo("<li id='d_" . $thisdevice->deviceid . "' class='device " . (isset($thisdevice->salesid) && $thisdevice->salesid>0?"assigned":"") . "' onclick='sd(" . $thisdevice->deviceid . ")'>" );
        echo( $thisdevice->devicekey);
        echo('<input type="hidden" id="dl_' . $thisdevice->deviceid . '" value="' . $thisdevice->salesid . '" /></li>');
    }
    echo("</ul>");
}
?>