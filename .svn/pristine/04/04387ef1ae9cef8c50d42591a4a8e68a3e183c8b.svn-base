<?php
include_once '../../lib/system/utilities.php';
include_once '../../lib/autoload.php';
if (!isset($_SESSION)) {
    session_start();
}
function getdate_IST() {
    $ServerDate_IST = strtotime(date("Y-m-d H:i:s"));
    return $ServerDate_IST;
}

function getvehicle($vehicleid) {
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicle = $vehiclemanager->get_vehicle_with_driver($vehicleid);
    if ($vehicle->isdeleted == '1') {
        header("location:vehicle.php");
    }
    return $vehicle;
}

function getvehicles() {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_all_vehicles();
    return $vehicles;
}

function getvehicles_latlng($routeid) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_all_vehicles_latlng($routeid);
    return $vehicles;
}

function getshopscount() {
    $route_obj = new RouteManager($_SESSION['customerno']);
    $route = $route_obj->get_shopscount();
    return $route;
}

function get_chk_for_vehicle($vehicleid,$followRouteSequence){
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $checkpoints = $vehiclemanager->get_chk_for_vehicle($vehicleid,$followRouteSequence);
    return $checkpoints;
}

function getroutes($vehicleid) {
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routes = $routemanager->getroutebyvehicleid($vehicleid);
    return $routes;
}

function getroutes_list() {
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routes = $routemanager->get_all_routes();
    return $routes;
}

function get_routeid($vehicleid) {
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routes = $routemanager->get_routeid_for_vehicleid($vehicleid);
    return $routes;
}

function report_desc_seq_new($vehicleid, $customerno, $firstelement, $lastelement, $starttime, $endtime) {
    $starttime = $starttime . ":00";
    $endtime = $endtime . ":00";
    $STdate = date("Y-m-d");
    // $STdate = "2014-08-07";
    $interval = 5;
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $Query = "select * from V" . $vehicleid . " WHERE DATE(date) = '" . $STdate . "' AND TIME(date) BETWEEN '" . $starttime . "' AND '" . $endtime . "' ORDER BY date DESC, chkrepid DESC";
    $reportdesc = array();
    $laststatus = '';
    $i = 1;
    try
    {
        $db = new PDO($path);
        $result = $db->query($Query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                if ($row["chkid"] == $firstelement && $i != 1 && $laststatus == '') {
                    $checkpoint = new stdClass();
                    $checkpoint->id = $row["chkrepid"];
                    $checkpoint->date = $row["date"];
                    $checkpoint->lastupdated = $row["date"];
                    $checkpoint->chkid = $row["chkid"];
                    $checkpoint->status = $row["status"];
                    $reportdesc[] = $checkpoint;
                    return $reportdesc;
                } elseif ($row["chkid"] == $firstelement && $i != 1 && $laststatus == $row["status"]) {
                    return $reportdesc;
                }
                if (isset($checkpoint_old1) && $row["chkid"] == $checkpoint_old1->chkid && $row["status"] == '0' && round(abs(strtotime($checkpoint_old1->lastupdated) - strtotime($row["date"])) / 60, 2) > $interval) {
                    $checkpoint = new stdClass();
                    $checkpoint->id = $row["chkrepid"];
                    $checkpoint->lastupdated = $row["date"];
                    $checkpoint->chkid = $row["chkid"];
                    $checkpoint->status = $row["status"];
                    $reportdesc[] = $checkpoint_old1;
                    $reportdesc[] = $checkpoint;
                }
                if ($row["status"] == '1') {
                    $checkpoint_old1 = new stdClass();
                    $checkpoint_old1->id = $row["chkrepid"];
                    $checkpoint_old1->lastupdated = $row["date"];
                    $checkpoint_old1->chkid = $row["chkid"];
                    $checkpoint_old1->status = $row["status"];
                    if ($row["chkid"] == $firstelement) {
                        $reportdesc[] = $checkpoint_old1;
                    }
                } elseif (isset($checkpoint_old1) && $row["chkid"] != $checkpoint_old1->chkid && $row["status"] == '0') {
                    $checkpoint = new stdClass();
                    $checkpoint->id = $row["chkrepid"];
                    $checkpoint->lastupdated = $row["date"];
                    $checkpoint->chkid = $row["chkid"];
                    $checkpoint->status = $row["status"];
                    $reportdesc[] = $checkpoint;
                }
                //$reportdesc[] = $checkpoint_old1;
                //if(($last_insertedid = $checkpoint->chkiddesc && $last_insertedid == $firstelement) || ($last_insertedid = $checkpoint->chkiddesc && $last_insertedid == $lastelement))
                if ($row["chkid"] == $firstelement && $laststatus == '') {
                    $checkpoint = new stdClass();
                    $checkpoint->id = $row["chkrepid"];
                    $checkpoint->lastupdated = $row["date"];
                    $checkpoint->chkid = $row["chkid"];
                    $checkpoint->status = $row["status"];
                    $reportdesc[] = $checkpoint;
                    $laststatus = $row["status"];
                }
                $i++;
            }
        }
    } catch (PDOException $e) {
        $reportdesc = 0;
    }

    return $reportdesc;
}

