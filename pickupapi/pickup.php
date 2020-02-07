<?php
//echo phpinfo();
//error_reporting(E_ALL ^E_STRICT);
//ini_set('display_errors', 'on');

require_once("class/config.inc.php");
require_once("class/class.api.php");
//require_once '../lib/bo/PointLocationManager.php';
$apiobj = new api();
//$t = file_get_contents("datajson.json");

if(isset($_REQUEST['cust']))
{
    $t = $_REQUEST['cust'];
    $customers = json_decode($t);
    $action = $customers->action;
    if($action=="add"){
        $test = $apiobj->add_pickup_customer($customers);
        print_r($test);    
    }else if($action=="edit"){
        $test = $apiobj->edit_pickup_customer($customers);
        print_r($test);    
    }else if($action=="delete"){
        $test = $apiobj->delete_pickup_customer($customers);
        print_r($test);    
    }    
}

if(isset($_REQUEST['vendor']))
{
    $t = $_REQUEST['vendor'];
    $vendor = json_decode($t);
    $action = $vendor->action;
    if($action=="add"){
        $test = $apiobj->add_pickup_vendor($vendor);
        print_r($test);    
    }else if($action=="edit"){
        $test = $apiobj->edit_pickup_vendor($vendor);
        print_r($test);    
    }else if($action=="delete"){
        $test = $apiobj->delete_pickup_vendor($vendor);
        print_r($test);    
    }    
}

if(isset($_REQUEST['pcb']))
{
    $t = $_REQUEST['pcb'];
    $pcb = json_decode($t);
    $action = $pcb->action;
    if($action=="add"){
        $test = $apiobj->add_pickup_pcb($pcb);
        print_r($test);    
    }else if($action=="edit"){
        $test = $apiobj->edit_pickup_pcb($pcb);
        print_r($test);    
    }else if($action=="delete"){
        $test = $apiobj->delete_pickup_pcb($pcb);
        print_r($test);    
    }    
}

if(isset($_REQUEST['ship']))
{
    $t = $_REQUEST['ship'];
    $ship = json_decode($t);
    $action = $ship->action;
    if($action=="add"){
        $test = $apiobj->add_pickup_shipment($ship);
        print_r($test);    
    }else if($action=="edit"){
        $test = $apiobj->edit_pickup_shipment($ship);
        print_r($test);    
    }else if($action=="delete"){
        $test = $apiobj->delete_pickup_shipment($ship);
        print_r($test);    
    } 
}



/**
 * 
 * Json File 
 * 
 $pod = file_get_contents("datajson.json");


    $t = $pod;
    $orders = json_decode($t);
    //print_r($t);
   $test = $apiobj->addPODOrder($orders);
   echo $test;

 */
?>