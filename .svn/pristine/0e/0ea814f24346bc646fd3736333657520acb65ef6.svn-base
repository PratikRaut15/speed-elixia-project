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
require_once($RELATIVE_PATH_DOTS . "lib/autoload.php");

$jsonreqData = isset($_REQUEST['jsonreq']) ? $_REQUEST['jsonreq'] : "";
$jsonreq = json_decode($jsonreqData);

if(isset($jsonreq->apiUrl) && $jsonreq->apiUrl != ""){
    define("RYT_MAP_LOCATION_API", "$jsonreq->apiUrl");    
}else{
    define("RYT_MAP_LOCATION_API", "");
}

define("TIMEOUT_SECS", "30");
$action = "";
extract($_REQUEST);

$objApi = new api();

$jsonreq = json_decode($jsonreq);

$jsonResponse = array();
$apiUrlConstant = RYT_MAP_LOCATION_API;
$arrOutputData = $objApi->failure("Missing params");
if(isset($apiUrlConstant) && $apiUrlConstant!=""){
    if ($action == "getAllVehicleData") {
        //Resource Address
        $url = $apiUrlConstant."user_api_hash=".$jsonreq->userHashKey;         
        
        //Send Request to resource
        $objCurl = curl_init();

        curl_setopt($objCurl, CURLOPT_URL, $url);
        curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($objCurl, CURLOPT_POSTFIELDS, http_build_query($postDataForConsumeApi));
        curl_setopt($objCurl, CURLOPT_TIMEOUT, TIMEOUT_SECS);

        //Get Response from resource
        $jsonResponse = curl_exec($objCurl);
        
        // var_dump($jsonResponse); die;
        // print_r($jsonResponse[0]->items); die;

        if (curl_error($objCurl)) {
            $errorMsg = 'error:' . curl_error($objCurl);
        }
        curl_close($objCurl);
        $jsonResponse = json_decode($jsonResponse);
        //Process the response\
        $finalArray = array();
        if (isset($jsonResponse) && !empty($jsonResponse)) {
            $newArray = array();
            $newArray['Message'] = "Success";
            $newArray['Status'] = "1";
            $newArray[2] = $jsonResponse[0]->items;
            if(isset($newArray['Status']) && $newArray['Status'] == '1' || $newArray['Status'] == 'true'){
                $arrOutput = array_values($newArray);


                ################## Data Parsing Logic #################
                #######################################################
                if(isset($jsonreq->customerNo) && !empty($jsonreq->customerNo)){
                    foreach ($arrOutput[2] as $arrVehData) {
                        $objVehDetail = array();
                        $vehData = isset($arrVehData) ? $arrVehData : NULL;
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
        }
        else {
            $arrOutputData = $objApi->failure("Unable to fetch data - " . $errorMsg);
        }
        // print_r($arrOutputData); die;
    }

} // end if - url is not exist
else{
    $arrOutputData = $objApi->failure("API URL is Missing");    
}

function ParseData($vehData) {
    $objVehDetail = new stdClass();
    $objVehDetail->vehNo        = str_replace(" ", "", str_replace(" ", "", $vehData->name));
    $objVehDetail->deviceLat    = isset($vehData->lat) ? $vehData->lat : 0;
    $objVehDetail->deviceLng    = isset($vehData->lng) ? $vehData->lng : 0;
    $objVehDetail->altitude     = isset($vehData->altitude) ? $vehData->altitude : 0;
    $objVehDetail->direction    = isset($vehData->direction) ? $vehData->direction : 0;

    ############################################ Odometer #####################################################
    ##########  odometer is coming in km thats what calculated as like: $vehData->sensors[4]->val*1000 ########
    ##########                                            #####################################################         
    
    $odometerDetails =  array_values(array_filter($vehData->sensors, "checkOdometer"));
    $objVehDetail->odometer     = isset($odometerDetails[0]->val) ? $odometerDetails[0]->val*1000 : 0; 

    $objVehDetail->speed        = isset($vehData->speed) ? $vehData->speed : 0;
    $objVehDetail->analog1      = isset($vehData->Temperature) ? $vehData->Temperature * 100 : 0;
    $objVehDetail->analog2      = 0;
    $objVehDetail->analog3      = 0;
    $objVehDetail->analog4      = 0;
    $objVehDetail->isPowered    = 1;
    $objVehDetail->tamper       = 0;
    $objVehDetail->isOffline    = 0;
    $objVehDetail->gsmStrength  = 0;
    $objVehDetail->inbatt       = 0;
    $objVehDetail->extbatt      = 0;

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

    $objVehDetail->bmStr        = isset($vehData->bmStr) ? $vehData->bmStr : false;

    $ignitionDetails =  array_values(array_filter($vehData->sensors, "checkIgnition"));
    
    $objVehDetail->ignition     = (isset($ignitionDetails[0]->val) && $ignitionDetails[0]->val != false)  ? $ignitionDetails[0]->val : 0;

    if (strpos($objVehDetail->bmStr, 'AC on') !== false) {
        $objVehDetail->digitalio = 1;
    }
    else {
        $objVehDetail->digitalio = 0;
    }
    /*if (strpos($objVehDetail->bmStr, 'Key on') !== false) {
        $objVehDetail->ignition = 1;
    }
    else {
        $objVehDetail->ignition = 0;
    }*/

    // $objVehDetail->lastUpdated = date('Y-m-d H:i:s', ($vehData->timestamp / 1000));
    $objVehDetail->lastUpdated = date('Y-m-d H:i:s', (strtotime($vehData->time))); // [time] => 19-10-2019 02:55:53 PM
    
    $objVehDetail->stoppageTransitTime = $objVehDetail->lastUpdated;
    return $objVehDetail;
}

function checkOdometer($array) 
{ 
    if(strtoupper($array->name)=="ODOMETER") 
    return TRUE; 
    else
    return FALSE; 
}
function checkIgnition($array) 
{ 
    if(strtoupper($array->name)=="IGNITION KEY") 
    return TRUE; 
    else
    return FALSE; 
}

if (!isset($arrOutputData)) {
    $arrOutputData = $objApi->failure("Seems like you are playing around with incorrect actions or without any actions !!");
}
//print_r($arrOutputData);  die;
echo json_encode($arrOutputData);
?>