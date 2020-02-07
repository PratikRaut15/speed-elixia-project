<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
include_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
include_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
include_once $RELATIVE_PATH_DOTS . 'lib/comman_function/reports_func.php';
include_once $RELATIVE_PATH_DOTS . 'lib/components/ajaxpage.inc.php';
include '../common/map_common_functions.php';
if (!isset($_SESSION)) {
    session_start();
}
function getvehicles() {
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $devices = $devicemanager->devicesformapping();
    return $devices;
}

function getvehicle_byID($vehicleid) {
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $devices = $devicemanager->deviceformapping($vehicleid);
    return $devices;
}

function getdate_IST() {
    $ServerDate_IST = strtotime(date("Y-m-d H:i:s"));
    return $ServerDate_IST;
}

function getunitdetailsfromvehid($deviceid) {
    $um = new UnitManager($_SESSION['customerno']);
    $unitno = $um->getunitdetailsfromvehid($deviceid);
    return $unitno;
}

function route_histNewRefined($vehicleid, $SDdate, $EDdate, $holdtime, $flag, $toggleTripId = 0) {
    $distancetravelled = 0;
    $device = Array();
    $device2 = Array();
    $totaldays = Array();
    $currentdate = date("Y-m-d H:i:s");
    $unit = getunitdetailsfromvehid($vehicleid);
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $SDdate = GetSafeValueString($SDdate, 'string');
    $EDdate = GetSafeValueString($EDdate, 'string');
    $holdtime = GetSafeValueString($holdtime, 'int');
    $SDdate = $startTime = date('Y-m-d H:i:s', strtotime($SDdate));
    $EDdate = $endTime = date('Y-m-d H:i:s', strtotime($EDdate));
    $startdate = $SDdate;
    $enddate = $EDdate;
    $SDdate = date('Y-m-d', strtotime($SDdate));

    // Added for Mongodb Implementation
    // $startDate = date('Y-m-d', strtotime($SDdate));
    // $endDate = date('Y-m-d', strtotime($EDdate));
    // Added for Mongodb Implementation

    
    $EDdate = date('Y-m-d', strtotime($EDdate));
    if ($SDdate != $EDdate) {
        $devicedata[] = NULL;
        $STdate = $startdate;
        $STdate_end = date('Y-m-d', strtotime('+1 day', strtotime($STdate)));
        $STdate_end .= " 23:59:59";
        $counter = 0;
        while (strtotime($STdate) < strtotime($EDdate)) {
            $totaldays[$counter][0] = $STdate;
            $totaldays[$counter][1] = $STdate_end;
            $STdate = date('Y-m-d', strtotime('+1 day', strtotime($STdate)));
            $STdate_end = $STdate . " 23:59:59";
            $counter += 1;
        }
        $totaldays[$counter][0] = date('Y-m-d', strtotime($enddate));
        $totaldays[$counter][1] = date('Y-m-d H:i:s', strtotime($enddate));
    } else {
        $totaldays[0][0] = $startdate;
        $totaldays[0][1] = $enddate;
    }
    if (isset($totaldays)) {
        $cumulative = 0;
        foreach ($totaldays as $Date) {
            $date = date("Y-m-d", strtotime($Date[0]));
            $unitmanager = new UnitManager($_SESSION['customerno']);
            $unitno = $unitmanager->getunitno($vehicleid);
            $customerno = $_SESSION['customerno'];
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                //$device = getdatafromsqlite_newRefined($location, $Date, $vehicleid, $device, $holdtime, $distancetravelled, $unitno, $unit, $flag);
                
                if($_SERVER['REMOTE_ADDR'] == '::1'){
                    $reports = getReportDetailsFromMongoDb($customerno,$date ,$startTime,$endTime);
                    if(!empty($reports)){
                        $arrData = json_decode(json_encode($reports->data), true);
                        $device = getDataFromMongo($arrData,$Date, $vehicleid, $device, $holdtime, $distancetravelled, $unitno, $unit, $flag);
                    }
                
                }
                
                //print_r($device);
                foreach ($device as $thisdevice) {
                    
                    /*
                    if($thisdevice->cumulative < $distancetravelled)
                    {
                    $max = GetOdometerMax($date, $unitno);
                    $thisdevice->cumulative = $thisdevice->cumulative + $max;
                    }
                     *
                     */
                    $cumulative = $thisdevice->cumulative - $distancetravelled;
                }
                $distancetravelled += $cumulative;
            }
        }
    }
    if (isset($device2) && $device2 != NULL) {
        $devicedata = array_merge($device, $device2);
    } else {
        $devicedata = array_merge($device);
    }
    $finaloutput = array();
    //print_r($devicedata);die();
    if (isset($devicedata) && count($devicedata) > 0) {
        $finaloutput = vehicleonmap_route_history($devicedata, $unit);
    } else {
        $Date = $totaldays[0];
        $date = date("Y-m-d", strtotime($Date[0]));
        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
        if (file_exists($location)) {
            $unitmanager = new UnitManager($_SESSION['customerno']);
            $unitno = $unitmanager->getunitno($vehicleid);
            $customerno = $_SESSION['customerno'];
            $location = "sqlite:" . $location;
            $device = firstmappingforvehiclebydate_fromsqlite_newRefined($location, $Date, $unit);
        }
        $devicedata[] = $device;
        if (count(end($totaldays)) > 0) {
            $Date = end($totaldays);
        }
        $date = date("Y-m-d", strtotime($Date[0]));
        if (file_exists($location)) {
            $unitmanager = new UnitManager($_SESSION['customerno']);
            $unitno = $unitmanager->getunitno($vehicleid);
            $customerno = $_SESSION['customerno'];
            $location = "sqlite:" . $location;
            $device = firstmappingforvehiclebydate_fromsqlite_newRefined($location, $Date, $unit);
        }
        $devicedata[] = $device;
        $finaloutput = vehicleonmap_route_history($devicedata);
    }
    //print_r($finaloutput);
    $ajaxpage = new ajaxpage();
    $ajaxpage->SetResult($finaloutput);
    $ajaxpage->Render();
}

