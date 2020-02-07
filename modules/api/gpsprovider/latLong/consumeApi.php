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

$action = "";
$objApi = new api();
$output = null;

extract($_REQUEST);
$jsonreqData = $jsonreq; #isset($_REQUEST['jsonreq']) ? $_REQUEST['jsonreq'] : "";
$jsonreq = json_decode($jsonreq);
###### Change Log##############
/*
    1) added $jsonreq->apiUrl which would come from cronFetchLatestData API for New customers who will use latLog Service to pull data in Elixiatech
    2) else define("LATLONG_LOCATION_API", "http://latlong.net.in/Client/LatLong_Mondelez.asmx?wsdl"); this is existing API URL which is used for 644 Gati
*/

if(isset($jsonreq->apiUrl) && $jsonreq->apiUrl != ""){
    define("LATLONG_LOCATION_API", "$jsonreq->apiUrl");    
}else{
    define("LATLONG_LOCATION_API", "http://latlong.net.in/Client/LatLong_Mondelez.asmx?wsdl"); // for 644 
}

// define("LATLONG_LOCATION_API", "http://latlong.net.in/Client/LatLong_Mondelez.asmx?wsdl"); // this was used for 644

define("TIMEOUT_SECS", "30");
define("DEBUG", "1");

// print_r($jsonreq); die;
$apiConstant = LATLONG_LOCATION_API;
$arrOutputData = $objApi->failure("Missing params");
if(isset($apiConstant) && $apiConstant!=""){
    if ($action == "getAllVehicleData") {
        if(isset($jsonreq) && $jsonreq->isGetDataByDateTime == 1) {
            $currentDateTime = $endDateTime = date("Y-m-d H:i:s");            
            $startDateTime = date('Y-m-d H:i:s', strtotime($currentDateTime.'-10 minute'));
            $startDateTime = str_replace(" ", "%20", $startDateTime);
            $endDateTime = str_replace(" ", "%20", $endDateTime);

            /*$startDateTime = "2020-01-21%2011:00:00";
            $endDateTime = "2020-01-21%2011:10:00";*/

            $url = LATLONG_LOCATION_API . "/GetGPSDataFromLatLong?startdate=".$startDateTime."&enddate=".$endDateTime."";
            //Send Request to resource
            $objCurl = curl_init();
            curl_setopt($objCurl, CURLOPT_URL, $url);
            curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($objCurl, CURLOPT_TIMEOUT, TIMEOUT_SECS);
            // curl_setopt($objCurl, CURLOPT_POSTFIELDS, $post_data);s
            curl_setopt($objCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($objCurl, CURLOPT_FOLLOWLOCATION, 1); // allow redirects 
            curl_setopt($objCurl, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($objCurl, CURLOPT_MAXREDIRS,5); // return into a variable

            //Get Response from resource
            $jsonResponse = curl_exec($objCurl);
            $result = simplexml_load_string($jsonResponse, "SimpleXMLElement", LIBXML_NOCDATA);
            $result = json_decode(json_encode($result));
            $xml = $result->statusData->statusData;
            // print("<prE>");print_r($xml);die();
            if (curl_error($objCurl)) {
                $errorMsg = 'error:' . curl_error($objCurl);
            }
            curl_close($objCurl);

        }else{
            $client = new SoapClient(LATLONG_LOCATION_API);
            $result = $client->GetGPSDataFromLatLong()->GetGPSDataFromLatLongResult;
            $xml = $result->statusData->statusData;   

            // print("<pre>"); print_r($xml); 
        }

        // $xml = simplexml_load_string($result->GetGPSDataFromLatLongResult->statusData->statusData);
        //Process the response
        $finalArray = array();
        if (isset($xml) && !empty($xml)) {
            $newArray = array();
            $newArray['Message'] = "Success";
            $newArray['Status'] = "1";
            $newArray[2] = $xml;
            
            if(isset($newArray['Status']) && $newArray['Status'] == '1' || $newArray['Status'] == 'true'){
                $arrOutput = array_values($newArray);    

                // print("<pre>"); print_r($arrOutput); die;        
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

                            $vehData->apiUrl = $url;
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
            $arrOutputData = $objApi->failure("Unable to fetch data ");
        }

} // end action if

} // end if - url is not exist
else{
    $arrOutputData = $objApi->failure("customer No. Missing in API call");    
}


function ParseData($vehData) {
    $objVehDetail = new stdClass();
    $objVehDetail->vehNo = $vehData->vehicleNumber;
    $objVehDetail->deviceLat = $vehData->currentlocationlatitude;
    $objVehDetail->deviceLng = $vehData->currentlocationlongitude;
    $objVehDetail->altitude = 0;
    $objVehDetail->direction = 0;
    $objVehDetail->odometer = isset($vehData->distanceTravelled) ? $vehData->distanceTravelled : 0;
    $objVehDetail->speed = $vehData->currentspeed;
    $objVehDetail->analog1 = isset($vehData->currenttemperature) ? ROUND(($vehData->currenttemperature * 100), 2) : 0;
    $objVehDetail->analog2 = isset($vehData->currenttemperature2) ? ROUND(($vehData->currenttemperature2 * 100), 2) : 0;
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
    
    $objVehDetail->ignition = (isset($vehData->currentignitionStatus) && strtoupper($vehData->currentignitionStatus) == "ON") ? 1 : 0;
    $objVehDetail->digitalio = 0;

    // $objVehDetail->lastUpdated = date('Y-m-d H:i:s', ($vehData->timestamp / 1000));
    $objVehDetail->lastUpdated = DateTime::createFromFormat('d/m/Y, h:i:s A', $vehData->currenttimestamp)->format('Y-m-d H:i:s');

    //print_r($vehData->datetime); die;
    $objVehDetail->stoppageTransitTime = $objVehDetail->lastUpdated;


    return $objVehDetail;
}

############# Final Ouput Response ##############
if (!isset($arrOutputData)) {
    $arrOutputData = $objApi->failure("Seems like you are playing around with incorrect actions or without any actions !!");
}
//print_r($arrOutputData);  die;
echo json_encode($arrOutputData);
?>