<?php
    include_once '../../lib/system/utilities.php';
    include_once '../../lib/autoload.php';
    include_once '../../lib/comman_function/reports_func.php';
    include_once '../../lib/model/TempConversion.php';
    set_time_limit(0);

    //date_default_timezone_set('Asia/Calcutta');
    function getdevices() {
        $devicemanager = new DeviceManager($_SESSION['customerno']);
        $devicedata = $devicemanager->getdevicesforrtd();
        return $devicedata;
    }

    function getmisc() {
        $devicemanager = new DeviceManager($_SESSION['customerno']);
        $devicedata = $devicemanager->getmiscforrtd();
        return $devicedata;
    }

    function getsim() {
        $devicemanager = new DeviceManager($_SESSION['customerno']);
        $devicedata = $devicemanager->getsimforrtd();
        return $devicedata;
    }

    //////////CRM manager ///////////////////
    function rel_manager($cid) {
        $relmanager = new VehicleManager($_SESSION['customerno']);
        $reldata = $relmanager->getrel_manager($cid);
        return $reldata;
    }

    function getvehicles() {
        $vehiclemanager = new VehicleManager($_SESSION['customerno']);
        $vehicledata = $vehiclemanager->getvehiclesforrtd();
        return $vehicledata;
    }

    function getvehicles_withpagination() {
        $vehiclemanager = new VehicleManager($_SESSION['customerno']);
        $vehicledata = $vehiclemanager->getvehiclesforrtdwithpagination();
        return $vehicledata;
    }

    function getvehicles_withpagination_fassos() {
        $vehiclemanager = new VehicleManager($_SESSION['customerno']);
        $vehicledata = $vehiclemanager->getvehiclesforrtdwithpagination_fassos();
        return $vehicledata;
    }

    function getwarehouse_withpagination() {
        $vehiclemanager = new VehicleManager($_SESSION['customerno']);
        $vehicledata = $vehiclemanager->getwarehouseforrtd(); 
        return $vehicledata;
    }

    function getFilteredDeviceData($sel_status) {
        $arrFilteredDeviceData = array();
        $vehiclemanager = new VehicleManager($_SESSION['customerno']);
        $vehicledata = $vehiclemanager->getwarehouseforrtd();
        if (isset($sel_status) && $sel_status != '') {
            $filteredStatus = explode(',', $sel_status);
        }

        if (isset($vehicledata) && !empty($vehicledata)) {
            foreach ($vehicledata as $vehicle) {
                $vehicleFilteredStatus = 2; //Normal
                $vehicle->vehicleFilteredStatus = $vehicleFilteredStatus;
                $serverISTLessThanHour = new DateTime();
                $serverISTLessThanHour->modify('-60 minutes');
                $lastupdated = new DateTime($vehicle->lastupdated);
                if ($lastupdated < $serverISTLessThanHour) {
                    $vehicleFilteredStatus = 1; //Inactive
                    $vehicle->vehicleFilteredStatus = $vehicleFilteredStatus;
                } else {
                    $tempconversion = new TempConversion();
                    $tempconversion->unit_type = $vehicle->get_conversion;
                    $tempconversion->use_humidity = $_SESSION['use_humidity'];
                    $tempconversion->switch_to = $_SESSION['switch_to'];
                    switch ($_SESSION['temp_sensors']) { 
                    case 4:
                        if ($vehicle->tempsen4 != 0) {
                            $vehicle->temp4_min = isset($vehicle->temp4_min) ? $vehicle->temp4_min : 0;
                            $vehicle->temp4_max = isset($vehicle->temp4_max) ? $vehicle->temp4_max : 0;
                            $s = "analog" . $vehicle->tempsen4;
                            if ($vehicle->$s != 0) {
                                $tempconversion->rawtemp = $vehicle->$s;
                                $temp = getTempUtil($tempconversion);
                                if ($temp != 0) {

                                    if ($temp < $vehicle->temp4_min || $temp > $vehicle->temp4_max) {
                                        if ($vehicle->temp4_min != $vehicle->temp4_max) {
                                            $vehicleFilteredStatus = 3; //Temerature Conflict
                                        }
                                    }
                                }
                            }
                        }
                    case 3:
                        if ($vehicle->tempsen3 != 0) {
                            $vehicle->temp3_min = isset($vehicle->temp3_min) ? $vehicle->temp3_min : 0;
                            $vehicle->temp3_max = isset($vehicle->temp3_max) ? $vehicle->temp3_max : 0;
                            $s = "analog" . $vehicle->tempsen3;
                            if ($vehicle->$s != 0) {
                                $tempconversion->rawtemp = $vehicle->$s;
                                $temp = getTempUtil($tempconversion);
                                if ($temp != 0) {

                                    if ($temp < $vehicle->temp3_min || $temp > $vehicle->temp3_max) {
                                        if ($vehicle->temp3_min != $vehicle->temp3_max) {
                                            $vehicleFilteredStatus = 3; //Temerature Conflict
                                        }
                                    }
                                }
                            }
                        }
                    case 2:
                        if ($vehicle->tempsen2 != 0) {

                            $vehicle->temp2_min = isset($vehicle->temp2_min) ? $vehicle->temp2_min : 0;
                            $vehicle->temp2_max = isset($vehicle->temp2_max) ? $vehicle->temp2_max : 0;
                            $s = "analog" . $vehicle->tempsen2;
                            if ($vehicle->$s != 0) {
                                $tempconversion->rawtemp = $vehicle->$s;
                                $temp = getTempUtil($tempconversion);
                                if ($temp != 0) {

                                    if ($temp < $vehicle->temp2_min || $temp > $vehicle->temp2_max) {
                                        if ($vehicle->temp2_min != $vehicle->temp2_max) {
                                            $vehicleFilteredStatus = 3; //Temerature Conflict
                                        }
                                    }

                                }
                            }
                        }
                    case 1:
                        if ($vehicle->tempsen1 != 0) {

                            $vehicle->temp1_min = isset($vehicle->temp1_min) ? $vehicle->temp1_min : 0;
                            $vehicle->temp1_max = isset($vehicle->temp1_max) ? $vehicle->temp1_max : 0;
                            $s = "analog" . $vehicle->tempsen1;
                            if ($vehicle->$s != 0) {
                                $tempconversion->rawtemp = $vehicle->$s;
                                $temp = getTempUtil($tempconversion);
                                if ($temp != 0) {

                                    if ($temp < $vehicle->temp1_min || $temp > $vehicle->temp1_max) {
                                        if ($vehicle->temp1_min != $vehicle->temp1_max) {
                                            $vehicleFilteredStatus = 3; //Temerature Conflict
                                        }
                                    }

                                }
                            }
                        }
                    }
                    $vehicle->vehicleFilteredStatus = $vehicleFilteredStatus;
                }
                if (isset($filteredStatus)) {
                    if (in_array($vehicle->vehicleFilteredStatus, $filteredStatus)) {
                        $arrFilteredDeviceData[] = $vehicle;
                    }
                } else {
                    $arrFilteredDeviceData[] = $vehicle;
                }

            }
        }
        return $arrFilteredDeviceData;
    }

    function getvehiclesforstatus() {
        $vehiclemanager = new VehicleManager($_SESSION['customerno']);
        $vehicledata = $vehiclemanager->getvehiclesforrtd();
        return $vehicledata;
    }

    function get_filter_vehicles($sel_status, $sel_stoppage) {
        $vehiclemanager = new VehicleManager($_SESSION['customerno']);
        $vehicledata = $vehiclemanager->get_filter_vehiclesforrtd($sel_status, $sel_stoppage);
        return $vehicledata;
    }

    function getManager() {
        $vehiclemanager = new VehicleManager($_SESSION['customerno']);
        $vehicledata = $vehiclemanager->getManager();
        return $vehicledata;
    }

    function get_chks_for_vehicle($vehicleid) {
        $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
        $checkpoints = $checkpointmanager->getchksforvehicle($vehicleid);
        return $checkpoints;
    }

    function get_fences_for_vehicle($vehicleid) {
        $geofencemanager = new GeofenceManager($_SESSION['customerno']);
        $geofences = $geofencemanager->getfencesforvehicle($vehicleid);
        return $geofences;
    }

    function getgroupname($groupid) {
        $groupmanager = new GroupManager($_SESSION['customerno']);
        $group = $groupmanager->getgroupname($groupid);
        return $group;
    }

    function get_ecode_for_vehicle($vehicleid) {
        $elixiacodemanager = new ElixiaCodeManager($_SESSION['customerno']);
        $elixiacode = $elixiacodemanager->get_ecode_vehicles($vehicleid);
        return $elixiacode;
    }

    function getdistance($unitno, $date) {
        $date = date("Y-m-d", strtotime($date));
        $totaldistance = 0;
        $lastodometer = GetOdometer($date, $unitno, 'DESC');
        $firstodometer = GetOdometer($date, $unitno, 'ASC');
        if ($lastodometer < $firstodometer) {
            $lastodometermax = GetOdometerMax($date, $unitno);
            $lastodometer = $lastodometermax + $lastodometer;
        }
        $totaldistance = $lastodometer - $firstodometer;
        if ($totaldistance != 0) {
            return $totaldistance / 1000;
        }
        return $totaldistance;
    }

    function getdistance_all($customerno, $unitno, $date) {
        $date = date("Y-m-d", strtotime($date));
        $totaldistance = 0;
        $lastodometer = GetOdometer_all($customerno, $date, $unitno, 'DESC');
        $firstodometer = GetOdometer_all($customerno, $date, $unitno, 'ASC');
        $totaldistance = $lastodometer - $firstodometer;
        if ($totaldistance != 0) {
            return $totaldistance / 1000;
        }
        return $totaldistance;
    }

    function GetOdometer($date, $unitno, $order) {
        $date = substr($date, 0, 11);
        $customerno = $_SESSION['customerno'];
        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
        $ODOMETER = 0;
        if (file_exists($location)) {
            $path = "sqlite:$location";
            $db = new PDO($path);
            $query = "SELECT odometer from vehiclehistory ORDER BY vehiclehistory.lastupdated $order LIMIT 0,1";
            $result = $db->query($query);
            foreach ($result as $row) {
                $ODOMETER = $row['odometer'];
            }
        }
        return $ODOMETER;
    }

    function GetOdometerMax($date, $unitno) {
        $date = substr($date, 0, 11);
        $customerno = $_SESSION['customerno'];
        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
        $ODOMETER = 0;
        if (file_exists($location)) {
            $path = "sqlite:$location";
            $db = new PDO($path);
            $query = "SELECT max(odometer) as odometerm from vehiclehistory";
            $result = $db->query($query);
            foreach ($result as $row) {
                $ODOMETER = $row['odometerm'];
            }
        }
        return $ODOMETER;
    }

    function getLastDigital($location, $unitno, $lastupdated, $flag) {
        if ($flag == 0) {
            $new_flag = 1;
        } else {
            $new_flag = 0;
        }
        if (file_exists($location)) {
            $path = "sqlite:$location";
            $db = new PDO($path);
            $query = "SELECT lastupdated from unithistory where unitno = $unitno AND lastupdated < '$lastupdated' and digitalio = $new_flag Order by lastupdated DESC limit 1";
            $result = $db->query($query);
            foreach ($result as $row) {
                $query1 = "SELECT lastupdated from unithistory where unitno = $unitno AND lastupdated > '" . $row['lastupdated'] . "' and digitalio = $flag Order by lastupdated ASC limit 1";
                $result1 = $db->query($query1);
                foreach ($result1 as $row1) {
                    return $row1['lastupdated'];
                }
            }
        }
    }

    function GetOdometer_all($customerno, $date, $unitno, $order) {
        $date = substr($date, 0, 11);
        $customerno = $customerno;
        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
        $ODOMETER = 0;
        if (file_exists($location)) {
            $path = "sqlite:$location";
            $db = new PDO($path);
            $query = "SELECT odometer from vehiclehistory ORDER BY vehiclehistory.lastupdated $order LIMIT 0,1";
            $result = $db->query($query);
            foreach ($result as $row) {
                $ODOMETER = $row['odometer'];
            }
        }
        return $ODOMETER;
    }

    function row_highlight($lastupdated) {
        $ServerIST_less1 = new DateTime();
        $ServerIST_less1->modify('-60 minutes');
        $lastupdated = new DateTime($lastupdated);
        if ($lastupdated < $ServerIST_less1) {
            echo "<tr  style='background:#FFE0CC;'>";
        } else {
            echo "<tr>";
        }
        return $lastupdated;
    }

    function row_highlight_by_id($lastupdated, $row_id) {
        $lastupdated = new DateTime($lastupdated);
        echo "<tr id='" . $row_id . "' style='height:10px;cursor:pointer;' >";
        return $lastupdated;
    }

    function location($lat, $long, $usegeolocation) {
        $address = NULL;
        if ($lat != '0' && $long != '0') {
            if ($usegeolocation == 1) {
                $API = "http://www.speed.elixiatech.com/location.php?lat=" . $lat . "&long=" . $long . "";
                $location = json_decode(file_get_contents("$API&sensor=false"));
                @$address = "Near " . $location->results[0]->formatted_address;
                if (isset($location) && $location->results[0]->formatted_address == "") {
                    $GeoCoder_Obj = new GeoCoder($_SESSION['customerno']);
                    $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
                }
            } else {
                $GeoCoder_Obj = new GeoCoder($_SESSION['customerno']);
                $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
            }
        }
        return $address;
    }

    function locationtest($lat, $long, $usegeolocation) {
        $address = NULL;
        if ($lat != '0' && $long != '0') {
            if ($usegeolocation == 1) {
                $API = "http://www.speed.elixiatech.com/location.php?lat=" . $lat . "&long=" . $long . "";
                $location = json_decode(file_get_contents("$API&sensor=false"));
                @$address = "Near " . $location->results[0]->formatted_address;
            }
        }
        return $address;
    }

    function getimage($vehicle) {
        $temp_conversion = new TempConversion();
        $temp_conversion->use_humidity = $_SESSION['use_humidity'];
        $temp_conversion->switch_to = $_SESSION['switch_to'];
        $temp_conversion->unit_type = $vehicle->get_conversion;
        $ServerIST_less1 = new DateTime();
        $vm = new VehicleManager($vehicle->customerno);
        $min = $vm->getTimezoneDiffInMin($vehicle->customerno);
        if (!empty($min)) {
            $min = "+$min seconds";
            $ServerIST_less1->modify($min);
        }
        $ServerIST_less1->modify('-60 minutes');
        $vehicle->lastupdated = new DateTime($vehicle->lastupdated->format('Y-m-d H:i:s'));
        $diff = getduration($vehicle->stoppage_transit_time);

        $image = "<img id='innerImgWh' class='imgcl'  src='../../images/RTD/Vehicles/";
        $image .= "Warehouse/";
        if ($_SESSION['portable'] != '1' && $vehicle->type == 'Warehouse') {
            if ($vehicle->lastupdated < $ServerIST_less1) {
                $image .= "inactive.png'/>";
            } elseif (isset($_SESSION['temp_sensors'])) {
                $image1 = "on.png'/>";
                switch ($_SESSION['temp_sensors']) {
                case 4:
                    $temp4 = '';
                    $s = "analog" . $vehicle->tempsen4;
                    if ($vehicle->tempsen4 != 0 && $vehicle->$s != 0) {
                        $temp_conversion->rawtemp = $vehicle->$s;
                        $temp4 = getTempUtil($temp_conversion);
                    }
                    if ($temp4 != 0 && ($temp4 < $vehicle->temp4_min || $temp4 > $vehicle->temp4_max) && $vehicle->temp4_min != $vehicle->temp4_max) {
                        $image1 = "conflict.png'/>";
                    }
                case 3:
                    $temp3 = '';
                    $s = "analog" . $vehicle->tempsen3;
                    if ($vehicle->tempsen3 != 0 && $vehicle->$s != 0) {
                        $temp_conversion->rawtemp = $vehicle->$s;
                        $temp3 = getTempUtil($temp_conversion);
                    }
                    if ($temp3 != 0 && ($temp3 < $vehicle->temp3_min || $temp3 > $vehicle->temp3_max) && $vehicle->temp3_min != $vehicle->temp3_max) {
                        $image1 = "conflict.png'/>";
                    }
                case 2:
                    $temp2 = '';
                    $s = "analog" . $vehicle->tempsen2;
                    if ($vehicle->tempsen2 != 0 && $vehicle->$s != 0) {
                        $temp_conversion->rawtemp = $vehicle->$s;
                        $temp2 = getTempUtil($temp_conversion);
                    }
                    if ($temp2 != 0 && ($temp2 < $vehicle->temp2_min || $temp2 > $vehicle->temp2_max) && $vehicle->temp2_min != $vehicle->temp2_max) {
                        $image1 = "conflict.png'/>";
                    }
                case 1:
                    $temp1 = '';
                    $s = "analog" . $vehicle->tempsen1;
                    if ($vehicle->tempsen1 != 0 && $vehicle->$s != 0) {
                        $temp_conversion->rawtemp = $vehicle->$s;
                        $temp1 = getTempUtil($temp_conversion);
                    }
                    if ($temp1 != 0 && ($temp1 < $vehicle->temp1_min || $temp1 > $vehicle->temp1_max) && $vehicle->temp1_min != $vehicle->temp1_max) {
                        $image1 = "conflict.png'/>";
                    }
                }
                $image = $image . $image1;
            }
        }
        echo $image;
    }

    function getstatus($stoppage_flag, $stoppage_transit_time, $lastupdated_store) {
        $ServerIST_less1 = new DateTime();
        $ServerIST_less1->modify('-60 minutes');
        $lastupdated = new DateTime($lastupdated_store);
        if ($lastupdated < $ServerIST_less1) {
            $status = "Inactive";
        } else {
            $diff = getduration_status($stoppage_transit_time);
            if ($stoppage_flag == '0') {
                $status = "Idle since<br> " . $diff;
            } else {
                $status .= "Running since<br> " . $diff;
            }
        }
        return $status;
    }

    function getStatusForPower($stoppage_flag, $stoppage_transit_time, $lastupdated_store) {
       /* $ServerIST_less1 = new DateTime();
        $ServerIST_less1->modify('-60 minutes');
        $lastupdated = new DateTime($lastupdated_store);
        if ($lastupdated < $ServerIST_less1) {
            $status = "Inactive";
        } else {*/
            $diff = getduration_status($stoppage_transit_time);
            if ($stoppage_flag == '0') {
                $status = "Off since<br> " . $diff;
            } else {
                $status .= "On since<br> " . $diff;
            }
        /*}*/
        return $status;
    }

    function vehicleimage($device) {
        $basedir = "../../images/vehicles/";
        $temp_conversion = new TempConversion();
        $temp_conversion->use_humidity = $_SESSION['use_humidity'];
        $temp_conversion->switch_to = $_SESSION['switch_to'];
        $temp_conversion->unit_type = $device->get_conversion;
        $directionfile = round($device->directionchange / 10);
        if ($device->type == 'Car' || $device->type == 'Cab' || $device->type == 'SUV') {
            $device->type = 'Car';
            $ServerIST_less1 = new DateTime();
            $ServerIST_less1->modify('-60 minutes');
            $lastupdated = new DateTime($device->lastupdated_store);
            if ($lastupdated < $ServerIST_less1) {
                $image = $device->type . "/Idle/" . $device->type . $directionfile . ".png";
            } elseif ($device->ignition == '0') {
                $image = $device->type . "/IdleIgnOff/" . $device->type . $directionfile . ".png";
            } else {
                if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 1) {
                    $temp = '';
                    $s = "analog" . $device->tempsen1;
                    if ($device->tempsen1 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp = getTempUtil($temp_conversion);
                    } else {
                        $temp = '';
                    }
                    if ($temp != '' && ($temp < $device->temp1_min || $temp > $device->temp1_max)) {
                        $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                    } elseif ($device->curspeed > $device->overspeed_limit) {
                        $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                    } else {
                        if ($device->stoppage_flag == '0') {
                            $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
                        } else {
                            $image = $device->type . "/Running/" . $device->type . $directionfile . ".png";
                        }
                    }
                } elseif (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 2) {
                    $temp1 = '';
                    $temp2 = '';
                    $s = "analog" . $device->tempsen1;
                    if ($vehicle->tempsen1 != 0 && $vehicle->$s != 0) {
                        $temp_conversion->rawtemp = $vehicle->$s;
                        $temp1 = getTempUtil($temp_conversion);
                    } else {
                        $temp1 = '';
                    }
                    $s = "analog" . $device->tempsen2;
                    if ($device->tempsen2 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp2 = getTempUtil($temp_conversion);
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
                        if ($device->stoppage_flag == '0') {
                            $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
                        } else {
                            $image = $device->type . "/Running/" . $device->type . $directionfile . ".png";
                        }
                    }
                } elseif ($device->curspeed > $device->overspeed_limit) {
                    $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                } else {
                    if ($device->stoppage_flag == '0') {
                        $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
                    } else {
                        $image = $device->type . "/Running/" . $device->type . $directionfile . ".png";
                    }
                }
            }
        } elseif ($device->type == 'Bus') {
            $device->type = 'Bus';
            $ServerIST_less1 = new DateTime();
            $ServerIST_less1->modify('-60 minutes');
            $lastupdated = new DateTime($device->lastupdated_store);
            if ($lastupdated < $ServerIST_less1) {
                $image = $device->type . "/Idle/" . $device->type . $directionfile . ".png";
            } elseif ($device->ignition == '0') {
                $image = $device->type . "/IdleIgnOff/" . $device->type . $directionfile . ".png";
            } else {
                if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 1) {
                    $temp = '';
                    $s = "analog" . $device->tempsen1;
                    if ($device->tempsen1 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp = getTempUtil($temp_conversion);
                    } else {
                        $temp = '';
                    }
                    if ($temp != '' && ($temp < $device->temp1_min || $temp > $device->temp1_max)) {
                        $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                    } elseif ($device->curspeed > $device->overspeed_limit) {
                        $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                    } else {
                        if ($device->stoppage_flag == '0') {
                            $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
                        } else {
                            $image = $device->type . "/Running/" . $device->type . $directionfile . ".png";
                        }
                    }
                } elseif (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 2) {
                    $temp1 = '';
                    $temp2 = '';
                    $s = "analog" . $device->tempsen1;
                    if ($vehicle->tempsen1 != 0 && $vehicle->$s != 0) {
                        $temp_conversion->rawtemp = $vehicle->$s;
                        $temp1 = getTempUtil($temp_conversion);
                    } else {
                        $temp1 = '';
                    }
                    $s = "analog" . $device->tempsen2;
                    if ($device->tempsen2 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp2 = getTempUtil($temp_conversion);
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
                        if ($device->stoppage_flag == '0') {
                            $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
                        } else {
                            $image = $device->type . "/Running/" . $device->type . $directionfile . ".png";
                        }
                    }
                } elseif ($device->curspeed > $device->overspeed_limit) {
                    $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                } else {
                    if ($device->stoppage_flag == '0') {
                        $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
                    } else {
                        $image = $device->type . "/Running/" . $device->type . $directionfile . ".png";
                    }
                }
            }
        } elseif ($device->type == 'Truck') {
            $device->type = 'Truck';
            $ServerIST_less1 = new DateTime();
            $ServerIST_less1->modify('-60 minutes');
            $lastupdated = new DateTime($device->lastupdated_store);
            if ($lastupdated < $ServerIST_less1) {
                $image = $device->type . "/Idle/" . $device->type . $directionfile . ".png";
            } elseif ($device->ignition == '0') {
                $image = $device->type . "/IdleIgnOff/" . $device->type . $directionfile . ".png";
            } else {
                if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 1) {
                    $temp = '';
                    $s = "analog" . $device->tempsen1;
                    if ($device->tempsen1 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp = getTempUtil($temp_conversion);
                    } else {
                        $temp = '';
                    }
                    if ($temp != '' && ($temp < $device->temp1_min || $temp > $device->temp1_max)) {
                        $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                    } elseif ($device->curspeed > $device->overspeed_limit) {
                        $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                    } else {
                        if ($device->stoppage_flag == '0') {
                            $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
                        } else {
                            $image = $device->type . "/Running/" . $device->type . $directionfile . ".png";
                        }
                    }
                } elseif (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 2) {
                    $temp1 = '';
                    $temp2 = '';
                    $s = "analog" . $device->tempsen1;
                    if ($vehicle->tempsen1 != 0 && $vehicle->$s != 0) {
                        $temp_conversion->rawtemp = $vehicle->$s;
                        $temp1 = getTempUtil($temp_conversion);
                    } else {
                        $temp1 = '';
                    }
                    $s = "analog" . $device->tempsen2;
                    if ($device->tempsen2 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp2 = getTempUtil($temp_conversion);
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
                        if ($device->stoppage_flag == '0') {
                            $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
                        } else {
                            $image = $device->type . "/Running/" . $device->type . $directionfile . ".png";
                        }
                    }
                } elseif ($device->curspeed > $device->overspeed_limit) {
                    $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                } else {
                    if ($device->stoppage_flag == '0') {
                        $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
                    } else {
                        $image = $device->type . "/Running/" . $device->type . $directionfile . ".png";
                    }
                }
            }
        } elseif ($device->type == 'Warehouse') {
            $device->type = 'Warehouse';
            $ServerIST_less1 = new DateTime();
            $ServerIST_less1->modify('-60 minutes');
            $lastupdated = new DateTime($device->lastupdated_store);
            if ($lastupdated < $ServerIST_less1) {
                $image = "Warehouse/inactive.png";
            } else {
                if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 1) {
                    $temp = '';
                    $s = "analog" . $device->tempsen1;
                    if ($device->tempsen1 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp = getTempUtil($temp_conversion);
                    } else {
                        $temp = '';
                    }
                    if ($temp != '' && ($temp < $device->temp1_min || $temp > $device->temp1_max) && $device->temp1_min != $device->temp1_max) {
                        $image = "Warehouse/conflict.png";
                    } else {
                        $image = "Warehouse/on.png";
                    }
                } elseif (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 2) {
                    $temp1 = '';
                    $temp2 = '';
                    $s = "analog" . $device->tempsen1;
                    if ($device->tempsen1 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp1 = getTempUtil($temp_conversion);
                    } else {
                        $temp1 = '';
                    }
                    $s = "analog" . $device->tempsen2;
                    if ($device->tempsen2 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp2 = getTempUtil($temp_conversion);
                    } else {
                        $temp2 = '';
                    }
                    if ($temp1 != '' && ($temp1 < $device->temp1_min || $temp1 > $device->temp1_max) && $device->temp1_min != $device->temp1_max) {
                        $image = "Warehouse/conflict.png";
                    } elseif ($temp2 != '' && ($temp2 < $device->temp2_min || $temp2 > $device->temp2_max) && $device->temp2_min != $device->temp2_max) {
                        $image = "Warehouse/conflict.png";
                    } else {
                        $image = "Warehouse/on.png";
                    }
                } elseif (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 3) {
                    $temp1 = '';
                    $temp2 = '';
                    $temp3 = '';
                    $s = "analog" . $device->tempsen1;
                    if ($device->tempsen1 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp1 = getTempUtil($temp_conversion);
                    } else {
                        $temp1 = '';
                    }
                    $s = "analog" . $device->tempsen2;
                    if ($device->tempsen2 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp2 = getTempUtil($temp_conversion);
                    } else {
                        $temp2 = '';
                    }
                    $s = "analog" . $device->tempsen3;
                    if ($device->tempsen3 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp3 = getTempUtil($temp_conversion);
                    } else {
                        $temp3 = '';
                    }
                    if ($temp1 != '' && ($temp1 < $device->temp1_min || $temp1 > $device->temp1_max) && $device->temp1_min != $device->temp1_max) {
                        $image = "Warehouse/conflict.png";
                    } elseif ($temp2 != '' && ($temp2 < $device->temp2_min || $temp2 > $device->temp2_max) && $device->temp2_min != $device->temp2_max) {
                        $image = "Warehouse/conflict.png";
                    } elseif ($temp3 != '' && ($temp3 < $device->temp3_min || $temp2 > $device->temp3_max) && $device->temp3_min != $device->temp3_max) {
                        $image = "Warehouse/conflict.png";
                    } else {
                        $image = "Warehouse/on.png";
                    }
                } elseif (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 4) {
                    $temp1 = '';
                    $temp2 = '';
                    $temp3 = '';
                    $temp4 = '';
                    $s = "analog" . $device->tempsen1;
                    if ($device->tempsen1 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp1 = getTempUtil($temp_conversion);
                    } else {
                        $temp1 = '';
                    }
                    $s = "analog" . $device->tempsen2;
                    if ($device->tempsen2 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp2 = getTempUtil($temp_conversion);
                    } else {
                        $temp2 = '';
                    }
                    $s = "analog" . $device->tempsen3;
                    if ($device->tempsen3 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp3 = getTempUtil($temp_conversion);
                    } else {
                        $temp3 = '';
                    }
                    $s = "analog" . $device->tempsen4;
                    if ($device->tempsen4 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp4 = getTempUtil($temp_conversion);
                    } else {
                        $temp4 = '';
                    }
                    if ($temp1 != '' && ($temp1 < $device->temp1_min || $temp1 > $device->temp1_max) && $device->temp1_min != $device->temp1_max) {
                        $image = "Warehouse/conflict.png";
                    } elseif ($temp2 != '' && ($temp2 < $device->temp2_min || $temp2 > $device->temp2_max) && $device->temp2_min != $device->temp2_max) {
                        $image = "Warehouse/conflict.png";
                    } elseif ($temp3 != '' && ($temp3 < $device->temp3_min || $temp2 > $device->temp3_max) && $device->temp3_min != $device->temp3_max) {
                        $image = "Warehouse/conflict.png";
                    } elseif ($temp4 != '' && ($temp4 < $device->temp4_min || $temp4 > $device->temp4_max) && $device->temp_min != $device->temp4_max) {
                        $image = "Warehouse/conflict.png";
                    } else {
                        $image = "Warehouse/on.png";
                    }
                } else {
                    $image = "Warehouse/on.png";
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

    function signal_strength($gsmstrength, $vehicleid) {
        $network = round($gsmstrength / 31 * 100);
        if ($network > 70) {
            echo "<td><img class = 'gsmcl_$vehicleid' src = '../../images/RTD/Network/best.png' title='Excellent: " . $network . "%'></td>";
        } elseif ($network > 30) {
            echo "<td><img class = 'gsmcl_$vehicleid'src = '../../images/RTD/Network/on.png' title='Good:" . $network . "%'></td>";
        } elseif ($network >= 0) {
            echo "<td><img class = 'gsmcl_$vehicleid'src = '../../images/RTD/Network/off.png' title='Bad:" . $network . "%'></td>";
        }
    }

    function powercut($powercut) {
        if ($powercut == 0) {
            echo "<img id='innerSmallWh' class = 'pccl' src = '../../images/RTD/PowerCut/off.png' title = 'PowerCut'>";
        } else {
            echo "<span id='innerSmallWh' class = 'pccl' title = 'Normal'>";
        }
    }

    function tamper($tamper) {
        if ($tamper == 1 && $_SESSION['customerno'] == 135) {
            echo "<td><img class = 'tampercl' src = '../../images/RTD/Tamper/on.png' title = 'Normal'></td>";
        } elseif ($tamper == 1 && $_SESSION['customerno'] != 135) {
            echo "<td><img class = 'tampercl' src = '../../images/RTD/Tamper/off.png' title = 'Tampered'></td>";
        } else {
            echo "<td><img class = 'tampercl' src = '../../images/RTD/Tamper/on.png' title = 'Normal'></td>";
        }
    }

    function display_vehicledata() {
        include 'pages/vehicle.php';
        $cnt = 1;
        $vehicledata1 = getwarehouse_withpagination();

        if (@isset($vehicledata1)) {
            @print_warehouse($vehicledata1, $cnt);
        } else {
            echo "<tr><td colspan=100%>No Data Avialable</td></tr>";
        }
        echo "</tbody></table>";
    }

    function display_filter_vehicledata($sel_status) {
        include 'pages/vehicle.php';
        $cnt = 1;
        $vehicledata = getFilteredDeviceData($sel_status);
        if (isset($vehicledata) && !empty($vehicledata)) {
            print_warehouse($vehicledata, $cnt);
        } else {
            echo "<tr><td colspan=100%>No Data Avialable</td></tr>";
        }
        echo "</tbody></table>";
    }

    function display_warehouse_status() {
        $vehiclestatusdata = groupBased_warehosue_cron($_SESSION['customerno'], $_SESSION['userid']); //getvehiclesforstatus();
        $p = 0;
        $q = 0;
        $r = 0;
        $temp_conversion = new TempConversion();
        $temp_conversion->use_humidity = $_SESSION['use_humidity'];
        $temp_conversion->switch_to = $_SESSION['switch_to'];
        if (isset($vehiclestatusdata)) {
            foreach ($vehiclestatusdata as $vehicle) {
                $temp_conversion->unit_type = $vehicle->get_conversion;
                $ServerIST_less1 = new DateTime();
                $ServerIST_less1->modify('-60 minutes');
                $lastupdated = new DateTime($vehicle->lastupdated_store);
                if ($lastupdated < $ServerIST_less1) {
                    $p++;
                } elseif (isset($_SESSION['temp_sensors'])) {
                    $conflict_temp = 0;
                    switch ($_SESSION['temp_sensors']) {
                    case 4:
                        $temp4 = '';
                        $s = "analog" . $vehicle->tempsen4;
                        if ($vehicle->tempsen4 != 0 && $vehicle->$s != 0) {
                            $temp_conversion->rawtemp = $vehicle->$s;
                            $temp4 = getTempUtil($temp_conversion);
                        }
                        if ($temp4 != 0 && ($temp4 < $vehicle->temp4_min || $temp4 > $vehicle->temp4_max) && $vehicle->temp4_min != $vehicle->temp4_max) {
                            $conflict_temp = 1;
                        }
                    case 3:
                        $temp3 = '';
                        $s = "analog" . $vehicle->tempsen3;
                        if ($vehicle->tempsen3 != 0 && $vehicle->$s != 0) {
                            $temp_conversion->rawtemp = $vehicle->$s;
                            $temp3 = getTempUtil($temp_conversion);
                        }
                        if ($temp3 != 0 && ($temp3 < $vehicle->temp3_min || $temp3 > $vehicle->temp3_max) && $vehicle->temp3_min != $vehicle->temp3_max) {
                            $conflict_temp = 1;
                        }
                    case 2:
                        $temp2 = '';
                        $s = "analog" . $vehicle->tempsen2;
                        if ($vehicle->tempsen2 != 0 && $vehicle->$s != 0) {
                            $temp_conversion->rawtemp = $vehicle->$s;
                            $temp2 = getTempUtil($temp_conversion);
                        }
                        if ($temp2 != 0 && ($temp2 < $vehicle->temp2_min || $temp2 > $vehicle->temp2_max) && $vehicle->temp2_min != $vehicle->temp2_max) {
                            $conflict_temp = 1;
                        }
                    case 1:
                        $temp1 = '';
                        $s = "analog" . $vehicle->tempsen1;
                        if ($vehicle->tempsen1 != 0 && $vehicle->$s != 0) {
                            $temp_conversion->rawtemp = $vehicle->$s;
                            $temp1 = getTempUtil($temp_conversion);
                        }
                        if ($temp1 != 0 && ($temp1 < $vehicle->temp1_min || $temp1 > $vehicle->temp1_max) && $vehicle->temp1_min != $vehicle->temp1_max) {
                            $conflict_temp = 1;
                        }
                    }
                    if ($conflict_temp == 1) {
                        $q++;
                    } else {
                        $r++;
                    }
                }
            }
        }
    ?>
    <div class="lGb" id="mapdetails search_table" style="margin-left: 2%; text-align: left;">
        <div class="b-s-t Ye rate5" id="1"><div class="zc bn ID-tooltipcontent-0"><font color="#000">Inactive</font></div>
            <div class="zt"><div><div class="eK" ><font color="#000" id="inactive"><?php echo $p; ?></font><img style="padding-left: 1px;" src="../../images/RTD/Vehicles/Warehouse/inactive.png"></img></div></div></div>
        </div>
        <div class="b-s-t Ye rate5" id="2"><div class="zc bn ID-tooltipcontent-0"><font color="#009900">Normal</font></div>
            <div class="zt"><div><div class="eK" ><font color="#009900" id="w_on"><?php echo $r; ?></font><img style="padding-left: 1px;" src="../../images/RTD/Vehicles/Warehouse/on.png"></img></div></div></div>
        </div>
        <?php if ($_SESSION["customerno"] != 391) {?>
            <div class="b-s-t Ye rate5" id="3"><div class="zc bn ID-tooltipcontent-0"><font color="#FF0000">Temp Conflict</font></div>
                <div class="zt"><div><div class="eK" ><font color="#FF0000" id="conflict"><?php echo $q; ?></font><img style="padding-left: 1px;" src="../../images/RTD/Vehicles/Warehouse/conflict.png"></img></div></div></div>
            </div>
        <?php }?>
    </div>
    <?php
        }

        function getvehicles_all() {
            $vehiclemanager = new VehicleManager($_SESSION['customerno']);
            $vehicledata = $vehiclemanager->getvehiclesforrtd_all();
            return $vehicledata;
        }

        function display_vehicledata_all() {
            include 'pages/vehicle_all.php';
            $vehicledata = getvehicles_all();
            if (isset($vehicledata)) {
                print_vehicledata_all($vehicledata);
                include '../common/locationmessage.php';
            } else {
                echo "<tr><td colspan=100%>No Data Avialable</td></tr>";
            }
            echo "</table>";
        }

        function getduration($StartTime) {
            $EndTime = date('Y-m-d H:i:s');
            //                echo $EndTime.'_'.$StartTime.'<br>';
            $idleduration = strtotime($EndTime) - strtotime($StartTime);
            $years = floor($idleduration / (365 * 60 * 60 * 24));
            $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
            $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
            $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
            if ($years >= '1' || $months >= '1') {
                $diff = date('d-m-Y', strtotime($StartTime));
            } elseif ($days > 0) {
                $diff = $days . ' days ' . $hours . ' hrs ago';
            } elseif ($hours > 0) {
                $diff = $hours . ' hrs and ' . $minutes . ' mins ago';
            } elseif ($minutes > 0) {
                $diff = $minutes . ' mins ago';
            } else {
                $seconds = strtotime($EndTime) - strtotime($StartTime);
                $diff = $seconds . ' sec ago';
            }
            return $diff;
        }

        function getduration_digitalio($StartTime, $EndTime) {
            $idleduration = strtotime($EndTime) - strtotime($StartTime);
            $years = floor($idleduration / (365 * 60 * 60 * 24));
            $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
            $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
            $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
            if ($years >= '1' || $months >= '1') {
                $diff = date('d-m-Y', strtotime($StartTime));
            } elseif ($days > 0) {
                $diff = $days . ' days ' . $hours . ' hrs ';
            } elseif ($hours > 0) {
                $diff = $hours . ' hrs and ' . $minutes . ' mins ';
            } else {
                $diff = $minutes . ' mins ';
            }
            return $diff;
        }

        function getduration_status($StartTime) {
            $EndTime = date('Y-m-d H:i:s');
            //                echo $EndTime.'_'.$StartTime.'<br>';
            $idleduration = strtotime($EndTime) - strtotime($StartTime);
            $years = floor($idleduration / (365 * 60 * 60 * 24));
            $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
            $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
            $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
            if ($years >= '1' || $months >= '1') {
                $diff = date('d-m-Y', strtotime($StartTime));
            } elseif ($days > 0) {
                $diff = $days . ' days ' . $hours . ' hrs';
            } elseif ($hours > 0) {
                $diff = $hours . ' hrs and ' . $minutes . ' mins';
            } elseif ($minutes > 0) {
                $diff = $minutes . ' mins';
            } else {
                $seconds = strtotime($EndTime) - strtotime($StartTime);
                $diff = $seconds . ' sec';
            }
            return $diff;
        }

        function print_warehouse($vehicledata, $cnt) {
            $i = $cnt;
            $customerno = $_SESSION['customerno'];
            foreach ($vehicledata as $vehicle) {
                $average = '';
                $location = location($vehicle->devicelat, $vehicle->devicelong, $vehicle->use_geolocation);
                $veh_status = getStatusForPower($vehicle->ignition, $vehicle->stoppage_transit_time, $vehicle->lastupdated);
                $vehicle->lastupdated = row_highlight_by_id($vehicle->lastupdated, $vehicle->vehicleid);
                echo "<td><input type='hidden' id='latlong" . $vehicle->vehicleid . "' value='" . $vehicle->devicelat . "," . $vehicle->devicelong . "'/><a>" . $i++ . "</a></td>";
                $image = vehicleimage($vehicle);
                echo "<td><div style='position: relative;'>";
                getimage($vehicle);
                // Powercut
                powercut($vehicle->powercut);
                echo "</div></td>";
                if ($_SESSION['buzzer'] == 1 && $vehicle->is_buzzer == 1) {
                    echo "<td><a href='javascript:void(0);' onclick='click_buzzer($vehicle->vehicleid, $vehicle->unitno);'><img class = 'buzzer' src = '../../images/buzzer.png' title = 'Buzzer'></a></td>";
                } elseif ($_SESSION['buzzer'] == 1 && $vehicle->is_buzzer == 0) {
                    echo "<td><a href='javascript:void(0);' onclick='click_buzzer1($vehicle->vehicleid, $vehicle->unitno);'><img class = 'rt' src = '../../images/buzzer.png' title = 'Buzzer'></a></td>";
                }
                $diff = getduration($vehicle->lastupdated->format('Y-m-d H:i:s'));
                echo "<td class='lupd'>" . $diff . "</td>";
                echo"<td class='lupd'>".$veh_status."</td>";
                if ($_SESSION['groupid'] == 0) {
                    if ($vehicle->groupid == 0) {
                        echo "<td class='grpid' id=" . $vehicle->groupid . " >Ungrouped</td>";
                    } else {
                        $group = getgroupname($vehicle->groupid);
                        $temp_solution = isset($group->groupname) ? $group->groupname : '';
                        echo "<td class='grpid'>" . $temp_solution . "</td>";
                    }
                }
                if ($_SESSION["Session_UserRole"] == $vehicle->customerno && ($_SESSION["Session_UserRole"] == 'Administrator' || $_SESSION["Session_UserRole"] == 'elixir')) {
                    echo "<td class='edit_td tooltip-right' id='" . $vehicle->vehicleid . "' title='Double Click To Edit' style='cursor: pointer;'>
        <span id='vehicleno_" . $vehicle->vehicleid . "' class='text'>" . $vehicle->vehicleno . "</span>
        <input type='text' class='editbox' id='vehicleno_input_" . $vehicle->vehicleid . "' value='" . $vehicle->vehicleno . "' style='display:none;'/>
        <input type='hidden' id='vehicle" . $vehicle->vehicleid . "' value='" . $vehicle->vehicleno . "'/>
        <input type='hidden' id='vehicleimage" . $vehicle->vehicleid . "' class='gimg' value='" . $image . "'/>
        </td>";
                } else {
                    echo "<td>" . $vehicle->vehicleno . "<input type='hidden' id='vehicle" . $vehicle->vehicleid . "' value='" . $vehicle->vehicleno . "'/><input type='hidden' id='vehicleimage" . $vehicle->vehicleid . "' value='" . $image . "'/></td>";
                }
                if ($_SESSION['Session_UserRole'] == 'elixir') {
                    echo "<td>$vehicle->unitno</td>";
                }
                if ($_SESSION["customerno"] == speedConstants::CUSTNO_NXTDIGITAL) {
                    echo '<td class="loccl tooltip-right" style="cursor: pointer;"><a style="text-decoration:underline;">' . $location . '</a></td>';
                }
                if ($_SESSION['portable'] != '1') {
                    if ($_SESSION['userid'] != 391 && $_SESSION['userid'] != 392) {
                        // Load Status
                        if ($_SESSION['use_loading'] == 1) {
                            if ($vehicle->msgkey == 1) {
                                echo "<td class='loadcl'>Loading</td>";
                            } elseif ($vehicle->msgkey == 2) {
                                echo "<td class='loadcl'>Unloading</td>";
                            } else {
                                echo "<td class='loadcl'>Normal</td>";
                            }
                        }
                    }
                    // AC Sensor
                    if ($_SESSION['use_ac_sensor'] == 1 || $_SESSION['use_genset_sensor'] == 1) {
                        if ($vehicle->acsensor == 1) {
                            if ($_SESSION["customerno"] == speedConstants::CUSTNO_NXTDIGITAL) {
                                if ($vehicle->digitalio == 0) {
                                    echo "<td class='accl'>Moved</td>";
                                } elseif ($vehicle->digitalio == 1) {
                                    echo "<td class='accl'>Stable</td>";
                                }
                            } else {
                                if ($vehicle->digitalioupdated != '0000-00-00 00:00:00') {
                                    $digitaldiff = getduration_digitalio($vehicle->digitalioupdated, $vehicle->lastupdated->format('Y-m-d H:i:s'));
                                }
                                if ($vehicle->digitalio == 0) {
                                    if ($vehicle->isacopp == 0) {
                                        if ($vehicle->digitalioupdated != '0000-00-00 00:00:00') {
                                            echo "<td class='accl'>On <br/>since " . $digitaldiff . " </td>";
                                        } else {
                                            echo "<td class='accl'>On </td>";
                                        }
                                    } else {
                                        if ($vehicle->digitalioupdated != '0000-00-00 00:00:00') {
                                            echo "<td class='accl'>Off <br/>since " . $digitaldiff . " </td>";
                                        } else {
                                            echo "<td class='accl'>Off </td>";
                                        }
                                    }
                                } elseif ($vehicle->isacopp == 0) {
                                    if ($vehicle->digitalioupdated != '0000-00-00 00:00:00') {
                                        echo "<td class='accl'>Off <br/>since " . $digitaldiff . " </td>";
                                    } else {
                                        echo "<td class='accl'>Off </td>";
                                    }
                                } else {
                                    if ($vehicle->digitalioupdated != '0000-00-00 00:00:00') {
                                        echo "<td class='accl'>On <br/>since " . $digitaldiff . " </td>";
                                    } else {
                                        echo "<td class='accl'>On </td>";
                                    }
                                }
                            }
                        } else {
                            echo "<td class='accl'>Not Active</td>";
                        }
                    }
                    /*if ($_SESSION['use_door_sensor']) {
                        $digitaldiff = 'Not Active';
                        if ($vehicle->door_digitalioupdated != '0000-00-00 00:00:00') {
                            $digitaldiff = getduration_digitalio($vehicle->door_digitalioupdated, $vehicle->lastupdated->format('Y-m-d H:i:s'));
                            $door_status = door_status($vehicle->is_door_opp, $vehicle->digitalio);
                            if (!$door_status) {
                                $digitaldiff = "Open <br/>since $digitaldiff";
                            } else {
                                $digitaldiff = "Closed <br/>since $digitaldiff";
                            }
                        }
                        echo "<td class='doorCl'>$digitaldiff</td>";
                    }*/
                    // Temperature Sensor
                    if (isset($_SESSION['temp_sensors'])) {
                        $average = getTemperature($vehicle);
                    }
                    if ($_SESSION['use_extradigital'] == 1) {
                        $category_array = Array();
                        $digital = Array();
                        $digital2 = Array();
                        $category = (int) $vehicle->digitalio;
                        $binarycategory = sprintf("%08s", DecBin($category));
                        for ($shifter = 1; $shifter <= 127; $shifter = $shifter << 1) {
                            $binaryshifter = sprintf("%08s", DecBin($shifter));
                            if ($category & $shifter) {
                                $category_array[] = $shifter;
                            }
                        }
                        $diffextra1 = '';
                        $diffextra2 = '';
                        if ($vehicle->extra_digitalioupdated != '0000-00-00 00:00:00') {
                            $diffextra1 = "since<br/>" . getduration_digitalio($vehicle->extra_digitalioupdated, $vehicle->lastupdated->format('Y-m-d H:i:s'));
                        }
                        if ($vehicle->extra2_digitalioupdated != '0000-00-00 00:00:00') {
                            $diffextra2 = "since<br/>" . getduration_digitalio($vehicle->extra2_digitalioupdated, $vehicle->lastupdated->format('Y-m-d H:i:s'));
                        }
                        if ($vehicle->extra_digital == '' || $vehicle->extra_digital == '0') {
                            echo '<td></td>';
                            echo '<td></td>';
                        } else {
                            if ($vehicle->extra_digital >= 1) {
                                echo '<td>';
                                if (in_array(1, $category_array)) {
                                    $digital[] = "On  $diffextra1";
                                } else {
                                    $digital[] = "Off $diffextra1";
                                }
                                if ($vehicle->genset1 != '' && $vehicle->transmitter1 != '') {
                                    if ($vehicle->setcom == 1) {
                                        $vehicle->genset1 = 'Syncing';
                                    }
                                    echo $vehicle->genset1 . "|" . $vehicle->transmitter1;
                                    echo "<br/>";
                                }
                                echo $commaList = implode(',<br/> ', $digital);
                                echo '</td>';
                            } else {
                                echo "<td>Not Active</td>";
                            }
                            if ($vehicle->extra_digital == 2) {
                                echo '<td>';
                                if (in_array(2, $category_array)) {
                                    $digital2[] = "On $diffextra2";
                                } else {
                                    $digital2[] = "Off $diffextra2";
                                }
                                if ($vehicle->genset2 != '' && $vehicle->transmitter2 != '') {
                                    if ($vehicle->setcom == 1) {
                                        $vehicle->genset2 = 'Syncing';
                                    }
                                    echo $vehicle->genset2 . "|" . $vehicle->transmitter2;
                                    echo "<br/>";
                                }
                                echo $commaList2 = implode(',<br/> ', $digital2);
                                echo '</td>';
                            } else {
                                echo "<td>Not Active</td>";
                            }
                        }
                    }

                    /*if (isset($_SESSION['temp_sensors'])) {
                        get_humidity($vehicle);
                    }*/
                    if ($_SESSION['customerno'] == speedConstants::CUSTNO_MDLZ) {
                        if($average == 0 ){
                            echo "<td class='average'></td>";
                        }else{
                            echo "<td class='average'>$average</td>";
                        }
                    }
                        /*Commeneted temporarry */
                      //if ($_SESSION['use_warehouse'] == 1 && $_SESSION['use_tracking'] == 0)
                      if($_SESSION['customerno'] == 2) {
                          echo "<td class='average'><div>";
                            if($vehicle->avg_temp_sens1 != "" ){
                                //<div class="inline" style="width:150px;text-align:left">$vehicle->n1 - </div>
                         echo "<div class='inline' style='width:110px;text-align:left'>$vehicle->avg_temp_sens1</div>";
                    }
                    else{
                        echo "<div class='inline' style='width:110px;text-align:left'>0</div>";
                    }
                    if($vehicle->avg_temp_sens2 != ""){
                         echo "<br><div class='inline' style='width:110px;text-align:left'>$vehicle->avg_temp_sens2</div>";
                    }
                    else{
                         echo "<br><div class='inline' style='width:110px;text-align:left'>0</div>";
                    }
                    if($vehicle->avg_temp_sens3 != ""){
                         echo "<br><div class='inline' style='width:110px;text-align:left'>$vehicle->avg_temp_sens3</div>";

                    }
                    else{
                         echo "<br><div class='inline' style='width:110px;text-align:left'>0</div>";
                    }
                    if($vehicle->avg_temp_sens4 != ""){
                         echo "<br><div class='inline' style='width:110px;text-align:left'>$vehicle->avg_temp_sens4</div>";

                    }
                    else{
                         echo "<br><div class='inline' style='width:110px;text-align:left'>0</div>";
                    }
                    echo "</div></td>";
                     }
                         // Humidity Sensor
                    if (isset($_SESSION['temp_sensors'])) {
                        get_humidity($vehicle);
                    }

                    if ($_SESSION['use_door_sensor'] == 1) {
                        echo getDoorSensorData($vehicle->door_digitalioupdated,$vehicle->lastupdated->format('Y-m-d H:i:s'),$vehicle->is_door_opp, $vehicle->digitalio,$vehicle->extbatt,$vehicle->isDoorExt,$vehicle->extra_digitalioupdated);
                    }

                }
            }
            echo "</tr>";
        }

        function getDoorSensorData($door_digitalioupdated,$lastupdated,$is_door_opp,$digitalio,$extbatt,$isDoorExt,$extra_digitalioupdated){
           /* Code to calculate Door1 status starts here */ 
            $digitaldiff = 'Not Active';
                if ($door_digitalioupdated != '0000-00-00 00:00:00') {
                    $digitaldiff = getduration_digitalio($door_digitalioupdated, $lastupdated);
                    $door_status = door_status($is_door_opp, $digitalio);
                    if (!$door_status) {
                            $digitaldiff = "Open <br/>since $digitaldiff";
                            } else {
                                $digitaldiff = "Closed <br/>since $digitaldiff";
                            }
                }
             /* Code to calculate Door1 status ends here */    
            if($isDoorExt!=0)
            {
                $digitalDifferenceForSecondDoor = getduration_digitalio($extra_digitalioupdated, $lastupdated);
                
                $doorSecondStatus = getExternalBatteryStatus($extbatt,$digitalDifferenceForSecondDoor);
                
            }
            else
            {
                $doorSecondStatus = 'Not Active';
            }
            
            echo'<td>
                <div>
                    <div class="inline" style="width:110px;text-align:left">Door 1</div>
                    <div style="width:100px;" class="inline tempcl">
                        <div class="inline" style="width:60px;text-align:left">'.$digitaldiff.'</div>
                   </div>
                </div>
                <br>
                <div class="divBorder">
                    <div class="inline" style="width:110px;text-align:left">Door 2</div>
                    <div style="width:100px;" class="inline tempc2">
                        <div class="inline" style="width:60px;text-align:left">'.$doorSecondStatus.'</div>
                   </div>
                </div>
            </td>';
        }

        /* FUnction to get door 2 status starst here */
        function getExternalBatteryStatus($extbatt,$digitalDifferenceForSecondDoor)
        {
            if((int)$extbatt / 100 > 0)
            {
                return "Open <br/>since ".$digitalDifferenceForSecondDoor;
            }
            else
            {
                return "Closed <br/>since ".$digitalDifferenceForSecondDoor;
            }
        }
        /* Function to get door 2 status ends here */

        function getName($nid) {
            $vehiclemanager = new VehicleManager($_SESSION['customerno']);
            $vehicledata = $vehiclemanager->getNameForTemp($nid);
            return $vehicledata;
        }

        function getTemperature($vehicle) {  
            $average = 0;
            $t1 = getName($vehicle->n1);
            $t2 = getName($vehicle->n2);
            $t3 = getName($vehicle->n3);
            $t4 = getName($vehicle->n4);
            $t1 = isset($t1) ? $t1 : 'Temperature1 ';
            $t2 = isset($t2) ? $t2 : 'Temperature2 ';
            $t3 = isset($t3) ? $t3 : 'Temperature3 ';
            $t4 = isset($t4) ? $t4 : 'Temperature4 ';
            $tempconversion = new TempConversion();
            $tempconversion->unit_type = $vehicle->get_conversion;
            $tempconversion->use_humidity = $_SESSION['use_humidity'];
            $tempconversion->switch_to = $_SESSION['switch_to'];
            $tdclass_temp1 = $tdclass_temp2 = $tdclass_temp3 = $tdclass_temp4 = " ";
            $spantext1 = $spantext2 = $spantext3 = $spantext4 = '';
            $tempReport1 = $tempReport2 = $tempReport3 = $tempReport4 = '';
            $temp1 = $temp2 = $temp3 = $temp4 = speedConstants::TEMP_WARNING;
            $tempString = '';
            $tempTd1 = '';
            $tempTd2 = '';
            $tempTd3 = '';
            $tempTd4 = '';
            $color1 = '';
            $color2 = '';
            $color3 = '';
            $color4 = '';
            $temp1_in_farnht = 0;
            switch ($_SESSION['temp_sensors']) {
            case 4:
                if ($vehicle->tempsen4 != 0) {
                    $spantext4 = " <span onclick='muteVehicle($vehicle->vehicleid,4)'>" . speedConstants::TEMP_MUTE . "</span>";
                    if ($vehicle->temp4_mute == 1) {
                        $spantext4 = " <span onclick='unmuteVehicle($vehicle->vehicleid,4)'>" . speedConstants::TEMP_UNMUTE . "</span>";
                    }
                    $tempReport4 = " <img title='Temperature Report' onclick='tempreport(" . $vehicle->vehicleid . ",4," . $vehicle->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
                    $vehicle->temp4_min = isset($vehicle->temp4_min) ? $vehicle->temp4_min : 0;
                    $vehicle->temp4_max = isset($vehicle->temp4_max) ? $vehicle->temp4_max : 0;
                    $s = "analog" . $vehicle->tempsen4;
                    if ($vehicle->$s != 0) {
                        $tempconversion->rawtemp = $vehicle->$s;
                        $temp = getTempUtil($tempconversion);
                        if ($temp != 0) {
                            $tdclass_temp4 = " normalTemperature";
                            if ($temp < $vehicle->temp4_min || $temp > $vehicle->temp4_max) {
                                if ($vehicle->temp4_min != $vehicle->temp4_max) {
                                    $tdclass_temp4 = " off";
                                }
                            } elseif($vehicle->temp4_allowance > 0){
                                if(($temp > $vehicle->temp4_min && $temp < ($vehicle->temp4_min + $vehicle->temp4_allowance)) || ($temp < $vehicle->temp4_max && $temp > ($vehicle->temp4_max - $vehicle->temp4_allowance))){
                                    $tdclass_temp4 = " allowance";
                                }
                            }
                            if (isset($vehicle->trcmvehicleid)) {
                                if($temp > $vehicle->temp4_range1_start && $temp < $vehicle->temp4_range1_end) {
                                    $color4 = 'background-color:'.$vehicle->temp4_range1_color;
                                } elseif ($temp > $vehicle->temp4_range2_start && $temp < $vehicle->temp4_range2_end) {
                                    $color4 = 'background-color:'.$vehicle->temp4_range2_color;
                                } elseif ($temp > $vehicle->temp4_range3_start && $temp < $vehicle->temp4_range3_end) {
                                    $color4 = 'background-color:'.$vehicle->temp4_range3_color;
                                } elseif ($temp > $vehicle->temp4_range4_start && $temp < $vehicle->temp4_range4_end) {
                                    $color4 = 'background-color:'.$vehicle->temp4_range4_color;
                                }
                            }
                            $temp4 = $temp . speedConstants::TEMP_DEGREE;
                            if($vehicle->customerno == speedConstants::CUSTNO_CUREFIT){
                                $temp4_in_farnht = " (".round(($temp4 * 9/5) + 32, 2)."<sup>o</sup>F)";
                            }
                            else{
                                 $temp4_in_farnht = "";
                            }
                        }
                    }
                } else {
                    $temp4 = speedConstants::TEMP_NOTACTIVE;
                }
                if (isset($vehicle->customerno) && $vehicle->customerno == speedConstants::CUSTNO_PERKINELMER) {
                    $tempTd4 = "<div class='divBorder'><div class='inline' style='width:150px;text-align:left;'>" . $vehicle->vehicleno . " - </div><div class='inline' style='width:110px;text-align:left'>" . $t4 . "</div><div style='width:100px;".$color4."' class='inline tempc4 " . $tdclass_temp4 . "'><div class='inline' style='width:60px;text-align:left'>" . $temp4 . "</div><div class='inline' style='width:15px;text-align:left'>" . $spantext4 . "</div><div class='inline' style='width:15px;text-align:right'>" . $tempReport4 . "</div></div></div>";
                } else {
                    $tempTd4 = "<div class='divBorder'><div class='inline' style='width:110px;text-align:left'>" . $t4 . "</div><div style='width:100px;".$color4."' class='inline tempc4 " . $tdclass_temp4 . "'><div class='inline' style='width:60px;text-align:left'>" . $temp4 .$temp4_in_farnht. "</div><div class='inline' style='width:15px;text-align:left'>" . $spantext4 . "</div><div class='inline' style='width:15px;text-align:right'>" . $tempReport4 . "</div></div></div>";
                }
            case 3:
                if ($vehicle->tempsen3 != 0) {
                    $spantext3 = " <span onclick='muteVehicle($vehicle->vehicleid,3)'>" . speedConstants::TEMP_MUTE . "</span>";
                    if ($vehicle->temp3_mute == 1) {
                        $spantext3 = " <span onclick='unmuteVehicle($vehicle->vehicleid,3)'>" . speedConstants::TEMP_UNMUTE . "</span>";
                    }
                    $tempReport3 = " <img title='Temperature Report' onclick='tempreport(" . $vehicle->vehicleid . ",3," . $vehicle->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
                    $vehicle->temp3_min = isset($vehicle->temp3_min) ? $vehicle->temp3_min : 0;
                    $vehicle->temp3_max = isset($vehicle->temp3_max) ? $vehicle->temp3_max : 0;
                    $s = "analog" . $vehicle->tempsen3;
                    if ($vehicle->$s != 0) {
                        $tempconversion->rawtemp = $vehicle->$s;
                        $temp = getTempUtil($tempconversion);
                        if ($temp != 0) {
                            $tdclass_temp3 = " normalTemperature";
                            if ($temp < $vehicle->temp3_min || $temp > $vehicle->temp3_max) {
                                if ($vehicle->temp3_min != $vehicle->temp3_max) {
                                    $tdclass_temp3 = " off";
                                }
                            } elseif($vehicle->temp3_allowance > 0) {
                                if(($temp > $vehicle->temp3_min && $temp < ($vehicle->temp3_min + $vehicle->temp3_allowance)) || ($temp < $vehicle->temp3_max && $temp > ($vehicle->temp3_max - $vehicle->temp3_allowance))) {
                                    $tdclass_temp3 = " allowance";
                                }
                            }
                            if (isset($vehicle->trcmvehicleid)) {
                                if($temp > $vehicle->temp3_range1_start && $temp < $vehicle->temp3_range1_end) {
                                    $color3 = 'background-color:'.$vehicle->temp3_range1_color;
                                } elseif ($temp > $vehicle->temp3_range2_start && $temp < $vehicle->temp3_range2_end) {
                                    $color3 = 'background-color:'.$vehicle->temp3_range2_color;
                                } elseif ($temp > $vehicle->temp3_range3_start && $temp < $vehicle->temp3_range3_end) {
                                    $color3 = 'background-color:'.$vehicle->temp3_range3_color;
                                } elseif ($temp > $vehicle->temp3_range4_start && $temp < $vehicle->temp3_range4_end) {
                                    $color3 = 'background-color:'.$vehicle->temp3_range4_color;
                                }
                            }
                            $temp3 = $temp . speedConstants::TEMP_DEGREE;
                            if($vehicle->customerno == speedConstants::CUSTNO_CUREFIT){
                                $temp3_in_farnht = " (".round(($temp3 * 9/5) + 32, 2)."<sup>o</sup>F)";
                            }
                            else{
                                 $temp3_in_farnht = "";
                            }
                        }
                    }
                } else {
                    $temp3 = speedConstants::TEMP_NOTACTIVE;
                }
                if (isset($vehicle->customerno) && $vehicle->customerno == speedConstants::CUSTNO_PERKINELMER) {
                    $tempTd3 = "<div class='divBorder'><div class='inline' style='width:150px;text-align:left'>" . $vehicle->vehicleno . " - </div><div class='inline' style='width:110px;text-align:left'>" . $t3 . "</div><div style='width:100px;".$color3."' class='inline tempc3 " . $tdclass_temp3 . "'><div class='inline' style='width:60px;text-align:left'>" . $temp3 . "</div><div class='inline'style='width:15px;text-align:left'>" . $spantext3 . "</div><div class='inline'style='width:15px;text-align:right'>" . $tempReport3 . "</div></div></div>";
                } else {
                    $tempTd3 = "<div class='divBorder'><div class='inline' style='width:110px;text-align:left'>" . $t3 . "</div><div style='width:100px;".$color3."' class='inline tempc3 " . $tdclass_temp3 . "'><div class='inline' style='width:60px;text-align:left'>" . $temp3 .$temp3_in_farnht. "</div><div class='inline'style='width:15px;text-align:left'>" . $spantext3 . "</div><div class='inline'style='width:15px;text-align:right'>" . $tempReport3 . "</div></div></div>";
                }
            case 2:
                if ($vehicle->tempsen2 != 0) {
                    $spantext2 = " <span onclick='muteVehicle($vehicle->vehicleid,2)'>" . speedConstants::TEMP_MUTE . "</span>";
                    if ($vehicle->temp2_mute == 1) {
                        $spantext2 = " <span onclick='unmuteVehicle($vehicle->vehicleid,2)'>" . speedConstants::TEMP_UNMUTE . "</span>";
                    }
                    $tempReport2 = " <img title='Temperature Report' onclick='tempreport(" . $vehicle->vehicleid . ",2," . $vehicle->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
                    $vehicle->temp2_min = isset($vehicle->temp2_min) ? $vehicle->temp2_min : 0;
                    $vehicle->temp2_max = isset($vehicle->temp2_max) ? $vehicle->temp2_max : 0;
                    $s = "analog" . $vehicle->tempsen2;
                    if ($vehicle->$s != 0) {
                        $tempconversion->rawtemp = $vehicle->$s;
                        $temp = getTempUtil($tempconversion);
                        if ($temp != 0) {
                            $tdclass_temp2 = " normalTemperature";
                            if ($temp < $vehicle->temp2_min || $temp > $vehicle->temp2_max) {
                                if ($vehicle->temp2_min != $vehicle->temp2_max) {
                                    $tdclass_temp2 = " off";
                                }
                            } elseif ($vehicle->temp2_allowance > 0) {
                                if (($temp > $vehicle->temp2_min && $temp < ($vehicle->temp2_min + $vehicle->temp2_allowance)) || ($temp < $vehicle->temp2_max && $temp > ($vehicle->temp2_max - $vehicle->temp2_allowance))) {
                                    $tdclass_temp2 = " allowance";
                                }
                            }
                            if (isset($vehicle->trcmvehicleid)) {
                                if($temp > $vehicle->temp2_range1_start && $temp < $vehicle->temp2_range1_end) {
                                    $color2 = 'background-color:'.$vehicle->temp2_range1_color;
                                } elseif ($temp > $vehicle->temp2_range2_start && $temp < $vehicle->temp2_range2_end) {
                                    $color2 = 'background-color:'.$vehicle->temp2_range2_color;
                                } elseif ($temp > $vehicle->temp2_range3_start && $temp < $vehicle->temp2_range3_end) {
                                    $color2 = 'background-color:'.$vehicle->temp2_range3_color;
                                } elseif ($temp > $vehicle->temp2_range4_start && $temp < $vehicle->temp2_range4_end) {
                                    $color2 = 'background-color:'.$vehicle->temp2_range4_color;
                                }
                            }
                            $temp2 = $temp . speedConstants::TEMP_DEGREE;
                            if($vehicle->customerno == speedConstants::CUSTNO_CUREFIT){
                                $temp2_in_farnht = " (".round(($temp2 * 9/5) + 32, 2)."<sup>o</sup>F)";
                            }
                            else{
                                 $temp2_in_farnht = "";
                            }
                        }
                    }
                } else {
                    $temp2 = speedConstants::TEMP_NOTACTIVE;
                }
                if (isset($vehicle->customerno) && $vehicle->customerno == speedConstants::CUSTNO_PERKINELMER) {
                    $tempTd2 = "<div class='divBorder'><div class='inline' style='width:150px;text-align:left'>" . $vehicle->vehicleno . " - </div><div class='inline' style='width:110px;text-align:left'>" . $t2 . "</div><div style='width:100px;".$color2."' class='inline tempc2 " . $tdclass_temp2 . "'><div class='inline' style='width:60px;text-align:left'>" . $temp2 . "</div><div class='inline' style='width:15px;text-align:left'>" . $spantext2 . "</div><div class='inline' style='width:15px;text-align:right'>" . $tempReport2 . "</div></div></div>";
                } else {
                    $tempTd2 = "<div class='divBorder'><div class='inline' style='width:110px;text-align:left'>" . $t2 . "</div><div style='width:100px;".$color2."' class='inline tempc2 " . $tdclass_temp2 . "'><div class='inline' style='width:60px;text-align:left'>" . $temp2 .$temp2_in_farnht. "</div><div class='inline' style='width:15px;text-align:left'>" . $spantext2 . "</div><div class='inline' style='width:15px;text-align:right'>" . $tempReport2 . "</div></div></div>";
                }
            case 1:
                if ($vehicle->tempsen1 != 0) {
                    $spantext1 = " <span onclick='muteVehicle($vehicle->vehicleid,1)'>" . speedConstants::TEMP_MUTE . "</span>";
                    if ($vehicle->temp1_mute == 1) {
                        $spantext1 = " <span onclick='unmuteVehicle($vehicle->vehicleid,1)'>" . speedConstants::TEMP_UNMUTE . "</span>";
                    }
                    $tempReport1 = " <img title='Temperature Report' onclick='tempreport(" . $vehicle->vehicleid . ",1," . $vehicle->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
                    $vehicle->temp1_min = isset($vehicle->temp1_min) ? $vehicle->temp1_min : 0;
                    $vehicle->temp1_max = isset($vehicle->temp1_max) ? $vehicle->temp1_max : 0;
                    $s = "analog" . $vehicle->tempsen1;
                    if ($vehicle->$s != 0) {
                        $tempconversion->rawtemp = $vehicle->$s;
                        $temp = getTempUtil($tempconversion);
                        if ($temp != 0) {
                            $tdclass_temp1 = " normalTemperature";
                            if ($temp < $vehicle->temp1_min || $temp > $vehicle->temp1_max) {
                                if ($vehicle->temp1_min != $vehicle->temp1_max) {
                                    $tdclass_temp1 = " off";
                                }
                            } elseif ($vehicle->temp1_allowance > 0) {
                                if (($temp > $vehicle->temp1_min && $temp < ($vehicle->temp1_min + $vehicle->temp1_allowance)) || ($temp < $vehicle->temp1_max && $temp > ($vehicle->temp1_max - $vehicle->temp1_allowance))) {
                                    $tdclass_temp1 = " allowance";
                                }
                            }
                            if (isset($vehicle->trcmvehicleid)) {
                                if($temp > $vehicle->temp1_range1_start && $temp < $vehicle->temp1_range1_end) {
                                    $color1 = 'background-color:'.$vehicle->temp1_range1_color;
                                } elseif ($temp > $vehicle->temp1_range2_start && $temp < $vehicle->temp1_range2_end) {
                                    $color1 = 'background-color:'.$vehicle->temp1_range2_color;
                                } elseif ($temp > $vehicle->temp1_range3_start && $temp < $vehicle->temp1_range3_end) {
                                    $color1 = 'background-color:'.$vehicle->temp1_range3_color;
                                } elseif ($temp > $vehicle->temp1_range4_start && $temp < $vehicle->temp1_range4_end) {
                                    $color1 = 'background-color:'.$vehicle->temp1_range4_color;
                                }
                            }
                            $temp1 = $temp . speedConstants::TEMP_DEGREE;
                            if($vehicle->customerno == speedConstants::CUSTNO_CUREFIT){
                                $temp1_in_farnht = " (".round(($temp * 9/5) + 32, 2)."<sup>o</sup>F)";
                            }
                            else{
                                 $temp1_in_farnht = "";
                            }
                        }
                    }
                } else {
                    $temp1 = speedConstants::TEMP_NOTACTIVE;
                }
                if (isset($vehicle->customerno) && $vehicle->customerno == speedConstants::CUSTNO_PERKINELMER) {
                    $tempTd1 = "<div><div class='inline' style='width:150px;text-align:left'>" . $vehicle->vehicleno . " - </div><div class='inline' style='width:110px;text-align:left'>" . $t1 . "</div><div style='width:100px;".$color1."' class='inline tempcl " . $tdclass_temp1 . "'><div class='inline' style='width:60px;text-align:left'>" . $temp1 . "</div><div class='inline'style='width:15px;text-align:left'>" . $spantext1 . "</div><div class='inline' style='width:15px;text-align:right'>" . $tempReport1 . "</div></div></div>";
                } else {
                    $tempTd1 = "<div><div class='inline' style='width:110px;text-align:left'>" . $t1 . "</div><div style='width:100px;".$color1."' class='inline tempcl " . $tdclass_temp1 . "'><div class='inline' style='width:60px;text-align:left'>" . $temp1 .$temp1_in_farnht. "</div><div class='inline'style='width:15px;text-align:left'>" . $spantext1 . "</div><div class='inline' style='width:15px;text-align:right'>" . $tempReport1 . "</div></div></div>";
                }
            }
            $tempString .= "<td>";
            if ($tempTd1 != "") {
                $tempString .= $tempTd1;
            }
            if ($tempTd2 != "") {
                $tempString .= "<br>" . $tempTd2;
            }
            if ($tempTd3 != "") {
                $tempString .= "<br" . $tempTd3;
            }
            if ($tempTd4 != "") {
                $tempString .= "<br>" . $tempTd4;
            }
            $tempString .= "</td>";

            if($vehicle->customerno == speedConstants::CUSTNO_MDLZ){
                $count = 0;
                $tempSum = 0;
                if($temp1!=speedConstants::TEMP_NOTACTIVE && $temp1 !=speedConstants::TEMP_WARNING){
                    $count++;
                    $tempSum += $temp1;
                }
                if($temp2!=speedConstants::TEMP_NOTACTIVE && $temp2 !=speedConstants::TEMP_WARNING){
                    $count++;
                    $tempSum += $temp2;
                }
                if($temp3!=speedConstants::TEMP_NOTACTIVE && $temp3 !=speedConstants::TEMP_WARNING){
                    $count++;
                    $tempSum += $temp3;
                }
                if($temp4!=speedConstants::TEMP_NOTACTIVE && $temp4 !=speedConstants::TEMP_WARNING){
                    $count++;
                    $tempSum += $temp4;
                }

                if($count > 0 ){
                    $average = round(($tempSum / $count),2);
                    $average = $average . speedConstants::TEMP_DEGREE;
                }
            }
            if($vehicle->customerno != '116'){
                echo $tempString;
            } 
            return $average;
        }

        function get_humidity($vehicle) {
            $temp_conversion = new TempConversion();
            $temp_conversion->unit_type = $vehicle->get_conversion;
            $temp_conversion->use_humidity = $_SESSION["use_humidity"];
			$temp_conversion->is_humidity = 1;
            $temp_conversion->switch_to = $_SESSION["switch_to"];
            $degree = "%";
            $temp1 = $temp2 = $temp3 = $temp4 = '-';
            $humrep = "<img title='Humidity & Temperature Report' onclick='humreport($vehicle->vehicleid,$vehicle->deviceid)' src='../../images/temp_report.png' width='20' height='20'>";
            if ($_SESSION['use_humidity'] == 1) {
                $s = "analog" . $vehicle->humidity;
                if ($vehicle->humidity != 0 && $vehicle->$s != 0) {
                    $temp_conversion->rawtemp = $vehicle->$s;
                    $temp1 = getTempUtil($temp_conversion) . " $degree";
                }
                if ($temp1 == '-') {
                    echo "<td class='humicl'>$temp1</td>";
                } else {
                    echo "<td class='humicl'>$temp1&nbsp;$humrep</td>";
                }
            }
        }

        function getdate_IST() {
            $ServerDate_IST = strtotime(date("Y-m-d H:i:s"));
            return $ServerDate_IST;
        }

        function getVehicleName($vehicleid) {
            $VehicleManager = new VehicleManager($_SESSION['customerno']);
            $vehicleno = $VehicleManager->getVehicleName($vehicleid);
            return $vehicleno;
        }

        function getRemark($remarkid, $customerno) {
            $VehicleManager = new VehicleManager($customerno);
            $vehicleno = $VehicleManager->getRemark($remarkid);
            return $vehicleno;
        }

        function gettemp($rawtemp) {
            if ($_SESSION['use_humidity'] == 1) {
                $temp = round($rawtemp / 100);
            } else {
                $temp = round((($rawtemp - 1150) / 4.45), 1);
            }
            return $temp;
        }

        function getpressure($rawtemp) {
            $temp = $rawtemp;
            return $temp;
        }

        function vehicle_table($vehicleid) {
            echo "<tr>";
            //External Battery
            $vehicle->extbatt = round($vehicle->extbatt / 100, 2);
            echo "<td>$vehicle->extbatt</td>";
            // Internal Battery
            if ($vehicle->inbatt != "" && $vehicle->inbatt != NULL) {
                echo "<td>" . ($vehicle->inbatt / 1000) . "</td>";
            } else {
                echo '<td>0</td>';
            }
            // Network Strength
            echo "<td>signal_strength($vehicle->gsmstrength)</td>";
            //Phone No.
            echo "<td>$vehicle->phone</td>";
            echo "</tr>";
            echo "</tbody></table>";
        }

        function get_vehicledesc_by_vehicleid($vehicleid) {
            $vehiclemanager = new VehicleManager($_SESSION['customerno']);
            $vehicledata = $vehiclemanager->get_all_vehiclesbyid($vehicleid);
            if (!isset($_SESSION['ecodeid'])) {
            ?>
        <div style="height: 330px; width: 100%; overflow-y: scroll;">
            <table id="map_table" class="vehicletable_<?php echo $vehicledata->vehicleid; ?>">
                <tbody>
                    <?php
                        if ($_SESSION['userid'] != 391 && $_SESSION['userid'] != 392) {
                                    if ($_SESSION['portable'] != '1') {
                                        if ($_SESSION["use_fuel_sensor"] == '1') {
                                        ?>
                                <tr><th colspan="4">Fuel Meter </th></tr>
                                <tr>
                                    <?php
                                        $tank = $vehiclemanager->get_Fuel_Tank($vehicledata->vehicleid);
                                                            $fuel = $vehiclemanager->getFueledVehicle_byID($vehicledata->vehicleid);
                                                            $fuelalert = $vehiclemanager->get_FuelAlert();
                                                            $date = ('Y-m-d');
                                                            $totaldistance = getdistance($fuel[0]->unitno, $date);
                                                            if ($totaldistance != 0) {
                                                                $consumedfule = round($totaldistance / $fuel[0]->average, 2);
                                                                $balancefuel = round($fuel[0]->fuel_balance - $consumedfule, 2);
                                                            } else {
                                                                $balancefuel = round($fuel[0]->fuel_balance, 2);
                                                            }
                                                            $alert = $tank[0]->fuelcapacity / 100 * $fuelalert;
                                                            $fueltank = 0;
                                                            $average = 0;
                                                            $fuelbalance = 0;
                                                            if ($tank[0]->fuelcapacity != 0) {
                                                                $fueltank = $tank[0]->fuelcapacity;
                                                            }
                                                            if ($fuel[0]->average != 0) {
                                                                $average = $fuel[0]->average;
                                                            }
                                                            if ($fuel[0]->fuel_balance != 0) {
                                                                $fuelbalance = $fuel[0]->fuel_balance;
                                                            }
                                                            /*
                                                             * function to load js
                                                            draw_fuel_gauge(<?php echo $tank[0]->fuelcapacity;?>, <?php echo $alert;?>);
                                                             *
                                                             */
                                                        ?>
                                    <td colspan="4">
                                        <input type="hidden" id="capacity_<?php echo $vehicledata->vehicleid; ?>" value="<?php echo $tank[0]->fuelcapacity; ?>"/>
                                        <input type="hidden" id="alert_<?php echo $vehicledata->vehicleid; ?>" value="<?php echo $alert; ?>"/>
                                        <input type="hidden" id="balance_<?php echo $vehicledata->vehicleid; ?>" value="<?php echo $balancefuel; ?>"/>
                            <center>
                                <?php
                                    if ($tank[0]->fuelcapacity != 0 || $fuel[0]->avarage != 0) {
                                                        ?>
                                    <div id="chart_fuel_<?php echo $vehicledata->vehicleid; ?>">
                                    </div>
                                    <?php
                                        } else {
                                                                echo "<img src='../../images/RTD/Fuel/gauge.png' alt='Inactive'/>";
                                                                //                          echo "</br>"; echo "<a href='javascript:void(0);' onclick='add_fulecon($vehicledata->vehicleid,$average,$fueltank,$fuelbalance);'><input type='button' name='activate' id='activate' value='Activate' class='btn btn-primary'/></a>";
                                                            }
                                                        ?>
                            </center>
                            </td>
                            </tr>
                            <?php
                                }
                                            }
                                        }
                                    ?>
                <tr>
                    <?php
                        if ($_SESSION['portable'] != '1') {
                                    echo "<th>Ext Batt (V)</th><th>Int Batt (V) ";
                                } else {
                                    echo "<th colspan='2'>Int Batt</th>";
                            }
                            ?></th><th>Network</th><th>Phone No</th></tr>
                <tr>
                    <?php
                        //External Battery
                                $vehicledata->extbatt = round(($vehicledata->extbatt) / 100, 2);
                                //echo "<td>$vehicle->extbatt</td>";
                                if ($_SESSION['portable'] != '1') {
                                ?>
                        <td class="ebcl_<?php echo $vehicledata->vehicleid; ?>"><?php echo $vehicledata->extbatt; ?></td>
                        <td class="ibcl_<?php echo $vehicledata->vehicleid; ?>">
                            <?php
                                if ($vehicledata->intbatt != "" && $vehicledata->intbatt != NULL) {
                                                echo $vehicledata->intbatt / 1000;
                                            } else {
                                                echo '0';
                                            }
                                        ?>
                        </td>
                    <?php } else {
                                ?>
                        <td colspan="2" class="ibcl_<?php echo $vehicledata->vehicleid; ?>">
                            <?php
                                if ($vehicledata->intbatt != "" && $vehicledata->intbatt != NULL) {
                                                echo $vehicledata->intbatt / 1000;
                                            } else {
                                                echo '0';
                                            }
                                        ?>
                        </td>
                        <?php
                            }
                                    signal_strength($vehicledata->gsmstrength, $vehicledata->vehicleid);
                                ?>
                    <td><?php echo substr($vehicledata->phone, 0, 3) . '*******' ?></td>
                </tr>
                <tr>
                    <th colspan="4" class="tooltip-top" title="Want to get an email / SMS when vehicle reaches a point?">Check Point
                        <?php
                            $checkpoints = get_chks_for_vehicle($vehicleid);
                                    if ($_SESSION["role_modal"] == 'Administrator' || $_SESSION["role_modal"] == 'elixir') {
                                    ?>
                            <a href="#addcheckpoint" onClick="addedcheckpoint();" class="add_checkpoint_<?php echo $vehicledata->vehicleid; ?> " data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;">
                                <img class="tooltip-top" title="Click To Add Checkpoint" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;"></a>
                                <!-- <a href="#checkpt_popup1" onClick="addedcheckpoint();" id="checkpoint_pop" class="add_checkpoint_<?php //echo $vehicledata->vehicleid;                        ?>"><img class="tooltip-top" title="Click To Add Checkpoint" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;"></a>-->
                        <?php } elseif (isset($_SESSION['ecodeid'])) {?>
                            <a href="#addcheckpoint" onClick="addedcheckpoint();" class="add_checkpoint_<?php echo $vehicledata->vehicleid; ?> " data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;">
                                <img class="tooltip-top" title="Click To Add Checkpoint" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;"></a>
                        <?php }?>
                    </th>
                </tr>
                <tr>
                    <th colspan="2">Check Point Name</th><th colspan="2">Radius (in KM)</th>
                </tr>
                <?php
                    if (isset($checkpoints)) {
                                foreach ($checkpoints as $checkpoint) {
                                    if ($_SESSION["role_modal"] == 'Administrator' || $_SESSION["role_modal"] == 'elixir') {
                                        echo '<tr class="edit_chkname" id="c_' . $checkpoint->checkpointid . '" rel="' . $vehicledata->vehicleid . '"><td class="edit_chkname" id="' . $checkpoint->checkpointid . '" rel="' . $vehicledata->vehicleid . '" colspan="2"><span id="chkname_' . $vehicledata->vehicleid . '_' . $checkpoint->checkpointid . '"  class="text tooltip-top" title="Double Click To Edit">' . $checkpoint->cname . '</span><input id="chkname_input_' . $vehicledata->vehicleid . '_' . $checkpoint->checkpointid . '" size="10" class="editbox" type="text" value="' . $checkpoint->cname . '"></td>';
                                        echo "<input type='hidden' class='chk_latlong' value='" . $checkpoint->cgeolat . "," . $checkpoint->cgeolong . "," . $checkpoint->crad . "," . $checkpoint->cname . "'/>";
                                        echo "<input type='hidden' id='" . $checkpoint->checkpointid . "' class='chk_id' value='" . $checkpoint->checkpointid . "'/>";
                                        echo '<td class="edit_chkname" id="' . $checkpoint->checkpointid . '" rel="' . $vehicledata->vehicleid . '" colspan="2"><span id="chkrad_' . $vehicledata->vehicleid . '_' . $checkpoint->checkpointid . '"  class="text tooltip-top" title="Double Click To Edit">' . $checkpoint->crad . '</span><input size="4" id="chkrad_input_' . $vehicledata->vehicleid . '_' . $checkpoint->checkpointid . '" class="editbox" type="text" value="' . $checkpoint->crad . '"><a href="#delete_chk" onClick="delete_chkpoint_by_id(' . $checkpoint->checkpointid . ');" id="delete_ckhpop" class="delete_chkpoint_' . $vehicledata->vehicleid . '" data-toggle="modal"><img class="del_chkpoint_' . $checkpoint->checkpointid . ' tooltip-top" title="Click To Delete Checkpoint" src="../../images/delete1.png" style="width: 12px; height:12px; vertical-align:middle; float: right;"></td></tr>';
                                    } else {
                                        echo '<tr id="c_' . $checkpoint->checkpointid . '" rel="' . $vehicledata->vehicleid . '"><td id="' . $checkpoint->checkpointid . '" rel="' . $vehicledata->vehicleid . '" colspan="2">' . $checkpoint->cname . '</td>';
                                        echo "<input type='hidden' class='chk_latlong' value='" . $checkpoint->cgeolat . "," . $checkpoint->cgeolong . "," . $checkpoint->crad . "," . $checkpoint->cname . "'/>";
                                        echo "<input type='hidden' class='chk_rad' value='" . $checkpoint->crad . "'/>";
                                        echo '<td id="' . $checkpoint->checkpointid . '" rel="' . $vehicledata->vehicleid . '" colspan="2">' . $checkpoint->crad . '</td></tr>';
                                    }
                                }
                            }
                        ?>
                <tr>
                    <th colspan="4" class="tooltip-top" title="Want to get an email / SMS when vehicle breaches a fence?">Fence
                        <?php if ($_SESSION["role_modal"] == 'Administrator' || $_SESSION["role_modal"] == 'elixir') {?>
                            <a href="#addfence" onClick="addedfence();" class="add_fencing_<?php echo $vehicledata->vehicleid; ?> " data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;"><img class="tooltip-top" title="Click To Add Fence" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;"></a>
                            <!--    <a href="#fence_popup1" onClick="addedfence();" id="fence_pop" class="add_fencing_<?php //echo $vehicledata->vehicleid;                                        ?>"><img class="tooltip-top" title="Click To Add Fence" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;"></a>-->
                        <?php }?>
                    </th>
                </tr>
                <?php
                    $geofences = get_fences_for_vehicle($vehicleid);
                            if (isset($geofences)) {
                                foreach ($geofences as $geofence) {
                                    if ($_SESSION["role_modal"] == 'Administrator' || $_SESSION["role_modal"] == 'elixir') {
                                        echo '<tr class="edit_fences" id="f_' . $geofence->fenceid . '" rel="' . $vehicledata->vehicleid . '"><td class="edit_fence" id="' . $geofence->fenceid . '" rel="' . $vehicledata->vehicleid . '" colspan="4"><span id="fencename_' . $vehicledata->vehicleid . '_' . $geofence->fenceid . '"  class="text tooltip-top" title="Double Click To Edit">' . $geofence->fencename . '</span><input id="fencename_input_' . $vehicledata->vehicleid . '_' . $geofence->fenceid . '" class="editbox" type="text" value="' . $geofence->fencename . '"><a href="#delete_fence" onClick="delete_fence_by_id(' . $geofence->fenceid . ');" id="delete_pop" data-toggle="modal" class="delete_fencing_' . $vehicledata->vehicleid . '"><img class="tooltip-top" title="Click To Delete Fence" src="../../images/delete1.png" style="width: 12px; height:12px; vertical-align:middle; float: right;"></a>';
                                        echo "<input type='hidden' class='fence_latlong' value='" . json_encode($geofence->geofencelatlong) . "," . $geofence->fencename . "'/></td><tr>";
                                        echo "<input type='hidden' id='" . $geofence->fenceid . "' class='fence_id' value='" . $geofence->fenceid . "'/>";
                                    } else {
                                        echo '<tr id="f_' . $fence->fenceid . '" rel="' . $vehicledata->vehicleid . '"><td id="' . $fence->fenceid . '" rel="' . $vehicledata->vehicleid . '" colspan="4">' . $fence->fencename . '</td><tr>';
                                    }
                                }
                            }
                        ?>
                <script>
                    jQuery(document).ready(function () {
                        //alert("hhrhrhr");
                        jQuery('.tooltip-top').tipsy({gravity: 'se'});
                        jQuery(".tipsy").each(function () {
                            jQuery(this).hide();
                        });
                    });
                    function delete_fence_by_id(key_fence) {
                        jQuery('#del_fence').val(key_fence);
                    }
                    function delete_chkpoint_by_id(key_chkpoint) {
                        jQuery('#del_chkpoint').val(key_chkpoint);
                    }
                    function delete_ecode_by_id(key_ecode) {
                        jQuery('#del_ecode').val(key_ecode);
                    }
                </script>
                <th colspan="4" class="tooltip-top" title="Want your clients to track your vehicles?">Client Code
                    <?php if ($_SESSION["role_modal"] == 'Administrator' || $_SESSION["role_modal"] == 'elixir') {?>
                        <a href="#getecode" onClick="addedvehicle(<?php echo $vehicledata->vehicleid; ?>);" class="ecode_<?php echo $vehicledata->vehicleid; ?>" data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;"><img class="tooltip-top" title="Generate a Client Code" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;"></a>
                    <?php }?>
                </th>
                </tr>
                <tr><th colspan="2">Code</th><th colspan="2">Exp Date</th></tr>
                <?php
                    $codes = get_ecode_for_vehicle($vehicleid);
                            if (isset($codes)) {
                                foreach ($codes as $ecode) {
                                    if ($_SESSION["role_modal"] == 'Administrator' || $_SESSION["role_modal"] == 'elixir') {
                                        echo '<tr class="edit_ecode" id="e_' . $ecode->id . '" rel="' . $vehicledata->vehicleid . '"><td colspan="2">' . $ecode->ecode . '</td><td colspan="2">' . $ecode->expirydate . '<a href="#delete_ecode1" data-toggle="modal" onClick="delete_ecode_by_id(' . $ecode->id . ');" id="delete_ecodepop" class="delete_ecode_' . $vehicledata->vehicleid . '"><img class="tooltip-top" title="Click To Delete Client Code" src="../../images/delete1.png" style="width: 12px; height:12px; vertical-align:middle; float: right;"></a></td></tr>';
                                    } else {
                                        echo '<tr><td colspan="2">' . $ecode->ecode . '</td><td colspan="2">' . $ecode->expirydate . '</td></tr>';
                                    }
                                }
                            }
                        ?>
                </tbody>
            </table>
        </div>
        <?php
            echo '<input type="hidden" id="chk_vehid" value="' . $vehicleid . '">';
                    include 'modal_addcheckpoint.php';
                    echo '<input type="hidden" id="fence_vehid" value="' . $vehicleid . '">';
                    include 'modal_addfence.php';
                    $codes = get_ecode_for_vehicle($vehicleid);
                    include 'modal_createcode.php';
                    echo '<input type="hidden" id="del_fence_vehid" value="' . $vehicleid . '">';
                    include 'delete.php';
                    echo '<input type="hidden" id="del_chk_vehid" value="' . $vehicleid . '">';
                    include 'delete_chk.php';
                    echo '<input type="hidden" id="del_ecode_vehid" value="' . $vehicleid . '">';
                    include 'delete_ecode.php';
                    //                    echo '<input type="hidden" id="fence_vehid" value="'.$vehicleid.'">';
                    //                    include('modal_fence_NEW.php');
                 ?>
        <a href="#x" class="overlay" id="fence_popup"></a>
        <div class="popup">
            <?php
                echo '<input type="hidden" id="fence_vehid" value="' . $vehicleid . '">';
                        include 'modal_fence.php';
                    ?>
            <a class="close1" href="#close"></a>
        </div>
        <a href="#x" class="overlay" id="checkpt_popup"></a>
        <div class="popup">
            <?php
                echo '<input type="hidden" id="chk_vehid" value="' . $vehicleid . '">';
                        include 'modal_checkpt.php';
                    ?>
            <a class="close1" href="#close"></a>
        </div>
        <?php
            }
            }

            function getgroups() {
                $groupmanager = new GroupManager($_SESSION['customerno']);
                $allgroups = $groupmanager->getallgroups();
                return $allgroups;
            }

            function getallvehicles() {
                $vehiclemanager = new VehicleManager($_SESSION['customerno']);
                $allvehicles = $vehiclemanager->Get_All_Vehicles_SQLite();
                return $allvehicles;
            }

            function getallvehiclesbygroup($groupid) {
                $vehiclemanager = new VehicleManager($_SESSION['customerno']);
                $allvehicles = $vehiclemanager->Get_All_Vehicles_SQLite_ByGroup($groupid);
                return $allvehicles;
            }

            function get_vehicle_histdata_leftdiv($vehicleid) {
                include_once '../../lib/bo/ComQueueManager.php';
                $cqm = new ComQueueManager($_SESSION['customerno']);
                $currentdate = date("d-m-Y");
                $i = 1;
                $data = '';
                $data .= '<div style="height: 330px; width: 100%; background-color:#E8EDFF; overflow-y: scroll;"><table width="100%" style="border : 1px solid #fff;">'
                    . '<tr><td colspan="5" style="text-align:center; border : 1px solid #fff;"><b>Alert History</b></td></tr>'
                    . '<tr><td style="border : 1px solid #fff;">No.</td><td style="border : 1px solid #fff;">Message</td><td>Time</td>';
                $queues = $cqm->getalerthistleftdiv($vehicleid, $_SESSION['customerno']);
                if (isset($queues)) {
                    foreach ($queues as $queue) {
                        if ($queue->processed == 1 && $queue->comtype == 0) {
                            $data .= "<tr>"
                                . "<td style='border : 1px solid #fff;'>$i</td><td style='border : 1px solid #fff;'>$queue->message</td><td style='border : 1px solid #fff;' >$queue->timeadded</td></tr>";
                        } elseif ($queue->processed == 1 && $queue->comtype == 1) {
                            $data .= "<tr><td style='border : 1px solid #fff;'>$i</td><td style='border : 1px solid #fff;'>$queue->message</td><td style='border : 1px solid #fff;' >$queue->timeadded</td></tr>";
                        } else {
                            $data .= "<tr><td style='border : 1px solid #fff;' >$i</td><td style='border : 1px solid #fff;'>$queue->message</td><td style='border : 1px solid #fff;'>$queue->timeadded</td></tr>";
                        }
                        $i++;
                    }
                }
                if (empty($queues)) {
                    $data .= "<tr><td colspan='5' style='text-align:center; border : 1px solid #fff;'>No Data Available</td></tr>";
                }
                $data .= '</body></table></div>';
                echo $data;
            }

            function updateRefreshTime($refreshTime) {
                include_once '../../lib/bo/UserManager.php';
                $userManager = new UserManager($_SESSION['customerno']);
                $userManager->updateRefreshTime($refreshTime);
            }

        ?>
