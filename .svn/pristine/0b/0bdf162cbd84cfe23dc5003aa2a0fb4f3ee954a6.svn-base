<?php

error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'on');

if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../../../";
}

include_once "../daily_report_common_functions.php";
include_once $RELATIVE_PATH_DOTS . "lib/autoload.php";

define("GEOTRACKER_CUST_ID_RCON", "RCON");
$url = "http://speed.elixiatech.com/modules/api/gpsprovider/geotrackers/consumeApi.php";
$objJsonReq = new stdClass();
$objVendorDetails = new stdClass();
$objVendorDetails->userName = "rconmap";
$objVendorDetails->pwd = "guest";
$objVendorDetails->customerNo = speedConstants::CUSTNO_REFCON;

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
curl_setopt($objCurl, CURLOPT_TIMEOUT, speedConstants::CURL_TIMEOUT_SECS);
//Get Response from resource
$jsonResponse = curl_exec($objCurl);

print_r($jsonResponse);

if (curl_error($objCurl)) {
    echo 'error:' . curl_error($objCurl);
}
curl_close($objCurl);
if (isset($jsonResponse) && $jsonResponse != "") {
    $decodedText = html_entity_decode($jsonResponse);
    $outputData = json_decode($decodedText);
    //echo "<br/>" . $error = json_last_error();
    if ($outputData->Status == 1) {
        ProcessData($outputData->Result);
    }
}


function ProcessData($arrLatestVehData) {
    $objVehicleManager = new VehicleManager(speedConstants::CUSTNO_REFCON);
    //prettyPrint($arrLatestVehData);
    if(isset($arrLatestVehData) && !empty($arrLatestVehData) && is_array($arrLatestVehData)){
        foreach ($arrLatestVehData as $arrVehData) {
            $vehData = isset($arrVehData[0]) ? $arrVehData[0] : NULL; /// Raw data from Api
            $objVehDetail = isset($arrVehData[1]) ? $arrVehData[1] : NULL; /// Parsed data
            if (isset($vehData)) {
                
                // $objVehDetail = ParseData($vehData);

                ################ Daily Report And Odometer Validation  ##################
                #########################################################################

                ############ Odometer check ###################
                ###############################################
                
                $limit = 1;
                $arrHistData = array();
                $objVehDetail->isValid = 0;
                $objVehDetail->isOffline = 1;
                $objVehDetail->isPacketTimeValid = 0;

                $arrHistData = $objVehicleManager->get_vehicle_by_vehno($objVehDetail->vehNo, $limit);
                // print_r($arrHistData); die;
                if(isset($arrHistData) && !empty($arrHistData)){
                    $preOdometer = isset($arrHistData[0]->odometer) ? $arrHistData[0]->odometer : 0;
                    $currOdometer = $objVehDetail->odometer;
                    

                    $objVehDetail->odometer = $preOdometer + $currOdometer;

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

                ############ END Odometer check ###################
                ###############################################

                ###### Update in MySQL ########
                ###############################
                
                // $objOutput = $objVehicleManager->geotrackersUpdateDeviceData($objVehDetail);
                $objOutput = $objVehicleManager->gpsProviderUpdateDeviceData($objVehDetail);
                //Enter in Sqlite
                $objVehDetail->customerNo = speedConstants::CUSTNO_REFCON;
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
                    echo "Odometer is not valid OR Vehicle not exist in the system"; 
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

?>
