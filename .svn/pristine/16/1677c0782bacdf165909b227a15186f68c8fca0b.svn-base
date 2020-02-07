<?php

include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/DriverManager.php';
include_once '../../lib/bo/NationManager.php';
include_once '../../lib/bo/StateManager.php';
include_once '../../lib/bo/DistrictManager.php';
include_once '../../lib/bo/StateManager.php';
include_once '../../lib/bo/CityManager.php';
include_once '../../lib/bo/DealerManager.php';
include_once '../../lib/bo/GroupManager.php';
include_once '../../lib/system/utilities.php';
if(!isset($_SESSION))
{
    session_start();
}
function adddealer($name, $phoneno, $cellphone, $notes, $address, $vendor, $code,$cityid, $other1, $other2)
{
    $name = GetSafeValueString($name,"string");
    $phoneno = GetSafeValueString($phoneno,"string");
    $cellphone = GetSafeValueString($cellphone,"string");
    $notes = GetSafeValueString($notes,"string");
    $address = GetSafeValueString($address,"string");
    $vendor = GetSafeValueString($vendor,"string");
    $code = GetSafeValueString($code,"string");
    $cityid = GetSafeValueString($cityid,"string");    
    $dealermanager = new DealerManager($_SESSION['customerno']);
    $response = $dealermanager->add_dealer($name, $phoneno, $cellphone, $notes, $address, $vendor, $code, $_SESSION['userid'],$cityid,$other1, $other2); 
    return $response;
}
function editdealer($dealerid, $name, $phoneno, $cellphone, $notes, $address, $vendor, $code,$cityid,$other1, $other2)
{
    $dealerid = GetSafeValueString($dealerid,"string");
    $name = GetSafeValueString($name,"string");
    $phoneno = GetSafeValueString($phoneno,"string");
    $cellphone = GetSafeValueString($cellphone,"string");
    $notes = GetSafeValueString($notes,"string");
    $address = GetSafeValueString($address,"string");
    $vendor = GetSafeValueString($vendor,"string");
    $code = GetSafeValueString($code,"string");
    $cityid = GetSafeValueString($cityid,"string");    
    $dealermanager = new DealerManager($_SESSION['customerno']);
    $response = $dealermanager->edit_dealer($dealerid, $name, $phoneno, $cellphone, $notes, $address, $vendor, $code, $_SESSION['userid'],$cityid,$other1,$other2); 
    return $response;
}

function getdealers()
{
    $dealermanager = new DealerManager($_SESSION['customerno']);
    $dealers = $dealermanager->get_all_dealers();
    return $dealers;
}

function get_all_cities($nationid)
{
    $citymanager = new CityManager($_SESSION['customerno']);
    $cities = $citymanager->get_all_cities_nation($nationid);
    return $cities;    
}

function getdealer($dealerid)
{
    $dealermanager = new DealerManager($_SESSION['customerno']);
    $dealers = $dealermanager->get_dealer($dealerid);
    return $dealers;
}

function deldealer($dealerid)
{
    $dealermanager = new DealerManager($_SESSION['customerno']);
    $dealermanager->DeleteDealer($dealerid, $_SESSION['userid']);
}
?>