<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
require_once("class/config.inc.php");
require_once("class/class.api.php");
//require_once("class/class.sqlite.php");
ob_start("ob_gzhandler");
//ojbect creation
$apiobj = new api();

extract($_REQUEST);


if($action=="login"){
        $test=$apiobj->check_login($username,$password);
 
}

if($action=="pickups"){
    $test=$apiobj->pullorders_pending($userkey);
    $apiobj->updateLogin($userkey);
}
if($action == "pullreasons")
{
    $test = $apiobj->pull_reasons($userkey);
}

if($action=="vendors"){
    $test=$apiobj->pullvendors($userkey);
    $apiobj->updateLogin($userkey);
}

if($action=="clients"){
    $test=$apiobj->pullclients($userkey);
    $apiobj->updateLogin($userkey);
}


if($action == "orderdetail")
{
    $test = $apiobj->pullorder_details($userkey, $orderid);
    $apiobj->updateLogin($userkey);
}

if($action == "cancel")
{
    $test = $apiobj->pushstatus($userkey, $pickupids, $reasonid);
    $apiobj->updateLogin($userkey);
}
if($action == "signature")
{
    $test = $apiobj->pushsignature($userkey, $pickupids, $signature);
    $apiobj->updateLogin($userkey);
}

if($action == "pushphoto")
{
    $test = $apiobj->pushphoto($userkey, $pickupids, $photo);
    $apiobj->updateLogin($userkey);
}

?>