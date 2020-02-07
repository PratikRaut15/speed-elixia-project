<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
include_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
include_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
include_once 'map_popup.php';
include_once $RELATIVE_PATH_DOTS . 'lib/comman_function/reports_func.php';
set_time_limit(0);
if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
}
function getdate_IST() {
    $ServerDate_IST = strtotime(date("Y-m-d H:i:s"));
    return $ServerDate_IST;
}

function getvehicles() {
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $devices = $devicemanager->devicesformapping();
    return $devices;
}

function new_travel_data($customerno, $unitno, $userdate, $count, $firstelement, $endelement, $vehicleid, $Shour, $Ehour) {
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
    if (!file_exists($location)) {
        return null;
    }
    if (filesize($location) == 0) {
        return null;
    }
    $location = "sqlite:" . $location;
    $defaultStartTime = "00:00:00";
    $defaultEndTime = "23:59:59";
    if ($count == 1) {
        $devicedata = getdatafromsqliteTimebased($vehicleid, $location, $userdate, $Shour, $Ehour);
    } elseif ($count > 1 && $userdate == $firstelement) {
        $devicedata = getdatafromsqliteTimebased($vehicleid, $location, $userdate, $Shour, $defaultEndTime);
    } elseif ($count > 1 && $userdate == $endelement) {
        $devicedata = getdatafromsqliteTimebased($vehicleid, $location, $userdate, $defaultStartTime, $Ehour);
    } else {
        $devicedata = getdatafromsqliteTimebased($vehicleid, $location, $userdate, $defaultStartTime, $defaultEndTime);
    }
    if ($devicedata != null) {
        $lastday = processtraveldata($devicedata, $unitno);
        return $lastday;
    } else {
        return null;
    }
}

function gettraveldata_trip($vehicleid, $SDate, $EDate, $Shour, $Ehour, $geocode) {
    $vehicleid = GetSafeValueString($vehicleid, 'string');
    $devicedata = array();
    $days = array();
    $SDate = GetSafeValueString($SDate, 'string');
    $SDate = explode('-', $SDate);
    $SDate = $SDate[2] . "-" . $SDate[1] . "-" . $SDate[0];
    $EDate = GetSafeValueString($EDate, 'string');
    $EDate = explode('-', $EDate);
    $EDate = $EDate[2] . "-" . $EDate[1] . "-" . $EDate[0];
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $totaldays = array();
    $STdate = date('Y-m-d', strtotime($SDate));
    while (strtotime($STdate) <= strtotime($EDate)) {
        $totaldays[] = $STdate;
        $STdate = date("Y-m-d", strtotime('+1 day', strtotime($STdate)));
    }
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $vehicleno = $devicemanager->getvehiclenofromdeviceid($vehicleid);
    $customerno = $_SESSION['customerno'];
    $um = new UnitManager($customerno);
    $unitno = $um->getunitnofromdeviceid($vehicleid);
    $count = count($totaldays);
    $endelement = end($totaldays);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            if ($count > 1 && $userdate != $endelement) {
                $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                if (file_exists($location)) {
                    $filesize = filesize($location);
                    if ($filesize > 0) {
                        $location = "sqlite:" . $location;
                        $devicedata = getdatafromsqliteTimebased($vehicleid, $location, $userdate, $Shour, null);
                        $lastday = processtraveldata($devicedata, $unitno);
                        if ($lastday != null) {
                            $days = array_merge($days, $lastday);
                        }
                    }
                }
            } elseif ($count > 1 && $userdate == $endelement) {
                $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                if (file_exists($location)) {
                    $filesize = filesize($location);
                    if ($filesize > 0) {
                        $location = "sqlite:" . $location;
                        $devicedata = getdatafromsqliteTimebased($vehicleid, $location, $userdate, null, $Ehour);
                        $lastday = processtraveldata($devicedata, $unitno);
                        if ($lastday != null) {
                            $days = array_merge($days, $lastday);
                        }
                    }
                }
            } elseif ($count == 1) {
                $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                if (file_exists($location)) {
                    $filesize = filesize($location);
                    if ($filesize > 0) {
                        $location = "sqlite:" . $location;
                        $devicedata = getdatafromsqliteTimebased($vehicleid, $location, $userdate, $Shour, $Ehour);
                        $lastday = processtraveldata($devicedata, $unitno);
                        if ($lastday != null) {
                            $days = array_merge($days, $lastday);
                        }
                    }
                } else {
                    echo 'File Does not exist';
                }
            }
        }
    }
    if (isset($days) && count($days) > 0) {
        // include 'pages/panels/travelhistrep_pdf.php';
        displaytraveldata_trip($days, $vehicleid, $unitno, $geocode);
    } else {
        echo $error;
    }
}

