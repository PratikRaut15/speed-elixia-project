<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
include_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
include_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
include_once $RELATIVE_PATH_DOTS . 'lib/components/ajaxpage.inc.php';
include_once '../common/map_common_functions.php';
include_once $RELATIVE_PATH_DOTS . 'lib/comman_function/reports_func.php';
if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    @date_default_timezone_set('' . $_SESSION['timezone'] . '');
}
class jsonop {
    // Empty class!
}
function getvehicle($vehicleids) {
    $idarray = explode(",", $vehicleids);
    $IST = getIST();
    if (isset($idarray)) {
        $finaloutput = array();
        foreach ($idarray as $vehicleid) {
            if (isset($vehicleid) && $vehicleid != "") {
                $devicemanager = new DeviceManager($_SESSION['customerno']);
                $vehicleid = GetSafeValueString($vehicleid, "string");
                $device = $devicemanager->deviceformapping($vehicleid);
                if (isset($device)) {
                    $output = new jsonop();
                    $lastupdated2 = strtotime($device->lastupdated);
                    $output->cgeolat = $device->devicelat;
                    $output->cgeolong = $device->devicelong;
                    $output->cname = $device->vehicleno;
                    $output->cdrivername = $device->drivername;
                    $output->cdriverphone = $device->driverphone;
                    $output->cspeed = $device->curspeed;
                    $output->clastupdated = diff($IST, $lastupdated2);
                    $output->iimage = vehicleimage($device);
                    $output->cvehicleid = $device->vehicleid;
                    $output->image = vehicleimage($device);
                    $output->idleimage = vehicleimageidle($device);
                    $output->extbatt = round($device->extbatt / 100, 2);
                    $output->inbatt = $device->inbatt / 1000;
                    $network = round($device->gsmstrength / 31 * 100);
                    if ($network > 70) {
                        $output->gsmimg = '../../images/RTD/Network/best.png';
                        $output->network = "Excellent: " . $network . "%";
                    } elseif ($network > 30) {
                        $output->gsmimg = '../../images/RTD/Network/on.png';
                        $output->network = "Good: " . $network . "%";
                    } elseif ($network >= 0) {
                        $output->gsmimg = '../../images/RTD/Network/off.png';
                        $output->network = "Bad: " . $network . "%";
                    }
                    $finaloutput[] = $output;
                }
            }
        }
    }
    $ajaxpage = new ajaxpage();
    $ajaxpage->SetResult($finaloutput);
    $ajaxpage->Render();
}

