<?php

include_once '../../lib/system/utilities.php';
include_once '../../lib/components/ajaxpage.inc.php';
include_once '../../lib/autoload.php';
include_once '../common/map_common_functions.php';
include_once '../../lib/comman_function/reports_func.php';

if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
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
                    $output = new stdClass();
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

function getvehicles_wh($vehicleids = NULL, $export = NULL) {
    $customerno = $_SESSION['customerno'];
    $finaloutput1 = array();
    $devicemanager = new DeviceManager($customerno);
    $IST = getIST();
    $temp_conversion = new TempConversion();
    $temp_conversion->use_humidity = $_SESSION["use_humidity"];
    $temp_conversion->switch_to = $_SESSION["switch_to"];
    $devices2 = $devicemanager->deviceformappings_wh($vehicleids);
    $group_based_warehouse = vehicles_array(groupBased_warehosue_cron($customerno, $_SESSION['userid']));
    if (isset($devices2)) {
        $p = 0;
        $q = 0;
        $r = 0;
        $j = 1;
        foreach ($devices2 as $device) {
            $average = 0;
            if (!array_key_exists($device->vehicleid, $group_based_warehouse)) {
                continue;
            }
            $output = new stdClass();
            $output->p = $p;
            $output->q = $q;
            $output->r = $r;

            $lastupdated2 = strtotime($device->lastupdated);
            $output->cgeolat = $device->devicelat;
            $output->cgeolong = $device->devicelong;
            $output->customerno = $device->customerno;
            $output->location = location($device->devicelat, $device->devicelong, $device->use_geolocation);
            $output->cspeed = $device->curspeed;
            $output->lastupdated = diff($IST, $lastupdated2);
            $output->clastupdated = getduration($device->lastupdated);
            $output->status = getstatus($device->stoppage_flag, $device->stoppage_transit_time, $device->lastupdated_store);
            if ($output->status == '') {
                $output->status = 0;
            }
            $output->cvehicleid = $device->vehicleid;
            $output->idleimage = vehicleimageidle($device);
            $output->iimage = vehicleimage($device);
            $temp_conversion->unit_type = $device->get_conversion;
            $output->pc = 'on';
            $output->pctitle = "Normal";
            if ($device->powercut == 0) {
                $output->pc = '../../images/RTD/PowerCut/off.png';
                $output->pctitle = "Power Cut";
            }
            $output->totaldist = round(getdistance_new($device->vehicleid, $customerno), 2);
            if ($output->totaldist < 0) {
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
                    if ($_SESSION["customerno"] == speedConstants::CUSTNO_NXTDIGITAL) {
                        if ($device->digitalio == 0) {
                            $output->acsensor = "Moved";
                        } elseif ($device->digitalio == 1) {
                            $output->acsensor = "Stable";
                        }
                    } else {
                        if ($device->digitalioupdated != '0000-00-00 00:00:00') {
                            $digitaldiff = getduration_digitalio($device->digitalioupdated, date('Y-m-d H:i:s', strtotime($device->lastupdated)));
                        }
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
                if ($device->door_digitalioupdated != '0000-00-00 00:00:00') {
                    $digitaldiff = getduration_digitalio($device->door_digitalioupdated, date('Y-m-d H:i:s', strtotime($device->lastupdated)));
                    $door_status = door_status($device->is_door_opp, $device->digitalio);
                    if (!$door_status) {
                        $digitaldiff = "Open <br/>since $digitaldiff";
                    } else {
                        $digitaldiff = "Closed <br/>since $digitaldiff";
                    }
                }
                $output->doorsensor = $digitaldiff;
            }
            // Temperature Sensor
            $output->temp_sensors = $_SESSION['temp_sensors'];
            $output->temp1 = $output->temp2 = $output->temp3 = $output->temp4 = '';
            $temp1 = $temp2 = $temp3 = $temp4 = speedConstants::TEMP_WARNING;
            $spantext1 = $spantext2 = $spantext3 = $spantext4 = '';
            $tempReport1 = $tempReport2 = $tempReport3 = $tempReport4 = '';
            $tdclass_temp1 = $tdclass_temp2 = $tdclass_temp3 = $tdclass_temp4 = ' ';
            switch ($_SESSION['temp_sensors']) {
            case 4:
                if ($device->tempsen4 != 0) {
                    $spantext4 = " <span onclick='muteVehicle($device->vehicleid,4)'>" . speedConstants::TEMP_MUTE . "</span>";
                    if ($device->temp4_mute == 1) {
                        $spantext4 = "<span onclick='unmuteVehicle($device->vehicleid,4)'>" . speedConstants::TEMP_UNMUTE . "</span>";
                    }
                    $tempReport4 = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",4," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
                    $s = "analog" . $device->tempsen4;
                    if ($device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp = getTempUtil($temp_conversion);
                        if ($temp != 0) {
                            $tdclass_temp4 = " normalTemperature";
                            if (($device->temp4_min != $device->temp4_max) && ($temp < $device->temp4_min || $temp > $device->temp4_max)) {
                                $tdclass_temp4 = "off";
                            }
                            $temp4 = $temp . speedConstants::TEMP_DEGREE;
                                  if($device->customerno  == speedConstants::CUSTNO_CUREFIT){
                                $temp4_in_farnht = " (".round(($temp4 * 9/5) + 32, 2)."<sup>o</sup>F)";
                            }
                            else{
                                 $temp4_in_farnht = "";
                            }
                        } elseif ($temp == 0 || $temp > NORMAL_MAX_TEMP || $temp < NORMAL_MIN_TEMP) {
                            $temp4 = speedConstants::TEMP_WARNING;
                        }
                    }
                    $output->tempconf4 = $tdclass_temp4;
                } else {
                    $temp4 = speedConstants::TEMP_NOTACTIVE;
                }

                if ($temp4 != speedConstants::TEMP_NOTACTIVE) {
                    $output->temp4on = 1;
                } else {
                    $output->temp4on = 0;
                }
                $output->temp4 = "<div class='inline' style='width:60px;text-align:left'>" . $temp4 . "</div><div class='inline' style='width:15px;text-align:left' >" . $spantext4 . "</div><div class='inline' style='width:15px;text-align:right' >" . $tempReport4 . "</div>";
            case 3:
                if ($device->tempsen3 != 0) {
                    $spantext3 = " <span onclick='muteVehicle($device->vehicleid,3)'>" . speedConstants::TEMP_MUTE . "</span>";
                    if ($device->temp3_mute == 1) {
                        $spantext3 = "<span onclick='unmuteVehicle($device->vehicleid,3)'>" . speedConstants::TEMP_UNMUTE . "</span>";
                    }
                    $tempReport3 = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",3," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
                    $s = "analog" . $device->tempsen3;
                    if ($device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp = getTempUtil($temp_conversion);
                        if ($temp != 0) {
                            $tdclass_temp3 = " normalTemperature";
                            if ($device->temp3_min != $device->temp3_max && ($temp < $device->temp3_min || $temp > $device->temp3_max)) {
                                $tdclass_temp3 = "off";
                            }
                            $temp3 = $temp . speedConstants::TEMP_DEGREE;
                                  if($device->customerno  == speedConstants::CUSTNO_CUREFIT){
                                $temp3_in_farnht = " (".round(($temp3 * 9/5) + 32, 2)."<sup>o</sup>F)";
                            }
                            else{
                                 $temp3_in_farnht = "";
                            }
                        } elseif ($temp == 0 || $temp > NORMAL_MAX_TEMP || $temp < NORMAL_MIN_TEMP) {
                            $temp3 = speedConstants::TEMP_WARNING;
                        }
                    }
                    $output->tempconf3 = $tdclass_temp3;
                } else {
                    $temp3 = speedConstants::TEMP_NOTACTIVE;
                }

                if ($temp3 != speedConstants::TEMP_NOTACTIVE) {
                    $output->temp3on = 1;
                } else {
                    $output->temp3on = 0;
                }
                $output->temp3 = "<div class='inline' style='width:60px;text-align:left'>" . $temp3 . "</div><div class='inline' style='width:15px;text-align:left' >" . $spantext3 . "</div><div class='inline' style='width:15px;text-align:right' >" . $tempReport3 . "</div>";
            case 2:
                if ($device->tempsen2 != 0) {
                    $spantext2 = " <span onclick='muteVehicle($device->vehicleid,2)'>" . speedConstants::TEMP_MUTE . "</span>";
                    if ($device->temp2_mute == 1) {
                        $spantext2 = "<span onclick='unmuteVehicle($device->vehicleid,2)'>" . speedConstants::TEMP_UNMUTE . "</span>";
                    }
                    $tempReport2 = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",2," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
                    $s = "analog" . $device->tempsen2;
                    if ($device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp = getTempUtil($temp_conversion);
                        if ($temp != 0) {
                            $tdclass_temp2 = " normalTemperature";
                            if (($device->temp2_min != $device->temp2_max) && ($temp < $device->temp2_min || $temp > $device->temp2_max)) {
                                $tdclass_temp2 = "off";
                            }
                            $temp2 = $temp . speedConstants::TEMP_DEGREE;
                                   if($device->customerno  == speedConstants::CUSTNO_CUREFIT){
                                $temp2_in_farnht = " (".round(($temp2 * 9/5) + 32, 2)."<sup>o</sup>F)";
                            }
                            else{
                                 $temp2_in_farnht = "";
                            }
                        } elseif ($temp == 0 || $temp > NORMAL_MAX_TEMP || $temp2 < NORMAL_MIN_TEMP) {
                            $temp2 = speedConstants::TEMP_WARNING;
                        }
                    }
                    $output->tempconf2 = $tdclass_temp2;
                } else {
                    $temp2 = speedConstants::TEMP_NOTACTIVE;
                }

                if ($temp2 != speedConstants::TEMP_NOTACTIVE) {
                    $output->temp2on = 1;
                } else {
                    $output->temp2on = 0;
                }
                $output->temp2 = "<div class='inline' style='width:60px;text-align:left'>" . $temp2 . "</div><div class='inline' style='width:15px;text-align:left' >" . $spantext2 . "</div><div class='inline' style='width:15px;text-align:right' >" . $tempReport2 . "</div>";
            case 1:
                if ($device->tempsen1 != 0) {
                    $spantext1 = " <span onclick='muteVehicle($device->vehicleid,1)'>" . speedConstants::TEMP_MUTE . "</span>";
                    if ($device->temp1_mute == 1) {
                        $spantext1 = "<span onclick='unmuteVehicle($device->vehicleid,1)'>" . speedConstants::TEMP_UNMUTE . "</span>";
                    }
                    $tempReport1 = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",1," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
                    $s = "analog" . $device->tempsen1;
                    if ($device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp = getTempUtil($temp_conversion);
                        if ($temp != 0) {
                            $tdclass_temp1 = " normalTemperature";
                            if (($device->temp1_min != $device->temp1_max) && ($temp < $device->temp1_min || $temp > $device->temp1_max)) {
                                $tdclass_temp1 = "off";
                            }
                            $temp1 = $temp . speedConstants::TEMP_DEGREE;
                             if($device->customerno  == speedConstants::CUSTNO_CUREFIT){
                                $temp1_in_farnht = " (".round(($temp * 9/5) + 32, 2)."<sup>o</sup>F)";
                            }
                            else{
                                 $temp1_in_farnht = "";
                            }
                        } elseif ($temp == 0 || $temp > NORMAL_MAX_TEMP || $temp < NORMAL_MIN_TEMP) {
                            $temp1 = speedConstants::TEMP_WARNING;
                        }
                    }
                    $output->tempconf1 = $tdclass_temp1;
                } else {
                    $temp1 = speedConstants::TEMP_NOTACTIVE;
                }

                if ($temp1 != speedConstants::TEMP_NOTACTIVE) {
                    $output->temp1on = 1;
                } else {
                    $output->temp1on = 0;
                }
                $output->temp1 = "<div class='inline' style='width:60px;text-align:left'>" . $temp1 .$temp1_in_farnht. "</div><div class='inline' style='width:15px;text-align:left' >" . $spantext1 . "</div><div class='inline' style='width:15px;text-align:right' >" . $tempReport1 . "</div>";
                if ($device->customerno == speedConstants::CUSTNO_MDLZ) {
                    $count = 0;
                    $tempSum = 0;

                    $temp1 = str_replace(speedConstants::TEMP_DEGREE, '', $temp1);
                    $temp2 = str_replace(speedConstants::TEMP_DEGREE, '', $temp2);
                    $temp3 = str_replace(speedConstants::TEMP_DEGREE, '', $temp3);
                    $temp4 = str_replace(speedConstants::TEMP_DEGREE, '', $temp4);

                    if ($temp1 != speedConstants::TEMP_NOTACTIVE && $temp1 != speedConstants::TEMP_WARNING) {
                        $count++;
                        $tempSum += $temp1;
                    }
                    if ($temp2 != speedConstants::TEMP_NOTACTIVE && $temp2 != speedConstants::TEMP_WARNING) {
                        $count++;
                        $tempSum += $temp2;
                    }
                    if ($temp3 != speedConstants::TEMP_NOTACTIVE && $temp3 != speedConstants::TEMP_WARNING) {
                        $count++;
                        $tempSum += $temp3;
                    }
                    if ($temp4 != speedConstants::TEMP_NOTACTIVE && $temp4 != speedConstants::TEMP_WARNING) {
                        $count++;
                        $tempSum += $temp4;
                    }

                    if ($count > 0) {
                        $average = ($tempSum / $count);
                        $average = $average . speedConstants::TEMP_DEGREE;
                        $output->average = $average;
                    }
                }
            }
            /* Humidity */
            $output->use_humidity = $_SESSION['use_humidity'];
            $humidity = '-';
            if ($_SESSION['use_humidity'] == 1) {
                $s = "analog" . $device->humidity;
                $humrep = "<img title='Humidity & Temperature Report' onclick='humreport($device->vehicleid,$device->deviceid)' src='../../images/temp_report.png' width='20' height='20'>";
                if ($device->humidity != 0 && $device->$s != 0) {
                    $temp_conversion->rawtemp = $device->$s;
                    $humidity = getTempUtil($temp_conversion);
                }
                if ($humidity != '-') {
                    $output->humidity = $humidity . "% " . $humrep;
                    $output->humidityon = 1;
                } else {
                    $output->humidity = $humidity;
                    $output->humidityon = 0;
                }
            }
            $ServerIST_less1 = new DateTime();
            $ServerIST_less1->modify('-60 minutes');
            $lastupdated = new DateTime($device->lastupdated_store);
            if ($lastupdated < $ServerIST_less1) {
                $p++;
                $output->p = $p;
            } else {
                $temp1 = $temp2 = $temp3 = $temp4 = '';
                $conflictStatus = 0;
                switch ($_SESSION['temp_sensors']) {
                case 4:
                    if ($device->tempsen4 != 0) {
                        $s = "analog" . $device->tempsen4;
                        if ($device->$s != 0) {
                            $temp_conversion->rawtemp = $device->$s;
                            $temp4 = getTempUtil($temp_conversion);
                            if ($temp4 != 0 && ($temp4 < $device->temp4_min || $temp4 > $device->temp4_max) && ($device->temp4_min != $device->temp4_max)) {
                                $conflictStatus = 1;
                            }
                        }
                    }
                case 3:
                    if ($device->tempsen3 != 0) {
                        $s = "analog" . $device->tempsen3;
                        if ($device->$s != 0) {
                            $temp_conversion->rawtemp = $device->$s;
                            $temp3 = getTempUtil($temp_conversion);
                            if ($temp3 != 0 && ($temp3 < $device->temp3_min || $temp3 > $device->temp3_max) && ($device->temp3_min != $device->temp3_max)) {
                                $conflictStatus = 1;
                            }
                        }
                    }
                case 2:
                    if ($device->tempsen2 != 0) {
                        $s = "analog" . $device->tempsen2;
                        if ($device->$s != 0) {
                            $temp_conversion->rawtemp = $device->$s;
                            $temp2 = getTempUtil($temp_conversion);
                            if ($temp2 != 0 && ($temp2 < $device->temp2_min || $temp2 > $device->temp2_max) && ($device->temp2_min != $device->temp2_max)) {
                                $conflictStatus = 1;
                            }
                        }
                    }
                case 1:
                    if ($device->tempsen1 != 0) {
                        $s = "analog" . $device->tempsen1;
                        if ($device->$s != 0) {
                            $temp_conversion->rawtemp = $device->$s;
                            $temp1 = getTempUtil($temp_conversion);
                            if ($temp1 != 0 && ($temp1 < $device->temp1_min || $temp1 > $device->temp1_max) && ($device->temp1_min != $device->temp1_max)) {
                                $conflictStatus = 1;
                            }
                        }
                    }
                }
                if ($conflictStatus == 1) {
                    $q++;
                    $output->q = $q;
                } else {
                    $r++;
                    $output->r = $r;
                }
            }
            $finaloutput1[] = $output;
        }
    }
    if ($export == 'export') {
        return $finaloutput1;
    } else {
        $ajaxpage = new ajaxpage();
        $ajaxpage->SetResult($finaloutput1);
        $ajaxpage->Render();
    }
}

function exprtWarehouseDtls($vehicleid) {
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $vehicledata = $devicemanager->vhclRmnDtls($vehicleid);
    return $vehicledata;
}

function getvehicles_wh_fassos($vehicleids) {
    $customerno = $_SESSION['customerno'];
    $finaloutput1 = array();
    $devicemanager = new DeviceManager($customerno);
    $IST = getIST();
    $temp_conversion = new TempConversion();
    $temp_conversion->use_humidity = $_SESSION["use_humidity"];
    $temp_conversion->switch_to = $_SESSION["switch_to"];
    $devices2 = $devicemanager->deviceformappings_wh($vehicleids);
    $group_based_warehouse = vehicles_array(groupBased_warehosue_cron($customerno, $_SESSION['userid']));
    if (isset($devices2)) {
        $p = 0;
        $q = 0;
        $r = 0;
        $j = 1;
        foreach ($devices2 as $device) {
            if (!array_key_exists($device->vehicleid, $group_based_warehouse)) {
                continue;
            }
            $output = new stdClass();
            $output->p = $p;
            $output->q = $q;
            $output->r = $r;
            $output->t1 = (getName($device->n1) !== null) ? getName($device->n1) : '-';
            $output->t2 = (getName($device->n2) !== null) ? getName($device->n2) : '-';
            $output->t3 = (getName($device->n3) !== null) ? getName($device->n3) : '-';
            $output->t4 = (getName($device->n4) !== null) ? getName($device->n4) : '-';
            $lastupdated2 = strtotime($device->lastupdated);
            $output->cgeolat = $device->devicelat;
            $output->cgeolong = $device->devicelong;
            $output->customerno = $device->customerno;
            $output->location = location($device->devicelat, $device->devicelong, $device->use_geolocation);
            $output->cspeed = $device->curspeed;
            $output->lastupdated = diff($IST, $lastupdated2);
            $output->clastupdated = getduration($device->lastupdated);
            $output->status = getstatus($device->stoppage_flag, $device->stoppage_transit_time, $device->lastupdated_store);
            $temp_conversion->unit_type = $device->get_conversion;
            if ($output->status == '') {
                $output->status = 0;
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
                } else {
                    $output->acsensor = "Not Active";
                }
            }
            if ($_SESSION['use_door_sensor']) {
                $digitaldiff = 'Not Active';
                if ($device->door_digitalioupdated != '0000-00-00 00:00:00') {
                    $digitaldiff = getduration_digitalio($device->door_digitalioupdated, date('Y-m-d H:i:s', strtotime($device->lastupdated)));
                    $door_status = door_status($device->is_door_opp, $device->digitalio);
                    if (!$door_status) {
                        $digitaldiff = "Open <br/>since $digitaldiff";
                    } else {
                        $digitaldiff = "Closed <br/>since $digitaldiff";
                    }
                }
                $output->doorsensor = $digitaldiff;
            }
            // Temperature Sensor
            $output->temp_sensors = $_SESSION['temp_sensors'];
            $output->temp1 = $output->temp2 = $output->temp3 = $output->temp4 = '';
            $temp1 = $temp2 = $temp3 = $temp4 = speedConstants::TEMP_WARNING;
            $spantext1 = $spantext2 = $spantext3 = $spantext4 = '';
            $tempReport1 = $tempReport2 = $tempReport3 = $tempReport4 = '';
            switch ($_SESSION['temp_sensors']) {
            case 4:
                if ($device->tempsen4 != 0) {
                    $spantext4 = "<br/><span onclick='muteVehicle($device->vehicleid,4)'>" . speedConstants::TEMP_MUTE . "</span>";
                    if ($device->temp4_mute == '1') {
                        $spantext4 = "<br/><span onclick='unmuteVehicle($device->vehicleid,4)'>" . speedConstants::TEMP_UNMUTE . "</span>";
                    }
                    $tempReport4 = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",4," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
                    $s = "analog" . $device->tempsen4;
                    if ($device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp4 = getTempUtil($temp_conversion);

                        if ($temp4 == 0 || $temp4 > NORMAL_MAX_TEMP || $temp4 < NORMAL_MIN_TEMP) {
                            $temp4 = speedConstants::TEMP_WARNING;
                        } else {
                            $temp4 = $temp4 . speedConstants::TEMP_DEGREE;
                        }
                    }
                } else {
                    $temp4 = speedConstants::TEMP_NOTACTIVE;
                }
                $output->temp4 = $t4 . $temp4 . $spantext4 . $tempReport4;
            case 3:
                if ($device->tempsen3 != 0) {
                    $spantext3 = "<br/><span onclick='muteVehicle($device->vehicleid,3)'>" . speedConstants::TEMP_MUTE . "</span>";
                    if ($device->temp3_mute == '1') {
                        $spantext3 = "<br/><span onclick='unmuteVehicle($device->vehicleid,3)'>" . speedConstants::TEMP_UNMUTE . "</span>";
                    }
                    $tempReport3 = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",3," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
                    $s = "analog" . $device->tempsen3;
                    if ($device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp3 = getTempUtil($temp_conversion);
                        if ($temp3 == 0 || $temp3 > NORMAL_MAX_TEMP || $temp3 < NORMAL_MIN_TEMP) {
                            $temp3 = speedConstants::TEMP_WARNING;
                        } else {
                            $temp3 = $temp3 . speedConstants::TEMP_DEGREE;
                        }
                    }
                } else {
                    $temp3 = speedConstants::TEMP_NOTACTIVE;
                }
                $output->temp3 = $temp3 . $spantext3 . $tempReport3;
            case 2:
                if ($device->tempsen2 != 0) {
                    $spantext2 = "<br/><span onclick='muteVehicle($device->vehicleid,2)'>" . speedConstants::TEMP_MUTE . "</span>";
                    if ($device->temp2_mute == '1') {
                        $spantext2 = "<br/><span onclick='unmuteVehicle($device->vehicleid,2)'>" . speedConstants::TEMP_UNMUTE . "</span>";
                    }
                    $tempReport2 = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",2," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
                    $s = "analog" . $device->tempsen2;
                    if ($device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp2 = getTempUtil($temp_conversion);
                        if ($temp2 == 0 || $temp2 > NORMAL_MAX_TEMP || $temp2 < NORMAL_MIN_TEMP) {
                            $temp2 = speedConstants::TEMP_WARNING;
                        } else {
                            $temp2 = $temp2 . speedConstants::TEMP_DEGREE;
                        }
                    }
                } else {
                    $temp2 = speedConstants::TEMP_NOTACTIVE;
                }
                $output->temp2 = $temp2 . $spantext2 . $tempReport2;
            case 1:
                if ($device->tempsen1 != 0) {
                    $spantext1 = "<br/><span onclick='muteVehicle($device->vehicleid,1)'>" . speedConstants::TEMP_MUTE . "</span>";
                    if ($device->temp1_mute == '1') {
                        $spantext1 = "<br/><span onclick='unmuteVehicle($device->vehicleid,1)'>" . speedConstants::TEMP_UNMUTE . "</span>";
                    }
                    $tempReport1 = " <img title='Temperature Report' onclick='tempreport(" . $device->vehicleid . ",1," . $device->deviceid . ")' src='../../images/temp_report.png' width='15' height='15'>";
                    $s = "analog" . $device->tempsen1;
                    if ($device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp1 = getTempUtil($temp_conversion);
                        if ($temp1 == 0 || $temp1 > NORMAL_MAX_TEMP || $temp1 < NORMAL_MIN_TEMP) {
                            $temp1 = speedConstants::TEMP_WARNING;
                        } else {
                            $temp1 = $temp1 . speedConstants::TEMP_DEGREE;
                        }
                    }
                } else {
                    $temp1 = speedConstants::TEMP_NOTACTIVE;
                }
                $output->temp1 = $temp1 . $spantext1 . $tempReport1;
            }
            $ServerIST_less1 = new DateTime();
            $ServerIST_less1->modify('-60 minutes');
            $lastupdated = new DateTime($device->lastupdated_store);
            if ($lastupdated < $ServerIST_less1) {
                $p++;
                $output->p = $p;
            } else {
                $temp1 = $temp2 = $temp3 = $temp4 = '';
                $conflictStatus = 0;
                switch ($_SESSION['temp_sensors']) {
                case 4:
                    if ($device->tempsen4 != 0) {
                        $s = "analog" . $device->tempsen1;
                        if ($device->$s != 0) {
                            $temp_conversion->rawtemp = $device->$s;
                            $temp4 = getTempUtil($temp_conversion);
                            if ($temp4 != '' && ($temp4 < $device->temp4_min || $temp4 > $device->temp4_max)) {
                                $conflictStatus = 1;
                            }
                        }
                    }
                case 3:
                    if ($device->tempsen3 != 0) {
                        $s = "analog" . $device->tempsen3;
                        if ($device->$s != 0) {
                            $temp_conversion->rawtemp = $device->$s;
                            $temp3 = getTempUtil($temp_conversion);
                            if ($temp3 != '' && ($temp3 < $device->temp3_min || $temp3 > $device->temp3_max)) {
                                $conflictStatus = 1;
                            }
                        }
                    }
                case 2:
                    if ($device->tempsen2 != 0) {
                        $s = "analog" . $device->tempsen2;
                        if ($device->$s != 0) {
                            $temp_conversion->rawtemp = $device->$s;
                            $temp2 = getTempUtil($temp_conversion);
                            if ($temp2 != '' && ($temp2 < $device->temp2_min || $temp2 > $device->temp2_max)) {
                                $conflictStatus = 1;
                            }
                        }
                    }
                case 1:
                    if ($device->tempsen1 != 0) {
                        $s = "analog" . $device->tempsen1;
                        if ($device->$s != 0) {
                            $temp_conversion->rawtemp = $device->$s;
                            $temp1 = getTempUtil($temp_conversion);
                            if ($temp1 != '' && ($temp1 < $device->temp1_min || $temp1 > $device->temp1_max)) {
                                $conflictStatus = 1;
                            }
                        }
                    }
                }
                if ($conflictStatus == 1) {
                    $q++;
                    $output->q = $q;
                } else {
                    $r++;
                    $output->r = $r;
                }
            }
            $finaloutput1[] = $output;
        }
    }
    $ajaxpage = new ajaxpage();
    $ajaxpage->SetResult($finaloutput1);
    $ajaxpage->Render();
}

