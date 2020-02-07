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
// print_r($jsonreq); die;
define("LOCONAV_GPS_LOCATION_API", "http://www.loconav.com/api/v3/device/all_devices?start_index=0&end_index=100");
define("TIMEOUT_SECS", "60");
$action = "";
$objApi = new api();
extract($_REQUEST);
$jsonreq= json_decode($jsonreq);

$jsonResponse = array();
$apiConstant = LOCONAV_GPS_LOCATION_API;
$arrOutputData = $objApi->failure("Missing params");
if(isset($apiConstant) && $apiConstant!=""){
    if ($action == "getAllVehicleData") {
        //Resource Address
        $url = LOCONAV_GPS_LOCATION_API;
        //Send Request to resource
        $objCurl = curl_init();
        curl_setopt($objCurl, CURLOPT_URL, $url);
        curl_setopt($objCurl, CURLOPT_HTTPHEADER, $jsonreq->vendorDetails->arrHeaders);
        curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($objCurl, CURLOPT_TIMEOUT, TIMEOUT_SECS);
        // curl_setopt($objCurl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // curl_setopt($objCurl, CURLOPT_USERPWD, $jsonreq->vendorDetails->userName . ":" . $jsonreq->vendorDetails->pwd);

        //Get Response from resource
        $jsonResponse = curl_exec($objCurl);
        if (curl_error($objCurl)) {
            $errorMsg = 'error:' . curl_error($objCurl);
        }
        curl_close($objCurl);
        $jsonResponse = json_decode($jsonResponse, true);
        //Process the response
        if (isset($jsonResponse) && !empty($jsonResponse)) {
            $newArray = array();
            $newArray['Message'] = "Success";
            $newArray['Status'] = "1";
            $newArray[2] = $jsonResponse;
            if(isset($newArray['Status']) && $newArray['Status'] == '1' || $newArray['Status'] == 'true'){
                $arrOutput = array_values($newArray);
                $arrOutputData = $objApi->success("Fetched Data successfully", $arrOutput[2]);
            }
        }
        else {
            $arrOutputData = $objApi->failure("Unable to fetch data - " . $errorMsg);
        }
    }

} // end if - url is not exist
else{
    $arrOutputData = $objApi->failure("customer No. Missing in API call");
}
if (!isset($arrOutputData)) {
    $arrOutputData = $objApi->failure("Seems like you are playing around with incorrect actions or without any actions !!");
}
//print_r($arrOutputData);  die;
echo json_encode($arrOutputData);
?>
