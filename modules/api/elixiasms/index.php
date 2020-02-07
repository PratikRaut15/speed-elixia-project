<?php

//file required
//error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'on');
set_time_limit(60);
$RELATIVE_PATH_DOTS = "../../../";
require_once "../../../lib/system/utilities.php";
require_once "../../../lib/autoload.php";
require_once "class/class.api.php";

$apiobj = new api();
extract($_REQUEST);

/*
Sample API:
http://speed.elixiatech.com/modules/api/elixiasms/index.php?action=sendSMSForVTSUtilities&jsonreq={"phoneNo":"9969941084","message":"IGN","timeAdded":"2017-09-23 16:20:21"}
*/
if ($action == "sendSMSForVTSUtilities") {
    $arrResult['status'] = 0;
    $bidText = "";
    $arrResult['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
    $jsonRequest = json_decode($jsonreq);

    /*print("<pre>"); print_r($jsonRequest);
    die;*/
    $bidText = strtoupper(substr(trim($jsonRequest->message),0,3));

    if($bidText == "BID"){
    	$message = explode(" ", $jsonRequest->message);
    	$response = array();
        $ch = curl_init();
        $headers  = array(
                        'x-api-key: elixiatech',
                        'Content-Type: application/json'
                    );
        $postData = array(
            'bidId' => $message['1'],
            'noOfVehicles' => $message['2'],
            'bidAmount' => $message['3'],
            'jsonreq' => $jsonRequest
        );

        $objCurl = curl_init();
        curl_setopt($objCurl, CURLOPT_URL, speedConstants::UPDATE_BID_DETAILS_BY_SMS);
        curl_setopt($objCurl, CURLOPT_POST, 1);
        curl_setopt($objCurl, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($objCurl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:53.0) Gecko/20100101 Firefox/53.0");
        curl_setopt($objCurl, CURLOPT_TIMEOUT, 120);
        //Get Response from resource
        $jsonResponse = curl_exec($objCurl); 
   
        $jsonResponse = json_decode($jsonResponse);
        if(isset($jsonResponse) && !empty($jsonResponse)){
        	if($jsonResponse->result == 1){
        		$arrResult['status'] = 1;
			    $arrResult['message'] = "Updated Successfully";
        	}
        }
        if (curl_error($objCurl)) {
            echo 'error:' . curl_error($objCurl);
        }
        curl_close($objCurl);

    }else if (isset($jsonRequest->phoneNo) && $jsonRequest->phoneNo != "" && is_numeric($jsonRequest->phoneNo) && strlen($jsonRequest->phoneNo) == 10){
        	$arrResult = $apiobj->sendSMSForVTSUtilities($jsonRequest);
    	}
}
if(isset($arrResult)){
	echo json_encode($arrResult);
}

?>
