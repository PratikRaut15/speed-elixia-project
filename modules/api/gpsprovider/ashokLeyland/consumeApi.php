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

define("ASHOKLEYLAND_LOCATION_API", "https://ialert2.ashokleyland.com/ialert/daas/api/getdata?token=");

define("TIMEOUT_SECS", "30");

$action = "";
$objApi = new api();
$output = null;
extract($_REQUEST);
$apiConstant = ASHOKLEYLAND_LOCATION_API;
$token = "xhbahkvaldmsdmbbmbmsd";
$token = $token;
$arrOutputData = $objApi->failure("Missing params");
if(isset($apiConstant) && $apiConstant!=""){
    if ($action == "getAllVehicleData") {
        $inputJsonData = json_decode($jsonreq);
        //Resource Address
        $url = ASHOKLEYLAND_LOCATION_API.$inputJsonData->vendorDetails->token;

        //Send Request to resource
        $objCurl = curl_init();
        curl_setopt($objCurl, CURLOPT_URL, $url);
        curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($objCurl, CURLOPT_TIMEOUT, TIMEOUT_SECS);
        curl_setopt($objCurl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //curl_setopt($objCurl, CURLOPT_USERPWD, $inputJsonData->vendorDetails->userName . ":" . $inputJsonData->vendorDetails->pwd);

        //Get Response from resource
        $jsonResponse = curl_exec($objCurl);
        
        // print_r($jsonResponse); die;

        if (curl_error($objCurl)) {
            $errorMsg = 'error:' . curl_error($objCurl);
        }
        curl_close($objCurl);
        $jsonResponse = json_decode($jsonResponse, true);

        //Process the response
        if (isset($jsonResponse) && $jsonResponse != "") {

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
        // print_r($arrOutputData); die;
    }
    else if ($action == "getSpecificVehicleData") {
        $inputJsonData = json_decode($jsonreq);
        if (isset($inputJsonData->vehRegNo)) {
            //Resource Address
            $url = ASHOKLEYLAND_LOCATION_API . "/getLatestLocation/" . $inputJsonData->vehRegNo;

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
if (!isset($arrOutputData)) {
    $arrOutputData = $objApi->failure("Seems like you are playing around with incorrect actions or without any actions !!");
}
//print_r($arrOutputData);  die;
echo json_encode($arrOutputData);
?>