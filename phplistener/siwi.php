<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set("Asia/Kolkata");
$isErrorOccured = 0;
try
{
    $jdata = json_decode(file_get_contents('php://input'));
    if (isset($jdata)) {
        /*
        $gps_date = date('Y-m-d H:i:s', $jdata->info->dt);
        $unit_no = $jdata->uid;
        $date_time = $gps_date;
        $reason = $jdata->info->txn;
        $msg_serial_no = $jdata->info->msgid;
        $msg_key = $jdata->info->msgkey;
        $command_key = 0;
        $command_key_value = $jdata->info->cmdval;
        $gps_fixed = $jdata->gps->fix;
        $latitude = $jdata->gps->loc[0];
        $longitude = $jdata->gps->loc[1];
        $speed = $jdata->gps->speed;
        $altitude = $jdata->gps->alt;
        $direction = $jdata->gps->dir;
        $odometer = $jdata->gps->odo;
        $sat_mode = $jdata->gps->sat;
        $box_open = $jdata->io->box;
        $ignition = $jdata->io->ign;
        $digital_io = $jdata->io->gpi;
        $analog_in_1 = $jdata->io->sensor->temp[0];
        $power_cut = $jdata->pwr->main;
        $ext_batt_volt = 0; // Not used
        $in_batt = $jdata->pwr->volt;
        $gsm_register = $jdata->dbg->status[0];
        $gprs_register = $jdata->dbg->status[1];
        $gsm_strength = $jdata->dbg->status[2];
        $serverStatus = $jdata->dbg->status[3];
        $isOffline = $jdata->dbg->status[5];
        $hw_version = $jdata->dbg->ver[1];
        $sw_version = $jdata->dbg->ver[0];
        $analog_in_2 = 0;
        $analog_in_3 = 0;
        $analog_in_4 = 0;

        $x_address = "";
        $state = "";
        $district = "";
        $fuel_data = "";
        $current_landmark = "";
        $current_landmark_id = "";
        $last_snap = "";
         */

        $objParsedData = new stdClass();
        $objParsedData->lastUpdated = NULL;
        $objParsedData->status = NULL;
        $objParsedData->msgId = NULL;
        $objParsedData->msgKey = NULL;
        $objParsedData->commandKey = NULL;
        $objParsedData->commandKeyVal = NULL;

        $objParsedData->gpsFixed = NULL;
        $objParsedData->latValue = NULL;
        $objParsedData->lngValue = NULL;
        $objParsedData->speedValue = NULL;
        $objParsedData->satv = NULL;
        $objParsedData->altitude = NULL;
        $objParsedData->directionValue = NULL;
        $objParsedData->odometerValue = NULL;

        $objParsedData->tamper = NULL;
        $objParsedData->isIgnitionOn = NULL;
        $objParsedData->digitalIO = NULL;
        $objParsedData->analog = NULL;
        $objParsedData->analog1 = NULL;
        $objParsedData->analog2 = NULL;
        $objParsedData->analog3 = NULL;
        $objParsedData->analog4 = NULL;
        $objParsedData->sensorError = NULL;

        $objParsedData->isPowered = NULL;
        $objParsedData->extBatt = NULL;
        $objParsedData->inBatt = NULL;

        $objParsedData->gsmRegister = NULL;
        $objParsedData->gprsRegister = NULL;
        $objParsedData->gsmStrength = NULL;
        $objParsedData->network = NULL;
        $objParsedData->serverStatus = NULL;
        $objParsedData->isOffline = NULL;
        $objParsedData->swv = NULL;
        $objParsedData->hwv = NULL;
        $objParsedData->lib = NULL;

        $objParsedData->unitNo = $jdata->uid;

        if (isset($jdata->info)) {
            $objParsedData->lastUpdated = isset($jdata->info->dt) ? date('Y-m-d H:i:s', $jdata->info->dt) : NULL;
            $objParsedData->status = isset($jdata->info->txn) ? $jdata->info->txn : NULL;
            $objParsedData->msgId = isset($jdata->info->msgid) ? $jdata->info->msgid : NULL;
            $objParsedData->msgKey = isset($jdata->info->msgkey) ? $jdata->info->msgkey : NULL;
            $objParsedData->commandKey = isset($jdata->info->cmdkey) ? $jdata->info->cmdkey : NULL;
            $objParsedData->commandKeyVal = isset($jdata->info->cmdval) ? $jdata->info->cmdval : NULL;
        }

        if (isset($jdata->gps)) {
            $objParsedData->gpsFixed = isset($jdata->gps->fix) ? $jdata->gps->fix : NULL;
            $objParsedData->latValue = isset($jdata->gps->loc[0]) ? $jdata->gps->loc[0] : NULL;
            $objParsedData->lngValue = isset($jdata->gps->loc[1]) ? $jdata->gps->loc[1] : NULL;
            $objParsedData->speedValue = isset($jdata->gps->speed) ? $jdata->gps->speed : NULL;
            $objParsedData->satv = isset($jdata->gps->sat) ? $jdata->gps->sat : NULL;
            $objParsedData->altitude = isset($jdata->gps->alt) ? $jdata->gps->alt : NULL;
            $objParsedData->directionValue = isset($jdata->gps->dir) ? $jdata->gps->dir : NULL;
            $objParsedData->odometerValue = isset($jdata->gps->odo) ? $jdata->gps->odo : NULL;
        }

        if (isset($jdata->io)) {
            $objParsedData->tamper = isset($jdata->io->box) ? $jdata->io->box : NULL;
            $objParsedData->isIgnitionOn = isset($jdata->io->ign) ? $jdata->io->ign : NULL;
            $objParsedData->digitalIO = isset($jdata->io->gpi) ? $jdata->io->gpi : NULL;
            $objParsedData->analog = isset($jdata->io->analog) ? $jdata->io->analog : NULL;
            if (isset($jdata->io->sensor)) {
                $objParsedData->analog1 = isset($jdata->io->sensor->temp[0]) ? $jdata->io->sensor->temp[0] : NULL;
                $objParsedData->analog2 = isset($jdata->io->sensor->temp[1]) ? $jdata->io->sensor->temp[1] : NULL;
                $objParsedData->analog3 = isset($jdata->io->sensor->temp[2]) ? $jdata->io->sensor->temp[2] : NULL;
                $objParsedData->analog4 = isset($jdata->io->sensor->temp[3]) ? $jdata->io->sensor->temp[3] : NULL;
                $objParsedData->sensorError = isset($jdata->io->sensor->err) ? $jdata->io->sensor->err : NULL;
            }
        }

        if (isset($jdata->tsensor)) {
            $objParsedData->analog1 = isset($jdata->tsensor[0]) ? $jdata->tsensor[0]->temp : NULL;
            $objParsedData->analog2 = isset($jdata->tsensor[1]) ? $jdata->tsensor[1]->temp : NULL;
            $objParsedData->analog3 = isset($jdata->tsensor[2]) ? $jdata->tsensor[2]->temp : NULL;
            $objParsedData->analog4 = isset($jdata->tsensor[3]) ? $jdata->tsensor[3]->temp : NULL;
        }

        if (isset($jdata->thsensor)) {
            /* assuming that the analog1 would already contain some temperature or other data. Hence, temperature would always be present in analog 2 */
            $objParsedData->analog2 = isset($jdata->thsensor[0]) ? $jdata->thsensor[0]->temp : NULL;
            /* By default only 1 humidity sensor would be present nd would be there in analog 4 */
            $objParsedData->analog4 = isset($jdata->thsensor[0]) ? $jdata->thsensor[0]->humid : NULL;

            /* There is other temperature and humidity sensor value too but as of now not sure where iit needs to be captured */
            //$objParsedData->analog2 = isset($jdata->thsensor[1]) ? $jdata->thsensor[1]->temp : NULL;
        }

        if (isset($jdata->pwr)) {
            $objParsedData->isPowered = isset($jdata->pwr->main) ? $jdata->pwr->main : NULL;
            $objParsedData->extBatt = isset($jdata->pwr->batt) ? $jdata->pwr->batt : NULL;
            $objParsedData->inBatt = isset($jdata->pwr->volt) ? $jdata->pwr->volt : NULL;
        }

        if (isset($jdata->dbg)) {
            $objParsedData->gsmRegister = isset($jdata->dbg->status[0]) ? $jdata->dbg->status[0] : NULL;
            $objParsedData->gprsRegister = isset($jdata->dbg->status[1]) ? $jdata->dbg->status[1] : NULL;
            $objParsedData->gsmStrength = isset($jdata->dbg->status[2]) ? $jdata->dbg->status[2] : NULL;
            $objParsedData->network = isset($jdata->dbg->status[3]) ? $jdata->dbg->status[3] : NULL;
            $objParsedData->serverStatus = isset($jdata->dbg->status[4]) ? $jdata->dbg->status[4] : NULL;
            $objParsedData->isOffline = isset($jdata->dbg->status[5]) ? $jdata->dbg->status[5] : NULL;
            $objParsedData->swv = isset($jdata->dbg->ver[0]) ? $jdata->dbg->ver[0] : NULL;
            $objParsedData->hwv = isset($jdata->dbg->ver[1]) ? $jdata->dbg->ver[1] : NULL;
            $objParsedData->lib = isset($jdata->dbg->lib) ? $jdata->dbg->lib : NULL;
        }
    } else {
        $isErrorOccured = 1;
    }
} catch (Exception $ex) {
    $isErrorOccured = 1;
    //log exception
}

