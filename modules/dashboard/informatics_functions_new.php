<?php

ini_set("memory_limit", "256M");
if (!isset($_SESSION)) {
    session_start();
}
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/UserManager.php';
include_once '../../lib/bo/UnitManager.php';
include_once '../../lib/comman_function/reports_func.php';

class VODatacap {

}

class VODatacap_i {

}


$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];

$groupwise_vehicles = groupBased_vehicles_cron($customerno, $userid);

$all_vehicles = array();
foreach ($groupwise_vehicles as $single_veh) {
    $all_vehicles[$single_veh->vehicleid] = array(
        'vehicleno' => $single_veh->vehicleno,
        'groupid' => $single_veh->groupid,
        'unitno' => $single_veh->unitno,
        'deviceid' => $single_veh->deviceid,
        'overspeed_limit' => $single_veh->overspeed_limit,
        'is_ac_opp' => $single_veh->is_ac_opp,
        'acsensor' => $single_veh->acsensor
    );
}

function get_daily_informatics($location, $days) {
    $objUnitManager = new UnitManager($_SESSION['customerno']);
    $arrUnitResult = $objUnitManager->getuid_all();
    //For PHP >= 5.5
    //$arrUids = array_column($arrUnitResult, 'uid');
    //For PHP >= 5.3
    $arrUids = array_map(function($element) {
        return $element->uid;
    }, $arrUnitResult);
    $path = "sqlite:$location";
    $db = new PDO($path);
    $REPORT = array();

    if (isset($days)) {
        foreach ($days as $day) {
            $sqlday = date("dmy", strtotime($day));
            $query = "SELECT * from A$sqlday  order by vehicleid ASC";
            $result = $db->query($query);
            if (isset($result) && $result != "") {
                foreach ($result as $row) {
                    if (in_array($row['uid'], $arrUids)) {
                        $Datacap = new VODatacap_i();
                        $Datacap->date = strtotime($day);
                        $Datacap->info_date = $day;
                        $Datacap->uid = $row['uid'];
                        $Datacap->tamper = isset($row['tamper']) ? $row['tamper'] : '';
                        $Datacap->overspeed = $row['overspeed'];
                        $Datacap->harsh_break = $row['harsh_break'];
                        $Datacap->sudden_acc = $row['sudden_acc'];
                        $Datacap->towing = $row['towing'];
                        $Datacap->totaldistance = $row['totaldistance'];
                        $Datacap->topspeed = $row['topspeed'];
                        $Datacap->topspeed_datetime = isset($row['topspeed_datetime']) ? $row['topspeed_datetime'] : '00:00:00';
                        $Datacap->fenceconflict = $row['fenceconflict'];
                        $Datacap->idletime = $row['idletime'];
                        $Datacap->genset = $row['genset'];
                        $Datacap->runningtime = $row['runningtime'];
                        $Datacap->vehicleid = $row['vehicleid'];
                        $Datacap->is_night_drive = isset($row['is_night_drive']) ? $row['is_night_drive'] : 0;
                        $Datacap->night_distance = isset($row['night_distance']) ? $row['night_distance'] : 0;
                        $Datacap->is_weekend_drive = isset($row['is_weekend_drive']) ? $row['is_weekend_drive'] : 0;
                        $Datacap->weekend_distance = isset($row['weekend_distance']) ? $row['weekend_distance'] : 0;
                        /* Get vehicle name from vehicleid */
                        $objVehicleManager = new VehicleManager($_SESSION['customerno']);
                        $Datacap->vehicleno = '';
                        $Datacap->unitno = '';
                        $vehicleDetails = $objVehicleManager->get_vehicle_details($row['vehicleid']);
                        if(isset($vehicleDetails) && !empty($vehicleDetails)){
                          $Datacap->vehicleno =  $vehicleDetails->vehicleno;
                          $Datacap->unitno = $vehicleDetails->unitno;
                        }

                        /* Get group info from mysql db */
                        $objVehGroupInfo = $objVehicleManager->getvehgroupinfo($row['vehicleid']);
                        $Datacap->branchid = $objVehGroupInfo->branchid;
                        $Datacap->branchname = $objVehGroupInfo->branchname;
                        $Datacap->zoneid = $objVehGroupInfo->zoneid;
                        $Datacap->zonename = $objVehGroupInfo->zonename;
                        $Datacap->regionid = $objVehGroupInfo->regionid;
                        $Datacap->regionname = $objVehGroupInfo->regionname;
                        if($Datacap->vehicleno!=''){
                            $REPORT[] = $Datacap;
                        }
                    }
                }
            }
        }
    }
    return $REPORT;
}

