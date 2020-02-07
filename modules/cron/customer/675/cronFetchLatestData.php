<?php

error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'on');

if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../../../";
}
require_once($RELATIVE_PATH_DOTS . "lib/system/utilities.php");
include_once $RELATIVE_PATH_DOTS . "lib/autoload.php";

$url = "http://speed.elixiatech.com/modules/api/gpsprovider/transWorld/consumeApi.php";
//$url = "http://localhost/speed/modules/api/gpsprovider/transWorld/consumeApi.php";
$customerNo =  speedConstants::CUSTNO_STELLAR; // 613 Coldex;
/* Request Parameters */
/*$objJsonReq = new stdClass();
$objJsonReq->vehicleNo = "all"; // all or reg. vehicle No.
$objJsonReq->customerName = "KelvinNestle";
$objJsonReq->apiKey = "kelvinnest";
$objJsonReq->todayDate = date("Y-m-d H:i:s");
$objJsonReq->dispCat = "json";
$objJsonReq->dataStatus = "current";*/

$objJsonReq = new stdClass();
$objJsonReq->vehicleNo = "all"; // all or reg. vehicle No.
$objJsonReq->customerName = "Kelvin%20Cold%20Chain%20Logistics%20Pvt%20Ltd";
$objJsonReq->apiKey = "kelvin2Cold";
$objJsonReq->todayDate = date("Y-m-d H:i:s");
$objJsonReq->dispCat = "json";
$objJsonReq->dataStatus = "current";

/* End Request Parameters */

$jsonreq = json_encode($objJsonReq);
$postData = array(
    'action' => "getAllVehicleData",
    'jsonreq' => $jsonreq
);
//Send Request to resource
$objCurl = curl_init();
curl_setopt($objCurl, CURLOPT_URL, $url);
curl_setopt($objCurl, CURLOPT_POST, 1);
curl_setopt($objCurl, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, 1); // return into a variable
curl_setopt($objCurl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:53.0) Gecko/20100101 Firefox/53.0");
curl_setopt($objCurl, CURLOPT_TIMEOUT, 30);
//Get Response from resource
$jsonResponse = curl_exec($objCurl);

if (curl_error($objCurl)) {
    echo 'error:' . curl_error($objCurl);
}
curl_close($objCurl);
if (isset($jsonResponse) && $jsonResponse != "") {
    $decodedText = html_entity_decode($jsonResponse);
    $outputData = json_decode($decodedText);
    //echo "<br/>" . $error = json_last_error();
    if ($outputData->Status == "1") {
        // ProcessData($outputData->Result);
        ProcessData($outputData->Result);
    }
}

function ProcessData($arrLatestVehData) {
    $objVehicleManager = new VehicleManager(speedConstants::CUSTNO_STELLAR);
    // prettyPrint($arrLatestVehData); die;
    foreach ($arrLatestVehData as $arrVehData) {
        // prettyPrint($arrVehData); die;
        $vehData = isset($arrVehData->VehicleNo) ? $arrVehData->VehicleNo : NULL;
        if (isset($vehData)) {
            $objVehDetail = ParseData($arrVehData);

            /* check the unit no in all customer */
            // if($objVehDetail->vehNo == "MH46F4431"){
            if(isset($objVehDetail->vehNo) && $objVehDetail->vehNo != ""){
                $arrData = $objVehicleManager->getDuplicateUnitsByVehicleNo($objVehDetail);

                if(isset($arrData) && !empty($arrData)){
                    foreach ($arrData as $data) {

                        $objVehDetail->customerNo = isset($data['customerNo']) ? $data['customerNo'] : speedConstants::CUSTNO_STELLAR;

                        //Update in MySQL
                        $objOutput = $objVehicleManager->gpsProviderUpdateDeviceData($objVehDetail);
                        //Enter in Sqlite
                        echo "Cron excecuted for ".$objVehDetail->customerNo;
                        // $objVehDetail->customerNo = speedConstants::CUSTNO_STELLAR;
                        $objVehDetail->uid = isset($objOutput->uid) ? $objOutput->uid : 0;
                        $objVehDetail->unitNo = isset($objOutput->unitNo) ? $objOutput->unitNo : "";
                        $objVehDetail->vehicleId = isset($objOutput->vehicleId) ? $objOutput->vehicleId : 0;
                        $objVehDetail->deviceId = isset($objOutput->deviceId) ? $objOutput->deviceId : 0;
                        $objVehDetail->driverId = isset($objOutput->driverId) ? $objOutput->driverId : 0;
                        InsertDataInSqlite($arrVehData, $objVehDetail);
                    }

                }
                /*print("<pre>"); print_r($arrData);
                die;*/
            }

        }
    }
}

