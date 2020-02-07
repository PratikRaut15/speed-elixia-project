<?php

class VODRM {};

function TRANSACTION($report, $db, $C) {
    $tablename = new DateTime($report['date']);
    if ($C == 1) {
        $table = "CREATE TABLE IF NOT EXISTS A" . $tablename->format('dmy') . " (uid INTEGER,tamper INTEGER,powercuts INTEGER,overspeed INTEGER,
                totaldistance INTEGER,fenceconflict INTEGER,idletime INTEGER,runningtime INTEGER,dailyreportid INTEGER,vehicleid INTEGER,
                PRIMARY KEY (dailyreportid));";
        $db->exec($table);
    }

    $query = 'DELETE FROM A' . $tablename->format('dmy') . ' WHERE uid = ' . $report['uid'];
    $db->exec($query);

    $columns = 'uid,tamper,powercuts,overspeed,totaldistance,fenceconflict,idletime,runningtime,vehicleid';
    $values = $report['uid'] . ',' . $report['tamper'] . ',' . $report['powercut'] . ',' . $report['overspeed'] . ',' . $report['totaldistance'] . ',' . $report['fenceconflict'] . ',' . $report['idletime'] . ',' . $report['runningtime'] . ',' . $report['vehicleid'];
    $INSERT = "INSERT INTO A" . $tablename->format('dmy') . "($columns) VALUES ($values);";
    $db->exec($INSERT);
}

function DELETEDAILYTABLE($report, $db) {
    $table = " DELETE FROM $report;"; // . $report . ";";
    $db->exec($table);
}

function TRANSACTIONG($report, $db, $C) {
    $tablename = new DateTime($report['date']);
    if ($C == 1) {
        $table = "CREATE TABLE IF NOT EXISTS A" . $tablename->format('dmy') . " (uid INTEGER,harsh_break INTEGER, sudden_acc INTEGER, sharp_turn INTEGER, towing INTEGER,
                avgspeed INTEGER,genset INTEGER,overspeed INTEGER,
                totaldistance INTEGER,fenceconflict INTEGER,idletime INTEGER,runningtime INTEGER,dailyreportid INTEGER,vehicleid INTEGER,dev_lat FLOAT,
                dev_long FLOAT,first_dev_lat FLOAT,first_dev_long FLOAT, fuel_balance FLOAT DEFAULT 0,topspeed INTEGER,
                PRIMARY KEY (dailyreportid));";
        $db->exec($table);
    }

    $query = 'DELETE FROM A' . $tablename->format('dmy') . ' WHERE uid = ' . $report['uid'];
    $db->exec($query);

    if ($report['fuel_balance'] < 0) {
        $report['fuel_balance'] = 0;
    }
    $columns = 'uid,harsh_break,sudden_acc,towing,avgspeed,genset,overspeed,totaldistance,fenceconflict,idletime,runningtime,vehicleid,dev_lat,dev_long,first_dev_lat,first_dev_long,fuel_balance,topspeed';
    $values = $report['uid'] . ',' . $report['harsh_break'] . ',' . $report['sudden_acc'] . ',' . $report['towing'] . ',' . $report['averagespeed'] . ',' . $report['gensetusage'] . ',' . $report['overspeed'] . ',' . $report['totaldistance'] . ',' . $report['fenceconflict'] . ',' . $report['idletime'] . ',' . $report['runningtime'] . ',' . $report['vehicleid'] . ',' . $report['lat'] . ',' . $report['long'] . ',' . $report['first_lat'] . ',' . $report['first_long'] . ',' . $report['fuel_balance'] . ',' . $report['topspeed'];
    $INSERT = "INSERT INTO A" . $tablename->format('dmy') . "($columns) VALUES ($values);";
    $db->exec($INSERT);
}

