<?php
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/DriverManager.php';
include_once '../../lib/bo/NationManager.php';
include_once '../../lib/bo/StateManager.php';
include_once '../../lib/bo/DistrictManager.php';
include_once '../../lib/system/utilities.php';
if(!isset($_SESSION))
{
    session_start();
}
function adddistrict($name, $code, $address, $nationid, $stateid)
{
    $stateid = GetSafeValueString($stateid,"string");
    $name = GetSafeValueString($name,"string");
    $code = GetSafeValueString($code,"string");
    $nationid = GetSafeValueString($nationid,"string");
    $address = GetSafeValueString($address,"string");
    $districtmanager = new DistrictManager($_SESSION['customerno']);
    $status = $districtmanager->add_district($name, $code, $stateid, $address, $_SESSION['userid']); 
    return $status;
}
function editdistrict($districtid, $stateid, $name, $code, $nationid, $address)
{
    $districtid = GetSafeValueString($districtid,"string");
    $nationid = GetSafeValueString($nationid,"string");
    $stateid = GetSafeValueString($stateid,"string");
    $name = GetSafeValueString($name,"string");
    $code = GetSafeValueString($code,"string");
    $address = GetSafeValueString($address,"string");
    $districtmanager = new DistrictManager($_SESSION['customerno']);
    $status = $districtmanager->edit_district($districtid, $stateid, $name, $code, $address, $_SESSION['userid']); 
    return $status;
}

function getnations($userid)
{
    $nationmanager = new NationManager($_SESSION['customerno']);
    $nations = $nationmanager->get_all_nations($userid);
    return $nations;
}

function getstates($userid)
{
    $statemanager = new StateManager($_SESSION['customerno']);
    $states = $statemanager->get_all_states($userid);
    return $states;
}

function getdistricts($userid)
{
    $districtmanager = new DistrictManager($_SESSION['customerno']);
    $districts = $districtmanager->get_all_districts($userid);
    return $districts;
}

function getdistrict($districtid)
{
    $districtmanager = new DistrictManager($_SESSION['customerno']);
    $districts = $districtmanager->get_district($districtid);
    return $districts;
}

function deldistrict($districtid)
{
    $districtmanager = new DistrictManager($_SESSION['customerno']);
    $districtmanager->DeleteDistrict($districtid, $_SESSION['userid']);
}
?>