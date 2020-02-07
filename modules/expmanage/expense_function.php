<?php
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/DriverManager.php';
include_once '../../lib/bo/DeliveryManager.php';
include_once '../../lib/system/utilities.php';
include_once '../../lib/comman_function/reports_func.php';

if(!isset($_SESSION)){
    session_start();
}

function addamount_driver($data){
$dlm = new DeliveryManager($data['customerno']);
$dlm = $dlm->add_amount_driver($data);  
return $dlm;
}

function get_totalfund($driverid,$customerno){
    $dlm = new DeliveryManager($customerno);
    $totalamt = $dlm->get_all_totalfund($driverid);  
    return $totalamt; 
}

function get_allcategory($customerno){
    $dlm = new DeliveryManager($customerno);
    $res = $dlm->getcategory($customerno);
    return $res;
}

function get_editcategory($customerno,$catid){
    $dlm = new DeliveryManager($customerno);
    $res = $dlm->geteditcategory($customerno,$catid);
    return $res;
}

function getexp_data($expid,$customerno){
    $dlm = new DeliveryManager($customerno);
    $res = $dlm->geteditexpense($customerno,$expid);
    return $res;
}


function addcategory($data,$customerno){
    $dlm = new DeliveryManager($customerno);
    $res = $dlm->addcategory($data);
    return $res;
}

function editcategory($data,$customerno){
    $dlm = new DeliveryManager($customerno);
    $res = $dlm->editcategory($data);
    return $res;
}

function deletecategory($data,$customerno){
    $dlm = new DeliveryManager($customerno);
    $res = $dlm->deletecategory($data);
    return $res;
}


function getdrivers_allocated()
{
    $drivermanager = new DriverManager($_SESSION['customerno']);
    $drivers = $drivermanager->get_all_drivers_with_vehicles_allocated();
    return $drivers;
}

function get_expense($customerno){
    $dlm = new DeliveryManager($customerno);
    $res = $dlm->get_expense($customerno);
    return $res;
}


function editexpense($data,$customerno){
    $dlm = new DeliveryManager($customerno);
    $res = $dlm->edit_expense($customerno,$data);
    return $res;
}

function addexpense($data,$customerno){
    $dlm = new DeliveryManager($customerno);
    $res = $dlm->add_expense($data);
    return $res;
}


?>