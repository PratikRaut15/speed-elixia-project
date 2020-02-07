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
// define("GWTRACKER_GPS_LOCATION_API", "http://45.249.111.52/jsp/Service.jsp?");

// define("GWTRACKER_GPS_LOCATION_API", "http://13.235.165.37/jsp/Service.jsp?");

// http://gwtrackers.net/webservice?token=getLiveData&user=junaid&pass=12345678&format=xml

define("GWTRACKER_GPS_LOCATION_API", "http://gwtrackers.net/webservice?token=getLiveData&");
define("TIMEOUT_SECS", "30");

$action = "";
$objApi = new api();
$output = null;
$post_data = array(
                'jsonreq' => $jsonreq
            );
extract($_REQUEST);
$jsonreq= json_decode($jsonreq);
// print_r($jsonreq); die;
/*print_r($jsonreq->vendorDetails->userName); echo "<br>";
print_r($jsonreq->vendorDetails->pwd);

die;*/
$jsonResponse = array();
$apiConstant = GWTRACKER_GPS_LOCATION_API;
$arrOutputData = $objApi->failure("Missing params");
if(isset($apiConstant) && $apiConstant!=""){
    if ($action == "getAllVehicleData") {
        //$inputJsonData = json_decode($jsonreq);
        //Resource Address
        $url = GWTRACKER_GPS_LOCATION_API."user=".$jsonreq->vendorDetails->userName."&pass=".$jsonreq->vendorDetails->pwd."&format=".$jsonreq->vendorDetails->format."";   
        // echo $url; die;                       
        //Send Request to resource
        $objCurl = curl_init();
        curl_setopt($objCurl, CURLOPT_URL, $url);
        curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($objCurl, CURLOPT_TIMEOUT, TIMEOUT_SECS);
        //curl_setopt($objCurl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);   
        //curl_setopt($objCurl, CURLOPT_USERPWD, $inputJsonData->vendorDetails->userName . ":" . $inputJsonData->vendorDetails->pwd);

        //Get Response from resource
        $jsonResponse = curl_exec($objCurl);
        
        // print("<pre>"); print_r($jsonResponse); die;

        $xml = simplexml_load_string($jsonResponse, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        // $jsonResponse = json_decode($json,TRUE);

        if (curl_error($objCurl)) {
            $errorMsg = 'error:' . curl_error($objCurl);
        }
        curl_close($objCurl);
        $jsonResponse = json_decode($json, true);

        // print("<pre>"); print_r($jsonResponse); die;
        //Process the response
        $finalArray = array();
        if (isset($jsonResponse) && !empty($jsonResponse)) {
            $newArray = array();
            $newArray['Message'] = "Success";
            $newArray['Status'] = "1";
            // $newArray[2] = $jsonResponse['detaildata'];
            $newArray[2] = $jsonResponse['VehicleData'];
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
    $objVehDetail->vehNo = str_replace(" ", "", $vehData->Vehicle_No);
    $objVehDetail->deviceLat = isset($vehData->Latitude) ? $vehData->Latitude : 0;
    $objVehDetail->deviceLng = isset($vehData->Longitude) ? $vehData->Longitude : 0;
    $objVehDetail->altitude = isset($vehData->elevation) ? $vehData->elevation : 0; // no data
    $objVehDetail->direction = isset($vehData->direction) ? $vehData->direction : 0; // no data
    $objVehDetail->odometer = isset($vehData->Odometer) ? $vehData->Odometer : 0; // now data coming
    $objVehDetail->speed = isset($vehData->Speed) ? $vehData->Speed : 0;
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
    $objVehDetail->ignition = 0;
    if (isset($vehData->AC) && strtoupper($vehData->AC) == strtoupper('ON')) {
        $objVehDetail->digitalio = 1;
    }
    else {
        $objVehDetail->digitalio = 0;
    }
    if(isset($vehData->IGN) && strtoupper($vehData->IGN) == strtoupper('on')){

        $objVehDetail->ignition = 1;    
    }


    // $objVehDetail->ignition = (isset($vehData->IGN) && strtoupper($vehData->IGN) == strtoupper('on')) ? 1 : 0;
    $objVehDetail->lastUpdated = date('Y-m-d H:i:s',strtotime($vehData->GPSActualTime));
    //TODO
    $objVehDetail->stoppageTransitTime = $objVehDetail->lastUpdated;
    return $objVehDetail;
}

/*function ParseData($vehData) {
    $objVehDetail = new stdClass();
    $objVehDetail->vehNo = str_replace(" ", "", $vehData->Vehicle_No);
    $objVehDetail->deviceLat = $vehData->lattitude;
    $objVehDetail->deviceLng = $vehData->longitude;
    $objVehDetail->altitude = isset($vehData->elevation) ? $vehData->elevation : 0; // no data
    $objVehDetail->direction = isset($vehData->direction) ? $vehData->direction : 0; // no data
    $objVehDetail->odometer = isset($vehData->odometer) ? $vehData->odometer : 0; // no data
    $objVehDetail->speed = $vehData->Speed;
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
    $objVehDetail->ignition = (isset($vehData->IGN) && $vehData->IGN =='on') ? 1 : 0;
    $objVehDetail->lastUpdated = date('Y-m-d H:i:s',strtotime($vehData->Datetime));
    //TODO
    $objVehDetail->stoppageTransitTime = $objVehDetail->lastUpdated;
    return $objVehDetail;
}*/




if (!isset($arrOutputData)) {
    $arrOutputData = $objApi->failure("Seems like you are playing around with incorrect actions or without any actions !!");
}
//print_r($arrOutputData);  die;
echo json_encode($arrOutputData);
?>