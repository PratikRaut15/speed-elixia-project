<?php
//file required
require_once("class/config.inc.php");
require_once("class/class.api.php");
//require_once("class/class.sqlite.php");
ob_start("ob_gzhandler");
//ojbect creation
$apiobj = new api();

extract($_REQUEST);


if($action == "pullreasons")
{
    $test = $apiobj->pull_reasons($device);
}
if($action == 'checkregistration')
{
    $test = $apiobj->checkregistration($androidid);
}
if($action == "submitregistration")
{
    $test = $apiobj->submitregistration($device, $androidid);
}
if($action == "pullorders")
{
    $test = $apiobj->pullorders($device);
}

if($action == "pullorderdetails")
{
    $test = $apiobj->pullorderdetail($device, $orderid);
}

if($action == "pushcancellation")
{
    $test = $apiobj->pushcancellation($device, $orderid, $reasonid);
}
  
if($action == "pushsignature")
{
    $test = $apiobj->pushsignature($device, $orderid, $signature);
}





?>