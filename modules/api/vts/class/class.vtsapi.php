<?php

require_once "database.inc.php";

class VODevices {
}

class VODatacap1 {
}

class TempConversion {
    /* int */public $rawtemp;
    /* boolean */public $unit_type = 0;
    /* boolean */public $use_humidity = 0;
    /* boolean */public $switch_to = 0;
}

class api {
    static $SMS_TEMPLATE_FOR_QUICK_SHARE = "Vehicle No: {{VEHICLENO}}\r\nLocation: {{LOCATION}}\r\nShared by: {{USERNAME}}";

    //<editor-fold defaultstate="collapsed" desc="Constructor">

    public function __construct() {
        $this->db = new database(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, SPEEDDB);
    }

    // </editor-fold>
    //
    //<editor-fold defaultstate="collapsed" desc="API functions">
    public function getVehicleList($objGetVehicleList) {
        try {
            $objVehicleManager = new VehicleManager($objGetVehicleList->customerNo);
            $arrResult = $objVehicleManager->get_all_vehicles();
            $arrVehicles["vehicles"] = array_map(function ($element) {
                return preg_replace("/\s+/", "", $element->vehicleno);
            }, $arrResult);
            //$arrVehicles["vehicles"] = array_column($arrResult, "vehicleno");
        } catch (Exception $ex) {
            throw $ex;
        }
        return $arrVehicles;
    }

    public function getlatestvehicledata($objGetLatestVehicleRequest) {
        $finalResponse = array();
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
        return $finalResponse;
    }

    public function pushvehicledata(RequestPushVehicleData $objPushVehicleDataRequest) {
        $objResponsePushVehicleData = new ResponsePushVehicleData($objPushVehicleDataRequest);
        $objResponsePushVehicleData->isLiveDataUpdated = 0;
        $objResponsePushVehicleData->isReportDataUpdated = 0;
        if ($objPushVehicleDataRequest->vehicleNo == "" || $objPushVehicleDataRequest->unitNo == "") {
            $objResponsePushVehicleData->isLiveDataUpdated = 0;
        } else {
            $objVehicleManager = new VehicleManager($objPushVehicleDataRequest->customerno);

            if (isset($objPushVehicleDataRequest->customerno) && $objPushVehicleDataRequest->customerno == 745) {
                $objPushVehicleDataRequest->odometer = $objPushVehicleDataRequest->odometer;
            } elseif (isset($objPushVehicleDataRequest->customerno) && $objPushVehicleDataRequest->customerno == speedConstants::CUSTNO_COLDRUSH) {
                $objPushVehicleDataRequest->odometer = isset($objPushVehicleDataRequest->odometer) ? ROUND(($objPushVehicleDataRequest->odometer * 1000), 0) : 0;
            }

            if ($objPushVehicleDataRequest->isOnline == 1) {
                // $objVehicleManager = new VehicleManager($objPushVehicleDataRequest->customerno);
                //Update in MySQL
                $objOutput = $objVehicleManager->apiUpdateDeviceData($objPushVehicleDataRequest);
            } elseif ($objPushVehicleDataRequest->isOnline == 0) {
                $objOutput = $objVehicleManager->getVehicleDataFromUnitNo($objPushVehicleDataRequest);
            }
            if ((isset($objOutput->isUpdated) && $objOutput->isUpdated == 1) || $objPushVehicleDataRequest->isOnline == 0) {
                //Enter in Sqlite
                $objPushVehicleDataRequest->uid = isset($objOutput->uid) ? $objOutput->uid : 0;
                $objPushVehicleDataRequest->unitNo = isset($objOutput->unitNo) ? $objOutput->unitNo : "";
                $objPushVehicleDataRequest->vehicleId = isset($objOutput->vehicleId) ? $objOutput->vehicleId : 0;
                $objPushVehicleDataRequest->deviceId = isset($objOutput->deviceId) ? $objOutput->deviceId : 0;
                $objPushVehicleDataRequest->driverId = isset($objOutput->driverId) ? $objOutput->driverId : 0;
                $isDataInSqlite = $this->InsertDataInSqlite($objPushVehicleDataRequest);
                $objResponsePushVehicleData->isLiveDataUpdated = 1;
                if ($isDataInSqlite == 1) {
                    $objResponsePushVehicleData->isReportDataUpdated = 1;
                }

                // if (isset($objPushVehicleDataRequest->customerno) && $objPushVehicleDataRequest->customerno == 745) {
                #######################
                ##/* Daily Report */###
                #######################
                $objDailyReportManager = new DailyReportManager($objPushVehicleDataRequest->customerno);

                $objPushVehicleDataRequest->status = isset($objPushVehicleDataRequest->status) ? $objPushVehicleDataRequest->status : "";
                $todaysDateTime = new DateTime();
                // if ($objDeviceData->gpsfixed == "A" && $objDeviceData->isOffline == 0) {
                if ($objPushVehicleDataRequest->isOnline == 1) {
                    /*error_reporting(E_ALL);
                    ini_set('display_errors', 1);
                    ini_set('display_startup_errors', 1);*/

                    $objRequest = new stdClass();
                    $objRequest->timestamp = $objPushVehicleDataRequest->lastUpdated; ///////////////////////////////
                    $objRequest->customerno = $objPushVehicleDataRequest->customerno;
                    $objRequest->vehicleid = $objPushVehicleDataRequest->vehicleId;
                    $objRequest->unitid = $objPushVehicleDataRequest->uid;
                    $objRequest->devicelat = $objPushVehicleDataRequest->latitude;
                    $objRequest->devicelong = $objPushVehicleDataRequest->longitude;
                    $objRequest->odometer = $objPushVehicleDataRequest->odometer;
                    $objRequest->driverid = $objPushVehicleDataRequest->driverId;
                    $objRequest->daily_date = isset($objPushVehicleDataRequest->lastUpdated) ?
                    date(speedConstants::DATE_Ymd, strtotime($objPushVehicleDataRequest->lastUpdated))
                    : null;

                    // $arrDailyReportResult = $objDailyReportManager->GetDailyReport_Listener($objPushVehicleDataRequest);
                    $arrDailyReportResult = $objDailyReportManager->GetDailyReport_Listener($objRequest);
// print("<pre>"); print_r($arrDailyReportResult[0]); die;
                    if (isset($arrDailyReportResult) && is_array($arrDailyReportResult) && count($arrDailyReportResult) == 1) {
                        $objRequest->flag_harsh_break = $arrDailyReportResult[0][0];
                        $objRequest->flag_sudden_acc = $arrDailyReportResult[0][1];
                        $objRequest->flag_towing = $arrDailyReportResult[0][2];
                        $objRequest->last_odometer = $arrDailyReportResult[0][3];
                        $objRequest->topspeed = $arrDailyReportResult[0][4];
                        $objRequest->last_online_updated = $arrDailyReportResult[0][5];
                        $objRequest->offline_data_time = $arrDailyReportResult[0][6];
                        $objRequest->topspeed_lat = $arrDailyReportResult[0][7];
                        $objRequest->topspeed_long = $arrDailyReportResult[0][8];
                        $objRequest->harsh_break = $arrDailyReportResult[0][9];
                        $objRequest->sudden_acc = $arrDailyReportResult[0][10];
                        $objRequest->towing = $arrDailyReportResult[0][11];
                        $objRequest->max_odometer = $arrDailyReportResult[0][12];
                        $objRequest->end_lat = $arrDailyReportResult[0][13];
                        $objRequest->end_long = $arrDailyReportResult[0][14];
                        $objRequest->first_odometer = $arrDailyReportResult[0][15];
                        $objRequest->topspeed_time = $arrDailyReportResult[0][16];
                        $objRequest->daily_date = $arrDailyReportResult[0][17];

                        if ($objRequest->first_odometer == 0) {
                            $objRequest->first_odometer = $objRequest->last_odometer;
                        }
                        $last_online_updatedDate = new DateTime($objRequest->last_online_updated);
                        $diffInterval = $last_online_updatedDate->diff($todaysDateTime);

                        $diffInSeconds = ($diffInterval->y * 12 * 30 * 24 * 60 * 60)
                             + ($diffInterval->m * 30 * 24 * 60 * 60)
                             + ($diffInterval->d * 24 * 60 * 60)
                             + ($diffInterval->h * 60 * 60)
                             + ($diffInterval->i * 60)
                             + ($diffInterval->s);

                        if ($diffInSeconds > 600) {
                            $objRequest->offline_data_time = $objRequest->offline_data_time + $diffInSeconds;
                        }
                        # Top Speed
                        if ($objPushVehicleDataRequest->speed > $objRequest->topspeed) {
                            $objRequest->topspeed = $objPushVehicleDataRequest->speed;
                            $objRequest->topspeed_lat = $objRequest->devicelat;
                            $objRequest->topspeed_long = $objRequest->devicelong;
                            $objRequest->topspeed_time = $objRequest->timestamp;
                        }
                        # Harsh Break
                        if ($objPushVehicleDataRequest->status == 'S' && $objRequest->flag_harsh_break == 0) {
                            $objRequest->harsh_break = $objRequest->harsh_break + 1;
                            $objRequest->flag_harsh_break = 1;
                        }
                        if ($objPushVehicleDataRequest->status != 'S') {
                            $objRequest->flag_harsh_break = 0;
                        }
                        # Sudden Acceleration

                        if ($objPushVehicleDataRequest->status == 'U' and $objRequest->flag_sudden_acc == 0) {
                            $objRequest->sudden_acc = $objRequest->sudden_acc + 1;
                            $objRequest->flag_sudden_acc = 1;
                        }
                        if ($objPushVehicleDataRequest->status != 'U') {
                            $objRequest->flag_sudden_acc = 0;
                        }
                        # Towing
                        if (($objPushVehicleDataRequest->status == 'V' || ($objPushVehicleDataRequest->ignition == 0 && $objPushVehicleDataRequest->speed > 10)) && str($objRequest->flag_towing) == '0') {
                            $objRequest->towing = $objRequest->towing + 1;
                            $objRequest->flag_towing = 1;
                        }
                        if ($objPushVehicleDataRequest->status != 'V' && (($objPushVehicleDataRequest->ignition == 0 && $objPushVehicleDataRequest->speed < 10) || $objPushVehicleDataRequest->ignition == 1)) {
                            $objRequest->flag_towing = 0;
                        }
                        # Max Odometer Reading
                        if ($objPushVehicleDataRequest->odometer > $objRequest->max_odometer) {
                            $objRequest->max_odometer = $objPushVehicleDataRequest->odometer;
                        }
                        /*echo $todaysDateTime->format("Y-m-d");
                        echo "<br>".$objRequest->daily_date;*/
                        if ($todaysDateTime->format("Y-m-d") == $objRequest->daily_date) {
                            // echo "upadte"; die;
                            $objDailyReportManager->UpdateDailyReport_Listener($objRequest);
                        } else {
                            // echo "insert";
                            // print_r($objRequest); die;
                            $objDailyReportManager->InsertDailyReport_Listener($objRequest);
                        }
                    } else {
                        // print("<pre>"); print_r($objRequest); die;
                        $objDailyReportManager->InsertDailyReport_Listener($objRequest);
                    }
                }
                ###########################
                ##/* END Daily Report */###
                ###########################
                // }
            }
        }
        return $objResponsePushVehicleData;
    }

    public function getlocationreport($objGetLocationVehicleRequest) {
        $finalResponse = array();
        $intervalarr = array(1, 5, 10, 30, 60);
        $mins = isset($objGetLocationVehicleRequest->interval) ? $objGetLocationVehicleRequest->interval : "";
        if (!empty($objGetLocationVehicleRequest->vehicleno) && !empty($objGetLocationVehicleRequest->startdatetime) && ($objGetLocationVehicleRequest->startdatetime) < ($objGetLocationVehicleRequest->enddatetime) && !empty($objGetLocationVehicleRequest->enddatetime) && !empty($objGetLocationVehicleRequest->interval) && in_array($mins, $intervalarr)) {
            $customerno = $objGetLocationVehicleRequest->customerno;
            $vehicleno = $objGetLocationVehicleRequest->vehicleno;
            $startdatetime = $objGetLocationVehicleRequest->startdatetime;
            $enddatetime = $objGetLocationVehicleRequest->enddatetime;
            $frequency = isset($objGetLocationVehicleRequest->reporttype) ? $objGetLocationVehicleRequest->reporttype : 1; // reporttype 2 distance not allowed
            $vehicleid = "";
            if (!empty($vehicleno)) {
                $vehicleid = $this->getvehicleid($customerno, $vehicleno);
            }

            $datecheck = $this->datediff($startdatetime, $enddatetime);
            $datediffcheck = $this->date_SDiff($startdatetime, $enddatetime);

            //cap for location report
            /*
             * For interval minute =1 && datedifference shold not be greater than 1 day
             * For interval minutes =5 and 10 && datedifference shold not be greater than 2 days
             * For interval minutes 30 && 60 && datedifference shold not be greater than 2 days
             */
            //echo $mins ."--". $datediffcheck; die();
            if ($datecheck == 1 && $mins == 1 && $datediffcheck > 1) {
                $message = "Date difference should not be greater than one day";
                $finalResponse['status'] = 0;
                $finalResponse['result'] = "";
                $finalResponse['message'] = $message;
            } elseif ($datecheck == 1 && ($mins == 5 || $mins == 10) && ($datediffcheck > 2)) {
                $message = "Date difference should not be greater than two days";
                $finalResponse['status'] = 0;
                $finalResponse['result'] = "";
                $finalResponse['message'] = $message;
            } elseif ($datecheck == 1 && ($mins == 30 || $mins == 60) && ($datediffcheck > 15)) {
                $message = "Date difference should not be greater than 15-days";
                $finalResponse['status'] = 0;
                $finalResponse['result'] = "";
                $finalResponse['message'] = $message;
            } elseif ($datecheck == 1 && ($datediffcheck <= 30)) {
                $deviceid = 0;
                $devices = $this->getvehicledetail($vehicleid, $customerno);
                if (!empty($devices)) {
                    $deviceid = $devices['deviceid'];
                    $unitno = $devices['unitno'];
                }
                $locationdata = $this->getlocationreportapi($startdatetime, $enddatetime, $deviceid, $unitno, $mins, null, $customerno);
                $devicelat = "";
                $devicelong = "";
                $locationdatacount = "0";
                $locationdatacount = count($locationdata);

                if ($locationdatacount == 1) {
                    $devicelat = $locationdata[0]['devicelat'];
                    $devicelong = $locationdata[0]['devicelong'];
                }

                if (empty($locationdata)) {
                    $message = "Data not found";
                    $finalResponse['status'] = 0;
                    $finalResponse['result'] = "";
                    $finalResponse['message'] = $message;
                } elseif ($locationdatacount == 1 && $devicelat == '0' && $devicelong == '0') {
                    $message = "Data not found";
                    $finalResponse['status'] = 0;
                    $finalResponse['result'] = "";
                    $finalResponse['message'] = $message;
                } else {
                    $dataresult = array();
                    foreach ($locationdata as $row) {
                        $data = new ResponseLocationVehicleClass($row);
                        $dataresult[] = array(
                            'datetime' => $data->datetime,
                            'lat' => $data->lat,
                            'long' => $data->long,
                            'vehiclespeed' => $data->vehiclespeed,
                            'temperature1' => $data->temperature1,
                            'temperature2' => $data->temperature2,
                            'temperature3' => $data->temperature3,
                            'temperature4' => $data->temperature4,
                            'digitalstatus' => $data->digitalstatus,
                            'vehiclestatus' => $data->vehiclestatus,
                            'distance' => $data->distance,
                            'cumulative_distance' => $data->cumulative_distance
                        );
                    }
                    $message = "sucessful";
                    $finalResponse['status'] = 1;
                    $finalResponse['result'] = $dataresult;
                    $finalResponse['message'] = $message;
                }
            }
        } else {
            $finalResponse['message'] = "Please check passing parameters";
            $finalResponse['result'] = "";
            $finalResponse['status'] = 0;
        }
        return $finalResponse;
    }

