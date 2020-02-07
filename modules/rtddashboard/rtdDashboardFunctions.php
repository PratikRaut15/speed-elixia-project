<?php
/**
 * @Author: shrikant
 * @Date:   2018-09-06 11:48:54
 * @Last Modified by:   shrikant
 * @Last Modified time: 2018-10-09 19:51:49
 */
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
include_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
include_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
set_time_limit(0);
if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
}
date_default_timezone_set($_SESSION['timezone']);
function getRtdDashboardData($vehicleNo) {
    $arrRTDData = array();
    $loginType = 1;
    $ecode = 0;
    $groupIds = $_SESSION['groupid'];
    $objRtddashboardManager = new RtddashboardManager($_SESSION['customerno'], $_SESSION['userid']);
    $groups = $objRtddashboardManager->getUserGroups();
    if ($_SESSION['groupid'] == 0 && $groups[0] != 0) {
        $groupIds = implode(',', $groups);
    }
    if (isset($_SESSION['ecodeid'])) {
        $loginType = 2;
        $ecode = $_SESSION['ecodeid'];
    } elseif (isset($_SESSION['roleid']) && $_SESSION['roleid'] == '43') {
        $loginType = 3;
    }
    $objVehicle = new stdClass();
    $objVehicle->loginType = $loginType;
    $objVehicle->groupsIds = $groupIds;
    $objVehicle->pageIndex = 1;
    $objVehicle->pageSize = -1;
    $objVehicle->customerNo = $_SESSION['customerno'];
    $objVehicle->isWareHouse = 0;
    $objVehicle->searchString = $vehicleNo;
    $objVehicle->userkey = sha1($_SESSION['userkey']);
    $objVehicle->isRequiredThirdParty = 0;
    $objVehicle->ecode = $ecode;
    $rtdData = $objRtddashboardManager->getRtdDashboardDeatils($objVehicle);
    if (isset($rtdData)) {
        //print_r($rtdData);
        $arrRTDData = generateDashboardData($rtdData);
    }
    return $arrRTDData;
}