function getdatafromsqlite($vehicleid, $location, $stime, $etime) {
    $customerno = isset($_SESSION['customerno']) ? $_SESSION['customerno'] : '';
    $devices2;
    $lastrow;
    try {
        $database = new PDO($location);
        $query = "SELECT devicehistory.deviceid, devicehistory.devicelat,
        devicehistory.devicelong, devicehistory.ignition, devicehistory.status,
        devicehistory.lastupdated, vehiclehistory.odometer from devicehistory
        INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
        WHERE vehiclehistory.vehicleid=$vehicleid AND time(devicehistory.lastupdated) BETWEEN '$stime:00' AND '$etime:00'
        ORDER BY devicehistory.lastupdated DESC Limit 0,1";
        $result = $database->query($query);
        $lastrow;
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $lastdevice = new VODevices();
                $lastdevice->deviceid = $row['deviceid'];
                $lastdevice->devicelat = $row['devicelat'];
                $lastdevice->devicelong = $row['devicelong'];
                $lastdevice->ignition = $row['ignition'];
                $lastdevice->status = $row['status'];
                $lastdevice->lastupdated = $row['lastupdated'];
                $lastdevice->odometer = $row['odometer'];
                $lastrow = $lastdevice;
            }
        }
        $query = "SELECT vehiclehistory.vehicleno,devicehistory.deviceid, devicehistory.devicelat,
        devicehistory.devicelong, devicehistory.ignition, devicehistory.status,
        devicehistory.lastupdated, vehiclehistory.odometer from devicehistory
        INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
        WHERE vehiclehistory.vehicleid=$vehicleid AND time(devicehistory.lastupdated) BETWEEN '$stime:00' AND '$etime:00' group by devicehistory.lastupdated
        ORDER BY devicehistory.lastupdated ASC";
        $_SESSION["sqlquery"] = $query;
        $result = $database->query($query);
        $devices2 = array();
        $laststatus;
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                if (!isset($laststatus) || $laststatus != $row['ignition']) {
                    $device = new VODevices();
                    $device->deviceid = $row['deviceid'];
                    $device->vehicleno = $row['vehicleno'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->ignition = $row['ignition'];
                    $device->status = $row['status'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->odometer = $row['odometer'];
                    $laststatus = $row['ignition'];
                    $devices2[] = $device;
                }
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    $sqlitedata[] = $devices2;
    $sqlitedata[] = $lastrow;
    return $sqlitedata;
}

function getdatafromsqliteTimebased($vehicleid, $location, $userdate, $Shour, $Ehour, $customerno = '') {
    $customerno = ($customerno != '') ? $customerno : $_SESSION['customerno'];
    $sqlitedata = array();
    $devices2;
    $lastrow;
    if (isset($Shour) && isset($Ehour)) {
        try {
            $database = new PDO($location);
            $query = "SELECT devicehistory.deviceid, devicehistory.devicelat,
                devicehistory.devicelong, devicehistory.ignition, devicehistory.status,
                devicehistory.lastupdated, vehiclehistory.odometer ,vehiclehistory.curspeed
                FROM devicehistory
                INNER JOIN vehiclehistory ON vehiclehistory.vehiclehistoryid = devicehistory.id
                WHERE vehiclehistory.vehicleid=$vehicleid";
            if ($customerno != speedConstants::CUSTNO_APMT) {
                //$query .= " AND devicehistory.gpsfixed = 'A'";
                $query .= " AND COALESCE(devicehistory.gpsfixed, 'A') ='A'";
            }

            $query .= " AND devicehistory.lastupdated >= '$userdate $Shour' AND devicehistory.lastupdated <= '$userdate $Ehour'
                AND devicelat <> '0.000000' AND devicelong <> '0.000000'
                ORDER BY devicehistory.lastupdated DESC Limit 0,1";
            $result = $database->query($query);
            $lastrow;
            if (isset($result) && $result != "") {
                foreach ($result as $row) {
                    $lastdevice = new VODevices();
                    $lastdevice->deviceid = $row['deviceid'];
                    $lastdevice->devicelat = $row['devicelat'];
                    $lastdevice->devicelong = $row['devicelong'];
                    $lastdevice->ignition = $row['ignition'];
                    $lastdevice->status = $row['status'];
                    $lastdevice->lastupdated = $row['lastupdated'];
                    $lastdevice->odometer = $row['odometer'];
                    $lastdevice->speed = $row['curspeed'];
                    $lastrow = $lastdevice;
                }
            }
            $query = "SELECT vehiclehistory.vehicleno,devicehistory.deviceid, devicehistory.devicelat,
                devicehistory.devicelong, devicehistory.ignition, devicehistory.status,
                devicehistory.lastupdated, vehiclehistory.odometer ,vehiclehistory.curspeed
                FROM devicehistory
                INNER JOIN vehiclehistory ON vehiclehistory.vehiclehistoryid = devicehistory.id
                WHERE vehiclehistory.vehicleid=$vehicleid ";
            if ($customerno != speedConstants::CUSTNO_APMT) {
                //$query .= " AND devicehistory.gpsfixed = 'A'";
                $query .= " AND COALESCE(devicehistory.gpsfixed, 'A') = 'A'";
            }

            $query .= " AND devicehistory.lastupdated >= '$userdate $Shour' AND devicehistory.lastupdated <= '$userdate $Ehour'
                AND devicelat <> '0.000000' AND devicelong <> '0.000000' group by devicehistory.lastupdated
                ORDER BY devicehistory.lastupdated ASC";
            $result = $database->query($query);
            $devices2 = array();
            $laststatus;
            $previous_lastupdated = 0;
            if (isset($result) && $result != "") {
                foreach ($result as $row) {
                    $currtime = date('H:i', strtotime($row['lastupdated']));
                    if (!isset($laststatus) || $laststatus != $row['ignition']
                        || ($row['ignition'] == 0
                            && (
                                ($lastspeed == 0 && $row['curspeed'] > 0 && strtotime($previous_lastupdated) != strtotime($currtime))
                                || ($lastspeed > 0 && $row['curspeed'] == 0 && strtotime($previous_lastupdated) != strtotime($currtime))
                            )
                        )
                    ) {
                        $device = new VODevices();
                        $device->deviceid = $row['deviceid'];
                        $device->vehicleno = $row['vehicleno'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $device->ignition = $row['ignition'];
                        $device->status = $row['status'];
                        $device->lastupdated = $row['lastupdated'];
                        $device->odometer = $row['odometer'];
                        $device->speed = $row['curspeed'];
                        $device->prev = $previous_lastupdated;
                        $device->cur = $currtime;
                        $laststatus = $row['ignition'];
                        $lastspeed = $row['curspeed'];
                        $previous_lastupdated = date('H:i', strtotime($row['lastupdated']));
                        $devices2[] = $device;
                    } else {
                        $previous_lastupdated = date('H:i', strtotime($row['lastupdated']));
                    }
                }
            }
        } catch (PDOException $e) {
            die($e);
        }
    }
    if (isset($devices2) && !empty($devices2)) {
        $sqlitedata[] = $devices2;
    }
    if (isset($lastrow) && !empty($lastrow)) {
        $sqlitedata[] = $lastrow;
    }
    return $sqlitedata;
}

function processtraveldata($devicedata, $unitno, $isApi = "0") {
    $devices2 = $devicedata[0];
    $lastrow = $devicedata[1];
    $data = array();
    $datalen = count($devices2);
    if (isset($devices2) && count($devices2) > 1) {
        foreach ($devices2 as $device) {
            $datacap = new stdClass();
            $datacap->ignition = $device->ignition;
            $arrayLength = count($data);
            if ($arrayLength == 0) {
                $datacap->starttime = $device->lastupdated;
                $datacap->startlat = $device->devicelat;
                $datacap->startlong = $device->devicelong;
                $datacap->startodo = $device->odometer;
                $datacap->startspeed = $device->speed;
                $datacap->unitno = $unitno;
            } elseif ($arrayLength == 1) {
                $data[0]->endlat = $device->devicelat;
                $data[0]->endlong = $device->devicelong;
                $data[0]->endtime = $device->lastupdated;
                $data[0]->endodo = $device->odometer;
                $data[0]->endspeed = $device->speed;
                $data[0]->duration = getduration($data[0]->endtime, $data[0]->starttime);
                $data[0]->distancetravelled = 0;
                if ($data[0]->startspeed >= 0) {
                    if ($data[0]->endodo < $data[0]->startodo) {
                        $date = date('Y-m-d', strtotime($device->lastupdated));
                        $max = GetOdometerMax($date, $unitno);
                        $data[0]->endodo = $max + $data[0]->endodo;
                    }
                    $distanceTravelled = round(($data[0]->endodo / 1000 - $data[0]->startodo / 1000), 2);
                    if ($distanceTravelled > 0.1) {
                        $data[0]->distancetravelled = $distanceTravelled;
                    }
                }
                $datacap->starttime = $data[0]->endtime;
                $datacap->startlat = $data[0]->endlat;
                $datacap->startlong = $data[0]->endlong;
                $datacap->startodo = $data[0]->endodo;
                $datacap->startspeed = $data[0]->endspeed;
                $datacap->endtime = $lastrow->lastupdated;
                $datacap->endlat = $lastrow->devicelat;
                $datacap->endlong = $lastrow->devicelong;
                $datacap->endodo = $lastrow->odometer;
                $datacap->endspeed = $lastrow->speed;
                $datacap->unitno = $unitno;
            } else {
                $last = $arrayLength - 1;
                $data[$last]->endtime = $device->lastupdated;
                $data[$last]->endlat = $device->devicelat;
                $data[$last]->endlong = $device->devicelong;
                $data[$last]->endodo = $device->odometer;
                $data[$last]->endspeed = $device->speed;
                $data[$last]->duration = getduration($data[$last]->endtime, $data[$last]->starttime);
                $data[$last]->distancetravelled = 0;
                if ($data[$last]->startspeed >= 0) {
                    if ($data[$last]->endodo < $data[$last]->startodo) {
                        $date = date('Y-m-d', strtotime($device->lastupdated));
                        $max = GetOdometerMax($date, $unitno);
                        $data[$last]->endodo = $max + $data[$last]->endodo;
                    }
                    $distanceTravelled = round(($data[$last]->endodo / 1000 - $data[$last]->startodo / 1000), 2);
                    if ($distanceTravelled > 0.1) {
                        $data[$last]->distancetravelled = $distanceTravelled;
                    }
                }
                $datacap->starttime = $data[$last]->endtime;
                $datacap->startlat = $data[$last]->endlat;
                $datacap->startlong = $data[$last]->endlong;
                $datacap->startodo = $data[$last]->endodo;
                $datacap->startspeed = $data[$last]->endspeed;
                $datacap->speed = $lastrow->speed;
                $datacap->unitno = $unitno;
                if ($datalen - 1 == $arrayLength) {
                    $datacap->endtime = $lastrow->lastupdated;
                    $datacap->endlat = $lastrow->devicelat;
                    $datacap->endlong = $lastrow->devicelong;
                    $datacap->endodo = $lastrow->odometer;
                    $datacap->endspeed = $lastrow->speed;
                    $datacap->duration = getduration($datacap->endtime, $datacap->starttime);
                    $datacap->distancetravelled = 0;
                    if ($datacap->startspeed >= 0) {
                        if ($datacap->endodo < $datacap->startodo) {
                            $date = date('Y-m-d', strtotime($lastrow->lastupdated));
                            $max = GetOdometerMax($date, $unitno);
                            $datacap->endodo = $max + $datacap->endodo;
                        }
                        $distanceTravelled = round(($datacap->endodo / 1000 - $datacap->startodo / 1000), 2);
                        if ($distanceTravelled > 0.1) {
                            $datacap->distancetravelled = $distanceTravelled;
                        }
                    }
                    $datacap->ignition = $device->ignition;
                }
            }
            $data[] = $datacap;
        }
        if ($data != null && count($data) > 0) {
            //$optdata = optimizerep($data);
        }
        return $data;
    } elseif (isset($devices2) && count($devices2) == 1) {
        $datacap = new stdClass();
        $datacap->starttime = $devices2[0]->lastupdated;
        $datacap->startlat = $devices2[0]->devicelat;
        $datacap->startlong = $devices2[0]->devicelong;
        $datacap->startodo = $devices2[0]->odometer;
        $datacap->startspeed = $devices2[0]->speed;
        $datacap->endtime = $lastrow->lastupdated;
        $datacap->endlat = $lastrow->devicelat;
        $datacap->endlong = $lastrow->devicelong;
        $datacap->endodo = $lastrow->odometer;
        $datacap->endspeed = $lastrow->speed;
        $datacap->speed = $lastrow->speed;
        $datacap->duration = getduration($datacap->endtime, $datacap->starttime);
        $datacap->distancetravelled = $datacap->endodo / 1000 - $datacap->startodo / 1000;
        $datacap->ignition = $devices2[0]->ignition;
        $datacap->unitno = $unitno;
        $data[] = $datacap;
        return $data;
    } else {
        if ($isApi == "1") {
            return null;
        } else {
            echo "<script>$('error').show();jQuery('#error').fadeOut(3000);</script>";
        }
    }
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

function getduration($EndTime, $StartTime) {
    $idleduration = strtotime($EndTime) - strtotime($StartTime);
    $years = floor($idleduration / (365 * 60 * 60 * 24));
    $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
    $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
    $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
    return $hours * 60 + $minutes;
}

function optimizerep($data) {
    $datarows = array();
    $arrayLength = count($data);
    $currentrow = $data[0];
    $a = 0;
    while ($currentrow != $data[$arrayLength - 1]) {
        $i = 1;
        while (($i + $a) < $arrayLength - 1 && $data[$i + $a]->duration < 3) {
            $i += 2;
        }
        $currentrow->endtime = $data[$i + $a - 1]->endtime;
        $currentrow->endlat = $data[$i + $a - 1]->endlat;
        $currentrow->endlong = $data[$i + $a - 1]->endlong;
        $currentrow->endodo = $data[$i + $a - 1]->endodo;
        $currentrow->speed = $data[$i + $a - 1]->endspeed;
        $currentrow->duration = getduration($currentrow->endtime, $currentrow->starttime);
        $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;
        $datarows[] = $currentrow;
        if (($a + $i) <= $arrayLength - 1) {
            $currentrow = $data[$i + $a];
        }
        $a += $i;
        if (($a) >= $arrayLength - 1) {
            $currentrow = $data[$arrayLength - 1];
        }
    }
    if ($datarows != null) {
        $checkop = end($datarows);
        $checkup = end($data);
        if ($checkop->endtime != $checkup->endtime) {
            $currentrow->starttime = $checkop->endtime;
            $currentrow->startlat = $checkop->endlat;
            $currentrow->startlong = $checkop->endlong;
            $currentrow->startodo = $checkop->endodo;
            $currentrow->startspeed = $checkop->endspeed;
            $currentrow->endtime = $checkup->endtime;
            $currentrow->endlat = $checkup->endlat;
            $currentrow->endlong = $checkup->endlong;
            $currentrow->endodo = $checkup->endodo;
            $currentrow->endspeed = $checkup->endspeed;
            $currentrow->duration = getduration($currentrow->endtime, $currentrow->starttime);
            $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;
            $currentrow->speed = $currentrow->speed;
            $datarows[] = $currentrow;
        }
    } else {
        $currentrow = end($data);
        $currentrow->endlat = $currentrow->startlat;
        $currentrow->endlong = $currentrow->startlong;
        $currentrow->endtime = date('Y-m-d', strtotime($currentrow->starttime));
        $currentrow->endtime .= " 23:59:59";
        $currentrow->endodo = $currentrow->startodo;
        $currentrow->endspeed = $currentrow->startspeed;
        $currentrow->duration = getduration($currentrow->endtime, $currentrow->starttime);
        $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;
        $currentrow->speed = $currentrow->speed;
        $datarows[] = $currentrow;
    }
    return $datarows;
}

function dispalytraveldata_pdf($datarows, $vehicleid, $unitno, $geocode, $STDate, $ETDate, $customerno) {
    ?>
 <table id='search_table_2' style="width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;">
   <tbody>
 <?php
$t = 1;
    $runningtime = 0;
    $idletime = 0;
    $idle_ign_on = 0;
    $totaldistance = 0;
    $lastdate = null;
    $totalminute = 0;
    $today = date('d-m-Y', strtotime('Now'));
    $lastLat = 0;
    $lastLng = 0;
    $lastLocation = '';
    if (isset($datarows)) {
        $usegeolocation = get_usegeolocation($customerno);
        $chkManager = new CheckpointManager($customerno);
        $arrCheckpoints = $chkManager->getcheckpointsforcustomer();
        foreach ($datarows as $change) {
            $startLocation = 'Same Place';
            $endLocation = 'Same Place';
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                }
                ?>
      </tbody></table>
    <?php
$lastdate = date('d-m-Y', strtotime($change->endtime));
                ?>
    <hr  id='style-six' /><br/>
    <table  id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;' >
      <tbody>
        <tr style='background-color:#CCCCCC;font-weight:bold;'>
          <td style='width:20px;' ></td>
          <td style='width:100px;height:auto;'>Start Time</td>
          <td style='width:100px;height:auto;'>End Time</td>
          <td style='width:150px;height:auto;'>Start Location<?php if ($usegeolocation == 1) {echo "(Checkpoint)";}?></td>
          <td style='width:150px;height:auto;'>End Location</td>
          <td style='width:100px;height:auto;'>Duration [HH:MM]</td>
          <td style='width:50px;height:auto;'>Distance [KM]</td>
          <td style='width:100px;height:auto;'>Cumulative Distance[KM]</td>
          <td style='width:50px;height:auto;'>Status</td>
        </tr>
        <tr style='background-color:#D8D5D6;font-weight:bold;'><td colspan='9' >Date<?php echo $lastdate; ?></td></tr>
    <?php
$i = 1;
            }
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            $totaldistance += round($change->distancetravelled, 2);
            $duration = hrs_mins($change->duration);
            $chk = getChkRealy($change->startlat, $change->startlong, $arrCheckpoints);
            echo "<tr>";
            echo "<td  width='20px' >" . $i++ . "</td>";
            echo "<td style='width:100px;height:auto;'>$change->starttime</td>";
            echo "<td style='width:100px;height:auto;'>$change->endtime</td>";
            //Printing Location
            if ($lastLat != 0 && $lastLng != 0 && $change->startlat == $lastLat && $change->startlong == $lastLng) {
                $startLocation = $lastLocation;
            } else {
                $startLocation = getlocation($change->startlat, $change->startlong, $geocode, $_SESSION['customerno']);
            }
            if ($change->startlat != $change->endlat && $change->startlong != $change->endlong) {
                $endLocation = getlocation($change->endlat, $change->endlong, $geocode, $_SESSION['customerno']);
            } else {
                $endLocation = $startLocation;
            }
            $customChekpoint = '';
            if ($usegeolocation == 1 && $change->ignition != 1) {
                $customChekpoint = isset($chk[0]->cname) ? '<br/><b style="color:green">(' . $chk[0]->cname . ')</b>' : '';
            }
            $stLoc = $startLocation . '' . $customChekpoint; //die();
            echo "<td style='width:150px;height:auto;'>" . wordwrap($stLoc, Location_Wrap) . "</td>";
            echo "<td style='width:150px;height:auto;'>" . wordwrap($endLocation, Location_Wrap, "<br/>\n") . " </td>";
            echo "<td style='width:100px;height:auto;'>$duration</td>";
            echo "<td style='width:100px;height:auto;'>" . round($change->distancetravelled, 1) . "</td>";
            echo "<td style='width:100px;height:auto;'>" . $totaldistance . "</td>";
            if ($change->distancetravelled > 0) {
                echo "<td style='width:50px;height:auto;'>Running</td>";
                $runningtime += $change->duration;
            } elseif ($change->ignition == 1) {
                echo "<td style='width:50px;height:auto;'>Idle - Ignition On</td>";
                $idle_ign_on += $change->duration;
            } else {
                echo "<td style='width:50px;height:auto;'>Idle</td>";
                $idletime += $change->duration;
            }
            echo "</tr>";
            $lastLat = $change->endlat;
            $lastLng = $change->endlong;
            $lastLocation = $endLocation;
        }
        echo "</tbody></table>";
    }
    if ($totaldistance > 0 && $runningtime != 0) {
        $AverageSpeed = (int) ($totaldistance / ($runningtime / 60));
    } else {
        $AverageSpeed = 0;
    }
    //$totaldistance = round($totaldistance, 1);
    $totaltime = round(abs(strtotime($ETDate) - strtotime($STDate)) / 60, 2);
    $offtime = $totaltime - $runningtime - $idle_ign_on;
    $offtime = hrs_mins($offtime);
    $runningtime = hrs_mins($runningtime);
    $idletime = hrs_mins($idletime);
    $idle_ign_on = hrs_mins($idle_ign_on);
    echo "
    <hr style='margin-top:20px;'>
    <page_footer>
        <table class='page_footer'>
            <tr>
                <td style='width: 100%; text-align: right'>
                    page [[page_cu]]/[[page_nb]]
                </td>
            </tr>
        </table>
    </page_footer>
<div style='float:right;margin:15px;margin-right:60px;'>
    <table align='center'  style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
    <tbody>
            <tr  style='background-color:#CCCCCC;font-weight:bold;'><td colspan = '9'><h4>Statistics</h4></td></tr>
            <tr><td colspan = '9'>Total Running Time = $runningtime Hours</td></tr>
            <tr><td colspan = '9'>Total Idle Time = $offtime Hours</td></tr>
                <tr><td colspan = '9'>Total Idle-Ignition On Time = $idle_ign_on Hours</td></tr>
            <tr><td colspan = '9'>Total Distance Travelled = $totaldistance km</td></tr>
            <tr><td colspan = '9'>Average Speed [Running Time] = $AverageSpeed km/hr</td></tr>
        </tbody>
  </table>
</div>
    ";
}

function displaytraveldata_trip($datarows, $vehicleid, $unitno, $geocode) {
    //echo"<pre>"; print_r($datarows); die();
    $starttime = 0;
    $endtime = 0;
    ?>
   <table id='search_table_2' style="width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;">
     <?php
$t = 1;
    $runningtime = 0;
    $idletime = 0;
    $totaldistance = 0;
    $lastdate = null;
    $totalminute = 0;
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    if (isset($datarows)) {
        $z = 0;
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
                    $count = $t;
                }
                ?>
      </table>
      <h4>
        <?php
//echo "<div> Date ".date('d-m-Y',strtotime($change->endtime))."</div>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                ?>
      </h4>
      <hr  id="style-six" />
      <table  id='search_table_2' style="width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;">
        <tr style="background-color:#CCCCCC;">
          <td style='width:20px;'  ></td>
          <td style='width:50px;height:auto;'>Start Time</td>
          <td style='width:50px;height:auto;'>End Time</td>
          <td style='width:300px;height:auto;'>Start Location</td>
          <td style='width:300px;height:auto;'>End Location</td>
          <td style='width:100px;height:auto;'>Duration [H:M]</td>
          <td style='width:50px;height:auto;'>Distance [KM]</td>
          <td style='width:100px;height:auto;'>Cumulative Distance[KM]</td>
          <td style='width:50px;height:auto;'>Status</td>
        </tr>
        <tbody>
        <?php
$t++;
                $i = 1;
            }
            $currentdate = date("Y-m-d H:i:s");
            $currentdate = substr($currentdate, '0', 11);
            if ($change->startlat == 0) {
                $latlong = getlatlong($change->starttime, $change->endtime, $vehicleid, $unitno, 'asc');
                if (isset($latlong) && count($latlong) > 0) {
                    $change->startlat = $latlong[0];
                    $change->startlong = $latlong[1];
                }
            }
            if ($change->endlat == 0) {
                $latlong = getlatlong($change->starttime, $change->endtime, $vehicleid, $unitno, 'desc');
                if (isset($latlong) && count($latlong) > 0) {
                    $change->endlat = $latlong[0];
                    $change->endlong = $latlong[1];
                }
            }
            //Removing Date Details From DateTime
            $starttime += strtotime($change->starttime);
            $change->starttime = date("H:i", $starttime);
            $endtime += strtotime($change->endtime);
            $change->endtime = date("H:i", $endtime);
            if ($change->ignition == 1) {
                echo "<tr style='cursor:pointer;' style onclick='call_row(" . ++$z . ")' id='" . $z . "' >";
            } else {
                echo "<tr>";
            }
            echo "<td  width='20px' >" . $i++ . "<input type='hidden' id='vehicle" . $z . "' value='" . $vehicleid . "'>
                        <input type='hidden' id='unitno" . $z . "' value='" . $unitno . "'><input type='hidden' id='date" . $z . "' value='" . $lastdate . "'>
                        <input type='hidden' id='timestamp" . $z . "' value='" . $change->starttime . "," . $change->endtime . "'></td>";
            echo "<td style='width:50px;height:auto;'>$change->starttime</td>";
            echo "<td style='width:50px;height:auto;'>$change->endtime</td>";
            //Printing Location
            $location = getlocation($change->startlat, $change->startlong, $geocode, $_SESSION['customerno']);
            echo "<td style='width:300px;height:auto;'>$location</td>";
            if ($change->startlat != $change->endlat && $change->startlong != $change->endlong) {
                $location = getlocation($change->endlat, $change->endlong, $geocode, $_SESSION['customerno']);
                echo "<td style='width:300px;height:auto;'>$location</td>";
            } else {
                echo "<td >Same Place</td>";
            }
            $hour = floor(($change->duration) / 60);
            $minutes = ($change->duration) % 60;
            if ($minutes < 10) {
                $minutes = "0" . $minutes;
            }
            $change->duration = $hour . ":" . $minutes;
            echo "<td style='width:100px;height:auto;'>$change->duration</td>";
            if ($change->ignition == 0) {
                echo '<td></td>';
            } else {
                echo "<td style='width:50px;height:auto;'>" . round($change->distancetravelled, 1) . "</td>";
                $totaldistance += round($change->distancetravelled, 1);
            }
            echo "<td style='width:100px;height:auto;'>" . $totaldistance . "</td>";
            if ($change->ignition == 1) {
                echo "<td style='width:50px;height:auto;'>Running</td>";
                $runningtime += $minutes + ($hour * 60);
            } else {
                echo "<td style='width:50px;height:auto;'>Idle</td>";
                $idletime += $minutes + ($hour * 60);
            }
            echo "</tr>";
        }
        echo "</tbody>";
    }
    if (isset($totaldistance) && ($totaldistance) > 0) {
        if (isset($runningtime) && $runningtime != 0) {
            $AverageSpeed = (int) ($totaldistance / ($runningtime / 60));
        }
    } else {
        $AverageSpeed = 0;
    }
    $totaldistance = round($totaldistance, 1);
    $totaltime = round(abs($endtime - $starttime) / 60, 2);
    $offtime = $totaltime - $runningtime;
    $hours = floor(($offtime) / 60);
    $minutess = ($offtime) % 60;
    if ($minutess < 10) {
        $minutess = "0" . $minutess;
    }
    $offtime = $hours . ":" . $minutess;
    $hour = floor(($runningtime) / 60);
    $minutes = ($runningtime) % 60;
    if ($minutes < 10) {
        $minutes = "0" . $minutes;
    }
    $runningtime = $hour . ":" . $minutes;
    $hour = floor(($idletime) / 60);
    $minutes = ($idletime) % 60;
    if ($minutes < 10) {
        $minutes = "0" . $minutes;
    }
    $idletime = $hour . ":" . $minutes;
    echo "
    <tfoot>
        <tr>
            <th colspan = '9'>Statistics</th>
        </tr>
        <tr><td colspan = '9'>Total Running Time = $runningtime Hours</td></tr>
        <tr><td colspan = '9'>Total Idle Time = $offtime Hours</td></tr>
        <tr><td colspan = '9'>Total Distance Travelled = $totaldistance km</td></tr>
        <tr><td colspan = '9'>Average Speed [Running Time] = $AverageSpeed km/hr</td></tr>
       </tfoot>
  </table>
    ";
}