function TRANSACTIONG_NEW($report, $db) {
    $tablename = new DateTime($report['date']);
    $table = "CREATE TABLE IF NOT EXISTS A" . $tablename->format('dmy') . " (
        uid INTEGER,
        harsh_break INTEGER,
        sudden_acc INTEGER,
        towing INTEGER,
        avgspeed INTEGER,
        genset INTEGER,
        overspeed INTEGER,
        totaldistance INTEGER,
        fenceconflict INTEGER,
        idletime INTEGER,
        runningtime INTEGER,
        dailyreportid INTEGER,
        vehicleid INTEGER,
        dev_lat FLOAT,
        dev_long FLOAT,
        first_dev_lat FLOAT,
        first_dev_long FLOAT,
        fuel_balance FLOAT DEFAULT 0,
        sms_count INTEGER DEFAULT 0,
        email_count INTEGER DEFAULT 0,
        average_distance float,
        topspeed integer,
        topspeed_lat float,
        topspeed_long float,
        offline_data_time integer,
        topspeed_datetime TEXT,
        night_distance FLOAT,
        weekend_distance FLOAT,
        is_night_drive INTEGER,
        is_weekend_drive INTEGER,
        idleignitiontime INTEGER,
        first_odometer INTEGER,
        last_odometer INTEGER,
        trip_count INTEGER,
        night_first_odometer INTEGER ,
        night_last_odometer INTEGER ,
        night_first_lat FLOAT ,
        night_first_long FLOAT,
        night_end_lat FLOAT,
        night_end_long FLOAT,
        driverid INTEGER DEFAULT 0,
        startlocation TEXT,
        endlocation TEXT,
        freezeIgnitionOnTime INTEGER,
        PRIMARY KEY (dailyreportid));";
    $db->exec($table);

    $query = 'DELETE FROM A' . $tablename->format('dmy') . ' WHERE vehicleid = ' . $report['vehicleid'];
    $db->exec($query);

    if ($report['fuel_balance'] < 0) {
        $report['fuel_balance'] = 0;
    }
    $columns = 'uid,harsh_break,sudden_acc,towing,avgspeed,genset,overspeed,totaldistance,fenceconflict,idletime,runningtime,vehicleid,dev_lat,dev_long,first_dev_lat,first_dev_long,fuel_balance,sms_count, email_count, average_distance, topspeed, topspeed_lat, topspeed_long, offline_data_time, topspeed_datetime,idleignitiontime, first_odometer, last_odometer, trip_count, driverid,freezeIgnitionOnTime';
    $values = $report['uid'] . ',' . $report['harsh_break'] . ',' . $report['sudden_acc'] . ',' . $report['towing'] . ',' . $report['averagespeed'] . ',' . $report['gensetusage'] . ',' . $report['overspeed'] . ',' . $report['totaldistance'] . ',' . $report['fenceconflict'] . ',' . $report['idletime'] . ',' . $report['runningtime'] . ',' . $report['vehicleid'] . ',' . $report['lat'] . ',' . $report['long'] . ',' . $report['first_lat'] . ',' . $report['first_long'] . ',' . $report['fuel_balance'] . ',' . $report['sms_count'] . ',' . $report['email_count'];
    $values .= ",'" . $report['average_distance'] . "'," . $report['topspeed'] . ",'" . $report['topspeed_lat'] . "','" . $report['topspeed_long'] . "'," . $report['offline_data_time'] . ",'" . $report['topspeed_time'] . "','" . $report['idleignitiontime'] . "','" . $report['first_odometer'] . "','" . $report['last_odometer'] . "','" . $report['trip_count'] . "','" . $report['driverid']. "','" . $report['freezeIgnitionOnTime']."'";
    $INSERT = "INSERT INTO A" . $tablename->format('dmy') . "($columns) VALUES ($values);";
    $db->exec($INSERT);
}

function TRANSACTIONG_UPDATE_NIGHT($report, $db) {
    $tablename = new DateTime($report['date']);
    $query = 'UPDATE A' . $tablename->format('dmy') . ' SET  night_distance = ' . $report['night_totaldistance'] . ', weekend_distance = ' . $report['weekend_totaldistance'] . ', is_night_drive = ' . $report['is_night_drive'] . ', is_weekend_drive = ' . $report['is_weekend_drive'] . ' WHERE vehicleid = ' . $report['vehicleid'];
    //die();
    $db->exec($query);
}

