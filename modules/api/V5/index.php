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

 
if($action=="pullcredentials"){
 $test=$apiobj->check_login($username,$password);
}

if($action=="pullcrm"){
 $test=$apiobj->pullcrm($userkey);
}

if($action=="pulldetails"){
$test=$apiobj->device_list($userkey);
$apiobj->updateLogin($userkey);
}

if($action=="pullvehicledetails"){
$test=$apiobj->device_list_details($userkey,$vehicleid);
$apiobj->updateLogin($userkey);
}

if($action=="pullvehiclebyname"){
$test=$apiobj->device_list_byname($userkey,$vehicleno);
$apiobj->updateLogin($userkey);
}


if($action=="clientcode"){
 $test=$apiobj->client_code_details($clientcode);
}

if($action=="createclientcode"){
$test=$apiobj->create_client_code($userkey, $clientcode);
}

if($action=="registergcm"){
$test=$apiobj->register_gcm($userkey,$regid);
}

if($action=="unregistergcm"){
$test=$apiobj->unregister_gcm($userkey);
}

if($action=="summary"){
$test=$apiobj->summary($userkey, $vehicleid, $date);
}


if($action=="contractinfo"){
$test=$apiobj->contractinfo($userkey, $pageno, $pagesize);
}

if($action=="dashboard"){
$test=$apiobj->dashboard($userkey);
}



//$file = fopen("test.txt","a");
//fwrite($file,json_encode($_REQUEST).$test);
//fclose($file);

?>