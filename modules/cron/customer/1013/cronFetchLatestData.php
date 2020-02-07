<?php
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'on');

if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../../../";
}
include_once "../daily_report_common_functions.php";
include_once $RELATIVE_PATH_DOTS . "lib/autoload.php";

$url = "http://speed.elixiatech.com/modules/api/gpsprovider/trackingExpert/consumeApi.php";
// $url = "http://localhost/elixia_speed/modules/api/gpsprovider/trackingExpert/consumeApi.php";
   $objJsonReq = new stdClass();
   $objJsonReq->token = "51405";
   $objJsonReq->customerNo = speedConstants::CUSTNO_S_K_FROZEN_FOOD_CARRIER;

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
curl_setopt($objCurl, CURLOPT_TIMEOUT, speedConstants::CURL_TIMEOUT_SECS);
//Get Response from resource
$jsonResponse = curl_exec($objCurl);

print_r($jsonResponse);
// die;
if (curl_error($objCurl)) {
    echo 'error:' . curl_error($objCurl);
}

curl_close($objCurl);
if (isset($jsonResponse) && $jsonResponse != "") {
    $decodedText = html_entity_decode($jsonResponse);
    $outputData = json_decode($decodedText);
    //echo "<br/>" . $error = json_last_error();
    // echo $outputData->Status; die;
    if ($outputData->Status == 1) {
        ProcessData($outputData->Result);
    }
}

//}// end foreach

function ProcessData($arrLatestVehData) {
    $objVehicleManager = new VehicleManager(speedConstants::CUSTNO_S_K_FROZEN_FOOD_CARRIER);
    //prettyPrint($arrLatestVehData);
    if (isset($arrLatestVehData) && !empty($arrLatestVehData) && is_array($arrLatestVehData)) {
        foreach ($arrLatestVehData as $arrVehData) {
            // $vehData = (isset($arrVehData) && !empty($arrVehData)) ? $arrVehData : NULL;
            $vehData = isset($arrVehData[0]) ? $arrVehData[0] : NULL; /// Raw data from Api
            $objVehDetail = isset($arrVehData[1]) ? $arrVehData[1] : NULL; /// Parsed data

            if (isset($vehData)) {
                // $objVehDetail = ParseData($vehData);

                ################ Daily Report And Odometer Validation  ##################
                #########################################################################
                
                $limit = 1;
                $arrHistData = array();
                $objVehDetail->isValid = 0;
                $objVehDetail->isOffline = 1;
                $objVehDetail->isPacketTimeValid = 0;

                $arrHistData = $objVehicleManager->get_vehicle_by_vehno($objVehDetail->vehNo, $limit);
                if(isset($arrHistData) && !empty($arrHistData)){
                    $preOdometer = isset($arrHistData[0]->odometer) ? $arrHistData[0]->odometer : 0;
                    $currOdometer = $objVehDetail->odometer;
/*print("<prE>");
print_r($arrHistData[0]);*/
                    // $objVehDetail->odometer = $preOdometer + $currOdometer;
                    $objVehDetail->stoppageOdometer = isset($arrHistData[0]->stoppage_odometer) ? $arrHistData[0]->stoppage_odometer : 0;
                    $objVehDetail->stoppageFlag = isset($arrHistData[0]->stoppage_flag) ? $arrHistData[0]->stoppage_flag : 0;
                    
                    /*echo "<br> Vehicle No.".$objVehDetail->vehNo;
                    echo "<br> preOdometer".$preOdometer;
                    echo "<br> currOdometer".$currOdometer;
                    echo "<br> Final Odometer".$objVehDetail->odometer;
                    echo "<br>";*/

                    ### Hardcoded set valid for Below variables ###
                    $objVehDetail->isValid = 1;
                    $objVehDetail->isOffline = 0;
                    $objVehDetail->isPacketTimeValid = 1;

                    /*if($currOdometer >= $preOdometer){
                        $objVehDetail->isValid = 1;
                        $objVehDetail->isOffline = 0;
                        $objVehDetail->isPacketTimeValid = 1;
                    }*/
                    
                }

                ###### Update in MySQL ########
                ###############################
                
                // $objOutput = $objVehicleManager->geotrackersUpdateDeviceData($objVehDetail);
                $objOutput = $objVehicleManager->gpsProviderUpdateDeviceData($objVehDetail);
                //Enter in Sqlite
                $objVehDetail->customerNo = speedConstants::CUSTNO_S_K_FROZEN_FOOD_CARRIER;
                $objVehDetail->uid = isset($objOutput->uid) ? $objOutput->uid : 0;
                $objVehDetail->unitNo = isset($objOutput->unitNo) ? $objOutput->unitNo : "";
                $objVehDetail->vehicleId = isset($objOutput->vehicleId) ? $objOutput->vehicleId : 0;
                $objVehDetail->deviceId = isset($objOutput->deviceId) ? $objOutput->deviceId : 0;
                $objVehDetail->driverId = isset($objOutput->driverId) ? $objOutput->driverId : 0;

                ################# Daily Report for RTD ##############
                #####################################################
                if(isset($objOutput) && !empty($objOutput) && $objVehDetail->isValid == 1){
                    mergeDailyReport_Listener($objVehDetail);
                    
                }else{
                    echo "<br>Odometer is not valid OR Vehicle ".$objVehDetail->vehNo." not exist in the system"; 
                }

                ### Enter in Sqlite ###

                if($objVehDetail->uid){
                    InsertDataInSqliteFunction($vehData, $objVehDetail);    
                }else{
                   echo "<br> This Vehicle is Not Exist in the Sysytem '".$objVehDetail->vehNo."'";
                }

                ############### END Daily Report And Odometer Validation ################
                #########################################################################
            }
        }
    }
}

