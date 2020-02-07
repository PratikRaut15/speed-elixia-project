<?php
include_once("../../lib/system/utilities.php");

class json{
// Empty class
}

if(isset($_GET["username"]) && isset($_GET["password"]))
{
    $jsonstatus = new json();
    $username = GetSafeValueString($_GET["username"],"string");
    $password = GetSafeValueString($_GET["password"],"string");

    include_once("../../lib/bo/TrackManager.php");
    $tm = new TrackManager();
    $user = $tm->checklogin($username, $password);    
    if(isset($user))
    {
        $jsonstatus->status = "success";        
        $jsonstatus->customerno = $user->customerno;
        $jsonstatus->userkey = $user->userkey;
        $jsonstatus->userid = $user->userid;        
        $jsonstatus->trackees = Array();
        $trackees = $tm->pulltrackees($user->customerno);        
        if(isset($trackees))
        {
            foreach($trackees as $thistrackee)
            {
                $jsonstatus->trackees[] = $thistrackee;            
            }
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