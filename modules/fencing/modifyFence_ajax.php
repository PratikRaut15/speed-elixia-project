<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
include_once 'fence_functions.php';
$customerno = exit_issetor($_SESSION['customerno']);
if($_POST['action']=='deleteFence' && ($_POST['fenceId']!='' || $_POST['fenceId']!='null' || $_POST['fenceId']!=null))
{
    $gm = new GeofenceManager($customerno);
    $gm->deleteFence($_POST['fenceId']);     
    echo"OK";     
}




$userid = exit_issetor($_SESSION['userid']);
$raw_data = exit_issetor($_POST['data']);
$data = json_decode($raw_data);

if(empty($data)){
    exit('Fence not edited');
}

$fence_arr = array();
$insert_arr = array();


foreach($data as $keyOuter=>$s){ 
    $fence_arr[] = $fenceid = (int)exit_issetor($s->fenceid);
    $latLong = exit_issetor($s->latLong);
    
    /* foreach($latLong as $ll){
        $ll_arr = explode(',',$ll);
        $lat = (float) $ll_arr[0];
        $long = (float) $ll_arr[1];
        $insert_arr[] = " ($customerno, $fenceid, '$lat', '$long', $userid) ";
    } */
    $latLong = [];
    $ploygonLatLongJsonArray = [];
    foreach($s->latLong as $key=>$ll){
        $ll_arr = explode(',',$ll);
        $lat = $ll_arr[0];
        $long = $ll_arr[1];
        $ploygonLatLongJsonArray/* [$keyOuter] */[$key]['cgeolat'] = $lat;
        $ploygonLatLongJsonArray/* [$keyOuter] */[$key]['cgeolong'] = $long; 
        //$insert_arr[] = " ($customerno, $fenceid, '$lat', '$long', $userid) ";
    }
    $insert_arr[$keyOuter]['customerno'] = $customerno;
    $insert_arr[$keyOuter]['fenceid'] = $fenceid;
    $insert_arr[$keyOuter]['ploygonLatLongJsonArray'] = json_encode($ploygonLatLongJsonArray);
    $insert_arr[$keyOuter]['userid'] = $userid;
}
/* echo"Array data: <pre>";
print_r($insert_arr); exit(); */
$gm = new GeofenceManager($customerno);
$gm->modify_multiple_fences($fence_arr, $insert_arr, $userid,$insert_arr);

echo "Fence Updated Successfully";


?>