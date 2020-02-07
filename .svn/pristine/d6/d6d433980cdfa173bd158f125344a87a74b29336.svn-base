<?php

require_once "database.inc.php";

class VODevices {
}

class VODatacap1 {
}

class api {
    //<editor-fold defaultstate="collapsed" desc="Constructor">
    public function __construct() {
        $this->db = new database(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, SPEEDDB);
    }

    // </editor-fold>
    //
    //<editor-fold defaultstate="collapsed" desc="API functions">
    public function getlatestvehicledata(GetLatestVehicleRequest $objGetLatestVehicleRequest) {
        try {
            $getvehicledata = $this->getvehicledata($objGetLatestVehicleRequest);
            if (isset($getvehicledata)) {
                $arrVehDetails = $getvehicledata['result'];
                $totalVehicleCount = $getvehicledata['totalVehicleCount'];
                $finalResponse = array();
                foreach ($arrVehDetails as $vehDetail) {
                    $objGetLatestVehicleResponse = new GetLatestVehicleResponse($vehDetail);
                    $finalResponse[] = $objGetLatestVehicleResponse;
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        print_r($finalResponse);
        return $finalResponse;
    }

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Helper functions">

    public function getvehicledata(GetLatestVehicleRequest $objGetLatestVehicleRequest) {
        //Prepare parameters
        $sp_params = "'" . $objGetLatestVehicleRequest->pageindex . "'"
        . ",'" . $objGetLatestVehicleRequest->pagesize . "'"
        . "," . $objGetLatestVehicleRequest->customerno . ""
        . "," . $objGetLatestVehicleRequest->iswarehouse . ""
        . ",'" . $objGetLatestVehicleRequest->searchstring . "'"
        . ",'" . $objGetLatestVehicleRequest->groupid . "'"
        . ",'" . $objGetLatestVehicleRequest->userkey . "'"
        . ",'" . $objGetLatestVehicleRequest->isRequiredThirdParty . "'";

        $queryCallSP = "CALL " . speedConstants::SP_GET_VEHICLEWAREHOUSE_DETAILS . "($sp_params)";
        $records = $this->db->query($queryCallSP, __FILE__, __LINE__);
        $json_p = array();
        $x = 0;
        $totalVehicleCount = 0;
        while ($row = $this->db->fetch_array($records)) {
            if ($totalVehicleCount == 0) {
                $totalVehicleCount = $row['recordCount'];
            }
            //if ($firstgroup == '' || (in_array($row['veh_grpid'], $groupids))) {
            $json_p[$x]['vehicleid'] = $row['vehicleid'];
            $json_p[$x]['vehicleno'] = $row['vehicleno'];
            $json_p[$x]['groupid'] = $row['groupid'];
            $kind = 0;
            if ($row['kind'] == "Car") {
                $kind = 1;
            } elseif ($row['kind'] == "Truck") {
                $kind = 2;
            } elseif ($row['kind'] == "Bus") {
                $kind = 3;
            }
            $json_p[$x]['kind'] = $kind;
            /* No Need To Pull Location For Integration Platform, Will use the lat, longs for the same */
//$json_p[$x]['location'] = $this->get_location_bylatlong($row['devicelat'], $row['devicelong'], $row['customer_no'], $row['use_geolocation']);
            $json_p[$x]['location'] = '';
            $json_p[$x]['lastupdated'] = $this->getduration1($row['lastupdated']);
            $json_p[$x]['driverphone'] = $row['driverphone'];
            $json_p[$x]['simcardno'] = $row['simcardno'];
            $json_p[$x]['sequenceno'] = $row['sequenceno'];
            $json_p[$x]['isFrozen'] = isset($row['is_freeze']) ? $row['is_freeze'] : '0';
            //status start
            $status = "";
            $ServerIST_less1 = new DateTime();
            $ServerIST_less1->modify('-60 minutes');
            $lastupdated = new DateTime($row['lastupdated']);
            $temp1_conflicted = 0;
            $temp2_conflicted = 0;
            $temp3_conflicted = 0;
            $temp4_conflicted = 0;
            $speed_conflicted = 0;
            if ($lastupdated < $ServerIST_less1) {
                $status = "1"; //inactive grey
            } else {
                //<editor-fold defaultstate="collapsed" desc="Check for temperature conflict">
                if (isset($row['temp_sensors'])) {
                    $status = "1";
                    $temp1 = '';
                    $temp1_min = '';
                    $temp1_max = '';
                    $temp2 = '';
                    $temp2_min = '';
                    $temp2_max = '';
                    $temp3 = '';
                    $temp3_min = '';
                    $temp3_max = '';
                    $temp4 = '';
                    $temp4_min = '';
                    $temp4_max = '';
                    switch ($row['temp_sensors']) {
                        case 4:
                            if (isset($row['tempsen4'])) {
                                //Set default temp conflict as 0
                                $json_p[$x]['temp4_conflicted'] = $temp4_conflicted;
                                $s = "analog" . $row['tempsen4'];
                                $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                if ($row['tempsen4'] != 0 && $analogValue != 0) {
                                    $temp4 = $this->gettemplist($analogValue, $row['use_humidity']);
                                } else {
                                    $temp4 = '';
                                }
                                $temp4_min = (isset($row['temp4_min'])) ? $row['temp4_min'] : '';
                                $temp4_max = (isset($row['temp4_max'])) ? $row['temp4_max'] : '';
                            }
                        case 3:
                            if (isset($row['tempsen3'])) {
                                //Set default temp conflict as 0
                                $json_p[$x]['temp3_conflicted'] = $temp3_conflicted;
                                $s = "analog" . $row['tempsen3'];
                                $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                if ($row['tempsen3'] != 0 && $analogValue != 0) {
                                    $temp3 = $this->gettemplist($analogValue, $row['use_humidity']);
                                } else {
                                    $temp3 = '';
                                }
                                $temp3_min = (isset($row['temp3_min'])) ? $row['temp3_min'] : '';
                                $temp3_max = (isset($row['temp3_max'])) ? $row['temp3_max'] : '';
                            }
                        case 2:
                            if (isset($row['tempsen2'])) {
                                //Set default temp conflict as 0
                                $json_p[$x]['temp2_conflicted'] = $temp2_conflicted;
                                $s = "analog" . $row['tempsen2'];
                                $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                if ($row['tempsen2'] != 0 && $analogValue != 0) {
                                    $temp2 = $this->gettemplist($analogValue, $row['use_humidity']);
                                } else {
                                    $temp2 = '';
                                }
                                $temp2_min = (isset($row['temp2_min'])) ? $row['temp2_min'] : '';
                                $temp2_max = (isset($row['temp2_max'])) ? $row['temp2_max'] : '';
                            }
                        case 1:
                            if (isset($row['tempsen1'])) {
                                $json_p[$x]['temp1_conflicted'] = $temp1_conflicted;
                                $s = "analog" . $row['tempsen1'];
                                $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                if ($row['tempsen1'] != 0 && $analogValue != 0) {
                                    $temp1 = $this->gettemplist($analogValue, $row['use_humidity']);
                                } else {
                                    $temp1 = '';
                                }
                                $temp1_min = (isset($row['temp1_min'])) ? $row['temp1_min'] : '';
                                $temp1_max = (isset($row['temp1_max'])) ? $row['temp1_max'] : '';
                            }
                            break;

                        default:
                            if (isset($row['tempsen1'])) {
                                $json_p[$x]['temp1_conflicted'] = $temp1_conflicted;
                                $s = "analog" . $row['tempsen1'];
                                $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                if ($row['tempsen1'] != 0 && $analogValue != 0) {
                                    $temp1 = $this->gettemplist($analogValue, $row['use_humidity']);
                                } else {
                                    $temp1 = '';
                                }
                                $temp1_min = (isset($row['temp1_min'])) ? $row['temp1_min'] : '';
                                $temp1_max = (isset($row['temp1_max'])) ? $row['temp1_max'] : '';
                            }
                            break;
                    }
                    if ($temp1 != '') {
                        if (!empty($temp1_min) && !empty($temp1_max) && !($temp1_min == 0 && $temp1_max == 0)) {
                            if ($temp1 < $temp1_min || $temp1 > $temp1_max) {
                                // temperature 1 conflict
                                $temp1_conflicted = 1;
                            }
                        }
                        $json_p[$x]['temp1_conflicted'] = $temp1_conflicted;
                    }
                    if ($temp2 != '') {
                        if (!empty($temp2_min) && !empty($temp2_max) && !($temp2_min == 0 && $temp2_max == 0)) {
                            if ($temp2 < $temp2_min || $temp2 > $temp2_max) {
                                // temperature 2 conflict
                                $temp2_conflicted = 1;
                            }
                        }
                        $json_p[$x]['temp2_conflicted'] = $temp2_conflicted;
                    }
                    if ($temp3 != '') {
                        if (!empty($temp3_min) && !empty($temp3_max) && !($temp3_min == 0 && $temp3_max == 0)) {
                            if ($temp3 < $temp3_min || $temp3 > $temp3_max) {
                                // temperature 3 conflict
                                $temp3_conflicted = 1;
                            }
                            $json_p[$x]['temp3_conflicted'] = $temp3_conflicted;
                        }
                    }
                    if ($temp4 != '') {
                        if (!empty($temp4_min) && !empty($temp4_max) && !($temp4_min == 0 && $temp4_max == 0)) {
                            if ($temp4 < $temp4_min || $temp4 > $temp4_max) {
                                // temperature 4 conflict
                                $temp4_conflicted = 1;
                            }
                            $json_p[$x]['temp4_conflicted'] = $temp4_conflicted;
                        }
                    }
                    $json_p[$x]['temp1'] = $temp1;
                    $json_p[$x]['temp2'] = $temp2;
                    $json_p[$x]['temp3'] = $temp3;
                    $json_p[$x]['temp4'] = $temp4;
                }
                //</editor-fold>
                //
                //<editor-fold defaultstate="collapsed" desc="Decide Vehicle colour">
                if ($row['ignition'] == '0') {
                    $status = "2"; //orange yellow
                } else {
                    $currentSpeed = isset($row['curspeed']) ? $row['curspeed'] : '';
                    $overspeedLimit = isset($row['overspeed_limit']) ? $row['overspeed_limit'] : '';
                    if ($currentSpeed != '' && $overspeedLimit != '' && ($currentSpeed > $overspeedLimit)) {
                        $status = "4"; //red overspeed
                        $speed_conflicted = 1;
                    } else {
                        $stoppage_flag = isset($row['stoppage_flag']) ? $row['stoppage_flag'] : '';
                        if ($stoppage_flag != '' && $stoppage_flag == '0') {
                            $status = "5"; //blue idle ignition
                        } else {
                            $status = "3"; //green  run
                        }
                    }
                }
                $json_p[$x]['speed_conflicted'] = $speed_conflicted;
                //</editor-fold>
            }

            $json_p[$x]['distance'] = $this->distance($row['customer_no'], $row['unitno']);
            $json_p[$x]['vehicle_color'] = $status;
            switch ($status) {
                case 1:
                    $json_p[$x]['vehiclestatus'] = "Vehicle - Inactive";
                    break;
                case 2:
                    $json_p[$x]['vehiclestatus'] = "Vehicle - Idle-Ignition Off";
                    break;
                case 3:
                    $json_p[$x]['vehiclestatus'] = "Vehicle - Running";
                    break;
                case 4:
                    $json_p[$x]['vehiclestatus'] = "Vehicle - Overspeed";
                    break;
                case 5:
                    $json_p[$x]['vehiclestatus'] = "Vehicle - Idle-Ignition On";
                    break;
                default:$json_p[$x]['vehiclestatus'] = "";
                    break;
            }
            $json_p[$x]['vehiclespeed'] = isset($row['curspeed']) ? $row['curspeed'] : '';
            $json_p[$x]['drivername'] = isset($row['drivername']) ? $row['drivername'] : '';
            $json_p[$x]['groupname'] = isset($row['groupname']) ? $row['groupname'] : '';
            $json_p[$x]['lat'] = $row['devicelat'];
            $json_p[$x]['lng'] = $row['devicelong'];
            $json_p[$x]['direction'] = isset($row['directionchange']) ? $row['directionchange'] : 0;
            $x++;
        }
        // Free result set
        $records->close();
        $this->db->next_result();
        $arr_p['result'] = $json_p;
        $arr_p['totalVehicleCount'] = $totalVehicleCount;
        return $arr_p;
    }

    public function get_userdetails_by_key($userkey) {
        $SQL = "SELECT customerno,userid,role,roleid FROM user WHERE isdeleted=0 and userkey='" . $userkey . "'";
        $Query = sprintf($SQL);
        $result = $this->db->query($Query, __FILE__, __LINE__);
        if ($this->db->num_rows($result) > 0) {
            while ($row = $this->db->fetch_array($result)) {
                $data = array(
                    'customerno' => $row['customerno'],
                    'userid' => $row['userid'],
                    'role' => $row['role'],
                    'roleid' => $row['roleid']
                );
                return $data;
            }
        }
        return null;
    }

    public function checkValidity($customerno) {
        $devices = $this->checkforvalidity($customerno);
        $initday = 0;
        if (isset($devices)) {
            foreach ($devices as $thisdevice) {
                $days = $this->check_validity_login($thisdevice->expirydate, $thisdevice->today);
                if ($days > 0) {
                    $initday = $days;
                }
            }
        }
        return $initday;
    }

    public function checkforvalidity($customerno, $deviceid = null) {
        $devices = Array();
        $Query = "SELECT deviceid,expirydate, Now() as today FROM `devices` where customerno=%d ";
        if ($deviceid != null) {
            $Query .= " AND deviceid = $deviceid";
        }
        $devicesQuery = sprintf($Query, $customerno);
        $record = $this->db->query($devicesQuery, __FILE__, __LINE__);

        while ($row = $this->db->fetch_array($record)) {
            $device = new VODevices();
            $device->deviceid = $row['deviceid'];
            $device->today = $row["today"];
            $device->expirydate = $row["expirydate"];
            $devices[] = $device;
        }
        return $devices;
    }

    public function check_validity_login($expirydate, $currentdate) {
        date_default_timezone_set("Asia/Calcutta");
        $expirytimevalue = '23:59:59';
        $expirydate = date('Y-m-d H:i:s', strtotime("$expirydate $expirytimevalue"));
        $realtime = strtotime($currentdate);
        $expirytime = strtotime($expirydate);
        $diff = $expirytime - $realtime;
        return $diff;
    }

    public function get_LoadingidleMin($reportloadingdate, $loadingdate, $vehicleid, $customerno, $unitno) {
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

    public function optimizerep($data) {
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

    public function countloadingidle($datarows, $vehicleid, $unitno, $startdate, $enddate) {
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
            'idlemin' => $idletime
        );

        return $data;
    }

    public function new_travel_data1($customerno, $unitno, $userdate, $count, $firstelement, $endelement, $vehicleid, $Shour, $Ehour) {
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

    public function processtraveldata($devicedata, $unitno, $customerno) {
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

    public function getduration($EndTime, $StartTime) {
        $idleduration = strtotime($EndTime) - strtotime($StartTime);
        $years = floor($idleduration / (365 * 60 * 60 * 24));
        $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
        $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        return $hours * 60 + $minutes;
    }

    public function GetOdometerMax($date, $unitno, $customerno) {
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

    public function getdatafromsqliteTimebased($vehicleid, $location, $userdate, $Shour, $Ehour, $customerno = '') {
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
//                  $exquery = sprintf($query);
                //                  $result = $this->db->query($exquery, __FILE__, __LINE__);
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

    public function gendays_cmn($STdate, $EDdate) {
        $TOTALDAYS = Array();
        $STdate = date("Y-m-d", strtotime($STdate));
        $EDdate = date("Y-m-d", strtotime($EDdate));
        while (strtotime($STdate) <= strtotime($EDdate)) {
            $TOTALDAYS[] = $STdate;
            $STdate = date("Y-m-d", strtotime($STdate . ' + 1 day'));
        }
        return $TOTALDAYS;
    }

    public function get_location_bylatlong($lat, $long, $customerno) {
        $location_string = '';
        $latint = floor($lat);
        $longint = floor($long);
        $pdo = $this->db->CreatePDOConn();
        $geoloc_query = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( " . $lat . "- `lat` ) * PI( ) /180 /2 ) , 2 ) +
                         COS( " . $lat . " * PI( ) /180 ) * COS( `lat` * PI( ) /180 ) * POWER( SIN( ( " . $long . " - `long` ) * PI( ) /180 /2 ) , 2 ) ) )
                         AS distance FROM geotest WHERE `latfloor` = " . $latint . " AND `longfloor` = " . $longint . " HAVING distance <2 AND customerno = " . $customerno . " ORDER BY distance LIMIT 0,1 ";
        $arrResult = $pdo->query($geoloc_query)->fetchAll(PDO::FETCH_ASSOC);
        $this->db->ClosePDOConn($pdo);
        if (count($arrResult) == 1) {
            if ($arrResult[0]['distance'] > 1) {
                $location_string = round($arrResult[0]['distance'], 2) . " Km from " . $arrResult[0]['location'] . ", " . $arrResult[0]['city'] . ", " . $arrResult[0]['state'];
            } else {
                $location_string = "Near " . $arrResult[0]['location'] . ", " . $arrResult[0]['city'] . ", " . $arrResult[0]['state'];
            }
        } else {
            $geolocation_query = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( " . $lat . "- `lat` ) * PI( ) /180 /2 ) , 2 ) +
                             COS( " . $lat . " * PI( ) /180 ) * COS( `lat` * PI( ) /180 ) * POWER( SIN( ( " . $long . " - `long` ) * PI( ) /180 /2 ) , 2 ) ) )
                             AS distance FROM geotest WHERE `latfloor` = " . $latint . " AND `longfloor` = " . $longint . " HAVING distance <10 AND customerno IN(0, " . $customerno . ") ORDER BY distance LIMIT 0,1 ";

            $pdo = $this->db->CreatePDOConn();
            $arrResult = $pdo->query($geolocation_query)->fetchAll(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            if (count($arrResult) == 1) {
                if ($arrResult[0]['distance'] > 1) {
                    $location_string = round($arrResult[0]['distance'], 2) . " Km from " . $arrResult[0]['location'] . ", " . $arrResult[0]['city'] . ", " . $arrResult[0]['state'];
                } else {
                    $location_string = "Near " . $arrResult[0]['location'] . ", " . $arrResult[0]['city'] . ", " . $arrResult[0]['state'];
                }
            } else {
                $location_string = "google temporarily down";
            }
        }
        return $location_string;
    }

    //calculate distance
    public function distance($customerno, $unitno) {
        /*
        $date = date('Y-m-d');
        $location = "../../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
        $Data = $this->odometerfromSqlite($location);
        if ($Data != 0) {
        $lastodometer = $Data['last'];
        $firstodometer = $Data['first'];
        $distance = $lastodometer / 1000 - $firstodometer / 1000;
        $distancekm = round($distance, 2);
        }
        else {
        $distancekm = 0;
        }
        return $distancekm;
         */

        $totaldistance = 0;
        $todaysDate = date('Y-m-d');
        /* realtime-data distance calculation */
        //Prepare parameters
        $this->db->next_result();
        $sp_params = "'" . $unitno . "'"
            . "," . $customerno
            . ",'" . $todaysDate . "'"
        ;
        $queryCallSP = "CALL " . speedConstants::SP_GET_ODOMETER_READING . "($sp_params)";

        $records = $this->db->query($queryCallSP, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($records);

        $arrResult = array();
        if ($row_count > 0) {
            $arrResult = $this->db->fetch_array($records);
        }
        if (!empty($arrResult)) {
            $firstodometer = $arrResult['first_odometer'];
            $lastodometer = $arrResult['last_odometer'];

            if ($lastodometer < $firstodometer) {
                $lastodometer = $arrResult['max_odometer'] + $lastodometer;
            }
            $totaldistance = $lastodometer - $firstodometer;
            if (round($totaldistance) != 0) {
                $totaldistance = round(($totaldistance / 1000), 2);
            }
        }
        // Free result set
        $records->close();
        $this->db->next_result();
        return $totaldistance;
    }

    public function getduration1($StartTime) {
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

    public function odometerfromSqlite($location) {
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

    public function gettemplist($rawtemp, $use_humidity) {
        if ($use_humidity == 1) {
            $temp = round($rawtemp / 100);
        } else {
            $temp = round((($rawtemp - 1150) / 4.45), 1);
        }
        return $temp;
    }

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Utility functions">

    public function generate_valid_xml_from_array($array, $node_block) {
        $xml = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
        $xml .= '<' . $node_block . '>' . "\n";
        $xml .= $this->generate_xml_from_array($array);
        $xml .= '</' . $node_block . '>' . "\n";

        return $xml;
    }

    public function generate_xml_from_array($array) {
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

    public function failure($message, $result = null) {
        return array('Status' => '0', 'Message' => $message, 'Result' => $result);
    }

    public function success($message, $result) {
        return array('Status' => '1', 'Message' => $message, 'Result' => $result);
    }

    // </editor-fold>
}

?>