function getvehicles($type = null) {
    $customerno = $_SESSION['customerno'];
    $finaloutput = Array();
    $IST = getIST();
    $temp_conversion = new TempConversion();
    $temp_conversion->use_humidity = $_SESSION['use_humidity'];
    @$temp_conversion->switch_to = $_SESSION['switch_to'];
    $devicemanager = new DeviceManager($customerno);
    if ($_REQUEST['all'] == 1) {
        $devices = $devicemanager->deviceformappings();
    } else {
        $devices = $devicemanager->deviceformappings($_REQUEST['all']);
    }
    $group_based_vehicle = vehicles_array(groupBased_vehicles_cron($customerno, $_SESSION['userid']));

    /* Fetch vehicle common status starts here */
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicleStatusData = $vehiclemanager->getVehicleStatusData();
    //echo"Data is:<pre>"; print_r($vehicleStatusData);
    /* Fetch vehicle common status ends here */

    if (isset($devices)) {
        $a = 0;
        $b = 0;
        $c = 0;
        $d = 0;
        $e = 0;
        $j = 1;
        foreach ($devices as $device) {
            $overspeed = '';
            $tdclass_temp1 = '';
            $tdclass_temp2 = '';
            $tdclass_temp3 = '';
            $tdclass_temp4 = '';
            $temp_conversion->unit_type = $device->get_conversion;
            if (!array_key_exists($device->vehicleid, $group_based_vehicle)) {
                continue;
            }
            $output = new jsonop();
            $output->a = $a;
            $output->b = $b;
            $output->c = $c;
            $output->d = $d;
            $output->e = $e;
            $output->tempconf1 = $tdclass_temp1;
            $output->tempconf2 = $tdclass_temp2;
            $output->tempconf3 = $tdclass_temp3;
            $output->tempconf4 = $tdclass_temp4;
            $output->overspeedconf = $overspeed;
            $output->use_extradigital = $_SESSION['use_extradigital'];
            $lastupdated2 = strtotime($device->lastupdated);
            $output->cgeolat = $device->devicelat;
            $output->cgeolong = $device->devicelong;
            $output->lat = $device->devicelat;
            $output->long = $device->devicelong;
            $deviceStatus = '';
            $deviceStatus = getstatus($device->stoppage_flag, $device->stoppage_transit_time, $device->lastupdated_store, $device->gpsfixed);
            $output->location = '';
            if ($deviceStatus != '' && $type == "export") {
                if ($customerno == 97 && ($j == 10 || $j == 20 || $j == 30)) {
                    $output->location = location($device->devicelat, $device->devicelong, $device->use_geolocation);
                    sleep(1);
                } else {
                    $location = location($device->devicelat, $device->devicelong, $device->use_geolocation);
                    $output->location = wordwrap($location, Location_Wrap, "<br>\n");
                }
            }

            if (isset($device->cname)) {
                $chkpnt_toggle = getduration_digitalio($device->checkpoint_timestamp, date('Y-m-d H:i:s', strtotime($device->lastupdated)));
                if ($device->chkpoint_status == 0) {
                    $output->chkpnt_status = "IN-" . $device->cname . " since " . $chkpnt_toggle;
                } elseif ($device->chkpoint_status == 1) {
                    $output->chkpnt_status = "OUT-" . $device->cname . " since " . $chkpnt_toggle;
                }
            } else {
                $output->chkpnt_status = "NA";
            }
            if ($device->routeDirection) {
                $cronManagerObj = new CronManager();
                $arrCheckpoints = $cronManagerObj->checkVehicleExistsInRoute($device->checkpointId, $customerno, $device->vehicleid);
                //print_r($arrCheckpoints);
                if (isset($arrCheckpoints) && !empty($arrCheckpoints)) {
                    $arrRouteCheckpoints = array();
                    //echo $arrCheckpoints[0]->rmid;
                    $routeManagerObj = new RouteManager($customerno);
                    //print_r($routeManagerObj);
                    $arrRouteCheckpoints = $routeManagerObj->getchksforroute($arrCheckpoints[0]->routeid);
                    //print_r($arrRouteCheckpoints);
                    if (!empty($arrRouteCheckpoints)) {
                        $firstChk = reset($arrRouteCheckpoints);
                        $lastChk = end($arrRouteCheckpoints);
                        $img = '';
                        if ($type == 'export') {
                            if ($device->routeDirection == 1) {
                                $img = ' towards ';
                            } elseif ($device->routeDirection == 2) {
                                $img = ' return ';
                            }
                        } else {
                            if ($device->routeDirection == 1) {
                                $img = ' <img src="../../images/right_trip.png"  style="cursor:pointer;height:25px;width:25px;"> ';
                            } elseif ($device->routeDirection == 2) {
                                $img = ' <img src="../../images/left_trip.png"  style="cursor:pointer;height:25px;width:25px;"> ';
                            }
                        }
                        $output->routeDirection = $firstChk->cname . $img . $lastChk->cname;
                    } else {
                        $output->routeDirection = "NA";
                    }
                } else {
                    $output->routeDirection = "NA";
                }
            } else {
                $output->routeDirection = "NA";
            }
            $j++;
            $output->cspeed = $device->curspeed;
            $output->lastupdated = diff($IST, $lastupdated2);
            $output->clastupdated = getduration($device->lastupdated);

            /* Binding vehicle common status data to dropdown list starts here */
            $vehicle_common_status = '';
            if ($device->vehicle_status_color_code != 0 || $device->vehicle_status_color_code != '') {
                $vehicle_common_status .= "<div style='float: left;
                width: 10px;
                height: 10px;
               /* margin: 5px;*/
                margin-top: 27px;
                border: 1px solid rgba(0, 0, 0, .2);
                background: " . $device->vehicle_status_color_code . ";'></div>";
            } else {
                $vehicle_common_status .= "<div style='float: left;
                width: 10px;
                height: 10px;
                /*margin: 10px;*/
                margin-top: 27px;
                border: 1px solid rgba(0, 0, 0, .2);
                background: #008000;'></div>";
            }

            $vehicle_common_status .= '<div class="styled-select" data-position="left" style="margin-left: 20px;padding-right: 18px;">';
            //$vehicle_common_status .= "<select id='grouplistForVehicleCommonStatus' name='grouplist' onchange='updateVehicleCommonStatus(".$device->vehicleid.")' class='input-mini'>";
            $vehicle_common_status .= "<select id='grouplistForVehicleCommonStatus_" . $device->vehicleid . "' name='grouplist' onchange='updateVehicleCommonStatus(" . $device->vehicleid . ",this.value)' class='input-mini'>";
            foreach ($vehicleStatusData AS $statusData) {
                if ($statusData->vehicleStatus == $device->vehicle_status) {
                    $vehicle_common_status .= "<option selected='selected' value=" . $statusData->vehicleStatusId . ">" . $statusData->vehicleStatus . "</option>";
                } else {
                    $vehicle_common_status .= "<option value=" . $statusData->vehicleStatusId . ">" . $statusData->vehicleStatus . "</option>";
                }
            }

            //$vehicle_common_status .= "<option value='add' style='color:black;'>Create new status...</option>";
            $vehicle_common_status .= "</select>";
            $vehicle_common_status .= "</div>";

            /* $vehicle_common_status .= '<div class="styled-select" data-position="left" style="margin-left: 20px;padding-right: 18px;">';
            $vehicle_common_status .= "<select id='grouplistForVehicleCommonStatus' name='grouplistForVehicleCommonStatus' class='input-mini'>";
            $vehicle_common_status .= "<option value='add' style='color:black;'>Create new status...</option>";
            $vehicle_common_status .="</select>";
            $vehicle_common_status .='</div>'; */
            /* Binding vehicle common status data to dropdown list ends here */

            if ($device->ignition_wirecut == 1) {
                $output->status = 'Ignition Wire Removed ' . $vehicle_common_status;
            } else {
                $output->status = getstatus($device->stoppage_flag, $device->stoppage_transit_time, $device->lastupdated_store, $device->gpsfixed) . ' ' . $vehicle_common_status;
            }
            $output->cvehicleid = $device->vehicleid;
            $output->idleimage = vehicleimageidle($device);
            $output->iimage = vehicleimage($device);
            $output->pc = 'on';
            $output->pctitle = "Normal";
            if ($device->powercut == 0) {
                $output->pc = '../../images/RTD/PowerCut/off.png';
                $output->pctitle = "Power Cut";
            }
            $output->totaldist = round(getdistance_new($device->vehicleid, $customerno), 2);
            if ($output->totaldist < 0) {
                //$output->totaldist = round(getdistance_new($device->vehicleid,$customerno),2);
                $output->totaldist = round($output->totaldist, 2);
            }
            // Loading
            $output->msgkey = "Normal";
            if ($device->msgkey == 1) {
                $output->msgkey = "Loading";
            } elseif ($device->msgkey == 2) {
                $output->msgkey = "Unloading";
            }
            // AC Sensor
            if ($_SESSION['use_ac_sensor'] == 1 || $_SESSION['use_genset_sensor'] == 1) {
                if ($device->acsensor == 1) {
                    if ($device->digitalioupdated != '0000-00-00 00:00:00') {
                        $digitaldiff = getduration_digitalio($device->digitalioupdated, date('Y-m-d H:i:s', strtotime($device->lastupdated)));
                    }
                    if ($_SESSION['customerno'] == 528) {
                        $arrAcOn = explode(',', speedConstants::IS_AC_ON);
                        if (in_array($device->digitalio, $arrAcOn)) {
                            if ($device->digitalioupdated != '0000-00-00 00:00:00') {
                                $output->acsensor = "On since " . $digitaldiff;
                            } else {
                                $output->acsensor = "On";
                            }
                        } else {
                            if ($device->digitalioupdated != '0000-00-00 00:00:00') {
                                $output->acsensor = "Off since " . $digitaldiff;
                            } else {
                                $output->acsensor = "Off";
                            }
                        }
                    } else {
                        if ($device->digitalio == 0) {
                            if ($device->isacopp == 0) {
                                if ($device->digitalioupdated != '0000-00-00 00:00:00') {
                                    $output->acsensor = "On since " . $digitaldiff;
                                } else {
                                    $output->acsensor = "On ";
                                }
                            } else {
                                if ($device->digitalioupdated != '0000-00-00 00:00:00') {
                                    $output->acsensor = "Off since " . $digitaldiff;
                                } else {
                                    $output->acsensor = "Off ";
                                }
                            }
                        } elseif ($device->isacopp == 0) {
                            if ($device->digitalioupdated != '0000-00-00 00:00:00') {
                                $output->acsensor = "Off since " . $digitaldiff;
                            } else {
                                $output->acsensor = "Off ";
                            }
                        } else {
                            if ($device->digitalioupdated != '0000-00-00 00:00:00') {
                                $output->acsensor = "On since " . $digitaldiff;
                            } else {
                                $output->acsensor = "On ";
                            }
                        }
                    }
                } else {
                    $output->acsensor = "Not Active";
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
                    $door1diff = getduration_digitalio($device->door_digitalioupdated, $device->lastupdated->format('Y-m-d H:i:s'));
                    $door2diff = getduration_digitalio($device->extra_digitalioupdated, $device->lastupdated->format('Y-m-d H:i:s'));
                    if (in_array($device->digitalio, $arrDoor1)) {
                        $door1String = speedConstants::CUST_528_DOOR_BIG . " Open<br/>since $door1diff";
                    } else {
                        $door1String = speedConstants::CUST_528_DOOR_BIG . " Closed<br/>since $door1diff";
                    }
                    if (in_array($device->digitalio, $arrDoor2)) {
                        $door2String = speedConstants::CUST_528_DOOR_SMALL . " Open<br/>since $door2diff";
                    } else {
                        $door2String = speedConstants::CUST_528_DOOR_SMALL . " Closed<br/>since $door2diff";
                    }
                    $digitaldiff = $door1String . " " . $door2String;
                } elseif ($device->isDoorExt == 1) {
                    if ($device->door_digitalioupdated != '0000-00-00 00:00:00') {
                        $digitaldiff = getduration_digitalio($device->door_digitalioupdated, date('Y-m-d H:i:s', strtotime($device->lastupdated)));
                        // $device->lastupdated->format('Y-m-d H:i:s')
                        if ($device->door_digitalio == 1) {
                            $digitaldiff = "Open <br/>since $digitaldiff";
                        } else {
                            $digitaldiff = "Closed <br/>since $digitaldiff";
                        }
                    }
                } elseif ($device->door_digitalioupdated != '0000-00-00 00:00:00') {
                    $digitaldiff = getduration_digitalio($device->door_digitalioupdated, date('Y-m-d H:i:s', strtotime($device->lastupdated)));
                    $door_status = door_status($device->is_door_opp, $device->digitalio);
                    if (!$door_status) {
                        $digitaldiff = "Open <br/>since $digitaldiff";
                    } else {
                        $digitaldiff = "Closed <br/>since $digitaldiff";
                    }
                }

                /* Shortcut for image to redirect on door senor report starts here */

                $doorSensorReportShortcut = "<br><a href='" . BASE_PATH . "/modules/reports/reports.php?id=39&STdate=" . date('d-m-Y') . "&EDdate=" . date('d-m-Y') . "&deviceid=" . $device->deviceid . "&vehicleno=" . $device->vehicleno . "' target='_blank'><img title='Door Sensor Report'  src='../../images/temp_report.png' width='15' height='15'></a>";

                /* Shortcut for image to redirect on door senor report ends here */

                $output->doorsensor = $digitaldiff . ' ' . $doorSensorReportShortcut;
            }
            // Temperature Sensor
            $output->temp_sensors = $_SESSION['temp_sensors'];
            $spantext1 = $spantext2 = $spantext3 = $spantext4 = '';
            $tempReport1 = $tempReport2 = $tempReport3 = $tempReport4 = '';
            $temp1 = $temp2 = $temp3 = $temp4 = speedConstants::TEMP_WARNING;
            switch ($_SESSION['temp_sensors']) {
                case 4:
                    if ($device->tempsen4 != 0) {
                        $spantext4 = " <br/><span onclick='muteVehicle($device->vehicleid,4)'>" . speedConstants::TEMP_MUTE . "</span>";
                        if ($device->temp4_mute == 1) {
                            $spantext4 = " <br/><span onclick='unmuteVehicle($device->vehicleid,4)'>" . speedConstants::TEMP_UNMUTE . "</span>";
                        }
                        $tempReport4 = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",4," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
                        $s = "analog" . $device->tempsen4;
                        if ($device->$s != 0) {
                            $temp_conversion->rawtemp = $device->$s;
                            $temp = getTempUtil($temp_conversion);
                            if ($temp != '' && ($device->temp4_min != 0 && $device->temp4_max != 0) && ($temp < $device->temp4_min || $temp > $device->temp4_max)) {
                                $tdclass_temp4 = "off";
                            }
                            if ($temp != 0) {
                                $temp4 = $temp . speedConstants::TEMP_DEGREE;
                            }
                        }
                        $output->tempconf4 = $tdclass_temp4;
                    } else {
                        $temp4 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $output->temp4 = $temp4 . $spantext4 . $tempReport4;
                    if ($temp4 != speedConstants::TEMP_NOTACTIVE) {
                        $output->temp4on = 1;
                    } else {
                        $output->temp4on = 0;
                    }
                case 3:
                    if ($device->tempsen3 != 0) {
                        $spantext3 = "<br/><span onclick='muteVehicle($device->vehicleid,3)'>" . speedConstants::TEMP_MUTE . "</span>";
                        if ($device->temp3_mute == 1) {
                            $spantext3 = "<br/><span onclick='unmuteVehicle($device->vehicleid,3)'>" . speedConstants::TEMP_UNMUTE . "</span>";
                        }
                        $tempReport3 = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",3," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
                        $s = "analog" . $device->tempsen3;
                        if ($device->$s != 0) {
                            $temp_conversion->rawtemp = $device->$s;
                            $temp = getTempUtil($temp_conversion);
                            if ($temp != '' && ($device->temp3_min != 0 && $device->temp3_max != 0) && ($temp < $device->temp3_min || $temp > $device->temp3_max)) {
                                $tdclass_temp3 = "off";
                            }
                            if ($temp != 0) {
                                $temp3 = $temp . speedConstants::TEMP_DEGREE;
                            }
                        }
                        $output->tempconf3 = $tdclass_temp3;
                    } else {
                        $temp3 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $output->temp3 = $temp3 . $spantext3 . $tempReport3;
                    if ($temp3 != speedConstants::TEMP_NOTACTIVE) {
                        $output->temp3on = 1;
                    } else {
                        $output->temp3on = 0;
                    }
                case 2:
                    if ($device->tempsen2 != 0) {
                        $spantext2 = "<br/><span onclick='muteVehicle($device->vehicleid,2)'>" . speedConstants::TEMP_MUTE . "</span>";
                        if ($device->temp2_mute == 1) {
                            $spantext2 = "<br/><span onclick='unmuteVehicle($device->vehicleid,2)'>" . speedConstants::TEMP_UNMUTE . "</span>";
                        }
                        $tempReport2 = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",2," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
                        $s = "analog" . $device->tempsen2;
                        if ($device->$s != 0) {
                            $temp_conversion->rawtemp = $device->$s;
                            $temp = getTempUtil($temp_conversion);
                            if ($temp != '' && ($device->temp2_min != 0 && $device->temp2_max != 0) && ($temp < $device->temp2_min || $temp > $device->temp2_max)) {
                                $tdclass_temp2 = "off";
                            }
                            if ($temp != 0) {
                                $temp2 = $temp . speedConstants::TEMP_DEGREE;
                            }
                        }
                        $output->tempconf2 = $tdclass_temp2;
                    } else {
                        $temp2 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $output->temp2 = $temp2 . $spantext2 . $tempReport2;
                    if ($temp2 != speedConstants::TEMP_NOTACTIVE) {
                        $output->temp2on = 1;
                    } else {
                        $output->temp2on = 0;
                    }
                case 1:
                    if ($device->tempsen1 != 0) {
                        $spantext1 = "<br/><span onclick='muteVehicle($device->vehicleid,1)'>" . speedConstants::TEMP_MUTE . "</span>";
                        if ($device->temp1_mute == 1) {
                            $spantext1 = "<br/><span onclick='unmuteVehicle($device->vehicleid,1)'>" . speedConstants::TEMP_UNMUTE . "</span>";
                        }
                        $tempReport1 = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",1," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
                        $s = "analog" . $device->tempsen1;
                        if ($device->$s != 0) {
                            $temp_conversion->rawtemp = $device->$s;
                            $temp = getTempUtil($temp_conversion);
                            if ($temp != '' && ($device->temp1_min != 0 && $device->temp1_max != 0) && ($temp < $device->temp1_min || $temp > $device->temp1_max)) {
                                $tdclass_temp1 = "off";
                            }
                            if ($temp != 0) {
                                $temp1 = $temp . speedConstants::TEMP_DEGREE;
                            }
                        }
                        $output->tempconf1 = $tdclass_temp1;
                    } else {
                        $temp1 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $output->temp1 = $temp1 . $spantext1 . $tempReport1;
                    if ($temp1 != speedConstants::TEMP_NOTACTIVE) {
                        $output->temp1on = 1;
                    } else {
                        $output->temp1on = 0;
                    }
            }
            if ($_SESSION['use_extradigital'] == 1) {
                $lastupdated = new DateTime($device->lastupdated_store);
                if ($device->acsensor == 1) {
                    if ($device->extra_digitalioupdated != '0000-00-00 00:00:00') {
                        $digitaldiff = getduration_digitalio($device->extra_digitalioupdated, $lastupdated->format('Y-m-d H:i:s'));
                    }
                // <editor-fold defaultstate="collapsed" desc="Old Code">
                /*
                $category_array = Array();
                $digital = Array();
                $digital2 = Array();
                $category = (int) $device->digitalio;
                $binarycategory = sprintf("%08s", DecBin($category));
                for ($shifter = 1; $shifter <= 127; $shifter = $shifter << 1) {
                    $binaryshifter = sprintf("%08s", DecBin($shifter));
                    if ($category & $shifter) {
                        $category_array[] = $shifter;
                    }
                }
                $diffextra1 = '';
                $diffextra2 = '';
                if ($device->extra_digitalioupdated != '0000-00-00 00:00:00') {
                    $diffextra1 = "since " . getduration_digitalio($device->extra_digitalioupdated, Datetime::createFromFormat('Y-m-d H:i:s', $device->lastupdated)->format('Y-m-d H:i:s'));
                }
                if ($device->extra2_digitalioupdated != '0000-00-00 00:00:00') {
                    $diffextra2 = "since " . getduration_digitalio($device->extra2_digitalioupdated, Datetime::createFromFormat('Y-m-d H:i:s', $device->lastupdated)->format('Y-m-d H:i:s'));
                }
                if ($device->extra_digital == '' || $device->extra_digital == '0') {
                    $output->genset1 = '';
                    $output->genset2 = '';
                } else {
                    if ($device->extra_digital >= 1) {
                        $output->genset1 = '';
                        if (in_array(1, $category_array)) {
                            $digital[] = "On  $diffextra1";
                        } else {
                            $digital[] = "Off $diffextra1";
                        }
                        if ($device->genset1 != '' && $device->transmitter1 != '') {
                            if ($device->setcom == 1) {
                                $device->genset1 = 'Syncing';
                            }
                            $output->genset1 .= $device->genset1 . "|" . $device->transmitter1;
                        }
                        $output->genset1 .= implode(',', $digital);
                    } else {
                        $output->genset1 = 'Not Active';
                    }
                    if ($device->extra_digital == 2) {
                        $output->genset2 = '';
                        if (in_array(2, $category_array)) {
                            $digital2[] = "On $diffextra2";
                        } else {
                            $digital2[] = "Off $diffextra2";
                        }
                        if ($device->genset2 != '' && $device->transmitter2 != '') {
                            if ($device->setcom == 1) {
                                $device->genset2 = 'Syncing';
                            }
                            $output->genset2 .= $device->genset2 . "|" . $device->transmitter2;
                        }
                        $output->genset2 .= implode(',', $digital2);
                    } else {
                        $output->genset2 = 'Not Active';
                    }
                }
                */
                //</editor-fold>
                
                    $output->genset2 = '';
                    if ($_SESSION['customerno'] == 528) {
                        $arrAcOn = explode(',', speedConstants::IS_AC_ON);
                        if (in_array($device->extra_digital, $arrAcOn)) {
                            if ($device->extra_digitalioupdated != '0000-00-00 00:00:00') {
                                $output->genset2 = "since " . $digitaldiff ;
                            } else {
                                $output->genset2 = "On ";
                            }
                        } else {
                            if ($device->extra_digitalioupdated != '0000-00-00 00:00:00') {
                                $output->genset2 = "Off <br/>since " . $digitaldiff . " ";
                            } else {
                                $output->genset2 = "Off ";
                            }
                        }
                    } else {
                        if ($device->extra_digital == 0) {
                            if ($device->isacopp == 0) {
                                if ($device->extra_digitalioupdated != '0000-00-00 00:00:00') {
                                    $output->genset2 = "On <br/>since " . $digitaldiff . " ";
                                } else {
                                    $output->genset2 = "On ";
                                }
                            } else {
                                if ($device->extra_digitalioupdated != '0000-00-00 00:00:00') {
                                    $output->genset2 = "Off <br/>since " . $digitaldiff . " ";
                                } else {
                                    $output->genset2 = "Off ";
                                }
                            }
                        } elseif ($device->isacopp == 0) {
                            if ($device->extra_digitalioupdated != '0000-00-00 00:00:00') {
                                $output->genset2 = "Off <br/>since " . $digitaldiff . " ";
                            } else {
                                $output->genset2 = "Off ";
                            }
                        } else {
                            if ($device->extra_digitalioupdated != '0000-00-00 00:00:00') {
                                $output->genset2 = "On <br/>since " . $digitaldiff . " ";
                            } else {
                                $output->genset2 = "On ";
                            }
                        }
                    } 
                } else {
                    $output->genset2 = "Not Active";
                }
            }
            /* Humidity */
            $output->use_humidity = $_SESSION['use_humidity'];
            $humidity = '-';
            if ($_SESSION['use_humidity'] == 1) {
                $s = "analog" . $device->humidity;
                if ($device->humidity != 0 && $device->$s != 0) {
                    $temp_conversion->rawtemp = $device->$s;
                    $humidity = getTempUtil($temp_conversion);
                }
                $output->humidity = $humidity;
                if ($humidity != '-') {
                    $output->humidityon = 1;
                } else {
                    $output->humidityon = 0;
                }
            }
            $ServerIST_less1 = new DateTime();
            $ServerIST_less1->modify('-60 minutes');
            $lastupdated = new DateTime($device->lastupdated_store);
            if ($lastupdated < $ServerIST_less1) {
                $e++;
                $output->e = $e;
            } else {
                if ($device->ignition == '0') {
                    $d++;
                    $output->d = $d;
                } else {
                    if ($device->curspeed > $device->overspeed_limit) {
                        $a++;
                        $output->a = $a;
                        $overspeed = 'off';
                    } else {
                        if ($device->stoppage_flag == '0') {
                            $c++;
                            $output->c = $c;
                        } else {
                            $b++;
                            $output->b = $b;
                        }
                    }
                    $output->overspeedconf = $overspeed;
                }
            }
            $finaloutput['result'][] = $output;
            //$finaloutput[] = $output;
        }
    }
    if ($type == 'export') {
        return $finaloutput;
    } else {
        $arrayObj = json_encode($finaloutput, true);
        echo $arrayObj; //exit();
        /*  $ajaxpage = new ajaxpage();
    $ajaxpage->SetResult($finaloutput);
    $ajaxpage->Render(); */
    }
}

function exprtVhclDtls($vehicleid) {
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $vehicledata = $devicemanager->vhclRmnDtls($vehicleid);
    return $vehicledata;
}

function getName($nid) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicledata = $vehiclemanager->getNameForTemp($nid);
    return $vehicledata;
}

function getIST() {
    $IST = strtotime(date("Y-m-d H:i:s"));
    return $IST;
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

function location($lat, $long, $usegeolocation) {
    $address = NULL;
    $GeoCoder_Obj = new GeoCoder($_SESSION['customerno']);
    $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
    return $address;
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

function updateVehicleCommonStatus($vehicleId, $vehicleStatusId) {
    //$customerno = $_SESSION['customerno'];
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    //$vehicledata = $vehiclemanager->getNameForTemp($nid);
    $vehicledata = $vehiclemanager->updateVehicleCommonStatus($vehicleId, $vehicleStatusId);
    //return $vehicledata;
}
