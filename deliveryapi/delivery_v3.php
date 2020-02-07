<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../";
}
//error_reporting(E_ALL ^E_STRICT);
//ini_set('display_errors', 'on');
error_reporting(0);
require_once "class/config.inc.php";
require_once "class/class.api.php";
require_once "function/get_location.php";
require_once $RELATIVE_PATH_DOTS . "lib/bo/PointLocationManager.php";
require_once $RELATIVE_PATH_DOTS . "lib/comman_function/reports_func.php";
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
    $road = '';
    $city = '';
    $state = '';
    $country = '';
    $pincode = '';
    $locationtxt = '';
    $landmark = '';
    $areaid = 0;
    $zoneid = 0;
    $fenceid = 0;
    $t = $_REQUEST['bill'];
    $orders = json_decode($t);
    $user = $apiobj->getUser($orders);
    if (!empty($user)) {
        $accuracy = 1;
        $lat = urlencode($orders->lat);
        $long = urlencode($orders->long);
        if ($lat != "" && $long != "") {
            $customerno = $user['customerno'];
            $areadata = $apiobj->existslatlong_areamaster($lat, $long, $customerno);
            if (!empty($areadata)) {
                $locationtxt = $areadata->areaname;
                $areaid = $areadata->areaid;
                $zoneid = $areadata->zoneid;
            } else {
                $fencedata = $apiobj->getZone_fence_details($customerno);
                // If their no fecedata we declared zoneid=0 as india
                if (isset($fencedata)) {
                    $fenceid = $apiobj->getZone_details($customerno, $fencedata, $lat, $long);
                    $zoneid = $apiobj->getzoneid($fenceid, $customerno);
                } else {
                    $zoneid = $apiobj->getzoneid(0, $customerno);
                }
                //get address from lat long
                $API = "http://www.speed.elixiatech.com/location.php?lat=" . $lat . "&long=" . $long . "";
                $location = json_decode(file_get_contents("$API&sensor=false"));
                $today = date('Y-m-d h:i:s');
                if (isset($location->results[0]->address_components)) {
                    foreach ($location->results[0]->address_components as $add) {
                        if ($add->types[0] == 'country') {
                            $country = $add->long_name;
                        }
                        if ($add->types[0] == 'administrative_area_level_1') {
                            $state = $add->long_name;
                        }
                        if ($add->types[0] == 'administrative_area_level_2') {
                            $city = $add->long_name;
                        }
                        if ($add->types[0] == 'route') {
                            $road = $add->long_name;
                        }
                        if ($add->types[0] == 'postal_code') {
                            $pincode = $add->long_name;
                        }
                        if ($add->types[0] == 'political') {
                            $landmark = $add->long_name;
                        }
                        $locationtxt = $location->results[0]->formatted_address;
                        if ($road == "Unnamed Road") {
                            $fomattedAddress = str_replace($road, "", $location->results[0]->formatted_address);
                            $locationtxt = $fomattedAddress;
                        }
                    }
                }
                $areadata1 = array(
                    "customerno" => $customerno,
                    "zone_id" => $zoneid,
                    "areaname" => $locationtxt,
                    "lat" => $lat,
                    "long" => $long
                );
                $areaid = $apiobj->insert_areamaster($areadata1);
            }
            $orders->city = $city;
            $orders->street = $road;
            $orders->city = $city;
            $orders->pincode = $pincode;
            $orders->address = $locationtxt;
            $orders->customerno = $user['customerno'];
            $orders->user = $user['userid'];
            $orders->lat = $lat;
            $orders->long = $long;
            $orders->accuracy = $accuracy;
            $orders->landmark = $landmark;
            $orders->zoneid = $zoneid;
            $orders->areaid = $areaid;
            $addBill = $apiobj->addBillOrder($orders);
            $json = json_encode($addBill);
        } else {
            $result = array();
            $result['Status'] = 'Failure';
            $result['Error'] = 'Empty values supplied for latitude and longitude.';
            $json = json_encode($result);
        }
    } else {
        $result = array();
        $result['Status'] = 'Failure';
        $result['Error'] = 'Userkey not exists';
        $json = json_encode($result);
    }
    echo $json;
    exit;
} elseif (isset($_REQUEST['edit'])) {
    $t = $_REQUEST['edit'];
    $orders = json_decode($t);
    $areaid = isset($orders->areaid) ? $orders->areaid : 0;
    $user = $apiobj->getUser($orders);
    exit_issetor($user['customerno']);
    if (!empty($areaid)) {
        $fenceid = $apiobj->get_zone_id($user['customerno'], $areaid);
    }
    $lat = "";
    $long = "";
    $accuracy = 0;
    $delboyid = urlencode($orders->delboyid);
    $address = urlencode($orders->address);
    $building_landmark_area = urlencode("$orders->building, $orders->street, $orders->landmark, $areaname, $orders->city");
    $street_landmark_area = urlencode("$orders->street, $orders->landmark, $areaname, $orders->city");
    $landmark_area = urlencode("$orders->landmark, $areaname, $orders->city");
    $key = "";
    $google_api1 = signUrl("http://maps.google.com/maps/api/geocode/json?address=$address&region=in&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
    $google_api2 = signUrl("http://maps.google.com/maps/api/geocode/json?address=$building_landmark_area&region=in&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
    $google_api3 = signUrl("http://maps.google.com/maps/api/geocode/json?address=$street_landmark_area&region=in&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
    $google_api4 = signUrl("http://maps.google.com/maps/api/geocode/json?address=$landmark_area&region=in&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
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
    $orders->area = $areaname;
    $orders->delboyid = $delboyid;
    $status = $apiobj->addEditOrder($orders);
    $json = json_encode($status);
    echo $json;
    exit;
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