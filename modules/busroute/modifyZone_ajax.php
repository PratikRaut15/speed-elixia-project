<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

include_once 'zone_functions.php';

$customerno = exit_issetor($_SESSION['customerno']);
$userid = exit_issetor($_SESSION['userid']);
$raw_data = exit_issetor($_POST['data']);
$data = json_decode($raw_data);

if(empty($data)){
    exit('Zone not edited');
}

$zone_arr = array();
$insert_arr = array();
foreach($data as $s){
    $zone_arr[] = $zoneid = (int)exit_issetor($s->zoneid);
    $latLong = exit_issetor($s->latLong);
    
    foreach($latLong as $ll){
        $ll_arr = explode(',',$ll);
        $lat = (float) $ll_arr[0];
        $long = (float) $ll_arr[1];
        $insert_arr[] = " ($customerno, $zoneid, '$lat', '$long', $userid) ";
    }
}

$gm = new ZoneManager($customerno);
$gm->modify_multiple_zones($zone_arr, $insert_arr, $userid);

echo "Zone Updated Successfully";


?>