function checkStatuses($vehicleId,$chkArray,$visitedArray){

    $retArray = array();
    foreach ($visitedArray as $visit){
        if(isset($retArray[$visit['chkid']])){
            if($retArray[$visit['chkid']]<$visit['status']){
                $retArray[$visit['chkid']] = $visit['status'];
            }
        }else{
            $retArray[$visit['chkid']] = $visit['status'];
        }
    }
    foreach($retArray as &$r){
        $r = $r+1;
    }
    foreach($chkArray as $chk){
        if(!isset($retArray[$chk])){
            $retArray[$chk]=0;
        }
    }
    $retArray['status'] = getChkEtaStatus($vehicleId);
    //print_r($retArray['status']);
    return $retArray;
}
function date_compare($a, $b){
    $t1 = strtotime($a['date']);
    $t2 = strtotime($b['date']);
    return $t1 - $t2;
}   
function searchForId($id, $array) {
   foreach ($array as $key => $val) {
        if($val['seq']==$id){
            return $val['id'];
        }
   }
   return null;
}
function searchForChkId($id, $array) {
   foreach ($array as $key => $val) {
        if($val['id']==$id){
            return $val['id'];
        }
   }
   return null;
}
function report_no_seq($vehicleid, $customerno,$chkList, $starttime, $endtime,$startdate,$followRouteSequence,$client_codeObj) {
   
    $starttime = $starttime . ":00";
    $endtime = $endtime . ":00";
    //$STdate = date("Y-m-d");
    $STdate = date("Y-m-d",strtotime($startdate));
    //$STdate = "2018-11-20";
    $interval = 5;
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $Query = "select * from V" . $vehicleid . " WHERE DATE(date) = '" . $STdate . "' AND TIME(date) BETWEEN '" . $starttime . "' AND '" . $endtime . "'";
    
    $tmp = (array) $client_codeObj; // Object will be empty when logged in user is not client.
    
    if(!empty($tmp)){
        
        $Query .= "AND DATE(date) >= '".$client_codeObj->startdate."' AND DATE(date) <= '".$client_codeObj->enddate."'";
    }
    
    $Query .= "ORDER BY date ASC";
    
    $reportdesc = array();
    $laststatus = '';
    $i = 1;
    //$followRouteSequence=1;
    if($followRouteSequence==1){
        try{
            $db = new PDO($path);
            $result = $db->query($Query);
            if (isset($result) && $result != "") {
                $chkArray = array();
                $pairs = array();
                $pairs['in'] = array();
                $pairs['out'] = array();
                $prevChkId = 0;
                $seq = 1;
                $returnRep=array();
                foreach ($result as $row) {
                    //echo "Prev chkid = ".$prevChkId;
                    $checkpoint = array();
                    $checkpoint['id'] = $row["chkrepid"];
                    $checkpoint['date'] = $row["date"];
                    $checkpoint['lastupdated'] = $row["date"];
                    $checkpoint['status'] = $row["status"];
                    $checkpoint['chkid'] = $row["chkid"];

                    $seqStarted = 0;
                    if($checkpoint['chkid'] == $prevChkId ){
                        $returnRep[]=$checkpoint;
                        continue;
                    }
                    // echo "searching for chkid :".$checkpoint['chkid']." at seq $seq</br>";
                    if($checkpoint['chkid'] == searchForId($seq,$chkList)){
                        // echo "found</br>";
                        $seqStarted = 1;
                        $seq++;
                        $returnRep[] = $checkpoint;
                    }else{
                        // echo "not found</br>";
                        $seq = 1;
                        $seqStarted = 0;
                    }
                    $prevChkId = $checkpoint['chkid'];
                //     if($row['status']=='0'){
                //         $pairs['in'][$checkpoint['chkid']]=$checkpoint;
                //     }
                //     if($row['status']=='1'){
                //         $pairs['out'][$checkpoint['chkid']]=$checkpoint;
                //     }
                //     $chkArray[$row["chkid"]] = $checkpoint;
                // }
                // uasort($pairs['in'], 'date_compare');
                // uasort($pairs['out'], 'date_compare');
                // $totalSeq = count($chkList);
                // $seq = 1;
                // $seqStarted = 0;

                // if(isset($pairs['out'])){
                //     foreach($pairs['out'] as $visitOutArray){
                //         if($visitOutArray["chkid"]==searchForId($seq,$chkList)){
                //             //echo "Found (out) sequence $seq at checkpoint : ".$visitOutArray['chkid'];
                //             $chkOut = new stdClass();
                //             $chkOut->id = $visitOutArray['id'];
                //             $chkOut->date =   $visitOutArray['date'];
                //             $chkOut->lastupdated =  $visitOutArray['date'];
                //             $chkOut->chkid =   $visitOutArray["chkid"];
                //             $chkOut->status =   1;
                //             $reportdesc1[] = $chkOut;
                //             unset($chkOut);
                //             $seqStarted = 1;
                //             $seq++;
                //             break;
                //         }
                //     }
                // }

                // foreach($pairs['in'] as $k=>$visitArray){
                //     if($seq<=$totalSeq){
                //         echo "Checking sequence $seq at checkpoint : ".$visitArray['chkid'].PHP_EOL;
                //         if( $visitArray['chkid']==searchForId($seq,$chkList) ){
                //             echo "Found sequence $seq at checkpoint : ".$visitArray['chkid'].PHP_EOL;
                //             $seq++;
                //             $seqStarted=1;
                //             $checkpoint = new stdClass();
                //             $checkpoint->id = $visitArray["id"];
                //             $checkpoint->date =   $visitArray["date"];
                //             $checkpoint->lastupdated =  $visitArray["date"];
                //             $checkpoint->chkid =   $visitArray["chkid"];
                //             $checkpoint->status =   $visitArray["status"];
                //             if(isset($pairs['out'][$checkpoint->chkid])){
                //                 $inTime = new DateTime($pairs['in'][$checkpoint->chkid]['date']);
                //                 $outTime = new DateTime($pairs['out'][$checkpoint->chkid]['date']);
                //                 if(isset($chkOut)){
                //                     unset($chkOut);
                //                 }
                //                 if($inTime<$outTime){
                //                     $chkOut = new stdClass();
                //                     $chkOut->id = $pairs['out'][$checkpoint->chkid]['id'];
                //                     $chkOut->date =   $pairs['out'][$checkpoint->chkid]['date'];
                //                     $chkOut->lastupdated =  $pairs['out'][$checkpoint->chkid]['date'];
                //                     $chkOut->chkid =   $checkpoint->chkid;
                //                     $chkOut->status =   1;
                //                 }
                //             }
                //             $reportdesc1[] = $checkpoint;
                //             if(isset($chkOut)){
                //                 $reportdesc1[] = $chkOut;
                //             }
                //         }else{
                //             if($seqStarted == 1){
                //                 echo "resetting because ".$visitArray['chkid']."is not in seq $seq</br>";
                //                 $seqStarted = 0;
                //                 $seq = 1;
                //                 continue;
                //             }
                //         }
                //     }
                // }
                }
            }
            return $returnRep;
        }catch (PDOException $e){
            $reportdesc = 0;
        }
    }else{
        try{
            $db = new PDO($path);
            $result = $db->query($Query);
            if (isset($result) && $result != "") {
                foreach ($result as $row) {
                    if($row['chkid']==searchForChkId($row['chkid'],$chkList)){
                        $checkpoint = array();
                        $checkpoint['id'] = $row["chkrepid"];
                        $checkpoint['date'] = $row["date"];
                        $checkpoint['lastupdated'] = $row["date"];
                        $checkpoint['chkid'] = $row["chkid"];
                        $checkpoint['status'] = $row["status"];
                        $reportdesc[] = $checkpoint;
                    }
                }
                return $reportdesc;
            } 
        }catch (PDOException $e){
            $reportdesc = 0;
        }
    }
    

    return $reportdesc;
}

