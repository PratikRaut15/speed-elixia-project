<?php

//file required
error_reporting(E_ALL ^E_STRICT);
ini_set('display_errors', 'on');
require_once("class/config.inc.php");
require_once("class/class.api.php");

$apiobj = new api();

extract($_REQUEST);

if ($action == "register") {
    $test = $apiobj->register($name, $email, $phone, $type, $desc, $otpverified, $city, $institution, $image);
}

if ($action == "otpverification") {
    $test = $apiobj->otpverification($lastid, $otp);
}

if ($action == "pullrequests") {
    $test = $apiobj->pullrequests();
}

?>