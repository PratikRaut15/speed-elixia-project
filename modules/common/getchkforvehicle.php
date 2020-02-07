<?php
session_start();
require_once("../../lib/system/utilities.php");
require_once("../../lib/components/ajaxpage.inc.php");
$dontredirect=true;// This is set so we don't ask for login details...
$ajaxpage = new ajaxpage();
$customerno = $_SESSION['customerno'];

class jsonop
{
    // Empty class!
}

include_once("../../lib/bo/CheckpointManager.php");
$checkpointmanager = new CheckpointManager($customerno);
$vehicleid = GetSafeValueString(isset( $_POST["vehicleid"])? $_POST["vehicleid"]:"","string");
$checkpoints = $checkpointmanager->getchksforvehicle($vehicleid);
$finaloutput = array();
if(isset($checkpoints))
{
    foreach ($checkpoints as $checkpoint)
    {
        $output = new jsonop();
        $output->cgeolat = $checkpoint->cgeolat;
        $output->cgeolong = $checkpoint->cgeolong;
        $output->cname = $checkpoint->cname;        
        $output->crad = $checkpoint->crad*1000;
        $finaloutput[] = $output;
    }
}
    $ajaxpage->SetResult($finaloutput);
    $ajaxpage->Render();
?>