if ($isErrorOccured == 0) {
    /*
    $db_hostname = "localhost";
    $db_loginname = "UserSpeed";
    $db_loginpassword = "el!365x!@";
    //$db_loginname = "root";
    //$db_loginpassword = "";
    $db_databasename = "speed";

    $link = mysqli_connect($db_hostname, $db_loginname, $db_loginpassword, $db_databasename);
    if (!$link) {
    die('Could not connect: ' . mysqli_error());
    }
    @mysqli_select_db($link, $db_databasename) or die("Unable to select database");

    $sql = "INSERT INTO trackdata (unitid, msgid, txnreason, cmdkey, cmdkeyval, ign, power, cover, msgkey, odom, speed, gpsmode, gpsfix, lat, lng, alt, dir, dt, location, state, district, `signal`, GSMstatus, GPRSstatus, ServerStatus, Inbatt, Exbatt, Digi, analog1, analog2, analog3, analog4, hwv, swv, type, insertdt, current_landmark, current_landmark_id, fuel_data, last_snap) VALUES ('" . addslashes($unit_no) . "', '" . addslashes($msg_serial_no) . "', '" . addslashes($reason) . "', '" . addslashes($command_key) . "', '" . addslashes($command_key_value) . "', '" . addslashes($ignition) . "', '" . addslashes($power_cut) . "', '" . addslashes($box_open) . "', '" . addslashes($msg_key) . "', '" . addslashes($odometer) . "', '" . addslashes($speed) . "', '" . addslashes($sat_mode) . "', '" . addslashes($gps_fixed) . "', '" . addslashes($latitude) . "', '" . addslashes($longitude) . "', '" . addslashes($altitude) . "', '" . addslashes($direction) . "', '" . addslashes($gps_date) . "', '" . addslashes($x_address) . "', '" . addslashes($state) . "', '" . addslashes($district) . "', '" . addslashes($gsm_strength) . "', '" . addslashes($gsm_register) . "', '" . addslashes($gprs_register) . "', '" . addslashes($serverStatus) . "', '" . addslashes($in_batt) . "', '" . addslashes($ext_batt_volt) . "', '" . addslashes($digital_io) . "', '" . addslashes($analog_in_1) . "', '" . addslashes($analog_in_2) . "', '" . addslashes($analog_in_3) . "', '" . addslashes($analog_in_4) . "', '" . addslashes($hw_version) . "', '" . addslashes($sw_version) . "', '$isOffline', '" . date('Y-m-d H:i:s') . "', '" . addslashes($current_landmark) . "', '" . addslashes($current_landmark_id) . "', '" . addslashes($fuel_data) . "', '" . addslashes($last_snap) . "')";
    mysqli_query($link, $sql);

    mysqli_close($link);
     */

    if (!isset($RELATIVE_PATH_DOTS) || $RELATIVE_PATH_DOTS == "") {
        $RELATIVE_PATH_DOTS = "../";
    }
    require_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
    require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
    require_once $RELATIVE_PATH_DOTS . 'lib/system/Log.php';

    /*
    if ($objParsedData->unitNo == '1818010019309'
    || $objParsedData->unitNo == '1803010016588'
    || $objParsedData->unitNo == '1820010019321'
    || $objParsedData->unitNo == '1818010019259'
    || $objParsedData->unitNo == '1818010019291'
    || $objParsedData->unitNo == '1747010016470'
    || $objParsedData->unitNo == '1803010016570'
    || $objParsedData->unitNo == '1818010019283'
    || $objParsedData->unitNo == '1818010019275'
    || $objParsedData->unitNo == '1803010016562'
    || $objParsedData->unitNo == '1832010021595'
    || $objParsedData->unitNo == '1832010021611'
    || $objParsedData->unitNo == '1832010021645'
    || $objParsedData->unitNo == '1832010021629'
    || $objParsedData->unitNo == '1803010016562'
    || $objParsedData->unitNo == '1803010016612'
    || $objParsedData->unitNo == '1818010019267'
    || $objParsedData->unitNo == '1818010019317'
    ) {
    ExecuteAttendanceProcess($objParsedData, $jdata);
    }
     */
    //Initialize customer as 0 as it is mandate in constructor. It would not be used in getUnitDetailsForListener
    $customerno = 0;
    $objUnitManager = new UnitManager($customerno);
    $objDailyReportManager = new DailyReportManager($customerno);
    $arrUnitDetails = $objUnitManager->getUnitDetails_Listener($objParsedData);

    foreach ($arrUnitDetails as $arrUnitDetail) {
        $arrReqdDeviceDetails = $objUnitManager->getReqdDeviceDetailsBeforeUpdate_Listener($arrUnitDetail);
        if (isset($arrUnitDetail)) {
            $objDeviceData = new stdClass();
            $objDeviceData->unitid = $arrUnitDetail[0];
            $objDeviceData->customerno = $arrUnitDetail[1];
            $objDeviceData->unitno = $arrUnitDetail[2];
            $objDeviceData->command = $arrUnitDetail[3];
            $objDeviceData->setcom = $arrUnitDetail[4];

            $objDeviceData->timediff = $arrReqdDeviceDetails[0];
            $objDeviceData->vehicleid = $arrReqdDeviceDetails[1];
            $objDeviceData->vehicleno = $arrReqdDeviceDetails[2];
            $objDeviceData->driverid = $arrReqdDeviceDetails[3];
            $objDeviceData->overspeed_limit = $arrReqdDeviceDetails[4];
            $objDeviceData->stoppage_odometer = $arrReqdDeviceDetails[5];
            $objDeviceData->stoppage_flag = $arrReqdDeviceDetails[6];
            $objDeviceData->prev_digitalio = $arrReqdDeviceDetails[7];
            $objDeviceData->prev_odometer = $arrReqdDeviceDetails[8];
            $objDeviceData->deviceid = $arrReqdDeviceDetails[9];
            $objDeviceData->ignition_wirecut = $arrReqdDeviceDetails[10];
            $objDeviceData->kind = $arrReqdDeviceDetails[11];
            $objDeviceData->hasDeliverySwitch = $arrReqdDeviceDetails[12];
            $objDeviceData->previousAnalog1Value = $arrReqdDeviceDetails[13];

            $objDeviceData->devicelat = $objParsedData->latValue;
            $objDeviceData->devicelong = $objParsedData->lngValue;
            $objDeviceData->altitude = $objParsedData->altitude;
            $objDeviceData->directionchange = $objParsedData->directionValue;
            $objDeviceData->satv = $objParsedData->satv;
            $objDeviceData->inbatt = $objParsedData->inBatt;
            $objDeviceData->hwv = $objParsedData->hwv;
            $objDeviceData->swv = $objParsedData->swv;
            $objDeviceData->msgid = $objParsedData->msgId;
            $objDeviceData->status = $objParsedData->status;
            $objDeviceData->ignition = $objParsedData->isIgnitionOn;
            $objDeviceData->powercut = $objParsedData->isPowered;
            $objDeviceData->tamper = $objParsedData->tamper;
            $objDeviceData->isOffline = $objParsedData->isOffline;
            $objDeviceData->gpsfixed = $objParsedData->gpsFixed;
            $objDeviceData->gsmstrength = $objParsedData->gsmStrength;
            $objDeviceData->gsmregister = $objParsedData->gsmRegister;
            $objDeviceData->gprsregister = $objParsedData->gprsRegister;
            $objDeviceData->extbatt = $objParsedData->extBatt;
            $objDeviceData->odometer = $objParsedData->odometerValue;
            $objDeviceData->speed = $objParsedData->speedValue;
            $objDeviceData->analog1 = $objParsedData->analog1;
            $objDeviceData->analog2 = $objParsedData->analog2;
            $objDeviceData->analog3 = $objParsedData->analog3;
            $objDeviceData->analog4 = $objParsedData->analog4;
            $objDeviceData->digitalio = $objParsedData->digitalIO;
            $objDeviceData->commandkey = $objParsedData->commandKey;
            $objDeviceData->commandkeyval = $objParsedData->commandKeyVal;
            $lastUpdated = DateTime::createFromFormat(speedConstants::DEFAULT_TIMESTAMP, $objParsedData->lastUpdated);
            //print_r($lastUpdated);die();
            $isNegativeTimeInterval = 0;
            if ($objDeviceData->timediff < 0) {
                $isNegativeTimeInterval = 1;
            }
            $interval = new DateInterval('PT' . abs($objDeviceData->timediff) . 'S');
            $interval->invert = $isNegativeTimeInterval;
            $lastUpdated->add($interval);
            $deviceYear = $lastUpdated->format("Y");
            $objDeviceData->timestamp = $lastUpdated->format(speedConstants::DEFAULT_TIMESTAMP);
            $objDeviceData->isPacketTimeValid = 1;
            $objDeviceData->alterremark = "";

            $todaysDateTime = new DateTime();
            $todaysDateTime->add($interval);
            $currentYear = $todaysDateTime->format("Y");
            $previousYear = $currentYear - 1;

            //TODO: Check year of lastUpdated parameter before setting below parameter

            if ($objDeviceData->timestamp == NULL || ($deviceYear != $currentYear && $deviceYear != $previousYear)) {
                $objDeviceData->timestamp = $todaysDateTime->format(speedConstants::DEFAULT_TIMESTAMP);
                $objDeviceData->isPacketTimeValid = 0;
            }

            //print_r($objDeviceData);

            if ($objDeviceData->isPacketTimeValid == 0) {
                $objDeviceData->gpsfixed = "V";
            }
            //print_r($objDeviceData);
            $isUpdated = $objUnitManager->UpdateDeviceDataInMySQL_Listener($objDeviceData);

            /* Daily Report */

            if ($objDeviceData->gpsfixed == "A" && $objDeviceData->isOffline == 0) {
                $objRequest = new stdClass();
                $objRequest->timestamp = $objDeviceData->timestamp;
                $objRequest->customerno = $objDeviceData->customerno;
                $objRequest->vehicleid = $objDeviceData->vehicleid;
                $objRequest->unitid = $objDeviceData->unitid;
                $objRequest->devicelat = $objDeviceData->devicelat;
                $objRequest->devicelong = $objDeviceData->devicelong;
                $objRequest->odometer = $objDeviceData->odometer;
                $objRequest->driverid = $objDeviceData->driverid;
                $objRequest->daily_date = $lastUpdated->format(speedConstants::DATE_Ymd);

                $arrDailyReportResult = $objDailyReportManager->GetDailyReport_Listener($objRequest);

                // $arrDailyReportResult = $objDailyReportManager->GetDailyReport_Listener($objDeviceData);
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
                    if ($objDeviceData->speed > $objRequest->topspeed) {
                        $objRequest->topspeed = $objDeviceData->speed;
                        $objRequest->topspeed_lat = $objRequest->devicelat;
                        $objRequest->topspeed_long = $objRequest->devicelong;
                        $objRequest->topspeed_time = $objRequest->timestamp;
                    }
                    # Harsh Break
                    if ($objDeviceData->status == 'S' && $objRequest->flag_harsh_break == 0) {
                        $objRequest->harsh_break = $objRequest->harsh_break + 1;
                        $objRequest->flag_harsh_break = 1;
                    }
                    if ($objDeviceData->status != 'S') {
                        $objRequest->flag_harsh_break = 0;
                    }
                    # Sudden Acceleration
                    if ($objDeviceData->status == 'U' and $objRequest->flag_sudden_acc == 0) {
                        $objRequest->sudden_acc = $objRequest->sudden_acc + 1;
                        $objRequest->flag_sudden_acc = 1;
                    }
                    if ($objDeviceData->status != 'U') {
                        $objRequest->flag_sudden_acc = 0;
                    }
                    # Towing
                    if (($objDeviceData->status == 'V' || ($objDeviceData->ignition == 0 && $objDeviceData->speed > 10)) && str($objRequest->flag_towing) == '0') {
                        $objRequest->towing = $objRequest->towing + 1;
                        $objRequest->flag_towing = 1;
                    }
                    if ($objDeviceData->status != 'V' && (($objDeviceData->ignition == 0 && $objDeviceData->speed < 10) || $objDeviceData->ignition == 1)) {
                        $objRequest->flag_towing = 0;
                    }
                    # Max Odometer Reading
                    if ($objDeviceData->odometer > $objRequest->max_odometer) {
                        $objRequest->max_odometer = $objDeviceData->odometer;
                    }

                    if ($todaysDateTime->format("Y-m-d") == $objRequest->daily_date) {
                        $objDailyReportManager->UpdateDailyReport_Listener($objRequest);
                    } else {
                        $objDailyReportManager->InsertDailyReport_Listener($objRequest);
                    }
                } else {
                    $objDailyReportManager->InsertDailyReport_Listener($objRequest);
                }
            }

            /* Get the raw data from sqlite */
            $rawData = json_encode($jdata);
            $objSqlite = new SqliteManager($objDeviceData->customerno, $objDeviceData->unitno, $objDeviceData->timestamp);
            $objSqlite->InsertDataInSqlite($rawData, $objDeviceData);
            /* Send Command if applicable */
            if (isset($objDeviceData->command) && isset($objDeviceData->setcom) &&
                $objDeviceData->setcom == 1 && $objDeviceData->command != "") {
                $serverResponse = array('result' => 'true', 'msg' => 'Data Received', 'cmd' => $objDeviceData->command);
                $command = new stdClass();
                $command->uid = $objDeviceData->unitid;
                $command->command = "";
                $command->setcom = 0;
                $objUnitManager->setCommand($command);
            } else {
                $serverResponse = array('result' => 'true', 'msg' => 'Data Received');
                //$serverResponse = '{"result":true,"msg":"Data Received"}';
            }
        } else {
            $serverResponse = array('result' => 'false', 'msg' => 'Unable to decode json');
        }
    }
} else {
    $serverResponse = array('result' => 'false', 'msg' => 'Error Receiving data');
}

