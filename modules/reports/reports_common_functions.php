<?php

if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
include_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
include_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
include_once $RELATIVE_PATH_DOTS . 'lib/comman_function/reports_func.php';

if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
}

function get_location($lat, $long) {
    $geo_location = "N/A";
    if ($lat != "" && $long != "") {
        $geo_obj = new GeoCoder($_SESSION['customerno']);
        $geo_location = $geo_obj->get_city_bylatlong($lat, $long);
    }
    return $geo_location;
}

function get_location_pdf($lat, $long, $customerno) {
    $geo_location = "N/A";
    if ($lat != "" && $long != "") {
        $geo_obj = new GeoCoder($customerno);
        $geo_location = $geo_obj->get_city_bylatlong($lat, $long);
    }
    return $geo_location;
}

function get_usegeolocation($customerno) {
    $GeoCoder_Obj = new GeoCoder($customerno);
    $geolocation = $GeoCoder_Obj->get_use_geolocation();
    return $geolocation;
}

function get_location_detail($lat, $long, $custID = null) {
    $address = null;
    $customerno = (!isset($custID)) ? $_SESSION['customerno'] : $custID;
    $GeoCoder_Obj = new GeoCoder($customerno);
    $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
    return $address;
}

function getunitno($deviceid) {
    $um = new UnitManager($_SESSION['customerno']);
    $unitno = $um->getuidfromdeviceid($deviceid);
    return $unitno;
}

function getextra($deviceid) {
    $um = new UnitManager($_SESSION['customerno']);
    $unitno = $um->getextrafromdeviceid($deviceid);
    return $unitno;
}

function getunitdetails($deviceid) {
    $um = new UnitManager($_SESSION['customerno']);
    $unitno = $um->getunitdetailsfromdeviceid($deviceid);
    return $unitno;
}

function getunitmutedetails($vehiclied, $uid, $customerno = null) {
    $customerno = isset($customerno) ? $customerno : $_SESSION['customerno'];
    $um = new UnitManager($customerno);
    $unitno = $um->getunitmutedetails($vehiclied, $uid);
    return $unitno;
}

function checkTemperatureMute($mutedetails, $tempsen, $lastupdated) {
    $isMuted = false;
    if (isset($mutedetails)) {
        foreach ($mutedetails as $rowdata) {
            if ($rowdata->temp_type == $tempsen && strtotime($lastupdated) >= strtotime($rowdata->mute_starttime) && strtotime($lastupdated) <= strtotime($rowdata->mute_endtime)) {
                $isMuted = true;
            }
        }
    }
    return $isMuted;
}

function getName_ByType($nid) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicledata = $vehiclemanager->getNameForTemp($nid);
    return $vehicledata;
}

function getName_ByType_pdf($nid, $customerno) {
    $vehiclemanager = new VehicleManager($customerno);
    $vehicledata = $vehiclemanager->getNameForTemp($nid);
    return $vehicledata;
}

function getunitdetailsfromvehid($deviceid, $customerno = null) {
    $customerno = isset($customerno) ? $customerno : $_SESSION['customerno'];
    $um = new UnitManager($customerno);
    $unitno = $um->getunitdetailsfromvehid($deviceid);
    return $unitno;
}

function getunitdetailspdf($customerno, $deviceid) {
    $um = new UnitManager($customerno);
    $unitno = $um->getunitdetailsfromdeviceid($deviceid);
    return $unitno;
}

function getunitdetailspdf_cron($customerno, $deviceid) {
    $um = new UnitManager($customerno);
    $unitno = $um->getunitdetailsfromdeviceid_cron($deviceid);
    return $unitno;
}

function getunitnopdf($customerno, $deviceid) {
    $um = new UnitManager($customerno);
    $unitno = $um->getuidfromdeviceid($deviceid);
    return $unitno;
}

function getacinvertval($unitno) {
    $um = new UnitManager($_SESSION['customerno']);
    $invertval = $um->getacinvertval($unitno);
    return $invertval['0'];
}

function getacinvertvalpdf($customerno, $unitno) {
    $um = new UnitManager($customerno);
    $invertval = $um->getacinvertval($unitno);
    return $invertval['0'];
}

function getvehicleno($deviceid) {
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $vehicleno = $devicemanager->getvehiclenofromdeviceid($deviceid);
    return $vehicleno;
}

function getacreport($STdate, $EDdate, $deviceid) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $days = array();
    $customerno = $_SESSION['customerno'];
    $unitno = getunitno($deviceid);
    $acinvertval = getacinvertval($unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getacdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $finalreport = create_html_from_report($days, $acinvertval);
    }
    echo $finalreport;
}

//full genset report start here
function getgensetreport($STdate, $EDdate, $deviceid) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $finalreport = '';
    $customerno = $_SESSION['customerno'];
    $unitno = getunitno($deviceid);
    $acinvertval = getacinvertval($unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getgensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $finalreport = create_gensethtml_from_report($days, $acinvertval);
    } else {
        $finalreport = "<tr><td colspan='100%' style='text-align:center;'>No Data</td></tr></tbody></table>";
    }
    echo $finalreport;
}

//get genset report summary only -ganesh
function getgensetreportsummary($STdate, $EDdate, $deviceid) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $finalreport = '';
    $customerno = $_SESSION['customerno'];
    $unitno = getunitno($deviceid);
    $acinvertval = getacinvertval($unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getgensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $finalreport = create_gensethtml_summary_from_report($days, $acinvertval);
    } else {
        $finalreport = "<tr><td colspan='100%' style='text-align:center;'>No Data</td></tr></tbody></table>";
    }
    echo $finalreport;
}

//genset summary end here
//get gensert report details only -ganesh
function getgensetreportdetails($STdate, $EDdate, $deviceid, $STime = null, $ETime = null, $gensetSensor = null) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $finalreport = '';
    $customerno = $_SESSION['customerno'];
    $unitno = getunitno($deviceid);
    $acinvertval = getacinvertval($unitno);
    $gensetSensor = isset($gensetSensor) ? $gensetSensor : 1;
    if (isset($totaldays)) {
        $firstDay = reset($totaldays);
        $lastDay = end($totaldays);
        $defaultStartTime = "00:00:00";
        $defaultEndTime = "23:59:59";
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $startTime = $userdate . " " . $STime;
                $endTime = $userdate . " " . $ETime;
                if ($firstDay == $userdate && $lastDay == $userdate) {
                    $data = getgensetdata_fromsqlite($location, $deviceid, $startTime, $endTime, $gensetSensor);
                } elseif ($firstDay == $userdate) {
                    $data = getgensetdata_fromsqlite($location, $deviceid, $startTime, null, $gensetSensor);
                } elseif ($lastDay == $userdate) {
                    $data = getgensetdata_fromsqlite($location, $deviceid, null, $endTime, $gensetSensor);
                } else {
                    $data = getgensetdata_fromsqlite($location, $deviceid, null, null, $gensetSensor);
                }
            }
            //prettyPrint($data);die();
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $finalreport = create_genset_detail_html_from_report($days, $acinvertval, $gensetSensor);
    } else {
        $finalreport = "<tr><td colspan='100%' style='text-align:center;'>No Data</td></tr></tbody></table>";
    }
    echo $finalreport;
}

function get_inactive_device($startdate, $enddate) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $devices = $vm->getInactiveDevices($startdate, $enddate);
    return $devices;
}

function getextragensetreport($STdate, $EDdate, $deviceid, $extraid, $extraval) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $finalreport = '';
    $customerno = $_SESSION['customerno'];
    $unitno = getunitno($deviceid);
    //$acinvertval = getacinvertval($unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getextragensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $finalreport = create_extragensethtml_from_report($days, $extraid, $extraval);
    } else {
        $finalreport = "<tr><td colspan='100%' style='text-align:center;'>No Data</td></tr></tbody></table>";
    }
    echo $finalreport;
}

function gettemptabularreport($STdate, $EDdate, $deviceid, $interval, $stime, $etime, $tripmin = null, $tripmax = null, $tempselect = null, $objReport = null) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $graph_days = array();
    $graph_days_temp1 = array();
    $graph_days_temp2 = array();
    $graph_days_temp3 = array();
    $graph_days_temp4 = array();
    $graph_days_ig = array();
    $veh_temp_details = array();
    $customerno = $_SESSION['customerno'];
    $unit = getunitdetails($deviceid);

    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $graph_data = null;
            $STdate = date("Y-m-d", strtotime($STdate));
            $f_STdate = $userdate . " 00:00:00";
            if ($userdate == $STdate) {
                $f_STdate = $userdate . " " . $stime . ":00";
            }
            $EDdate = date("Y-m-d", strtotime($EDdate));
            $f_EDdate = $userdate . " 23:59:59";
            if ($userdate == $EDdate) {
                $f_EDdate = $userdate . " " . $etime . ":00";
            }

            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $return = gettempdata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, true, $unit);
                /*
                 * Changes Made By : Pratik Raut
                 * Added Condition as BAD Data was Coming
                 * Date : 04-10-2019
                 */
                if (!empty($return['vehicleid'])) {
                    $vehicleid = $return['vehicleid'];
                }
                /**
                 * Changes ends Here
                 */
                $data = $return[0];
                $graph_data = $return[1];
                $countGraph0 = count($graph_data['0']);
                $countGraph1 = count($graph_data['1']);
                $countGraph2 = count($graph_data['2']);
                $countGraph3 = count($graph_data['3']);
                //echo $countGraph1; die;
                $graph_data_temp1 = (isset($graph_data[0]) && $countGraph0 > 0) ? $graph_data[0] : NULL;
                $graph_data_temp2 = (isset($graph_data[1]) && $countGraph1 > 0) ? $graph_data[1] : NULL;
                $graph_data_temp3 = (isset($graph_data[2]) && $countGraph2 > 0) ? $graph_data[2] : NULL;
                $graph_data_temp4 = (isset($graph_data[3]) && $countGraph3 > 0) ? $graph_data[3] : NULL;
                $graph_ig = $return['ig_graph'];
                // pr($graph_data_temp1);
                // pr($graph_data_temp2);
                // pr($graph_data_temp3);
                // pr($graph_data_temp4);
            }
            //p($graph_data_temp2);
            if (isset($data) && count($data) > 1) {
                $days = array_merge($days, $data);
            }
            if (isset($graph_data_temp1) && count($graph_data_temp1) > 1) {
                $graph_days_temp1 = array_merge($graph_days_temp1, $graph_data_temp1);
                $graph_days_ig = array_merge($graph_days_ig, $graph_ig);
            }

            if (isset($graph_data_temp2) && count($graph_data_temp2) > 1) {
                $graph_days_temp2 = array_merge($graph_days_temp2, $graph_data_temp2);
            }

            if (isset($graph_data_temp3) && count($graph_data_temp3) > 1) {
                $graph_days_temp3 = array_merge($graph_days_temp3, $graph_data_temp3);
            }
            if (isset($graph_data_temp4) && count($graph_data_temp4) > 1) {
                $graph_days_temp4 = array_merge($graph_days_temp4, $graph_data_temp4);
            }
        }
    }
    //p($graph_days_temp2);
    if (isset($days) && count($days) > 0) {
        if (isset($return['vehicleid'])) {
            /*
             * Changes Made By : Pratik Raut
             * Added Condition as BAD Data was Coming
             * Date : 04-10-2019
             */
            $veh_temp_details = getunitdetailsfromvehid(empty($return['vehicleid']) ? $vehicleid : $return['vehicleid'], $customerno);

            /**
             * Changes Ends Here
             */
        }
        if (isset($tripmin)) {
            $unit->temp1_min = $tripmin;
            $unit->temp2_min = $tripmin;
            $unit->temp3_min = $tripmin;
            $unit->temp4_min = $tripmin;
        }
        if (isset($tripmax)) {
            $unit->temp1_max = $tripmax;
            $unit->temp2_max = $tripmax;
            $unit->temp3_max = $tripmax;
            $unit->temp4_max = $tripmax;
        }
        $finalreport = create_temphtml_from_report($days, $unit, $veh_temp_details, $interval, $tempselect, $objReport);
    } else {
        $finalreport = "<tr><td colspan='10'>No Data</td></tr>";
    }
    $graph_days_final_temp1 = '';
    $graph_days_final_temp2 = '';
    $graph_days_final_temp3 = '';
    $graph_days_final_temp4 = '';
    $graph_ig_final = '';
    if (!empty($graph_days_temp1)) {
        $graph_days_final_temp1 = implode(',', $graph_days_temp1);
        $graph_ig_final = implode(',', $graph_days_ig);
    }
    if (!empty($graph_days_temp2)) {
        $graph_days_final_temp2 = implode(',', $graph_days_temp2);
    }
    if (!empty($graph_days_temp3)) {
        $graph_days_final_temp3 = implode(',', $graph_days_temp3);
    }
    if (!empty($graph_days_temp4)) {
        $graph_days_final_temp4 = implode(',', $graph_days_temp4);
    }
    $graph_days_final = array($graph_days_final_temp1, $graph_days_final_temp2, $graph_days_final_temp3, $graph_days_final_temp4);
    return array($finalreport, $graph_days_final, $veh_temp_details, $graph_ig_final);
}

function create_temphtml_from_report($datarows, $vehicle, $veh_temp_details = null, $interval = 120, $tempselect = null, $objReport = null) {
    $i = 1;
    $tr = 0;
    $totalminute = 0;
    $lastdate = null;
    $display = '';
    $goodcount = 0;
    $temp1_non_comp_count = 0;
    $temp2_non_comp_count = 0;
    $temp3_non_comp_count = 0;
    $temp4_non_comp_count = 0;
    $temp1_bad_reading = 0;
    $temp2_bad_reading = 0;
    $temp3_bad_reading = 0;
    $temp4_bad_reading = 0;
    $temp1_mute_reading = 0;
    $temp2_mute_reading = 0;
    $temp3_mute_reading = 0;
    $temp4_mute_reading = 0;
    $temp1_nonmute_reading = 0;
    $temp2_nonmute_reading = 0;
    $temp3_nonmute_reading = 0;
    $temp4_nonmute_reading = 0;
    $temp1_data = "";
    $temp2_data = "";
    $temp3_data = "";
    $temp4_data = "";
    $restemp1 = array(0);
    $restemp2 = array(0);
    $restemp3 = array(0);
    $restemp4 = array(0);
    $temp1_min = '';
    $temp2_min = '';
    $temp3_min = '';
    $temp4_min = '';
    $temp1_max = '';
    $temp2_max = '';
    $temp3_max = '';
    $temp4_max = '';
    if (isset($objReport) && isset($objReport->customMinTemp) && isset($objReport->customMaxTemp) && $objReport->customMinTemp != '' && $objReport->customMaxTemp != '' && $objReport->customMinTemp != -1 && $objReport->customMaxTemp != -1) {
        $min_max_temp1 = getCustomTempRange(1, $objReport->customMinTemp, $objReport->customMaxTemp);
        $min_max_temp2 = getCustomTempRange(2, $objReport->customMinTemp, $objReport->customMaxTemp);
        $min_max_temp3 = getCustomTempRange(3, $objReport->customMinTemp, $objReport->customMaxTemp);
        $min_max_temp4 = getCustomTempRange(4, $objReport->customMinTemp, $objReport->customMaxTemp);
    } else {
        $min_max_temp1 = get_min_max_temp(1, $veh_temp_details);
        $min_max_temp2 = get_min_max_temp(2, $veh_temp_details);
        $min_max_temp3 = get_min_max_temp(3, $veh_temp_details);
        $min_max_temp4 = get_min_max_temp(4, $veh_temp_details);
    }

    $temp1counter = 0;
    $temp2counter = 0;
    $temp3counter = 0;
    $temp4counter = 0;
    $temp1totalreadingcount = 0;
    $temp2totalreadingcount = 0;
    $temp3totalreadingcount = 0;
    $temp4totalreadingcount = 0;
    $kelvinDenominator4 = 0;
    $kelvinDenominator3 = 0;
    $kelvinDenominator2 = 0;
    $kelvinDenominator1 = 0;
    $temp1_color = array('range1' => 0, 'range2' => 0, 'range3' => 0, 'range4' => 0);
    $temp2_color = array('range1' => 0, 'range2' => 0, 'range3' => 0, 'range4' => 0);
    $temp3_color = array('range1' => 0, 'range2' => 0, 'range3' => 0, 'range4' => 0);
    $temp4_color = array('range1' => 0, 'range2' => 0, 'range3' => 0, 'range4' => 0);
    $mutedetails = getunitmutedetails($vehicle->vehicleid, $vehicle->uid, $_SESSION['customerno']);

    if (isset($datarows)) {
        $sumTEMP1 = 0;
        $sumTEMP2 = 0;
        $sumTEMP3 = 0;
        $sumTEMP4 = 0;
        foreach ($datarows as $change) {
            /* Don't show the entire log of 1 min data when customer pulls out this interval for compliance only */
            if ($interval != "1") {
                $comparedate = date('d-m-Y', strtotime($change->endtime));
                $today = date('d-m-Y', strtotime('Now'));
                if (strtotime($lastdate) != strtotime($comparedate)) {
                    if ($today == $comparedate) {
                        $todays = date('Y-m-d');
                        $todayhms = date('Y-m-d H:i:s');
                        $to_time = strtotime("$todayhms");
                        $from_time = strtotime("$todays 00:00:00");
                        $totalminute = round(abs($to_time - $from_time) / 60, 2);
                    } else {
                        $count = $i;
                    }
                    $display .= "<tr><th align='center' colspan = '100%' style='background:#d8d5d6'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                    $lastdate = date('d-m-Y', strtotime($change->endtime));
                    $i++;
                }
                $change->lastupdated = $change->starttime;
                $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
                $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
                if ($_SESSION['customerno'] == 116) {
                    $starttime_disp = date(speedConstants::DEFAULT_DATETIME, strtotime($change->lastupdated));
                } else {
                    $starttime_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
                }
                $display .= "<tr><td>$starttime_disp</td>";
                if ($_SESSION['switch_to'] != 3) {
                    $location = get_location_detail($change->devicelat, $change->devicelong);
                    $display .= "<td>$location</td>";
                }
            }

            $temp1 = $temp2 = $temp3 = $temp4 = speedConstants::TEMP_WARNING;
            $tdstring = '';
            $objTemp = new TempConversion();
            $objTemp->unit_type = $veh_temp_details->get_conversion;
            $objTemp->use_humidity = $_SESSION['use_humidity'];
            $objTemp->switch_to = $_SESSION['switch_to'];

            if (isset($_POST['tempsel'])) {
                $arr = explode("-", $_POST['tempsel']);
            } else {
                $arr = explode("-", $_SESSION['temp_sensors']);
            }

            $temp_sensors = $_SESSION['temp_sensors'];

            $pass = (isset($arr[0]) && ($arr[0] != "") ? $arr[0] : $temp_sensors);

            switch ($pass) {
                case 4:
                    if ($vehicle->tempsen4 != 0) {
                        $isTemperatureMuted = checkTemperatureMute($mutedetails, 4, $change->lastupdated);
                        if ($isTemperatureMuted) {
                            $temp4 = 'Muted';
                            $temp4_mute_reading++;
                        } else {
                            $temp4_nonmute_reading++;
                            $s4 = "analog" . $vehicle->tempsen4;
                            if ($change->$s4 != 0) {
                                $objTemp->rawtemp = $change->$s4;
                                $temp4 = getTempUtil($objTemp);
                                $sumTEMP4 += $temp4;
                                /*Kelvin Calculation*/
                                $kelvinTemp4 = convertDegreeToKelvin($temp4); // Step 1
                                $kelvinDenominator4 = $kelvinDenominator4 + exp((-(speedConstants::DELTA_H) / (speedConstants::GAS_CONSTANT * $kelvinTemp4))); // Step 2

                                $totalKelvinTemperature4 = $kelvinDenominator4;
                                /*Kelvin Calculation*/
                                if ($temp4 == 0 || $temp4 > NORMAL_MAX_TEMP || $temp4 < NORMAL_MIN_TEMP) {
                                    if ($temp4 == 0) {
                                        $temp4 = 'Wirecut';
                                    } else {
                                        $temp4 = 'Bad Data';
                                    }
                                    $temp4_bad_reading++;
                                } elseif ($temp4 != 0) {
                                    $temp4_min = set_summary_min_temp4($temp4);
                                    $temp4_max = set_summary_max_temp4($temp4);
                                    if ($temp4 > $min_max_temp4['temp_max_limit'] || $temp4 < $min_max_temp4['temp_min_limit']) {
                                        $temp4_non_comp_count++;
                                    } else {
                                        if (!empty($min_max_temp4['temp_color'])) {
                                            for ($j = 1; $j < 5; $j++) {
                                                $key_start = 'temp4_range' . $j . '_start';
                                                $key_end = 'temp4_range' . $j . '_end';
                                                $key_color = 'temp4_range' . $j . '_color';
                                                if (($min_max_temp4['temp_color'][$key_start] != '') && ($temp4 > $min_max_temp4['temp_color'][$key_start] && $temp4 < $min_max_temp4['temp_color'][$key_end])) {
                                                    $temp4_color['range' . $j]++;
                                                }
                                            }
                                        }
                                    }
                                    $temp4 = $temp4 . speedConstants::TEMP_DEGREE;
                                }
                            } else {
                                $temp4_bad_reading++;
                            }
                        }
                        $temp4totalreadingcount++;
                    } else {
                        $temp4 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $tdstring = "<td>" . $temp4 . "</td>" . $tdstring;
                    //// checked condition comming from session or tempselect(from select box)
                    if ((isset($arr[0]) && $arr[0] == 4) && isset($arr[1]) && $arr[1] != "all") {
                        break;
                    }
                case 3:
                    if ($vehicle->tempsen3 != 0) {
                        $isTemperatureMuted = checkTemperatureMute($mutedetails, 3, $change->lastupdated);
                        if ($isTemperatureMuted) {
                            $temp3 = 'Muted';
                            $temp3_mute_reading++;
                        } else {
                            $temp3_nonmute_reading++;
                            $s3 = "analog" . $vehicle->tempsen3;
                            if ($change->$s3 != 0) {
                                $objTemp->rawtemp = $change->$s3;
                                $temp3 = getTempUtil($objTemp);
                                $sumTEMP3 += $temp3;
                                /*Kelvin Calculation*/
                                $kelvinTemp3 = convertDegreeToKelvin($temp3); // Step 1
                                $kelvinDenominator3 = $kelvinDenominator3 + exp((-(speedConstants::DELTA_H) / (speedConstants::GAS_CONSTANT * $kelvinTemp3))); // Step 2

                                $totalKelvinTemperature3 = $kelvinDenominator3;
                                /*Kelvin Calculation*/

                                if ($temp3 == 0 || $temp3 > NORMAL_MAX_TEMP || $temp3 < NORMAL_MIN_TEMP) {
                                    if ($temp3 == 0) {
                                        $temp3 = 'Wirecut';
                                    } else {
                                        $temp3 = 'Bad Data';
                                    }
                                    $temp3_bad_reading++;
                                } elseif ($temp3 != 0) {
                                    $temp3_min = set_summary_min_temp3($temp3);
                                    $temp3_max = set_summary_max_temp3($temp3);
                                    if ($temp3 > $min_max_temp3['temp_max_limit'] || $temp3 < $min_max_temp3['temp_min_limit']) {
                                        $temp3_non_comp_count++;
                                    } else {
                                        if (!empty($min_max_temp3['temp_color'])) {
                                            for ($j = 1; $j < 5; $j++) {
                                                $key_start = 'temp3_range' . $j . '_start';
                                                $key_end = 'temp3_range' . $j . '_end';
                                                $key_color = 'temp3_range' . $j . '_color';
                                                if (($min_max_temp3['temp_color'][$key_start] != '') && ($temp3 > $min_max_temp3['temp_color'][$key_start] && $temp3 < $min_max_temp3['temp_color'][$key_end])) {
                                                    $temp3_color['range' . $j]++;
                                                }
                                            }
                                        }
                                    }
                                    $temp3 = $temp3 . speedConstants::TEMP_DEGREE;
                                }
                            } else {
                                $temp3_bad_reading++;
                            }
                        }
                        $temp3totalreadingcount++;
                    } else {
                        $temp3 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $tdstring = "<td>" . $temp3 . "</td>" . $tdstring;
                    //// checked condition comming from session or tempselect(from select box)
                    if ((isset($arr[0]) && $arr[0] == 3) && (isset($arr[1]) && $arr[1] != "all")) {
                        break;
                    }
                case 2:
                    if ($vehicle->tempsen2 != 0) {
                        $isTemperatureMuted = checkTemperatureMute($mutedetails, 2, $change->lastupdated);
                        if ($isTemperatureMuted) {
                            $temp2 = 'Muted';
                            $temp2_mute_reading++;
                        } else {
                            $temp2_nonmute_reading++;
                            $s2 = "analog" . $vehicle->tempsen2;
                            if ($change->$s2 != 0) {
                                $objTemp->rawtemp = $change->$s2;
                                // print(" obj Temp <pre>"); print_r($objTemp);
                                $temp2 = getTempUtil($objTemp);
                                $sumTEMP2 += $temp2;
                                /*Kelvin Calculation*/
                                $kelvinTemp2 = convertDegreeToKelvin($temp2); // Step 1
                                $kelvinDenominator2 = $kelvinDenominator2 + exp((-(speedConstants::DELTA_H) / (speedConstants::GAS_CONSTANT * $kelvinTemp2))); // Step 2

                                $totalKelvinTemperature2 = $kelvinDenominator2;
                                /*Kelvin Calculation*/

                                if ($temp2 == 0 || $temp2 > NORMAL_MAX_TEMP || $temp2 < NORMAL_MIN_TEMP) {
                                    if ($temp2 == 0) {
                                        $temp2 = 'Wirecut';
                                    } else {
                                        $temp2 = 'Bad Data';
                                    }
                                    $temp2_bad_reading++;
                                } elseif ($temp2 != 0) {
                                    $temp2_min = set_summary_min_temp2($temp2);
                                    $temp2_max = set_summary_max_temp2($temp2);
                                    if ($temp2 > $min_max_temp2['temp_max_limit'] || $temp2 < $min_max_temp2['temp_min_limit']) {
                                        $temp2_non_comp_count++;
                                    } else {
                                        if (!empty($min_max_temp2['temp_color'])) {
                                            for ($j = 1; $j < 5; $j++) {
                                                $key_start = 'temp2_range' . $j . '_start';
                                                $key_end = 'temp2_range' . $j . '_end';
                                                $key_color = 'temp2_range' . $j . '_color';
                                                if (($min_max_temp2['temp_color'][$key_start] != '') && ($temp2 > $min_max_temp2['temp_color'][$key_start] && $temp2 < $min_max_temp2['temp_color'][$key_end])) {
                                                    $temp2_color['range' . $j]++;
                                                }
                                            }
                                        }
                                    }
                                    $temp2 = $temp2 . speedConstants::TEMP_DEGREE;
                                }
                            } else {
                                $temp2_bad_reading++;
                            }
                        }
                        $temp2totalreadingcount++;
                    } else {
                        $temp2 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $tdstring = "<td>" . $temp2 . "</td>" . $tdstring;
                    //// checked condition comming from session or tempselect(from select box)
                    if ((isset($arr[0]) && $arr[0] == 2) && (isset($arr[1]) && $arr[1] != "all")) {
                        break;
                    }
                case 1:
                    if ($vehicle->tempsen1 != 0) {
                        $isTemperatureMuted = checkTemperatureMute($mutedetails, 1, $change->lastupdated);
                        if ($isTemperatureMuted) {
                            $temp1 = 'Muted';
                            $temp1_mute_reading++;
                        } else {
                            $temp1_nonmute_reading++;
                            $s1 = "analog" . $vehicle->tempsen1;
                            if ($change->$s1 != 0) {
                                $objTemp->rawtemp = $change->$s1;
                                // print(" obj Temp <pre>"); print_r($objTemp);

                                $temp1 = getTempUtil($objTemp);
                                $sumTEMP1 += $temp1;
                                /*Kelvin Calculation*/
                                $kelvinTemp1 = convertDegreeToKelvin($temp1); // Step 1
                                $kelvinDenominator1 = $kelvinDenominator1 + exp((-(speedConstants::DELTA_H) / (speedConstants::GAS_CONSTANT * $kelvinTemp1))); // Step 2

                                $totalKelvinTemperature1 = $kelvinDenominator1;
                                /*Kelvin Calculation*/
                                if ($temp1 == 0 || $temp1 > NORMAL_MAX_TEMP || $temp1 < NORMAL_MIN_TEMP) {
                                    if ($temp1 == 0) {
                                        $temp1 = 'Wirecut';
                                    } else {
                                        $temp1 = 'Bad Data';
                                    }
                                    $temp1_bad_reading++;
                                } elseif ($temp1 != 0) {
                                    $temp1_min = set_summary_min_temp($temp1);
                                    $temp1_max = set_summary_max_temp($temp1);
                                    if ($temp1 > $min_max_temp1['temp_max_limit'] || $temp1 < $min_max_temp1['temp_min_limit']) {
                                        $temp1_non_comp_count++;
                                    } else {
                                        if (!empty($min_max_temp1['temp_color'])) {
                                            for ($j = 1; $j < 5; $j++) {
                                                $key_start = 'temp1_range' . $j . '_start';
                                                $key_end = 'temp1_range' . $j . '_end';
                                                $key_color = 'temp1_range' . $j . '_color';
                                                if (($min_max_temp1['temp_color'][$key_start] != '') && ($temp1 > $min_max_temp1['temp_color'][$key_start] && $temp1 < $min_max_temp1['temp_color'][$key_end])) {
                                                    $temp1_color['range' . $j]++;
                                                }
                                            }
                                        }
                                    }
                                    $temp1 = $temp1 . speedConstants::TEMP_DEGREE;
                                }
                            } else {
                                $temp1_bad_reading++;
                            }
                        }
                        $temp1totalreadingcount++;
                    } else {
                        $temp1 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $tdstring = "<td>" . $temp1 . "</td>" . $tdstring;
            }
            /* Don't show the entire log of 1 min data when customer pulls out this interval for compliance only */
            if ($interval != "1") {
                $display .= $tdstring;
            }
            if ($_SESSION['use_ac_sensor'] == 1) {
                if ($vehicle->acsensor == 1) {
                    if ($change->digitalio == 0 && $vehicle->isacopp == 0) {
                        $display .= "<td>On</td>";
                    } elseif ($change->digitalio == 0 && $vehicle->isacopp == 1) {
                        $display .= "<td>Off</td>";
                    } elseif ($change->digitalio == 1 && $vehicle->isacopp == 0) {
                        $display .= "<td>Off</td>";
                    } elseif ($change->digitalio == 1 && $vehicle->isacopp == 1) {
                        $display .= "<td>On</td>";
                    }
                } else {
                    $display .= "<td>Not Active</td>";
                }
            }
            $display .= '</tr>';
            $tr++;
        }
    }
    $display .= '</tbody>';
    $display .= '</table>';
    $t1 = getName_ByType($vehicle->n1);
    $t2 = getName_ByType($vehicle->n2);
    $t3 = getName_ByType($vehicle->n3);
    $t4 = getName_ByType($vehicle->n4);

    $t1 = ($t1 == '') ? 'Temperature 1' : $t1;
    $t2 = ($t2 == '') ? 'Temperature 2' : $t2;
    $t3 = ($t3 == '') ? 'Temperature 3' : $t3;
    $t4 = ($t4 == '') ? 'Temperature 4' : $t4;
    $temphtml = '';
    $temphtml2 = '';
    $temphtml3 = '';
    $temphtml4 = '';
    $span = null;

    switch ($pass) {
        case 4:
            $span = isset($span) ? $span : 4;
            $goodcount = $temp4_nonmute_reading - $temp4_bad_reading;
            if ($temp4_nonmute_reading == 0 || $goodcount == 0) {
                $abv_compliance = "Not Applicable";
                $within_compliance = "Not Applicable";
            } else {
                $abv_compliance = round(($temp4_non_comp_count / $goodcount) * 100, 2);
                $compliance_count = $goodcount - $temp4_non_comp_count;
                $within_compliance = round($compliance_count / $goodcount * 100, 2);
            }
            if (isset($compliance_count) && $compliance_count > 0) {
                for ($j = 1; $j < 5; $j++) {
                    $key_start = 'temp4_range' . $j . '_start';
                    $key_end = 'temp4_range' . $j . '_end';
                    $key_color = 'temp4_range' . $j . '_color';
                    if (isset($min_max_temp4['temp_color'][$key_color]) && $min_max_temp4['temp_color'][$key_color] != '') {
                        $temp4_color_details['range' . $j]['start'] = $min_max_temp4['temp_color'][$key_start];
                        $temp4_color_details['range' . $j]['end'] = $min_max_temp4['temp_color'][$key_end];
                        $temp4_color_details['range' . $j]['perc'] = round($temp4_color['range' . $j] / $compliance_count * 100, 2);
                        $temp4_color_details['range' . $j]['color'] = $min_max_temp4['temp_color'][$key_color];
                    }
                }
            }
            $colorTable = '';
            if (!empty($temp4_color_details)) {
                $colorTable .= '<br><table style="width:80%;">';
                foreach ($temp4_color_details AS $colorData) {
                    $colorTable .= '<tr>
                                        <td style="border:1px white solid;background-color:' . $colorData['color'] . ';text-align:center;">' . $colorData['start'] . ' - ' . $colorData['end'] . ' C</td>
                                        <td style="border:1px white solid;text-align:center;">' . $colorData['perc'] . ' %</td>
                                    </tr>';
                }
                $colorTable .= '</table>';
            }
            $temp4_min = ($temp4_min != '' ? $temp4_min . " &deg;C" : "N/A");
            $temp4_max = ($temp4_max != '' ? $temp4_max . " &deg;C" : "N/A");

            $objKelvin4 = new stdClass();
            if (isset($totalKelvinTemperature4)) {
                $objKelvin4->totalKelvinTemperature = isset($totalKelvinTemperature4)?$totalKelvinTemperature4:NULL;
                $objKelvin4->temptotalreadingcount = $temp4totalreadingcount;
                $temp4_average = round(($sumTEMP4 / $temp4totalreadingcount), 2);
                $finalMKTValue4 = round(getMeanKineticTemp($objKelvin4), 2);
            } else {
                $temp4_average = 0;
                $finalMKTValue4 = 0;
            }

            $temphtml4 = "<td style='text-align:center;'>
        <table class='table newTable'><thead><tr><th><u>" . $t4 . "</u></th></tr></thead>
            <tbody>
                <tr><td>Temperature Range :" . $min_max_temp4['temp_min_limit'] . " &deg;C to " . $min_max_temp4['temp_max_limit'] . " &deg;C</td></tr>
                <tr><td>Minimum Temperature: $temp4_min</td></tr>
                <tr><td>Maximum Temperature: $temp4_max</td></tr>
                <tr><td>Average Temperature: $temp4_average &deg;C</td></tr>
                <tr><td>Total Reading: $temp4totalreadingcount</td></tr>
                <tr><td>Muted Reading: $temp4_mute_reading</td></tr>";
            if (isset($_SESSION['customerno']) && $_SESSION['customerno'] != speedConstants::CUSTNO_PHARM_EASY) {
                $temphtml4 .= "<tr><td>Non compliance readings : $temp4_non_comp_count</td></tr>";
            }
            $temphtml4 .= "<tr><td>Bad readings : $temp4_bad_reading</td></tr>";
            if (isset($_SESSION['customerno']) && $_SESSION['customerno'] != speedConstants::CUSTNO_PHARM_EASY) {
                $temphtml4 .= "<tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span>$colorTable</td></tr>";
            }
            $temphtml4 .= "<td>
                    <a href='https://en.wikipedia.org/wiki/Mean_kinetic_temperature' target='blank'> Mean Kinetic Temperature: <span style='color:green;'>$finalMKTValue4 &deg;C</span></a>

                    <div id='div'>
                        <img src='../../images/calculation-of-mean-knetic-temperature-MKT.png'>
                    </div>
                </td></tr>
                </tbody></table>
            </td>";
            //// checked condition comming from session or tempselect(from select box)
            if ((isset($arr[0]) && $arr[0] == 4) && (isset($arr[1]) && $arr[1] != "all")) {
                $temphtml .= $temphtml4;
                break;
            }
        case 3:
            $span = isset($span) ? $span : 3;
            $goodcount = $temp3_nonmute_reading - $temp3_bad_reading;
            if ($temp3_nonmute_reading == 0 || $goodcount == 0) {
                $abv_compliance = "Not Applicable";
                $within_compliance = "Not Applicable";
            } else {
                $abv_compliance = round(($temp3_non_comp_count / $goodcount) * 100, 2);
                $compliance_count = $goodcount - $temp3_non_comp_count;
                $within_compliance = round($compliance_count / $goodcount * 100, 2);
            }
            if (isset($compliance_count) && $compliance_count > 0) {
                for ($j = 1; $j < 5; $j++) {
                    $key_start = 'temp3_range' . $j . '_start';
                    $key_end = 'temp3_range' . $j . '_end';
                    $key_color = 'temp3_range' . $j . '_color';
                    if (isset($min_max_temp3['temp_color'][$key_color]) && $min_max_temp3['temp_color'][$key_color] != '') {
                        $temp3_color_details['range' . $j]['start'] = $min_max_temp3['temp_color'][$key_start];
                        $temp3_color_details['range' . $j]['end'] = $min_max_temp3['temp_color'][$key_end];
                        $temp3_color_details['range' . $j]['perc'] = round($temp3_color['range' . $j] / $compliance_count * 100, 2);
                        $temp3_color_details['range' . $j]['color'] = $min_max_temp3['temp_color'][$key_color];
                    }
                }
            }
            $colorTable = '';
            if (!empty($temp3_color_details)) {
                $colorTable .= '<br><table style="width:100%;">';
                foreach ($temp3_color_details AS $colorData) {
                    $colorTable .= '<tr>
                                        <td style="border:1px white solid;background-color:' . $colorData['color'] . ';text-align:center;">' . $colorData['start'] . ' - ' . $colorData['end'] . ' C</td>
                                        <td style="border:1px white solid;text-align:center;">' . $colorData['perc'] . ' %</td>
                                    </tr>';
                }
                $colorTable .= '</table>';
            }
            $temp3_min = ($temp3_min != '' ? $temp3_min . " &deg;C" : "N/A");
            $temp3_max = ($temp3_max != '' ? $temp3_max . " &deg;C" : "N/A");
            $objKelvin3 = new stdClass();
            if (isset($totalKelvinTemperature3)) {
                $objKelvin3->totalKelvinTemperature = isset($totalKelvinTemperature3)?$totalKelvinTemperature3:NULL;
                $objKelvin3->temptotalreadingcount = $temp3totalreadingcount;
                $temp3_average = round(($sumTEMP3 / $temp3totalreadingcount), 2);
                $finalMKTValue3 = round(getMeanKineticTemp($objKelvin3), 2);
            } else {
                $temp3_average = 0;
                $finalMKTValue3 = 0;
            }
            $temphtml3 = "<td style='text-align:center;'>

        <table class='table newTable'><thead><tr><th><u>" . $t3 . "</u></th></tr></thead>
            <tbody>
                <tr><td>Temperature Range :" . $min_max_temp3['temp_min_limit'] . " &deg;C to " . $min_max_temp3['temp_max_limit'] . " &deg;C</td></tr>
                <tr><td>Minimum Temperature: $temp3_min</td></tr>
                <tr><td>Maximum Temperature: $temp3_max</td></tr>
                <tr><td>Average Temperature: $temp3_average &deg;C</td></tr>
                <tr><td>Total Reading: $temp3totalreadingcount</td></tr>
                <tr><td>Muted Reading: $temp3_mute_reading</td></tr>";
            if (isset($_SESSION['customerno']) && $_SESSION['customerno'] != speedConstants::CUSTNO_PHARM_EASY) {
                $temphtml3 .= "<tr><td>Non compliance readings : $temp3_non_comp_count</td></tr>";
            }
            $temphtml3 .= "<tr><td>Bad readings : $temp3_bad_reading</td></tr>";
            if (isset($_SESSION['customerno']) && $_SESSION['customerno'] != speedConstants::CUSTNO_PHARM_EASY) {
                $temphtml3 .= "<tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                                <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span>$colorTable</td></tr>";
            }
            $temphtml3 .= "<td>
                    <a href='https://en.wikipedia.org/wiki/Mean_kinetic_temperature' target='blank'> Mean Kinetic Temperature: <span style='color:green;'>$finalMKTValue3 &deg;C</span></a>

                    <div id='div'>
                        <img src='../../images/calculation-of-mean-knetic-temperature-MKT.png'>
                    </div>
                </td></tr>
                </tbody></table>
            </td>" . $temphtml4;
            //// checked condition comming from session or tempselect(from select box)
            if ((isset($arr[0]) && $arr[0] == 3) && (isset($arr[1]) && $arr[1] != "all")) {
                $temphtml .= $temphtml3;
                break;
            }
        case 2:
            $span = isset($span) ? $span : 2;
            $goodcount = $temp2_nonmute_reading - $temp2_bad_reading;
            if ($temp2_nonmute_reading == 0 || $goodcount == 0) {
                $abv_compliance = "Not Applicable";
                $within_compliance = "Not Applicable";
            } else {
                $abv_compliance = round(($temp2_non_comp_count / $goodcount) * 100, 2);
                $compliance_count = $goodcount - $temp2_non_comp_count;
                $within_compliance = round($compliance_count / $goodcount * 100, 2);
            }
            if (isset($compliance_count) && $compliance_count > 0) {
                for ($j = 1; $j < 5; $j++) {
                    $key_start = 'temp2_range' . $j . '_start';
                    $key_end = 'temp2_range' . $j . '_end';
                    $key_color = 'temp2_range' . $j . '_color';
                    if (isset($min_max_temp2['temp_color'][$key_color]) && $min_max_temp2['temp_color'][$key_color] != '') {
                        $temp2_color_details['range' . $j]['start'] = $min_max_temp2['temp_color'][$key_start];
                        $temp2_color_details['range' . $j]['end'] = $min_max_temp2['temp_color'][$key_end];
                        $temp2_color_details['range' . $j]['perc'] = round($temp2_color['range' . $j] / $compliance_count * 100, 2);
                        $temp2_color_details['range' . $j]['color'] = $min_max_temp2['temp_color'][$key_color];
                    }
                }
            }
            $colorTable = '';
            if (!empty($temp2_color_details)) {
                $colorTable .= '<br><table style="width:100%;">';
                foreach ($temp2_color_details AS $colorData) {
                    $colorTable .= '<tr>
                                        <td style="border:1px white solid;background-color:' . $colorData['color'] . ';text-align:center;">' . $colorData['start'] . ' - ' . $colorData['end'] . ' C</td>
                                        <td style="border:1px white solid;text-align:center;">' . $colorData['perc'] . ' %</td>
                                    </tr>';
                }
                $colorTable .= '</table>';
            }
            $temp2_min = ($temp2_min != '' ? $temp2_min . " &deg;C" : "N/A");
            $temp2_max = ($temp2_max != '' ? $temp2_max . " &deg;C" : "N/A");

            $objKelvin2 = new stdClass();
            if (isset($totalKelvinTemperature2)) {
                $objKelvin2->totalKelvinTemperature = isset($totalKelvinTemperature2)?$totalKelvinTemperature2:NULL;
                $objKelvin2->temptotalreadingcount = $temp2totalreadingcount;
                /*$kelvinNumeratirComplete2 = (speedConstants::DELTA_H / speedConstants::GAS_CONSTANT);
                $kelvinDenominatorComplete2 = log($totalKelvinTemperature2 / $temp2totalreadingcount);
                 */
                $temp2_average = round(($sumTEMP2 / $temp2totalreadingcount), 2);
                $finalMKTValue2 = round(getMeanKineticTemp($objKelvin2), 2);
            } else {
                $temp2_average = 0;
                $finalMKTValue2 = 0;
            }
            $temphtml2 = "<td style='text-align:center;'>

        <table class='table newTable'><thead><tr><th><u>" . $t2 . "</u></th></tr></thead>
            <tbody>
                <tr><td>Temperature Range :" . $min_max_temp2['temp_min_limit'] . " &deg;C to " . $min_max_temp2['temp_max_limit'] . " &deg;C</td></tr>
                <tr><td>Minimum Temperature: $temp2_min</td></tr>
                <tr><td>Maximum Temperature: $temp2_max</td></tr>
                <tr><td>Average Temperature: $temp2_average &deg;C</td></tr>
                <tr><td>Total Reading: $temp2totalreadingcount</td></tr>
                <tr><td>Muted Reading: $temp2_mute_reading</td></tr>";
            if (isset($_SESSION['customerno']) && $_SESSION['customerno'] != speedConstants::CUSTNO_PHARM_EASY) {
                $temphtml2 .= "<tr><td> Non compliance readings : $temp2_non_comp_count</td></tr>";
            }
            $temphtml2 .= "<tr><td>Bad readings : $temp2_bad_reading</td></tr>";

            if (isset($_SESSION['customerno']) && $_SESSION['customerno'] != speedConstants::CUSTNO_PHARM_EASY) {
                $temphtml2 .= "<tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span>$colorTable</td></tr>";
            }
            $temphtml2 .= "<tr><td>
                   <a href='https://en.wikipedia.org/wiki/Mean_kinetic_temperature' target='blank' > Mean Kinetic Temperature: <span style='color:green;'>$finalMKTValue2 &deg;C</span></a>

                    <div id='div'>
                        <img src='../../images/calculation-of-mean-knetic-temperature-MKT.png'>
                    </div>
                </td></tr>
                </tbody></table>
            </td>" . $temphtml3;
            //// checked condition comming from session or tempselect(from select box)
            if ((isset($arr[0]) && $arr[0] == 2) && (isset($arr[1]) && $arr[1] != "all")) {
                $temphtml .= $temphtml2;
                break;
            }
        case 1:
            $span = isset($span) ? $span : 1;
            $goodcount = $temp1_nonmute_reading - $temp1_bad_reading;
            if ($temp1_nonmute_reading == 0 || $goodcount == 0) {
                $abv_compliance = "Not Applicable";
                $within_compliance = "Not Applicable";
            } else {
                $abv_compliance = round(($temp1_non_comp_count / $goodcount) * 100, 2);
                $compliance_count = $goodcount - $temp1_non_comp_count;
                $within_compliance = round($compliance_count / $goodcount * 100, 2);
            }
            if (isset($compliance_count) && $compliance_count > 0) {
                for ($j = 1; $j < 5; $j++) {
                    $key_start = 'temp1_range' . $j . '_start';
                    $key_end = 'temp1_range' . $j . '_end';
                    $key_color = 'temp1_range' . $j . '_color';
                    if (isset($min_max_temp1['temp_color'][$key_color]) && $min_max_temp1['temp_color'][$key_color] != '') {
                        $temp1_color_details['range' . $j]['start'] = $min_max_temp1['temp_color'][$key_start];
                        $temp1_color_details['range' . $j]['end'] = $min_max_temp1['temp_color'][$key_end];
                        $temp1_color_details['range' . $j]['perc'] = round($temp1_color['range' . $j] / $compliance_count * 100, 2);
                        $temp1_color_details['range' . $j]['color'] = $min_max_temp1['temp_color'][$key_color];
                    }
                }
            }
            $colorTable = '';
            if (!empty($temp1_color_details)) {
                $colorTable .= '<br><table style="width:100%;">';
                foreach ($temp1_color_details AS $colorData) {
                    $colorTable .= '<tr>
                                        <td style="border:1px white solid;background-color:' . $colorData['color'] . ';text-align:center;">' . $colorData['start'] . ' - ' . $colorData['end'] . ' C</td>
                                        <td style="border:1px white solid;text-align:center;">' . $colorData['perc'] . ' %</td>
                                    </tr>
                                    ';
                }
                $colorTable .= '</table>';
            }
            $temp1_min = ($temp1_min != '' ? $temp1_min . " &deg;C" : "N/A");
            $temp1_max = ($temp1_max != '' ? $temp1_max . " &deg;C" : "N/A");

            $objKelvin1 = new stdClass();
            $objKelvin1->totalKelvinTemperature = !empty($totalKelvinTemperature1) ? $totalKelvinTemperature1 : NULL;
            $objKelvin1->temptotalreadingcount = $temp1totalreadingcount;
            if ($temp1totalreadingcount > 0) {
                $temp1_average = (round(($sumTEMP1 / $temp1totalreadingcount), 2)).' &deg;C';
            } else {
                $temp1_average = 'NA';
            }
            $finalMKTValue1 = round(getMeanKineticTemp($objKelvin1), 2);
            $temphtml = "<td style='text-align:center;'>
        <table class='table newTable'><thead>
            <tr><th><u>" . $t1 . "</u></th></tr></thead>
            <tbody>
                <tr><td>Temperature Range :" . $min_max_temp1['temp_min_limit'] . " &deg;C to " . $min_max_temp1['temp_max_limit'] . " &deg;C</td></tr>
                <tr><td>Minimum Temperature: $temp1_min</td></tr>
                <tr><td>Maximum Temperature: $temp1_max</td></tr>
                <tr><td>Average Temperature: $temp1_average</td></tr>
                <tr><td>Total Reading: $temp1totalreadingcount</td></tr>
                <tr><td>Muted Reading: $temp1_mute_reading</td></tr>";
            if (isset($_SESSION['customerno']) && $_SESSION['customerno'] != speedConstants::CUSTNO_PHARM_EASY) {
                $temphtml .= "<tr><td>Non compliance readings: $temp1_non_comp_count</td></tr>";
            }
            $temphtml .= "<tr><td>Bad readings : $temp1_bad_reading</td></tr>";
            if (isset($_SESSION['customerno']) && $_SESSION['customerno'] != speedConstants::CUSTNO_PHARM_EASY) {
                $temphtml .= "<tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span>$colorTable</td></tr>";
            }
            $temphtml .= "<tr><td>
                    <a href='https://en.wikipedia.org/wiki/Mean_kinetic_temperature' target='blank'> Mean Kinetic Temperature: <span style='color:green;'>$finalMKTValue1 &deg;C</span></a>

                    <div id='div'>
                        <img src='../../images/calculation-of-mean-knetic-temperature-MKT.png'>
                    </div>

                </td></tr>
                </tbody></table>
            </td>" . $temphtml2;
    }
    $summary = "<table class='table newTable'>
                    <thead>
                        <tr><th colspan=$span>Statistics</th></tr>
                    </thead>
                    <tbody>
                        <tr> $temphtml</tr>
                    </tbody>
                </table>";
    $display .= "$summary</div>";
    return $display;
}

function gettempreportpdf($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $interval, $stime, $etime, $switchto, $vgroupname = null, $reporttype = null, $tempselect = null, $arrRequest = null) {
    if (!$tempselect) {
        $tempselect = "null";
    }

    //echo "temp is equal to - ".$tempselect; die;
    $totaldays = gendays($STdate, $EDdate);

    $finalreport = '';
    $days = array();
    $unit = getunitdetailspdf($customerno, $deviceid);

    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $STdate = date("Y-m-d", strtotime($STdate));
            $f_STdate = $userdate . " 00:00:00";
            if ($userdate == $STdate) {
                $f_STdate = $userdate . " " . $stime . ":00";
            }
            $EDdate = date("Y-m-d", strtotime($EDdate));
            $f_EDdate = $userdate . " 23:59:59";
            if ($userdate == $EDdate) {
                $f_EDdate = $userdate . " " . $etime . ":00";
            }
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = gettempdata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, false, null, $customerno);

                $vehicleid = $data['vehicleid'];
            }
            if ($data[0] != null && count($data[0]) > 1) {
                $days = array_merge($days, $data[0]);
            }
        }
    }

    if ($days != null && count($days) > 0) {
        $cm = new CustomerManager($customerno);
        $customer_details = $cm->getcustomerdetail_byid($customerno);

        $fromdate = date('d-m-Y H:i', strtotime($STdate . ' ' . $stime));
        $todate = date('d-m-Y H:i', strtotime($EDdate . ' ' . $etime));
        $title = 'Temperature Report';

        if ($switchto == 3) {
            if (isset($_SESSION['Warehouse'])) {
                $veh = $_SESSION['Warehouse'];
            } else {
                $veh = "Warehouse";
            }
            $subTitle = array(
                "$veh: $vehicleno",
                "Start Date: $fromdate",
                "End Date: $todate",
                "Interval: $interval mins"
            );
        } else {
            $subTitle = array(
                "Vehicle No: $vehicleno",
                "Start Date: $fromdate",
                "End Date: $todate",
                "Interval: $interval mins"
            );
        }
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        if ($reporttype == "pdf") {
            $finalreport = pdf_header($title, $subTitle, $customer_details);
        }
        if ($reporttype == "xls") {
            $finalreport = excel_header($title, $subTitle, $customer_details);
        }
        $finalreport .= "<hr /><br/><br/>
        <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
        <tbody>";
        $veh_temp_details = '';

        if (isset($vehicleid)) {
            $veh_temp_details = getunitdetailsfromvehid($vehicleid, $customerno);
            $veh_temp_details->use_humidity = $customer_details->use_humidity;
        }

        $finalreport .= create_temppdf_from_report($days, $unit, $customerno, $veh_temp_details, $switchto, $tempselect, $arrRequest);

        // $finalreport .= create_temppdf_from_report($days, $unit, $customerno, $veh_temp_details, $switchto, $tempselect);
    } else {
        if (isset($_REQUEST['vehicleid'])) {
            $finalreport = "Data Not Available";
        }
    }
    return $finalreport;
}

function create_temppdf_from_report($datarows, $vehicle, $custID = null, $veh_temp_details = null, $switchto = null, $tempselect = null, $arrRequest = null) {
    $i = 1;
    $tr = 0;
    $totalminute = 0;
    $lastdate = null;
    $display = '';
    $temp1_non_comp_count = 0;
    $temp2_non_comp_count = 0;
    $temp3_non_comp_count = 0;
    $temp4_non_comp_count = 0;
    $temp1_bad_reading = 0;
    $temp2_bad_reading = 0;
    $temp3_bad_reading = 0;
    $temp4_bad_reading = 0;
    $temp1_mute_reading = 0;
    $temp2_mute_reading = 0;
    $temp3_mute_reading = 0;
    $temp4_mute_reading = 0;
    $temp1_nonmute_reading = 0;
    $temp2_nonmute_reading = 0;
    $temp3_nonmute_reading = 0;
    $temp4_nonmute_reading = 0;
    $temp1_data = '';
    $temp2_data = '';
    $temp3_data = '';
    $temp4_data = '';
    $restemp1 = array(0);
    $restemp2 = array(0);
    $restemp3 = array(0);
    $restemp4 = array(0);
    $temp1_min = '';
    $temp2_min = '';
    $temp3_min = '';
    $temp4_min = '';
    $temp1_max = '';
    $temp2_max = '';
    $temp3_max = '';
    $temp4_max = '';
    //        $min_max_temp1 = get_min_max_temp(1, $veh_temp_details);
    //        $min_max_temp2 = get_min_max_temp(2, $veh_temp_details);
    //        $min_max_temp3 = get_min_max_temp(3, $veh_temp_details);
    //        $min_max_temp4 = get_min_max_temp(4, $veh_temp_details);
    $temp1counter = 0;
    $temp2counter = 0;
    $temp3counter = 0;
    $temp4counter = 0;
    $temp1totalreadingcount = 0;
    $temp2totalreadingcount = 0;
    $temp3totalreadingcount = 0;
    $temp4totalreadingcount = 0;
    $kelvinDenominator4 = 0;
    $kelvinDenominator3 = 0;
    $kelvinDenominator2 = 0;
    $kelvinDenominator1 = 0;
    $temp1_color = array('range1' => 0, 'range2' => 0, 'range3' => 0, 'range4' => 0);
    $temp2_color = array('range1' => 0, 'range2' => 0, 'range3' => 0, 'range4' => 0);
    $temp3_color = array('range1' => 0, 'range2' => 0, 'range3' => 0, 'range4' => 0);
    $temp4_color = array('range1' => 0, 'range2' => 0, 'range3' => 0, 'range4' => 0);
    $mutedetails = getunitmutedetails($vehicle->vehicleid, $vehicle->uid, $custID);
    if (isset($datarows)) {
        $customerno = ($custID == null) ? $_SESSION['customerno'] : $custID;
        $cm = new CustomerManager(null);
        $cm_details = $cm->getcustomerdetail_byid($customerno);
        if (isset($arrRequest) && isset($arrRequest['customMinTemp']) && isset($arrRequest['customMaxTemp']) && $arrRequest['customMinTemp'] != '' && $arrRequest['customMaxTemp'] != '' && $arrRequest['customMinTemp'] != -1 && $arrRequest['customMaxTemp'] != -1) {
            $min_max_temp1 = getCustomTempRange(1, $arrRequest['customMinTemp'], $arrRequest['customMaxTemp']);
            $min_max_temp2 = getCustomTempRange(2, $arrRequest['customMinTemp'], $arrRequest['customMaxTemp']);
            $min_max_temp3 = getCustomTempRange(3, $arrRequest['customMinTemp'], $arrRequest['customMaxTemp']);
            $min_max_temp4 = getCustomTempRange(4, $arrRequest['customMinTemp'], $arrRequest['customMaxTemp']);
        } else {
            $min_max_temp1 = get_min_max_temp(1, $veh_temp_details, $cm_details->temp_sensors);
            $min_max_temp2 = get_min_max_temp(2, $veh_temp_details, $cm_details->temp_sensors);
            $min_max_temp3 = get_min_max_temp(3, $veh_temp_details, $cm_details->temp_sensors);
            $min_max_temp4 = get_min_max_temp(4, $veh_temp_details, $cm_details->temp_sensors);
        }

        $t1 = getName_ByType($vehicle->n1);
        $t2 = getName_ByType($vehicle->n2);
        $t3 = getName_ByType($vehicle->n3);
        $t4 = getName_ByType($vehicle->n4);
        $t1 = ($t1 == '') ? 'Temperature 1' : $t1;
        $t2 = ($t2 == '') ? 'Temperature 2' : $t2;
        $t3 = ($t3 == '') ? 'Temperature 3' : $t3;
        $t4 = ($t4 == '') ? 'Temperature 4' : $t4;

        $tempselectArr = explode("-", $tempselect);

        $sumTEMP1 = 0;
        $sumTEMP2 = 0;
        $sumTEMP3 = 0;
        $sumTEMP4 = 0;

        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $display .= "
                    <tr style='background-color:#CCCCCC;font-weight:bold;'>
                    <td style='width:150px;height:auto;'>Time</td>";
                if ($switchto != 3) {
                    $display .= "<td style='width:250px;height:auto;'>Location</td>";
                }

                if ($cm_details->temp_sensors == 4) {
                    if ($tempselectArr[0] == "1" && $tempselectArr[1] == "0") {
                        $display .= "<td style='width:150px;height:auto;'>" . $t1 . "</td>";
                    } elseif ($tempselectArr[0] == "2" && $tempselectArr[1] == "0") {
                        $display .= "<td style='width:150px;height:auto;'>" . $t2 . "</td>";
                    } elseif ($tempselectArr[0] == "3" && $tempselectArr[1] == "0") {
                        $display .= "<td style='width:150px;height:auto;'>" . $t3 . "</td>";
                    } elseif ($tempselectArr[0] == "4" && $tempselectArr[1] == "0") {
                        $display .= "<td style='width:150px;height:auto;'>" . $t4 . "</td>";
                    } else {
                        $display .= "<td style='width:150px;height:auto;'>" . $t1 . "</td>
                                <td style='width:150px;height:auto;'>" . $t2 . "</td><td style='width:150px;height:auto;'>" . $t3 . "</td><td style='width:150px;height:auto;'>" . $t4 . "</td>";
                    }
                } elseif ($cm_details->temp_sensors == 3) {
                    if ($tempselectArr[0] == "1" && $tempselectArr[1] == "0") {
                        $display .= "<td style='width:150px;height:auto;'>" . $t1 . "</td>";
                    } elseif ($tempselectArr[0] == "2" && $tempselectArr[1] == "0") {
                        $display .= " <td style='width:150px;height:auto;'>" . $t2 . "</td>";
                    } elseif ($tempselectArr[0] == "3" && $tempselectArr[1] == "0") {
                        $display .= "<td style='width:150px;height:auto;'>" . $t3 . "</td>";
                    } else {
                        $display .= "<td style='width:150px;height:auto;'>" . $t1 . "</td><td style='width:150px;height:auto;'>" . $t2 . "</td><td style='width:150px;height:auto;'>" . $t3 . "</td>";
                    }
                } elseif ($cm_details->temp_sensors == 2) {
                    if ($tempselectArr[0] == "1" && $tempselectArr[1] == "0") {
                        $display .= "<td style='width:150px;height:auto;'>" . $t1 . "</td>";
                    } elseif ($tempselectArr[0] == "2" && $tempselectArr[1] == "0") {
                        $display .= "<td style='width:150px;height:auto;'>" . $t2 . "</td>";
                    } else {
                        $display .= "<td style='width:150px;height:auto;'>" . $t1 . "</td><td style='width:150px;height:auto;'>" . $t2 . "</td>";
                    }
                } elseif ($cm_details->temp_sensors == 1) {
                    if ($tempselectArr[0] == "1") {
                        $display .= "<td style='width:150px;height:auto;'>" . $t1 . "</td>";
                    }
                }
                /* if (isset($tempselectArr) && $tempselectArr[0] != "null") {
                if ($cm_details->temp_sensors == 2 && $tempselectArr[0] == 1) {
                $display .= "<td style='width:150px;height:auto;'>Temperature 1</td>";
                }

                else if ($cm_details->temp_sensors == 2 && $tempselectArr[0] == 2 && $tempselectArr[1] == "0") {
                $display .= "<td style='width:150px;height:auto;'>Temperature 2</td>";
                }

                else if ($cm_details->temp_sensors == 2 && $tempselectArr[0] == 2 && (isset($tempselectArr[1]) && $tempselectArr[1] == "all")) {
                $display .= "<td style='width:150px;height:auto;'>Temperature 1</td>
                <td style='width:150px;height:auto;'>Temperature 2</td>";
                }
                } else {
                if ($cm_details->temp_sensors == 2) {
                $display .= "<td style='width:150px;height:auto;'>Temperature 1</td>
                <td style='width:150px;height:auto;'>Temperature 2</td>";
                } elseif ($cm_details->temp_sensors == 1) {
                $display .= "<td style='width:150px;height:auto;'>Temperature</td>";
                }
                }
                 */

                /*if ($cm_details->temp_sensors == 2) {
                $display .= "<td style='width:150px;height:auto;'>Temperature 1</td>
                <td style='width:150px;height:auto;'>Temperature 2</td>";

                }

                elseif ($cm_details->temp_sensors == 1) {
                $display .= "<td style='width:150px;height:auto;'>Temperature</td>";
                 */
                $display .= "</tr>";

                if ($cm_details->temp_sensors == 4) {
                    if ($tempselectArr[0] == "1" && $tempselectArr[1] == "0") {
                        $colspan = 2;
                    } elseif ($tempselectArr[0] == "2" && $tempselectArr[1] == "0") {
                        $colspan = 2;
                    } elseif ($tempselectArr[0] == "3" && $tempselectArr[1] == "0") {
                        $colspan = 2;
                    } elseif ($tempselectArr[0] == "4" && $tempselectArr[1] == "0") {
                        $colspan = 2;
                    } else {
                        $colspan = 6;
                    }
                    $display .= "<tr><td colspan='" . $colspan . "' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                } elseif ($cm_details->temp_sensors == 3) {
                    if ($tempselectArr[0] == "1" && $tempselectArr[1] == "0") {
                        $colspan = 3;
                    } elseif ($tempselectArr[0] == "2" && $tempselectArr[1] == "0") {
                        $colspan = 4;
                    } elseif ($tempselectArr[0] == "3" && $tempselectArr[1] == "0") {
                        $colspan = 5;
                    } else {
                        $colspan = 6;
                    }
                    $display .= "<tr><td colspan='" . $colspan . "' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                } elseif ($cm_details->temp_sensors == 2) {
                    if ($tempselectArr[0] == "1" && $tempselectArr[1] == "0") {
                        $colspan = 3;
                    } elseif ($tempselectArr[0] == "2" && $tempselectArr[1] == "0") {
                        $colspan = 4;
                    } else {
                        $colspan = 6;
                    }
                    $display .= "<tr><td colspan='" . $colspan . "' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                } elseif ($cm_details->temp_sensors == 1) {
                    $display .= "<tr><td colspan='3' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                }
                $i++;
            }
            //Removing Date Details From DateTimespeedConstants::DEFAULT_TIME
            $change->lastupdated = $change->starttime;
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            if ($customerno == 116) {
                $display .= "<tr><td style='width:150px;height:auto;'>" . date(speedConstants::DEFAULT_DATETIME, strtotime($change->lastupdated)) . "</td>";
            } else {
                $display .= "<tr><td style='width:150px;height:auto;'>" . date(speedConstants::DEFAULT_TIME, strtotime($change->starttime)) . "</td>";
            }
            if ($change->devicelat != '' && $change->devicelong != '') {
                $location = get_location_detail($change->devicelat, $change->devicelong, $custID);
            }
            if ($switchto != 3) {
                $display .= "<td style='width:250px;height:auto;'>$location</td>";
            }
            // Temperature Sensors
            // Temperature Sensor
            $temp1 = $temp2 = $temp3 = $temp4 = speedConstants::TEMP_WARNING;
            $warning = '-';
            $tdstring = '';
            $objTemp = new TempConversion();
            $objTemp->unit_type = $veh_temp_details->get_conversion;
            $objTemp->use_humidity = $veh_temp_details->use_humidity;
            $objTemp->switch_to = $switchto;
            // $pass = (isset($arr[0]) ? $arr[0] : $_SESSION['temp_sensors']);
            $pass = ((isset($tempselectArr[0]) && $tempselectArr[0] != "null") ? $tempselectArr[0] : $cm_details->temp_sensors);
            // echo "switch value - ".$pass; die;
            //$pass =  $cm_details->temp_sensors;
            //switch ($cm_details->temp_sensors) {
            switch ($pass) {
                case 4:
                    if ($vehicle->tempsen4 != 0) {
                        $isTemperatureMuted = checkTemperatureMute($mutedetails, 4, $change->lastupdated);
                        if ($isTemperatureMuted) {
                            $temp4 = 'Muted';
                            $temp4_mute_reading++;
                        } else {
                            $temp4_nonmute_reading++;
                            $s4 = "analog" . $vehicle->tempsen4;
                            if ($change->$s4 != 0) {
                                $objTemp->rawtemp = $change->$s4;
                                $temp4 = getTempUtil($objTemp);
                                $sumTEMP4 += $temp4;
                                /*Kelvin Calculation*/
                                $kelvinTemp4 = convertDegreeToKelvin($temp4); // Step 1
                                $kelvinDenominator4 = $kelvinDenominator4 + exp((-(speedConstants::DELTA_H) / (speedConstants::GAS_CONSTANT * $kelvinTemp4))); // Step 2

                                $totalKelvinTemperature4 = $kelvinDenominator4;
                                /*Kelvin Calculation*/

                                if ($temp4 == 0 || $temp4 > NORMAL_MAX_TEMP || $temp4 < NORMAL_MIN_TEMP) {
                                    if ($temp4 == 0) {
                                        $temp4 = 'Wirecut';
                                    } else {
                                        $temp4 = 'Bad Data';
                                    }
                                    $temp4_bad_reading++;
                                } elseif ($temp4 != 0) {
                                    $temp4_min = set_summary_min_temp4($temp4);
                                    $temp4_max = set_summary_max_temp4($temp4);
                                    if ($temp4 > $min_max_temp4['temp_max_limit'] || $temp4 < $min_max_temp4['temp_min_limit']) {
                                        $temp4_non_comp_count++;
                                    } else {
                                        if (!empty($min_max_temp4['temp_color'])) {
                                            for ($j = 1; $j < 5; $j++) {
                                                $key_start = 'temp4_range' . $j . '_start';
                                                $key_end = 'temp4_range' . $j . '_end';
                                                $key_color = 'temp4_range' . $j . '_color';
                                                if (($min_max_temp4['temp_color'][$key_start] != '') && ($temp4 > $min_max_temp4['temp_color'][$key_start] && $temp4 < $min_max_temp4['temp_color'][$key_end])) {
                                                    $temp4_color['range' . $j]++;
                                                }
                                            }
                                        }
                                    }
                                    $temp4 = $temp4 . speedConstants::TEMP_DEGREE;
                                }
                            } else {
                                $temp4_bad_reading++;
                            }
                        }
                    } else {
                        $temp4 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $tdstring = "<td>" . $temp4 . "</td>" . $tdstring;
                    if ((isset($tempselectArr[0]) && $tempselectArr[0] == 4) && (isset($tempselectArr[1]) && $tempselectArr[1] != "all")) {
                        break;
                    }

                case 3:
                    if ($vehicle->tempsen3 != 0) {
                        $isTemperatureMuted = checkTemperatureMute($mutedetails, 3, $change->lastupdated);
                        if ($isTemperatureMuted) {
                            $temp3 = 'Muted';
                            $temp3_mute_reading++;
                        } else {
                            $temp3_nonmute_reading++;
                            $s3 = "analog" . $vehicle->tempsen3;
                            if ($change->$s3 != 0) {
                                $objTemp->rawtemp = $change->$s3;
                                $temp3 = getTempUtil($objTemp);
                                $sumTEMP3 += $temp3;
                                /*Kelvin Calculation*/
                                $kelvinTemp3 = convertDegreeToKelvin($temp3); // Step 1
                                $kelvinDenominator3 = $kelvinDenominator3 + exp((-(speedConstants::DELTA_H) / (speedConstants::GAS_CONSTANT * $kelvinTemp3))); // Step 2

                                $totalKelvinTemperature3 = $kelvinDenominator3;
                                /*Kelvin Calculation*/
                                if ($temp3 == 0 || $temp3 > NORMAL_MAX_TEMP || $temp3 < NORMAL_MIN_TEMP) {
                                    if ($temp3 == 0) {
                                        $temp3 = 'Wirecut';
                                    } else {
                                        $temp3 = 'Bad Data';
                                    }
                                    $temp3_bad_reading++;
                                } elseif ($temp3 != 0) {
                                    $temp3_min = set_summary_min_temp3($temp3);
                                    $temp3_max = set_summary_max_temp3($temp3);
                                    if ($temp3 > $min_max_temp3['temp_max_limit'] || $temp3 < $min_max_temp3['temp_min_limit']) {
                                        $temp3_non_comp_count++;
                                    } else {
                                        if (!empty($min_max_temp3['temp_color'])) {
                                            for ($j = 1; $j < 5; $j++) {
                                                $key_start = 'temp3_range' . $j . '_start';
                                                $key_end = 'temp3_range' . $j . '_end';
                                                $key_color = 'temp3_range' . $j . '_color';
                                                if (($min_max_temp3['temp_color'][$key_start] != '') && ($temp3 > $min_max_temp3['temp_color'][$key_start] && $temp3 < $min_max_temp3['temp_color'][$key_end])) {
                                                    $temp3_color['range' . $j]++;
                                                }
                                            }
                                        }
                                    }
                                    $temp3 = $temp3 . speedConstants::TEMP_DEGREE;
                                }
                            } else {
                                $temp3_bad_reading++;
                            }
                        }
                    } else {
                        $temp3 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $tdstring = "<td>" . $temp3 . "</td>" . $tdstring;
                    if ((isset($tempselectArr[0]) && $tempselectArr[0] == 3) && (isset($tempselectArr[1]) && $tempselectArr[1] != "all")) {
                        break;
                    }

                case 2:
                    if ($vehicle->tempsen2 != 0) {
                        $isTemperatureMuted = checkTemperatureMute($mutedetails, 2, $change->lastupdated);
                        if ($isTemperatureMuted) {
                            $temp2 = 'Muted';
                            $temp2_mute_reading++;
                        } else {
                            $temp2_nonmute_reading++;
                            $s2 = "analog" . $vehicle->tempsen2;
                            if ($change->$s2 != 0) {
                                $objTemp->rawtemp = $change->$s2;
                                $temp2 = getTempUtil($objTemp);
                                $sumTEMP2 += $temp2;
                                /*Kelvin Calculation*/
                                $kelvinTemp2 = convertDegreeToKelvin($temp2); // Step 1
                                $kelvinDenominator2 = $kelvinDenominator2 + exp((-(speedConstants::DELTA_H) / (speedConstants::GAS_CONSTANT * $kelvinTemp2))); // Step 2

                                $totalKelvinTemperature2 = $kelvinDenominator2;
                                /*Kelvin Calculation*/
                                if ($temp2 == 0 || $temp2 > NORMAL_MAX_TEMP || $temp2 < NORMAL_MIN_TEMP) {
                                    if ($temp2 == 0) {
                                        $temp2 = 'Wirecut';
                                    } else {
                                        $temp2 = 'Bad Data';
                                    }
                                    $temp2_bad_reading++;
                                } elseif ($temp2 != 0) {
                                    $temp2_min = set_summary_min_temp2($temp2);
                                    $temp2_max = set_summary_max_temp2($temp2);
                                    if ($temp2 > $min_max_temp2['temp_max_limit'] || $temp2 < $min_max_temp2['temp_min_limit']) {
                                        $temp2_non_comp_count++;
                                    } else {
                                        if (!empty($min_max_temp2['temp_color'])) {
                                            for ($j = 1; $j < 5; $j++) {
                                                $key_start = 'temp2_range' . $j . '_start';
                                                $key_end = 'temp2_range' . $j . '_end';
                                                $key_color = 'temp2_range' . $j . '_color';
                                                if (($min_max_temp2['temp_color'][$key_start] != '') && ($temp2 > $min_max_temp2['temp_color'][$key_start] && $temp2 < $min_max_temp2['temp_color'][$key_end])) {
                                                    $temp2_color['range' . $j]++;
                                                }
                                            }
                                        }
                                    }
                                    $temp2 = $temp2 . speedConstants::TEMP_DEGREE;
                                }
                            } else {
                                $temp2_bad_reading++;
                            }
                        }
                    } else {
                        $temp2 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $tdstring = "<td>" . $temp2 . "</td>" . $tdstring;
                    if (isset($tempselectArr[0]) && $tempselectArr[0] != "null") {
                        if ((isset($tempselectArr[0]) && $tempselectArr[0] == 2) && (isset($tempselectArr[1]) && $tempselectArr[1] != "all")) {
                            break;
                        }
                    }
                    if ((isset($tempselectArr[0]) && $tempselectArr[0] == 2) && (isset($tempselectArr[1]) && $tempselectArr[1] != "all")) {
                        break;
                    }

                case 1:
                    if ($vehicle->tempsen1 != 0) {
                        $isTemperatureMuted = checkTemperatureMute($mutedetails, 1, $change->lastupdated);
                        if ($isTemperatureMuted) {
                            $temp1 = 'Muted';
                            $temp1_mute_reading++;
                        } else {
                            $temp1_nonmute_reading++;
                            $s1 = "analog" . $vehicle->tempsen1;
                            if ($change->$s1 != 0) {
                                $objTemp->rawtemp = $change->$s1;
                                $temp1 = getTempUtil($objTemp);
                                $sumTEMP1 += $temp1;
                                /*Kelvin Calculation*/
                                $kelvinTemp1 = convertDegreeToKelvin($temp1); // Step 1
                                $kelvinDenominator1 = $kelvinDenominator1 + exp((-(speedConstants::DELTA_H) / (speedConstants::GAS_CONSTANT * $kelvinTemp1))); // Step 2

                                $totalKelvinTemperature1 = $kelvinDenominator1;
                                /*Kelvin Calculation*/
                                if ($temp1 == 0 || $temp1 > NORMAL_MAX_TEMP || $temp1 < NORMAL_MIN_TEMP) {
                                    if ($temp1 == 0) {
                                        $temp1 = 'Wirecut';
                                    } else {
                                        $temp1 = 'Bad Data';
                                    }
                                    $temp1_bad_reading++;
                                } elseif ($temp1 != 0) {
                                    $temp1_min = set_summary_min_temp($temp1);
                                    $temp1_max = set_summary_max_temp($temp1);
                                    if ($temp1 > $min_max_temp1['temp_max_limit'] || $temp1 < $min_max_temp1['temp_min_limit']) {
                                        $temp1_non_comp_count++;
                                    } else {
                                        if (!empty($min_max_temp1['temp_color'])) {
                                            for ($j = 1; $j < 5; $j++) {
                                                $key_start = 'temp1_range' . $j . '_start';
                                                $key_end = 'temp1_range' . $j . '_end';
                                                $key_color = 'temp1_range' . $j . '_color';
                                                if (($min_max_temp1['temp_color'][$key_start] != '') && ($temp1 > $min_max_temp1['temp_color'][$key_start] && $temp1 < $min_max_temp1['temp_color'][$key_end])) {
                                                    $temp1_color['range' . $j]++;
                                                }
                                            }
                                        }
                                    }
                                    $temp1 = $temp1 . speedConstants::TEMP_DEGREE;
                                }
                            } else {
                                $temp1_bad_reading++;
                            }
                        }
                    } else {
                        $temp1 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $tdstring = "<td>" . $temp1 . "</td>" . $tdstring;
                    if ((isset($tempselectArr[0]) && $tempselectArr[0] == 1) && (isset($tempselectArr[1]) && $tempselectArr[1] != "all")) {
                        break;
                    }
            }

            $display .= $tdstring;
            $display .= '</tr>';
            $tr++;
        }
    }
    $display .= '</tbody>';
    $display .= '</table>';
    $temphtml = '';
    $temphtml2 = '';
    $temphtml3 = '';
    $temphtml4 = '';
    $span = null;

    switch ($pass) {
        //switch ($cm_details->temp_sensors) {
        case 4:
            $span = isset($span) ? $span : 4;
            $goodcount = $temp4_nonmute_reading - $temp4_bad_reading;
            if ($temp4_nonmute_reading == 0 || $goodcount == 0) {
                $abv_compliance = "Not Applicable";
                $within_compliance = "Not Applicable";
            } else {
                $abv_compliance = round(($temp4_non_comp_count / $goodcount) * 100, 2);
                $compliance_count = $goodcount - $temp4_non_comp_count;
                $within_compliance = round($compliance_count / $goodcount * 100, 2);
            }
            if (isset($compliance_count) && $compliance_count > 0) {
                for ($j = 1; $j < 5; $j++) {
                    $key_start = 'temp4_range' . $j . '_start';
                    $key_end = 'temp4_range' . $j . '_end';
                    $key_color = 'temp4_range' . $j . '_color';
                    if (isset($min_max_temp4['temp_color'][$key_color]) && $min_max_temp4['temp_color'][$key_color] != '') {
                        $temp4_color_details['range' . $j]['start'] = $min_max_temp4['temp_color'][$key_start];
                        $temp4_color_details['range' . $j]['end'] = $min_max_temp4['temp_color'][$key_end];
                        $temp4_color_details['range' . $j]['perc'] = round($temp4_color['range' . $j] / $compliance_count * 100, 2);
                        $temp4_color_details['range' . $j]['color'] = $min_max_temp4['temp_color'][$key_color];
                    }
                }
            }
            $colorTable = '';
            if (!empty($temp4_color_details)) {
                $colorTable .= '<br><table style="width:100%;">';
                foreach ($temp4_color_details AS $colorData) {
                    $colorTable .= '<tr>
                                        <td style="border:1px white solid;background-color:' . $colorData['color'] . ';text-align:center;">' . $colorData['start'] . ' - ' . $colorData['end'] . ' C</td>
                                        <td style="border:1px white solid;text-align:center;">' . $colorData['perc'] . ' %</td>
                                    </tr>';
                }
                $colorTable .= '</table>';
            }
            $temp4_min = ($temp4_min != '' ? $temp4_min . " &deg;C" : "N/A");
            $temp4_max = ($temp4_max != '' ? $temp4_max . " &deg;C" : "N/A");

            $objKelvin4 = new stdClass();
            if (isset($totalKelvinTemperature4)) {
                $objKelvin4->totalKelvinTemperature = isset($totalKelvinTemperature4)?$totalKelvinTemperature4:NULL;
                $objKelvin4->temptotalreadingcount = $tr;
                $temp4_average = round(($sumTEMP4 / $tr), 2);
                $finalMKTValue4 = round(getMeanKineticTemp($objKelvin4), 2);
            } else {
                $temp4_average = '0';
                $finalMKTValue4 = '0';
            }
            $temphtml4 = "
            <td style='text-align:center;'>
            <table class='table newTable'><thead><tr><th><u>" . $t4 . "</u></th></tr></thead>
                <tbody>
                    <tr><td>Temprature Range :" . $min_max_temp4['temp_min_limit'] . " &deg;C to " . $min_max_temp4['temp_max_limit'] . " &deg;C</td></tr>
                    <tr><td>Minimum Temperature: $temp4_min &deg;C</td></tr>
                    <tr><td>Maximum Temperature: $temp4_max &deg;C</td></tr>
                    <tr><td>Average Temperature: $temp4_average &deg;C</td></tr>
                    <tr><td>Total Reading: $tr</td></tr>";
            if (isset($_SESSION['customerno']) && $_SESSION['customerno'] != speedConstants::CUSTNO_PHARM_EASY) {
                $temphtml4 .= "<tr><td>Non compliance readings : $temp4_non_comp_count</td></tr>";
            }

            $temphtml4 .= "<tr><td>Bad readings : $temp4_bad_reading</td></tr>";
            if (isset($_SESSION['customerno']) && $_SESSION['customerno'] != speedConstants::CUSTNO_PHARM_EASY) {
                $temphtml4 .= "<tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                        <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span>$colorTable</td></tr>";
            }
            $temphtml4 .= "<tr>
                        <td>
                            <a href='https://en.wikipedia.org/wiki/Mean_kinetic_temperature' target='blank'> Mean Kinetic Temperature: <span style='color:green;'>$finalMKTValue4 &deg;C</span></a>


                        </td>
                    </tr>
                    </tbody></table>
                </td>";
            if ((isset($tempselectArr[0]) && $tempselectArr[0] == 4) && (isset($tempselectArr[1]) && $tempselectArr[1] != "all")) {
                $temphtml .= $temphtml4;
                break;
            }
        case 3:
            $span = isset($span) ? $span : 3;
            $goodcount = $temp3_nonmute_reading - $temp3_bad_reading;
            if ($temp3_nonmute_reading == 0 || $goodcount == 0) {
                $abv_compliance = "Not Applicable";
                $within_compliance = "Not Applicable";
            } else {
                $abv_compliance = round(($temp3_non_comp_count / $goodcount) * 100, 2);
                $compliance_count = $goodcount - $temp3_non_comp_count;
                $within_compliance = round($compliance_count / $goodcount * 100, 2);
            }
            if (isset($compliance_count) && $compliance_count > 0) {
                for ($j = 1; $j < 5; $j++) {
                    $key_start = 'temp3_range' . $j . '_start';
                    $key_end = 'temp3_range' . $j . '_end';
                    $key_color = 'temp3_range' . $j . '_color';
                    if (isset($min_max_temp3['temp_color'][$key_color]) && $min_max_temp3['temp_color'][$key_color] != '') {
                        $temp3_color_details['range' . $j]['start'] = $min_max_temp3['temp_color'][$key_start];
                        $temp3_color_details['range' . $j]['end'] = $min_max_temp3['temp_color'][$key_end];
                        $temp3_color_details['range' . $j]['perc'] = round($temp3_color['range' . $j] / $compliance_count * 100, 2);
                        $temp3_color_details['range' . $j]['color'] = $min_max_temp3['temp_color'][$key_color];
                    }
                }
            }
            $colorTable = '';
            if (!empty($temp3_color_details)) {
                $colorTable .= '<br><table style="width:100%;">';
                foreach ($temp3_color_details AS $colorData) {
                    $colorTable .= '<tr>
                                        <td style="border:1px white solid;background-color:' . $colorData['color'] . ';text-align:center;">' . $colorData['start'] . ' - ' . $colorData['end'] . ' C</td>
                                        <td style="border:1px white solid;text-align:center;">' . $colorData['perc'] . ' %</td>
                                    </tr>';
                }
                $colorTable .= '</table>';
            }
            $temp3_min = ($temp3_min != '' ? $temp3_min . " &deg;C" : "N/A");
            $temp3_max = ($temp3_max != '' ? $temp3_max . " &deg;C" : "N/A");

            $objKelvin3 = new stdClass();
            $objKelvin3->totalKelvinTemperature = isset($totalKelvinTemperature3)?$totalKelvinTemperature3:NULL;
            $objKelvin3->temptotalreadingcount = $tr;
            $temp3_average = round(($sumTEMP3 / $tr), 2);
            $finalMKTValue3 = round(getMeanKineticTemp($objKelvin3), 2);

            $temphtml3 = "<td style='text-align:center;'>
        <table class='table newTable'><thead><tr><th><u>" . $t3 . "</u></th></tr></thead>
            <tbody>
                <tr><td>Temprature Range :" . $min_max_temp3['temp_min_limit'] . " &deg;C to " . $min_max_temp3['temp_max_limit'] . " &deg;C</td></tr>
                <tr><td>Minimum Temperature: $temp3_min &deg;C</td></tr>
                <tr><td>Maximum Temperature: $temp3_max &deg;C</td></tr>
                <tr><td>Average Temperature: $temp3_average &deg;C</td></tr>
                <tr><td>Total Reading: $tr</td></tr>";
            if (isset($_SESSION['customerno']) && $_SESSION['customerno'] != speedConstants::CUSTNO_PHARM_EASY) {
                $temphtml3 .= "<tr><td>Non compliance readings : $temp3_non_comp_count</td></tr>";
            }

            $temphtml3 .= "<tr><td>Bad readings : $temp3_bad_reading</td></tr>";
            if (isset($_SESSION['customerno']) && $_SESSION['customerno'] != speedConstants::CUSTNO_PHARM_EASY) {
                $temphtml3 .= "<tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span>$colorTable</td></tr>";
            }
            $temphtml3 .= "<tr>
                        <td>
                            <a href='https://en.wikipedia.org/wiki/Mean_kinetic_temperature' target='blank'> Mean Kinetic Temperature: <span style='color:green;'>$finalMKTValue3 &deg;C</span></a>
                        </td>
                    </tr>
                </tbody></table>
            </td>" . $temphtml4;
            if ((isset($tempselectArr[0]) && $tempselectArr[0] == 3) && (isset($tempselectArr[1]) && $tempselectArr[1] != "all")) {
                $temphtml .= $temphtml3;
                break;
            }
        case 2:
            $span = isset($span) ? $span : 2;
            $goodcount = $temp2_nonmute_reading - $temp2_bad_reading;
            if ($temp2_nonmute_reading == 0 || $goodcount == 0) {
                $abv_compliance = "Not Applicable";
                $within_compliance = "Not Applicable";
            } else {
                $abv_compliance = round(($temp2_non_comp_count / $goodcount) * 100, 2);
                $compliance_count = $goodcount - $temp2_non_comp_count;
                $within_compliance = round($compliance_count / $goodcount * 100, 2);
            }
            if (isset($compliance_count) && $compliance_count > 0) {
                for ($j = 1; $j < 5; $j++) {
                    $key_start = 'temp2_range' . $j . '_start';
                    $key_end = 'temp2_range' . $j . '_end';
                    $key_color = 'temp2_range' . $j . '_color';
                    if (isset($min_max_temp2['temp_color'][$key_color]) && $min_max_temp2['temp_color'][$key_color] != '') {
                        $temp2_color_details['range' . $j]['start'] = $min_max_temp2['temp_color'][$key_start];
                        $temp2_color_details['range' . $j]['end'] = $min_max_temp2['temp_color'][$key_end];
                        $temp2_color_details['range' . $j]['perc'] = round($temp2_color['range' . $j] / $compliance_count * 100, 2);
                        $temp2_color_details['range' . $j]['color'] = $min_max_temp2['temp_color'][$key_color];
                    }
                }
            }
            $colorTable = '';
            if (!empty($temp2_color_details)) {
                $colorTable .= '<br><table style="width:100%;">';
                foreach ($temp2_color_details AS $colorData) {
                    $colorTable .= '<tr>
                                        <td style="border:1px white solid;background-color:' . $colorData['color'] . ';text-align:center;">' . $colorData['start'] . ' - ' . $colorData['end'] . ' C</td>
                                        <td style="border:1px white solid;text-align:center;">' . $colorData['perc'] . ' %</td>
                                    </tr>';
                }
                $colorTable .= '</table>';
            }
            $temp2_min = ($temp2_min != '' ? $temp2_min . " &deg;C" : "N/A");
            $temp2_max = ($temp2_max != '' ? $temp2_max . " &deg;C" : "N/A");

            $objKelvin2 = new stdClass();
            $objKelvin2->totalKelvinTemperature = isset($totalKelvinTemperature2) ? $totalKelvinTemperature2 : NULL;
            $objKelvin2->temptotalreadingcount = $tr; // Total Temp Reading Count
            $temp2_average = round(($sumTEMP2 / $tr), 2);
            $finalMKTValue2 = round(getMeanKineticTemp($objKelvin2), 2);

            $temphtml2 = "<td style='text-align:center;'>
            <table class='table newTable'><thead><tr><th><u>" . $t2 . "</u></th></tr></thead>
            <tbody>
                <tr><td>Temprature Range :" . $min_max_temp2['temp_min_limit'] . " &deg;C to " . $min_max_temp2['temp_max_limit'] . " &deg;C</td></tr>
                <tr><td>Minimum Temperature: $temp2_min </td></tr>
                <tr><td>Maximum Temperature: $temp2_max </td></tr>
                <tr><td>Average Temperature: $temp2_average &deg;C</td></tr>
                <tr><td>Total Reading: $tr</td></tr>";
            if (isset($_SESSION['customerno']) && $_SESSION['customerno'] != speedConstants::CUSTNO_PHARM_EASY) {
                $temphtml2 .= "<tr><td> Non compliance readings : $temp2_non_comp_count</td></tr>";
            }

            $temphtml2 .= "<tr><td>Bad readings : $temp2_bad_reading</td></tr>";
            if (isset($_SESSION['customerno']) && $_SESSION['customerno'] != speedConstants::CUSTNO_PHARM_EASY) {
                $temphtml2 .= "<tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span>$colorTable</td></tr>";
            }

            $temphtml2 .= "<tr>
                    <td>
                        <a href='https://en.wikipedia.org/wiki/Mean_kinetic_temperature' target='blank'> Mean Kinetic Temperature: <span style='color:green;'>$finalMKTValue2 &deg;C</span></a>

                    </td>
                </tr>
            </tbody></table>
        </td>" . $temphtml3;
            if ((isset($tempselectArr[0]) && $tempselectArr[0] == 2) && (isset($tempselectArr[1]) && $tempselectArr[1] != "all")) {
                $temphtml .= $temphtml2;
                break;
            }
        case 1:
            $span = isset($span) ? $span : 1;
            $goodcount = $temp1_nonmute_reading - $temp1_bad_reading;
            if ($temp1_nonmute_reading == 0 || $goodcount == 0) {
                $abv_compliance = "Not Applicable";
                $within_compliance = "Not Applicable";
            } else {
                $abv_compliance = round(($temp1_non_comp_count / $goodcount) * 100, 2);
                $compliance_count = $goodcount - $temp1_non_comp_count;
                $within_compliance = round($compliance_count / $goodcount * 100, 2);
            }
            if (isset($compliance_count) && $compliance_count > 0) {
                for ($j = 1; $j < 5; $j++) {
                    $key_start = 'temp1_range' . $j . '_start';
                    $key_end = 'temp1_range' . $j . '_end';
                    $key_color = 'temp1_range' . $j . '_color';
                    if (isset($min_max_temp1['temp_color'][$key_color]) && $min_max_temp1['temp_color'][$key_color] != '') {
                        $temp1_color_details['range' . $j]['start'] = $min_max_temp1['temp_color'][$key_start];
                        $temp1_color_details['range' . $j]['end'] = $min_max_temp1['temp_color'][$key_end];
                        $temp1_color_details['range' . $j]['perc'] = round($temp1_color['range' . $j] / $compliance_count * 100, 2);
                        $temp1_color_details['range' . $j]['color'] = $min_max_temp1['temp_color'][$key_color];
                    }
                }
            }
            $colorTable = '';
            if (!empty($temp1_color_details)) {
                $colorTable .= '<br><table style="width:100%;">';
                foreach ($temp1_color_details AS $colorData) {
                    $colorTable .= '<tr>
                                        <td style="border:1px white solid;background-color:' . $colorData['color'] . ';text-align:center;">' . $colorData['start'] . ' - ' . $colorData['end'] . ' C</td>
                                        <td style="border:1px white solid;text-align:center;">' . $colorData['perc'] . ' %</td>
                                    </tr>';
                }
                $colorTable .= '</table>';
            }
            $temp1_min = ($temp1_min != '' ? $temp1_min . " &deg;C" : "N/A");
            $temp1_max = ($temp1_max != '' ? $temp1_max . " &deg;C" : "N/A");

            $objKelvin1 = new stdClass();
            $objKelvin1->totalKelvinTemperature = isset($totalKelvinTemperature1)?$totalKelvinTemperature1:NULL;
            $objKelvin1->temptotalreadingcount = $tr;
            $temp1_average = round(($sumTEMP1 / $tr), 2);
            $finalMKTValue1 = round(getMeanKineticTemp($objKelvin1), 2);

            $temphtml = "<td style='text-align:center;'>
            <table class='table newTable'><thead>
            <tr><th><u>" . $t1 . "</u></th></tr></thead>
            <tbody>
                <tr><td>Temprature Range :" . $min_max_temp1['temp_min_limit'] . " &deg;C to " . $min_max_temp1['temp_max_limit'] . " &deg;C</td></tr>
                <tr><td>Minimum Temperature: $temp1_min </td></tr>
                <tr><td>Maximum Temperature: $temp1_max </td></tr>
                <tr><td>Average Temperature: $temp1_average &deg;C</td></tr>
                <tr><td>Total Reading: $tr</td></tr>";
            if (isset($_SESSION['customerno']) && $_SESSION['customerno'] != speedConstants::CUSTNO_PHARM_EASY) {
                $temphtml .= "<tr><td>Non compliance readings: $temp1_non_comp_count</td></tr>";
            }
            $temphtml .= "<tr><td>Bad readings : $temp1_bad_reading</td></tr>";
            if (isset($_SESSION['customerno']) && $_SESSION['customerno'] != speedConstants::CUSTNO_PHARM_EASY) {
                $temphtml .= "<tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span>$colorTable</td></tr>";
            }
            $temphtml .= "<tr>
                        <td>
                            <a href='https://en.wikipedia.org/wiki/Mean_kinetic_temperature' target='blank'> Mean Kinetic Temperature: <span style='color:green;'>$finalMKTValue1 &deg;C</span></a>
                        </td>
                    </tr>
                </tbody></table>
            </td>" . $temphtml2;
            break;
    }
    $summary = "<table align='center' style='width: auto; font-size:13px; text-align:center; border:1px solid #000;'>
        <thead>
            <tr><td colspan='$span' style='background-color:#CCCCCC;font-weight:bold;'>Statistics</td></tr>
        </thead>
        <tbody>
            <tr>$temphtml</tr>
        </tbody>
    </table>";
    $display .= "$summary";
    return $display;
}

function gethumidityreport($STdate, $EDdate, $deviceid, $interval, $stime, $etime) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $graph_days = array();
    $graph_days_ig = array();
    $veh_temp_details = array();
    $customerno = $_SESSION['customerno'];
    $unit = getunitdetails($deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $graph_data = null;
            $STdate = date("Y-m-d", strtotime($STdate));
            if ($userdate == $STdate) {
                $f_STdate = $userdate . " " . $stime . ":00";
            } else {
                $f_STdate = $userdate . " 00:00:00";
            }
            $EDdate = date("Y-m-d", strtotime($EDdate));
            if ($userdate == $EDdate) {
                $f_EDdate = $userdate . " " . $etime . ":00";
            } else {
                $f_EDdate = $userdate . " 23:59:59";
            }
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $return = gethumiditydata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, true, $unit);
                $data = $return[0];
                $graph_data = $return[1];
                $graph_ig = $return['ig_graph'];
            }
            if ($data != null && count($data) > 1) {
                $days = array_merge($days, $data);
            }
            if ($graph_data != null && count($graph_data) > 1) {
                $graph_days = array_merge($graph_days, $graph_data);
                $graph_days_ig = array_merge($graph_days_ig, $graph_ig);
            }
        }
    }
    if ($days != null && count($days) > 0) {
        if (isset($return['vehicleid'])) {
            $veh_temp_details = getunitdetailsfromvehid($return['vehicleid'], $customerno);
        }
        $finalreport = create_humidityhtml_from_report($days, $unit, $veh_temp_details);
    } else {
        $finalreport = "<tr><td colspan='3'>No Data</td></tr>";
    }
    $graph_days_final = '';
    $graph_ig_final = '';
    if (!empty($graph_days)) {
        $graph_days_final = implode(',', $graph_days);
        $graph_ig_final = implode(',', $graph_days_ig);
    }
    return array($finalreport, $graph_days_final, $veh_temp_details, $graph_ig_final);
}

function gethumiditytempreport($STdate, $EDdate, $deviceid, $interval, $stime, $etime) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $sqliteDeviceData = array();
    $graph_data_humidity = array();
    $graph_data_temperature = array();
    $veh_temp_details = array();
    $customerno = $_SESSION['customerno'];
    $unit = getunitdetails($deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $deviceData = null;
            $humidityReadings = null;
            $temperatureReadings = null;
            $STdate = date("Y-m-d", strtotime($STdate));
            if ($userdate == $STdate) {
                $f_STdate = $userdate . " " . $stime . ":00";
            } else {
                $f_STdate = $userdate . " 00:00:00";
            }
            $EDdate = date("Y-m-d", strtotime($EDdate));
            if ($userdate == $EDdate) {
                $f_EDdate = $userdate . " " . $etime . ":00";
            } else {
                $f_EDdate = $userdate . " 23:59:59";
            }
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $returnTempData = gettempdata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, true, $unit);
                $returnHumidityData = gethumiditydata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, true, $unit);
                $deviceData = $returnHumidityData[0];
                $humidityReadings = $returnHumidityData[1];
                $temperatureReadings = $returnTempData[1];
            }
            if ($deviceData != null && count($deviceData) > 1) {
                $sqliteDeviceData = array_merge($sqliteDeviceData, $deviceData);
            }
            if ($humidityReadings != null && count($humidityReadings) > 1) {
                $graph_data_humidity = array_merge($graph_data_humidity, $humidityReadings);
            }
            if ($temperatureReadings != null && count($temperatureReadings) > 1) {
                $graph_data_temperature = array_merge($graph_data_temperature, $temperatureReadings);
            }
        }
    }
    if ($sqliteDeviceData != null && count($sqliteDeviceData) > 0) {
        if (isset($returnHumidityData['vehicleid'])) {
            $veh_temp_details = getunitdetailsfromvehid($returnHumidityData['vehicleid'], $customerno);
        }
        $finalreport = create_humiditytemphtml_from_report($sqliteDeviceData, $unit, $veh_temp_details);
    } else {
        $finalreport = "<tr><td colspan='3'>No Data</td></tr>";
    }
    $humidityReadingsFinal = '';
    $temperatureReadingsFinal = '';
    if (!empty($graph_data_humidity)) {
        $humidityReadingsFinal = implode(',', $graph_data_humidity);
    }
    if (!empty($graph_data_temperature)) {
        foreach ($graph_data_temperature as $keyData => $valData) {
            if (is_array($valData)) {
                $graph_data_temperature_final = $graph_data_temperature[0];
            } else {
                $graph_data_temperature_final = $graph_data_temperature;
            }
        }
        $temperatureReadingsFinal = implode(',', $graph_data_temperature_final);
    }

    return array($finalreport, $humidityReadingsFinal, $veh_temp_details, $temperatureReadingsFinal);
}

function gettempExcepreport($STdate, $EDdate, $deviceid, $interval, $stime, $etime, $tempselect) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $veh_temp_details = array();
    $customerno = $_SESSION['customerno'];
    $unit = getunitdetails($deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $graph_data = null;
            $STdate = date("Y-m-d", strtotime($STdate));
            if ($userdate == $STdate) {
                $f_STdate = $userdate . " " . $stime . ":00";
            } else {
                $f_STdate = $userdate . " 00:00:00";
            }
            $EDdate = date("Y-m-d", strtotime($EDdate));
            if ($userdate == $EDdate) {
                $f_EDdate = $userdate . " " . $etime . ":00";
            } else {
                $f_EDdate = $userdate . " 23:59:59";
            }
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $return = gettempExcep_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, true, $unit);
                $data = $return[0];
            }
            if ($data != null && count($data) > 1) {
                $days = array_merge($days, $data);
            }
        }
    }
    if ($days != null && count($days) > 0) {
        if (isset($return['vehicleid'])) {
            $veh_temp_details = getunitdetailsfromvehid($return['vehicleid'], $customerno);
        }
        $datediff = strtotime($EDdate . " $etime") - strtotime($STdate . " $stime");
        $finalreport = create_temphtml_Excep_report($days, $unit, $veh_temp_details, $tempselect, count($totaldays), $datediff);
    } else {
        $finalreport = "<tr><td colspan='100%'>No Data</td></tr>";
    }
    return array($finalreport, $veh_temp_details);
}

function getacreportpdf($customerno, $STdate, $EDdate, $deviceid, $vehicleno) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getacdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date('Y-m-d H:i:s');
        $formatdate = date(speedConstants::DEFAULT_DATE, strtotime($STdate));
        $finalreport = '<div style="width:auto; height:30px;">
        <table style="width: auto; border:none;">
            <tr>
                <td style="width:430px; border:none;"><img style="width:50%; height: 50%;" src="../../images/elixiaspeed_logo.png" /></td>
                <td style="width:420px; border:none;">
                    <h3 style="text-transform:uppercase;">Gen Set Sensor Report</h3><br />
                </td>
                <td style="width:230px;border:none;">
                    <img src="../../images/elixia_logo_75.png"  /></td>
                </tr>
            </table>
        </div>';
        $finalreport .= "<hr />
        <h4>
            <div align='center' style='text-align:center;'>
                Vehicle No. $vehicleno</div><div align='right' style='text-align:center;' >
                $formatdate
            </div>
        </h4>
        <style type='text/css'>
            table, td { border: solid 1px  #999999; color:#000000; }
            hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
        </style>
        <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
            <tbody>
                <tr style='background-color:#CCCCCC;'>
                    <td style='width:100px;height:auto;'>Start Time</td>
                    <td style='width:100px;height:auto;'>End Time</td>
                    <td style='width:150px;height:auto;'>Ignition Status</td>
                    <td style='width:150px;height:auto;'>Gen Set Status</td>
                    <td style='width:150px;height:auto;'>Duration[Hours:Minutes]</td>
                </tr>";
        $finalreport .= create_pdf_from_report($days, $acinvertval);
    }
    echo $finalreport;
}

function getgensetreportpdf($customerno, $STdate, $EDdate, $deviceid, $vehicleno) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $finalreport = '';
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $filesize = filesize($location);
                if ($filesize > 0) {
                    $location = "sqlite:" . $location;
                    $data = getgensetdata_fromsqlite($location, $deviceid);
                }
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date('Y-m-d H:i:s');
        $formatdate = date(speedConstants::DEFAULT_DATE, strtotime($STdate));
        $finalreport = '<div style="width:auto; height:30px;">
                <table style="width: auto; border:none;">
                    <tr>
                        <td style="width:430px; border:none;"><img style="width:50%; height: 50%;" src="../../images/elixiaspeed_logo.png" /></td>
                        <td style="width:420px; border:none;">
                            <h3 style="text-transform:uppercase;">Gen Set Sensor Report</h3><br />
                        </td>
                        <td style="width:230px;border:none;">
                            <img src="../../images/elixia_logo_75.png"  /></td>
                        </tr>
                    </table>
                </div>';
        $finalreport .= "<hr />
                <h4>
                    <div align='center' style='text-align:center;'>
                        Vehicle No. $vehicleno</div><div align='right' style='text-align:center;' >
                        $formatdate
                    </div>
                </h4>
                <style type='text/css'>
                    table, td { border: solid 1px  #999999; color:#000000; }
                    hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
                </style>
                <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                    <tbody>
                        <tr style='background-color:#CCCCCC;'>
                            <td style='width:100px;height:auto;'>Start Time</td>
                            <td style='width:100px;height:auto;'>End Time</td>
                            <td style='width:150px;height:auto;'>Gen Set Status</td>
                            <td style='width:150px;height:auto;'>Duration[Hours:Minutes]</td>
                        </tr>";
        $finalreport .= create_gensetpdf_from_report($days, $acinvertval);
    }
    echo $finalreport;
}

function getacreportpdfMultipleDays($customerno, $STdate, $EDdate, $deviceid, $vehicleno) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getacdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date('Y-m-d H:i:s');
        $formatdate = date(speedConstants::DEFAULT_DATE, strtotime($STdate));
        $fromdate = date(speedConstants::DEFAULT_DATE, strtotime($STdate));
        $todate = date(speedConstants::DEFAULT_DATE, strtotime($EDdate));
        $finalreport = '<div style="width:auto; height:30px;">
                        <table style="width: auto; border:none;">
                            <tr>
                                <td style="width:430px; border:none;"><img style="width:50%; height: 50%;" src="../../images/elixiaspeed_logo.png" /></td>
                                <td style="width:420px; border:none;">
                                    <h3 style="text-transform:uppercase;">Gen Set Sensor Report</h3><br />
                                </td>
                                <td style="width:230px;border:none;">
                                    <img src="../../images/elixia_logo_75.png"/></td>
                                </tr>
                            </table>
                        </div>';
        $finalreport .= "Vehicle No. $vehicleno
                        From :  $fromdate to : $todate";
        $finalreport .= "<hr />
                        <style type='text/css'>
                            table, td { border: solid 1px  #999999; color:#000000; }
                            hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
                        </style>
                        <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                            <tbody>";
        $finalreport .= create_pdf_for_multipledays($days, $acinvertval);
    }
    echo $finalreport;
}

function getgensetreportpdfMultipleDays($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getgensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $cm = new CustomerManager();
        $cd = $cm->getcustomerdetail_byid($customerno);
        $title = getcustombyid(1, 'Digital', $customerno) . ' Sensor History';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $STdate",
            "End Date: $EDdate"
        );
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        $finalreport = pdf_header($title, $subTitle, $cd);
        $finalreport .= "
                                <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                    <tbody>";
        $finalreport .= create_gensetpdf_for_multipledays($days, $acinvertval, $customerno, $cd->use_geolocation);
    } else {
        $finalreport = "No Data";
    }
    echo $finalreport;
}

function getInactiveDevicePDF($customerno, $sdate, $edate) {
    $title = 'Inactive Device Report';
    $subTitle = array(
        "Start Date: $sdate",
        "End Date: $edate"
    );
    $finalreport = pdf_header($title, $subTitle);
    $vm = new VehicleManager($_SESSION['customerno']);
    $inactive = $vm->getInactiveDevices($sdate, $edate);
    $finalreport .= '<table id="search_table_2" align="center" style="font-size:15px; text-align:center;border-collapse:collapse;border:1px solid #000;">';
    $finalreport .= '<tr>
                        <th style="border:1px solid #000;padding:3px;">Sr No</th>
                        <th style="border-right:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;padding:3px;">Vehicle No</th>
                        <th style="border-right:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;padding:3px;">Start Time</th>
                        <th style="border:1px solid #000;padding:3px;">End Time</th>
                        <th style="border:1px solid #000;padding:3px;">Inactive Hours</th>
                    </tr>';
    if ($inactive != NULL) {
        $x = 1;
        foreach ($inactive as $data) {
            if ($data['end_time'] != '0000-00-00 00:00:00') {
                $endtime = date('d-m-Y H:i', strtotime($data['end_time']));
            } else {
                $endtime = '';
            }

            if ($data['start_time'] != '0000-00-00 00:00:00') {
                $starttime = date('d-m-Y H:i', strtotime($data['start_time']));
            } else {
                $starttime = '';
            }
            $finalreport .= '<tr><td style="padding:3px;">' . $x . '</td><td style="padding:3px;">' . $data['vehicleno'] . '</td><td style="padding:3px;">' . $starttime . '</td><td style="padding:3px;">' . $endtime . '</td><td style="padding:3px;">' . $data['time_difference'] . '</td></tr>';
            $x++;
        }
    } else {
        $finalreport .= '<tr><td colspan="4">Data Not Available</td></tr>';
    }
    $finalreport .= '</table>';
    echo $finalreport;
}

function getgensetreportpdfMultipleDays_details($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $STime, $ETime, $vgroupname = null, $gensetSensor = null) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    $gensetSensor = isset($gensetSensor) ? $gensetSensor : 1;
    if (isset($totaldays)) {
        $firstDay = reset($totaldays);
        $lastDay = end($totaldays);
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $startTime = $userdate . " " . $STime;
                $endTime = $userdate . " " . $ETime;
                if ($firstDay == $userdate && $lastDay == $userdate) {
                    $data = getgensetdata_fromsqlite($location, $deviceid, $startTime, $endTime, $gensetSensor);
                } elseif ($firstDay == $userdate) {
                    $data = getgensetdata_fromsqlite($location, $deviceid, $startTime, null, $gensetSensor);
                } elseif ($lastDay == $userdate) {
                    $data = getgensetdata_fromsqlite($location, $deviceid, null, $endTime, $gensetSensor);
                } else {
                    $data = getgensetdata_fromsqlite($location, $deviceid, null, null, $gensetSensor);
                }
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $cm = new CustomerManager();
        $cd = $cm->getcustomerdetail_byid($customerno);
        $title = getcustombyid(1, 'Digital', $customerno) . ' Sensor History Details';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $STdate",
            "End Date: $EDdate"
        );
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        $finalreport = pdf_header($title, $subTitle, $cd);
        $finalreport .= "
                                        <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                            <tbody>";
        $finalreport .= create_gensetpdf_for_multipledays_details($days, $acinvertval, $customerno, $cd->use_geolocation, $gensetSensor);
    } else {
        $finalreport = "No Data";
    }
    echo $finalreport;
}

function getgensetreportpdfMultipleDays_summary($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getgensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $cm = new CustomerManager();
        $cd = $cm->getcustomerdetail_byid($customerno);
        $title = getcustombyid(1, 'Digital', $customerno) . ' Sensor Summary';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $STdate",
            "End Date: $EDdate"
        );
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        $finalreport = pdf_header($title, $subTitle, $cd);
        $finalreport .= "<table id='search_table_2' align='center' style='  width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tbody>";
        $finalreport .= create_gensetpdf_for_multipledays_summary($days, $acinvertval, $customerno, $cd->use_geolocation);
    } else {
        $finalreport = "No Data";
    }
    echo $finalreport;
}

function getextrareportpdfMultipleDays($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $extraid, $extraval, $vgroupname = null) {
    //var_dump($extraid);die();
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    //$acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getextradata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $title = $_SESSION["digitalcon"] . ' Sensor History';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $STdate",
            "End Date: $EDdate"
        );
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }

        $finalreport = pdf_header($title, $subTitle);
        $finalreport .= "
                                                <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                                    <tbody>";
        $finalreport .= create_extrapdf_for_multipledays($days, $extraid, $extraval);
    } else {
        $finalreport = "No Data";
    }
    echo $finalreport;
}

function gethumidityreportpdf($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $interval, $stime, $etime, $switchto, $vgroupname = null, $reporttype = null) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $unit = getunitdetailspdf($customerno, $deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $STdate = date("Y-m-d", strtotime($STdate));
            if ($userdate == $STdate) {
                $f_STdate = $userdate . " " . $stime . ":00";
            } else {
                $f_STdate = $userdate . " 00:00:00";
            }
            $EDdate = date("Y-m-d", strtotime($EDdate));
            if ($userdate == $EDdate) {
                $f_EDdate = $userdate . " " . $etime . ":00";
            } else {
                $f_EDdate = $userdate . " 23:59:59";
            }
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = gethumiditydata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, false, null, $customerno);
                $vehicleid = $data['vehicleid'];
            }
            if ($data[0] != null && count($data[0]) > 1) {
                $days = array_merge($days, $data[0]);
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $cm = new CustomerManager($customerno);
        $customer_details = $cm->getcustomerdetail_byid($customerno);
        $fromdate = date('d-m-Y H:i', strtotime($STdate . ' ' . $stime));
        $todate = date('d-m-Y H:i', strtotime($EDdate . ' ' . $etime));
        $title = 'Humidity Report';
        if ($switchto == 3) {
            if (isset($_SESSION['Warehouse'])) {
                $veh = $_SESSION['Warehouse'];
            } else {
                $veh = "Warehouse";
            }
            $subTitle = array(
                "$veh: $vehicleno",
                "Start Date: $fromdate",
                "End Date: $todate",
                "Interval: $interval mins"
            );
        } else {
            $subTitle = array(
                "Vehicle No: $vehicleno",
                "Start Date: $fromdate",
                "End Date: $todate",
                "Interval: $interval mins"
            );
        }
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        if ($reporttype == 'pdf') {
            $finalreport = pdf_header($title, $subTitle, $customer_details);
        } elseif ($reporttype == 'xls') {
            $finalreport = excel_header($title, $subTitle, $customer_details);
        }
        $finalreport .= "<hr /><br/><br/>
                                                                <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                                                    <tbody>";
        $veh_temp_details = '';
        if (isset($vehicleid)) {
            $veh_temp_details = getunitdetailsfromvehid($vehicleid, $customerno);
        }
        $finalreport .= create_humiditypdf_from_report($days, $unit, $customerno, $veh_temp_details, $switchto);
    }
    echo $finalreport;
}

// Temprature and Humidity In Pdf
function gettemphumidityreportpdf($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $interval, $stime, $etime, $switchto, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $veh_temp_details = array();
    $days = array();
    $unit = getunitdetailspdf($customerno, $deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $STdate = date("Y-m-d", strtotime($STdate));
            if ($userdate == $STdate) {
                $f_STdate = $userdate . " " . $stime . ":00";
            } else {
                $f_STdate = $userdate . " 00:00:00";
            }
            $EDdate = date("Y-m-d", strtotime($EDdate));
            if ($userdate == $EDdate) {
                $f_EDdate = $userdate . " " . $etime . ":00";
            } else {
                $f_EDdate = $userdate . " 23:59:59";
            }
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = gethumiditydata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, false, null, $customerno);
                $vehicleid = $data['vehicleid'];
            }
            if ($data[0] != null && count($data[0]) > 1) {
                $days = array_merge($days, $data[0]);
            }
        }
    }
    $cm = new CustomerManager($customerno);
    $customer_details = $cm->getcustomerdetail_byid($customerno);
    $fromdate = date('d-m-Y H:i', strtotime($STdate . ' ' . $stime));
    $todate = date('d-m-Y H:i', strtotime($EDdate . ' ' . $etime));
    $title = 'Humidity And Temperature Report';
    if ($switchto == 3) {
        if (isset($_SESSION['Warehouse'])) {
            $veh = $_SESSION['Warehouse'];
        } else {
            $veh = "Warehouse";
        }
        $subTitle = array(
            "$veh: $vehicleno",
            "Start Date: $fromdate",
            "End Date: $todate",
            "Interval: $interval mins"
        );
    } else {
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $fromdate",
            "End Date: $todate",
            "Interval: $interval mins"
        );
    }
    if (!is_null($vgroupname)) {
        $subTitle[] = "Group-name: $vgroupname";
    }
    $finalreport .= pdf_header($title, $subTitle, $customer_details);
    $finalreport .= "<hr /><br/><br/>
    <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
    <tbody>";
    if (isset($vehicleid)) {
        $veh_temp_details = getunitdetailsfromvehid($vehicleid, $customerno);
    }
    if ($days != null && count($days) > 0) {
        $finalreport .= create_humiditytemp_pdf_from_report($days, $unit, $veh_temp_details, $switchto, $customer_details);
    } else {
        $finalreport .= "<tr><td>No Data</td></tr></tbody></table>";
    }
    echo $finalreport;
}

// Temprature and Humidity In Excel
function gettemphumidityreportxls($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $interval, $stime, $etime, $switchto, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $veh_temp_details = array();
    $days = array();
    $unit = getunitdetailspdf($customerno, $deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $STdate = date("Y-m-d", strtotime($STdate));
            if ($userdate == $STdate) {
                $f_STdate = $userdate . " " . $stime . ":00";
            } else {
                $f_STdate = $userdate . " 00:00:00";
            }
            $EDdate = date("Y-m-d", strtotime($EDdate));
            if ($userdate == $EDdate) {
                $f_EDdate = $userdate . " " . $etime . ":00";
            } else {
                $f_EDdate = $userdate . " 23:59:59";
            }
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = gethumiditydata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, false, null, $customerno);
                $vehicleid = $data['vehicleid'];
            }
            if ($data[0] != null && count($data[0]) > 1) {
                $days = array_merge($days, $data[0]);
            }
        }
    }
    $cm = new CustomerManager($customerno);
    $customer_details = $cm->getcustomerdetail_byid($customerno);
    $fromdate = date('d-m-Y H:i', strtotime($STdate . ' ' . $stime));
    $todate = date('d-m-Y H:i', strtotime($EDdate . ' ' . $etime));
    $title = 'Humidity And Temperature Report';
    if ($switchto == 3) {
        if (isset($_SESSION['Warehouse'])) {
            $veh = $_SESSION['Warehouse'];
        } else {
            $veh = "Warehouse";
        }
        $subTitle = array(
            "$veh: $vehicleno",
            "Start Date: $fromdate",
            "End Date: $todate",
            "Interval: $interval mins"
        );
    } else {
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $fromdate",
            "End Date: $todate",
            "Interval: $interval mins"
        );
    }
    if (!is_null($vgroupname)) {
        $subTitle[] = "Group-name: $vgroupname";
    }
    $finalreport .= excel_header($title, $subTitle, $customer_details);
    $finalreport .= "<hr /><br/><br/>
    <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
    <tbody>";
    //$veh_temp_details = '';
    if (isset($vehicleid)) {
        $veh_temp_details = getunitdetailsfromvehid($vehicleid, $customerno);
    }
    if ($days != null && count($days) > 0) {
        $finalreport .= create_humiditytemp_pdf_from_report($days, $unit, $veh_temp_details, $switchto, $customer_details);
    } else {
        $finalreport .= "<tr><td>No Data</td></tr></tbody></table>";
    }
    echo $finalreport;
}

function gettempreportpdf_Excep($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $interval, $stime, $etime, $tempselect, $switchto, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $unit = getunitdetailspdf($customerno, $deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $STdate = date("Y-m-d", strtotime($STdate));
            if ($userdate == $STdate) {
                $f_STdate = $userdate . " " . $stime . ":00";
            } else {
                $f_STdate = $userdate . " 00:00:00";
            }
            $EDdate = date("Y-m-d", strtotime($EDdate));
            if ($userdate == $EDdate) {
                $f_EDdate = $userdate . " " . $etime . ":00";
            } else {
                $f_EDdate = $userdate . " 23:59:59";
            }
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = gettempdata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, false, null, $customerno);
                $vehicleid = $data['vehicleid'];
            }
            if ($data[0] != null && count($data[0]) > 1) {
                $days = array_merge($days, $data[0]);
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $cm = new CustomerManager($customerno);
        $customer_details = $cm->getcustomerdetail_byid($customerno);
        $fromdate = date('d-m-Y H:i', strtotime($STdate . ' ' . $stime));
        $todate = date('d-m-Y H:i', strtotime($EDdate . ' ' . $etime));
        $title = 'Temperature Exception Report';
        if ($switchto == 3) {
            if (isset($_SESSION['Warehouse'])) {
                $veh = $_SESSION['Warehouse'];
            } else {
                $veh = "Warehouse";
            }
            $subTitle = array(
                "$veh: $vehicleno",
                "Start Date: $fromdate",
                "End Date: $todate",
                "Interval: $interval mins"
            );
        } else {
            $subTitle = array(
                "Vehicle No: $vehicleno",
                "Start Date: $fromdate",
                "End Date: $todate",
                "Interval: $interval mins"
            );
        }
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        $finalreport = pdf_header($title, $subTitle, $customer_details);
        $finalreport .= "<hr /><br/><br/>
                                                                                        <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                                                                            <tbody>";
        $veh_temp_details = '';
        if (isset($vehicleid)) {
            $veh_temp_details = getunitdetailsfromvehid($vehicleid, $customerno);
        }
        $datediff = strtotime($EDdate . " $etime") - strtotime($STdate . " $stime");
        $finalreport .= create_temppdf_Excep_report($days, $unit, $customerno, $veh_temp_details, $tempselect, count($totaldays), $datediff);
    }
    echo $finalreport;
}

function gettempreportxlsExcep($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $interval, $stime, $etime, $tempselect, $switchto, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $finalreport = '';
    $unit = getunitdetailspdf($customerno, $deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $STdate = date("Y-m-d", strtotime($STdate));
            if ($userdate == $STdate) {
                $f_STdate = $userdate . " " . $stime . ":00";
            } else {
                $f_STdate = $userdate . " 00:00:00";
            }
            $EDdate = date("Y-m-d", strtotime($EDdate));
            if ($userdate == $EDdate) {
                $f_EDdate = $userdate . " " . $etime . ":00";
            } else {
                $f_EDdate = $userdate . " 23:59:59";
            }
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = gettempdata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate);
                $vehicleid = $data['vehicleid'];
            }
            if ($data[0] != null && count($data[0]) > 1) {
                $days = array_merge($days, $data[0]);
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $cm = new CustomerManager($customerno);
        $customer_details = $cm->getcustomerdetail_byid($customerno);
        $fromdate = date(speedConstants::DEFAULT_DATETIME, strtotime($STdate . ' ' . $stime));
        $todate = date(speedConstants::DEFAULT_DATETIME, strtotime($EDdate . ' ' . $etime));
        $title = "Temperature Exception Report";
        if ($switchto == 3) {
            if (isset($_SESSION['Warehouse'])) {
                $veh = $_SESSION['Warehouse'];
            } else {
                $veh = "Warehouse";
            }
            $subTitle = array(
                "$veh: $vehicleno",
                "Start Date: $fromdate",
                "End Date: $todate",
                "Interval: <b>$interval</b> mins"
            );
        } else {
            $subTitle = array(
                "Vehicle No: $vehicleno",
                "Start Date: $fromdate",
                "End Date: $todate",
                "Interval: <b>$interval</b> mins"
            );
        }
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        $finalreport = excel_header($title, $subTitle, $customer_details);
        $finalreport .= "
                                                                                                <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                                                                                    <tbody>";
        $veh_temp_details = '';
        if (isset($vehicleid)) {
            $veh_temp_details = getunitdetailsfromvehid($vehicleid, $customerno);
        }
        $datediff = strtotime($EDdate . " $etime") - strtotime($STdate . " $stime");
        $finalreport .= create_temppdf_Excep_report($days, $unit, $customerno, $veh_temp_details, $tempselect, count($totaldays), $datediff);
    }
    echo $finalreport;
}

function getacreportcsv($customerno, $STdate, $EDdate, $deviceid, $vehicleno) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = substr(date("Y-m-d H:i:s"), '0', 11);
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getacdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date(speedConstants::DEFAULT_DATETIME);
        $repdate = convertDateToFormat($STdate, speedConstants::DEFAULT_DATE);
        $finalreport = '<div style="width:1120px; height:30px;">
                                                                                                        <table style="width: 1120px;  border:1px solid #000;">
                                                                                                            <tr>
                                                                                                                <td colspan="7" style="width:1120px; text-align: center; text-transform:uppercase; border:none;"><h4 style="text-transform:uppercase;">Gen Set Sensor Report</h4></td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </div>';
        $finalreport .= "<div style='width:1120px; height:30px;'>
                                                                                                    <table id='search_table_2' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                                                                                        <tr>
                                                                                                            <td colspan='2' style='text-align:center;'><b>Vehicle No. $vehicleno</b></td>
                                                                                                            <td colspan='3' style='text-align:center;'><b>Date : $repdate</b></td>
                                                                                                            <td colspan='2' style='text-align:center;'><b>Report Generated On : $today</b></td>
                                                                                                        </tr>
                                                                                                    </table>
                                                                                                </div>
                                                                                                <hr />
                                                                                                <style type='text/css'>
                                                                                                    table, td { border: solid 1px  #999999; color:#000000; }
                                                                                                    hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
                                                                                                </style>
                                                                                                <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                                                                                    <tbody>
                                                                                                        <tr style='background-color:#CCCCCC;'>
                                                                                                            <td style='width:50px;height:auto; text-align: center;'></td>
                                                                                                            <td style='width:50px;height:auto; text-align: center;'>Start Time</td>
                                                                                                            <td style='width:50px;height:auto; text-align: center;'>End Time</td>
                                                                                                            <td style='width:50px;height:auto; text-align: center;'>Ignition Status</td>
                                                                                                            <td style='width:50px;height:auto; text-align: center;'>Gen Set Status</td>
                                                                                                            <td style='width:150px;height:auto; text-align: center;'>Duration[Hours:Minutes]</td>
                                                                                                            <td style='width:50px;height:auto; text-align: center;'></td>
                                                                                                        </tr>";
        $finalreport .= create_csv_from_report($days, $acinvertval);
    }
    echo $finalreport;
}

function getgensetreportcsv($customerno, $STdate, $EDdate, $deviceid, $vehicleno) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $finalreport = '';
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getgensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date(speedConstants::DEFAULT_DATETIME);
        $repdate = date(speedConstants::DEFAULT_DATE, strtotime($STdate));
        $finalreport = '<div style="width:1120px; height:30px;">
                                                                                                        <table style="width: 1120px;  border:1px solid #000;">
                                                                                                            <tr>
                                                                                                                <td colspan="7" style="width:1120px; text-align: center; text-transform:uppercase; border:none;"><h4 style="text-transform:uppercase;">Gen Set Sensor Report</h4></td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </div>';
        $finalreport .= "<div style='width:1120px; height:30px;'>
                                                                                                    <table id='search_table_2' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                                                                                        <tr>
                                                                                                            <td colspan='2' style='text-align:center;'><b>Vehicle No. $vehicleno</b></td>
                                                                                                            <td colspan='3' style='text-align:center;'><b>Date : $repdate</b></td>
                                                                                                            <td colspan='2' style='text-align:center;'><b>Report Generated On : $today</b></td>
                                                                                                        </tr>
                                                                                                    </table>
                                                                                                </div>
                                                                                                <hr />
                                                                                                <style type='text/css'>
                                                                                                    table, td { border: solid 1px  #999999; color:#000000; }
                                                                                                    hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
                                                                                                </style>
                                                                                                <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                                                                                    <tbody>
                                                                                                        <tr style='background-color:#CCCCCC;'>
                                                                                                            <td style='width:50px;height:auto; text-align: center;'></td>
                                                                                                            <td style='width:50px;height:auto; text-align: center;'>Start Time</td>
                                                                                                            <td style='width:50px;height:auto; text-align: center;'>End Time</td>
                                                                                                            <td style='width:50px;height:auto; text-align: center;'>Gen Set Status</td>
                                                                                                            <td style='width:150px;height:auto; text-align: center;'>Duration[Hours:Minutes]</td>
                                                                                                            <td style='width:50px;height:auto; text-align: center;'></td>
                                                                                                        </tr>";
        $finalreport .= create_gensetcsv_from_report($days, $acinvertval);
    }
    echo $finalreport;
}

function getacreportexcel($customerno, $STdate, $EDdate, $deviceid, $vehicleno) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getacdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date(speedConstants::DEFAULT_DATETIME);
        $repdate = date(speedConstants::DEFAULT_DATE, strtotime($STdate));
        $fromdate = date(speedConstants::DEFAULT_DATE, strtotime($STdate));
        $todate = date(speedConstants::DEFAULT_DATE, strtotime($EDdate));
        $finalreport = '<div style="width:1120px; height:30px;">
                                                                                                        <table style="width: 1120px;  border:1px solid #000;">
                                                                                                            <tr>
                                                                                                                <td colspan="7" style="width:1120px; text-align: center; text-transform:uppercase; border:none;"><h4 style="text-transform:uppercase;">Gen Set Sensor Report</h4></td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </div>';
        $finalreport .= "<div style='width:1120px; height:30px;'>
                                                                                                    <table id='search_table_2' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                                                                                        <tr>
                                                                                                            <td colspan='2' style='text-align:center;'><b>Vehicle No. $vehicleno</b></td>
                                                                                                            <td colspan='3' style='text-align:center;'><b>From :  $fromdate To : $todate</b></td>
                                                                                                            <td colspan='2' style='text-align:center;'><b>Report Generated On : $today</b></td>
                                                                                                        </tr>
                                                                                                    </table>
                                                                                                </div>
                                                                                                <hr />
                                                                                                <style type='text/css'>
                                                                                                    table, td { border: solid 1px  #999999; color:#000000; }
                                                                                                    hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
                                                                                                </style>
                                                                                                <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                                                                                    <tbody>";
        $finalreport .= create_excel_from_report($days, $acinvertval);
    }
    echo $finalreport;
}

function getgensetreportexcel($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $finalreport = '';
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getgensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $cm = new CustomerManager();
        $cd = $cm->getcustomerdetail_byid($customerno);
        $title = getcustombyid(1, 'Digital', $customerno) . ' Sensor History';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $STdate",
            "End Date: $EDdate"
        );
        $finalreport = excel_header($title, $subTitle, $cd);
        $finalreport .= "<table  id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tbody>";
        $finalreport .= create_gensetexcel_from_report($days, $acinvertval, $customerno, $cd->use_geolocation);
    }
    echo $finalreport;
}

function getgensetreportexcelsummary($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $finalreport = '';
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getgensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $cm = new CustomerManager();
        $cd = $cm->getcustomerdetail_byid($customerno);
        $title = getcustombyid(1, 'Digital', $customerno) . ' Sensor History';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $STdate",
            "End Date: $EDdate"
        );
        $finalreport = excel_header($title, $subTitle, $cd);
        $finalreport .= "<table  id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tbody>";
        $finalreport .= create_gensetexcel_summary_from_report($days, $acinvertval, $customerno, $cd->use_geolocation);
    }
    echo $finalreport;
}

function getgensetreportexceldetails($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $STime, $ETime, $vgroupname = null, $gensetSensor = null) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $finalreport = '';
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    $gensetSensor = isset($gensetSensor) ? $gensetSensor : 1;
    if (isset($totaldays)) {
        $firstDay = reset($totaldays);
        $lastDay = end($totaldays);
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $startTime = $userdate . " " . $STime;
                $endTime = $userdate . " " . $ETime;
                if ($firstDay == $userdate && $lastDay == $userdate) {
                    $data = getgensetdata_fromsqlite($location, $deviceid, $startTime, $endTime, $gensetSensor);
                } elseif ($firstDay == $userdate) {
                    $data = getgensetdata_fromsqlite($location, $deviceid, $startTime, null, $gensetSensor);
                } elseif ($lastDay == $userdate) {
                    $data = getgensetdata_fromsqlite($location, $deviceid, null, $endTime, $gensetSensor);
                } else {
                    $data = getgensetdata_fromsqlite($location, $deviceid, null, null, $gensetSensor);
                }
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $cm = new CustomerManager();
        $cd = $cm->getcustomerdetail_byid($customerno);
        $title = getcustombyid(1, 'Digital', $customerno) . ' Sensor History Details';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $STdate",
            "End Date: $EDdate"
        );
        $finalreport = excel_header($title, $subTitle, $cd);
        $finalreport .= "<table  id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tbody>";
        $finalreport .= create_gensetexcel_from_reportdetails($days, $acinvertval, $customerno, $cd->use_geolocation, $gensetSensor);
    }
    echo $finalreport;
}

function getextrareportexcel($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $extraid, $extraval, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = substr(date("Y-m-d H:i:s"), '0', 11);
    $days = array();
    $finalreport = '';
    $unitno = getunitnopdf($customerno, $deviceid);
    //$acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getextradata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $title = $_SESSION["digitalcon"] . ' Sensor History';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $STdate",
            "End Date: $EDdate"
        );
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        $finalreport = excel_header($title, $subTitle);
        $finalreport .= "<table  id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tbody>";

        $finalreport .= create_extraexcel_from_report($days, $extraid, $extraval, $customerno);
    }
    echo $finalreport;
}

function gettripreport($STdate, $EDdate, $deviceid) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $days = array();
    $customerno = $_SESSION['customerno'];
    $unitno = getunitno($deviceid);
    $acinvertval = getacinvertval($unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getacdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $finalreport = create_html_from_report($days, $acinvertval);
    }
    echo $finalreport;
}

function createrep($data) {
    //echo "Data: <pre>"; print_r($data); exit();
    $currentrow = new stdClass();
    $currentrow->digitalio = $data[0]->digitalio;
    $currentrow->extradigitalio = $data[0]->extradigitalio;
    $currentrow->ignition = $data[0]->ignition;
    $currentrow->starttime = $data[0]->lastupdated;
    $currentrow->endtime = 0;
    $currentrow->fuelltr = 0;
    $currentrow->startcgeolat = $data[0]->cgeolat;
    $currentrow->startcgeolong = $data[0]->cgeolong;
    $currentrow->distancetravelled = $data[0]->distancetravelled;
    $gen_report = array();
    $a = 0;
    $counter = 0;
    //Creating Rows Of Data Where Duration Is Greater Than 3
    while (TRUE) {
        $i = 1;
        while (isset($data[$a + $i]) && checkdate_status($data[$a + $i], $currentrow, $data, ($a + $i))) {
            $i += 1;
        }
        if (isset($data[$a + $i])) {
            $currentrow->endtime = $data[$a + $i]->lastupdated;
            if (isset($data[$a + $i]->fuelltr)) {
                $currentrow->fuelltr = $data[$a + $i]->fuelltr;
            }
            $currentrow->endcgeolat = $data[$a + $i]->cgeolat;
            $currentrow->endcgeolong = $data[$a + $i]->cgeolong;
            $currentrow->duration = round(getduration($currentrow->endtime, $currentrow->starttime), 0);
            $gen_report[] = $currentrow;
            $currentrow = new stdClass();
            $currentrow->starttime = $data[$a + $i]->lastupdated;
            $currentrow->startcgeolat = $data[$a + $i]->cgeolat;
            $currentrow->startcgeolong = $data[$a + $i]->cgeolong;
            $currentrow_count = $a + $i;
            //Just To Check That Data For The Next Row Is Not Wrong
            /*while (isset($data[$a + $i + 1]) && getduration($data[$a + $i + 1]->lastupdated, $currentrow->starttime) < 3) {
            $i += 1;
             */
            if (($a + $i) > $currentrow_count) {
                $gen_report[$counter]->endtime = $data[$a + $i]->lastupdated;
                $currentrow->endcgeolat = $data[$a + $i]->cgeolat;
                $currentrow->endcgeolong = $data[$a + $i]->cgeolong;
                $gen_report[$counter]->duration = round(getduration($gen_report[$counter]->endtime, $gen_report[$counter]->starttime), 0);
                $currentrow->starttime = $data[$a + $i]->lastupdated;
                $currentrow->startcgeolat = $data[$a + $i]->cgeolat;
                $currentrow->startcgeolong = $data[$a + $i]->cgeolong;
            }
            $currentrow->digitalio = $data[$a + $i]->digitalio;
            $currentrow->extradigitalio = $data[$a + $i]->extradigitalio;
            $currentrow->ignition = $data[$a + $i]->ignition;
            $currentrow->distancetravelled = $data[$a + $i]->distancetravelled;
            $a += $i;
        } else {
            break;
        }
        $counter += 1;
    }
    //var_dump($gen_report);
    //Clubing Data With Similar AC & Ignition [Both Together]
    //$gen_report = optimizerep_clean($gen_report);
    return $gen_report;
}

function checkdate_status($data, $currentrow, $entire_array, $currentrowcount) {
    $duration = getduration($data->lastupdated, $currentrow->starttime);
    if ($duration > 3) {
        return FALSE;
    } else {
        if (isset($entire_array[$currentrowcount + 1])) {
            if (getduration($entire_array[$currentrowcount + 1]->lastupdated, $currentrow->starttime) > 3) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
        return FALSE;
    }
}

function optimizerep_clean($gen_report) {
    while (TRUE) {
        $gen_report = markremove($gen_report);
        $remove = 0;
        $count = count($gen_report);
        for ($i = 0; $i <= $count; $i++) {
            if (isset($gen_report[$i]) && $gen_report[$i] == 'Remove') {
                $remove += 1;
                unset($gen_report[$i]);
            }
        }
        if ($remove != 0) {
            $a = $gen_report;
            $gen_report = array();
            $i = 0;
            if (isset($a)) {
                foreach ($a as $value) {
                    $gen_report[$i] = $value;
                    $i += 1;
                }
            }
        } else {
            break;
        }
    }
    $i = 0;
    $array_length = count($gen_report);
    while (TRUE) {
        if ($i < $array_length - 1) {
            if (isset($gen_report[$i]) && $gen_report[$i]->duration < 3) {
                $gen_report[$i - 1]->endtime = $gen_report[$i]->endtime;
                $gen_report[$i - 1]->duration = round(getduration($gen_report[$i - 1]->endtime, $gen_report[$i - 1]->starttime), 0);
                unset($gen_report[$i]);
            }
        } else {
            break;
        }
        $i += 1;
    }
    $a = $gen_report;
    $gen_report = array();
    $i = 0;
    if (isset($a)) {
        foreach ($a as $value) {
            $gen_report[$i] = $value;
            $i += 1;
        }
    }
    return $gen_report;
}

function markremove($gen_report) {
    //var_dump($gen_report);
    $i = 0;
    while (TRUE) {
        if (isset($gen_report[$i]) && isset($gen_report[$i + 1]) && $gen_report[$i] != 'Remove') {
            if ($gen_report[$i]->ignition == $gen_report[$i + 1]->ignition && $gen_report[$i]->digitalio == $gen_report[$i + 1]->digitalio) {
                $gen_report[$i]->endtime = $gen_report[$i + 1]->endtime;
                $gen_report[$i]->duration = round(getduration($gen_report[$i]->endtime, $gen_report[$i]->starttime), 0);
                $gen_report[$i + 1] = 'Remove';
            }
        } elseif (isset($gen_report[$i]) && $gen_report[$i] == 'Remove') {
            if (isset($gen_report[$i - 1]) && isset($gen_report[$i + 1])) {
                if (gettype($gen_report[$i - 1]) == 'object' && $gen_report[$i - 1]->ignition == $gen_report[$i + 1]->ignition && $gen_report[$i - 1]->digitalio == $gen_report[$i + 1]->digitalio) {
                    $gen_report[$i - 1]->endtime = $gen_report[$i + 1]->endtime;
                    $gen_report[$i - 1]->duration = round(getduration($gen_report[$i + 1]->endtime, $gen_report[$i - 1]->starttime), 0);
                    $gen_report[$i + 1] = 'Remove';
                }
            }
        } else {
            break;
        }
        $i += 1;
    }
    return $gen_report;
}

function getacdata_fromsqlite($location, $deviceid) {
    $devices = array();
    $query = "SELECT devicehistory.ignition, devicehistory.status, devicehistory.lastupdated,
                                                                                                    unithistory.digitalio from devicehistory INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
                                                                                                    WHERE devicehistory.deviceid= $deviceid AND (devicehistory.status IS NULL OR devicehistory.status!='F')  group by devicehistory.lastupdated";
    try {
        $database = new PDO($location);
        $query1 = $query . " ORDER BY devicehistory.lastupdated ASC";
        $result = $database->query($query1);
        $laststatus = array();
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                if (@$laststatus['digitalio'] != $row['digitalio'] || @$laststatus['ig'] != $row['ignition']) {
                    $device = new VODevices();
                    $device->ignition = $row['ignition'];
                    $device->status = $row['status'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->digitalio = $row['digitalio'];
                    $laststatus['ig'] = $row['ignition'];
                    $laststatus['digitalio'] = $row['digitalio'];
                    $devices[] = $device;
                }
            }
        }
        $query2 = $query . " ORDER BY devicehistory.lastupdated DESC Limit 0,1";
        $result = $database->query($query2);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $device = new VODevices();
                $device->ignition = $row['ignition'];
                $device->status = $row['status'];
                $device->lastupdated = $row['lastupdated'];
                $device->digitalio = $row['digitalio'];
                $devices2 = $device;
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    $devices[] = $devices2;
    return $devices;
}

function getgensetdata_fromsqlite($location, $deviceid, $startTime = null, $endtime = null, $gensetSensor = null) {
    $um = new UnitManager($_SESSION['customerno']);
    $unit = $um->getunitdetailsfromdeviceid($deviceid);
    $devices = array();
    if ($unit->fuelsensor != 0) {
        $min_c = $unit->fuel_min_volt; //value for empty voltage
        $max_c = $unit->fuel_max_volt; //value for max voltage
        $one = ($min_c + $max_c) / 100; //value for 1 %
        $fuelsensor = ",unithistory.analog$unit->fuelsensor as fuelvalue";
    } else {
        $fuelsensor = "";
    }

    $locationNotFound = '';
    if ($unit->kind != "Warehouse") {
        $locationNotFound = " AND devicelat <> '0.000000' AND devicelong <> '0.000000'";
    }
    $timeCondition = '';
    if (isset($startTime)) {
        $timeCondition .= " AND devicehistory.lastupdated > '" . $startTime . "'  ";
    }
    if (isset($endtime)) {
        $timeCondition .= " AND devicehistory.lastupdated < '" . $endtime . "'  ";
    }

    $query = "SELECT devicehistory.ignition, devicehistory.status, devicehistory.lastupdated, devicehistory.devicelat, devicehistory.devicelong,
        unithistory.digitalio, unithistory.analog1 as extradigitalio " . $fuelsensor . ", vehiclehistory.odometer,vehiclehistory.curspeed AS speed
        from devicehistory
        INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
        INNER JOIN vehiclehistory ON vehiclehistory.vehiclehistoryid = devicehistory.id
        WHERE devicehistory.deviceid= $deviceid AND (devicehistory.status IS NULL OR devicehistory.status!='F')  " . $locationNotFound . $timeCondition . "
        group by devicehistory.lastupdated";
    try {
        $database = new PDO($location);
        $query1 = $query . " ORDER BY devicehistory.lastupdated ASC";
        $result = $database->query($query1);
        $laststatus = array();
        if (isset($result) && $result != "") {
            $i = 0;
            foreach ($result as $key => $row) {
                $reading = (int) ($row['extradigitalio'] / 100);

                if ($reading > 0) {
                    $row['extradigitalio'] = 1;
                } else {
                    $row['extradigitalio'] = 0;
                }

                //echo $row['extradigitalio'];
                if (isset($gensetSensor) && $gensetSensor == 2) {
                    if ((@$laststatus['extradigitalio'] != $row['extradigitalio']) || (@$laststatus['ignition'] != $row['ignition'])) {
                        $fuel_ltr = "";
                        if ($unit->fuelsensor != 0) {
                            $fuel_consumed = round($row['fuelvalue'] / $one, 2);
                            $dimension = 256.122; /// [17cm*162cm*93cm = 256122 cm to ltr 256.122]
                            $fuel_ltr = round(256.122 * ($fuel_consumed / 100), 2);
                            if ($fuel_consumed > 100) {
                                //skipping wrong data
                                continue;
                            }
                        }

                        /* Code for calculating distance travelled starts here */

                        if ($key == 0) {
                            $laststatus['startspeed'] = $row['speed'];
                            $laststatus['startodo'] = $row['odometer'];
                            $laststatus['distancetravelled'] = 0;
                        }
                        if ($key != 0) {
                            if ($laststatus['startspeed'] >= 0) {
                                if ($$laststatus['endodo'] < $laststatus['startodo']) {
                                    $date = date('Y-m-d', strtotime($row['lastupdated']));
                                    $max = GetOdometerMax($date, $unit->unitno);
                                    $laststatus['endodo'] = $max + $laststatus['endodo'];
                                }
                                $distanceTravelled = round(($laststatus['endodo'] / 1000 - $laststatus['startodo'] / 1000), 2);
                                if ($distanceTravelled > 0.1) {
                                    $laststatus['distancetravelled'] = $distanceTravelled;
                                }
                            }
                        }
                        /* Code for calculating distance travelled ends here */

                        $device = new VODevices();
                        $device->ignition = $row['ignition'];
                        $device->status = $row['status'];
                        $device->lastupdated = $row['lastupdated'];
                        $device->digitalio = $row['digitalio'];
                        $device->extradigitalio = $row['extradigitalio'];
                        $device->cgeolat = $row['devicelat'];
                        $device->cgeolong = $row['devicelong'];
                        $device->distancetravelled = $laststatus['distancetravelled'];
                        $laststatus['ig'] = $row['ignition'];
                        $laststatus['digitalio'] = $row['digitalio'];
                        $laststatus['extradigitalio'] = $row['extradigitalio'];

                        //Added for ignition status
                        $laststatus['ignition'] = $row['ignition'];

                        $device->fuelltr = $fuel_ltr;
                        $devices[] = $device;
                    } elseif ($i == 0) {
                        $fuel_ltr = "";
                        if ($unit->fuelsensor != 0) {
                            $fuel_consumed = round($row['fuelvalue'] / $one, 2);
                            $dimension = 256.122; /// [17cm*162cm*93cm = 256122 cm to ltr 256.122]
                            $fuel_ltr = round(256.122 * ($fuel_consumed / 100), 2);
                            if ($fuel_consumed > 100) {
                                //skipping wrong data
                                continue;
                            }
                        }

                        /* Code for calculating distance travelled starts here */

                        if ($key == 0) {
                            $laststatus['startspeed'] = $row['speed'];
                            $laststatus['startodo'] = $row['odometer'];
                            $laststatus['distancetravelled'] = 0;
                        }

                        if ($key != 0) {
                            if ($laststatus['startspeed'] >= 0) {
                                if ($$laststatus['endodo'] < $laststatus['startodo']) {
                                    $date = date('Y-m-d', strtotime($row['lastupdated']));
                                    $max = GetOdometerMax($date, $unit->unitno);
                                    $laststatus['endodo'] = $max + $laststatus['endodo'];
                                }
                                $distanceTravelled = round(($laststatus['endodo'] / 1000 - $laststatus['startodo'] / 1000), 2);
                                if ($distanceTravelled > 0.1) {
                                    $laststatus['distancetravelled'] = $distanceTravelled;
                                }
                            }
                        }
                        /* Code for calculating distance travelled ends here */

                        $device = new VODevices();
                        $device->ignition = $row['ignition'];
                        $device->status = $row['status'];
                        $device->lastupdated = $row['lastupdated'];
                        $device->digitalio = $row['digitalio'];
                        $device->extradigitalio = $row['extradigitalio'];
                        $device->cgeolat = $row['devicelat'];
                        $device->cgeolong = $row['devicelong'];
                        $device->distancetravelled = $laststatus['distancetravelled'];
                        $laststatus['ig'] = $row['ignition'];
                        $laststatus['digitalio'] = $row['digitalio'];
                        $laststatus['extradigitalio'] = $row['extradigitalio'];

                        //Added for ignition status
                        $laststatus['ignition'] = $row['ignition'];

                        $device->fuelltr = $fuel_ltr;
                        $devices[] = $device;
                    }
                    $i++;
                } else {
                    if ((@$laststatus['digitalio'] != $row['digitalio']) || (@$laststatus['ignition'] != $row['ignition'])) {
                        $fuel_ltr = "";
                        if ($unit->fuelsensor != 0) {
                            $fuel_consumed = round($row['fuelvalue'] / $one, 2);
                            $dimension = 256.122; /// [17cm*162cm*93cm = 256122 cm to ltr 256.122]
                            $fuel_ltr = round(256.122 * ($fuel_consumed / 100), 2);
                            if ($fuel_consumed > 100) {
                                //skipping wrong data
                                continue;
                            }
                        }

                        /* Code for calculating distance travelled starts here */

                        if ($key == 0) {
                            @$laststatus['startspeed'] = $row['speed'];
                            @$laststatus['startodo'] = $row['odometer'];
                            @$laststatus['distancetravelled'] = 0;
                        }

                        if ($key != 0) {
                            if (@$laststatus['startspeed'] >= 0) {
                                if (@$laststatus['endodo'] < @$laststatus['startodo']) {
                                    $date = date('Y-m-d', strtotime($row['lastupdated']));
                                    $max = GetOdometerMax($date, $unit->unitno);
                                    @$laststatus['endodo'] = $max+@$laststatus['endodo'];
                                }
                                $distanceTravelled = round(($laststatus['endodo'] / 1000 - $laststatus['startodo'] / 1000), 2);
                                if ($distanceTravelled > 0.1) {
                                    $laststatus['distancetravelled'] = $distanceTravelled;
                                }
                            }
                        }
                        /* Code for calculating distance travelled ends here */

                        $device = new VODevices();
                        $device->ignition = $row['ignition'];
                        $device->status = $row['status'];
                        $device->lastupdated = $row['lastupdated'];
                        $device->digitalio = $row['digitalio'];
                        $device->extradigitalio = $row['extradigitalio'];
                        $device->cgeolat = $row['devicelat'];
                        $device->cgeolong = $row['devicelong'];
                        $device->distancetravelled = $laststatus['distancetravelled'];
                        $laststatus['ig'] = $row['ignition'];
                        $laststatus['digitalio'] = $row['digitalio'];
                        $laststatus['extradigitalio'] = $row['extradigitalio'];

                        //Added for ignition status
                        $laststatus['ignition'] = $row['ignition'];

                        $device->fuelltr = $fuel_ltr;
                        $devices[] = $device;
                    } elseif ($i == 0) {
                        $fuel_ltr = "";
                        if ($unit->fuelsensor != 0) {
                            $fuel_consumed = round($row['fuelvalue'] / $one, 2);
                            $dimension = 256.122; /// [17cm*162cm*93cm = 256122 cm to ltr 256.122]
                            $fuel_ltr = round(256.122 * ($fuel_consumed / 100), 2);
                            if ($fuel_consumed > 100) {
                                //skipping wrong data
                                continue;
                            }
                        }

                        /* Code for calculating distance travelled starts here */

                        if ($key == 0) {
                            $laststatus['startspeed'] = $row['speed'];
                            $laststatus['startodo'] = $row['odometer'];
                            $laststatus['distancetravelled'] = 0;
                        }

                        if ($key != 0) {
                            if ($laststatus['startspeed'] >= 0) {
                                if ($$laststatus['endodo'] < $laststatus['startodo']) {
                                    $date = date('Y-m-d', strtotime($row['lastupdated']));
                                    $max = GetOdometerMax($date, $unit->unitno);
                                    $laststatus['endodo'] = $max + $laststatus['endodo'];
                                }
                                $distanceTravelled = round(($laststatus['endodo'] / 1000 - $laststatus['startodo'] / 1000), 2);
                                if ($distanceTravelled > 0.1) {
                                    $laststatus['distancetravelled'] = $distanceTravelled;
                                }
                            }
                        }
                        /* Code for calculating distance travelled ends here */

                        $device = new VODevices();
                        $device->ignition = $row['ignition'];
                        $device->status = $row['status'];
                        $device->lastupdated = $row['lastupdated'];
                        $device->digitalio = $row['digitalio'];
                        $device->extradigitalio = $row['extradigitalio'];
                        $device->cgeolat = $row['devicelat'];
                        $device->cgeolong = $row['devicelong'];
                        $device->distancetravelled = $laststatus['distancetravelled'];
                        $laststatus['ig'] = $row['ignition'];
                        $laststatus['digitalio'] = $row['digitalio'];
                        $laststatus['extradigitalio'] = $row['extradigitalio'];

                        //Added for ignition status
                        $laststatus['ignition'] = $row['ignition'];

                        $device->fuelltr = $fuel_ltr;
                        $devices[] = $device;
                    }
                    $i++;
                }

                //die();
            }
        }
        $query2 = $query . " ORDER BY devicehistory.lastupdated DESC Limit 0,1";
        $result = $database->query($query2);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $reading = (int) ($row['extradigitalio'] / 100);

                if ($reading > 0) {
                    $row['extradigitalio'] = 1;
                } else {
                    $row['extradigitalio'] = 0;
                }

                $device = new VODevices();
                $device->ignition = $row['ignition'];
                $device->status = $row['status'];
                $device->lastupdated = $row['lastupdated'];
                $device->digitalio = $row['digitalio'];
                $device->extradigitalio = $row['extradigitalio'];
                $device->cgeolat = $row['devicelat'];
                $device->cgeolong = $row['devicelong'];
                $device->distancetravelled = 0;
                //$devices2 = $device;
                $devices[] = $device;
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    //$devices[] = $devices2;
    //echo "<pre>"; print_r(json_decode(json_encode($devices))); exit();
    return $devices;
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

function getextradata_fromsqlite($location, $deviceid) {
    $um = new UnitManager($_SESSION['customerno']);
    $unit = $um->getunitdetailsfromdeviceid($deviceid);
    $locationNotFound = '';
    if ($unit->kind != "Warehouse") {
        $locationNotFound = " AND devicelat <> '0.000000' AND devicelong <> '0.000000'";
    }
    $devices = array();
    $query = "SELECT devicehistory.ignition, devicehistory.status, devicehistory.lastupdated, devicehistory.devicelat, devicehistory.devicelong,
    unithistory.digitalio from devicehistory INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
    WHERE devicehistory.deviceid= $deviceid AND (devicehistory.status IS NULL OR devicehistory.status!='F')  " . $locationNotFound . " group by devicehistory.lastupdated";
    try {
        $database = new PDO($location);
        $query1 = $query . " ORDER BY devicehistory.lastupdated ASC";
        $result = $database->query($query1);
        $laststatus = array();
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                if (@$laststatus['digitalio'] != $row['digitalio']) {
                    $device = new VODevices();
                    $device->ignition = $row['ignition'];
                    $device->status = $row['status'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->digitalio = $row['digitalio'];
                    $device->cgeolat = $row['devicelat'];
                    $device->cgeolong = $row['devicelong'];
                    $laststatus['ig'] = $row['ignition'];
                    $laststatus['digitalio'] = $row['digitalio'];
                    $devices[] = $device;
                }
            }
        }
        $query2 = $query . " ORDER BY devicehistory.lastupdated DESC Limit 0,1";
        $result = $database->query($query2);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $device = new VODevices();
                $device->ignition = $row['ignition'];
                $device->status = $row['status'];
                $device->lastupdated = $row['lastupdated'];
                $device->digitalio = $row['digitalio'];
                $device->cgeolat = $row['devicelat'];
                $device->cgeolong = $row['devicelong'];
                $devices2 = $device;
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    $devices[] = $devices2;
    return $devices;
}

function getextragensetdata_fromsqlite($location, $deviceid) {
    $um = new UnitManager($_SESSION['customerno']);
    $unit = $um->getunitdetailsfromdeviceid($deviceid);
    $locationNotFound = '';
    if ($unit->kind != "Warehouse") {
        $locationNotFound = " AND devicelat <> '0.000000' AND devicelong <> '0.000000'";
    }
    $devices = array();
    $query = "SELECT devicehistory.ignition, devicehistory.status, devicehistory.lastupdated, devicehistory.devicelat, devicehistory.devicelong,
    unithistory.digitalio from devicehistory INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
    WHERE devicehistory.deviceid= $deviceid AND (devicehistory.status IS NULL OR devicehistory.status!='F')  " . $locationNotFound . " group by devicehistory.lastupdated";
    try {
        $database = new PDO($location);
        $query1 = $query . " ORDER BY devicehistory.lastupdated ASC";
        $result = $database->query($query1);
        $laststatus = array();
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                if (@$laststatus['digitalio'] != $row['digitalio']) {
                    $device = new VODevices();
                    $device->ignition = $row['ignition'];
                    $device->status = $row['status'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->digitalio = $row['digitalio'];
                    $device->cgeolat = $row['devicelat'];
                    $device->cgeolong = $row['devicelong'];
                    if (isset($_SESSION['customerno'])) {
                        $device->uselocation = get_usegeolocation($_SESSION['customerno']);
                    }
                    $laststatus['ig'] = $row['ignition'];
                    $laststatus['digitalio'] = $row['digitalio'];
                    $devices[] = $device;
                }
            }
        }
        $query2 = $query . " ORDER BY devicehistory.lastupdated DESC Limit 0,1";
        $result = $database->query($query2);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $device = new VODevices();
                $device->ignition = $row['ignition'];
                $device->status = $row['status'];
                $device->lastupdated = $row['lastupdated'];
                $device->digitalio = $row['digitalio'];
                $device->cgeolat = $row['devicelat'];
                $device->cgeolong = $row['devicelong'];
                $devices2 = $device;
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    $devices[] = $devices2;
    return $devices;
}

function get_min_temperature($temp, $get_data = false) {
    static $min_temp = 50;
    if (!is_null($temp) && !$get_data && $temp < $min_temp) {
        $min_temp = round($temp);
    }
    return $min_temp;
}

function get_max_temperature($temp, $get_data = false) {
    static $max_temp;
    if (!$get_data && $temp > $max_temp) {
        $max_temp = round($temp);
    }
    return $max_temp;
}

function set_temp_graph_data($updated_date, $unit_type, $unit, $analog1, $analog2, $analog3, $analog4, $only_date = false, $tempSelected = 1, $noOfSensors = null, $switchTo = 0) {
    $str_ch = strtotime($updated_date);
    $yr = date('Y', $str_ch);
    $mth = date('m', $str_ch) - 1;
    $day = date('d', $str_ch);
    $hour = date('H', $str_ch);
    $mins = date('i', $str_ch);
    $temp = null;
    $cm = new CustomerManager();
    $details = $cm->getcustomerdetail_byid($_SESSION['customerno']);

    $noOfSensors = isset($details->temp_sensors) ? $details->temp_sensors : $noOfSensors;
    $tempconversion = new TempConversion();
    $tempconversion->unit_type = $unit_type;
    $tempconversion->use_humidity = $details->use_humidity;
    $details->switch_to = $switchTo;
    $tempconversion->switch_to = $details->switch_to;
    if ($only_date) {
        return "[Date.UTC($yr, $mth, $day, $hour, $mins), $only_date]";
    }
    $temp1 = null;
    $temp2 = null;
    $temp3 = null;
    $temp4 = null;
    switch ($noOfSensors) {
        case 4:
            $s = "analog" . $unit->tempsen4;
            if ($unit->tempsen4 != 0 && $$s != 0) {
                $tempconversion->rawtemp = $$s;
                $temp4 = getTempUtil($tempconversion);
            }
        case 3:
            $s = "analog" . $unit->tempsen3;
            if ($unit->tempsen3 != 0 && $$s != 0) {
                $tempconversion->rawtemp = $$s;
                $temp3 = getTempUtil($tempconversion);
            }
        case 2:
            $s = "analog" . $unit->tempsen2;
            if ($unit->tempsen2 != 0 && $$s != 0) {
                $tempconversion->rawtemp = $$s;
                $temp2 = getTempUtil($tempconversion);
            }
        case 1:
            $s = "analog" . $unit->tempsen1;
            if ($unit->tempsen1 != 0 && $$s != 0) {
                $tempconversion->rawtemp = $$s;
                $temp1 = getTempUtil($tempconversion);
            }
        default:
            $s = "analog" . $unit->tempsen1;
            if ($unit->tempsen1 != 0 && $$s != 0) {
                $tempconversion->rawtemp = $$s;
                $temp1 = getTempUtil($tempconversion);
            }
    }
    if ($tempSelected == 1) {
        $temp = $temp1;
    } elseif ($tempSelected == 2) {
        $temp = $temp2;
    } elseif ($tempSelected == 3) {
        $temp = $temp3;
    } elseif ($tempSelected == 4) {
        $temp = $temp4;
    } else {
        $temp = $temp1;
    }
    /**/
    if (!is_null($temp) && $temp != '0' && $temp != '-' && $temp < NORMAL_MAX_TEMP && $temp > NORMAL_MIN_TEMP) {
        get_min_temperature($temp);
        get_max_temperature($temp);
        return "[Date.UTC($yr, $mth, $day, $hour, $mins), $temp]";
    } else {
        return null;
    }
}

function set_humidity_graph_data($updated_date, $unit_type, $unit, $analog1, $analog2, $analog3, $analog4, $only_date = false) {
    $str_ch = strtotime($updated_date);
    $yr = date('Y', $str_ch);
    $mth = date('m', $str_ch) - 1;
    $day = date('d', $str_ch);
    $hour = date('H', $str_ch);
    $mins = date('i', $str_ch);
    $temp = null;
    $tempconversion = new TempConversion();
    $tempconversion->unit_type = $unit_type;
    $tempconversion->use_humidity = $_SESSION['use_humidity'];
    $tempconversion->switch_to = $_SESSION['switch_to'];
    if ($only_date) {
        return "[Date.UTC($yr, $mth, $day, $hour, $mins), $only_date]";
    }
    if ($_SESSION['use_humidity'] == 1) {
        $s = "analog" . $unit->humidity;
        if ($unit->humidity != 0 && $$s != 0) {
            $tempconversion->rawtemp = $$s;
            $temp = getTempUtil($tempconversion);
        }
    }
    /**/
    if (!is_null($temp) && $temp != '0' && $temp != '-') {
        get_min_temperature($temp);
        get_max_temperature($temp);
        return "[Date.UTC($yr, $mth, $day, $hour, $mins), $temp]";
    } else {
        return null;
    }
}

function gettempdata_fromsqlite($location, $deviceid, $interval, $startdate, $enddate, $graph = false, $unit = null, $customerno = null, $isApi = null, $noOfSensors = 0, $switchTo = 0) {
    $devices = array();
    $graph_devices = array();
    $graph_devices_temp1 = array();
    $graph_devices_temp2 = array();
    $graph_devices_temp3 = array();
    $graph_devices_temp4 = array();
    $graph_ignition = array();
    $last_ignition = null;
    $customerno = isset($customerno) ? $customerno : $_SESSION['customerno'];
    $_SESSION['customerno'] = $customerno;
    if (!isset($unit)) {
        $um = new UnitManager($customerno);
        $unit = $um->getunitdetailsfromdeviceid($deviceid);
    }
    $locationNotFound = '';
    if ($unit->kind != "Warehouse") {
        $locationNotFound = " AND devicelat <> '0.000000' AND devicelong <> '0.000000'";
    }
    $unit_type = $unit->get_conversion;
    $query = "SELECT devicehistory.ignition AS ignition
                        , devicehistory.lastupdated AS lastupdated
                        , devicehistory.devicelat AS devicelat
                        , devicehistory.devicelong AS devicelong
                        , u.unitno AS unitno
                        , u.vehicleid AS vehicleid
                        , u.digitalio AS digitalio
                        , u.analog1 AS analog1
                        , u.analog2 AS analog2
                        , u.analog3 AS analog3
                        , u.analog4 AS analog4
                        , v.odometer AS odometer
                FROM devicehistory
                INNER JOIN (SELECT  unitno,vehicleid,digitalio,analog1,analog2,analog3,analog4,lastupdated
                            FROM    unithistory
                            WHERE   uhid IN (SELECT vehiclehistoryid
                                                FROM    vehiclehistory
                                                GROUP BY strftime('%d-%m-%Y %H:%M' , lastupdated) HAVING odometer = MAX(odometer))) AS u ON u.lastupdated = devicehistory.lastupdated
                INNER JOIN (SELECT lastupdated,odometer
                            FROM    vehiclehistory
                            GROUP BY strftime('%d-%m-%Y %H:%M' , lastupdated) HAVING odometer = MAX(odometer)) as v ON devicehistory.lastupdated = v.lastupdated
                WHERE   devicehistory.lastupdated BETWEEN '$startdate' AND '$enddate'
                AND     devicehistory.deviceid= $deviceid
                AND (devicehistory.status IS NULL OR devicehistory.status!='F')
                " . $locationNotFound . "
                GROUP BY devicehistory.lastupdated";

    try {
        $database = new PDO($location);
        $query1 = $query . " ORDER BY devicehistory.lastupdated ASC";
        $result = $database->query($query1);
        if (isset($result) && $result != "") {
            $total = 0;
            foreach ($result as $row) {
                if (!isset($vehicleid)) {
                    $vehicleid = $row['vehicleid'];
                }
                $total++;
                $device = new VODevices();
                if (isset($row['ignition']) && $row['ignition'] == 'ON') {
                    $row['ignition'] = '1';
                }
                if (isset($row['ignition']) && $row['ignition'] == 'OFF') {
                    $row['ignition'] = '0';
                }
                if ((!isset($lastupdated)) || (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) >= $interval)) {
                    $device->unitno = $row['unitno'];
                    $device->analog1 = $row['analog1'];
                    $device->analog2 = $row['analog2'];
                    $device->analog3 = $row['analog3'];
                    $device->analog4 = $row['analog4'];
                    $device->starttime = $row['lastupdated'];
                    $device->endtime = $row['lastupdated'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->digitalio = $row['digitalio'];
                    $device->odometer = $row['odometer'];
                    $devices[] = $device;
                    if ($graph) {
                        $temp_val1 =
                            set_temp_graph_data($row['lastupdated'], $unit_type, $unit, $row['analog1'], $row['analog2'], $row['analog3'], $row['analog4'], false, 1, $noOfSensors, $switchTo);

                        $temp_val2 =
                            set_temp_graph_data($row['lastupdated'], $unit_type, $unit, $row['analog1'], $row['analog2'], $row['analog3'], $row['analog4'], false, 2, $noOfSensors, $switchTo);

                        $temp_val3 =
                            set_temp_graph_data($row['lastupdated'], $unit_type, $unit, $row['analog1'], $row['analog2'], $row['analog3'], $row['analog4'], false, 3, $noOfSensors, $switchTo);

                        $temp_val4 =
                            set_temp_graph_data($row['lastupdated'], $unit_type, $unit, $row['analog1'], $row['analog2'], $row['analog3'], $row['analog4'], false, 4, $noOfSensors, $switchTo);
                        if (!is_null($temp_val1)) {
                            $graph_devices_temp1[] = $temp_val1;
                        }
                        if (!is_null($temp_val2)) {
                            $graph_devices_temp2[] = $temp_val2;
                        }
                        if (!is_null($temp_val3)) {
                            $graph_devices_temp3[] = $temp_val3;
                        }
                        if (!is_null($temp_val4)) {
                            $graph_devices_temp4[] = $temp_val4;
                        }
                    }
                    $lastupdated = $row['lastupdated'];
                }

                if ($isApi != 1) {
                    if ($last_ignition != $row['ignition']) {
                        if (isset($ig_lastupdated)) {
                            $graph_ignition[] = set_temp_graph_data($row['lastupdated'], $unit_type, null, null, null, null, null, "#$last_ignition#", 1, $noOfSensors, $switchTo);
                        }
                        $graph_ignition[] = set_temp_graph_data($row['lastupdated'], $unit_type, null, null, null, null, null, "#{$row['ignition']}#", 1, $noOfSensors, $switchTo);
                        $last_ignition = $row['ignition'];
                        $ig_lastupdated = $row['lastupdated'];
                    }
                }
            }
            //$graph_ignition[] = set_temp_graph_data($row['lastupdated'], null, null, null, null, null, "#{$row['ignition']}#");
            $graph_devices = array($graph_devices_temp1, $graph_devices_temp2, $graph_devices_temp3, $graph_devices_temp4);
        }
    } catch (PDOException $e) {
        die($e);
    }
    $vehicleid = isset($vehicleid) ? $vehicleid : 0;
    if ($graph) {
        return array($devices, $graph_devices, 'vehicleid' => $vehicleid, 'ig_graph' => $graph_ignition);
    } else {
        return array($devices, 'vehicleid' => $vehicleid);
    }
}

function gettempdata_fromsqlite_cron($location, $deviceid, $interval, $startdate, $enddate, $customerno = null) {
    $devices = array();
    $graph_devices = array();
    $graph_ignition = array();
    $last_ignition = null;
    $customerno = isset($customerno) ? $customerno : $_SESSION['customerno'];
    $um = new UnitManager($customerno);
    $unit = $um->getunitdetailsfromdeviceid($deviceid);
    $locationNotFound = '';
    $unit_type = $unit->get_conversion;
    $query = "SELECT devicehistory.ignition, devicehistory.lastupdated, devicehistory.devicelat, devicehistory.devicelong
    , unithistory.unitno ,unithistory.vehicleid,unithistory.digitalio, unithistory.analog1, unithistory.analog2, unithistory.analog3, unithistory.analog4
    from devicehistory INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
    WHERE devicehistory.lastupdated BETWEEN '$startdate' AND '$enddate' AND devicehistory.deviceid= $deviceid AND (devicehistory.status IS NULL OR devicehistory.status!='F')   " . $locationNotFound . " group by devicehistory.lastupdated";

    try {
        $database = new PDO($location);
        $query1 = $query . " ORDER BY devicehistory.lastupdated ASC";
        $result = $database->query($query1);

        if (isset($result) && $result != "") {
            $total = 0;
            foreach ($result as $row) {
                if (!isset($vehicleid)) {
                    $vehicleid = $row['vehicleid'];
                }
                $total++;
                $device = new VODevices();
                if ((!isset($lastupdated)) || (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) >= $interval)) {
                    $device->unitno = $row['unitno'];
                    $device->analog1 = $row['analog1'];
                    $device->analog2 = $row['analog2'];
                    $device->analog3 = $row['analog3'];
                    $device->analog4 = $row['analog4'];
                    $device->starttime = $row['lastupdated'];
                    $device->endtime = $row['lastupdated'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->digitalio = $row['digitalio'];
                    $devices[] = $device;
                    $lastupdated = $row['lastupdated'];
                }
                if ($graph && round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) > 0) {
                    $temp_val = set_temp_graph_data($row['lastupdated'], $unit_type, $unit, $row['analog1'], $row['analog2'], $row['analog3'], $row['analog4'], false);
                    if (!is_null($temp_val)) {
                        $graph_devices[] = $temp_val;
                    }
                }
                if ($last_ignition != $row['ignition']) {
                    if (isset($ig_lastupdated)) {
                        $graph_ignition[] = set_temp_graph_data($row['lastupdated'], $unit_type, null, null, null, null, null, "#$last_ignition#");
                    }
                    $graph_ignition[] = set_temp_graph_data($row['lastupdated'], $unit_type, null, null, null, null, null, "#{$row['ignition']}#");
                    $last_ignition = $row['ignition'];
                    $ig_lastupdated = $row['lastupdated'];
                }
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    $vehicleid = isset($vehicleid) ? $vehicleid : 0;
    return array($devices, 'vehicleid' => $vehicleid);
}

function gethumiditydata_fromsqlite($location, $deviceid, $interval, $startdate, $enddate, $graph = false, $unit = null, $customerno = null) {
    $devices = array();
    $graph_devices = array();
    $graph_ignition = array();
    $last_ignition = null;
    $customerno = isset($customerno) ? $customerno : $_SESSION['customerno'];
    if (!isset($unit)) {
        $um = new UnitManager($customerno);
        $unit = $um->getunitdetailsfromdeviceid($deviceid);
    }

    $locationNotFound = '';
    if ($unit->kind != "Warehouse") {
        $locationNotFound = " AND devicelat <> '0.000000' AND devicelong <> '0.000000'";
    }
    $unit_type = $unit->get_conversion;
    //print_r($unit);die();
    $query = "SELECT devicehistory.ignition, devicehistory.lastupdated, devicehistory.devicelat, devicehistory.devicelong, unithistory.vehicleid,unithistory.digitalio,
    unithistory.analog1, unithistory.analog2, unithistory.analog3, unithistory.analog4 from devicehistory INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
    WHERE devicehistory.lastupdated BETWEEN '$startdate' AND '$enddate' AND devicehistory.deviceid= $deviceid AND (devicehistory.status IS NULL OR devicehistory.status!='F')  " . $locationNotFound . " group by devicehistory.lastupdated";

    try {
        $database = new PDO($location);
        $query1 = $query . " ORDER BY devicehistory.lastupdated ASC";
        $result = $database->query($query1);

        if (isset($result) && $result != "") {
            $total = 0;
            foreach ($result as $row) {
                if (!isset($vehicleid)) {
                    $vehicleid = $row['vehicleid'];
                }
                $total++;
                if (!isset($lastupdated)) {
                    $lastupdated = $row['lastupdated'];
                }
                if ($graph && round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) >= 1) {
                    $temp_val = set_humidity_graph_data($row['lastupdated'], $unit_type, $unit, $row['analog1'], $row['analog2'], $row['analog3'], $row['analog4'], false);
                    if (!is_null($temp_val)) {
                        $graph_devices[] = $temp_val;
                    }
                }
                if (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) >= $interval) {
                    $device = new VODevices();
                    $device->analog1 = $row['analog1'];
                    $device->analog2 = $row['analog2'];
                    $device->analog3 = $row['analog3'];
                    $device->analog4 = $row['analog4'];
                    $device->starttime = $row['lastupdated'];
                    $device->endtime = $row['lastupdated'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->digitalio = $row['digitalio'];
                    $devices[] = $device;
                    $lastupdated = $row['lastupdated'];
                }
                if ($last_ignition != $row['ignition']) {
                    if (isset($ig_lastupdated)) {
                        $graph_ignition[] = set_humidity_graph_data($row['lastupdated'], $unit_type, null, null, null, null, null, "#$last_ignition#");
                    }
                    $graph_ignition[] = set_humidity_graph_data($row['lastupdated'], $unit_type, null, null, null, null, null, "#{$row['ignition']}#");
                    $last_ignition = $row['ignition'];
                    $ig_lastupdated = $row['lastupdated'];
                }
            }
            //$graph_ignition[] = set_humidity_graph_data($row['lastupdated'], null, null, null, null, null, "#{$row['ignition']}#");
        }
    } catch (PDOException $e) {
        die($e);
    }
    $vehicleid = isset($vehicleid) ? $vehicleid : 0;
    if ($graph) {
        return array($devices, $graph_devices, 'vehicleid' => $vehicleid, 'ig_graph' => $graph_ignition);
    } else {
        return array($devices, 'vehicleid' => $vehicleid);
    }
}

function gettempExcep_fromsqlite($location, $deviceid, $interval, $startdate, $enddate, $graph = false, $unit = null, $customerno = null) {
    $devices = array();
    $graph_devices = array();
    $graph_ignition = array();
    $last_ignition = null;
    $customerno = isset($customerno) ? $customerno : $_SESSION['customerno'];
    if (!isset($unit)) {
        $um = new UnitManager($customerno);
        $unit = $um->getunitdetailsfromdeviceid($deviceid);
    }
    $locationNotFound = '';
    if ($unit->kind != "Warehouse") {
        $locationNotFound = " AND devicelat <> '0.000000' AND devicelong <> '0.000000'";
    }
    $unit_type = $unit->get_conversion;
    try {
        $query = "SELECT devicehistory.ignition, devicehistory.lastupdated, devicehistory.devicelat, devicehistory.devicelong, unithistory.vehicleid,unithistory.digitalio,
        unithistory.analog1, unithistory.analog2, unithistory.analog3, unithistory.analog4 from devicehistory INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
        WHERE devicehistory.lastupdated BETWEEN '$startdate' AND '$enddate' AND devicehistory.deviceid= $deviceid AND (devicehistory.status IS NULL OR devicehistory.status!='F')  " . $locationNotFound . " group by devicehistory.lastupdated";
        $database = new PDO($location);
        $result = $database->query($query);
        if (isset($result) && $result != "") {
            $total = 0;
            foreach ($result as $row) {
                $total++;
                if (!isset($lastupdated)) {
                    $lastupdated = $row['lastupdated'];
                }
                if ($graph && round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) >= 1) {
                    $temp_val = set_temp_graph_data($row['lastupdated'], $unit_type, $unit, $row['analog1'], $row['analog2'], $row['analog3'], $row['analog4'], false);
                    if (!is_null($temp_val)) {
                        $graph_devices[] = $temp_val;
                    }
                }
                if (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) >= $interval) {
                    $device = new VODevices();
                    $device->analog1 = $row['analog1'];
                    $device->analog2 = $row['analog2'];
                    $device->analog3 = $row['analog3'];
                    $device->analog4 = $row['analog4'];
                    $device->starttime = $row['lastupdated'];
                    $device->endtime = $row['lastupdated'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->digitalio = $row['digitalio'];
                    $devices[] = $device;
                    $lastupdated = $row['lastupdated'];
                }
                if ($last_ignition != $row['ignition']) {
                    if (isset($ig_lastupdated)) {
                        $graph_ignition[] = set_temp_graph_data($row['lastupdated'], $unit_type, null, null, null, null, null, "#$last_ignition#");
                    }
                    $graph_ignition[] = set_temp_graph_data($row['lastupdated'], $unit_type, null, null, null, null, null, "#{$row['ignition']}#");
                    $last_ignition = $row['ignition'];
                    $ig_lastupdated = $row['lastupdated'];
                }
            }
            $graph_ignition[] = set_temp_graph_data($row['lastupdated'], $unit_type, null, null, null, null, null, "#{$row['ignition']}#");
        }
    } catch (PDOException $e) {
        die($e);
    }
    if ($graph) {
        return array($devices, $graph_devices, 'vehicleid' => $row['vehicleid'], 'ig_graph' => $graph_ignition);
    } else {
        return array($devices, 'vehicleid' => $row['vehicleid']);
    }
}

function gendays($STdate, $EDdate) {
    $TOTALDAYS = array();
    $STdate = date("Y-m-d", strtotime($STdate));
    $EDdate = date("Y-m-d", strtotime($EDdate));
    while (strtotime($STdate) <= strtotime($EDdate)) {
        $TOTALDAYS[] = $STdate;
        $STdate = date("Y-m-d", strtotime($STdate . ' + 1 day'));
    }
    return $TOTALDAYS;
}

function genhours($STdate, $EDdate) {
    $TOTALDAYS = array();
    $STdate = date("Y-m-d 00:00:01", strtotime($STdate));
    $EDdate = date("Y-m-d 23:59:59", strtotime($EDdate));
    while (strtotime($STdate) <= strtotime($EDdate)) {
        $TOTALDAYS[] = $STdate;
        $STdate = date("Y-m-d H:s:i", strtotime($STdate . ' + 1 hour'));
    }
    //echo "<pre>".  print_r($TOTALDAYS)."</pre>";
    return $TOTALDAYS;
}

function getFuelTank($vehicleid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $tank = $vm->get_Fuel_Tank($vehicleid);
    return $tank;
}

function getFuelGauge($vehicleid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $fuel = $vm->getFueledVehicle_byID($vehicleid);
    return $fuel;
}

function getdailyreport($STdate, $EDdate, $vehicleid = null) {
    $totaldays = gendays($STdate, $EDdate);
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if (file_exists($location)) {
        $DATA = GetDailyReport_Data($location, $totaldays, $vehicleid);
    }
    return $DATA;
}

function getdailyreport_All($STdate, $EDdate, $vehicleid = NULL) {
    $totaldays = gendays($STdate, $EDdate);
    $customerno = $_SESSION['customerno']; ///  loged User Number//
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";

    $DATA = null;
    if (file_exists($location)) {
        //echo "test";
        //        $DATA = GetDailyReport_Data_All($location, $totaldays);
        $DATA = getDailyReportNew($location, $totaldays, $options = array('islocation' => 1, 'customerno' => array(), 'vehicleid' => $vehicleid, 'groupid' => array()));
    }
    return $DATA;
}

function getSqlitereport($STdate, $vehicleid) {
    //$totaldays = gendays($STdate, $EDdate);
    $unitno = getunitnotemp($vehicleid);

    $get_conversion = get_conversion($unitno);

    $customerno = $_SESSION['customerno']; ///  loged User Number//
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$STdate.sqlite";
    //echo "Date ".$STdate." Location ".$location." Vehicle ID".$vehicleid; die;

    $DATA = null;
    if (file_exists($location)) {
        //echo "Location"; die;
        //    $DATA = GetDailyReport_Data_All($location, $totaldays);
        $DATA = GetchangeSqliteReport_Data_All($location, $STdate, $options = array('islocation' => 1, 'customerno' => array(), 'vehicleid' => $vehicleid, 'groupid' => array()));

        //print("<pre>"); print_r($DATA); die;

        $tempData = array();

        if (isset($DATA) && !empty($DATA)) {
            foreach ($DATA as $data) {
                if (isset($data->analog1) && $data->analog1 != 0 && $data->analog1 != 1150) {
                    $tempconversion = new TempConversion();
                    $tempconversion->unit_type = $get_conversion;
                    $tempconversion->use_humidity = $_SESSION['use_humidity'];
                    $tempconversion->switch_to = $_SESSION['switch_to'];
                    //$s = "analog" . $vehicle->humidity;
                    $tempconversion->rawtemp = $data->analog1;
                    $temp1 = getTempUtil($tempconversion);
                } else {
                    $temp1 = 0;
                }
                if (isset($data->analog2) && $data->analog2 != 0 && $data->analog2 != 1150) {
                    $tempconversion = new TempConversion();
                    $tempconversion->unit_type = $get_conversion;
                    $tempconversion->use_humidity = $_SESSION['use_humidity'];
                    $tempconversion->switch_to = $_SESSION['switch_to'];
                    $tempconversion->rawtemp = $data->analog2;
                    $temp2 = getTempUtil($tempconversion);
                } else {
                    $temp2 = 0;
                }
                if (isset($data->analog3) && $data->analog3 != 0 && $data->analog3 != 1150) {
                    $tempconversion = new TempConversion();
                    $tempconversion->unit_type = $get_conversion;
                    $tempconversion->use_humidity = $_SESSION['use_humidity'];
                    $tempconversion->switch_to = $_SESSION['switch_to'];
                    $tempconversion->rawtemp = $data->analog3;
                    $temp3 = getTempUtil($tempconversion);
                } else {
                    $temp3 = 0;
                }
                if (isset($data->analog4) && $data->analog4 != 0 && $data->analog4 != 1150) {
                    $tempconversion = new TempConversion();
                    $tempconversion->unit_type = $get_conversion;
                    $tempconversion->use_humidity = $_SESSION['use_humidity'];
                    $tempconversion->switch_to = $_SESSION['switch_to'];
                    $tempconversion->rawtemp = $data->analog4;
                    $temp4 = getTempUtil($tempconversion);
                } else {
                    $temp4 = 0;
                }
                $std = new stdClass();
                $std->temp1 = $temp1;
                $std->temp2 = $temp2;
                $std->temp3 = $temp3;
                $std->temp4 = $temp4;

                $std->vehicleid = $data->vehicleid;
                $std->date = $data->date;
                $std->uhid = $data->uhid;
                $std->uid = $data->uid;

                $tempData[] = $std;
            }
            return $tempData;
        } else {
            $tempData = "";
        }
    }

    //return $DATA;
}

function generate_genset_graph($DATA) {
    $all_data = array();
    $chart_height = 200;
    foreach ($DATA as $report) {
        if (isset($all_data[$report->vehicleid])) {
            $all_data[$report->vehicleid] += $report->genset;
        } else {
            $all_data[$report->vehicleid] = $report->genset;
        }
    }
    $vehs = $total = '';
    $vm = new VehicleManager($_SESSION['customerno']);
    //print_r("<pre>"); print_r($all_data); die;

    foreach ($all_data as $vehid => $os) {
        $veh_no = $vm->get_vehicle_details($vehid);
        if (!isset($veh_no->vehicleno)) {
            continue;
        }
        $vehs .= "'$veh_no->vehicleno', ";
        $total .= "$os, ";
        $chart_height += 30;
    }

    return array('vehs' => $vehs, 'data' => $total, 'height' => $chart_height);
}

function generate_os_graph($DATA) {
    $all_data = array();
    $chart_height = 200;
    foreach ($DATA as $report) {
        if (isset($all_data[$report->vehicleid])) {
            $all_data[$report->vehicleid] += (int) $report->overspeed;
        } else {
            $all_data[$report->vehicleid] = (int) $report->overspeed;
        }
    }
    $vehs = $total = '';
    $vm = new VehicleManager($_SESSION['customerno']);
    foreach ($all_data as $vehid => $os) {
        $veh_no = $vm->get_vehicle_details($vehid);
        if (!isset($veh_no->vehicleno)) {
            continue;
        }
        $vehs .= "'$veh_no->vehicleno', ";
        $total .= "$os, ";
        $chart_height += 30;
    }
    return array('vehs' => $vehs, 'data' => $total, 'height' => $chart_height);
}

function generate_idletime_graph($DATA) {
    $all_data = array();
    $chart_height = 200;
    foreach ($DATA as $report) {
        if (isset($all_data[$report->vehicleid])) {
            $all_data[$report->vehicleid] += $report->idletime;
        } else {
            $all_data[$report->vehicleid] = $report->idletime;
        }
    }
    $vehs = $total = '';
    $vm = new VehicleManager($_SESSION['customerno']);
    foreach ($all_data as $vehid => $os) {
        $veh_no = $vm->get_vehicle_details($vehid);
        if (!isset($veh_no->vehicleno)) {
            continue;
        }
        $os = round(m2h($os));
        $vehs .= "'$veh_no->vehicleno', ";
        $total .= "$os, ";
        $chart_height += 30;
    }
    return array('vehs' => $vehs, 'data' => $total, 'height' => $chart_height);
}

function generate_fuel_graph($DATA) {
    $all_data = array();
    $chart_height = 200;
    foreach ($DATA as $report) {
        $fuel_consumed = ($report->average != 0) ? abs(round(($report->totaldistance / 1000) / $report->average, 2)) : 0;
        if (isset($all_data[$report->vehicleid])) {
            $all_data[$report->vehicleid] += $fuel_consumed;
        } else {
            $all_data[$report->vehicleid] = $fuel_consumed;
        }
    }
    $vehs = $total = '';
    $vm = new VehicleManager($_SESSION['customerno']);
    foreach ($all_data as $vehid => $os) {
        $veh_no = $vm->get_vehicle_details($vehid);
        if (!isset($veh_no->vehicleno)) {
            continue;
        }
        $vehs .= "'$veh_no->vehicleno', ";
        $total .= "$os, ";
        $chart_height += 30;
    }
    return array('vehs' => $vehs, 'data' => $total, 'height' => $chart_height);
}

function generate_fence_graph($DATA) {
    $all_data = array();
    $chart_height = 200;
    foreach ($DATA as $report) {
        if (isset($all_data[$report->vehicleid])) {
            $all_data[$report->vehicleid] += (int) $report->fenceconflict;
        } else {
            $all_data[$report->vehicleid] = (int) $report->fenceconflict;
        }
    }
    $vehs = $total = '';
    $vm = new VehicleManager($_SESSION['customerno']);
    foreach ($all_data as $vehid => $os) {
        $veh_no = $vm->get_vehicle_details($vehid);
        if (!isset($veh_no->vehicleno)) {
            continue;
            echo "</table>";
        }
        $vehs .= "'$veh_no->vehicleno', ";
        $total .= "$os, ";
        $chart_height += 30;
    }
    return array('vehs' => $vehs, 'data' => $total, 'height' => $chart_height);
}

function getideltimereportpdf($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if (file_exists($location)) {
        $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
    }
    if (isset($DATA)) {
        $title = 'IdleTime Analysis Report';
        $subTitle = array("Vehicle No: All Vehicles", "Start Date: $STdate", "End Date: $EDdate", 'Unit: HH:MM');
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        echo pdf_header($title, $subTitle);
        ?>
        <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
            <tr style="background-color: #CCCCCC;font-weight:bold;">
                <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                <?php
$SDate = $STdate;
        $STdate = date('d-m-Y', strtotime($STdate));
        $lastvehicle = array();
        $vehicles = GetVehicles_SQLite();
        while (strtotime($STdate) <= strtotime($EDdate)) {
            echo "<td style='height: 30px; vertical-align: middle;' >" . substr($STdate, 0, 5) . "</td>";
            $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
        }
        echo "<td style='height: 30px; vertical-align: middle;' >Total</td>";
        ?>
            </tr>
            <?php
$firstdate = $SDate;
        foreach ($DATA as $report) {
            $getin = true;
            if (isset($lastvehicle)) {
                foreach ($lastvehicle as $thisvehicle) {
                    if ($thisvehicle == $report->vehicleid) {
                        $getin = false;
                    }
                }
            }
            if (isset($vehicles[$report->vehicleid]['vehicleno']) == null) {
                $getin = false;
            }
            if ($getin == true) {
                $total = 0;
                $CompareDate = strtotime($firstdate);
                $SDatetemp = $firstdate;
                echo '<tr>';
                $id = $report->vehicleid;
                echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                foreach ($DATA as $variablerep) {
                    if ($report->vehicleid == $variablerep->vehicleid) {
                        while ($CompareDate <= $variablerep->date) {
                            if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                            } elseif ($CompareDate == $variablerep->date) {
                                if ($variablerep->idletime != 0) {
                                    echo "<td style='height: 30px; vertical-align: middle;'>" . m2h($variablerep->idletime) . "</td>";
                                } else {
                                    $variablerep->idletime = 1440;
                                    echo "<td style='height: 30px; vertical-align: middle;'>" . m2h($variablerep->idletime) . "</td>";
                                }
                                $total += $variablerep->idletime;
                            }
                            $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                            $CompareDate = strtotime($SDatetemp);
                        }
                    }
                }
                while ($CompareDate <= strtotime($EDdate)) {
                    echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                    $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                    $CompareDate = strtotime($SDatetemp);
                }
                echo "<td style='height: 30px; vertical-align: middle;'><b>" . m2h($total) . "</b></td>";
                echo "</tr>";
            }
            $lastvehicle[] = $report->vehicleid;
        }
        echo "</table>";
    }
}

function getideltimereportcsv($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if (file_exists($location)) {
        $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
    }
    if (isset($DATA)) {
        $title = 'Idle-Time Analysis Report';
        $subTitle = array("Vehicle No: All Vehicles", "Start Date: $STdate", "End Date: $EDdate", 'Unit: HH:MM');
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        echo excel_header($title, $subTitle);
        ?>
            <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tr style="background-color: #CCCCCC;font-weight:bold;">
                    <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                    <?php
$SDate = $STdate;
        $STdate = date('d-m-Y', strtotime($STdate));
        $lastvehicle = array();
        $vehicles = GetVehicles_SQLite();
        while (strtotime($STdate) <= strtotime($EDdate)) {
            echo "<td style='height: 30px; vertical-align: middle;' >" . substr($STdate, 0, 5) . "</td>";
            $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
        }
        echo "<td style='height: 30px; vertical-align: middle;' >Total</td>";
        ?>
                </tr>
                <?php
$firstdate = $SDate;
        foreach ($DATA as $report) {
            $getin = true;
            if (isset($lastvehicle)) {
                foreach ($lastvehicle as $thisvehicle) {
                    if ($thisvehicle == $report->vehicleid) {
                        $getin = false;
                    }
                }
            }
            if (isset($vehicles[$report->vehicleid]['vehicleno']) == null) {
                $getin = false;
            }
            if ($getin == true) {
                $total = 0;
                $CompareDate = strtotime($firstdate);
                $SDatetemp = $firstdate;
                echo '<tr>';
                $id = $report->vehicleid;
                echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                foreach ($DATA as $variablerep) {
                    if ($report->vehicleid == $variablerep->vehicleid) {
                        while ($CompareDate <= $variablerep->date) {
                            if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                            } elseif ($CompareDate == $variablerep->date) {
                                if ($variablerep->idletime != 0) {
                                    echo "<td style='height: 30px; vertical-align: middle;'>" . m2h($variablerep->idletime) . "</td>";
                                } else {
                                    $variablerep->idletime = 1440;
                                    echo "<td style='height: 30px; vertical-align: middle;'>" . m2h($variablerep->idletime) . "</td>";
                                }
                                $total += $variablerep->idletime;
                            }
                            $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                            $CompareDate = strtotime($SDatetemp);
                        }
                    }
                }
                while ($CompareDate <= strtotime($EDdate)) {
                    echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                    $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                    $CompareDate = strtotime($SDatetemp);
                }
                echo "<td style='height: 30px; vertical-align: middle;'><b>" . m2h($total) . "</b></td>";
                echo "</tr>";
            }
            $lastvehicle[] = $report->vehicleid;
        }
        echo "</table>";
    }
}

function getoverspeed_reportpdf($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if (file_exists($location)) {
        $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
    }
    if (isset($DATA)) {
        $title = 'Overspeed Analysis Report';
        $subTitle = array("Vehicle No: All Vehicles", "Start Date: $STdate", "End Date: $EDdate");
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        echo pdf_header($title, $subTitle);
        ?>
                <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                    <tbody>
                        <tr style="background-color: #CCCCCC;font-weight:bold;">
                            <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                            <?php
$SDate = $STdate;
        $STdate = date('d-m-Y', strtotime($STdate));
        $lastvehicle = array();
        $vehicles = GetVehicles_SQLite();
        $conf_count = 0;
        while (strtotime($STdate) <= strtotime($EDdate)) {
            $disp_date = substr($STdate, 0, 5);
            if ($conf_count > 22) {
                $disp_date = str_replace('-', '<br/>-<br/>', $disp_date);
            }
            echo "<td style='height: 30px; vertical-align: middle;' >$disp_date</td>";
            $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
            $conf_count++;
        }
        echo "<td style='height: 30px; vertical-align: middle;' >Total</td>";
        ?>
                        </tr>
                        <?php
$firstdate = $SDate;
        foreach ($DATA as $report) {
            $getin = true;
            if (isset($lastvehicle)) {
                foreach ($lastvehicle as $thisvehicle) {
                    if ($thisvehicle == $report->vehicleid) {
                        $getin = false;
                    }
                }
            }
            if (isset($vehicles[$report->vehicleid]['vehicleno']) == null) {
                $getin = false;
            }
            if ($getin == true) {
                $total = 0;
                $CompareDate = strtotime($firstdate);
                $SDatetemp = $firstdate;
                echo '<tr>';
                $id = $report->vehicleid;
                echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                foreach ($DATA as $variablerep) {
                    if ($report->vehicleid == $variablerep->vehicleid) {
                        while ($CompareDate <= $variablerep->date) {
                            if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                            } elseif ($CompareDate == $variablerep->date) {
                                echo "<td>$variablerep->overspeed</td>";
                                $total += $variablerep->overspeed;
                            }
                            $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                            $CompareDate = strtotime($SDatetemp);
                        }
                    }
                }
                while ($CompareDate <= strtotime($EDdate)) {
                    echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                    $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                    $CompareDate = strtotime($SDatetemp);
                }
                echo "<td style='height: 30px; vertical-align: middle;'><b>" . $total . "</b></td>";
                echo "</tr>";
            }
            $lastvehicle[] = $report->vehicleid;
        }
        ?>
                    </tbody></table>
                <?php
}
}

function getoverspeed_reportcsv($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if (file_exists($location)) {
        $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
    }
    if (isset($DATA)) {
        $title = 'Overspeed Analysis Report';
        $subTitle = array("Vehicle No: All Vehicles", "Start Date: $STdate", "End Date: $EDdate");
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        echo excel_header($title, $subTitle);
        ?>
                <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                    <tr style="background-color: #CCCCCC;font-weight:bold;">
                        <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                        <?php
$SDate = $STdate;
        $STdate = date('d-m-Y', strtotime($STdate));
        $lastvehicle = array();
        $vehicles = GetVehicles_SQLite();
        while (strtotime($STdate) <= strtotime($EDdate)) {
            echo "<td style='height: 30px; vertical-align: middle;' >" . substr($STdate, 0, 5) . "</td>";
            $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
        }
        echo "<td style='height: 30px; vertical-align: middle;' >Total</td>";
        ?>
                    </tr>
                    <?php
$firstdate = $SDate;
        foreach ($DATA as $report) {
            $getin = true;
            if (isset($lastvehicle)) {
                foreach ($lastvehicle as $thisvehicle) {
                    if ($thisvehicle == $report->vehicleid) {
                        $getin = false;
                    }
                }
            }
            if (isset($vehicles[$report->vehicleid]['vehicleno']) == null) {
                $getin = false;
            }
            if ($getin == true) {
                $total = 0;
                $CompareDate = strtotime($firstdate);
                $SDatetemp = $firstdate;
                echo '<tr>';
                $id = $report->vehicleid;
                echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                foreach ($DATA as $variablerep) {
                    if ($report->vehicleid == $variablerep->vehicleid) {
                        while ($CompareDate <= $variablerep->date) {
                            if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                            } elseif ($CompareDate == $variablerep->date) {
                                echo "<td>$variablerep->overspeed</td>";
                                $total += $variablerep->overspeed;
                            }
                            $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                            $CompareDate = strtotime($SDatetemp);
                        }
                    }
                }
                while ($CompareDate <= strtotime($EDdate)) {
                    echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                    $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                    $CompareDate = strtotime($SDatetemp);
                }
                echo "<td style='height: 30px; vertical-align: middle;'><b>" . $total . "</b></td>";
                echo "</tr>";
            }
            $lastvehicle[] = $report->vehicleid;
        }
        ?>
                </table>
                <?php
}
}

function getfence_reportpdf($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if (file_exists($location)) {
        $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
    }
    if (isset($DATA)) {
        $subTitle = array("Start Date: $STdate", "End Date: $EDdate");
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        $title = 'Fence Conflict Analysis Report';
        echo pdf_header($title, $subTitle);
        ?>
                <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                    <tr style="background-color: #CCCCCC;font-weight:bold;">
                        <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                        <?php
$SDate = $STdate;
        $STdate = date('d-m-Y', strtotime($STdate));
        $lastvehicle = array();
        $vehicles = GetVehicles_SQLite();
        $conf_count = 0;
        while (strtotime($STdate) <= strtotime($EDdate)) {
            $disp_date = substr($STdate, 0, 5);
            if ($conf_count > 20) {
                $disp_date = str_replace('-', '<br/>-<br/>', $disp_date);
            }
            echo "<td style='height: 30px; vertical-align: middle;' >$disp_date</td>";
            $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
            $conf_count++;
        }
        echo "<td style='height: 30px; vertical-align: middle;' >Total</td>";
        ?>
                    </tr>
                    <?php
$firstdate = $SDate;
        foreach ($DATA as $report) {
            $getin = true;
            if (isset($lastvehicle)) {
                foreach ($lastvehicle as $thisvehicle) {
                    if ($thisvehicle == $report->vehicleid) {
                        $getin = false;
                    }
                }
            }
            if (isset($vehicles[$report->vehicleid]['vehicleno']) == null) {
                $getin = false;
            }
            if ($getin == true) {
                $total = 0;
                $CompareDate = strtotime($firstdate);
                $SDatetemp = $firstdate;
                echo '<tr>';
                $id = $report->vehicleid;
                echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                foreach ($DATA as $variablerep) {
                    if ($report->vehicleid == $variablerep->vehicleid) {
                        while ($CompareDate <= $variablerep->date) {
                            if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                            } elseif ($CompareDate == $variablerep->date) {
                                echo "<td>$variablerep->fenceconflict</td>";
                                $total += $variablerep->fenceconflict;
                            }
                            $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                            $CompareDate = strtotime($SDatetemp);
                        }
                    }
                }
                while ($CompareDate <= strtotime($EDdate)) {
                    echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                    $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                    $CompareDate = strtotime($SDatetemp);
                }
                echo "<td style='height: 30px; vertical-align: middle;'><b>" . $total . "</b></td>";
                echo "</tr>";
            }
            $lastvehicle[] = $report->vehicleid;
        }
        ?>
                </table>
                <?php
}
}

function getfence_reportcsv($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if (file_exists($location)) {
        $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
    }
    if (isset($DATA)) {
        $title = 'Fence Conflict Analysis Report';
        $subTitle = array("Start Date: $STdate", "End Date: $EDdate");

        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }

        echo excel_header($title, $subTitle);
        ?>
                <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                    <tr style="background-color: #CCCCCC;font-weight:bold;">
                        <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                        <?php
$SDate = $STdate;
        $STdate = date('d-m-Y', strtotime($STdate));
        $lastvehicle = array();
        $vehicles = GetVehicles_SQLite();
        while (strtotime($STdate) <= strtotime($EDdate)) {
            echo "<td style='height: 30px; vertical-align: middle;' >" . substr($STdate, 0, 5) . "</td>";
            $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
        }
        echo "<td style='height: 30px; vertical-align: middle;' >Total</td>";
        ?>
                    </tr>
                    <?php
$firstdate = $SDate;
        foreach ($DATA as $report) {
            $getin = true;
            if (isset($lastvehicle)) {
                foreach ($lastvehicle as $thisvehicle) {
                    if ($thisvehicle == $report->vehicleid) {
                        $getin = false;
                    }
                }
            }
            if (isset($vehicles[$report->vehicleid]['vehicleno']) == null) {
                $getin = false;
            }
            if ($getin == true) {
                $total = 0;
                $CompareDate = strtotime($firstdate);
                $SDatetemp = $firstdate;
                echo '<tr>';
                $id = $report->vehicleid;
                echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                foreach ($DATA as $variablerep) {
                    if ($report->vehicleid == $variablerep->vehicleid) {
                        while ($CompareDate <= $variablerep->date) {
                            if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                            } elseif ($CompareDate == $variablerep->date) {
                                echo "<td>$variablerep->fenceconflict</td>";
                                $total += $variablerep->fenceconflict;
                            }
                            $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                            $CompareDate = strtotime($SDatetemp);
                        }
                    }
                }
                while ($CompareDate <= strtotime($EDdate)) {
                    echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                    $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                    $CompareDate = strtotime($SDatetemp);
                }
                echo "<td style='height: 30px; vertical-align: middle;'><b>" . $total . "</b></td>";
                echo "</tr>";
            }
            $lastvehicle[] = $report->vehicleid;
        }
        echo "</table>";
    }
}

function getlocation_reportpdf($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = substr(date("Y-m-d H:i:s"), '0', 11);
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if (file_exists($location)) {
        $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
    }
    if (isset($DATA)) {
        $subTitle = array("Start Date: $STdate", "End Date: $EDdate");
        $title = 'Location Analysis Report';

        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }

        echo pdf_header($title, $subTitle);
        ?>
                    <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                        <tbody>
                            <tr style="background-color: #CCCCCC;font-weight:bold;">
                                <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                                <?php
$SDate = $STdate;
        $STdate = date('d-m-Y', strtotime($STdate));
        $lastvehicle = array();
        $vehicles = GetVehicles_SQLite();
        while (strtotime($STdate) <= strtotime($EDdate)) {
            echo "<td style='height: 30px; vertical-align: middle;' >" . substr($STdate, 0, 5) . "</td>";
            $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
        }
        ?>
                            </tr>
                            <?php
$firstdate = $SDate;
        foreach ($DATA as $report) {
            $getin = true;
            if (isset($lastvehicle)) {
                foreach ($lastvehicle as $thisvehicle) {
                    if ($thisvehicle == $report->vehicleid) {
                        $getin = false;
                    }
                }
            }
            if (isset($vehicles[$report->vehicleid]['vehicleno']) == null) {
                $getin = false;
            }
            if ($getin == true) {
                $total = 0;
                $CompareDate = strtotime($firstdate);
                $SDatetemp = $firstdate;
                echo '<tr>';
                $id = $report->vehicleid;
                echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                foreach ($DATA as $variablerep) {
                    if ($report->vehicleid == $variablerep->vehicleid) {
                        while ($CompareDate <= $variablerep->date) {
                            if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                            } elseif ($CompareDate == $variablerep->date) {
                                echo "<td>$variablerep->location</td>";
                            }
                            $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                            $CompareDate = strtotime($SDatetemp);
                        }
                    }
                }
                while ($CompareDate <= strtotime($EDdate)) {
                    echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                    $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                    $CompareDate = strtotime($SDatetemp);
                }
                echo "</tr>";
            }
            $lastvehicle[] = $report->vehicleid;
        }
        ?>
                        </tbody>
                    </table>
                    <?php
}
}

function getlocation_reportcsv($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if (file_exists($location)) {
        $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
    }
    if (isset($DATA)) {
        $title = 'Location Analysis Report';
        $subTitle = array("Start Date: $STdate", "End Date: $EDdate");
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        echo excel_header($title, $subTitle);
        ?>
                    <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                        <tr style="background-color: #CCCCCC;font-weight:bold;">
                            <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                            <?php
$SDate = $STdate;
        $STdate = date('d-m-Y', strtotime($STdate));
        $lastvehicle = array();
        $vehicles = GetVehicles_SQLite();
        while (strtotime($STdate) <= strtotime($EDdate)) {
            echo "<td style='height: 30px; vertical-align: middle;' >" . substr($STdate, 0, 5) . "</td>";
            $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
        }
        ?>
                        </tr>
                        <?php
$firstdate = $SDate;
        foreach ($DATA as $report) {
            $getin = true;
            if (isset($lastvehicle)) {
                foreach ($lastvehicle as $thisvehicle) {
                    if ($thisvehicle == $report->vehicleid) {
                        $getin = false;
                    }
                }
            }
            if (isset($vehicles[$report->vehicleid]['vehicleno']) == null) {
                $getin = false;
            }
            if ($getin == true) {
                $total = 0;
                $CompareDate = strtotime($firstdate);
                $SDatetemp = $firstdate;
                echo '<tr>';
                $id = $report->vehicleid;
                echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                foreach ($DATA as $variablerep) {
                    if ($report->vehicleid == $variablerep->vehicleid) {
                        while ($CompareDate <= $variablerep->date) {
                            if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                            } elseif ($CompareDate == $variablerep->date) {
                                echo "<td>$variablerep->location</td>";
                            }
                            $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                            $CompareDate = strtotime($SDatetemp);
                        }
                    }
                }
                while ($CompareDate <= strtotime($EDdate)) {
                    echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                    $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                    $CompareDate = strtotime($SDatetemp);
                }
                echo "</tr>";
            }
            $lastvehicle[] = $report->vehicleid;
        }
        ?>
                    </table>
                    <?php
}
}

function getFuel_reportpdf($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if (file_exists($location)) {
        $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
    }
    if (isset($DATA)) {
        $subTitle = array("Start Date: $STdate", "End Date: $EDdate");
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        $title = 'Fuel Consumption Analysis Report';
        echo pdf_header($title, $subTitle);
        ?>
                    <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                        <tr style="background-color: #CCCCCC;font-weight:bold;">
                            <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                            <?php
$SDate = $STdate;
        $STdate = date('d-m-Y', strtotime($STdate));
        $lastvehicle = array();
        $vehicles = GetVehicles_SQLite();
        while (strtotime($STdate) <= strtotime($EDdate)) {
            echo "<td style='height: 30px; vertical-align: middle;' >" . substr($STdate, 0, 5) . "</td>";
            $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
        }
        echo "<td style='height: 30px; vertical-align: middle;' >Total</td>";
        ?>
                        </tr>
                        <?php
$firstdate = $SDate;
        foreach ($DATA as $report) {
            $getin = true;
            if (isset($lastvehicle)) {
                foreach ($lastvehicle as $thisvehicle) {
                    if ($thisvehicle == $report->vehicleid) {
                        $getin = false;
                    }
                }
            }
            if (isset($vehicles[$report->vehicleid]['vehicleno']) == null) {
                $getin = false;
            }
            if ($getin == true) {
                $total = 0;
                $CompareDate = strtotime($firstdate);
                $SDatetemp = $firstdate;
                echo '<tr>';
                $id = $report->vehicleid;
                echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                foreach ($DATA as $variablerep) {
                    if ($report->vehicleid == $variablerep->vehicleid) {
                        while ($CompareDate <= $variablerep->date) {
                            if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                            } elseif ($CompareDate == $variablerep->date) {
                                if ($variablerep->average > 0) {
                                    $variablerep->totaldistancetravelled = round(($variablerep->totaldistance / 1000) / $variablerep->average, 2);
                                    echo "<td style='height: 30px; vertical-align: middle;'>$variablerep->totaldistancetravelled</td>";
                                    $total += $variablerep->totaldistancetravelled;
                                } else {
                                    echo "<td style='height: 30px; vertical-align: middle;'>0</td>";
                                }
                            }
                            $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                            $CompareDate = strtotime($SDatetemp);
                        }
                    }
                }
                while ($CompareDate <= strtotime($EDdate)) {
                    echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                    $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                    $CompareDate = strtotime($SDatetemp);
                }
                echo "<td style='height: 30px; vertical-align: middle;' >" . $total . "</td>";
                echo "</tr>";
            }
            $lastvehicle[] = $report->vehicleid;
        }
        echo "</table>";
    }
}

function getFuel_reportcsv($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if (file_exists($location)) {
        $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
    }
    if (isset($DATA)) {
        $title = 'Fuel Consumption Analysis Report';
        $subTitle = array("Start Date: $STdate", "End Date: $EDdate");
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }

        echo excel_header($title, $subTitle);
        ?>
                        <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                            <tr style="background-color: #CCCCCC;font-weight:bold;">
                                <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                                <?php
$SDate = $STdate;
        $STdate = date('d-m-Y', strtotime($STdate));
        $lastvehicle = array();
        $vehicles = GetVehicles_SQLite();
        while (strtotime($STdate) <= strtotime($EDdate)) {
            echo "<td style='height: 30px; vertical-align: middle;' >" . substr($STdate, 0, 5) . "</td>";
            $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
        }
        echo "<td style='height: 30px; vertical-align: middle;' >Total</td>";
        ?>
                            </tr>
                            <?php
$firstdate = $SDate;
        foreach ($DATA as $report) {
            $getin = true;
            if (isset($lastvehicle)) {
                foreach ($lastvehicle as $thisvehicle) {
                    if ($thisvehicle == $report->vehicleid) {
                        $getin = false;
                    }
                }
            }
            if (isset($vehicles[$report->vehicleid]['vehicleno']) == null) {
                $getin = false;
            }
            if ($getin == true) {
                $total = 0;
                $CompareDate = strtotime($firstdate);
                $SDatetemp = $firstdate;
                echo '<tr>';
                $id = $report->vehicleid;
                echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                foreach ($DATA as $variablerep) {
                    if ($report->vehicleid == $variablerep->vehicleid) {
                        while ($CompareDate <= $variablerep->date) {
                            if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                            } elseif ($CompareDate == $variablerep->date) {
                                if ($variablerep->average > 0) {
                                    $variablerep->totaldistancetravelled = round(($variablerep->totaldistance / 1000) / $variablerep->average, 2);
                                    echo "<td style='height: 30px; vertical-align: middle;'>$variablerep->totaldistancetravelled</td>";
                                    $total += $variablerep->totaldistancetravelled;
                                } else {
                                    echo "<td style='height: 30px; vertical-align: middle;'>0</td>";
                                }
                            }
                            $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                            $CompareDate = strtotime($SDatetemp);
                        }
                    }
                }
                while ($CompareDate <= strtotime($EDdate)) {
                    echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                    $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                    $CompareDate = strtotime($SDatetemp);
                }
                echo "<td style='height: 30px; vertical-align: middle;' >" . $total . "</td>";
                echo "</tr>";
            }
            $lastvehicle[] = $report->vehicleid;
        }
        echo "</table>";
    }
}

function getgensetreportpdf_All($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if (file_exists($location)) {
        $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
    }
    if (isset($DATA)) {
        $subTitle = array("Start Date: $STdate", "End Date: $EDdate", "( Unit - Hours : Minutes )");
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        $title = 'Genset Analysis Report';
        echo pdf_header($title, $subTitle);
        ?>
                            <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                <tr style="background-color: #CCCCCC;font-weight:bold;">
                                    <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                                    <?php
$SDate = $STdate;
        $STdate = date('d-m-Y', strtotime($STdate));
        $lastvehicle = array();
        $vehicles = GetGensetVehicles_SQLite();
        while (strtotime($STdate) <= strtotime($EDdate)) {
            echo "<td style='height: 30px; vertical-align: middle;' >" . substr($STdate, 0, 5) . "</td>";
            $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
        }
        echo "<td style='height: 30px; vertical-align: middle;' >Total</td>";
        ?>
                                </tr>
                                <?php
$firstdate = $SDate;
        foreach ($DATA as $report) {
            $getin = true;
            if (isset($lastvehicle)) {
                foreach ($lastvehicle as $thisvehicle) {
                    if ($thisvehicle == $report->vehicleid) {
                        $getin = false;
                    }
                }
            }
            if (isset($vehicles[$report->vehicleid]['vehicleno']) == null) {
                $getin = false;
            }
            if ($getin == true) {
                $total = 0;
                $CompareDate = strtotime($firstdate);
                $SDatetemp = $firstdate;
                echo '<tr>';
                $id = $report->vehicleid;
                echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                foreach ($DATA as $variablerep) {
                    if ($report->vehicleid == $variablerep->vehicleid) {
                        while ($CompareDate <= $variablerep->date) {
                            if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                                $total += $variablerep->genset;
                            } elseif ($CompareDate == $variablerep->date) {
                                if ($variablerep->genset != 0) {
                                    echo "<td style='height: 30px; vertical-align: middle;'>" . m2h($variablerep->idletime) . "</td>";
                                } else {
                                    $variablerep->genset = 1440;
                                    echo "<td style='height: 30px; vertical-align: middle;'>" . m2h($variablerep->idletime) . "</td>";
                                }
                                $total += $variablerep->genset;
                            }
                            $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                            $CompareDate = strtotime($SDatetemp);
                        }
                    }
                }
                while ($CompareDate <= strtotime($EDdate)) {
                    echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                    $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                    $CompareDate = strtotime($SDatetemp);
                }
                echo "<td style='height: 30px; vertical-align: middle;'><b>" . m2h($total) . "</b></td>";
                echo "</tr>";
            }
            $lastvehicle[] = $report->vehicleid;
        }
        ?>
                            </table>
                            <?php
}
}

function getgensetreportcsv_All($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if (file_exists($location)) {
        $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
    }
    if (isset($DATA)) {
        $title = 'Genset Analysis Report';
        $subTitle = array("Start Date: $STdate", "End Date: $EDdate", "( Unit - Hours : Minutes )");
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        echo excel_header($title, $subTitle);
        ?>
                            <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                <tr style="background-color: #CCCCCC;font-weight:bold;">
                                    <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                                    <?php
$SDate = $STdate;
        $STdate = date('d-m-Y', strtotime($STdate));
        $lastvehicle = array();
        $vehicles = GetGensetVehicles_SQLite();
        while (strtotime($STdate) <= strtotime($EDdate)) {
            echo "<td style='height: 30px; vertical-align: middle;' >" . substr($STdate, 0, 5) . "</td>";
            $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
        }
        echo "<td style='height: 30px; vertical-align: middle;' >Total</td>";
        ?>
                                </tr>
                                <?php
$firstdate = $SDate;
        foreach ($DATA as $report) {
            $getin = true;
            if (isset($lastvehicle)) {
                foreach ($lastvehicle as $thisvehicle) {
                    if ($thisvehicle == $report->vehicleid) {
                        $getin = false;
                    }
                }
            }
            if (isset($vehicles[$report->vehicleid]['vehicleno']) == null) {
                $getin = false;
            }
            if ($getin == true) {
                $total = 0;
                $CompareDate = strtotime($firstdate);
                $SDatetemp = $firstdate;
                echo '<tr>';
                $id = $report->vehicleid;
                echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                foreach ($DATA as $variablerep) {
                    if ($report->vehicleid == $variablerep->vehicleid) {
                        while ($CompareDate <= $variablerep->date) {
                            if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                            } elseif ($CompareDate == $variablerep->date) {
                                if ($variablerep->genset != 0) {
                                    echo "<td style='height: 30px; vertical-align: middle;'>" . m2h($variablerep->idletime) . "</td>";
                                } else {
                                    $variablerep->genset = 1440;
                                    echo "<td style='height: 30px; vertical-align: middle;'>" . m2h($variablerep->idletime) . "</td>";
                                }
                                $total += $variablerep->genset;
                            }
                            $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                            $CompareDate = strtotime($SDatetemp);
                        }
                    }
                }
                while ($CompareDate <= strtotime($EDdate)) {
                    echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                    $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                    $CompareDate = strtotime($SDatetemp);
                }
                echo "<td style='height: 30px; vertical-align: middle;'><b>" . m2h($total) . "</b></td>";
                echo "</tr>";
            }
            $lastvehicle[] = $report->vehicleid;
        }
        ?>
                            </table>
                            <?php
}
}

function getdailyreport_byID($STdate, $EDdate, $vehicleid) {
    $totaldays = gendays($STdate, $EDdate);
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if (file_exists($location)) {
        $DATA = GetDailyReport_Data_ByID($location, $totaldays, $vehicleid);
    }
    return $DATA;
}

function getFuel_Report($STdate, $EDdate, $vehicleid) {
    $ST = date('Y-m-d 00:00:00', strtotime($STdate));
    $ED = date('Y-m-d 23:59:59', strtotime($EDdate));
    $vm = new VehicleManager($_SESSION['customerno']);
    $DATA = $vm->GetDailyFuelReport_Data($vehicleid, $ST, $ED);
    //print_r($DATA);
    return $DATA;
}

function getFuel_ReportAll($STdate, $EDdate) {
    $ST = date('Y-m-d 00:00:00', strtotime($STdate));
    $ED = date('Y-m-d 23:59:59', strtotime($EDdate));
    $vm = new VehicleManager($_SESSION['customerno']);
    $DATA = $vm->GetDailyFuelReportAll_Data($ST, $ED);
    return $DATA;
}

function getunitnotemp($vehicleid) {
    $um = new UnitManager($_SESSION['customerno']);
    $unitno = $um->getuidfromvehicleid($vehicleid);
    return $unitno;
}

function get_conversion($unitid) {
    $um = new UnitManager($_SESSION['customerno']);
    $deviceid = $um->get_conversionfromUnitno($unitid);
    return $deviceid;
}

function gethourlyreportfortemp($STdate, $vehicleid, $STHour) {
    $date = date("Y-m-d", strtotime($STdate));
    $date = substr($date, 0, 11);
    $unitno = getunitnotemp($vehicleid);
    //$totaldays = genminutes($STdate, $EDdate);
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
    if (file_exists($location)) {
        $DATA = GetHourlyReport_Temp($location, $STdate, $STHour);
    }
    return $DATA;
}

function getdailyreportfortemp($STdate, $EDdate, $vehicleid, $stime = null, $etime = null) {
    $start = $STdate;
    $DATAS = array();
    $date = date("Y-m-d", strtotime($STdate));
    $date = substr($date, 0, 11);
    $unitno = getunitnotemp($vehicleid);
    //$totaldays = genminutes($STdate, $EDdate);
    $customerno = $_SESSION['customerno'];
    //$location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
    $SDate = GetSafeValueString($STdate, 'string');
    $SDate = explode('-', $SDate);
    $SDate = $SDate[2] . "-" . $SDate[1] . "-" . $SDate[0];
    $EDate = GetSafeValueString($EDdate, 'string');
    $EDate = explode('-', $EDate);
    $EDate = $EDate[2] . "-" . $EDate[1] . "-" . $EDate[0];
    $totaldays = array();
    $STdate = date('Y-m-d', strtotime($SDate));
    while (strtotime($STdate) <= strtotime($EDate)) {
        $totaldays[] = $STdate;
        $STdate = date("Y-m-d", strtotime('+1 day', strtotime($STdate)));
    }
    //print_r($totaldays);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $STdate = date("Y-m-d", strtotime($start));
                if ($userdate == $STdate) {
                    $STdate = $userdate . " " . $stime . ":00";
                } else {
                    $STdate = $userdate . " 00:00:00";
                }
                $EDdate = date("Y-m-d", strtotime($EDdate));
                if ($userdate == $EDdate) {
                    $EDdate = $userdate . " " . $etime . ":00";
                } else {
                    $EDdate = $userdate . " 23:59:59";
                }
                $DATA = GetDailyReport_Temp($location, $STdate, $EDdate);
                if ($DATA != null) {
                    $DATAS = array_merge($DATAS, $DATA);
                }

                //$DATAS[] = $DATA;
            }
        }
    }
    //print_r($DATAS);
    return $DATAS;
}

function getdailyreportfortempselected($STdate, $EDdate, $vehicleid, $analogtype, $stime = null, $etime = null) {
    // echo $stime,$etime;
    $start = $STdate;
    $DATAS = array();
    $date = date("Y-m-d", strtotime($STdate));
    $date = substr($date, 0, 11);
    $unitno = getunitnotemp($vehicleid);
    //$totaldays = genminutes($STdate, $EDdate);
    $customerno = $_SESSION['customerno'];
    //$location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
    $SDate = GetSafeValueString($STdate, 'string');
    $SDate = explode('-', $SDate);
    $SDate = $SDate[2] . "-" . $SDate[1] . "-" . $SDate[0];
    $EDate = GetSafeValueString($EDdate, 'string');
    $EDate = explode('-', $EDate);
    $EDate = $EDate[2] . "-" . $EDate[1] . "-" . $EDate[0];
    $totaldays = array();
    $STdate = date('Y-m-d', strtotime($SDate));
    while (strtotime($STdate) <= strtotime($EDate)) {
        $totaldays[] = $STdate;
        $STdate = date("Y-m-d", strtotime('+1 day', strtotime($STdate)));
    }
    //print_r($totaldays);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $STdate = date("Y-m-d", strtotime($start));
                $EDdate = date('Y-m-d', strtotime($EDdate));
                if ($userdate == $STdate) {
                    $STdate = $userdate . " " . $stime . ":00";
                } else {
                    $STdate = $userdate . " 00:00:00";
                }
                $EDdate = date("Y-m-d", strtotime($EDdate));
                if ($userdate == $EDdate) {
                    $EDdate = $userdate . " " . $etime . ":00";
                } else {
                    $EDdate = $userdate . " 23:59:59";
                }
                $DATA = GetDailyReport_Temp_Analog($location, $STdate, $EDdate, $analogtype);
                if ($DATA != null) {
                    $DATAS = array_merge($DATAS, $DATA);
                }

                //$DATAS[] = $DATA;
            }
        }
    }
    //print_r($DATAS);
    return $DATAS;
}

function getvehicles() {
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $devices = $devicemanager->deviceformappings();
    return $devices;
}

function getwarehouses() {
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $devices = $devicemanager->deviceformappings_wh();
    return $devices;
}

function getvehiclesforteam($customerno) {
    $devicemanager = new DeviceManager($customerno);
    $devices = $devicemanager->devicesformapping();
    return $devices;
}

function get_all_chk() {
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->getallchkpts();
    return $checkpoints;
}

function get_all_chkforteam($customerno) {
    $checkpointmanager = new CheckpointManager($customerno);
    $checkpoints = $checkpointmanager->getallchkpts();
    return $checkpoints;
}

function get_all_checkpoint() {
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->getallcheckpoints();
    return $checkpoints;
}

function get_all_fence() {
    $geofencemanager = new GeofenceManager($_SESSION['customerno']);
    $fences = $geofencemanager->getallfencenames();
    return $fences;
}

function get_all_fenceteam($customerno) {
    $geofencemanager = new GeofenceManager($customerno);
    $fences = $geofencemanager->getallfencenames();
    return $fences;
}

function get_all_alerttype() {
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $status = $devicemanager->getallalerttype();
    return $status;
}

function GetVehicles_SQLite() {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $VEHICLES = $vehiclemanager->Get_All_Vehicles_SQLite();
    return $VEHICLES;
}

function GetGensetVehicles_SQLite() {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $VEHICLES = $vehiclemanager->vehicles_acsensor();
    return $VEHICLES;
}

function getvehicles_acsensor() {
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $devices = $devicemanager->devicesformapping_acsensor();
    return $devices;
}

function getdate_IST() {
    $ServerDate_IST = strtotime(date("Y-m-d H:i:s"));
    return $ServerDate_IST;
}

function getduration($EndTime, $StartTime) {
    $diff = strtotime($EndTime) - strtotime($StartTime);
    $years = floor($diff / (365 * 60 * 60 * 24));
    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
    $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
    $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
    return $hours * 60 + $minutes;
}

function create_html_from_report($datarows, $acinvert) {
    $i = 1;
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
    $display = '';
    if (isset($datarows)) {
        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                $count = $i;
                $display .= "<tr><th align='center' colspan = '100%'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $i++;
            }
            $currentdate = substr(date("Y-m-d H:i:s"), '0', 11);
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            $display .= "<tr><td>$change->starttime</td><td>$change->endtime</td>";
            if ($change->ignition == 1) {
                $display .= '<td>ON</td>';
            } else {
                $display .= '<td>OFF</td>';
            }
            if ($acinvert == 1) {
                if ($change->digitalio == 0) {
                    $display .= '<td>OFF</td>';
                    $runningtime += $change->duration;
                } else {
                    $display .= '<td>ON</td>';
                    $idletime += $change->duration;
                }
            } else {
                if ($change->digitalio == 0) {
                    $display .= '<td>ON</td>';
                    $runningtime += $change->duration;
                } else {
                    $display .= '<td>OFF</td>';
                    $idletime += $change->duration;
                }
            }
            $dt = new DateTime();
            $currDate = date_format($dt, 'Y-m-d');
            //$startT = gmdate("H:i", $change->duration);
            $startT = date('H:i', strtotime($change->duration . ' minutes'));
            $hours = floor($change->duration / 60); //round down to nearest minute.
            $minutes = $change->duration % 60;
            if ($hours <= 9) {
                $hour = '0' . $hours;
            } else {
                $hour = $hours;
            }
            if ($minutes <= 9) {
                $minute = '0' . $minutes;
            } else {
                $minute = $minutes;
            }
            $display .= "<td>$hour : $minute</td>";
            $display .= '</tr>';
        }
    }
    $display .= '</tbody>';
    $display .= '</table><br><br>';
    $display .= "<table align = 'center'><thead><tr><th colspan='100%'>Statistics</th></tr></thead>";
    $totaltime = 1440 * $count;
    if ($acinvert == 1) {
        $offtime = $totaltime - $idletime;
        $display .= "<tbody><tr><td>Total Gen Set ON Time = $idletime Minutes</td></tr><tr><td>Total Gen Set OFF Time = $offtime Minutes</td></tr></tbody></table>";
    } else {
        $offtime = $totaltime - $runningtime;
        $display .= "<tbody><tr><td>Total Gen Set ON Time = $runningtime Minutes</td></tr><tr><td>Total Gen Set OFF Time = $offtime Minutes</td></tr></tbody></table>";
    }
    return $display;
}

function create_gensethtml_from_report($datarows, $acinvert) {
    $i = 1;
    $runningtime = 0;
    $idletime = 0;
    $totalminute = 0;
    $lastdate = null;
    $display = '';
    $date_wise_arr = array();
    if (isset($datarows)) {
        foreach ($datarows as $change) {
            $thisdate = date('d-m-Y', strtotime($change->endtime));
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $display .= "<tr><th align='center' style='background:#d8d5d6' colspan = '100%'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $i++;
            }
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            if (!empty($change->startcgeolat) || !empty($change->startcgeolong)) {
                $use_location = isset($change->uselocation) ? $change->uselocation : 0;
                $change->startlocation = location($change->startcgeolat, $change->startcgeolong, $use_location);
                $change->endlocation = location($change->endcgeolat, $change->endcgeolong, $use_location);
            } else {
                $change->startlocation = "Unable to Pull Location";
                $change->endlocation = "Unable to Pull Location";
            }
            $start_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
            $end_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->endtime));
            $display .= "<tr><td>$start_time_disp</td><td>$change->startlocation</td><td>$end_time_disp</td><td>$change->endlocation</td>";
            if ($acinvert == 1) {
                if ($change->digitalio == 0) {
                    $display .= '<td>OFF</td>';
                    $runningtime += $change->duration;
                } else {
                    $display .= '<td>ON</td>';
                    $idletime += $change->duration;
                    if (isset($date_wise_arr[$thisdate])) {
                        $date_wise_arr[$thisdate] += $change->duration;
                    } else {
                        $date_wise_arr[$thisdate] = $change->duration;
                    }
                }
            } else {
                if ($change->digitalio == 0) {
                    $display .= '<td>ON</td>';
                    $runningtime += $change->duration;
                    if (isset($date_wise_arr[$thisdate])) {
                        $date_wise_arr[$thisdate] += $change->duration;
                    } else {
                        $date_wise_arr[$thisdate] = $change->duration;
                    }
                } else {
                    $display .= '<td>OFF</td>';
                    $idletime += $change->duration;
                }
            }
            $hours = floor($change->duration / 60); //round down to nearest minute.
            $minutes = $change->duration % 60;
            if ($hours <= 9) {
                $hour = '0' . $hours;
            } else {
                $hour = $hours;
            }
            if ($minutes <= 9) {
                $minute = '0' . $minutes;
            } else {
                $minute = $minutes;
            }
            $display .= "<td>$hour : $minute</td>";
            if (isset($change->fuelltr)) {
                $fuelltr[] = $change->fuelltr;
                $display .= "<td>" . $change->fuelltr . "</td>";
            } else {
                $display .= "<td> </td>";
            }
            $display .= '</tr>';
        }
    }
    $display .= '</tbody>';
    $display .= '</table><br><br>';
    $datewise = ac_datewise($count, $date_wise_arr);
    $display .= "$datewise<table class='table newTable' style='width:50%;'><thead><tr><th colspan='100%'>Statistics</th></tr></thead>";
    $totaltime = round(1440 * $count + $totalminute);
    if ($acinvert == 1) {
        $offtime = $totaltime - $idletime;
        $display .= "<tbody><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " ON Time = " . get_hh_mm($idletime * 60) . " Hours</td></tr><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr><tr><td style='text-align:center;'>Total Fuel Consumed = " . array_sum($fuelltr) . "</td></tr></tbody></table>";
    } else {
        $offtime = $totaltime - $runningtime;
        $display .= "<tbody><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " ON Time = " . get_hh_mm($runningtime * 60) . " Hours</td></tr><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr><tr><td colspan='100%'></td></tr></tbody></table>";
    }
    $display .= "</div>";
    return $display;
}

//gensert summary function -start
function create_gensethtml_summary_from_report($datarows, $acinvert) {
    $i = 1;
    $runningtime = 0;
    $idletime = 0;
    $totalminute = 0;
    $lastdate = null;
    $display = '';
    $date_wise_arr = array();
    if (isset($datarows)) {
        foreach ($datarows as $change) {
            $thisdate = date('d-m-Y', strtotime($change->endtime));
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                //$display .= "<tr><th align='center' style='background:#d8d5d6' colspan = '100%'>".date('d-m-Y',strtotime($change->endtime))."</th></tr>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $i++;
            }
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            if (!empty($change->startcgeolat) || !empty($change->startcgeolong)) {
                $use_location = isset($change->uselocation) ? $change->uselocation : 0;
                $change->startlocation = location($change->startcgeolat, $change->startcgeolong, $use_location);
                $change->endlocation = location($change->endcgeolat, $change->endcgeolong, $use_location);
            } else {
                $change->startlocation = "Unable to Pull Location";
                $change->endlocation = "Unable to Pull Location";
            }
            $start_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
            $end_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->endtime));
            //$display .= "<tr><td>$start_time_disp</td><td>$change->startlocation</td><td>$end_time_disp</td><td>$change->endlocation</td>";
            if ($acinvert == 1) {
                if ($change->digitalio == 0) {
                    //$display .= '<td>OFF</td>';
                    $runningtime += $change->duration;
                } else {
                    //$display .= '<td>ON</td>';
                    $idletime += $change->duration;
                    if (isset($date_wise_arr[$thisdate])) {
                        $date_wise_arr[$thisdate] += $change->duration;
                    } else {
                        $date_wise_arr[$thisdate] = $change->duration;
                    }
                }
            } else {
                if ($change->digitalio == 0) {
                    //$display .= '<td>ON</td>';
                    $runningtime += $change->duration;
                    if (isset($date_wise_arr[$thisdate])) {
                        $date_wise_arr[$thisdate] += $change->duration;
                    } else {
                        $date_wise_arr[$thisdate] = $change->duration;
                    }
                } else {
                    //$display .= '<td>OFF</td>';
                    $idletime += $change->duration;
                }
            }
            $hours = floor($change->duration / 60); //round down to nearest minute.
            $minutes = $change->duration % 60;
            if ($hours <= 9) {
                $hour = '0' . $hours;
            } else {
                $hour = $hours;
            }
            if ($minutes <= 9) {
                $minute = '0' . $minutes;
            } else {
                $minute = $minutes;
            }
            // $display .= "<td>$hour : $minute</td>";
            if (isset($change->fuelltr)) {
                $fuelltr[] = $change->fuelltr;
                //  $display .= "<td>".$change->fuelltr."</td>";
            } else {
                // $display .= "<td> </td>";
            }
            // $display .= '</tr>';
        }
    }
    //$display .= '</tbody>';
    // $display .= '</table><br><br>';
    $datewise = ac_datewise($count, $date_wise_arr);
    $display .= "$datewise<table class='table newTable' style='width:50%;'><thead><tr><th colspan='100%'>Statistics</th></tr></thead>";
    $totaltime = round(1440 * $count + $totalminute);
    if ($acinvert == 1) {
        $offtime = $totaltime - $idletime;
        if ($_SESSION['use_fuel_sensor'] != 0) {
            $fuelsummary = "<td style='text-align:center;'>Total Fuel Consumed = " . array_sum($fuelltr) . " ltr</td>";
        } else {
            $fuelsummary = "";
        }
        $display .= "<tbody><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " ON Time = " . get_hh_mm($idletime * 60) . " Hours</td></tr><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr><tr>" . $fuelsummary . "</tr></tbody></table>";
    } else {
        $offtime = $totaltime - $runningtime;
        $display .= "<tbody><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " ON Time = " . get_hh_mm($runningtime * 60) . " Hours</td></tr><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr><tr><td colspan='100%'></td></tr><tr>" . $fuelsummary . "</tr></tbody></table>";
    }
    $display .= "</div>";
    return $display;
}

////genset summary function -end
//genset details function -start -ganesh
function create_genset_detail_html_from_report($datarows, $acinvert, $gensetSensor) {
    //echo "Data count: <pre>"; print_r($datarows); exit();
    $i = 1;
    $runningtime = 0;
    $idletime = 0;
    $totalminute = 0;
    $lastdate = null;
    $display = '';
    $date_wise_arr = array();
    $count = 0;

    if (isset($datarows)) {
        if ($_SESSION['role'] == 'elixir') {
            prettyPrint($datarows);
        }
        $fromTime = reset($datarows);
        $toTime = end($datarows);
        $totalminute = round(abs(strtotime($fromTime->starttime) - strtotime($toTime->endtime)) / 60, 2);
        foreach ($datarows as $change) {
            /* Code to Continuing the loop if duration = "00:00" and Ignition status = "Running" starts here */
            $continueLoopStatus = getContinueLoopStatus($change->duration, $change->distancetravelled, $change->ignition);
            if ($continueLoopStatus) {
                continue;
            }
            /*  Code to Continuing the loop if duration = "00:00" and Ignition status = "Running" ends here  */

            //print_r($change);die();
            if ($gensetSensor == 2) {
                $change->digitalio = $change->extradigitalio;
            }
            $thisdate = date('d-m-Y', strtotime($change->endtime));
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    //$totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $display .= "<tr><th align='center' style='background:#d8d5d6' colspan = '100%'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $i++;
            }
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            if (!empty($change->startcgeolat) || !empty($change->startcgeolong)) {
                $use_location = isset($change->uselocation) ? $change->uselocation : 0;
                $change->startlocation = location($change->startcgeolat, $change->startcgeolong, $use_location);
                $change->endlocation = location($change->endcgeolat, $change->endcgeolong, $use_location);
            } else {
                $change->startlocation = "Unable to Pull Location";
                $change->endlocation = "Unable to Pull Location";
            }
            $start_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
            $end_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->endtime));
            $display .= "<tr><td>$start_time_disp</td><td>$change->startlocation</td><td>$end_time_disp</td><td>$change->endlocation</td>";
            if ($gensetSensor == 1) {
                if ($acinvert == 1) {
                    if ($change->digitalio == 0) {
                        $display .= '<td>OFF</td>';
                        $runningtime += round($change->duration, 2);
                    } else {
                        //$display .= '<td>ON1</td>';

                        $fetchedStatus = renderACStatusOFF($change->duration, $change->distancetravelled, $change->ignition);
                        $display .= '<td>' . $fetchedStatus . '</td>';
                        //$display .= '<td>ON1</td>';
                        if ($fetchedStatus == 'ON') {
                            $idletime += $change->duration;
                        }

                        if (isset($date_wise_arr[$thisdate])) {
                            $date_wise_arr[$thisdate] += round($change->duration, 2);
                        } else {
                            $date_wise_arr[$thisdate] = round($change->duration, 2);
                        }
                    }
                } else {
                    if ($change->digitalio == 0) {
                        /* Code for if AC is on but ignition status is OFF for 00:00 time then AC status should render OFF starts here */
                        $fetchedStatus = renderACStatusOFF($change->duration, $change->distancetravelled, $change->ignition);
                        $display .= '<td>' . $fetchedStatus . '</td>';
                        /* Code for if AC is on but ignition status is OFF for 00:00 time then AC status should render OFF ends here */
                        //$display .= '<td>ON</td>';
                        if ($fetchedStatus == 'ON') {
                            $runningtime += round($change->duration, 2);
                        }

                        //$runningtime += round($change->duration, 2);
                        if (isset($date_wise_arr[$thisdate])) {
                            $date_wise_arr[$thisdate] += round($change->duration, 2);
                        } else {
                            $date_wise_arr[$thisdate] = round($change->duration, 2);
                        }
                    } else {
                        $display .= '<td>OFF</td>';
                        $idletime += round($change->duration, 2);
                    }
                }
            } else {
                if ($change->digitalio == 1) {
                    /* Code for if AC is on but ignition status is OFF for 00:00 time then AC status should render OFF starts here */
                    $fetchedStatus = renderACStatusOFF($change->duration, $change->distancetravelled, $change->ignition);
                    $display .= '<td>' . $fetchedStatus . '</td>';
                    /* Code for if AC is on but ignition status is OFF for 00:00 time then AC status should render OFF ends here */

                    if ($fetchedStatus == 'ON') {
                        $idletime += $change->duration;
                    }

                    if (isset($date_wise_arr[$thisdate])) {
                        $date_wise_arr[$thisdate] += round($change->duration, 2);
                    } else {
                        $date_wise_arr[$thisdate] = round($change->duration, 2);
                    }
                } else {
                    $display .= '<td>OFF</td>';
                    $runningtime += round($change->duration, 2);
                }
            }
            /* Rendering vehicle ignition status starts here */
            /*
            if($change->ignition == 0)
            {
            $display .= '<td>OFF</td>';
            }
            else
            {
            $display .= '<td>ON</td>';
            }
             */
            if ($change->distancetravelled > 0) {
                $display .= "<td style='cursor:pointer;'> <a style='text-decoration:underline;'>Running</a></td>";
                //$runningtime += $change->duration;
            } else {
                if ($change->ignition == 1) {
                    $display .= "<td>Idle - Ignition On</td>";
                    //$idle_ign_on += $change->duration;
                } else {
                    $display .= "<td>Idle</td>";
                    //$idletime += $change->duration;
                }
            }
            /* Rendering vehicle ignition status ends here */

            $hours = floor($change->duration / 60); //round down to nearest minute.
            $minutes = $change->duration % 60;
            if ($hours <= 9) {
                $hour = '0' . $hours;
            } else {
                $hour = $hours;
            }
            if ($minutes <= 9) {
                $minute = '0' . $minutes;
            } else {
                $minute = $minutes;
            }
            $display .= "<td>$hour : $minute</td>";
            if (isset($change->fuelltr) && $change->fuelltr != 0) {
                $fuelltr[] = $change->fuelltr;
                $display .= "<td>" . $change->fuelltr . "</td>";
            } else {
                $display .= "";
            }
            $display .= '</tr>';
        }
    }
    $display .= '</tbody>';
    $display .= '</table><br><br>';
    //$datewise = ac_datewise($count,$date_wise_arr);
    //$display .= "$datewise<table class='table newTable' style='width:50%;'><thead><tr><th colspan='100%'>Statistics</th></tr></thead>";
    $display .= "<table class='table newTable' style='width:50%;'><thead><tr><th colspan='100%'>Statistics</th></tr></thead>";
    $totaltime = round(1440 * $count + $totalminute);
    if ($acinvert == 1) {
        $offtime = $totalminute - $idletime;
        if ($_SESSION['use_fuel_sensor'] != 0) {
            $array_sum_fuel = "<td style='text-align:center;'>Total Fuel Consumed = " . array_sum($fuelltr) . "</td>";
        } else {
            $array_sum_fuel = "";
        }
        $display .= "<tbody><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " ON Time = " . get_hh_mm($idletime * 60) . " Hours</td></tr><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr><tr>" . $array_sum_fuel . "</tr></tbody></table>";
    } else {
        $offtime = $totalminute - $runningtime;
        $display .= "<tbody><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " ON Time = " . get_hh_mm($runningtime * 60) . " Hours</td></tr><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr><tr><td colspan='100%'></td></tr></tbody></table>";
    }
    $display .= "</div>";
    return $display;
}

/* Testing function starts here */

/* Code for if duration of vehicle is 00:00 and ignition is OFF then AC sensor should OFF starts here */
function renderACStatusOFF($duration, $distancetravelled, $ignition) {
    $hours = floor($duration / 60); //round down to nearest minute.
    $minutes = $duration % 60;
    if ($hours <= 9) {
        $hour = '0' . $hours;
    } else {
        $hour = $hours;
    }
    if ($minutes <= 9) {
        $minute = '0' . $minutes;
    } else {
        $minute = $minutes;
    }
    $time = "$hour : $minute";

    if ($time == '00 : 00') {
        if ($distancetravelled > 0) {
            $display = "Running";
            return 'ON';
        } else {
            if ($ignition == 1) {
                $display = "Idle - Ignition On";
                return 'ON';
            } else {
                $display = "Idle";
                return 'OFF';
            }
        }
    } else {
        return 'ON';
    }
}

/* Code for if duration of vehicle is 00:00 and ignition is OFF the AC sensor should OFF ends here */

/* Function to check whether continue a loop or not starts here  */
function getContinueLoopStatus($duration, $distancetravelled, $ignition) {
    $hours = floor($duration / 60); //round down to nearest minute.
    $minutes = $duration % 60;
    $display = '';
    if ($hours <= 9) {
        $hour = '0' . $hours;
    } else {
        $hour = $hours;
    }
    if ($minutes <= 9) {
        $minute = '0' . $minutes;
    } else {
        $minute = $minutes;
    }
    $time = "$hour : $minute";

    if ($distancetravelled > 0) {
        $display = "Running";
    } else {
        if ($ignition == 1) {
            $display = "Idle - Ignition On";
        } else {
            $display = "Idle";
        }
    }

    if ($time == '00 : 00' && $display == 'Running') {
        return true;
    } else {
        return false;
    }
}

/* Function to check whether continue a loop or not ends here */

function testGenerateHtml($datarows, $acinvert, $gensetSensor) {
    $i = 1;
    $runningtime = 0;
    $idletime = 0;
    $totalminute = 0;
    $lastdate = null;
    $display = '';
    $date_wise_arr = array();
    $count = 0;

    if (isset($datarows)) {
        $fromTime = reset($datarows);
        $toTime = end($datarows);
        $totalminute = round(abs(strtotime($fromTime->starttime) - strtotime($toTime->endtime)) / 60, 2);
        foreach ($datarows as $change) {
            //print_r($change);die();
            if ($gensetSensor == 2) {
                $change->digitalio = $change->extradigitalio;
            }
            $thisdate = date('d-m-Y', strtotime($change->endtime));
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    //$totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $display .= "<tr><th align='center' style='background:#d8d5d6' colspan = '100%'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $i++;
            }
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            if (!empty($change->startcgeolat) || !empty($change->startcgeolong)) {
                $use_location = isset($change->uselocation) ? $change->uselocation : 0;
                $change->startlocation = location($change->startcgeolat, $change->startcgeolong, $use_location);
                $change->endlocation = location($change->endcgeolat, $change->endcgeolong, $use_location);
            } else {
                $change->startlocation = "Unable to Pull Location";
                $change->endlocation = "Unable to Pull Location";
            }
            $start_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
            $end_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->endtime));
            $display .= "<tr><td>$start_time_disp</td><td>$change->startlocation</td><td>$end_time_disp</td><td>$change->endlocation</td>";
            if ($gensetSensor == 1) {
                if ($acinvert == 1) {
                    if ($change->digitalio == 0) {
                        $display .= '<td>OFF</td>';
                        $runningtime += round($change->duration, 2);
                    } else {
                        $display .= '<td>ON</td>';
                        $idletime += $change->duration;
                        if (isset($date_wise_arr[$thisdate])) {
                            $date_wise_arr[$thisdate] += round($change->duration, 2);
                        } else {
                            $date_wise_arr[$thisdate] = round($change->duration, 2);
                        }
                    }
                } else {
                    if ($change->digitalio == 0) {
                        $display .= '<td>ON</td>';
                        $runningtime += round($change->duration, 2);
                        if (isset($date_wise_arr[$thisdate])) {
                            $date_wise_arr[$thisdate] += round($change->duration, 2);
                        } else {
                            $date_wise_arr[$thisdate] = round($change->duration, 2);
                        }
                    } else {
                        $display .= '<td>OFF</td>';
                        $idletime += round($change->duration, 2);
                    }
                }
            } else {
                if ($change->digitalio == 1) {
                    $display .= '<td>ON</td>';
                    $idletime += $change->duration;
                    if (isset($date_wise_arr[$thisdate])) {
                        $date_wise_arr[$thisdate] += round($change->duration, 2);
                    } else {
                        $date_wise_arr[$thisdate] = round($change->duration, 2);
                    }
                } else {
                    $display .= '<td>OFF</td>';
                    $runningtime += round($change->duration, 2);
                }
            }

            $hours = floor($change->duration / 60); //round down to nearest minute.
            $minutes = $change->duration % 60;
            if ($hours <= 9) {
                $hour = '0' . $hours;
            } else {
                $hour = $hours;
            }
            if ($minutes <= 9) {
                $minute = '0' . $minutes;
            } else {
                $minute = $minutes;
            }
            $display .= "<td>$hour : $minute</td>";
            if (isset($change->fuelltr) && $change->fuelltr != 0) {
                $fuelltr[] = $change->fuelltr;
                $display .= "<td>" . $change->fuelltr . "</td>";
            } else {
                $display .= "";
            }
            $display .= '</tr>';
        }
    }
    $display .= '</tbody>';
    $display .= '</table><br><br>';
    //$datewise = ac_datewise($count,$date_wise_arr);
    //$display .= "$datewise<table class='table newTable' style='width:50%;'><thead><tr><th colspan='100%'>Statistics</th></tr></thead>";
    $display .= "<table class='table newTable' style='width:50%;'><thead><tr><th colspan='100%'>Statistics</th></tr></thead>";
    $totaltime = round(1440 * $count + $totalminute);
    if ($acinvert == 1) {
        $offtime = $totalminute - $idletime;
        if ($_SESSION['use_fuel_sensor'] != 0) {
            $array_sum_fuel = "<td style='text-align:center;'>Total Fuel Consumed = " . array_sum($fuelltr) . "</td>";
        } else {
            $array_sum_fuel = "";
        }
        $display .= "<tbody><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " ON Time = " . get_hh_mm($idletime * 60) . " Hours</td></tr><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr><tr>" . $array_sum_fuel . "</tr></tbody></table>";
    } else {
        $offtime = $totalminute - $runningtime;
        $display .= "<tbody><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " ON Time = " . get_hh_mm($runningtime * 60) . " Hours</td></tr><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr><tr><td colspan='100%'></td></tr></tbody></table>";
    }
    $display .= "</div>";
    return $display;
}

/* Testing function ends here */
//genset detail function -end
function ac_datewise($count, $date_wise_arr, $from = 'html') {
    $datewise = "";
    if ($count > 1) {
        if ($from == 'pdf') {
            $datewise .= "<table align='center'  style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
            $datewise .= "<tbody><tr><td style='background-color:#CCCCCC;font-weight:bold;' colspan='3'>Datewise</td></tr>";
            $datewise .= "<tr style='background-color:#CCCCCC;font-weight:bold;'><td>Date</td><td>" . $_SESSION["digitalcon"] . " ON Time</td><td>" . $_SESSION["digitalcon"] . " OFF Time</td></tr>";
        } else {
            $datewise .= "<table class='table newTable' style='width:50%;'>";
            $datewise .= "<thead><tr><th colspan='3'>Datewise</th></tr>";
            $datewise .= "<tr><th>Date</th><th>" . $_SESSION["digitalcon"] . " ON Time</th><th>" . $_SESSION["digitalcon"] . " OFF Time</th></tr></thead>";
            $datewise .= "<tbody>";
        }
        foreach ($date_wise_arr as $date => $val) {
            $on = get_hh_mm($val * 60);
            if ($date == date('d-m-Y')) {
                $now = (date('H') * 60) + date('i');
                $off_val = $now - $val;
            } else {
                $off_val = 1440 - $val;
            }
            $off = get_hh_mm($off_val * 60);
            $datewise .= "<tr><td>$date</td><td>$on Hours</td><td>$off Hours</td></tr>";
        }
        $datewise .= "</tbody></table><br/>";
    }
    return $datewise;
}

function create_extragensethtml_from_report($datarows, $extraid, $extraval) {
    $i = 1;
    $runningtime = 0;
    $idletime = 0;
    $totalminute = 0;
    $lastdate = null;
    $display = '';
    if (isset($datarows)) {
        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $display .= "<tr><th align='center' style='background:#d8d5d6' colspan = '100%'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $i++;
            }
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            if (!empty($change->startcgeolat) || !empty($change->startcgeolong)) {
                $use_location = isset($change->uselocation) ? $change->uselocation : 0;
                $change->startlocation = location($change->startcgeolat, $change->startcgeolong, $use_location);
                $change->endlocation = location($change->endcgeolat, $change->endcgeolong, $use_location);
            } else {
                $change->startlocation = "Unable to Pull Location";
                $change->endlocation = "Unable to Pull Location";
            }
            $start_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
            $end_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->endtime));
            $display .= "<tr><td>$start_time_disp</td><td>$change->startlocation</td><td>$end_time_disp</td><td>$change->endlocation</td>";
            $category_array = array();
            $digital = array();
            $category = (int) $change->digitalio;
            $binarycategory = sprintf("%08s", DecBin($category));
            for ($shifter = 1; $shifter <= 127; $shifter = $shifter << 1) {
                $binaryshifter = sprintf("%08s", DecBin($shifter));
                if ($category & $shifter) {
                    $category_array[] = $shifter;
                }
            }
            if (in_array($extraid, $category_array)) {
                $display .= '<td>On</td>';
                $runningtime += $change->duration;
            } else {
                $display .= '<td>OFF</td>';
                $idletime += $change->duration;
            }
            $dt = new DateTime();
            $currDate = date_format($dt, 'Y-m-d');
            //$startT = gmdate("H:i", $change->duration);
            $startT = date('H:i', strtotime($change->duration . ' minutes'));
            $hours = floor($change->duration / 60); //round down to nearest minute.
            $minutes = $change->duration % 60;
            if ($hours <= 9) {
                $hour = '0' . $hours;
            } else {
                $hour = $hours;
            }
            if ($minutes <= 9) {
                $minute = '0' . $minutes;
            } else {
                $minute = $minutes;
            }
            $display .= "<td>$hour : $minute</td>";
            $display .= '</tr>';
        }
    }
    $display .= '</tbody>';
    $display .= '</table><br><br>';
    $display .= "<div class='container' style='width:45%;'><table class='table newTable' ><thead><tr><th colspan='100%'>Statistics</th></tr></thead>";
    $totaltime = 1440 * $count + $totalminute;
    $totaltime = round($totaltime);
    if (isset($acinvert) && $acinvert == 1) {
        $offtime = $totaltime - $idletime;
        $display .= "<tbody><tr><td style='text-align:center;'>Total " . $extraval . " ON Time = " . m2h($idletime) . " HH:MM  </td></tr><tr><td style='text-align:center;'>Total " . $extraval . " OFF Time = " . m2h($offtime) . " HH:MM </td></tr></tbody></table>";
    } else {
        $offtime = $totaltime - $runningtime;
        $display .= "<tbody><tr><td style='text-align:center;'>Total " . $extraval . " ON Time = " . m2h($runningtime) . " HH:MM </td></tr><tr><td style='text-align:center;'>Total " . $extraval . " OFF Time = " . m2h($offtime) . " HH:MM</td></tr></tbody></table>";
    }
    return $display;
}

function create_humidityhtml_from_report($datarows, $vehicle, $veh_temp_details = null) {
    $i = 1;
    $tr = 0;
    $non_compl = 0;
    $bel_min = 0;
    $abo_max = 0;
    $tr_abv_max = 0;
    $tr2_abv_max = 0;
    $tr3_abv_max = 0;
    $tr4_abv_max = 0;
    $totalminute = 0;
    $lastdate = null;
    $display = '';
    $min_max_temp1 = get_min_max_temp(1, $veh_temp_details);
    $tempconversion = new TempConversion();
    $tempconversion->unit_type = $vehicle->get_conversion;
    $tempconversion->use_humidity = $_SESSION['use_humidity'];
    $tempconversion->switch_to = $_SESSION['switch_to'];
    if (isset($datarows)) {
        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $display .= "<tr><th align='center' colspan = '100%' style='background:#d8d5d6'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $i++;
            }
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            $starttime_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
            $display .= "<tr><td>$starttime_disp</td>";
            if ($_SESSION['switch_to'] != 3) {
                $location = get_location_detail($change->devicelat, $change->devicelong);
                $display .= "<td>$location</td>";
            }
            // Temperature Sensor

            if ($_SESSION['use_humidity'] == 1) {
                $temp = 'Not Active';
                $s = "analog" . $vehicle->humidity;
                if ($vehicle->humidity != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp = getTempUtil($tempconversion);
                } else {
                    $temp = '-';
                }

                if ($temp != '-' && $temp != "Not Active") {
                    if (($temp < $vehicle->hum_min) || ($temp > $vehicle->hum_max)) {
                        $non_compl++;
                    }
                    if (($temp < $vehicle->hum_min)) {
                        $bel_min++;
                    }
                    if (($temp > $vehicle->hum_max)) {
                        $abo_max++;
                    }
                    $display .= "<td>$temp</td>";
                } else {
                    $display .= "<td>$temp</td>";
                }
                /* min temp */

                $temp1_min = set_summary_min_temp($temp);
                /* maximum temp */
                $temp1_max = set_summary_max_temp($temp);
                /* above max */
                if ($temp > $min_max_temp1['temp_max_limit']) {
                    $tr_abv_max++;
                }
            }
            $display .= '</tr>';
            $tr++;
        }
    }
    $display .= '</tbody>';
    $display .= '</table>';
    $abv_compliance = round(($tr_abv_max / $tr) * 100, 2);
    $within_compliance = round((($tr - $tr_abv_max) / $tr) * 100, 2);
    $temp1_data = "<td style='text-align:center;'>
                        <table class='table newTable'>

                        <tbody> <tr><td>Total Reading: $tr</td></tr>
                            <tr><td>Non-Compliance Reading :$non_compl</td></tr>
                            <tr><td>Readings Below Minimum :$bel_min</td></tr>
                            <tr><td>Readings Above Maximum :$abo_max</td></tr>
                            <tr><td>Minimum Humidity: $temp1_min %</td></tr>
                            <tr><td>Maximum Humidity: $temp1_max %</td></tr>

                        </td>";
    $span = 1;
    $temp2_data = '';
    $summary = "<table class='table newTable'>
                        <thead>
                            <tr><th colspan=$span>Statistics</th></tr>
                        </thead>
                        <tbody>
                            <tr>$temp1_data</tr>
                        </tbody>
                    </table>";
    $display .= "$summary</div>";
    return $display;
}

// format report to pdf format for temperature and humidity
function create_humiditytemp_pdf_from_report($datarows, $vehicle, $veh_temp_details = null, $switchto, $customer_details) {
    $i = 1;
    $tr = 0;
    $tr_abv_max = 0;
    $tr2_abv_max = 0;
    $tr3_abv_max = 0;
    $tr4_abv_max = 0;
    $totalminute = 0;
    $lastdate = null;
    $display = '';
    $min_max_temp1 = get_min_max_temp(1, $veh_temp_details);
    $min_max_temp2 = get_min_max_temp(1, $veh_temp_details);
    $tempconversion = new TempConversion();
    $tempconversion->unit_type = $vehicle->get_conversion;
    if (isset($datarows)) {
        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $display .= "<tr style='background-color:#CCCCCC;font-weight:bold;'>
                                <td style='width:150px;height:auto;'>Time</td>";
                if ($switchto != 3) {
                    $display .= "<td style='width:550px;height:auto;'>Location</td>";
                }
                $display .= "<td style='width:150px;height:auto;'>Humidity %</td>
                                <td style='width:150px;height:auto;'>Temperature &deg;C</td>
                            </tr>";
                if ($switchto != 3) {
                    $display .= "<tr><td colspan='4' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>"
                    . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                } else {
                    $display .= "<tr><td colspan='3' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>"
                    . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                }
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $i++;
            }
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            $starttime_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
            $display .= "<tr><td>$starttime_disp</td>";
            if ($switchto != 3) {
                $location = get_location_detail($change->devicelat, $change->devicelong);
                $display .= "<td>$location</td>";
            }
            // Temperature Sensor
            //print_r($_SESSION);
            if ($customer_details->use_humidity == 1) {
                $temp = 'Not Active';
                $s = "analog" . $vehicle->humidity;
                if ($vehicle->humidity != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $tempconversion->use_humidity = $customer_details->use_humidity;
                    $tempconversion->switch_to = $switchto;
                    $temp = getTempUtil($tempconversion);
                } else {
                    $temp = '-';
                }

                if ($temp != '-' && $temp != "Not Active") {
                    $display .= "<td>$temp</td>";
                } else {
                    $display .= "<td>$temp</td>";
                }

                /* min temp */
                $temp1_min = set_summary_min_temp($temp);
                /* maximum temp */
                $temp1_max = set_summary_max_temp($temp);
                /* above max */
                if ($temp > $min_max_temp1['temp_max_limit']) {
                    $tr_abv_max++;
                }
            }

            $temp1 = 'Not Active';
            $s = "analog" . $vehicle->tempsen1;
            if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                $tempconversion->rawtemp = $change->$s;
                $tempconversion->use_humidity = $customer_details->use_humidity;
                $tempconversion->switch_to = $switchto;
                $temp1 = getTempUtil($tempconversion);
            } else {
                $temp1 = '-';
            }

            if ($temp1 != '-' && $temp1 != "Not Active") {
                $display .= "<td>$temp1</td>";
            } else {
                $display .= "<td>$temp1</td>";
            }

            /* min temp */
            $temp2_min = set_summary_min_temp2($temp1);
            /* maximum temp */
            $temp2_max = set_summary_max_temp2($temp1);
            /* above max */
            if ($temp1 > $min_max_temp2['temp_max_limit']) {
                $tr2_abv_max++;
            }
            $display .= '</tr>';
            $tr++;
        }
    }
    $display .= '</tbody>';
    $display .= '</table>';
    $temp1_data = "<td style='text-align:center;'>
                <table class='table newTable' cellspacing =0;cellpadding=0; >
                    <thead>
                        <tr><th style='background-color:#CCCCCC;'><u>Humidity</u></th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Minimum Humidity: $temp1_min %</td></tr>
                        <tr><td>Maximum Humidity: $temp1_max %</td></tr>
                        <tr><td>Total Reading: $tr</td></tr>
                    </tbody>
                </table>
            </td>";
    $temp2_data = "<td style='text-align:center;'>
            <table class='table newTable' cellspacing =0;cellpadding=0;>
                <thead>
                    <tr><th style='background-color:#CCCCCC;'><u>Temperature</u></th></tr>
                </thead>
                <tbody>
                    <tr><td>Minimum Temperature: $temp2_min &deg;C</td></tr>
                    <tr><td>Maximum Temperature: $temp2_max &deg;C</td></tr>
                    <tr><td>Total Reading: $tr</td></tr>
                </tbody>
            </table>
        </td>";
    $span = 2;
    $summary = "<br/> <table align='center' style='width: auto; font-size:13px; text-align:center;
        border:1px solid #000;' cellspacing =0;cellpadding=0;>
        <thead>
            <tr><th colspan=$span style='background-color:#CCCCCC;'>Statistics</th></tr>
        </thead>
        <tbody>
            <tr>$temp1_data$temp2_data</tr>
        </tbody>
    </table>";
    $display .= "$summary";
    return $display;
}

function create_humiditytemphtml_from_report($datarows, $vehicle, $veh_temp_details = null) {
    $i = 1;
    $tr = 0;
    $non_compl = 0;
    $bel_min = 0;
    $abv_max = 0;
    $non_compl1 = 0;
    $bel_min1 = 0;
    $abv_max1 = 0;
    $tr_abv_max = 0;
    $tr2_abv_max = 0;
    $tr3_abv_max = 0;
    $tr4_abv_max = 0;
    $totalminute = 0;
    $lastdate = null;
    $temp2_min = 0;
    $temp2_max = 0;
    $display = '';
    $min_max_temp1 = get_min_max_temp(1, $veh_temp_details);
    $min_max_temp2 = get_min_max_temp(1, $veh_temp_details);
    $tempconversion = new TempConversion();
    $tempconversion->unit_type = $vehicle->get_conversion;
    $tempconversion->use_humidity = $_SESSION['use_humidity'];
    $tempconversion->switch_to = $_SESSION['switch_to'];
    if (isset($datarows)) {
        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $display .= "<tr><th align='center' colspan = '100%' style='background:#d8d5d6'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $i++;
            }
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            $starttime_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
            $display .= "<tr><td>$starttime_disp</td>";
            // Temperature Sensor
            if ($_SESSION['use_humidity'] == 1) {
                $temp = 'Not Active';
                $s = "analog" . $vehicle->humidity;
                if ($vehicle->humidity != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp = getTempUtil($tempconversion);
                } else {
                    $temp = '-';
                }

                if ($temp != '-' && $temp != "Not Active") {
                    if (($temp < $vehicle->hum_min) || ($temp > $vehicle->hum_max)) {
                        $non_compl++;
                    }
                    if (($temp < $vehicle->hum_min)) {
                        $bel_min++;
                    }
                    if (($temp > $vehicle->hum_max)) {
                        $abv_max++;
                    }
                    $display .= "<td>$temp</td>";
                } else {
                    $display .= "<td>$temp</td>";
                }

                /* min temp */
                $temp1_min = set_summary_min_temp($temp);
                /* maximum temp */

                $temp1_max = set_summary_max_temp($temp);
                /* above max */
                if ($temp > $min_max_temp1['temp_max_limit']) {
                    $tr_abv_max++;
                }
            }

            $temp1 = 'Not Active';
            $s = "analog" . $vehicle->tempsen1;
            if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                $tempconversion->rawtemp = $change->$s;
                $temp1 = getTempUtil($tempconversion);
            } else {
                $temp1 = '-';
            }

            if ($temp1 != '-' && $temp1 != "Not Active") {
                if (($temp < $min_max_temp1['temp_min_limit']) || ($temp > $min_max_temp1['temp_max_limit'])) {
                    $non_compl1++;
                }
                if (($temp < $min_max_temp1['temp_min_limit'])) {
                    $bel_min1++;
                }
                if (($temp > $min_max_temp1['temp_max_limit'])) {
                    $abv_max1++;
                }
                $display .= "<td>$temp1</td>";
            } else {
                $display .= "<td>$temp1</td>";
            }

            /* min temp */
            $temp2_min = set_summary_min_temp2($temp1);
            /* maximum temp */
            $temp2_max = set_summary_max_temp2($temp1);
            /* above max */
            if ($temp1 > $min_max_temp2['temp_max_limit']) {
                $tr2_abv_max++;
            }
            $display .= '</tr>';
            $tr++;
        }
    }
    $display .= '</tbody>';
    $display .= '</table>';
    $temp1_data = "<td style='text-align:center;'>
    <table class='table newTable'><thead><tr><th><u>Humidity</u></th></tr></thead>
        <tbody>
            <tr><td>Total Reading: $tr</td></tr>
            <tr><td>Non-Compliance Reading: $non_compl</td></tr>
            <tr><td>Reading Below Minimum: $bel_min</td></tr>
            <tr><td>Reading Above Maximum: $abv_max</td></tr>
            <tr><td>Minimum Humidity: $temp1_min %</td></tr>
            <tr><td>Maximum Humidity: $temp1_max %</td></tr>
        </tbody>
    </table>
    </td>";
    $temp2_data = "<td style='text-align:center;'>
    <table class='table newTable'><thead><tr><th><u>Temperature</u></th></tr></thead>
        <tbody>
            <tr><td>Total Reading: $tr</td></tr>
            <tr><td>Non-Compliance Reading: $non_compl1</td></tr>
            <tr><td>Reading Below Minimum: $bel_min1</td></tr>
            <tr><td>Reading Above Maximum: $abv_max1</td></tr>
            <tr><td>Minimum Temperature: $temp2_min &deg;C</td></tr>
            <tr><td>Maximum Temperature: $temp2_max &deg;C</td></tr>
        </tbody></table>
    </td>";
    $span = 2;
    $summary = "<table class='table newTable'>
    <thead>
        <tr><th colspan=$span>Statistics</th></tr>
    </thead>
    <tbody>
        <tr>$temp1_data$temp2_data</tr>
    </tbody>
</table>";
    $display .= "$summary</div>";
    return $display;
}

function set_summary_min_temp($temp) {
    static $minTemp;
    if ($minTemp == null) {
        $minTemp = $temp;
    }
    if ($temp < $minTemp) {
        $minTemp = $temp;
    }
    return $minTemp;
}

function set_summary_min_temp2($temp) {
    static $minTemp;
    if ($minTemp == null) {
        $minTemp = $temp;
    }
    if ($temp < $minTemp) {
        $minTemp = $temp;
    }
    return $minTemp;
}

function set_summary_min_temp3($temp) {
    static $minTemp;
    if ($minTemp == null) {
        $minTemp = $temp;
    }
    if ($temp < $minTemp) {
        $minTemp = $temp;
    }
    return $minTemp;
}

function set_summary_min_temp4($temp) {
    static $minTemp;
    if ($minTemp == null) {
        $minTemp = $temp;
    }
    if ($temp < $minTemp) {
        $minTemp = $temp;
    }
    return $minTemp;
}

function set_summary_max_temp($temp) {
    static $maxTemp;
    if ($maxTemp == null) {
        $maxTemp = $temp;
    }
    if ($temp > $maxTemp) {
        $maxTemp = $temp;
    }
    return $maxTemp;
}

function set_summary_max_temp2($temp) {
    static $maxTemp;
    if ($maxTemp == null) {
        $maxTemp = $temp;
    }
    if ($temp > $maxTemp) {
        $maxTemp = $temp;
    }
    return $maxTemp;
}

function set_summary_max_temp3($temp) {
    static $maxTemp;
    if ($maxTemp == null) {
        $maxTemp = $temp;
    }
    if ($temp > $maxTemp) {
        $maxTemp = $temp;
    }
    return $maxTemp;
}

function set_summary_max_temp4($temp) {
    static $maxTemp;
    if ($maxTemp == null) {
        $maxTemp = $temp;
    }
    if ($temp > $maxTemp) {
        $maxTemp = $temp;
    }
    return $maxTemp;
}

/* get min and max temp-limit for this customer */

function get_min_max_temp($tempselect, $return, $temp_sensors = null) {
    $sess_temp_sensors = ($temp_sensors != null) ? $temp_sensors : $_SESSION['temp_sensors'];
    $temp_max_limit = 7;
    $temp_min_limit = 0;
    $temp_color = array();
    if ($sess_temp_sensors == 4) {
        if (isset($tempselect) && $tempselect == 1) {
            $temp_max_limit = isset($return->temp1_max) ? $return->temp1_max : $temp_max_limit;
            $temp_min_limit = isset($return->temp1_min) ? $return->temp1_min : $temp_min_limit;
        }
        if (isset($tempselect) && $tempselect == 2) {
            $temp_max_limit = isset($return->temp2_max) ? $return->temp2_max : $temp_max_limit;
            $temp_min_limit = isset($return->temp2_min) ? $return->temp2_min : $temp_min_limit;
        }
        if (isset($tempselect) && $tempselect == 3) {
            $temp_max_limit = isset($return->temp3_max) ? $return->temp3_max : $temp_max_limit;
            $temp_min_limit = isset($return->temp3_min) ? $return->temp3_min : $temp_min_limit;
        }
        if (isset($tempselect) && $tempselect == 4) {
            $temp_max_limit = isset($return->temp4_max) ? $return->temp4_max : $temp_max_limit;
            $temp_min_limit = isset($return->temp4_min) ? $return->temp4_min : $temp_min_limit;
        }
    }
    if ($sess_temp_sensors == 3) {
        if (isset($tempselect) && $tempselect == 1) {
            $temp_max_limit = isset($return->temp1_max) ? $return->temp1_max : $temp_max_limit;
            $temp_min_limit = isset($return->temp1_min) ? $return->temp1_min : $temp_min_limit;
        }
        if (isset($tempselect) && $tempselect == 2) {
            $temp_max_limit = isset($return->temp2_max) ? $return->temp2_max : $temp_max_limit;
            $temp_min_limit = isset($return->temp2_min) ? $return->temp2_min : $temp_min_limit;
        }
        if (isset($tempselect) && $tempselect == 3) {
            $temp_max_limit = isset($return->temp3_max) ? $return->temp3_max : $temp_max_limit;
            $temp_min_limit = isset($return->temp3_min) ? $return->temp3_min : $temp_min_limit;
        }
    }
    if ($sess_temp_sensors == 2) {
        if (isset($tempselect) && $tempselect == 1) {
            $temp_max_limit = isset($return->temp1_max) ? $return->temp1_max : $temp_max_limit;
            $temp_min_limit = isset($return->temp1_min) ? $return->temp1_min : $temp_min_limit;
        }
        if (isset($tempselect) && $tempselect == 2) {
            $temp_max_limit = isset($return->temp2_max) ? $return->temp2_max : $temp_max_limit;
            $temp_min_limit = isset($return->temp2_min) ? $return->temp2_min : $temp_min_limit;
        }
    }
    if ($sess_temp_sensors == 1) {
        $temp_max_limit = isset($return->temp1_max) ? $return->temp1_max : $temp_max_limit;
        $temp_min_limit = isset($return->temp1_min) ? $return->temp1_min : $temp_min_limit;
    }
    if (isset($return->trcmvehicleid) && isset($tempselect)) {
        for ($i = 1; $i < 5; $i++) {
            $key_start = 'temp' . $tempselect . '_range' . $i . '_start';
            $key_end = 'temp' . $tempselect . '_range' . $i . '_end';
            $key_color = 'temp' . $tempselect . '_range' . $i . '_color';
            $temp_color[$key_start] = $return->$key_start;
            $temp_color[$key_end] = $return->$key_end;
            $temp_color[$key_color] = $return->$key_color;
        }
    }
    return array('temp_max_limit' => $temp_max_limit, 'temp_min_limit' => $temp_min_limit, 'temp_color' => $temp_color);
}

function create_humiditypdf_from_report($datarows, $vehicle, $custID = null, $veh_temp_details = null, $switchto = null) {
    $i = 1;
    $tr = 0;
    $tr_abv_max = 0;
    $tr2_abv_max = 0;
    $tr3_abv_max = 0;
    $tr4_abv_max = 0;
    $totalminute = 0;
    $temp2_min = 0;
    $temp2_max = 0;
    $lastdate = null;
    $display = '';
    $tempconversion = new TempConversion();
    $tempconversion->unit_type = $vehicle->get_conversion;
    $tempconversion->use_humidity = $_SESSION['use_humidity'];
    $tempconversion->switch_to = $_SESSION['switch_to'];
    if (isset($datarows)) {
        $customerno = ($custID == null) ? $_SESSION['customerno'] : $custID;
        $cm = new CustomerManager(null);
        $cm_details = $cm->getcustomerdetail_byid($customerno);
        $min_max_temp1 = get_min_max_temp(1, $veh_temp_details, $cm_details->temp_sensors);
        $min_max_temp2 = get_min_max_temp(2, $veh_temp_details, $cm_details->temp_sensors);
        $min_max_temp3 = get_min_max_temp(3, $veh_temp_details, $cm_details->temp_sensors);
        $min_max_temp4 = get_min_max_temp(4, $veh_temp_details, $cm_details->temp_sensors);
        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                if ($cm_details->use_humidity == 1) {
                    $display .= "
                                        <tr style='background-color:#CCCCCC;font-weight:bold;'>
                                            <td style='width:150px;height:auto;'>Time</td>
                                            <td style='width:150px;height:auto;'>Humidity</td>
                                        </tr>";
                }
                if ($cm_details->use_humidity == 1) {
                    $display .= "<tr><td colspan='2' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                }
                $i++;
            }
            //Removing Date Details From DateTimespeedConstants::DEFAULT_TIME
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            $display .= "<tr><td style='width:150px;height:auto;'>" . date(speedConstants::DEFAULT_TIME, strtotime($change->starttime)) . "</td>";
            //$location = get_location_detail($change->devicelat, $change->devicelong, $custID);
            //$display .= "<td style='width:250px;height:auto;'>$location</td>";
            // Temperature Sensors
            // Temperature Sensor
            if ($cm_details->use_humidity == 1) {
                $temp = 'Not Active';
                $s = "analog" . $vehicle->humidity;
                if ($vehicle->humidity != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp = getTempUtil($tempconversion);
                } else {
                    $temp = '-';
                }

                if ($temp != '-' && $temp != "Not Active") {
                    $display .= "<td style='width:150px;height:auto;'>$temp %</td>";
                } else {
                    $display .= "<td style='width:150px;height:auto;'>$temp</td>";
                }

                /* min temp */
                $temp1_min = set_summary_min_temp($temp);
                /* maximum temp */
                $temp1_max = set_summary_max_temp($temp);
                /* above max */
                if ($temp > $min_max_temp1['temp_max_limit']) {
                    $tr_abv_max++;
                }
            }
            $display .= '</tr>';
            $tr++;
        }
    }
    $display .= '</tbody>';
    $display .= '</table><br/>';
    $abv_compliance = round(($tr_abv_max / $tr) * 100, 2);
    $within_compliance = round((($tr - $tr_abv_max) / $tr) * 100, 2);
    $temp1_data = "<td style='text-align:center;'>
                        <table class='table newTable'><thead><tr><th><u>Humidity</u></th></tr></thead>
                            <tbody><tr><td>Minimum Humidity: $temp1_min%</td></tr>
                                <tr><td>Maximum Humidity: $temp1_max %</td></tr>
                                <tr><td>Total Reading: $tr</td></tr>
                            </tbody></table>
                        </td>";
    $summary = "<table align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                        <thead>
                            <tr><td colspan=$span style='background-color:#CCCCCC;font-weight:bold;'>Statistics</td></tr>
                        </thead>
                        <tbody>
                            <tr>$temp1_data</tr>
                        </tbody>
                    </table>";
    $display .= "$summary";
    return $display;
}

function datewise_temp_excep($totaldays, $datewise_arr, $for = 'html') {
    $datewise = '';
    if ($totaldays > 1) {
        if ($for == 'pdf') {
            $datewise .= "<table align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tbody><tr><td colspan='3'  style='background-color:#CCCCCC;font-weight:bold;'>Datewise</td></tr>";
            $datewise .= "<tr style='background-color:#CCCCCC;font-weight:bold;'><td>Date</td><td>Exception time</td><td>Normal time</td></tr>";
        } else {
            $datewise .= "<table class='table newTable' style='width:50%;'><thead><tr><th colspan='3'>Datewise</th></tr>";
            $datewise .= "<tr><th>Date</th><th>Exception time</th><th>Normal time</th></tr>";
            $datewise .= "</thead>";
            $datewise .= "<tbody>";
        }
        foreach ($datewise_arr as $date => $val) {
            $exception = get_hh_mm($val * 60);
            if ($date == date('d-m-Y')) {
                $now = (date('H') * 60) + date('i');
                $normal_val = $now - $val;
            } else {
                $normal_val = 1440 - $val;
            }
            $normal = get_hh_mm($normal_val * 60);
            $datewise .= "<tr><td>$date</td><td>$exception Hours</td><td>$normal Hours</td></tr>";
        }
        $datewise .= "</tbody></table><br/>";
    }
    return $datewise;
}

function create_temphtml_Excep_report($datarows, $vehicle, $veh_temp_details = null, $tempselect = null, $totaldays = 1, $datediff = 24) {
    $tr = 0;
    $display = '';
    $blank = false;
    $min_max_temp1 = get_min_max_temp($tempselect, $veh_temp_details);
    $final = array();
    $setstart = 0;
    $count = 0;
    $tempconversion = new TempConversion();
    $tempconversion->unit_type = $vehicle->get_conversion;
    $tempconversion->use_humidity = $_SESSION['use_humidity'];
    $tempconversion->switch_to = $_SESSION['switch_to'];
    /*  prettyPrint($vehicle);*/
    $select = "tempsen" . $tempselect;
    if (isset($vehicle->$select) && $vehicle->$select == "0") {
        echo "Data Not Applicable";
        exit;
    } else {
        foreach ($datarows as $change) {
            $k = "tempsen$tempselect";
            $s = "analog{$vehicle->$k}";

            $tempconversion->rawtemp = $change->$s;
            $temp = getTempUtil($tempconversion);
            if ($temp != '') {
                get_min_temperature($temp);
                get_max_temperature($temp);
            }
            $v = date("Y-m-d", strtotime($change->starttime)) . " 23:59:00 ";
            $conflict = false;
            if ($temp != '' && ($temp < $min_max_temp1['temp_min_limit'] || $temp > $min_max_temp1['temp_max_limit']) && strtotime($change->starttime) < strtotime($v)) {
                $conflict = true;
            }
            if ($conflict) {
                if ($setstart == 0) {
                    $final[$count] = array('starttime' => $change->starttime, 'starttemp' => $temp);
                }
                $setstart++;
                $prevTime = $change->starttime;
            } else {
                if (isset($prevTime)) {
                    $final[$count]['endtime'] = $prevTime;
                    $final[$count]['endtemp'] = $temp;
                    $count++;
                    unset($prevTime);
                }
                $setstart = 0;
            }
        }
    }
    if (!empty($final)) {
        $tr = 0;
        $datewise_arr = array();
        foreach ($final as $datalist) {
            $display .= "<tr><td>" . date('d-m-Y', strtotime($datalist['starttime'])) . "</td>";
            $display .= "<td>" . date(speedConstants::DEFAULT_TIME, strtotime($datalist['starttime'])) . "</td>";
            $display .= "<td>" . $datalist['starttemp'] . " &deg;C</td>";
            if (empty($datalist['endtime'])) {
                $datalist['endtime'] = $v;
                $datalist['endtemp'] = $datalist['starttemp'];
                $display .= "<td>" . date('d-m-Y', strtotime($datalist['endtime'])) . "</td>";
                $display .= "<td>" . date(speedConstants::DEFAULT_TIME, strtotime($datalist['endtime'])) . "</td>";
                $display .= "<td>" . $datalist['endtemp'] . " &deg;C</td>";
                $time = getduration($datalist['endtime'], $datalist['starttime']);
            } else {
                $display .= "<td>" . date('d-m-Y', strtotime($datalist['starttime'])) . "</td>";
                $display .= "<td>" . date(speedConstants::DEFAULT_TIME, strtotime($datalist['starttime'])) . "</td>";
                $display .= "<td>" . $datalist['endtemp'] . " &deg;C</td>";
                $time = getduration($datalist['endtime'], $datalist['starttime']);
            }
            $tr += $time;
            $hour = floor(($time) / 60);
            $minutes = ($time) % 60;
            if ($minutes < 10) {
                $minutes = "0" . $minutes;
            }
            $time1 = $hour . ":" . $minutes;
            $display .= "<td>$time1</td><tr>";
            $thisdate = date('d-m-Y', strtotime($datalist['starttime']));
            if (isset($datewise_arr[$thisdate])) {
                $datewise_arr[$thisdate] += $time;
            } else {
                $datewise_arr[$thisdate] = $time;
            }
        }
        $display .= '</tbody>';
        $display .= '</table>';
        $hour = floor(($tr) / 60);
        $minutes = ($tr) % 60;
        if ($minutes < 10) {
            $minutes = "0" . $minutes;
        }
        $tr1 = $hour . ":" . $minutes;
        $datewise = datewise_temp_excep($totaldays, $datewise_arr);
        $normaltime = get_hh_mm($datediff - ($tr * 60));
        $static_min_temp = get_min_temperature(null, true);
        $static_max_temp = get_max_temperature(null, true);
        $totalhours = round($datediff / 60 / 60);
        $summary = "$datewise<table class='table newTable' style='width:45%;'><thead><tr><th>Statistics(Temperature" . $tempselect . ")</th></tr></thead>";
        $summary .= "<tbody><tr><td style='text-align:center;'>Total number of selected days: $totaldays</td></tr>";
        $summary .= "<tr><td style='text-align:center;'>Total hours: " . $totalhours . " Hours</td></tr>";
        $summary .= "<tr><td style='text-align:center;'>Total normal temperature time: $normaltime Hours</td></tr>";
        $summary .= "<tr><td style='text-align:center;'>Total exception temperature time: $tr1 Hours</td></tr>";
        $summary .= "<tr><td style='text-align:center;'>Minimum temperature: $static_min_temp &deg;C</td></tr>";
        $summary .= "<tr><td style='text-align:center;'>Maximum temperature: $static_max_temp &deg;C</td></tr>";
        $summary .= "</tbody></table>";
        $display .= "$summary</div>";
    } else {
        $display .= "<tr><td colspan='100%' style='text-align:center;'>No Data</td></tr>";
    }
    return $display;
}

function create_temppdf_Excep_report($datarows, $vehicle, $custID = null, $veh_temp_details = null, $tempselect = 1, $totaldays = 1, $datediff = 24) {
    $customerno = ($custID == null) ? $_SESSION['customerno'] : $custID;
    $cm = new CustomerManager(null);
    $cm_details = $cm->getcustomerdetail_byid($customerno);
    $min_max_temp1 = get_min_max_temp($tempselect, $veh_temp_details, $cm_details->temp_sensors);
    $final = array();
    $setstart = 0;
    $count = 0;
    $tempconversion = new TempConversion();
    $tempconversion->unit_type = $vehicle->get_conversion;
    $tempconversion->use_humidity = $_SESSION['use_humidity'];
    $tempconversion->switch_to = $_SESSION['switch_to'];
    foreach ($datarows as $change) {
        $k = "tempsen$tempselect";
        $s = "analog{$vehicle->$k}";
        $tempconversion->rawtemp = $change->$s;
        $temp = getTempUtil($tempconversion);
        if ($temp != '') {
            get_min_temperature($temp);
            get_max_temperature($temp);
        }
        $v = date("Y-m-d", strtotime($change->starttime)) . " 23:59:00 ";
        $conflict = false;
        if ($temp != '' && ($temp < $min_max_temp1['temp_min_limit'] || $temp > $min_max_temp1['temp_max_limit']) && strtotime($change->starttime) < strtotime($v)) {
            $conflict = true;
        }
        if ($conflict) {
            if ($setstart == 0) {
                $final[$count] = array('starttime' => $change->starttime, 'starttemp' => $temp);
            }
            $setstart++;
            $prevTime = $change->starttime;
        } else {
            if (isset($prevTime)) {
                $final[$count]['endtime'] = $prevTime;
                $final[$count]['endtemp'] = $temp;
                $count++;
                unset($prevTime);
            }
            $setstart = 0;
        }
    }
    $display = "
                    <tr style='background-color:#CCCCCC;font-weight:bold;'>
                        <td style='width:150px;height:auto;'>Start Date</td>
                        <td style='width:150px;height:auto;'>Start Time</td>
                        <td style='width:150px;height:auto;'>Start Temp</td>
                        <td style='width:150px;height:auto;'>End Date</td>
                        <td style='width:150px;height:auto;'>End Time</td>
                        <td style='width:150px;height:auto;'>End Temp</td>
                        <td style='width:150px;height:auto;'>Duration [HH:MM]</td>
                    </tr>";
    $tr = 0;
    $datewise_arr = array();
    if (!empty($final)) {
        foreach ($final as $datalist) {
            $display .= "<tr><td>" . date("d-m-Y", strtotime($datalist['starttime'])) . "</td>";
            $display .= "<td>" . date(speedConstants::DEFAULT_TIME, strtotime($datalist['starttime'])) . "</td>";
            $display .= "<td>" . $datalist['starttemp'] . " &deg;C</td>";
            if (empty($datalist['endtime'])) {
                $datalist['endtime'] = $v;
                $datalist['endtemp'] = $datalist['starttemp'];
                $display .= "<td>" . date("d-m-Y", strtotime($datalist['endtime'])) . "</td>";
                $display .= "<td>" . date(speedConstants::DEFAULT_TIME, strtotime($datalist['endtime'])) . "</td>";
                $display .= "<td>" . $datalist['endtemp'] . " &deg;C</td>";
            } else {
                $display .= "<td>" . date("d-m-Y", strtotime($datalist['starttime'])) . "</td>";
                $display .= "<td>" . date(speedConstants::DEFAULT_TIME, strtotime($datalist['starttime'])) . "</td>";
                $display .= "<td>" . $datalist['endtemp'] . " &deg;C</td>";
            }
            $time = getduration($datalist['endtime'], $datalist['starttime']);
            $tr += $time;
            $hour = floor(($time) / 60);
            $minutes = ($time) % 60;
            if ($minutes < 10) {
                $minutes = "0" . $minutes;
            }
            $time1 = $hour . ":" . $minutes;
            $display .= "<td>$time1</td></tr>";
            $thisdate = date('d-m-Y', strtotime($datalist['starttime']));
            if (isset($datewise_arr[$thisdate])) {
                $datewise_arr[$thisdate] += $time;
            } else {
                $datewise_arr[$thisdate] = $time;
            }
        }
        $hour = floor(($tr) / 60);
        $minutes = ($tr) % 60;
        if ($minutes < 10) {
            $minutes = "0" . $minutes;
        }
        $tr1 = $hour . ":" . $minutes;
        $datewise = datewise_temp_excep($totaldays, $datewise_arr, 'pdf');
        $normaltime = get_hh_mm($datediff - ($tr * 60));
        $static_min_temp = get_min_temperature(null, true);
        $static_max_temp = get_max_temperature(null, true);
        $totalhours = round($datediff / 60 / 60);
        $display .= "</tbody></table><br/><br/>";
        $display .= "$datewise<table align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
        $display .= "<thead><tr><td style='background-color:#CCCCCC;font-weight:bold;' >Statistics(Temperature" . $tempselect . ")</td></tr></thead>";
        $display .= "<tbody><tr><td style='text-align:center;'>Total number of selected days: $totaldays</td></tr>";
        $display .= "<tr><td style='text-align:center;'>Total hours: " . $totalhours . " Hours</td></tr>";
        $display .= "<tr><td style='text-align:center;'>Total normal temperature time: $normaltime Hours</td></tr>";
        $display .= "<tr><td style='text-align:center;'>Total exception temperature time: $tr1 Hours</td></tr>";
        $display .= "<tr><td style='text-align:center;'>Minimum Temperature: $static_min_temp &deg;C</td></tr>";
        $display .= "<tr><td style='text-align:center;'>Maximum Temperature: $static_max_temp &deg;C</td></tr>";
    } else {
        $display .= "<tr><td colspan='100%' style='text-align:center;'> No Data </td></tr>";
    }
    $display .= '</tbody>';
    $display .= '</table>';
    return $display;
}

function create_pdf_from_report($datarows, $acinvert) {
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
    $display = '';
    if (isset($datarows)) {
        foreach ($datarows as $change) {
            //            $comparedate = date('d-m-Y',strtotime($change->endtime));
            //            if(strtotime($lastdate) != strtotime($comparedate))
            //            {
            //                $display .= "<tr style='background-color:#CCCCCC;'><th align='center' colspan = '100%'>".date('d-m-Y',strtotime($change->endtime))."</th></tr>";
            //                $lastdate = date('d-m-Y',strtotime($change->endtime));
            //            }
            $currentdate = date("Y-m-d H:i:s");
            $currentdate = substr($currentdate, '0', 11);
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            $display .= "<tr><td style='width:100px;height:auto;'>$change->starttime</td><td style='width:100px;height:auto;'>$change->endtime</td>";
            if ($change->ignition == 1) {
                $display .= "<td style='width:150px;height:auto;'>ON</td>";
            } else {
                $display .= "<td style='width:150px;height:auto;'>OFF</td>";
            }
            if ($acinvert == 1) {
                if ($change->digitalio == 0) {
                    $display .= "<td style='width:150px;height:auto;'>OFF</td>";
                    $runningtime += $change->duration;
                } else {
                    $display .= "<td style='width:150px;height:auto;'>ON</td>";
                    $idletime += $change->duration;
                }
            } else {
                if ($change->digitalio == 0) {
                    $display .= "<td style='width:150px;height:auto;'>ON</td>";
                    $runningtime += $change->duration;
                } else {
                    $display .= "<td style='width:150px;height:auto;'>OFF</td>";
                    $idletime += $change->duration;
                }
            }
            $dt = new DateTime();
            $currDate = date_format($dt, 'Y-m-d');
            //$startT = gmdate("H:i", $change->duration);
            $startT = date('H:i', strtotime($change->duration . ' minutes'));
            $hours = floor($change->duration / 60); //round down to nearest minute.
            $minutes = $change->duration % 60;
            if ($hours <= 9) {
                $hour = '0' . $hours;
            } else {
                $hour = $hours;
            }
            if ($minutes <= 9) {
                $minute = '0' . $minutes;
            } else {
                $minute = $minutes;
            }
            $display .= "<td style='width:100px;height:auto;'>$hour : $minute</td>";
            $display .= '</tr>';
        }
    }
    $display .= '</tbody>';
    $display .= "</table>";
    $display .= "
                        <page_footer>
                        [[page_cu]]/[[page_nb]]
                        </page_footer>
                        <div style='float:right;margin:15px;margin-right:60px;'>
                            <table align='right' style='font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tr style='background-color:#CCCCCC;'><th><h4>Statistics</h4></th></tr>";
    if ($acinvert == 1) {
        $offtime = 1440 - $idletime;
        $display .= "<tr><td>Total Gen Set ON Time = $idletime Minutes</td></tr><tr><td>Total Gen Set OFF Time = $offtime Minutes</td></tr>";
    } else {
        $offtime = 1440 - $runningtime;
        $display .= "<tr><td>Total Gen Set ON Time = $runningtime Minutes</td></tr><tr><td>Total Gen Set OFF Time = $offtime Minutes</td></tr>";
    }
    $display .= "</table></div><hr style='margin-top:5px;'>";
    $formatdate1 = date(speedConstants::DEFAULT_DATETIME);
    $display .= "<div align='right' style='text-align:center;'> Report Generated On: $formatdate1 </div>";
    return $display;
}

function create_gensetpdf_from_report($datarows, $acinvert) {
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
    $display = '';
    if (isset($datarows)) {
        foreach ($datarows as $change) {
            //            $comparedate = date('d-m-Y',strtotime($change->endtime));
            //            if(strtotime($lastdate) != strtotime($comparedate))
            //            {
            //                $display .= "<tr style='background-color:#CCCCCC;'><th align='center' colspan = '100%'>".date('d-m-Y',strtotime($change->endtime))."</th></tr>";
            //                $lastdate = date('d-m-Y',strtotime($change->endtime));
            //            }
            $currentdate = date("Y-m-d H:i:s");
            $currentdate = substr($currentdate, '0', 11);
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            $display .= "<tr><td style='width:100px;height:auto;'>$change->starttime</td><td style='width:100px;height:auto;'>$change->endtime</td>";
            //            if($change->ignition==1)
            //            {
            //                $display .= "<td style='width:150px;height:auto;'>ON</td>";
            //            }
            //            else
            //            {
            //                $display .= "<td style='width:150px;height:auto;'>OFF</td>";
            //            }
            if ($acinvert == 1) {
                if ($change->digitalio == 0) {
                    $display .= "<td style='width:150px;height:auto;'>OFF</td>";
                    $runningtime += $change->duration;
                } else {
                    $display .= "<td style='width:150px;height:auto;'>ON</td>";
                    $idletime += $change->duration;
                }
            } else {
                if ($change->digitalio == 0) {
                    $display .= "<td style='width:150px;height:auto;'>ON</td>";
                    $runningtime += $change->duration;
                } else {
                    $display .= "<td style='width:150px;height:auto;'>OFF</td>";
                    $idletime += $change->duration;
                }
            }
            $dt = new DateTime();
            $currDate = date_format($dt, 'Y-m-d');
            //$startT = gmdate("H:i", $change->duration);
            $startT = date('H:i', strtotime($change->duration . ' minutes'));
            $hours = floor($change->duration / 60); //round down to nearest minute.
            $minutes = $change->duration % 60;
            if ($hours <= 9) {
                $hour = '0' . $hours;
            } else {
                $hour = $hours;
            }
            if ($minutes <= 9) {
                $minute = '0' . $minutes;
            } else {
                $minute = $minutes;
            }
            $display .= "<td style='width:100px;height:auto;'>$hour : $minute</td>";
            $display .= '</tr>';
        }
    }
    $display .= '</tbody>';
    $display .= "</table>";
    $display .= "
                        <page_footer>
                        [[page_cu]]/[[page_nb]]
                        </page_footer>
                        <div style='float:right;margin:15px;margin-right:60px;'>
                            <table align='right' style='font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tr style='background-color:#CCCCCC;'><th><h4>Statistics</h4></th></tr>";
    if ($acinvert == 1) {
        $offtime = 1440 - $idletime;
        $display .= "<tr><td>Total Gen Set ON Time = $idletime Minutes</td></tr><tr><td>Total Gen Set OFF Time = $offtime Minutes</td></tr>";
    } else {
        $offtime = 1440 - $runningtime;
        $display .= "<tr><td>Total Gen Set ON Time = $runningtime Minutes</td></tr><tr><td>Total Gen Set OFF Time = $offtime Minutes</td></tr>";
    }
    $display .= "</table></div><hr style='margin-top:5px;'>";
    $formatdate1 = date(speedConstants::DEFAULT_DATETIME);
    $display .= "<div align='right' style='text-align:center;'> Report Generated On: $formatdate1 </div>";
    return $display;
}

function create_pdf_for_multipledays($datarows, $acinvert) {
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
    $display = '';
    if (isset($datarows)) {
        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                $display .= "</tbody></table><h4><div> Date " . date('d-m-Y', strtotime($change->endtime)) . "</div></h4>
                                            <hr  id='style-six' /><table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                            <tbody>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $display .= "
                                                <tr style='background-color:#CCCCCC;'>
                                                    <td style='width:100px;height:auto;'>Start Time</td>
                                                    <td style='width:100px;height:auto;'>End Time</td>
                                                    <td style='width:150px;height:auto;'>Ignition Status</td>
                                                    <td style='width:150px;height:auto;'>Gen Set Status</td>
                                                    <td style='width:150px;height:auto;'>Duration[Hours:Minutes]</td>
                                                </tr>";
            }
            $currentdate = date("Y-m-d H:i:s");
            $currentdate = substr($currentdate, '0', 11);
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            $display .= "<tr><td style='width:100px;height:auto;'>$change->starttime</td><td style='width:100px;height:auto;'>$change->endtime</td>";
            if ($change->ignition == 1) {
                $display .= "<td style='width:150px;height:auto;'>ON</td>";
            } else {
                $display .= "<td style='width:150px;height:auto;'>OFF</td>";
            }
            if ($acinvert == 1) {
                if ($change->digitalio == 0) {
                    $display .= "<td style='width:150px;height:auto;'>OFF</td>";
                    $runningtime += $change->duration;
                } else {
                    $display .= "<td style='width:150px;height:auto;'>ON</td>";
                    $idletime += $change->duration;
                }
            } else {
                if ($change->digitalio == 0) {
                    $display .= "<td style='width:150px;height:auto;'>ON</td>";
                    $runningtime += $change->duration;
                } else {
                    $display .= "<td style='width:150px;height:auto;'>OFF</td>";
                    $idletime += $change->duration;
                }
            }
            $dt = new DateTime();
            $currDate = date_format($dt, 'Y-m-d');
            //$startT = gmdate("H:i", $change->duration);
            $startT = date('H:i', strtotime($change->duration . ' minutes'));
            $hours = floor($change->duration / 60); //round down to nearest minute.
            $minutes = $change->duration % 60;
            if ($hours <= 9) {
                $hour = '0' . $hours;
            } else {
                $hour = $hours;
            }
            if ($minutes <= 9) {
                $minute = '0' . $minutes;
            } else {
                $minute = $minutes;
            }
            $display .= "<td style='width:100px;height:auto;'>$hour : $minute</td>";
            $display .= '</tr>';
        }
    }
    $display .= '</tbody>';
    $display .= "</table>";
    $display .= "
                        <page_footer>
                        [[page_cu]]/[[page_nb]]
                        </page_footer>
                        <div style='float:right;margin:15px;margin-right:60px;'>
                            <table align='right' style='font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tr style='background-color:#CCCCCC;'><th><h4>Statistics</h4></th></tr>";
    if ($acinvert == 1) {
        $display .= "<tr><td>Total Gen Set ON Time = $idletime Minutes</td></tr><tr><td>Total Gen Set OFF Time = $runningtime Minutes</td></tr>";
    } else {
        $display .= "<tr><td>Total Gen Set ON Time = $runningtime Minutes</td></tr><tr><td>Total Gen Set OFF Time = $idletime Minutes</td></tr>";
    }
    $display .= "</table></div><hr style='margin-top:5px;'>";
    $formatdate1 = date(speedConstants::DEFAULT_DATETIME);
    $display .= "<div align='right' style='text-align:center;'> Report Generated On: $formatdate1 </div>";
    return $display;
}

function create_gensetpdf_for_multipledays($datarows, $acinvert, $customerno, $uselocation) {
    $i = 1;
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
    $totalminute = 0;
    $display = '';
    if (isset($datarows)) {
        $date_wise_arr = array();
        foreach ($datarows as $change) {
            $thisdate = date('d-m-Y', strtotime($change->starttime));
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $display .= "</tbody></table>
                                            <hr  id='style-six' /><br/>
                                            <table  id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;' ><tbody>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $display .= "
                                                <tr style='background-color:#CCCCCC;font-weight:bold;'>
                                                    <td style='width:50px;height:auto;'>Start Time</td>
                                                    <td style='width:300px;height:auto;'>Start Location</td>
                                                    <td style='width:50px;height:auto;'>End Time</td>
                                                    <td style='width:300px;height:auto;'>End Location</td>
                                                    <td style='width:100px;height:auto;'>Genset Status</td>
                                                    <td style='width:100px;height:auto;'>Duration[HH:MM]</td>
                                                    <td style='width:100px;height:auto;'>Fuel Consumed<br>(In litre)</td>
                                                </tr>
                                                <tr style='background-color:#D8D5D6;font-weight:bold;'><td colspan='7' >Date $lastdate</td></tr>";
                $i++;
            }
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            if (!isset($change->startcgeolat) || !isset($change->startcgeolong)) {
                $change->startlocation = 'Unable to fetch Location';
                $change->endlocation = 'Unable to fetch Location';
            } else {
                $change->startlocation = location_cmn($change->startcgeolat, $change->startcgeolong, $uselocation, $customerno);
                $change->endlocation = location_cmn($change->endcgeolat, $change->endcgeolong, $uselocation, $customerno);
            }
            $start_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
            $end_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->endtime));
            $display .= "<tr><td style='width:50px;height:auto;'>$start_time_disp</td><td style='width:300px;height:auto;'>$change->startlocation</td>
                                            <td style='width:50px;height:auto;' >$end_time_disp</td><td style='width:300px;height:auto;'>$change->endlocation</td>";
            if ($acinvert == 1) {
                if ($change->digitalio == 0) {
                    $display .= "<td style='width:100px;height:auto;'>OFF</td>";
                    $runningtime += $change->duration;
                } else {
                    $display .= "<td style='width:100px;height:auto;'>ON</td>";
                    $idletime += $change->duration;
                    if (isset($date_wise_arr[$thisdate])) {
                        $date_wise_arr[$thisdate] += $change->duration;
                    } else {
                        $date_wise_arr[$thisdate] = $change->duration;
                    }
                }
            } else {
                if ($change->digitalio == 0) {
                    $display .= "<td style='width:100px;height:auto;'>ON</td>";
                    $runningtime += $change->duration;
                    if (isset($date_wise_arr[$thisdate])) {
                        $date_wise_arr[$thisdate] += $change->duration;
                    } else {
                        $date_wise_arr[$thisdate] = $change->duration;
                    }
                } else {
                    $display .= "<td style='width:100px;height:auto;'>OFF</td>";
                    $idletime += $change->duration;
                }
            }
            $dt = new DateTime();
            $hours = floor($change->duration / 60); //round down to nearest minute.
            $minutes = $change->duration % 60;
            if ($hours <= 9) {
                $hour = '0' . $hours;
            } else {
                $hour = $hours;
            }
            if ($minutes <= 9) {
                $minute = '0' . $minutes;
            } else {
                $minute = $minutes;
            }
            $display .= "<td style='width:150px;height:auto;'>$hour : $minute</td>";
            if (isset($change->fuelltr)) {
                $fuelltr[] = $change->fuelltr;
                $display .= "<td>" . $change->fuelltr . "</td>";
            } else {
                $display .= "<td> </td>";
            }
            $display .= '</tr>';
        }
    }
    $display .= '</tbody></table>';
    $display .= "<page_footer>[[page_cu]]/[[page_nb]]</page_footer>";
    $totaltime = 1440 * $count + $totalminute;
    $totaltime = round($totaltime);
    if ($acinvert == 1) {
        $offtime = $totaltime - $idletime;
        $last = "<tr><td colspan = '9'>Total Genset ON Time = " . get_hh_mm($idletime * 60) . " Hours</td></tr><tr><td colspan = '9'>Total Genset OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>	<tr><td style='text-align:center;'>Total Fuel Consumed = " . array_sum($fuelltr) . " ltr</td></tr>";
    } else {
        $offtime = $totaltime - $runningtime;
        $last .= "<tr><td colspan = '9'>Total Genset ON Time = " . get_hh_mm($runningtime * 60) . " Hours</td></tr><tr><td colspan = '9'>Total Genset OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>	<tr><td style='text-align:center;'>Total Fuel Consumed = " . array_sum($fuelltr) . " ltr</td></tr>";
    }
    $datewise = ac_datewise($count, $date_wise_arr, 'pdf');
    $display .= "
                        <div style='float:right;margin:15px;margin-right:60px;'>
                            $datewise
                            <table align='center'  style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                             <tbody>
                                <tr  style='background-color:#CCCCCC;font-weight:bold;'><td colspan = '9'>Statistics</td></tr>
                                $last
                            </tbody>
                        </table>
                    </div>";
    return $display;
}

function create_gensetpdf_for_multipledays_details($datarows, $acinvert, $customerno, $uselocation, $gensetSensor = null) {
    $i = 1;
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
    $totalminute = 0;
    $display = '';
    $gensetSensor = isset($gensetSensor) ? $gensetSensor : 1;
    if (isset($datarows)) {
        $fromTime = reset($datarows);
        $toTime = end($datarows);
        $totalminute = round(abs(strtotime($fromTime->starttime) - strtotime($toTime->endtime)) / 60, 2);

        $date_wise_arr = array();
        foreach ($datarows as $change) {
            /* Code to Continuing the loop if duration = "00:00" and Ignition status = "Running" starts here */

            $continueLoopStatus = getContinueLoopStatus($change->duration, $change->distancetravelled, $change->ignition);
            if ($continueLoopStatus) {
                continue;
            }
            /*  Code to Continuing the loop if duration = "00:00" and Ignition status = "Running" ends here  */
            if ($gensetSensor == 2) {
                $change->digitalio = $change->extradigitalio;
            }
            $thisdate = date('d-m-Y', strtotime($change->starttime));
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    //$totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $display .= "</tbody></table>
                                <hr  id='style-six' /><br/>
                                <table  id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;' ><tbody>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $fuelsensor = "";
                if ($_SESSION['use_fuel_sensor'] != 0) {
                    $fuelsensor .= "<td style='width:100px;height:auto;'>Fuel Consumed<br>(In litre)</td></tr>
                                        <tr style='background-color:#D8D5D6;font-weight:bold;'><td colspan='7' >Date $lastdate</td></tr>";
                } else {
                    $fuelsensor .= "</tr><tr style='background-color:#D8D5D6;font-weight:bold;'><td colspan='6' >Date $lastdate</td></tr>";
                }
                $display .= "
                                    <tr style='background-color:#CCCCCC;font-weight:bold;'>
                                        <td style='width:50px;height:auto;'>Start Time</td>
                                        <td style='width:300px;height:auto;'>Start Location</td>
                                        <td style='width:50px;height:auto;'>End Time</td>
                                        <td style='width:300px;height:auto;'>End Location</td>
                                        <td style='width:100px;height:auto;'>Genset Status</td>
                                        <td style='width:100px;height:auto;'>Ignition Status</td>
                                        <td style='width:100px;height:auto;'>Duration[HH:MM]</td>
                                        $fuelsensor ";
                $i++;
            }
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            if (!isset($change->startcgeolat) || !isset($change->startcgeolong)) {
                $change->startlocation = 'Unable to fetch Location';
                $change->endlocation = 'Unable to fetch Location';
            } else {
                $change->startlocation = location_cmn($change->startcgeolat, $change->startcgeolong, $uselocation, $customerno);
                $change->endlocation = location_cmn($change->endcgeolat, $change->endcgeolong, $uselocation, $customerno);
            }
            $start_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
            $end_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->endtime));
            $display .= "<tr><td style='width:50px;height:auto;'>$start_time_disp</td><td style='width:300px;height:auto;'>$change->startlocation</td>
                                    <td style='width:50px;height:auto;' >$end_time_disp</td><td style='width:300px;height:auto;'>$change->endlocation</td>";
            if ($gensetSensor == 1) {
                if ($acinvert == 1) {
                    if ($change->digitalio == 0) {
                        $display .= "<td style='width:100px;height:auto;'>OFF</td>";
                        $runningtime += $change->duration;
                    } else {
                        //$display .= "<td style='width:100px;height:auto;'>ON</td>";
                        /* Code for if AC is on but ignition status is OFF for 00:00 time then AC status should render OFF starts here */
                        @$fetchedStatus = renderACStatusOFF($change->duration, $change->distancetravelled, $change->ignition);
                        $display .= '<td style="width:100px;height:auto;">' . $fetchedStatus . '</td>';
                        /* Code for if AC is on but ignition status is OFF for 00:00 time then AC status should render OFF ends here */
                        $idletime += $change->duration;
                        if (isset($date_wise_arr[$thisdate])) {
                            $date_wise_arr[$thisdate] += $change->duration;
                        } else {
                            $date_wise_arr[$thisdate] = $change->duration;
                        }
                    }
                } else {
                    if ($change->digitalio == 0) {
                        //$display .= "<td style='width:100px;height:auto;'>ON</td>";
                        /* Code for if AC is on but ignition status is OFF for 00:00 time then AC status should render OFF starts here */
                        @$fetchedStatus = renderACStatusOFF($change->duration, $change->distancetravelled, $change->ignition);
                        $display .= '<td style="width:100px;height:auto;">' . $fetchedStatus . '</td>';
                        /* Code for if AC is on but ignition status is OFF for 00:00 time then AC status should render OFF ends here */
                        $runningtime += $change->duration;
                        if (isset($date_wise_arr[$thisdate])) {
                            $date_wise_arr[$thisdate] += $change->duration;
                        } else {
                            $date_wise_arr[$thisdate] = $change->duration;
                        }
                    } else {
                        $display .= "<td style='width:100px;height:auto;'>OFF</td>";
                        $idletime += $change->duration;
                    }
                }
            } else {
                if ($change->digitalio == 1) {
                    //$display .= "<td style='width:100px;height:auto;'>ON</td>";
                    /* Code for if AC is on but ignition status is OFF for 00:00 time then AC status should render OFF starts here */
                    @$fetchedStatus = renderACStatusOFF($change->duration, $change->distancetravelled, $change->ignition);
                    $display .= '<td style="width:100px;height:auto;">' . $fetchedStatus . '</td>';
                    /* Code for if AC is on but ignition status is OFF for 00:00 time then AC status should render OFF ends here */
                    $idletime += $change->duration;
                    if (isset($date_wise_arr[$thisdate])) {
                        $date_wise_arr[$thisdate] += $change->duration;
                    } else {
                        $date_wise_arr[$thisdate] = $change->duration;
                    }
                } else {
                    $display .= "<td style='width:100px;height:auto;'>OFF</td>";
                    $runningtime += $change->duration;
                }
            }
            /* if($change->ignition == 0)
            {
            $display .= "<td style='width:100px;height:auto;'>OFF</td>";
            }
            else
            {
            $display .= "<td style='width:100px;height:auto;'>ON</td>";
             */
            if (@$change->distancetravelled > 0) {
                $display .= "<td style='cursor:pointer;'> <a style='text-decoration:underline;'>Running</a></td>";
                //$runningtime += $change->duration;
            } else {
                if ($change->ignition == 1) {
                    $display .= "<td>Idle - Ignition On</td>";
                    //$idle_ign_on += $change->duration;
                } else {
                    $display .= "<td>Idle</td>";
                    //$idletime += $change->duration;
                }
            }
            $dt = new DateTime();
            $hours = floor($change->duration / 60); //round down to nearest minute.
            $minutes = $change->duration % 60;
            if ($hours <= 9) {
                $hour = '0' . $hours;
            } else {
                $hour = $hours;
            }
            if ($minutes <= 9) {
                $minute = '0' . $minutes;
            } else {
                $minute = $minutes;
            }
            $display .= "<td style='width:150px;height:auto;'>$hour : $minute</td>";
            if (isset($change->fuelltr) && $_SESSION['use_fuel_sensor'] != 0) {
                $fuelltr[] = $change->fuelltr;
                $display .= "<td>" . $change->fuelltr . "</td>";
            } else {
                $display .= "";
            }
            $display .= '</tr>';
        }
    }
    $display .= '</tbody></table>';
    $display .= "<page_footer>[[page_cu]]/[[page_nb]]</page_footer>";
    // $totaltime = 1440 * $count + $totalminute;
    $totaltime = round(1440 * $count + $totalminute);
    //$totaltime = round($totaltime);
    if ($acinvert == 1) {
        $offtime = $totalminute - $idletime;
        if ($_SESSION['use_fuel_sensor'] != 0) {
            $fuelcons = "<td style='text-align:center;'>Total Fuel Consumed = " . array_sum($fuelltr) . " ltr</td>";
        } else {
            $fuelcons = "";
        }
        $last = "<tr><td colspan = '9'>Total Genset ON Time = " . get_hh_mm($idletime * 60) . " Hours</td></tr><tr><td colspan = '9'>Total Genset OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>   <tr>" . $fuelcons . "</tr>";
    } else {
        $offtime = $totalminute - $runningtime;
        $last .= "<tr><td colspan = '9'>Total Genset ON Time = " . get_hh_mm($runningtime * 60) . " Hours</td></tr><tr><td colspan = '9'>Total Genset OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>   <tr>" . $fuelcons . "</tr>";
    }
    $display .= " <div style='float:right;margin:15px;margin-right:60px;'> <table align='center'  style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'> <tbody> <tr  style='background-color:#CCCCCC;font-weight:bold;'><td colspan = '9'>Statistics</td></tr> $last </tbody> </table></div>";
    return $display;
}

function create_gensetpdf_for_multipledays_summary($datarows, $acinvert, $customerno, $uselocation) {
    $i = 1;
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
    $totalminute = 0;
    $display = '';
    if (isset($datarows)) {
        $date_wise_arr = array();
        foreach ($datarows as $change) {
            $thisdate = date('d-m-Y', strtotime($change->starttime));
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                //$display .="<table  id='search_table_2' align='center' style='display:none;' ><tbody>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                //$display .= "<tr style='background-color:#D8D5D6;font-weight:bold;'><td colspan='7' >Date $lastdate</td></tr>";
                $i++;
            }
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            if (!isset($change->startcgeolat) || !isset($change->startcgeolong)) {
                $change->startlocation = 'Unable to fetch Location';
                $change->endlocation = 'Unable to fetch Location';
            } else {
                $change->startlocation = location_cmn($change->startcgeolat, $change->startcgeolong, $uselocation, $customerno);
                $change->endlocation = location_cmn($change->endcgeolat, $change->endcgeolong, $uselocation, $customerno);
            }
            $start_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
            $end_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->endtime));
            //            $display .= "<tr><td style='width:50px;height:auto;'>$start_time_disp</td><td style='width:300px;height:auto;'>$change->startlocation</td>
            //                    <td style='width:50px;height:auto;' >$end_time_disp</td><td style='width:300px;height:auto;'>$change->endlocation</td>";
            if ($acinvert == 1) {
                if ($change->digitalio == 0) {
                    //$display .= "<td style='width:100px;height:auto;'>OFF</td>";
                    $runningtime += $change->duration;
                } else {
                    //$display .= "<td style='width:100px;height:auto;'>ON</td>";
                    $idletime += $change->duration;
                    if (isset($date_wise_arr[$thisdate])) {
                        $date_wise_arr[$thisdate] += $change->duration;
                    } else {
                        $date_wise_arr[$thisdate] = $change->duration;
                    }
                }
            } else {
                if ($change->digitalio == 0) {
                    //$display .= "<td style='width:100px;height:auto;'>ON</td>";
                    $runningtime += $change->duration;
                    if (isset($date_wise_arr[$thisdate])) {
                        $date_wise_arr[$thisdate] += $change->duration;
                    } else {
                        $date_wise_arr[$thisdate] = $change->duration;
                    }
                } else {
                    //$display .= "<td style='width:100px;height:auto;'>OFF</td>";
                    $idletime += $change->duration;
                }
            }
            $dt = new DateTime();
            $hours = floor($change->duration / 60); //round down to nearest minute.
            $minutes = $change->duration % 60;
            if ($hours <= 9) {
                $hour = '0' . $hours;
            } else {
                $hour = $hours;
            }
            if ($minutes <= 9) {
                $minute = '0' . $minutes;
            } else {
                $minute = $minutes;
            }
            //$display .= "<td style='width:150px;height:auto;'>$hour : $minute</td>";
            if (isset($change->fuelltr)) {
                $fuelltr[] = $change->fuelltr;
                //$display .= "<td>".$change->fuelltr."</td>";
            } else {
                //$display .= "<td> </td>";
            }
            //   $display .= '</tr></tbody></table>';
        }
    }
    $display .= '</tbody></table>';
    $display .= "<page_footer>[[page_cu]]/[[page_nb]]</page_footer>";
    $totaltime = 1440 * $count + $totalminute;
    $totaltime = round($totaltime);
    if ($acinvert == 1) {
        $offtime = $totaltime - $idletime;
        if ($_SESSION['use_fuel_session'] != 0) {
            $summary_fuel = "<td style='text-align:center;'>Total Fuel Consumed = " . array_sum($fuelltr) . " ltr</td>";
        } else {
            $summary_fuel = "";
        }
        $last = "<tr><td colspan = '9'>Total Genset ON Time = " . get_hh_mm($idletime * 60) . " Hours</td></tr><tr><td colspan = '9'>Total Genset OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>	<tr>" . $summary_fuel . "</tr>";
    } else {
        $offtime = $totaltime - $runningtime;
        $last .= "<tr><td colspan = '9'>Total Genset ON Time = " . get_hh_mm($runningtime * 60) . " Hours</td></tr><tr><td colspan = '9'>Total Genset OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>	<tr>" . $summary_fuel . "</tr>";
    }
    $datewise = ac_datewise($count, $date_wise_arr, 'pdf');
    $display .= "
                        <div style='float:right;margin:15px;margin-right:60px;'>
                            $datewise
                            <table align='center'  style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                             <tbody>
                                <tr  style='background-color:#CCCCCC;font-weight:bold;'><td colspan = '9'>Statistics</td></tr>
                                $last
                            </tbody>
                        </table>
                    </div>";
    return $display;
}

function create_extrapdf_for_multipledays($datarows, $extraid, $extraval) {
    $i = 1;
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
    $totalminute = 0;
    $display = '';
    if (isset($datarows)) {
        $test = 0;
        foreach ($datarows as $change) {
            $test++;
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $display .= "</tbody></table>
                                <hr  id='style-six' /><br/>
                                <table  id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;' ><tbody>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $display .= "
                                    <tr style='background-color:#CCCCCC;font-weight:bold;'>
                                        <td style='width:50px;height:auto;'>Start Time</td>
                                        <td style='width:300px;height:auto;'>Start Location</td>
                                        <td style='width:50px;height:auto;'>End Time</td>
                                        <td style='width:300px;height:auto;'>End Location</td>
                                        <td style='width:100px;height:auto;'>$extraval Status</td>
                                        <td style='width:150px;height:auto;'>Duration[HH:MM]</td>
                                    </tr>
                                    <tr style='background-color:#D8D5D6;font-weight:bold;'><td colspan='6' >Date $lastdate</td></tr>";
                $i++;
            }
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            if (!isset($change->startcgeolat) || !isset($change->startcgeolong)) {
                $change->startlocation = 'Unable to fetch Location';
                $change->endlocation = 'Unable to fetch Location';
            } else {
                $uselocation = retval_issetor($change->uselocation);
                $change->startlocation = location($change->startcgeolat, $change->startcgeolong, $uselocation);
                $change->endlocation = location($change->endcgeolat, $change->endcgeolong, $uselocation);
            }
            $start_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
            $end_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->endtime));
            $display .= "<tr><td style='width:50px;height:auto;'>$start_time_disp</td><td style='width:300px;height:auto;'>$change->startlocation</td>
                                <td style='width:50px;height:auto;' >$end_time_disp</td><td style='width:300px;height:auto;'>$change->endlocation</td>";
            $category_array = array();
            $digital = array();
            $category = (int) $change->digitalio;
            $binarycategory = sprintf("%08s", DecBin($category));
            for ($shifter = 1; $shifter <= 127; $shifter = $shifter << 1) {
                $binaryshifter = sprintf("%08s", DecBin($shifter));
                if ($category & $shifter) {
                    $category_array[] = $shifter;
                }
            }
            if (in_array($extraid, $category_array)) {
                $display .= '<td>On</td>';
                $runningtime += $change->duration;
            } else {
                $display .= '<td>OFF</td>';
                $idletime += $change->duration;
            }
            $dt = new DateTime();
            $currDate = date_format($dt, 'Y-m-d');
            $startT = date('H:i', strtotime($change->duration . ' minutes'));
            $hours = floor($change->duration / 60); //round down to nearest minute.
            $minutes = $change->duration % 60;
            if ($hours <= 9) {
                $hour = '0' . $hours;
            } else {
                $hour = $hours;
            }
            if ($minutes <= 9) {
                $minute = '0' . $minutes;
            } else {
                $minute = $minutes;
            }
            $display .= "<td style='width:150px;height:auto;'>$hour : $minute</td>";
            $display .= '</tr>';
        }
    }
    $display .= '</tbody></table>';
    $display .= "<page_footer>[[page_cu]]/[[page_nb]]</page_footer>";
    $totaltime = 1440 * $count + $totalminute;
    $totaltime = round($totaltime);
    if (isset($acinvert) && $acinvert == 1) {
        $offtime = $totaltime - $idletime;
        $last = "<tr><td colspan = '9'>Total $extraval ON Time = " . m2h($idletime) . " HH:MM </td></tr><tr><td colspan = '9'>Total $extraval OFF Time = " . m2h($offtime) . "HH:MM </td></tr>";
    } else {
        $offtime = $totaltime - $runningtime;
        $last = "<tr><td colspan = '9'>Total $extraval ON Time = " . m2h($runningtime) . " HH:MM </td></tr><tr><td colspan = '9'>Total $extraval OFF Time = " . m2h($offtime) . " HH:MM </td></tr>";
    }
    $display .= "
                        <div style='float:right;margin:15px;margin-right:60px;'>
                            <table align='center'  style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                             <tbody>
                                <tr  style='background-color:#CCCCCC;font-weight:bold;'><td colspan = '9'><h4>Statistics</h4></td></tr>
                                $last
                            </tbody>
                        </table>
                    </div>";
    return $display;
}

function create_csv_from_report($datarows, $acinvert) {
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
    $display = '';
    if (isset($datarows)) {
        foreach ($datarows as $change) {
            //            $comparedate = date('d-m-Y',strtotime($change->endtime));
            //            if(strtotime($lastdate) != strtotime($comparedate))
            //            {
            //                $display .= "<tr style='background-color:#CCCCCC;'><th align='center' colspan = '100%'>".date('d-m-Y',strtotime($change->endtime))."</th></tr>";
            //                $lastdate = date('d-m-Y',strtotime($change->endtime));
            //            }
            $currentdate = date("Y-m-d H:i:s");
            $currentdate = substr($currentdate, '0', 11);
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            $display .= "<tr><td style='width:50px;height:auto; text-align: center;'></td><td style='width:50px;height:auto; text-align: center;'>$change->starttime</td><td style='width:50px;height:auto; text-align: center;'>$change->endtime</td>";
            if ($change->ignition == 1) {
                $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
            } else {
                $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
            }
            if ($acinvert == 1) {
                if ($change->digitalio == 0) {
                    $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                    $runningtime += $change->duration;
                } else {
                    $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                    $idletime += $change->duration;
                }
            } else {
                if ($change->digitalio == 0) {
                    $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                    $runningtime += $change->duration;
                } else {
                    $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                    $idletime += $change->duration;
                }
            }
            $dt = new DateTime();
            $currDate = date_format($dt, 'Y-m-d');
            //$startT = gmdate("H:i", $change->duration);
            $startT = date('H:i', strtotime($change->duration . ' minutes'));
            $hours = floor($change->duration / 60); //round down to nearest minute.
            $minutes = $change->duration % 60;
            if ($hours <= 9) {
                $hour = '0' . $hours;
            } else {
                $hour = $hours;
            }
            if ($minutes <= 9) {
                $minute = '0' . $minutes;
            } else {
                $minute = $minutes;
            }
            $display .= "<td style='width:100px;height:auto; text-align: center;'>$hour : $minute</td>";
            $display .= '<td style="width:50px;height:auto; text-align: center;"></td></tr>';
        }
    }
    $display .= '</tbody>';
    $display .= "</table><hr style='margin-top:20px;'>";
    $display .= "<table align = 'center' style='text-align:center;'><thead><tr style='background-color:#CCCCCC;'><th style='width:50px;height:auto; text-align: center;'></th><th colspan='5'>Statistics</th><th style='width:50px;height:auto; text-align: center;'></th></tr></thead>";
    if ($acinvert == 1) {
        $display .= "<tbody><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set ON Time = $idletime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set OFF Time = $runningtime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr></tbody></table>";
    } else {
        $display .= "<tbody><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set ON Time = $runningtime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set OFF Time = $idletime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr></tbody></table>";
    }
    return $display;
}

function create_gensetcsv_from_report($datarows, $acinvert) {
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
    $display = '';
    if (isset($datarows)) {
        foreach ($datarows as $change) {
            //            $comparedate = date('d-m-Y',strtotime($change->endtime));
            //            if(strtotime($lastdate) != strtotime($comparedate))
            //            {
            //                $display .= "<tr style='background-color:#CCCCCC;'><th align='center' colspan = '100%'>".date('d-m-Y',strtotime($change->endtime))."</th></tr>";
            //                $lastdate = date('d-m-Y',strtotime($change->endtime));
            //            }
            $currentdate = date("Y-m-d H:i:s");
            $currentdate = substr($currentdate, '0', 11);
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            $display .= "<tr><td style='width:50px;height:auto; text-align: center;'></td><td style='width:50px;height:auto; text-align: center;'>$change->starttime</td><td style='width:50px;height:auto; text-align: center;'>$change->endtime</td>";
            //            if($change->ignition==1)
            //            {
            //                $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
            //            }
            //            else
            //            {
            //                $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
            //            }
            if ($acinvert == 1) {
                if ($change->digitalio == 0) {
                    $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                    $runningtime += $change->duration;
                } else {
                    $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                    $idletime += $change->duration;
                }
            } else {
                if ($change->digitalio == 0) {
                    $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                    $runningtime += $change->duration;
                } else {
                    $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                    $idletime += $change->duration;
                }
            }
            $dt = new DateTime();
            $currDate = date_format($dt, 'Y-m-d');
            //$startT = gmdate("H:i", $change->duration);
            $startT = date('H:i', strtotime($change->duration . ' minutes'));
            $hours = floor($change->duration / 60); //round down to nearest minute.
            $minutes = $change->duration % 60;
            if ($hours <= 9) {
                $hour = '0' . $hours;
            } else {
                $hour = $hours;
            }
            if ($minutes <= 9) {
                $minute = '0' . $minutes;
            } else {
                $minute = $minutes;
            }
            $display .= "<td style='width:100px;height:auto; text-align: center;'>$hour : $minute</td>";
            $display .= '<td style="width:50px;height:auto; text-align: center;"></td></tr>';
        }
    }
    $display .= '</tbody>';
    $display .= "</table><hr style='margin-top:20px;'>";
    $display .= "<table align = 'center' style='text-align:center;'><thead><tr style='background-color:#CCCCCC;'><th style='width:50px;height:auto; text-align: center;'></th><th colspan='5'>Statistics</th><th style='width:50px;height:auto; text-align: center;'></th></tr></thead>";
    if ($acinvert == 1) {
        $offtime = 1440 - $idletime;
        $display .= "<tbody><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set ON Time = $idletime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set OFF Time = $offtime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr></tbody></table>";
    } else {
        $offtime = 1440 - $runningtime;
        $display .= "<tbody><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set ON Time = $runningtime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set OFF Time = $offtime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr></tbody></table>";
    }
    return $display;
}

function create_excel_from_report($datarows, $acinvert) {
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
    $display = '';
    if (isset($datarows)) {
        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                $display .= "<tr style='background-color:#CCCCCC;'><th align='center' colspan = '7'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $display .= "
                                    <tr style='background-color:#CCCCCC;'>
                                        <td style='width:50px;height:auto; text-align: center;'></td>
                                        <td style='width:50px;height:auto; text-align: center;'>Start Time</td>
                                        <td style='width:50px;height:auto; text-align: center;'>End Time</td>
                                        <td style='width:50px;height:auto; text-align: center;'>Ignition Status</td>
                                        <td style='width:50px;height:auto; text-align: center;'>Gen Set Status</td>
                                        <td style='width:150px;height:auto; text-align: center;'>Duration[Hours:Minutes]</td>
                                        <td style='width:50px;height:auto; text-align: center;'></td>
                                    </tr>";
            }
            $currentdate = date("Y-m-d H:i:s");
            $currentdate = substr($currentdate, '0', 11);
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            $display .= "<tr><td style='width:50px;height:auto; text-align: center;'></td><td style='width:50px;height:auto; text-align: center;'>$change->starttime</td><td style='width:50px;height:auto; text-align: center;'>$change->endtime</td>";
            if ($change->ignition == 1) {
                $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
            } else {
                $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
            }
            if ($acinvert == 1) {
                if ($change->digitalio == 0) {
                    $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                    $runningtime += $change->duration;
                } else {
                    $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                    $idletime += $change->duration;
                }
            } else {
                if ($change->digitalio == 0) {
                    $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                    $runningtime += $change->duration;
                } else {
                    $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                    $idletime += $change->duration;
                }
            }
            $dt = new DateTime();
            $currDate = date_format($dt, 'Y-m-d');
            //$startT = gmdate("H:i", $change->duration);
            $startT = date('H:i', strtotime($change->duration . ' minutes'));
            $hours = floor($change->duration / 60); //round down to nearest minute.
            $minutes = $change->duration % 60;
            if ($hours <= 9) {
                $hour = '0' . $hours;
            } else {
                $hour = $hours;
            }
            if ($minutes <= 9) {
                $minute = '0' . $minutes;
            } else {
                $minute = $minutes;
            }
            $display .= "<td style='width:100px;height:auto; text-align: center;'>$hour : $minute</td>";
            $display .= '<td style="width:50px;height:auto; text-align: center;"></td></tr>';
        }
    }
    $display .= '</tbody>';
    $display .= "</table><hr style='margin-top:20px;'>";
    $display .= "<table align = 'center' style='text-align:center;'><thead><tr style='background-color:#CCCCCC;'><th style='width:50px;height:auto; text-align: center;'></th><th colspan='5'>Statistics</th><th style='width:50px;height:auto; text-align: center;'></th></tr></thead>";
    if ($acinvert == 1) {
        $display .= "<tbody><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set ON Time = $idletime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set OFF Time = $runningtime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr></tbody></table>";
    } else {
        $display .= "<tbody><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set ON Time = $runningtime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set OFF Time = $idletime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr></tbody></table>";
    }
    return $display;
}

function create_gensetexcel_from_report($datarows, $acinvert, $customerno, $uselocation) {
    $i = 1;
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
    $totalminute = 0;
    $display = '';
    if (isset($datarows)) {
        $start = 0;
        $date_wise_arr = array();
        foreach ($datarows as $change) {
            $start++;
            $thisdate = date('d-m-Y', strtotime($change->endtime));
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                if ($start != 1) {
                    $display .= "<tr><td colspan = '6'></td></tr>";
                }
                $display .= "
                                    <tr style='background-color:#CCCCCC;font-weight:bold;'>
                                        <td style='width:50px;height:auto; text-align: center;'>Start Time</td>
                                        <td style='width:150px;height:auto; text-align: center;'>Start Location</td>
                                        <td style='width:50px;height:auto; text-align: center;'>End Time</td>
                                        <td style='width:150px;height:auto; text-align: center;'>End Location</td>
                                        <td style='width:50px;height:auto; text-align: center;'>Gen Set Status</td>
                                        <td style='width:150px;height:auto; text-align: center;'>Duration[Hours:Minutes]</td>
                                        <td style='width:150px;height:auto; text-align: center;'>Fuel Consumed(In litre)</td>
                                    </tr>";
                $display .= "<tr style='background-color:#D8D5D6;'><th align='center' colspan = '7'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                $i++;
            }
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            if (!isset($change->startcgeolat) || !isset($change->startcgeolong)) {
                $change->startlocation = 'Unable to fetch Location';
                $change->endlocation = 'Unable to fetch Location';
            } else {
                $change->startlocation = location_cmn($change->startcgeolat, $change->startcgeolong, $uselocation, $customerno);
                $change->endlocation = location_cmn($change->endcgeolat, $change->endcgeolong, $uselocation, $customerno);
            }
            $start_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
            $end_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->endtime));
            $display .= "<tr><td style='width:50px;height:auto; text-align: center;'>$start_time_disp</td><td>$change->startlocation</td><td style='width:50px;height:auto; text-align: center;'>$end_time_disp</td><td>$change->endlocation</td>";
            if ($acinvert == 1) {
                if ($change->digitalio == 0) {
                    $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                    $runningtime += $change->duration;
                } else {
                    $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                    $idletime += $change->duration;
                    if (isset($date_wise_arr[$thisdate])) {
                        $date_wise_arr[$thisdate] += $change->duration;
                    } else {
                        $date_wise_arr[$thisdate] = $change->duration;
                    }
                }
            } else {
                if ($change->digitalio == 0) {
                    $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                    $runningtime += $change->duration;
                    if (isset($date_wise_arr[$thisdate])) {
                        $date_wise_arr[$thisdate] += $change->duration;
                    } else {
                        $date_wise_arr[$thisdate] = $change->duration;
                    }
                } else {
                    $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                    $idletime += $change->duration;
                }
            }
            $hours = floor($change->duration / 60); //round down to nearest minute.
            $minutes = $change->duration % 60;
            if ($hours <= 9) {
                $hour = '0' . $hours;
            } else {
                $hour = $hours;
            }
            if ($minutes <= 9) {
                $minute = '0' . $minutes;
            } else {
                $minute = $minutes;
            }
            $display .= "<td style='width:100px;height:auto; text-align: center;'>$hour : $minute</td>";
            if (isset($change->fuelltr)) {
                $fuelltr[] = $change->fuelltr;
                $display .= "<td>" . $change->fuelltr . "</td>";
            } else {
                $display .= "<td> </td>";
            }
            $display .= '</tr>';
        }
    }
    $display .= '</tbody>';
    $display .= "</table><hr style='margin-top:20px;'>";
    $datewise = ac_datewise($count, $date_wise_arr, 'pdf');
    $display .= "$datewise<table align = 'center' style='text-align:center;'>
                        <thead><tr style='background-color:#CCCCCC;'><th colspan='6'>Statistics</th></tr></thead>";
    $totaltime = 1440 * $count + $totalminute;
    $totaltime = round($totaltime);
    if ($acinvert == 1) {
        $offtime = $totaltime - $idletime;
        $display .= "<tbody>
                            <tr><td colspan = '6' style=' text-align: center;'>Total Gen Set ON Time = " . get_hh_mm($idletime * 60) . " Hours</td></tr>
                            <tr><td colspan = '6' style=' text-align: center;'>Total Gen Set OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>
                            <tr><td colspan = '6' style=' text-align: center;'>Total Fuel Consumed = " . array_sum($fuelltr) . "ltr</td></tr></tbody></table>";
    } else {
        $offtime = $totaltime - $runningtime;
        $display .= "<tbody>
                            <tr><td colspan = '6' style='text-align: center;'>Total Gen Set ON Time = " . get_hh_mm($runningtime * 60) . " Hours</td></tr>
                            <tr><td colspan = '6' style='text-align: center;'>Total Gen Set OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>
                            <tr><td colspan = '6' style=' text-align: center;'>Total Fuel Consumed = " . array_sum($fuelltr) . "ltr</td></tr></tbody></table>";
    }
    return $display;
}

//genset excel summary report - start
function create_gensetexcel_summary_from_report($datarows, $acinvert, $customerno, $uselocation) {
    $i = 1;
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
    $totalminute = 0;
    $display = '';
    if (isset($datarows)) {
        $start = 0;
        $date_wise_arr = array();
        foreach ($datarows as $change) {
            $start++;
            $thisdate = date('d-m-Y', strtotime($change->endtime));
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                if ($start != 1) {
                    $display .= "<tr><td colspan = '6'></td></tr>";
                }
                //                $display .= "
                //                    <tr style='background-color:#CCCCCC;font-weight:bold;'>
                //                        <td style='width:50px;height:auto; text-align: center;'>Start Time</td>
                //                        <td style='width:150px;height:auto; text-align: center;'>Start Location</td>
                //                        <td style='width:50px;height:auto; text-align: center;'>End Time</td>
                //                        <td style='width:150px;height:auto; text-align: center;'>End Location</td>
                //                        <td style='width:50px;height:auto; text-align: center;'>Gen Set Status</td>
                //                        <td style='width:150px;height:auto; text-align: center;'>Duration[Hours:Minutes]</td>
                //                        <td style='width:150px;height:auto; text-align: center;'>Fuel Consumed(In litre)</td>
                //                    </tr>";
                //$display .= "<tr style='background-color:#D8D5D6;'><th align='center' colspan = '7'>".date('d-m-Y',strtotime($change->endtime))."</th></tr>";
                $i++;
            }
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            if (!isset($change->startcgeolat) || !isset($change->startcgeolong)) {
                $change->startlocation = 'Unable to fetch Location';
                $change->endlocation = 'Unable to fetch Location';
            } else {
                $change->startlocation = location_cmn($change->startcgeolat, $change->startcgeolong, $uselocation, $customerno);
                $change->endlocation = location_cmn($change->endcgeolat, $change->endcgeolong, $uselocation, $customerno);
            }
            $start_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
            $end_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->endtime));
            //$display .= "<tr><td style='width:50px;height:auto; text-align: center;'>$start_time_disp</td><td>$change->startlocation</td><td style='width:50px;height:auto; text-align: center;'>$end_time_disp</td><td>$change->endlocation</td>";
            if ($acinvert == 1) {
                if ($change->digitalio == 0) {
                    //$display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                    $runningtime += $change->duration;
                } else {
                    //$display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                    $idletime += $change->duration;
                    if (isset($date_wise_arr[$thisdate])) {
                        $date_wise_arr[$thisdate] += $change->duration;
                    } else {
                        $date_wise_arr[$thisdate] = $change->duration;
                    }
                }
            } else {
                if ($change->digitalio == 0) {
                    //$display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                    $runningtime += $change->duration;
                    if (isset($date_wise_arr[$thisdate])) {
                        $date_wise_arr[$thisdate] += $change->duration;
                    } else {
                        $date_wise_arr[$thisdate] = $change->duration;
                    }
                } else {
                    //$display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                    $idletime += $change->duration;
                }
            }
            $hours = floor($change->duration / 60); //round down to nearest minute.
            $minutes = $change->duration % 60;
            if ($hours <= 9) {
                $hour = '0' . $hours;
            } else {
                $hour = $hours;
            }
            if ($minutes <= 9) {
                $minute = '0' . $minutes;
            } else {
                $minute = $minutes;
            }
            //$display .= "<td style='width:100px;height:auto; text-align: center;'>$hour : $minute</td>";
            if (isset($change->fuelltr)) {
                $fuelltr[] = $change->fuelltr;
                //  $display .= "<td>".$change->fuelltr."</td>";
            } else {
                //$display .= "<td> </td>";
            }
            //$display .= '</tr>';
        }
    }
    $display .= '</tbody>';
    $display .= "</table><hr style='margin-top:20px;'>";
    $datewise = ac_datewise($count, $date_wise_arr, 'pdf');
    $display .= "$datewise<table align = 'center' style='text-align:center;'>
                        <thead><tr style='background-color:#CCCCCC;'><th colspan='6'>Statistics</th></tr></thead>";
    $totaltime = 1440 * $count + $totalminute;
    $totaltime = round($totaltime);
    if ($acinvert == 1) {
        $offtime = $totaltime - $idletime;
        if ($_SESSION['use_fuel_sensor'] != 0) {
            $fuel_summary = "<td colspan = '6' style=' text-align: center;'>Total Fuel Consumed = " . array_sum($fuelltr) . "ltr</td>";
        } else {
            $fuel_summary = "";
        }
        $display .= "<tbody>
                            <tr><td colspan = '6' style=' text-align: center;'>Total Gen Set ON Time = " . get_hh_mm($idletime * 60) . " Hours</td></tr>
                            <tr><td colspan = '6' style=' text-align: center;'>Total Gen Set OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>
                            <tr>" . $fuel_summary . "</tr></tbody></table>";
    } else {
        $offtime = $totaltime - $runningtime;
        $display .= "<tbody>
                            <tr><td colspan = '6' style='text-align: center;'>Total Gen Set ON Time = " . get_hh_mm($runningtime * 60) . " Hours</td></tr>
                            <tr><td colspan = '6' style='text-align: center;'>Total Gen Set OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>
                            <tr>" . $fuel_summary . "</tr></tbody></table>";
    }
    return $display;
}

//gensert excel sumamry report - end
//genset excel report -details start
function create_gensetexcel_from_reportdetails($datarows, $acinvert, $customerno, $uselocation, $gensetSensor = null) {
    $i = 1;
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
    $totalminute = 0;
    $display = '';
    $gensetSensor = isset($gensetSensor) ? $gensetSensor : 1;
    if (isset($datarows)) {
        $fromTime = reset($datarows);
        $toTime = end($datarows);
        $totalminute = round(abs(strtotime($fromTime->starttime) - strtotime($toTime->endtime)) / 60, 2);

        $start = 0;
        $date_wise_arr = array();
        foreach ($datarows as $change) {
            /* Code to Continuing the loop if duration = "00:00" and Ignition status = "Running" starts here */
            $continueLoopStatus = getContinueLoopStatus($change->duration, $change->distancetravelled, $change->ignition);
            if ($continueLoopStatus) {
                continue;
            }
            /*  Code to Continuing the loop if duration = "00:00" and Ignition status = "Running" ends here  */
            if ($gensetSensor == 2) {
                $change->digitalio = $change->extradigitalio;
            }
            $start++;
            $thisdate = date('d-m-Y', strtotime($change->endtime));
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    //$totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                if ($start != 1) {
                    $display .= "<tr><td colspan = '6'></td></tr>";
                }
                $fuelsensor = "";
                if ($_SESSION['use_fuel_sensor'] != 0) {
                    $fuelsensor .= "<td style='width:150px;height:auto; text-align: center;'>Fuel Consumed(In litre)</td></tr><tr style='background-color:#D8D5D6;'><th align='center' colspan = '7'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                } else {
                    $fuelsensor .= "</tr><tr style='background-color:#D8D5D6;'><th align='center' colspan = '6'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                }
                $display .= "
                                    <tr style='background-color:#CCCCCC;font-weight:bold;'>
                                        <td style='width:50px;height:auto; text-align: center;'>Start Time</td>
                                        <td style='width:150px;height:auto; text-align: center;'>Start Location</td>
                                        <td style='width:50px;height:auto; text-align: center;'>End Time</td>
                                        <td style='width:150px;height:auto; text-align: center;'>End Location</td>
                                        <td style='width:50px;height:auto; text-align: center;'>Gen Set Status</td>
                                        <td style='width:50px;height:auto; text-align: center;'>Ignition Status</td>
                                        <td style='width:150px;height:auto; text-align: center;'>Duration[Hours:Minutes]</td>
                                        $fuelsensor ";
                $i++;
            }
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            if (!isset($change->startcgeolat) || !isset($change->startcgeolong)) {
                $change->startlocation = 'Unable to fetch Location';
                $change->endlocation = 'Unable to fetch Location';
            } else {
                $change->startlocation = location_cmn($change->startcgeolat, $change->startcgeolong, $uselocation, $customerno);
                $change->endlocation = location_cmn($change->endcgeolat, $change->endcgeolong, $uselocation, $customerno);
            }
            $start_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
            $end_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->endtime));
            $display .= "<tr><td style='width:50px;height:auto; text-align: center;'>$start_time_disp</td><td>$change->startlocation</td><td style='width:50px;height:auto; text-align: center;'>$end_time_disp</td><td>$change->endlocation</td>";
            if ($gensetSensor == 1) {
                if ($acinvert == 1) {
                    if ($change->digitalio == 0) {
                        $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                        $runningtime += $change->duration;
                    } else {
                        //$display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                        /* Code for if AC is on but ignition status is OFF for 00:00 time then AC status should render OFF starts here */
                        $fetchedStatus = renderACStatusOFF($change->duration, $change->distancetravelled, $change->ignition);
                        $display .= '<td style="width:50px;height:auto; text-align: center;">' . $fetchedStatus . '</td>';
                        /* Code for if AC is on but ignition status is OFF for 00:00 time then AC status should render OFF ends here */
                        $idletime += $change->duration;
                        if (isset($date_wise_arr[$thisdate])) {
                            $date_wise_arr[$thisdate] += $change->duration;
                        } else {
                            $date_wise_arr[$thisdate] = $change->duration;
                        }
                    }
                } else {
                    if ($change->digitalio == 0) {
                        //$display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                        /* Code for if AC is on but ignition status is OFF for 00:00 time then AC status should render OFF starts here */
                        $fetchedStatus = renderACStatusOFF($change->duration, $change->distancetravelled, $change->ignition);
                        $display .= '<td style="width:50px;height:auto; text-align: center;">' . $fetchedStatus . '</td>';
                        /* Code for if AC is on but ignition status is OFF for 00:00 time then AC status should render OFF ends here */
                        $runningtime += $change->duration;
                        if (isset($date_wise_arr[$thisdate])) {
                            $date_wise_arr[$thisdate] += $change->duration;
                        } else {
                            $date_wise_arr[$thisdate] = $change->duration;
                        }
                    } else {
                        $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                        $idletime += $change->duration;
                    }
                }
            } else {
                if ($change->digitalio == 1) {
                    //$display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                    /* Code for if AC is on but ignition status is OFF for 00:00 time then AC status should render OFF starts here */
                    @$fetchedStatus = renderACStatusOFF($change->duration, $change->distancetravelled, $change->ignition);
                    $display .= '<td style="width:50px;height:auto; text-align: center;">' . $fetchedStatus . '</td>';
                    /* Code for if AC is on but ignition status is OFF for 00:00 time then AC status should render OFF ends here */
                    $idletime += $change->duration;
                    if (isset($date_wise_arr[$thisdate])) {
                        $date_wise_arr[$thisdate] += $change->duration;
                    } else {
                        $date_wise_arr[$thisdate] = $change->duration;
                    }
                } else {
                    $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                    $runningtime += $change->duration;
                }
            }
            /* if($change->ignition == 0)
            {
            $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
            }
            else
            {
            $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
             */

            if (@$change->distancetravelled > 0) {
                $display .= "<td style='cursor:pointer;'> <a style='text-decoration:underline;'>Running</a></td>";
                //$runningtime += $change->duration;
            } else {
                if ($change->ignition == 1) {
                    $display .= "<td>Idle - Ignition On</td>";
                    //$idle_ign_on += $change->duration;
                } else {
                    $display .= "<td>Idle</td>";
                    //$idletime += $change->duration;
                }
            }
            $hours = floor($change->duration / 60); //round down to nearest minute.
            $minutes = $change->duration % 60;
            if ($hours <= 9) {
                $hour = '0' . $hours;
            } else {
                $hour = $hours;
            }
            if ($minutes <= 9) {
                $minute = '0' . $minutes;
            } else {
                $minute = $minutes;
            }
            $display .= "<td style='width:100px;height:auto; text-align: center;'>$hour : $minute</td>";
            if (isset($change->fuelltr) && $_SESSION['use_fuel_sensor'] != 0) {
                $fuelltr[] = $change->fuelltr;
                $display .= "<td>" . $change->fuelltr . "</td>";
            } else {
                $display .= "";
            }
            $display .= '</tr>';
        }
    }
    $display .= '</tbody>';
    $display .= "</table><hr style='margin-top:20px;'>";
    //$datewise = ac_datewise($count,$date_wise_arr,'pdf');
    $display .= "<table align = 'center' style='text-align:center;'><thead><tr style='background-color:#CCCCCC;'><th colspan='6'>Statistics</th></tr></thead>";
    //$totaltime = 1440 * $count + $totalminute;
    //$totaltime = round($totaltime);
    $totaltime = round(1440 * $count + $totalminute);
    if ($acinvert == 1) {
        $offtime = $totalminute - $idletime;
        if ($_SESSION['use_fuel_sensor'] != 0) {
            $fuelsum = "<td colspan = '6' style=' text-align: center;'>Total Fuel Consumed = " . array_sum($fuelltr) . "ltr</td>";
        } else {
            $fuelsum = "";
        }
        $display .= "<tbody>
                            <tr><td colspan = '6' style=' text-align: center;'>Total Gen Set ON Time = " . get_hh_mm($idletime * 60) . " Hours</td></tr>
                            <tr><td colspan = '6' style=' text-align: center;'>Total Gen Set OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>
                            <tr>" . $fuelsum . "</tr></tbody></table>";
    } else {
        $offtime = $totalminute - $runningtime;
        $display .= "<tbody>
                            <tr><td colspan = '6' style='text-align: center;'>Total Gen Set ON Time = " . get_hh_mm($runningtime * 60) . " Hours</td></tr>
                            <tr><td colspan = '6' style='text-align: center;'>Total Gen Set OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>
                            <tr>" . $fuelsum . "</tr></tbody></table>";
    }
    return $display;
}

//gensert excel report -details end
function create_extraexcel_from_report($datarows, $extraid, $extraval, $customerno) {
    $i = 1;
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
    $totalminute = 0;
    $display = '';
    $use_geolocation = get_usegeolocation($customerno);
    if (isset($datarows)) {
        $start = 0;
        foreach ($datarows as $change) {
            $start++;
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                if ($start != 1) {
                    $display .= "<tr><td colspan = '6'></td></tr>";
                }
                $display .= "
                                    <tr style='background-color:#CCCCCC;font-weight:bold;'>
                                        <td style='width:50px;height:auto; text-align: center;'>Start Time</td>
                                        <td style='width:150px;height:auto; text-align: center;'>Start Location</td>
                                        <td style='width:50px;height:auto; text-align: center;'>End Time</td>
                                        <td style='width:150px;height:auto; text-align: center;'>End Location</td>
                                        <td style='width:50px;height:auto; text-align: center;'>$extraval Status</td>
                                        <td style='width:150px;height:auto; text-align: center;'>Duration[Hours:Minutes]</td>
                                    </tr>";
                $display .= "<tr style='background-color:#D8D5D6;'><th align='center' colspan = '6'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                $i++;
            }
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            if (!isset($change->startcgeolat) || !isset($change->startcgeolong)) {
                $change->startlocation = 'Unable to fetch Location';
                $change->endlocation = 'Unable to fetch Location';
            } else {
                $change->startlocation = location($change->startcgeolat, $change->startcgeolong, $use_geolocation);
                $change->endlocation = location($change->endcgeolat, $change->endcgeolong, $use_geolocation);
            }
            $start_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
            $end_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->endtime));
            $display .= "<tr><td style='width:50px;height:auto; text-align: center;'>$start_time_disp</td><td>$change->startlocation</td><td style='width:50px;height:auto; text-align: center;'>$end_time_disp</td><td>$change->endlocation</td>";
            $category_array = array();
            $digital = array();
            $category = (int) $change->digitalio;
            $binarycategory = sprintf("%08s", DecBin($category));
            for ($shifter = 1; $shifter <= 127; $shifter = $shifter << 1) {
                $binaryshifter = sprintf("%08s", DecBin($shifter));
                if ($category & $shifter) {
                    $category_array[] = $shifter;
                }
            }
            if (in_array($extraid, $category_array)) {
                $display .= '<td>On</td>';
                $runningtime += $change->duration;
            } else {
                $display .= '<td>OFF</td>';
                $idletime += $change->duration;
            }
            $startT = date('H:i', strtotime($change->duration . ' minutes'));
            $hours = floor($change->duration / 60); //round down to nearest minute.
            $minutes = $change->duration % 60;
            if ($hours <= 9) {
                $hour = '0' . $hours;
            } else {
                $hour = $hours;
            }
            if ($minutes <= 9) {
                $minute = '0' . $minutes;
            } else {
                $minute = $minutes;
            }
            $display .= "<td style='width:100px;height:auto; text-align: center;'>$hour : $minute</td>";
            $display .= '</tr>';
        }
    }
    $display .= '</tbody>';
    $display .= "</table><hr style='margin-top:20px;'>";
    $display .= "<table align = 'center' style='text-align:center;'>
                        <thead><tr style='background-color:#CCCCCC;'><th colspan='6'>Statistics</th></tr></thead>";
    $totaltime = 1440 * $count + $totalminute;
    $totaltime = round($totaltime);
    if (isset($acinvert) && $acinvert == 1) {
        $offtime = $totaltime - $idletime;
        $display .= "<tbody>
                            <tr><td colspan = '6' style=' text-align: center;'>Total $extraval ON Time = " . m2h($idletime) . " HH:MM</td></tr>
                            <tr><td colspan = '6' style=' text-align: center;'>Total $extraval OFF Time = " . m2h($offtime) . " HH:MM</td></tr></tbody></table>";
    } else {
        $offtime = $totaltime - $runningtime;
        $display .= "<tbody>
                            <tr><td colspan = '6' style='text-align: center;'>Total $extraval ON Time = " . m2h($runningtime) . " HH:MM</td></tr>
                            <tr><td colspan = '6' style='text-align: center;'>Total $extraval OFF Time = " . m2h($offtime) . " HH:MM</td></tr></tbody></table>";
    }
    return $display;
}

//                    function getvehiclesbygroup($groupid)) {
function getvehiclesbygroup($groupid, $options) {
    //                        $VehicleManager = new VehicleManager($_SESSION['customerno']);
    $customerno = isset($_SESSION['customerno']) ? $_SESSION['customerno'] : $options['customerno'];
    $customerno = is_array($customerno) ? implode(',', $customerno) : $customerno;
    $VehicleManager = new VehicleManager($customerno);

    $vehicles = $VehicleManager->get_groups_vehicles($groupid);
    return $vehicles;
}

function getvehiclesbygroup_ecode() {
    $VehicleManager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $VehicleManager->get_groups_vehicles_ecode();
    return $vehicles;
}

function get_all_vehiclesbyheirarchy() {
    $VehicleManager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $VehicleManager->get_all_vehiclesbyheirarchy();
    return $vehicles;
}

function GetDailyReport_Data($location, $days, $vehicleid = null) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $REPORT = array();
    if (isset($days)) {
        foreach ($days as $day) {
            if ($_SESSION['groupid'] != 0) {
                $vehiclesbygroup = getvehiclesbygroup($_SESSION['groupid']);
                if (isset($vehiclesbygroup)) {
                    foreach ($vehiclesbygroup as $vehicle) {
                        $sqlday = date("dmy", strtotime($day));
                        $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid";
                        $result = $db->query($query);
                        if (isset($result) && $result != "") {
                            foreach ($result as $row) {
                                $Datacap = new stdClass();
                                $Datacap->date = strtotime($day);
                                $Datacap->uid = $row['uid'];
                                $Datacap->tamper = $row['tamper'];
                                $Datacap->overspeed = $row['overspeed'];
                                $Datacap->totaldistance = $row['totaldistance'];
                                $Datacap->fenceconflict = $row['fenceconflict'];
                                $Datacap->idletime = $row['idletime'];
                                $Datacap->genset = $row['genset'];
                                $Datacap->runningtime = $row['runningtime'];
                                $Datacap->vehicleid = $row['vehicleid'];
                                $Datacap->dev_lat = $row['dev_lat'];
                                $Datacap->dev_long = $row['dev_long'];
                                $Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                                $Datacap->average = getAverage($row['vehicleid']);
                                $REPORT[] = $Datacap;
                            }
                        }
                    }
                }
            } elseif ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
                $vehiclesbygroup = get_all_vehiclesbyheirarchy();
                if (isset($vehiclesbygroup)) {
                    foreach ($vehiclesbygroup as $vehicle) {
                        $sqlday = date("dmy", strtotime($day));
                        $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid";
                        $result = $db->query($query);
                        if (isset($result) && $result != "") {
                            foreach ($result as $row) {
                                $Datacap = new stdClass();
                                $Datacap->date = strtotime($day);
                                $Datacap->uid = $row['uid'];
                                $Datacap->tamper = $row['tamper'];
                                $Datacap->overspeed = $row['overspeed'];
                                $Datacap->totaldistance = $row['totaldistance'];
                                $Datacap->fenceconflict = $row['fenceconflict'];
                                $Datacap->idletime = $row['idletime'];
                                $Datacap->genset = $row['genset'];
                                $Datacap->runningtime = $row['runningtime'];
                                $Datacap->vehicleid = $row['vehicleid'];
                                $Datacap->dev_lat = $row['dev_lat'];
                                $Datacap->dev_long = $row['dev_long'];
                                $Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                                $Datacap->average = getAverage($row['vehicleid']);
                                $REPORT[] = $Datacap;
                            }
                        }
                    }
                }
            } else {
                $sqlday = date("dmy", strtotime($day));
                $query = "SELECT * from A$sqlday";
                if ($vehicleid != null) {
                    $query .= " where vehicleid=$vehicleid";
                }
                $result = $db->query($query);
                if (isset($result) && $result != "") {
                    foreach ($result as $row) {
                        $Datacap = new stdClass();
                        $Datacap->date = strtotime($day);
                        $Datacap->uid = $row['uid'];
                        if (isset($row['tamper'])) {
                            $Datacap->tamper = $row['tamper'];
                        } else {
                            $Datacap->tamper = 0;
                        }
                        // $Datacap->tamper = $row['tamper'];

                        $Datacap->overspeed = $row['overspeed'];
                        $Datacap->totaldistance = $row['totaldistance'];
                        $Datacap->fenceconflict = $row['fenceconflict'];
                        $Datacap->idletime = $row['idletime'];
                        $Datacap->genset = $row['genset'];
                        $Datacap->runningtime = $row['runningtime'];
                        $Datacap->vehicleid = $row['vehicleid'];
                        $Datacap->dev_lat = $row['dev_lat'];
                        $Datacap->dev_long = $row['dev_long'];
                        $Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                        $Datacap->average = getAverage($row['vehicleid']);
                        $REPORT[] = $Datacap;
                    }
                }
            }
        }
    }
    return $REPORT;
}

/*
function get_daily_informatics($location,$days)
{
$path = "sqlite:$location";
$db = new PDO($path);
$REPORT = array();
if(isset($days))
{
foreach ($days as $day)
{
if($_SESSION['groupid'] != 0){
$vehiclesbygroup = getvehiclesbygroup($_SESSION['groupid']);
if(isset($vehiclesbygroup))
{
foreach ($vehiclesbygroup as $vehicle)
{
$sqlday = date("dmy",strtotime($day));
$query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
$result = $db->query($query);
if(isset($result) && $result!="")
{
foreach ($result as $row)
{
$Datacap = new stdClass();
$Datacap->date = strtotime($day);
$Datacap->info_date = $day;
$Datacap->uid = $row['uid'];
$Datacap->tamper = $row['tamper'];
$Datacap->overspeed = $row['overspeed'];
$Datacap->totaldistance = $row['totaldistance'];
$Datacap->fenceconflict = $row['fenceconflict'];
$Datacap->idletime = $row['idletime'];
$Datacap->genset = $row['genset'];
$Datacap->runningtime = $row['runningtime'];
$Datacap->vehicleid = $row['vehicleid'];
//$Datacap->dev_lat = $row['dev_lat'];
//$Datacap->dev_long = $row['dev_long'];
//$Datacap->location=get_location($row['dev_lat'],$row['dev_long']);
//$Datacap->average = getAverage($row['vehicleid']);
$REPORT[] = $Datacap;
}
}
}
}
}
else if($_SESSION['use_maintenance']=='1' && $_SESSION['use_hierarchy'] == '1'){
$vehiclesbygroup = get_all_vehiclesbyheirarchy();
if(isset($vehiclesbygroup))
{
foreach ($vehiclesbygroup as $vehicle)
{
$sqlday = date("dmy",strtotime($day));
$query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
$result = $db->query($query);
if(isset($result) && $result!="")
{
foreach ($result as $row)
{
$Datacap = new stdClass();
$Datacap->date = strtotime($day);
$Datacap->info_date = $day;
$Datacap->uid = $row['uid'];
$Datacap->tamper = $row['tamper'];
$Datacap->overspeed = $row['overspeed'];
$Datacap->totaldistance = $row['totaldistance'];
$Datacap->fenceconflict = $row['fenceconflict'];
$Datacap->idletime = $row['idletime'];
$Datacap->genset = $row['genset'];
$Datacap->runningtime = $row['runningtime'];
$Datacap->vehicleid = $row['vehicleid'];
//$Datacap->dev_lat = $row['dev_lat'];
//$Datacap->dev_long = $row['dev_long'];
//$Datacap->location=get_location($row['dev_lat'],$row['dev_long']);
//$Datacap->average = getAverage($row['vehicleid']);
$REPORT[] = $Datacap;
}
}
}
}
}
else if(isset($_SESSION['ecodeid']))
{
$vehiclesbygroup = getvehiclesbygroup_ecode();
if(isset($vehiclesbygroup))
{
foreach ($vehiclesbygroup as $vehicle)
{
$sqlday = date("dmy",strtotime($day));
$query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
$result = $db->query($query);
if(isset($result) && $result!="")
{
foreach ($result as $row)
{
$Datacap = new stdClass();
$Datacap->date = strtotime($day);
$Datacap->info_date = $day;
$Datacap->uid = $row['uid'];
$Datacap->tamper = $row['tamper'];
$Datacap->overspeed = $row['overspeed'];
$Datacap->totaldistance = $row['totaldistance'];
$Datacap->fenceconflict = $row['fenceconflict'];
$Datacap->idletime = $row['idletime'];
$Datacap->genset = $row['genset'];
$Datacap->runningtime = $row['runningtime'];
$Datacap->vehicleid = $row['vehicleid'];
//$Datacap->dev_lat = $row['dev_lat'];
//$Datacap->dev_long = $row['dev_long'];
//$Datacap->location=get_location($row['dev_lat'],$row['dev_long']);
//$Datacap->average = getAverage($row['vehicleid']);
$REPORT[] = $Datacap;
}
}
}
}
}
else {
$sqlday = date("dmy",strtotime($day));
$query = "SELECT * from A$sqlday order by vehicleid ASC";
$result = $db->query($query);
if(isset($result) && $result!="")
{
foreach ($result as $row)
{
$Datacap = new stdClass();
$Datacap->date = strtotime($day);
$Datacap->info_date = $day;
$Datacap->uid = $row['uid'];
$Datacap->tamper = isset($row['tamper']) ? $row['tamper'] : '';
$Datacap->overspeed = $row['overspeed'];
$Datacap->totaldistance = $row['totaldistance'];
$Datacap->fenceconflict = $row['fenceconflict'];
$Datacap->idletime = $row['idletime'];
$Datacap->genset = $row['genset'];
$Datacap->runningtime = $row['runningtime'];
$Datacap->vehicleid = $row['vehicleid'];
//$Datacap->dev_lat = $row['dev_lat'];
//$Datacap->dev_long = $row['dev_long'];
//$Datacap->location=get_location($row['dev_lat'],$row['dev_long']);
//$Datacap->average = getAverage($row['vehicleid']);
$REPORT[] = $Datacap;
}
}
}
}
}
return $REPORT;
 */

/*function for vehicle data by date*/

function GetchangeSqliteReport_Data_All($location, $STdate, $options = array()) {
    /**
     * @since 25-09-2017 By Suman Sharma
     * @internal $options['islocation'] = 0 means location details need to be fetched from google
     * @internal $options['islocation'] = 1 means location details not required and can be null/empty
     * @internal $options['groupid'] = array(1, 2, 3) or array(1);
     * @internal $options = array('islocation' => 0, 'customerno' => array(98), 'groupid' => array());
     * @todo Testing pending
     */

    //echo print("<pre>"); print_r($options); die;

    $vehicleid = $options['vehicleid'];
    $groupid = (isset($_SESSION['groupid']) ? array($_SESSION['groupid']) : $options['groupid']);
    $customerno = (isset($_SESSION['customerno']) ? array($_SESSION['customerno']) : $options['customerno']);
    $path = "sqlite:$location";

    $db = new PDO($path);
    $REPORT = array();
    if (isset($STdate)) {
        //echo 'here 2';
        $sqlday = date("dmy", strtotime($STdate));
        //echo "sql day". $STdate; die;
        //echo    "vehicle id is " . $vehicleid;

        if (isset($vehicleid) && $vehicleid != "") {
            // echo "<br>true called".$vehicleid; die;
            $query = "SELECT * from unithistory where vehicleid = $vehicleid";
        }

        $result = $db->query($query);

        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $Datacap = new stdClass();
                $Datacap->uid = $row['uid'];
                $Datacap->uhid = $row['uhid'];
                $Datacap->vehicleid = $row['vehicleid'];
                $Datacap->date = $STdate;
                $Datacap->analog1 = $row['analog1'];
                $Datacap->analog2 = $row['analog2'];
                $Datacap->analog3 = $row['analog3'];
                $Datacap->analog4 = $row['analog4'];

                $REPORT[] = $Datacap;
            }
        }
    }
    /*
    print("<pre>");
     */
    return $REPORT;
}

/*end */

function GetDailyReport_Data_All($location, $days, $options = array()) {
    /**
     * @since 25-09-2017 By Suman Sharma
     * @internal $options['islocation'] = 0 means location details need to be fetched from google
     * @internal $options['islocation'] = 1 means location details not required and can be null/empty
     * @internal $options['groupid'] = array(1, 2, 3) or array(1);
     * @internal $options = array('islocation' => 0, 'customerno' => array(98), 'groupid' => array());
     * @todo Testing pending
     */

    //echo print("<pre>"); print_r($options); die;

    $vehicleid = isset($options['vehicleid']) ? $options['vehicleid'] : 0;
    $groupid = (isset($_SESSION['groupid']) ? array($_SESSION['groupid']) : $options['groupid']);
    $customerno = (isset($_SESSION['customerno']) ? array($_SESSION['customerno']) : $options['customerno']);
    $path = "sqlite:$location";

    $db = new PDO($path);
    $REPORT = array();
    if (isset($days) && isset($groupid)) {
        $strGroupId = implode(',', $groupid);
        foreach ($days as $day) {
            if (isset($_SESSION['ecodeid'])) {
                $vehiclesbygroup = getvehiclesbygroup_ecode();
                if (isset($vehiclesbygroup)) {
                    foreach ($vehiclesbygroup as $vehicle) {
                        $sqlday = date("dmy", strtotime($day));
                        $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
                        $result = $db->query($query);
                        if (isset($result) && $result != "") {
                            foreach ($result as $row) {
                                $Datacap = new stdClass();
                                $Datacap->date = strtotime($day);
                                $Datacap->info_date = $day;
                                $Datacap->uid = $row['uid'];
                                //$Datacap->tamper = $row['tamper'];
                                $Datacap->overspeed = $row['overspeed'];
                                $Datacap->totaldistance = $row['totaldistance'];
                                $Datacap->fenceconflict = $row['fenceconflict'];
                                $Datacap->idletime = $row['idletime'];
                                $Datacap->genset = $row['genset'];
                                $Datacap->runningtime = $row['runningtime'];
                                $Datacap->vehicleid = $row['vehicleid'];
                                $Datacap->dev_lat = $row['dev_lat'];
                                $Datacap->dev_long = $row['dev_long'];
                                //$Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                                //$Datacap->average = getAverage($row['vehicleid']);
                                $REPORT[] = $Datacap;
                            }
                        }
                    }
                }
            } elseif (!in_array(0, $groupid)) {
                $vehiclesbygroup = getvehiclesbygroup($strGroupId, $options);

                if (isset($vehiclesbygroup)) {
                    foreach ($vehiclesbygroup as $vehicle) {
                        $sqlday = date("dmy", strtotime($day));
                        $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
                        $result = $db->query($query);
                        if (isset($result) && $result != "") {
                            foreach ($result as $row) {
                                $Datacap = new stdClass();

                                $Datacap->info_date = $day;
                                $Datacap->vehicleid = $row['vehicleid'];
                                $Datacap->totaldistance = $row['totaldistance'];
                                $Datacap->vehicleno = $vehicle->vehicleno;
                                $Datacap->customerno = $vehicle->customerno;
                                $Datacap->groupid = $vehicle->groupid;
                                $Datacap->groupname = $vehicle->groupname;
                                $Datacap->groupcode = $vehicle->groupcode;
                                $Datacap->g_userid = $vehicle->g_userid;
                                $Datacap->cityid = $vehicle->cityid;
                                $Datacap->citycode = $vehicle->citycode;
                                $Datacap->cityname = $vehicle->cityname;
                                $Datacap->cityuserid = $vehicle->cityuserid;
                                $Datacap->districtid = $vehicle->districtid;
                                $Datacap->districtcode = $vehicle->districtcode;
                                $Datacap->districtname = $vehicle->districtname;
                                $Datacap->districtuserid = $vehicle->districtuserid;

                                $Datacap->uid = $row['uid'];
                                $Datacap->tamper = retval_issetor($row['tamper']);
                                $Datacap->overspeed = $row['overspeed'];
                                $Datacap->fenceconflict = $row['fenceconflict'];
                                $Datacap->idletime = $row['idletime'];
                                $Datacap->genset = $row['genset'];
                                $Datacap->runningtime = $row['runningtime'];
                                $Datacap->dev_lat = $row['dev_lat'];
                                $Datacap->dev_long = $row['dev_long'];
                                $Datacap->date = strtotime($day);

                                //$Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                                if ($options['islocation'] == 0) {
                                    $Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                                } else {
                                    $Datacap->location = array();
                                }

                                //$Datacap->average = getAverage($row['vehicleid'], $options);
                                $REPORT[] = $Datacap;
                            }
                        }
                    }
                }
            } else {
                $sqlday = date("dmy", strtotime($day));
                //echo    "vehicle id is " . $vehicleid;

                if (isset($vehicleid) && $vehicleid != "") {
                    //echo "<br>true called".$vehicleid; die;
                    $query = "SELECT * from A$sqlday where vehicleid = $vehicleid";
                } else {
                    //echo "else called"; die;
                    $query = "SELECT * from A$sqlday order by vehicleid ASC";
                }

                $result = $db->query($query);
                if (isset($result) && $result != "") {
                    foreach ($result as $row) {
                        $Datacap = new stdClass();
                        $Datacap->date = strtotime($day);
                        $Datacap->info_date = $day;
                        $Datacap->uid = $row['uid'];
                        $Datacap->tamper = isset($row['tamper']) ? $row['tamper'] : '';
                        $Datacap->overspeed = $row['overspeed'];
                        $Datacap->totaldistance = $row['totaldistance'];
                        $Datacap->fenceconflict = $row['fenceconflict'];
                        $Datacap->idletime = $row['idletime'];
                        $Datacap->genset = $row['genset'];
                        $Datacap->runningtime = $row['runningtime'];
                        $Datacap->vehicleid = $row['vehicleid'];
                        $Datacap->dev_lat = $row['dev_lat'];
                        $Datacap->dev_long = $row['dev_long'];
                        if ($options['islocation'] == 1) // changed checking $options['islocation'] == 0 to 1, because we are passing 1 as value
                        {
                            $Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                        } else {
                            $Datacap->location = "";
                        }
                        $REPORT[] = $Datacap;
                    }
                }
            }
        }
    }
    return $REPORT;
}

function getDailyReportNew($location, $days, $options = array()) {
    /**
     * @since 25-09-2017 By Kartik Joshi
     * @internal $options['islocation'] = 0 means location details need to be fetched from google
     * @internal $options['islocation'] = 1 means location details not required and can be null/empty
     * @internal $options['groupid'] = array(1, 2, 3) or array(1);
     * @internal $options = array('islocation' => 0, 'customerno' => array(98), 'groupid' => array());
     * @todo Testing pending
     */
    $vehicleid = $options['vehicleid'];
    $groupid = (isset($_SESSION['groupid']) ? array($_SESSION['groupid']) : $options['groupid']);
    $customerno = (isset($_SESSION['customerno']) ? array($_SESSION['customerno']) : $options['customerno']);
    $path = "sqlite:$location";
    $db = new PDO($path);
    $REPORT = array();
    if (isset($days) && isset($groupid)) {
        $strGroupId = implode(',', $groupid);
        foreach ($days as $day) {
            if (isset($_SESSION['ecodeid'])) {
                $vehiclesbygroup = getvehiclesbygroup_ecode();
                if (isset($vehiclesbygroup)) {
                    foreach ($vehiclesbygroup as $vehicle) {
                        $sqlday = date("dmy", strtotime($day));
                        $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
                        $result = $db->query($query);
                        if (isset($result) && $result != "") {
                            foreach ($result as $row) {
                                $Datacap = new stdClass();
                                $Datacap->date = strtotime($day);
                                $Datacap->info_date = $day;
                                $Datacap->uid = $row['uid'];
                                $Datacap->overspeed = $row['overspeed'];
                                $Datacap->totaldistance = $row['totaldistance'];
                                $Datacap->fenceconflict = $row['fenceconflict'];
                                $Datacap->idletime = $row['idletime'];
                                $Datacap->genset = $row['genset'];
                                $Datacap->runningtime = $row['runningtime'];
                                $Datacap->vehicleid = $row['vehicleid'];
                                $Datacap->dev_lat = $row['dev_lat'];
                                $Datacap->dev_long = $row['dev_long'];
                                $REPORT[] = $Datacap;
                            }
                        }
                    }
                }
            } elseif (!in_array(0, $groupid) && !isset($vehicleid) && $vehicleid != "") {
                $vehiclesbygroup = getvehiclesbygroup($strGroupId, $options);
                if (isset($vehiclesbygroup)) {
                    foreach ($vehiclesbygroup as $vehicle) {
                        $sqlday = date("dmy", strtotime($day));
                        $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
                        $result = $db->query($query);
                        if (isset($result) && $result != "") {
                            foreach ($result as $row) {
                                $Datacap = new stdClass();

                                $Datacap->info_date = $day;
                                $Datacap->vehicleid = $row['vehicleid'];
                                $Datacap->totaldistance = $row['totaldistance'];
                                $Datacap->vehicleno = $vehicle->vehicleno;
                                $Datacap->customerno = $vehicle->customerno;
                                $Datacap->groupid = $vehicle->groupid;
                                $Datacap->groupname = $vehicle->groupname;
                                $Datacap->groupcode = $vehicle->groupcode;
                                $Datacap->g_userid = $vehicle->g_userid;
                                $Datacap->cityid = $vehicle->cityid;
                                $Datacap->citycode = $vehicle->citycode;
                                $Datacap->cityname = $vehicle->cityname;
                                $Datacap->cityuserid = $vehicle->cityuserid;
                                $Datacap->districtid = $vehicle->districtid;
                                $Datacap->districtcode = $vehicle->districtcode;
                                $Datacap->districtname = $vehicle->districtname;
                                $Datacap->districtuserid = $vehicle->districtuserid;

                                $Datacap->uid = $row['uid'];
                                $Datacap->tamper = retval_issetor($row['tamper']);
                                $Datacap->overspeed = $row['overspeed'];
                                $Datacap->fenceconflict = $row['fenceconflict'];
                                $Datacap->idletime = $row['idletime'];
                                $Datacap->genset = $row['genset'];
                                $Datacap->runningtime = $row['runningtime'];
                                $Datacap->dev_lat = $row['dev_lat'];
                                $Datacap->dev_long = $row['dev_long'];
                                $Datacap->date = strtotime($day);

                                //$Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                                if ($options['islocation'] == 0) {
                                    $Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                                } else {
                                    $Datacap->location = array();
                                }

                                //$Datacap->average = getAverage($row['vehicleid'], $options);
                                $REPORT[] = $Datacap;
                            }
                        }
                    }
                }
            } else {
                $sqlday = date("dmy", strtotime($day));
                //echo    "vehicle id is " . $vehicleid;

                if (isset($vehicleid) && $vehicleid != "") {
                    //echo "<br>true called".$vehicleid; die;
                    $query = "SELECT * from A$sqlday where vehicleid = $vehicleid";
                } else {
                    //echo "else called"; die;
                    $query = "SELECT * from A$sqlday order by vehicleid ASC";
                }

                $result = $db->query($query);
                if (isset($result) && $result != "") {
                    foreach ($result as $row) {
                        // echo "time:".date('H:i:s')."</br>";
                        // echo "<pre>";
                        // print_r($row);
                        // echo "</pre>";
                        //print_r($row);
                        $Datacap = new stdClass();
                        $Datacap->date = strtotime($day);
                        $Datacap->info_date = $day;
                        $Datacap->uid = $row['uid'];
                        $Datacap->tamper = isset($row['tamper']) ? $row['tamper'] : '';
                        $Datacap->overspeed = $row['overspeed'];
                        $Datacap->totaldistance = $row['totaldistance'];
                        $Datacap->fenceconflict = $row['fenceconflict'];
                        $Datacap->idletime = $row['idletime'];
                        $Datacap->genset = $row['genset'];
                        $Datacap->runningtime = $row['runningtime'];
                        $Datacap->vehicleid = $row['vehicleid'];
                        $Datacap->dev_lat = $row['dev_lat'];
                        $Datacap->dev_long = $row['dev_long'];
                        $Datacap->topspeed = $row['topspeed'];
                        if ($options['islocation'] == 1) // changed checking $options['islocation'] == 0 to 1, because we are passing 1 as value
                        {
                            $Datacap->location = "";
                        } else {
                            $Datacap->location = "";
                        }
                        $REPORT[] = $Datacap;
                    }
                }
            }
        }
    }
    return $REPORT;
}

function GetDailyReport_Data_All_PDF_New($location, $days, $customerno, $usemaintainance, $usehierarchy, $groupid) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $REPORT = array();
    if (isset($days)) {
        foreach ($days as $day) {
            if ($groupid != 0) {
                $vehiclesbygroup = getvehiclesbygroup($groupid);
                if (isset($vehiclesbygroup)) {
                    foreach ($vehiclesbygroup as $vehicle) {
                        $sqlday = date("dmy", strtotime($day));
                        $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
                        $result = $db->query($query);
                        if (isset($result) && $result != "") {
                            foreach ($result as $row) {
                                $Datacap = new stdClass();
                                $Datacap->date = strtotime($day);
                                $Datacap->info_date = $day;
                                $Datacap->uid = $row['uid'];
                                $Datacap->tamper = retval_issetor($row['tamper']);
                                $Datacap->overspeed = $row['overspeed'];
                                $Datacap->totaldistance = $row['totaldistance'];
                                $Datacap->fenceconflict = $row['fenceconflict'];
                                $Datacap->idletime = $row['idletime'];
                                $Datacap->genset = $row['genset'];
                                $Datacap->runningtime = $row['runningtime'];
                                $Datacap->vehicleid = $row['vehicleid'];
                                $Datacap->dev_lat = $row['dev_lat'];
                                $Datacap->dev_long = $row['dev_long'];
                                $Datacap->location = "";
                                //$Datacap->average = getAverage_pdf($row['vehicleid'], $customerno);
                                $REPORT[] = $Datacap;
                            }
                        }
                    }
                }
            } elseif ($usemaintainance == '1' && $usehierarchy == '1') {
                $vehiclesbygroup = get_all_vehiclesbyheirarchy();
                if (isset($vehiclesbygroup)) {
                    foreach ($vehiclesbygroup as $vehicle) {
                        $sqlday = date("dmy", strtotime($day));
                        $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
                        $result = $db->query($query);
                        if (isset($result) && $result != "") {
                            foreach ($result as $row) {
                                $Datacap = new stdClass();
                                $Datacap->date = strtotime($day);
                                $Datacap->info_date = $day;
                                $Datacap->uid = $row['uid'];
                                $Datacap->tamper = $row['tamper'];
                                $Datacap->overspeed = $row['overspeed'];
                                $Datacap->totaldistance = $row['totaldistance'];
                                $Datacap->fenceconflict = $row['fenceconflict'];
                                $Datacap->idletime = $row['idletime'];
                                $Datacap->genset = $row['genset'];
                                $Datacap->runningtime = $row['runningtime'];
                                $Datacap->vehicleid = $row['vehicleid'];
                                $Datacap->dev_lat = $row['dev_lat'];
                                $Datacap->dev_long = $row['dev_long'];
                                $Datacap->location = "";
                                //$Datacap->average = getAverage_pdf($row['vehicleid'], $customerno);
                                $REPORT[] = $Datacap;
                            }
                        }
                    }
                }
            } elseif (isset($_SESSION['ecodeid'])) {
                $vehiclesbygroup = getvehiclesbygroup_ecode();
                if (isset($vehiclesbygroup)) {
                    foreach ($vehiclesbygroup as $vehicle) {
                        $sqlday = date("dmy", strtotime($day));
                        $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
                        $result = $db->query($query);
                        if (isset($result) && $result != "") {
                            foreach ($result as $row) {
                                $Datacap = new stdClass();
                                $Datacap->date = strtotime($day);
                                $Datacap->info_date = $day;
                                $Datacap->uid = $row['uid'];
                                $Datacap->tamper = $row['tamper'];
                                $Datacap->overspeed = $row['overspeed'];
                                $Datacap->totaldistance = $row['totaldistance'];
                                $Datacap->fenceconflict = $row['fenceconflict'];
                                $Datacap->idletime = $row['idletime'];
                                $Datacap->genset = $row['genset'];
                                $Datacap->runningtime = $row['runningtime'];
                                $Datacap->vehicleid = $row['vehicleid'];
                                $Datacap->dev_lat = $row['dev_lat'];
                                $Datacap->dev_long = $row['dev_long'];
                                $Datacap->location = "";
                                //$Datacap->average = getAverage_pdf($row['vehicleid'], $customerno);
                                $REPORT[] = $Datacap;
                            }
                        }
                    }
                }
            } else {
                $sqlday = date("dmy", strtotime($day));
                $query = "SELECT * from A$sqlday order by vehicleid ASC";
                $result = $db->query($query);
                if (isset($result) && $result != "") {
                    foreach ($result as $row) {
                        $Datacap = new stdClass();
                        $Datacap->date = strtotime($day);
                        $Datacap->info_date = $day;
                        $Datacap->uid = $row['uid'];
                        $Datacap->tamper = retval_issetor($row['tamper']);
                        $Datacap->overspeed = $row['overspeed'];
                        $Datacap->totaldistance = $row['totaldistance'];
                        $Datacap->fenceconflict = $row['fenceconflict'];
                        $Datacap->idletime = $row['idletime'];
                        $Datacap->genset = $row['genset'];
                        $Datacap->runningtime = $row['runningtime'];
                        $Datacap->vehicleid = $row['vehicleid'];
                        $Datacap->dev_lat = $row['dev_lat'];
                        $Datacap->dev_long = $row['dev_long'];
                        $Datacap->location = "";
                        //$Datacap->average = getAverage_pdf($row['vehicleid'], $customerno);
                        $REPORT[] = $Datacap;
                    }
                }
            }
        }
    }
    return $REPORT;
}

function GetDailyReport_Data_All_PDF($location, $days, $customerno, $usemaintainance, $usehierarchy, $groupid) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $REPORT = array();
    if (isset($days)) {
        foreach ($days as $day) {
            if ($groupid != 0) {
                $vehiclesbygroup = getvehiclesbygroup($groupid);
                if (isset($vehiclesbygroup)) {
                    foreach ($vehiclesbygroup as $vehicle) {
                        $sqlday = date("dmy", strtotime($day));
                        $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
                        $result = $db->query($query);
                        if (isset($result) && $result != "") {
                            foreach ($result as $row) {
                                $Datacap = new stdClass();
                                $Datacap->date = strtotime($day);
                                $Datacap->info_date = $day;
                                $Datacap->uid = $row['uid'];
                                $Datacap->tamper = retval_issetor($row['tamper']);
                                $Datacap->overspeed = $row['overspeed'];
                                $Datacap->totaldistance = $row['totaldistance'];
                                $Datacap->fenceconflict = $row['fenceconflict'];
                                $Datacap->idletime = $row['idletime'];
                                $Datacap->genset = $row['genset'];
                                $Datacap->runningtime = $row['runningtime'];
                                $Datacap->vehicleid = $row['vehicleid'];
                                $Datacap->dev_lat = $row['dev_lat'];
                                $Datacap->dev_long = $row['dev_long'];
                                $Datacap->location = get_location_pdf($row['dev_lat'], $row['dev_long'], $customerno);
                                //$Datacap->average = getAverage_pdf($row['vehicleid'], $customerno);
                                $REPORT[] = $Datacap;
                            }
                        }
                    }
                }
            } elseif ($usemaintainance == '1' && $usehierarchy == '1') {
                $vehiclesbygroup = get_all_vehiclesbyheirarchy();
                if (isset($vehiclesbygroup)) {
                    foreach ($vehiclesbygroup as $vehicle) {
                        $sqlday = date("dmy", strtotime($day));
                        $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
                        $result = $db->query($query);
                        if (isset($result) && $result != "") {
                            foreach ($result as $row) {
                                $Datacap = new stdClass();
                                $Datacap->date = strtotime($day);
                                $Datacap->info_date = $day;
                                $Datacap->uid = $row['uid'];
                                $Datacap->tamper = $row['tamper'];
                                $Datacap->overspeed = $row['overspeed'];
                                $Datacap->totaldistance = $row['totaldistance'];
                                $Datacap->fenceconflict = $row['fenceconflict'];
                                $Datacap->idletime = $row['idletime'];
                                $Datacap->genset = $row['genset'];
                                $Datacap->runningtime = $row['runningtime'];
                                $Datacap->vehicleid = $row['vehicleid'];
                                $Datacap->dev_lat = $row['dev_lat'];
                                $Datacap->dev_long = $row['dev_long'];
                                $Datacap->location = get_location_pdf($row['dev_lat'], $row['dev_long'], $customerno);
                                //$Datacap->average = getAverage_pdf($row['vehicleid'], $customerno);
                                $REPORT[] = $Datacap;
                            }
                        }
                    }
                }
            } elseif (isset($_SESSION['ecodeid'])) {
                $vehiclesbygroup = getvehiclesbygroup_ecode();
                if (isset($vehiclesbygroup)) {
                    foreach ($vehiclesbygroup as $vehicle) {
                        $sqlday = date("dmy", strtotime($day));
                        $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
                        $result = $db->query($query);
                        if (isset($result) && $result != "") {
                            foreach ($result as $row) {
                                $Datacap = new stdClass();
                                $Datacap->date = strtotime($day);
                                $Datacap->info_date = $day;
                                $Datacap->uid = $row['uid'];
                                $Datacap->tamper = $row['tamper'];
                                $Datacap->overspeed = $row['overspeed'];
                                $Datacap->totaldistance = $row['totaldistance'];
                                $Datacap->fenceconflict = $row['fenceconflict'];
                                $Datacap->idletime = $row['idletime'];
                                $Datacap->genset = $row['genset'];
                                $Datacap->runningtime = $row['runningtime'];
                                $Datacap->vehicleid = $row['vehicleid'];
                                $Datacap->dev_lat = $row['dev_lat'];
                                $Datacap->dev_long = $row['dev_long'];
                                $Datacap->location = get_location_pdf($row['dev_lat'], $row['dev_long'], $customerno);
                                //$Datacap->average = getAverage_pdf($row['vehicleid'], $customerno);
                                $REPORT[] = $Datacap;
                            }
                        }
                    }
                }
            } else {
                $sqlday = date("dmy", strtotime($day));
                $query = "SELECT * from A$sqlday order by vehicleid ASC";
                $result = $db->query($query);
                if (isset($result) && $result != "") {
                    foreach ($result as $row) {
                        $Datacap = new stdClass();
                        $Datacap->date = strtotime($day);
                        $Datacap->info_date = $day;
                        $Datacap->uid = $row['uid'];
                        $Datacap->tamper = retval_issetor($row['tamper']);
                        $Datacap->overspeed = $row['overspeed'];
                        $Datacap->totaldistance = $row['totaldistance'];
                        $Datacap->fenceconflict = $row['fenceconflict'];
                        $Datacap->idletime = $row['idletime'];
                        $Datacap->genset = $row['genset'];
                        $Datacap->runningtime = $row['runningtime'];
                        $Datacap->vehicleid = $row['vehicleid'];
                        $Datacap->dev_lat = $row['dev_lat'];
                        $Datacap->dev_long = $row['dev_long'];
                        $Datacap->location = get_location_pdf($row['dev_lat'], $row['dev_long'], $customerno);
                        //$Datacap->average = getAverage_pdf($row['vehicleid'], $customerno);
                        $REPORT[] = $Datacap;
                    }
                }
            }
        }
    }
    return $REPORT;
}

/*  single vehicle pdf distance report */

function GetDailyReport_Data_Vehicle_PDF($vehicleid, $location, $days, $customerno, $usemaintainance, $usehierarchy, $groupid) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $REPORT = array();

    //echo "groupid".$groupid; die;
    if (isset($days)) {
        foreach ($days as $day) {
            //if ($groupid != 0) {
            // $vehiclesbygroup = getvehiclesbygroup($groupid);
            //if (isset($vehiclesbygroup)) {
            //foreach ($vehiclesbygroup as $vehicle) {
            $sqlday = date("dmy", strtotime($day));
            $query = "SELECT * from A$sqlday where vehicleid=$vehicleid order by vehicleid ASC";
            //echo $query;
            $result = $db->query($query);
            if (isset($result) && $result != "") {
                foreach ($result as $row) {
                    $Datacap = new stdClass();
                    $Datacap->date = strtotime($day);
                    $Datacap->info_date = $day;
                    $Datacap->uid = $row['uid'];
                    $Datacap->tamper = retval_issetor($row['tamper']);
                    $Datacap->overspeed = $row['overspeed'];
                    $Datacap->totaldistance = $row['totaldistance'];
                    $Datacap->fenceconflict = $row['fenceconflict'];
                    $Datacap->idletime = $row['idletime'];
                    $Datacap->genset = $row['genset'];
                    $Datacap->runningtime = $row['runningtime'];
                    $Datacap->vehicleid = $row['vehicleid'];
                    $Datacap->dev_lat = $row['dev_lat'];
                    $Datacap->dev_long = $row['dev_long'];
                    $Datacap->topspeed = $row['topspeed'];
                    //$Datacap->location = get_location_pdf($row['dev_lat'], $row['dev_long'], $customerno);
                    //$Datacap->average = getAverage_pdf($row['vehicleid'], $customerno);
                    $REPORT[] = $Datacap;

                    // }
                    // }
                    //}
                }
            } elseif ($usemaintainance == '1' && $usehierarchy == '1') {
                $vehiclesbygroup = get_all_vehiclesbyheirarchy();
                if (isset($vehiclesbygroup)) {
                    foreach ($vehiclesbygroup as $vehicle) {
                        $sqlday = date("dmy", strtotime($day));
                        $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
                        $result = $db->query($query);
                        if (isset($result) && $result != "") {
                            foreach ($result as $row) {
                                $Datacap = new stdClass();
                                $Datacap->date = strtotime($day);
                                $Datacap->info_date = $day;
                                $Datacap->uid = $row['uid'];
                                $Datacap->tamper = $row['tamper'];
                                $Datacap->overspeed = $row['overspeed'];
                                $Datacap->totaldistance = $row['totaldistance'];
                                $Datacap->fenceconflict = $row['fenceconflict'];
                                $Datacap->idletime = $row['idletime'];
                                $Datacap->genset = $row['genset'];
                                $Datacap->runningtime = $row['runningtime'];
                                $Datacap->vehicleid = $row['vehicleid'];
                                $Datacap->dev_lat = $row['dev_lat'];
                                $Datacap->dev_long = $row['dev_long'];
                                $Datacap->topspeed = $row['topspeed'];
                                //$Datacap->location = get_location_pdf($row['dev_lat'], $row['dev_long'], $customerno);
                                //$Datacap->average = getAverage_pdf($row['vehicleid'], $customerno);
                                $REPORT[] = $Datacap;
                            }
                        }
                    }
                }
            } elseif (isset($_SESSION['ecodeid'])) {
                $vehiclesbygroup = getvehiclesbygroup_ecode();
                if (isset($vehiclesbygroup)) {
                    foreach ($vehiclesbygroup as $vehicle) {
                        $sqlday = date("dmy", strtotime($day));
                        $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
                        $result = $db->query($query);
                        if (isset($result) && $result != "") {
                            foreach ($result as $row) {
                                $Datacap = new stdClass();
                                $Datacap->date = strtotime($day);
                                $Datacap->info_date = $day;
                                $Datacap->uid = $row['uid'];
                                $Datacap->tamper = $row['tamper'];
                                $Datacap->overspeed = $row['overspeed'];
                                $Datacap->totaldistance = $row['totaldistance'];
                                $Datacap->fenceconflict = $row['fenceconflict'];
                                $Datacap->idletime = $row['idletime'];
                                $Datacap->genset = $row['genset'];
                                $Datacap->runningtime = $row['runningtime'];
                                $Datacap->vehicleid = $row['vehicleid'];
                                $Datacap->dev_lat = $row['dev_lat'];
                                $Datacap->dev_long = $row['dev_long'];
                                $Datacap->topspeed = $row['topspeed'];
                                //$Datacap->location = get_location_pdf($row['dev_lat'], $row['dev_long'], $customerno);
                                //$Datacap->average = getAverage_pdf($row['vehicleid'], $customerno);
                                $REPORT[] = $Datacap;
                            }
                        }
                    }
                }
            } else {
                $sqlday = date("dmy", strtotime($day));
                $query = "SELECT * from A$sqlday order by vehicleid ASC";
                $result = $db->query($query);
                if (isset($result) && $result != "") {
                    foreach ($result as $row) {
                        $Datacap = new stdClass();
                        $Datacap->date = strtotime($day);
                        $Datacap->info_date = $day;
                        $Datacap->uid = $row['uid'];
                        $Datacap->tamper = retval_issetor($row['tamper']);
                        $Datacap->overspeed = $row['overspeed'];
                        $Datacap->totaldistance = $row['totaldistance'];
                        $Datacap->fenceconflict = $row['fenceconflict'];
                        $Datacap->idletime = $row['idletime'];
                        $Datacap->genset = $row['genset'];
                        $Datacap->runningtime = $row['runningtime'];
                        $Datacap->vehicleid = $row['vehicleid'];
                        $Datacap->dev_lat = $row['dev_lat'];
                        $Datacap->dev_long = $row['dev_long'];
                        $Datacap->topspeed = $row['topspeed'];
                        //$Datacap->location = get_location_pdf($row['dev_lat'], $row['dev_long'], $customerno);
                        //$Datacap->average = getAverage_pdf($row['vehicleid'], $customerno);
                        $REPORT[] = $Datacap;
                    }
                }
            }
        }
    }
    /*print("<pre>");
    print_r($REPORT); die;*/
    return $REPORT;
}

function GetDailyReport_Data_ByID($location, $days, $vehicleid) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $REPORT = array();
    if (isset($days)) {
        foreach ($days as $day) {
            if ($_SESSION['groupid'] != 0) {
                $sqlday = date("dmy", strtotime($day));
                $query = "SELECT * from A$sqlday where vehicleid=$vehicleid";
                $result = $db->query($query);
                if (isset($result) && $result != "") {
                    foreach ($result as $row) {
                        $Datacap = new stdClass();
                        $Datacap->date = strtotime($day);
                        $Datacap->uid = $row['uid'];
                        $Datacap->tamper = $row['tamper'];
                        $Datacap->overspeed = $row['overspeed'];
                        $Datacap->totaldistance = $row['totaldistance'];
                        $Datacap->fenceconflict = $row['fenceconflict'];
                        $Datacap->idletime = $row['idletime'];
                        $Datacap->genset = $row['genset'];
                        $Datacap->runningtime = $row['runningtime'];
                        $Datacap->vehicleid = $row['vehicleid'];
                        $Datacap->dev_lat = $row['dev_lat'];
                        $Datacap->dev_long = $row['dev_long'];
                        $Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                        $Datacap->average = getAverage($row['vehicleid']);
                        $REPORT[] = $Datacap;
                    }
                }
            } elseif ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
                $sqlday = date("dmy", strtotime($day));
                $query = "SELECT * from A$sqlday where vehicleid=$vehicleid";
                $result = $db->query($query);
                if (isset($result) && $result != "") {
                    foreach ($result as $row) {
                        $Datacap = new stdClass();
                        $Datacap->date = strtotime($day);
                        $Datacap->uid = $row['uid'];
                        $Datacap->tamper = $row['tamper'];
                        $Datacap->overspeed = $row['overspeed'];
                        $Datacap->totaldistance = $row['totaldistance'];
                        $Datacap->fenceconflict = $row['fenceconflict'];
                        $Datacap->idletime = $row['idletime'];
                        $Datacap->genset = $row['genset'];
                        $Datacap->runningtime = $row['runningtime'];
                        $Datacap->vehicleid = $row['vehicleid'];
                        $Datacap->dev_lat = $row['dev_lat'];
                        $Datacap->dev_long = $row['dev_long'];
                        $Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                        $Datacap->average = getAverage($row['vehicleid']);
                        $REPORT[] = $Datacap;
                    }
                }
            } else {
                $sqlday = date("dmy", strtotime($day));
                $query = "SELECT * from A$sqlday";
                if ($vehicleid != null) {
                    $query .= " where vehicleid=$vehicleid";
                }
                $result = $db->query($query);
                if (isset($result) && $result != "") {
                    foreach ($result as $row) {
                        $Datacap = new stdClass();
                        $Datacap->date = strtotime($day);
                        $Datacap->uid = $row['uid'];
                        $Datacap->tamper = $row['tamper'];
                        $Datacap->overspeed = $row['overspeed'];
                        $Datacap->totaldistance = $row['totaldistance'];
                        $Datacap->fenceconflict = $row['fenceconflict'];
                        $Datacap->idletime = $row['idletime'];
                        $Datacap->genset = $row['genset'];
                        $Datacap->runningtime = $row['runningtime'];
                        $Datacap->vehicleid = $row['vehicleid'];
                        $Datacap->dev_lat = $row['dev_lat'];
                        $Datacap->dev_long = $row['dev_long'];
                        $Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                        $Datacap->average = getAverage($row['vehicleid']);
                        $REPORT[] = $Datacap;
                    }
                }
            }
        }
    }
    return $REPORT;
}

function getAverage($vehicleid, $options = null) {
    $_SESSION['customerno'] = isset($_SESSION['customerno']) ? $_SESSION['customerno'] : $options['customerno'];
    $VehicleManager = new VehicleManager($_SESSION['customerno']);
    $average = $VehicleManager->getAverage($vehicleid);
    return $average;
}

function getAverage_pdf($vehicleid, $customerno) {
    $VehicleManager = new VehicleManager($customerno);
    $average = $VehicleManager->getAverage($vehicleid);
    return $average;
}

function gettemp($rawtemp, $useHumidity = null, $switchTo = null) {
    $useHumidity = isset($useHumidity) ? $useHumidity : $_SESSION['use_humidity'];
    $switchTo = isset($switchTo) ? $switchTo : $_SESSION['switch_to'];
    if (isset($useHumidity) && isset($switchTo) && $useHumidity == 1 && $switchTo == 3) {
        $temp = round($rawtemp / 100);
    } else {
        $temp = round((($rawtemp - 1150) / 4.45), 1);
    }
    return $temp;
}

function gettemp_humidity($rawtemp, $use_humidity, $switchto) {
    if ($use_humidity == 4 && $switchto == 3) {
        $temp = round($rawtemp / 100);
    } else {
        $temp = round((($rawtemp - 1150) / 4.45), 1);
    }
    return $temp;
}

function getFuelRefill($vehicleid, $date) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $fuelrefill = $vehiclemanager->get_Fuel_Refill($vehicleid, $date);
    return $fuelrefill;
}

function GetHourlyReport_Temp($location, $STdate, $SThour) {
    $temp = 0;
    $time = strtotime($SThour) + 3600;
    $nexthr = date('H:i:s', $time);
    $path = "sqlite:$location";
    $db = new PDO($path);
    $REPORT = array();
    if ($SThour != '23:00:00') {
        $query = "SELECT * from unithistory where DATETIME(lastupdated) BETWEEN '$STdate $SThour' AND '$STdate $nexthr' ORDER BY lastupdated ASC";
    } else {
        $query = "SELECT * from unithistory where DATETIME(lastupdated) BETWEEN '$STdate $SThour' AND '$STdate 23:59:59' ORDER BY lastupdated ASC";
    }
    $result = $db->query($query);
    if (isset($result) && $result != "") {
        foreach ($result as $row) {
            if ($temp != 0 && abs($row['analog1'] - $temp) < 22.25) {
                $Datacap = new stdClass();
                $Datacap->date = strtotime($day);
                $Datacap->analog1 = $row['analog1'];
                $Datacap->lastupdated = $row['lastupdated'];
                $REPORT[] = $Datacap;
                $temp = $row['analog1'];
            } elseif ($temp == 0) {
                $Datacap = new stdClass();
                $Datacap->date = strtotime($day);
                $Datacap->analog1 = $row['analog1'];
                $Datacap->lastupdated = $row['lastupdated'];
                $REPORT[] = $Datacap;
                $temp = $row['analog1'];
            }
        }
    }
    return $REPORT;
}

function GetDailyReport_Temp($location, $STdate, $EDdate) {
    $temp = 0;
    $time = strtotime($SThour) + 3600;
    $path = "sqlite:$location";
    $db = new PDO($path);
    $REPORT = array();
    $query = "SELECT * from unithistory where lastupdated BETWEEN '$STdate' AND '$EDdate' ORDER BY lastupdated ASC";
    $result = $db->query($query);
    if (isset($result) && $result != "") {
        foreach ($result as $row) {
            if ($temp != 0 && abs($row['analog1'] - $temp) < 100) {
                $Datacap = new stdClass();
                $Datacap->date = strtotime($day);
                $Datacap->analog1 = $row['analog1'];
                $Datacap->analog2 = $row['analog2'];
                $Datacap->analog3 = $row['analog3'];
                $Datacap->analog4 = $row['analog4'];
                $Datacap->lastupdated = $row['lastupdated'];
                $REPORT[] = $Datacap;
                $temp = $row['analog1'];
            } elseif ($temp == 0) {
                $Datacap = new stdClass();
                $Datacap->date = strtotime($day);
                $Datacap->analog1 = $row['analog1'];
                $Datacap->analog2 = $row['analog2'];
                $Datacap->analog3 = $row['analog3'];
                $Datacap->analog4 = $row['analog4'];
                $Datacap->lastupdated = $row['lastupdated'];
                $REPORT[] = $Datacap;
                $temp = $row['analog1'];
            }
        }
    }
    return $REPORT;
}

function GetDailyReport_Temp_Analog($location, $STdate, $EDdate, $analogtype) {
    $temp = 0;
    $time = strtotime($SThour) + 3600;
    $path = "sqlite:$location";
    $db = new PDO($path);
    $REPORT = array();
    $query = "SELECT * from unithistory where lastupdated BETWEEN '$STdate' AND '$EDdate' ORDER BY lastupdated ASC";
    $result = $db->query($query);
    if (isset($result) && $result != "") {
        foreach ($result as $row) {
            if ($analogtype == 1) {
                if ($temp != 0 && abs($row['analog1'] - $temp) < 22.25) {
                    $Datacap = new stdClass();
                    $Datacap->date = strtotime($day);
                    $Datacap->analog1 = $row['analog1'];
                    $Datacap->lastupdated = $row['lastupdated'];
                    $REPORT[] = $Datacap;
                    $temp = $row['analog1'];
                } elseif ($temp == 0) {
                    $Datacap = new stdClass();
                    $Datacap->date = strtotime($day);
                    $Datacap->analog1 = $row['analog1'];
                    $Datacap->lastupdated = $row['lastupdated'];
                    $REPORT[] = $Datacap;
                    $temp = $row['analog1'];
                }
            } elseif ($analogtype == 2) {
                if ($temp != 0 && abs($row['analog2'] - $temp) < 22.25) {
                    $Datacap = new stdClass();
                    $Datacap->date = strtotime($day);
                    $Datacap->analog1 = $row['analog2'];
                    $Datacap->lastupdated = $row['lastupdated'];
                    $REPORT[] = $Datacap;
                    $temp = $row['analog2'];
                } elseif ($temp == 0) {
                    $Datacap = new stdClass();
                    $Datacap->date = strtotime($day);
                    $Datacap->analog1 = $row['analog2'];
                    $Datacap->lastupdated = $row['lastupdated'];
                    $REPORT[] = $Datacap;
                    $temp = $row['analog2'];
                }
            } elseif ($analogtype == 3) {
                if ($temp != 0 && abs($row['analog3'] - $temp) < 22.25) {
                    $Datacap = new stdClass();
                    $Datacap->date = strtotime($day);
                    $Datacap->analog1 = $row['analog3'];
                    $Datacap->lastupdated = $row['lastupdated'];
                    $REPORT[] = $Datacap;
                    $temp = $row['analog3'];
                } elseif ($temp == 0) {
                    $Datacap = new stdClass();
                    $Datacap->date = strtotime($day);
                    $Datacap->analog1 = $row['analog3'];
                    $Datacap->lastupdated = $row['lastupdated'];
                    $REPORT[] = $Datacap;
                    $temp = $row['analog3'];
                }
            } elseif ($analogtype == 4) {
                if ($temp != 0 && abs($row['analog4'] - $temp) < 22.25) {
                    $Datacap = new stdClass();
                    $Datacap->date = strtotime($day);
                    $Datacap->analog1 = $row['analog4'];
                    $Datacap->lastupdated = $row['lastupdated'];
                    $REPORT[] = $Datacap;
                    $temp = $row['analog4'];
                } elseif ($temp == 0) {
                    $Datacap = new stdClass();
                    $Datacap->date = strtotime($day);
                    $Datacap->analog1 = $row['analog4'];
                    $Datacap->lastupdated = $row['lastupdated'];
                    $REPORT[] = $Datacap;
                    $temp = $row['analog4'];
                }
            }
        }
    }
    return $REPORT;
}

function m2h($mins) {
    if ($mins < 0) {
        $min = Abs($mins);
    } else {
        $min = $mins;
    }
    $H = Floor($min / 60);
    $M = ($min - ($H * 60)) / 100;
    $hours = $H + $M;
    if ($mins < 0) {
        $hours = $hours * (-1);
    }
    $expl = explode(".", $hours);
    $H = $expl[0];
    if (empty($expl[1])) {
        $expl[1] = 00;
    }
    $M = $expl[1];
    if (strlen($M) < 2) {
        $M = $M . 0;
    }
    $hours = $H . ":" . $M;
    return $hours;
}

function getalerthist($date, $type, $vehicleid, $checkpointid, $fenceid) {
    include_once '../../lib/bo/ComQueueManager.php';
    $cqm = new ComQueueManager($_SESSION['customerno']);
    $currentdate = date("d-m-Y");
    $i = 1;
    $data = '';
    if ($date == $currentdate) {
        if ($_SESSION['customerno'] == 421) {
            $prevDate = date('Y-m-d', strtotime("- 20 days"));
            $queues = $cqm->getalerthistprev($date, $prevDate, $type, $vehicleid, $checkpointid, $fenceid, $_SESSION['customerno']);
        } else {
            $queues = $cqm->getalerthist($date, $type, $vehicleid, $checkpointid, $fenceid, $_SESSION['customerno']);
        }
        //print_r($queues);
        if (isset($queues)) {
            foreach ($queues as $queue) {
                if ($queue->processed == 1 && $queue->comtype == 0) {
                    $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>$queue->email</td><td>---</td></tr>";
                } elseif ($queue->processed == 1 && $queue->comtype == 1) {
                    $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>$queue->phone</td></tr>";
                } else {
                    $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>---</td></tr>";
                }
                $i++;
            }
        }
    } else {
        $dt = strtotime(date("Y-m-d", strtotime($date)));
        $file = date("MY", $dt);
        $location = "../../customer/" . $_SESSION['customerno'] . "/history/$file.sqlite";
        if (file_exists($location)) {
            $queues = getalerthist_sqlite($location, $date, $type, $vehicleid, $checkpointid, $fenceid);
            if (isset($queues)) {
                foreach ($queues as $queue) {
                    if ($queue->processed == 1) {
                        $historys = getcomhist_sqlite($location, $queue->cqid);
                        if (isset($historys)) {
                            foreach ($historys as $history) {
                                if ($history->comtype == 0) {
                                    $users = $cqm->getuserdetailss($history->userid);
                                    if (isset($users)) {
                                        $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>$users->email</td><td>---</td></tr>";
                                        $i++;
                                    }
                                } elseif ($history->comtype == 1) {
                                    $users = $cqm->getuserdetailss($history->userid);
                                    $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>$users->phone</td></tr>";
                                    $i++;
                                } elseif ($history->comtype == 1) {
                                    $users = $cqm->getuserdetailss($history->userid);
                                    $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>$users->phone</td></tr>";
                                    $i++;
                                }
                            }
                        }
                    } else {
                        $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>---</td></tr>";
                        $i++;
                    }
                }
            }
        }
    }
    if ($data == '') {
        $data .= "<tr><td colspan='5' style='text-align:center;'>No Data Available</td></tr>";
    }
    $data .= '</body></table>';
    echo $data;
}

// function for to covert to pdf for alert history
function get_pdf_alerthist($date, $type, $vehicleid, $checkpointid, $fenceid, $vehicleno, $customerno, $alertmode, $switchto, $vgroupname = null) {
    $finalreport = '';
    $title = 'Alert History Report';
    if ($switchto == 3) {
        if (isset($_SESSION['Warehouse'])) {
            $veh = $_SESSION['Warehouse'];
        } else {
            $veh = "Warehouse";
        }
        $subTitle = array("Type : $alertmode", "$veh: $vehicleno", "Date: $date");
    } else {
        $subTitle = array("Type : $alertmode", "Vehicle No: $vehicleno", "Date: $date");
    }

    if (!is_null($vgroupname)) {
        $subTitle[] = "Group-name: $vgroupname";
    }
    $finalreport = pdf_header($title, $subTitle);
    $finalreport .= "<table align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                    <tr style='background-color:#CCCCCC;'>
                        <td style='width:100px;height:auto;'></td>
                        <td style='width:100px;height:auto;'><b>Message</b></td>
                        <td style='width:150px;height:auto;'><b>Time</b></td>
                        <td style='width:150px;height:auto;'><b>Email Sent</b></td>
                        <td style='width:150px;height:auto;'><b>SMS Sent</b></td>
                    </tr>";
    $data = create_pdf_alerthist($date, $type, $vehicleid, $checkpointid, $fenceid, $customerno);
    if ($data == '') {
        $finalreport .= "<tr><td colspan='5' style='text-align:center;'>No Data Available</td></tr>";
    } else {
        $finalreport .= $data;
    }
    $finalreport .= "</table>";
    echo $finalreport;
}

function create_pdf_alerthist($date, $type, $vehicleid, $checkpointid, $fenceid, $customerno) {
    include_once '../../lib/bo/ComQueueManager.php';
    $cqm = new ComQueueManager($customerno);
    $currentdate = date("d-m-Y");
    $i = 1;
    $data = '';
    if ($date == $currentdate) {
        $queues = $cqm->getalerthist($date, $type, $vehicleid, $checkpointid, $fenceid, $customerno);
        if (isset($queues)) {
            foreach ($queues as $queue) {
                if ($queue->processed == 1 && $queue->comtype == 0) {
                    $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>$queue->email</td><td>---</td></tr>";
                } elseif ($queue->processed == 1 && $queue->comtype == 1) {
                    $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>$queue->phone</td></tr>";
                } else {
                    $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>---</td></tr>";
                }
                $i++;
            }
        }
    } else {
        $dt = strtotime(date("Y-m-d", strtotime($date)));
        $file = date("MY", $dt);
        $location = "../../customer/" . $customerno . "/history/$file.sqlite";
        if (file_exists($location)) {
            $queues = getalerthist_sqlite($location, $date, $type, $vehicleid, $checkpointid, $fenceid);
            if (isset($queues)) {
                foreach ($queues as $queue) {
                    if ($queue->processed == 1) {
                        $historys = getcomhist_sqlite($location, $queue->cqid);
                        if (isset($historys)) {
                            foreach ($historys as $history) {
                                if ($history->comtype == 0) {
                                    $users = $cqm->getuserdetailss($history->userid);
                                    $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>$users->email</td><td>---</td></tr>";
                                    $i++;
                                } elseif ($history->comtype == 1) {
                                    $users = $cqm->getuserdetailss($history->userid);
                                    $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>$users->phone</td></tr>";
                                    $i++;
                                } elseif ($history->comtype == 1) {
                                    $users = $cqm->getuserdetailss($history->userid);
                                    $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>$users->phone</td></tr>";
                                    $i++;
                                }
                            }
                        }
                    } else {
                        $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>---</td></tr>";
                        $i++;
                    }
                }
            }
        }
    }
    return $data;
}

function getalertreportxls($date, $type, $vehicleid, $checkpointid, $fenceid, $vehicleno, $customerno, $alertmode, $switchto, $vgroupname = null) {
    $finalreport = '';
    $title = 'Alert History Report';
    if ($switchto == 3) {
        if (isset($_SESSION['Warehouse'])) {
            $veh = $_SESSION['Warehouse'];
        } else {
            $veh = "Warehouse";
        }
        $subTitle = array("Type : $alertmode", "$veh: $vehicleno", "Date: $date");
    } else {
        $subTitle = array("Type : $alertmode", "Vehicle No: $vehicleno", "Date: $date");
    }

    if (!is_null($vgroupname)) {
        $subTitle[] = "Group-name: $vgroupname";
    }

    $finalreport = excel_header($title, $subTitle);
    $finalreport .= "<table align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                    <tr style='background-color:#CCCCCC;'>
                        <td style='width:100px;height:auto;'></td>
                        <td style='width:100px;height:auto;'><b>Message</b></td>
                        <td style='width:150px;height:auto;'><b>Time</b></td>
                        <td style='width:150px;height:auto;'><b>Email Sent</b></td>
                        <td style='width:150px;height:auto;'><b>SMS Sent</b></td>
                    </tr>";
    $data = create_pdf_alerthist($date, $type, $vehicleid, $checkpointid, $fenceid, $customerno);
    if ($data == '') {
        $finalereport .= "<tr><td colspan='5' style='text-align:center;'>No Data Available</td></tr>";
    } else {
        $finalreport .= $data;
    }
    $finalreport .= "</table>";
    echo $finalreport;
}

function getalerthistTeam($date, $type, $vehicleid, $checkpointid, $fenceid, $customerno) {
    include_once '../../lib/bo/ComQueueManager.php';
    $cqm = new ComQueueManager($customerno);
    $currentdate = date("d-m-Y");
    //$currentdate = '16-01-2014';
    if ($date == $currentdate) {
        $data = '';
        $queues = $cqm->getalerthist($date, $type, $vehicleid, $checkpointid, $fenceid, $customerno);
        include_once '../reports/pages/panels/alerthistrep_team.php';
        $i = 1;
        if (isset($queues)) {
            foreach ($queues as $queue) {
                if ($queue->processed == 1 && $queue->comtype == 0) {
                    $data .= "<tr style='background:#FFE0CC;'><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>$queue->email</td><td>---</td></tr>";
                    $i++;
                } elseif ($queue->processed == 1 && $queue->comtype == 1) {
                    $data .= "<tr style='background:#FFE0CC;'><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>$queue->phone</td></tr>";
                    $i++;
                } else {
                    $data .= "<tr style='background:#FFE0CC;'><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>---</td></tr>";
                    $i++;
                }
            }
            $data .= '</body></table>';
        } else {
            $data .= "<tr style='background:#FFE0CC;'><td colspan='5'>No Data Available</td></tr>";
            $data .= '</body></table>';
        }
    } else {
        $data = '';
        $dt = strtotime(date("Y-m-d", strtotime($date)));
        $file = date("MY", $dt);
        $location = "../../customer/$customerno/history/$file.sqlite";
        if (file_exists($location)) {
            $queues = getalerthist_sqlite_team($location, $date, $type, $vehicleid, $checkpointid, $fenceid);
            include_once '../reports/pages/panels/alerthistrep_team.php';
            $i = 1;
            if (isset($queues)) {
                foreach ($queues as $queue) {
                    if ($queue->processed == 1) {
                        $historys = getcomhist_sqlite($location, $queue->cqid);
                        if (isset($historys)) {
                            foreach ($historys as $history) {
                                if ($history->comtype == 0) {
                                    $users = $cqm->getuserdetailss($history->userid);
                                    $data .= "<tr style='background:#FFE0CC;'><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>$users->email</td><td>---</td></tr>";
                                    $i++;
                                } elseif ($history->comtype == 1) {
                                    $users = $cqm->getuserdetailss($history->userid);
                                    $data .= "<tr style='background:#FFE0CC;'><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>$users->phone</td></tr>";
                                    $i++;
                                } elseif ($history->comtype == 1) {
                                    $users = $cqm->getuserdetailss($history->userid);
                                    $data .= "<tr style='background:#FFE0CC;'><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>$users->phone</td></tr>";
                                    $i++;
                                }
                            }
                        } else {
                            $data .= "<tr style='background:#FFE0CC;'><td colspan='5'>No Data Available</td></tr>";
                        }
                    } else {
                        $data .= "<tr style='background:#FFE0CC;'><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>---</td></tr>";
                        $i++;
                    }
                }
            } else {
                $data .= "<tr style='background:#FFE0CC;'><td colspan='5'>No Data Available</td></tr>";
            }
            $data .= '</body></table>';
        }
    }
    echo $data;
}

function getcomhist_sqlite($location, $cqid) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $queues = array();
    $query = "SELECT userid,comtype from comhistory where comqid = $cqid";
    $result = $db->query($query);
    if (isset($result) && $result != "") {
        foreach ($result as $row) {
            $Datacap = new stdClass();
            $Datacap->userid = $row['userid'];
            $Datacap->comtype = $row['comtype'];
            $queues[] = $Datacap;
        }
        return $queues;
    }
    return null;
}

function getalerthist_sqlite($location, $date, $type, $vehicleid, $checkpointid, $fenceid) {
    $newdate = date('Y-m-d', strtotime($date));
    $path = "sqlite:$location";
    $db = new PDO($path);
    $queues = array();
    switch ($type) {
        case '-1':
            {
                if ($vehicleid != '') {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                } else {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                }
            }
            break;
        case '2':
            {
                if ($vehicleid != '' && $checkpointid != '') {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND chkid = $checkpointid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                } elseif ($vehicleid == '' && $checkpointid != '') {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where type = $type AND chkid = $checkpointid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                } elseif ($vehicleid != '' && $checkpointid == '') {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                } else {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                }
            }
            break;
        case '3':
            {
                if ($vehicleid != '' && $fenceid != '') {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND fenceid = $fenceid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                } elseif ($vehicleid == '' && $fenceid != '') {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where type = $type AND fenceid = $fenceid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                } elseif ($vehicleid != '' && $fenceid == '') {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                } else {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                }
            }
            break;
        default:{
                if ($vehicleid != '') {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                } else {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                }
            }
            break;
    }
    $query .= "ORDER BY  `comqueue`.`timeadded` ASC ";
    //echo $query;
    $result = $db->query($query);
    if ($vehicleid == '' && $_SESSION['groupid'] != 0) {
        $gm = new GroupManager($_SESSION['customerno']);
        $groupedvehicles = $gm->getvehicleforgroup($_SESSION['groupid']);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                if (in_array($row['vehicleid'], $groupedvehicles)) {
                    $Datacap = new stdClass();
                    $Datacap->cqid = $row['cqid'];
                    $Datacap->timeadded = convertDateToFormat($row['timeadded'], speedConstants::DEFAULT_TIME);
                    $Datacap->message = $row['message'];
                    $Datacap->processed = $row['processed'];
                    $queues[] = $Datacap;
                }
            }
            return $queues;
        }
        return null;
    } else {
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $Datacap = new stdClass();
                $Datacap->cqid = $row['cqid'];
                $Datacap->timeadded = convertDateToFormat($row['timeadded'], speedConstants::DEFAULT_TIME);
                $Datacap->message = $row['message'];
                $Datacap->processed = $row['processed'];
                $queues[] = $Datacap;
            }
            return $queues;
        }
        return null;
    }
}

function getalerthist_sqlite_team($location, $date, $type, $vehicleid, $checkpointid, $fenceid) {
    $newdate = date('Y-m-d', strtotime($date));
    $path = "sqlite:$location";
    $db = new PDO($path);
    $queues = array();
    switch ($type) {
        case '-1':
            {
                if ($vehicleid != '') {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                } else {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                }
            }
            break;
        case '2':
            {
                if ($vehicleid != '' && $checkpointid != '') {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND chkid = $checkpointid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                } elseif ($vehicleid == '' && $checkpointid != '') {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where type = $type AND chkid = $checkpointid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                } elseif ($vehicleid != '' && $checkpointid == '') {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                } else {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                }
            }
            break;
        case '3':
            {
                if ($vehicleid != '' && $fenceid != '') {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND fenceid = $fenceid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                } elseif ($vehicleid == '' && $fenceid != '') {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where type = $type AND fenceid = $fenceid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                } elseif ($vehicleid != '' && $fenceid == '') {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                } else {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                }
            }
            break;
        default:{
                if ($vehicleid != '') {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                } else {
                    $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                }
            }
            break;
    }
    $query .= "ORDER BY  `comqueue`.`timeadded` ASC ";
    //echo $query;
    $result = $db->query($query);
    if (isset($result) && $result != "") {
        foreach ($result as $row) {
            $Datacap = new stdClass();
            $Datacap->cqid = $row['cqid'];
            $Datacap->timeadded = convertDateToFormat($row['timeadded'], speedConstants::DEFAULT_TIME);
            $Datacap->message = $row['message'];
            $Datacap->processed = $row['processed'];
            $queues[] = $Datacap;
        }
        return $queues;
    }
    return null;
}

function location($lat, $long, $usegeolocation, $customerno = null) {
    $address = null;
    $customerno = (!isset($customerno)) ? $_SESSION['customerno'] : $customerno;
    $GeoCoder_Obj = new GeoCoder($customerno);
    $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
    return $address;
}

function gettemptabularreport_fassos_cron($STdate, $EDdate, $deviceid, $interval, $stime, $etime, $customerno) {
    $_SESSION['temp_sensors'] = 4;
    $_SESSION['use_humidity'] = 0;
    $_SESSION['customerno'] = 177;
    $_SESSION['switch_to'] = 3;
    $_SESSION['use_ac_sensor'] = 0;
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $graph_days = array();
    $graph_days_ig = array();
    $veh_temp_details = array();
    $unit = getunitdetailspdf($customerno, $deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $graph_data = null;
            $STdate = date("Y-m-d", strtotime($STdate));
            if ($userdate == $STdate) {
                $f_STdate = $userdate . " " . $stime . ":00";
            } else {
                $f_STdate = $userdate . " 00:00:00";
            }
            $EDdate = date("Y-m-d", strtotime($EDdate));
            if ($userdate == $EDdate) {
                $f_EDdate = $userdate . " " . $etime . ":00";
            } else {
                $f_EDdate = $userdate . " 23:59:59";
            }
            $location = "";
            if (!empty($unit->unitno)) {
                $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            }
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $return = gettempdata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, true, $unit);
                $data = $return[0];
            }
            if ($data != null && count($data) > 1) {
                $days = array_merge($days, $data);
            }
        }
    }
    if ($days != null && count($days) > 0) {
        if (isset($return['vehicleid'])) {
            $veh_temp_details = getunitdetailsfromvehid($return['vehicleid'], $customerno);
        }
        $finalreport = create_temp_from_reportcron($days, $unit, $veh_temp_details);
    }
    return $finalreport;
}

function gettemptabularreport_daily_report_cron($STdate, $EDdate, $deviceid, $interval, $stime, $etime, $customerno, $temp_sensors) {
    $use_humidity = 0;
    $switch_to = 3;
    $use_ac_sensor = 0;
    $_SESSION['temp_sensors'] = $temp_sensors;
    $_SESSION['use_humidity'] = $use_humidity;
    $_SESSION['customerno'] = $customerno;
    $_SESSION['switch_to'] = $switch_to;
    $_SESSION['use_ac_sensor'] = $use_ac_sensor;

    $totaldays = gendays($STdate, $EDdate);
    //print_r($totaldays);
    $finalreport = '';
    $days = array();
    $graph_days = array();
    $graph_days_ig = array();
    $veh_temp_details = array();
    $unit = getunitdetailspdf($customerno, $deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $graph_data = null;
            $STdate = date("Y-m-d", strtotime($STdate));
            if ($userdate == $STdate) {
                $f_STdate = $userdate . " " . $stime . ":00";
            } else {
                $f_STdate = $userdate . " 00:00:00";
            }
            $EDdate = date("Y-m-d", strtotime($EDdate));
            if ($userdate == $EDdate) {
                $f_EDdate = $userdate . " " . $etime . ":00";
            } else {
                $f_EDdate = $userdate . " 23:59:59";
            }
            $location = "";
            if (!empty($unit->unitno)) {
                $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            }
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $return = gettempdata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, true, $unit);
                $data = $return[0];
            }
            if ($data != null && count($data) > 1) {
                $days = array_merge($days, $data);
            }
        }
    }
    if ($days != null && count($days) > 0) {
        if (isset($return['vehicleid'])) {
            $veh_temp_details = getunitdetailsfromvehid($return['vehicleid'], $customerno);
        }
        $finalreport = create_temp_from_reportcron_daily($days, $unit, $veh_temp_details, $temp_sensors);
    }
    return $finalreport;
}

function create_temp_from_reportcron($datarows, $unitdetails, $veh_temp_details = null) {
    $i = 0;
    $totalrow = 0;
    $tr_abv_max = 0;
    $tr2_abv_max = 0;
    $tr3_abv_max = 0;
    $tr4_abv_max = 0;
    $temp1_bad_reading = 0;
    $temp2_bad_reading = 0;
    $temp3_bad_reading = 0;
    $temp4_bad_reading = 0;
    $temp1_mute_reading = 0;
    $temp2_mute_reading = 0;
    $temp3_mute_reading = 0;
    $temp4_mute_reading = 0;
    $temp1_nonmute_reading = 0;
    $temp2_nonmute_reading = 0;
    $temp3_nonmute_reading = 0;
    $temp4_nonmute_reading = 0;
    $totalminute = 0;
    $lastdate = null;
    $temp1_data = "";
    $temp2_data = "";
    $temp3_data = "";
    $temp4_data = "";
    $display = '';
    $restemp1 = array(0);
    $restemp2 = array(0);
    $restemp3 = array(0);
    $restemp4 = array(0);
    $min_max_temp1 = get_min_max_temp(1, $veh_temp_details);
    $min_max_temp2 = get_min_max_temp(2, $veh_temp_details);
    $min_max_temp3 = get_min_max_temp(3, $veh_temp_details);
    $min_max_temp4 = get_min_max_temp(4, $veh_temp_details);
    $temp1counter = 0;
    $temp2counter = 0;
    $temp3counter = 0;
    $temp4counter = 0;
    $mutedetails = getunitmutedetails($unitdetails->vehicleid, $unitdetails->uid);
    $tempconversion = new TempConversion();
    $tempconversion->unit_type = $unitdetails->get_conversion;
    $tempconversion->use_humidity = $_SESSION['use_humidity'];
    $tempconversion->switch_to = $_SESSION['switch_to'];
    $temp1_min = $temp1_max = $temp2_min = $temp2_max = $temp3_min = $temp3_max = $temp4_min = $temp4_max = '';
    if (isset($datarows)) {
        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $i++;
            }
            //Removing Date Details From DateTime
            $change->lastupdated = $change->starttime;
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            $starttime_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
            // Temperature Sensor
            $temp = 'Not Active';
            $temp1 = 'Not Active';
            $temp2 = 'Not Active';
            $temp3 = 'Not Active';
            $temp4 = 'Not Active';
            $tdstring = '';
            switch ($_SESSION['temp_sensors']) {
                case 4:
                    if (isset($mutedetails)) {
                        foreach ($mutedetails as $rowdata) {
                            if ($rowdata->temp_type == $unitdetails->tempsen4 && strtotime($change->lastupdated) >= strtotime($rowdata->mute_starttime) && strtotime($change->lastupdated) <= strtotime($rowdata->mute_endtime)
                            ) {
                                $temp4 = 'Muted';
                                $temp4_mute_reading++;
                            }
                        }
                    }
                    if ($temp4 != 'Muted') {
                        $s4 = "analog" . $unitdetails->tempsen4;
                        if ($unitdetails->tempsen4 != 0 && $change->$s4 != 0) {
                            $tempconversion->rawtemp = $change->$s4;
                            $temp4 = getTempUtil($tempconversion);
                            if ($temp4 > NORMAL_MAX_TEMP || $temp4 < NORMAL_MIN_TEMP) {
                                $temp4_bad_reading++;
                            } else {
                                /* minimum temp */
                                $temp4_min = set_summary_min_temp4($temp4);
                                /* maximum temp */
                                $temp4_max = set_summary_max_temp4($temp4);
                                if ($temp4 > $min_max_temp4['temp_max_limit'] || $temp4 < $min_max_temp4['temp_min_limit']) {
                                    $tr4_abv_max++;
                                }
                            }
                        } else {
                            $temp4_bad_reading++;
                        }
                        $temp4_nonmute_reading++;
                    }

                case 3:
                    if (isset($mutedetails)) {
                        foreach ($mutedetails as $rowdata) {
                            if ($rowdata->temp_type == $unitdetails->tempsen3 && strtotime($change->lastupdated) >= strtotime($rowdata->mute_starttime) && strtotime($change->lastupdated) <= strtotime($rowdata->mute_endtime)
                            ) {
                                $temp3 = 'Muted';
                                $temp3_mute_reading++;
                            }
                        }
                    }
                    if ($temp3 != 'Muted') {
                        $s3 = "analog" . $unitdetails->tempsen3;
                        if ($unitdetails->tempsen3 != 0 && $change->$s3 != 0) {
                            $tempconversion->rawtemp = $change->$s3;
                            $temp3 = getTempUtil($tempconversion);
                            if ($temp3 > NORMAL_MAX_TEMP || $temp3 < NORMAL_MIN_TEMP) {
                                $temp3_bad_reading++;
                            } else {
                                /* minimum temp */
                                $temp3_min = set_summary_min_temp3($temp3);
                                /* maximum temp */
                                $temp3_max = set_summary_max_temp3($temp3);
                                if ($temp3 > $min_max_temp3['temp_max_limit'] || $temp3 < $min_max_temp3['temp_min_limit']) {
                                    $tr3_abv_max++;
                                }
                            }
                        } else {
                            $temp3_bad_reading++;
                        }
                        $temp3_nonmute_reading++;
                    }

                case 2:
                    if (isset($mutedetails)) {
                        foreach ($mutedetails as $rowdata) {
                            if ($rowdata->temp_type == $unitdetails->tempsen2 && strtotime($change->lastupdated) >= strtotime($rowdata->mute_starttime) && strtotime($change->lastupdated) <= strtotime($rowdata->mute_endtime)
                            ) {
                                $temp2 = 'Muted';
                                $temp2_mute_reading++;
                            }
                        }
                    }
                    if ($temp2 != 'Muted') {
                        $s2 = "analog" . $unitdetails->tempsen2;
                        if ($unitdetails->tempsen2 != 0 && $change->$s2 != 0) {
                            $tempconversion->rawtemp = $change->$s2;
                            $temp2 = getTempUtil($tempconversion);
                            if ($temp2 > NORMAL_MAX_TEMP || $temp2 < NORMAL_MIN_TEMP) {
                                $temp2_bad_reading++;
                            } else {
                                /* minimum temp */
                                $temp2_min = set_summary_min_temp2($temp2);
                                /* maximum temp */
                                $temp2_max = set_summary_max_temp2($temp2);
                                /* above max */
                                if ($temp2 > $min_max_temp2['temp_max_limit'] || $temp2 < $min_max_temp2['temp_min_limit']) {
                                    $tr2_abv_max++;
                                }
                            }
                        } else {
                            $temp2_bad_reading++;
                        }
                        $temp2_nonmute_reading++;
                    }

                case 1:
                    if (isset($mutedetails)) {
                        foreach ($mutedetails as $rowdata) {
                            if ($rowdata->temp_type == $unitdetails->tempsen1 && strtotime($change->lastupdated) >= strtotime($rowdata->mute_starttime) && strtotime($change->lastupdated) <= strtotime($rowdata->mute_endtime)
                            ) {
                                $temp1 = 'Muted';
                                $temp1_mute_reading++;
                            }
                        }
                    }
                    if ($temp1 != 'Muted') {
                        $s1 = "analog" . $unitdetails->tempsen1;
                        if ($unitdetails->tempsen1 != 0 && $change->$s1 != 0) {
                            $tempconversion->rawtemp = $change->$s1;
                            $temp1 = getTempUtil($tempconversion);
                            if ($temp1 > NORMAL_MAX_TEMP || $temp1 < NORMAL_MIN_TEMP) {
                                $temp1_bad_reading++;
                            } else {
                                /* min temp */
                                $temp1_min = set_summary_min_temp($temp1);
                                /* maximum temp */
                                $temp1_max = set_summary_max_temp($temp1);
                                /* above max */
                                if ($temp1 > $min_max_temp1['temp_max_limit'] || $temp1 < $min_max_temp1['temp_min_limit']) {
                                    $tr_abv_max++;
                                }
                            }
                        } else {
                            $temp1_bad_reading++;
                        }
                        $temp1_nonmute_reading++;
                    }
                    break;
            }
            $totalrow++;
        }
    }
    $t1 = getName_ByType($unitdetails->n1);
    if ($t1 == '') {
        $t1 = 'Temperature 1';
    }
    $t2 = getName_ByType($unitdetails->n2);
    if ($t2 == '') {
        $t2 = 'Temperature 2';
    }
    $t3 = getName_ByType($unitdetails->n3);
    if ($t3 == '') {
        $t3 = 'Temperature 3';
    }
    $t4 = getName_ByType($unitdetails->n4);
    if ($t4 == '') {
        $t4 = 'Temperature 4';
    }
    if ($_SESSION['temp_sensors'] == 4) {
        $temp1_data = array(
            'title' => $t1,
            'titleid' => $unitdetails->n1,
            'totalreading' => $totalrow,
            'totalnonmutereading' => $temp1_nonmute_reading,
            'totalabvcount' => $tr_abv_max,
            'unitno' => $unitdetails->unitno,
            'vehicleno' => $unitdetails->vehicleno,
            'mutedcount' => $temp1_mute_reading,
            'badcount' => $temp1_bad_reading
        );
        $temp2_data = array(
            'title' => $t2,
            'titleid' => $unitdetails->n2,
            'totalreading' => $totalrow,
            'totalnonmutereading' => $temp2_nonmute_reading,
            'totalabvcount' => $tr2_abv_max,
            'unitno' => $unitdetails->unitno,
            'vehicleno' => $unitdetails->vehicleno,
            'mutedcount' => $temp2_mute_reading,
            'badcount' => $temp2_bad_reading
        );
        $temp3_data = array(
            'title' => $t3,
            'titleid' => $unitdetails->n3,
            'totalreading' => $totalrow,
            'totalnonmutereading' => $temp3_nonmute_reading,
            'totalabvcount' => $tr3_abv_max,
            'unitno' => $unitdetails->unitno,
            'vehicleno' => $unitdetails->vehicleno,
            'mutedcount' => $temp3_mute_reading,
            'badcount' => $temp3_bad_reading
        );
        $temp4_data = array(
            'title' => $t4,
            'titleid' => $unitdetails->n4,
            'totalreading' => $totalrow,
            'totalnonmutereading' => $temp4_nonmute_reading,
            'totalabvcount' => $tr4_abv_max,
            'unitno' => $unitdetails->unitno,
            'vehicleno' => $unitdetails->vehicleno,
            'mutedcount' => $temp4_mute_reading,
            'badcount' => $temp4_bad_reading
        );
    }
    $compliancedata = array($temp1_data, $temp2_data, $temp3_data, $temp4_data);
    return $compliancedata;
}

function create_temp_from_reportcron_daily($datarows, $unitdetails, $veh_temp_details = null, $temp_sensors, $objReqData = null) {
    $i = 0;
    $totalrow = 0;
    $tr_abv_max = 0;
    $tr2_abv_max = 0;
    $tr3_abv_max = 0;
    $tr4_abv_max = 0;
    $temp1_bad_reading = 0;
    $temp2_bad_reading = 0;
    $temp3_bad_reading = 0;
    $temp4_bad_reading = 0;
    $temp1_mute_reading = 0;
    $temp2_mute_reading = 0;
    $temp3_mute_reading = 0;
    $temp4_mute_reading = 0;
    $temp1_nonmute_reading = 0;
    $temp2_nonmute_reading = 0;
    $temp3_nonmute_reading = 0;
    $temp4_nonmute_reading = 0;
    $totalminute = 0;
    $lastdate = null;
    $temp1_data = "";
    $temp2_data = "";
    $temp3_data = "";
    $temp4_data = "";
    $display = '';
    $restemp1 = array(0);
    $restemp2 = array(0);
    $restemp3 = array(0);
    $restemp4 = array(0);
    if (isset($objReqData) && isset($objReqData->customMinTemp) && isset($objReqData->customMaxTemp) && $objReqData->customMinTemp != '' && $objReqData->customMaxTemp != '' && $objReqData->customMinTemp != -1 && $objReqData->customMaxTemp != -1) {
        $min_max_temp1 = getCustomTempRange(1, $objReqData->customMinTemp, $objReqData->customMaxTemp);
        $min_max_temp2 = getCustomTempRange(2, $objReqData->customMinTemp, $objReqData->customMaxTemp);
        $min_max_temp3 = getCustomTempRange(3, $objReqData->customMinTemp, $objReqData->customMaxTemp);
        $min_max_temp4 = getCustomTempRange(4, $objReqData->customMinTemp, $objReqData->customMaxTemp);
    } else {
        $min_max_temp1 = get_min_max_temp(1, $veh_temp_details);
        $min_max_temp2 = get_min_max_temp(2, $veh_temp_details);
        $min_max_temp3 = get_min_max_temp(3, $veh_temp_details);
        $min_max_temp4 = get_min_max_temp(4, $veh_temp_details);
    }

    $temp1counter = 0;
    $temp2counter = 0;
    $temp3counter = 0;
    $temp4counter = 0;
    $mutedetails = getunitmutedetails($unitdetails->vehicleid, $unitdetails->uid);
    $tempconversion = new TempConversion();
    $tempconversion->unit_type = $unitdetails->get_conversion;
    $tempconversion->use_humidity = isset($_SESSION['use_humidity']) ? $_SESSION['use_humidity'] : 0;
    $tempconversion->switch_to = isset($_SESSION['switch_to']) ? $_SESSION['switch_to'] : 3;
    $temp1_min = $temp1_max = $temp2_min = $temp2_max = $temp3_min = $temp3_max = $temp4_min = $temp4_max = '';
    //echo "<pre>" ;print_r($datarows);
    if (isset($datarows)) {
        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $i++;
            }
            //Removing Date Details From DateTime
            $change->lastupdated = $change->starttime;
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            $starttime_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
            // Temperature Sensor
            $temp = 'Not Active';
            $temp1 = 'Not Active';
            $temp2 = 'Not Active';
            $temp3 = 'Not Active';
            $temp4 = 'Not Active';
            $tdstring = '';
            switch ($temp_sensors) {
                case 4:
                    if (isset($mutedetails)) {
                        foreach ($mutedetails as $rowdata) {
                            if ($rowdata->temp_type == $unitdetails->tempsen4 && strtotime($change->lastupdated) >= strtotime($rowdata->mute_starttime) && strtotime($change->lastupdated) <= strtotime($rowdata->mute_endtime)
                            ) {
                                $temp4 = 'Muted';
                                $temp4_mute_reading++;
                            }
                        }
                    }
                    if ($temp4 != 'Muted') {
                        $s4 = "analog" . $unitdetails->tempsen4;
                        if ($unitdetails->tempsen4 != 0 && $change->$s4 != 0) {
                            $tempconversion->rawtemp = $change->$s4;
                            $temp4 = getTempUtil($tempconversion);
                            if ($temp4 > NORMAL_MAX_TEMP || $temp4 < NORMAL_MIN_TEMP) {
                                $temp4_bad_reading++;
                            } else {
                                /* minimum temp */
                                $temp4_min = set_summary_min_temp4($temp4);
                                /* maximum temp */
                                $temp4_max = set_summary_max_temp4($temp4);
                                if ($temp4 > $min_max_temp4['temp_max_limit'] || $temp4 < $min_max_temp4['temp_min_limit']) {
                                    $tr4_abv_max++;
                                }
                            }
                        } else {
                            $temp4_bad_reading++;
                        }
                        $temp4_nonmute_reading++;
                    }

                case 3:
                    if (isset($mutedetails)) {
                        foreach ($mutedetails as $rowdata) {
                            if ($rowdata->temp_type == $unitdetails->tempsen3 && strtotime($change->lastupdated) >= strtotime($rowdata->mute_starttime) && strtotime($change->lastupdated) <= strtotime($rowdata->mute_endtime)
                            ) {
                                $temp3 = 'Muted';
                                $temp3_mute_reading++;
                            }
                        }
                    }
                    if ($temp3 != 'Muted') {
                        $s3 = "analog" . $unitdetails->tempsen3;
                        if ($unitdetails->tempsen3 != 0 && $change->$s3 != 0) {
                            $tempconversion->rawtemp = $change->$s3;
                            $temp3 = getTempUtil($tempconversion);
                            if ($temp3 > NORMAL_MAX_TEMP || $temp3 < NORMAL_MIN_TEMP) {
                                $temp3_bad_reading++;
                            } else {
                                /* minimum temp */
                                $temp3_min = set_summary_min_temp3($temp3);
                                /* maximum temp */
                                $temp3_max = set_summary_max_temp3($temp3);
                                if ($temp3 > $min_max_temp3['temp_max_limit'] || $temp3 < $min_max_temp3['temp_min_limit']) {
                                    $tr3_abv_max++;
                                }
                            }
                        } else {
                            $temp3_bad_reading++;
                        }
                        $temp3_nonmute_reading++;
                    }

                case 2:
                    if (isset($mutedetails)) {
                        foreach ($mutedetails as $rowdata) {
                            if ($rowdata->temp_type == $unitdetails->tempsen2 && strtotime($change->lastupdated) >= strtotime($rowdata->mute_starttime) && strtotime($change->lastupdated) <= strtotime($rowdata->mute_endtime)
                            ) {
                                $temp2 = 'Muted';
                                $temp2_mute_reading++;
                            }
                        }
                    }
                    if ($temp2 != 'Muted') {
                        $s2 = "analog" . $unitdetails->tempsen2;
                        if ($unitdetails->tempsen2 != 0 && $change->$s2 != 0) {
                            $tempconversion->rawtemp = $change->$s2;
                            $temp2 = getTempUtil($tempconversion);
                            if ($temp2 > NORMAL_MAX_TEMP || $temp2 < NORMAL_MIN_TEMP) {
                                $temp2_bad_reading++;
                            } else {
                                /* minimum temp */
                                $temp2_min = set_summary_min_temp2($temp2);
                                /* maximum temp */
                                $temp2_max = set_summary_max_temp2($temp2);
                                /* above max */
                                if ($temp2 > $min_max_temp2['temp_max_limit'] || $temp2 < $min_max_temp2['temp_min_limit']) {
                                    $tr2_abv_max++;
                                }
                            }
                        } else {
                            $temp2_bad_reading++;
                        }
                        $temp2_nonmute_reading++;
                    }

                case 1:
                    if (isset($mutedetails)) {
                        foreach ($mutedetails as $rowdata) {
                            if ($rowdata->temp_type == $unitdetails->tempsen1 && strtotime($change->lastupdated) >= strtotime($rowdata->mute_starttime) && strtotime($change->lastupdated) <= strtotime($rowdata->mute_endtime)
                            ) {
                                $temp1 = 'Muted';
                                $temp1_mute_reading++;
                            }
                        }
                    }
                    if ($temp1 != 'Muted') {
                        $s1 = "analog" . $unitdetails->tempsen1;
                        if ($unitdetails->tempsen1 != 0 && $change->$s1 != 0) {
                            $tempconversion->rawtemp = $change->$s1;
                            $temp1 = getTempUtil($tempconversion);
                            if ($temp1 > NORMAL_MAX_TEMP || $temp1 < NORMAL_MIN_TEMP) {
                                $temp1_bad_reading++;
                            } else {
                                /* min temp */
                                $temp1_min = set_summary_min_temp($temp1);
                                /* maximum temp */
                                $temp1_max = set_summary_max_temp($temp1);
                                /* above max */
                                if ($temp1 > $min_max_temp1['temp_max_limit'] || $temp1 < $min_max_temp1['temp_min_limit']) {
                                    $tr_abv_max++;
                                }
                            }
                        } else {
                            $temp1_bad_reading++;
                        }
                        $temp1_nonmute_reading++;
                    }
                    break;
            }
            $totalrow++;
        }
    }

    $t1 = getName_ByType($unitdetails->n1);
    if ($t1 == '') {
        $t1 = 'Temperature 1';
    }
    $t2 = getName_ByType($unitdetails->n2);
    if ($t2 == '') {
        $t2 = 'Temperature 2';
    }
    $t3 = getName_ByType($unitdetails->n3);
    if ($t3 == '') {
        $t3 = 'Temperature 3';
    }
    $t4 = getName_ByType($unitdetails->n4);
    if ($t4 == '') {
        $t4 = 'Temperature 4';
    }
    switch ($temp_sensors) {
        case 4:
            $temp4_data = array(
                'title' => $t4,
                'titleid' => $unitdetails->n4,
                'totalreading' => $totalrow,
                'totalnonmutereading' => $temp4_nonmute_reading,
                'totalabvcount' => $tr_abv_max,
                'unitno' => $unitdetails->unitno,
                'vehicleno' => $unitdetails->vehicleno,
                'mutedcount' => $temp4_mute_reading,
                'badcount' => $temp4_bad_reading,
                'temp_max' => $temp4_max,
                'temp_min' => $temp4_min
            );

        case 3:
            $temp3_data = array(
                'title' => $t3,
                'titleid' => $unitdetails->n3,
                'totalreading' => $totalrow,
                'totalnonmutereading' => $temp3_nonmute_reading,
                'totalabvcount' => $tr3_abv_max,
                'unitno' => $unitdetails->unitno,
                'vehicleno' => $unitdetails->vehicleno,
                'mutedcount' => $temp3_mute_reading,
                'badcount' => $temp3_bad_reading,
                'temp_max' => $temp3_max,
                'temp_min' => $temp3_min
            );

        case 2:
            $temp2_data = array(
                'title' => $t2,
                'titleid' => $unitdetails->n2,
                'totalreading' => $totalrow,
                'totalnonmutereading' => $temp2_nonmute_reading,
                'totalabvcount' => $tr2_abv_max,
                'unitno' => $unitdetails->unitno,
                'vehicleno' => $unitdetails->vehicleno,
                'mutedcount' => $temp2_mute_reading,
                'badcount' => $temp2_bad_reading,
                'temp_max' => $temp2_max,
                'temp_min' => $temp2_min
            );

        case 1:
            $temp1_data = array(
                'title' => $t1,
                'titleid' => $unitdetails->n1,
                'totalreading' => $totalrow,
                'totalnonmutereading' => $temp1_nonmute_reading,
                'totalabvcount' => $tr_abv_max,
                'unitno' => $unitdetails->unitno,
                'vehicleno' => $unitdetails->vehicleno,
                'mutedcount' => $temp1_mute_reading,
                'badcount' => $temp1_bad_reading,
                'temp_max' => $temp1_max,
                'temp_min' => $temp1_min
            );

            break;
    }
    if (!empty($temp1_data)) {
        $compliancedata[] = $temp1_data;
    }
    if (!empty($temp2_data)) {
        $compliancedata[] = $temp2_data;
    }
    if (!empty($temp3_data)) {
        $compliancedata[] = $temp3_data;
    }
    if (!empty($temp4_data)) {
        $compliancedata[] = $temp4_data;
    }
    $compliancedata = array($temp1_data, $temp2_data);

    //echo"<pre>"; print_r($compliancedata); die();
    return $compliancedata;
}

function getRouteList($customerno) {
    $objRouteManager = new RouteManager($customerno);
    $CustomerRoutes = $objRouteManager->get_route_fromcustomer($customerno);
    return $CustomerRoutes;
}

function gettempreportpdf_cron($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $interval, $stime, $etime, $switchto, $vgroupname = null, $reporttype = null) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $um = new UnitManager($customerno);
    $unit = getunitdetailspdf_cron($customerno, $deviceid);
    $unitno = $unit[0]->unitno;
    if (isset($totaldays) && $unitno != ' ') {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $STdate = date("Y-m-d", strtotime($STdate));
            $f_STdate = $userdate . " 00:00:00";
            if ($userdate == $STdate) {
                $f_STdate = $userdate . " " . $stime . ":00";
            }
            $EDdate = date("Y-m-d", strtotime($EDdate));
            $f_EDdate = $userdate . " 23:59:59";
            if ($userdate == $EDdate) {
                $f_EDdate = $userdate . " " . $etime . ":00";
            }

            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = gettempdata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, false, null, $customerno);
                $vehicleid = $data['vehicleid'];
            }
            if ($data[0] != null && count($data[0]) > 1) {
                $days = array_merge($days, $data[0]);
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $cm = new CustomerManager($customerno);
        $customer_details = $cm->getcustomerdetail_byid($customerno);

        $finalreport .= "<hr /><br/><br/>
        <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
        <tbody>";
        $veh_temp_details = '';
        if (isset($vehicleid)) {
            $veh_temp_details = getunitdetailsfromvehid($vehicleid, $customerno);
            $veh_temp_details->use_humidity = $customer_details->use_humidity;
        }
        $finalreport .= create_temppdf_from_report_cron($days, $unit, $customerno, $veh_temp_details, $switchto);
    }

    return $finalreport;
}

function create_temppdf_from_report_cron($datarows, $vehicle, $custID = null, $veh_temp_details = null, $switchto = null) {
    $i = 1;
    $tr = 0;
    $totalminute = 0;
    $lastdate = null;
    $display = '';
    $temp1_non_comp_count = 0;
    $temp2_non_comp_count = 0;
    $temp3_non_comp_count = 0;
    $temp4_non_comp_count = 0;
    $temp1_bad_reading = 0;
    $temp2_bad_reading = 0;
    $temp3_bad_reading = 0;
    $temp4_bad_reading = 0;
    $temp1_mute_reading = 0;
    $temp2_mute_reading = 0;
    $temp3_mute_reading = 0;
    $temp4_mute_reading = 0;
    $temp1_nonmute_reading = 0;
    $temp2_nonmute_reading = 0;
    $temp3_nonmute_reading = 0;
    $temp4_nonmute_reading = 0;
    $temp1_data = '';
    $temp2_data = '';
    $temp3_data = '';
    $temp4_data = '';
    $restemp1 = array(0);
    $restemp2 = array(0);
    $restemp3 = array(0);
    $restemp4 = array(0);
    $temp1_min = '';
    $temp2_min = '';
    $temp3_min = '';
    $temp4_min = '';
    $temp1_max = '';
    $temp2_max = '';
    $temp3_max = '';
    $temp4_max = '';
    $min_max_temp1 = get_min_max_temp(1, $veh_temp_details);
    $min_max_temp2 = get_min_max_temp(2, $veh_temp_details);
    $min_max_temp3 = get_min_max_temp(3, $veh_temp_details);
    $min_max_temp4 = get_min_max_temp(4, $veh_temp_details);
    $temp1counter = 0;
    $temp2counter = 0;
    $temp3counter = 0;
    $temp4counter = 0;

    $mutedetails = getunitmutedetails($vehicle[0]->vehicleid, $vehicle[0]->uid, $custID);
    if (isset($datarows)) {
        $customerno = ($custID == null) ? $_SESSION['customerno'] : $custID;
        $cm = new CustomerManager(null);
        $cm_details = $cm->getcustomerdetail_byid($customerno);
        $min_max_temp1 = get_min_max_temp(1, $veh_temp_details, $cm_details->temp_sensors);
        $min_max_temp2 = get_min_max_temp(2, $veh_temp_details, $cm_details->temp_sensors);
        $min_max_temp3 = get_min_max_temp(3, $veh_temp_details, $cm_details->temp_sensors);
        $min_max_temp4 = get_min_max_temp(4, $veh_temp_details, $cm_details->temp_sensors);
        $t1 = getName_ByType($vehicle[0]->n1);
        $t2 = getName_ByType($vehicle[0]->n2);
        $t3 = getName_ByType($vehicle[0]->n3);
        $t4 = getName_ByType($vehicle[0]->n4);
        $t1 = ($t1 == '') ? 'Temperature 1' : $t1;
        $t2 = ($t2 == '') ? 'Temperature 2' : $t2;
        $t3 = ($t3 == '') ? 'Temperature 3' : $t3;
        $t4 = ($t4 == '') ? 'Temperature 4' : $t4;
        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $display .= "
                    <tr style='background-color:#CCCCCC;font-weight:bold;'>
                    <td style='width:150px;height:auto;'>Time</td>";
                if ($switchto != 3) {
                    $display .= "<td style='width:250px;height:auto;'>Location</td>";
                }
                if ($cm_details->temp_sensors == 4) {
                    $display .= "<td style='width:150px;height:auto;'>" . $t1 . "</td>
                    <td style='width:150px;height:auto;'>" . $t2 . "</td>
                    <td style='width:150px;height:auto;'>" . $t3 . "</td>
                    <td style='width:150px;height:auto;'>" . $t4 . "</td>";
                }
                if ($cm_details->temp_sensors == 3) {
                    $display .= "<td style='width:150px;height:auto;'>Temperature 1</td>
                    <td style='width:150px;height:auto;'>Temperature 2</td>
                    <td style='width:150px;height:auto;'>Temperature 3</td>";
                }
                if ($cm_details->temp_sensors == 2) {
                    $display .= "<td style='width:150px;height:auto;'>" . $t1 . "</td>
                    <td style='width:150px;height:auto;'>" . $t2 . "</td>";
                } elseif ($cm_details->temp_sensors == 1) {
                    $display .= "<td style='width:150px;height:auto;'>Temperature</td>";
                }
                $display .= "</tr>";
                if ($cm_details->temp_sensors == 4) {
                    $display .= "<tr><td colspan='6' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                }
                if ($cm_details->temp_sensors == 3) {
                    $display .= "<tr><td colspan='5' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                }
                if ($cm_details->temp_sensors == 2) {
                    $display .= "<tr><td colspan='3' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                } elseif ($cm_details->temp_sensors == 1) {
                    $display .= "<tr><td colspan='3' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                }
                $i++;
            }
            //Removing Date Details From DateTimespeedConstants::DEFAULT_TIME
            $change->lastupdated = $change->starttime;
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            if ($customerno == 116) {
                $display .= "<tr><td style='width:150px;height:auto;'>" . date(speedConstants::DEFAULT_DATETIME, strtotime($change->lastupdated)) . "</td>";
            } else {
                $display .= "<tr><td style='width:150px;height:auto;'>" . date(speedConstants::DEFAULT_TIME, strtotime($change->starttime)) . "</td>";
            }
            $location = get_location_detail($change->devicelat, $change->devicelong, $custID);
            if ($switchto != 3) {
                $display .= "<td style='width:250px;height:auto;'>$location</td>";
            }
            // Temperature Sensors
            // Temperature Sensor
            $temp1 = $temp2 = $temp3 = $temp4 = speedConstants::TEMP_WARNING;
            $warning = '-';
            $tdstring = '';
            $objTemp = new TempConversion();
            $objTemp->unit_type = $veh_temp_details->get_conversion;
            $objTemp->use_humidity = $veh_temp_details->use_humidity;
            $objTemp->switch_to = $switchto;
            switch ($cm_details->temp_sensors) {
                case 4:
                    if ($vehicle[0]->tempsen4 != 0) {
                        $isTemperatureMuted = checkTemperatureMute($mutedetails, 4, $change->lastupdated);
                        if ($isTemperatureMuted) {
                            $temp4 = 'Muted';
                            $temp4_mute_reading++;
                        } else {
                            $temp4_nonmute_reading++;
                            $s4 = "analog" . $vehicle[0]->tempsen4;
                            if ($change->$s4 != 0) {
                                $objTemp->rawtemp = $change->$s4;
                                $temp4 = getTempUtil($objTemp);
                                if ($temp4 == 0 || $temp4 > NORMAL_MAX_TEMP || $temp4 < NORMAL_MIN_TEMP) {
                                    if ($temp4 == 0) {
                                        $temp4 = 'Wirecut';
                                    } else {
                                        $temp4 = 'Bad Data';
                                    }
                                    $temp4_bad_reading++;
                                } elseif ($temp4 != 0) {
                                    $temp4_min = set_summary_min_temp4($temp4);
                                    $temp4_max = set_summary_max_temp4($temp4);
                                    if ($temp4 > $min_max_temp4['temp_max_limit'] || $temp4 < $min_max_temp4['temp_min_limit']) {
                                        $temp4_non_comp_count++;
                                    }
                                    $temp4 = $temp4 . speedConstants::TEMP_DEGREE;
                                }
                            } else {
                                $temp4_bad_reading++;
                            }
                        }
                    } else {
                        $temp4 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $tdstring = "<td>" . $temp4 . "</td>" . $tdstring;
                case 3:
                    if ($vehicle[0]->tempsen3 != 0) {
                        $isTemperatureMuted = checkTemperatureMute($mutedetails, 3, $change->lastupdated);
                        if ($isTemperatureMuted) {
                            $temp3 = 'Muted';
                            $temp3_mute_reading++;
                        } else {
                            $temp3_nonmute_reading++;
                            $s3 = "analog" . $vehicle[0]->tempsen3;
                            if ($change->$s3 != 0) {
                                $objTemp->rawtemp = $change->$s3;
                                $temp3 = getTempUtil($objTemp);
                                if ($temp3 == 0 || $temp3 > NORMAL_MAX_TEMP || $temp3 < NORMAL_MIN_TEMP) {
                                    if ($temp3 == 0) {
                                        $temp3 = 'Wirecut';
                                    } else {
                                        $temp3 = 'Bad Data';
                                    }
                                    $temp3_bad_reading++;
                                } elseif ($temp3 != 0) {
                                    $temp3_min = set_summary_min_temp3($temp3);
                                    $temp3_max = set_summary_max_temp3($temp3);
                                    if ($temp3 > $min_max_temp3['temp_max_limit'] || $temp3 < $min_max_temp3['temp_min_limit']) {
                                        $temp3_non_comp_count++;
                                    }
                                    $temp3 = $temp3 . speedConstants::TEMP_DEGREE;
                                }
                            } else {
                                $temp3_bad_reading++;
                            }
                        }
                    } else {
                        $temp3 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $tdstring = "<td>" . $temp3 . "</td>" . $tdstring;
                case 2:
                    if ($vehicle[0]->tempsen2 != 0) {
                        $isTemperatureMuted = checkTemperatureMute($mutedetails, 2, $change->lastupdated);
                        if ($isTemperatureMuted) {
                            $temp2 = 'Muted';
                            $temp2_mute_reading++;
                        } else {
                            $temp2_nonmute_reading++;
                            $s2 = "analog" . $vehicle[0]->tempsen2;
                            if ($change->$s2 != 0) {
                                $objTemp->rawtemp = $change->$s2;
                                $temp2 = getTempUtil($objTemp);
                                if ($temp2 == 0 || $temp2 > NORMAL_MAX_TEMP || $temp2 < NORMAL_MIN_TEMP) {
                                    if ($temp2 == 0) {
                                        $temp2 = 'Wirecut';
                                    } else {
                                        $temp2 = 'Bad Data';
                                    }
                                    $temp2_bad_reading++;
                                } elseif ($temp2 != 0) {
                                    $temp2_min = set_summary_min_temp2($temp2);
                                    $temp2_max = set_summary_max_temp2($temp2);
                                    if ($temp2 > $min_max_temp2['temp_max_limit'] || $temp2 < $min_max_temp2['temp_min_limit']) {
                                        $temp2_non_comp_count++;
                                    }
                                    $temp2 = $temp2 . speedConstants::TEMP_DEGREE;
                                }
                            } else {
                                $temp2_bad_reading++;
                            }
                        }
                    } else {
                        $temp2 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $tdstring = "<td>" . $temp2 . "</td>" . $tdstring;
                case 1:
                    if ($vehicle[0]->tempsen1 != 0) {
                        $isTemperatureMuted = checkTemperatureMute($mutedetails, 1, $change->lastupdated);
                        if ($isTemperatureMuted) {
                            $temp1 = 'Muted';
                            $temp1_mute_reading++;
                        } else {
                            $temp1_nonmute_reading++;
                            $s1 = "analog" . $vehicle[0]->tempsen1;
                            if ($change->$s1 != 0) {
                                $objTemp->rawtemp = $change->$s1;
                                $temp1 = getTempUtil($objTemp);
                                if ($temp1 == 0 || $temp1 > NORMAL_MAX_TEMP || $temp1 < NORMAL_MIN_TEMP) {
                                    if ($temp1 == 0) {
                                        $temp1 = 'Wirecut';
                                    } else {
                                        $temp1 = 'Bad Data';
                                    }
                                    $temp1_bad_reading++;
                                } elseif ($temp1 != 0) {
                                    $temp1_min = set_summary_min_temp($temp1);
                                    $temp1_max = set_summary_max_temp($temp1);
                                    if ($temp1 > $min_max_temp1['temp_max_limit'] || $temp1 < $min_max_temp1['temp_min_limit']) {
                                        $temp1_non_comp_count++;
                                    }
                                    $temp1 = $temp1 . speedConstants::TEMP_DEGREE;
                                }
                            } else {
                                $temp1_bad_reading++;
                            }
                        }
                    } else {
                        $temp1 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $tdstring = "<td>" . $temp1 . "</td>" . $tdstring;
            }
            $display .= $tdstring;
            $display .= '</tr>';
            $tr++;
        }
    }
    $display .= '</tbody>';
    $display .= '</table><br><br>';
    $temphtml = '';
    $span = null;
    switch ($cm_details->temp_sensors) {
        case 4:
            $span = isset($span) ? $span : 4;
            $goodcount = $temp4_nonmute_reading - $temp4_bad_reading;
            if ($temp4_nonmute_reading == 0 || $goodcount == 0) {
                $abv_compliance = "Not Applicable";
                $within_compliance = "Not Applicable";
            } else {
                $abv_compliance = round(($temp4_non_comp_count / $goodcount) * 100, 2);
                $compliance_count = $goodcount - $temp4_non_comp_count;
                $within_compliance = round($compliance_count / $goodcount * 100, 2);
            }
            $temphtml = "
        <td style='text-align:center;'>
            <table class='table newTable'><thead><tr><th><u>" . $t4 . "</u></th></tr></thead>
                <tbody>
                    <tr><td>Temprature Range :" . $min_max_temp4['temp_min_limit'] . " &deg;C to " . $min_max_temp4['temp_max_limit'] . " &deg;C</td></tr>
                    <tr><td>Minimum Temperature: $temp4_min &deg;C</td></tr>
                    <tr><td>Maximum Temperature: $temp4_max &deg;C</td></tr>
                    <tr><td>Total Reading: $tr</td></tr>
                    <tr><td>Non compliance readings : $temp4_non_comp_count</td></tr>
                    <tr><td>Bad readings : $temp4_bad_reading</td></tr>
                    <tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                    <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
                </td>";
        case 3:
            $span = isset($span) ? $span : 3;
            $goodcount = $temp3_nonmute_reading - $temp3_bad_reading;
            if ($temp3_nonmute_reading == 0 || $goodcount == 0) {
                $abv_compliance = "Not Applicable";
                $within_compliance = "Not Applicable";
            } else {
                $abv_compliance = round(($temp3_non_comp_count / $goodcount) * 100, 2);
                $compliance_count = $goodcount - $temp3_non_comp_count;
                $within_compliance = round($compliance_count / $goodcount * 100, 2);
            }
            $temphtml = "<td style='text-align:center;'>
        <table class='table newTable'><thead><tr><th><u>" . $t3 . "</u></th></tr></thead>
            <tbody>
                <tr><td>Temprature Range :" . $min_max_temp3['temp_min_limit'] . " &deg;C to " . $min_max_temp3['temp_max_limit'] . " &deg;C</td></tr>
                <tr><td>Minimum Temperature: $temp3_min &deg;C</td></tr>
                <tr><td>Maximum Temperature: $temp3_max &deg;C</td></tr>
                <tr><td>Total Reading: $tr</td></tr>
                <tr><td>Non compliance readings : $temp3_non_comp_count</td></tr>
                <tr><td>Bad readings : $temp3_bad_reading</td></tr>
                <tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
            </td>" . $temphtml;
        case 2:
            $span = isset($span) ? $span : 2;
            $goodcount = $temp2_nonmute_reading - $temp2_bad_reading;
            if ($temp2_nonmute_reading == 0 || $goodcount == 0) {
                $abv_compliance = "Not Applicable";
                $within_compliance = "Not Applicable";
            } else {
                $abv_compliance = round(($temp2_non_comp_count / $goodcount) * 100, 2);
                $compliance_count = $goodcount - $temp2_non_comp_count;
                $within_compliance = round($compliance_count / $goodcount * 100, 2);
            }
            $temphtml = "<td style='text-align:center;'>
        <table class='table newTable'><thead><tr><th><u>" . $t2 . "</u></th></tr></thead>
            <tbody>
                <tr><td>Temprature Range :" . $min_max_temp2['temp_min_limit'] . " &deg;C to " . $min_max_temp2['temp_max_limit'] . " &deg;C</td></tr>
                <tr><td>Minimum Temperature: $temp2_min &deg;C</td></tr>
                <tr><td>Maximum Temperature: $temp2_max &deg;C</td></tr>
                <tr><td>Total Reading: $tr</td></tr>
                <tr><td> Non compliance readings : $temp2_non_comp_count</td></tr>
                <tr><td>Bad readings : $temp2_bad_reading</td></tr>
                <tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
            </td>" . $temphtml;
        case 1:
            $span = isset($span) ? $span : 1;
            $goodcount = $temp1_nonmute_reading - $temp1_bad_reading;
            if ($temp4_nonmute_reading == 0 || $goodcount == 0) {
                $abv_compliance = "Not Applicable";
                $within_compliance = "Not Applicable";
            } else {
                $abv_compliance = round(($temp1_non_comp_count / $goodcount) * 100, 2);
                $compliance_count = $goodcount - $temp1_non_comp_count;
                $within_compliance = round($compliance_count / $goodcount * 100, 2);
            }
            $temphtml = "<td style='text-align:center;'>
        <table class='table newTable'><thead>
            <tr><th><u>" . $t1 . "</u></th></tr></thead>
            <tbody>
                <tr><td>Temprature Range :" . $min_max_temp1['temp_min_limit'] . " &deg;C to " . $min_max_temp1['temp_max_limit'] . " &deg;C</td></tr>
                <tr><td>Minimum Temperature: $temp1_min &deg;C</td></tr>
                <tr><td>Maximum Temperature: $temp1_max &deg;C</td></tr>
                <tr><td>Total Reading: $tr</td></tr>
                <tr><td>Non compliance readings: $temp1_non_comp_count</td></tr>
                <tr><td>Bad readings : $temp1_bad_reading</td></tr>
                <tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
            </td>" . $temphtml;
            break;
    }
    return $display;
}

/*
function getGroup($params=array('customerno'=>'')){
$query = "select u.userid, u.realname, m.groupid, u.roleid, u.role "
. "from user as u inner join groupman as m on u.userid=m.userid "
. "where u.customerno = (%d) and u.isdeleted=0 and m.isdeleted=0";

$result = $db->query($query, $params['customerno']);
print_r($result);

if (isset($result) && $result != "") {
foreach ($result as $row) {
$Datacap = new stdClass();
$Datacap->cqid = $row['cqid'];
$Datacap->timeadded = convertDateToFormat($row['timeadded'], speedConstants::DEFAULT_TIME);
$Datacap->message = $row['message'];
$Datacap->processed = $row['processed'];
$queues[] = $Datacap;
}
return $queues;
}

 */

function getTripData($STdate, $EDdate, $deviceid, $interval, $stime, $etime, $customerno, $temp_sensors) {
    $use_humidity = 0;
    $switch_to = 3;
    $use_ac_sensor = 0;
    $_SESSION['temp_sensors'] = $temp_sensors;
    $_SESSION['use_humidity'] = $use_humidity;
    $_SESSION['customerno'] = $customerno;
    $_SESSION['switch_to'] = $switch_to;
    $_SESSION['use_ac_sensor'] = $use_ac_sensor;

    $totaldays = gendays($STdate, $EDdate);
    // print_r($totaldays);
    $finalreport = '';
    $days = array();
    $graph_days = array();
    $graph_days_ig = array();
    $veh_temp_details = array();
    $unit = getunitdetailspdf($customerno, $deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $graph_data = null;
            $STdate = date("Y-m-d", strtotime($STdate));
            if ($userdate == $STdate) {
                $stime = date('H:i:s', strtotime($stime));
                $f_STdate = $userdate . " " . $stime;
            } else {
                $f_STdate = $userdate . " 00:00:00";
            }
            $EDdate = date("Y-m-d", strtotime($EDdate));
            if ($userdate == $EDdate) {
                $etime = date('H:i:s', strtotime($etime));
                $f_EDdate = $userdate . " " . $etime;
            } else {
                $f_EDdate = $userdate . " 23:59:59";
            }
            $location = "";
            if (!empty($unit->unitno)) {
                $location = "../../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            }
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $return = gettempdata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, true, $unit);
                $data = $return[0];
            }
            if ($data != null && count($data) > 1) {
                $days = array_merge($days, $data);
            }
        }
    }

    return $days;
}

function calculateComplaiance($finalreport) {
    $temp1 = array();
    $temp2 = array();
    $temp1_compliance_count_store = $temp1_compliance_percent_store = $temp2_compliance_count_store = $temp2_compliance_percent_store = '';
    if (isset($finalreport)) {
        $storemessage = '';
        foreach ($finalreport as $temp_comp_data_for_warehouse) {
            if (is_array($temp_comp_data_for_warehouse) || is_object($temp_comp_data_for_warehouse)) {
                foreach ($temp_comp_data_for_warehouse as $temp_comp_detail) {
                    //print_r($temp_comp_detail);
                    $unitno = $temp_comp_detail['unitno'];
                    if ($temp_comp_detail['title'] == 'Temperature 1') {
                        $temp1[$unitno] = array(
                            'nonComplianceCount' => $temp_comp_detail['totalabvcount'],
                            'goodCount' => $temp_comp_detail['totalnonmutereading'] - $temp_comp_detail['badcount'],
                            'totalCount' => $temp_comp_detail['totalreading'],
                            'badCount' => $temp_comp_detail['badcount'],
                            'mutedCount' => $temp_comp_detail['mutedcount'],
                            'temp_max' => $temp_comp_detail['temp_max'],
                            'temp_min' => $temp_comp_detail['temp_min']
                        );
                        $goodCounttemp1store = $temp1[$unitno]['goodCount'];
                        $badcounttemp1store = $temp1[$unitno]['badCount'];
                        $totalcounttemp1store = $temp1[$unitno]['totalCount'];
                        $mutedtemp1store = $temp1[$unitno]['mutedCount'];

                        $temp1_compliance_count_store = $temp1[$unitno]['goodCount'] - $temp1[$unitno]['nonComplianceCount'];
                        if ($temp1[$unitno]['goodCount'] != 0) {
                            $temp1_compliance_percent_store = round($temp1_compliance_count_store / $temp1[$unitno]['goodCount'] * 100, 2);
                        } else {
                            $temp1_compliance_percent_store = 0;
                        }
                    }

                    if ($temp_comp_detail['title'] == 'Temperature 2') {
                        $temp2[$unitno] = array(
                            'nonComplianceCount' => $temp_comp_detail['totalabvcount'],
                            'goodCount' => $temp_comp_detail['totalnonmutereading'] - $temp_comp_detail['badcount'],
                            'totalCount' => $temp_comp_detail['totalreading'],
                            'badCount' => $temp_comp_detail['badcount'],
                            'mutedCount' => $temp_comp_detail['mutedcount'],
                            'temp_max' => $temp_comp_detail['temp_max'],
                            'temp_min' => $temp_comp_detail['temp_min']
                        );
                        $goodCounttemp2store = $temp2[$unitno]['goodCount'];
                        $badcounttemp2store = $temp2[$unitno]['badCount'];
                        $totalcounttemp2store = $temp2[$unitno]['totalCount'];
                        $mutedtemp2store = $temp2[$unitno]['mutedCount'];
                        $temp2_compliance_count_store = $temp2[$unitno]['goodCount'] - $temp2[$unitno]['nonComplianceCount'];
                        if ($temp2[$unitno]['goodCount'] != 0) {
                            $temp2_compliance_percent_store = round($temp2_compliance_count_store / $temp2[$unitno]['goodCount'] * 100, 2);
                        } else {
                            $temp2_compliance_percent_store = 0;
                        }
                    }
                }
            }
        }
    }

    $withtemp1 = $withtemp2 = '';
    $goodCounttemp1 = $goodCounttemp2 = '';
    $totalcounttemp1 = $badcounttemp2 = '';
    $noncompliancecounttemp1 = $noncompliancecounttemp2 = '';
    $temp1_compliance_percent = $temp2_compliance_percent = '';
    $compliancecounttemp1 = $compliancecounttemp2 = '';
    $temp_maxtemp1 = $temp_maxtemp2 = '0';
    $temp_mintemp1 = $temp_mintemp2 = '0';
    $totalcounttemp1 = $totalcounttemp2 = 0;
    //print_r($temp1);
    if (!empty($temp1)) {
        //echo '42 - Inside';
        $totalcounttemp1 = array_sum_byproperty($temp1, 'totalCount');
        $badcounttemp1 = array_sum_byproperty($temp1, 'badCount');
        $goodCounttemp1 = array_sum_byproperty($temp1, 'goodCount');
        $mutedcounttemp1 = array_sum_byproperty($temp1, 'mutedCount');
        $numbermax = array_map(function ($details) {
            return $details['temp_max'];
        }, $temp1);
        $numbermin = array_map(function ($details) {
            return $details['temp_min'];
        }, $temp1);

        $temp_maxtemp1 = max($numbermax);
        $temp_mintemp1 = min($numbermin);
        if ($goodCounttemp1 > 0) {
            $noncompliancecounttemp1 = array_sum_byproperty($temp1, 'nonComplianceCount');
            $compliancecounttemp1 = $goodCounttemp1 - $noncompliancecounttemp1;
            $temp1_compliance_percent = round($compliancecounttemp1 / $goodCounttemp1 * 100, 2);
            $temp1_noncompliance_percent = (100 - $temp1_compliance_percent);
        } else {
            $temp1_compliance_percent = "Not Applicable";
            $temp1_noncompliance_percent = "Not Applicable";
        }
    }
    if (!empty($temp2)) {
        // 41  - Gate
        $totalcounttemp2 = array_sum_byproperty($temp2, 'totalCount');
        $badcounttemp2 = array_sum_byproperty($temp2, 'badCount');
        $goodCounttemp2 = array_sum_byproperty($temp2, 'goodCount');
        $mutedcounttemp2 = array_sum_byproperty($temp2, 'mutedCount');
        $numbermax = array_map(function ($details) {
            return $details['temp_max'];
        }, $temp2);
        $numbermin = array_map(function ($details) {
            return $details['temp_min'];
        }, $temp2);

        $temp_maxtemp2 = max($numbermax);
        $temp_mintemp2 = min($numbermin);
        if ($goodCounttemp2 > 0) {
            $noncompliancecounttemp2 = array_sum_byproperty($temp2, 'nonComplianceCount');
            $compliancecounttemp2 = $goodCounttemp2 - $noncompliancecounttemp2;
            $temp2_compliance_percent = round($compliancecounttemp2 / $goodCounttemp2 * 100, 2);
            $temp2_noncompliance_percent = (100 - $temp2_compliance_percent);
        } else {
            $temp2_compliance_percent = "Not Applicable";
            $temp2_noncompliance_percent = "Not Applicable";
        }
    }

    if ($temp_maxtemp1 != 0 && $temp_maxtemp2 != 0) {
        $array_max_temp = array($temp_maxtemp1, $temp_maxtemp2);
        $temp_maxtemp1_temp2 = max($array_max_temp);
    } elseif ($temp_maxtemp1 == 0 && $temp_maxtemp2 != 0) {
        $temp_maxtemp1_temp2 = $temp_maxtemp2;
    } elseif ($temp_maxtemp1 != 0 && $temp_maxtemp2 == 0) {
        $temp_maxtemp1_temp2 = $temp_maxtemp1;
    }

    if ($temp_mintemp1 != 0 && $temp_mintemp2 != 0) {
        $array_min_temp = array($temp_mintemp1, $temp_mintemp2);
        $temp_mintemp1_temp2 = min($array_min_temp);
    } elseif ($temp_mintemp1 == 0 && $temp_mintemp2 != 0) {
        $temp_mintemp1_temp2 = $temp_mintemp2;
    } elseif ($temp_mintemp1 != 0 && $temp_mintemp2 == 0) {
        $temp_mintemp1_temp2 = $temp_mintemp1;
    }
    $totalcounttemp1_temp2 = $totalcounttemp1 + $totalcounttemp2;
    if (is_numeric($noncompliancecounttemp1) && is_numeric($noncompliancecounttemp2)) {
        $noncompliancecounttemp1_temp2 = $noncompliancecounttemp1 + $noncompliancecounttemp2;
    }
    /*
    $goodCounttemp1_temp2 = $goodCounttemp1 + $goodCounttemp2;
    if (is_numeric($compliancecounttemp1) && is_numeric($compliancecounttemp2)) {
    $compliancecounttemp1_temp2 = $compliancecounttemp1 + $compliancecounttemp2;
    }
    if ($goodCounttemp1_temp2) {
    if (isset($compliancecounttemp1_temp2)) {
    $temp1_temp2_compliance_percent = round($compliancecounttemp1_temp2 / $goodCounttemp1_temp2 * 100, 2);
    }
    } else {
    if (isset($compliancecounttemp1_temp2)) {
    $temp1_temp2_compliance_percent = round($compliancecounttemp1_temp2, 2);
    }
    }
    if (isset($temp1_temp2_compliance_percent)) {
    $temp1_temp2_noncompliance_percent = (100 - $temp1_temp2_compliance_percent);
    } else {
    $temp1_temp2_compliance_percent = NULL;
    } */

//    if (is_numeric($compliancecounttemp1) && is_numeric($compliancecounttemp2)) {
    //        $compliancecounttemp1_temp2 = $compliancecounttemp1 + $compliancecounttemp2;
    //    }
    if (isset($goodCounttemp1)
        && isset($compliancecounttemp1)
        && $goodCounttemp1 > 0
        && $compliancecounttemp1 !== ''
    ) {
        $temp1_compliance_percent = round($compliancecounttemp1 / $goodCounttemp1 * 100, 2);
        $temp1_noncompliance_percent = (100 - $temp1_compliance_percent);
    } else {
        $temp1_compliance_percent = NULL;
    }
    //$complianaceData = array("compliance" => $temp1_temp2_compliance_percent, "nonCompliance" => $temp1_temp2_noncompliance_percent);
    $complianaceData = $temp1_compliance_percent;
    return $complianaceData;
}

function calculateOdometerDistance($startOdometer, $endOdometer) {
    $distance = 0;
    $distance = (($endOdometer - $startOdometer) / 1000);
    return $distance;
}

/**/
//error_reporting(E_NOTICE^E_ALL);

function updateSqlite($column, $editval, $id, $date, $vehicleid, $uid) {
    //$STdate, $vehicleid) {

    //$totaldays = gendays($STdate, $EDdate);
    $unitno = getunitnotemp($vehicleid);

    $get_conversion = get_conversion($unitno);

    $customerno = $_SESSION['customerno']; ///  loged User Number//
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";

    $DATA = null;
    if (file_exists($location)) {
        $tempData = array();

        if (isset($unitno) && !empty($unitno)) {
            $tempconversion = new TempConversion();
            $tempconversion->unit_type = $get_conversion;
            $tempconversion->use_humidity = $_SESSION['use_humidity'];
            $tempconversion->switch_to = $_SESSION['switch_to'];
            //$s = "analog" . $vehicle->humidity;
            $tempconversion->rawtemp = $editval;
            $analog = getAnalogUtil($tempconversion);

            $path = "sqlite:$location";

            $db = new PDO($path);

            $Query = "update unithistory set "
                . "$column='" . $analog
                . "' where uhid=" . $id;
            //echo
            $SQL = sprintf($Query);

            $db->exec('BEGIN IMMEDIATE TRANSACTION');
            $db->exec($SQL);
            $db->exec('COMMIT TRANSACTION');
            //return $msg = "Data Updated Successfully";
        }
    }

    //return $DATA;
}

////////////////////////////////////////this function is specific for Nestle 473 //////////////////////////////////////
function create_temphtml_from_report_with_noncom_reading($datarows, $vehicle, $veh_temp_details = null, $interval = 120, $tempselect = null) {
    $i = 1;
    $tr = 0;
    $totalminute = 0;
    $lastdate = null;
    $display = '';
    $goodcount = 0;
    $temp1_non_comp_count = 0;
    $temp2_non_comp_count = 0;
    $temp3_non_comp_count = 0;
    $temp4_non_comp_count = 0;
    $temp1_bad_reading = 0;
    $temp2_bad_reading = 0;
    $temp3_bad_reading = 0;
    $temp4_bad_reading = 0;
    $temp1_mute_reading = 0;
    $temp2_mute_reading = 0;
    $temp3_mute_reading = 0;
    $temp4_mute_reading = 0;
    $temp1_nonmute_reading = 0;
    $temp2_nonmute_reading = 0;
    $temp3_nonmute_reading = 0;
    $temp4_nonmute_reading = 0;
    $temp1_data = "";
    $temp2_data = "";
    $temp3_data = "";
    $temp4_data = "";
    $restemp1 = array(0);
    $restemp2 = array(0);
    $restemp3 = array(0);
    $restemp4 = array(0);
    $temp1_min = '';
    $temp2_min = '';
    $temp3_min = '';
    $temp4_min = '';
    $temp1_max = '';
    $temp2_max = '';
    $temp3_max = '';
    $temp4_max = '';
    $min_max_temp1 = get_min_max_temp(1, $veh_temp_details);
    $min_max_temp2 = get_min_max_temp(2, $veh_temp_details);
    $min_max_temp3 = get_min_max_temp(3, $veh_temp_details);
    $min_max_temp4 = get_min_max_temp(4, $veh_temp_details);
    $temp1counter = 0;
    $temp2counter = 0;
    $temp3counter = 0;
    $temp4counter = 0;
    $temp1totalreadingcount = 0;
    $temp2totalreadingcount = 0;
    $temp3totalreadingcount = 0;
    $temp4totalreadingcount = 0;

    $non_complaince_check1 = 1;
    $non_complaince_check2 = 1;
    $non_complaince_check3 = 1;
    $non_complaince_check4 = 1;

    $mutedetails = getunitmutedetails($vehicle->vehicleid, $vehicle->uid, $_SESSION['customerno']);
    if (isset($datarows)) {
        foreach ($datarows as $change) {
            /* Don't show the entire log of 1 min data when customer pulls out this interval for compliance only */
            if ($interval != "1") {
                $comparedate = date('d-m-Y', strtotime($change->endtime));
                $today = date('d-m-Y', strtotime('Now'));
                if (strtotime($lastdate) != strtotime($comparedate)) {
                    if ($today == $comparedate) {
                        $todays = date('Y-m-d');
                        $todayhms = date('Y-m-d H:i:s');
                        $to_time = strtotime("$todayhms");
                        $from_time = strtotime("$todays 00:00:00");
                        $totalminute = round(abs($to_time - $from_time) / 60, 2);
                    } else {
                        $count = $i;
                    }
                    $display .= "<tr><th align='center' colspan = '100%' style='background:#d8d5d6'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                    $lastdate = date('d-m-Y', strtotime($change->endtime));
                    $i++;
                }
                $change->lastupdated = $change->starttime;
                $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
                $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
                if ($_SESSION['customerno'] == 116) {
                    $starttime_disp = date(speedConstants::DEFAULT_DATETIME, strtotime($change->lastupdated));
                } else {
                    $starttime_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
                }
                $display .= "<tr><td>$starttime_disp</td>";
                $location = get_location_detail($change->devicelat, $change->devicelong);
                if ($_SESSION['switch_to'] != 3) {
                    $display .= "<td>$location</td>";
                }
            }

            $temp1 = $temp2 = $temp3 = $temp4 = speedConstants::TEMP_WARNING;
            $tdstring = '';
            $objTemp = new TempConversion();
            $objTemp->unit_type = $veh_temp_details->get_conversion;
            $objTemp->use_humidity = $_SESSION['use_humidity'];
            $objTemp->switch_to = $_SESSION['switch_to'];
            $arr = explode("-", $tempselect);

            $pass = (isset($arr[0]) ? $arr[0] : $_SESSION['temp_sensors']);
            // switch ($_SESSION['temp_sensors']) {

            switch ($pass) {
                case 4:
                    if ($vehicle->tempsen4 != 0) {
                        $isTemperatureMuted = checkTemperatureMute($mutedetails, 4, $change->lastupdated);
                        if ($isTemperatureMuted) {
                            $temp4 = 'Muted';
                            $temp4_mute_reading++;
                        } else {
                            $temp4_nonmute_reading++;
                            $s4 = "analog" . $vehicle->tempsen4;
                            if ($change->$s4 != 0) {
                                $objTemp->rawtemp = $change->$s4;
                                $temp4 = getTempUtil($objTemp);
                                if ($temp4 == 0 || $temp4 > NORMAL_MAX_TEMP || $temp4 < NORMAL_MIN_TEMP) {
                                    if ($temp4 == 0) {
                                        $temp4 = 'Wirecut';
                                    } else {
                                        $temp4 = 'Bad Data';
                                    }
                                    $temp4_bad_reading++;
                                } elseif ($temp4 != 0) {
                                    $temp4_min = set_summary_min_temp4($temp4);
                                    $temp4_max = set_summary_max_temp4($temp4);
                                    if ($temp4 > $min_max_temp4['temp_max_limit'] || $temp4 < $min_max_temp4['temp_min_limit']) {
                                        if ($non_complaince_check4 == 5) {
                                            //echo "hello i reached".$non_complaince_check; //die;
                                            // $temp4_non_comp_count++;
                                            $temp4_non_comp_count = $temp4_non_comp_count + 5;
                                            $non_complaince_check4 = 1;
                                        } else {
                                            //echo "<br>temp is ".$temp1." else ".$non_complaince_check."<br>";
                                            $non_complaince_check4++;
                                        }

                                        //$temp1_non_comp_count++;
                                        //echo "check non compliance - ".$non_complaince_check."<br>";
                                        //echo "count non compliance - ".$temp1_non_comp_count."<br>";
                                    } else {
                                        $non_complaince_check4 = 1;
                                    }
                                    /*$temp4_non_comp_count++;
                                    }*/
                                    $temp4 = $temp4 . speedConstants::TEMP_DEGREE;
                                }
                            } else {
                                $temp4_bad_reading++;
                            }
                        }
                        $temp4totalreadingcount++;
                    } else {
                        $temp4 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $tdstring = "<td>" . $temp4 . "</td>" . $tdstring;
                    //// checked condition comming from session or tempselect(from select box)
                    if ((isset($arr[0]) && $arr[0] == 4) && $arr[1] != "all") {
                        break;
                    }
                case 3:
                    if ($vehicle->tempsen3 != 0) {
                        $isTemperatureMuted = checkTemperatureMute($mutedetails, 3, $change->lastupdated);
                        if ($isTemperatureMuted) {
                            $temp3 = 'Muted';
                            $temp3_mute_reading++;
                        } else {
                            $temp3_nonmute_reading++;
                            $s3 = "analog" . $vehicle->tempsen3;
                            if ($change->$s3 != 0) {
                                $objTemp->rawtemp = $change->$s3;
                                $temp3 = getTempUtil($objTemp);

                                if ($temp3 == 0 || $temp3 > NORMAL_MAX_TEMP || $temp3 < NORMAL_MIN_TEMP) {
                                    if ($temp3 == 0) {
                                        $temp3 = 'Wirecut';
                                    } else {
                                        $temp3 = 'Bad Data';
                                    }
                                    $temp3_bad_reading++;
                                } elseif ($temp3 != 0) {
                                    $temp3_min = set_summary_min_temp3($temp3);
                                    $temp3_max = set_summary_max_temp3($temp3);
                                    if ($temp3 > $min_max_temp3['temp_max_limit'] || $temp3 < $min_max_temp3['temp_min_limit']) {
                                        if ($non_complaince_check3 == 5) {
                                            //echo "hello i reached".$non_complaince_check; //die;
                                            // $temp3_non_comp_count++;
                                            $temp3_non_comp_count = $temp3_non_comp_count + 5;
                                            $non_complaince_check3 = 1;
                                        } else {
                                            //echo "<br>temp is ".$temp1." else ".$non_complaince_check."<br>";
                                            $non_complaince_check3++;
                                        }
                                    } else {
                                        $non_complaince_check3 = 1;
                                    }
                                    /*$temp3_non_comp_count++;
                                    }*/
                                    $temp3 = $temp3 . speedConstants::TEMP_DEGREE;
                                }
                            } else {
                                $temp3_bad_reading++;
                            }
                        }
                        $temp3totalreadingcount++;
                    } else {
                        $temp3 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $tdstring = "<td>" . $temp3 . "</td>" . $tdstring;
                    //// checked condition comming from session or tempselect(from select box)
                    if ((isset($arr[0]) && $arr[0] == 3) && $arr[1] != "all") {
                        break;
                    }
                case 2:
                    if ($vehicle->tempsen2 != 0) {
                        $isTemperatureMuted = checkTemperatureMute($mutedetails, 2, $change->lastupdated);
                        if ($isTemperatureMuted) {
                            $temp2 = 'Muted';
                            $temp2_mute_reading++;
                        } else {
                            $temp2_nonmute_reading++;
                            $s2 = "analog" . $vehicle->tempsen2;
                            if ($change->$s2 != 0) {
                                $objTemp->rawtemp = $change->$s2;
                                $temp2 = getTempUtil($objTemp);

                                if ($temp2 == 0 || $temp2 > NORMAL_MAX_TEMP || $temp2 < NORMAL_MIN_TEMP) {
                                    if ($temp2 == 0) {
                                        $temp2 = 'Wirecut';
                                    } else {
                                        $temp2 = 'Bad Data';
                                    }
                                    $temp2_bad_reading++;
                                } elseif ($temp2 != 0) {
                                    $temp2_min = set_summary_min_temp2($temp2);
                                    $temp2_max = set_summary_max_temp2($temp2);
                                    if ($temp2 > $min_max_temp2['temp_max_limit'] || $temp2 < $min_max_temp2['temp_min_limit']) {
                                        if ($non_complaince_check2 == 5) {
                                            //echo "hello i reached".$non_complaince_check; //die;
                                            // $temp2_non_comp_count++;
                                            $temp2_non_comp_count = $temp2_non_comp_count + 5;
                                            $non_complaince_check2 = 1;
                                        } else {
                                            //echo "<br>temp is ".$temp1." else ".$non_complaince_check."<br>";
                                            $non_complaince_check2++;
                                        }
                                    } else {
                                        $non_complaince_check2 = 1;
                                    }
                                    /*$temp2_non_comp_count++;
                                    }*/
                                    $temp2 = $temp2 . speedConstants::TEMP_DEGREE;
                                }
                            } else {
                                $temp2_bad_reading++;
                            }
                        }
                        $temp2totalreadingcount++;
                    } else {
                        $temp2 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $tdstring = "<td>" . $temp2 . "</td>" . $tdstring;
                    //// checked condition comming from session or tempselect(from select box)
                    if ((isset($arr[0]) && $arr[0] == 2) && $arr[1] != "all") {
                        break;
                    }
                case 1:
                    if ($vehicle->tempsen1 != 0) {
                        $isTemperatureMuted = checkTemperatureMute($mutedetails, 1, $change->lastupdated);
                        if ($isTemperatureMuted) {
                            $temp1 = 'Muted';
                            $temp1_mute_reading++;
                        } else {
                            $temp1_nonmute_reading++;
                            $s1 = "analog" . $vehicle->tempsen1;
                            if ($change->$s1 != 0) {
                                $objTemp->rawtemp = $change->$s1;
                                $temp1 = getTempUtil($objTemp);
                                // echo "temperature - ".$temp1."<br>";
                                if ($temp1 == 0 || $temp1 > NORMAL_MAX_TEMP || $temp1 < NORMAL_MIN_TEMP) {
                                    if ($temp1 == 0) {
                                        $temp1 = 'Wirecut';
                                    } else {
                                        $temp1 = 'Bad Data';
                                    }
                                    $temp1_bad_reading++;
                                } elseif ($temp1 != 0) {
                                    $temp1_min = set_summary_min_temp($temp1);
                                    $temp1_max = set_summary_max_temp($temp1);
                                    // $non_complaince_check = 0;
                                    if ($temp1 > $min_max_temp1['temp_max_limit'] || $temp1 < $min_max_temp1['temp_min_limit']) {
                                        if ($non_complaince_check1 == 5) {
                                            //echo "hello i reached".$non_complaince_check; //die;
                                            // $temp1_non_comp_count++;
                                            $temp1_non_comp_count = $temp1_non_comp_count + 5;
                                            $non_complaince_check1 = 1;
                                        } else {
                                            //echo "<br>temp is ".$temp1." else ".$non_complaince_check."<br>";
                                            $non_complaince_check1++;
                                        }

                                        //$temp1_non_comp_count++;
                                        //echo "check non compliance - ".$non_complaince_check."<br>";
                                        //echo "count non compliance - ".$temp1_non_comp_count."<br>";
                                    } else {
                                        $non_complaince_check1 = 1;
                                    }
                                    $temp1 = $temp1 . speedConstants::TEMP_DEGREE;
                                }
                            } else {
                                $temp1_bad_reading++;
                            }
                        }
                        $temp1totalreadingcount++;
                    } else {
                        $temp1 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $tdstring = "<td>" . $temp1 . "</td>" . $tdstring;
            }
            /* Don't show the entire log of 1 min data when customer pulls out this interval for compliance only */
            if ($interval != "1") {
                $display .= $tdstring;
            }
            if ($_SESSION['use_ac_sensor'] == 1) {
                if ($vehicle->acsensor == 1) {
                    if ($change->digitalio == 0 && $vehicle->isacopp == 0) {
                        $display .= "<td>On</td>";
                    } elseif ($change->digitalio == 0 && $vehicle->isacopp == 1) {
                        $display .= "<td>Off</td>";
                    } elseif ($change->digitalio == 1 && $vehicle->isacopp == 0) {
                        $display .= "<td>Off</td>";
                    } elseif ($change->digitalio == 1 && $vehicle->isacopp == 1) {
                        $display .= "<td>On</td>";
                    }
                } else {
                    $display .= "<td>Not Active</td>";
                }
            }
            $display .= '</tr>';
            $tr++;
        }
    }

    // echo "total count - ".$temp1_non_comp_count;
    $display .= '</tbody>';
    $display .= '</table>';
    $t1 = getName_ByType($vehicle->n1);
    $t2 = getName_ByType($vehicle->n2);
    $t3 = getName_ByType($vehicle->n3);
    $t4 = getName_ByType($vehicle->n4);
    $t1 = ($t1 == '') ? 'Temperature 1' : $t1;
    $t2 = ($t2 == '') ? 'Temperature 2' : $t2;
    $t3 = ($t3 == '') ? 'Temperature 3' : $t3;
    $t4 = ($t4 == '') ? 'Temperature 4' : $t4;
    $temphtml = '';
    $span = null;

    switch ($pass) {
        case 4:
            $span = isset($span) ? $span : 4;
            $goodcount = $temp4_nonmute_reading - $temp4_bad_reading;
            if ($temp4_nonmute_reading == 0 || $goodcount == 0) {
                $abv_compliance = "Not Applicable";
                $within_compliance = "Not Applicable";
            } else {
                $abv_compliance = round(($temp4_non_comp_count / $goodcount) * 100, 2);
                $compliance_count = $goodcount - $temp4_non_comp_count;
                $within_compliance = round($compliance_count / $goodcount * 100, 2);
            }
            $temp4_min = ($temp4_min != '' ? $temp4_min . " &deg;C" : "N/A");
            $temp4_max = ($temp4_max != '' ? $temp4_max . " &deg;C" : "N/A");
            $temphtml = "<td style='text-align:center;'>
        <table class='table newTable'><thead><tr><th><u>" . $t4 . "</u></th></tr></thead>
            <tbody>
                <tr><td>Temperature Range :" . $min_max_temp4['temp_min_limit'] . " &deg;C to " . $min_max_temp4['temp_max_limit'] . " &deg;C</td></tr>
                <tr><td>Minimum Temperature: $temp4_min</td></tr>
                <tr><td>Maximum Temperature: $temp4_max</td></tr>
                <tr><td>Total Reading: $temp4totalreadingcount</td></tr>
                <tr><td>Muted Reading: $temp4_mute_reading</td></tr>
                <tr><td>Non compliance readings : $temp4_non_comp_count</td></tr>
                <tr><td>Bad readings : $temp4_bad_reading</td></tr>
                <tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
            </td>";
            //// checked condition comming from session or tempselect(from select box)
            if ((isset($arr[0]) && $arr[0] == 4) && $arr[1] != "all") {
                break;
            }
        case 3:
            $span = isset($span) ? $span : 3;
            $goodcount = $temp3_nonmute_reading - $temp3_bad_reading;
            if ($temp3_nonmute_reading == 0 || $goodcount == 0) {
                $abv_compliance = "Not Applicable";
                $within_compliance = "Not Applicable";
            } else {
                $abv_compliance = round(($temp3_non_comp_count / $goodcount) * 100, 2);
                $compliance_count = $goodcount - $temp3_non_comp_count;
                $within_compliance = round($compliance_count / $goodcount * 100, 2);
            }
            $temp3_min = ($temp3_min != '' ? $temp3_min . " &deg;C" : "N/A");
            $temp3_max = ($temp3_max != '' ? $temp3_max . " &deg;C" : "N/A");
            $temphtml = "<td style='text-align:center;'>
        <table class='table newTable'><thead><tr><th><u>" . $t3 . "</u></th></tr></thead>
            <tbody>
                <tr><td>Temperature Range :" . $min_max_temp3['temp_min_limit'] . " &deg;C to " . $min_max_temp3['temp_max_limit'] . " &deg;C</td></tr>
                <tr><td>Minimum Temperature: $temp3_min</td></tr>
                <tr><td>Maximum Temperature: $temp3_max</td></tr>
                <tr><td>Total Reading: $temp3totalreadingcount</td></tr>
                <tr><td>Muted Reading: $temp3_mute_reading</td></tr>
                <tr><td>Non compliance readings : $temp3_non_comp_count</td></tr>
                <tr><td>Bad readings : $temp3_bad_reading</td></tr>
                <tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
            </td>" . $temphtml;
            //// checked condition comming from session or tempselect(from select box)
            if ((isset($arr[0]) && $arr[0] == 3) && $arr[1] != "all") {
                break;
            }
        case 2:
            $span = isset($span) ? $span : 2;
            $goodcount = $temp2_nonmute_reading - $temp2_bad_reading;
            if ($temp2_nonmute_reading == 0 || $goodcount == 0) {
                $abv_compliance = "Not Applicable";
                $within_compliance = "Not Applicable";
            } else {
                $abv_compliance = round(($temp2_non_comp_count / $goodcount) * 100, 2);
                $compliance_count = $goodcount - $temp2_non_comp_count;
                $within_compliance = round($compliance_count / $goodcount * 100, 2);
            }
            $temp2_min = ($temp2_min != '' ? $temp2_min . " &deg;C" : "N/A");
            $temp2_max = ($temp2_max != '' ? $temp2_max . " &deg;C" : "N/A");
            $temphtml = "<td style='text-align:center;'>
        <table class='table newTable'><thead><tr><th><u>" . $t2 . "</u></th></tr></thead>
            <tbody>
                <tr><td>Temperature Range :" . $min_max_temp2['temp_min_limit'] . " &deg;C to " . $min_max_temp2['temp_max_limit'] . " &deg;C</td></tr>
                <tr><td>Minimum Temperature: $temp2_min</td></tr>
                <tr><td>Maximum Temperature: $temp2_max</td></tr>
                <tr><td>Total Reading: $temp2totalreadingcount</td></tr>
                <tr><td>Muted Reading: $temp2_mute_reading</td></tr>
                <tr><td> Non compliance readings : $temp2_non_comp_count</td></tr>
                <tr><td>Bad readings : $temp2_bad_reading</td></tr>
                <tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
            </td>" . $temphtml;
            //// checked condition comming from session or tempselect(from select box)
            if ((isset($arr[0]) && $arr[0] == 2) && $arr[1] != "all") {
                break;
            }
        case 1:
            $span = isset($span) ? $span : 1;
            $goodcount = $temp1_nonmute_reading - $temp1_bad_reading;
            if ($temp1_nonmute_reading == 0 || $goodcount == 0) {
                $abv_compliance = "Not Applicable";
                $within_compliance = "Not Applicable";
            } else {
                // echo "<br> total count ". $temp1_non_comp_count." and good count is ". $goodcount;
                $abv_compliance = round(($temp1_non_comp_count / $goodcount) * 100, 2);
                $compliance_count = $goodcount - $temp1_non_comp_count;
                $within_compliance = round($compliance_count / $goodcount * 100, 2);
            }
            $temp1_min = ($temp1_min != '' ? $temp1_min . " &deg;C" : "N/A");
            $temp1_max = ($temp1_max != '' ? $temp1_max . " &deg;C" : "N/A");
            $temphtml = "<td style='text-align:center;'>
        <table class='table newTable'><thead>
            <tr><th><u>" . $t1 . "</u></th></tr></thead>
            <tbody>
                <tr><td>Temperature Range :" . $min_max_temp1['temp_min_limit'] . " &deg;C to " . $min_max_temp1['temp_max_limit'] . " &deg;C</td></tr>
                <tr><td>Minimum Temperature: $temp1_min</td></tr>
                <tr><td>Maximum Temperature: $temp1_max</td></tr>
                <tr><td>Total Reading: $temp1totalreadingcount</td></tr>
                <tr><td>Muted Reading: $temp1_mute_reading</td></tr>
                <tr><td>Non compliance readings: $temp1_non_comp_count</td></tr>
                <tr><td>Bad readings : $temp1_bad_reading</td></tr>
                <tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
            </td>" . $temphtml;
    }
    $summary = "<table class='table newTable'>
                    <thead>
                        <tr><th colspan=$span>Statistics</th></tr>
                    </thead>
                    <tbody>
                        <tr> $temphtml</tr>
                    </tbody>
                </table>";
    $display .= "$summary</div>";
    return $display;
}

/////////////////

function create_temppdf_from_report_with_noncom_reading($datarows, $vehicle, $custID = null, $veh_temp_details = null, $switchto = null, $tempselect = null) {
    $i = 1;
    $tr = 0;
    $totalminute = 0;
    $lastdate = null;
    $display = '';
    $temp1_non_comp_count = 0;
    $temp2_non_comp_count = 0;
    $temp3_non_comp_count = 0;
    $temp4_non_comp_count = 0;
    $temp1_bad_reading = 0;
    $temp2_bad_reading = 0;
    $temp3_bad_reading = 0;
    $temp4_bad_reading = 0;
    $temp1_mute_reading = 0;
    $temp2_mute_reading = 0;
    $temp3_mute_reading = 0;
    $temp4_mute_reading = 0;
    $temp1_nonmute_reading = 0;
    $temp2_nonmute_reading = 0;
    $temp3_nonmute_reading = 0;
    $temp4_nonmute_reading = 0;
    $temp1_data = '';
    $temp2_data = '';
    $temp3_data = '';
    $temp4_data = '';
    $restemp1 = array(0);
    $restemp2 = array(0);
    $restemp3 = array(0);
    $restemp4 = array(0);
    $temp1_min = '';
    $temp2_min = '';
    $temp3_min = '';
    $temp4_min = '';
    $temp1_max = '';
    $temp2_max = '';
    $temp3_max = '';
    $temp4_max = '';
    $min_max_temp1 = get_min_max_temp(1, $veh_temp_details);
    $min_max_temp2 = get_min_max_temp(2, $veh_temp_details);
    $min_max_temp3 = get_min_max_temp(3, $veh_temp_details);
    $min_max_temp4 = get_min_max_temp(4, $veh_temp_details);
    $temp1counter = 0;
    $temp2counter = 0;
    $temp3counter = 0;
    $temp4counter = 0;

    $non_complaince_check1 = 1;
    $non_complaince_check2 = 1;
    $non_complaince_check3 = 1;
    $non_complaince_check4 = 1;

    $mutedetails = getunitmutedetails($vehicle->vehicleid, $vehicle->uid, $custID);
    if (isset($datarows)) {
        $customerno = ($custID == null) ? $_SESSION['customerno'] : $custID;
        $cm = new CustomerManager(null);
        $cm_details = $cm->getcustomerdetail_byid($customerno);
        $min_max_temp1 = get_min_max_temp(1, $veh_temp_details, $cm_details->temp_sensors);
        $min_max_temp2 = get_min_max_temp(2, $veh_temp_details, $cm_details->temp_sensors);
        $min_max_temp3 = get_min_max_temp(3, $veh_temp_details, $cm_details->temp_sensors);
        $min_max_temp4 = get_min_max_temp(4, $veh_temp_details, $cm_details->temp_sensors);
        $t1 = getName_ByType($vehicle->n1);
        $t2 = getName_ByType($vehicle->n2);
        $t3 = getName_ByType($vehicle->n3);
        $t4 = getName_ByType($vehicle->n4);
        $t1 = ($t1 == '') ? 'Temperature 1' : $t1;
        $t2 = ($t2 == '') ? 'Temperature 2' : $t2;
        $t3 = ($t3 == '') ? 'Temperature 3' : $t3;
        $t4 = ($t4 == '') ? 'Temperature 4' : $t4;

        $tempselectArr = explode("-", $tempselect);

        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $display .= "
                    <tr style='background-color:#CCCCCC;font-weight:bold;'>
                    <td style='width:150px;height:auto;'>Time</td>";
                if ($switchto != 3) {
                    $display .= "<td style='width:250px;height:auto;'>Location</td>";
                }
                if ($cm_details->temp_sensors == 4) {
                    $display .= "<td style='width:150px;height:auto;'>" . $t1 . "</td>
                    <td style='width:150px;height:auto;'>" . $t2 . "</td>
                    <td style='width:150px;height:auto;'>" . $t3 . "</td>
                    <td style='width:150px;height:auto;'>" . $t4 . "</td>";
                }
                if ($cm_details->temp_sensors == 3) {
                    $display .= "<td style='width:150px;height:auto;'>Temperature 1</td>
                    <td style='width:150px;height:auto;'>Temperature 2</td>
                    <td style='width:150px;height:auto;'>Temperature 3</td>";
                }

                if ($tempselectArr[0] != "null") {
                    /*echo "if called - ";
                    echo $tempselectArr[0]; die;*/
                    if ($cm_details->temp_sensors == 2 && $tempselectArr[0] == 1) {
                        $display .= "<td style='width:150px;height:auto;'>Temperature 1</td>";
                    }

                    if ($cm_details->temp_sensors == 2 && $tempselectArr[0] == 2 && $tempselectArr[1] == "0") {
                        $display .= "<td style='width:150px;height:auto;'>Temperature 2</td>";
                    }

                    if ($cm_details->temp_sensors == 2 && $tempselectArr[0] == 2 && $tempselectArr[1] == "all") {
                        $display .= "<td style='width:150px;height:auto;'>Temperature 1</td>
                        <td style='width:150px;height:auto;'>Temperature 2</td>";
                    }
                } else {
                    /*echo "else called - ";
                    echo $tempselectArr[0]; die;*/
                    if ($cm_details->temp_sensors == 2) {
                        $display .= "<td style='width:150px;height:auto;'>Temperature 1</td>
                            <td style='width:150px;height:auto;'>Temperature 2</td>";
                    } elseif ($cm_details->temp_sensors == 1) {
                        $display .= "<td style='width:150px;height:auto;'>Temperature</td>";
                    }
                }

                /*if ($cm_details->temp_sensors == 2) {
                $display .= "<td style='width:150px;height:auto;'>Temperature 1</td>
                <td style='width:150px;height:auto;'>Temperature 2</td>";

                }

                elseif ($cm_details->temp_sensors == 1) {
                $display .= "<td style='width:150px;height:auto;'>Temperature</td>";
                 */
                $display .= "</tr>";

                if ($cm_details->temp_sensors == 4) {
                    $display .= "<tr><td colspan='6' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                }
                if ($cm_details->temp_sensors == 3) {
                    $display .= "<tr><td colspan='5' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                }
                if ($cm_details->temp_sensors == 2) {
                    $display .= "<tr><td colspan='4' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                } elseif ($cm_details->temp_sensors == 1) {
                    $display .= "<tr><td colspan='3' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                }
                $i++;
            }
            //Removing Date Details From DateTimespeedConstants::DEFAULT_TIME
            $change->lastupdated = $change->starttime;
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            if ($customerno == 116) {
                $display .= "<tr><td style='width:150px;height:auto;'>" . date(speedConstants::DEFAULT_DATETIME, strtotime($change->lastupdated)) . "</td>";
            } else {
                $display .= "<tr><td style='width:150px;height:auto;'>" . date(speedConstants::DEFAULT_TIME, strtotime($change->starttime)) . "</td>";
            }
            $location = get_location_detail($change->devicelat, $change->devicelong, $custID);
            if ($switchto != 3) {
                $display .= "<td style='width:250px;height:auto;'>$location</td>";
            }
            // Temperature Sensors
            // Temperature Sensor
            $temp1 = $temp2 = $temp3 = $temp4 = speedConstants::TEMP_WARNING;
            $warning = '-';
            $tdstring = '';
            $objTemp = new TempConversion();
            $objTemp->unit_type = $veh_temp_details->get_conversion;
            $objTemp->use_humidity = $veh_temp_details->use_humidity;
            $objTemp->switch_to = $switchto;
            // $pass = (isset($arr[0]) ? $arr[0] : $_SESSION['temp_sensors']);
            $pass = ((isset($tempselectArr[0]) && $tempselectArr[0] != "null") ? $tempselectArr[0] : $cm_details->temp_sensors);
            // echo "switch value - ".$pass; die;
            //$pass =  $cm_details->temp_sensors;
            //switch ($cm_details->temp_sensors) {
            switch ($pass) {
                case 4:
                    if ($vehicle->tempsen4 != 0) {
                        $isTemperatureMuted = checkTemperatureMute($mutedetails, 4, $change->lastupdated);
                        if ($isTemperatureMuted) {
                            $temp4 = 'Muted';
                            $temp4_mute_reading++;
                        } else {
                            $temp4_nonmute_reading++;
                            $s4 = "analog" . $vehicle->tempsen4;
                            if ($change->$s4 != 0) {
                                $objTemp->rawtemp = $change->$s4;
                                $temp4 = getTempUtil($objTemp);
                                if ($temp4 == 0 || $temp4 > NORMAL_MAX_TEMP || $temp4 < NORMAL_MIN_TEMP) {
                                    if ($temp4 == 0) {
                                        $temp4 = 'Wirecut';
                                    } else {
                                        $temp4 = 'Bad Data';
                                    }
                                    $temp4_bad_reading++;
                                } elseif ($temp4 != 0) {
                                    $temp4_min = set_summary_min_temp4($temp4);
                                    $temp4_max = set_summary_max_temp4($temp4);
                                    if ($temp4 > $min_max_temp4['temp_max_limit'] || $temp4 < $min_max_temp4['temp_min_limit']) {
                                        if ($non_complaince_check4 == 5) {
                                            //echo "hello i reached".$non_complaince_check; //die;
                                            // $temp4_non_comp_count++;
                                            $temp4_non_comp_count = $temp4_non_comp_count + 5;
                                            $non_complaince_check4 = 1;
                                        } else {
                                            //echo "<br>temp is ".$temp1." else ".$non_complaince_check."<br>";
                                            $non_complaince_check4++;
                                        }
                                    } else {
                                        $non_complaince_check4 = 1;
                                    }
                                    /*$temp4_non_comp_count++;
                                    }*/
                                    $temp4 = $temp4 . speedConstants::TEMP_DEGREE;
                                }
                            } else {
                                $temp4_bad_reading++;
                            }
                        }
                    } else {
                        $temp4 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $tdstring = "<td>" . $temp4 . "</td>" . $tdstring;

                case 3:
                    if ($vehicle->tempsen3 != 0) {
                        $isTemperatureMuted = checkTemperatureMute($mutedetails, 3, $change->lastupdated);
                        if ($isTemperatureMuted) {
                            $temp3 = 'Muted';
                            $temp3_mute_reading++;
                        } else {
                            $temp3_nonmute_reading++;
                            $s3 = "analog" . $vehicle->tempsen3;
                            if ($change->$s3 != 0) {
                                $objTemp->rawtemp = $change->$s3;
                                $temp3 = getTempUtil($objTemp);

                                if ($temp3 == 0 || $temp3 > NORMAL_MAX_TEMP || $temp3 < NORMAL_MIN_TEMP) {
                                    if ($temp3 == 0) {
                                        $temp3 = 'Wirecut';
                                    } else {
                                        $temp3 = 'Bad Data';
                                    }
                                    $temp3_bad_reading++;
                                } elseif ($temp3 != 0) {
                                    $temp3_min = set_summary_min_temp3($temp3);
                                    $temp3_max = set_summary_max_temp3($temp3);
                                    if ($temp3 > $min_max_temp3['temp_max_limit'] || $temp3 < $min_max_temp3['temp_min_limit']) {
                                        if ($non_complaince_check3 == 5) {
                                            //echo "hello i reached".$non_complaince_check; //die;
                                            // $temp3_non_comp_count++;
                                            $temp3_non_comp_count = $temp3_non_comp_count + 5;
                                            $non_complaince_check3 = 1;
                                        } else {
                                            //echo "<br>temp is ".$temp1." else ".$non_complaince_check."<br>";
                                            $non_complaince_check3++;
                                        }
                                    } else {
                                        $non_complaince_check3 = 1;
                                    }
                                    /*$temp3_non_comp_count++;
                                    }*/
                                    $temp3 = $temp3 . speedConstants::TEMP_DEGREE;
                                }
                            } else {
                                $temp3_bad_reading++;
                            }
                        }
                    } else {
                        $temp3 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $tdstring = "<td>" . $temp3 . "</td>" . $tdstring;

                case 2:
                    if ($vehicle->tempsen2 != 0) {
                        $isTemperatureMuted = checkTemperatureMute($mutedetails, 2, $change->lastupdated);
                        if ($isTemperatureMuted) {
                            $temp2 = 'Muted';
                            $temp2_mute_reading++;
                        } else {
                            $temp2_nonmute_reading++;
                            $s2 = "analog" . $vehicle->tempsen2;
                            if ($change->$s2 != 0) {
                                $objTemp->rawtemp = $change->$s2;
                                $temp2 = getTempUtil($objTemp);

                                if ($temp2 == 0 || $temp2 > NORMAL_MAX_TEMP || $temp2 < NORMAL_MIN_TEMP) {
                                    if ($temp2 == 0) {
                                        $temp2 = 'Wirecut';
                                    } else {
                                        $temp2 = 'Bad Data';
                                    }
                                    $temp2_bad_reading++;
                                } elseif ($temp2 != 0) {
                                    $temp2_min = set_summary_min_temp2($temp2);
                                    $temp2_max = set_summary_max_temp2($temp2);
                                    if ($temp2 > $min_max_temp2['temp_max_limit'] || $temp2 < $min_max_temp2['temp_min_limit']) {
                                        if ($non_complaince_check2 == 5) {
                                            //echo "hello i reached".$non_complaince_check; //die;
                                            // $temp2_non_comp_count++;
                                            $temp2_non_comp_count = $temp2_non_comp_count + 5;
                                            $non_complaince_check2 = 1;
                                        } else {
                                            //echo "<br>temp is ".$temp1." else ".$non_complaince_check."<br>";
                                            $non_complaince_check2++;
                                        }
                                    } else {
                                        $non_complaince_check2 = 1;
                                    }
                                    /*$temp2_non_comp_count++;
                                    }*/
                                    $temp2 = $temp2 . speedConstants::TEMP_DEGREE;
                                }
                            } else {
                                $temp2_bad_reading++;
                            }
                        }
                    } else {
                        $temp2 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $tdstring = "<td>" . $temp2 . "</td>" . $tdstring;
                    if (isset($tempselectArr[0]) && $tempselectArr[0] != "null") {
                        if ((isset($tempselectArr[0]) && $tempselectArr[0] == 2) && $tempselectArr[1] != "all") {
                            break;
                        }
                    }

                case 1:
                    if ($vehicle->tempsen1 != 0) {
                        $isTemperatureMuted = checkTemperatureMute($mutedetails, 1, $change->lastupdated);
                        if ($isTemperatureMuted) {
                            $temp1 = 'Muted';
                            $temp1_mute_reading++;
                        } else {
                            $temp1_nonmute_reading++;
                            $s1 = "analog" . $vehicle->tempsen1;
                            if ($change->$s1 != 0) {
                                $objTemp->rawtemp = $change->$s1;
                                $temp1 = getTempUtil($objTemp);

                                if ($temp1 == 0 || $temp1 > NORMAL_MAX_TEMP || $temp1 < NORMAL_MIN_TEMP) {
                                    if ($temp1 == 0) {
                                        $temp1 = 'Wirecut';
                                    } else {
                                        $temp1 = 'Bad Data';
                                    }
                                    $temp1_bad_reading++;
                                } elseif ($temp1 != 0) {
                                    $temp1_min = set_summary_min_temp($temp1);
                                    $temp1_max = set_summary_max_temp($temp1);
                                    if ($temp1 > $min_max_temp1['temp_max_limit'] || $temp1 < $min_max_temp1['temp_min_limit']) {
                                        if ($non_complaince_check1 == 5) {
                                            //echo "hello i reached".$non_complaince_check; //die;
                                            // $temp1_non_comp_count++;
                                            $temp1_non_comp_count = $temp1_non_comp_count + 5;
                                            $non_complaince_check1 = 1;
                                        } else {
                                            //echo "<br>temp is ".$temp1." else ".$non_complaince_check."<br>";
                                            $non_complaince_check1++;
                                        }
                                    } else {
                                        $non_complaince_check1 = 1;
                                    }
                                    /*$temp1_non_comp_count++;
                                    }*/
                                    $temp1 = $temp1 . speedConstants::TEMP_DEGREE;
                                }
                            } else {
                                $temp1_bad_reading++;
                            }
                        }
                    } else {
                        $temp1 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $tdstring = "<td>" . $temp1 . "</td>" . $tdstring;
            }

            $display .= $tdstring;
            $display .= '</tr>';
            $tr++;
        }
    }
    $display .= '</tbody>';
    $display .= '</table>';
    $temphtml = '';
    $span = null;

    switch ($pass) {
        //switch ($cm_details->temp_sensors) {
        case 4:
            $span = isset($span) ? $span : 4;
            $goodcount = $temp4_nonmute_reading - $temp4_bad_reading;
            if ($temp4_nonmute_reading == 0 || $goodcount == 0) {
                $abv_compliance = "Not Applicable";
                $within_compliance = "Not Applicable";
            } else {
                $abv_compliance = round(($temp4_non_comp_count / $goodcount) * 100, 2);
                $compliance_count = $goodcount - $temp4_non_comp_count;
                $within_compliance = round($compliance_count / $goodcount * 100, 2);
            }
            $temp4_min = ($temp4_min != '' ? $temp4_min . " &deg;C" : "N/A");
            $temp4_max = ($temp4_max != '' ? $temp4_max . " &deg;C" : "N/A");

            $temphtml = "
            <td style='text-align:center;'>
            <table class='table newTable'><thead><tr><th><u>" . $t4 . "</u></th></tr></thead>
                <tbody>
                    <tr><td>Temprature Range :" . $min_max_temp4['temp_min_limit'] . " &deg;C to " . $min_max_temp4['temp_max_limit'] . " &deg;C</td></tr>
                    <tr><td>Minimum Temperature: $temp4_min &deg;C</td></tr>
                    <tr><td>Maximum Temperature: $temp4_max &deg;C</td></tr>
                    <tr><td>Total Reading: $tr</td></tr>
                    <tr><td>Non compliance readings : $temp4_non_comp_count</td></tr>
                    <tr><td>Bad readings : $temp4_bad_reading</td></tr>
                    <tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                    <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
                </td>";
            if ((isset($tempselectArr[0]) && $tempselectArr[0] == 4) && $tempselectArr[1] != "all") {
                break;
            }
        case 3:
            $span = isset($span) ? $span : 3;
            $goodcount = $temp3_nonmute_reading - $temp3_bad_reading;
            if ($temp3_nonmute_reading == 0 || $goodcount == 0) {
                $abv_compliance = "Not Applicable";
                $within_compliance = "Not Applicable";
            } else {
                $abv_compliance = round(($temp3_non_comp_count / $goodcount) * 100, 2);
                $compliance_count = $goodcount - $temp3_non_comp_count;
                $within_compliance = round($compliance_count / $goodcount * 100, 2);
            }
            $temp3_min = ($temp3_min != '' ? $temp3_min . " &deg;C" : "N/A");
            $temp3_max = ($temp3_max != '' ? $temp3_max . " &deg;C" : "N/A");
            $temphtml = "<td style='text-align:center;'>
        <table class='table newTable'><thead><tr><th><u>" . $t3 . "</u></th></tr></thead>
            <tbody>
                <tr><td>Temprature Range :" . $min_max_temp3['temp_min_limit'] . " &deg;C to " . $min_max_temp3['temp_max_limit'] . " &deg;C</td></tr>
                <tr><td>Minimum Temperature: $temp3_min &deg;C</td></tr>
                <tr><td>Maximum Temperature: $temp3_max &deg;C</td></tr>
                <tr><td>Total Reading: $tr</td></tr>
                <tr><td>Non compliance readings : $temp3_non_comp_count</td></tr>
                <tr><td>Bad readings : $temp3_bad_reading</td></tr>
                <tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
            </td>" . $temphtml;
            if ((isset($tempselectArr[0]) && $tempselectArr[0] == 3) && $tempselectArr[1] != "all") {
                break;
            }
        case 2:
            $span = isset($span) ? $span : 2;
            $goodcount = $temp2_nonmute_reading - $temp2_bad_reading;
            if ($temp2_nonmute_reading == 0 || $goodcount == 0) {
                $abv_compliance = "Not Applicable";
                $within_compliance = "Not Applicable";
            } else {
                $abv_compliance = round(($temp2_non_comp_count / $goodcount) * 100, 2);
                $compliance_count = $goodcount - $temp2_non_comp_count;
                $within_compliance = round($compliance_count / $goodcount * 100, 2);
            }
            $temp2_min = ($temp2_min != '' ? $temp2_min . " &deg;C" : "N/A");
            $temp2_max = ($temp2_max != '' ? $temp2_max . " &deg;C" : "N/A");

            $temphtml = "<td style='text-align:center;'>
        <table class='table newTable'><thead><tr><th><u>" . $t2 . "</u></th></tr></thead>
            <tbody>
                <tr><td>Temprature Range :" . $min_max_temp2['temp_min_limit'] . " &deg;C to " . $min_max_temp2['temp_max_limit'] . " &deg;C</td></tr>
                <tr><td>Minimum Temperature: $temp2_min </td></tr>
                <tr><td>Maximum Temperature: $temp2_max </td></tr>
                <tr><td>Total Reading: $tr</td></tr>
                <tr><td> Non compliance readings : $temp2_non_comp_count</td></tr>
                <tr><td>Bad readings : $temp2_bad_reading</td></tr>
                <tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
            </td>" . $temphtml;
            if ((isset($tempselectArr[0]) && $tempselectArr[0] == 2) && $tempselectArr[1] != "all") {
                break;
            }
        case 1:
            $span = isset($span) ? $span : 1;
            $goodcount = $temp1_nonmute_reading - $temp1_bad_reading;
            if ($temp1_nonmute_reading == 0 || $goodcount == 0) {
                $abv_compliance = "1Not Applicable";
                $within_compliance = "2Not Applicable";
            } else {
                $abv_compliance = round(($temp1_non_comp_count / $goodcount) * 100, 2);
                $compliance_count = $goodcount - $temp1_non_comp_count;
                $within_compliance = round($compliance_count / $goodcount * 100, 2);
            }
            $temp1_min = ($temp1_min != '' ? $temp1_min . " &deg;C" : "N/A");
            $temp1_max = ($temp1_max != '' ? $temp1_max . " &deg;C" : "N/A");
            $temphtml = "<td style='text-align:center;'>
        <table class='table newTable'><thead>
            <tr><th><u>" . $t1 . "</u></th></tr></thead>
            <tbody>
                <tr><td>Temprature Range :" . $min_max_temp1['temp_min_limit'] . " &deg;C to " . $min_max_temp1['temp_max_limit'] . " &deg;C</td></tr>
                <tr><td>Minimum Temperature: $temp1_min </td></tr>
                <tr><td>Maximum Temperature: $temp1_max </td></tr>
                <tr><td>Total Reading: $tr</td></tr>
                <tr><td>Non compliance readings: $temp1_non_comp_count</td></tr>
                <tr><td>Bad readings : $temp1_bad_reading</td></tr>
                <tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
            </td>" . $temphtml;
            break;
    }
    $summary = "<table align='center' style='width: auto; font-size:13px; text-align:center; border:1px solid #000;'>
        <thead>
            <tr><td colspan='$span' style='background-color:#CCCCCC;font-weight:bold;'>Statistics</td></tr>
        </thead>
        <tbody>
            <tr>$temphtml</tr>
        </tbody>
    </table>";
    $display .= "$summary";
    return $display;
}

function gettemptabularreport_nestle($STdate, $EDdate, $deviceid, $interval, $stime, $etime, $tripmin = null, $tripmax = null, $tempselect = null) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $graph_days = array();
    $graph_days_temp1 = array();
    $graph_days_temp2 = array();
    $graph_days_temp3 = array();
    $graph_days_temp4 = array();
    $graph_days_ig = array();
    $veh_temp_details = array();
    $customerno = $_SESSION['customerno'];
    $unit = getunitdetails($deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $graph_data = null;
            $STdate = date("Y-m-d", strtotime($STdate));
            $f_STdate = $userdate . " 00:00:00";
            if ($userdate == $STdate) {
                $f_STdate = $userdate . " " . $stime . ":00";
            }
            $EDdate = date("Y-m-d", strtotime($EDdate));
            $f_EDdate = $userdate . " 23:59:59";
            if ($userdate == $EDdate) {
                $f_EDdate = $userdate . " " . $etime . ":00";
            }

            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $return = gettempdata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, true, $unit);
                $data = $return[0];
                $graph_data = $return[1];
                $countGraph0 = count($graph_data['0']);
                $countGraph1 = count($graph_data['1']);
                $countGraph2 = count($graph_data['2']);
                $countGraph3 = count($graph_data['3']);
                //echo $countGraph1; die;
                $graph_data_temp1 = (isset($graph_data[0]) && $countGraph0 > 0) ? $graph_data[0] : NULL;
                $graph_data_temp2 = (isset($graph_data[1]) && $countGraph1 > 0) ? $graph_data[1] : NULL;
                $graph_data_temp3 = (isset($graph_data[2]) && $countGraph2 > 0) ? $graph_data[2] : NULL;
                $graph_data_temp4 = (isset($graph_data[3]) && $countGraph3 > 0) ? $graph_data[3] : NULL;
                $graph_ig = $return['ig_graph'];
            }
            if (isset($data) && count($data) > 1) {
                $days = array_merge($days, $data);
            }
            if (isset($graph_data_temp1) && count($graph_data_temp1) > 1) {
                $graph_days_temp1 = array_merge($graph_days_temp1, $graph_data_temp1);
                $graph_days_ig = array_merge($graph_days_ig, $graph_ig);

                if (isset($graph_data_temp2) && count($graph_data_temp2) > 1) {
                    $graph_days_temp2 = array_merge($graph_days_temp2, $graph_data_temp2);
                }
                if (isset($graph_data_temp3) && count($graph_data_temp3) > 1) {
                    $graph_days_temp3 = array_merge($graph_days_temp3, $graph_data_temp3);
                }
                if (isset($graph_data_temp4) && count($graph_data_temp4) > 1) {
                    $graph_days_temp4 = array_merge($graph_days_temp4, $graph_data_temp4);
                }
            }
        }
    }
    if (isset($days) && count($days) > 0) {
        if (isset($return['vehicleid'])) {
            $veh_temp_details = getunitdetailsfromvehid($return['vehicleid'], $customerno);
        }
        if (isset($tripmin)) {
            $unit->temp1_min = $tripmin;
            $unit->temp2_min = $tripmin;
            $unit->temp3_min = $tripmin;
            $unit->temp4_min = $tripmin;
        }
        if (isset($tripmax)) {
            $unit->temp1_max = $tripmax;
            $unit->temp2_max = $tripmax;
            $unit->temp3_max = $tripmax;
            $unit->temp4_max = $tripmax;
        }

        $finalreport = create_temphtml_from_report_with_noncom_reading($days, $unit, $veh_temp_details, $interval, $tempselect);
    } else {
        $finalreport = "<tr><td colspan='3'>No Data</td></tr>";
    }
    $graph_days_final_temp1 = '';
    $graph_days_final_temp2 = '';
    $graph_days_final_temp3 = '';
    $graph_days_final_temp4 = '';
    $graph_ig_final = '';
    if (!empty($graph_days_temp1)) {
        $graph_days_final_temp1 = implode(',', $graph_days_temp1);
        $graph_ig_final = implode(',', $graph_days_ig);
        if (!empty($graph_days_temp2)) {
            $graph_days_final_temp2 = implode(',', $graph_days_temp2);
        }
        if (!empty($graph_days_temp3)) {
            $graph_days_final_temp3 = implode(',', $graph_days_temp3);
        }
        if (!empty($graph_days_temp4)) {
            $graph_days_final_temp4 = implode(',', $graph_days_temp4);
        }
    }

    $graph_days_final = array($graph_days_final_temp1, $graph_days_final_temp2, $graph_days_final_temp3, $graph_days_final_temp4);
    return array($finalreport, $graph_days_final, $veh_temp_details, $graph_ig_final);
}

function gettempreportpdf_nestle($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $interval, $stime, $etime, $switchto, $vgroupname = null, $reporttype = null, $tempselect = null) {
    if (!$tempselect) {
        $tempselect = "null";
    }

    //echo "reporttype ". $reporttype; die;

    //echo "temp is equal to - ".$tempselect; die;
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $unit = getunitdetailspdf($customerno, $deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $STdate = date("Y-m-d", strtotime($STdate));
            $f_STdate = $userdate . " 00:00:00";
            if ($userdate == $STdate) {
                $f_STdate = $userdate . " " . $stime . ":00";
            }
            $EDdate = date("Y-m-d", strtotime($EDdate));
            $f_EDdate = $userdate . " 23:59:59";
            if ($userdate == $EDdate) {
                $f_EDdate = $userdate . " " . $etime . ":00";
            }
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = gettempdata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, false, null, $customerno);
                $vehicleid = $data['vehicleid'];
            }
            if ($data[0] != null && count($data[0]) > 1) {
                $days = array_merge($days, $data[0]);
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $cm = new CustomerManager($customerno);
        $customer_details = $cm->getcustomerdetail_byid($customerno);
        $fromdate = date('d-m-Y H:i', strtotime($STdate . ' ' . $stime));
        $todate = date('d-m-Y H:i', strtotime($EDdate . ' ' . $etime));
        $title = 'Temperature Report';
        if ($switchto == 3) {
            if (isset($_SESSION['Warehouse'])) {
                $veh = $_SESSION['Warehouse'];
            } else {
                $veh = "Warehouse";
            }
            $subTitle = array(
                "$veh: $vehicleno",
                "Start Date: $fromdate",
                "End Date: $todate",
                "Interval: $interval mins"
            );
        } else {
            $subTitle = array(
                "Vehicle No: $vehicleno",
                "Start Date: $fromdate",
                "End Date: $todate",
                "Interval: $interval mins"
            );
        }
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        if ($reporttype == "pdf") {
            $finalreport = pdf_header($title, $subTitle, $customer_details);
        }
        if ($reporttype == "xls") {
            $finalreport = excel_header($title, $subTitle, $customer_details);
        }
        $finalreport .= "<hr /><br/><br/>
        <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
        <tbody>";
        $veh_temp_details = '';
        if (isset($vehicleid)) {
            $veh_temp_details = getunitdetailsfromvehid($vehicleid, $customerno);
            $veh_temp_details->use_humidity = $customer_details->use_humidity;
        }
        $finalreport .= create_temppdf_from_report_with_noncom_reading($days, $unit, $customerno, $veh_temp_details, $switchto, $tempselect);

        // $finalreport .= create_temppdf_from_report($days, $unit, $customerno, $veh_temp_details, $switchto, $tempselect);
    } else {
        $finalreport = "Data Not Available";
    }
    return $finalreport;
}

function getInactiveDevicesPerVehicle($vehicleid, $startdate, $enddate) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $devices = $vm->getInactiveDevicesPerVehicle($vehicleid, $startdate, $enddate);
    return $devices;
}

function getNightTravelReportPDF($customer, $date, $nightDriveDetails, $reportType = '') {
    // $date= '05-09-2018';
    $drm = new DailyReportManager(0);
    $finalreport = '';

    $devices = $drm->pulldevices_dailyreport_night_perCustomer($customer);
    if (isset($devices) && !empty($devices)) {
        foreach ($devices as $deviceK => $deviceV) {
            //     $date       = $reportDate->format(speedConstants::DEFAULT_DATE);
            $dateParam = new DateTime($date);
            $tableName = 'A' . $dateParam->format(DATEFORMAT_DMY);
            $location = "../../customer/$customer/reports/dailyreport.sqlite";
            $unitId = $deviceV->uid;
            if (file_exists($location) && $unitId != '') {
                $path = "sqlite:$location";
                $db = new PDO($path);
                $GeoCoder_Obj = new GeoCoder($customer);
                $topspeedtimequery = "SELECT * FROM '" . $tableName . "' WHERE uid = '" . $unitId . "'";
                $arrQueryResult = $db->query($topspeedtimequery);

                if ($arrQueryResult) {
                    $result = $arrQueryResult->fetchAll();
                    $data = array();
                    $cronObj = new CronManager();

                    if (isset($result) && !empty($result)) {
                        foreach ($result as $resKey => $resVal) {
                            $data['vehicleNo'] = $cronObj->getVehicleNo($resVal['vehicleid'], $customer);
                            if ($data['vehicleNo'] != '') {
                                $data['start_time'] = $nightDriveDetails['start_time'];
                                $data['end_time'] = $nightDriveDetails['end_time'];
                                if (isset($resVal['night_first_lat']) && $resVal['night_first_lat'] != '' && $resVal['night_first_lat'] != '0' && isset($resVal['night_first_long']) && $resVal['night_first_long'] != '' && $resVal['night_first_long'] != '0') {
                                    $data['start_location'] = $GeoCoder_Obj->get_location_bylatlong($resVal['night_first_lat'], $resVal['night_first_long']);
                                } else {
                                    $data['start_location'] = 'Not Applicable';
                                }

                                if (isset($resVal['night_end_lat']) && $resVal['night_end_lat'] != '' && $resVal['night_end_lat'] != '0' && isset($resVal['night_end_long']) && $resVal['night_end_long'] != '' && $resVal['night_end_long'] != '0') {
                                    $data['end_location'] = $GeoCoder_Obj->get_location_bylatlong($resVal['night_end_lat'], $resVal['night_end_long']);
                                } else {
                                    $data['end_location'] = 'Not Applicable';
                                }

                                /*   $sDate =    date('Y-m-d',strtotime('-1 day'));
                                $eDate =    date('Y-m-d');

                                $start_date = $sDate." ".$nightDriveDetails['start_time'];
                                $end_date   = $eDate." ".$nightDriveDetails['end_time'];

                                 */
                                $distance = $resVal['night_distance'] / 1000;
                                if ($distance != 0) {
                                    $data['distance'] = $distance;
                                } else {
                                    $data['distance'] = '-';
                                }
                                $data['checkpoint'] = "";
                                $distance = 0;
                            }
                        }
                        $updateArray[] = $data;
                        $cm = new CustomerManager($customer);
                        $customer_details = $cm->getcustomerdetail_byid($customer);
                        $title = 'Night Drive Travel Report';
                        $start_date = date($date, strtotime('Y-m-d'));
                        $end_date = $date . " " . $nightDriveDetails['end_time'];
                        $start_date = $start_date . " " . $nightDriveDetails['start_time'];
                        $subTitle = array("Start Date : $start_date",
                            "End Date   : $end_date");
                        if ($reportType == "pdf") {
                            $finalreport = pdf_header($title, $subTitle, $customer_details);
                        }
                        if ($reportType == "xls") {
                            $finalreport = excel_header($title, $subTitle, $customer_details);
                        }

                        $finalreport .= "<hr /><br/><br/>
                        <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                        <tbody>";
                        $finalreport .= create_nightReportpdf_from_report($updateArray, $customer);
                        $finalreport .= "</tbody></table>";
                    }
                }
            } //
        }
    } else {
        $finalreport .= "Data Not Available";
    }
    return $finalreport;
}

function create_nightReportpdf_from_report($updatedArray, $customer) {
    $display = "";
    if (isset($updatedArray)) {
        $k = 0;
        $display .= "<tr style='background-color:#CCCCCC;font-weight:bold;'>";
        $display .= "<td style='width:150px;height:auto;'>Sr No</td>";
        $display .= "<td style='width:150px;height:auto;'>Vehicle No</td>";
        $display .= "<td style='width:150px;height:auto;'>Start Location</td>";
        $display .= "<td style='width:150px;height:auto;'>End Location</td>";
        $display .= "<td style='width:150px;height:auto;'>Distance</td></tr>";

        foreach ($updatedArray as $key => $val) {
            $k++;
            $display .= "<tr><td style='width:150px;height:auto;'>" . $k . "</td>";
            $display .= "<td style='width:150px;height:auto;'>" . $val['vehicleNo'] . "</td>";
            $display .= "<td style='width:150px;height:auto;'>" . $val['start_location'] . "</td>";
            $display .= "<td style='width:150px;height:auto;'>" . $val['end_location'] . "</td>";
            $display .= "<td style='width:150px;height:auto;'>" . $val['distance'] . "</td></tr>";
        }
    }
    return $display;
}

function getNightTravelReportXLS() {}
function getNomensName($deviceid, $temp_sensors) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $nomensName = $vm->getNomensName($deviceid, $_SESSION['customerno'], $temp_sensors);
    return $nomensName;
}

function getSmsStoreLog($startDate, $endDate) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $getSmsStoreData = $vm->getSmsStoreLog($startDate, $endDate, $_SESSION['customerno']);
    return $getSmsStoreData;
}

function getCustomTempRange($tempselect, $minTemp, $maxTemp) {
    return array('temp_max_limit' => $maxTemp, 'temp_min_limit' => $minTemp);
}

function getSmsStorePDF($customerno, $sdate, $edate) {
    $title = 'SMS Store Report';
    $subTitle = array(
        "Start Date: $sdate",
        "End Date: $edate"
    );
    $finalreport = pdf_header($title, $subTitle);
    $vm = new VehicleManager($_SESSION['customerno']);
    $smsmStore = $vm->getSmsStoreLog($sdate, $edate, $_SESSION['customerno']);
    $finalreport .= '<table id="search_table_2" align="center" style="font-size:15px; text-align:center;border-collapse:collapse;border:1px solid #000;">';
    $finalreport .= '<tr>
                        <th style="border:1px solid #000;padding:3px;">Sr No</th>
                        <th style="border-right:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;padding:3px;">Vehicle No</th>
                        <th style="border-right:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;padding:3px;">Checkpoint</th>
                        <th style="border:1px solid #000;padding:3px;">Message</th>
                        <th style="border:1px solid #000;padding:3px;">Phone</th>
                        <th style="border:1px solid #000;padding:3px;">Message Sent On</th>
                    </tr>';

    if ($smsmStore != NULL) {
        $x = 1;
        foreach ($smsmStore as $data) {
            if ($data['message_sent_on'] != '0000-00-00 00:00:00') {
                $message_sent_on = date('d-m-Y H:i', strtotime($data['message_sent_on']));
            } else {
                $message_sent_on = '';
            }

            if ($data['message'] != "") {
                $message = $data['message'];
            } else {
                $message = "";
            }

            $finalreport .= '<tr><td style="padding:3px;">' . $x . '</td><td style="padding:3px;">' . $data['vehicleno'] . '</td><td style="padding:3px;">' . $data['cname'] . '</td><td style="padding:3px;">' . wordwrap($message, 40, "<br>\n") . '</td><td style="padding:3px;">' . $data['phone'] . '</td><td style="padding:3px;">' . $message_sent_on . '</td></tr>';
            $x++;
        }
    } else {
        $finalreport .= '<tr><td colspan="4">Data Not Available</td></tr>';
    }
    $finalreport .= '</table>';
    echo $finalreport;
}

function getSmsStoreExcel($customerno, $sdate, $edate) {
    $title = 'SMS Store Report';
    $subTitle = array(
        "Start Date: $sdate",
        "End Date: $edate"
    );
    $finalreport = pdf_header($title, $subTitle);
    $vm = new VehicleManager($_SESSION['customerno']);
    $smsmStore = $vm->getSmsStoreLog($sdate, $edate, $_SESSION['customerno']);
    $finalreport .= '<table id="search_table_2" align="center" style="font-size:15px; text-align:center;border-collapse:collapse;border:1px solid #000;">';
    $finalreport .= '<tr>
                        <th style="border:1px solid #000;padding:3px;">Sr No</th>
                        <th style="border-right:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;padding:3px;">Vehicle No</th>
                        <th style="border-right:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;padding:3px;">Checkpoint</th>
                        <th style="border:1px solid #000;padding:3px;">Message</th>
                        <th style="border:1px solid #000;padding:3px;">Phone</th>
                        <th style="border:1px solid #000;padding:3px;">Message Sent On</th>
                    </tr>';

    if ($smsmStore != NULL) {
        $x = 1;
        foreach ($smsmStore as $data) {
            if ($data['message_sent_on'] != '0000-00-00 00:00:00') {
                $message_sent_on = date('d-m-Y H:i', strtotime($data['message_sent_on']));
            } else {
                $message_sent_on = '';
            }
            if (strlen($data['message']) > 40) {
                $message = wordwrap($data['message'], 40, "<br>\n");
            } else {
                $message = $data['message'];
            }

            $finalreport .= '<tr><td style="padding:3px;">' . $x . '</td><td style="padding:3px;">' . $data['vehicleno'] . '</td><td style="padding:3px;">' . $data['cname'] . '</td><td style="padding:3px;">' . $data['message'] . '</td><td style="padding:3px;">' . $data['phone'] . '</td><td style="padding:3px;">' . $message_sent_on . '</td></tr>';
            $x++;
        }
    } else {
        $finalreport .= '<tr><td colspan="4">Data Not Available</td></tr>';
    }
    $finalreport .= '</table>';
    echo $finalreport;
}

function getInactiveVehicle($groups) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $devices = $vm->get_all_vehicles_by_group($groups);
    return $devices;
}

function getInactiveVehiclePDF($customerno, $sdate, $edate) {
    $arrGroups = array();
    $objCustomerManager = new CustomerManager();
    $objUserManager = new UserManager();
    $title = 'Inactive Vehicle Report';
    $subTitle = array(
        "Start Date: $sdate",
        "End Date: $edate"
    );
    $finalreport = pdf_header($title, $subTitle);
    $vm = new VehicleManager($customerno);
    $userDetails = $objUserManager->getusersforcustomer($customerno);

    foreach ($userDetails as $user) {
        $userGroups = $objUserManager->get_groups_fromuser($customerno, $user->userid);
        if (isset($userGroups) && !empty($userGroups)) {
            foreach ($userGroups as $group) {
                $arrGroups[] = $group->groupid;
            }
        }
    }
    $arrGroups = array_unique($arrGroups);
    if (($key = array_search('0', $arrGroups)) !== false) {
        unset($arrGroups[$key]);
    }
    $inactiveVehicles = $vm->get_all_vehicles_by_group($arrGroups);

    $finalreport .= '<table id="search_table_2" align="center" style="font-size:12px; text-align:center;border-collapse:collapse;border:1px solid #000;width:60%;">';
    $finalreport .= '<tr>
                                        <th style="border:1px solid #000;padding:3px;">Sr No</th>
                                        <th style="border:1px solid #000;padding:3px;">Vehicle No</th>
                                        <th style="border:1px solid #000;padding:3px;">Group Name</th>
                                        <th style="border:1px solid #000;padding:3px;">Unit No</th>
                                        <th style="border:1px solid #000;padding:3px;">Simcard No.</th>';
    if ($customerno == 64) {
        $finalreport .= '<th style="border:1px solid #000;padding:3px;">Region Name</th>
                                        <th style="border:1px solid #000;padding:3px;">Zone Name</th>';
    }
    $finalreport .= '<th style="border:1px solid #000;padding:3px;">Last Updated</th>
                                        <th style="border:1px solid #000;padding:3px;">Inactive Days</th>
                                        <th style="border:1px solid #000;padding:3px;">Bucket</th>
                                        <th style="border:1px solid #000;padding:3px;">Status</th>';
    if ($customerno == 64) {
        $finalreport .=
            '<th style="border:1px solid #000;padding:3px;">Branch User</th>
                                            <th style="border:1px solid #000;padding:3px;">SAP Code</th>
                                            <th style="border:1px solid #000;padding:3px;">Mobile</th>
                                            <th style="border:1px solid #000;padding:3px;">Regional User</th>
                                            <th style="border:1px solid #000;padding:3px;">SAP Code</th>
                                            <th style="border:1px solid #000;padding:3px;">Mobile</th>
                                            <th style="border:1px solid #000;padding:3px;">Zonal User</th>
                                            <th style="border:1px solid #000;padding:3px;">SAP Code</th>
                                            <th style="border:1px solid #000;padding:3px;">Mobile</th>';
    }
    $finalreport .= '</tr>';
    if ($inactiveVehicles != NULL) {
        $x = 1;
        $lessthan_hour_ago = date("Y-m-d H:i:s", strtotime('-1 hour'));
        foreach ($inactiveVehicles as $data) {
            if (strtotime($data->lastupdated) < strtotime($lessthan_hour_ago) && $data->simcardno != '') {
                if ($customerno == 64) {
                    $heirarchyDetails = $objUserManager->vehicleHeirarchy($customerno, $user->userid, $data->vehicleid);
                }

                if (strlen($data->bucket_days) > 15) {
                    $bucketdays = wordwrap($data->bucket_days, 15, "<br>\n");
                } else {
                    $bucketdays = $data->bucket_days;
                }

                if (strlen($data->lastupdated) > 15) {
                    $lastupdated = wordwrap($data->lastupdated, 20, "<br>\n");
                } else {
                    $lastupdated = $data->lastupdated;
                }

                if (strlen($data->reason) > 10) {
                    $reason = wordwrap($data->reason, 15, "<br>\n");
                } else {
                    $reason = $data->reason;
                }

                if (strlen($data->groupname) > 10) {
                    $groupname = wordwrap($data->groupname, 10, "<br>\n");
                } else {
                    $groupname = trim($data->groupname);
                }
                $finalreport .= '<tr><td style="padding:3px;">' . $x . '</td><td style="padding:3px;">' . $data->vehicleno . '</td><td style="padding:3px;">' . $groupname . '</td>
                                                    <td style="padding:3px;">' . $data->unitno . '</td><td style="padding:3px;">' . $data->simcardno . '</td>';
                if ($customerno == 64) {
                    $finalreport .= '<td style="padding:3px;">' . $heirarchyDetails[0]['regionname'] . '</td><td style="padding:3px;">' . $heirarchyDetails[0]['zonename'];
                }
                $finalreport .= '</td><td style="padding:3px;">' . $lastupdated . '</td><td style="padding:3px;">' . $data->inactive_days . '</td><td style="padding:3px;">' . $bucketdays . '</td><td style="padding:3px;">' . $reason . '</td>';
                if ($customerno == 64) {
                    $finalreport .= '<td style="padding:3px;width:auto;overflow:hidden;word-wrap: break-word;">' . $heirarchyDetails[0]['realname'] . '</td><td style="padding:3px;width:auto;overflow:hidden;word-wrap: break-word;">' . $heirarchyDetails[0]['username'] . '</td><td style="padding:3px;width:auto;overflow:hidden;word-wrap: break-word;">' . $heirarchyDetails[0]['phone'] . '</td><td style="padding:3px;width:auto;overflow:hidden;word-wrap: break-word;">' . $heirarchyDetails[0]['regionalUserName'] . '</td><td style="padding:3px;">' . $heirarchyDetails[0]['regionalUserSAP'] . '</td><td style="padding:3px;width:auto;overflow:hidden;word-wrap: break-word;">' . $heirarchyDetails[0]['regionalUserSAPPhone'] . '</td><td style="padding:3px;width:auto;overflow:hidden;word-wrap: break-word;">' . $heirarchyDetails[0]['zonalUserName'] . '</td><td style="padding:3px;width:auto;overflow:hidden;word-wrap: break-word;">' . $heirarchyDetails[0]['zonalUserSAP'] . '</td><td style="padding:3px;width:auto;overflow:hidden;word-wrap: break-word;">' . $heirarchyDetails[0]['zonalUserSAPPhone'] . '</td>';
                }
                $finalreport .= '</tr>';
                $x++;
            }
        }
    } else {
        $finalreport .= '<tr><td colspan="7">Data Not Available</td></tr>';
    }
    $finalreport .= '</table>';
    echo $finalreport;
}

function getInactivevehicleExcel($customerno, $sdate, $edate) {
    $title = 'Inactive Vehicle Report';
    $subTitle = array(
        "Start Date: $sdate",
        "End Date: $edate"
    );
    $finalreport = pdf_header($title, $subTitle);
    $vm = new VehicleManager($_SESSION['customerno']);
    $inactiveVehicles = $vm->get_all_vehicles_by_group('', '', '', '');
    $userDetails = $objUserManager->getusersforcustomer($customerno);

    foreach ($userDetails as $user) {
        $userGroups = $objUserManager->get_groups_fromuser($customerno, $user->userid);
        if (isset($userGroups) && !empty($userGroups)) {
            foreach ($userGroups as $group) {
                $arrGroups[] = $group->groupid;
            }
        }
    }
    $arrGroups = array_unique($arrGroups);
    if (($key = array_search('0', $arrGroups)) !== false) {
        unset($arrGroups[$key]);
    }
    $inactiveVehicles = $vm->get_all_vehicles_by_group($arrGroups);
    $finalreport .= '<table id="search_table_2" align="center" style="font-size:12px; text-align:center;border-collapse:collapse;border:1px solid #000;width:80%;">';
    $finalreport .= '<tr>
                                        <th style="border:1px solid #000;padding:3px;">Sr No</th>
                                        <th style="border:1px solid #000;padding:3px;">Vehicle No</th>
                                        <th style="border:1px solid #000;padding:3px;">Group Name</th>
                                        <th style="border:1px solid #000;padding:3px;">Unit No</th>
                                        <th style="border:1px solid #000;padding:3px;">Simcard No.</th>
                                        <th style="border:1px solid #000;padding:3px;">Region Name</th>
                                        <th style="border:1px solid #000;padding:3px;">Zone Name</th>
                                        ';
    $finalreport .= '<th style="border:1px solid #000;padding:3px;">Last Updated</th>
                                        <th style="border:1px solid #000;padding:3px;">Inactive Days</th>
                                        <th style="border:1px solid #000;padding:3px;">Bucket</th>
                                    </tr>';
    if ($customerno == 64) {
        $finalreport .=
            '<th style="border:1px solid #000;padding:3px;">Branch User</th>
                                            <th style="border:1px solid #000;padding:3px;">SAP Code</th>
                                            <th style="border:1px solid #000;padding:3px;">Mobile</th>
                                            <th style="border:1px solid #000;padding:3px;">Regional User</th>
                                            <th style="border:1px solid #000;padding:3px;">SAP Code</th>
                                            <th style="border:1px solid #000;padding:3px;">Mobile</th>
                                            <th style="border:1px solid #000;padding:3px;">Zonal User</th>
                                            <th style="border:1px solid #000;padding:3px;">SAP Code</th>
                                            <th style="border:1px solid #000;padding:3px;">Mobile</th>';
    }
    if ($inactiveVehicles != NULL) {
        $x = 1;
        $lessthan_hour_ago = date("Y-m-d H:i:s", strtotime('-1 hour'));
        foreach ($inactiveVehicles as $data) {
            if (strtotime($data->lastupdated) < strtotime($lessthan_hour_ago) && $data->simcardno != '') {
                if (strlen($data->bucket_days) > 20) {
                    $bucketdays = wordwrap($data->bucket_days, 20, "<br>\n");
                } else {
                    $bucketdays = $data->bucket_days;
                }
                $finalreport .= '<tr><td style="padding:3px;">' . $x . '</td><td style="padding:3px;">' . $data->vehicleno . '</td><td style="padding:3px;">' . $data->groupname . '</td>';
                $finalreport .= '<td style="padding:3px;">' . $data->unitno . '</td><td style="padding:3px;">' . $data->simcardno . '</td>';
                if ($customerno == 64) {
                    $finalreport .= '<td style="padding:3px;">' . $heirarchyDetails[0]['regionname'] . '</td><td style="padding:3px;">' . $heirarchyDetails[0]['zonename'] . '</td>';
                }
                $finalreport .= '<td style="padding:3px;">' . $data->lastupdated . '</td><td style="padding:3px;">' . $data->inactive_days . '</td><td style="padding:3px;">' . $bucketdays . '</td></tr>';
                if ($customerno == 64) {
                    $finalreport .= '<td style="padding:3px;width:auto;overflow:hidden;word-wrap: break-word;">' . $heirarchyDetails[0]['realname'] . '</td><td style="padding:3px;width:auto;overflow:hidden;word-wrap: break-word;">' . $heirarchyDetails[0]['username'] . '</td><td style="padding:3px;width:auto;overflow:hidden;word-wrap: break-word;">' . $heirarchyDetails[0]['phone'] . '</td><td style="padding:3px;width:auto;overflow:hidden;word-wrap: break-word;">' . $heirarchyDetails[0]['regionalUserName'] . '</td><td style="padding:3px;width:auto;overflow:hidden;word-wrap: break-word;">' . $heirarchyDetails[0]['regionalUserSAP'] . '</td><td style="padding:3px;width:auto;overflow:hidden;word-wrap: break-word;">' . $heirarchyDetails[0]['regionalUserSAPPhone'] . '</td><td style="padding:3px;width:auto;overflow:hidden;word-wrap: break-word;">' . $heirarchyDetails[0]['zonalUserName'] . '</td><td style="padding:3px;width:auto;overflow:hidden;word-wrap: break-word;">' . $heirarchyDetails[0]['zonalUserSAP'] . '</td><td style="padding:3px;width:auto;overflow:hidden;word-wrap: break-word;">' . $heirarchyDetails[0]['zonalUserSAPPhone'] . '</td>';
                }
                $x++;
            }
        }
    } else {
        $finalreport .= '<tr><td colspan="4">Data Not Available</td></tr>';
    }
    $finalreport .= '</table>';
    echo $finalreport;
}

function update_Dump_Shipmentno($request) {
    $db = new DatabaseManager();
    $sp_params = "'" . $request->vehicleid . "'"
    . ",'" . $request->shipmentno . "'"
    . ",'" . $request->startDateTime . "'"
    . ",'" . $request->endDateTime . "'"
    . ",'" . $request->customerno . "'"
        . ",@isUpdated";
    $queryCallSP = PrepareSP(speedConstants::SP_UPDATE_DUMP_SHIPMENT_NO, $sp_params);
    $db->executeQuery($queryCallSP);
}

function PrepareSP($sp_name, $sp_params) {
    return "call " . $sp_name . "(" . $sp_params . ");";
}

function getTemperatureData($STdate, $EDdate, $deviceid, $interval, $stime, $etime, $customerno, $noOfSensors, $switchTo) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $graph_days = array();
    $graph_days_ig = array();
    $veh_temp_details = array();
    $unit = getunitdetailspdf($customerno, $deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $graph_data = null;
            $STdate = date("Y-m-d", strtotime($STdate));
            if ($userdate == $STdate) {
                $f_STdate = $userdate . " " . $stime . ":00";
            } else {
                $f_STdate = $userdate . " 00:00:00";
            }
            $EDdate = date("Y-m-d", strtotime($EDdate));
            if ($userdate == $EDdate) {
                $f_EDdate = $userdate . " " . $etime . ":00";
            } else {
                $f_EDdate = $userdate . " 23:59:59";
            }
            $location = "";
            if (!empty($unit->unitno)) {
                $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            }
            // echo var_dump($location);
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $return = gettempdata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, true, $unit, $customerno, null, $noOfSensors, $switchTo);
                // echo "count : ". count($return);
                // die();
                $data = $return[0];
            }
            // print_r($data);
            if ($data != null && count($data) > 1) {
                $days = array_merge($days, $data);
            }
        }
    }
    if ($days != null && count($days) > 0) {
        if (isset($return['vehicleid'])) {
            $veh_temp_details = getunitdetailsfromvehid($return['vehicleid'], $customerno);
        }
        $finalreport = formatTemperatureData($days, $unit, $veh_temp_details, $noOfSensors, $switchTo);
    }
    // print_r($finalreport);
    return $finalreport;
}

function formatTemperatureData($datarows, $unitdetails, $veh_temp_details = null, $noOfSensors, $switchTo) {
    $i = 0;
    $totalrow = 0;
    $totalminute = 0;
    $lastdate = null;
    $display = '';
    $cm = new CustomerManager();
    $details = $cm->getcustomerdetail_byid($_SESSION['customerno']);

    $mutedetails = getunitmutedetails($unitdetails->vehicleid, $unitdetails->uid);
    $tempconversion = new TempConversion();
    $tempconversion->unit_type = $unitdetails->get_conversion;
    $tempconversion->use_humidity = $details->use_humidity;
    $tempconversion->switch_to = $switchTo;
    $temp1_min = $temp1_max = $temp2_min = $temp2_max = $temp3_min = $temp3_max = $temp4_min = $temp4_max = '';
    if (isset($datarows)) {
        $sensorData = array();
        $readings = array();
        $result = array();

        $sensorNo = 1;
        while ($sensorNo <= $noOfSensors) {
            $sensorData['temp' . $sensorNo] = array();
            $sensorData['temp' . $sensorNo]['sensorName'] = getName_ByType($unitdetails->{"n" . $sensorNo});
            if ($sensorData['temp' . $sensorNo]['sensorName'] == '') {
                $sensorData['temp' . $sensorNo]['sensorName'] = 'Temperature ' . $sensorNo;
            }
            $sensorData['temp' . $sensorNo]['minMax'] = get_min_max_temp(1, $veh_temp_details, $noOfSensors);
            $readings['temp' . $sensorNo]['mutedCount'] = 0;
            $readings['temp' . $sensorNo]['badCount'] = 0;
            $readings['temp' . $sensorNo]['nonMutedCount'] = 0;
            $readings['temp' . $sensorNo]['abvMaxCount'] = 0;
            $readings['temp' . $sensorNo]['totalCount'] = 0;
            $sensorNo++;
        }
        foreach ($datarows as $k => $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $i++;
            }
            //Removing Date Details From DateTime
            $change->lastupdated = $change->starttime;
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            $starttime_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));

            $sensorNo = 1;
            while ($sensorNo <= $noOfSensors) {
                $sensorData['unit'] = $unitdetails;
                if (isset($mutedetails)) {
                    foreach ($mutedetails as $rowdata) {
                        if ($rowdata->temp_type == $unitdetails->{"tempsen" . $sensorNo} && strtotime($change->lastupdated) >= strtotime($rowdata->mute_starttime) && strtotime($change->lastupdated) <= strtotime($rowdata->mute_endtime)
                        ) {
                            $readings['temp' . $sensorNo]['mutedCount']++;
                        }
                    }
                } else {
                    $sensorData['temp' . $sensorNo]['target'] = "analog" . $unitdetails->{"tempsen" . $sensorNo};
                    if ($unitdetails->{"tempsen" . $sensorNo} != 0 && $change->{$sensorData['temp' . $sensorNo]['target']} != 0) {
                        $tempconversion->rawtemp = $change->{$sensorData['temp' . $sensorNo]['target']};
                        $temp = getTempUtil($tempconversion);
                        if ($temp > NORMAL_MAX_TEMP || $temp < NORMAL_MIN_TEMP) {
                            $readings['temp' . $sensorNo]['badCount']++;
                        } else {
                            $temp_min = set_summary_min_temp($temp);
                            $temp_max = set_summary_max_temp($temp);
                            if ($temp > $sensorData['temp' . $sensorNo]['minMax']['temp_max_limit'] || $temp < $sensorData['temp' . $sensorNo]['minMax']['temp_min_limit']) {
                                $readings['temp' . $sensorNo]['abvMaxCount']++;
                            }
                        }
                    } else {
                        $readings['temp' . $sensorNo]['badCount']++;
                    }
                    $readings['temp' . $sensorNo]['nonMutedCount']++;
                }
                $readings['temp' . $sensorNo]['totalCount']++;
                $sensorData["temp" . $sensorNo]['readings'] = $readings['temp' . $sensorNo];
                $sensorNo++;
            }
            $totalrow++;
        }
        $sensorNo = 1;
        while ($sensorNo <= $noOfSensors) {
            $sensorStats = array(
                'nonComplianceCount' => $sensorData["temp" . $sensorNo]['readings']['abvMaxCount'],
                'goodCount' => $sensorData["temp" . $sensorNo]['readings']['nonMutedCount'] - $sensorData["temp" . $sensorNo]['readings']['badCount'],
                'totalCount' => $sensorData["temp" . $sensorNo]['readings']['totalCount'],
                'badCount' => $sensorData["temp" . $sensorNo]['readings']['badCount'],
                'mutedCount' => $sensorData["temp" . $sensorNo]['readings']['mutedCount']
            );
            if ($sensorData["temp" . $sensorNo]['readings']['nonMutedCount'] == 0) {
                $sensorStats['complianceCount'] = "Not Applicable";
                $sensorStats['compliancePercent'] = "Not Applicable";
            } else {
                $sensorStats['complianceCount'] = $sensorStats['goodCount'] - $sensorStats['nonComplianceCount'];
                if ($sensorStats['goodCount'] > 0) {
                    $sensorStats['compliancePercent'] = round($sensorStats['complianceCount'] / $sensorStats['goodCount'] * 100, 2);
                } else {
                    $sensorStats['compliancePercent'] = "0";
                }
            }
            $sensorData["temp" . $sensorNo]['sensorStats'] = $sensorStats;
            $sensorNo++;
        }
        $result[] = $sensorData;
    }
    return $sensorData;
}

function getAnnexureDetails($objRequest) {
    $startDate = $objRequest->startDate;
    $endDate = $objRequest->endDate;
    $customerNo = $objRequest->customerNo;

    $totaldays = gendays_cmn($startDate, $endDate);
    $location = "../../customer/" . $customerNo . "/reports/annexure.sqlite";
    $DATA = null;
    if (file_exists($location)) {
        $DATA = getAnnexureDetailsData($location, $totaldays);
    }
    return $DATA;
}

function getAnnexureDetailsData($location, $days) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $REPORT = array();

    if (isset($days)) {
        foreach ($days as $day) {
            $sqlday = date("dmy", strtotime($day));
            $query = "SELECT * from A$sqlday order by vehicleid ASC";
            $result = $db->query($query);
            if (isset($result) && $result != "") {
                foreach ($result as $row) {
                    $reportDate = date('d-M-Y', strtotime($day));
                    $dataArray = array();
                    $dataArray['reportdate'] = $reportDate;
                    $dataArray['unitId'] = $row['uid'];
                    $dataArray['vehicleId'] = $row['vehicleid'];
                    $dataArray['customerno'] = $row['customerno'];
                    $dataArray['isActive'] = ($row['isActive'] == 1) ? "W" : (($row['isActive'] == 0) ? "N" : "NA");
                    $dataArray['isTemperature'] = ($row['isTemperature'] == 1) ? "W" : (($row['isTemperature'] == 0) ? "N" : "NA");
                    $dataArray['isHumidity'] = ($row['isHumidity'] == 1) ? "W" : (($row['isHumidity'] == 0) ? "N" : "NA");
                    $dataArray['isDigital'] = ($row['isDigital'] == 1) ? "W" : (($row['isDigital'] == 0) ? "N" : "NA");
                    $REPORT[$reportDate][] = $dataArray;
                }
            }
        }
    }
    return $REPORT;
}

function getAnnexurePDF($customerno, $sdate, $edate) {
    $title = 'Annexure Report';
    $subTitle = array(
        "Start Date: $sdate",
        "End Date: $edate"
    );
    $finalreport = pdf_header($title, $subTitle);
    $vm = new VehicleManager($_SESSION['customerno']);
    $customer_vehicles = vehicles_array($vm->get_all_vehicles());

    $objRequest = new stdClass();
    $objRequest->startDate = $sdate;
    $objRequest->endDate = $edate;
    $objRequest->customerNo = $customerno;
    $annexureDetails = getAnnexureDetails($objRequest);
    $finalreport .= '<table id="search_table_2" align="center" style="font-size:15px; text-align:center;border-collapse:collapse;border:1px solid #000;">';
    $finalreport .= '<tr>
                                         <th style="border:1px solid #000;padding:3px;">Sr No</th>
                                         <th style="border-right:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;padding:3px;">Vehicle No</th>
                                         <th style="border-right:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;padding:3px;">Is Active</th>
                                         <th style="border-right:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;padding:3px;">Is Temperature</th>
                                         <th style="border-right:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;padding:3px;">Is Humidity</th>
                                         <th style="border-right:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;padding:3px;">Is Digital</th>
                                        </tr>';

    if (isset($annexureDetails) && !empty($annexureDetails)) {
        foreach ($annexureDetails as $subKey => $subValues) {
            $finalreport .= '<tr><th style="border:1px solid #000;padding:3px;" colspan="6">' . $subKey . '</th></tr>';
            $x = 1;
            $vehicleno = '';
            foreach ($subValues as $key => $values) {
                $vehicleno = isset($customer_vehicles[$values['vehicleId']]['vehno']) ? $customer_vehicles[$values['vehicleId']]['vehno'] : '';
                if ($vehicleno != '') {
                    $finalreport .= '<tr>';
                    $finalreport .= '<td>' . $x . '</td>';
                    $finalreport .= '<td>' . $vehicleno . '</td>';
                    $finalreport .= '<td>' . $values['isActive'] . '</td>';
                    $finalreport .= '<td>' . $values['isTemperature'] . '</td>';
                    $finalreport .= '<td>' . $values['isHumidity'] . '</td>';
                    $finalreport .= '<td>' . $values['isDigital'] . '</td>';
                    $finalreport .= '</tr>';
                    $x++;
                }
            }
        }
    } else {
        $finalreport .= '<tr><td colspan="5">Data Not Available</td></tr>';
    }
    $finalreport .= '</table>';
    echo $finalreport;
}

?>