function route_histNew($vehicleid, $SDdate, $EDdate, $SDTime, $EDTime) {
    $device = Array();
    $device2 = Array();
    $totaldays = Array();
    $currentdate = date("Y-m-d H:i:s");
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $SDdate = GetSafeValueString($SDdate, 'string');
    $EDdate = GetSafeValueString($EDdate, 'string');
    $STime = GetSafeValueString($SDTime, "string");
    $ETime = GetSafeValueString($EDTime, "string");
    //$Smin = GetSafeValueString($Smin,"string");
    //$Emin = GetSafeValueString($Emin,"string");
    $SDdate = date('Y-m-d', strtotime($SDdate));
    $EDdate = date('Y-m-d', strtotime($EDdate));
    $startdate = $SDdate . " " . $STime . ":00";
    $enddate = $EDdate . " " . $ETime . ":59";
    if ($SDdate != $EDdate) {
        $devicedata[] = NULL;
        $STdate = $startdate;
        $STdate_end = date('Y-m-d', strtotime('+1 day', strtotime($STdate)));
        $STdate_end .= " 23:59:59";
        $counter = 0;
        while (strtotime($STdate) < strtotime($EDdate)) {
            $totaldays[$counter][0] = $STdate;
            $totaldays[$counter][1] = $STdate_end;
            $STdate = date('Y-m-d', strtotime('+1 day', strtotime($STdate)));
            $STdate_end = $STdate . " 23:59:59";
            $counter += 1;
        }
        $totaldays[$counter][0] = date('Y-m-d', strtotime($enddate));
        $totaldays[$counter][1] = date('Y-m-d H:i:s', strtotime($enddate));
    } else {
        $totaldays[0][0] = $startdate;
        $totaldays[0][1] = $enddate;
    }
    if (isset($totaldays)) {
        foreach ($totaldays as $Date) {
            $date = date("Y-m-d", strtotime($Date[0]));
            $unitmanager = new UnitManager($_SESSION['customerno']);
            $unitno = $unitmanager->getunitno($vehicleid);
            $customerno = $_SESSION['customerno'];
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $device = getdatafromsqlite($location, $Date, $vehicleid, $device);
            }
        }
    }
    if (isset($device2) && $device2 != NULL) {
        $devicedata = array_merge($device, $device2);
    } else {
        $devicedata = array_merge($device);
    }
    $finaloutput = array();
    if (isset($devicedata) && count($devicedata) > 0) {
        $finaloutput = vehicleonmap($devicedata);
    } else {
        $Date = $totaldays[0];
        $date = date("Y-m-d", strtotime($Date[0]));
        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
        if (file_exists($location)) {
            $unitmanager = new UnitManager($_SESSION['customerno']);
            $unitno = $unitmanager->getunitno($vehicleid);
            $customerno = $_SESSION['customerno'];
            $location = "sqlite:" . $location;
            $device = firstmappingforvehiclebydate_fromsqlite($location, $Date, $vehicleid);
        }
        $devicedata[] = $device;
        if (count(end($totaldays)) > 0) {
            $Date = end($totaldays);
        }
        $date = date("Y-m-d", strtotime($Date[0]));
        if (file_exists($location)) {
            $unitmanager = new UnitManager($_SESSION['customerno']);
            $unitno = $unitmanager->getunitno($vehicleid);
            $customerno = $_SESSION['customerno'];
            $location = "sqlite:" . $location;
            $device = firstmappingforvehiclebydate_fromsqlite($location, $Date, $vehicleid);
        }
        $devicedata[] = $device;
        $finaloutput = vehicleonmap($devicedata);
    }
    $ajaxpage = new ajaxpage();
    $ajaxpage->SetResult($finaloutput);
    $ajaxpage->Render();
}

function get_checkpoint_from_chkmanage($vehicleid) {
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->get_checkpoint_from_chkmanage($vehicleid);
    //return $checkpoints;
    $ajaxpage = new ajaxpage();
    $ajaxpage->SetResult($checkpoints);
    $ajaxpage->Render();
}

function route_hist($vehicleid, $SDdate, $EDdate, $Shour, $Ehour, $Smin, $Emin) {
    $device = Array();
    $device2 = Array();
    $totaldays = Array();
    $currentdate = date("Y-m-d H:i:s");
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $SDdate = GetSafeValueString($SDdate, 'string');
    $EDdate = GetSafeValueString($EDdate, 'string');
    $Shour = GetSafeValueString($Shour, "string");
    $Ehour = GetSafeValueString($Ehour, "string");
    $Smin = GetSafeValueString($Smin, "string");
    $Emin = GetSafeValueString($Emin, "string");
    $SDdate = date('Y-m-d', strtotime($SDdate));
    $EDdate = date('Y-m-d', strtotime($EDdate));
    if ($Shour == 24) {
        $Shour = "23:59:59";
        $startdate = $SDdate . " " . $Shour;
    } else {
        $startdate = $SDdate . " " . $Shour . ":" . $Smin . ":00";
    }
    if ($Ehour == 24) {
        $Ehour = "23:59:59";
        $enddate = $EDdate . " " . $Ehour;
    } else {
        $enddate = $EDdate . " " . $Ehour . ":" . $Emin . ":00";
    }
    if ($SDdate != $EDdate) {
        $devicedata[] = NULL;
        $STdate = $startdate;
        $STdate_end = date('Y-m-d', strtotime('+1 day', strtotime($STdate)));
        $STdate_end .= " 23:59:59";
        $counter = 0;
        while (strtotime($STdate) < strtotime($EDdate)) {
            $totaldays[$counter][0] = $STdate;
            $totaldays[$counter][1] = $STdate_end;
            $STdate = date('Y-m-d', strtotime('+1 day', strtotime($STdate)));
            $STdate_end = $STdate . " 23:59:59";
            $counter += 1;
        }
        $totaldays[$counter][0] = date('Y-m-d', strtotime($enddate));
        $totaldays[$counter][1] = date('Y-m-d H:i:s', strtotime($enddate));
    } else {
        $totaldays[0][0] = $startdate;
        $totaldays[0][1] = $enddate;
    }
    if (isset($totaldays)) {
        foreach ($totaldays as $Date) {
            $date = date("Y-m-d", strtotime($Date[0]));
            $unitmanager = new UnitManager($_SESSION['customerno']);
            $unitno = $unitmanager->getunitno($vehicleid);
            $customerno = $_SESSION['customerno'];
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $device = getdatafromsqlite($location, $Date, $vehicleid, $device);
            }
        }
    }
    if (isset($device2) && $device2 != NULL) {
        $devicedata = array_merge($device, $device2);
    } else {
        $devicedata = array_merge($device);
    }
    $finaloutput = array();
    if (isset($devicedata) && count($devicedata) > 0) {
        $finaloutput = vehicleonmap($devicedata);
    } else {
        $Date = $totaldays[0];
        $date = date("Y-m-d", strtotime($Date[0]));
        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
        if (file_exists($location)) {
            $unitmanager = new UnitManager($_SESSION['customerno']);
            $unitno = $unitmanager->getunitno($vehicleid);
            $customerno = $_SESSION['customerno'];
            $location = "sqlite:" . $location;
            $device = firstmappingforvehiclebydate_fromsqlite($location, $Date, $vehicleid);
        }
        $devicedata[] = $device;
        if (count(end($totaldays)) > 0) {
            $Date = end($totaldays);
        }
        $date = date("Y-m-d", strtotime($Date[0]));
        if (file_exists($location)) {
            $unitmanager = new UnitManager($_SESSION['customerno']);
            $unitno = $unitmanager->getunitno($vehicleid);
            $customerno = $_SESSION['customerno'];
            $location = "sqlite:" . $location;
            $device = firstmappingforvehiclebydate_fromsqlite($location, $Date, $vehicleid);
        }
        $devicedata[] = $device;
        $finaloutput = vehicleonmap($devicedata);
    }
    $ajaxpage = new ajaxpage();
    $ajaxpage->SetResult($finaloutput);
    $ajaxpage->Render();
}