echo json_encode($serverResponse);

function ExecuteAttendanceProcess($objParsedData, $jdata) {
    echo $todaysdate = date('Y-m-d H:i:s');
    $currentYear = date('Y');
    $previousYear = $currentYear - 1;
    $arrUnitDetails = getUnitDetails_ATListener($objParsedData);
    foreach ($arrUnitDetails as $arrUnitDetail) {
        $objDeviceData = new stdClass();
        $objDeviceData->unitId = $arrUnitDetail["uid"];
        $objDeviceData->customerNo = $arrUnitDetail["customerno"];
        $objDeviceData->unitNo = $arrUnitDetail["unitno"];

        echo $objDeviceData->lastUpdated = $objParsedData->lastUpdated;
        $objDeviceData->deviceStatus = $objParsedData->status;
        //$objDeviceData->msgId = $objParsedData->msgId;
        //$objDeviceData->msgKey = $objParsedData->msgKey;
        $objDeviceData->commandKey = $objParsedData->commandKey;
        $objDeviceData->commandKeyVal = $objParsedData->commandKeyVal;
        $objDeviceData->isCardSwiped = 0;
        if ($objDeviceData->deviceStatus == 'W' AND $objDeviceData->commandKey = 'RFID') {
            $objDeviceData->isCardSwiped = 1;
        }

        $objDeviceData->gpsFixed = $objParsedData->gpsFixed;
        $objDeviceData->deviceLat = $objParsedData->latValue;
        $objDeviceData->deviceLng = $objParsedData->lngValue;
        $objDeviceData->speed = $objParsedData->speedValue;
        $objDeviceData->odometer = $objParsedData->odometerValue;

        $objDeviceData->altitude = $objParsedData->altitude;
        $objDeviceData->directionChange = $objParsedData->directionValue;
        $objDeviceData->satv = $objParsedData->satv;

        $objDeviceData->tamper = $objParsedData->tamper;
        $objDeviceData->ignition = $objParsedData->isIgnitionOn;
        $objDeviceData->digitalIO = $objParsedData->digitalIO;
        $objDeviceData->analog = $objParsedData->analog;

        $objDeviceData->isPowered = $objParsedData->isPowered;
        $objDeviceData->inbatt = $objParsedData->inBatt;
        $objDeviceData->extbatt = $objParsedData->extBatt;

        $objDeviceData->gsmRegister = $objParsedData->gsmRegister;
        $objDeviceData->gprsRegister = $objParsedData->gprsRegister;
        $objDeviceData->gsmStrength = $objParsedData->gsmStrength;
        $objDeviceData->network = $objParsedData->network;
        $objDeviceData->serverStatus = $objParsedData->serverStatus;
        $objDeviceData->isOffline = $objParsedData->isOffline;

        $objDeviceData->hwv = $objParsedData->hwv;
        $objDeviceData->swv = $objParsedData->swv;
        $objDeviceData->fwv = $objParsedData->fwv;

        $lastUpdatedDate = DateTime::createFromFormat("Y-m-d H:i:s", $objParsedData->lastUpdated);
        $lastUpdatedYear = $lastUpdatedDate->format("Y");

        $objDeviceData->isPacketTimeValid = 1;
        if ($lastUpdatedYear != $currentYear && $lastUpdatedYear != $previousYear) {
            $objDeviceData->isPacketTimeValid = 0;
            $objDeviceData->lastUpdated = $todaysdate;
        }

        print_r($objDeviceData);
        $objResult = new stdClass();
        $objResult = UpdateDeviceDataInMySQL_ATListener($objDeviceData);
        //die();
        $currentDate = DateTime::createFromFormat("Y-m-d H:i:s", $todaysdate);
        $diffInterval = $lastUpdatedDate->diff($currentDate);
        echo $noOfHours = $diffInterval->h + ($diffInterval->days * 24);

        /* Send SMS only if person name, parent name and contact number is present and checkin status is not 0 */
        if (isset($objResult->parentName) && isset($objResult->personName) && isset($objResult->contactNumber) && isset($objResult->checkInStatus) && $objResult->checkInStatus != 0 && $noOfHours < 1) {
            $objSMSRequest = new stdClass();
            $objSMSRequest->personId = $objResult->personId;
            $objSMSRequest->cardId = $objResult->cardId;
            if ($objResult->checkInStatus == 1) {
                $checkInStatus = "checked in";
                $objSMSRequest->isCheckInSMSSent = 1;
            } elseif ($objResult->checkInStatus == 2) {
                $checkInStatus = "checked out";
                $objSMSRequest->isCheckOutSMSSent = 1;
            }
            $SMSTemplate = "Dear {{PARENT_NAME}}, {{PEOPLE_NAME}} has {{CHECKIN_STATUS}} at {{CHECKIN_TIME}}. Message sent at {{TIMESTAMP}}";
            $message = str_replace("{{PARENT_NAME}}", $objResult->parentName, $SMSTemplate);
            $message = str_replace("{{PEOPLE_NAME}}", $objResult->personName, $message);
            $message = str_replace("{{CHECKIN_STATUS}}", $checkInStatus, $message);
            $message = str_replace("{{CHECKIN_TIME}}", date("d-m-Y h:i a", strtotime($objDeviceData->lastUpdated)), $message);
            $message = str_replace("{{TIMESTAMP}}", date("d-m-Y h:i a", strtotime($todaysdate)), $message);

            $objSMSRequest->customerNo = $objDeviceData->customerNo;
            $objSMSRequest->mobileNo = $objResult->contactNumber;
            $objSMSRequest->message = $message;
            $smsStatus = getSMSStatus_ATListener($objSMSRequest);
            echo $smsStatus;
            $smsProviderResponse = "";
            if ($smsStatus == 1) {
                echo $SMS_URL_Attendance = "http://smpp.keepintouch.co.in/vendorsms/pushsms.aspx?user=elixia_attendance&password=elixia@123&msisdn={{PHONENO}}&sid=ELIXIA&msg={{MESSAGETEXT}}&fl=0&gwid=2";
                $isSmsSent = sendSMSUtil(array($objResult->contactNumber), $message, $smsProviderResponse, $SMS_URL_Attendance);
                if ($isSmsSent == 1) {
                    $objSMSRequest->smsProviderResponse = $smsProviderResponse;
                    $objSMSRequest->isSmsSent = $isSmsSent;
                    smsPostProcess_ATListener($objSMSRequest);
                }
            }
        } else {
            $serverResponse = array('result' => 'false', 'msg' => 'No SMS details Received or person have checked out before 15 mins');
        }
    }
    if (!isset($serverResponse)) {
        $serverResponse = array('result' => 'true', 'msg' => 'Data Received');
    }
}

