<?php
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');

set_time_limit(0);
date_default_timezone_set("Asia/Calcutta");

$q = isset($_GET['q']) ? trim($_GET['q']) : exit('No inputs provided');
$params = explode('-', $q);

$report = isset($params[0]) ? trim($params[0]) : exit('Report not mentioned');
$type = isset($params[1]) ? $params[1] : 'pdf';
$customer_id = isset($params[2]) ? (int)$params[2] : exit('Customer id not found');
if($customer_id==0){
    exit('Customer id is Invalid');
}
$user_id = isset($params[3]) ? (int)$params[3] : exit('User id not found');
if($user_id==0){
    exit('User id is Invalid');
}
$gdate = isset($params[4]) ? trim($params[4]) : exit('Date not mentioned');
$date1 = date('d-m-Y', $gdate);
$date = date('d-m-Y',strtotime("-1 day ".$date1));


if($report == 'summary'){
    ob_start();
    require_once 'summary.php';
}
elseif($report == 'genset'){
    $veh_id = isset($_GET['v']) ? (int)$_GET['v'] : exit('Vehicle id not found');
    ob_start();
    require_once 'genset.php';
}
else{
    exit('Report name does not exists');
}

?>