function ParseData($vehData) {
    $objVehDetail = new stdClass();
    // $objVehDetail->vehNo = str_replace(" ", "", str_replace(SAHARAROADLINES_CUST_ID_SR . "-", "", $vehData->regNo));
    $pos = strpos($vehData->vname, "-");
    $rest = substr($vehData->vname, $pos + 1);
    $objVehDetail->vehNo = str_replace(" ", "", $vehData->vname);

    $objVehDetail->deviceLat = $vehData->lat;
    $objVehDetail->deviceLng = $vehData->lngt;
    $objVehDetail->altitude = isset($vehData->elevation) ? $vehData->elevation : 0;
    $objVehDetail->direction = $vehData->angle;
    $objVehDetail->odometer = $vehData->odo;
    $objVehDetail->speed = $vehData->speed;
    $objVehDetail->analog1 = isset($vehData->temperature) ? ($vehData->temperature * 100) : 0;
    $objVehDetail->analog2 = 0;
    $objVehDetail->analog3 = 0;
    $objVehDetail->analog4 = 0;
    $objVehDetail->isPowered = 1;
    $objVehDetail->tamper = 0;
    $objVehDetail->isOffline = 0;
    $objVehDetail->gsmStrength = 0;
    $objVehDetail->inbatt = 0;
    $objVehDetail->extbatt = $vehData->batlevel;
    $objVehDetail->digitalio = $vehData->ac;
    $objVehDetail->ignition = $vehData->ignition;
    $dttime = str_replace(":000", "", $vehData->dttime);
    $dateTime = new DateTime($dttime);
    $objVehDetail->lastUpdated = $dateTime->format('Y-m-d H:i:s');
    //TODO
    $objVehDetail->stoppageTransitTime = $objVehDetail->lastUpdated;
    return $objVehDetail;
}

function InsertDataInSqlite($vehData, $objVehDetail) {
    if ($objVehDetail->unitNo != "") {
        $date = convertDateToFormat($objVehDetail->lastUpdated, speedConstants::DATE_Ymd);
        $db = "sqlite:" . RELATIVE_PATH_DOTS . "customer/" . speedConstants::CUSTNO_S_K_FROZEN_FOOD_CARRIER . "/unitno/" . $objVehDetail->unitNo . "/sqlite/" . $date . ".sqlite";

        //echo "<br>".$db."<br>";
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
