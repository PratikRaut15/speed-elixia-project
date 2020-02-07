<?php
include_once("../session.php");
include_once("../lib/system/utilities.php");

class json{
// Empty class
}

$jsonstatus = new json();
$customerno= GetSafeValueString(isset( $_POST["customerno"])? $_POST["customerno"]:"","string");

if(isset($customerno))
{    
    include_once("../lib/bo/SubFolderAjaxManager.php");
    $sm = new SubFolderAjaxManager($customerno);

    $data = $sm->pulldatafromcustomer();
    if(isset($data))
    {
        $jsonstatus = new json();
        $jsonstatus->name = $data->name;
        $jsonstatus->company = $data->company;
        $jsonstatus->phone = $data->phone;
        $jsonstatus->email = $data->email;
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