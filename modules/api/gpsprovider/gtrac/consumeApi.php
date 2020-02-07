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
$jsonreqData = isset($_REQUEST['jsonreq']) ? $_REQUEST['jsonreq'] : '';
$jsonreq = json_decode($jsonreqData);

if(isset($_REQUEST) && $_REQUEST['customerNo'] == "206"){
    define("GTRACK_LOCATION_API", "http://203.115.101.54/mobileapp/elixia_api.php");// safe and secure API
}elseif(isset($_REQUEST) && $_REQUEST['customerNo'] == "473"){
    define("GTRACK_LOCATION_API", "http://203.115.101.54/mobileapp/nestlenorth_api.php");// Nestle API
}elseif(isset($_REQUEST) && $_REQUEST['customerNo'] == "613"){
    if(isset($jsonreq->apiUrl)){
        define("GTRACK_LOCATION_API", "$jsonreq->apiUrl");    
    }else{
        define("GTRACK_LOCATION_API", "http://203.115.101.54/mobileapp/mondelezprimary_api.php");
    }
}else{
    define("GTRACK_LOCATION_API", "");
}

//define("GTRACK_LOCATION_API", "http://203.115.101.54/mobileapp/elixia_api.php");// safe and secure API
define("TIMEOUT_SECS", "30");

$action = "";
$objApi = new api();
$output = null;
extract($_REQUEST);
$apiConstant = GTRACK_LOCATION_API;
$arrOutputData = $objApi->failure("Missing params");
if(isset($apiConstant) && $apiConstant!=""){
    if ($action == "getAllVehicleData") {
        $errorMsg = '';
        //$inputJsonData = json_decode($jsonreq);
        //Resource Address
        $url = GTRACK_LOCATION_API;
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
        if (isset($jsonResponse) && $jsonResponse != "") {
            if(isset($jsonResponse['status']) && $jsonResponse['status'] == '1' || $jsonResponse['status'] == 'true'){
                $arrOutput = array_values($jsonResponse);

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

                /*$arrResponse = json_decode($jsonResponse, true);
                $arrOutput = array_values($arrResponse);*/
                //print_r($arrOutput[2]); die;
                // $arrOutputData = $objApi->success("Fetched Data successfully", $arrOutput[2]);
                $arrOutputData = $objApi->success("Fetched Data successfully", $finalArray);
            }
        }
        else {
            $arrOutputData = $objApi->failure("Unable to fetch data - " . $errorMsg);
        }
        //print_r($arrOutputData);
    }
    else if ($action == "getSpecificVehicleData") {
        $inputJsonData = json_decode($jsonreq);
        if (isset($inputJsonData->vehRegNo)) {
            //Resource Address
            $url = GTRACK_LOCATION_API . "/getLatestLocation/" . $inputJsonData->vehRegNo;

            //Send Request to resource
            $objCurl = curl_init();
            curl_setopt($objCurl, CURLOPT_URL, $url);
            curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($objCurl, CURLOPT_TIMEOUT, TIMEOUT_SECS);
            curl_setopt($objCurl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($objCurl, CURLOPT_USERPWD, $inputJsonData->vendorDetails->userName . ":" . $inputJsonData->vendorDetails->pwd);

            //Get Response from resource
            $jsonResponse = curl_exec($objCurl);
            if (curl_error($objCurl)) {
                $errorMsg = 'error:' . curl_error($objCurl);
            }
            curl_close($objCurl);

            //Process the response
            if (isset($jsonResponse) && $jsonResponse != "") {
                $arrResponse = json_decode($jsonResponse, true);
                $arrOutput = array_values($arrResponse);
                $arrOutputData = $objApi->success("Fetched Data successfully", $arrOutput);
            }
            else {
                $arrOutputData = $objApi->failure("Unable to fetch data - " . $errorMsg);
            }
            //print_r($arrOutputData);
        }
    }

} // end if - url is not exist
else{
    $arrOutputData = $objApi->failure("customer No. Missing in API call");    
}


function ParseData($vehData) {
    $objVehDetail = new stdClass();
    // $objVehDetail->vehNo = str_replace(" ", "", str_replace(GEOTRACKER_CUST_ID_GHGC . "-", "", $vehData->regNo));
    $objVehDetail->vehNo = $vehData->vehicleno;
    $objVehDetail->deviceLat = $vehData->latitude;
    $objVehDetail->deviceLng = $vehData->longitude;
    $objVehDetail->altitude = 0;
    $objVehDetail->direction = 0;
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


    $objVehDetail->ignition = $vehData->ignition;
    $objVehDetail->digitalio = 0;


    // $objVehDetail->lastUpdated = date('Y-m-d H:i:s', ($vehData->timestamp / 1000));
    $objVehDetail->lastUpdated = $vehData->datetime;
    //print_r($vehData->datetime); die;
    //TODO
    $objVehDetail->stoppageTransitTime = $objVehDetail->lastUpdated;
    return $objVehDetail;
}

############### Final Output #####################
if (!isset($arrOutputData)) {
    $arrOutputData = $objApi->failure("Seems like you are playing around with incorrect actions or without any actions !!");
}
//print_r($arrOutputData);  die;
echo json_encode($arrOutputData);
?>