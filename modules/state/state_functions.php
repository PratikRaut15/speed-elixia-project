<?php
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/DriverManager.php';
include_once '../../lib/bo/NationManager.php';
include_once '../../lib/bo/StateManager.php';
include_once '../../lib/system/utilities.php';
if(!isset($_SESSION))
{
    session_start();
}
function addstate($name, $code, $address, $nationid)
{
    $name = GetSafeValueString($name,"string");
    $code = GetSafeValueString($code,"string");
    $nationid = GetSafeValueString($nationid,"string");
    $address = GetSafeValueString($address,"string");
    $statemanager = new StateManager($_SESSION['customerno']);
    $status = $statemanager->add_state($name, $code, $nationid, $address, $_SESSION['userid']); 
    return $status;
}
function editstate($stateid, $name, $code, $nationid, $address)
{
    $nationid = GetSafeValueString($nationid,"string");
    $stateid = GetSafeValueString($stateid,"string");
    $name = GetSafeValueString($name,"string");
    $code = GetSafeValueString($code,"string");
    $address = GetSafeValueString($address,"string");
    $statemanager = new StateManager($_SESSION['customerno']);
    $status = $statemanager->edit_state($stateid, $name, $code, $nationid, $address, $_SESSION['userid']); 
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
    $nations = $statemanager->get_all_states($userid);
    return $nations;
}

function getstate($stateid)
{
    $statemanager = new StateManager($_SESSION['customerno']);
    $states = $statemanager->get_state($stateid);
    return $states;
}

function delstate($stateid)
{
    $statemanager = new StateManager($_SESSION['customerno']);
    $statemanager->DeleteState($stateid, $_SESSION['userid']);
}
?>