function DataFromSqlite($location) {
    $PATH = "sqlite:$location";
    $Query = 'select * from devicehistory';
    $Query .= ' INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated';
    $Query .= ' INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = unithistory.lastupdated group by devicehistory.lastupdated';
    $DRMS = array();
    $lastig;
    try {
        $db = new PDO($PATH);
        $result = $db->query($Query);
        foreach ($result as $row) {
            $DRM = new VODRM();

            if (!isset($lastig) || $lastig != $row['ignition']) {
                $DRM->condition = 1;
            } else {
                $DRM->condition = 0;
            }
            //Unit Part
            $DRM->uhid = $row['uhid'];
            $DRM->uid = $row['uid'];
            $DRM->unitno = $row['unitno'];
            $DRM->customerno = $row['customerno'];
            $DRM->vehicleid = $row['vehicleid'];
            $DRM->analog1 = $row['analog1'];
            $DRM->analog2 = $row['analog2'];
            $DRM->analog3 = $row['analog3'];
            $DRM->analog4 = $row['analog4'];
            $DRM->digitalio = $row['digitalio'];
            $DRM->lastupdated = $row['lastupdated'];
//            $DRM->dhid = $row['dhid'];
            //            $DRM->vhid = $row['vhid'];
            //VehiclePart
            $DRM->vehiclehistoryid = $row['vehiclehistoryid'];
            $DRM->vehicleid = $row['vehicleid'];
            $DRM->vehicleno = $row['vehicleno'];
//            $DRM->devicekey = $row['devicekey'];
            $DRM->extbatt = $row['extbatt'];
            $DRM->odometer = $row['odometer'];
            $DRM->curspeed = $row['curspeed'];
            $DRM->customerno = $row['customerno'];
            $DRM->driverid = $row['driverid'];

            //DevicePart
            $DRM->devicehistoryid = $row['id'];
            $DRM->deviceid = $row['deviceid'];
            $DRM->customerno = $row['customerno'];
            $DRM->devicelat = $row['devicelat'];
            $DRM->devicelong = $row['devicelong'];
//            $DRM->devicekey = $row['devicekey'];
            $DRM->altitude = $row['altitude'];
            $DRM->directionchange = $row['directionchange'];
            $DRM->inbatt = $row['inbatt'];
            $DRM->hwv = $row['hwv'];
            $DRM->swv = $row['swv'];
            $DRM->msgid = $row['msgid'];
            $DRM->uid = $row['uid'];
            $DRM->status = $row['status'];
            $DRM->ignition = $row['ignition'];
            $DRM->powercut = $row['powercut'];
            $DRM->tamper = $row['tamper'];
            $DRM->gpsfixed = $row['gpsfixed'];
            $DRM->online_offline = $row['online/offline'];
            $DRM->gsmstrength = $row['gsmstrength'];
            $DRM->gsmregister = $row['gsmregister'];
            $DRM->gprsregister = $row['gprsregister'];
            $lastig = $row['ignition'];
            $DRMS[] = $DRM;
        }
    } catch (PDOException $e) {
        $DRMS = 0;
    }
    return $DRMS;
}

function CorrectOdometer($location) {
 $PATH = "sqlite:$location";
 $Query = "select * from vehiclehistory WHERE lastupdated BETWEEN '2016-12-17 19:00:00' AND '2016-12-17 21:00:00' order by lastupdated ASC";
 $DRMS = array();
 $lastig;
 try {
  $db = new PDO($PATH);
  $result = $db->query($Query);
  foreach ($result as $row) {
   $DRM = new VODRM();
   $DRM->vehiclehistoryid = $row['vehiclehistoryid'];
   $DRM->odometer = $row['odometer'];
   $DRMS[] = $DRM;
  }
 } catch (PDOException $e) {
  $DRMS = 0;
 }
 return $DRMS;
}

function Odometer_Correction_Calculation($Data, $location) {
 $PATH = "sqlite:$location";
 $prev_odometer = 0;
 $db_reply = "No Effect";
    foreach($Data as $thisdata){
        // Calculation
        if($thisdata->odometer < '221689' || $thisdata->odometer > '230689')
        {
            $prev_odometer = $thisdata->odometer;
        }
        if($thisdata->odometer >= '221689' && $thisdata->odometer < '230689')
        {
            $odometer = $prev_odometer + $thisdata->odometer - 221689;
            $Query = "UPDATE vehiclehistory SET odometer = $odometer WHERE vehiclehistoryid = $thisdata->vehiclehistoryid";
            try {
             $db = new PDO($PATH);
             $result = $db->query($Query);
            } catch (PDOException $e) {
             $db_reply = 'Failure';

            }
            $db_reply = 'Success';
        }

    }
    return $db_reply;
}

function Odometer_Correction_Calculation2($Data, $location) {
 $PATH = "sqlite:$location";
 $prev_odometer_77 = 0;
 $db_reply = "No Effect";
    foreach($Data as $thisdata){
        // Calculation
        if($thisdata->odometer < '77164' || $thisdata->odometer > '90000')
        {
            $prev_odometer_77 = $thisdata->odometer;
        }
        if($thisdata->odometer >= '77164' && $thisdata->odometer < '90000')
        {
            $odometer2 = $prev_odometer_77 + $thisdata->odometer - 77164;
            $Query = "UPDATE vehiclehistory SET odometer = $odometer2 WHERE vehiclehistoryid = $thisdata->vehiclehistoryid";
            try {
             $db = new PDO($PATH);
             $result = $db->query($Query);
            } catch (PDOException $e) {
             $db_reply = 'Failure';

            }
            $db_reply = 'Success';
        }


    }
    return $db_reply;
}