    public function getalerthistory($objGetLatestVehicleRequest) {
        $vehicleno = $objGetLatestVehicleRequest->vehicleno;
        $customerno = $objGetLatestVehicleRequest->customerno;
        $vehicleid = "";
        if (!empty($vehicleno)) {
            $vehicleid = $this->getvehicleid($customerno, $vehicleno);
        }

        $queue = Array();
        $newdate = date('Y-m-d');
        //$newdate = "2016-08-02";
        $checkdate = '%' . $newdate . '%';
        if ($vehicleid != '') {
            $queueQuery = sprintf("SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = %d AND comqueue.vehicleid= %d AND comqueue.timeadded LIKE '%s'", Sanitise::Long($customerno), Sanitise::Long($vehicleid), Sanitise::String($checkdate));
        } else {
            $queueQuery = sprintf("SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = %d AND comqueue.timeadded LIKE '%s'", Sanitise::Long($customerno), Sanitise::String($checkdate));
        }
        $queueQuery .= " ORDER BY  comqueue.timeadded ASC ";
        //echo $queueQuery; die();
        $record = $this->db->query($queueQuery, __FILE__, __LINE__);
        $data = array();
        $row_count = $this->db->num_rows($record);
        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $data[] = array(
                    'type' => $row['type'],
                    'timeadded' => convertDateToFormat($row["timeadded"], speedConstants::DEFAULT_TIME),
                    'message' => $row['message'],
                    'processed' => $row['processed'],
                    'comtype' => $row['comtype'],
                    'email' => $row['email'],
                    'phone' => $row['phone']
                );
            }
        }
        return $data;
    }

    public function freezevehicledata($objGetFreezeVehicleRequest) {
        try {
            $getvehicledata = $this->freezevehicleapi($objGetFreezeVehicleRequest);

            if (isset($getvehicledata)) {
                $arrVehDetails = $getvehicledata['Result'];
                $finalResponse = array();
                $finalResponse['result'] = new ResponseFreezeClass($arrVehDetails);
                $finalResponse['message'] = $getvehicledata['Message'];
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return $finalResponse;
    }

    public function freezevehicleapi($objGetFreezeVehicleRequest) {
        $vehicleno = $objGetFreezeVehicleRequest->vehicleno;
        $customerno = $objGetFreezeVehicleRequest->customerno;
        $userid = $objGetFreezeVehicleRequest->userid;
        $fstatus = $objGetFreezeVehicleRequest->freeze;
        $fRadiusInKm = isset($objGetFreezeVehicleRequest->freezeRadiusInKm) ? $objGetFreezeVehicleRequest->freezeRadiusInKm : 0;
        if ($vehicleno != '' && !empty($vehicleno)) {
            $vehicleid = $this->getvehicleid($customerno, $vehicleno);
        }

        $arr_p = array();
        if ($customerno == 97) {
            date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
        }
        $datavehdata = $this->getvehicledetail($vehicleid, $customerno);
        // print_r($datavehdata); die;
        if (isset($datavehdata)) {
            $today = date("Y-m-d H:i:s");

            $objFreeze = new stdClass();
            $objFreeze->vehicleIdFreeze = $datavehdata['vehicleid'];
            $objFreeze->uIdFreeze = $datavehdata['uid'];
            $objFreeze->deviceLatFreeze = $datavehdata['devicelat'];
            $objFreeze->deviceLongFreeze = $datavehdata['devicelong'];
            $objFreeze->freezeRadiusInKm = $fRadiusInKm;
            $objFreeze->userId = $userid;
            $objFreeze->customerNo = $customerno;
            $objFreeze->todaysDate = $today;
            $objFreeze->isApi = '1';
            $objFreeze->fStatus = $fstatus;

            $pdo = $this->db->CreatePDOConn();
            /* Prepare SP Parameters */
            $sp_params = "";
            if (isset($objFreeze)) {
                $sp_params = "'" . $objFreeze->vehicleIdFreeze . "'"
                . ",'" . $objFreeze->uIdFreeze . "'"
                . ",'" . $objFreeze->deviceLatFreeze . "'"
                . ",'" . $objFreeze->deviceLongFreeze . "'"
                . ",'" . $objFreeze->freezeRadiusInKm . "'"
                . ",'" . $objFreeze->isApi . "'"
                . ",'" . $objFreeze->fStatus . "'"
                . ",'" . $objFreeze->userId . "'"
                . ",'" . $objFreeze->customerNo . "'"
                . ",'" . $objFreeze->todaysDate . "'"
                    . "," . "@currentFreezeLogId";
            }

            $arr_p['Result'] = "";
            if ($fstatus == '1') {
                if ($datavehdata['freezeId'] == "") {
                    try {
                        $query = $this->db->PrepareSP(speedConstants::SP_INSERT_FREEZE_LOG, $sp_params);

                        $pdo->query($query);
                        $outputParamsQuery = "SELECT @currentFreezeLogId AS currentFreezeLogId";
                        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
                        if ($outputResult['currentFreezeLogId'] > 0) {
                            $currentFreezeLogId = $outputResult["currentFreezeLogId"];
                            $arr_p['Status'] = "1";
                            $arr_p['Message'] = "Freezed Vehicle ";
                            //"vehicle":"<vehicleno>","freezestatus":"1"
                            $arr_p['Result'] = array("vehicleno" => $vehicleno, "freezestatus" => $fstatus);
                        } else {
                            $arr_p['Status'] = "0";
                            $arr_p['Message'] = "Vehicle Already Freezed.";
                            $arr_p['Result'] = array("vehicleno" => $vehicleno, "freezestatus" => $fstatus);
                        }
                    } catch (Exception $ex) {
                        $log = new Log();
                        $log->createlog($this->customerno, $ex, $this->userid, speedConstants::MODULE_VTS, __FUNCTION__);
                    }
                } else {
                    $arr_p['Status'] = "1";
                    $arr_p['Message'] = "Vehicle Already Freezed.";
                    //"vehicle":"<vehicleno>","freezestatus":"1"
                    $arr_p['Result'] = array("vehicleno" => $vehicleno, "freezestatus" => $fstatus);
                }
            } elseif ($fstatus == '0') {
                try {
                    $query = $this->db->PrepareSP(speedConstants::SP_INSERT_FREEZE_LOG, $sp_params);

                    $pdo->query($query);
                    $outputParamsQuery = "SELECT @currentFreezeLogId AS currentFreezeLogId";
                    $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
                    if ($outputResult['currentFreezeLogId'] > 0) {
                        $currentFreezeLogId = $outputResult["currentFreezeLogId"];
                        $arr_p['Status'] = "1";
                        $arr_p['Message'] = "Unfreezed Vehicle ";
                        $arr_p['Result'] = array("vehicleno" => $vehicleno, "freezestatus" => $fstatus);
                    } else {
                        $arr_p['Status'] = "0";
                        $arr_p['Message'] = "Vehicle Already Unfreezed.";
                        $arr_p['Result'] = array("vehicleno" => $vehicleno, "freezestatus" => $fstatus);
                    }
                } catch (Exception $ex) {
                    $log = new Log();
                    $log->createlog($this->customerno, $ex, $this->userid, speedConstants::MODULE_VTS, __FUNCTION__);
                }
            } else {
                $arr_p['Status'] = "1";
                $arr_p['Message'] = "No Freeze ";
            }
        } else {
            $arr_p['Status'] = "0";
            $arr_p['Error'] = "vehicleno Missing";
        }
        return $arr_p;
    }

    public function pushmobiliserdata(GetImobilizerVehicleRequest $objGetImobVehicleRequest) {
        try {
            $getvehicledata = $this->pushmobiliserapi($objGetImobVehicleRequest);
            if (isset($getvehicledata)) {
                $arrVehDetails = $getvehicledata['Result'];
                $finalResponse = array();
                $finalResponse['result'] = new ResponseImmobilizerClass($arrVehDetails);
                $finalResponse['message'] = $getvehicledata['Message'];
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return $finalResponse;
    }

    public function pushmobiliserapi($objGetLatestVehicleRequest) {
        $vehicleno = $objGetLatestVehicleRequest->vehicleno;
        $customerno = $objGetLatestVehicleRequest->customerno;
        $userid = $objGetLatestVehicleRequest->userid;
        $status = $objGetLatestVehicleRequest->immobilize;
        if (!empty($vehicleno)) {
            $vehicleid = $this->getvehicleid($customerno, $vehicleno);
        }

        $arr_p = array();
        $arr_p['Result'] = "";
        if ($customerno == 97) {
            date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
        }
        $datavehdata = $this->getvehicledetail($vehicleid, $customerno);
        if (isset($datavehdata)) {
            $vehicleid_freeze = $datavehdata['vehicleid'];
            $uid_freeze = $datavehdata['uid'];
            $devicelat = $datavehdata['devicelat'];
            $devicelong = $datavehdata['devicelong'];
            $uid = $datavehdata['uid'];

            $today = date("Y-m-d H:i:s");
            if ($customerno == 97) {
                date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
            }
            $query = "select u.unitno from vehicle as v inner join unit as u on v.uid = u.uid  where v.vehicleid =" . $vehicleid . " AND  v.isdeleted=0";
            $record = $this->db->query($query, __FILE__, __LINE__);
            while ($row = $this->db->fetch_array($record)) {
                $unitno = $row['unitno'];
            }
            if (!empty($unitno)) {
                $check = "";
                if ($status == '0') {
                    $arr_p['Status'] = 1;
                    $arr_p['Message'] = 'Start Vehicle'; //start
                    $arr_p['Result'] = array("vehicle" => $vehicleno);
                    $command = 'STARTV';
                    $check = 1;
                } elseif ($status == '1') {
                    $arr_p['Status'] = 1;
                    $command = 'STOPV';
                    $arr_p['Message'] = 'Stop Vehicle'; // stop vehicle
                    $arr_p['Result'] = array("vehicle" => $vehicleno);
                    $check = 1;
                }

                if (!empty($check)) {
                    if ($command == 'STARTV') {
                        $flag = 0;
                    } else {
                        $flag = 1;
                    }
                    $Que = "UPDATE unit SET  setcom = 1, command='" . $command . "', mobiliser_flag=" . $flag . "  WHERE unitno='" . $unitno . "' AND customerno=" . $customerno;
                    //$record1 = $this->db->query($Que, __FILE__, __LINE__);
                    ///Insert in mobiliserlog table

                    if (isset($datavehdata)) {
                        $today = date("Y-m-d H:i:s");
                        $sql = "INSERT INTO immobiliserlog (uid, vehicleid, devicelat,devicelong,commandname,mobiliser_flag,customerno,createdby ,createdon,is_api) "
                            . " VALUES ('" . $uid . "','" . $vehicleid . "','" . $devicelat . "','" . $devicelong . "','" . $command . "','" . $flag . "','" . $customerno . "','" . $userid . "','" . $today . "',1)";
                        $this->db->query($sql, __FILE__, __LINE__);
                    }
                }
            }
        } else {
            $arr_p['Status'] = 0;
            $arr_p['Failure'] = "vehicleno Missing";
        }
        return $arr_p;
    }

    public function pushbuzzerdata(RequestBuzzerClass $objBuzzerVehicleRequest) {
        try {
            $getvehicledata = $this->pushbuzzerapi($objBuzzerVehicleRequest);
            if (isset($getvehicledata)) {
                $arrVehDetails = $getvehicledata['Result'];
                $finalResponse = array();
                $finalResponse['result'] = new ResponseBuzzerClass($arrVehDetails);
                $finalResponse['message'] = $getvehicledata['Message'];
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return $finalResponse;
    }

    public function pushbuzzerapi($objGetLatestVehicleRequest) {
        $vehicleno = $objGetLatestVehicleRequest->vehicleno;
        $customerno = $objGetLatestVehicleRequest->customerno;
        $userid = $objGetLatestVehicleRequest->userid;
        $status = $objGetLatestVehicleRequest->status;
        //buzzer status =1 on // buzzer status==0 off  //buzzerstatus ==-1
        if (!empty($vehicleno)) {
            $vehicleid = $this->getvehicleid($customerno, $vehicleno);
        }
        $arr_p = array();
        $arr_p['Result'] = "";
        if ($customerno == 97) {
            date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
        }
        if ($status == 1) {
            //Do You Like To Alarm The Vehicle ?
            //send alert
            $query = "select u.unitno from vehicle as v inner join unit as u on v.uid = u.uid  where v.vehicleid =" . $vehicleid . " AND v.isdeleted=0";
            $record = $this->db->query($query, __FILE__, __LINE__);
            while ($row = $this->db->fetch_array($record)) {
                $unitno = $row['unitno'];
            }
            if (!empty($unitno)) {
                $Que = "UPDATE unit SET  setcom = 1, command='buzz' WHERE unitno='" . $unitno . "' AND customerno=" . $customerno;
                // $record1 = $this->db->query($Que, __FILE__, __LINE__);
                //insert into buzzerlog
                $datavehdata = $this->getvehicledetail($vehicleid, $customerno);
                if (isset($datavehdata)) {
                    $vehicleid = $datavehdata['vehicleid'];
                    $uid = $datavehdata['uid'];
                    $devicelat = $datavehdata['devicelat'];
                    $devicelong = $datavehdata['devicelong'];
                    $today = date("Y-m-d H:i:s");
                    $sql = "INSERT INTO buzzerlog (uid, vehicleid, devicelat,devicelong,customerno,createdby ,createdon,is_api) "
                        . "VALUES ('" . $uid . "','" . $vehicleid . "','" . $devicelat . "','" . $devicelong . "','" . $customerno . "','" . $userid . "','" . $today . "',1)";
                    $this->db->query($sql, __FILE__, __LINE__);
                }
                $arr_p['Status'] = '1';
                $arr_p['Message'] = 'Buzzer Alarm sent successfully';
                $arr_p['Result'] = array("vehicle" => $vehicleno);
            }
        }
        return $arr_p;
    }

    public function getcheckpointstatus($objChkPtStatus) {
        if (isset($objChkPtStatus->vehicleId) && isset($objChkPtStatus->arrChkPtDetails) && count($objChkPtStatus->arrChkPtDetails) > 0) {
            if (isset($objChkPtStatus->customerNo) && $objChkPtStatus->customerNo > 0) {
                if (isset($objChkPtStatus->tripCreated) && $objChkPtStatus->tripCreated == 1) {
                    $this->mapVehicleToChkPts($objChkPtStatus);
                } else {
                    $location = "../../../customer/" . $objChkPtStatus->customerNo . "/reports/chkreport.sqlite";
                    if (file_exists($location)) {
                        $objChkPtStatus = $this->getChkPtData($location, $objChkPtStatus);
                    }
                }
            }
        }
        return $objChkPtStatus;
    }

    public function getHistoricVehSummary($objReqData) {
        if (($objReqData->vehicleId > 0 || $objReqData->vehicleNo != "") && ($objReqData->customerNo > 0)) {
            $objReqData->startDateTime = isset($objReqData->startDateTime) ? $objReqData->startDateTime : today();
            $objReqData->endDateTime = isset($objReqData->endDateTime) ? $objReqData->endDateTime : today();
            if (strtotime($objReqData->endDateTime) >= strtotime($objReqData->startDateTime)) {
                $diffInDays = $this->date_SDiff($objReqData->startDateTime, $objReqData->endDateTime);
                if ($diffInDays <= 15) {
                    $objVehManager = new VehicleManager($objReqData->customerNo);
                    if ($objReqData->vehicleId > 0) {
                        $objRequest = $objVehManager->get_vehicle_details($objReqData->vehicleId);
                    } elseif ($objReqData->vehicleNo != "") {
                        $objReq = $objVehManager->get_vehicle_by_vehno($objReqData->vehicleNo, 1);
                        if ($objReq != NULL) {
                            $objRequest = (object) $objReq[0];
                            $objReqData->vehicleId = $objRequest->vehicleid;
                        }
                    }
                    if (isset($objRequest) && isset($objRequest->unitno)) {
                        $objRequest->interval = isset($objReqData->interval) ? $objReqData->interval : 5;
                        $objRequest->temp_sensors = 2;
                        $objRequest->customerNo = $objReqData->customerNo;
                        $objRequest->startDate = convertDateToFormat($objReqData->startDateTime, speedConstants::DATE_Ymd);
                        $objRequest->startTime = convertDateToFormat($objReqData->startDateTime, speedConstants::TIME_His);
                        $objRequest->endDate = convertDateToFormat($objReqData->endDateTime, speedConstants::DATE_Ymd);
                        $objRequest->endTime = convertDateToFormat($objReqData->endDateTime, speedConstants::TIME_His);
                        //print_r($objRequest);
                        $data = getTripData($objRequest->startDate, $objRequest->endDate, $objRequest->deviceid, $objRequest->interval, $objRequest->startTime, $objRequest->endTime, $objRequest->customerNo, $objRequest->temp_sensors);
                        if (isset($objReqData->customParams['muteRanges']) && !empty($objReqData->customParams['muteRanges'])) {
                            foreach ($objReqData->customParams['muteRanges'] AS $range) {
                                foreach ($data AS $tempKey => $tempData) {
                                    if (strtotime($tempData->starttime) > strtotime($range['starttime'])
                                        && strtotime($tempData->starttime) < strtotime($range['endtime'])) {
                                        unset($data[$tempKey]);
                                    }
                                }
                            }
                        }
                        if (isset($data) && !empty($data)) {
                            $veh_temp_details = getunitdetailsfromvehid($objRequest->vehicleid, $objRequest->customerNo);
                            $unit = getunitdetailspdf($objRequest->customerNo, $objRequest->deviceid);
                            $complianceData[] = create_temp_from_reportcron_daily($data, $unit, $veh_temp_details, $objRequest->temp_sensors, $objReqData);
                            //print_r($complianceData);
                            $compliance = calculateComplaiance($complianceData);
                            $startData = reset($data);
                            $lastData = end($data);
                            $distance = calculateOdometerDistance($startData->odometer, $lastData->odometer);
                            $objReqData->distance = $distance;
                            $objReqData->compliance = $compliance;
                        }
                        //prettyPrint($compliance);
                    }
                } else {
                    $objReqData->days = $diffInDays;
                }
            }
        }
        return $objReqData;
    }

    public function getCheckpointReport($objReqData) {
        //Prepare Reponse
        $arrResponse = array();
        $arrResponse['vehicleNo'] = $objReqData->vehicleNo;
        $arrResponse['startDateTime'] = $objReqData->startDateTime;
        $arrResponse['endDateTime'] = $objReqData->endDateTime;
        $arrResponse['chkPtData'] = array();
        $arrResponse['customParams'] = $objReqData->customParams;
        $objReqData->chkPtId = 0;
        $objReqData->reportType = 0;
        if (($objReqData->vehicleId > 0 || $objReqData->vehicleNo != "") && ($objReqData->customerNo > 0)) {
            $objReqData->startDateTime = isset($objReqData->startDateTime) ? $objReqData->startDateTime : today();
            $objReqData->endDateTime = isset($objReqData->endDateTime) ? $objReqData->endDateTime : today();
            if (strtotime($objReqData->endDateTime) >= strtotime($objReqData->startDateTime)) {
                $diffInDays = $this->date_SDiff($objReqData->startDateTime, $objReqData->endDateTime);
                if ($diffInDays <= 30) {
                    $objVehManager = new VehicleManager($objReqData->customerNo);
                    if ($objReqData->vehicleId > 0) {
                        $objRequest = $objVehManager->get_vehicle_details($objReqData->vehicleId);
                    } elseif ($objReqData->vehicleNo != "") {
                        $objReq = $objVehManager->get_vehicle_by_vehno($objReqData->vehicleNo, 1);
                        $objRequest = (object) $objReq[0];
                        $objRequest->vehicleNo = $objReqData->vehicleNo;
                        $objRequest->vehicleId = $objRequest->vehicleid;
                    }

                    if (isset($objRequest) && isset($objRequest->unitno)) {
                        $objRequest->customerNo = $objReqData->customerNo;
                        $checkpoints = getcheckpoints_cust($objRequest->vehicleid, $objRequest->customerNo);
                        $objRequest->startDate = convertDateToFormat($objReqData->startDateTime, speedConstants::DATE_Ymd);
                        $objRequest->startTime = convertDateToFormat($objReqData->startDateTime, speedConstants::TIME_Hi);
                        $objRequest->endDate = convertDateToFormat($objReqData->endDateTime, speedConstants::DATE_Ymd);
                        $objRequest->endTime = convertDateToFormat($objReqData->endDateTime, speedConstants::TIME_Hi);
                        $objRequest->startDateTime = $objRequest->startDate . " " . $objRequest->startTime;
                        $objRequest->endDateTime = $objRequest->endDate . " " . $objRequest->endTime;
                        $objRequest->checkpointDetails = $checkpoints;
                        $objRequest->chkPtId = $objReqData->chkPtId;
                        $objRequest->reportSpecificCondition = $objReqData->reportType;

                        $objCustomerManager = new CustomerManager();
                        $customer_details = $objCustomerManager->getcustomerdetail_byid($objRequest->customerNo);

                        $checkpointData = getCheckpointReportData($objRequest, $customer_details);

                        if (isset($checkpointData)) {
                            foreach ($checkpointData as $data) {
                                $objChkPtReportResponse = new ChkPtReportResponse($data);
                                $arrResponse['chkPtData'][] = $objChkPtReportResponse;
                            }
                        }
                    }
                }
            }
        }
        return $arrResponse;
    }

    public function getCheckpointList($objGetCheckpointList) {
        try {
            $objChkPtManager = new CheckpointManager($objGetCheckpointList->customerNo);
            $arrResult = $objChkPtManager->getcheckpointsforcustomer();
            $arrCheckpointList["checkpoints"] = array_map(function ($element) {
                return $element->cname;
            }, $arrResult);
            //$arrCheckpointList["checkpoints"] = array_column($arrResult, 'cname');
        } catch (Exception $ex) {
            throw $ex;
        }
        return $arrCheckpointList;
    }

    // </editor-fold>

    public function getDeviceid($unitno, $customerno) {
        $data = array();
        $sql = "SELECT d.deviceid,u.uid,u.vehicleid FROM " . DB_PARENT . ".unit as u left join " . DB_PARENT . ".devices as d on u.uid=d.uid WHERE u.`unitno`='" . $unitno . "' AND u.customerno=" . $customerno;
        $record = $this->db->query($sql, __FILE__, __LINE__);
        while ($row = $this->db->fetch_array($record)) {
            $deviceid = $row['deviceid'];
            $uid = $row['uid'];
            $vehicleid = $row['vehicleid'];
        }
        $data = array(
            'deviceid' => $deviceid,
            'uid' => $uid,
            'vehicleid' => $vehicleid
        );
        return $data;
    }

    public function getvehicleid($customerno, $vehicleno) {
        $vehicleno = str_replace(' ', '', $vehicleno);
        $sql = "select v.vehicleid
        from vehicle as v
        INNER JOIN devices as d ON d.uid = v.uid
        INNER JOIN unit as u ON d.uid = u.uid
        where v.customerno='" . $customerno . "' AND u.customerno='" . $customerno . "' AND d.customerno='" . $customerno . "' AND REPLACE(`vehicleno`, ' ','') LIKE '%" . $vehicleno . "%' AND u.trans_statusid NOT IN (10,22) AND v.isdeleted = 0 ";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        while ($row = $this->db->fetch_array($record)) {
            $vehicleid = $row['vehicleid'];
        }
        return $vehicleid;
    }

    public function getvehicledetail($vehicleid, $customerno) {
        $sql = "SELECT v.vehicleid,v.uid, v.vehicleno,u.unitno,d.deviceid,d.lastupdated,d.devicelat, d.devicelong, fl.fid
            FROM vehicle as v
            INNER JOIN devices as d ON d.uid = v.uid
            INNER JOIN unit as u ON d.uid = u.uid
			LEFT JOIN freezelog fl ON fl.uid = v.uid AND fl.isdeleted = 0
            WHERE v.customerno='" . $customerno . "' AND v.isdeleted=0 AND v.vehicleid ='" . $vehicleid . "' ORDER BY d.lastupdated DESC";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $data = array();
        $rowcount = $this->db->num_rows($record);
        if ($rowcount > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $data = array(
                    "vehicleid" => $row['vehicleid'],
                    "devicelat" => $row['devicelat'],
                    "devicelong" => $row['devicelong'],
                    "uid" => $row['uid'],
                    "deviceid" => $row['deviceid'],
                    "unitno" => $row['unitno'],
                    "freezeId" => $row['fid']
                );
            }
            return $data;
        }
        return null;
    }

    public function getquicksharetextapi($objGetLatestVehicleRequest) {
        $vehicleno = $objGetLatestVehicleRequest->vehicleno;
        $customerno = $objGetLatestVehicleRequest->customerno;
        $userid = $objGetLatestVehicleRequest->userid;
        $username = $objGetLatestVehicleRequest->realname;
        //buzzer status =1 on // buzzer status==0 off  //buzzerstatus ==-1
        if (!empty($vehicleno)) {
            $vehicleid = $this->getvehicleid($customerno, $vehicleno);
        }
        $arr_p = array();
        $arr_p['Result'] = '';
        if ($customerno == 97) {
            date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
        }

        $objVehicleDetails = $this->get_vehicle($vehicleid, $customerno);
        if (isset($objVehicleDetails)) {
            $vehicleno = $objVehicleDetails->vehicleno;
            $usegeolocation = 1;
            $strlocation = $this->location($objVehicleDetails->devicelat, $objVehicleDetails->devicelong, $usegeolocation, $customerno);
            /*
             * TODO:
             * Logic to generate tiny url for passed vehicle.
             * Expiry time 1 hr.
             * $url = "http://speed.elixiatech.com";
             */

            $quickShareText = api::$SMS_TEMPLATE_FOR_QUICK_SHARE;
            $quickShareText = str_replace("{{USERNAME}}", $username, $quickShareText);
            $quickShareText = str_replace("{{VEHICLENO}}", $vehicleno, $quickShareText);
            $quickShareText = str_replace("{{LOCATION}}", $strlocation, $quickShareText);
            //$quickShareText = str_replace("{{URL}}", $url, $quickShareText);
            $arr_p['Status'] = "1";
            $arr_p['Message'] = $quickShareText;
            $arr_p['Result'] = array("vehicle" => $vehicleno, "text" => $quickShareText);
        } else {
            $arr_p['Status'] = "0";
            $arr_p['Error'] = "Unable to fetch vehicle details.";
        }
        return $arr_p;
    }

    public function devicesformapping_byId($srhstring, $customerno) {
        $srhstring = "%" . $srhstring . "%";
        $devices = Array();
        $vehicle_ses = '';
        $list = " vehicle.vehicleid
            ,vehicle.customerno
            ,vehicle.vehicleno
            ,vehicle.kind
            ,vehicle.overspeed_limit
            ,unit.uid
            ,unit.unitno
            ,`group`.groupname
            ,driver.drivername
            ,driver.driverphone
            ,devices.deviceid
            ,devices.devicelat
            ,devices.devicelong
            ,vehicle.lastupdated
            ,devices.ignition
            ,devices.status
            ,vehicle.curspeed
            ,vehicle.stoppage_flag
            ,vehicle.stoppage_transit_time
            ,devices.directionchange ";

        $vehicle_ses = " AND vehicle.kind <> 'Warehouse' ";

        $Query = "SELECT $list FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                INNER JOIN driver ON vehicle.driverid = driver.driverid
                LEFT JOIN `group` on vehicle.groupid = `group`.groupid
                where (devices.customerno=%d )
                AND unit.trans_statusid NOT IN (10,22)
                AND vehicle.isdeleted = 0
                AND vehicleno LIKE '%s' $vehicle_ses ";
        $Query .= " ORDER BY vehicle.vehicleno";
        $devicesQuery = sprintf($Query, $customerno, Sanitise::String($srhstring));
        //die();
        $record = $this->db->query($devicesQuery, __FILE__, __LINE__);
        //$this->db->executeQuery($devicesQuery);
        // print("<pre>"); print_r($record);die;
        if ($this->db->num_rows($record) > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $device = new stdClass();
                if ($row['uid'] > 0) {
                    $device->vehicleno = $row['vehicleno'];
                    $device->vehicleid = $row['vehicleid'];
                    $device->deviceid = $row['deviceid'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->drivername = $row['drivername'];
                    $device->driverphone = $row['driverphone'];
                    $device->curspeed = $row['curspeed'];
                    $device->overspeed_limit = $row['overspeed_limit'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->type = $row['kind'];
                    $device->ignition = $row['ignition'];
                    $device->status = $row['status'];
                    $device->stoppage_flag = $row['stoppage_flag'];
                    $device->stoppage_transit_time = $row['stoppage_transit_time'];
                    $device->directionchange = $row['directionchange'];
                    $device->groupname = $row['groupname'];
                    $devices[] = $device;
                }
            }
            return $devices;
        }
        return null;
    }

    public function getlocationreportapi($startdatetime, $enddatetime, $deviceid, $unitno, $interval = null, $distance = null, $customerno) {
        $Shour = date("H:i", strtotime($startdatetime));
        $Ehour = date("H:i", strtotime($enddatetime));
        $totaldays = $this->gendays($startdatetime, $enddatetime);
        $days = array();
        $count = count($totaldays);
        $endelement = end($totaldays);
        $firstelement = $totaldays[0];
        $unit = $this->getunitdetails($deviceid, $customerno);
        $acinvertval = $this->getacinvertval($unitno, $customerno);
        if (isset($totaldays)) {
            foreach ($totaldays as $userdate) {
                $location = "../../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                if (!file_exists($location)) {
                    continue;
                } else {
                    $data = $this->get_location_data($location, $count, $userdate, $firstelement, $endelement, $deviceid, $interval, $distance, $Shour, $Ehour);
                    if ($data != NULL && count($data) > 0) {
                        $days = array_merge($days, $data);
                    }
                }
            }
        }

        if ($days != NULL && count($days) > 0) {
            $finalreport = $this->create_location_from_report($days, $unit, $endelement, $acinvertval, $customerno);
        } else {
            $finalreport = null;
        }
        return $finalreport;
    }

    public function create_location_from_report($datarows, $vehicle, $lastdate = null, $acinvertval, $customerno) {
        $display = '';
        $old_distance = '';
        $oldtemp1 = '';
        $oldtemp2 = '';
        $oldtemp3 = '';
        $oldtemp4 = '';
        $cumulativedistance = 0;
        $currentdistance = 0;
        $prevdistance = 0;
        $tempconversion = new TempConversion();
        $tempconversion->unit_type = $vehicle->get_conversion;
        $tempconversion->use_humidity = 0;

        $custdata = $this->get_customerdetails($customerno);
        $use_genset_sensor = $custdata['use_genset_sensor'];
        $temp_sensors = $custdata['temp_sensors'];
        $use_ac_sensor = $custdata['use_ac_sensor'];
        if (isset($datarows)) {
            foreach ($datarows as $change) {
                $locationdata = array();
                $comparedate = date('d-m-Y', strtotime($change->endtime));
                $today = date('d-m-Y', strtotime('Now'));

                if (strtotime($lastdate) != strtotime($comparedate)) {
                    if ($today == $comparedate) {
                        $todays = date('Y-m-d');
                        $todayhms = date('Y-m-d H:i:s');
                        $to_time = strtotime("$todayhms");
                        $from_time = strtotime("$todays 00:00:00");
                        $totalminute = round(abs($to_time - $from_time) / 60, 2);
                    }
                    $lastdate = date('d-m-Y', strtotime($change->endtime));
                    $prevdistance = 0;
                }

                $currentdate = date("Y-m-d H:i:s");
                $currentdate = substr($currentdate, '0', 11);

                $lasttime = date(speedConstants::DEFAULT_TIME, strtotime($change->lastupdated));
                $devicelat = isset($change->devicelat) ? $change->devicelat : '0';
                $devicelong = isset($change->devicelong) ? $change->devicelong : '0';
                $curspeed = isset($change->curspeed) ? $change->curspeed : "";
                $locationdata['time'] = "$lasttime";
                $locationdata['devicelat'] = "$devicelat";
                $locationdata['devicelong'] = "$devicelong";
                $locationdata['curspeed'] = "$curspeed";
                $locationdata['tempsensor1'] = "";
                $locationdata['tempsensor2'] = "";
                $locationdata['tempsensor3'] = "";
                $locationdata['tempsensor4'] = "";
                //$locationdata['genset'] = "";
                $locationdata['distance'] = "";
                $locationdata['cumulative_distance'] = "";
                $locationdata['digitalstatus'] = "";
                $locationdata['status'] = '';
                $displaytemp1 = '';
                $displaytemp2 = '';
                $displaytemp3 = '';
                $displaytemp4 = '';

                if ($temp_sensors == 1) {
                    $temp = 'Not Active';
                    $s = "analog" . $vehicle->tempsen1;
                    if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                        $tempconversion->rawtemp = $change->$s;
                        $temp = getTempUtil($tempconversion);
                    } else {
                        $temp = '-';
                    }

                    if ($temp == '-') {
                        $temp = $oldtemp1;
                    }

                    if ($temp != '-' && $temp != "Not Active") {
                        $displaytemp1 = $temp;
                    } else {
                        $displaytemp1 = $temp;
                    }

                    $oldtemp1 = $temp;
                    $locationdata['tempsensor1'] = "$displaytemp1";
                }
                if ($temp_sensors == 2) {
                    $temp1 = 'Not Active';
                    $temp2 = 'Not Active';

                    $s = "analog" . $vehicle->tempsen1;
                    if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                        $tempconversion->rawtemp = $change->$s;
                        $temp1 = getTempUtil($tempconversion);
                    } else {
                        $temp1 = '-';
                    }

                    $s = "analog" . $vehicle->tempsen2;
                    if ($vehicle->tempsen2 != 0 && $change->$s != 0) {
                        $tempconversion->rawtemp = $change->$s;
                        $temp2 = getTempUtil($tempconversion);
                    } else {
                        $temp2 = '-';
                    }

                    if ($temp1 == '-') {
                        $temp1 = $oldtemp1;
                    }
                    if ($temp2 == '-') {
                        $temp2 = $oldtemp2;
                    }

                    if ($temp1 != '-' && $temp1 != "Not Active") {
                        $displaytemp1 = $temp1;
                    } else {
                        $displaytemp1 = $temp1;
                    }

                    if ($temp2 != '-' && $temp2 != "Not Active") {
                        $displaytemp2 = $temp2;
                    } else {
                        $displaytemp2 = $temp2;
                    }

                    $oldtemp1 = $temp1;
                    $oldtemp2 = $temp2;

                    $locationdata['tempsensor1'] = "$displaytemp1";
                    $locationdata['tempsensor2'] = "$displaytemp2";
                }
                if ($temp_sensors == 3) {
                    $temp1 = 'Not Active';
                    $temp2 = 'Not Active';
                    $temp3 = 'Not Active';

                    $s = "analog" . $vehicle->tempsen1;
                    if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                        $tempconversion->rawtemp = $change->$s;
                        $temp1 = getTempUtil($tempconversion);
                    } else {
                        $temp1 = '-';
                    }

                    $s = "analog" . $vehicle->tempsen2;
                    if ($vehicle->tempsen2 != 0 && $change->$s != 0) {
                        $tempconversion->rawtemp = $change->$s;
                        $temp2 = getTempUtil($tempconversion);
                    } else {
                        $temp2 = '-';
                    }

                    $s = "analog" . $vehicle->tempsen3;
                    if ($vehicle->tempsen3 != 0 && $change->$s != 0) {
                        $tempconversion->rawtemp = $change->$s;
                        $temp3 = getTempUtil($tempconversion);
                    } else {
                        $temp3 = '-';
                    }

                    if ($temp1 == '-') {
                        $temp1 = $oldtemp1;
                    }
                    if ($temp2 == '-') {
                        $temp2 = $oldtemp2;
                    }
                    if ($temp3 == '-') {
                        $temp3 = $oldtemp3;
                    }

                    if ($temp1 != '-' && $temp1 != "Not Active") {
                        $displaytemp1 = $temp1;
                    } else {
                        $displaytemp1 = $temp1;
                    }

                    if ($temp2 != '-' && $temp2 != "Not Active") {
                        $displaytemp2 = $temp2;
                    } else {
                        $displaytemp2 = $temp2;
                    }

                    if ($temp3 != '-' && $temp3 != "Not Active") {
                        $displaytemp3 = $temp3;
                    } else {
                        $displaytemp3 = $temp3;
                    }
                    $oldtemp1 = $temp1;
                    $oldtemp2 = $temp2;
                    $oldtemp3 = $temp3;

                    $locationdata['tempsensor1'] = "$displaytemp1";
                    $locationdata['tempsensor2'] = "$displaytemp2";
                    $locationdata['tempsensor3'] = "$displaytemp3";
                }
                if ($temp_sensors == 4) {
                    $temp1 = 'Not Active';
                    $temp2 = 'Not Active';
                    $temp3 = 'Not Active';
                    $temp4 = 'Not Active';

                    $s = "analog" . $vehicle->tempsen1;
                    if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                        $tempconversion->rawtemp = $change->$s;
                        $temp1 = getTempUtil($change->$s);
                    } else {
                        $temp1 = '-';
                    }

                    $s2 = "analog" . $vehicle->tempsen2;
                    if ($vehicle->tempsen2 != 0 && $change->$s2 != 0) {
                        $tempconversion->rawtemp = $change->$s2;
                        $temp2 = getTempUtil($tempconversion);
                    } else {
                        $temp2 = '-';
                    }

                    $s3 = "analog" . $vehicle->tempsen3;
                    if ($vehicle->tempsen3 != 0 && $change->$s3 != 0) {
                        $tempconversion->rawtemp = $change->$s3;
                        $temp3 = getTempUtil($tempconversion);
                    } else {
                        $temp3 = '-';
                    }

                    $s4 = "analog" . $vehicle->tempsen4;
                    if ($vehicle->tempsen4 != 0 && $change->$s4 != 0) {
                        $tempconversion->rawtemp = $change->$s4;
                        $temp4 = getTempUtil($tempconversion);
                    } else {
                        $temp4 = '-';
                    }

                    if ($temp1 == '-') {
                        $temp1 = $oldtemp1;
                    }
                    if ($temp2 == '-') {
                        $temp2 = $oldtemp2;
                    }
                    if ($temp3 == '-') {
                        $temp3 = $oldtemp3;
                    }
                    if ($temp4 == '-') {
                        $temp4 = $oldtemp4;
                    }

                    if ($temp1 != '-' && $temp1 != "Not Active") {
                        $displaytemp1 = $temp1;
                    } else {
                        $displaytemp1 = $temp1;
                    }

                    if ($temp2 != '-' && $temp2 != "Not Active") {
                        $displaytemp2 = $temp2;
                    } else {
                        $displaytemp2 = $temp2;
                    }

                    if ($temp3 != '-' && $temp3 != "Not Active") {
                        $displaytemp3 = $temp3;
                    } else {
                        $displaytemp3 = $temp3;
                    }

                    if ($temp4 != '-' && $temp4 != "Not Active") {
                        $displaytemp4 = $temp4;
                    } else {
                        $displaytemp4 = $temp4;
                    }

                    $oldtemp1 = $temp1;
                    $oldtemp2 = $temp2;
                    $oldtemp3 = $temp3;
                    $oldtemp4 = $temp4;

                    $locationdata['tempsensor1'] = "$displaytemp1";
                    $locationdata['tempsensor2'] = "$displaytemp2";
                    $locationdata['tempsensor3'] = "$displaytemp3";
                    $locationdata['tempsensor4'] = "$displaytemp4";
                }
                if ($use_genset_sensor == 1) {
                    $locationdata['digitalstatus'] = "";
                    if ($acinvertval == 1) {
                        if ($change->digitalio == 0) {
                            $gensetstatus = 0; //0-OFF
                        } else {
                            $gensetstatus = 1; //1 - ON
                        }
                    } else {
                        if ($change->digitalio == 0) {
                            $gensetstatus = 0; //0- ON
                        } else {
                            $gensetstatus = 1; // 1- OFF
                        }
                    }
                    $locationdata['digitalstatus'] = "$gensetstatus";
                }

                // Add New column for status running or idle - Ganesh

                if ($change->ignition == 1 && round($change->distancecumulative, 1) > 0 && round($change->distancecumulative, 1) != round($old_distance, 1)) {
                    $status = "Running";
                } else {
                    if ($change->ignition == 1) {
                        $status = "Idle - Ignition On";
                    } else {
                        $status = "Idle";
                    }
                }
                $locationdata['status'] = "$status";
                //show compressor status on or off

                if ($use_ac_sensor == 1) {
                    $locationdata['digitalstatus'] = "";
                    if ($acinvertval == 1) {
                        if ($change->digitalio == 0) {
                            $acsensorstatus = 1; //1 - OFF
                        } else {
                            $acsensorstatus = 0; // 0 - ON
                        }
                    } else {
                        if ($change->digitalio == 0) {
                            $acsensorstatus = 0; // 0 - ON
                        } else {
                            $acsensorstatus = 1; //1 - OFF
                        }
                    }
                    $locationdata['digitalstatus'] = "$acsensorstatus";
                }

                if ($customerno != 96 && $customerno != 66) {
                    $curdist = isset($change->distancecumulative) ? $change->distancecumulative : 0;
                    $prevdist = isset($prevdistance) ? $prevdistance : 0;
                    $totaldist = $curdist - $prevdist;
                    $distance = $totaldist;
                    $currentdistance = $change->distancecumulative;
                    $cumulativedistance = $currentdistance - $prevdistance + $cumulativedistance;
                }
                //$old_location = $location;
                $old_distance = $change->distancecumulative;
                $prevdistance = $currentdistance;

                $locationdata['distance'] = "$distance";
                $locationdata['cumulative_distance'] = "$cumulativedistance";
                $data[] = $locationdata;
            }
        }
        return $data;
    }

    public function get_customerdetails($customerno) {
        $data = array();
        $query = "SELECT * FROM `customer` WHERE `customerno` = " . $customerno;
        $unitQuery = sprintf($query);
        $record = $this->db->query($unitQuery, __FILE__, __LINE__);
        if ($this->db->num_rows($record) > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $data = array(
                    'temp_sensors' => $row['temp_sensors'],
                    'use_genset_sensor' => $row['use_genset_sensor'],
                    'use_ac_sensor' => $row['use_ac_sensor']
                );
            }
        }
        return $data;
    }

    public function get_location_data($location, $count, $userdate, $firstelement, $endelement, $deviceid, $interval, $distance, $Shour, $Ehour) {
        $data = null;
        $location = "sqlite:" . $location;
        if ($count > 1 && $userdate != $endelement && $userdate == $firstelement) {
            $data = $this->getlocation_fromsqlite($location, $deviceid, $interval, $distance, $Shour, Null, $userdate);
        } elseif ($count > 1 && $userdate == $endelement) {
            $data = $this->getlocation_fromsqlite($location, $deviceid, $interval, $distance, Null, $Ehour, $userdate);
        } elseif ($count == 1) {
            $data = $this->getlocation_fromsqlite($location, $deviceid, $interval, $distance, $Shour, $Ehour, $userdate);
        } else {
            $data = $this->getlocation_fromsqlite($location, $deviceid, $interval, $distance, Null, Null, $userdate);
        }
        return $data;
    }

    public function getlocation_fromsqlite($location, $deviceid, $interval, $distance, $Shour, $Ehour, $userdate) {
        $devices = array();
        $query = "SELECT unithistory.digitalio,devicehistory.lastupdated, devicehistory.ignition, vehiclehistory.odometer,vehiclehistory.curspeed,vehiclehistory.vehicleid,devicehistory.devicelat, devicehistory.devicelong, analog1, analog2, analog3, analog4
              from devicehistory
              INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
              INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.deviceid= $deviceid AND devicehistory.status!='F'";
        if ($Shour != Null) {
            $query .= " AND devicehistory.lastupdated > '$userdate $Shour'";
        }
        if ($Ehour != Null) {
            $query .= " AND devicehistory.lastupdated < '$userdate $Ehour'";
        }
        $query .= " order by devicehistory.lastupdated ASC";
        try {
            $database = new PDO($location);
            $query1 = "SELECT max(odometer) as odometerm from vehiclehistory";
            $result1 = $database->query($query1);
            foreach ($result1 as $row) {
                $ODOMETER = $row['odometerm'];
            }

            $result = $database->query($query);
            if (isset($result) && $result != "") {
                $lastupdated = "";
                $distanceval = "";
                foreach ($result as $row) {
                    if ($lastupdated == "") {
                        $lastupdated = $row["lastupdated"];
                    }
                    if ($distanceval == "") {
                        $distanceval = $row["odometer"];
                        $device = new stdClass();
                        $device->lastupdated = $row['lastupdated'];
                        $device->starttime = $row['lastupdated'];
                        $device->endtime = $row['lastupdated'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $device->curspeed = $row["curspeed"];
                        $device->deviceid = $row["vehicleid"];
                        $device->analog1 = $row["analog1"];
                        $device->analog2 = $row["analog2"];
                        $device->analog3 = $row["analog3"];
                        $device->analog4 = $row["analog4"];
                        $device->digitalio = $row["digitalio"];
                        $device->ignition = $row["ignition"];
                        if ($row['odometer'] < $distanceval) {
                            $row['odometer'] = $ODOMETER + $row['odometer'];
                        }
                        $device->distancecumulative = round(($row["odometer"] - $distanceval) / 1000, 2);
                        $devices[] = $device;
                    } else {
                        if ($interval != null) {
                            if ($interval == 1) {
                                $device = new stdClass();
                                $device->lastupdated = $row['lastupdated'];
                                $device->starttime = $row['lastupdated'];
                                $device->endtime = $row['lastupdated'];
                                $device->devicelat = $row['devicelat'];
                                $device->devicelong = $row['devicelong'];
                                $device->curspeed = $row["curspeed"];
                                $device->deviceid = $row["vehicleid"];
                                $device->analog1 = $row["analog1"];
                                $device->analog2 = $row["analog2"];
                                $device->analog3 = $row["analog3"];
                                $device->analog4 = $row["analog4"];
                                $device->digitalio = $row["digitalio"];
                                $device->ignition = $row["ignition"];
                                if ($row['odometer'] < $distanceval) {
                                    $row['odometer'] = $ODOMETER + $row['odometer'];
                                }
                                $device->distancecumulative = round(($row["odometer"] - $distanceval) / 1000, 2);
                                $devices[] = $device;
                                $lastupdated = $row["lastupdated"];
                            } elseif (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) > $interval) {
                                $device = new stdClass();
                                $device->lastupdated = $row['lastupdated'];
                                $device->starttime = $row['lastupdated'];
                                $device->endtime = $row['lastupdated'];
                                $device->devicelat = $row['devicelat'];
                                $device->devicelong = $row['devicelong'];
                                $device->curspeed = $row["curspeed"];
                                $device->deviceid = $row["vehicleid"];
                                $device->analog1 = $row["analog1"];
                                $device->analog2 = $row["analog2"];
                                $device->analog3 = $row["analog3"];
                                $device->analog4 = $row["analog4"];
                                $device->digitalio = $row["digitalio"];
                                $device->ignition = $row["ignition"];
                                if ($row['odometer'] < $distanceval) {
                                    $row['odometer'] = $ODOMETER + $row['odometer'];
                                }
                                $device->distancecumulative = round(($row["odometer"] - $distanceval) / 1000, 2);
                                $devices[] = $device; //ganesh - added
                                $lastupdated = $row["lastupdated"];
                            }
                        }
                    }
                    if ($distance != null && $distance != "") {
                        if ($row["odometer"] - $distanceval > $distance * 1000) {
                            $device = new stdClass();
                            $device->lastupdated = $row['lastupdated'];
                            $device->starttime = $row['lastupdated'];
                            $device->endtime = $row['lastupdated'];
                            $device->devicelat = $row['devicelat'];
                            $device->devicelong = $row['devicelong'];
                            $device->curspeed = $row["curspeed"];
                            $device->deviceid = $row["vehicleid"];
                            $device->analog1 = $row["analog1"];
                            $device->analog2 = $row["analog2"];
                            $device->analog3 = $row["analog3"];
                            $device->analog4 = $row["analog4"];
                            $device->digitalio = $row["digitalio"];
                            $device->ignition = $row["ignition"];
                            if ($row['odometer'] < $distanceval) {
                                $row['odometer'] = $ODOMETER + $row['odometer'];
                            }
                            $device->distancecumulative = round(($row["odometer"] - $distanceval) / 1000, 2);
                            $devices[] = $device;
                            if ($row["ignition"] == "1") {
                                $distanceval = $row["odometer"];
                            }
                        }
                    }
                }
            } else {
                $device = new stdClass();
                $device->lastupdated = isset($row['lastupdated']) ? $row['lastupdated'] : '';
                $device->starttime = isset($row['lastupdated']) ? $row['lastupdated'] : '';
                $device->endtime = isset($row['lastupdated']) ? $row['lastupdated'] : '';
                $device->devicelat = isset($row['devicelat']) ? $row['devicelat'] : 0;
                $device->devicelong = isset($row['devicelong']) ? $row['devicelong'] : 0;
                $device->curspeed = isset($row["curspeed"]) ? $row["curspeed"] : '';
                $device->deviceid = isset($row["vehicleid"]) ? $row["vehicleid"] : '';
                $device->analog1 = isset($row["analog1"]) ? $row["analog1"] : '';
                $device->analog2 = isset($row["analog2"]) ? $row["analog2"] : '';
                $device->analog3 = isset($row["analog3"]) ? $row["analog3"] : '';
                $device->analog4 = isset($row["analog4"]) ? $row["analog4"] : '';
                $device->digitalio = isset($row["digitalio"]) ? $row["digitalio"] : '';
                $device->ignition = isset($row["ignition"]) ? $row["ignition"] : '';
                if (isset($row['odometer'])) {
                    if ($row['odometer'] < $distanceval) {
                        $row['odometer'] = $ODOMETER + $row['odometer'];
                    }
                    $device->distancecumulative = round(($row["odometer"] - $distanceval) / 1000, 2);
                } else {
                    $device->distancecumulative = 0;
                }
                $devices[] = $device;
            }
        } catch (PDOException $e) {
            die($e);
        }

        return $devices;
    }

    public function getacinvertval($unitno, $customerno) {
        $Query = 'SELECT acsensor,is_ac_opp FROM unit
            WHERE customerno = %d AND unitno = %d';
        $unitQuery = sprintf($Query, $customerno, Sanitise::Long($unitno));
        $record = $this->db->query($unitQuery, __FILE__, __LINE__);
        if ($this->db->num_rows($record) > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $acopp['0'] = $row['is_ac_opp'];
                $acopp['1'] = $row['acsensor'];
                return $acopp;
            }
        }
        return NULL;
    }

    public function getunitdetails($deviceid, $customerno) {
        $Query = 'SELECT vehicle.vehicleno,
                vehicle.fuel_min_volt,
                vehicle.fuel_max_volt,
                vehicle.fuelcapacity,
                vehicle.max_voltage
                ,vehicle.kind
            ,vehicle.hum_min,
            vehicle.hum_max,
            unit.unitno,
             unit.tempsen1,
              unit.tempsen2,
              unit.tempsen3,
              unit.tempsen4
            ,unit.n1,unit.n2,
             unit.n3,unit.n4,
             unit.analog1,
             unit.analog2,
             unit.analog3,
             unit.analog4,
             unit.get_conversion
            ,unit.humidity,
            unit.acsensor,
            unit.fuelsensor,
            unit.is_ac_opp,
            unit.uid,
            vehicle.vehicleid
            ,unit.isDoorExt
            ,unit.is_door_opp
            FROM unit
            INNER JOIN devices ON devices.uid = unit.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            WHERE unit.customerno = %d AND devices.deviceid = %d';
        $unitQuery = sprintf($Query, $customerno, Sanitise::Long($deviceid));
        $record = $this->db->query($unitQuery, __FILE__, __LINE__);
        if ($this->db->num_rows($record) > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $unit = new stdClass();
                $unit->uid = $row['uid'];
                $unit->vehicleid = $row['vehicleid'];
                $unit->kind = $row['kind'];
                $unit->unitno = $row['unitno'];
                $unit->vehicleno = $row['vehicleno'];
                $unit->tempsen1 = $row['tempsen1'];
                $unit->tempsen2 = $row['tempsen2'];
                $unit->tempsen3 = $row['tempsen3'];
                $unit->tempsen4 = $row['tempsen4'];
                $unit->n1 = $row['n1'];
                $unit->n2 = $row['n2'];
                $unit->n3 = $row['n3'];
                $unit->n4 = $row['n4'];
                $unit->analog1 = $row['analog1'];
                $unit->analog2 = $row['analog2'];
                $unit->analog3 = $row['analog3'];
                $unit->analog4 = $row['analog4'];
                $unit->get_conversion = $row['get_conversion'];
                $unit->humidity = $row['humidity'];
                $unit->acsensor = $row['acsensor'];
                $unit->fuelsensor = $row['fuelsensor'];
                $unit->fuelcapacity = $row['fuelcapacity'];
                $unit->maxvoltage = $row['max_voltage'];
                $unit->hum_min = $row['hum_min'];
                $unit->hum_max = $row['hum_max'];
                $unit->fuel_min_volt = $row['fuel_min_volt'];
                $unit->fuel_max_volt = $row['fuel_max_volt'];
                $unit->isacopp = $row['is_ac_opp'];
                $unit->isDoorExt = $row['isDoorExt'];
                $unit->is_door_opp = $row['is_door_opp'];
                return $unit;
            }
        }
        return NULL;
    }

    public function updateMdlzDumpShipmentno($objReqData) {
        if (($objReqData->vehicleId > 0 || $objReqData->vehicleNo != "") && ($objReqData->customerNo > 0)) {
            $objReqData->startDateTime = isset($objReqData->startDateTime) ? $objReqData->startDateTime : today();
            $objReqData->endDateTime = isset($objReqData->endDateTime) ? $objReqData->endDateTime : today();
            if (strtotime($objReqData->endDateTime) >= strtotime($objReqData->startDateTime)) {
                $objVehManager = new VehicleManager($objReqData->customerNo);
                if ($objReqData->vehicleId > 0) {
                    $objRequest = $objVehManager->get_vehicle_details($objReqData->vehicleId);
                } elseif ($objReqData->vehicleNo != "") {
                    $objReq = $objVehManager->get_vehicle_by_vehno($objReqData->vehicleNo, 1);
                    if ($objReq != NULL) {
                        $objRequest = (object) $objReq[0];
                        $objReqData->vehicleId = $objRequest->vehicleid;
                    }
                }
                if (isset($objRequest) && isset($objRequest->unitno)) {
                    $objRequest->startDateTime = $objReqData->startDateTime;
                    $objRequest->endDateTime = $objReqData->endDateTime;
                    $objRequest->shipmentno = $objReqData->shipmentNo;
                    $objRequest->customerno = $objReqData->customerNo;
                    $data = update_Dump_Shipmentno($objRequest);
                }
            }
        }
        return $objReqData;
    }

    public function getVehicleRouteSummary($objGetVehicleList) {
        $startDate = $endDate = date('Y-m-d');
        try {
            $objVehicleManager = new RouteManager($objGetVehicleList->customerno);
            $arrResult = $objVehicleManager->getRouteWiseData(0, $startDate, $endDate, $objGetVehicleList->customerno, NULL, $objGetVehicleList->vehicleid, 1);
        } catch (Exception $ex) {
            throw $ex;
        }
        return $arrResult;
    }

    //
    // <editor-fold defaultstate="collapsed" desc="Helper functions">

    public function getvehicledata($objGetLatestVehicleRequest) {
        $tempConversion = new TempConversion();
        //echo "<pre>"; print_r($objGetLatestVehicleRequest); die();
        //Prepare parameters

        $pdo = $this->db->CreatePDOConn();
        $sp_params = "'" . $objGetLatestVehicleRequest->pageindex . "'"
        . ",'" . $objGetLatestVehicleRequest->pagesize . "'"
        . "," . $objGetLatestVehicleRequest->customerno . ""
        . "," . $objGetLatestVehicleRequest->iswarehouse . ""
        . ",'" . $objGetLatestVehicleRequest->searchstring . "'"
        . ",'" . $objGetLatestVehicleRequest->groupid . "'"
        . ",'" . $objGetLatestVehicleRequest->userkey . "'"
        . ",'" . $objGetLatestVehicleRequest->isRequiredThirdParty . "'";

        $queryCallSP = "CALL " . speedConstants::SP_GET_VEHICLEWAREHOUSE_DETAILS_VTS . "($sp_params)";
        $outputResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $this->db->ClosePDOConn($pdo);
        $json_p = array();
        $x = 0;
        $totalVehicleCount = 0;
        if (isset($outputResult) && !empty($outputResult) && is_array($outputResult)) {
            foreach ($outputResult as $k => &$row) {
                if ($totalVehicleCount == 0) {
                    $totalVehicleCount = $row['recordCount'];
                }
                //if ($firstgroup == '' || (in_array($row['veh_grpid'], $groupids))) {
                $json_p[$x]['vehicleid'] = (int) $row['vehicleid'];
                $json_p[$x]['vehicleno'] = $row['vehicleno'];
                $json_p[$x]['unitno'] = $row['unitno'];
                $json_p[$x]['groupid'] = (int) $row['groupid'];
                $json_p[$x]['digitalstatus'] = "";
                $json_p[$x]['kind'] = $row['kind'];
                if (isset($objGetLatestVehicleRequest->isLocationEnabled) && $objGetLatestVehicleRequest->isLocationEnabled == 1 || $row['use_geolocation'] == 1) {
                    $json_p[$x]['location'] = $this->get_location_bylatlong($row['devicelat'], $row['devicelong'], $row['customer_no'], $row['use_geolocation']);
                } else {
                    $json_p[$x]['location'] = '';
                }

                $json_p[$x]['lastupdated'] = $this->getduration1($row['lastupdated']);
                $json_p[$x]['driverphone'] = $row['driverphone'];
                $json_p[$x]['simcardno'] = $row['simcardno'];
                $json_p[$x]['sequenceno'] = $row['sequenceno'];
                $json_p[$x]['isFrozen'] = isset($row['is_freeze']) ? (int) $row['is_freeze'] : 0;
                $shortUrl = "http://speed.elixiatech.com/modules/map/map.php?userkey=" . $objGetLatestVehicleRequest->userkey . "&vehicleno=" . $row['vehicleno'] . "";
                $objShort = new ShorturlManager();

                $shortUrl = $objShort->urlToShortCode($shortUrl);
                $json_p[$x]['shorturl'] = "http://speed.elixiatech.com/modules/shorturl/r.php?c=" . $shortUrl;
                $tempConversion->unit_type = $row['get_conversion'];
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

                //<editor-fold defaultstate="collapsed" desc="Check for temperature conflict">
                if (isset($row['temp_sensors'])) {
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
                                    //$temp4 = $this->gettemplist($analogValue, $row['use_humidity']);
                                    $tempConversion->use_humidity = $row['use_humidity'];
                                    $tempConversion->rawtemp = $analogValue;
                                    $temp4 = getTempUtil($tempConversion);
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
                                    //$temp3 = $this->gettemplist($analogValue, $row['use_humidity']);
                                    $tempConversion->use_humidity = $row['use_humidity'];
                                    $tempConversion->rawtemp = $analogValue;
                                    $temp3 = getTempUtil($tempConversion);
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
                                    //$temp2 = $this->gettemplist($analogValue, $row['use_humidity']);
                                    $tempConversion->use_humidity = $row['use_humidity'];
                                    $tempConversion->rawtemp = $analogValue;
                                    $temp2 = getTempUtil($tempConversion);
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
                                    //$temp1 = $this->gettemplist($analogValue, $row['use_humidity']);
                                    $tempConversion->use_humidity = $row['use_humidity'];
                                    $tempConversion->rawtemp = $analogValue;
                                    $temp1 = getTempUtil($tempConversion);
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
                                    //$temp1 = $this->gettemplist($analogValue, $row['use_humidity']);
                                    $tempConversion->use_humidity = $row['use_humidity'];
                                    $tempConversion->rawtemp = $analogValue;
                                    $temp1 = getTempUtil($tempConversion);
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
                    $json_p[$x]['temp1'] = ($temp1 != "") ? (double) $temp1 : "";
                    $json_p[$x]['temp2'] = ($temp2 != "") ? (double) $temp2 : "";
                    $json_p[$x]['temp3'] = ($temp3 != "") ? (double) $temp3 : "";
                    $json_p[$x]['temp4'] = ($temp4 != "") ? (double) $temp4 : "";
                }
                //</editor-fold>

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
                $json_p[$x]['status'] = $status;
                $json_p[$x]['vehiclespeed'] = isset($row['curspeed']) ? (int) $row['curspeed'] : '';
                $json_p[$x]['odometer'] = isset($row['odometer']) ? (double) $row['odometer'] : '';
                $json_p[$x]['drivername'] = isset($row['drivername']) ? $row['drivername'] : '';
                $json_p[$x]['groupname'] = isset($row['groupname']) ? $row['groupname'] : '';
                $json_p[$x]['ignition'] = isset($row['ignition']) ? (int) $row['ignition'] : 0;
                $json_p[$x]['timestamp'] = isset($row['lastupdated']) ? $row['lastupdated'] : '';
                $json_p[$x]['utctimestamp'] = isset($row['lastupdated']) ? gmdate('Y-m-d H:i:s', strtotime($row['lastupdated'])) : '';

                //digitaliostatus -ganesh added
                $unitno = $row['unitno'];
                $customerno = $row['customer_no'];
                $custdata = $this->get_customerdetails($customerno);
                $use_genset_sensor = $custdata['use_genset_sensor'];
                $temp_sensors = $custdata['temp_sensors'];
                $use_ac_sensor = $custdata['use_ac_sensor'];
                $acinvertval = $this->getacinvertval($unitno, $customerno);

                if ($use_genset_sensor == 1) {
                    $json_p[$x]['digitalstatus'] = "";
                    if ($acinvertval == 1) {
                        if ($row['digitalio'] == 0) {
                            $gensetstatus = 0; //0-OFF
                        } else {
                            $gensetstatus = 1; //1 - ON
                        }
                    } else {
                        if ($row['digitalio'] == 0) {
                            $gensetstatus = 0; //0- ON
                        } else {
                            $gensetstatus = 1; // 1- OFF
                        }
                    }
                    $json_p[$x]['digitalstatus'] = (int) $gensetstatus;
                }

                if ($use_ac_sensor == 1) {
                    $json_p[$x]['digitalstatus'] = "";
                    if ($acinvertval == 1) {
                        if ($row['digitalio'] == 0) {
                            $acsensorstatus = 1; //1 - OFF
                        } else {
                            $acsensorstatus = 0; // 0 - ON
                        }
                    } else {
                        if ($row['digitalio'] == 0) {
                            $acsensorstatus = 0; // 0 - ON
                        } else {
                            $acsensorstatus = 1; //1 - OFF
                        }
                    }
                    $json_p[$x]['digitalstatus'] = (int) $acsensorstatus;
                }
                $json_p[$x]['lat'] = (double) $row['devicelat'];
                $json_p[$x]['lng'] = (double) $row['devicelong'];
                $json_p[$x]['direction'] = isset($row['directionchange']) ? (int) $row['directionchange'] : 0;
                $x++;
            }
            $arr_p['result'] = $json_p;
            $arr_p['totalVehicleCount'] = $totalVehicleCount;
            return $arr_p;
        }
        // Free result set
        //        $records->close();
        //        $this->db->next_result();
        return null;
    }

    public function get_userdetails_by_key($userkey) {
        $pdo = $this->db->CreatePDOConn();
        $SQL = "SELECT  customerno
                        , userid
                        , role
                        , roleid
                        , realname
                FROM    user
                WHERE   isdeleted = 0
                AND     sha1(userkey)='%s'";
        $Query = sprintf($SQL, $userkey);
        $result = $pdo->query($Query);
        $this->db->ClosePDOConn($pdo);
        while ($row = $result->fetchObject()) {
            $data = array(
                'customerno' => $row->customerno,
                'userid' => $row->userid,
                'role' => $row->role,
                'roleid' => $row->roleid,
                'realname' => $row->realname
            );
            return $data;
        }
        return null;
    }

    public function checkValidity($customerno) {
        $devices = $this->checkforvalidity($customerno);
        $initday = 0;
        if (isset($devices)) {
            foreach ($devices as $thisdevice) {
                $days = $this->check_validity_login(Sanitise::Date($thisdevice->expirydate), Sanitise::DateTime($thisdevice->today));
                if ($days > 0) {
                    $initday = $days;
                }
            }
        }
        return $initday;
    }

    public function checkforvalidity($customerno, $deviceid = null) {
        $pdo = $this->db->CreatePDOConn();
        $Query = "  SELECT  deviceid
                            , expirydate
                            , Now() as today
                    FROM    `devices`
                    where   customerno=%d ";
        if (isset($deviceid)) {
            $Query .= " AND deviceid = %d";
        }
        if (isset($deviceid)) {
            $devicesQuery = sprintf($Query, $customerno, $deviceid);
        } else {
            $devicesQuery = sprintf($Query, $customerno);
        }
        $result = $pdo->query($devicesQuery);
        $this->db->ClosePDOConn($pdo);
        while ($row = $result->fetchObject()) {
            $device = new VODevices();
            $device->deviceid = $row->deviceid;
            $device->today = $row->today;
            $device->expirydate = $row->expirydate;
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

    public function location($lat, $long, $usegeolocation, $customerno) {
        $address = NULL;
        $address = $this->get_location_bylatlong($lat, $long, $customerno);
        return $address;
    }

    public function get_vehicle($vehicleid, $customerno) {
        $Query = "SELECT vehicle.vehicleno
        , driver.drivername
        , driver.driverphone
        , devices.deviceid
        , devices.devicelat
        , devices.devicelong
        FROM    vehicle
        inner join devices on devices.uid=vehicle.uid
        left join driver on driver.driverid=vehicle.driverid
        WHERE   vehicle.customerno =$customerno
        AND     vehicle.vehicleid=$vehicleid
        AND     vehicle.isdeleted=0
        group by vehicle.vehicleid";

        $record = $this->db->query($Query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);
        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $vehicle = new stdClass();
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverphone = $row['driverphone'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->devicelat = $row['devicelat'];
                $vehicle->devicelong = $row['devicelong'];
            }
            return $vehicle;
        }
        return null;
    }

    public function mapVehicleToChkPts($objChkPtStatus) {
        $objUserManager = new UserManager();
        $objUser = $objUserManager->getAllElixir($objChkPtStatus->customerNo);
        $userid = (isset($objUser) && isset($objUser->userid)) ? $objUser->userid : -1;
        $objChkPtManager = new CheckpointManager($objChkPtStatus->customerNo);
        foreach ($objChkPtStatus->arrChkPtDetails as $chkPt) {
            $checkpoint = new stdClass();
            $checkpoint->checkpointid = $chkPt['chkPtId'];
            $checkpoint->vehicles = array($objChkPtStatus->vehicleId);
            $deleteParticularVehicle = 1;
            $objChkPtManager->mapVehicleToCheckpoint($checkpoint, $userid, $deleteParticularVehicle);
        }
    }

    public function getChkPtData($sqliteLocation, $objChkPtStatus) {
        $location = "sqlite:" . $sqliteLocation;
        foreach ($objChkPtStatus->arrChkPtDetails as &$chkPt) {
            $db = new PDO($location);
            $outQuery = "select chkrepid as outchkrepid, chkid as outchkid, status as outstatus, date as outdate "
            . " from V" . $objChkPtStatus->vehicleId . " WHERE chkid = " . $chkPt['chkPtId'] . " and status = 1 "
            . " and date BETWEEN '" . $objChkPtStatus->startDateTime . "' AND '" . $objChkPtStatus->endDateTime . "' ORDER BY date DESC limit 1";
            $outResult = $db->query($outQuery)->fetchAll();
            if (isset($outResult)) {
                foreach ($outResult as $row) {
                    $chkPt['outTime'] = isset($row["outdate"]) ? $row["outdate"] : $chkPt['outTime'];
                }
            }
            $inQuery = "select chkrepid as inchkrepid, chkid as inchkid, status as instatus, date as indate "
            . " from V" . $objChkPtStatus->vehicleId . " WHERE chkid = " . $chkPt['chkPtId'] . " and status = 0 "
            . " and date BETWEEN '" . $objChkPtStatus->startDateTime . "' AND '" . $objChkPtStatus->endDateTime . "' ORDER BY date DESC limit 1";
            $inResult = $db->query($inQuery)->fetchAll();
            if (isset($inResult)) {
                foreach ($inResult as $row) {
                    $chkPt['inTime'] = isset($row["indate"]) ? $row["indate"] : $chkPt['inTime'];
                }
            }
            /*
        $queryStmt = "select outChkPt.*, inChkPt.* FROM "
        . " (select chkrepid as outchkrepid, chkid as outchkid, status as outstatus, date as outdate "
        . " from V" . $objChkPtStatus->vehicleId . " WHERE chkid = " . $chkPt['chkPtId'] . " and status = 1 "
        . " and date BETWEEN '" . $objChkPtStatus->startDateTime . "' AND '" . $objChkPtStatus->endDateTime . "' ORDER BY date DESC limit 1) as outChkPt "
        . " CROSS JOIN "
        . " (select chkrepid as inchkrepid, chkid as inchkid, status as instatus, date as indate "
        . " from V" . $objChkPtStatus->vehicleId . " WHERE chkid = " . $chkPt['chkPtId'] . " and status = 0 "
        . " and date BETWEEN '" . $objChkPtStatus->startDateTime . "' AND '" . $objChkPtStatus->endDateTime . "' ORDER BY date DESC limit 1) as inChkPt ";

        $result = $db->query($queryStmt);
        foreach ($result as $row) {
        $chkpt->inTime = isset($row["indate"]) ? $row["indate"] : "";
        $chkpt->outTime = isset($row["outdate"]) ? $row["outdate"] : "";
        }

         */
        }
        return $objChkPtStatus;
    }

    public function InsertDataInSqlite($objVehDetail) {
        $isDataInsertedInSqlite = 0;
        if ($objVehDetail->unitNo != "") {
            $date = convertDateToFormat($objVehDetail->lastUpdated, speedConstants::DATE_Ymd);
            $sqliteFilePath = RELATIVE_PATH_DOTS . "customer/" . $objVehDetail->customerno . "/unitno/" . $objVehDetail->unitNo . "/sqlite/";
            if (file_exists($sqliteFilePath)) {
                $db = "sqlite:" . $sqliteFilePath . $date . ".sqlite";

                $this->CreateSqliteTables($db);
                $dbh = new PDO($db);

                $objVehDetail->isPowered = isset($objVehDetail->isPowered) ? $objVehDetail->isPowered : 1;
                $objVehDetail->extbatt = isset($objVehDetail->extbatt) ? $objVehDetail->extbatt : "";
                $objVehDetail->tamper = isset($objVehDetail->tamper) ? $objVehDetail->tamper : 0;
                $objVehDetail->isOffline = isset($objVehDetail->isOffline) ? $objVehDetail->isOffline : 0;
                $objVehDetail->analog1 = isset($objVehDetail->temperature1) ? $objVehDetail->temperature1 : 0;
                $objVehDetail->analog2 = isset($objVehDetail->temperature2) ? $objVehDetail->temperature2 : 0;
                $objVehDetail->analog3 = isset($objVehDetail->temperature3) ? $objVehDetail->temperature3 : 0;
                $objVehDetail->analog4 = isset($objVehDetail->temperature4) ? $objVehDetail->temperature4 : 0;

                /* Convert object to string */
                ob_start();
                print_r($objVehDetail);
                $rawdata = ob_get_clean();
                $rawDataColumns = 'data,client,insertedon';
                $rawDataParams = ':dataParam,:clientParam,:insertedonParam';
                $arrRawDataValues = array(
                    ":dataParam" => $rawdata
                    , ":clientParam" => ""
                    , ":insertedonParam" => today()
                );

                $deviceColumns = 'deviceid, customerno, devicelat, devicelong, lastupdated, altitude, directionchange, inbatt, uid, ';
                $deviceColumns .= 'ignition, powercut, tamper, `online/offline`, gsmstrength';
                $deviceParams = ":deviceidParam, :customernoParam, :devicelatParam, :devicelongParam, :lastupdatedParam, :altitudeParam, :directionchangeParam, :inbattParam, :uidParam, ";
                $deviceParams .= ':ignitionParam, :powercutParam, :tamperParam, :isOfflineParam, :gsmstrengthParam';
                $arrDeviceValues = array(
                    ":deviceidParam" => $objVehDetail->deviceId
                    , ":customernoParam" => $objVehDetail->customerno
                    , ":devicelatParam" => $objVehDetail->latitude
                    , ":devicelongParam" => $objVehDetail->longitude
                    , ":lastupdatedParam" => $objVehDetail->lastUpdated
                    , ":altitudeParam" => $objVehDetail->altitude
                    , ":directionchangeParam" => $objVehDetail->direction
                    , ":inbattParam" => $objVehDetail->vehicleBattery
                    , ":uidParam" => $objVehDetail->uid
                    , ":ignitionParam" => $objVehDetail->ignition
                    , ":powercutParam" => $objVehDetail->isPowered
                    , ":tamperParam" => $objVehDetail->tamper
                    , ":isOfflineParam" => $objVehDetail->isOffline
                    , ":gsmstrengthParam" => $objVehDetail->gsmStrength
                );

                $vehicleColumns = "vehicleid,vehicleno,extbatt,odometer,lastupdated,curspeed,customerno,driverid,uid";
                $vehicleParams = ":vehicleidParam, :vehiclenoParam, :extbattParam, :odometerParam, :lastupdatedParam, :speedParam, :customernoParam, :driveridParam, :uidParam";
                $arrVehicleValues = array(
                    ":vehicleidParam" => $objVehDetail->vehicleId
                    , ":vehiclenoParam" => $objVehDetail->vehicleNo
                    , ":extbattParam" => $objVehDetail->extbatt
                    , ":odometerParam" => $objVehDetail->odometer
                    , ":lastupdatedParam" => $objVehDetail->lastUpdated
                    , ":speedParam" => $objVehDetail->speed
                    , ":customernoParam" => $objVehDetail->customerno
                    , ":driveridParam" => $objVehDetail->driverId
                    , ":uidParam" => $objVehDetail->uid
                );

                $unitColumns = "uid,unitno,customerno,vehicleid,analog1,analog2,analog3,analog4,digitalio,lastupdated";
                $unitParams = ":uidParam, :unitnoParam, :customernoParam, :vehicleidParam, :analog1Param, :analog2Param, :analog3Param, :analog4Param, :digitalioParam, :lastupdatedParam";
                $arrUnitValues = array(
                    ":uidParam" => $objVehDetail->uid
                    , ":unitnoParam" => $objVehDetail->unitNo
                    , ":customernoParam" => $objVehDetail->customerno
                    , ":vehicleidParam" => $objVehDetail->vehicleId
                    , ":analog1Param" => $objVehDetail->analog1
                    , ":analog2Param" => $objVehDetail->analog2
                    , ":analog3Param" => $objVehDetail->analog3
                    , ":analog4Param" => $objVehDetail->analog4
                    , ":digitalioParam" => $objVehDetail->digitalio
                    , ":lastupdatedParam" => $objVehDetail->lastUpdated
                );

                $dbh->exec('BEGIN IMMEDIATE TRANSACTION');
                $rawdataquery = $dbh->prepare("INSERT INTO data(" . $rawDataColumns . ") VALUES(" . $rawDataParams . ")");
                $rawdataquery->execute($arrRawDataValues);

                $devicequery = $dbh->prepare('INSERT into devicehistory (' . $deviceColumns . ') Values (' . $deviceParams . ')');
                $devicequery->execute($arrDeviceValues);

                $vehiclequery = $dbh->prepare('INSERT into vehiclehistory (' . $vehicleColumns . ') Values (' . $vehicleParams . ')');
                $vehiclequery->execute($arrVehicleValues);

                $unitquery = $dbh->prepare('INSERT into unithistory (' . $unitColumns . ') Values (' . $unitParams . ')');
                $unitquery->execute($arrUnitValues);
                $isDataInsertedInSqlite = 1;
                $dbh->exec('COMMIT TRANSACTION');
                $dbh = NULL;
            }
        }
        return $isDataInsertedInSqlite;
    }

    public function CreateSqliteTables($path) {
        $dbh = new PDO($path);
        $tables = 'CREATE TABLE IF NOT EXISTS data (dataid INTEGER,data TEXT,client TEXT, insertedon DATETIME, PRIMARY KEY(dataid));';
        $tables .= ' CREATE TABLE IF NOT EXISTS unithistory (uhid INTEGER,uid INTEGER,unitno TEXT,customerno INTEGER,vehicleid INTEGER,';
        $tables .= 'analog1 TEXT,analog2 TEXT,analog3 TEXT,analog4 TEXT,digitalio TEXT,lastupdated datetime,commandkey TEXT,commandkeyval TEXT,PRIMARY KEY (uhid));';
        $tables .= ' CREATE TABLE IF NOT EXISTS devicehistory(id INTEGER,deviceid INTEGER,customerno INTEGER,devicelat TEXT,devicelong TEXT,';
        $tables .= 'lastupdated datetime,altitude INTEGER,directionchange TEXT,inbatt TEXT,hwv TEXT,swv TEXT,msgid TEXT,';
        $tables .= 'uid INTEGER,status TEXT,ignition INTEGER,powercut INTEGER,tamper INTEGER,gpsfixed TEXT,`online/offline` INTEGER,gsmstrength INTEGER,';
        $tables .= 'gsmregister INTEGER,gprsregister INTEGER,satv TEXT,PRIMARY KEY (id));';
        $tables .= ' CREATE TABLE IF NOT EXISTS vehiclehistory(vehiclehistoryid INTEGER,vehicleid INTEGER,vehicleno TEXT,';
        $tables .= 'extbatt TEXT,odometer INTEGER,lastupdated datetime,curspeed INTEGER,customerno INTEGER,driverid INTEGER,uid TEXT,PRIMARY KEY (vehiclehistoryid));';
        $tables .= 'CREATE INDEX IF NOT EXISTS `fk_vehiclehistory` ON `vehiclehistory` (`lastupdated`);';
        $tables .= 'CREATE INDEX IF NOT EXISTS `fk_unithistory` ON `unithistory` (`lastupdated`);';
        $tables .= 'CREATE INDEX IF NOT EXISTS `fk_devicehistory` ON `devicehistory` (`lastupdated`);';
        $dbh->exec($tables);
        $dbh = NULL;
    }

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Utility functions">

    public function datediff($STdate, $EDdate) {
        if (strtotime($STdate) > strtotime($EDdate)) {
            return 0;
        } else {
            return 1;
        }
    }

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

    public function failure($text, $result = NULL) {
        if ($result == NULL) {
            return array('Provider' => 'Elixia', 'Status' => '0', 'Error' => $text);
        } else {
            return array('Provider' => 'Elixia', 'Status' => '0', 'Error' => $text, 'Result' => $result);
        }
    }

    public function success($message, $result) {
        return array('Provider' => 'Elixia', 'Status' => '1', 'Message' => $message, 'Result' => $result);
    }

    public function date_SDiff($dt1, $dt2, $timeZone = 'GMT', $isAbsolute = 1) {
        $tZone = new DateTimeZone($timeZone);
        $dt1 = new DateTime($dt1, $tZone);
        $dt2 = new DateTime($dt2, $tZone);
        $ts1 = $dt1->format('Y-m-d');
        $ts2 = $dt2->format('Y-m-d');
        $diff = ($isAbsolute) ? abs(strtotime($ts1) - strtotime($ts2)) : strtotime($ts1) - strtotime($ts2);
        $diff /= 3600 * 24;
        return $diff;
    }

    public function gendays($STdate, $EDdate) {
        $TOTALDAYS = Array();

        $STdate = date("Y-m-d", strtotime($STdate));
        $EDdate = date("Y-m-d", strtotime($EDdate));
        while (strtotime($STdate) <= strtotime($EDdate)) {
            $TOTALDAYS[] = $STdate;
            $STdate = date("Y-m-d", strtotime($STdate . ' + 1 day'));
        }
        return $TOTALDAYS;
    }

    public function getTempUtil($tempConversion) {
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

/////

    public function getTravelHistoryReport($objReqData) {
        //Prepare Reponse
        //print_r($objReqData); die;
        /* [userkey] => 364a20b0bbae0a05ba3b25531173bfe3550a2a42
        [vehicleNo] => MH 04 FU 3871
        [SDate] => 2017-10-05
        [EDate] => 2017-10-10
        [STime] => 10:00:00
        [ETime] => 11:00:00
        [customerNo] => 116
         */
        //print_r($objReqData); die;
        if (!isset($objReqData->vehicleId)) {
            $objReqData->vehicleId = "";
        }
        $startDateTime = $objReqData->SDate . " " . $objReqData->STime;
        $endDateTime = $objReqData->EDate . " " . $objReqData->ETime;

        $arrResponse = array();
        $arrResponse['vehicleNo'] = $objReqData->vehicleNo;
        $arrResponse['SDate'] = $objReqData->SDate;
        $arrResponse['EDate'] = $objReqData->EDate;
        $arrResponse['STime'] = $objReqData->STime;
        $arrResponse['ETime'] = $objReqData->ETime;
        $arrResponse['travelHistoryReport'] = array();
        //$arrResponse['customParams'] = $objReqData->customParams;
        $objReqData->chkPtId = 0;
        $objReqData->reportType = 0;

        if (($objReqData->vehicleId > 0 || $objReqData->vehicleNo != "") && ($objReqData->customerNo > 0)) {
            //$objReqData->startDateTime = isset($objReqData->startDateTime) ? $objReqData->startDateTime : today();
            //$objReqData->endDateTime = isset($objReqData->endDateTime) ? $objReqData->endDateTime : today();
            if (strtotime($endDateTime) >= strtotime($startDateTime)) {
                $diffInDays = $this->date_SDiff($startDateTime, $endDateTime);
                if ($diffInDays <= 30) {
                    $objVehManager = new VehicleManager($objReqData->customerNo);
                    if ($objReqData->vehicleId > 0) {
                        $objRequest = $objVehManager->get_vehicle_details($objReqData->vehicleId);
                    } elseif ($objReqData->vehicleNo != "") {
                        $objReq = $objVehManager->get_vehicle_by_vehno($objReqData->vehicleNo, 1);

                        $objRequest = (object) $objReq[0];
                        $objRequest->vehicleNo = $objReqData->vehicleNo;
                        $objRequest->vehicleId = $objRequest->vehicleid;
                    }
                    $totaldays = gendays_cmn($objReqData->SDate, $objReqData->EDate);
                    $count = count($totaldays);
                    $endelement = end($totaldays);
                    $firstelement = $totaldays[0];
                    //$unitno = $um->getunitnofromdeviceid($vehicleid);
                    $days = array();
                    if (isset($totaldays)) {
                        foreach ($totaldays as $userdate) {
                            $lastday = $this->travelHistoryData($objReqData->customerNo, $objRequest->unitno, $userdate, $count, $firstelement, $endelement, $objRequest->vehicleId, $objReqData->STime, $objReqData->ETime);
                            if ($lastday != null) {
                                $days = array_merge($days, $lastday);
                            }
                        }
                    }

                    $geocode = null;
                    if (isset($days) && count($days) > 0) {
                        $paramForDisplayTravelData = array();
                        $param = new stdClass();
                        $param->days = $days;
                        $param->vehicleId = $objRequest->vehicleId;

                        $param->SDate = $objReqData->SDate;
                        $param->EDate = $objReqData->EDate;
                        $param->STime = $objReqData->STime;
                        $param->ETime = $objReqData->ETime;
                        $param->customerno = $objReqData->customerNo;

                        $param->unitno = $objRequest->unitno;
                        $param->geocode = $geocode;
                        $arrResponse['travelHistoryReport'] = $this->dispalytraveldata($param);
                        //count($data); die;
                    }
// print_r($arrResponse['trHisData']); die;
                    //print_r($arrResponse); die;
                }
            }
        }
        return $arrResponse;
    }

///////

    public function dispalytraveldata($param) {
        //$datarows, $vehicleid, $unitno, $geocode
        /*
        [days] => data
        [vehicleId] => 10937
        [SDate] => 2018-03-18
        [EDate] => 2018-03-19
        [STime] => 10:00:00
        [ETime] => 11:00:00
        [unitno] => 1809010017401
         */
        //print_r($param); die;

        $t = 1;
        $runningtime = 0;
        $idletime = 0;
        $idle_ign_on = 0;
        $startdate = $param->SDate . ' ' . $param->STime;
        $enddate = $param->EDate . ' ' . $param->ETime;
        $input_end_date = $param->EDate;
        $startdate = date('Y-m-d H:i:s', strtotime($startdate));
        $enddate = date('Y-m-d H:i:s', strtotime($enddate));
        $totaldistance = 0;
        $lastdate = null;
        $totalminute = 0;
        $devicemanager = new DeviceManager($param->customerno);
        // $finalReport = '';
        $finaldata = array();

        if (isset($param->days)) {
            $z = 0;
            $ak_text = 0;
            $a = 0;

            foreach ($param->days as $change) {
                $finalReport = new stdClass();
                // print_r($change->endtime); die;
                $ak_text++;
                $comparedate = date('d-m-Y', strtotime($change->endtime));
                $today = date('d-m-Y', strtotime('Now'));

                if ($change->startlat == 0) {
                    $latlong = getlatlong($change->starttime, $change->endtime, $param->vehicleId, $param->unitno, 'asc');
                    if (isset($latlong) && count($latlong) > 0) {
                        $change->startlat = $latlong[0];
                        $change->startlong = $latlong[1];
                    }
                }
                if ($change->endlat == 0) {
                    $latlong = getlatlong($change->starttime, $change->endtime, $param->vehicleId, $param->unitno, 'desc');
                    if (isset($latlong) && count($latlong) > 0) {
                        $change->endlat = $latlong[0];
                        $change->endlong = $latlong[1];
                    }
                }
                //Removing Date Details From DateTime
                //echo $change->endtime ; die();
                $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
                $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));

                $test = 0;
                // if ($change->distancetravelled > 0) { original code
                $innerData = "";
                if ($change->distancetravelled > 0) {
                    $timestamp = $change->starttime . "," . $change->endtime;
                    $innerData = $this->get_ajax_data_history($param->vehicleId, $param->EDate, $timestamp, $param->unitno, $param->customerno);
                }
                $finalReport->innerdata = $innerData;
                /* echo "hello<br>";
                print_r($test); die; */

                /* if ($change->distancetravelled > 0) {
                $finalReport .= "<tr style='cursor:default;' onclick='call_row(" . ++$z . ")' id='" . $z . "'  >";
                } else {
                $finalReport .= "<tr style='cursor:default;' >";
                }
                $finalReport .= "<td>" . $i++ . "<input type='hidden' id='vehicle" . $z . "' value='" . $vehicleid . "'>
                <input type='hidden' id='unitno" . $z . "' value='" . $unitno . "'><input type='hidden' id='date" . $z . "' value='" . $lastdate . "'>
                <input type='hidden' id='timestamp" . $z . "' value='" . $change->starttime . "," . $change->endtime . "'></td>";
                 */

                /* $finalReport .= "<td>$change->starttime</td>";
                $finalReport .= "<td>$change->endtime</td>"; */
                $finalReport->startdatetime = $change->starttime;
                $finalReport->enddatetime = $change->endtime;

                //Printing Location
                $location = getlocation($change->startlat, $change->startlong, $param->geocode, $param->customerno);
                // $finalReport .= "<td>$location</td>";
                $finalReport->startlocation = $location;

                if ($change->startlat != $change->endlat && $change->startlong != $change->endlong) {
                    $location = getlocation($change->endlat, $change->endlong, $param->geocode, $param->customerno);
                    $finalReport->endlocation = $location;
                } else {
                    $finalReport->endlocation = "Same Place";
                }
                // print_r($finalReport); die;
                $hour = floor(($change->duration) / 60);
                $minutes = ($change->duration) % 60;
                if ($minutes < 10) {
                    $minutes = "0" . $minutes;
                }
                $change->duration = $hour . ":" . $minutes;
                $finalReport->duration = $change->duration;

                $finalReport->distancetravelled = "" . round($change->distancetravelled, 2);
                $totaldistance += round($change->distancetravelled, 2);
                $finalReport->totaldistance = "" . $totaldistance;

                if ($change->distancetravelled > 0) {
                    $finalReport->status = "Running";
                    $runningtime += $minutes + ($hour * 60);
                    //$finalReport.= "<td></td>";
                } else {
                    if ($change->ignition == 1) {
                        $finalReport->status = "Idle - Ignition On";
                        $idle_ign_on += $minutes + ($hour * 60);
                    } else {
                        $finalReport->status = "Idle";
                        $idletime += $minutes + ($hour * 60);
                    }
                }

                $chk = getChkRealy($change->startlat, $change->startlong);
                if (!empty($chk)) {
                    $finalReport->cname = $chk[0]->cname;
                } else {
                    $finalReport->cname = "";
                }
                $finaldata[] = $finalReport;

                $a++;
            } // end foreach
        }

        if (isset($totaldistance) && ($totaldistance) > 0) {
            if (isset($runningtime) && $runningtime != 0) {
                $AverageSpeed = (int) ($totaldistance / ($runningtime / 60));
            }
        } else {
            $AverageSpeed = 0;
        }
        $totaldistance = round($totaldistance, 1);
        if ($input_end_date == date('d-m-Y')) {
            $now = date('Y-m-d H:i:s');
            $totaltime = round(abs(strtotime($now) - strtotime($startdate)) / 60, 2);
        } else {
            $totaltime = round(abs(strtotime($enddate) - strtotime($startdate)) / 60, 2);
        }
        $offtime = $totaltime - $runningtime - $idle_ign_on;
        $offtime = hrs_mins($offtime);
        $runningtime = hrs_mins($runningtime);
        $idletime = hrs_mins($idletime);
        $idle_ign_on = hrs_mins($idle_ign_on);
        $statistic = array();
        $statistic['totalrunningtime'] = $runningtime . ' Hours';
        $statistic['totalidletime'] = $offtime . ' Hours';
        $statistic['totalidleignitionontime'] = $idle_ign_on . ' Hours';
        $statistic['totaldistancetravelled'] = $totaldistance . ' km';
        $statistic['avgspeedrunningtime'] = $AverageSpeed . ' km/hr';

        //$finalReport->statistics = $statistic;
        //$finaldata['result'] = $finalReport;

        $finalRecords = array();
        $finalRecords['rows'] = $finaldata;
        $finalRecords['summary'] = $statistic;
        //print_r($finalRecords); die;
        // echo $finalReport;
        return $finalRecords;
    }

