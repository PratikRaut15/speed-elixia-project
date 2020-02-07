<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
include_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
include_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
include_once $RELATIVE_PATH_DOTS . 'lib/comman_function/reports_func.php';
include_once '../../lib/model/TempConversion.php';
set_time_limit(0);
if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
}
date_default_timezone_set($_SESSION['timezone']);
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
    //$vehicledata = $vehiclemanager->getvehiclesforrtdwithpagination();
    $vehicledata['vehicleData'] = $vehiclemanager->getvehiclesforrtdwithpagination();
    $vehicledata['vehicleStatusData'] = $vehiclemanager->getVehicleStatusData();
    return $vehicledata;
}

function getvehiclesforstatus() {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicledata = $vehiclemanager->getvehiclesforrtd();
    return $vehicledata;
}

function get_filter_vehicles($sel_status, $sel_stoppage) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    //$vehicledata = $vehiclemanager->get_filter_vehiclesforrtd($sel_status, $sel_stoppage);
    $vehicledata['vehicleData'] = $vehiclemanager->get_filter_vehiclesforrtd($sel_status, $sel_stoppage);
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
    $bgcolour = '';
    $less_1 = date('Y-m-d H:i:s', strtotime('-60 minutes'));
    $less_48 = date('Y-m-d H:i:s', strtotime('-2 days'));
    $less_7days = date('Y-m-d H:i:s', strtotime('-7 days'));
    if ($lastupdated < $less_1 && $lastupdated > $less_48) {
        $bgcolour = "#B4F716";
    } elseif ($lastupdated < $less_48 && $lastupdated > $less_7days) {
        $bgcolour = "#FFB249";
    } else {
        $bgcolour = "#C64E00";
    }
    echo "<tr  style='background:$bgcolour;'>";
    return $lastupdated;
}

function row_highlight_by_id($lastupdated, $row_id) {
    $lastupdated = new DateTime($lastupdated);
    echo "<tr id='" . $row_id . "' style='height:10px;cursor:pointer;' >";
    return $lastupdated;
}

function row_highlight_by_id_changeforfreeze($lastupdated, $row_id, $isfreeze, $sessfreeze) {
    $lastupdated = new DateTime($lastupdated);
    if ($sessfreeze == 1 && $isfreeze == 1) {
        echo "<tr class='freezetr' id='" . $row_id . "' style='height:10px;cursor:pointer;' >";
    } else {
        echo "<tr id='" . $row_id . "' style='height:10px;cursor:pointer;' >";
    }
    return $lastupdated;
}

function location($lat, $long, $usegeolocation, $customerno = null) {
    $address = NULL;
    $customerno = (!isset($customerno)) ? $_SESSION['customerno'] : $customerno;
    $GeoCoder_Obj = new GeoCoder($customerno);
    $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
    return $address;
}

function getimage($vehicle) {
    $ServerIST_less1 = new DateTime();
    $min = $_SESSION["timediff"];
    if (!empty($min)) {
        $min = "+$min seconds";
        $ServerIST_less1->modify($min);
    }
    $ServerIST_less1->modify('-60 minutes');
    $vehicle->lastupdated = new DateTime($vehicle->lastupdated->format('Y-m-d H:i:s'));
    $diff = getduration($vehicle->stoppage_transit_time);
    $image = "<img id='innerImg' class='imgcl'  src='../../images/RTD/Vehicles/";
    if ($vehicle->type == 'Bus') {
        $image .= "Bus/Bus";
    } elseif ($vehicle->type == 'Truck') {
        $image .= "Truck/Truck";
    } elseif ($vehicle->type == 'Car' || $vehicle->type == 'SUV' || $vehicle->type == 'Cab') {
        $image .= "Car/Car";
    }
    if ($_SESSION['portable'] != '1') {
        if ($vehicle->lastupdated < $ServerIST_less1) {
            $image .= "I.png'/>";
        } else {
            if ($vehicle->ignition == '0') {
                $image .= "IOn.png'/>";
            } else {
                if ($vehicle->curspeed > $vehicle->overspeed_limit) {
                    $image .= "O.png'/>";
                } else {
                    if ($vehicle->stoppage_flag == '0') {
                        $image .= "N.png'/>";
                    } else {
                        $image .= "R.png'/>";
                    }
                }
            }
        }
    } else {
        $image .= "R.png'/>";
    }
    return $image;
}

function getstatus($stoppage_flag, $stoppage_transit_time, $lastupdated_store, $gpsfixed = null) {
    $status = '';
    $ServerIST_less1 = new DateTime();
    $ServerIST_less1->modify('-60 minutes');
    $lastupdated = new DateTime($lastupdated_store);
    if ($lastupdated < $ServerIST_less1) {
        $status = "Inactive";
    } elseif (isset($gpsfixed) && $gpsfixed == 'V') {
        $status = "GPS Signal Lost";
    } else {
        $diff = getduration_status($stoppage_transit_time);
        if ($stoppage_flag == '0') {
            $status = "Idle since<br> " . $diff;
        } else {
            $status = "Running since<br> " . $diff;
        }
    }
    return $status;
}