function DataFromDailyReport($location, $date) {
    $PATH = "sqlite:$location";
    $Query = "select * from A" . $date;
    $DRMS = array();
    try {
        $db = new PDO($PATH);
        $result = $db->query($Query);
        foreach ($result as $row) {
            $DRM = new VODRM();
            $DRM->totaldistance = $row['totaldistance'];
            $DRM->vehicleid = $row['vehicleid'];
            $DRM->topspeed = $row['topspeed'];
            $DRM->topspeed_lat = $row['topspeed_lat'];
            $DRM->topspeed_long = $row['topspeed_long'];
            $DRM->topspeed_datetime = $row['topspeed_datetime'];
            $DRM->overspeed = $row['overspeed'];

            $DRMS[] = $DRM;
        }
    } catch (PDOException $e) {
        $DRMS = 0;
    }
    return $DRMS;
}

function DailyReport($device, $date, $info, $overspeed_limit, $acinvertval, $acsensor) {
    $harsh_break = "S";
    $sudden_acc = "U";
    $towing = "V";
    $sharp_turn = "W";

    //Device Info
    $record['vehicleid'] = $device->vehicleid;
    $record['uid'] = $device->uid;
    $record['customerno'] = $device->customerno;
    $total_harsh_break = 0;
    $total_sud_acc = 0;
    $total_towing = 0;
    $total_sharp_turn = 0;

    //Idle & Running Time Calculation
    $dat = '';
    $dat = array();
    foreach ($info as $inf) {
        if ($inf->condition == 1) {
            $dat[] = $inf;
        }
    }
    $enddat = end($info);
    $data = processtraveldata($dat, $enddat);
    $display = displaytraveldata($data);
    //$utilizationtime = utilization($data);
    $record['runningtime'] = $display[0];
    $record['idletime'] = $display[1];

    //Odometer Calculation
    $lastrow = end($info);
    $firstrow = $info[0];

    $lastodometer = $lastrow->odometer;
    $firstodometer = $firstrow->odometer;

    $last_lat = $lastrow->devicelat;
    $last_long = $lastrow->devicelong;

    $first_lat = $firstrow->devicelat;
    $first_long = $firstrow->devicelong;

    $record['totaldistance'] = $lastodometer - $firstodometer;
    if ($record['totaldistance'] < 0) {
        $lastodometermax = GetOdometerMax($date, $device->unitno, $device->customerno);
        $lastodometer = $lastodometermax + $lastodometer;
        $record['totaldistance'] = $lastodometer - $firstodometer;
    }
    $record['totaldistanceKM'] = $lastodometer / 1000 - $firstodometer / 1000;
    if (isset($record['totaldistanceKM']) && $record['totaldistanceKM'] > 0) {
        if (isset($record['runningtime']) && $record['runningtime'] != 0) {
            $AverageSpeed = (int) ($record['totaldistanceKM'] / ($record['runningtime'] / 60));
        } else {
            $AverageSpeed = 0;
        }
    } else {
        $AverageSpeed = 0;
    }
    $record['averagespeed'] = $AverageSpeed;
    $acdat = '';
    $acdat = array();
    foreach ($info as $inf) {
        if ($inf->status != 'F') {
            $acdat[] = $inf;
        }

        if ($inf->status == $harsh_break) {
            $total_harsh_break += 1;
        } elseif ($inf->status == $sudden_acc) {
            $total_sud_acc += 1;
        } elseif ($inf->status == $towing) {
            $total_towing += 1;
        } elseif ($inf->status == $sharp_turn) {
            $total_sharp_turn += 1;
        }
    }
    if ($acsensor == '1') {
        $record['gensetusage'] = gensetusage($acdat, $acinvertval);
    } else {
        $record['gensetusage'] = 0;
    }

    //Tampering PowerCut Overspeed FenceConflict
    $times = monitoring($device->vehicleid, $device->customerno, $info, $overspeed_limit);
    $record['overspeed'] = $times[0];
    $record['tamper'] = $times[1];
    $record['fenceconflict'] = $times[2];
    $record['powercut'] = $times[3];
    $record['date'] = $date;
    $record['lat'] = $last_lat;
    $record['long'] = $last_long;

    $record['first_lat'] = $first_lat;
    $record['first_long'] = $first_long;
    $record['fuel_balance'] = $device->fuel_balance;
    $record['harsh_break'] = $total_harsh_break;
    $record['sudden_acc'] = $total_sud_acc;
    $record['sharp_turn'] = $total_sharp_turn;
    $record['towing'] = $total_towing;
    /*First Odometer*/
    $record['firstodometer'] = $firstodometer;
    /*Get TopSpeed Details */
    $topSpeedDetails = getTopSpeed($date, $info[0]->unitno, $info[0]->customerno);
    $record['topspeed'] = $topSpeedDetails['speed'];
    $record['topspeed_lat'] = $topSpeedDetails['topspeed_lat'];
    $record['topspeed_long'] = $topSpeedDetails['topspeed_long'];
    $record['topspeed_time'] = $topSpeedDetails['topspeed_time'];
    return $record;
}

