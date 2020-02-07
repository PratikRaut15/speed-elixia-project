<?php

//file required
//error_reporting(E_ALL ^E_STRICT);
//ini_set('display_errors', 'on');
$RELATIVE_PATH_DOTS = "../../../../";
require_once($RELATIVE_PATH_DOTS . "lib/system/utilities.php");
require_once ($RELATIVE_PATH_DOTS . "lib/system/Sanitise.php");
require_once("class/class.api.php");
// require_once("class/model/RequestClass.php");
require_once("class/model/ResponseClass.php");
require_once($RELATIVE_PATH_DOTS . "vendor/autoload.php");

$jsonreqData = isset($_REQUEST['jsonreq']) ? $_REQUEST['jsonreq'] : "";
$jsonreq = json_decode($jsonreqData);


###### Change Log##############
/*
    1) added $jsonreq->apiUrl which would come from cronFetchLatestData API for New customers who will use latLog Service to pull data in Elixiatech
    2) else define("ARYAOMNITALK_GPS_LOCATION_API", "http://118.67.240.85:7003/api/GET_INFORMATION_ALL?"); this is existing API URL which is used for 761
*/

if(isset($jsonreq->apiUrl) && $jsonreq->apiUrl != ""){
    define("ARYAOMNITALK_GPS_LOCATION_API", "$jsonreq->apiUrl");    
}else{
    define("ARYAOMNITALK_GPS_LOCATION_API", "http://118.67.240.85:7003/api/GET_INFORMATION_ALL?"); // for 761 
}
// define("ARYAOMNITALK_GPS_LOCATION_API", "http://118.67.240.85:7003/api/GET_INFORMATION_ALL?");
// print_r($postData); die;
define("TIMEOUT_SECS", "30");
$action = "";
extract($_REQUEST);

$objApi = new api();

$jsonreq = json_decode($jsonreq);

$jsonResponse = array();
$apiConstant = ARYAOMNITALK_GPS_LOCATION_API;
$arrOutputData = $objApi->failure("Missing params");
if(isset($apiConstant) && $apiConstant!=""){
    if ($action == "getAllVehicleData") {
        //Resource Address
        $url = ARYAOMNITALK_GPS_LOCATION_API."User_Id=".$jsonreq->vendorDetails->userName;             
        //Send Request to resource
        $objCurl = curl_init();

        $headers  = array(
                            'Content-Type: application/json'
                            // ,'Authorization: Basic '. base64_encode($jsonreq->vendorDetails->userName.":".$jsonreq->vendorDetails->pwd)
                        );

        curl_setopt($objCurl, CURLOPT_URL, $url);
        curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, 1); // return into a variable

        if(isset($postData) && $postData != "" ){
            // curl_setopt($objCurl, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($objCurl, CURLOPT_POSTFIELDS, json_encode($postData));
            curl_setopt($objCurl, CURLOPT_HTTPHEADER, $headers);
        }

        curl_setopt($objCurl, CURLOPT_TIMEOUT, TIMEOUT_SECS);
        curl_setopt($objCurl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);   
        curl_setopt($objCurl, CURLOPT_USERPWD, $jsonreq->vendorDetails->userName . ":" . $jsonreq->vendorDetails->pwd);

        //Get Response from resource
        $jsonResponse = curl_exec($objCurl);

        // print_r($jsonResponse); die;

        if (curl_error($objCurl)) {
            $errorMsg = 'error:' . curl_error($objCurl);
        }
        curl_close($objCurl);
        $jsonResponse = json_decode($jsonResponse, true);
        //Process the response
        $finalArray = array();
        if (isset($jsonResponse) && !empty($jsonResponse)) {
            $newArray = array();
            $newArray['Message'] = "Success";
            $newArray['Status'] = "1";
            $newArray[2] = $jsonResponse['VEHICLES'];
            if(isset($newArray['Status']) && $newArray['Status'] == '1' || $newArray['Status'] == 'true'){
                $arrOutput = array_values($newArray);   

                ################## Data Parsing Logic #################
                #######################################################

                foreach ($arrOutput[2] as $arrVehData) {
                    $objVehDetail = array();
                    $vehData = isset($arrVehData) ? (object) $arrVehData : NULL;
                    if (isset($vehData) && !empty($vehData)) {
                        /*if(isset($jsonreq->customerNo) && !empty($jsonreq->customerNo)){
                            $parsedVehicleNo   = str_replace(" ", "", str_replace(" ", "", $vehData->name));
                            $objVehicleManager = new VehicleManager($jsonreq->customerNo);
                            $arrHistData       = $objVehicleManager->get_vehicle_by_vehno($parsedVehicleNo, 1);
                        }*/

                        $objVehDetail[] = $vehData; // Raw data
                        $objVehDetail[] = ParseData($vehData); // Parsed data

                        $finalArray[] = $objVehDetail;

                    }
                }

                ################## Data Parsing Logic #################
                #######################################################
                // $arrOutputData = $objApi->success("Fetched Data successfully", $arrOutput[2]);
                $arrOutputData = $objApi->success("Fetched Data successfully", $finalArray);
            }
        }
        else {
            $arrOutputData = $objApi->failure("Unable to fetch data - " . $errorMsg);
        }
        // print_r($arrOutputData); die;
    }

} // end if - url is not exist
else{
    $arrOutputData = $objApi->failure("customer No. Missing in API call");    
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
    // $objVehDetail->odometer = isset($vehData->VEHICLE_ODOMETER) ? $vehData->VEHICLE_ODOMETER : 0;
    $objVehDetail->odometer = isset($vehData->VEHICLE_ODOMETER) ? ROUND(($vehData->VEHICLE_ODOMETER * 1000),0) : 0;
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

    #### Additional Fields Added while making generic #####

    $objVehDetail->directionchange = isset($vehData->direction) ? $vehData->direction : 0;
    $objVehDetail->hwv = '';
    $objVehDetail->swv = '';
    $objVehDetail->msgid = '';
    $objVehDetail->status = '';
    $objVehDetail->powercut = 0;
    $objVehDetail->gpsfixed = 'A';
    $objVehDetail->gsmregister = 0;
    $objVehDetail->gprsregister = 0;
    $objVehDetail->satv = '';
    $objVehDetail->vehicleno = $objVehDetail->vehNo;
    $objVehDetail->commandkey = '';
    $objVehDetail->commandkeyval = '';

    #### END Additional Fields Added while making generic #####

    
    if (isset($vehData->bmStr) && strpos($vehData->bmStr, 'AC on') !== false) {
        $objVehDetail->digitalio = 1;
    }
    else {
        $objVehDetail->digitalio = 0;
    }
    $objVehDetail->ignition = (isset($vehData->IGN) && $vehData->IGN =='on') ? 1 : 0;
    $objVehDetail->lastUpdated = date('Y-m-d H:i:s',strtotime($vehData->VEHICLE_GPS_DATETIME));
    //TODO
    $objVehDetail->stoppageTransitTime = $objVehDetail->lastUpdated;
    return $objVehDetail;
}
###########  Final outPut Data ###########################
if (!isset($arrOutputData)) {
    $arrOutputData = $objApi->failure("Seems like you are playing around with incorrect actions or without any actions !!");
}
//print_r($arrOutputData);  die;
echo json_encode($arrOutputData);
?>