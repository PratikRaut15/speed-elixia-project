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

define("TRACKING_EXPERT", "http://203.115.101.54/mobileapp/vehile_api.php?token=");
define("TIMEOUT_SECS", "30");

$action = "";
$objApi = new api();
$output = null;

extract($_REQUEST);
$jsonreq= json_decode($jsonreq);
// print_r($jsonreq);die;
$jsonResponse = array();
$apiConstant = TRACKING_EXPERT;
$arrOutputData = $objApi->failure("Missing params");
if (isset($apiConstant) && $apiConstant != "") {
   if ($action == "getAllVehicleData") {
    //Resource Address
    $url = TRACKING_EXPERT . $jsonreq->token;
    //Send Request to resource
    $objCurl = curl_init();
    curl_setopt($objCurl, CURLOPT_URL, $url);
    curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    curl_setopt($objCurl, CURLOPT_TIMEOUT, TIMEOUT_SECS);
    //curl_setopt($objCurl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);   
    //curl_setopt($objCurl, CURLOPT_USERPWD, $inputJsonData->vendorDetails->userName . ":" . $inputJsonData->vendorDetails->pwd);
    
	//Get Response from resource
    $jsonResponse = curl_exec($objCurl);

    /*$xml = json_decode($jsonResponse);
    $json = json_encode($xml);*/
    $errorMsg = '';
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
        if (isset($newArray['Status']) && $newArray['Status'] == '1' || $newArray['Status'] == 'true') {
            $arrOutput = array_values($newArray);

                ################## Data Parsing Logic #################
                #######################################################
                if(isset($jsonreq->customerNo) && !empty($jsonreq->customerNo)){
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
                }else{
                    $finalArray = $arrOutput[2];
                }

                ################## Data Parsing Logic #################
                #######################################################


            // $arrOutputData = $objApi->success("Fetched Data successfully", $arrOutput[2]);
            $arrOutputData = $objApi->success("Fetched Data successfully", $finalArray);
        }
    } else {
        $arrOutputData = $objApi->failure("Unable to fetch data - " . $errorMsg);
    }
    // print_r($arrOutputData);
   }
} // end if - url is not exist
else {
    $arrOutputData = $objApi->failure("customer No. Missing in API call");
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
    $objVehDetail->odometer = isset($vehData->odo) ? ROUND(($vehData->odo * 1000),0) : 0; 

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

    $objVehDetail->digitalio = $vehData->ac;
    $objVehDetail->ignition = $vehData->ignition;
    $dttime = str_replace(":000", "", $vehData->dttime);
    $dateTime = new DateTime($dttime);
    $objVehDetail->lastUpdated = $dateTime->format('Y-m-d H:i:s');
    $objVehDetail->stoppageTransitTime = $objVehDetail->lastUpdated;
    return $objVehDetail;
}


############## Final output Response #############
if (!isset($arrOutputData)) {
    $arrOutputData = $objApi->failure("Seems like you are playing around with incorrect actions or without any actions !!");
}
//print_r($arrOutputData);  die;
echo json_encode($arrOutputData);
?>