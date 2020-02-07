<?php
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'on');

if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../../../";
}
include_once "../daily_report_common_functions.php";
include_once $RELATIVE_PATH_DOTS . "lib/autoload.php";
define("RAJESH_ROAD_LINES", "RRL");
$aryaGpsUsers = array(
    array(
        "userName" => "Rrpladmin",
        "pwd" => "Rrpladmin123",
    )
);
    $url = "http://speed.elixiatech.com/modules/api/gpsprovider/aryaOmnitalk/consumeApi.php";
	// $url = "http://localhost/elixia_speed/modules/api/gpsprovider/aryaOmnitalk/consumeApi.php";
	$objJsonReq = new stdClass();
	$objVendorDetails = new stdClass();
	$objVendorDetails->userName = $aryaGpsUsers[0]['userName'];
	$objVendorDetails->pwd = $aryaGpsUsers[0]['pwd'];

	$objJsonReq->vendorDetails = $objVendorDetails;
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

print_r($jsonResponse); 
//die;
	if (curl_error($objCurl)) {
		echo 'error:' . curl_error($objCurl);
	}

	curl_close($objCurl);
	if (isset($jsonResponse) && $jsonResponse != "") {
		$decodedText = html_entity_decode($jsonResponse);
		$outputData = json_decode($decodedText);
		if ($outputData->Status == 1) {
			ProcessData($outputData->Result);
		}
	}

function ProcessData($arrLatestVehData) {
    $objVehicleManager = new VehicleManager(speedConstants::CUSTNO_RAJESH_ROADLINES);
    // prettyPrint($arrLatestVehData); die;
	if(isset($arrLatestVehData) && !empty($arrLatestVehData) && is_array($arrLatestVehData)){
		foreach ($arrLatestVehData as $arrVehData) {
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
                $objVehDetail->customerNo = speedConstants::CUSTNO_RAJESH_ROADLINES;
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




				/*//Update in MySQL
				// $objOutput = $objVehicleManager->geotrackersUpdateDeviceData($objVehDetail);
	            $objOutput = $objVehicleManager->gpsProviderUpdateDeviceData($objVehDetail);
				//Enter in Sqlite
				$objVehDetail->customerNo = speedConstants::CUSTNO_RAJESH_ROADLINES;
				$objVehDetail->uid = isset($objOutput->uid) ? $objOutput->uid : 0;
				$objVehDetail->unitNo = isset($objOutput->unitNo) ? $objOutput->unitNo : "";
				$objVehDetail->vehicleId = isset($objOutput->vehicleId) ? $objOutput->vehicleId : 0;
				$objVehDetail->deviceId = isset($objOutput->deviceId) ? $objOutput->deviceId : 0;
				$objVehDetail->driverId = isset($objOutput->driverId) ? $objOutput->driverId : 0;
                InsertDataInSqlite($vehData, $objVehDetail);*/
			}
		}
	}
}

function ParseData($vehData) {
    $objVehDetail = new stdClass();
    $objVehDetail->vehNo = str_replace(" ", "", str_replace("-", "", $vehData->VEHICLE_RTO_NO));
    /*$pos = strpos($vehData->VEHICLE_RTO_NO, "-");
    $rest = substr($vehData->VEHICLE_RTO_NO, $pos+1);
    $objVehDetail->vehNo = str_replace(" ", "", $vehData->VEHICLE_RTO_NO);*/

    $objVehDetail->deviceLat = $vehData->VEHICLE_LAT;
    $objVehDetail->deviceLng = $vehData->VEHICLE_LONG;
    $objVehDetail->altitude = isset($vehData->elevation) ? $vehData->elevation : 0; // no data
    $objVehDetail->direction = isset($vehData->direction) ? $vehData->direction : 0; // no data
    $objVehDetail->odometer = isset($vehData->VEHICLE_ODOMETER) ? $vehData->VEHICLE_ODOMETER : 0;
    $objVehDetail->speed = isset($vehData->VEHICLE_SPEED) ? $vehData->VEHICLE_SPEED : 0;
    $objVehDetail->analog1 = isset($vehData->Temperature) ? $vehData->Temperature * 100 : 0;
    $objVehDetail->analog2 = 0;
    $objVehDetail->analog3 = 0;
    $objVehDetail->analog4 = 0;
    $objVehDetail->isPowered = (isset($vehData->Power) && $vehData->Power =='on') ? 1 : 0;
    $objVehDetail->tamper = 0;
    $objVehDetail->isOffline = (isset($vehData->GPS) && $vehData->GPS =='on') ? 0 : 1;
    $objVehDetail->gsmStrength = 0;
    $objVehDetail->inbatt = 0;
    $objVehDetail->extbatt = 0;
    if (isset($vehData->bmStr) && strpos($vehData->bmStr, 'AC on') !== false) {
        $objVehDetail->digitalio = 1;
    }
    else {
        $objVehDetail->digitalio = 0;
    }
    $objVehDetail->ignition = (isset($vehData->IGN) && $vehData->IGN =='off') ? 0 : 1;
    $objVehDetail->lastUpdated = date('Y-m-d H:i:s',strtotime($vehData->VEHICLE_GPS_DATETIME));
    //TODO
    $objVehDetail->stoppageTransitTime = $objVehDetail->lastUpdated;
    return $objVehDetail;
}

function InsertDataInSqlite($vehData, $objVehDetail) {
    if ($objVehDetail->unitNo != "") {
        $date = convertDateToFormat($objVehDetail->lastUpdated, speedConstants::DATE_Ymd);
        $db = "sqlite:" . RELATIVE_PATH_DOTS . "customer/" . speedConstants::CUSTNO_RAJESH_ROADLINES . "/unitno/" . $objVehDetail->unitNo . "/sqlite/" . $date . ".sqlite";

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
