<?php

require_once "database.inc.php";

class VODevices {
}

class VODatacap1 {
}

class TempConversion {

    public /* int */$rawtemp;
    public /* boolean */$unit_type = 0;
    public /* boolean */$use_humidity = 0;
    public /* boolean */$switch_to = 0;
}

class api {

    //<editor-fold defaultstate="collapsed" desc="Constructor">
    function __construct() {
        $this->db = new database(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, SPEEDDB);
    }

    // </editor-fold>
    //
    //<editor-fold defaultstate="collapsed" desc="API functions">
    function getlatestvehicledata($vehicleno, $customerno) {
        $status = "";
        $getvehicledata = $this->getvehicledata($vehicleno);
        $location = isset($getvehicledata['location']) ? $getvehicledata['location'] : '';
        $datetime = isset($getvehicledata['datetime']) ? $getvehicledata['datetime'] : '';
        $temp1 = isset($getvehicledata['temp1']) ? $getvehicledata['temp1'] : 0;
        /*
        $temp2 = isset($getvehicledata['temp2']) ? $getvehicledata['temp2'] : 0;
        $temp3 = isset($getvehicledata['temp3']) ? $getvehicledata['temp3'] : 0;
        $temp4 = isset($getvehicledata['temp4']) ? $getvehicledata['temp4'] : 0;
         */
        $digitalio = isset($getvehicledata['digitalio']) ? $getvehicledata['digitalio'] : 0;
        $lat = isset($getvehicledata['lat']) ? $getvehicledata['lat'] : 0;
        $long = isset($getvehicledata['long']) ? $getvehicledata['long'] : 0;
        $distance = isset($getvehicledata['distance']) ? $getvehicledata['distance'] : 0;

        if ($digitalio == 1) {
            $status = "ON";
        } else {
            $status = "OFF";
        }
        /* Need to keep IgnitionStatus as is and pass genset status in this element
        as the element name is fixed for EFleetServices
         */
        $data = array(
            'VehicleNo' => $vehicleno,
            'Location' => $location,
            'Datetime' => $datetime,
            'Temperature' => $temp1,
            /*
            'Temperature2' => $temp2,
            'Temperature3' => $temp3,
            'Temperature4' => $temp4,
             */
            'IgnitionStatus' => $status,
            'Lat' => $lat,
            'Long' => $long,
            'Distance' => $distance,
        );
        $innerXML = $this->generate_valid_xml_from_array($data, 'VehicleResponse');
        $data['innerXML'] = $innerXML;
        return $data;
    }

    function gettripdataontripend($vehicleno, $reportloadingdate, $loadingdate, $reportunloadingdate, $unloadingdate, $maxidlespeed) {
        //vehicleno, dateofposting-currentdate , loading KM, loading idle min, transit km , transit idle min, uploading km, unloading idle min, log count during the triplog
        $dateofposting = date("Y/m/d H:i:s");
        $getvehicledata = $this->getvehicledata($vehicleno);
        $unitno = isset($getvehicledata['unitno']) ? $getvehicledata['unitno'] : 0;
        $customerno = isset($getvehicledata['customerno']) ? $getvehicledata['customerno'] : 0;
        $vehicleid = isset($getvehicledata['vehicleid']) ? $getvehicledata['vehicleid'] : 0;

        //loading KM - Report loading date & loading date
        $loadingdata = $this->get_LoadingidleMin($reportloadingdate, $loadingdate, $vehicleid, $customerno, $unitno);
        $loadingkm = isset($loadingdata['distance']) ? $loadingdata['distance'] : 0;
        $loadingidlemin = isset($loadingdata['idlemin']) ? $loadingdata['idlemin'] : 0;

        //transit km and idle min
        $transitdata = $this->get_LoadingidleMin($loadingdate, $reportunloadingdate, $vehicleid, $customerno, $unitno);
        $transitkm = isset($transitdata['distance']) ? $transitdata['distance'] : 0;
        $transitmin = isset($transitdata['idlemin']) ? $transitdata['idlemin'] : 0;

        //unloading kms only
        $unloadingdata = $this->get_LoadingidleMin($reportloadingdate, $unloadingdate, $vehicleid, $customerno, $unitno);
        $unloadingkm = isset($unloadingdata['distance']) ? $unloadingdata['distance'] : 0;
        $unloadingmin = isset($unloadingdata['idlemin']) ? $unloadingdata['idlemin'] : 0;

        //(Total No of Logs Recorded during the period [ReportLoadingDate] And [UnloadingDate])
        $totalCount = 0;
        $data = array(
            'VehicleNo' => $vehicleno,
            'DateTime' => $dateofposting,
            'LoadingKM' => $loadingkm,
            'LoadingIdleMinutes' => $loadingidlemin,
            'TransitKM' => $transitkm,
            'TransitIdleMinutes' => $transitmin,
            'UnloadingKM' => $unloadingkm,
            'UnloadingIdleMinutes' => $unloadingmin,
            'TotalCount' => $totalCount,
        );
        $innerXML = $this->generate_valid_xml_from_array($data, 'VehicleTLDetailResponse');
        $data['innerXML'] = $innerXML;
        return $data;
    }

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Helper functions">