function vehicleimage($device) {
    $basedir = "../../images/vehicles/";
    if ($device->directionchange != '' && $device->directionchange >= 0) {
        $directionfile = round($device->directionchange / 10);
    } else {
        $directionfile = 0;
    }
    if ($device->type == 'Car' || $device->type == 'Cab' || $device->type == 'SUV') {
        $device->type = 'Car';
    } elseif ($device->type == 'Bus') {
        $device->type = 'Bus';
    } elseif ($device->type == 'Truck') {
        $device->type = 'Truck';
    }
    if ($device->type == 'Car' || $device->type == 'Bus' || $device->type == 'Truck') {
        $ServerIST_less1 = new DateTime();
        $ServerIST_less1->modify('-60 minutes');
        $lastupdated = new DateTime($device->lastupdated_store);
        if ($lastupdated < $ServerIST_less1) {
            $image = $device->type . "/Idle/" . $device->type . $directionfile . ".png";
        } elseif ($device->ignition == '0') {
            $image = $device->type . "/IdleIgnOff/" . $device->type . $directionfile . ".png";
        } else {
            if ($device->curspeed > $device->overspeed_limit) {
                $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
            } else {
                if ($device->stoppage_flag == '0') {
                    $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
                } else {
                    $image = $device->type . "/Running/" . $device->type . $directionfile . ".png";
                }
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
        $imageTd = "<img id='innerSmall' class = 'pccl' src = '../../images/RTD/PowerCut/off.png' title = 'PowerCut'>";
    } else {
        $imageTd = "<span id='innerSmall' class = 'pccl' title = 'Normal'>";
    }
    return $imageTd;
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
    $vehicledata = getvehicles_withpagination();
    if (isset($vehicledata)) {
        print_vehicledata($vehicledata);
    } else {
        echo "<tr><td colspan=100%>No Data Avialable</td></tr>";
    }
    echo "</tbody></table>";
}

function display_filter_vehicledata($sel_status, $sel_stoppage) {
    include 'pages/vehicle.php';
    $vehicledata = get_filter_vehicles($sel_status, $sel_stoppage);
    //    $vehiclestatusdata = getvehiclesforstatus();
    if (isset($vehicledata)) {
        print_vehicledata($vehicledata);
    } else {
        echo "<tr><td colspan=100%>No Data Avialable</td></tr>";
    }
    echo "</tbody></table>";
}

function display_vehicle_status() {
    $tempconversion = new TempConversion();
    $vehiclestatusdata = groupBased_vehicles_cron($_SESSION['customerno'], $_SESSION['userid']); //getvehiclesforstatus();
    $type = "";
    $a = 0;
    $b = 0;
    $c = 0;
    $d = 0;
    $e = 0;
    if (isset($vehiclestatusdata)) {
        foreach ($vehiclestatusdata as $vehicle) {
            $tempconversion->unit_type = $vehicle->get_conversion;
            $tempconversion->use_humidity = $_SESSION['use_humidity'];
            // Pull Details for Count
            if ($vehicle->type == 'Truck') {
                $type = 'Truck';
            } elseif ($vehicle->type == 'Warehouse') {
                $type = 'Truck';
            } elseif ($type == 'Truck') {
                $type = 'Truck';
            } else {
                $type = '';
            }
            $ServerIST_less1 = new DateTime();
            $ServerIST_less1->modify('-60 minutes');
            $lastupdated = new DateTime($vehicle->lastupdated_store);
            if ($lastupdated < $ServerIST_less1 || !isset($vehicle->lastupdated_store)) {
                $e++;
            } else {
                if ($vehicle->ignition == '0') {
                    $d++;
                } else {
                    if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 1) {
                        $temp = '';
                        $s = "analog" . $vehicle->tempsen1;
                        if ($vehicle->tempsen1 != 0 && $vehicle->$s != 0) {
                            $tempconversion->rawtemp = $vehicle->$s;
                            $temp = getTempUtil($tempconversion);
                        } else {
                            $temp = '';
                        }
                    } elseif (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 2) {
                        $temp1 = '';
                        $temp2 = '';
                        $s = "analog" . $vehicle->tempsen1;
                        if ($vehicle->tempsen1 != 0 && $vehicle->$s != 0) {
                            $tempconversion->rawtemp = $vehicle->$s;
                            $temp1 = getTempUtil($tempconversion);
                        } else {
                            $temp1 = '';
                        }
                        $s = "analog" . $vehicle->tempsen2;
                        if ($vehicle->tempsen2 != 0 && $vehicle->$s != 0) {
                            $tempconversion->rawtemp = $vehicle->$s;
                            $temp2 = getTempUtil($tempconversion);
                        } else {
                            $temp2 = '';
                        }
                    } elseif (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 3) {
                        $temp1 = '';
                        $temp2 = '';
                        $temp3 = '';
                        $s = "analog" . $vehicle->tempsen1;
                        if ($vehicle->tempsen1 != 0 && $vehicle->$s != 0) {
                            $tempconversion->rawtemp = $vehicle->$s;
                            $temp1 = getTempUtil($tempconversion);
                        } else {
                            $temp1 = '';
                        }
                        $s = "analog" . $vehicle->tempsen2;
                        if ($vehicle->tempsen2 != 0 && $vehicle->$s != 0) {
                            $tempconversion->rawtemp = $vehicle->$s;
                            $temp2 = getTempUtil($tempconversion);
                        } else {
                            $temp2 = '';
                        }
                        $s = "analog" . $vehicle->tempsen3;
                        if ($vehicle->tempsen3 != 0 && $vehicle->$s != 0) {
                            $tempconversion->rawtemp = $vehicle->$s;
                            $temp3 = getTempUtil($tempconversion);
                        } else {
                            $temp3 = '';
                        }
                    } elseif (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 4) {
                        $temp1 = '';
                        $temp2 = '';
                        $temp3 = '';
                        $temp4 = '';
                        $s = "analog" . $vehicle->tempsen1;
                        if ($vehicle->tempsen1 != 0 && $vehicle->$s != 0) {
                            $tempconversion->rawtemp = $vehicle->$s;
                            $temp1 = getTempUtil($tempconversion);
                        } else {
                            $temp1 = '';
                        }
                        $s = "analog" . $vehicle->tempsen2;
                        if ($vehicle->tempsen2 != 0 && $vehicle->$s != 0) {
                            $tempconversion->rawtemp = $vehicle->$s;
                            $temp2 = getTempUtil($tempconversion);
                        } else {
                            $temp2 = '';
                        }
                        $s = "analog" . $vehicle->tempsen3;
                        if ($vehicle->tempsen3 != 0 && $vehicle->$s != 0) {
                            $tempconversion->rawtemp = $vehicle->$s;
                            $temp3 = getTempUtil($tempconversion);
                        } else {
                            $temp3 = '';
                        }
                        $s = "analog" . $vehicle->tempsen4;
                        if ($vehicle->tempsen4 != 0 && $vehicle->$s != 0) {
                            $tempconversion->rawtemp = $vehicle->$s;
                            $temp4 = getTempUtil($tempconversion);
                        } else {
                            $temp4 = '';
                        }
                    }
                    if ($vehicle->curspeed > $vehicle->overspeed_limit) {
                        $a++;
                    } elseif ($vehicle->stoppage_flag == '0') {
                        $c++;
                    } else {
                        $b++;
                    }
                }
            }
        }
    }
    if ($_SESSION['use_warehouse'] == 1) {
        @$warehousestatusdata = @groupBased_warehosue_cron($_SESSION['customerno'], $_SESSION['userid']); //getvehiclesforstatus();
        if (isset($warehousestatusdata)) {
            foreach ($warehousestatusdata as $warehouse) {
                if ($warehouse->type == 'Warehouse') {
                    $type = 'Truck';
                } elseif ($type == 'Truck') {
                    $type = 'Truck';
                } else {
                    $type = '';
                }
            }
        }
    }

    if ($type == 'Truck') {
        $vehicletype = 'Truck/Truck';
    } else {
        $vehicletype = 'Car/Car';
    }
    ?>
    <div class="lGb" id="mapdetails search_table" style="margin-right: 2%; text-align: right;">
        <div class="b-s-t Ye rate3" id="1"><div class="zc bn ID-tooltipcontent-0"><font color="#FF0000"> Overspeed </font></div>
            <div class="zt"><div><div class="eK" ><font color="#FF0000" id="overspeed_val"><?php echo $a; ?></font><img style="padding-left: 5px;" src="../../images/RTD/Vehicles/<?php echo $vehicletype ?>O.png"></img></div></div></div>
        </div>
        <div class="b-s-t Ye rate3" id="2"><div class="zc bn ID-tooltipcontent-0"><font color="#009900">Running</font></div>
            <div class="zt"><div><div class="eK" ><font color="#009900" id="running_val"><?php echo $b; ?></font><img style="padding-left: 5px;" src="../../images/RTD/Vehicles/<?php echo $vehicletype ?>R.png"></img></div></div></div>
        </div>
        <div class="b-s-t Ye rate3" id="3"><div class="zc bn ID-tooltipcontent-0"><font color="#0066FF">Idle - Ign On</font></div>
            <div class="zt"><div><div class="eK" ><font color="#0066FF" id="idle_ign_on"><?php echo $c; ?></font><img style="padding-left: 5px;" src="../../images/RTD/Vehicles/<?php echo $vehicletype ?>N.png"></img></div></div></div>
        </div>
        <div class="b-s-t Ye rate3" id="4"><div class="zc bn ID-tooltipcontent-0"><font color="#FF9900">Idle - Ign Off</font></div>
            <div class="zt"><div><div class="eK" ><font color="#FF9900" id="idle_ign_off"><?php echo $d; ?></font><img style="padding-left: 5px;" src="../../images/RTD/Vehicles/<?php echo $vehicletype ?>IOn.png"></img></div></div></div>
        </div>
        <div class="b-s-t Ye rate3" id="5"><div class="zc bn ID-tooltipcontent-0"><font color="#707070">Inactive</font></div>
            <div class="zt"><div><div class="eK" ><font color="#707070" id="inactive_val"><?php echo $e; ?></font><img style="padding-left: 5px;" src="../../images/RTD/Vehicles/<?php echo $vehicletype ?>I.png"></img></div></div></div>
        </div>
    </div>
    <?php
}

function getvehicles_all() {
    $customerno = isset($_SESSION['customerno']) ? $_SESSION['customerno'] : null;
    $vehiclemanager = new VehicleManager($customerno);
    $vehicledata = $vehiclemanager->getvehiclesforrtd_all();
    return $vehicledata;
}

function getvehicles_all_inactive($from_date, $to_date, $crmmanager, $issuetype, $icustomer) {
    $customerno = isset($_SESSION['customerno']) ? $_SESSION['customerno'] : null;
    $vehiclemanager = new VehicleManager($customerno);
    $vehicledata = $vehiclemanager->getvehiclesforrtd_all_inactive($from_date, $to_date, $crmmanager, $issuetype, $icustomer);
    return $vehicledata;
}

function team_getvehicles_all_inactive($crmmanager, $issuetype, $icustomer) {
    $customerno = isset($_SESSION['customerno']) ? $_SESSION['customerno'] : null;
    $vehiclemanager = new VehicleManager($customerno);
    $vehicledata = $vehiclemanager->team_getvehiclesforrtd_all_inactive($crmmanager, $issuetype, $icustomer);
    return $vehicledata;
}

function team_getvehicles_all_inactive_days($crmmanager, $issuetype, $icustomer, $count_days) {
    $customerno = isset($_SESSION['customerno']) ? $_SESSION['customerno'] : null;
    $vehiclemanager = new VehicleManager($customerno);
    $vehicledata = $vehiclemanager->team_getvehiclesforrtd_all_inactive_days($crmmanager, $issuetype, $icustomer, $count_days);
    return $vehicledata;
}

function gettemp_wirecuts($crmmanager, $issuetype, $icustomer) {
    $customerno = isset($_SESSION['customerno']) ? $_SESSION['customerno'] : null;
    $vehiclemanager = new VehicleManager($customerno);
    $vehicledata = $vehiclemanager->gettemp_wirecuts($crmmanager, $issuetype, $icustomer);
    return $vehicledata;
}

//pending invoice
function getvehicles_pending_inv() {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicledata = $vehiclemanager->getvehiclesforrtd_all_pending();
    return $vehicledata;
}

//pending invoice as per search
function getsearch_pending_inv($expdate, $customerno) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $searchinv = $vehiclemanager->getsearch_pendinginv($expdate, $customerno);
    return $searchinv;
}

//pending renewals
function getvehicles_pending_inv1() {
    $vehiclemanager1 = new VehicleManager($_SESSION['customerno']);
    $vehicledata1 = $vehiclemanager1->getvehiclesforrtd_all_pending1();
    return $vehicledata1;
}

//pending invoice as per search
function getsearch_pending_renewal($expdate, $customerno, $insdate) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $searchinv = $vehiclemanager->getsearch_renewal($expdate, $customerno, $insdate);
    return $searchinv;
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

function display_vehicledata_all_inactive($vehicledata) {
    include 'pages/vehicle_all_inactive.php';
    //$vehicledata = getvehicles_all_inactive();
    if (isset($vehicledata)) {
        print_vehicledata_all_inactive($vehicledata);
        include '../common/locationmessage.php';
    } else {
        echo "<tr><td colspan=100%>No Data Avialable</td></tr>";
    }
    echo "</table>";
}

function display_vehicledata_all_temp_wirecuts($vehicledata) {
    include 'pages/temp_wirecuts.php';
    if (isset($vehicledata)) {
        print_temp_wirecuts($vehicledata);
        include '../common/locationmessage.php';
    } else {
        echo "<tr><td colspan=100%>No Data Avialable</td></tr>";
    }
    echo "</table>";
}

//to display all pending invoices
function display_pending_vehicles() {
    include 'pages/pending_invoices.php';
    $vehicledata = getvehicles_pending_inv();
    if (isset($vehicledata)) {
        print_vehicledata_pending_inv($vehicledata);
        include '../common/locationmessage.php';
    } else {
        echo "<tr><td colspan=100%>No Data Avialable</td></tr>";
    }
    echo "</table>";
}

//to display pending invoices BY search
function display_search_invoice($expdate, $customerno) {
    include 'pages/pending_invoices.php';
    $vehicledata = getsearch_pending_inv($expdate, $customerno);
    if (isset($vehicledata)) {
        print_search_pending_inv($vehicledata);
        include '../common/locationmessage.php';
    } else {
        echo "<tr><td colspan=100%>No Data Avialable</td></tr>";
    }
    echo "</table>";
}

function display_pending_renewals() {
    include 'pages/pending_renewals.php';
    $vehicledata1 = getvehicles_pending_inv1();
    if (isset($vehicledata1)) {
        print_vehicledata_pending_inv1($vehicledata1);
        include '../common/locationmessage.php';
    } else {
        echo "<tr><td colspan=100%>No Data Avialable</td></tr>";
    }
    echo "</table>";
}