//////
    public function travelHistoryData($customerno, $unitno, $userdate, $count, $firstelement, $endelement, $vehicleid, $Shour, $Ehour) {
        $location = "../../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
        if (!file_exists($location)) {
            return null;
        }
        if (filesize($location) == 0) {
            return null;
        }
        $location = "sqlite:" . $location;
        if ($count > 1 && $userdate != $endelement && $userdate == $firstelement) {
            $devicedata = getdatafromsqliteTimebased($vehicleid, $location, $userdate, $Shour, null, $customerno);
        } elseif ($count > 1 && $userdate == $endelement) {
            $devicedata = getdatafromsqliteTimebased($vehicleid, $location, $userdate, null, $Ehour, $customerno);
        } elseif ($count == 1) {
            $devicedata = getdatafromsqliteTimebased($vehicleid, $location, $userdate, $Shour, $Ehour, $customerno);
        } else {
            $devicedata = getdatafromsqliteTimebased($vehicleid, $location, $userdate, null, null, $customerno);
        }
        //print_r($devicedata); die;
        if ($devicedata != null) {
            $lastday = processtraveldata($devicedata, $unitno, $isApi = "1");
            return $lastday;
        } else {
            return null;
        }
    }

///

    public function get_ajax_data_history($v_id, $data, $timestamp, $unitno, $customerno) {
        if (isset($customerno)) {
            // $customerno = $_SESSION['customerno'];
            $t_array = explode(",", $timestamp);
            $location = "../../../customer/$customerno/unitno/$unitno/sqlite/" . date("Y-m-d", strtotime($data)) . ".sqlite";
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
                        $cumdist = 0;
                        $n = 0;
                        $innerData = array();
                        foreach ($result as $row) {
                            //$stdobj = new stdClass();
                            if ($i == 0) {
                                $startodo = $row['odometer'];
                            }
                            $i++;
                            if (($i % $divisor == 0) && ($y <= 10)) {
                                $endodo = $row['odometer'];
                                $cumdist = ($endodo / 1000) - ($startodo / 1000);

                                $GeoCoder_Obj = new GeoCoder($customerno);
                                $address = $GeoCoder_Obj->get_location_bylatlong($row['devicelat'], $row['devicelong']);

                                //echo $y++;
                                $innerData[$n]['address'] = $address;
                                $innerData[$n]['time'] = date(speedConstants::DEFAULT_DATETIME, strtotime($row['lastupdated']));
                                $innerData[$n]['cumdistance'] = "" . round($cumdist, 2);
                                $startodo = $endodo;
                            }
                            $n++;
                        } // end foreach
                        return $innerData;
                    } // end outer if
                } catch (PDOException $e) {
                    die($e);
                }
            }
        }
    }

    public function gettemptabularreport_nestle($objReqData) {
        //Prepare Reponse
        //print_r($objReqData); die;
        /* {"userkey":"b4a58c9ad31d5ce7439a13a1f33c70cf100c8795",
        "vehicleNo":"HR 46  D 7431",
        "SDate":"2018-05-18",
        "EDate":"2018-05-18",
        "STime":"10:00:00",
        "ETime":"11:00:00",
        "interval":"15"
        }*/

        if (!isset($objReqData->vehicleId)) {
            $objReqData->vehicleId = "";
        }

        $startDateTime = $objReqData->SDate . " " . $objReqData->STime;
        $endDateTime = $objReqData->EDate . " " . $objReqData->ETime;

        $arrResponse = array();
        $arrResponse['vehicleNo'] = $objReqData->vehicleNo;
        $arrResponse['SDate'] = $objReqData->SDate;
        $arrResponse['EDate'] = $objReqData->EDate;
        $arrResponse['STime'] = $objReqData->STime;
        $arrResponse['ETime'] = $objReqData->ETime;
        $arrResponse['interval'] = $objReqData->interval;
        $arrResponse['customParams'] = $objReqData->customParams;

        $arrResponse['finalreport'] = array();

        $objReqData->chkPtId = 0;
        $objReqData->reportType = 0;
        $tempselect = null;

        // $deviceid = $objReqData->deviceid;

        if (($objReqData->vehicleId > 0 || $objReqData->vehicleNo != "") && ($objReqData->customerNo > 0)) {
            if (strtotime($endDateTime) >= strtotime($startDateTime)) {
                $diffInDays = $this->date_SDiff($startDateTime, $endDateTime);
                if ($diffInDays <= 30) {
                    $objVehManager = new VehicleManager($objReqData->customerNo);
                    if ($objReqData->vehicleId > 0) {
                        $objRequest = $objVehManager->get_vehicle_details($objReqData->vehicleId);
                    } elseif ($objReqData->vehicleNo != "") {
                        $objReq = $objVehManager->get_vehicle_by_vehno($objReqData->vehicleNo, 1);

                        $objRequest = (object) $objReq[0];
                        $objRequest->vehicleNo = $objReqData->vehicleNo;
                        $objRequest->vehicleId = $objRequest->vehicleid;
                    }
                    $totaldays = gendays_cmn($objReqData->SDate, $objReqData->EDate);
                    $count = count($totaldays);
                    $endelement = end($totaldays);
                    $firstelement = $totaldays[0];

                    $days = array();

                    $unit = $this->getunitdetails($objReqData->deviceid, $objReqData->customerNo);

                    $objCustomerManager = new CustomerManager();
                    $customer_details = $objCustomerManager->getcustomerdetail_byid($objReqData->customerNo);

                    if (isset($customer_details) && !empty($customer_details)) {
                        $tempselect = $customer_details->temp_sensors;
                    }

                    if (isset($totaldays)) {
                        foreach ($totaldays as $userdate) {
                            //Date Check Operations
                            $data = null;
                            $graph_data = null;
                            $STdate = date("Y-m-d", strtotime($objReqData->SDate));
                            $f_STdate = $userdate . " 00:00:00";
                            if ($userdate == $STdate) {
                                $f_STdate = $userdate . " " . $objReqData->STime . ":00";
                            }
                            $EDdate = date("Y-m-d", strtotime($objReqData->EDate));
                            $f_EDdate = $userdate . " 23:59:59";
                            if ($userdate == $EDdate) {
                                $f_EDdate = $userdate . " " . $objReqData->ETime . ":00";
                            }

                            $location = "../../../customer/$objReqData->customerNo/unitno/$unit->unitno/sqlite/$userdate.sqlite";
                            if (file_exists($location)) {
                                // echo "<br>file exist"; die;
                                $location = "sqlite:" . $location;
                                $return = gettempdata_fromsqlite($location, $objReqData->deviceid, $objReqData->interval, $f_STdate, $f_EDdate, false, $unit, $objReqData->customerNo, $isApi = 1);

                                $data = $return[0];
                            }

                            if (isset($data) && count($data) > 1) {
                                $days = array_merge($days, $data);
                                // print("days - <pre>"); print_r($days); die;
                            }
                        }
                    }

                    if (isset($days) && count($days) > 0) {
                        if (isset($return['vehicleid'])) {
                            $veh_temp_details = getunitdetailsfromvehid($return['vehicleid'], $objReqData->customerNo);
                            // print(" veh temp <pre>"); print_r($veh_temp_details); die;
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
                        // print("<pre>"); print_r($objReqData); die;
                        $data = getTripData($objReqData->SDate, $objReqData->EDate, $objReqData->deviceid, $objReqData->interval, $objReqData->STime, $objReqData->ETime, $objReqData->customerNo, $tempselect);
                        // print("<pre>"); print_r($data); die;
                        if (isset($data) && !empty($data)) {
                            $startData = reset($data);
                            $lastData = end($data);
                            $distance = calculateOdometerDistance($startData->odometer, $lastData->odometer);
                            $arrResponse['distance'] = "" . round($distance, 2);
                        }
                        if (isset($objReqData->customMinTemp) && isset($objReqData->customMaxTemp)) {
                            $customMinTemp = $objReqData->customMinTemp;
                            $customMaxTemp = $objReqData->customMaxTemp;
                        } else {
                            $customMinTemp = -1;
                            $customMaxTemp = -1;
                        }
                        $arrResponse['finalreport'] = $this->get_report_with_noncom_reading($days, $unit, $veh_temp_details, $objReqData->interval, $tempselect, $objReqData->customerNo, $customMinTemp, $customMaxTemp);
                    }
                }
            }
        }
        return $arrResponse;
    }

//create_temphtml_from_report_with_noncom_reading
    public function get_report_with_noncom_reading($datarows, $vehicle, $veh_temp_details = null, $interval = 120, $tempselect = null, $customerNo, $customMinTemp = -1, $customMaxTemp = -1) {
        // print("<pre>"); print_r($customerNo); die;

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
        if ($customMinTemp != -1 && $customMaxTemp != -1) {
            $min_max_temp1 = getCustomTempRange(1, $customMinTemp, $customMaxTemp);
            $min_max_temp2 = getCustomTempRange(2, $customMinTemp, $customMaxTemp);
            $min_max_temp3 = getCustomTempRange(3, $customMinTemp, $customMaxTemp);
            $min_max_temp4 = getCustomTempRange(4, $customMinTemp, $customMaxTemp);
        } else {
            $min_max_temp1 = get_min_max_temp(1, $veh_temp_details, $tempselect); // here $tempselect is Temp Sensors
            $min_max_temp2 = get_min_max_temp(2, $veh_temp_details, $tempselect);
            $min_max_temp3 = get_min_max_temp(3, $veh_temp_details, $tempselect);
            $min_max_temp4 = get_min_max_temp(4, $veh_temp_details, $tempselect);
        }

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

        $mutedetails = getunitmutedetails($vehicle->vehicleid, $vehicle->uid, $customerNo);
        $switch_to = isset($vehicle->switch_to) ? $vehicle->switch_to : 0;
        $finaldata = array();
        if (isset($datarows)) {
            foreach ($datarows as $change) {
                /* Don't show the entire log of 1 min data when customer pulls out this interval for compliance only */
                $finalreport = new stdClass();

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
                        /* $display .= "<tr><th align='center' colspan = '100%' style='background:#d8d5d6'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>"; */
                        $lastdate = date('d-m-Y', strtotime($change->endtime));
                        $i++;
                    }

                    $change->lastupdated = $change->starttime;
                    $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
                    $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
                    if ($customerNo == 116) {
                        $starttime_disp = date(speedConstants::DEFAULT_DATETIME, strtotime($change->lastupdated));
                    } else {
                        $starttime_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
                    }

                    $finalreport->starttime = $starttime_disp;

                    $location = get_location_detail($change->devicelat, $change->devicelong, $customerNo);

                    if ($switch_to != 3) {
                        $finalreport->location = $location;
                    }
                }

                $temp1 = $temp2 = $temp3 = $temp4 = speedConstants::TEMP_WARNING;

                $tdstring = '';
                $objTemp = new TempConversion();
                $objTemp->unit_type = $veh_temp_details->get_conversion;
                /* $objTemp->use_humidity = $_SESSION['use_humidity'];
                $objTemp->switch_to = $_SESSION['switch_to']; */
                $arr = explode("-", $tempselect);

                $pass = (isset($arr[0]) ? $arr[0] : $_SESSION['temp_sensors']);

                switch ($pass) {
                    case 4:
                        // echo "case 4 "; die;
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
                                        /* $temp4_non_comp_count++;
                                        } */
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
                        $finalreport->temp4 = "" . round($temp4, 2);

                    case 3:
                        // echo "case 3 "; die;
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
                                        /* $temp3_non_comp_count++;
                                        } */
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

                        $finalreport->temp3 = "" . round($temp3, 2);

                    case 2:
                        // echo "case 2 "; die;
                        //echo "tkbfkb ".$vehicle->tempsen2; die;
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
                                        /* $temp2_non_comp_count++;
                                        } */
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
                        // $tdstring = "<td>" . $temp2 . "</td>" . $tdstring;
                        $finalreport->temp2 = "" . round($temp2, 2);

                    case 1:
                        // echo "case 1 "; //die;
                        if ($vehicle->tempsen1 != 0) {
                            // echo "tempselect". $tempselect; die;
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
                                    // echo "temperature - ".$temp1."<br>";  die;
                                    // echo NORMAL_MIN_TEMP; die;
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

                                        // echo "<br>min". $temp1_min. "max ". $temp1_max; die;
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
                                        $temp1 = $temp1; //. speedConstants::TEMP_DEGREE;
                                    }
                                } else {
                                    $temp1_bad_reading++;
                                }
                            }
                            $temp1totalreadingcount++;
                        } else {
                            $temp1 = speedConstants::TEMP_NOTACTIVE;
                        }
                        $finalreport->temp1 = "" . round($temp1, 2);
                        // $tdstring = "<td>" . $temp1 . "</td>" . $tdstring;
                }
                /* Don't show the entire log of 1 min data when customer pulls out this interval for compliance only */
                if ($interval != "1") {
                    // $display .= $tdstring;
                    /* $finalreport->temp1= $temp1;
                $finalreport->temp2= $temp2;
                $finalreport->temp3= $temp3;
                 */
                }
                // $display .= '</tr>';
                $finaldata[] = $finalreport;
                $tr++;
            }
        }

        $t1 = $this->getName_ByType($vehicle->n1, $customerNo);
        $t2 = $this->getName_ByType($vehicle->n2, $customerNo);
        $t3 = $this->getName_ByType($vehicle->n3, $customerNo);
        $t4 = $this->getName_ByType($vehicle->n4, $customerNo);
        $t1 = ($t1 == '') ? 'Temperature 1' : $t1;
        $t2 = ($t2 == '') ? 'Temperature 2' : $t2;
        $t3 = ($t3 == '') ? 'Temperature 3' : $t3;
        $t4 = ($t4 == '') ? 'Temperature 4' : $t4;
        $temphtml = '';
        $span = null;

        $finalreport->t1 = $t1;
        // print_r($finalreport);
        $finalRecords = array();
        $statistics1 = array();
        $statistics2 = array();
        $statistics3 = array();
        $statistics4 = array();