function route_hist_trip($vehicleid, $SDdate, $EDdate, $Shour, $Ehour) {
    $device = Array();
    $device2 = Array();
    $totaldays = Array();
    $date = new Date();
    $currentdate = date("Y-m-d H:i:s");
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $SDdate = GetSafeValueString($SDdate, 'string');
    $EDdate = GetSafeValueString($EDdate, 'string');
    $Shour = GetSafeValueString($Shour, "string");
    $Ehour = GetSafeValueString($Ehour, "string");
    $SDdate = date('Y-m-d', strtotime($SDdate));
    $EDdate = date('Y-m-d', strtotime($EDdate));
    if ($Shour == 24) {
        $Shour = "23:59:59";
        $startdate = $SDdate . " " . $Shour;
    } else {
        $startdate = $SDdate . " " . $Shour;
    }
    if ($Ehour == 24) {
        $Ehour = "23:59:59";
        $enddate = $EDdate . " " . $Ehour;
    } else {
        $enddate = $EDdate . " " . $Ehour;
    }
    if ($SDdate != $EDdate) {
        $devicedata[] = NULL;
        $STdate = $startdate;
        $STdate_end = date('Y-m-d', strtotime('+1 day', strtotime($STdate)));
        $STdate_end .= " 23:59:59";
        $counter = 0;
        while (strtotime($STdate) < strtotime($EDdate)) {
            $totaldays[$counter][0] = $STdate;
            $totaldays[$counter][1] = $STdate_end;
            $STdate = date('Y-m-d', strtotime('+1 day', strtotime($STdate)));
            $STdate_end = $STdate . " 23:59:59";
            $counter += 1;
        }
        $totaldays[$counter][0] = date('Y-m-d', strtotime($enddate));
        $totaldays[$counter][1] = date('Y-m-d H:i:s', strtotime($enddate));
    } else {
        $totaldays[0][0] = $startdate;
        $totaldays[0][1] = $enddate;
    }
    if (isset($totaldays)) {
        foreach ($totaldays as $Date) {
            $date = date("Y-m-d", strtotime($Date[0]));
            $unitmanager = new UnitManager($_SESSION['customerno']);
            $unitno = $unitmanager->getunitno($vehicleid);
            $customerno = $_SESSION['customerno'];
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $device = getdatafromsqlite($location, $Date, $vehicleid, $device);
            }
        }
    }
    if (isset($device2) && $device2 != NULL) {
        $devicedata = array_merge($device, $device2);
    } else {
        $devicedata = array_merge($device);
    }
    $finaloutput = array();
    if (isset($devicedata) && count($devicedata) > 0) {
        $finaloutput = vehicleonmap($devicedata);
    } else {
        $Date = $totaldays[0];
        $date = date("Y-m-d", strtotime($Date[0]));
        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
        if (file_exists($location)) {
            $unitmanager = new UnitManager($_SESSION['customerno']);
            $unitno = $unitmanager->getunitno($vehicleid);
            $customerno = $_SESSION['customerno'];
            $location = "sqlite:" . $location;
            $device = firstmappingforvehiclebydate_fromsqlite($location, $Date, $vehicleid);
        }
        $devicedata[] = $device;
        if (count(end($totaldays)) > 0) {
            $Date = end($totaldays);
        }
        $date = date("Y-m-d", strtotime($Date[0]));
        if (file_exists($location)) {
            $unitmanager = new UnitManager($_SESSION['customerno']);
            $unitno = $unitmanager->getunitno($vehicleid);
            $customerno = $_SESSION['customerno'];
            $location = "sqlite:" . $location;
            $device = firstmappingforvehiclebydate_fromsqlite($location, $Date, $vehicleid);
        }
        $devicedata[] = $device;
        $finaloutput = vehicleonmap($devicedata);
    }
    $ajaxpage = new ajaxpage();
    $ajaxpage->SetResult($finaloutput);
    $ajaxpage->Render();
}

function getdatafromsqlite($location, $Date, $vehicleid, $device) {
    $customerno = $_SESSION['customerno'];
    $basequery = "SELECT vehiclehistory.vehicleid,vehiclehistory.driverid,vehiclehistory.vehicleno,vehiclehistory.odometer, devicehistory.lastupdated, vehiclehistory.curspeed,devicehistory.deviceid, devicehistory.devicelong, devicehistory.devicelat, devicehistory.uid, devicehistory.ignition, devicehistory.status, devicehistory.directionchange FROM vehiclehistory LEFT OUTER JOIN devicehistory ON devicehistory.lastupdated = vehiclehistory.lastupdated ";
    $devicequery = "WHERE vehiclehistory.lastupdated BETWEEN '$Date[0]' AND '$Date[1]' ORDER BY vehiclehistory.lastupdated ASC, vehiclehistory.odometer ASC";
    //echo $basequery.$devicequery;
    $database = new PDO($location);
    $result = $database->query($basequery . $devicequery);
    $drivermanager = new DriverManager($_SESSION['customerno']);
    $drivers = $drivermanager->get_all_drivers_with_vehicles();
    $counter_first = 0;
    if (isset($result)) {
        $lastdistance = 0;
        $lastdistance_acc = 0;
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                if ($row['uid'] > 0) {
                    if ($row['devicelat'] > 0 && $row['devicelong'] > 0) {
                        $cumulative_dist = $row['odometer'] - $lastdistance_acc;
                        $counter_first++;
                        if ($_SESSION['portable'] != '1') {
                            if ($counter_first == 1) {
                                $lastdistance = $row['odometer'];
                                $lastdistance_acc = $row['odometer'];
                                $cumulative_dist = $row['odometer'] - $lastdistance_acc;
                                $device[] = managerow($drivers, $row, $customerno, $cumulative_dist);
                            }
                            if ($row['ignition'] == '1') {
                                if (($lastdistance + 200) < $row['odometer']) {
                                    $lastdistance = $row['odometer'];
                                    $device[] = managerow($drivers, $row, $customerno, $cumulative_dist);
                                }
                            } elseif ($row['ignition'] == '0' && $lastdistance != $row['odometer']) {
                                if ($row['curspeed'] > 0) {
                                    $device[] = managerow($drivers, $row, $customerno, $cumulative_dist);
                                }
                            }
                        } else {
                            $device[] = managerow($drivers, $row, $customerno, $cumulative_dist);
                        }
                    }
                }
            }
        }
    }
    return $device;
}

