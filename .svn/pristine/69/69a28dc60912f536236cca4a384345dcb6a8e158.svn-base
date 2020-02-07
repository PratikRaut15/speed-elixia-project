<?php

//file required
//error_reporting(E_ALL ^E_STRICT);
//ini_set('display_errors', 'on');
require_once("class/config.inc.php");
require_once("class/class.api.php");
require_once("class/class.sqlite.php");
require_once("class/class.geo.coder.php");
ob_start("ob_gzhandler");
//ojbect creation
$apiobj = new api();

extract($_REQUEST);


if ($action == "pullcredentials") {
    $test = $apiobj->check_login($username, $password);
}

if ($action == "pullcrm") {
    $test = $apiobj->pullcrm($userkey);
}

if ($action == "pulldetails") {
    $test = $apiobj->device_list($userkey);
    $apiobj->updateLogin($userkey, $phone, $version);
    exit;
}

if ($action == "whpulldetails") {
    $test = $apiobj->device_list_wh($userkey);
    $apiobj->updateLogin($userkey, $phone, $version);
    exit;
}

if ($action == "pullvehicledetails") {
    $test = $apiobj->device_list_details($userkey, $vehicleid);
    $apiobj->updateLogin($userkey, $phone, $version);
}

if ($action == "whpullvehicledetails") {
    $test = $apiobj->device_list_details_wh($userkey, $vehicleid);
    $apiobj->updateLogin($userkey, $phone, $version);
}

if ($action == "pushbuzzer") {  //for buzzer 
    $test = $apiobj->pushbuzzer($userkey, $vehicleid, $status);
}

if ($action == "pushmobiliser") {  //for mobiliser 
    $test = $apiobj->pushmobiliser($userkey, $vehicleid, $status);
}

if ($action == "freeze") {   //for freeze  vehicle location  (fstatus=1 => freeze vehicle // fstatus=0 => Unfreeze)
    $test = $apiobj->freezevehicle($userkey, $vehicleid, $status);
}

if ($action == "pullvehiclebyname") {
    $test = $apiobj->device_list_byname($userkey, $vehicleno);
}


if ($action == "clientcode") {
    $test = $apiobj->client_code_details($clientcode);
}

if ($action == "createclientcode") {
    $test = $apiobj->create_client_code($userkey, $clientcode);
}

if ($action == "registergcm") {
    $test = $apiobj->register_gcm($userkey, $regid);
}

if ($action == "unregistergcm") {
    $test = $apiobj->unregister_gcm($userkey);
}

if ($action == "summary") {
    $test = $apiobj->summary($userkey, $vehicleid, $date);
}

if ($action == "whsummary") {
    $test = $apiobj->summary_wh($userkey, $vehicleid, $date);
}

if ($action == "contractinfo") {
    $test = $apiobj->contractinfo($userkey, $pageno, $pagesize);
}

if ($action == "dashboard") {
    $test = $apiobj->dashboard($userkey);
    $apiobj->updateLogin($userkey, $phone, $version);
}
?>