function dispalytraveldata_csv($customerno, $datarows, $vehicleid, $unitno, $geocode) {
    $t = 1;
    $runningtime = 0;
    $idletime = 0;
    $totaldistance = 0;
    $lastdate = null;
    $devicemanager = new DeviceManager($customerno);
    if (isset($datarows)) {
        $z = 0;
        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                $count = $t;
                //echo "<h4><div> Date ".date('d-m-Y',strtotime($change->endtime))."</div></h4><hr  id='style-six' />";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                ?>
        <table  id='search_table_2' style="width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;">
          <tr style="background-color:#CCCCCC;">
            <td style='width:20px; text-align: center;'></td>
            <td style='width:50px;height:auto; text-align: center;'>Start Time</td>
            <td style='width:50px;height:auto; text-align: center;'>End Time</td>
            <td style='width:300px;height:auto; text-align: center;'>Start Location</td>
            <td style='width:300px;height:auto; text-align: center;'>End Location</td>
            <td style='width:100px;height:auto; text-align: center;'>Duration [H:M]</td>
            <td style='width:50px;height:auto; text-align: center;'>Distance [KM]</td>
            <td style='width:100px;height:auto; text-align: center;'>Cumulative Distance[KM]</td>
            <td style='width:50px;height:auto; text-align: center;'>Status</td>
          </tr>
          <?php
$t++;
                $i = 1;
            }
            $currentdate = date("Y-m-d H:i:s");
            $currentdate = substr($currentdate, '0', 11);
            if ($change->startlat == 0) {
                $latlong = getlatlong_cron($customerno, $change->starttime, $change->endtime, $vehicleid, $unitno, 'asc');
                if (isset($latlong) && count($latlong) > 0) {
                    $change->startlat = $latlong[0];
                    $change->startlong = $latlong[1];
                }
            }
            if ($change->endlat == 0) {
                $latlong = getlatlong_cron($customerno, $change->starttime, $change->endtime, $vehicleid, $unitno, 'desc');
                if (isset($latlong) && count($latlong) > 0) {
                    $change->endlat = $latlong[0];
                    $change->endlong = $latlong[1];
                }
            }
            //Removing Date Details From DateTime
            $starttime = strtotime($change->starttime);
            $change->starttime = date("H:i", $starttime);
            $endtime = strtotime($change->endtime);
            $change->endtime = date("H:i", $endtime);
            if ($change->ignition == 1) {
                echo "<tr>";
            } else {
                echo "<tr>";
            }
            echo "<td  width='20px' style=' text-align: center;' >" . $i++ . "</td>";
            echo "<td style='width:50px;height:auto; text-align: center;'>$change->starttime</td>";
            echo "<td style='width:50px;height:auto; text-align: center;'>$change->endtime</td>";
            //Printing Location
            $location = getlocation($change->startlat, $change->startlong, $geocode, $customerno);
            echo "<td style='width:300px;height:auto; text-align: center;'>$location</td>";
            if ($change->startlat != $change->endlat && $change->startlong != $change->endlong) {
                $location = getlocation($change->endlat, $change->endlong, $geocode, $customerno);
                echo "<td style='width:300px;height:auto; text-align: center;'>$location</td>";
            } else {
                echo "<td style=' text-align: center;'>Same Place</td>";
            }
            $hour = floor(($change->duration) / 60);
            $minutes = ($change->duration) % 60;
            if ($minutes < 10) {
                $minutes = "0" . $minutes;
            }
            $change->duration = $hour . ":" . $minutes;
            echo "<td style='width:100px;height:auto; text-align: center;'>$change->duration</td>";
            if ($change->ignition == 0) {
                echo '<td></td>';
            } else {
                echo "<td style='width:50px;height:auto; text-align: center;'>" . round($change->distancetravelled, 1) . "</td>";
                $totaldistance += round($change->distancetravelled, 1);
            }
            echo "<td style='width:100px;height:auto; text-align: center;'>" . $totaldistance . "</td>";
            if ($change->ignition == 1) {
                echo "<td style='width:50px;height:auto; text-align: center;'>Running</td>";
                $runningtime += $minutes + ($hour * 60);
            } else {
                echo "<td style='width:50px;height:auto; text-align: center;'>Idle</td>";
                $idletime += $minutes + ($hour * 60);
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    if (isset($totaldistance) && ($totaldistance) > 0) {
        if (isset($runningtime) && $runningtime != 0) {
            $AverageSpeed = (int) ($totaldistance / ($runningtime / 60));
        }
    } else {
        $AverageSpeed = 0;
    }
    $totaldistance = round($totaldistance, 1);
    $totaltime = 1440 * $count;
    $offtime = $totaltime - $runningtime;
    $hours = floor(($offtime) / 60);
    $minutess = ($offtime) % 60;
    if ($minutess < 10) {
        $minutess = "0" . $minutess;
    }
    $offtime = $hours . ":" . $minutess;
    $hour = floor(($runningtime) / 60);
    $minutes = ($runningtime) % 60;
    if ($minutes < 10) {
        $minutes = "0" . $minutes;
    }
    $runningtime = $hour . ":" . $minutes;
    $hour = floor(($idletime) / 60);
    $minutes = ($idletime) % 60;
    if ($minutes < 10) {
        $minutes = "0" . $minutes;
    }
    $idletime = $hour . ":" . $minutes;
    echo "
    <hr style='margin-top:20px;'>
    <div style='float:right;margin:15px;margin-right:60px;'>
    <table align='right'  style='text-align:center;'>
    <tr style='background-color:#CCCCCC;'>
      <th colspan = '9'><h4>Statistics</h4></th>
    </tr>
    <tr><td colspan = '9' style=' text-align: center;'>Total Running Time = $runningtime Hours</td></tr>
    <tr><td colspan = '9' style=' text-align: center;'>Total Idle Time = $offtime Hours</td></tr>
    <tr><td colspan = '9' style=' text-align: center;'>Total Distance Travelled = $totaldistance km</td></tr>
    <tr><td colspan = '9' style=' text-align: center;'>Average Speed [Running Time] = $AverageSpeed km/hr</td></tr>
  </table>
  </div>
    ";
}

function dispalytraveldata_excel($customerno, $datarows, $vehicleid, $unitno, $geocode, $STDate, $ETDate) {
    $report = "";
    $report .= "<table id='search_table_2' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
    $report .= "<tbody>";
    $t = 1;
    $runningtime = 0;
    $idletime = 0;
    $idle_ign_on = 0;
    $totaldistance = 0;
    $lastdate = null;
    $totalminute = 0;
    $today = date('d-m-Y', strtotime('Now'));
    $lastLat = 0;
    $lastLng = 0;
    $lastLocation = '';
    if (isset($datarows)) {
        $usegeolocation = get_usegeolocation($customerno);
        $chkManager = new CheckpointManager($customerno);
        $arrCheckpoints = $chkManager->getcheckpointsforcustomer();
        foreach ($datarows as $change) {
            $startLocation = 'Same Place';
            $endLocation = 'Same Place';
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                }
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $checkpoint = ($usegeolocation == 1) ? "(Checkpoint)" : '';
                $report .= "</tbody></table>";
                $report .= "<table  id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;' >";
                $report .= "<tbody>";
                $report .= "<tr style='background-color:#CCCCCC;font-weight:bold;'>";
                $report .= "<td style='width:20px;' ></td>";
                $report .= "<td style='width:50px;height:auto;'>Start Time</td>";
                $report .= "<td style='width:50px;height:auto;'>End Time</td>";
                $report .= "<td style='width:300px;height:auto;'>Start Location " . $checkpoint . "</td>";
                $report .= "<td style='width:300px;height:auto;'>End Location</td>";
                $report .= "<td style='width:100px;height:auto;'>Duration [HH:MM]</td>";
                $report .= "<td style='width:50px;height:auto;'>Distance [KM]</td>";
                $report .= "<td style='width:100px;height:auto;'>Cumulative Distance[KM]</td>";
                $report .= "<td style='width:50px;height:auto;'>Status</td>";
                $report .= "</tr>";
                $report .= "<tr style='background-color:#D8D5D6;font-weight:bold;'><td colspan='9' >Date" . $lastdate . "</td></tr>";
                $i = 1;
            }
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            $totaldistance += round($change->distancetravelled, 2);
            $duration = hrs_mins($change->duration);
            $chk = getChkRealy($change->startlat, $change->startlong, $arrCheckpoints);
            if ($lastLat != 0 && $lastLng != 0 && $change->startlat == $lastLat && $change->startlong == $lastLng) {
                $startLocation = $lastLocation;
            } else {
                $startLocation = getlocation($change->startlat, $change->startlong, $geocode, $_SESSION['customerno']);
            }
            if ($change->startlat != $change->endlat && $change->startlong != $change->endlong) {
                $endLocation = getlocation($change->endlat, $change->endlong, $geocode, $_SESSION['customerno']);
            } else {
                $endLocation = $startLocation;
            }
            $customChekpoint = '';
            if ($usegeolocation == 1 && $change->ignition != 1) {
                $customChekpoint = isset($chk[0]->cname) ? '<br/><b style="color:green">(' . $chk[0]->cname . ')</b>' : '';
            }
            $report .= "<tr>";
            $report .= "<td  width='20px' >" . $i++ . "</td>";
            $report .= "<td style='width:50px;height:auto;'>$change->starttime</td>";
            $report .= "<td style='width:50px;height:auto;'>$change->endtime</td>";
            $report .= "<td style='width:150px;height:auto;'>" . wordwrap($startLocation . '' . $customChekpoint, Location_Wrap, "<br/>\n") . "</td>";
            $report .= "<td style='width:150px;height:auto;'>" . wordwrap($endLocation, Location_Wrap, "<br/>\n") . " </td>";
            $report .= "<td style='width:100px;height:auto;'>$duration</td>";
            $report .= "<td style='width:100px;height:auto;'>" . round($change->distancetravelled, 1) . "</td>";
            $report .= "<td style='width:100px;height:auto;'>" . $totaldistance . "</td>";
            if ($change->distancetravelled > 0) {
                $report .= "<td style='width:50px;height:auto;'>Running</td>";
                $runningtime += $change->duration;
            } elseif ($change->ignition == 1) {
                $report .= "<td style='width:50px;height:auto;'>Idle - Ignition On</td>";
                $idle_ign_on += $change->duration;
            } else {
                $report .= "<td style='width:50px;height:auto;'>Idle</td>";
                $idletime += $change->duration;
            }
            $report .= "</tr>";
        }
        $report .= "</tbody></table>";
    }
    if ($totaldistance > 0 && $runningtime != 0) {
        $AverageSpeed = (int) ($totaldistance / ($runningtime / 60));
    } else {
        $AverageSpeed = 0;
    }
    //$totaldistance = round($totaldistance, 1);
    $totaltime = round(abs(strtotime($ETDate) - strtotime($STDate)) / 60, 2);
    $offtime = $totaltime - $runningtime - $idle_ign_on;
    $offtime = hrs_mins($offtime);
    $runningtime = hrs_mins($runningtime);
    $idletime = hrs_mins($idletime);
    $idle_ign_on = hrs_mins($idle_ign_on);
    $report .= "<hr style='margin-top:20px;'>
                <div style='float:right;margin:15px;margin-right:60px;'>
                    <table align='center'  style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                    <tbody>
                            <tr  style='background-color:#CCCCCC;font-weight:bold;'><td colspan = '9'><h4>Statistics</h4></td></tr>
                            <tr><td colspan = '9'>Total Running Time = $runningtime Hours</td></tr>
                            <tr><td colspan = '9'>Total Idle Time = $offtime Hours</td></tr>
                            <tr><td colspan = '9'>Total Idle-Ignition On Time = $idle_ign_on Hours</td></tr>
                            <tr><td colspan = '9'>Total Distance Travelled = $totaldistance km</td></tr>
                            <tr><td colspan = '9'>Average Speed [Running Time] = $AverageSpeed km/hr</td></tr>
                        </tbody>
                  </table>
                </div>";
    echo $report;
}

