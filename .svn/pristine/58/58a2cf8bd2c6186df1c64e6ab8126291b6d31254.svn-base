<?php
include_once("../session.php");
include_once("../lib/system/utilities.php");

class json{
// Empty class
}

$jsonstatus = new json();
$deviceid= GetSafeValueString(isset( $_POST["deviceid"])? $_POST["deviceid"]:"","string");
$customerno= GetSafeValueString(isset( $_POST["customerno"])? $_POST["customerno"]:"","string");

if(isset($customerno) && isset($deviceid))
{    
    include_once("../lib/bo/SubFolderAjaxManager.php");
    $sm = new SubFolderAjaxManager($customerno);

    $data = $sm->pulldevicerate($deviceid);
    if(isset($data))
    {
        $jsonstatus = new json();
        $jsonstatus->rate = $data->rate;
        $finalresponse = json_encode($jsonstatus);
        echo($finalresponse);
    }
    else
    {
        echo("notok");        
    }

}   
else
{
    echo("notok");
}
?>