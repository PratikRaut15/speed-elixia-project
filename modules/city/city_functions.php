<?php
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/DriverManager.php';
include_once '../../lib/bo/NationManager.php';
include_once '../../lib/bo/StateManager.php';
include_once '../../lib/bo/DistrictManager.php';
include_once '../../lib/bo/CityManager.php';
include_once '../../lib/system/utilities.php';
if(!isset($_SESSION))
{
    session_start();
}
function addcity($name, $code, $address, $nationid, $stateid, $districtid)
{
    $districtid = GetSafeValueString($districtid,"string");
    $stateid = GetSafeValueString($stateid,"string");
    $name = GetSafeValueString($name,"string");
    $code = GetSafeValueString($code,"string");
    $nationid = GetSafeValueString($nationid,"string");
    $address = GetSafeValueString($address,"string");
    $citymanager = new CityManager($_SESSION['customerno']);
    $status = $citymanager->add_city($name, $code, $districtid, $address, $_SESSION['userid']); 
    return $status;
}
function editcity($cityid, $districtid, $stateid, $name, $code, $nationid, $address)
{
    $cityid = GetSafeValueString($cityid,"string");
    $districtid = GetSafeValueString($districtid,"string");
    $nationid = GetSafeValueString($nationid,"string");
    $stateid = GetSafeValueString($stateid,"string");
    $name = GetSafeValueString($name,"string");
    $code = GetSafeValueString($code,"string");
    $address = GetSafeValueString($address,"string");
    $citymanager = new CityManager($_SESSION['customerno']);
    $status = $citymanager->edit_city($cityid, $districtid, $name, $code, $stateid, $address, $_SESSION['userid']); 
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

function getcities($cityid)
{
    $citymanager = new CityManager($_SESSION['customerno']);
    $citys = $citymanager->get_all_cities($cityid);
    return $citys;
}

function getcity($cityid)
{
    $citymanager = new CityManager($_SESSION['customerno']);
    $citys = $citymanager->get_city($cityid);
    return $citys;
}

function delcity($cityid)
{
    $citymanager = new CityManager($_SESSION['customerno']);
    $citymanager->DeleteCity($cityid, $_SESSION['userid']);
}
?>