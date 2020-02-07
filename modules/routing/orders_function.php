<?php
include_once "../../deliveryapi/class/config.inc.php";
include_once '../../lib/system/utilities.php';
include_once '../../lib/bo/CustomerManager.php';
include_once '../../lib/bo/MappingManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/DeliveryManager.php';
include_once '../../lib/bo/AlgoManager.php';
include_once '../reports/map_popup.php';
include_once '../../lib/comman_function/reports_func.php';
include_once '../../lib/bo/GeoCoder.php';

if (!isset($_SESSION)) {
 session_start();
}

function pullorders() {
    $dm = new DeliveryManager($_SESSION['customerno']);
    $data = $dm->pullorders();
    $datatest = array();
    $location="";
    if(isset($data)){
    foreach($data as $row){ 
            $order_id = $row->order_id;
            $zonename = $row->zonename;
            $areaname = $row->areaname;
            $flat = $row->flat;
            $building = $row->building;
            $city = $row->city;
            $landmark = $row->landmark;
            $slot = $row->slot;
            $delivery_date = $row->delivery_date;
            $orderdate = $row->orderdate;
            $statusname = $row->statusname;
            $id = $row->id;
            $editlink = $row->editlink;
            $route = $row->route;
            $id = $row->id;
            $delivery_lat = $row->delivery_lat;
            $delivery_long = $row->delivery_long;
            $realname = $row->realname;
            $signature = $row->signature;
            $photo = $row->photo;
            $geocoder = new GeoCoder($_SESSION['customerno']);
            if(!empty($delivery_lat) && !empty($delivery_long)){
                $location = $geocoder->get_location_bylatlong($delivery_lat, $delivery_long);
            }
            
            $datatest[] = array(
                'order_id' => $order_id,
                'zonename' => $zonename,
                'areaname' => $areaname,
                'flat' => $flat,
                'building' => $building,
                'city' => $city,
                'landmark' => $landmark,
                'slot' => $slot,
                'delivery_date' => $delivery_date,
                'signlocation' => $location,
                'orderdate' => $orderdate,
                'statusname' => $statusname,
                'delboyname'=>$realname,    
                'editlink' => $editlink,
                'signature' => $signature,
                'photo' => $photo,
                'route' => $route,
                'id' => $id
            );    
    }
    }
     
     return $datatest;
}


?>