function getdatafromsqlite_newRefined($location, $Date, $vehicleid, $device, $holdtime, $distancetravelled, $unitno, $unit, $flag) {
    $customerno = $_SESSION['customerno'];
    $basequery = "SELECT vehiclehistory.vehicleid,vehiclehistory.driverid,vehiclehistory.vehicleno
        ,vehiclehistory.odometer, devicehistory.lastupdated, vehiclehistory.curspeed
        ,devicehistory.deviceid, devicehistory.devicelong, devicehistory.devicelat
        , devicehistory.uid, devicehistory.ignition, devicehistory.status, devicehistory.directionchange
        ,unithistory.analog1,unithistory.analog2,unithistory.analog3,unithistory.analog4
        FROM vehiclehistory
        LEFT OUTER JOIN devicehistory ON devicehistory.lastupdated = vehiclehistory.lastupdated
        LEFT OUTER JOIN unithistory ON unithistory.lastupdated = vehiclehistory.lastupdated ";
    $devicequery = "WHERE vehiclehistory.lastupdated BETWEEN '$Date[0]' AND '$Date[1]' ORDER BY vehiclehistory.lastupdated ASC, vehiclehistory.odometer ASC";
    //echo $basequery . $devicequery;
    $database = new PDO($location);
    
    if($_SERVER['REMOTE_ADDR'] == '::1'){
        //echo $data = convertsqlLiteToMongo($database);
    }
    
    
    $result = $database->query($basequery . $devicequery);
    

    $drivermanager = new DriverManager($_SESSION['customerno']);
    $drivers = $drivermanager->get_all_drivers_with_vehicles();
    $counter_first = 0;
    if (isset($result)) {
        $lastdistance = 0;
        $hold_count = 0;
        $total_hold_time = 0;
        $lastdistance_acc = 0;
        $lastdistance_acc_diff = 0; // To Check the 40 Meter Diff
        $cumulative_dist = 0;
        if (isset($result) && $result != "") {
            foreach ($result as $rowKey => $row) {
                //print_r($row);die();
                $row['holdtime'] = 0;
                if ($row['uid'] > 0) {
                    if ($row['devicelat'] > 0 && $row['devicelong'] > 0) {
                        if ($row['odometer'] < $lastdistance_acc_diff) {
                            $max = GetOdometerMax($row['lastupdated'], $unitno);
                            $row['odometer'] = $max + $row['odometer'];
                        } else {
                            $max = GetOdometerMax($row['lastupdated'], $unitno);
                            $row['odometer'] = $max + $row['odometer'];
                            $lastdistance_acc_diff = $max + $row['odometer'];
                        }
                        $diffmeter = $row['odometer'] - $lastdistance_acc_diff; // To Check the 40 Meter Diff
                        if (($_SESSION['customerno'] != speedConstants::CUSTNO_APMT && $diffmeter > 40) || $rowKey == 0) {
                            if ($hold_count > 0) {
                                $minus = strtotime($row['lastupdated']) - strtotime($total_hold_time);
                                $minutes = floor(($minus) / 60);
                                $row_old['total_hold_time'] = $minutes;
                                if ($minutes > $holdtime) {
                                    $row_old['holdtime'] = 1;
                                    $row_old['devicelat'] = $row['devicelat'];
                                    $row_old['devicelong'] = $row['devicelong'];
                                    //Calling hold function because we need to get location for stoppage points
                                    $device[] = managerow_newRefined_hold($row_old, $cumulative_dist, $unit, $flag);
                                    $total_hold_time = $row['lastupdated'];
                                }
                                $hold_count = 0;
                                $row_old = array();
                            }
                            $cumulative_dist = $row['odometer'] - $lastdistance_acc + $distancetravelled;
                            $counter_first++;
                            if ($counter_first == 1) {
                                // condition for startpoint
                                $total_hold_time = $row['lastupdated'];
                                $lastdistance = $row['odometer'];
                                $lastdistance_acc = $row['odometer'];
                                if ($row['odometer'] < $lastdistance_acc) {
                                    $max = GetOdometerMax($row['lastupdated'], $unitno);
                                    $row['odometer'] = $max + $row['odometer'];
                                    $lastdistance_acc = $max + $row['odometer'];
                                }
                                $cumulative_dist = $row['odometer'] - $lastdistance_acc + $distancetravelled;
                                $row['total_hold_time'] = $total_hold_time;
                                //Calling hold function because we need to get location for first element as we need to show start and end locations on report
                                $device[] = managerow_newRefined_hold($row, $cumulative_dist, $unit, $flag);
                            }
                            if (($lastdistance_acc) < $row['odometer']) {
                                $lastdistance = $row['odometer'];
                                $row['total_hold_time'] = $total_hold_time;
                                $device[] = managerow_newRefined($row, $cumulative_dist, $unit, $flag);
                            }
                        } elseif ($_SESSION['customerno'] == speedConstants::CUSTNO_APMT) {
                            if ($hold_count > 0) {
                                $minus = strtotime($row['lastupdated']) - strtotime($total_hold_time);
                                $minutes = floor(($minus) / 60);
                                $row_old['total_hold_time'] = $minutes;
                                if ($minutes > $holdtime) {
                                    $row_old['holdtime'] = 1;
                                    $row_old['devicelat'] = $row['devicelat'];
                                    $row_old['devicelong'] = $row['devicelong'];
                                    //Calling hold function because we need to get location for stoppage points
                                    $device[] = managerow_newRefined_hold($row_old, $cumulative_dist, $unit, $flag);
                                    $total_hold_time = $row['lastupdated'];
                                }
                                $hold_count = 0;
                                $row_old = array();
                            }
                            $cumulative_dist = $row['odometer'] - $lastdistance_acc + $distancetravelled;
                            $counter_first++;
                            if ($counter_first == 1) {
                                // condition for startpoint
                                $total_hold_time = $row['lastupdated'];
                                $lastdistance = $row['odometer'];
                                $lastdistance_acc = $row['odometer'];
                                if ($row['odometer'] < $lastdistance_acc) {
                                    $max = GetOdometerMax($row['lastupdated'], $unitno);
                                    $row['odometer'] = $max + $row['odometer'];
                                    $lastdistance_acc = $max + $row['odometer'];
                                }
                                $cumulative_dist = $row['odometer'] - $lastdistance_acc + $distancetravelled;
                                $row['total_hold_time'] = $total_hold_time;
                                //Calling hold function because we need to get location for first element as we need to show start and end locations on report
                                $device[] = managerow_newRefined_hold($row, $cumulative_dist, $unit, $flag);
                            }
                            if (($lastdistance_acc) < $row['odometer']) {
                                $lastdistance = $row['odometer'];
                                $row['total_hold_time'] = $total_hold_time;
                                $device[] = managerow_newRefined($row, $cumulative_dist, $unit, $flag);
                            }
                        } else {
                            if ($hold_count == 0) {
                                $total_hold_time = $row['lastupdated'];
                                $row_old = $row;
                            }
                            $hold_count++;
                            $lastdistance = $row['odometer'];
                        } // To Check the 40 Meter Diff
                        $lastdistance_acc_diff = $row['odometer']; // To Check the 40 Meter Diff
                    }
                }
            }
        }
    }
    //Get location for last element as we need to show start and end locations on report
    if (isset($device) && !empty($device)) {
        $lastElementIndex = count($device) - 1;
        $device[$lastElementIndex]->location = getlocation($device[$lastElementIndex]->devicelat, $device[$lastElementIndex]->devicelong, $_SESSION["customerno"]);
    }
    
    return $device;
}

