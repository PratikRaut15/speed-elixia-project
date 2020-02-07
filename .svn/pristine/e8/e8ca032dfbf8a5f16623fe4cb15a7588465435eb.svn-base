<?php
//file required
require_once("class/config.inc.php");
require_once("class/class.api.php");
require_once '../../lib/system/Log.php';

//ojbect creation
$apiobj = new api();

extract($_REQUEST);
if($action=="checkdata"){
$apiobj->catchdata($_REQUEST);
}
if($action=="login"){
$apiobj->check_login($username,$password);
}
if($action=="trackeedata"){
$apiobj->device_list($customerno);
}
if($action=="track"){
$apiobj->api_track($skey,$vehicleno);
}
if($action=="map_timer"){
$apiobj->api_device_list($vehicleno);
}
if($action=="event_history"){
$apiobj->event_history_access_handler($skey,$vehicleno,$startdate,$endtime);
}
if($action=="login_key"){
$apiobj->event_login_access_handler($skey);
}
if($action=="login_userkey_unsub"){
$apiobj->event_login_access_handler_unsub($key);
}



?>