function getvehicles_wh_icelings($vehicleids) {
    $customerno = $_SESSION['customerno'];
    $finaloutput1 = array();
    $devicemanager = new DeviceManager($customerno);
    $IST = getIST();
    $temp_conversion = new TempConversion();
    $temp_conversion->use_humidity = $_SESSION["use_humidity"];
    $temp_conversion->switch_to = $_SESSION["switch_to"];
    $devices2 = $devicemanager->deviceformappings_wh($vehicleids);
    $group_based_warehouse = vehicles_array(groupBased_warehosue_cron($customerno, $_SESSION['userid']));
    if (isset($devices2)) {
        $p = 0;
        $q = 0;
        $r = 0;
        $j = 1;
        foreach ($devices2 as $device) {
            if (!array_key_exists($device->vehicleid, $group_based_warehouse)) {
                continue;
            }
            $output = new stdClass();
            $output->p = $p;
            $output->q = $q;
            $output->r = $r;
            $lastupdated2 = strtotime($device->lastupdated);
            $output->cgeolat = $device->devicelat;
            $output->cgeolong = $device->devicelong;
            $output->customerno = $device->customerno;
            $output->t1 = getName($device->n1);
            $output->t2 = getName($device->n2);
            $output->t3 = getName($device->n3);
            $output->t4 = getName($device->n4);
            $output->location = location($device->devicelat, $device->devicelong, $device->use_geolocation);
            $output->cspeed = $device->curspeed;
            $output->lastupdated = diff($IST, $lastupdated2);
            $output->clastupdated = getduration($device->lastupdated);
            $output->status = getstatus($device->stoppage_flag, $device->stoppage_transit_time, $device->lastupdated_store);
            $output->cvehicleid = $device->vehicleid;
            $output->idleimage = vehicleimageidle($device);
            $output->iimage = vehicleimage($device);
            $temp_conversion->unit_type = $device->get_conversion;

            if ($device->powercut == 0) {
                $output->pc = '../../images/RTD/PowerCut/off.png';
                $output->pctitle = "Power Cut";
            } else {
                $output->pc = 'on';
                $output->pctitle = "Normal";
            }
            $output->totaldist = round(getdistance_new($device->vehicleid, $customerno), 2);
            if ($output->totaldist < 0) {
                //$output->totaldist = round(getdistance_new($device->vehicleid,$customerno),2);
                $output->totaldist = round($output->totaldist, 2);
            }
            // Loading
            if ($device->msgkey == 1) {
                $output->msgkey = "Loading";
            } elseif ($device->msgkey == 2) {
                $output->msgkey = "Unloading";
            } else {
                $output->msgkey = "Normal";
            }
            // AC Sensor
            if ($_SESSION['use_ac_sensor'] == 1 || $_SESSION['use_genset_sensor'] == 1) {
                if ($device->acsensor == 1) {
                    if ($device->digitalioupdated != '0000-00-00 00:00:00') {
                        $digitaldiff = getduration_digitalio($device->digitalioupdated, date('Y-m-d H:i:s', strtotime($device->lastupdated)));
                    }
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
                } else {
                    $output->acsensor = "Not Active";
                }
            }
            if ($_SESSION['use_door_sensor']) {
                $digitaldiff = 'Not Active';
                if ($device->digitalioupdated != '0000-00-00 00:00:00') {
                    $digitaldiff = getduration_digitalio($device->digitalioupdated, date('Y-m-d H:i:s', strtotime($device->lastupdated)));
                    $door_status = door_status($device->is_door_opp, $device->digitalio);
                    if (!$door_status) {
                        $digitaldiff = "Open <br/>since $digitaldiff";
                    } else {
                        $digitaldiff = "Closed <br/>since $digitaldiff";
                    }
                }
                $output->doorsensor = $digitaldiff;
            }
            // Temperature Sensor
            $output->temp_sensors = $_SESSION['temp_sensors'];
            $temp1 = $temp2 = $temp3 = $temp4 = '-';
            if ($_SESSION['temp_sensors'] == 1) {
                $s = "analog" . $device->tempsen1;
                if ($device->tempsen1 != 0 && $device->$s != 0) {
                    $temp_conversion->rawtemp = $device->$s;
                    $temp1 = getTempUtil($temp_conversion);
                }
                $output->temp = $temp1;
                if ($temp1 != '-') {
                    $output->tempon = 1;
                } else {
                    $output->tempon = 0;
                }
            } elseif ($_SESSION['temp_sensors'] == 2) {
                $s = "analog" . $device->tempsen1;
                if ($device->tempsen1 != 0 && $device->$s != 0) {
                    $temp_conversion->rawtemp = $device->$s;
                    $temp1 = getTempUtil($temp_conversion);
                }
                $s = "analog" . $device->tempsen2;
                if ($device->tempsen2 != 0 && $device->$s != 0) {
                    $temp_conversion->rawtemp = $device->$s;
                    $temp2 = getTempUtil($temp_conversion);
                }
                $output->temp1 = $temp1;
                $output->temp2 = $temp2;
                if ($output->temp1 != '-') {
                    $output->temp1on = 1;
                } else {
                    $output->temp1on = 0;
                }
                if ($output->temp2 != '-') {
                    $output->temp2on = 1;
                } else {
                    $output->temp2on = 0;
                }
            } elseif ($_SESSION['temp_sensors'] == 3) {
                $s = "analog" . $device->tempsen1;
                if ($device->tempsen1 != 0 && $device->$s != 0) {
                    $temp_conversion->rawtemp = $device->$s;
                    $temp1 = getTempUtil($temp_conversion);
                }
                $s = "analog" . $device->tempsen2;
                if ($device->tempsen2 != 0 && $device->$s != 0) {
                    $temp_conversion->rawtemp = $device->$s;
                    $temp2 = getTempUtil($temp_conversion);
                }
                $s = "analog" . $device->tempsen3;
                if ($device->tempsen3 != 0 && $device->$s != 0) {
                    $temp_conversion->rawtemp = $device->$s;
                    $temp3 = getTempUtil($temp_conversion);
                }
                $output->temp1 = $temp1;
                $output->temp2 = $temp2;
                $output->temp3 = $temp3;
                if ($output->temp1 != '-') {
                    $output->temp1on = 1;
                } else {
                    $output->temp1on = 0;
                }
                if ($output->temp2 != '-') {
                    $output->temp2on = 1;
                } else {
                    $output->temp2on = 0;
                }
                if ($output->temp3 != '-') {
                    $output->temp3on = 1;
                } else {
                    $output->temp3on = 0;
                }
            } elseif ($_SESSION['temp_sensors'] == 4) {
                $s = "analog" . $device->tempsen1;
                if ($device->tempsen1 != 0 && $device->$s != 0) {
                    $temp_conversion->rawtemp = $device->$s;
                    $temp1 = getTempUtil($temp_conversion);
                }
                $s = "analog" . $device->tempsen2;
                if ($device->tempsen2 != 0 && $device->$s != 0) {
                    $temp2 = getpressure($device->$s);
                }
                $s = "analog" . $device->tempsen3;
                if ($device->tempsen3 != 0 && $device->$s != 0) {
                    $temp3 = getpressure($device->$s);
                }
                $s = "analog" . $device->tempsen4;
                if ($device->tempsen4 != 0 && $device->$s != 0) {
                    $temp4 = getpressure($device->$s);
                }
                $output->temp1 = $temp1;
                $output->temp2 = $temp2;
                $output->temp3 = $temp3;
                $output->temp4 = $temp4;
                if ($output->temp1 != '-') {
                    $output->temp1on = 1;
                } else {
                    $output->temp1on = 0;
                }
                if ($output->temp2 != '-') {
                    $output->temp2on = 1;
                } else {
                    $output->temp2on = 0;
                }
                if ($output->temp3 != '-') {
                    $output->temp3on = 1;
                } else {
                    $output->temp3on = 0;
                }
                if ($output->temp4 != '-') {
                    $output->temp4on = 1;
                } else {
                    $output->temp4on = 0;
                }
            }
            $ServerIST_less1 = new DateTime();
            $ServerIST_less1->modify('-60 minutes');
            $lastupdated = new DateTime($device->lastupdated_store);
            if ($lastupdated < $ServerIST_less1) {
                $p++;
                $output->p = $p;
            } else {
                $temp1 = $temp2 = '';
                if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 1) {
                    $s = "analog" . $device->tempsen1;
                    if ($device->tempsen1 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp1 = getTempUtil($temp_conversion);
                    }
                    if ($temp1 != '' && ($temp1 < $device->temp1_min || $temp1 > $device->temp1_max)) {
                        $q++;
                        $output->q = $q;
                    } else {
                        $r++;
                        $output->r = $r;
                    }
                } elseif (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 2) {
                    $s = "analog" . $device->tempsen1;
                    if ($device->tempsen1 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp1 = getTempUtil($temp_conversion);
                    }
                    $s = "analog" . $device->tempsen2;
                    if ($device->tempsen2 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp2 = getTempUtil($temp_conversion);
                    }
                    if ($temp1 != '' && ($temp1 < $device->temp1_min || $temp1 > $device->temp1_max)) {
                        $q++;
                        $output->q = $q;
                    } elseif ($temp2 != '' && ($temp2 < $device->temp2_min || $temp2 > $device->temp2_max)) {
                        $q++;
                        $output->q = $q;
                    } else {
                        $r++;
                        $output->r = $r;
                    }
                } elseif (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 3) {
                    $s = "analog" . $device->tempsen1;
                    if ($device->tempsen1 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp1 = getTempUtil($temp_conversion);
                    }
                    $s = "analog" . $device->tempsen2;
                    if ($device->tempsen2 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp2 = getTempUtil($temp_conversion);
                    }
                    $s = "analog" . $device->tempsen3;
                    if ($device->tempsen3 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp3 = getTempUtil($temp_conversion);
                    }
                    if ($temp1 != '' && ($temp1 < $device->temp1_min || $temp1 > $device->temp1_max)) {
                        $q++;
                        $output->q = $q;
                    } elseif ($temp2 != '' && ($temp2 < $device->temp2_min || $temp2 > $device->temp2_max)) {
                        $q++;
                        $output->q = $q;
                    } elseif ($temp3 != '' && ($temp3 < $device->temp3_min || $temp3 > $device->temp3_max)) {
                        $q++;
                        $output->q = $q;
                    } else {
                        $r++;
                        $output->r = $r;
                    }
                } elseif (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 4) {
                    $s = "analog" . $device->tempsen1;
                    if ($device->tempsen1 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp1 = getTempUtil($temp_conversion);
                    }
                    $s = "analog" . $device->tempsen2;
                    if ($device->tempsen2 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp2 = getTempUtil($temp_conversion);
                    }
                    $s = "analog" . $device->tempsen3;
                    if ($device->tempsen3 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp3 = getTempUtil($temp_conversion);
                    }
                    $s = "analog" . $device->tempsen4;
                    if ($device->tempsen4 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp4 = getTempUtil($temp_conversion);
                    }
                    if ($temp1 != '' && ($temp1 < $device->temp1_min || $temp1 > $device->temp1_max)) {
                        $q++;
                        $output->q = $q;
                    } elseif ($temp2 != '' && ($temp2 < $device->temp2_min || $temp2 > $device->temp2_max)) {
                        $q++;
                        $output->q = $q;
                    } elseif ($temp3 != '' && ($temp3 < $device->temp3_min || $temp3 > $device->temp3_max)) {
                        $q++;
                        $output->q = $q;
                    } elseif ($temp4 != '' && ($temp4 < $device->temp4_min || $temp4 > $device->temp4_max)) {
                        $q++;
                        $output->q = $q;
                    } else {
                        $r++;
                        $output->r = $r;
                    }
                } else {
                    $r++;
                    $output->r = $r;
                }
            }
            $finaloutput1[] = $output;
        }
    }
    // $finaloutput[] = array_merge($finaloutput1,$finaloutput2);
    $ajaxpage = new ajaxpage();
    $ajaxpage->SetResult($finaloutput1);
    $ajaxpage->Render();
}