    function get_person_details_by_key($userkey) {
        $SQL = "SELECT customerno,userid,role,roleid FROM user WHERE isdeleted=0 and userkey='" . $userkey . "'";
        $Query = sprintf($SQL);
        $result = $this->db->query($Query, __FILE__, __LINE__);
        if ($this->db->num_rows($result) > 0) {
            while ($row = $this->db->fetch_array($result)) {
                $data = array(
                    'customerno' => $row['customerno'],
                    'userid' => $row['userid'],
                    'role' => $row['role'],
                    'roleid' => $row['roleid'],
                );
                return $data;
            }
        }
        return null;
    }

    function get_LoadingidleMin($reportloadingdate, $loadingdate, $vehicleid, $customerno, $unitno) {
        $data = array();
        $Shour = date("H:i", strtotime($reportloadingdate));
        $Ehour = date("H:i", strtotime($loadingdate));
//        $Shour = date_format($reportloadingdate, "H:i");
        //        $Ehour = date_format($loadingdate, "H:i");

        $totaldays = $this->gendays_cmn($reportloadingdate, $loadingdate);
        $count = count($totaldays);
        $endelement = end($totaldays);
        $firstelement = $totaldays[0];
        $days = Array();
        if (isset($totaldays)) {
            foreach ($totaldays as $userdate) {
                $lastday = $this->new_travel_data1($customerno, $unitno, $userdate, $count, $firstelement, $endelement, $vehicleid, $Shour, $Ehour);
                if ($lastday != NULL) {
                    $days = array_merge($days, $lastday);
                }
            }
        }

        if (isset($days) && count($days) > 0) {
            $data = $this->countloadingidle($days, $vehicleid, $unitno, $reportloadingdate, $loadingdate);
        }
        return $data;
    }