function GetOdometerMax($date, $unitno) {
    $date = trim(substr($date, 0, 11));
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
    $ODOMETER = 0;
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path, "", "", array(PDO::ATTR_PERSISTENT => true));
        $query = "SELECT max(odometer) as odometerm from vehiclehistory";
        $result = $db->query($query);
        foreach ($result as $row) {
            $ODOMETER = $row['odometerm'];
        }
    }
    return $ODOMETER;
}

function firstmappingforvehiclebydate_fromsqlite($location, $Date, $vehicleid) {
    $customerno = $_SESSION['customerno'];
    $basequery = "SELECT vehiclehistory.vehicleid, vehiclehistory.driverid, vehiclehistory.vehicleno,vehiclehistory.odometer, devicehistory.lastupdated, vehiclehistory.curspeed,devicehistory.deviceid, devicehistory.devicelong, devicehistory.devicelat, devicehistory.uid, devicehistory.ignition, devicehistory.status, devicehistory.directionchange FROM `vehiclehistory` LEFT OUTER JOIN devicehistory ON devicehistory.lastupdated = vehiclehistory.lastupdated ";
    $devicequery = "WHERE vehiclehistory.lastupdated between '$Date[0]' and '$Date[1]' ORDER BY `vehiclehistory`.`lastupdated` ASC LIMIT 0,1";
    $database = new PDO($location);
    $result = $database->query($basequery . $devicequery);
    $drivermanager = new DriverManager($_SESSION['customerno']);
    $drivers = $drivermanager->get_all_drivers_with_vehicles();
    if (isset($result) && $result != "") {
        foreach ($result as $row) {
            if ($row['uid'] > 0) {
                if ($row['devicelat'] > 0 && $row['devicelong'] > 0) {
                    $device = managerow($drivers, $row, $customerno, 0);
                }
            }
        }
    }
    return $device;
}

function firstmappingforvehiclebydate_fromsqlite_newRefined($location, $Date) {
    $customerno = $_SESSION['customerno'];
    $basequery = "SELECT vehiclehistory.vehicleid, vehiclehistory.driverid, vehiclehistory.vehicleno,vehiclehistory.odometer, devicehistory.lastupdated, vehiclehistory.curspeed,devicehistory.deviceid, devicehistory.devicelong, devicehistory.devicelat, devicehistory.uid, devicehistory.ignition, devicehistory.status, devicehistory.directionchange FROM `vehiclehistory` LEFT OUTER JOIN devicehistory ON devicehistory.lastupdated = vehiclehistory.lastupdated ";
    $devicequery = "WHERE vehiclehistory.lastupdated between '$Date[0]' and '$Date[1]' ORDER BY `vehiclehistory`.`lastupdated` ASC LIMIT 0,1";
    $database = new PDO($location);
    //echo $basequery . $devicequery;
    $result = $database->query($basequery . $devicequery);
    $drivermanager = new DriverManager($_SESSION['customerno']);
    $drivers = $drivermanager->get_all_drivers_with_vehicles();
    if (isset($result) && $result != "") {
        foreach ($result as $row) {
            //print_r($row['devicelat']);
            if ($row['uid'] > 0) {
                if (isset($row['devicelat']) && isset($row['devicelong']) && $row['devicelat'] > 0 && $row['devicelong'] > 0) {
                    $device = managerow_newRefined($drivers, 0, $customerno, 0);
                }
            }
        }
    }
    return $device;
    //print_r($device);
}

function managerow($drivers, $row, $customerno, $cumulative_dist) {
    $dm = new DriverManager($customerno);
    $driverdetails = $dm->get_driver_with_vehicle($row['driverid']);
    $output = new stdClass();
    $output->vehicleno = $row['vehicleno'];
    $output->vehicleid = $row['vehicleid'];
    $output->cumulative = $cumulative_dist;
    $output->deviceid = $row['deviceid'];
    $output->devicelat = $row['devicelat'];
    $output->devicelong = $row['devicelong'];
    $output->drivername = $driverdetails->drivername;
    $output->driverphone = $driverdetails->driverphone;
    $output->curspeed = $row['curspeed'];
    $output->lastupdated = $row['lastupdated'];
    $output->type = $driverdetails->type;
    $output->status = $row['status'];
    $output->ignition = $row['ignition'];
    $output->directionchange = $row['directionchange'];
    $output->driverid = $row['driverid'];
    foreach ($drivers as $driver) {
        if ($driver->driverid == $output->driverid) {
            $output->drivername = $driver->drivername;
            $output->driverphone = $driver->driverphone;
        }
    }
    return $output;
}

function managerow_newRefined($row, $cumulative_dist, $unit, $flag) {
    $output = new stdClass();
    $output->devicelat = $row['devicelat'];
    $output->devicelong = $row['devicelong'];
    $output->location = "";
    $output->cumulative = $cumulative_dist;
    $output->curspeed = $row['curspeed'];
    $output->lastupdated = $row['lastupdated'];
    $output->status = $row['status'];
    $output->ignition = $row['ignition'];
    $output->holdtime = $row['holdtime'];
    $output->total_hold_time = $row['total_hold_time'];
    $output->test = $unit->unitno;
    if ($flag != 0) {
        $output->temp = set_temp_graph_data($row['lastupdated'], $unit, $row['analog1'], $row['analog2'], $row['analog3'], $row['analog4'], $flag);
    }
    $output->directionchange = round($row['directionchange'] / 10, 0);
    return $output;
}

