<?php
include_once("../../lib/system/utilities.php");

class json{
// Empty class
}

if(isset($_GET["trackeeids"]) && isset($_GET["customerno"]) && isset($_GET["userkey"]))
{
    $jsonstatus = new json();
    $trackeeids = GetSafeValueString($_GET["trackeeids"],"string");
    $customerno = GetSafeValueString($_GET["customerno"],"string");
    $userkey = GetSafeValueString($_GET["userkey"],"string");
    $trackees = explode(",", $trackeeids);    
    
    include_once("../../lib/bo/TrackManager.php");
    $tm = new TrackManager();
    if(isset($trackees))
    {
        $jsonstatus->details = Array();
        $jsonstatus->status = "success";
        foreach($trackees as $thistrackeeid)
        {
            $details = $tm->pulldetails($customerno, $thistrackeeid);
            $jsonstatus->details[] = $details;
        }
    }
    else
    {
        $jsonstatus->status = "failure";
    }    
}
else
{
    $jsonstatus->status = "failure";
}    
echo json_encode($jsonstatus);
?>