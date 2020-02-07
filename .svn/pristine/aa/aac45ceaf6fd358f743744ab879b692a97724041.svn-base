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
$jsonreq = json_decode($_REQUEST['jsonreq']);

define("TATA_FLEETMAN_GPS_LOCATION_API", "https://tatafleetman.microlise.com/Live/mobile/index.php/mobilityservices/index/listViews/getfleetdata");

$action = "";
$objApi = new api();
$output = null;
$post_data = array(
                'jsonreq' => $jsonreq
            );
extract($_REQUEST);
$jsonreq= json_decode($jsonreq);

$jsonResponse = array();
$apiConstant = TATA_FLEETMAN_GPS_LOCATION_API;
$arrOutputData = $objApi->failure("Missing params");
if(isset($apiConstant) && $apiConstant!=""){
    if ($action == "getAllVehicleData") {
        //$inputJsonData = json_decode($jsonreq);
        //Resource Address

        $url = TATA_FLEETMAN_GPS_LOCATION_API."/".$jsonreq->vendorDetails->format."/".$jsonreq->vendorDetails->apiToken."";   
        // echo $url; die;                       
        //Send Request to resource

        $objCurl = curl_init();
        curl_setopt($objCurl, CURLOPT_URL, $url);
        curl_setopt($objCurl, CURLOPT_POST, 1);
        // curl_setopt($objCurl, CURLOPT_POSTFIELDS, http_build_query(array()));
        // curl_setopt($objCurl, CURLOPT_POSTFIELDS, json_encode(array()));
        curl_setopt($objCurl, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded",'Content-Length: 0'));
        curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($objCurl, CURLOPT_TIMEOUT, speedConstants::CURL_TIMEOUT_SECS);
        
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
            $newArray = array();
            $newArray['Message'] = "Success";
            $newArray['Status'] = "1";
            $newArray[2] = $jsonResponse;

            // print("<pre>"); print_r($newArray); die;
            if(isset($newArray['Status']) && $newArray['Status'] == '1' || $newArray['Status'] == 'true'){
                $arrOutput = array_values($newArray);

                ################## Data Parsing Logic #################
                #######################################################

                foreach ($arrOutput[2] as $arrVehData) {
                    $objVehDetail = array();
                    $vehData = isset($arrVehData) ? (object) $arrVehData : NULL;
                    if (isset($vehData) && !empty($vehData)) {

                        $objVehDetail[] = $vehData; // Raw data
                        $objVehDetail[] = ParseData($vehData); // Parsed data

                        $finalArray[] = $objVehDetail;

                    }
                }

                // print_r($finalArray); die;

                ################## Data Parsing Logic #################
                #######################################################

                $arrOutputData = $objApi->success("Fetched Data successfully", $finalArray);
                // $arrOutputData = $objApi->success("Fetched Data successfully", $arrOutput[2]);
            }
        }
        else {
            $arrOutputData = $objApi->failure("Unable to fetch data - " . $errorMsg);
        }
        // print_r($arrOutputData);
    }

} // end if - url is not exist
else{
    $arrOutputData = $objApi->failure("customer No. Missing in API call");    
}


############ Parsing Data #############
#######################################

function ParseData($vehData) {
    $objVehDetail = new stdClass();
    $objVehDetail->vehNo = str_replace(" ", "", $vehData->VehicleName);
    $objVehDetail->deviceLat = isset($vehData->Latitude) ? $vehData->Latitude : 0;
    $objVehDetail->deviceLng = isset($vehData->Longitude) ? $vehData->Longitude : 0;
    $objVehDetail->altitude = isset($vehData->elevation) ? $vehData->elevation : 0; // no data receiving
    $objVehDetail->direction = isset($vehData->Direction) ? $vehData->Direction : 0; 
    $objVehDetail->odometer = isset($vehData->Odo) ? $vehData->Odo : 0;

    #### kilometers per hour = meter per second × 3.6 #####

    $covertedSpeed = isset($vehData->Speed) ? ($vehData->Speed*3.6) : 0;
    ######
    $objVehDetail->speed = isset($covertedSpeed) ? $covertedSpeed : 0;
    $objVehDetail->analog1 = isset($vehData->Temperature) ? $vehData->Temperature * 100 : 0;
    $objVehDetail->analog2 = 0;
    $objVehDetail->analog3 = 0;
    $objVehDetail->analog4 = 0;
    $objVehDetail->isPowered = (isset($vehData->Power) && strtoupper($vehData->Power) == strtoupper('on')) ? 1 : 0;
    $objVehDetail->tamper = 0;
    $objVehDetail->isOffline = (isset($vehData->GPS) && strtoupper($vehData->GPS) == strtoupper('on')) ? 0 : 1;
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

    $objVehDetail->ignition = 0;
    $objVehDetail->digitalio = 0;
    if (isset($vehData->AC) && strtoupper($vehData->AC) == strtoupper('ON')) {
        $objVehDetail->digitalio = 1;
    }
    if (strpos(strtoupper($vehData->VehicleStatus), 'IGNITION OFF') === false) {    
        $objVehDetail->ignition = 1;    
    }
    $LastUpdateDateTimeInUTC = str_replace("/","-",$vehData->LastUpdateDateTimeInUTC);
    $objVehDetail->lastUpdated = date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes', strtotime($LastUpdateDateTimeInUTC)));

    $objVehDetail->stoppageTransitTime = $objVehDetail->lastUpdated;
    return $objVehDetail;
}



if (!isset($arrOutputData)) {
    $arrOutputData = $objApi->failure("Seems like you are playing around with incorrect actions or without any actions !!");
}
//print_r($arrOutputData);  die;
echo json_encode($arrOutputData);
?>