function CreatePDOConn_ATListener() {
    $db_hostname = "13.232.106.239";
    //$db_loginname = "ATOfficeUser";
    $db_loginname = "ElixiaSpeedUser";
    $db_loginpassword = "el!365x!@";
    //$db_loginname = "root";
    //$db_loginpassword = "";
    $db_databasename = "uat_attendance";

    $dsn = 'mysql:dbname=' . $db_databasename . ';host=' . $db_hostname . '';
    $pdo = new PDO($dsn, $db_loginname, $db_loginpassword);
    return $pdo;
}

function ClosePDOConn_ATListener() {
    $pdo = NULL;
    return $pdo;
}

function getUnitDetails_ATListener($objParsedData) {
    $pdo = CreatePDOConn_ATListener();
    $query = "SELECT unit.uid, unit.customerno, unitno
                      FROM unit
                      INNER JOIN devices ON unit.uid = devices.uid
                      WHERE unit.unitno = '%s'";
    $queryCallSP = sprintf($query, $objParsedData->unitNo);
    $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
    ClosePDOConn_ATListener($pdo);
    print_r($arrResult);
    return $arrResult;
}

function UpdateDeviceDataInMySQL_ATListener($objDeviceData) {
    $isUpdated = 0;
    $todaysdate = date('Y-m-d H:i:s');
    /*
    Usually, when timestamp is invalid, GPSfixed should be "V". But, sometimes it is A. Hence,hardcoding to "A"
    If timestamp is not valid we would not like to consider any other data except lastupdated.
     */
    if ($objDeviceData->isPacketTimeValid == 0) {
        $gpsfixed = "V";
    }

    $pdo = CreatePDOConn_ATListener();
    $sp_params = "'" . $objDeviceData->unitId . "'"
    . ",'" . $objDeviceData->lastUpdated . "'"
    . ",'" . $objDeviceData->deviceStatus . "'"
    . ",'" . $objDeviceData->commandKey . "'"
    . ",'" . $objDeviceData->commandKeyVal . "'"
    . ",'" . $objDeviceData->gpsFixed . "'"
    . ",'" . $objDeviceData->deviceLat . "'"
    . ",'" . $objDeviceData->deviceLng . "'"
    . ",'" . $objDeviceData->speed . "'"
    . ",'" . $objDeviceData->odometer . "'"
    . ",'" . $objDeviceData->altitude . "'"
    . ",'" . $objDeviceData->directionChange . "'"
    . ",'" . $objDeviceData->satv . "'"
    . ",'" . $objDeviceData->tamper . "'"
    . ",'" . $objDeviceData->ignition . "'"
    . ",'" . $objDeviceData->digitalIO . "'"
    . ",'" . $objDeviceData->analog . "'"
    . ",'" . $objDeviceData->isPowered . "'"
    . ",'" . $objDeviceData->inbatt . "'"
    . ",'" . $objDeviceData->extbatt . "'"
    . ",'" . $objDeviceData->gsmRegister . "'"
    . ",'" . $objDeviceData->gprsRegister . "'"
    . ",'" . $objDeviceData->gsmStrength . "'"
    . ",'" . $objDeviceData->network . "'"
    . ",'" . $objDeviceData->serverStatus . "'"
    . ",'" . $objDeviceData->isOffline . "'"
    . ",'" . $objDeviceData->hwv . "'"
    . ",'" . $objDeviceData->swv . "'"
    . ",'" . $objDeviceData->fwv . "'"
    . ",'" . $objDeviceData->isCardSwiped . "'"
    . ",'" . $objDeviceData->customerNo . "'"
        . ",'" . $todaysdate . "'";
    echo $queryCallSP = "CALL listener_update_device_details(" . $sp_params . ")";
    //die();
    $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
    $objResult = (object) $arrResult;
    print_r($objResult);
    ClosePDOConn_ATListener($pdo);
    return $objResult;
}

