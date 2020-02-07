<?php
//error_reporting(E_ALL ^E_STRICT);
//ini_set('display_errors', 'on');

include_once 'class/config.inc.php';
include_once 'class/class.api.php';
include_once "../lib/comman_function/reports_func.php";
include_once "function/get_location.php";


$action = exit_issetor($_REQUEST['action'], failure('Please mention the action'));

$userkey = exit_issetor($_REQUEST['userkey'], failure('Userkey not found'));
$userdetails = new stdClass;
$userdetails->userkey = $userkey;
$api = new api();
$user = $api->getUser($userdetails);
if(!$user){ echo failure('User not found');exit; }
    
$data = exit_issetor($_REQUEST['data'], failure('Data not found'));
$dd = (array)json_decode($data); //framed object to array

/**********decision making part**********/
if($action=='add'){
    add_slot($dd, $user);
}
elseif($action=='delete'){
    delete_slot($dd, $user);
}
elseif($action=='edit'){
    edit_slot($dd, $user);
}
else{
    echo failure('Action not found');exit;
}
/**********decision making part**********/



function add_slot($dd, $user){
    $d = array();
    $d['slotid'] = exit_issetor($dd['slotid'],failure('Slotid not found'));
    $d['starttime'] = exit_issetor($dd['starttime'],failure('Start-time not found'));
    $d['endtime'] = exit_issetor($dd['endtime'],failure('End-time not found'));
    
    global $api;
    
    $sl = $api->is_slot_exists($user['customerno'],$d['slotid']);
    if($sl){ echo failure('Slot-id already exists');exit; }
    
    $status = $api->add_slot($user, $d);
    if($status!=0){
        echo success();exit;
    }
    else{
        echo failure('Unable to add Zone');exit;
    }
}

function edit_slot($dd, $user){
    $d = array();
    $d['slotid'] = exit_issetor($dd['slotid'],failure('Slotid not found'));
    $d['starttime'] = exit_issetor($dd['starttime'],failure('Start-time not found'));
    $d['endtime'] = exit_issetor($dd['endtime'],failure('End-time not found'));
    
    global $api;
    
    $sl = $api->is_slot_exists($user['customerno'],$d['slotid']);
    if(!$sl){ echo failure('Slot-id not found in our db');exit; }
    
    $status = $api->edit_slot($user, $d);
    if($status){
        echo success();exit;
    }
    else{
        echo failure('Unable to add Slot');exit;
    }
}

function delete_slot($dd, $user){
    $d = array();
    $d['slotid'] = exit_issetor($dd['slotid'],failure('Slotid not found'));
    
    global $api;
    
    $sl = $api->is_slot_exists($user['customerno'],$d['slotid']);
    if(!$sl){ echo failure('Slot-id not found in our db');exit; }
    
    $status = $api->delete_slot($user, $d);
    if($status){
        echo success();exit;
    }
    else{
        echo failure('Unable to delete Slot');exit;
    }
}