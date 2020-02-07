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
    
$for = exit_issetor($_REQUEST['for'], failure('Please mention the action for(zone or area).'));
$data = exit_issetor($_REQUEST['data'], failure('Data not found'));
$dd = (array)json_decode($data); //framed object to array

/**********decision making part**********/
if($action=='add'){
    switch ($for){
        case 'zone':
            add_zone($user,$dd);break;
        case 'area':
            add_area($user,$dd);break;
        default:
            echo failure('Action-for not found');exit;
    }
}
elseif($action=='delete'){
    switch ($for){
        case 'zone':
            delete_zone($user,$dd);break;
        case 'area':
            delete_area($user,$dd);break;
        default:
            echo failure('Action-for not found');exit;
    }
}
elseif($action=='edit'){
    switch ($for){
        case 'zone':
            edit_zonename($user,$dd);break;
        case 'area':
            edit_areaname($user,$dd);break;
        default:
            echo failure('Action-for not found');exit;
    }
    
}
else{
    echo failure('Action not found');exit;
}
/**********decision making part**********/


/*parse brackets of area-text*/
function parse_area($text){
    $areaname = str_replace('(', ', ', $text);
    $areaname = str_replace(')', '', $areaname);
    $areaname = trim($areaname);
    $areaname = array_filter(explode(' ', $areaname));
            
    $areaname_final = '';
    foreach($areaname as $s){
        if($s==','){
            $areaname_final = trim($areaname_final);
            $areaname_final .= ", ";
        }
        else{
            $areaname_final .= "$s ";
        }
    }
    return $areaname_final;
}


/*add zone*/
function add_zone($user,$dd){
    $d = array();
    $d['zoneid'] = (int)exit_issetor($dd['zoneid'], failure('Zone-id not found'));
    $zonename = exit_issetor($dd['zonename'], failure('Zone-name not found'));
    $d['zonename'] = required_exit($zonename,'Zone-name');
    
    global $api;
    $z = $api->is_zone_exists($user['customerno'],$d['zoneid']);
    if($z){ echo failure('Zone-id already exists');exit; }
    
    $status = $api->add_zone($d, $user['customerno']);
    if($status!=0){
        echo success();exit;
    }
    else{
        echo failure('Unable to add Zone');exit;
    }
}

/*add area*/
function add_area($user,$dd){
    $d = array();
    $d['zoneid'] = (int)exit_issetor($dd['zoneid'], failure('Zone-id not found'));
    $d['areaid'] = (int)exit_issetor($dd['areaid'], failure('Area-id not found'));
    $areaname = exit_issetor($dd['areaname'], failure('Area-name not found'));
    $d['areaname'] = parse_area(required_exit($areaname,'Area-name'));
    
    global $api;
    $z = $api->is_zone_exists($user['customerno'],$d['zoneid']);
    if(!$z){ echo failure('Zone-id not found in our db');exit; }
    
    $a = $api->is_area_exists($user['customerno'],$d['zoneid'], $d['areaid']);
    if($a){ echo failure('Zone/Area already exists');exit; }
    
    $location = get_google_location($d['areaname']);
    $d['lat'] = $location['lat'];
    $d['lng'] = $location['lng'];
    $status = $api->add_area($d, $user['customerno']);
    if($status!=0){
        echo success();exit;
    }
    else{
        echo failure('Unable to add Area');exit;
    }
}

/*delete zone*/
function delete_zone($user,$dd){
    $zoneid = (int)exit_issetor($dd['zoneid'], failure('Zone-id not found'));
    
    global $api;
    $z = $api->is_zone_exists($user['customerno'],$zoneid);
    if(!$z){ echo failure('Zone-id not found in our db');exit; }
    
    $status = $api->delete_zones($user['customerno'], $zoneid);
    if($status){
        echo success();exit;
    }
    else{
        echo failure('Unable to add delete zone');exit;
    }
}

/*delete area*/
function delete_area($user,$dd){
    $zoneid = (int)exit_issetor($dd['zoneid'], failure('Zone-id not found'));
    $areaid = (int)exit_issetor($dd['areaid'], failure('Area-id not found'));
    
    global $api;
    $za = $api->is_area_exists($user['customerno'],$zoneid, $areaid);
    if(!$za){ echo failure('Zone/Area not found in our db');exit; }
    
    $status = $api->delete_areas($user['customerno'], $zoneid, $areaid);
    if($status){
        echo success();exit;
    }
    else{
        echo failure('Unable to add delete zone/area');exit;
    }
}

function edit_zonename($user,$dd){
    $d = array();
    $d['zoneid'] = (int)exit_issetor($dd['zoneid'], failure('Zone-id not found'));
    $zonename = exit_issetor($dd['zonename'], failure('Zone-name not found'));
    $d['zonename'] = required_exit($zonename,'Zone-name');
    
    global $api;
    $z = $api->is_zone_exists($user['customerno'],$d['zoneid']);
    if(!$z){ echo failure('Zone not found in our db');exit; }
    
    $status = $api->edit_zone($user['customerno'], $d);
    if($status){
        echo success();exit;
    }
    else{
        echo failure('Unable to edit Zone');exit;
    }
}

function edit_areaname($user,$dd){
    $d = array();
    $d['zoneid'] = (int)exit_issetor($dd['zoneid'], failure('Zone-id not found'));
    $d['areaid'] = (int)exit_issetor($dd['areaid'], failure('Area-id not found'));
    $areaname = exit_issetor($dd['areaname'], failure('Area-name not found'));
    $d['areaname'] = parse_area(required_exit($areaname,'Area-name'));
    
    global $api;
    $za = $api->is_area_exists($user['customerno'],$d['zoneid'], $d['areaid']);
    if(!$za){ echo failure('Zone/Area not found in our db');exit; }
    
    $location = get_google_location($d['areaname']);
    $d['lat'] = $location['lat'];
    $d['lng'] = $location['lng'];
    $status = $api->edit_area($user['customerno'], $d);
    if($status){
        echo success();exit;
    }
    else{
        echo failure('Unable to edit Zone');exit;
    }
}

?>
