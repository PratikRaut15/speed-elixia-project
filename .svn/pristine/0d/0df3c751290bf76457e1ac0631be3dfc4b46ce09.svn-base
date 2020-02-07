<?php
error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
$isErrorOccured = 0;
try
{

    $jdata = json_decode(file_get_contents('php://input'));

    if (count($jdata) > 0) {
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
        $ext_batt_volt = 0; /* Not used */
        $in_batt = $jdata->pwr->volt;
        $gsm_register = $jdata->dbg->status[0];
        $gprs_register = $jdata->dbg->status[1];
        $gsm_strength = $jdata->dbg->status[2];
        $server_avail = $jdata->dbg->status[3];
        $data_type = $jdata->dbg->status[5];
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

        $objParsedData = new stdClass();
        $objParsedData->unitNo = $jdata->uid;
        $objParsedData->lastUpdated = date('Y-m-d H:i:s', $jdata->info->dt);
        $objParsedData->status = $jdata->info->txn;
        $objParsedData->msgId = $jdata->info->msgid;
        $objParsedData->msgKey = $jdata->info->msgkey;
        $objParsedData->commandKey = 0;
        $objParsedData->commandKeyVal = $jdata->info->cmdval;
        $objParsedData->gpsFixed = $jdata->gps->fix;
        $objParsedData->latValue = $jdata->gps->loc[0];
        $objParsedData->lngValue = $jdata->gps->loc[1];
        $objParsedData->speedValue = $jdata->gps->speed;
        $objParsedData->altitude = $jdata->gps->alt;
        $objParsedData->directionValue = $jdata->gps->dir;
        $objParsedData->odometerValue = $jdata->gps->odo;
        $objParsedData->satv = $jdata->gps->sat;
        $objParsedData->tamper = $jdata->io->box;
        $objParsedData->isIgnitionOn = $jdata->io->ign;
        $objParsedData->digitalIO = $jdata->io->gpi;
        $objParsedData->analog1 = $jdata->io->sensor->temp[0];
        $objParsedData->isPowered = $jdata->pwr->main;
        $objParsedData->extBatt = 0; /* Not used */
        $objParsedData->inBatt = $jdata->pwr->volt;
        $objParsedData->gsmRegister = $jdata->dbg->status[0];
        $objParsedData->gprsRegister = $jdata->dbg->status[1];
        $objParsedData->gsmStrength = $jdata->dbg->status[2];
        $objParsedData->server_avail = $jdata->dbg->status[3];
        $objParsedData->data_type = $jdata->dbg->status[5];
        $objParsedData->hwv = $jdata->dbg->ver[1];
        $objParsedData->swv = $jdata->dbg->ver[0];
        $objParsedData->analog2 = 0;
        $objParsedData->analog3 = 0;
        $objParsedData->analog4 = 0;
    } else {
        $isErrorOccured = 1;
    }
} catch (Exception $ex) {
    $isErrorOccured = 1;
    //log exception
}

if ($isErrorOccured == 0) {
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

    $sql = "INSERT INTO trackdata (unitid, msgid, txnreason, cmdkey, cmdkeyval, ign, power, cover, msgkey, odom, speed, gpsmode, gpsfix, lat, lng, alt, dir, dt, location, state, district, `signal`, GSMstatus, GPRSstatus, ServerStatus, Inbatt, Exbatt, Digi, analog1, analog2, analog3, analog4, hwv, swv, type, insertdt, current_landmark, current_landmark_id, fuel_data, last_snap) VALUES ('" . addslashes($unit_no) . "', '" . addslashes($msg_serial_no) . "', '" . addslashes($reason) . "', '" . addslashes($command_key) . "', '" . addslashes($command_key_value) . "', '" . addslashes($ignition) . "', '" . addslashes($power_cut) . "', '" . addslashes($box_open) . "', '" . addslashes($msg_key) . "', '" . addslashes($odometer) . "', '" . addslashes($speed) . "', '" . addslashes($sat_mode) . "', '" . addslashes($gps_fixed) . "', '" . addslashes($latitude) . "', '" . addslashes($longitude) . "', '" . addslashes($altitude) . "', '" . addslashes($direction) . "', '" . addslashes($gps_date) . "', '" . addslashes($x_address) . "', '" . addslashes($state) . "', '" . addslashes($district) . "', '" . addslashes($gsm_strength) . "', '" . addslashes($gsm_register) . "', '" . addslashes($gprs_register) . "', '" . addslashes($server_avail) . "', '" . addslashes($in_batt) . "', '" . addslashes($ext_batt_volt) . "', '" . addslashes($digital_io) . "', '" . addslashes($analog_in_1) . "', '" . addslashes($analog_in_2) . "', '" . addslashes($analog_in_3) . "', '" . addslashes($analog_in_4) . "', '" . addslashes($hw_version) . "', '" . addslashes($sw_version) . "', '$data_type', '" . date('Y-m-d H:i:s') . "', '" . addslashes($current_landmark) . "', '" . addslashes($current_landmark_id) . "', '" . addslashes($fuel_data) . "', '" . addslashes($last_snap) . "')";
    mysqli_query($link, $sql);

    mysqli_close($link);

    if (!isset($RELATIVE_PATH_DOTS) || $RELATIVE_PATH_DOTS == "") {
        $RELATIVE_PATH_DOTS = "../";
    }
    require_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
    require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';

    //Initialize customer as 0 as it is mandate in constructor. It would not be used in getUnitDetailsForListener
    $customerno = 0;
    $objUnitManager = new UnitManager($customerno);
    $arrUnitDetails = $objUnitManager->getUnitDetails_Listener($objParsedData);
    foreach ($arrUnitDetails as $arrUnitDetail) {
        $arrReqdDeviceDetails = $objUnitManager->getReqdDeviceDetailsBeforeUpdate_Listener($arrUnitDetail);

        $objDeviceData->unitid = $arrUnitDetail[0];
        $objDeviceData->customerno = $arrUnitDetail[1];
        $objDeviceData->unitno = $arrUnitDetail[2];

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
        $objDeviceData->isOffline = 0;
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
        $objDeviceData->timestamp = $objParsedData->lastUpdated;
        //TODO: Check year of lastUpdated parameter before setting below parameter
        $objDeviceData->isPacketTimeValid = 1;
        $objDeviceData->alterremark = "";
		//print_r($objDeviceData);
        $isUpdated = $objUnitManager->UpdateDeviceDataInMySQL_Listener($objDeviceData);
        /* Get the raw data from sqlite */
        $rawData = json_encode($jdata);
        $objSqlite = new SqliteManager($objDeviceData->customerno, $objDeviceData->unitno, $objDeviceData->timestamp);
        $objSqlite->InsertDataInSqlite($rawData, $objDeviceData);
    }
    $serverResponse = array('result' => 'true', 'msg' => 'Data Received');
    //$serverResponse = '{"result":true,"msg":"Data Received"}';
} else {
    $serverResponse = array('result' => 'false', 'msg' => 'Error Receiving data');
}

echo json_encode($serverResponse);
?>