function managerow_newRefined_hold($row, $cumulative_dist, $unit, $flag) {
    $output = new stdClass();
    $output->devicelat = $row['devicelat'];
    $output->devicelong = $row['devicelong'];
    $output->location = getlocation($output->devicelat, $output->devicelong, $_SESSION["customerno"]);
    $output->cumulative = $cumulative_dist;
    $output->curspeed = $row['curspeed'];
    $output->lastupdated = $row['lastupdated'];
    $output->status = $row['status'];
    $output->ignition = $row['ignition'];
    $output->holdtime = $row['holdtime'];
    $output->total_hold_time = $row['total_hold_time'];
    $output->test = $unit->unitno;
    if ($flag != 0) {
        $output->temp = set_temp_graph_data($row['lastupdated'], $unit, $row['analog1'], $row['analog2'], $row['analog3'], $row['analog4'], $flag);
    }
    $output->directionchange = round($row['directionchange'] / 10, 0);
    return $output;
}

function set_temp_graph_data($updated_date, $unit, $analog1, $analog2, $analog3, $analog4, $flag) {
    $temp = "";
    $temp_conversion = new TempConversion();
    $temp_conversion->use_humidity = $_SESSION["use_humidity"];
    $temp_conversion->switch_to = $_SESSION["switch_to"];
    $temp_conversion->unit_type = $unit->get_conversion;
    if ($_SESSION['temp_sensors'] == 1) {
        $s = "analog" . $unit->tempsen1;
        if ($unit->tempsen1 != 0 && $$s != 0) {
            $temp_conversion->rawtemp = $$s;
            $temp = getTempUtil($temp_conversion);
        } else {
            $temp = '-';
        }
    }
    /**/elseif ($_SESSION['temp_sensors'] == 2) {
        $temp1 = 'Not Active';
        $temp2 = 'Not Active';
        $s = "analog" . $unit->tempsen1;
        if ($unit->tempsen1 != 0 && $$s != 0) {
            $temp_conversion->rawtemp = $$s;
            $temp1 = getTempUtil($temp_conversion);
        } else {
            $temp1 = '-';
        }
        $s = "analog" . $unit->tempsen2;
        if ($unit->tempsen2 != 0 && $$s != 0) {
            $temp_conversion->rawtemp = $$s;
            $temp2 = getTempUtil($temp_conversion);
        } else {
            $temp2 = '-';
        }
        if ($flag == 1) {
            $temp = (int) $temp1;
        } else {
            $temp = (int) $temp2;
        }
    }
    /**/
    return $temp;
}

function get_usegeolocation($customerno) {
    $GeoCoder_Obj = new GeoCoder($customerno);
    $geolocation = $GeoCoder_Obj->get_use_geolocation();
    return $geolocation;
}

function getlocation($lat, $long, $customerno) {
    $key = $lat . $long;
    if (!isset($GLOBALS[$key])) {
        $GeoCoder_Obj = new GeoCoder($customerno);
        if ($lat == '' || $long == '') {
            $address = NULL;
        } else {
            $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
        }
        $output = $address;
        $GLOBALS[$key] = $address;
    } else {
        $output = $GLOBALS[$key];
    }
    return $output;
}

function vehicleonmap($device) {
    //var_dump($device);
    class jsonop {
        // Empty class!
    }
    $finaloutput = array();
    $length = count($device);
    $counter = 0;
    foreach ($device as $thisdevice) {
        if ($thisdevice == null) {
            break;
        }
        $counter++;
        $output = new jsonop();
        $date = new DateTime($thisdevice->lastupdated);
        $output->cgeolat = $thisdevice->devicelat;
        $output->cgeolong = $thisdevice->devicelong;
        $output->cname = $thisdevice->vehicleno;
        $output->cdrivername = $thisdevice->drivername;
        $output->cdriverphone = $thisdevice->driverphone;
        $output->cspeed = $thisdevice->curspeed;
        $output->clastupdated = $date->format('D d-M-Y H:i');
        $output->cvehicleid = $thisdevice->vehicleid;
        if ($counter == 1) {
            $output->image = '../../images/Sflag.png';
        } elseif ($counter == $length) {
            $output->image = '../../images/Eflag.png';
        } else {
            $output->image = vehicleimageSqlite($thisdevice);
        }
        $finaloutput[] = $output;
    }
    return $finaloutput;
}

function vehicleonmap_route_history($device) {
    //var_dump($device);
    class jsono {
        // Empty class!
    }
    $finaloutput = array();
    $length = count($device);
    $counter = 0;
    foreach ($device as $thisdevice) {
        if ($thisdevice == null) {
            break;
        }
        $counter++;
        $output = new jsono();
        $date = new DateTime($thisdevice->lastupdated);
        $output->cgeolat = $thisdevice->devicelat;
        $output->cgeolong = $thisdevice->devicelong;
        $output->cspeed = $thisdevice->curspeed;
        $output->cignition = $thisdevice->ignition;
        $output->holdtime = $thisdevice->holdtime;
        $output->cumulative = $thisdevice->cumulative / 1000;
        $output->clastupdated = $date->format('D d-M-Y H:i');
        $output->directionchange = $thisdevice->directionchange;
        $output->total_hold_time = $thisdevice->total_hold_time;
        $output->location = $thisdevice->location;
        $output->test = $thisdevice->test;
        $output->temp = $thisdevice->temp;
        if (isset($_SESSION['userid']) && $_SESSION['userid'] != 391 && $_SESSION['userid'] != 392) {
            $output->users_spec = 0;
        } else {
            $output->users_spec = 1;
        }
        $finaloutput[] = $output;
    }
    return $finaloutput;
    //print_r($finaloutput);
}

function getVehicleNumberFromId($vehicleid) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicleNo = $vehiclemanager->getVehicleNumber($vehicleid);
    return $vehicleNo;
}

function getDeviceNumberFromVehicle($vehicleNo) {
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $deviceNo = $devicemanager->getDeviceNumber($vehicleNo);
    return $deviceNo;
}

function getWeighBridgeDetails($toggleTripId) {
    $weighDetails = array();
    $objToggle = new ToggleSwitchManager();
    $weighDetails = $objToggle->getWeighBridgeDetails($toggleTripId, $_SESSION['customerno']);
    return $weighDetails;
}