function report_desc_seq($vehicleid, $customerno, $firstelement, $lastelement) {
    $STdate = date("Y-m-d");
    // $STdate = "2014-07-07";
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $Query = "select * from V" . $vehicleid . " WHERE DATE(date) = '" . $STdate . "' ORDER BY chkrepid DESC,date DESC";
    $reportdesc = array();
    $laststatus = '';
    $i = 1;
    try
    {
        $db = new PDO($path);
        $result = $db->query($Query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                if ($row["chkid"] == $firstelement && $i != 1 && $laststatus == '') {
                    $checkpoint = new stdClass();
                    $checkpoint->id = $row["chkrepid"];
                    $checkpoint->date = $row["date"];
                    $checkpoint->lastupdated = $row["date"];
                    $checkpoint->chkid = $row["chkid"];
                    $checkpoint->status = $row["status"];
                    $reportdesc[] = $checkpoint;
                    return $reportdesc;
                } elseif ($row["chkid"] == $firstelement && $i != 1 && $laststatus == $row["status"]) {
                    return $reportdesc;
                }
                $checkpoint = new stdClass();
                $checkpoint->id = $row["chkrepid"];
                $checkpoint->lastupdated = $row["date"];
                $checkpoint->chkid = $row["chkid"];
                $checkpoint->status = $row["status"];
                $reportdesc[] = $checkpoint;
//                if(($last_insertedid = $checkpoint->chkiddesc && $last_insertedid == $firstelement) || ($last_insertedid = $checkpoint->chkiddesc && $last_insertedid == $lastelement))
                if ($row["chkid"] == $firstelement && $laststatus == '') {
                    $laststatus = $row["status"];
                }
                $i++;
            }
        }
    } catch (PDOException $e) {
        $reportdesc = 0;
    }
    return $reportdesc;
}