function dispalytraveldata($datarows, $vehicleid, $unitno, $geocode) {
    $t = 1;
    $customerno = isset($cust) ? $cust : $_SESSION['customerno'];
    $runningtime = 0;
    $idletime = 0;
    $idle_ign_on = 0;
    $startdate = $_POST['SDate'] . ' ' . $_POST['STime'];
    $enddate = $_POST['EDate'] . ' ' . $_POST['ETime'];
    $startdate = date('Y-m-d H:i:s', strtotime($startdate));
    $enddate = date('Y-m-d H:i:s', strtotime($enddate));
    $totaldistance = 0;
    $lastdate = null;
    $totalminute = 0;
    $lastLat = 0;
    $lastLng = 0;
    $lastLocation = '';
    $finalReport = '';
    if (isset($datarows)) {
        $z = 0;
        $ak_text = 0;
        $chkManager = new CheckpointManager($customerno);
        $arrCheckpoints = $chkManager->getcheckpointsforcustomer();
        foreach ($datarows as $change) {
            $ak_text++;
            $startLocation = 'Same Place';
            $endLocation = 'Same Place';
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
                    $count = $t;
                }
                $finalReport .= "<tr><th align='center'  style='background:#d8d5d6' colspan = '100%'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $t++;
                $i = 1;
            }
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            @$totaldistance += round($change->distancetravelled, 2);
            @$duration = hrs_mins($change->duration);
            @$chk = getChkRealy($change->startlat, $change->startlong, $arrCheckpoints);
            if (@$change->distancetravelled > 0) {
                $finalReport .= "<tr style='cursor:default;' onclick='call_row(" . ++$z . ")' id='" . $z . "'  >";
            } else {
                $finalReport .= "<tr style='cursor:default;' >";
            }
            $finalReport .= "<td>" . $i++ . "<input type='hidden' id='vehicle" . $z . "' value='" . $vehicleid . "'>
                          <input type='hidden' id='unitno" . $z . "' value='" . $unitno . "'><input type='hidden' id='date" . $z . "' value='" . $lastdate . "'>
                          <input type='hidden' id='timestamp" . $z . "' value='" . $change->starttime . "," . $change->endtime . "'></td>";
            $finalReport .= "<td>$change->starttime</td>";
            $finalReport .= "<td>$change->endtime</td>";
            //Printing Location
            if ($lastLat != 0 && $lastLng != 0 && $change->startlat == $lastLat && $change->startlong == $lastLng) {
                $startLocation = $lastLocation;
            } else {
                $startLocation = getlocation($change->startlat, $change->startlong, $geocode, $_SESSION['customerno']);
            }
            if ($change->startlat != $change->endlat && $change->startlong != $change->endlong) {
                $endLocation = getlocation($change->endlat, $change->endlong, $geocode, $_SESSION['customerno']);
            } else {
                $endLocation = $startLocation;
            }
            $finalReport .= "<td>$startLocation</td>";
            $finalReport .= "<td>$endLocation</td>";
            $finalReport .= "<td>$duration</td>";
            @$finalReport .= "<td>" . round($change->distancetravelled, 2) . "</td>";
            $finalReport .= "<td>" . $totaldistance . "</td>";
            if (@$change->distancetravelled > 0) {
                $finalReport .= "<td style='cursor:pointer;'> <a style='text-decoration:underline;'>Running</a></td>";
                $runningtime += $change->duration;
            } else {
                if ($change->ignition == 1) {
                    $finalReport .= "<td>Idle - Ignition On</td>";
                    $idle_ign_on += isset($change->duration) ? $change->duration : 0;
                } else {
                    $finalReport .= "<td>Idle</td>";
                    @$idletime += $change->duration;
                }
            }
            if (!empty($chk)) {
                $finalReport .= "<td ><a style='text-decoration:underline;cursor:pointer;' onclick='create_map_for_modal_report_onlymap(" . $chk[0]->cgeolat . "," . $chk[0]->cgeolong . ")'>" . $chk[0]->cname . "</a></td>";
            } else {
                $finalReport .= '<td>
                                    <a id="added_' . $ak_text . '" style="display:none">Added as checkpoint</a>
                                    <a class="add_button" data-toggle="modal" id="add_' . $ak_text . '" href="#test_" onclick="assign_values_to_inputs(\'' . $startLocation . '\',' . $change->startlat . ',' . $change->startlong . ',' . $ak_text . ')">
                                    <img width="18" height="18" alt="add as checkpoint" src="../../images/add.png"></a> </td>';
            }
            $finalReport .= "</tr>";
            $lastLat = $change->endlat;
            $lastLng = $change->endlong;
            $lastLocation = $endLocation;
        }
    }
    $finalReport .= '</tbody>';
    if (isset($totaldistance) && ($totaldistance) > 0 && isset($runningtime) && $runningtime != 0) {
        $AverageSpeed = (int) ($totaldistance / ($runningtime / 60));
    } else {
        $AverageSpeed = 0;
    }
    $totaldistance = round($totaldistance, 1);
    $totaltime = round(abs(strtotime($enddate) - strtotime($startdate)) / 60, 2);
    $offtime = $totaltime - $runningtime - $idle_ign_on;
    $offtime = hrs_mins($offtime);
    $runningtime = hrs_mins($runningtime);
    $idletime = hrs_mins($idletime);
    $idle_ign_on = hrs_mins($idle_ign_on);
    $finalReport .= "
                        </table>
                        <div class='container' style='width:45%;'>
                            <table class='table newTable' >
                            <thead>
                                <tr><th colspan = '10'>Statistics</th></tr>
                            </thead>
                            <tbody>
                                <tr><td style='text-align:center;' colspan = '10'>Total Running Time = $runningtime Hours</td></tr>
                                <tr><td style='text-align:center;' colspan = '10'>Total Idle Time = $offtime Hours</td></tr>
                                <tr><td style='text-align:center;' colspan = '10'>Total Idle-Ignition On Time = $idle_ign_on Hours</td></tr>
                                <tr><td style='text-align:center;' colspan = '10'>Total Distance Travelled = $totaldistance km</td></tr>
                                <tr><td style='text-align:center;' colspan = '10'>Average Speed [Running Time] = $AverageSpeed km/hr</td></tr>
                            </tbody>
                            </table>
                        </div>";
    echo $finalReport;
}

