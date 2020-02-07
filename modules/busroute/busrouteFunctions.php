<?php
require_once '../../lib/system/utilities.php';
require_once '../../lib/comman_function/reports_func.php';
include_once '../../lib/components/ajaxpage.inc.php';
require_once '../../lib/autoload.php';
include_once '../../lib/model/TempConversion.php';
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['timezone'])) {
    $_SESSION['timezone'] = 'Asia/Kolkata';
}
date_default_timezone_set($_SESSION['timezone']);
$max_orders = 24;
define("MAX_ORDERS", $max_orders);
/* for testing */
$testing = false;
$max_box = 13;
$skip_box = 0;
/**/
$distUrl = "http://www.speed.elixiatech.com/location.php";
function uploadStudentData($data) {
    $skippedArray = array();
    $skipped = 0;
    $added = 0;
    foreach ($data as $row) {
        $objData = new stdClass();
        $objData->studentName = "";
        $objData->centerId = getGroupDetailsByGroupName($row['Code']);
        $objData->enrollmentNo = $row['ErnNo'];
        $objData->firstName = $row['Firstname'];
        $objData->lastName = $row['Surname'];
        $objData->grade = $row['Grade'];
        $objData->division = $row['Division'];
        $objData->address = $row['Address'];
        $objData->isBusStudent = 1; // Default Value
        $locationDetails = getLatLngByAddress($objData);
        $objData->lat = $locationDetails->lat;
        $objData->lng = $locationDetails->lng;
        $latlng = "$objData->lat,$objData->lng";
        $distance = calculateDistance($locationDetails->lat, $locationDetails->lng, 19.250784, 72.850693);
        $objData->distance = "";
        $objData->timeInMin = "";
        if (isset($distance) && !empty($distance)) {
            $objData->distance = $distance['km'];
            $objData->timeInMin = $distance['min'];
        }
        $objData->accuracy = $locationDetails->accuracy;
        $studentId = insertStudentData($objData);
        if ($studentId != 0) {
            $added++;
        } else {
            $skipped++;
        }
    }
    return array(
        'added' => $added,
        'skipped' => $skipped
    );
}

function insertStudentData($objData) {
    $objBusRoute = new BusRouteManager($_SESSION['customerno'], $_SESSION['userid']);
    $studentId = $objBusRoute->insertStudentData($objData);
    return $studentId;
}

function getstudents() {
    $StudentManager = new BusRouteManager($_SESSION['customerno'], $_SESSION['userid']);
    $students = $StudentManager->get_all_student();
    return $students;
}

function getGroupDetailsByGroupName($groupName) {
    $groupId = 0;
    $objGroup = new GroupManager($_SESSION['customerno']);
    $groupDetails = $objGroup->getgroupname(null, $groupName);
    //print_r($groupDetails); echo $groupDetails->groupid;die();
    $groupId = $groupDetails->groupid;
    return $groupId;
}

function getLatLngByAddress($locationData) {
    $locationDetails = array();
    $objGeoGoder = new GeoCoder($_SESSION['customerno']);
    $locationDetails = $objGeoGoder->getLatLngByAddress($locationData);
    return $locationDetails;
}

