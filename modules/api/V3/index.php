<?php
//file required
require_once("class/config.inc.php");
require_once("class/class.api.php");
require_once("class/class.sqlite.php");
ob_start("ob_gzhandler");
//ojbect creation
$apiobj = new api();

extract($_REQUEST);

 
if($action=="pullcredentials"){
 $test=$apiobj->check_login($username,$password);
}

if($action=="pulldetails"){
$test=$apiobj->device_list($userkey);
$apiobj->updateLogin($userkey);
$apiobj->updateLogin($userkey-1);
}

if($action=="pullvehicledetails"){
$test=$apiobj->device_list_details($userkey,$vehicleid);
$apiobj->updateLogin($userkey);
$apiobj->updateLogin($userkey-1);
}

if($action=="clientcode"){
$test=$apiobj->client_code_details($clientcode);
}

//$file = fopen("test.txt","a");
//fwrite($file,json_encode($_REQUEST).$test);
//fclose($file);

?>