/**
 *
 * @param mins $val
 * @return string
 */
function hrs_mins($val) {
    $hour = floor($val / 60);
    $minutes = ($val) % 60;
    if ($minutes < 10) {
        $minutes = "0" . $minutes;
    }
    $final = $hour . ":" . $minutes;
    return $final;
}

function getvalidlatlongfortimeperiod($starttime, $endtime, $unitno, $vehicleid, $order, $customerno = '') {
    $userdate = date('Y-m-d', strtotime($starttime));
    $customerno = ($customerno != '') ? $customerno : $_SESSION['customerno'];
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
    $location = "sqlite:" . $location;
    $latlong = array();
    $query = "SELECT deviceid,devicelat,devicelong from devicehistory
                  INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
                  INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = unithistory.lastupdated
                  WHERE devicehistory.customerno = $customerno AND";
    $query .= " vehiclehistory.vehicleid= $vehicleid AND devicehistory.gpsfixed = 'A' and devicehistory.lastupdated between '$starttime'";
    $query .= " and '$endtime' ORDER BY devicehistory.lastupdated $order";
    try {
        $database = new PDO($location);
        $result = $database->query($query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $latlong[0] = $row['devicelat'];
                $latlong[1] = $row['devicelong'];
                break;
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    return $latlong;
}

function getvalidlatlongfortimeperiod_cron($customerno, $starttime, $endtime, $unitno, $vehicleid, $order) {
    $userdate = date('Y-m-d', strtotime($starttime));
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
    $location = "sqlite:" . $location;
    $latlong = array();
    $query = "SELECT deviceid,devicelat,devicelong from devicehistory
                  INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
                  INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = unithistory.lastupdated
                  WHERE devicehistory.customerno = $customerno AND";
    $query .= " vehiclehistory.vehicleid= $vehicleid AND devicehistory.gpsfixed = 'A' and devicehistory.lastupdated between '$starttime'";
    $query .= " and '$endtime' ORDER BY devicehistory.lastupdated $order";
    try {
        $database = new PDO($location);
        $result = $database->query($query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $latlong[0] = $row['devicelat'];
                $latlong[1] = $row['devicelong'];
                break;
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    return $latlong;
}

function getlatlong($starttime, $endtime, $vehicleid, $unitno, $order, $customerno = '') {
    $latlong = getvalidlatlongfortimeperiod($starttime, $endtime, $unitno, $vehicleid, $order, $customerno);
    return $latlong;
}

function getlatlong_cron($customerno, $starttime, $endtime, $vehicleid, $unitno, $order) {
    $latlong = getvalidlatlongfortimeperiod_cron($customerno, $starttime, $endtime, $unitno, $vehicleid, $order);
    return $latlong;
}

function get_usegeolocation($customerno) {
    $GeoCoder_Obj = new GeoCoder($customerno);
    $geolocation = $GeoCoder_Obj->get_use_geolocation();
    return $geolocation;
}

function getlocation($lat, $long, $geocode, $customerno) {
    $address = null;
    $customerno = (!isset($customerno)) ? $_SESSION['customerno'] : $customerno;
    $GeoCoder_Obj = new GeoCoder($customerno);
    $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
    return $address;
}

function get_ajax_data_history($v_id, $data, $timestamp, $unitno) {
    if (isset($_SESSION['customerno'])) {
        $customerno = $_SESSION['customerno'];
        $t_array = explode(",", $timestamp);
        $location = "../../customer/$customerno/unitno/$unitno/sqlite/" . date("Y-m-d", strtotime($data)) . ".sqlite";
        if (file_exists($location)) {
            $location = "sqlite:" . $location;
            // try
            try {
                $database = new PDO($location);
                $query = "SELECT devicehistory.devicelat,
                        devicehistory.devicelong, devicehistory.ignition, devicehistory.status,
                        devicehistory.lastupdated, vehiclehistory.odometer from devicehistory
                        INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
                        WHERE vehiclehistory.vehicleid=" . $v_id . "
                        AND devicelat <> '0.000000' AND devicelong <> '0.000000'
                        and vehiclehistory.lastupdated between '" . date(speedConstants::DATE_Ymd, strtotime($data)) . " " . date(speedConstants::TIME_Hi, strtotime($t_array[0])) . "' and '" . date(speedConstants::DATE_Ymd, strtotime($data)) . " " . date(speedConstants::TIME_Hi, strtotime($t_array[1])) . "'
                        ORDER BY devicehistory.lastupdated ASC";
                $result = $database->query($query);
                $query_count = "SELECT count(*) as count  from devicehistory
                        INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
                        WHERE vehiclehistory.vehicleid=" . $v_id . "
                        AND devicelat <> '0.000000' AND devicelong <> '0.000000'
                        and vehiclehistory.lastupdated between '" . date(speedConstants::DATE_Ymd, strtotime($data)) . " " . date(speedConstants::TIME_Hi, strtotime($t_array[0])) . "' and '" . date(speedConstants::DATE_Ymd, strtotime($data)) . " " . date(speedConstants::TIME_Hi, strtotime($t_array[1])) . "'
                        ORDER BY devicehistory.lastupdated ASC";
                $count_num = $database->query($query_count);
                foreach ($count_num as $row_num) {
                    $count_total = $row_num['count'];
                }
                if ($count_total > 10) {
                    $divisor = intval($count_total / 10);
                } else {
                    $divisor = 1;
                }
                $i = 0;
                $y = 1;
                if (isset($result) && $result != "") {
                    ?><table width="100%" >
               <thead><tr><th>Sno</th><th>Location</th><th>Time</th><th>Distance</th></tr>
               </thead>
               <tbody>
             <?php
$cumdist = 0;
                    foreach ($result as $row) {
                        if ($i == 0) {
                            $startodo = $row['odometer'];
                        }
                        $i++;
                        if (($i % $divisor == 0) && ($y <= 10)) {
                            $endodo = $row['odometer'];
                            $cumdist = ($endodo / 1000) - ($startodo / 1000);
                            $GeoCoder_Obj = new GeoCoder($_SESSION['customerno']);
                            $address = $GeoCoder_Obj->get_location_bylatlong($row['devicelat'], $row['devicelong']);
                            ?><tr><td><?php echo $y++; ?></td><td><?php echo $address; ?></td><td><?php echo date(speedConstants::DEFAULT_DATETIME, strtotime($row['lastupdated'])); ?></td>
                                         <td><?php echo round($cumdist, 2); ?></td></tr><?php
$startodo = $endodo;
                        }
                    }
                    ?>
               </tbody>
             </table><?php
}
            } catch (PDOException $e) {
                die($e);
            }
        } else {
            //echo "flop show";
        }
    }
}

function get_travel_history_report_pdf($vehicleid, $SDate, $EDate, $STime, $ETime, $geocode, $customerno = '', $vgroupname = null) {
    $devicemanager = new DeviceManager($customerno);
    $vehicleno = $devicemanager->getvehiclenofromdeviceid($vehicleid);
    $title = 'Travel History';
    $subTitle = array(
        "Vehicle No: $vehicleno",
        "Start Date: $SDate $STime",
        "End Date: $EDate $ETime"
    );
    if (!is_null($vgroupname)) {
        $subTitle[] = "Group-name: $vgroupname";
    }
    $customer_details = null;
    if (!isset($_SESSION['customerno'])) {
        $cm = new CustomerManager($customerno);
        $customer_details = $cm->getcustomerdetail_byid($customerno);
    }
    echo pdf_header($title, $subTitle, $customer_details);
    get_travel_days_data($vehicleid, $SDate, $EDate, $STime, $ETime, $geocode, $customerno);
    return $vehicleno;
}

function get_travel_history_report_excel($customerno, $vehicleid, $SDate, $EDate, $STime, $ETime, $geocode, $vgroupname = null) {
    $devicemanager = new DeviceManager($customerno);
    $vehicleno = $devicemanager->getvehiclenofromdeviceid($vehicleid);
    $title = 'Travel History';
    $subTitle = array(
        "Vehicle No: $vehicleno",
        "Start Date: $SDate $STime",
        "End Date: $EDate $ETime"
    );
    if (!is_null($vgroupname)) {
        $subTitle[] = "Group-name: $vgroupname";
    }
    $customer_details = null;
    if (!isset($_SESSION['customerno'])) {
        $cm = new CustomerManager($customerno);
        $customer_details = $cm->getcustomerdetail_byid($customerno);
    }
    echo excel_header($title, $subTitle, $customer_details);
    get_travel_days_data($vehicleid, $SDate, $EDate, $STime, $ETime, $geocode, $customerno, 'excel');
    return $vehicleno;
}

function get_travel_days_data($vehid, $SDate, $EDate, $Shour, $Ehour, $geocode, $customerno, $report_type = 'pdf') {
    $um = new UnitManager($customerno);
    $vehicleid = GetSafeValueString($vehid, 'string');
    $totaldays = gendays_cmn($SDate, $EDate);
    @$count = count($totaldays);
    $endelement = end($totaldays);
    $firstelement = $totaldays[0];
    $unitno = $um->getunitnofromdeviceid($vehicleid);
    $days = array();
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $lastday = new_travel_data($customerno, $unitno, $userdate, $count, $firstelement, $endelement, $vehicleid, $Shour, $Ehour);
            if ($lastday != null) {
                $days = array_merge($days, $lastday);
            }
        }
    }
    if (isset($days) && @count($days) > 0) {
        $STDate = $SDate . $Shour . ":00";
        $ETDate = $EDate . $Ehour . ":00";
        switch ($report_type) {
            case 'pdf':
                dispalytraveldata_pdf($days, $vehicleid, $unitno, $geocode, $STDate, $ETDate, $customerno);
                break;
            case 'excel':
                dispalytraveldata_excel($customerno, $days, $vehicleid, $unitno, $geocode, $STDate, $ETDate);
                break;
        }
    }
}

function gettraveldata($vehid, $SDate, $EDate, $geocode, $Shour, $Ehour) {
    $error = "<script>$('#error').show();jQuery('#error').fadeOut(3000)</script>";
    $customerno = $_SESSION['customerno'];
    $um = new UnitManager($customerno);
    $vehicleid = GetSafeValueString($vehid, 'string');
    $totaldays = gendays_cmn($SDate, $EDate);
    $count = count($totaldays);
    $endelement = end($totaldays);
    $firstelement = $totaldays[0];
    $unitno = $um->getunitnofromdeviceid($vehicleid);
    $days = array();
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $lastday = new_travel_data($customerno, $unitno, $userdate, $count, $firstelement, $endelement, $vehicleid, $Shour, $Ehour);
            if ($lastday != null) {
                $days = array_merge($days, $lastday);
            }
        }
    }
    if (isset($days) && count($days) > 0) {
        include 'pages/panels/travelhistrep.php';
        dispalytraveldata($days, $vehicleid, $unitno, $geocode);
    } else {
        echo $error;
    }
}