function GetOdometerMax($date, $unitno, $customerno) {
    $date = substr($date, 0, 11);

    $location = "../../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
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

function getTopSpeed($date, $unitno, $customerno) {
    $date = substr($date, 0, 11);

    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";

    $topspeedDetails = array();
    if (file_exists($location)) {

        $path = "sqlite:$location";
        $dbpath = new PDO($path);
        $query = "SELECT max(curspeed) as speed, vehiclehistory.lastupdated, d.devicelat, d.devicelong
            from vehiclehistory
            INNER JOIN devicehistory as d on d.lastupdated = vehiclehistory.lastupdated";
        $result = $dbpath->query($query);
        foreach ($result as $row) {
            $topspeedDetails['speed'] = $row['speed'];
            $topspeedDetails['topspeed_lat'] = $row['devicelat'];
            $topspeedDetails['topspeed_long'] = $row['devicelong'];
            $topspeedDetails['topspeed_time'] = $row['lastupdated'];
        }
    }
    return $topspeedDetails;
}

function processtraveldata($devicedata, $lastrow) {
//    print_r($lastrow);
    $count = count($devicedata);
    $devices2 = $devicedata;
    $data = Array();
    $datalen = count($devices2);
    if (isset($devices2) && count($devices2) > 1) {
        foreach ($devices2 as $device) {
            $datacap = new VODatacap();
            $laststatus = $device->ignition;
            $datacap->ignition = $device->ignition;

            $ArrayLength = count($data);

            if ($ArrayLength == 0) {
                //echo $firstidle = '1st starttime'.$device->lastupdated.'_'.$device->id.'<br>';
                $datacap->starttime = $device->lastupdated;
                $datacap->startlat = $device->devicelat;
                $datacap->startlong = $device->devicelong;
                $datacap->startodo = $device->odometer;
            } elseif ($ArrayLength == 1) {
                //Filling Up First Array --- Array[0]
                //echo $firstidle = '1st starttime'.$device->lastupdated.'_'.$device->id.'<br>';
                $data[0]->endlat = $device->devicelat;
                $data[0]->endlong = $device->devicelong;
                $data[0]->endtime = $device->lastupdated;
                $data[0]->endodo = $device->odometer;
                $data[0]->distancetravelled = $data[0]->endodo / 1000 - $data[0]->startodo / 1000;
                $data[0]->duration = getduration($data[0]->endtime, $data[0]->starttime);

                $datacap->starttime = $data[0]->endtime;
                $datacap->startlat = $data[0]->endlat;
                $datacap->startlong = $data[0]->endlong;
                $datacap->startodo = $data[0]->endodo;
                $datacap->endtime = $lastrow->lastupdated;
                $datacap->endlat = $lastrow->devicelat;
                $datacap->endlong = $lastrow->devicelong;
                $datacap->endodo = $lastrow->odometer;
            } else {
                $last = $ArrayLength - 1;
                $data[$last]->endtime = $device->lastupdated;
                $data[$last]->endlat = $device->devicelat;
                $data[$last]->endlong = $device->devicelong;
                $data[$last]->endodo = $device->odometer;

                $data[$last]->duration = getduration($data[$last]->endtime, $data[$last]->starttime);

                $data[$last]->distancetravelled = $data[$last]->endodo / 1000 - $data[$last]->startodo / 1000;

                $datacap->starttime = $data[$last]->endtime;
                $datacap->startlat = $data[$last]->endlat;
                $datacap->startlong = $data[$last]->endlong;
                $datacap->startodo = $data[$last]->endodo;

                if ($datalen - 1 == $ArrayLength) {
                    $datacap->endtime = $lastrow->lastupdated;
                    $datacap->endlat = $lastrow->devicelat;
                    $datacap->endlong = $lastrow->devicelong;
                    $datacap->endodo = $lastrow->odometer;
                    $datacap->duration = getduration($datacap->endtime, $datacap->starttime);
                    $datacap->distancetravelled = $datacap->endodo / 1000 - $datacap->startodo / 1000;
                    $datacap->ignition = $device->ignition;
                }
            }
            $data[] = $datacap;
        }
        if ($data != NULL && count($data) > 0) {
            $optdata = optimizereptime($data);
        }
        return $optdata;
    } elseif (isset($devices2) && count($devices2) == 1) {
        $datacap = new VODatacap();
        $datacap->starttime = $devices2[0]->lastupdated;
        $datacap->startlat = $devices2[0]->devicelat;
        $datacap->startlong = $devices2[0]->devicelong;
        $datacap->startodo = $devices2[0]->odometer;
        $datacap->endtime = $lastrow->lastupdated;
        $datacap->endlat = $lastrow->devicelat;
        $datacap->endlong = $lastrow->devicelong;
        $datacap->endodo = $lastrow->odometer;
        $datacap->duration = getduration($datacap->endtime, $datacap->starttime);
        $datacap->distancetravelled = $datacap->endodo / 1000 - $datacap->startodo / 1000;
        $datacap->ignition = $devices2[0]->ignition;
        $data[] = $datacap;

        return $data;
    }
}

function optimizereptime($data) {
    $datarows = array();
    $ArrayLength = count($data);
    $currentrow = $data[0];
    $a = 0;
    while ($currentrow != $data[$ArrayLength - 1]) {
        $i = 1;
        while (($i + $a) < $ArrayLength - 1 && $data[$i + $a]->duration < 3) {
            $i += 2;
        }
        $currentrow->endtime = $data[$i + $a - 1]->endtime;
        $currentrow->endlat = $data[$i + $a - 1]->endlat;
        $currentrow->endlong = $data[$i + $a - 1]->endlong;
        $currentrow->endodo = $data[$i + $a - 1]->endodo;
        $currentrow->duration = getduration($currentrow->endtime, $currentrow->starttime);
        $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;
        $datarows[] = $currentrow;
        if (($a + $i) <= $ArrayLength - 1) {
            $currentrow = $data[$i + $a];
        }
        $a += $i;
        if (($a) >= $ArrayLength - 1) {
            $currentrow = $data[$ArrayLength - 1];
        }
    }
    if ($datarows != NULL) {
        $checkop = end($datarows);
        $checkup = end($data);
        if ($checkop->endtime != $checkup->endtime) {
            $currentrow->starttime = $checkop->endtime;
            $currentrow->startlat = $checkop->endlat;
            $currentrow->startlong = $checkop->endlong;
            $currentrow->startodo = $checkop->endodo;

            $currentrow->endtime = $checkup->endtime;
            $currentrow->endlat = $checkup->endlat;
            $currentrow->endlong = $checkup->endlong;
            $currentrow->endodo = $checkup->endodo;
            $currentrow->duration = getduration($currentrow->endtime, $currentrow->starttime);
            $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;

            $datarows[] = $currentrow;
        }
    } else {
        $currentrow = end($data);
        $currentrow->endlat = $currentrow->startlat;
        $currentrow->endlong = $currentrow->startlong;
        $currentrow->endtime = date('Y-m-d', strtotime($currentrow->starttime));
        $currentrow->endtime .= " 23:59:59";
        $currentrow->endodo = $currentrow->startodo;

        $currentrow->duration = getduration($currentrow->endtime, $currentrow->starttime);
        $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;
        $datarows[] = $currentrow;
    }
    return $datarows;
}

function displaytraveldata($datarows) {
    $runningtime = 0;
    $idletime = 0;
    if (isset($datarows)) {
        foreach ($datarows as $change) {

            //Removing Date Details From DateTime
            $change->starttime = substr($change->starttime, 11);
            $change->endtime = substr($change->endtime, 11);

            $hour = floor(($change->duration) / 60);
            $minutes = ($change->duration) % 60;
            if ($change->ignition == 1) {
                $runningtime += $minutes + ($hour * 60);
            } else {
                $idletime += $minutes + ($hour * 60);
            }
        }
    }

    $utilizationtime[0] = $runningtime;
    $utilizationtime[1] = $idletime;
    return $utilizationtime;
}

function gensetusage($info, $acinvertval) {
    $days = array();
    $data = getacdata($info);
    if ($data != NULL && count($data) > 1) {
        $report = createrep($data);
        if ($report != NULL) {
            $days = array_merge($days, $report);
        }
    }
    if ($days != NULL && count($days) > 0) {
        $finalreport = getacusagereport($days, $acinvertval);
    }
    return $finalreport;
}

function getacdata($info) {
    $count = count($info);
    $devices = array();
    if ($count > 1) {
        $DRM2 = new VODRM();
        $DRM2->ignition = $info[$count - 1]->ignition;
        $DRM2->status = $info[$count - 1]->status;
        $DRM2->lastupdated = $info[$count - 1]->lastupdated;
        $DRM2->digitalio = $info[$count - 1]->digitalio;
        $devices2 = $DRM2;
        unset($info[$count - 1]);

        foreach ($info as $data) {
            if (@$laststatus['digitalio'] != $data->digitalio) {
                $DRM = new VODRM();
                $DRM->ignition = $data->ignition;
                $DRM->status = $data->status;
                $DRM->lastupdated = $data->lastupdated;
                $DRM->digitalio = $data->digitalio;
                $laststatus['ig'] = $data->ignition;
                $laststatus['digitalio'] = $data->digitalio;
                $devices[] = $DRM;
            }
        }
        $devices[] = $devices2;
        return $devices;
    }
}

function monitoring($vehicleid, $custno, $deviceinfo, $overspeed_limit) {
    //Statuses
    $tamper = 0;
    $powercut = 1;
    $overspeed = 0;

    //Counters
    $tampercount = 0;
    $fenceconflictcount = 0;
    $overspeedcount = 0;
    $powercutcount = 0;

    //GeoFencing
    $pointLocation = new PointLocation($custno);
    $gm = new GeofenceManager($custno);
    $fences = $gm->getfencesforvehicle($vehicleid);

    $polygons = array();
    if (isset($fences) && !empty($fences)) {
        foreach ($fences as $fence) {
            $geofence = $gm->get_geofence_from_fenceid($fence->fenceid);

            //Making Polygon For GeoFencing
            $polygons[$fence->fenceid]['id'] = $fence->fenceid;
            $polygons[$fence->fenceid]['conflictstatus'] = 0;
            $polygons[$fence->fenceid]['conflictcount'] = 0;
            if(isset($geofence) && !empty($geofence)) {
                foreach ($geofence as $thisgeofence) {
                $polygons[$fence->fenceid][] = $thisgeofence->geolat . " " . $thisgeofence->geolong;
            }
            }

        }
    }

    foreach ($deviceinfo as $device) {
        if ($device->tamper == 1 && $tamper == 0) {
            $tampercount += 1;
            $tamper = 1;
        } elseif ($device->tamper == 0 && $tamper == 1) {
            $tamper = 0;
        }
        if ($device->powercut == 0 && $powercut == 0) {
            $powercutcount += 1;
            $powercut = 1;
        } elseif ($device->powercut == 1 && $powercut == 1) {
            $powercut = 0;
        }
        if ($device->curspeed > $overspeed_limit && $overspeed == 0) {
            $overspeedcount += 1;
            $overspeed = 1;
        } elseif ($device->curspeed < $overspeed_limit && $overspeed == 1) {
            $overspeed = 0;
        }
        $points = array($device->devicelat . " " . $device->devicelong);
        foreach ($points as $point) {
            if (isset($polygons)) {
                foreach ($polygons as $polygon) {
                    $a = $polygon;
                    $fenceid = $polygon['id'];

                    unset($a['id']);
                    unset($a['conflictstatus']);
                    unset($a['conflictcount']);

                    if ($pointLocation->checkPointStatus($point, $a) == "outside" && $polygons[$fenceid]['conflictstatus'] == 0) {
                        $polygons[$fenceid]['conflictcount'] += 1;
                        $polygons[$fenceid]['conflictstatus'] = 1;
                    } elseif ($pointLocation->checkPointStatus($point, $a) == "inside" && $polygons[$fenceid]['conflictstatus'] == 1) {
                        $polygons[$fenceid]['conflictstatus'] = 0;
                    }
                }
            }
        }
    }

    $counters[0] = $overspeedcount;
    $counters[1] = $tampercount;
    $counters[2] = $fenceconflictcount;
    $counters[3] = $powercutcount;

    return $counters;
}

function utilization($ignitionchange) {
    //Calculation Variables
    //Idle
    $off = 0;
    $idletime = 0;
    $firstidletime = 0;
    $lastidletime = 0;

    //Running
    $on = 0;
    $runningtime = 0;
    $firstrunningtime = 0;
    $lastrunningtime = 0;

    $counter = 0;
    $ArrayLength = count($ignitionchange);

    if (isset($ignitionchange)) {
        foreach ($ignitionchange as $change) {
            //IdleTime Calculation
            if ($change->ignition == '0' && $off == 0) {
                $firstidletime = strtotime($change->lastupdated);
                $off = 1;
            } elseif ($change->ignition == '1' && $off == 1) {
                $lastidletime = strtotime($change->lastupdated);
                $off = 0;
                $idleduration = $lastidletime - $firstidletime;
                $idletime += round($idleduration / 60);
            }

            //Running Time Calculation
            if ($change->ignition == '1' && $on == 0) {
                $firstrunningtime = strtotime($change->lastupdated);
                $on = 1;
            } elseif ($change->ignition == '0' && $on == 1) {
                $lastrunningtime = strtotime($change->lastupdated);
                $on = 0;

                $runduration = $lastrunningtime - $firstrunningtime;
                $runningtime += round($runduration / 60);
            }

            // For The Last Row Exception
            if ($counter == ($ArrayLength - 1)) {
                if ($off == 1) {
                    $lastidletime = strtotime($change->lastupdated);
                    $idleduration = $lastidletime - $firstidletime;
                    $idletime += round($idleduration / 60);
                } elseif ($on == 1) {
                    $lastrunningtime = strtotime($change->lastupdated);
                    $runduration = $lastrunningtime - $firstrunningtime;
                    $runningtime += round($runduration / 60);
                }
            }
            $counter += 1;
        }
    }

    $utilizationtime[0] = $runningtime;
    $utilizationtime[1] = $idletime;
    return $utilizationtime;
}

function createrep($data) {
    $currentrow = new VODatacap();
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

            $currentrow = new VODatacap();
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

function getacusagereport($datarows, $acinvert) {
    $runningtime = 0;
    $idletime = 0;
    $lastdate = NULL;
    $display = '';
    if (isset($datarows)) {
        foreach ($datarows as $change) {
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
        }
    }
    if ($acinvert == 1) {
        $display .= $idletime;
    } else {
        $display .= $runningtime;
    }
    return $display;
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

function odoemeterDataFromSqlite($location) {
    $odometer = 0;
    $PATH = "sqlite:$location";

    $Query1 = "DELETE FROM vehiclehistory where lastupdated between '2016-12-17 19:07:00' AND '2016-12-17 19:50:00'";
    $Query2 = "DELETE FROM unithistory where lastupdated between '2016-12-17 19:07:00' AND '2016-12-17 19:50:00'";
    $Query3 = "DELETE FROM devicehistory where lastupdated between '2016-12-17 19:07:00' AND '2016-12-17 19:50:00'";
     //$db = new PDO($PATH);
        //$result = $db->query($Query);
    $db = new PDO($PATH);
    $result = $db->query($Query1);
    $result = $db->query($Query2);
    $result = $db->query($Query3);
    echo $location . "<br/>";

}

function TRANSACTION_UPDATE_START_END_LOCATION($report, $db, $dbLocation) {
    $tableDate = new DateTime($report['date']);
    $tableName = 'A' . $tableDate->format('dmy');
    try {
        if (IsColumnExistInSqlite($dbLocation, $tableName, 'startlocation')) {
            $query = '  UPDATE  ' . $tableName . '
                        SET     startlocation = "' . $report['start_location'] . '"
                                ,endlocation = "' . $report['end_location'] . '"
                        WHERE   vehicleid = ' . $report['vehicleid'];
            $db->exec($query);
        } else {
            $query1 = ' ALTER TABLE ' . $tableName . '
                        ADD COLUMN `startlocation` TEXT';
            $db->exec($query1);

            $query2 = 'ALTER TABLE ' . $tableName . '
                        ADD COLUMN `endlocation` TEXT';
            $db->exec($query2);

            $query3 = ' UPDATE  ' . $tableName . '
                        SET     startlocation = "' . $report['start_location'] . '"
                                ,endlocation = "' . $report['end_location'] . '"
                        WHERE   vehicleid = ' . $report['vehicleid'];

            $db->exec($query3);
        }
    } catch (PDOException $e) {
        return NULL;
    }
}

//<editor-fold defaultstate="collapsed" desc="Annexure Function">

function insertAnnexureStatus($report, $db) {
    $tablename = new DateTime($report->reportDate);
    $table = "CREATE TABLE IF NOT EXISTS A" . $tablename->format('dmy') . "
    (uid INTEGER, vehicleid INTEGER, customerno INTEGER, isActive INTEGER, isTemperature INTEGER, isHumidity INTEGER, isDigital INTEGER,
    PRIMARY KEY (uid));";
    $db->exec($table);

    $query = 'DELETE FROM A' . $tablename->format('dmy') . ' WHERE vehicleid = ' . $report->vehicleId;
    $db->exec($query);


    $columns = 'uid, vehicleid, customerno, isActive, isTemperature, isHumidity, isDigital';
    $values = $report->unitId .",". $report->vehicleId .",". $report->customerNo .",". $report->isActive .",". $report->isTemperature ." , ". $report->isHumidity .", ". $report->isDigital;

    $INSERT = "INSERT INTO A" . $tablename->format('dmy') . "($columns) VALUES ($values);";
    $db->exec($INSERT);
}

//</editor-fold>



?>