function getSMSStatus_ATListener($objRequest) {
    $smsStatus = 1;
    $todaysdate = date('Y-m-d H:i:s');
    try {
        echo $queryCheckSMSSentStatus = "SELECT isCheckInSMSSent, isCheckOutSMSSent FROM attendance WHERE personId = $objRequest->personId AND cardId = $objRequest->cardId AND DATE(createdOn) = DATE('$todaysdate') ORDER BY createdOn DESC LIMIT 1;";
        echo $querySMSCount = "SELECT count(smsId) as smsCount FROM smslog WHERE personId = $objRequest->personId AND DATE(createdOn) = DATE('$todaysdate') GROUP BY personId, DATE(createdOn);";

        $pdo = CreatePDOConn_ATListener();
        $arrResultCheckSMSSentStatus = $pdo->query($queryCheckSMSSentStatus)->fetch(PDO::FETCH_ASSOC);
        $arrResultSMSCount = $pdo->query($querySMSCount)->fetch(PDO::FETCH_ASSOC);
        ClosePDOConn_ATListener($pdo);
        print_r($arrResultCheckSMSSentStatus);
        if (isset($arrResultCheckSMSSentStatus) && $arrResultCheckSMSSentStatus['isCheckInSMSSent'] == 1 && $arrResultCheckSMSSentStatus['isCheckOutSMSSent'] == 1 && isset($arrResultSMSCount) && $arrResultSMSCount['smsCount'] > 2) {
            $smsStatus = -1;
        }
    } catch (Exception $ex) {
        echo "Exception occurred.";
        //print_r($ex);
    }
    return $smsStatus;
}