//to display pending renewal invoices BY search
function display_search_renewal($expdate, $customerno, $insdate) {
    include 'pages/pending_invoices.php';
    $vehicledata = getsearch_pending_renewal($expdate, $customerno, $insdate);
    if (isset($vehicledata)) {
        print_search_pending_renewal($vehicledata);
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
    //$EndTime = date('Y-m-d H:i:s');
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

function print_vehicledata($vehicledata) {
    $customerno = $_SESSION['customerno'];
    $_SESSION["Session_UserRole"] = trim($_SESSION["Session_UserRole"]);
    $i = 1;
    $j = 0;
    $driverLabel = $_SESSION['Driver'];
    /* foreach ($vehicledata as $vehicle) { */
    foreach ($vehicledata['vehicleData'] as $vehicle) {
        $totaldistance = calculateRealtimeDisatnce($vehicle, $customerno);
        $location = location($vehicle->devicelat, $vehicle->devicelong, $vehicle->use_geolocation);
        $image = vehicleimage($vehicle);
        $veh_status = getstatus($vehicle->stoppage_flag, $vehicle->stoppage_transit_time, $vehicle->lastupdated_store, $vehicle->gpsfixed);
        $vehicle->lastupdated = row_highlight_by_id_changeforfreeze($vehicle->lastupdated, $vehicle->vehicleid, $vehicle->is_freeze, $_SESSION['freeze']);
        echo "<td><input type='hidden' id='latlong" . $vehicle->vehicleid . "' value='" . $vehicle->devicelat . "," . $vehicle->devicelong . "'/><a>" . $i++ . "</a></td>";
        echo "<td><div style='position: relative;'>";
        echo $vehImage = getimage($vehicle);
        echo $pwrImage = powercut($vehicle->powercut);
        echo "</div></td>";
        echo '<td><a title="Travel History" onclick="travelhistopen(' . $vehicle->vehicleid . ');"><img src="../../images/history-512.png"  style="cursor:pointer;height:25px;width:25px;"></img></a></td>';
        echo '<td><a title="Route History" onclick="routehistopen(' . $vehicle->vehicleid . ');"><img src="../../images/play_round.png"  style="cursor:pointer;height:30px;width:30px;"></img></a></td>';
        if ($_SESSION['buzzer'] == 1 && $vehicle->is_buzzer == 1) {
            echo "<td><a href='javascript:void(0);' onclick='click_buzzer($vehicle->vehicleid, $vehicle->unitno);'><img class = 'buzzer' src = '../../images/buzzer.png' alt='Buzzer On' title = 'Buzzer'></a></td>";
        } elseif ($_SESSION['buzzer'] == 1 && $vehicle->is_buzzer == 0) {
            echo "<td><a href='javascript:void(0);' onclick='click_buzzer1($vehicle->vehicleid, $vehicle->unitno);'><img class = 'rt' src = '../../images/buzzer.png' alt='Buzzer Off' title = 'Buzzer'></a></td>";
        }
        if ($_SESSION['immobiliser'] == 1 && $vehicle->is_mobiliser == 0) {
            echo "<td><a href='javascript:void(0);' onclick='click_immobiliser($vehicle->vehicleid, $vehicle->unitno, $vehicle->is_mobiliser, 1);'><img class = 'rt' src = '../../images/immobiliser1.png' alt='Immobiliser Off' title = 'Immobilizer'></a></td>";
        } elseif ($_SESSION['immobiliser'] == 1 && $vehicle->is_mobiliser == 1) {
            if ($vehicle->mobiliser_flag == 1) {
                echo "<td><a href='javascript:void(0);' onclick='click_immobiliser($vehicle->vehicleid, $vehicle->unitno, $vehicle->is_mobiliser, 2);'><img class = 'buzzer' src = '../../images/immobiliser.png' alt='Immobiliser On' title = 'Immobilizer'></a></td>";
            } elseif ($vehicle->mobiliser_flag == 0) {
                echo "<td><a href='javascript:void(0);' onclick='click_immobiliser($vehicle->vehicleid, $vehicle->unitno, $vehicle->is_mobiliser, 1);'><img class = 'buzzer' src = '../../images/immobiliser1.png' alt='Immobiliser Off' title = 'Immobilizer'></a></td>";
            }
        }
        if ($_SESSION['freeze'] == 1 && $vehicle->is_freeze == 1) {
            echo '<td><a href="javascript:void(0);" onclick="click_unfreeze(' . $vehicle->vehicleid . ', \'' . $vehicle->unitno . '\', ' . $vehicle->is_freeze . ', 0);"><img style="width:30px; height:24px;" class="buzzer"  src = "../../images/freezetrue.png"  alt="Freeze On" title = "Freeze" ></a></td>';
        } elseif ($_SESSION['freeze'] == 1 && $vehicle->is_freeze == 0) {
            echo '<td><a href="javascript:void(0);" onclick="click_freeze(' . $vehicle->vehicleid . ', \'' . $vehicle->unitno . '\', ' . $vehicle->is_freeze . ', 1);"><img style="width:30px; height:24px;" class="rt"  src = "../../images/freezefalse.png"  alt="Freeze Off" title = "Freeze" ></a></td>';
        }
        $diff = getduration($vehicle->lastupdated->format('Y-m-d H:i:s'));
        echo "<td class='lupd'>" . $diff . "</td>";
        if ($_SESSION['groupid'] == 0) {
            echo "<td class='grpid'>" . $vehicle->groupname . "</td>";
        }
        if ($_SESSION['portable'] != '1') {
            if (isset($vehicle->ignition_wirecut)) {
                if ($vehicle->ignition_wirecut == 1) {
                    //echo "<td class='status'>Ignition Wire Removed </td>";
                    //New testing code starts here
                    echo "<td class='status'>Ignition Wire Removed ";
                    /* Dropdown starts here */
                    if ($vehicle->vehicle_status_color_code != 0 || $vehicle->vehicle_status_color_code != '') {
                        echo "<div style='float: left;
                                     width: 10px;
                                     height: 10px;
                                     /*margin: 10px;*/
                                     margin-top: 27px;
                                     border: 1px solid rgba(0, 0, 0, .2);
                                     background: " . $vehicle->vehicle_status_color_code . ";'></div>";
                    } else {
                        echo "<div style='float: left;
                                width: 10px;
                                height: 10px;
                                /*margin: 10px;*/
                                margin-top: 27px;
                                border: 1px solid rgba(0, 0, 0, .2);
                                background: #008000;'></div>";
                    }
                    echo '<div class="styled-select" data-position="left" style="margin-left: 20px;padding-right: 18px;">';
                    echo "<select id='" . $vehicle->vehicleid . "' name='grouplist' onchange='updateVehicleCommonStatus(" . $vehicle->vehicleid . ",this.value)' class='input-mini'>";
                    foreach ($vehicledata['vehicleStatusData'] AS $statusData) {
                        if ($statusData->vehicleStatus == $vehicle->vehicle_status) {
                            echo "<option selected='selected' value=" . $statusData->vehicleStatusId . ">" . $statusData->vehicleStatus . "</option>";
                        } else {
                            echo "<option value=" . $statusData->vehicleStatusId . ">" . $statusData->vehicleStatus . "</option>";
                        }
                    }
                    //echo"<option value='add' style='color:black;'>Create new status...</option>";
                    echo "</select>";
                    echo "</div>";
                    /* Dropdown ends here */
                    echo "</td>";
                    //New testing code ends here
                } else {
                    //echo "<td class='status'>$veh_status </td>";
                    //New testing code starts here
                    echo "<td class='status'>" . $veh_status;
                    /* Dropdown starts here */
                    if ($vehicle->vehicle_status_color_code != 0 || $vehicle->vehicle_status_color_code != '') {
                        echo "<div style='float: left;
                                    width: 10px;
                                    height: 10px;
                                    /*margin: 10px;*/
                                    margin-top: 27px;
                                    border: 1px solid rgba(0, 0, 0, .2);
                                    background: " . $vehicle->vehicle_status_color_code . ";'></div>";
                    } else {
                        echo "<div style='float: left;
                               width: 10px;
                               height: 10px;
                               /*margin: 10px;*/
                               margin-top: 27px;
                               border: 1px solid rgba(0, 0, 0, .2);
                               background: #008000;'></div>";
                    }
                    echo '<div class="styled-select" data-position="left" style="margin-left: 20px;padding-right: 18px;">';
                    //echo"<select id='grouplistForVehicleCommonStatus' name='grouplistForVehicleCommonStatus' onchange='updateVehicleCommonStatus(".$vehicle->vehicleid.")' class='input-mini'>";
                    echo "<select id='" . $vehicle->vehicleid . "' name='grouplist' onchange='updateVehicleCommonStatus(" . $vehicle->vehicleid . ",this.value)' class='input-mini'>";
                    foreach ($vehicledata['vehicleStatusData'] AS $statusData) {
                        if ($statusData->vehicleStatus == $vehicle->vehicle_status) {
                            echo "<option selected='selected' value=" . $statusData->vehicleStatusId . ">" . $statusData->vehicleStatus . "</option>";
                        } else {
                            echo "<option value=" . $statusData->vehicleStatusId . ">" . $statusData->vehicleStatus . "</option>";
                        }
                    }
                    //echo"<option value='add' style='color:black;'>Create new status...</option>";
                    echo "</select>";
                    echo "</div>";
                    /* Dropdown ends here */
                    echo "</td>";
                    //New testing code ends here
                }
            }
        }
        if ($_SESSION["Session_UserRole"] == 'elixir' || $_SESSION["Session_UserRole"] == 'Administrator' || $_SESSION["Session_UserRole"] == 'Master') {
            echo "<td class='edit_td tooltip-right' id='" . $vehicle->vehicleid . "' title='Double Click To Edit' style='cursor: pointer;'>
                    <span id='vehicleno_" . $vehicle->vehicleid . "' class='text'>" . $vehicle->vehicleno . "  <span class='glyphicon glyphicon-list-alt'>  <a class='notesPopup'  customerno='" . $_SESSION["customerno"] . "' href='#' vehicleId='" . $vehicle->vehicleid . "'> Notes </a> </span>
                    </span>";
            if ($_SESSION["use_vehicle_type"] == 1 && $vehicle->vehicleType != '') {
                echo "<br><span id='vehicleno_" . $vehicle->vehicleid . "' class='text'>(" . $vehicle->vehicleType . ")</span>";
            }
            echo "<input type='text' class='editbox' id='vehicleno_input_" . $vehicle->vehicleid . "' value='" . $vehicle->vehicleno . "' style='display:none;'/>
                        <input type='hidden' id='vehicle" . $vehicle->vehicleid . "' value='" . $vehicle->vehicleno . "'/>
                        <input type='hidden' id='vehicleimage" . $vehicle->vehicleid . "' class='gimg' value='" . $image . "'/>
                        </td>";
            $unit_no = '"' . $vehicle->unitno . '"';
            echo "<td><div style='display:inline-flex'><div class='tooltip-right' id='" . $vehicle->vehicleid . "' title='Double Click To Edit' style='cursor: pointer;' onclick='add_driver($vehicle->vehicleid, $unit_no , $vehicle->driverid);'><span id='drivername_" . $vehicle->vehicleid . "' class='text'>" . $vehicle->drivername . "</span>";
            echo "<input type='text' class='editbox' id='drivername_input_" . $vehicle->vehicleid . "' value='" . $vehicle->drivername . "'  style='display:none;' /><br/>";
            echo "<span id='driverno_" . $vehicle->vehicleid . "' class='text'>(" . $vehicle->driverphone . ")</span></div><div title='send message to " . $driverLabel . "' style='cursor:pointer;'><img style='width:25px;height:25px;' alt=' ' src='../../images/sms_to.png' onclick='messageToDriver($vehicle->vehicleid,\" $vehicle->drivername\",\" $vehicle->driverphone\",\"$driverLabel\");'></img></div></div>";
            echo "<input type='text' class='editbox' id='driverno_input_" . $vehicle->vehicleid . "' value='" . $vehicle->driverphone . "' style='display:none;'/></td>";
        } else {
            echo "<td>" . $vehicle->vehicleno . "<input type='hidden' id='vehicle" . $vehicle->vehicleid . "' value='" . $vehicle->vehicleno . "'/><input type='hidden' id='vehicleimage" . $vehicle->vehicleid . "' value='" . $image . "'/></td>";
            echo "<td>" . $vehicle->drivername . "<br/>(" . $vehicle->driverphone . ")<br/><img title='send message to " . $driverLabel . "' style='width:25px;height:25px;cursor:pointer;' alt=' ' src='../../images/sms_to.png' onclick='messageToDriver($vehicle->vehicleid,\" $vehicle->drivername\",\" $vehicle->driverphone\");'></img>";
            echo "<input type='text' class='editbox' id='driverno_input_" . $vehicle->vehicleid . "' value='" . $vehicle->driverphone . "' style='display:none;' /></td>";
        }
        if ($_SESSION['Session_UserRole'] == 'elixir') {
            echo "<td>$vehicle->unitno</td>";
        }
        $lat_long = "<br/>" . " [" . $vehicle->devicelat . "," . $vehicle->devicelong . "] ";
        if ($_SESSION["Session_UserRole"] == 'elixir') {
            echo '<td class="loccl tooltip-right"  style="cursor: pointer;">' . wordwrap($location, Location_Wrap, "<br/>\n") . $lat_long . '</td>';
        } else {
            echo '<td class="loccl tooltip-right"  style="cursor: pointer;"><a style="text-decoration:underline;" title="Click To Open" onclick="call_row(' . $vehicle->vehicleid . ');">' . wordwrap($location, Location_Wrap, "<br/>\n") . '</a>' . $lat_long . '</td>';
        }

        if (isset($vehicle->cname)) {
            $checkpnt_toggle = getduration_digitalio($vehicle->checkpoint_timestamp, $vehicle->lastupdated->format('Y-m-d H:i:s'));
            if ($vehicle->chkpoint_status == 0) {
                echo '<td class="chkpnt">IN-' . $vehicle->cname . ' since <br/>' . $checkpnt_toggle . '</td>';
            } elseif ($vehicle->chkpoint_status == 1) {
                echo '<td class="chkpnt">OUT-' . $vehicle->cname . ' since <br/>' . $checkpnt_toggle . '</td>';
            }
        } else {
            echo '<td>NA</td>';
        }
        if (isset($vehicle->routeDirection)) {
            if ($vehicle->routeDirection) {
                $cronManagerObj = new CronManager();
                $arrCheckpoints = $cronManagerObj->checkVehicleExistsInRoute($vehicle->checkpointId, $vehicle->customerno, $vehicle->vehicleid);
                //print_r($arrCheckpoints);
                if (isset($arrCheckpoints) && !empty($arrCheckpoints)) {
                    $arrRouteCheckpoints = array();
                    //echo $arrCheckpoints[0]->rmid;
                    $routeManagerObj = new RouteManager($vehicle->customerno);
                    //print_r($routeManagerObj);
                    $arrRouteCheckpoints = $routeManagerObj->getchksforroute($arrCheckpoints[0]->routeid);
                    //print_r($arrRouteCheckpoints);
                    if (!empty($arrRouteCheckpoints)) {
                        $firstChk = reset($arrRouteCheckpoints);
                        $lastChk = end($arrRouteCheckpoints);
                        $img = '';
                        if ($vehicle->routeDirection == 1) {
                            $img = ' <img src="../../images/right_trip.png"  style="cursor:pointer;height:25px;width:25px;"> ';
                        } elseif ($vehicle->routeDirection == 2) {
                            $img = ' <img src="../../images/left_trip.png"  style="cursor:pointer;height:25px;width:25px;"> ';
                        }
                        $string = $firstChk->cname . $img . $lastChk->cname;
                        echo '<td class="routeStatus">' . $firstChk->cname . $img . $lastChk->cname . '</td>';
                    } else {
                        echo '<td class="routeStatus">NA</td>';
                    }
                } else {
                    echo '<td class="routeStatus">NA</td>';
                }
            } else {
                echo '<td class="routeStatus">NA</td>';
            }
        } else {
            echo '<td class="routeStatus">NA</td>';
        }
        if (isset($_SESSION['role_modal']) && strtolower($_SESSION['role_modal']) != 'consignee') {
            if ($_SESSION['portable'] != '1') {
                if ($vehicle->curspeed > $vehicle->overspeed_limit) {
                    echo "<td class='speedcl off'>$vehicle->curspeed</td>";
                } else {
                    echo "<td class='speedcl'>$vehicle->curspeed</td>";
                }
                if ($_SESSION['userid'] != 391 && $_SESSION['userid'] != 392) {
                    echo "<td class = 'distcl'>" . $totaldistance . "</td>";
                }
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
                        if ($vehicle->digitalioupdated != '0000-00-00 00:00:00') {
                            $digitaldiff = getduration_digitalio($vehicle->digitalioupdated, $vehicle->lastupdated->format('Y-m-d H:i:s'));
                        }
                        if ($_SESSION['customerno'] == 528) {
                            $arrAcOn = explode(',', speedConstants::IS_AC_ON);
                            if (in_array($vehicle->digitalio, $arrAcOn)) {
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
                        } else {
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
                if ($_SESSION['use_door_sensor']) {
                    $digitaldiff = 'Not Active';
                    if ($_SESSION['customerno'] == 528) {
                        $arrDoor1 = explode(',', speedConstants::IS_DOOR_1);
                        $arrDoor2 = explode(',', speedConstants::IS_DOOR_2);
                        $door1String = '';
                        $door2String = '';
                        $doorString = '';
                        $door1diff = getduration_digitalio($vehicle->door_digitalioupdated, $vehicle->lastupdated->format('Y-m-d H:i:s'));
                        $door2diff = getduration_digitalio($vehicle->extra_digitalioupdated, $vehicle->lastupdated->format('Y-m-d H:i:s'));
                        if (in_array($vehicle->digitalio, $arrDoor1)) {
                            $door1String = speedConstants::CUST_528_DOOR_BIG . " Open<br/>since $door1diff";
                        } else {
                            $door1String = speedConstants::CUST_528_DOOR_BIG . " Closed<br/>since $door1diff";
                        }
                        if (in_array($vehicle->digitalio, $arrDoor2)) {
                            $door2String = speedConstants::CUST_528_DOOR_SMALL . " Open<br/>since $door2diff";
                        } else {
                            $door2String = speedConstants::CUST_528_DOOR_SMALL . " Closed<br/>since $door2diff";
                        }
                        $digitaldiff = $door1String . " <br/>" . $door2String;
                    } elseif ($vehicle->isDoorExt == 1) {
                        // echo $vehicle->door_digitalioupdated." and " .$vehicle->lastupdated->format('Y-m-d H:i:s');
                        if ($vehicle->door_digitalioupdated != '0000-00-00 00:00:00') {
                            $digitaldiff = getduration_digitalio($vehicle->door_digitalioupdated, $vehicle->lastupdated->format('Y-m-d H:i:s'));
                            if ($vehicle->door_digitalio == 1) {
                                $digitaldiff = "Open <br/>since $digitaldiff";
                            } else {
                                $digitaldiff = "Closed <br/>since $digitaldiff";
                            }
                        }
                    } elseif ($vehicle->door_digitalioupdated != '0000-00-00 00:00:00') {
                        $digitaldiff = getduration_digitalio($vehicle->door_digitalioupdated, $vehicle->lastupdated->format('Y-m-d H:i:s'));
                        $door_status = door_status($vehicle->is_door_opp, $vehicle->digitalio);
                        if (!$door_status) {
                            $digitaldiff = "Open <br/>since $digitaldiff";
                        } else {
                            $digitaldiff = "Closed <br/>since $digitaldiff";
                        }
                    }
                    /* Shortcut for image to redirect on door senor report starts here */
                    $doorSensorReportShortcut = "<br><a href='" . BASE_PATH . "/modules/reports/reports.php?id=39&STdate=" . date('d-m-Y') . "&EDdate=" . date('d-m-Y') . "&deviceid=" . $vehicle->deviceid . "&vehicleno=" . $vehicle->vehicleno . "' target='_blank'><img title='Door Sensor Report'  src='../../images/temp_report.png' width='15' height='15'></a>";
                    /* Shortcut for image to redirect on door senor report ends here */
                    echo "<td class='doorCl'>$digitaldiff $doorSensorReportShortcut</td>";
                }
                // Temperature Sensor
                if (isset($_SESSION['temp_sensors'])) {
                    getTemperature($vehicle);
                }
                // Genset 2 Sensor
                if ($_SESSION['use_extradigital'] == 1) {
                    if ($vehicle->acsensor == 1) {
                        if ($vehicle->extra_digitalioupdated != '0000-00-00 00:00:00') {
                            $digitaldiff = getduration_digitalio($vehicle->extra_digitalioupdated, $vehicle->lastupdated->format('Y-m-d H:i:s'));
                        }
                        if ($_SESSION['customerno'] == 528) {
                            $arrAcOn = explode(',', speedConstants::IS_AC_ON);
                            if (in_array($vehicle->extra_digital, $arrAcOn)) {
                                if ($vehicle->extra_digitalioupdated != '0000-00-00 00:00:00') {
                                    echo "<td class='genset2'>On <br/>since " . $digitaldiff . " </td>";
                                }
                                /***
                                 * changes Made By : Pratik Raut
                                 * Date : 17-10-2019
                                 * Change : Show Genset column 2 changes
                                 */
                                
                                elseif ($_SESSION['customerno'] == 59) {
                                    if (!empty($vehicle->extra_digital) && $vehicle->extra_digital == 2) {
                                        $analog1 = $vehicle->analog1;
                                        $digitaldiff = getduration_digitalio($vehicle->extra_digitalioupdated, $vehicle->lastupdated->format('Y-m-d H:i:s'));
                                        //$analog1/1000;

                                        if($analog1 > 600){
                                            $status = 'On';
                                        }else{
                                            $status = 'Off';
                                        }
                                        
                                        if ($vehicle->extra_digitalioupdated != '0000-00-00 00:00:00') {
                                            echo "<td class='accl'>".$status." <br/> since " . $digitaldiff . " </td>";
                                        } else {
                                            echo "<td class='accl'>  ".$status." </td>";
                                        }
                                    } else {
                                        if ($vehicle->extra_digitalioupdated != '0000-00-00 00:00:00') {
                                            echo "<td class='accl'>Off <br/> since " . $digitaldiff . " </td>";
                                        } else {
                                            echo "<td class='accl'> Off </td>";
                                        }
                                    }
                                }

                                 /***
                                  * Changes Ends Here
                                  */
                                else {
                                    echo "<td class='genset2'>On </td>";
                                }
                            } else {
                                if ($vehicle->extra_digitalioupdated != '0000-00-00 00:00:00') {
                                    echo "<td class='genset2'>Off <br/>since " . $digitaldiff . " </td>";
                                } else {
                                    echo "<td class='genset2'>Off </td>";
                                }
                            }
                        } else {
                            if ($vehicle->extra_digital == 0) {
                                if ($vehicle->isacopp == 0) {
                                    if ($vehicle->extra_digitalioupdated != '0000-00-00 00:00:00') {
                                        echo "<td class='genset2'>On <br/>since " . $digitaldiff . " </td>";
                                    } else {
                                        echo "<td class='genset2'>On </td>";
                                    }
                                } else {
                                    if ($vehicle->extra_digitalioupdated != '0000-00-00 00:00:00') {
                                        echo "<td class='genset2'>Off <br/>since " . $digitaldiff . " </td>";
                                    } else {
                                        echo "<td class='genset2'>Off </td>";
                                    }
                                }
                            } elseif ($vehicle->isacopp == 0) {
                                if ($vehicle->extra_digitalioupdated != '0000-00-00 00:00:00') {
                                    echo "<td class='genset2'>Off <br/>since " . $digitaldiff . " </td>";
                                } else {
                                    echo "<td class='genset2'>Off </td>";
                                }
                            } else {
                                if ($vehicle->extra_digitalioupdated != '0000-00-00 00:00:00') {
                                    echo "<td class='genset2'>On <br/>since " . $digitaldiff . " </td>";
                                } else {
                                    echo "<td class='genset2'>On </td>";
                                }
                            }
                        }
                    } else {
                        echo "<td class='genset2'>Not Active</td>";
                    }
                }
                /* Use For Double Genset */
                /* if ($_SESSION['use_extradigital'] == 1) {
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
                if (isset($vehicle->extra2_digitalioupdated) && $vehicle->extra2_digitalioupdated != '0000-00-00 00:00:00') {
                //$diffextra2 = "since<br/>" . getduration_digitalio($vehicle->extra2_digitalioupdated, $vehicle->lastupdated->format('Y-m-d H:i:s'));
                }
                if (isset($vehicle->extra_digital) && $vehicle->extra_digital == '' || $vehicle->extra_digital == '0') {
                echo '<td class="genset1"></td>';
                // echo '<td class="genset2"></td>';
                } else {
                if (isset($vehicle->extra_digital) && $vehicle->extra_digital >= 1) {
                echo '<td class="genset1">';
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
                echo "<td class='genset1'>Not Active</td>";
                }
                if ($vehicle->extra_digital == 2) {
                echo '<td class="genset2">';
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
                echo "<td class='genset2'>Not Active</td>";
                }
                }
                } */
                if (isset($_SESSION['use_humidity']) == 1) {
                    get_humidity($vehicle);
                }
            }
        } elseif (isset($_SESSION['ecodeid']) && !isset($_SESSION['role_modal'])) {
            // Temperature Sensor
            if (isset($_SESSION['temp_sensors'])) {
                getTemperature($vehicle);
            }
        } elseif (isset($_SESSION['role_modal']) && strtolower($_SESSION['role_modal']) == 'consignee') {
            if ($_SESSION['portable'] != '1') {
                if ($vehicle->curspeed > $vehicle->overspeed_limit) {
                    echo "<td class='speedcl off'>$vehicle->curspeed</td>";
                } else {
                    echo "<td class='speedcl'>$vehicle->curspeed</td>";
                }
                if ($_SESSION['userid'] != 391 && $_SESSION['userid'] != 392) {
                    echo "<td class = 'distcl'>" . $totaldistance . "</td>";
                }
            }
        }
        echo "<td onclick='view_vehicle_history(" . $vehicle->vehicleid . ");' title='Service Call History'><img src='../../images/service_call.png' style='width: 25px; height:25px'></img></td>";

        if ($_SESSION['Session_UserRole'] == 'elixir') {
            echo "<td class='helper' style='cursor: pointer;'></td>";
        } else {
            echo "<td class='helper' onclick='call_row(" . $vehicle->vehicleid . ");' title='Click Here For Detail View' style='cursor: pointer;'><img class='iv_all' id='iv_" . $vehicle->vehicleid . "' src='../../images/show.png' alt=' ' style='width: 16px; height:16px' /></td>";
        }
    }
    echo "</tr>";
    ?>
<?php
}

function getName($nid) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicledata = $vehiclemanager->getNameForTemp($nid);
    return $vehicledata;
}

function get_temperature($vehicle) {
    $tempconversion = new TempConversion();
    $tempconversion->unit_type = $vehicle->get_conversion;
    $tempconversion->use_humidity = $_SESSION['use_humidity'];
    $tdclass_temp1 = "";
    $tdclass_temp2 = "";
    $tdclass_temp3 = "";
    $tdclass_temp4 = "";
    $degree = "<sup>0</sup>C";
    $warning = "<img alt='Error' src='../../images/warning.png' title='Unable To Fetch Temperature' />";
    $mute = "<img alt='mute' src='../../images/mute.png' title='Mute Temperature' width='15px' height='15px' />";
    $unmute = "<img alt='unmute' src='../../images/unmute.png' title='Unmute Temperature' width='15px' height='15px' />";
    $temprprt1 = "<img title='Temperature Report' onclick='tempreport(" . $vehicle->vehicleid . ",1," . $vehicle->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
    $temprprt2 = "<img title='Temperature Report' onclick='tempreport(" . $vehicle->vehicleid . ",2," . $vehicle->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
    $temprprt3 = "<img title='Temperature Report' onclick='tempreport(" . $vehicle->vehicleid . ",3," . $vehicle->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
    $temprprt4 = "<img title='Temperature Report' onclick='tempreport(" . $vehicle->vehicleid . ",4," . $vehicle->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
    $temp1 = $temp2 = $temp3 = $temp4 = "Not Active";
    $spantext1 = $spantext2 = $spantext3 = $spantext4 = '';
    if ($_SESSION['temp_sensors'] == 1) {
        $spantext1 = "<span onclick='muteVehicle($vehicle->vehicleid,1)'>$mute</span>";
        if ($vehicle->temp1_mute == 1) {
            $spantext1 = "<span onclick='unmuteVehicle($vehicle->vehicleid,1)'>$unmute</span>";
        }
        $vehicle->temp1_min = isset($vehicle->temp1_min) ? $vehicle->temp1_min : 0;
        $vehicle->temp1_max = isset($vehicle->temp1_max) ? $vehicle->temp1_max : 0;
        $s = "analog" . $vehicle->tempsen1;
        if ($vehicle->tempsen1 != 0 && $vehicle->$s != 0) {
            $tempconversion->rawtemp = $vehicle->$s;
            $temp1 = getTempUtil($tempconversion) . " $degree";
            $temp = getTempUtil($tempconversion);
            if ($temp != '' && ($temp < $vehicle->temp1_min || $temp > $vehicle->temp1_max)) {
                if ($vehicle->temp1_min != 0 && $vehicle->temp1_max != 0) {
                    $tdclass_temp1 = " off";
                }
            }
            if ($temp1 == '0 ' . $degree) {
                $temp1 = $warning;
            }
        }
        echo "<td class='tempcl" . $tdclass_temp1 . "'>$temp1 <br/>$spantext1&nbsp;$temprprt1</td>";
    } elseif ($_SESSION['temp_sensors'] == 2) {
        $spantext1 = "<span onclick='muteVehicle($vehicle->vehicleid,1)'>$mute</span>";
        if ($vehicle->temp1_mute == '1') {
            $spantext1 = "<span onclick='unmuteVehicle($vehicle->vehicleid,1)'>$unmute</span>";
        }
        $spantext2 = "<span onclick='muteVehicle($vehicle->vehicleid,2)'>$mute</span>";
        if ($vehicle->temp2_mute == '1') {
            $spantext2 = "<span onclick='unmuteVehicle($vehicle->vehicleid,2)'>$unmute</span>";
        }
        $vehicle->temp1_min = isset($vehicle->temp1_min) ? $vehicle->temp1_min : 0;
        $vehicle->temp1_max = isset($vehicle->temp1_max) ? $vehicle->temp1_max : 0;
        $vehicle->temp2_min = isset($vehicle->temp2_min) ? $vehicle->temp2_min : 0;
        $vehicle->temp2_max = isset($vehicle->temp2_max) ? $vehicle->temp2_max : 0;
        $s = "analog" . $vehicle->tempsen1;
        if ($vehicle->tempsen1 != 0 && $vehicle->$s != 0) {
            $tempconversion->rawtemp = $vehicle->$s;
            $temp1 = getTempUtil($tempconversion) . " $degree";
            $temp = getTempUtil($tempconversion);
            if ($temp != '' && ($temp < $vehicle->temp1_min || $temp > $vehicle->temp1_max)) {
                if ($vehicle->temp1_min != 0 && $vehicle->temp1_max != 0) {
                    $tdclass_temp1 = " off";
                }
            }
            if ($temp1 == '0 ' . $degree) {
                $temp1 = $warning;
            }
        }
        $s = "analog" . $vehicle->tempsen2;
        if ($vehicle->tempsen2 != 0 && $vehicle->$s != 0) {
            $tempconversion->rawtemp = $vehicle->$s;
            $temp2 = getTempUtil($tempconversion) . " $degree";
            $temp = getTempUtil($tempconversion);
            if ($temp != '' && ($temp < $vehicle->temp2_min || $temp > $vehicle->temp2_max)) {
                if ($vehicle->temp2_min != 0 && $vehicle->temp2_max != 0) {
                    $tdclass_temp2 = " off";
                }
            }
            if ($temp2 == '0 ' . $degree) {
                $temp2 = $warning;
            }
        }
        echo "<td class='tempcl" . $tdclass_temp1 . "'>$temp1 <br/>$spantext1&nbsp;$temprprt1</td>";
        echo "<td class='tempc2" . $tdclass_temp2 . "'>$temp2 <br/>$spantext2&nbsp;$temprprt2</td>";
    } elseif ($_SESSION['temp_sensors'] == 3) {
        $spantext1 = "<span onclick='muteVehicle($vehicle->vehicleid,1)'>$mute</span>";
        if ($vehicle->temp1_mute == 1) {
            $spantext1 = "<span onclick='unmuteVehicle($vehicle->vehicleid,1)'>$unmute</span>";
        }
        $spantext2 = "<span onclick='muteVehicle($vehicle->vehicleid,2)'>$mute</span>";
        if ($vehicle->temp2_mute == 1) {
            $spantext2 = "<span onclick='unmuteVehicle($vehicle->vehicleid,2)'>$unmute</span>";
        }
        $spantext3 = "<span onclick='muteVehicle($vehicle->vehicleid,3)'>$mute</span>";
        if ($vehicle->temp3_mute == 1) {
            $spantext3 = "<span onclick='unmuteVehicle($vehicle->vehicleid,3)'>$unmute</span>";
        }
        $vehicle->temp1_min = isset($vehicle->temp1_min) ? $vehicle->temp1_min : 0;
        $vehicle->temp1_max = isset($vehicle->temp1_max) ? $vehicle->temp1_max : 0;
        $vehicle->temp2_min = isset($vehicle->temp2_min) ? $vehicle->temp2_min : 0;
        $vehicle->temp2_max = isset($vehicle->temp2_max) ? $vehicle->temp2_max : 0;
        $vehicle->temp3_min = isset($vehicle->temp3_min) ? $vehicle->temp3_min : 0;
        $vehicle->temp3_max = isset($vehicle->temp3_max) ? $vehicle->temp3_max : 0;
        $s = "analog" . $vehicle->tempsen1;
        if ($vehicle->tempsen1 != 0 && $vehicle->$s != 0) {
            $tempconversion->rawtemp = $vehicle->$s;
            $temp1 = getTempUtil($tempconversion) . " $degree";
            $temp = getTempUtil($tempconversion);
            if ($temp != '' && ($temp < $vehicle->temp1_min || $temp > $vehicle->temp1_max)) {
                if ($vehicle->temp1_min != 0 && $vehicle->temp1_max != 0) {
                    $tdclass_temp1 = " off";
                }
            }
            if ($temp1 == '0 ' . $degree) {
                $temp1 = $warning;
            }
        }
        $s = "analog" . $vehicle->tempsen2;
        if ($vehicle->tempsen2 != 0 && $vehicle->$s != 0) {
            $tempconversion->rawtemp = $vehicle->$s;
            $temp2 = getTempUtil($tempconversion) . " $degree";
            $temp = getTempUtil($tempconversion);
            if ($temp != '' && ($temp < $vehicle->temp2_min || $temp > $vehicle->temp2_max)) {
                if ($vehicle->temp2_min != 0 && $vehicle->temp2_max != 0) {
                    $tdclass_temp2 = " off";
                }
            }
            if ($temp2 == '0 ' . $degree) {
                $temp2 = $warning;
            }
        }
        $s = "analog" . $vehicle->tempsen3;
        if ($vehicle->tempsen3 != 0 && $vehicle->$s != 0) {
            $tempconversion->rawtemp = $vehicle->$s;
            $temp3 = getTempUtil($tempconversion) . " $degree";
            $temp = getTempUtil($tempconversion);
            if ($temp != '' && ($temp < $vehicle->temp3_min || $temp > $vehicle->temp3_max)) {
                if ($vehicle->temp3_min != 0 && $vehicle->temp3_max != 0) {
                    $tdclass_temp3 = " off";
                }
            }
            if ($temp3 == '0 ' . $degree) {
                $temp3 = $warning;
            }
        }
        echo "<td class='tempcl " . $tdclass_temp1 . "'>$temp1 <br/>$spantext1&nbsp;$temprprt1</td>";
        echo "<td class='tempc2 " . $tdclass_temp2 . "'>$temp2 <br/>$spantext2&nbsp;$temprprt2</td>";
        echo "<td class='tempc3 " . $tdclass_temp3 . "'>$temp3 <br/>$spantext3&nbsp;$temprprt3</td>";
    } elseif ($_SESSION['temp_sensors'] == 4) {
        $spantext1 = "<span onclick='muteVehicle($vehicle->vehicleid,1)'>$mute</span>";
        if ($vehicle->temp1_mute == 1) {
            $spantext1 = "<span onclick='unmuteVehicle($vehicle->vehicleid,1)'>$unmute</span>";
        }
        $spantext2 = "<span onclick='muteVehicle($vehicle->vehicleid,2)'>$mute</span>";
        if ($vehicle->temp2_mute == 1) {
            $spantext2 = "<span onclick='unmuteVehicle($vehicle->vehicleid,2)'>$unmute</span>";
        }
        $spantext3 = "<span onclick='muteVehicle($vehicle->vehicleid,3)'>$mute</span>";
        if ($vehicle->temp3_mute == 1) {
            $spantext3 = "<span onclick='unmuteVehicle($vehicle->vehicleid,3)'>$unmute</span>";
        }
        $spantext4 = "<span onclick='muteVehicle($vehicle->vehicleid,4)'>$mute</span>";
        if ($vehicle->temp4_mute == 1) {
            $spantext4 = "<span onclick='unmuteVehicle($vehicle->vehicleid,4)'>$unmute</span>";
        }
        $vehicle->temp1_min = isset($vehicle->temp1_min) ? $vehicle->temp1_min : 0;
        $vehicle->temp1_max = isset($vehicle->temp1_max) ? $vehicle->temp1_max : 0;
        $vehicle->temp2_min = isset($vehicle->temp2_min) ? $vehicle->temp2_min : 0;
        $vehicle->temp2_max = isset($vehicle->temp2_max) ? $vehicle->temp2_max : 0;
        $vehicle->temp3_min = isset($vehicle->temp3_min) ? $vehicle->temp3_min : 0;
        $vehicle->temp3_max = isset($vehicle->temp3_max) ? $vehicle->temp3_max : 0;
        $vehicle->temp4_min = isset($vehicle->temp4_min) ? $vehicle->temp4_min : 0;
        $vehicle->temp4_max = isset($vehicle->temp4_max) ? $vehicle->temp4_max : 0;
        $s = "analog" . $vehicle->tempsen1;
        if ($vehicle->tempsen1 != 0 && $vehicle->$s != 0) {
            $tempconversion->rawtemp = $vehicle->$s;
            $temp1 = getTempUtil($tempconversion) . " $degree";
            $temp = getTempUtil($tempconversion);
            if ($temp != '' && ($temp < $vehicle->temp1_min || $temp > $vehicle->temp1_max)) {
                if ($vehicle->temp1_min != 0 && $vehicle->temp1_max != 0) {
                    $tdclass_temp1 = " off";
                }
            }
            if ($temp1 == '0 ' . $degree) {
                $temp1 = $warning;
            }
        }
        $s = "analog" . $vehicle->tempsen2;
        if ($vehicle->tempsen2 != 0 && $vehicle->$s != 0) {
            $tempconversion->rawtemp = $vehicle->$s;
            $temp2 = getTempUtil($tempconversion) . " $degree";
            $temp = getTempUtil($tempconversion);
            if ($temp != '' && ($temp < $vehicle->temp2_min || $temp > $vehicle->temp2_max)) {
                if ($vehicle->temp2_min != 0 && $vehicle->temp2_max != 0) {
                    $tdclass_temp1 = " off";
                }
            }
            if ($temp2 == '0 ' . $degree) {
                $temp2 = $warning;
            }
        }
        $s = "analog" . $vehicle->tempsen3;
        if ($vehicle->tempsen3 != 0 && $vehicle->$s != 0) {
            $tempconversion->rawtemp = $vehicle->$s;
            $temp3 = getTempUtil($tempconversion) . " $degree";
            $temp = getTempUtil($tempconversion);
            if ($temp != '' && ($temp < $vehicle->temp3_min || $temp > $vehicle->temp3_max)) {
                if ($vehicle->temp3_min != 0 && $vehicle->temp3_max != 0) {
                    $tdclass_temp3 = " off";
                }
            }
            if ($temp3 == '0 ' . $degree) {
                $temp3 = $warning;
            }
        }
        $s = "analog" . $vehicle->tempsen4;
        if ($vehicle->tempsen4 != 0 && $vehicle->$s != 0) {
            $tempconversion->rawtemp = $vehicle->$s;
            $temp4 = getTempUtil($tempconversion) . " $degree";
            $temp = getTempUtil($tempconversion);
            if ($temp != '' && ($temp < $vehicle->temp4_min || $temp > $vehicle->temp4_max)) {
                if ($vehicle->temp4_min != 0 && $vehicle->temp4_max != 0) {
                    $tdclass_temp4 = " off";
                }
            }
            if ($temp4 == '0 ' . $degree) {
                $temp4 = $warning;
            }
        }
        echo "<td class='tempcl " . $tdclass_temp1 . "'>$temp1 <br/>$spantext1&nbsp;$temprprt1</td>";
        echo "<td class='tempc2 " . $tdclass_temp2 . "'>$temp2 <br/>$spantext2&nbsp;$temprprt2</td>";
        echo "<td class='tempc3 " . $tdclass_temp3 . "'>$temp3 <br/>$spantext3&nbsp;$temprprt3</td>";
        echo "<td class='tempc4 " . $tdclass_temp4 . "'>$temp4 <br/>$spantext4&nbsp;$temprprt4</td>";
    }
}

function getTemperature($vehicle) {
    $tempconversion = new TempConversion();
    $tempconversion->unit_type = $vehicle->get_conversion;
    $tempconversion->use_humidity = $_SESSION['use_humidity'];
    $tempconversion->switch_to = $_SESSION['switch_to'];
    $tdclass_temp1 = $tdclass_temp2 = $tdclass_temp3 = $tdclass_temp4 = "";
    $spantext1 = $spantext2 = $spantext3 = $spantext4 = '';
    $tempReport1 = $tempReport2 = $tempReport3 = $tempReport4 = '';
    $temp1 = $temp2 = $temp3 = $temp4 = speedConstants::TEMP_WARNING;
    $tempString = '';
    switch ($_SESSION['temp_sensors']) {
        case 4:
            if ($vehicle->tempsen4 != 0) {
                $spantext4 = "<br/><span onclick='muteVehicle($vehicle->vehicleid,4)'>" . speedConstants::TEMP_MUTE . "</span>";
                if ($vehicle->temp4_mute == 1) {
                    $spantext4 = "<br/><span onclick='unmuteVehicle($vehicle->vehicleid,4)'>" . speedConstants::TEMP_UNMUTE . "</span>";
                }
                $tempReport4 = " <img title='Temperature Report' onclick='tempreport(" . $vehicle->vehicleid . ",4," . $vehicle->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
                $vehicle->temp4_min = isset($vehicle->temp4_min) ? $vehicle->temp4_min : 0;
                $vehicle->temp4_max = isset($vehicle->temp4_max) ? $vehicle->temp4_max : 0;
                $s = "analog" . $vehicle->tempsen4;
                if ($vehicle->$s != 0) {
                    $tempconversion->rawtemp = $vehicle->$s;
                    $temp = getTempUtil($tempconversion);
                    if ($temp != '' && ($temp < $vehicle->temp4_min || $temp > $vehicle->temp4_max)) {
                        if ($vehicle->temp4_min != 0 && $vehicle->temp4_max != 0) {
                            $tdclass_temp4 = " off";
                        }
                    }
                    if ($temp != 0) {
                        $temp4 = $temp . speedConstants::TEMP_DEGREE;
                    }
                }
            } else {
                $temp4 = speedConstants::TEMP_NOTACTIVE;
            }
            $tempString = "<td class='tempc4 " . $tdclass_temp4 . "'>" . $temp4 . $spantext4 . $tempReport4 . "</td>";
        case 3:
            if ($vehicle->tempsen3 != 0) {
                $spantext3 = "<br/><span onclick='muteVehicle($vehicle->vehicleid,3)'>" . speedConstants::TEMP_MUTE . "</span>";
                if ($vehicle->temp3_mute == 1) {
                    $spantext3 = "<br/><span onclick='unmuteVehicle($vehicle->vehicleid,3)'>" . speedConstants::TEMP_UNMUTE . "</span>";
                }
                $tempReport3 = " <img title='Temperature Report' onclick='tempreport(" . $vehicle->vehicleid . ",3," . $vehicle->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
                $vehicle->temp3_min = isset($vehicle->temp3_min) ? $vehicle->temp3_min : 0;
                $vehicle->temp3_max = isset($vehicle->temp3_max) ? $vehicle->temp3_max : 0;
                $s = "analog" . $vehicle->tempsen3;
                if ($vehicle->$s != 0) {
                    $tempconversion->rawtemp = $vehicle->$s;
                    $temp = getTempUtil($tempconversion);
                    if ($temp != '' && ($temp < $vehicle->temp3_min || $temp > $vehicle->temp3_max)) {
                        if ($vehicle->temp3_min != 0 && $vehicle->temp3_max != 0) {
                            $tdclass_temp3 = " off";
                        }
                    }
                    if ($temp != 0) {
                        $temp3 = $temp . speedConstants::TEMP_DEGREE;
                    }
                }
            } else {
                $temp3 = speedConstants::TEMP_NOTACTIVE;
            }
            $tempString = "<td class='tempc3 " . $tdclass_temp3 . "'>" . $temp3 . $spantext3 . $tempReport3 . "</td> " . $tempString;
        case 2:
            if ($vehicle->tempsen2 != 0) {
                $spantext2 = "<br/><span onclick='muteVehicle($vehicle->vehicleid,2)'>" . speedConstants::TEMP_MUTE . "</span>";
                if ($vehicle->temp2_mute == 1) {
                    $spantext2 = "<br/><span onclick='unmuteVehicle($vehicle->vehicleid,2)'>" . speedConstants::TEMP_UNMUTE . "</span>";
                }
                $tempReport2 = " <img title='Temperature Report' onclick='tempreport(" . $vehicle->vehicleid . ",2," . $vehicle->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
                $vehicle->temp2_min = isset($vehicle->temp2_min) ? $vehicle->temp2_min : 0;
                $vehicle->temp2_max = isset($vehicle->temp2_max) ? $vehicle->temp2_max : 0;
                $s = "analog" . $vehicle->tempsen2;
                if ($vehicle->$s != 0) {
                    $tempconversion->rawtemp = $vehicle->$s;
                    $temp = getTempUtil($tempconversion);
                    if ($temp != '' && ($temp < $vehicle->temp2_min || $temp > $vehicle->temp2_max)) {
                        if ($vehicle->temp2_min != 0 && $vehicle->temp2_max != 0) {
                            $tdclass_temp1 = " off";
                        }
                    }
                    if ($temp != 0) {
                        $temp2 = $temp . speedConstants::TEMP_DEGREE;
                    }
                }
            } else {
                $temp2 = speedConstants::TEMP_NOTACTIVE;
            }
            $tempString = "<td class='tempc2 " . $tdclass_temp2 . "'>" . $temp2 . $spantext2 . $tempReport2 . "</td> " . $tempString;
        case 1:
            if ($vehicle->tempsen1 != 0) {
                $spantext1 = "<br/><span onclick='muteVehicle($vehicle->vehicleid,1)'>" . speedConstants::TEMP_MUTE . "</span>";
                if ($vehicle->temp1_mute == 1) {
                    $spantext1 = "<br/><span onclick='unmuteVehicle($vehicle->vehicleid,1)'>" . speedConstants::TEMP_UNMUTE . "</span>";
                }
                $tempReport1 = " <img title='Temperature Report' onclick='tempreport(" . $vehicle->vehicleid . ",1," . $vehicle->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
                $vehicle->temp1_min = isset($vehicle->temp1_min) ? $vehicle->temp1_min : 0;
                $vehicle->temp1_max = isset($vehicle->temp1_max) ? $vehicle->temp1_max : 0;
                $s = "analog" . $vehicle->tempsen1;
                if ($vehicle->$s != 0) {
                    $tempconversion->rawtemp = $vehicle->$s;
                    $temp = getTempUtil($tempconversion);
                    if ($temp != '' && ($temp < $vehicle->temp1_min || $temp > $vehicle->temp1_max)) {
                        if ($vehicle->temp1_min != 0 && $vehicle->temp1_max != 0) {
                            $tdclass_temp1 = " off";
                        }
                    }
                    if ($temp != 0) {
                        $temp1 = $temp . speedConstants::TEMP_DEGREE;
                    }
                }
            } else {
                $temp1 = speedConstants::TEMP_NOTACTIVE;
            }
            $tempString = "<td class='tempcl " . $tdclass_temp1 . "'>" . $temp1 . $spantext1 . $tempReport1 . "</td> " . $tempString;
    }
    echo $tempString;
}

function get_humidity($vehicle) {
    $tempconversion = new TempConversion();
    $tempconversion->unit_type = $vehicle->get_conversion;
    $tempconversion->use_humidity = $_SESSION['use_humidity'];
    $tempconversion->switch_to = $_SESSION['switch_to'];
    $degree = "%";
    $temp1 = '-';
    if ($_SESSION['use_humidity'] == 1) {
        $s = "analog" . $vehicle->humidity;
        if ($vehicle->humidity != 0 && $vehicle->$s != 0) {
            $tempconversion->rawtemp = $vehicle->$s;
            $temp1 = getTempUtil($tempconversion) . " $degree";
        }
        echo "<td class='humicl'>$temp1</td>";
    }
}

function getdate_IST() {
    $ServerDate_IST = strtotime(date("Y-m-d H:i:s"));
    return $ServerDate_IST;
}

function print_vehicledata_all($vehicledata) {
    $tempconversion = new TempConversion();
    $x = 1;
    foreach ($vehicledata as $vehicle) {
        $tempconversion->unit_type = $vehicle->get_conversion;
        $totaldistance = round(getdistance_all($vehicle->customerno, $vehicle->unitno, $vehicle->lastupdated), 2);
        //echo  $location = location($vehicle->devicelat, $vehicle->devicelong);
        $vehicle->lastupdated_store = $vehicle->lastupdated;
        $vehicle->lastupdated = row_highlight($vehicle->lastupdated);
        //        getimage($vehicle->lastupdated_store, $vehicle->stoppage_flag,$vehicle->ignition,$vehicle->type,$vehicle->curspeed,$vehicle->overspeed_limit,$vehicle->stoppage_transit_time,$vehicle->igstatus);
        echo "<td>" . $x . "</td>";
        echo "<td>";
        $vehicle->lastupdated = new DateTime($vehicle->lastupdated);
        getimage($vehicle);
        echo "</td>";
        echo "<td>" . $vehicle->lastupdated->format('Y-m-d H:i:s') . "</td>";
        echo "<td>" . $vehicle->vehicleno . "</td>";
        echo "<td>" . $vehicle->customerno . "</td>";
        echo "<td>" . $vehicle->unitno . "</td>";
        //if($location == "Temporarily Unavailable")
        // echo '<td>'.$location.'</td>';
        // else
        //  echo '<td></td>';
        if ($vehicle->curspeed > $vehicle->overspeed_limit) {
            echo "<td id=off>$vehicle->curspeed</td>";
        } else {
            echo "<td>$vehicle->curspeed</td>";
        }
        echo "<td>" . $totaldistance . "</td>";
        $vehicle->extbatt = round($vehicle->extbatt / 100, 2);
        echo "<td>$vehicle->extbatt</td>";
        // Internal Battery
        if ($vehicle->inbatt != "" && $vehicle->inbatt != NULL) {
            echo "<td>" . ($vehicle->inbatt / 1000) . "</td>";
        } else {
            echo '<td>0</td>';
        }
        // Network Strength
        signal_strength($vehicle->gsmstrength, $vehicle->vehicleid);
        echo "<td>$vehicle->phone</td>";
        // Tamper
        tamper($vehicle->tamper);
        // Powercut
        powercut($vehicle->powercut);
        // Fuel Sensor
        // echo "<td>Not Active</td>";
        // AC Sensor
        if ($vehicle->acsensor == 1) {
            if ($vehicle->digitalio == 0) {
                echo "<td>ON</td>";
            } else {
                echo "<td>OFF</td>";
            }
        } else {
            echo "<td>Not Active</td>";
        }
        // Temperature Sensor
        $temp = 'Not Active';
        $s = "analog" . $vehicle->tempsen1;
        if ($vehicle->tempsen1 != 0 && $vehicle->$s != 0) {
            $tempconversion->rawtemp = $vehicle->$s;
            $temp = getTempUtil($tempconversion);
        } else {
            $temp = '-';
        }
        $swv = isset($vehicle->swv) ? $vehicle->swv : '';
        echo "<td>$swv</td>";
        //if($temp!='-' && $temp != "Not Active")
        // echo "<td>$temp <sup>0</sup>C</td>";
        // else
        //  echo "<td>$temp</td>";
        echo "</tr>";
        $x++;
    }
    echo "</tbody>";
}

function print_vehicledata_all_inactive($vehicledata) {
    $x = 1;
    $tempconversion = new TempConversion();
    foreach ($vehicledata as $vehicle) {
        $tempconversion->unit_type = $vehicle->get_conversion;
        $tempconversion->use_humidity = $_SESSION['use_humidity'];
        $tempconversion->switch_to = $_SESSION['switch_to'];
        $totaldistance = round(getdistance_all($vehicle->customerno, $vehicle->unitno, $vehicle->lastupdated), 2);
        //echo  $location = location($vehicle->devicelat, $vehicle->devicelong);
        $vehicle->lastupdated_store = $vehicle->lastupdated;
        $vehicle->lastupdated = row_highlight($vehicle->lastupdated);
        //        getimage($vehicle->lastupdated_store, $vehicle->stoppage_flag,$vehicle->ignition,$vehicle->type,$vehicle->curspeed,$vehicle->overspeed_limit,$vehicle->stoppage_transit_time,$vehicle->igstatus);
        echo "<td>" . $x . "</td>";
        /*
        echo "<td>"; getimage($vehicle->lastupdated_store, $vehicle->stoppage_flag,$vehicle->ignition,$vehicle->type,$vehicle->curspeed,$vehicle->overspeed_limit,$vehicle->stoppage_transit_time,$vehicle->igstatus);echo "</td>";
         *
         */
        echo "<td>" . date('d-M-Y H:i', strtotime($vehicle->lastupdated)) . "</td>";
        echo "<td>" . $vehicle->vehicleno . "</td>";
        echo "<td>" . $vehicle->customerno . "</td>";
        echo "<td>" . $vehicle->unitno . "</td>";
        //if($location == "Temporarily Unavailable")
        // echo '<td>'.$location.'</td>';
        // else
        //  echo '<td></td>';
        echo "<td>$vehicle->phone</td>";
        signal_strength($vehicle->gsmstrength, $vehicle->vehicleid);
        // Tamper
        // tamper($vehicle->tamper);
        // Powercut
        powercut($vehicle->powercut);
        //Fuel Sensor
        // echo "<td>Not Active</td>";
        // AC Sensor
        //        if ($vehicle->acsensor == 1) {
        //            if ($vehicle->digitalio == 0) {
        //                echo "<td>ON</td>";
        //            }
        //            else {
        //                echo "<td>OFF</td>";
        //            }
        //        }
        //        else {
        //            echo "<td>Not Active</td>";
        //        }
        //
        /////////////////////CRM manager//////////////////////////
        $managername = rel_manager($vehicle->customerno);
        if (!empty($managername)) {
            echo "<td>" . $managername . "</td>";
        } else {
            echo "<td> - </td>";
        }
        //////////////////Issue Type/////////////////////
        if ($vehicle->issuetype == '1') {
            $issue = "Customer Issue";
        } elseif ($vehicle->issuetype == '2') {
            $issue = "Elixia Issue";
        } else {
            $issue = "-";
        }
        echo "<td>" . $issue . "</td>";
        // Temperature Sensor
        $temp = 'Not Active';
        $s = "analog" . $vehicle->tempsen1;
        if ($vehicle->tempsen1 != 0 && $vehicle->$s != 0) {
            $tempconversion->rawtemp = $vehicle->$s;
            $temp = getTempUtil($tempconversion);
        } else {
            $temp = '-';
        }
        if ($vehicle->remark != '0') {
            $vehicle->remark = getRemark($vehicle->remark, $vehicle->customerno);
        } else {
            $vehicle->remark = $vehicle->alterremark;
        }
        if ($vehicle->remark == '') {
            echo "<td><a href='addremark.php?vid=$vehicle->vehicleid&cno=$vehicle->customerno&uid=$vehicle->unitno' style='color:blue;'>Add Remark</a></td>";
        } else {
            echo "<td>$vehicle->remark <br/><a href='addremark.php?vid=$vehicle->vehicleid&cno=$vehicle->customerno&uid=$vehicle->unitno'style='color:blue;'>Change Remark</a></td>";
        }
        echo "</tr>";
        $x++;
    }
    echo "</tbody>";
}

function print_temp_wirecuts($vehicledata) {
    $x = 1;
    foreach ($vehicledata as $vehicle) {
        echo "<td>" . $x . "</td>";
        echo "<td>" . date('d-M-Y H:i', strtotime($vehicle->lastupdated)) . "</td>";
        echo "<td>" . $vehicle->vehicleno . "</td>";
        echo "<td>" . $vehicle->customerno . "</td>";
        echo "<td>" . $vehicle->unitno . "</td>";
        echo "<td>$vehicle->phone</td>";
        $managername = rel_manager($vehicle->customerno);
        if (!empty($managername)) {
            echo "<td>" . $managername . "</td>";
        } else {
            echo "<td> - </td>";
        }
        echo "<td>$vehicle->tempsel</td>";
        echo "</tr>";
        $x++;
    }
    echo "</tbody>";
}

function print_vehicledata_pending_inv($vehicledata) {
    $x = 1;
    foreach ($vehicledata as $vehicle) {
        echo "<tr style='background:#FFE0CC;'>";
        echo "<td>" . $x . "</td>";
        echo "<td>" . $vehicle->vehicleno . "</td>";
        echo "<td>" . $vehicle->customerno . "</td>";
        echo "<td>" . $vehicle->unitno . "</td>";
        echo "<td>" . $vehicle->install . "</td>";
        echo "<td>" . $vehicle->expiry . "</td>";
        echo "<td>$vehicle->phone</td>";
        echo "<td><a style='cursor:pointer; color:blue;' href='modify_renewals.php?uid=$vehicle->uid'>Renewals</a></td>";
        echo "</tr>";
        $x++;
    }
    echo "</tbody>";
}

function print_search_pending_inv($vehicledata) {
    $x = 1;
    foreach ($vehicledata as $vehicle) {
        echo "<tr style='background:#FFE0CC;'>";
        echo "<td>" . $x . "</td>";
        echo "<td>" . $vehicle->vehicleno . "</td>";
        echo "<td>" . $vehicle->customerno . "</td>";
        echo "<td>" . $vehicle->unitno . "</td>";
        echo "<td>" . $vehicle->install . "</td>";
        echo "<td>" . $vehicle->expiry . "</td>";
        echo "<td>$vehicle->phone</td>";
        echo "<td><a style='cursor:pointer; color:blue;' href='modify_pendinginvoice.php?uid=$vehicle->uid'>Invoice</a></td>";
        echo "</tr>";
        $x++;
    }
    echo "</tbody>";
}

function print_vehicledata_pending_inv1($vehicledata1) {
    $x = 1;
    foreach ($vehicledata1 as $vehicle1) {
        echo "<tr style='background:#FFE0CC;'>";
        echo "<td>" . $x . "</td>";
        echo "<td>" . $vehicle1->vehicleno . "</td>";
        echo "<td>" . $vehicle1->customerno . "</td>";
        echo "<td>" . $vehicle1->unitno . "</td>";
        echo "<td>" . $vehicle1->install . "</td>";
        echo "<td>" . $vehicle1->expiry . "</td>";
        echo "<td>$vehicle1->phone</td>";
        echo "<td><a style='cursor:pointer; color:blue;' href='modify_renewals.php?uid=$vehicle1->uid'>Renewals</a></td>";
        echo "</tr>";
        $x++;
    }
    echo "</tbody>";
}

function print_search_pending_renewal($vehicledata) {
    $x = 1;
    foreach ($vehicledata as $vehicle) {
        echo "<tr style='background:#FFE0CC;'>";
        echo "<td>" . $x . "</td>";
        echo "<td>" . $vehicle->vehicleno . "</td>";
        echo "<td>" . $vehicle->customerno . "</td>";
        echo "<td>" . $vehicle->unitno . "</td>";
        echo "<td>" . $vehicle->install . "</td>";
        echo "<td>" . $vehicle->expiry . "</td>";
        echo "<td>$vehicle->phone</td>";
        echo "<td><a style='cursor:pointer; color:blue;' href='modify_pendinginvoice.php?uid=$vehicle->uid'>Invoice</a></td>";
        echo "</tr>";
        $x++;
    }
    echo "</tbody>";
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

function display_simdata() {
    include 'pages/sim.php';
    $devicedata = getsim();
    if (isset($devicedata)) {
        print_simdata($devicedata);
    } else {
        echo "<tr><td colspan=7>No Data Avialable</td></tr>";
    }
    echo "</table>";
}

function print_simdata($devicedata) {
    foreach ($devicedata as $device) {
        $device->lastupdated = row_highlight($device->lastupdated);
        //echo "<td>" . $device->lastupdated->format('d-M-Y H:i') . "</td>";
        echo "<td>" . date('d-M-Y H:i', strtotime($device->lastupdated)) . "</td>";
        echo "<td>" . $device->vehicleno . "</td>";
        echo "<td>" . $device->unitno . "</td>";
        echo "<td>" . $device->phone . "</td>";
        echo "<td>" . (int) ($device->gsmstrength / 31 * 100) . "</td>";
        if ($_SESSION['Session_UserRole'] == 'elixir') {
            if ($device->gpsfixed == 'A') {
                echo "<td>Valid</td>";
            } elseif ($device->gpsfixed == 'V') {
                echo "<td id=off>Invalid</td>";
            }
            if ($device->gsmregister == 0) {
                echo "<td id=off>Not Registered</td>";
            } elseif ($device->gsmregister == 1) {
                echo "<td>Registered Home Network</td>";
            } elseif ($device->gsmregister == 2) {
                echo "<td id=off>Searching New Network</td>";
            } elseif ($device->gsmregister == 3) {
                echo "<td id=off>Registration Denied</td>";
            } elseif ($device->gsmregister == 4) {
                echo "<td id=off>Unknown</td>";
            } elseif ($device->gsmregister == 5) {
                echo "<td id=off>Roaming</td>";
            }
            if ($device->gprsregister == 1) {
                echo "<td>OK</td>";
            } elseif ($device->gprsregister == 0) {
                echo "<td id=off>Not OK</td>";
            } else {
                echo "<td>$device->gprsregister</td>";
            }
        }
        echo "</tr>";
    }
    echo "</tbody>";
}

function display_misc() {
    include 'pages/additional.php';
    $devicedata = getmisc();
    if (isset($devicedata)) {
        print_misc($devicedata);
    } else {
        echo "<tr>
                <td colspan=100%>No Data Avialable</td>
            </tr>";
    }
    echo "</table>";
}

function print_misc($devicedata) {
    foreach ($devicedata as $device) {
        $device->lastupdated = row_highlight($device->lastupdated);
        //echo "<td>" . $device->lastupdated->format('d-M-Y H:i') . "</td>";
        echo "<td>" . date('d-M-Y H:i', strtotime($device->lastupdated)) . "</td>";
        echo "<td>" . $device->vehicleno . "</td>";
        if ($_SESSION['Session_UserRole'] == 'elixir') {
            echo "<td>$device->unitno</td>";
        }
        echo "<td>";
        if ($_SESSION['Session_UserRole'] == 'elixir') {
            include '../common/elixirreasons.php';
        } else {
            include '../common/userreasons.php';
        }
        echo "</td>";
        if ($_SESSION['Session_UserRole'] == 'elixir') {
            if ($device->online_offline == '0') {
                echo "<td>Online";
            } else {
                echo "<td class='notok'>Offline</td>";
            }
        }
        if ($_SESSION['Session_UserRole'] == 'elixir') {
            echo "<td>$device->analog3</td>";
            echo "<td>$device->analog4</td>";
        }
        if ($_SESSION['Session_UserRole'] == 'elixir') {
            echo "<td>" . $device->setcom . "</td>";
            echo "<td>" . $device->command . "</td>";
        }
        echo "<td>" . $device->phone . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
}

function gettemp($rawtemp) {
    if ($_SESSION['use_humidity'] == 1 && $_SESSION['switch_to'] == 3) {
        $temp = round($rawtemp / 100);
    } else {
        $temp = round((($rawtemp - 1150) / 4.45), 1);
    }
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
                                <tr><?php
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
                    if ($tank[0]->fuelcapacity != 0) {
                        $fueltank = $tank[0]->fuelcapacity;
                    } else {
                        $fueltank = 0;
                    }
                    if ($fuel[0]->average != 0) {
                        $average = $fuel[0]->average;
                    } else {
                        $average = 0;
                    }
                    if ($fuel[0]->fuel_balance != 0) {
                        $fuelbalance = $fuel[0]->fuel_balance;
                    } else {
                        $fuelbalance = 0;
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
                        //echo "</br>"; echo "<a href='javascript:void(0);' onclick='add_fulecon($vehicledata->vehicleid,$average,$fueltank,$fuelbalance);'><input type='button' name='activate' id='activate' value='Activate' class='btn btn-primary'/></a>";
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
                <tr><?php
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
                        <?php
} else {
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
                    <?php }?>
<?php signal_strength($vehicledata->gsmstrength, $vehicledata->vehicleid);?>
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
                      <!--                            <a href="#checkpt_popup1" onClick="addedcheckpoint();" id="checkpoint_pop" class="add_checkpoint_<?php //echo $vehicledata->vehicleid;                                                                                             ?>"><img class="tooltip-top" title="Click To Add Checkpoint" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;"></a>-->
                            <?php
} elseif (isset($_SESSION['ecodeid'])) {
            ?>
                            <a href="#addcheckpoint" onClick="addedcheckpoint();" class="add_checkpoint_<?php echo $vehicledata->vehicleid; ?> " data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;">
                                <img class="tooltip-top" title="Click To Add Checkpoint" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;"></a>
                            <?php
}
        ?> </th>
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
                        <?php if ($_SESSION["role_modal"] == 'Administrator' || $_SESSION["role_modal"] == 'elixir') {
            ?>
                            <a href="#addfence" onClick="addedfence();" class="add_fencing_<?php echo $vehicledata->vehicleid; ?> " data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;"><img class="tooltip-top" title="Click To Add Fence" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;"></a>
                    <!--                        <a href="#fence_popup1" onClick="addedfence();" id="fence_pop" class="add_fencing_<?php //echo $vehicledata->vehicleid;                                                                                            ?>"><img class="tooltip-top" title="Click To Add Fence" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;"></a>-->
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
                    <?php if ($_SESSION["role_modal"] == 'Administrator' || $_SESSION["role_modal"] == 'elixir') {
            ?>
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
        <div class="popup"><?php
echo '<input type="hidden" id="fence_vehid" value="' . $vehicleid . '">';
        include 'modal_fence.php';
        ?>
            <a class="close1" href="#close"></a>
        </div>
        <a href="#x" class="overlay" id="checkpt_popup"></a>
        <div class="popup"><?php
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

function getlastupdatefordevicesreason() {
    $vm = new VehicleManager($_SESSION['customerno']);
    $allinactive = $vm->getlastupdatefordevicesreason();
    return $allinactive;
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
    //. '<td style="border : 1px solid #fff;">Email</td><td style="border : 1px solid #fff;" >Phone</td></tr>';
    $queues = $cqm->getalerthistleftdiv($vehicleid, $_SESSION['customerno']);
    if (isset($queues)) {
        foreach ($queues as $queue) {
            $data .= "<tr>"
                . "<td style='border : 1px solid #fff;'>$i</td><td style='border : 1px solid #fff;'>$queue->message</td><td style='border : 1px solid #fff;' >$queue->timeadded </td></tr>";
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