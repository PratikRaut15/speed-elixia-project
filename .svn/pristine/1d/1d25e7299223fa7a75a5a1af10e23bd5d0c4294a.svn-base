<?php

//echo phpinfo();
//error_reporting(E_ALL ^E_STRICT);
//ini_set('display_errors', 'on');

require_once("class/config.inc.php");
require_once("class/class.api.php");
require_once '../lib/bo/PointLocationManager.php';
$apiobj = new api();
//$t = file_get_contents("datajson.json");
if (isset($_REQUEST['order'])) {
    $t = $_REQUEST['order'];
    $orders = json_decode($t);
    //var_dump($orders[0]); die();
    //print_r($orders->item_details[0]->taxes);
    $test = $apiobj->addOrder($orders[0]);
    echo $test;
} else if (isset($_REQUEST['pod'])) {
    $t = $_REQUEST['pod'];
    $orders = json_decode($t);
    //print_r($t);
    $test = $apiobj->addPODOrder($orders);
    $testjson = json_encode($test);
    echo $testjson;
   
} else if (isset($_REQUEST['bill'])) {
    $t = $_REQUEST['bill'];
    $orders = json_decode($t);
    $lat = "";
    $long = "";
    $accuracy = "";
    $key = "AIzaSyAH-IFdjWpYslrqLKsBnQTwaCBEqiMdchc";
    $address = urlencode($orders->address);
    $address1 = urlencode($orders->landmark . ", " . $orders->area);
    $address2 = urldecode($orders->pincode);

    $google_api = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&region=in";
    $ch1 = curl_init();
    curl_setopt($ch1, CURLOPT_URL, $google_api);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    $results = curl_exec($ch1);
    $array_data = json_decode($results);
    if ($array_data->status === 'OK' && $array_data->results[0]->partial_match == 0) {
        $location = $array_data->results[0]->geometry->location;
        $lat = $location->lat;
        $long = $location->lng;
        $accuracy = 1;
    } else {
        $google_api1 = "https://maps.googleapis.com/maps/api/geocode/json?address=$address1&region=in";
        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, $google_api1);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        $results1 = curl_exec($ch1);
        $array_data1 = json_decode($results1);
        if ($array_data1->status === 'OK') {
            $location = $array_data1->results[0]->geometry->location;
            $lat = $location->lat;
            $long = $location->lng;
            $accuracy = 2;
        } else {
         $google_api2 = "https://maps.googleapis.com/maps/api/geocode/json?address=$address2&region=in"; 
           
            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_URL, $google_api2);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            $results2 = curl_exec($ch1);
            $array_data2 = json_decode($results2);
            if ($array_data2->status === 'OK') {
                $location = $array_data2->results[0]->geometry->location;
                $lat = $location->lat;
                $long = $location->lng;
                $accuracy = 3;
            }
        }
    }




    curl_close($ch1);
    if ($lat == '' && $long == "") {
        $accuracy = 0;
    }
    $test = $apiobj->getUser($orders);
    $fences = $apiobj->getfence($test['customerno']);
    $fences = json_decode($fences);
    $ids = array();
    $i = 1;
    foreach ($fences->result as $val) {
        $ids[] = array($val->fenceid, $val->fencename);
    }
    //print_r($ids);
    //$ids[] = array(86,'ABC');
    $point = $lat . " " . $long;
    //print_r($points); // Lat Long For Ghatkoper
    $i = 0;
    $pointLocation = new PointLocation();
    foreach ($ids as $id) {
        $geofence = $apiobj->get_geofence_from_fenceid($id[0]);
        if (isset($geofence)) {
            foreach ($geofence as $thisgeofence) {
                if ($id[0] == $thisgeofence->fenceid) {
                    $polygon[] = $thisgeofence->geolat . " " . $thisgeofence->geolong;
                }
            }
            if ($pointLocation->checkPointStatus($point, $polygon) == "inside") {

                $rt[] = $id[0];
            }
        }
    }
    //echo $rt[0];
    $orders->customerno = $test['customerno'];
    $orders->user = $test['userid'];
    $orders->lat = $lat;
    $orders->long = $long;
    $orders->accuracy = $accuracy;
    $orders->fenceid = $rt[0];
    if($rt[0] == null || $rt[0] == "")
    {
        $orders->fenceid = 0;
    }
    //print_r($orders);

    $test = $apiobj->addBillOrder($orders);
    $testjson = json_encode($test);
    echo $testjson;
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