function smsPostProcess_ATListener($objRequest) {
    print_r($objRequest);
    $todaysdate = date('Y-m-d H:i:s');
    try {
        if (isset($objRequest->isCheckInSMSSent) && $objRequest->isCheckInSMSSent == 1) {
            echo $query = "UPDATE attendance SET updatedOn = '$todaysdate', isCheckInSMSSent = 1 WHERE personId = $objRequest->personId AND cardId = $objRequest->cardId AND DATE(createdOn) = DATE('$todaysdate') ORDER BY createdOn DESC LIMIT 1;";
        }
        if (isset($objRequest->isCheckOutSMSSent) && $objRequest->isCheckOutSMSSent == 1) {
            echo $query = "UPDATE attendance SET updatedOn = '$todaysdate', isCheckOutSMSSent = 1 WHERE personId = $objRequest->personId AND cardId = $objRequest->cardId AND DATE(createdOn) = DATE('$todaysdate') ORDER BY createdOn DESC LIMIT 1;";
        }

        $pdo = CreatePDOConn_ATListener();
        $pdo->query($query);

        //Insert Into SMS log
        $sp_params = "'" . $objRequest->mobileNo . "'"
        . ",'" . $objRequest->message . "'"
        . ",'" . $objRequest->smsProviderResponse . "'"
        . ",'" . $objRequest->personId . "'"
        . ",'" . $objRequest->isSmsSent . "'"
        . ",'" . $objRequest->customerNo . "'"
            . ",'" . $todaysdate . "'"
            . "," . '@currentSmsLogId';
        echo $queryCallSP = "CALL insert_smsLog" . "($sp_params)";
        $pdo->query($queryCallSP);
        $result = $pdo->query('SELECT @currentSmsLogId')->fetch(PDO::FETCH_ASSOC);
        $currentCardId = $result["@currentSmsLogId"];

        ClosePDOConn_ATListener($pdo);
    } catch (Exception $ex) {
        echo "Exception occurred";
    }
}

?>