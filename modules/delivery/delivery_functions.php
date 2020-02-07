<?php

include_once '../../lib/system/utilities.php';
include_once '../../lib/bo/DeliveryManager.php';
include_once '../../lib/bo/RouteManager.php';

if(!isset($_SESSION))
{
    session_start();
}
class VODatacap {}
function get_status() 
{
    $dm = new DeliveryManager($_SESSION['customerno']);
    $status = $dm->getstatus();
    return $status;
}

function get_status_byid($statusid)
{
    $dm = new DeliveryManager($_SESSION['customerno']);
    $status = $dm->getstatus_byid($statusid);
    return $status;
}

function get_reason_byid($statusid)
{
    $dm = new DeliveryManager($_SESSION['customerno']);
    $status = $dm->getreason_byid($statusid);
    return $status;
}


function edit_status_byid($status, $statusid)
{
    $dm = new DeliveryManager($_SESSION['customerno']);
    $status = $dm->editstatus($status,$statusid);
    return $status;
}

function edit_reason_byid($status, $statusid)
{
    $dm = new DeliveryManager($_SESSION['customerno']);
    $status = $dm->editreason($status,$statusid);
    return $status;
}


function get_history($orderid) 
{
    $dm = new DeliveryManager($_SESSION['customerno']);
    $status = $dm->gethistory($orderid);
    return $status;
}

function get_route($routeid) 
{
    $dm = new RouteManager($_SESSION['customerno']);
    $status = $dm->getRoute($routeid);
    return $status;
}

function get_devicekey($routeid) 
{
    $dm = new RouteManager($_SESSION['customerno']);
    $status = $dm->getdevicekey($routeid);
    return $status;
}

function get_reasons()
{
    $dm = new DeliveryManager($_SESSION['customerno']);
    $reason = $dm->getreason();
    return $reason;
}

function add_status($status){
    $dm = new DeliveryManager($_SESSION['customerno']);
    $status = $dm->addstatus($status);
    return $status;
}

function add_reason($status){
    $dm = new DeliveryManager($_SESSION['customerno']);
    $status = $dm->addreason($status);
    return $status;
}
function get_orders()
{
    $dm = new DeliveryManager($_SESSION['customerno']);
    $orders = $dm->getorders();
    return $orders;
    
}
function get_order_byid($orderid)
{
    $dm = new DeliveryManager($_SESSION['customerno']);
    $orders = $dm->getordersbyid($orderid);
    return $orders;
    
}
function get_order_item_byid($orderid)
{
    $dm = new DeliveryManager($_SESSION['customerno']);
    $orders = $dm->getordersitembyid($orderid);
    return $orders;
    
}

function get_billing_address($orderid)
{
    $dm = new DeliveryManager($_SESSION['customerno']);
    $orders = $dm->getBillingAddress($orderid);
    return $orders;
    
}

function get_shipping_address($orderid)
{
    $dm = new DeliveryManager($_SESSION['customerno']);
    $orders = $dm->getShippingAddress($orderid);
    return $orders;
    
}

function getroutes()
{
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routes = $routemanager->get_all_routes();
    return $routes;
}

?>