function getvehicles() {
    $finaloutput = array();
    $IST = getIST();
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $temp_coversion = new TempConversion();
    $devices = $devicemanager->deviceformappings();
    if (isset($devices)) {
        foreach ($devices as $device) {
            $output = new stdClass();
            $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
            $temp_coversion->unit_type = $device->get_conversion;
            $lastupdated2 = strtotime($device->lastupdated);
            $output->cgeolat = $device->devicelat;
            $output->cgeolong = $device->devicelong;
            $output->cname = $device->vehicleno;
            $output->clocation = location_cmn($device->devicelat, $device->devicelong, 0);
            $output->cdrivername = $device->drivername;
            $output->description = $device->description;
            $output->cdriverphone = $device->driverphone;
            $output->cspeed = $device->curspeed;
            $output->clastupdated = diff($IST, $lastupdated2);
            $output->cvehicleid = $device->vehicleid;
            $checkpoints1 = $checkpointmanager->get_checkpoint_from_chkmanage($device->vehicleid);
            if (!empty($checkpoints1)) {
                $chkptlist = array();
                foreach ($checkpoints1 as $row) {
                    $chkptlist[] = $row->cname;
                }
                $checkpointlist = implode(",", $chkptlist);
                $output->checkpointlist = $checkpointlist;
            } else {
                $output->checkpointlist = "";
            }
            $output->image = vehicleimage($device);
            if ($_SESSION['userid'] != 391 && $_SESSION['userid'] != 392) {
                $output->temp_sensors = $device->temp_sensors;
                if ($device->temp_sensors == 1) {
                    // Temperature Sensor
                    $temp = 'Not Active';
                    $s = "analog" . $device->tempsen1;
                    if ($device->tempsen1 != 0 && $device->$s != 0) {
                        $temp_coversion->rawtemp = $device->$s;
                        $temp = getTempUtil($temp_coversion);
                    } else {
                        $temp = '-';
                    }
                    $output->temp = $temp;
                    if ($temp != '-' && $temp != "Not Active") {
                        $output->tempon = 1;
                    } else {
                        $output->tempon = 0;
                    }
                }
                if ($device->temp_sensors == 2) {
                    $temp1 = 'Not Active';
                    $temp2 = 'Not Active';
                    $s = "analog" . $device->tempsen1;
                    if ($device->tempsen1 != 0 && $device->$s != 0) {
                        $temp_coversion->rawtemp = $device->$s;
                        $temp1 = getTempUtil($temp_coversion);
                    } else {
                        $temp1 = '-';
                    }
                    $s = "analog" . $device->tempsen2;
                    if ($device->tempsen2 != 0 && $device->$s != 0) {
                        $temp_coversion->rawtemp = $device->$s;
                        $temp2 = getTempUtil($temp_coversion);
                    } else {
                        $temp2 = '-';
                    }
                    $output->temp1 = $temp1;
                    $output->temp2 = $temp2;
                    if ($output->temp1 != '-' && $output->temp1 != "Not Active") {
                        $output->temp1on = 1;
                    } else {
                        $output->temp1on = 0;
                    }
                    if ($output->temp2 != '-' && $output->temp2 != "Not Active") {
                        $output->temp2on = 1;
                    } else {
                        $output->temp2on = 0;
                    }
                }
            } else {
                $output->temp_sensors = 0;
            }
            if ($_SESSION['portable'] != '1') {
                $output->portable = 0;
            } else {
                $output->portable = 1;
            }
            $output->totaldist = round(getdistance_new($device->vehicleid, $_SESSION['customerno']), 2);
            if ($output->totaldist < 0) {
                $output->totaldist = round($output->totaldist, 2);
            }
            $finaloutput[] = $output;
        }
    }
    $ajaxpage = new ajaxpage();
    $ajaxpage->SetResult($finaloutput);
    $ajaxpage->Render();
}

function getIST() {
    $IST = strtotime(date("Y-m-d H:i:s"));
    return $IST;
}

function getAllStudents($isBusStudent = 0) {
    $objBusRoute = new BusRouteManager($_SESSION['customerno'], $_SESSION['userid']);
    $arrResult = $objBusRoute->getAllStudents($isBusStudent);
    for ($i = 0; $i < count($arrResult); $i++) {
        $student = new stdClass();
        foreach ($arrResult[$i] as $key => $value) {
            $student->$key = $value;
            $student->image = getStudentImage();
        }
        $arrStudents[] = $student;
    }
    return $arrStudents;
}

function getStudentImage() {
    $images = "../../images/user.png";
    return $images;
}

function getDistanceBetweenLocation($latlng) {
    $distance = -1;
    $details = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=19.250784,72.850693&destinations=" . $latlng . "&mode=driving&sensor=false";
    $json = file_get_contents($details);
    $details = json_decode($json, true);
    if (isset($details['rows'][0]['elements'][0]['status']) && $details['rows'][0]['elements'][0]['status'] == "OK") {
        $distance = round(($details['rows'][0]['elements'][0]['distance']['value']) / 1000, 2);
    }
    return $distance;
}

function getAllStudentsSortedByDistance() {
    $arrStudentSortedData = array();
    $studentData = getAllStudents();
    usort($studentData, function ($a, $b) {
        return ($a->distance < $b->distance) ? -1 : 1;
    });
    return $studentData;
}

function insertBusStop($objData) {
    $objBusRoute = new BusRouteManager($_SESSION['customerno'], $_SESSION['userid']);
    $busStopId = $objBusRoute->insertBusStop($objData);
    return $busStopId;
}

function insertBusStopStudentMapping($objData) {
    $objBusRoute = new BusRouteManager($_SESSION['customerno'], $_SESSION['userid']);
    $busStopStudentMappingId = $objBusRoute->insertBusStopStudentMapping($objData);
    return $busStopStudentMappingId;
}

