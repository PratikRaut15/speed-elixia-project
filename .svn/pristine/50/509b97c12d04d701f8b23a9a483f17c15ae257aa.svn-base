<?php
include 'travelSettings_functions.php';

if(isset($_REQUEST['starttime']) && isset($_REQUEST['endtime']))
{   $starttime          = $_REQUEST['starttime'];
    $endtime            = $_REQUEST['endtime'];
    $travelsettingid    = isset($_REQUEST['travelsettingid'])?$_REQUEST['travelsettingid']:'0';
    $threshold          = $_REQUEST['threshold'];
    $customerno         = $_SESSION['customerno'];
    $creted_on          = date('Y-m-d h:i:s');
    $created_by         = $_SESSION['userid'];

    if(isset($travelsettingid)){

        $travelSettingId        = editTravelSetting($travelsettingid,$starttime,$endtime,$threshold,$customerno,$creted_on,$created_by);
    }
    else{
        $travelSettingId        = addTravelSetting($starttime,$endtime,$threshold,$customerno,$creted_on,$created_by);
    }

    echo $travelSettingId;
}

?>