function ParseData($vehData) {
    //print_r($vehData); die;
    $ignition = strtoupper(str_replace(" ", "", $vehData->Ignition));
    $objVehDetail = new stdClass();
    $objVehDetail->vehNo = str_replace(" ", "", $vehData->VehicleNo);
    $objVehDetail->deviceLat = $vehData->Latitude;
    $objVehDetail->deviceLng = $vehData->Longitude;
    $objVehDetail->altitude = 0;
    $objVehDetail->direction = 0;
    $objVehDetail->odometer = 0;
    $objVehDetail->speed = $vehData->Speed;
    $objVehDetail->analog1 = isset($vehData->Sensor1) ? $vehData->Sensor1 * 100 : 0;
    $objVehDetail->analog2 = isset($vehData->Sensor2) ? $vehData->Sensor2 * 100 : 0;
    $objVehDetail->analog3 = 0;
    $objVehDetail->analog4 = 0;
    $objVehDetail->isPowered = 1;
    $objVehDetail->tamper = 0;
    $objVehDetail->isOffline = 0;
    $objVehDetail->gsmStrength = 0;
    $objVehDetail->inbatt = 0;
    $objVehDetail->extbatt = 0;
    $objVehDetail->ignition = (isset($ignition) && ($vehData->Ignition =="ON" || $vehData->Ignition =="1")) ? '1' : '0';
    $objVehDetail->digitalio = 0;


    // $objVehDetail->lastUpdated = date('Y-m-d H:i:s', ($vehData->timestamp / 1000));
    $objVehDetail->lastUpdated = $vehData->GpsTime;
    //print_r($vehData->datetime); die;
    //TODO
    $objVehDetail->stoppageTransitTime = $objVehDetail->lastUpdated;
    return $objVehDetail;
}

function InsertDataInSqlite($vehData, $objVehDetail) {
    if ($objVehDetail->unitNo != "") {
        $customerNo = isset($objVehDetail->customerNo) ? $objVehDetail->customerNo : speedConstants::CUSTNO_STELLAR;
        //$date = convertDateToFormat("2018-04-11", speedConstants::DATE_Ymd);
        $date = convertDateToFormat($objVehDetail->lastUpdated, speedConstants::DATE_Ymd);
        //$date = "2018-04-11"; // hard coded for testing //
        $db = "sqlite:" . RELATIVE_PATH_DOTS . "customer/" . $customerNo . "/unitno/" . $objVehDetail->unitNo . "/sqlite/" . $date . ".sqlite";
        CreateSqliteTables($db);
        $dbh = new PDO($db);

        /* Convert object to string */
        ob_start();
        print_r($vehData);
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
            , ":customernoParam" => $objVehDetail->customerNo
            , ":devicelatParam" => $objVehDetail->deviceLat
            , ":devicelongParam" => $objVehDetail->deviceLng
            , ":lastupdatedParam" => $objVehDetail->lastUpdated
            , ":altitudeParam" => $objVehDetail->altitude
            , ":directionchangeParam" => $objVehDetail->direction
            , ":inbattParam" => $objVehDetail->inbatt
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
            , ":vehiclenoParam" => $objVehDetail->vehNo
            , ":extbattParam" => $objVehDetail->extbatt
            , ":odometerParam" => $objVehDetail->odometer
            , ":lastupdatedParam" => $objVehDetail->lastUpdated
            , ":speedParam" => $objVehDetail->speed
            , ":customernoParam" => $objVehDetail->customerNo
            , ":driveridParam" => $objVehDetail->driverId
            , ":uidParam" => $objVehDetail->uid
        );

        $unitColumns = "uid,unitno,customerno,vehicleid,analog1,analog2,analog3,analog4,digitalio,lastupdated";
        $unitParams = ":uidParam, :unitnoParam, :customernoParam, :vehicleidParam, :analog1Param, :analog2Param, :analog3Param, :analog4Param, :digitalioParam, :lastupdatedParam";
        $arrUnitValues = array(
            ":uidParam" => $objVehDetail->uid
            , ":unitnoParam" => $objVehDetail->unitNo
            , ":customernoParam" => $objVehDetail->customerNo
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
        $dbh->exec('COMMIT TRANSACTION');
        $dbh = NULL;
    }
}

function CreateSqliteTables($path) {
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

?>