function getAllBusStops($objBusStop) {
    $isBusStudent = 0;
    $arrBusStops = array();
    $objBusRoute = new BusRouteManager($_SESSION['customerno'], $_SESSION['userid']);
    $arrResult = $objBusRoute->getAllBusStops($objBusStop);
    if (isset($arrResult) && !empty($arrResult)) {
        for ($i = 0; $i < count($arrResult); $i++) {
            $objBusStop = new stdClass();
            foreach ($arrResult[$i] as $key => $value) {
                $objBusStop->$key = $value;
                if ($key == 'busStopId') {
                    $objBusStop->name = "Bus Stop - " . $value;
                    $students = $objBusRoute->getAllStudents($isBusStudent, $value);
                    if (isset($students)) {
                        $arrStudent = array();
                        foreach ($students as $student) {
                            $arrStudent[] = $student['firstName'] . " " . $student['lastName'];
                        }
                        if (!empty($arrStudent)) {
                            $objBusStop->students = implode(',<br />', $arrStudent);
                        }
                    }
                }
            }
            $arrBusStops[] = $objBusStop;
        }
    }
    return $arrBusStops;
}

function getAllBusRoutes($objBusRoute) {
    $arrBusRoutes = array();
    $objBusRouteManager = new BusRouteManager($_SESSION['customerno'], $_SESSION['userid']);
    $arrResult = $objBusRouteManager->getAllBusRoutes($objBusRoute);
    if (isset($arrResult) && !empty($arrResult)) {
        for ($i = 0; $i < count($arrResult); $i++) {
            $objRoute = new stdClass();
            foreach ($arrResult[$i] as $key => $value) {
                $objRoute->$key = $value;
                if ($key == 'busStopId') {
                    $objRoute->name = "Bus Stop - " . $value;
                }
            }
            $arrBusRoutes[] = $objRoute;
        }
    }
    return $arrBusRoutes;
}

function getMappedZoneSlot() {
    $customerno = exit_issetor($_SESSION['customerno']);
    $mm = new MappingManager($customerno);
    $data = $mm->getVehZoneSlot_js_arr();
    return $data;
}

function getVehiclesForMapping() {
    $objBusRoute = new BusRouteManager($_SESSION['customerno'], $_SESSION['userid']);
    $arrResult = $objBusRoute->getVehiclesForMapping();
    for ($i = 0; $i < count($arrResult); $i++) {
        $objVehicles = new stdClass();
        foreach ($arrResult[$i] as $key => $value) {
            $objVehicles->$key = $value;
        }
        $arrVehicles[] = $objVehicles;
    }
    $vehicle_arr = vehicles_array($arrVehicles);
    return $vehicle_arr;
}

function getZones() {
    $arrZones = array(
        1 => array("zoneid" => 1, "zname" => "One", "startll" => "19.250784,72.850693"),
        2 => array("zoneid" => 2, "zname" => "Two", "startll" => "19.250784,72.850693"),
        3 => array("zoneid" => 3, "zname" => "Three", "startll" => "19.250784,72.850693"),
        4 => array("zoneid" => 4, "zname" => "Four", "startll" => "19.250784,72.850693"),
        5 => array("zoneid" => 5, "zname" => "Five", "startll" => "19.250784,72.850693"),
        6 => array("zoneid" => 6, "zname" => "Six", "startll" => "19.250784,72.850693")
    );
    return $arrZones;
}

function getSlots() {
    $arrSlots = array(
        1 => array('timing' => '7:00 - 7:00')
    );
    return $arrSlots;
}

function getZoneSlotOrdersCount() {
    $objBusRoute = new BusRouteManager($_SESSION['customerno'], $_SESSION['userid']);
    $arrResult = $objBusRoute->getZoneSlotOrdersCount();
    return $arrResult;
}

function getMappedOrders() {
    $objBusRoute = new BusRouteManager($_SESSION['customerno'], $_SESSION['userid']);
    $arrResult = $objBusRoute->getMappedOrders();
    return $arrResult;
}

function getTimeRqrd($start_point, $ltlng) {
    global $distUrl;
    $mn_url = "$distUrl?startLoc=$start_point&endLoc=$ltlng";
    $data = json_decode(file_get_contents($mn_url));
    if (isset($data->rows[0]->elements[0]->duration)) {
        return $data->rows;
    } else {
        return $data->status;
    }
}

function vehicle_order_count($veh_orders, $vid) {
    $count = 0;
    if (isset($veh_orders[$vid])) {
        foreach ($veh_orders[$vid] as $clust) {
            foreach ($clust as $orders) {
                $count++;
            }
        }
    }
    return $count;
}