    function optimizerep($data) {
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
            $currentrow->duration = $this->getduration($currentrow->endtime, $currentrow->starttime);
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
                $currentrow->duration = $this->getduration($currentrow->endtime, $currentrow->starttime);
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

            $currentrow->duration = $this->getduration($currentrow->endtime, $currentrow->starttime);
            $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;
            $datarows[] = $currentrow;
        }
        return $datarows;
    }

    function countloadingidle($datarows, $vehicleid, $unitno, $startdate, $enddate) {
        $t = 1;
        $runningtime = 0;
        $idletime = 0;
        $idle_ign_on = 0;
        $startdate = date('Y-m-d H:i:s', strtotime($startdate));
        $enddate = date('Y-m-d H:i:s', strtotime($enddate));
        $totaldistance = 0;
        $lastdate = NULL;
        $totalminute = 0;
        if (isset($datarows)) {
            $z = 0;
            $ak_text = 0;
            foreach ($datarows as $change) {
                $ak_text++;
                $comparedate = date('d-m-Y', strtotime($change->endtime));
                $today = date('d-m-Y', strtotime('Now'));
                $hour = floor(($change->duration) / 60);
                $minutes = ($change->duration) % 60;
                if ($change->ignition == 0) {
                    $idletime += $minutes + ($hour * 60);
                } else {
                    $idletime += 0;
                }

                if ($change->ignition == 1) {
                    $totaldistance += round($change->distancetravelled, 1);
                } else {
                    $totaldistance += 0;
                }
            }
        }
        $data = array(
            'distance' => $totaldistance,
            'idlemin' => $idletime,
        );

        return $data;
    }

    function new_travel_data1($customerno, $unitno, $userdate, $count, $firstelement, $endelement, $vehicleid, $Shour, $Ehour) {
        $location = "../../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
        if (!file_exists($location)) {
            return null;
        }
        if (filesize($location) == 0) {
            return null;
        }
        $location = "sqlite:" . $location;
        if ($count > 1 && $userdate != $endelement && $userdate == $firstelement) {
            $devicedata = $this->getdatafromsqliteTimebased($vehicleid, $location, $userdate, $Shour, Null, $customerno);
        } elseif ($count > 1 && $userdate == $endelement) {
            $devicedata = $this->getdatafromsqliteTimebased($vehicleid, $location, $userdate, Null, $Ehour, $customerno);
        } elseif ($count == 1) {
            $devicedata = $this->getdatafromsqliteTimebased($vehicleid, $location, $userdate, $Shour, $Ehour, $customerno);
        } else {
            $devicedata = $this->getdatafromsqliteTimebased($vehicleid, $location, $userdate, Null, Null, $customerno);
        }

        if ($devicedata != null) {
            $lastday = $this->processtraveldata($devicedata, $unitno, $customerno);
            return $lastday;
        } else {
            return null;
        }
    }

    function processtraveldata($devicedata, $unitno, $customerno) {
        $devices2 = $devicedata[0];
        $lastrow = $devicedata[1];
        $data = Array();
        $datalen = count($devices2);
        if (isset($devices2) && count($devices2) > 1) {
            foreach ($devices2 as $device) {
                $datacap = new VODatacap1();

                $datacap->ignition = $device->ignition;

                $ArrayLength = count($data);

                if ($ArrayLength == 0) {
                    $datacap->starttime = $device->lastupdated;
                    $datacap->startlat = $device->devicelat;
                    $datacap->startlong = $device->devicelong;
                    $datacap->startodo = $device->odometer;
                    $datacap->unitno = $unitno;
                } elseif ($ArrayLength == 1) {
                    //Filling Up First Array --- Array[0]
                    $data[0]->endlat = $device->devicelat;
                    $data[0]->endlong = $device->devicelong;
                    $data[0]->endtime = $device->lastupdated;
                    $data[0]->endodo = $device->odometer;
                    if ($data[0]->endodo < $data[0]->startodo) {
                        $date = date('Y-m-d', strtotime($device->lastupdated));
                        $max = $this->GetOdometerMax($date, $unitno);
                        $data[0]->endodo = $max + $data[0]->endodo;
                    }
                    $data[0]->distancetravelled = $data[0]->endodo / 1000 - $data[0]->startodo / 1000;

                    $data[0]->duration = $this->getduration($data[0]->endtime, $data[0]->starttime);

                    $datacap->starttime = $data[0]->endtime;
                    $datacap->startlat = $data[0]->endlat;
                    $datacap->startlong = $data[0]->endlong;
                    $datacap->startodo = $data[0]->endodo;
                    $datacap->endtime = $lastrow->lastupdated;
                    $datacap->endlat = $lastrow->devicelat;
                    $datacap->endlong = $lastrow->devicelong;
                    $datacap->endodo = $lastrow->odometer;
                    $datacap->unitno = $unitno;
                } else {
                    $last = $ArrayLength - 1;
                    $data[$last]->endtime = $device->lastupdated;
                    $data[$last]->endlat = $device->devicelat;
                    $data[$last]->endlong = $device->devicelong;
                    $data[$last]->endodo = $device->odometer;

                    $data[$last]->duration = $this->getduration($data[$last]->endtime, $data[$last]->starttime);
                    if ($data[$last]->endodo < $data[$last]->startodo) {
                        $date = date('Y-m-d', strtotime($device->lastupdated));
                        $max = GetOdometerMax($date, $unitno);
                        $data[$last]->endodo = $max + $data[$last]->endodo;
                    }
                    $data[$last]->distancetravelled = $data[$last]->endodo / 1000 - $data[$last]->startodo / 1000;

                    $datacap->starttime = $data[$last]->endtime;
                    $datacap->startlat = $data[$last]->endlat;
                    $datacap->startlong = $data[$last]->endlong;
                    $datacap->startodo = $data[$last]->endodo;
                    $datacap->unitno = $unitno;

                    if ($datalen - 1 == $ArrayLength) {
                        $datacap->endtime = $lastrow->lastupdated;
                        $datacap->endlat = $lastrow->devicelat;
                        $datacap->endlong = $lastrow->devicelong;
                        $datacap->endodo = $lastrow->odometer;
                        $datacap->duration = $this->getduration($datacap->endtime, $datacap->starttime);
                        if ($datacap->endodo < $datacap->startodo) {
                            $date = date('Y-m-d', strtotime($lastrow->lastupdated));
                            $max = $this->GetOdometerMax($date, $unitno);
                            $datacap->endodo = $max + $datacap->endodo;
                        }
                        $datacap->distancetravelled = $datacap->endodo / 1000 - $datacap->startodo / 1000;
                        $datacap->ignition = $device->ignition;
                    }
                }
                $data[] = $datacap;
            }
            if ($data != NULL && count($data) > 0) {
                $optdata = $this->optimizerep($data);
            }
            return $optdata;
        } elseif (isset($devices2) && count($devices2) == 1) {
            $datacap = new VODatacap1();
            $datacap->starttime = $devices2[0]->lastupdated;
            $datacap->startlat = $devices2[0]->devicelat;
            $datacap->startlong = $devices2[0]->devicelong;
            $datacap->startodo = $devices2[0]->odometer;
            $datacap->endtime = $lastrow->lastupdated;
            $datacap->endlat = $lastrow->devicelat;
            $datacap->endlong = $lastrow->devicelong;
            $datacap->endodo = $lastrow->odometer;
            $datacap->duration = $this->getduration($datacap->endtime, $datacap->starttime);
            $datacap->distancetravelled = $datacap->endodo / 1000 - $datacap->startodo / 1000;
            $datacap->ignition = $devices2[0]->ignition;
            $datacap->unitno = $unitno;
            $data[] = $datacap;

            return $data;
        } else {
            return NULL;
        }
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

    function GetOdometerMax($date, $unitno, $customerno) {
        $date = substr($date, 0, 11);
        $location = "../../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
        $ODOMETER = 0;
        if (file_exists($location)) {
            $path = "sqlite:$location";
            $db = new PDO($path);
            $query = "SELECT max(odometer) as odometerm from vehiclehistory";
            $exquery = sprintf($query);
            $result = $this->db->query($exquery, __FILE__, __LINE__);
            foreach ($result as $row) {
                $ODOMETER = $row['odometerm'];
            }
        }
        return $ODOMETER;
    }

    function getdatafromsqliteTimebased($vehicleid, $location, $userdate, $Shour, $Ehour, $customerno = '') {
        $customerno = ($customerno != '') ? $customerno : $_SESSION['customerno'];
        $devices2;
        $lastrow;
        if ($Shour == Null && isset($Ehour)) {
            try {
                $database = new PDO($location);
                $query = "SELECT devicehistory.deviceid, devicehistory.devicelat,
                devicehistory.devicelong, devicehistory.ignition, devicehistory.status,
                devicehistory.lastupdated, vehiclehistory.odometer from devicehistory
                INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
                WHERE vehiclehistory.vehicleid=$vehicleid AND devicehistory.lastupdated <= '$userdate $Ehour'
                ORDER BY devicehistory.lastupdated DESC Limit 0,1";
                //$exquery = sprintf($query);
                //$result = $this->db->query($exquery, __FILE__, __LINE__);
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
                WHERE vehiclehistory.vehicleid=$vehicleid AND devicehistory.lastupdated <= '$userdate $Ehour' group by devicehistory.lastupdated
                ORDER BY devicehistory.lastupdated ASC";

                $result = $database->query($query);
//                $exquery = sprintf($query);
                //                $result = $this->db->query($exquery, __FILE__, __LINE__);

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
        } elseif (isset($Shour) && $Ehour == Null) {
            try {
                $database = new PDO($location);
                $query = "SELECT devicehistory.deviceid, devicehistory.devicelat,
                devicehistory.devicelong, devicehistory.ignition, devicehistory.status,
                devicehistory.lastupdated, vehiclehistory.odometer from devicehistory
                INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
                WHERE vehiclehistory.vehicleid=$vehicleid AND devicehistory.lastupdated >= '$userdate $Shour'
                ORDER BY devicehistory.lastupdated DESC Limit 0,1";

                //$result = $database->query($query);
                //                $exquery = sprintf($query);
                //                $result = $this->db->query($exquery, __FILE__, __LINE__);
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
                WHERE vehiclehistory.vehicleid=$vehicleid AND devicehistory.lastupdated >= '$userdate $Shour' group by devicehistory.lastupdated
                ORDER BY devicehistory.lastupdated ASC";

                $result = $database->query($query);
//                $exquery = sprintf($query);
                //                $result = $this->db->query($exquery, __FILE__, __LINE__);
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
        } elseif (isset($Shour) && isset($Ehour)) {
            try {
                $database = new PDO($location);
                $query = "SELECT devicehistory.deviceid, devicehistory.devicelat,
                devicehistory.devicelong, devicehistory.ignition, devicehistory.status,
                devicehistory.lastupdated, vehiclehistory.odometer from devicehistory
                INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
                WHERE vehiclehistory.vehicleid=$vehicleid AND devicehistory.lastupdated >= '$userdate $Shour' AND devicehistory.lastupdated <= '$userdate $Ehour'
                ORDER BY devicehistory.lastupdated DESC Limit 0,1";

                $result = $database->query($query);
//                $exquery = sprintf($query);
                //                $result = $this->db->query($exquery, __FILE__, __LINE__);
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
                WHERE vehiclehistory.vehicleid=$vehicleid AND devicehistory.lastupdated >= '$userdate $Shour' AND devicehistory.lastupdated <= '$userdate $Ehour' group by devicehistory.lastupdated
                ORDER BY devicehistory.lastupdated ASC";

                $result = $database->query($query);
//                $exquery = sprintf($query);
                //                $result = $this->db->query($exquery, __FILE__, __LINE__);
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
        } elseif ($Shour == Null && $Ehour == Null) {
            try {
                $database = new PDO($location);
                $query = "SELECT devicehistory.deviceid, devicehistory.devicelat,
                devicehistory.devicelong, devicehistory.ignition, devicehistory.status,
                devicehistory.lastupdated, vehiclehistory.odometer from devicehistory
                INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
                WHERE vehiclehistory.vehicleid=$vehicleid
                ORDER BY devicehistory.lastupdated DESC Limit 0,1";

                $result = $database->query($query);
//                $exquery = sprintf($query);
                //                $result = $this->db->query($exquery, __FILE__, __LINE__);
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
                WHERE vehiclehistory.vehicleid=$vehicleid AND devicehistory.lastupdated >= '$userdate $Shour' group by devicehistory.lastupdated
                ORDER BY devicehistory.lastupdated ASC";

                $result = $database->query($query);
//                $exquery = sprintf($query);
                //                $result = $this->db->query($exquery, __FILE__, __LINE__);
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
        }
        $sqlitedata[] = $devices2;
        $sqlitedata[] = $lastrow;
        return $sqlitedata;
    }

    function gendays_cmn($STdate, $EDdate) {
        $TOTALDAYS = Array();
        $STdate = date("Y-m-d", strtotime($STdate));
        $EDdate = date("Y-m-d", strtotime($EDdate));
        while (strtotime($STdate) <= strtotime($EDdate)) {
            $TOTALDAYS[] = $STdate;
            $STdate = date("Y-m-d", strtotime($STdate . ' + 1 day'));
        }
        return $TOTALDAYS;
    }

    function getvehicledata($vehicleno) {
        $devices = Array();
        $vehicleno = preg_replace('/\s+/', '', $vehicleno);
        $Query = "SELECT v.vehicleno, v.vehicleno,v.vehicleid,v.customerno"
            . ", d.ignition,d.devicelat,d.devicelong,d.lastupdated"
            . ", u.unitno,u.analog1, u.analog2, u.analog3, u.analog4"
            . ", u.tempsen1, u.tempsen2, u.tempsen3, u.tempsen4"
            . ", u.digitalio, u.get_conversion"
            . ", c.temp_sensors, c.use_humidity "
            . "FROM `vehicle` as v  "
            . "INNER JOIN unit as u on u.uid = v.uid AND u.vehicleid = v.vehicleid "
            . "INNER JOIN devices as d on d.uid = u.uid  "
            . "INNER JOIN customer as c ON c.customerno = v.customerno AND c.isoffline = 0 "
            . "WHERE REPLACE(v.vehicleno, ' ','') = '%s' "
            . "AND v.isdeleted = 0 "
            . "AND v.customerno != 1 "
            . "AND u.customerno != 1 "
            . "AND d.customerno != 1 "
            . "ORDER BY d.lastupdated DESC "
            . "LIMIT 1";
        $vehicleQuery = sprintf($Query, $vehicleno);
        $record = $this->db->query($vehicleQuery, __FILE__, __LINE__);
        $row = $this->db->fetch_array($record);
        //<editor-fold defaultstate="collapsed" desc="Check for temperature">
        if (isset($row['temp_sensors'])) {
            $objTempConversion = new TempConversion();
            $objTempConversion->unit_type = $row['get_conversion'];
            $objTempConversion->use_humidity = $row['use_humidity'];
            $temp1 = '';
            $temp2 = '';
            $temp3 = '';
            $temp4 = '';
            switch ($row['temp_sensors']) {
            case 4:
                if (isset($row['tempsen4'])) {
                    $s = "analog" . $row['tempsen4'];
                    $analogValue = isset($row[$s]) ? $row[$s] : 0;
                    $objTempConversion->rawtemp = $analogValue;
                    if ($row['tempsen4'] != 0 && $analogValue != 0) {
                        $temp4 = $this->gettemplist($objTempConversion);
                    } else {
                        $temp4 = '';
                    }
                }
            case 3:
                if (isset($row['tempsen3'])) {
                    $s = "analog" . $row['tempsen3'];
                    $analogValue = isset($row[$s]) ? $row[$s] : 0;
                    $objTempConversion->rawtemp = $analogValue;
                    if ($row['tempsen3'] != 0 && $analogValue != 0) {
                        $temp3 = $this->gettemplist($objTempConversion);
                    } else {
                        $temp3 = '';
                    }
                }
            case 2:
                if (isset($row['tempsen2'])) {
                    $s = "analog" . $row['tempsen2'];
                    $analogValue = isset($row[$s]) ? $row[$s] : 0;
                    $objTempConversion->rawtemp = $analogValue;
                    if ($row['tempsen2'] != 0 && $analogValue != 0) {
                        $temp2 = $this->gettemplist($objTempConversion);
                    } else {
                        $temp2 = '';
                    }
                }
            case 1:
                if (isset($row['tempsen1'])) {
                    $s = "analog" . $row['tempsen1'];
                    $analogValue = isset($row[$s]) ? $row[$s] : 0;
                    $objTempConversion->rawtemp = $analogValue;
                    if ($row['tempsen1'] != 0 && $analogValue != 0) {
                        $temp1 = $this->gettemplist($objTempConversion);
                    } else {
                        $temp1 = '';
                    }
                }
                break;
            }
        }
        //</editor-fold>
        $lat = $row['devicelat'];
        $long = $row['devicelong'];
        $customerno = $row['customerno'];
        $unitno = $row['unitno'];
        $getlocation = "";
        $getlocation = $this->get_location_bylatlong($lat, $long, $customerno);
        $distance = $this->distance($customerno, $unitno);
        $devices = array(
            'vehicleno' => $row['vehicleno'],
            'location' => $getlocation,
            'datetime' => $row['lastupdated'],
            'temp1' => $temp1,
            'temp2' => $temp2,
            'temp3' => $temp3,
            'temp4' => $temp4,
            'digitalio' => $row['digitalio'],
            'customerno' => $row['customerno'],
            'unitno' => $row["unitno"],
            'lat' => $lat,
            'long' => $long,
            'distance' => $distance,
            'vehicleid' => $row['vehicleid'],
        );
        return $devices;
    }

    function get_location_bylatlong($lat, $long, $customerno) {
        $latint = floor($lat);
        $longint = floor($long);

        $geoloc_query = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( " . $lat . "- `lat` ) * PI( ) /180 /2 ) , 2 ) +
                         COS( " . $lat . " * PI( ) /180 ) * COS( `lat` * PI( ) /180 ) * POWER( SIN( ( " . $long . " - `long` ) * PI( ) /180 /2 ) , 2 ) ) )
                         AS distance FROM geotest WHERE `latfloor` = " . $latint . " AND `longfloor` = " . $longint . " HAVING distance <2 AND customerno = " . $customerno . " ORDER BY distance LIMIT 0,1 ";
        $record = $this->db->query($geoloc_query, __FILE__, __LINE__);
        $record_counts = $this->db->query($geoloc_query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record_counts);
        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                if ($row['distance'] > 1) {
                    $location_string = round($row['distance'], 2) . " Km from " . $row['location'] . ", " . $row['city'] . ", " . $row['state'];
                } else {
                    $location_string = "Near " . $row['location'] . ", " . $row['city'] . ", " . $row['state'];
                }
            }
            return $location_string;
        } else {

            $geolocation_query = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( " . $lat . "- `lat` ) * PI( ) /180 /2 ) , 2 ) +
                             COS( " . $lat . " * PI( ) /180 ) * COS( `lat` * PI( ) /180 ) * POWER( SIN( ( " . $long . " - `long` ) * PI( ) /180 /2 ) , 2 ) ) )
                             AS distance FROM geotest WHERE `latfloor` = " . $latint . " AND `longfloor` = " . $longint . " HAVING distance <10 AND customerno IN(0, " . $customerno . ") ORDER BY distance LIMIT 0,1 ";
            $records = $this->db->query($geolocation_query, __FILE__, __LINE__);
            $record_countss = $this->db->query($geolocation_query, __FILE__, __LINE__);
            $row_counts = $this->db->num_rows($record_countss);
            if ($row_counts > 0) {
                while ($row = $this->db->fetch_array($records)) {
                    if ($row['distance'] > 1) {
                        $location_string = round($row['distance'], 2) . " Km from " . $row['location'] . ", " . $row['city'] . ", " . $row['state'];
                    } else {
                        $location_string = "Near " . $row['location'] . ", " . $row['city'] . ", " . $row['state'];
                    }
                }
                return $location_string;
            } else {
                return "google temporarily down";
            }
            return null;
        }
    }

    //calculate distance
    function distance($customerno, $unitno) {
        $date = date('Y-m-d');
        $location = "../../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
        $Data = $this->odometerfromSqlite($location);
        if ($Data != 0) {
            $lastodometer = $Data['last'];
            $firstodometer = $Data['first'];
            $distance = $lastodometer / 1000 - $firstodometer / 1000;
            $distancekm = round($distance, 2);
        } else {
            $distancekm = 0;
        }
        return $distancekm;

        //$location = "../../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
    }

    function odometerfromSqlite($location) {
        try {
            $DRMS = array();
            $DRMS['first'] = 0;
            $DRMS['last'] = 0;
            if (file_exists($location)) {
                $path = "sqlite:$location";
                $dbs = new PDO($path);
                //$query = "SELECT * from vehiclehistory ORDER BY vehiclehistory.lastupdated desc LIMIT 0,1";
                $Query = "SELECT (SELECT odometer FROM vehiclehistory ORDER BY odometer LIMIT 1) as 'first',(SELECT odometer FROM vehiclehistory ORDER BY odometer DESC LIMIT 1) as 'last'";
                $sobj = $dbs->prepare($Query);
                $sobj->execute();
                /* Fetch all of the remaining rows in the result set */

                $result = $sobj->fetchAll();
                $DRMS['first'] = $result[0]['first'];
                $DRMS['last'] = $result[0]['last'];
            }
        } catch (PDOException $e) {
            $e->getMessage();
        }
        return $DRMS;
    }

    function gettemplist($tempConversion) {
        $unitType = isset($tempConversion->unit_type) ? $tempConversion->unit_type : 0;
        $use_humidity = isset($tempConversion->use_humidity) ? $tempConversion->use_humidity : 0;
        $switch_to = isset($tempConversion->switch_to) ? $tempConversion->switch_to : 0;

        if ($unitType == 0) {
            $temp = round((($tempConversion->rawtemp - 1150) / 4.45), 2);
            if ($use_humidity == 1 && $switch_to == 3) {
                $temp = round(($tempConversion->rawtemp / 100), 2);
            }
        } elseif ($unitType == 1 || ($use_humidity == 1 && $switch_to == 3)) {
            $temp = round(($tempConversion->rawtemp / 100), 2);
        }
        return $temp;
    }

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Utility functions">

    function generate_valid_xml_from_array($array, $node_block) {
        $xml = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
        $xml .= '<' . $node_block . '>' . "\n";
        $xml .= $this->generate_xml_from_array($array);
        $xml .= '</' . $node_block . '>' . "\n";

        return $xml;
    }

    function generate_xml_from_array($array) {
        $xml = '';

        if (is_array($array) || is_object($array)) {
            foreach ($array as $key => $value) {
                $xml .= '<' . ucfirst($key) . '>' . "\n" . $this->generate_xml_from_array($value) . '</' . $key . '>' . "\n";
            }
        } else {
            $xml = htmlspecialchars($array, ENT_QUOTES) . "\n";
        }
        return $xml;
    }

    function failure($text) {
        $result = array('Status' => 'Failure', 'Error' => $text);
        return json_encode($result);
    }

    // </editor-fold>
}

?>