function getlatlng_chkpt($chkid, $vehicleid = null) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_latlng_chkpt($chkid, $vehicleid);
    return $vehicles;
}

function getChkEtaStatus($vehicleid) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->getChkEtaStatus($vehicleid);
    return $vehicles;
}

function distance($lat1, $lon1, $lat2, $lon2, $unit) {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);
    if ($unit == "K") {
        return (round($miles * 1.609344, 2));
    } elseif ($unit == "N") {
        return (round($miles * 0.8684, 2));
    } else {
        return round($miles, 2);
    }
}

function vehicleimage($device) {
    $basedir = "../../images/vehicles/";
    $directionfile = round($device->directionchange / 10);
    $tempconversion = new TempConversion();
        $tempconversion->unit_type = $device->get_conversion;
        $tempconversion->use_humidity = $_SESSION['use_humidity'];
    if ($device->type == 'Car' || $device->type == 'Cab') {
        $device->type = 'Car';
        if ($device->ignition == '0') {
            $image = $device->type . "/Idle/" . $device->type . $directionfile . ".png";
        } else {
            if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 1) {
                $temp = '';
                $s = "analog" . $device->tempsen1;
                if ($device->tempsen1 != 0 && $device->$s != 0) {
                    $tempconversion->rawtemp = $device->$s;
                    $temp = getTempUtil($tempconversion);
                } else {
                    $temp = '';
                }
                if ($temp != '' && ($temp < $device->temp1_min || $temp > $device->temp1_max)) {
                    $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                } elseif ($device->curspeed > $device->overspeed_limit) {
                    $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                } else {
                    $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
                }
            } elseif (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 2) {
                $temp1 = '';
                $temp2 = '';
                $s = "analog" . $device->tempsen1;
                if ($vehicle->tempsen1 != 0 && $vehicle->$s != 0) {
                    $tempconversion->rawtemp = $device->$s;
                    $temp1 = getTempUtil($tempconversion);
                } else {
                    $temp1 = '';
                }
                $s = "analog" . $device->tempsen2;
                if ($device->tempsen2 != 0 && $device->$s != 0) {
                    $tempconversion->rawtemp = $device->$s;
                    $temp2 = getTempUtil($tempconversion);
                } else {
                    $temp2 = '';
                }
                if ($temp1 != '' && ($temp1 < $device->temp1_min || $temp1 > $device->temp1_max)) {
                    $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                } elseif ($temp2 != '' && ($temp2 < $device->temp2_min || $temp2 > $device->temp2_max)) {
                    $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                } elseif ($device->curspeed > $device->overspeed_limit) {
                    $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                } else {
                    $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
                }
            } elseif ($device->curspeed > $device->overspeed_limit) {
                $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
            } else {
                $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
            }
        }
    } elseif ($device->type == 'Bus') {
        $device->type = 'Bus';
        if ($device->ignition == '0') {
            $image = $device->type . "/Idle/" . $device->type . $directionfile . ".png";
        } else {
            if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 1) {
                $temp = '';
                $s = "analog" . $device->tempsen1;
                if ($device->tempsen1 != 0 && $device->$s != 0) {
                    $tempconversion->rawtemp = $device->$s;
                    $temp = getTempUtil($tempconversion);
                } else {
                    $temp = '';
                }
                if ($temp != '' && ($temp < $device->temp1_min || $temp > $device->temp1_max)) {
                    $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                } elseif ($device->curspeed > $device->overspeed_limit) {
                    $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                } else {
                    $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
                }
            } elseif (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 2) {
                $temp1 = '';
                $temp2 = '';
                $s = "analog" . $device->tempsen1;
                if ($vehicle->tempsen1 != 0 && $vehicle->$s != 0) {
                    $tempconversion->rawtemp = $device->$s;
                    $temp1 = getTempUtil($tempconversion);
                } else {
                    $temp1 = '';
                }
                $s = "analog" . $device->tempsen2;
                if ($device->tempsen2 != 0 && $device->$s != 0) {
                    $tempconversion->rawtemp = $device->$s;
                    $temp2 = getTempUtil($tempconversion);
                } else {
                    $temp2 = '';
                }
                if ($temp1 != '' && ($temp1 < $device->temp1_min || $temp1 > $device->temp1_max)) {
                    $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                } elseif ($temp2 != '' && ($temp2 < $device->temp2_min || $temp2 > $device->temp2_max)) {
                    $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                } elseif ($device->curspeed > $device->overspeed_limit) {
                    $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                } else {
                    $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
                }
            } elseif ($device->curspeed > $device->overspeed_limit) {
                $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
            } else {
                $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
            }
        }
    } elseif ($device->type == 'Truck') {
        $device->type = 'Truck';
        if ($device->ignition == '0') {
            $image = $device->type . "/Idle/" . $device->type . $directionfile . ".png";
        } else {
            if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 1) {
                $temp = '';
                $s = "analog" . $device->tempsen1;
                if ($device->tempsen1 != 0 && $device->$s != 0) {
                    $tempconversion->rawtemp = $device->$s;
                    $temp = getTempUtil($tempconversion);

                } else {
                    $temp = '';
                }
                if ($temp != '' && ($temp < $device->temp1_min || $temp > $device->temp1_max)) {
                    $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                } elseif ($device->curspeed > $device->overspeed_limit) {
                    $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                } else {
                    $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
                }
            } elseif (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 2) {
                $temp1 = '';
                $temp2 = '';
                $s = "analog" . $device->tempsen1;
                if ($vehicle->tempsen1 != 0 && $vehicle->$s != 0) {
                    $tempconversion->rawtemp = $device->$s;
                    $temp1 = getTempUtil($tempconversion);
                } else {
                    $temp1 = '';
                }
                $s = "analog" . $device->tempsen2;
                if ($device->tempsen2 != 0 && $device->$s != 0) {
                    $tempconversion->rawtemp = $device->$s;
                    $temp2 = getTempUtil($tempconversion);
                } else {
                    $temp2 = '';
                }
                if ($temp1 != '' && ($temp1 < $device->temp1_min || $temp1 > $device->temp1_max)) {
                    $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                } elseif ($temp2 != '' && ($temp2 < $device->temp2_min || $temp2 > $device->temp2_max)) {
                    $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                } elseif ($device->curspeed > $device->overspeed_limit) {
                    $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                } else {
                    $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
                }
            } elseif ($device->curspeed > $device->overspeed_limit) {
                $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
            } else {
                $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
            }
        }
    } else {
        if ($device->ignition == '0') {
            $image = $device->type . "/" . $device->type . "I.png";
        } else {
            if ($device->curspeed > $device->overspeed_limit) {
                $image = $device->type . "/" . $device->type . "O.png";
            } else {
                $image = $device->type . "/" . $device->type . "N.png";
            }
        }
    }
    $image = $basedir . $image;
    return $image;
}

?>