function getVehicleDetailsByVehicleNo($vehicleNo) {
    $vehicleDetails = array();
    $VehicleManager = new VehicleManager($_SESSION['customerno']);
    $vehicleDetails = $VehicleManager->get_all_vehicles_byId($vehicleNo, 1);
    return $vehicleDetails;
}
function convertsqlLiteToMongo($dbParam){
    if(isset($dbParam)){
        $sql['devicehistory'] = "SELECT * FROM devicehistory";
        $sql['unithistory'] = "SELECT * FROM unithistory";
        $sql['vehiclehistory'] = "SELECT * FROM vehiclehistory";
        
        foreach($sql as $table => $query){
            $arrTable = $dbParam->query($query);
            if(isset($arrTable)){
                $arrTemp = array();
                foreach($arrTable as $key => $val){
                    $arrTemp[$key] = $val;
                }
                if(!empty($arrTemp)){
                    $arrData[$table] = $arrTemp;
                }
            }
        }
      

        
    }
    
    if(!empty($arrData)){
        if(isset($arrData)){
            $arrResult = array();
                foreach($arrData as $key => $table){
                    if(isset($key) && $key == 'devicehistory'){
                        foreach($table as $k => $val){
                            $arrCollection['deviceid'] = $val['deviceid'];
                            $arrCollection['customerno'] = $customerno = $val['customerno'];
                            $arrCollection['devicelat'] = $val['devicelat'];
                            $arrCollection['devicelong'] = $val['devicelong'];
                            $arrCollection['lastupdated'] = $val['lastupdated'];
                            $arrCollection['altitude'] = $val['altitude'];
                            $arrCollection['directionchange'] = $val['directionchange'];
                            $arrCollection['inbatt'] = $val['inbatt'];
                            $arrCollection['hwv'] = $val['hwv'];
                            $arrCollection['swv'] = $val['swv'];
                            $arrCollection['msgid'] = $val['msgid'];
                            $arrCollection['status'] = $val['status'];
                            $arrCollection['ignition'] = $val['ignition'];
                            $arrCollection['powercut'] = $val['powercut'];
                            $arrCollection['tamper'] = $val['tamper'];
                            $arrCollection['gpsfixed'] = $val['gpsfixed'];
                            $arrCollection['online_offline'] = $val['online/offline'];
                            $arrCollection['gsmstrength'] = $val['gsmstrength'];
                            $arrCollection['gsmregister'] = $val['gsmregister'];
                            $arrCollection['gprsregister'] = $val['gprsregister'];
                            $arrCollection['satv'] = $val['satv'];
                            $arrResult[] = $arrCollection;
                        } 
                    }
                }

                // end of loop $arrData
                if(!empty($arrResult)){
                    foreach($arrResult as $key => $value){
                        foreach($arrData['unithistory'] as $key => $val){
                            if(isset($val['lastupdated']) && $val['lastupdated'] == $value['lastupdated']){
                                $date = date("Y-m-d", strtotime($val['lastupdated']));
                                if(isset($val['uid']))
                                    $arrResult[$key]['uid'] = $uid =  $val['uid'];
                                if(isset($val['unitno']))
                                    $arrResult[$key]['unitno'] = $val['unitno'];
                                if(isset($val['analog1']))    
                                    $arrResult[$key]['analog1'] = $val['analog1'];
                                if(isset($val['analog2']))                            
                                    $arrResult[$key]['analog2'] = $val['analog2'];
                                if(isset($val['analog3']))    
                                    $arrResult[$key]['analog3'] = $val['analog3'];
                                if(isset($val['analog4']))    
                                    $arrResult[$key]['analog4'] = $val['analog4'];
                                if(isset($val['digitalio']))
                                    $arrResult[$key]['digitalio'] = $val['digitalio'];
                                if(isset($val['commandkey']))    
                                    $arrResult[$key]['commandkey'] = $val['commandkey'];
                                if(isset($val['commandkeyval']))    
                                    $arrResult[$key]['commandkeyval'] = $val['commandkeyval'];
                            }
                        } 
                        // end of Unit History Table 

                        foreach($arrData['vehiclehistory'] as $key => $val){
                            if(isset($val['lastupdated']) && $val['lastupdated'] == $value['lastupdated']){
                                if(isset($val['vehicleid']))    
                                  $arrResult[$key]['vehicleid'] = $vehicleid =  $val['vehicleid'];
                                if(isset($val['vehicleno']))
                                    $arrResult[$key]['vehicleno'] = $val['vehicleno'];
                                if(isset($val['extbatt']))    
                                    $arrResult[$key]['extbatt'] = $val['extbatt'];
                                if(isset($val['odometer']))  
                                    $arrResult[$key]['odometer'] = $val['odometer'];
                                if(isset($val['curspeed']))    
                                    $arrResult[$key]['curspeed'] = $val['curspeed'];
                                if(isset($val['driverid']))    
                                    $arrResult[$key]['driverid'] = $val['driverid'];
                            } 
                         }
                    }
                }
            }
        }

    
    if(!empty($arrResult)){
        $object = '{
            "customerno" : '.$customerno.',
            "date" : "'.$date.'",
            "uid" : "'.$uid.'",
            "vehicleid" : '.$vehicleid.',
            "data" : [';
        foreach($arrResult as $key => $val){
            $object.='{'.'"deviceid":'.$val['deviceid'].',';
            $object.='"customerno":'.$val['customerno'].',';
            $object.='"devicelat":"'.$val['devicelat'].'",';
            $object.='"devicelong":"'.$val['devicelong'].'" ,';
            $object.='"lastupdated":"'.$val['lastupdated'].'",';
            $object.='"altitude":'.$val['altitude'].',';
            $object.='"directionchange":"'.$val['directionchange'].'",';
            $object.='"inbatt":"'.$val['inbatt'].'",';
            $object.='"hwv":"'.$val['hwv'].'",';
            $object.='"swv":"'.$val['swv'].'",';
            $object.='"msgid":"'.$val['msgid'].'",';
            $object.='"status":"'.$val['status'].'",';
            $object.='"ignition":'.$val['ignition'].',';
            $object.='"powercut":'.$val['powercut'].',';
            $object.='"tamper":'.$val['tamper'].',';
            $object.='"gpsfixed":"'.$val['gpsfixed'].'",';
            $object.='"online_offline":'.$val['online_offline'].',';
            $object.='"gsmstrength":'.$val['gsmstrength'].',';
            $object.='"gsmregister":'.$val['gsmregister'].',';
            $object.='"gprsregister":'.$val['gprsregister'].',';
            $object.='"satv":"'.$val['satv'].'",';
            $object.='"uid":'.$val['uid'].',';
            $object.='"unitno":"'.$val['unitno'].'",';
            $object.='"analog1":"'.$val['analog1'].'",';
            $object.='"analog2":"'.$val['analog2'].'",';
            $object.='"analog3":"'.$val['analog3'].'",';
            $object.='"analog4":"'.$val['analog4'].'",';
            $object.='"digitalio":'.$val['digitalio'].',';
            $object.='"commandkey":"'.$val['commandkey'].'",';
            $object.='"commandkeyval":"'.$val['commandkeyval'].'",';
            $object.='"vehicleid":'.$val['vehicleid'].',';
            $object.='"vehicleno":"'.$val['vehicleno'].'",';
            $object.='"extbatt":"'.$val['extbatt'].'",';
            $object.='"odometer":'.$val['odometer'].',';
            $object.='"curspeed":'.$val['curspeed'].',';
            $object.='"driverid":'.$val['driverid'].'';
            $object.='},';
        }
        $object.=']}';
    }

    $jsonString =  substr_replace($object,"",-3).']}';
    return $jsonString;
    
    
}
    

function getDataFromMongo($result,$Date, $vehicleid, $device, $holdtime, $distancetravelled, $unitno, $unit, $flag){
    
    if(empty($result))
        return false;

    $drivermanager = new DriverManager($_SESSION['customerno']);
    $drivers = $drivermanager->get_all_drivers_with_vehicles();
    $counter_first = 0;
    if (isset($result)) {
        $lastdistance = 0;
        $hold_count = 0;
        $total_hold_time = 0;
        $lastdistance_acc = 0;
        $lastdistance_acc_diff = 0; // To Check the 40 Meter Diff
        $cumulative_dist = 0;
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $row['holdtime'] = 0;
                if(empty($unitno)){
                    $unitno = $row['unitno'];
                }
                if ($row['uid'] > 0) {
                    if ($row['devicelat'] > 0 && $row['devicelong'] > 0) {
                        if ($row['odometer'] < $lastdistance_acc_diff) {
                            $max = GetOdometerMax($row['lastupdated'], $unitno);
                            $row['odometer'] = $max + $row['odometer'];
                        } else {
                            $max = GetOdometerMax($row['lastupdated'], $unitno);
                            $row['odometer'] = $max + $row['odometer'];
                            $lastdistance_acc_diff = $max + $row['odometer'];
                        }
                        $diffmeter = $row['odometer'] - $lastdistance_acc_diff; // To Check the 40 Meter Diff
                        if ($diffmeter > 40) {
                            if ($hold_count > 0) {
                                $minus = strtotime($row['lastupdated']) - strtotime($total_hold_time);
                                $minutes = floor(($minus) / 60);
                                $row_old['total_hold_time'] = $minutes;
                                if ($minutes > $holdtime) {
                                    $row_old['holdtime'] = 1;
                                    $row_old['devicelat'] = $row['devicelat'];
                                    $row_old['devicelong'] = $row['devicelong'];
                                    //Calling hold function because we need to get location for stoppage points
                                    $device[] = managerow_newRefined_hold($row_old, $cumulative_dist, $unit, $flag);
                                    $total_hold_time = $row['lastupdated'];
                                }
                                $hold_count = 0;
                                $row_old = array();
                            }
                            $cumulative_dist = $row['odometer'] - $lastdistance_acc + $distancetravelled;
                            $counter_first++;
                            if ($counter_first == 1) {
                                // condition for startpoint
                                $total_hold_time = $row['lastupdated'];
                                $lastdistance = $row['odometer'];
                                $lastdistance_acc = $row['odometer'];
                                if ($row['odometer'] < $lastdistance_acc) {
                                    $max = GetOdometerMax($row['lastupdated'], $unitno);
                                    $row['odometer'] = $max + $row['odometer'];
                                    $lastdistance_acc = $max + $row['odometer'];
                                }
                                $cumulative_dist = $row['odometer'] - $lastdistance_acc + $distancetravelled;
                                $row['total_hold_time'] = $total_hold_time;
                                //Calling hold function because we need to get location for first element as we need to show start and end locations on report
                                $device[] = managerow_newRefined_hold($row, $cumulative_dist, $unit, $flag);
                            }
                            if (($lastdistance_acc) < $row['odometer']) {
                                $lastdistance = $row['odometer'];
                                $row['total_hold_time'] = $total_hold_time;
                                $device[] = managerow_newRefined($row, $cumulative_dist, $unit, $flag);
                            }
                        } else {
                            if ($hold_count == 0) {
                                $total_hold_time = $row['lastupdated'];
                                $row_old = $row;
                            }
                            $hold_count++;
                            $lastdistance = $row['odometer'];
                        } // To Check the 40 Meter Diff
                        $lastdistance_acc_diff = $row['odometer']; // To Check the 40 Meter Diff
                    }
                }
            }
        }
    }
    //Get location for last element as we need to show start and end locations on report
    if (isset($device) && !empty($device)) {
        $lastElementIndex = count($device) - 1;
        $device[$lastElementIndex]->location = getlocation($device[$lastElementIndex]->devicelat, $device[$lastElementIndex]->devicelong, $_SESSION["customerno"]);
    }

    return $device;
}