function createBusStop() {
    $arrStudent = getAllStudentsSortedByDistance();
    $arrStudentMaster = array();
    if (isset($arrStudent)) {
        foreach ($arrStudent as $student) {
            $arrStudentMaster[] = $student->studentId;
        }
    }
    $distanceInMeter = 0;
    $cgeolat = 0;
    $cgeolong = 0;
    $arrBus = array();
    $arrFinal = array();
    $i = 1;
    foreach ($arrStudent as $student) {
        if ($student->timeInMin != "") {
            $objData = new stdClass();
            $crad = 100;
            if ($i == 1) {
                $cgeolat = $student->lat;
                $cgeolong = $student->lng;
            }
            $distance = calculateDistance($student->lat, $student->lng, $cgeolat, $cgeolong);
            if (isset($distance) && !empty($distance) && $student->timeInMin != "") {
                $dist = round($distance['km'] * 1000);
                $objData->studentId = $student->studentId;
                $objData->lat = $student->lat;
                $objData->lng = $student->lng;
                $objData->distanceFromStop = round($dist / 1000, 2);
                $objData->distanceFromSchool = $student->distance;
                $objData->timeInMin = $student->timeInMin;
                if ($objData->distanceFromSchool < 5) {
                    $objData->zone = 1;
                } elseif ($objData->distanceFromSchool >= 5 && $objData->distanceFromSchool < 10) {
                    $objData->zone = 2;
                } elseif ($objData->distanceFromSchool >= 10 && $objData->distanceFromSchool < 15) {
                    $objData->zone = 3;
                } elseif ($objData->distanceFromSchool >= 15 && $objData->distanceFromSchool < 20) {
                    $objData->zone = 4;
                } elseif ($objData->distanceFromSchool >= 20 && $objData->distanceFromSchool < 25) {
                    $objData->zone = 5;
                } else {
                    $objData->zone = 6;
                }
                $objData->accuracy = $student->accuracy;
                $objData->address = $student->address;
                if ($dist < 100) {
                    $arrBus[] = $objData;
                    $cgeolat = $cgeolat;
                    $cgeolong = $cgeolong;
                } else {
                    $cgeolat = $student->lat;
                    $cgeolong = $student->lng;
                    $objData->distanceFromStop = 0;
                    if (!empty($arrBus)) {
                        $arrFinal[] = $arrBus;
                        unset($arrBus);
                    }
                    $arrBus[] = $objData;
                }
                $i++;
            }
        }
    }
    if (isset($arrFinal) && !empty($arrFinal)) {
        $i = 0;
        foreach ($arrFinal as $row) {
            $busStopId = 0;
            $busStopId = insertBusStop($row[0]);
            if ($busStopId != 0) {
                foreach ($row as $data) {
                    $data->busStopId = $busStopId;
                    insertBusStopStudentMapping($data);
                }
            }
        }
    }
}

function vehicleBusStopSequence($vehid) {
    $objBusRoute = new BusRouteManager($_SESSION['customerno'], $_SESSION['userid']);
    $arrResult = $objBusRoute->vehicleBusStopSequence($vehid);
    return $arrResult;
}

function getAllRoutesByVehicle($vehicleId) {
    $arrRoute = array();
    $objBusRoute = new BusRouteManager($_SESSION['customerno'], $_SESSION['userid']);
    $arrResult = $objBusRoute->getAllRoutesByVehicle($vehicleId);
    for ($i = 0; $i < count($arrResult); $i++) {
        $route = new stdClass();
        foreach ($arrResult[$i] as $key => $value) {
            $route->$key = $value;
        }
        $arrRoute[] = $route;
    }
    return $arrRoute;
}

function rad($x) {
    return $x * pi() / 180;
}

function calculate($devicelat, $devicelong, $cgeolat, $cgeolong) {
    //Earth's mean radius in km
    $ERadius = 6371;
    //Difference between devicelatlong and checkpointlatlong
    $diffLat = rad($cgeolat - $devicelat);
    $diffLong = rad($cgeolong - $devicelong);
    //Converting between devicelatlong to radians
    $devlat_rad = rad($devicelat);
    $devlong_rad = rad($cgeolat);
    //Calculation Using Haversine's formula
    //Applying Haversine formula
    $a = sin($diffLat / 2) * sin($diffLat / 2) + cos($devlat_rad) * cos($devlong_rad) * sin($diffLong / 2) * sin($diffLong / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    //Distance
    $diffdist = $ERadius * $c;
    return $diffdist;
}

function calculateDistance($devicelat, $devicelong, $cgeolat, $cgeolong) {
    //echo $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $cgeolat . "," . $cgeolong . "&destinations=" . $devicelat . "," . $devicelong . "&mode=driving&language=pl-PL";
    $url = signLocationUrl("https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $cgeolat . "," . $cgeolong . "&destinations=" . $devicelat . "," . $devicelong . "&mode=driving&language=pl-PLsensor=false&key=" . GOOGLE_MAP_API_KEY, '');
    $distance = array();
    //echo "<br/><br/>";
    $json = file_get_contents($url);
    $details = json_decode($json, true);
    if (isset($details['rows'][0]['elements'][0]['status']) && $details['rows'][0]['elements'][0]['status'] == "OK") {
        $distance['min'] = ceil(($details['rows'][0]['elements'][0]['duration']['value']) / 60);
        $distance['km'] = round(($details['rows'][0]['elements'][0]['distance']['value']) / 1000, 2);
    }
    return $distance;
}

?>