function get_daily_report_data($start_date, $end_date) {
    global $customerno, $all_vehicles;
    $arrFinalData = array();
    $totaldays = gendays_cmn($start_date, $end_date);
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if (!file_exists($location)) {
        return array('total' => 0);
    }
    $report_data = get_daily_informatics($location, $totaldays);
    //print_r($report_data);

    $tracked = 0;
    $highest_dis_trav_def = 0;
    $highest_dis_trav = array();
    $total_idle_time = $total_idle_time_final = 0;
    $total_running_time = 0;
    $idle_time_data = array();
    $idle_time_data_final = array();
    if (!empty($report_data)) {
        $groupwiseVehData = array();

        /* Informatics Reports Change - SS on 04 Dec 2015 */
        /* Overspeed Data - All Vehicle with overspeed count */
        $array = json_decode(json_encode($report_data), true);
        $overspeedsumVehicleWise = array_reduce($array, function ($result, $currentItem) {
            if (isset($result[$currentItem['vehicleid']])) {
                $result[$currentItem['vehicleid']]['overspeed'] += $currentItem['overspeed'];
            }
            else {
                $result[$currentItem['vehicleid']] = $currentItem;
                $result[$currentItem['vehicleid']]['max_topspeed'] = $currentItem['topspeed'];
                $result[$currentItem['vehicleid']]['max_topspeed_date'] = $currentItem['info_date'];
            }
            if ($currentItem['info_date'] != $result[$currentItem['vehicleid']]['max_topspeed_date'] && $currentItem['topspeed'] > $result[$currentItem['vehicleid']]['max_topspeed']) {
                $result[$currentItem['vehicleid']]['max_topspeed'] = $currentItem['topspeed'];
                $result[$currentItem['vehicleid']]['max_topspeed_date'] = $currentItem['info_date'];
            }
            //isset($a[$b['vehicleid']]) ? $a[$b['vehicleid']]['overspeed'] += $b['overspeed'] : $a[$b['vehicleid']] = $b;
            return $result;
        });

        /* Overspeed Data - 5 Top Speed Vehicles */
        $topspeedData = array();
        usort($overspeedsumVehicleWise, 'compare_values');
        $topspeedData = array_slice($overspeedsumVehicleWise, 0, 5, true);
        $topspeedData = array_filter($topspeedData, function($item) {
            return $item['max_topspeed'] > 0;
        });
        /* HarshBreak Data - All Vehicle with harsh break count */
        $array = json_decode(json_encode($report_data), true);
        $harshbreakVehicleWise = array_reduce($array, function ($result, $currentItem) {
            if (isset($result[$currentItem['vehicleid']])) {
                $result[$currentItem['vehicleid']]['harsh_break'] += $currentItem['harsh_break'];
            }
            else {
                $result[$currentItem['vehicleid']] = $currentItem;
                $result[$currentItem['vehicleid']]['distance_travelled'] = $currentItem['totaldistance'];
                $result[$currentItem['vehicleid']]['max_harsh_break_count'] = $currentItem['harsh_break'];
                $result[$currentItem['vehicleid']]['max_harsh_break_date'] = $currentItem['info_date'];
            }
            if ($currentItem['info_date'] != $result[$currentItem['vehicleid']]['max_harsh_break_date'] && $currentItem['harsh_break'] > $result[$currentItem['vehicleid']]['max_harsh_break_count']) {
                $result[$currentItem['vehicleid']]['distance_travelled'] = $currentItem['totaldistance'];
                $result[$currentItem['vehicleid']]['max_harsh_break_count'] = $currentItem['harsh_break'];
                $result[$currentItem['vehicleid']]['max_harsh_break_date'] = $currentItem['info_date'];
            }
            //isset($a[$b['vehicleid']]) ? $a[$b['vehicleid']]['overspeed'] += $b['overspeed'] : $a[$b['vehicleid']] = $b;
            return $result;
        });

        /* HarshBreak Data - 5 Top Vehicles */
        $topharshbreakData = array();
        usort($harshbreakVehicleWise, 'compare_harshbreak');
        $topharshbreakData = array_slice($harshbreakVehicleWise, 0, 5, true);
        $topharshbreakData = array_filter($topharshbreakData, function($item) {
            return $item['max_harsh_break_count'] > 0;
        });

        /* Acceleration Data - All Vehicle with harsh break count */
        $array = json_decode(json_encode($report_data), true);
        $accelerationVehicleWise = array_reduce($array, function ($result, $currentItem) {
            if (isset($result[$currentItem['vehicleid']])) {
                $result[$currentItem['vehicleid']]['sudden_acc'] += $currentItem['sudden_acc'];
            }
            else {
                $result[$currentItem['vehicleid']] = $currentItem;
                $result[$currentItem['vehicleid']]['max_sudden_acc_count'] = $currentItem['sudden_acc'];
                $result[$currentItem['vehicleid']]['max_sudden_acc_date'] = $currentItem['info_date'];
            }
            if ($currentItem['info_date'] != $result[$currentItem['vehicleid']]['max_sudden_acc_date'] && $currentItem['sudden_acc'] > $result[$currentItem['vehicleid']]['max_sudden_acc_count']) {
                $result[$currentItem['vehicleid']]['max_sudden_acc_count'] = $currentItem['sudden_acc'];
                $result[$currentItem['vehicleid']]['max_sudden_acc_date'] = $currentItem['info_date'];
            }
            //isset($a[$b['vehicleid']]) ? $a[$b['vehicleid']]['overspeed'] += $b['overspeed'] : $a[$b['vehicleid']] = $b;
            return $result;
        });

        /* Acceleration Data - 5 Top Vehicles */
        $topaccelarationData = array();
        usort($accelerationVehicleWise, 'compare_accelaration');
        $topaccelarationData = array_slice($accelerationVehicleWise, 0, 5, true);
        $topaccelarationData = array_filter($topaccelarationData, function($item) {
            return $item['max_sudden_acc_count'] > 0;
        });

        /* Night Drive Data - All Vehicle with night drive count */
        $array = json_decode(json_encode($report_data), true);
        $nightDriveVehicleWise = array_reduce($array, function ($result, $currentItem) {
            if (isset($result[$currentItem['vehicleid']])) {
                $result[$currentItem['vehicleid']]['night_drive'] += ($currentItem['is_night_drive']) ? 1 : 0;
            }
            else {
                $result[$currentItem['vehicleid']] = $currentItem;
                if (!isset($result[$currentItem['vehicleid']]['night_drive'])) {
                    $result[$currentItem['vehicleid']]['night_drive'] = 0;
                }
                $result[$currentItem['vehicleid']]['night_drive'] += ($currentItem['is_night_drive']) ? 1 : 0;
                $result[$currentItem['vehicleid']]['max_nightdrive_distance'] = $currentItem['night_distance'];
                $result[$currentItem['vehicleid']]['max_nightdrive_date'] = $currentItem['info_date'];
            }
            if ($currentItem['info_date'] != $result[$currentItem['vehicleid']]['max_nightdrive_date'] && $currentItem['night_distance'] > $result[$currentItem['vehicleid']]['max_nightdrive_distance']) {
                $result[$currentItem['vehicleid']]['max_nightdrive_distance'] = $currentItem['night_distance'];
                $result[$currentItem['vehicleid']]['max_nightdrive_date'] = $currentItem['info_date'];
            }
            return $result;
        });

        /* Night Drive Data - 5 Top Vehicles */
        $topNightDriveData = array();
        usort($nightDriveVehicleWise, 'compare_nightdrive');
        $topNightDriveData = array_slice($nightDriveVehicleWise, 0, 5, true);
        $topNightDriveData = array_filter($topNightDriveData, function($item) {
            return $item['max_nightdrive_distance'] > 0;
        });

        $array = json_decode(json_encode($report_data), true);
        $weekendDriveVehicleWise = array_reduce($array, function ($result, $currentItem) {
            if (isset($result[$currentItem['vehicleid']])) {
                $result[$currentItem['vehicleid']]['weekend_drive'] += ($currentItem['is_weekend_drive']) ? 1 : 0;
                $isWeekendDrive = $result[$currentItem['vehicleid']]['weekend_drive'];
                $totalWeekendDistance = round($currentItem['weekend_distance'] / 1000, 2);
                if ($totalWeekendDistance > 45 && $isWeekendDrive != 0) {
                    $result[$currentItem['vehicleid']]['isRed'] += 1;
                }
                else if ($totalWeekendDistance <= 45 && $totalWeekendDistance > 25 && ($isWeekendDrive != 0 && $result[$currentItem['vehicleid']]['isRed'] == 0)) {
                    $result[$currentItem['vehicleid']]['isYellow'] += 1;
                }
                else if ($isWeekendDrive != 0 && $result[$currentItem['vehicleid']]['isRed'] == 0 && $result[$currentItem['vehicleid']]['isYellow'] == 0) {
                    $result[$currentItem['vehicleid']]['isGreen'] += 1;
                }
            }
            else {
                $totalWeekendDistance = round($currentItem['weekend_distance'] / 1000, 2);
                $result[$currentItem['vehicleid']] = $currentItem;
                if (!isset($result[$currentItem['vehicleid']]['weekend_drive'])) {
                    $result[$currentItem['vehicleid']]['weekend_drive'] = 0;
                    $result[$currentItem['vehicleid']]['isRed'] = 0;
                    $result[$currentItem['vehicleid']]['isYellow'] = 0;
                    $result[$currentItem['vehicleid']]['isGreen'] = 0;
                }
                $result[$currentItem['vehicleid']]['weekend_drive'] += ($currentItem['is_weekend_drive']) ? 1 : 0;
                $result[$currentItem['vehicleid']]['max_weekenddrive_distance'] = $currentItem['weekend_distance'];
                $result[$currentItem['vehicleid']]['max_weekenddrive_date'] = $currentItem['info_date'];
            }
            if ($currentItem['info_date'] != $result[$currentItem['vehicleid']]['max_weekenddrive_date'] && $currentItem['weekend_distance'] > $result[$currentItem['vehicleid']]['max_weekenddrive_distance']) {
                $result[$currentItem['vehicleid']]['max_weekenddrive_distance'] = $currentItem['weekend_distance'];
                $result[$currentItem['vehicleid']]['max_weekenddrive_date'] = $currentItem['info_date'];
            }
            return $result;
        });


        $array = json_decode(json_encode($report_data), true);
        $weekendDriveVehicleWiseTotal = array_reduce($array, function ($result, $currentItem) {
            if (isset($result[$currentItem['vehicleid']])) {

                $result[$currentItem['vehicleid']]['weekend_drive'] += ($currentItem['is_weekend_drive']) ? 1 : 0;
                $isWeekendDrive = $result[$currentItem['vehicleid']]['weekend_drive'];
                $totalWeekendDistance = round($currentItem['weekend_distance'] / 1000, 2);
                $objCurrentDate = new DateTime($currentItem['info_date']);
                $isCurrentDaySunday = ($objCurrentDate->format('N') == 7) ? 1 : 0;
                if ($isCurrentDaySunday) {
                    $objWeekendInfo = new stdClass();
                    $objWeekendInfo->weekend_date = $currentItem['info_date'];
                    $objWeekendInfo->weekend_distance = $totalWeekendDistance;
                    $result[$currentItem['vehicleid']]['weekend_info'][] = $objWeekendInfo;
                }
            }
            else {
                $result[$currentItem['vehicleid']] = $currentItem;
                $result[$currentItem['vehicleid']]['weekend_info'] = array();
                if (!isset($result[$currentItem['vehicleid']]['weekend_drive'])) {
                    $result[$currentItem['vehicleid']]['weekend_drive'] = 0;
                }
                //print_r($currentItem);
                $result[$currentItem['vehicleid']]['weekend_drive'] += ($currentItem['is_weekend_drive']) ? 1 : 0;
                $result[$currentItem['vehicleid']]['max_weekenddrive_distance'] = $currentItem['weekend_distance'];
                $result[$currentItem['vehicleid']]['max_weekenddrive_date'] = $currentItem['info_date'];
            }
            if ($currentItem['info_date'] != $result[$currentItem['vehicleid']]['max_weekenddrive_date'] && $currentItem['weekend_distance'] > $result[$currentItem['vehicleid']]['max_weekenddrive_distance']) {
                $result[$currentItem['vehicleid']]['max_weekenddrive_distance'] = $currentItem['weekend_distance'];
                $result[$currentItem['vehicleid']]['max_weekenddrive_date'] = $currentItem['info_date'];
            }
            return $result;
        });
        //echo "<pre>";
        //print_r($weekendDriveVehicleWiseTotal);

        /* Weekend Drive Data - 5 Top Vehicles */
        $topWeekendDriveData = array();
        usort($weekendDriveVehicleWise, 'compare_weekenddrive');
        $topWeekendDriveData = array_slice($weekendDriveVehicleWise, 0, 5, true);
        $topWeekendDriveData = array_filter($topWeekendDriveData, function($item) {
            return $item['max_weekenddrive_distance'] > 0;
        });

        /* Calculate Tracking Days */
        $trackingDays = array();
        foreach ($overspeedsumVehicleWise as $data) {
            $trackCount = 0;
            foreach ($totaldays as $day) {
                $date = date('Y-m-d', strtotime($day));
                $location = "../../customer/$customerno/unitno/" . $data['unitno'] . "/sqlite/$date.sqlite";
                if (file_exists($location)) {
                    $trackCount +=1;
                }
            }
            $data['trackingdays'] = $trackCount;
            $trackingDays[] = $data;
        }
        //print_r($trackingDays);

        $arrFinalData['overspeedsumVehicleWise'] = $overspeedsumVehicleWise;
        $arrFinalData['overspeedTopSpeed'] = $topspeedData;
        $arrFinalData['harshbreakVehicleWise'] = $harshbreakVehicleWise;
        $arrFinalData['topharshbreakData'] = $topharshbreakData;
        $arrFinalData['accelerationVehicleWise'] = $accelerationVehicleWise;
        $arrFinalData['topaccelarationData'] = $topaccelarationData;
        $arrFinalData['nightDriveVehicleWise'] = $nightDriveVehicleWise;
        $arrFinalData['topNightDriveData'] = $topNightDriveData;
        $arrFinalData['weekendDriveVehicleWise'] = $weekendDriveVehicleWise;
        $arrFinalData['weekendDriveVehicleWiseTotal'] = $weekendDriveVehicleWiseTotal;
        $arrFinalData['topWeekendDriveData'] = $topWeekendDriveData;
        $arrFinalData['trackingDays'] = $trackingDays;
    }
    return $arrFinalData;
}