function getReportDetailsFromMongoDb($customerno = '',$date = '',$startTime = '',$endTime = ''){
    require '../../mongodb/vendor/autoload.php';
    $client = new MongoDB\Client;
    $collection = (new MongoDB\Client)->speed->test;
    // Filtering Data 
    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $filter = array(
        'customerno' => (int)$customerno,
        'date' => $date,
        'data.lastupdated' => array('$gte' => $startTime, '$lte' => $endTime)
    );
    // p($filter);
    $options = ["projection" => [
        '_id' => 0,
        'data.vehicleid' => 1,
        'data.driverid' => 1,
        'data.vehicleno' => 1,
        'data.odometer' => 1,
        'data.lastupdated' => 1,
        'data.curspeed' => 1,
        'data.deviceid' => 1,
        'data.devicelong' => 1,
        'data.devicelat' => 1,
        'data.uid' => 1,
        'data.ignition' => 1,
        'data.directionchange' => 1,
        'data.analog1' => 1,
        'data.analog2' => 1,
        'data.analog3' => 1,
        'data.analog4' => 1,
        'data.status' => 1

    ]];
    $query = new MongoDB\Driver\Query($filter,$options); 
    $res = $mng->executeQuery("speed.test", $query);
    $data = $reports = current($res->toArray());
    
    // Ends Here 

    //$reports = $manager->executeQuery('db.test.find({date : "2019-10-06" ,customerno : 549,"data.lastupdated" : {"$gte" : "2019-10-06 00:00:00", "$lte" : "2019-10-06 23:59:59" }})');
    //p($reports);
    //   $reports = $collection->findOne([
    //         'customerno' => 549,
    //         'date' => '2019-10-06'
    //     ]);

    return $data;
}


?>
