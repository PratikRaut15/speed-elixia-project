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

define("MAPTRONICS_LOCATION_API", "http://ankeshm.com/mtrack.php?key=4057C9D97E2A5AAA5478DAF6428268C2&cmd=ALL,*");
define("TIMEOUT_SECS", "30");

$action = "";
$objApi = new api();
$output = null;
extract($_REQUEST);
$apiConstant = MAPTRONICS_LOCATION_API;
$arrOutputData = $objApi->failure("Missing params");
if(isset($apiConstant) && $apiConstant!=""){
    if ($action == "getAllVehicleData") {
        //Resource Address
        $url = $apiConstant;

        //Send Request to resource
        $objCurl = curl_init();
        curl_setopt($objCurl, CURLOPT_URL, $url);
        curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($objCurl, CURLOPT_TIMEOUT, TIMEOUT_SECS);
        curl_setopt($objCurl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //curl_setopt($objCurl, CURLOPT_USERPWD, $inputJsonData->vendorDetails->userName . ":" . $inputJsonData->vendorDetails->pwd);

        //Get Response from resource
        $jsonResponse = curl_exec($objCurl);
        if (curl_error($objCurl)) {
            $errorMsg = 'error:' . curl_error($objCurl);
        }
        curl_close($objCurl);

        $jsonResponse = json_decode($jsonResponse, true);
        //Process the response
        $finalArray = array();
        if (isset($jsonResponse) && !empty($jsonResponse)) {
            $data = $jsonResponse['data'];

            ################## Data Parsing Logic #################
            #######################################################
            foreach ($data as $arrVehData) {
                $objVehDetail = array();
                $vehData = isset($arrVehData) ? (object) $arrVehData : NULL;
                if (isset($vehData) && !empty($vehData)) {

                    $objVehDetail[] = $vehData; // Raw data
                    $objVehDetail[] = ParseData($vehData); // Parsed data

                    $finalArray[] = $objVehDetail;

                }
            }

            ################## Data Parsing Logic #################
            #######################################################
            // $arrOutputData = $objApi->success("Fetched Data successfully", $data);
            $arrOutputData = $objApi->success("Fetched Data successfully", $finalArray);
        }
        else {
            $arrOutputData = $objApi->failure("Unable to fetch data - " . $errorMsg);
        }
        //print_r($arrOutputData);
    }
    

} // end if - url is not exist
else{
    $arrOutputData = $objApi->failure("customer No. Missing in API call");    
}


function ParseData($vehData) {
    $objVehDetail = new stdClass();
    $objVehDetail->vehNo = str_replace(" ", "", str_replace(" ", "", $vehData->name));
    $objVehDetail->deviceLat = $vehData->lat;
    $objVehDetail->deviceLng = $vehData->lng;
    $objVehDetail->altitude = isset($vehData->altitude) ? $vehData->altitude : 0;
    $objVehDetail->direction = isset($vehData->angle) ? $vehData->angle : 0;
    $objVehDetail->odometer = isset($vehData->odometer) ? $vehData->odometer : 0;
    $objVehDetail->speed = $vehData->speed;
    $objVehDetail->analog1 = isset($vehData->temperature) ? $vehData->temperature * 100 : 0;
    $objVehDetail->analog2 = 0;
    $objVehDetail->analog3 = 0;
    $objVehDetail->analog4 = 0;
    $objVehDetail->isPowered = 1;
    $objVehDetail->tamper = 0;
    $objVehDetail->isOffline = 0;
    $objVehDetail->gsmStrength = 0;
    $objVehDetail->inbatt = 0;
    $objVehDetail->extbatt = 0;

    #### Additional Fields Added while making generic #####

    $objVehDetail->directionchange = isset($vehData->angle) ? $vehData->angle : 0;
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


    $objVehDetail->ignition = (isset($vehData->ignition) && $vehData->ignition =='off') ? 0 : 1;

    $objVehDetail->digitalio = 0;

    // $objVehDetail->lastUpdated = date('Y-m-d H:i:s', ($vehData->timestamp / 1000));
    $objVehDetail->lastUpdated = $vehData->dt_server;

    //TODO
    $objVehDetail->stoppageTransitTime = $objVehDetail->lastUpdated;
    return $objVehDetail;
}


if (!isset($arrOutputData)) {
    $arrOutputData = $objApi->failure("Seems like you are playing around with incorrect actions or without any actions !!");
}
echo json_encode($arrOutputData);
?>