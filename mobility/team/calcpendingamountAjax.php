<?php
include_once("../session.php");
include_once("../lib/system/utilities.php");

$customerno= GetSafeValueString(isset( $_POST["customerno"])? $_POST["customerno"]:"","string");
$month= GetSafeValueString(isset( $_POST["month"])? $_POST["month"]:"","string");
$year= GetSafeValueString(isset( $_POST["year"])? $_POST["year"]:"","string");

class json{
// Empty class
}

$jsonstatus = new json();

if(isset($customerno))
{    
    include_once("../lib/bo/SubFolderAjaxManager.php");
    $sm = new SubFolderAjaxManager($customerno);

    $data = $sm->getdevices();
    if(isset($data))
    {
        foreach($data as $thisdevice)
        {
            $sm->calculatepndgamt($thisdevice->actualstartdate, $thisdevice->contract, $month, $thisdevice->rate, $year, $thisdevice->deviceid);
        }
    }
    $totalreceipts = $sm->getreceiptsamount();
    $totalpending = $sm->getpendingamount();
    $jsonstatus = new json();
    $jsonstatus->pending = $totalpending - $totalreceipts;
    $finalresponse = json_encode($jsonstatus);
    echo($finalresponse);
}   
else
{
    echo("notok");
}
?>