function getunitno($vehicleid, $customerno) {
    $um = new UnitManager($customerno);
    $unitno = $um->getunitnofromdeviceid($vehicleid);
    return $unitno;
}

function getTopSpeedDetails($vehicleid, $infodate, $speed, $customerno) {
    $day = date('Y-m-d', strtotime($infodate));
    $unitno = getunitno($vehicleid, $customerno);
    $lastUpdated = '';
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$day.sqlite";
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $query = "SELECT * from vehiclehistory where curspeed=$speed order by vehicleid ASC limit 1";
        $result = $db->query($query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $lastUpdated = $row['lastupdated'];
            }
        }
    }
    return $lastUpdated;
}

function validate_input($s_date, $e_date) {
    if (strtotime("+1 months", strtotime($s_date)) < strtotime($e_date)) {
        //echo "Please Select Dates With Difference Of Not More Than 1 Months";
        //return false;
    }
    elseif (strtotime($s_date) > strtotime($e_date)) {
        echo "Please Check The Dates";
        return false;
    }
    return true;
}

function compare_values($a, $b) {
    return strnatcmp($b['max_topspeed'], $a['max_topspeed']); // DESC array
    //return strnatcmp($a['topspeed'], $b['topspeed']); // ASC array
    // use with usort
}

function compare_harshbreak($a, $b) {
    return strnatcmp($b['harsh_break'], $a['harsh_break']); // DESC array
}

function compare_accelaration($a, $b) {
    return strnatcmp($b['sudden_acc'], $a['sudden_acc']); // DESC array
}

function compare_nightdrive($a, $b) {
    return strnatcmp($b['max_nightdrive_distance'], $a['max_nightdrive_distance']); // DESC array
}

function compare_weekenddrive($a, $b) {
    return strnatcmp($b['max_weekenddrive_distance'], $a['max_weekenddrive_distance']); // DESC array
}

?>
