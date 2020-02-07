<?php
//file required
require_once("class/config.inc.php");
require_once("class/class.api.php");

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
if($action=="get_schema_list"){
$apiobj->get_schema_list($customerno,$devicekey);
}
if($action=="get_schema"){
$apiobj->get_schema_definations($customerno,$schema_id);
}
if($action=="push_active_data"){
$apiobj->populated_data($json,$serviceid,$customerno,$ftid);
}



?>