function generateDashboardData($rtdData) {
    $arrList = array();
    if (isset($rtdData) && !empty($rtdData)) {
        $i = 1;
        foreach ($rtdData as $key => $data) {
            $objData = array();
            $lastUpdated = getduration(date(speedConstants::DEFAULT_TIMESTAMP, strtotime($data['lastupdated'])));
            $vehicleStatus = getstatus($data['stoppage_flag'], $data['stoppage_transit_time'], $data['lastupdated'], $data['gpsfixed']);
            $location = location($data['devicelat'], $data['devicelong'], $data['use_geolocation']);
            $totaldistance = getdistance_new($data['vehicleid'], $data['customerno']);
            $objData['Id'] = $data['vehicleid'];
            $objData['customerNo'] = $data['customerno'];
            $objData['SrNo'] = $i;
            $vehicle = (object) $data;
            $image = vehicleImage($vehicle);
            $objData['Image']  = $image;
            $vehicleId = $data['vehicleid'];
            $vehicleNo = $data['vehicleno'];
            $unitNo = $data['unitno'];
            $mobiliser = $data["is_mobiliser"];
            $freeze = $data["is_freeze"];
            $action = '';
            $action .= '<a title="Travel History" onclick="travelhistopen(' . $data['vehicleid'] . ');"><img src="../../images/history-512.png"  style="cursor:pointer;height:25px;width:25px;"></img></a>';
            $action .= '<a title="Route History" onclick="routehistopen(' . $data['vehicleid'] . ');"><img src="../../images/play_round.png"  style="cursor:pointer;height:30px;width:30px;"></img></a>';
            if ($_SESSION['buzzer'] == 1 && $data['is_buzzer'] == 1) {
                $action .= '<a href="javascript:void(0);" onclick="click_buzzer(' . $vehicleId . ',' . $unitNo . ');"><img class = "buzzer" src = "../../images/buzzer.png" alt="Buzzer On" title = "Buzzer"></a>';
            } elseif ($_SESSION['buzzer'] == 1 && $data['is_buzzer'] == 0) {
                $action .= '<a href="javascript:void(0);" onclick="click_buzzer1(' . $vehicleId . ',' . $unitNo . ');"><img class = "rt" src = "../../images/buzzer.png" alt="Buzzer Off" title = "Buzzer"></a>';
            }
            if ($_SESSION['immobiliser'] == 1 && $data['is_mobiliser'] == 0) {
                $action .= '<a href="javascript:void(0);" onclick="click_immobiliser(' . $vehicleId . ',' . $unitNo . ',' . $mobiliser . ', 1);"><img class = "rt" src = "../../images/immobiliser1.png" alt="Immobiliser Off" title = "Immobilizer"></a>';
            } elseif ($_SESSION['immobiliser'] == 1 && $data['is_mobiliser'] == 1) {
                if ($data['mobiliser_flag'] == 1) {
                    $action .= '<a href="javascript:void(0);" onclick="click_immobiliser(' . $vehicleId . ',' . $unitNo . ',' . $mobiliser . ', 2);"><img class = "rt" src = "../../images/immobiliser.png" alt="Immobiliser Off" title = "Immobilizer"></a>';
                } elseif ($data['mobiliser_flag'] == 0) {
                    $action .= '<a href="javascript:void(0);" onclick="click_immobiliser(' . $vehicleId . ',' . $unitNo . ',' . $mobiliser . ', 1);"><img class = "rt" src = "../../images/immobiliser1.png" alt="Immobiliser Off" title = "Immobilizer"></a>';
                }
            }
            if ($_SESSION['freeze'] == 1 && $data['is_freeze'] == 1) {
                $action .= '<a href="javascript:void(0);" onclick="click_unfreeze(' . $vehicleId . ',' . $unitNo . ',' . $freeze . ', 0);"><img style="width:30px; height:24px;" class="buzzer"  src = "../../images/freezetrue.png"  alt="Freeze On" title = "Freeze" ></a>';
            } elseif ($_SESSION['freeze'] == 1 && $data['is_freeze'] == 0) {
                $action .= '<a href="javascript:void(0);" onclick="click_freeze(' . $vehicleId . ',' . $unitNo . ',' . $freeze . ', 1);"><img style="width:30px; height:24px;" class="rt"  src = "../../images/freezefalse.png"  alt="Freeze Off" title = "Freeze" ></a>';
            }
            $objData['Action'] = $action;
            $objData['Last Updated'] = $lastUpdated;
            if ($_SESSION['groupid'] == 0) {
                if ($data['groupid'] == 0) {
                    $objData['Group'] = "Ungrouped";
                } else {
                    $group = getgroupname($data['groupid']);
                    $groupName = isset($group->groupname) ? $group->groupname : '';
                    $objData['Group'] = $groupName;
                }
            }
            if (isset($data['ignition_wirecut']) && $data['ignition_wirecut'] == 0) {
                $objData['Status'] = "Ignition Wire Removed";
            } else {
                $objData['Status'] = $vehicleStatus;
            }
            $objData['vehicleNo'] = $data['vehicleno'];
            $objData['Vehicle No'] = $data['vehicleno'];
            //$objData['Driver'] = $data['drivername'];
            $driverName = $data['drivername'];
            $driverPhone = $data['driverphone'];
            $objData['Driver'] = $data['drivername'] . "<br/>(" . $data['driverphone'] . ")
            <img title='send message to Driver' style='width:25px;height:25px;cursor:pointer;' alt=' ' src='../../images/sms_to.png' onclick='messageToDriver($vehicleId,\"$driverName\",\"$driverPhone\");'></img>";
            $objData['Unit'] = $data['unitno'];
            $objData['Location'] = $location;
            $objData['Checkpoint'] = "NA";
            if (isset($data['cname'])) {
                $checkpnt_toggle = getduration_digitalio($data['checkpoint_timestamp'], date(speedConstants::DEFAULT_TIMESTAMP, strtotime($data['lastupdated'])));
                if ($data['chkpoint_status'] == 0) {
                    $objData['Checkpoint'] = $data['cname'] . ' since ' . $checkpnt_toggle;
                } elseif ($data['chkpoint_status'] == 1) {
                    $objData['Checkpoint'] = $data['cname'] . ' since ' . $checkpnt_toggle;
                }
            }
            $objData['Route'] = "NA";
            if ($data['routeDirection']) {
                $cronManagerObj = new CronManager();
                $arrCheckpoints = $cronManagerObj->checkVehicleExistsInRoute($data['checkpointId'], $data['customerno'], $data['vehicleid']);
                if (isset($arrCheckpoints) && !empty($arrCheckpoints)) {
                    $arrRouteCheckpoints = array();
                    $routeManagerObj = new RouteManager($data['customerno']);
                    $arrRouteCheckpoints = $routeManagerObj->getchksforroute($arrCheckpoints[0]->routeid);
                    if (!empty($arrRouteCheckpoints)) {
                        $firstChk = reset($arrRouteCheckpoints);
                        $lastChk = end($arrRouteCheckpoints);
                        $img = '';
                        if ($data['routeDirection'] == 1) {
                            $img = ' <img src="../../images/right_trip.png"  style="cursor:pointer;height:25px;width:25px;"> ';
                        } elseif ($data['routeDirection'] == 2) {
                            $img = ' <img src="../../images/left_trip.png"  style="cursor:pointer;height:25px;width:25px;"> ';
                        }
                        $string = $firstChk->cname . $img . $lastChk->cname;
                        $objData['Checkpoint'] = $firstChk->cname . $img . $lastChk->cname;
                    }
                }
            }
            $objData['Speed(Km/hr)'] = $data['curspeed'];
            $objData['Speed'] = $data['curspeed'];
            $objData['Distance(In Kms)'] = $totaldistance;
            $objData['Distance'] = $totaldistance;
            $objData['View'] = "<a onclick='view_vehicle_history(" . $vehicleId . ");' title='Service Call History'><img src='../../images/service_call.png' style='width: 25px; height:25px'></img></a>";
            $objData['View'] .= "<a class='helper' onclick='call_row(" . $vehicle->vehicleid . ");' title='Click Here For Detail View' style='cursor: pointer;'><img class='iv_all' id='iv_" . $vehicleId . "' src='../../images/show.png' alt=' ' style='width: 16px; height:16px' /></a>";

            if (isset($_SESSION['temp_sensors'])) {
                $arrTemp = getTemperature($vehicle);
                if(isset($arrTemp) && !empty($arrTemp)){
                   if ($_SESSION['temp_sensors'] == 1) {
                        $objData['Temperature'] = $arrTemp['Temperature1'];
                    } elseif ($_SESSION['temp_sensors'] == 2) {
                        $objData['Temperature 1']=$arrTemp['Temperature1'];
                        $objData['Temperature 2']=$arrTemp['Temperature2'];
                    } elseif ($_SESSION['temp_sensors'] == 3) {
                        $objData['Temperature 1']=$arrTemp['Temperature1'];
                        $objData['Temperature 2']=$arrTemp['Temperature2'];
                        $objData['Temperature 3']=$arrTemp['Temperature3'];
                    } elseif ($_SESSION['temp_sensors'] == 4) {
                        $objData['Temperature 1']=$arrTemp['Temperature1'];
                        $objData['Temperature 2']=$arrTemp['Temperature2'];
                        $objData['Temperature 3']=$arrTemp['Temperature3'];
                        $objData['Temperature 4']=$arrTemp['Temperature4'];
                    }
                }
            }



            $arrList[] = $objData;
            $i++;
        }
    }
    return $arrList;
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

function getstatus($stoppage_flag, $stoppage_transit_time, $lastupdated, $gpsfixed = null) {
    $ServerIST_less1 = new DateTime();
    $ServerIST_less1->modify('-60 minutes');
    $status = '';
    $lastupdated = new DateTime($lastupdated);
    if ($lastupdated < $ServerIST_less1) {
        $status = "Inactive";
    } elseif (isset($gpsfixed) && $gpsfixed == 'V') {
        $status = "GPS Signal Lost";
    } else {
        $diff = getduration($stoppage_transit_time);
        if ($stoppage_flag == '0') {
            $status = "Idle since<br> " . $diff;
        } else {
            $status .= "Running since<br> " . $diff;
        }
    }
    return $status;
}

function location($lat, $long, $usegeolocation, $customerno = null) {
    $address = NULL;
    $customerno = (!isset($customerno)) ? $_SESSION['customerno'] : $customerno;
    $GeoCoder_Obj = new GeoCoder($customerno);
    $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
    return $address;
}

function getgroupname($groupid) {
    $groupmanager = new GroupManager($_SESSION['customerno']);
    $group = $groupmanager->getgroupname($groupid);
    return $group;
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

function vehicleImage($vehicle) {
        $tempconversion = new TempConversion();
        $tempconversion->unit_type = $vehicle->get_conversion;
        $tempconversion->use_humidity = $_SESSION['use_humidity'];

        $ServerIST_less1 = new DateTime();
        $vm = new VehicleManager($vehicle->customerno);
        $min = $vm->getTimezoneDiffInMin($vehicle->customerno);
        if (!empty($min)) {
            $min = "+$min seconds";
            $ServerIST_less1->modify($min);
        }
        $ServerIST_less1->modify('-60 minutes');
        $lastupdated = new DateTime($vehicle->lastupdated);
        $vehicle->lastupdated = $lastupdated->format('Y-m-d H:i:s');
        $diff = getduration($vehicle->stoppage_transit_time);
        if ($vehicle->kind == 'Bus') {
            $image = "<img id='innerImg' class='imgcl'  src='../../images/RTD/Vehicles/";
            $image .= "Bus/Bus";
        } elseif ($vehicle->kind == 'Truck') {
            $image = "<img id='innerImg' class='imgcl'  src='../../images/RTD/Vehicles/";
            $image .= "Truck/Truck";
        } elseif ($vehicle->kind == 'Car' || $vehicle->kind == 'SUV') {
            $image = "<img id='innerImg' class='imgcl'  src='../../images/RTD/Vehicles/";
            $image .= "Car/Car";
        }
        if ($_SESSION['portable'] != '1' && $vehicle->kind != 'Warehouse') {
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

function getTemperature($vehicle) {
        $arrTemp = null;
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
            $arrTemp['Temperature4'] = $temp4 . $spantext4 . $tempReport4;
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
            $arrTemp['Temperature3'] = $temp3 . $spantext3 . $tempReport3;
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
            $arrTemp['Temperature2'] = $temp2 . $spantext2 . $tempReport2;
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
            $arrTemp['Temperature1'] = $temp1 . $spantext1 . $tempReport1;
            $tempString = "<td class='tempcl " . $tdclass_temp1 . "'>" . $temp1 . $spantext1 . $tempReport1 . "</td> " . $tempString;
        }
        //echo $tempString;
        return $arrTemp;
    }

function updateVehicle($objVehicle) {
    $noOfRowsAffected = 0;
    $objRtddashboardManager = new RtddashboardManager($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objRtddashboardManager->updateVehicle($objVehicle);
    return $noOfRowsAffected;
}



?>