// die;
        // echo "<br>switch no - ". $pass;
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

                $statistics4["temperature"] = $t4;
                $statistics4["temp_min_limit"] = $min_max_temp4['temp_min_limit'];
                $statistics4["temp_max_limit"] = $min_max_temp4['temp_max_limit'];

                $statistics4["temp4_min"] = $temp4_min;
                $statistics4["temp4_max"] = $temp4_max;

                $statistics4["temp4totalreadingcount"] = $temp4totalreadingcount;
                $statistics4["temp4_mute_reading"] = $temp4_mute_reading;
                $statistics4["temp4_non_comp_count"] = $temp4_non_comp_count;
                $statistics4["temp4_bad_reading"] = $temp4_bad_reading;
                $statistics4["abv_compliance"] = $abv_compliance;
                $statistics4["within_compliance"] = $within_compliance;

                $finalRecords['summary4'] = $statistics4;
            //// checked condition comming from session or tempselect(from select box)
            /* if((isset($arr[0]) && $arr[0]==4) && $arr[1]!="all" ){
            break;
             */
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

                $statistics3["temperature"] = $t3;
                $statistics3["temp_min_limit"] = $min_max_temp3['temp_min_limit'];
                $statistics3["temp_max_limit"] = $min_max_temp3['temp_max_limit'];

                $statistics3["temp3_min"] = $temp3_min;
                $statistics3["temp3_max"] = $temp3_max;

                $statistics3["temp3totalreadingcount"] = $temp3totalreadingcount;
                $statistics3["temp3_mute_reading"] = $temp3_mute_reading;
                $statistics3["temp3_non_comp_count"] = $temp3_non_comp_count;
                $statistics3["temp3_bad_reading"] = $temp3_bad_reading;
                $statistics3["abv_compliance"] = $abv_compliance;
                $statistics3["within_compliance"] = $within_compliance;

                $finalRecords['summary3'] = $statistics3;
            //// checked condition comming from session or tempselect(from select box)
            /* if((isset($arr[0]) && $arr[0]==3) && $arr[1]!="all" ){
            break;
             */
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

                $statistics2["temperature"] = $t2;
                $statistics2["temp_min_limit"] = $min_max_temp2['temp_min_limit'];
                $statistics2["temp_max_limit"] = $min_max_temp2['temp_max_limit'];

                $statistics2["temp2_min"] = $temp2_min;
                $statistics2["temp2_max"] = $temp2_max;

                $statistics2["temp2totalreadingcount"] = $temp2totalreadingcount;
                $statistics2["temp2_mute_reading"] = $temp2_mute_reading;
                $statistics2["temp2_non_comp_count"] = $temp2_non_comp_count;
                $statistics2["temp2_bad_reading"] = $temp2_bad_reading;
                $statistics2["abv_compliance"] = $abv_compliance;
                $statistics2["within_compliance"] = $within_compliance;

                $finalRecords['summary2'] = $statistics2;
            //// checked condition comming from session or tempselect(from select box)
            /* if((isset($arr[0]) && $arr[0]==2) && $arr[1]!="all" ){
            break;
             */
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

                $statistics1["temperature"] = $t1;
                // echo "t1 nljbj ".$t1;
                $statistics1["temp_min_limit"] = $min_max_temp1['temp_min_limit'];
                $statistics1["temp_max_limit"] = $min_max_temp1['temp_max_limit'];

                $statistics1["temp1_min"] = $temp1_min;
                $statistics1["temp1_max"] = $temp1_max;

                $statistics1["temp1totalreadingcount"] = $temp1totalreadingcount;
                $statistics1["temp1_mute_reading"] = $temp1_mute_reading;
                $statistics1["temp1_non_comp_count"] = $temp1_non_comp_count;
                $statistics1["temp1_bad_reading"] = $temp1_bad_reading;
                $statistics1["abv_compliance"] = $abv_compliance;
                $statistics1["within_compliance"] = $within_compliance;

                $finalRecords['summary1'] = $statistics1;
        }

        //$finalreport->statistics = $statistics;
        // print_r($statistics);

        $finalRecords['rows'] = $finaldata;

        //return $display;
        return $finalRecords;
    }

    public function getTotalTravelDistance($objReqData) {
        if (($objReqData->vehicleId > 0 || $objReqData->vehicleNo != "") && ($objReqData->customerNo > 0)) {
            $objReqData->startDateTime = isset($objReqData->startDateTime) ? $objReqData->startDateTime : today();
            $objReqData->endDateTime = isset($objReqData->endDateTime) ? $objReqData->endDateTime : today();
            if (strtotime($objReqData->endDateTime) >= strtotime($objReqData->startDateTime)) {
                $objVehManager = new VehicleManager($objReqData->customerNo);
                if ($objReqData->vehicleId > 0) {
                    $objRequest = $objVehManager->get_vehicle_details($objReqData->vehicleId);
                } elseif ($objReqData->vehicleNo != "") {
                    $objReq = $objVehManager->get_vehicle_by_vehno($objReqData->vehicleNo, 1);
                    if ($objReq != NULL) {
                        $objRequest = (object) $objReq[0];
                        $objReqData->vehicleId = $objRequest->vehicleid;
                    }
                }
                if (isset($objRequest) && isset($objRequest->unitno)) {
                    $objRequest->interval = isset($objReqData->interval) ? $objReqData->interval : 5;
                    $objRequest->temp_sensors = 2;
                    $objRequest->customerNo = $objReqData->customerNo;
                    $objRequest->startDate = convertDateToFormat($objReqData->startDateTime, speedConstants::DATE_Ymd);
                    $objRequest->startTime = convertDateToFormat($objReqData->startDateTime, speedConstants::TIME_His);
                    $objRequest->endDate = convertDateToFormat($objReqData->endDateTime, speedConstants::DATE_Ymd);
                    $objRequest->endTime = convertDateToFormat($objReqData->endDateTime, speedConstants::TIME_His);
                    $data = getTripData($objRequest->startDate, $objRequest->endDate, $objRequest->deviceid, $objRequest->interval, $objRequest->startTime, $objRequest->endTime, $objRequest->customerNo, $objRequest->temp_sensors);
                    foreach ($data AS $tempKey => $tempData) {
                        if (isset($data[$tempKey + 1]) && $data[$tempKey]->odometer > $data[($tempKey + 1)]->odometer) {
                            unset($data[($tempKey + 1)]);
                        }
                    }
                    /*
                    if (isset($objReqData->customParams['muteRanges']) && !empty($objReqData->customParams['muteRanges'])) {
                    foreach ($objReqData->customParams['muteRanges'] AS $range) {
                    foreach ($data AS $tempKey => $tempData) {
                    if (strtotime($tempData->starttime) > strtotime($range['starttime'])
                    && strtotime($tempData->starttime) < strtotime($range['endtime'])) {
                    unset($data[$tempKey]);
                    }
                    }
                    }
                    } */
                    if (isset($data) && !empty($data)) {
                        $startData = reset($data);
                        $lastData = end($data);
                        if (isset($startData->odometer) && isset($lastData->odometer)) {
                            if ($startData->odometer < $lastData->odometer) {
                                $distance = calculateOdometerDistance($startData->odometer, $lastData->odometer);
                            } else {
                                $max_array = array_reduce($data, function ($a, $b) {
                                    return $a ? ($a->odometer > $b->odometer ? $a : $b) : $b;
                                });
                                $distance = calculateOdometerDistance($startData->odometer, $max_array->odometer);
                            }
                        }
                        $objReqData->distance = $distance;
                    }
                }
            }
        }
        return $objReqData;
    }

    public function getName_ByType($nid, $customerNo) {
        $vehiclemanager = new VehicleManager($customerNo);
        $vehicledata = $vehiclemanager->getNameForTemp($nid);
        return $vehicledata;
    }

    public function getRouteDistanceAndTime($objReqData) {
        $routeData = array();

        if (($objReqData->customerNo > 0)) {
            $routeData = calculateRouteDistanceAndTime($objReqData->origin->lat, $objReqData->origin->lng, $objReqData->destination->lat, $objReqData->destination->lng);
        }
        return $routeData;
    }

    // </editor-fold>
}

?>