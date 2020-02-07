<?php
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/DriverManager.php';
include_once '../../lib/bo/NationManager.php';
include_once '../../lib/system/utilities.php';
if(!isset($_SESSION))
{
    session_start();
}
function addnation($name, $code, $address)
{
    $name = GetSafeValueString($name,"string");
    $code = GetSafeValueString($code,"string");
    $address = GetSafeValueString($address,"string");
    $nationmanager = new NationManager($_SESSION['customerno']);
    $nations = $nationmanager->add_nation($name, $code, $address, $_SESSION['userid']); 
    return $nations;
}
function editnation($nationid, $name, $code, $address)
{
    $nationid = GetSafeValueString($nationid,"string");
    $name = GetSafeValueString($name,"string");
    $code = GetSafeValueString($code,"string");
    $address = GetSafeValueString($address,"string");
    $nationmanager = new NationManager($_SESSION['customerno']);
    $nations = $nationmanager->edit_nation($nationid, $name, $code, $address, $_SESSION['userid']); 
    return $nations;
}

function getnations($userid)
{
    $nationmanager = new NationManager($_SESSION['customerno']);
    $nations = $nationmanager->get_all_nations($userid);
    return $nations;
}

function getnation($nationid)
{
    $nationmanager = new NationManager($_SESSION['customerno']);
    $nations = $nationmanager->get_nation($nationid);
    return $nations;
}

function delnation($nationid)
{
    $nationmanager = new NationManager($_SESSION['customerno']);
    $nationmanager->DeleteNation($nationid, $_SESSION['userid']);
}
?>