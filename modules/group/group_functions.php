<?php
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/DriverManager.php';
include_once '../../lib/bo/GroupManager.php';
include_once '../../lib/bo/NationManager.php';
include_once '../../lib/bo/StateManager.php';
include_once '../../lib/bo/DistrictManager.php';
include_once '../../lib/bo/CityManager.php';
include_once '../../lib/system/utilities.php';
if(!isset($_SESSION))
{
    session_start();
}
function addgroup($groupname, $grouparray, $cityid, $code, $address)
{
    $groupname      = GetSafeValueString($groupname,"string");
    $grouparray     = GetSafeValueString($grouparray,"string");
    $cityid         = GetSafeValueString($cityid,"string");
    $code           = GetSafeValueString($code,"string");
    $address        = GetSafeValueString($address,"string");
    $groupmanager   = new GroupManager($_SESSION['customerno']);
    $groupmanager->add_group($groupname, $grouparray, $cityid, $code, $address, $_SESSION['userid']);
    echo "Group added successfully";
}
function editgroup($groupid, $grouparray, $groupname, $code, $cityid)
{
    $groupname = GetSafeValueString($groupname,"string");
    $groupid = GetSafeValueString($groupid,"string");
    $grouparray = GetSafeValueString($grouparray,"string");
    $code = GetSafeValueString($code,"string");
    $cityid = GetSafeValueString($cityid,"string");
    $groupmanager = new GroupManager($_SESSION['customerno']);
    $groupmanager->edit_group($groupid, $grouparray, $groupname, $code,$_SESSION['userid'],$cityid);
}
function getvehicles()
{
    $VehicleManager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $VehicleManager->get_all_vehicles();
    return $vehicles;
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
function getcities()
{
    $CityManager = new CityManager($_SESSION['customerno']);
    $cities = $CityManager->get_all_cities($_SESSION['userid']);
    return $cities;
}
function getvehiclesbydefaultid()
{
    $VehicleManager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $VehicleManager->get_vehicles_bygroupid_all();
    return $vehicles;
}
function getvehiclesbygroup($groupid)
{
    $VehicleManager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $VehicleManager->get_groups_vehicles($groupid);
    return $vehicles;
}
function get_group_name($groupn)
{
    $groupn = GetSafeValueString($groupn, 'string');
    $groupmanager = new GroupManager($_SESSION['customerno']);
    $groupss = $groupmanager->getallgroups();
    $status = NULL;
    if(isset($groupss))
    {
        foreach($groupss as $group)
        {
            if($group->groupname == $groupn)
            {
                $status = "notok";
                break;
            }
        }
        if(!isset($status))
        {
            $status = "ok";
        }
    }
    else
    {
        $status = "ok";
    }
    echo $status;
}
function getgroup($groupid)
{
    $groupmanager = new GroupManager($_SESSION['customerno']);
    $groups = $groupmanager->getgroupname($groupid);
    return $groups;
}
function getmappedgroup($userid)
{
    $groupmanager = new GroupManager($_SESSION['customerno']);
    $groups = $groupmanager->getallgroups();
    return $groups;
}

function getgroupdetail_hierarchy($userid)
{
    $groupmanager = new GroupManager($_SESSION['customerno']);
    $groups = $groupmanager->getallgroups_detail_hierarchy();
    return $groups;
}
function getgroupdetail($userid)
{
    $groupmanager = new GroupManager($_SESSION['customerno']);
    $groups = $groupmanager->getallgroups_detail();
    return $groups;
}

function delgroup($groupid)
{
    $groupmanager = new GroupManager($_SESSION['customerno']);
    $groupmanager->DeleteGroup($groupid, $_SESSION['userid']);
}
function getGroupRegion(){
     $groupmanager = new GroupManager($_SESSION['customerno']);
    $regions = $groupmanager->getRegions();
    return $regions;
}
function getZoneForRegions($regionId=''){
    $groupmanager = new GroupManager($_SESSION['customerno']);
    $zones = $groupmanager->getZoneList($regionId);
    return $zones;
}
function insertRegions($postDataArray){
   $groupmanager = new GroupManager($_SESSION['customerno']); 
   $groupmanager->insertIntoRegions($postDataArray);
}
function insertZones($postDataArray){
    $groupmanager = new GroupManager($_SESSION['customerno']); 
   $groupmanager->insertIntoZones($postDataArray);
}
function getStateForZones(){
    $groupmanager = new GroupManager($_SESSION['customerno']); 
  $states= $groupmanager->getStates();
  return $states;
}
function getAllGroupDetails($groupid){
    $groupmanager     = new GroupManager($_SESSION['customerno']); 
    $allDetails       = $groupmanager->getAllGroupDetails($groupid);
    return $allDetails;
}

?>