function getwh() {
    $customerno = $_SESSION['customerno'];
    $finaloutput = array();
    $IST = getIST();
    $temp_conversion = new TempConversion();
    $temp_conversion->use_humidity = $_SESSION["use_humidity"];
    $temp_conversion->switch_to = $_SESSION["switch_to"];
    $devicemanager = new DeviceManager($customerno);
    $devices = $devicemanager->deviceformappings_wh();
    $group_based_vehicle = vehicles_array(groupBased_warehosue_cron($customerno, $_SESSION['userid']));
    if (isset($devices)) {
        $p = 0;
        $q = 0;
        $r = 0;
        $j = 1;
        foreach ($devices as $device) {
            if (!array_key_exists($device->vehicleid, $group_based_vehicle)) {
                continue;
            }
            $output = new stdClass();
            $output->p = $p;
            $output->q = $q;
            $output->r = $r;
            $temp_conversion->unit_type = $device->get_conversion;
            $lastupdated2 = strtotime($device->lastupdated);
            $output->cgeolat = $device->devicelat;
            $output->cgeolong = $device->devicelong;
            $output->location = location($device->devicelat, $device->devicelong, $device->use_geolocation);
            $output->cspeed = $device->curspeed;
            $output->lastupdated = diff($IST, $lastupdated2);
            $output->clastupdated = getduration($device->lastupdated);
            $output->status = getstatus($device->stoppage_flag, $device->stoppage_transit_time, $device->lastupdated_store);
            $output->cvehicleid = $device->vehicleid;
            $output->idleimage = vehicleimageidle($device);
            $output->iimage = vehicleimage($device);
            if ($device->powercut == 0) {
                $output->pc = '../../images/RTD/PowerCut/off.png';
                $output->pctitle = "Power Cut";
            } else {
                $output->pc = 'on';
                $output->pctitle = "Normal";
            }
            $output->totaldist = round(getdistance_new($device->vehicleid, $customerno), 2);
            if ($output->totaldist < 0) {
                //$output->totaldist = round(getdistance_new($device->vehicleid,$customerno),2);
                $output->totaldist = round($output->totaldist, 2);
            }
            // Loading
            if ($device->msgkey == 1) {
                $output->msgkey = "Loading";
            } elseif ($device->msgkey == 2) {
                $output->msgkey = "Unloading";
            } else {
                $output->msgkey = "Normal";
            }
            // AC Sensor
            if ($_SESSION['use_ac_sensor'] == 1 || $_SESSION['use_genset_sensor'] == 1) {
                if ($device->acsensor == 1) {
                    if ($device->digitalioupdated != '0000-00-00 00:00:00') {
                        $digitaldiff = getduration_digitalio($device->digitalioupdated, date('Y-m-d H:i:s', strtotime($device->lastupdated)));
                    }
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
                } else {
                    $output->acsensor = "Not Active";
                }
            }
            if ($_SESSION['use_door_sensor']) {
                $digitaldiff = 'Not Active';
                if ($device->digitalioupdated != '0000-00-00 00:00:00') {
                    $digitaldiff = getduration_digitalio($device->digitalioupdated, date('Y-m-d H:i:s', strtotime($device->lastupdated)));
                    $door_status = door_status($device->is_door_opp, $device->digitalio);
                    if (!$door_status) {
                        $digitaldiff = "Open <br/>since $digitaldiff";
                    } else {
                        $digitaldiff = "Closed <br/>since $digitaldiff";
                    }
                }
                $output->doorsensor = $digitaldiff;
            }
            $output->temp_sensors = $_SESSION['temp_sensors'];
            if ($_SESSION['temp_sensors'] == 1) {
                // Temperature Sensor
                $temp = 'Not Active';
                $s = "analog" . $device->tempsen1;
                if ($device->tempsen1 != 0 && $device->$s != 0) {
                    $temp_conversion->rawtemp = $device->$s;
                    $temp = getTempUtil($temp_conversion);
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
            if ($_SESSION['temp_sensors'] == 2) {
                $temp1 = 'Not Active';
                $temp2 = 'Not Active';
                $s = "analog" . $device->tempsen1;
                if ($device->tempsen1 != 0 && $device->$s != 0) {
                    $temp_conversion->rawtemp = $device->$s;
                    $temp1 = getTempUtil($temp_conversion);
                } else {
                    $temp1 = '-';
                }
                $s = "analog" . $device->tempsen2;
                if ($device->tempsen2 != 0 && $device->$s != 0) {
                    $temp_conversion->rawtemp = $device->$s;
                    $temp2 = getTempUtil($temp_conversion);
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
            $ServerIST_less1 = new DateTime();
            $ServerIST_less1->modify('-60 minutes');
            $lastupdated = new DateTime($device->lastupdated_store);
            if ($lastupdated < $ServerIST_less1) {
                $p++;
                $output->p = $p;
            } else {
                if ($devices->temp1_min != 0 || $devices->temp2_min != 0) {
                    $s = "analog" . $devices->tempsen1;
                    if ($tempsen1 != 0 && $s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp = getTempUtil($temp_conversion);
                    } else {
                        $temp = '0';
                    }
                    if ($temp < $devices->temp1_min || $temp > $devices->temp1_max) {
                        $q++;
                        $output->q = $q;
                    } else {
                        $r++;
                        $output->r = $r;
                    }
                } else {
                    $r++;
                    $output->r = $r;
                }
            }
            $finaloutput[] = $output;
        }
    }
    $ajaxpage = new ajaxpage();
    $ajaxpage->SetResult($finaloutput);
    $ajaxpage->Render();
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
    $address = null;
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

function getpressure($rawtemp) {
    $temp = $rawtemp;
    return $temp;
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

?>
