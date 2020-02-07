<?php
//error_reporting(E_ALL ^E_STRICT);
//ini_set('display_errors', 'on');
require_once "class/config.inc.php";
require_once "class/class.api.php";
require_once '../lib/bo/PointLocationManager.php';
include_once "../lib/comman_function/reports_func.php";
include_once "function/get_location.php";
$apiobj = new api();
if (isset($_REQUEST['order'])) {
    $t = $_REQUEST['order'];
    $orders = json_decode($t);
    $test = $apiobj->addOrder($orders[0]);
    echo $test;
} elseif (isset($_REQUEST['pod'])) {
    $t = $_REQUEST['pod'];
    $orders = json_decode($t);
    $test = $apiobj->addPODOrder($orders);
    echo $test;
} elseif (isset($_REQUEST['bill'])) {
    $t = $_REQUEST['bill'];
    $orders = json_decode($t);
    if ($orders->slot == 100) {
        exit;
    }
    $areaid = (int) exit_issetor($orders->areaid, failure('Area-id not found'));
    $user = $apiobj->getUser($orders);
    $fenceid = $apiobj->get_zone_id($user['customerno'], $areaid);
    $areaname = retval_issetor($fenceid['areaname'], '');
    $lat = "";
    $long = "";
    $accuracy = 0;
    $address = urlencode($orders->address);
    $building_landmark_area = urlencode("$orders->building, $orders->street, $orders->landmark, $areaname, $orders->city");
    $street_landmark_area = urlencode("$orders->street, $orders->landmark, $areaname, $orders->city");
    $landmark_area = urlencode("$orders->landmark, $areaname, $orders->city");
    $key = "";
    $google_api1 = signUrl("http://maps.google.com/maps/api/geocode/json?address=$address&region=in&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
    $google_api2 = signUrl("http://maps.google.com/maps/api/geocode/json?address=$building_landmark_area&region=in&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
    $google_api3 = signUrl("http://maps.google.com/maps/api/geocode/json?address=$street_landmark_area&region=in&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
    $google_api4 = signUrl("http://maps.google.com/maps/api/geocode/json?address=$landmark_area&region=in&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
    //$key = "AIzaSyAH-IFdjWpYslrqLKsBnQTwaCBEqiMdchc"; //for demo use
    //$google_api1 = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&region=in&key=$key";
    //$google_api2 = "https://maps.googleapis.com/maps/api/geocode/json?address=$building_landmark_area&region=in&key=$key";
    //$google_api3 = "https://maps.googleapis.com/maps/api/geocode/json?address=$street_landmark_area&region=in&key=$key";
    //$google_api4 = "https://maps.googleapis.com/maps/api/geocode/json?address=$landmark_area&region=in&key=$key";
    $ch1 = curl_init();
    curl_setopt($ch1, CURLOPT_URL, $google_api1);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    $results1 = curl_exec($ch1);
    $array_data1 = json_decode($results1);
    $partial_match = retval_issetor($array_data1->results[0]->partial_match, null);
    if ($array_data1->status === 'OK' && $partial_match == 0) {
        $location = $array_data1->results[0]->geometry->location;
        $lat = $location->lat;
        $long = $location->lng;
        $accuracy = 1;
    } else {
        if ($orders->building != "") {
            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_URL, $google_api2);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            $results2 = curl_exec($ch1);
            $array_data2 = json_decode($results2);
            if ($array_data2->status === 'OK') {
                $location = $array_data2->results[0]->geometry->location;
                $lat = $location->lat;
                $long = $location->lng;
                $accuracy = 2;
            }
        }
        if ($accuracy == 0) {
            if ($orders->street != "") {
                $ch1 = curl_init();
                curl_setopt($ch1, CURLOPT_URL, $google_api3);
                curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); // return into a variable
                $results3 = curl_exec($ch1);
                $array_data3 = json_decode($results3);
                if (isset($array_data3->results[0]->address_components)) {
                    foreach ($array_data3->results[0]->address_components as $add) {
                        if ($add->types[0] == 'country') {
                            $cn = $add->long_name;
                        }
                    }
                }
                if ($array_data3->status === 'OK' && $cn == "India") {
                    $location = $array_data3->results[0]->geometry->location;
                    $lat = $location->lat;
                    $long = $location->lng;
                    $accuracy = 3;
                }
            }
            if ($accuracy == 0) {
                $ch1 = curl_init();
                curl_setopt($ch1, CURLOPT_URL, $google_api4);
                curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); // return into a variable
                $results4 = curl_exec($ch1);
                $array_data4 = json_decode($results4);
                if (isset($array_data4->results[0]->address_components)) {
                    foreach ($array_data4->results[0]->address_components as $add) {
                        if ($add->types[0] == 'country') {
                            $cn = $add->long_name;
                        }
                    }
                }
                if ($array_data4->status === 'OK' && $cn == "India") {
                    $location = $array_data4->results[0]->geometry->location;
                    $country = $array_data4->results[0]->address_components;
                    echo $country->short_name;
                    $lat = $location->lat;
                    $long = $location->lng;
                    $accuracy = 4;
                } else {
                    if ($fenceid['lat'] != null || $fenceid['lng'] != null) {
                        $lat = $fenceid['lat'];
                        $long = $fenceid['lng'];
                        $accuracy = 5;
                    }
                }
            }
        }
    }
    curl_close($ch1);
    $orders->customerno = $user['customerno'];
    $orders->user = $user['userid'];
    $orders->lat = $lat;
    $orders->long = $long;
    $orders->accuracy = $accuracy;
    $orders->fenceid = $fenceid['zone_id'];
    $orders->areaid = $areaid;
    $addBill = $apiobj->addBillOrder($orders);
    $json = json_encode($addBill);
    echo $json;exit;
} elseif (isset($_REQUEST['edit'])) {
    $t = $_REQUEST['edit'];
    $orders = json_decode($t);
    $areaid = (int) exit_issetor($orders->areaid, failure('Area-id not found'));
    $user = $apiobj->getUser($orders);
    exit_issetor($user['customerno']);
    $fenceid = $apiobj->get_zone_id($user['customerno'], $areaid);
    $areaname = retval_issetor($fenceid['areaname']);
    $lat = "";
    $long = "";
    $accuracy = 0;
    $address = urlencode($orders->address);
    $building_landmark_area = urlencode("$orders->building, $orders->street, $orders->landmark, $areaname, $orders->city");
    $street_landmark_area = urlencode("$orders->street, $orders->landmark, $areaname, $orders->city");
    $landmark_area = urlencode("$orders->landmark, $areaname, $orders->city");
    $key = "";
    $google_api1 = signUrl("http://maps.google.com/maps/api/geocode/json?address=$address&region=in&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
    $google_api2 = signUrl("http://maps.google.com/maps/api/geocode/json?address=$building_landmark_area&region=in&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
    $google_api3 = signUrl("http://maps.google.com/maps/api/geocode/json?address=$street_landmark_area&region=in&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
    $google_api4 = signUrl("http://maps.google.com/maps/api/geocode/json?address=$landmark_area&region=in&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
    /*
    $key = "AIzaSyAH-IFdjWpYslrqLKsBnQTwaCBEqiMdchc";
    $google_api1 = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&region=in&key=$key";
    $google_api2 = "https://maps.googleapis.com/maps/api/geocode/json?address=$building_landmark_area&region=in&key=$key";
    $google_api3 = "https://maps.googleapis.com/maps/api/geocode/json?address=$street_landmark_area&region=in&key=$key";
    $google_api4 = "https://maps.googleapis.com/maps/api/geocode/json?address=$landmark_area&region=in&key=$key";
     */
    $ch1 = curl_init();
    curl_setopt($ch1, CURLOPT_URL, $google_api1);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    $results1 = curl_exec($ch1);
    $array_data1 = json_decode($results1);
    $partial_match = retval_issetor($array_data1->results[0]->partial_match, null);
    if ($array_data1->status === 'OK' && $partial_match == 0) {
        $location = $array_data1->results[0]->geometry->location;
        $lat = $location->lat;
        $long = $location->lng;
        $accuracy = 1;
    } else {
        if ($orders->building != "") {
            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_URL, $google_api2);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            $results2 = curl_exec($ch1);
            $array_data2 = json_decode($results2);
            if ($array_data2->status === 'OK') {
                $location = $array_data2->results[0]->geometry->location;
                $lat = $location->lat;
                $long = $location->lng;
                $accuracy = 2;
            }
        }
        if ($accuracy == 0) {
            if ($orders->street != "") {
                $ch1 = curl_init();
                curl_setopt($ch1, CURLOPT_URL, $google_api3);
                curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); // return into a variable
                $results3 = curl_exec($ch1);
                $array_data3 = json_decode($results3);
                if (isset($array_data3->results[0]->address_components)) {
                    foreach ($array_data3->results[0]->address_components as $add) {
                        if ($add->types[0] == 'country') {
                            $cn = $add->long_name;
                        }
                    }
                }
                if ($array_data3->status === 'OK' && $cn == "India") {
                    $location = $array_data3->results[0]->geometry->location;
                    $lat = $location->lat;
                    $long = $location->lng;
                    $accuracy = 3;
                }
            }
            if ($accuracy == 0) {
                $ch1 = curl_init();
                curl_setopt($ch1, CURLOPT_URL, $google_api4);
                curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); // return into a variable
                $results4 = curl_exec($ch1);
                $array_data4 = json_decode($results4);
                if (isset($array_data4->results[0]->address_components)) {
                    foreach ($array_data4->results[0]->address_components as $add) {
                        if ($add->types[0] == 'country') {
                            $cn = $add->long_name;
                        }
                    }
                }
                if ($array_data4->status === 'OK' && $cn == "India") {
                    $location = $array_data4->results[0]->geometry->location;
                    $country = $array_data4->results[0]->address_components;
                    echo $country->short_name;
                    $lat = $location->lat;
                    $long = $location->lng;
                    $accuracy = 4;
                } else {
                    if ($fenceid['lat'] != null || $fenceid['lng'] != null) {
                        $lat = $fenceid['lat'];
                        $long = $fenceid['lng'];
                        $accuracy = 5;
                    }
                }
            }
        }
    }
    curl_close($ch1);
    $orders->customerno = $user['customerno'];
    $orders->user = $user['userid'];
    $orders->lat = $lat;
    $orders->long = $long;
    $orders->accuracy = $accuracy;
    $orders->fenceid = retval_issetor($fenceid['zone_id'], 0);
    $orders->areaid = $areaid;
    $status = $apiobj->addEditOrder($orders);
    $json = json_encode($status);
    echo $json;exit;
} elseif (isset($_REQUEST['cancel'])) {
    $t = $_REQUEST['cancel'];
    $orders = json_decode($t);
    $cust = $apiobj->getUser($orders);
    $orders->customerno = $cust['customerno'];
    $orders->userid = $cust['userid'];
    $test = $apiobj->addCancelOrder($orders);
    $testjson = json_encode($test);
    echo $testjson;
}
?>