function get_travel_history_report_trip($customerno, $vehicleid, $SDate, $EDate, $Shour, $Ehour, $geocode) {
    $devicemanager = new DeviceManager($customerno);
    $vehicleno = $devicemanager->getvehiclenofromdeviceid($vehicleid);
    ?>
         <style type="text/css">
           table, td { border: solid 1px  #999999; color:#000000; }
           hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
         </style>
         <?php
gettraveldata_trip($vehicleid, $SDate, $EDate, $Shour, $Ehour, $geocode);
    ?>
<?php
return $vehicleno;
}

function get_travel_history_report_all_vehicle_pdf($vehicleid, $SDate, $EDate, $geocode) {
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $vehicleno = $devicemanager->getvehiclenofromdeviceid($vehicleid);
    ?>
         <div style="width:1100px; height:30px;">
           <table style="width: 1100px; border:none;">
             <tr>
               <td style="width:430px; border:none;"><img src="../../images/elixiaspeed_logo.png" /></td>
               <td style="width:420px; border:none;">
                 <h4 style="text-transform:uppercase;">Travel History Report</h4><br />
               </td>
               <td style="width:230px;border:none;">
                 <img src="../../images/elixia_logo.png"  /></td>
             </tr>
           </table>
         </div>
         Vehicle No.<?php echo $vehicleno; ?>
         From :<?php echo date("d-m-y", strtotime($SDate)); ?> to :<?php echo date("d-m-y", strtotime($EDate)); ?>
         <span style="width:700px;float:right;">            Report  Dated :<?php $date_t = strtotime(date("Y-m-d H:i:s") . ' UTC');
    echo gmdate('Y-m-d H:i:s', $date_t + (0 * 3600));
    ?></span>
         <hr />
         <style type="text/css">
           table, td { border: solid 1px  #999999; color:#000000; }
           hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
         </style>
         <?php
gettraveldata_pdf($vehicleid, $SDate, $EDate, $geocode);
    //return $vehicleno;
}

function get_summary_report_pdf($customerno, $vehicleid, $deviceid, $Date, $geocode) {
    $devicemanager = new DeviceManager($customerno);
    $vehicleno = $devicemanager->getvehiclenofromdeviceid($vehicleid);
    $SDate = GetSafeValueString($Date, 'string');
    $SDate = explode('-', $SDate);
    $SDate = $SDate[2] . "-" . $SDate[1] . "-" . $SDate[0];
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    //$totaldays = array();
    $STdate = date('Y-m-d', strtotime($SDate));
    $um = new UnitManager($customerno);
    $unitno = $um->getunitnofromdeviceid($vehicleid);
    //$acinvertval = $um->getacinvertval($unitno);
    $acdata = $um->getacinvertval($unitno);
    $acinvertval = $acdata['0'];
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$STdate.sqlite";
    if (file_exists($location)) {
        $location = "sqlite:" . $location;
        $devicedata = getsummaryfromsqlite($vehicleid, $location);
        $days = processtraveldata($devicedata);
        if (isset($days) && count($days) > 0) {
            displaytraveldata_filtered($customerno, $days, $vehicleid, $unitno, $geocode);
        } else {
            echo $error;
        }
        $data = getacdata_fromsqlite($location, $deviceid);
        if ($data != null && count($data) > 1) {
            $report = createrep($data);
            if ($report != null) {
                $days = array_merge($days, $report);
            }
        }
        if ($days != null && count($days) > 0) {
            //echo '<td>'.$location.'</td>';
            // include 'pages/panels/travelhistrep_pdf.php';
            create_acpdf_from_report($days, $acinvertval);
        } else {
            echo 'no report';
            echo $error;
        }
    } else {
        echo "<td colspan='5'>No File Found</td>";
    }
}

function getacdata_fromsqlite($location, $deviceid) {
    $devices = array();
    $query = "SELECT devicehistory.ignition, devicehistory.status, devicehistory.lastupdated,
            unithistory.digitalio from devicehistory INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.deviceid= $deviceid AND devicehistory.status!='F'";
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

function getsummaryfromsqlite($vehicleid, $location) {
    $devices2;
    $lastrow;
    try {
        $database = new PDO($location);
        $query = "SELECT devicehistory.ignition, devicehistory.status, devicehistory.lastupdated,devicehistory.deviceid, devicehistory.devicelat,
                devicehistory.devicelong, vehiclehistory.vehicleno, vehiclehistory.odometer,unithistory.digitalio from devicehistory
                INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
                INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = unithistory.lastupdated
                WHERE vehiclehistory.vehicleid=$vehicleid ORDER BY devicehistory.lastupdated DESC Limit 0,1";
        $result = $database->query($query);
        $lastrow;
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $lastdevice = new VODevices();
                $lastdevice->ignition = $row['ignition'];
                $lastdevice->status = $row['status'];
                $lastdevice->lastupdated = $row['lastupdated'];
                $lastdevice->deviceid = $row['deviceid'];
                $lastdevice->devicelat = $row['devicelat'];
                $lastdevice->devicelong = $row['devicelong'];
                $lastdevice->vehicleno = $row['vehicleno'];
                $lastdevice->odometer = $row['odometer'];
                $lastdevice->digitalio = $row['digitalio'];
                $lastrow = $lastdevice;
            }
        }
        $query = "SELECT devicehistory.ignition, devicehistory.status, devicehistory.lastupdated,devicehistory.deviceid, devicehistory.devicelat,
                devicehistory.devicelong, vehiclehistory.vehicleno, vehiclehistory.odometer,unithistory.digitalio from devicehistory
                INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
                INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = unithistory.lastupdated
                WHERE vehiclehistory.vehicleid=$vehicleid ORDER BY devicehistory.lastupdated ASC";
        $result = $database->query($query);
        $devices2 = array();
        $laststatus;
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                if (!isset($laststatus) || $laststatus != $row['ignition']) {
                    $device = new VODevices();
                    $device->deviceid = $row['deviceid'];
                    $device->vehicleno = $row['vehicleno'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->ignition = $row['ignition'];
                    $device->status = $row['status'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->odometer = $row['odometer'];
                    $device->digitalio = $row['digitalio'];
                    $laststatus = $row['ignition'];
                    $devices2[] = $device;
                }
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    $sqlitedata[] = $devices2;
    $sqlitedata[] = $lastrow;
    return $sqlitedata;
}

function displaytraveldata_filtered($customerno, $datarows, $vehicleid, $unitno, $geocode) {
    $runningtime = 0;
    $idletime = 0;
    $totaldistance = 0;
    $lastdate = null;
    $devicemanager = new DeviceManager($customerno);
    if (isset($datarows)) {
        $z = 0;
        foreach ($datarows as $change) {
            $date = new Date();
            if ($change->startlat == 0) {
                $latlong = getlatlong_cron($customerno, $change->starttime, $change->endtime, $vehicleid, $unitno, 'asc');
                if (isset($latlong) && count($latlong) > 0) {
                    $change->startlat = $latlong[0];
                    $change->startlong = $latlong[1];
                }
            }
            if ($change->endlat == 0) {
                $latlong = getlatlong_cron($customerno, $change->starttime, $change->endtime, $vehicleid, $unitno, 'desc');
                if (isset($latlong) && count($latlong) > 0) {
                    $change->endlat = $latlong[0];
                    $change->endlong = $latlong[1];
                }
            }
            //Removing Date Details From DateTime
            $starttime = strtotime($change->starttime);
            $change->starttime = date("H:i", $starttime);
            $endtime = strtotime($change->endtime);
            $change->endtime = date("H:i", $endtime);
            $hour = floor(($change->duration) / 60);
            $minutes = ($change->duration) % 60;
            if ($minutes < 10) {
                $minutes = "0" . $minutes;
            }
            $change->duration = $hour . ":" . $minutes;
            if ($change->ignition != 0) {
                $totaldistance += round($change->distancetravelled, 1);
            }
            if ($change->ignition == 1) {
                $runningtime += $minutes + ($hour * 60);
            } else {
                $idletime += $minutes + ($hour * 60);
            }
        }
    }
    if (isset($totaldistance) && ($totaldistance) > 0) {
        if (isset($runningtime) && $runningtime != 0) {
            $AverageSpeed = (int) ($totaldistance / ($runningtime / 60));
        }
    } else {
        $AverageSpeed = 0;
    }
    $totaldistance = round($totaldistance, 1);
    $hour = floor(($runningtime) / 60);
    $minutes = ($runningtime) % 60;
    if ($minutes < 10) {
        $minutes = "0" . $minutes;
    }
    $runningtime = $hour . ":" . $minutes;
    $hour = floor(($idletime) / 60);
    $minutes = ($idletime) % 60;
    if ($minutes < 10) {
        $minutes = "0" . $minutes;
    }
    $idletime = $hour . ":" . $minutes;
    echo "<td>Distance Travelled = $totaldistance km</td>
            <td>Average Speed [Running Time] = $AverageSpeed km/hr</td>
            <td>Running Time = $runningtime Hours</td>";
}

function createrep($datas) {
    if (isset($datas)) {
        $currentrow = new stdClass();
        $currentrow->digitalio = $data[0]->digitalio;
        $currentrow->ignition = $data[0]->ignition;
        $currentrow->starttime = $data[0]->lastupdated;
        $currentrow->endtime = 0;
        $gen_report = array();
        $a = 0;
        $counter = 0;
        //Creating Rows Of Data Where Duration Is Greater Than 3
        while (TRUE) {
            $i = 1;
            /* while(isset($data[$a+$i]) && getduration($data[$a+$i]->lastupdated,$currentrow->starttime)<3)
            {
            $i+=1;
            } */
            while (isset($data[$a + $i]) && checkdate_status($data[$a + $i], $currentrow, $data, ($a + $i))) {
                $i += 1;
            }
            if (isset($data[$a + $i])) {
                $currentrow->endtime = $data[$a + $i]->lastupdated;
                $currentrow->duration = round(getduration($currentrow->endtime, $currentrow->starttime), 0);
                $gen_report[] = $currentrow;
                $currentrow = new stdClass();
                $currentrow->starttime = $data[$a + $i]->lastupdated;
                $currentrow_count = $a + $i;
                //Just To Check That Data For The Next Row Is Not Wrong
                while (isset($data[$a + $i + 1]) && getduration($data[$a + $i + 1]->lastupdated, $currentrow->starttime) < 3) {
                    $i += 1;
                }
                if (($a + $i) > $currentrow_count) {
                    $gen_report[$counter]->endtime = $data[$a + $i]->lastupdated;
                    $gen_report[$counter]->duration = round(getduration($gen_report[$counter]->endtime, $gen_report[$counter]->starttime), 0);
                    $currentrow->starttime = $data[$a + $i]->lastupdated;
                }
                $currentrow->digitalio = $data[$a + $i]->digitalio;
                $currentrow->ignition = $data[$a + $i]->ignition;
                $a += $i;
            } else {
                break;
            }
            $counter += 1;
        }
        //var_dump($gen_report);
        //Clubing Data With Similar AC & Ignition [Both Together]
        $gen_report = optimizerep_clean($gen_report);
        return $gen_report;
    }
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

function create_acpdf_from_report($datarows, $acinvert) {
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
    $display = '';
    if (isset($datarows)) {
        foreach ($datarows as $change) {
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            if ($acinvert == 1) {
                if ($change->digitalio == 0) {
                    $runningtime += $change->duration;
                } else {
                    $idletime += $change->duration;
                }
            } else {
                if ($change->digitalio == 0) {
                    $runningtime += $change->duration;
                } else {
                    $idletime += $change->duration;
                }
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
        }
    }
    if ($acinvert == 1) {
        $display .= "<td>Total Gen Set ON Time = $idletime Minutes</td><td>Total Gen Set OFF Time = $runningtime Minutes</td>";
    } else {
        $display .= "<td>Total Gen Set ON Time = $runningtime Minutes</td><td>Total Gen Set OFF Time = $idletime Minutes</td>";
    }
    echo $display;
}

function getSMSConsumedDetails($cust) {
    $cm = new CustomerManager();
    $details = $cm->getSMSConsumedDetails($cust);
    $display = "";
    $display .= "<table  border=1 style='width='100%';table-layout: fixed;'><tr><th>User Name</th><th>Vehicle No.</th><th>Message</th><th>Send Time</th></tr>";
    if (isset($details) && !empty($details)) {
        foreach ($details as $row) {
            $message = isset($row['message']) ? $row['message'] : "";
            $realname = isset($row['realname']) ? $row['realname'] : "";
            $sendtime = isset($row['sendtime']) ? $row['sendtime'] : "";
            $vehicleno = isset($row['vehicleno']) ? $row['vehicleno'] : "";
            $message = wordwrap($message, 120, "<br>\n");
            $sendtime = date('d-m-Y H:i:s', strtotime($sendtime));
            $display .= "<tr><td style='width:20%;'>" . $realname . "</td><td style='width:20%;'>" . $vehicleno . "</td><td style='width:40%; word-wrap:break-word;'>" . $message . "</td><td style='width:10%;'>" . $sendtime . "</td></tr>";
        }
    } else {
        $display .= "<tr><td colspan='100%'> No SMS send </td></tr>";
    }
    $display .= "</table>";
    echo $display;
}

?>
