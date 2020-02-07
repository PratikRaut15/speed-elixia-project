<?php

//file required
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'on');
set_time_limit(1800);

if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../../";
}
require_once $RELATIVE_PATH_DOTS."lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS."lib/autoload.php";
require_once $RELATIVE_PATH_DOTS."lib/system/Sanitise.php";
require_once $RELATIVE_PATH_DOTS."modules/api/vts/class/class.vtsapi.php";
require_once $RELATIVE_PATH_DOTS."modules/api/vts/class/model/ResponseClass.php";

$apiobj = new api();
$output = $apiobj->failure("Looks like the process didn't start at all. ");
try {
		$status = 0;
		$objGetLatestVehicleRequest = new stdClass();
		$objGetLatestVehicleRequest->userkey = "a041155ad2c0401d5592b67f3cbc10a64cd28b2e"; // for 742
		$objGetLatestVehicleRequest->pageindex = isset($objGetLatestVehicleRequest->pageindex) ? $objGetLatestVehicleRequest->pageindex : 1;
		/* If pagesize is -1, it means we need to give all the records in a single page */
		$objGetLatestVehicleRequest->pagesize = isset($objGetLatestVehicleRequest->pagesize) ? $objGetLatestVehicleRequest->pagesize : -1;
		/* If search string is set, it means we need to return all the records matching this string */
		$objGetLatestVehicleRequest->searchstring = isset($objGetLatestVehicleRequest->searchstring) ? $objGetLatestVehicleRequest->searchstring : '';
		$objGetLatestVehicleRequest->groupid = isset($objGetLatestVehicleRequest->groupid) ? $objGetLatestVehicleRequest->groupid : 0;
		$objGetLatestVehicleRequest->iswarehouse = isset($objGetLatestVehicleRequest->iswarehouse) ? $objGetLatestVehicleRequest->iswarehouse : 0;
		$objGetLatestVehicleRequest->isRequiredThirdParty = isset($objGetLatestVehicleRequest->isRequiredThirdParty) ? $objGetLatestVehicleRequest->isRequiredThirdParty : 0;

		$arrVTSResult = $apiobj->get_userdetails_by_key($objGetLatestVehicleRequest->userkey);
		if (empty($arrVTSResult)) {
			$output = $apiobj->failure('No Userdata');
		} else {
			$customerno = $arrVTSResult['customerno'];
			$validDays = $apiobj->checkValidity($customerno);
			if ($validDays > 0) {
				$objGetLatestVehicleRequest->customerno = $customerno;
				$arrResultData = $apiobj->getlatestvehicledata($objGetLatestVehicleRequest);
				if($customerno == 742){
					$vendorDetails['userName'] = "surjan";
					$vendorDetails['pwd'] = "Bsl@123456789";
					$message = "Available Records fetched successfully";
					foreach($arrResultData as $data){
						$arrOutput[] = $apiobj->success($message, array($data));
					}
				}
			} else {
				$output = $apiobj->failure("Device Validity expired.");
			}
		}
}catch (Exception $ex) {
	$output = $apiobj->failure("Whoops!!  Looks like there is an unknown exception");
}

if(isset($arrOutput) && count($arrOutput) > 0){
	//echo "<pre>";
	//print_r($arrOutput);die();
	$counter = 0;
	foreach($arrOutput as $output){
		$counter++;
		$objVehicleResponse = isset($output['Result'][0]) ? $output['Result'][0] : NULL;
		if(isset($objVehicleResponse)){
			echo "$counter <br/> Process for vehicle: $objVehicleResponse->vehicleno  Start On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";
			$postData = json_encode($output);
			$status = executePostVehicleDataCurlRequest($vendorDetails, $postData);
			//As Oracle API needs to be called after every 15 sec, we are introducing sleep
			echo "<br/> Process for vehicle: $objVehicleResponse->vehicleno  End On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";
			//sleep(15);
			/*
			if($counter == 1){
				break;
			}
			*/
		}
		if($status){
			$output = "Data Posted Successfully.";
		}else{
			$output = "Data Not Posted Successfully.";
		}
	}
} else if(isset($output) && !empty($output)){ 
	$postData = json_encode($output);
	$status = executePostVehicleDataCurlRequest($vendorDetails, $postData);
	if($status){
		$output = "Data Posted Successfully.";
	}else{
		$output = "Data Not Posted Successfully.";
	}
}


 function executePostVehicleDataCurlRequest($vendorDetails, $postData){
	echo $postData;
	$status = 0;
	$url = "https://test.transworldiot.oracleiotcloud.com/cgw/OraGenericConnector";
	//$url = "https://prod.transworldiot.oracleiotcloud.com/cgw/prodTestConnector";
	$objCurl = curl_init();
	curl_setopt($objCurl, CURLOPT_URL, $url);
	curl_setopt($objCurl, CURLOPT_POST, 1);
	curl_setopt($objCurl, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($objCurl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($objCurl, CURLOPT_USERPWD, $vendorDetails['userName'] . ":" . $vendorDetails['pwd']);
	curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, 1); // return into a variable
	curl_setopt($objCurl, CURLOPT_TIMEOUT, 10);
	//curl_setopt($objCurl, CURLINFO_HEADER_OUT, true);
    //curl_setopt($objCurl, CURLOPT_HEADER, true);
	//Get Response from resource
	
	/*
	$info = curl_getinfo($objCurl);
	echo "<br/><br/>";
	echo " Request Header";
	echo "<br/><br/>";
	print_r($info['request_header']);
	echo "<br/><br/>";
	echo " Response";
	echo "<br/><br/>";
	*/
	echo $jsonResponse = curl_exec($objCurl);

	if (curl_error($objCurl)) {
		echo 'error:' . curl_error($objCurl);
	}else{
		$status = 1;
	}
	curl_close($objCurl);
	return $status;
}

?>
