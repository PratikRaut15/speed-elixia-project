<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

//file required
//error_reporting(E_ALL ^E_STRICT);
//ini_set('display_errors', 'on');
$RELATIVE_PATH_DOTS = "../../../../";
require_once($RELATIVE_PATH_DOTS . "lib/system/utilities.php");
require_once ($RELATIVE_PATH_DOTS . "lib/system/Sanitise.php");
require_once("class/class.api.php");
require_once("class/model/RequestClass.php");
require_once("class/model/ResponseClass.php");
require_once($RELATIVE_PATH_DOTS . "vendor/autoload.php");
require_once($RELATIVE_PATH_DOTS . "lib/autoload.php");

define("GEOTRACKER_LOCATION_API", "http://blazer7.geotrackers.co.in/GTWS/gtWs/LocationWs");
define("TIMEOUT_SECS", "30");

$action = "";
$objApi = new api();
$output = null;

extract($_REQUEST);

$arrOutputData = $objApi->failure("Missing params");
if ($action == "getAllVehicleData") {
    $inputJsonData = json_decode($jsonreq);
    
    ################ Set Date Time for Distance Api call #############
    #################################################
    $todaysDate = date("Y-m-d");            
    $currentDateTime = $endDateTime = $todaysDate." ".date("H:i:s");
    $startDateTime = date('Y-m-d H:i:s', strtotime($currentDateTime.'-10 minute'));
    $fromDateTimestamp = strtotime($startDateTime)*1000; 
    $toDateTimestamp = strtotime($endDateTime)*1000; 

    ################ END Set Date Time for Distance Api call #############
    #################################################

    //Resource Address
    $url = GEOTRACKER_LOCATION_API . "/getUsrLatestLocation";

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

    //print_r(json_decode($jsonResponse, true)); exit();
    //Process the response
    $finalArray = array();
    if (isset($jsonResponse) && $jsonResponse != "") {
        $arrResponse = json_decode($jsonResponse, true);
        $arrOutput = array_values($arrResponse);

        ############# Parsing Data ################
        $i = 1;
        $count = count($arrOutput); 
        // echo $count; die;

        foreach ($arrOutput as $arrVehData) {
            $distanceData = array();
            // echo "Loop ". $i. "<br>";

            $objVehDetail = array();
            $vehData = isset($arrVehData[0]) ? (object)$arrVehData[0] : NULL;
            //print_r($vehData); die;
            if (isset($vehData) && !empty($vehData)) { //  && $i <= 20

                $vehicleNo = str_replace(" ", "%20", $vehData->regNo);
                // $timestampDate = date('Y-m-d H:i:s', ($vehData->timestamp / 1000));

            // if($vehData->regNo == "DBRC-NL 01N 2135"){
                ################### IF Gps Timestamp in between the fromDateTime and toDateTime ####################
                #################### Then Distance API Would Call ##################################################

                $lastUpdatedDate = $todaysDate;
                if(isset($inputJsonData->vendorDetails->customerNo) && !empty($inputJsonData->vendorDetails->customerNo)){
                    $pos = strpos($vehData->regNo, "-");
                    $rest = substr($vehData->regNo, $pos+1);
                    $parsedVehicleNo = str_replace(" ", "", $rest);

                    $objVehicleManager = new VehicleManager($inputJsonData->vendorDetails->customerNo);
                    $arrHistData = $objVehicleManager->get_vehicle_by_vehno($parsedVehicleNo, 1);

                    ### Override the dates #######
                    if(isset($arrHistData) && !empty($arrHistData)){
                        ########### Set stoppageOdometer and stoppageFlag to Status in RTD idle, inactive since...#########
                            $vehData->stoppageOdometer = isset($arrHistData[0]->stoppage_odometer) ? $arrHistData[0]->stoppage_odometer : 0;
                            $vehData->stoppageFlag = isset($arrHistData[0]->stoppage_flag) ? $arrHistData[0]->stoppage_flag : 0;
                        ########### Set stoppageOdometer and stoppageFlag to Status in RTD idle, inactive since...#########
                        if(isset($arrHistData[0]->lastupdated) && $arrHistData[0]->lastupdated != ""){
                            $lastUpdatedDate = date("Y-m-d", strtotime($arrHistData[0]->lastupdated));
                            if($todaysDate == $lastUpdatedDate){
                                $startDateTime = $arrHistData[0]->lastupdated;
                            }else{
                                $startDateTime = date('Y-m-d H:i:s', strtotime($currentDateTime.'-10 minute'));
                            }
                        }
                    }  
                }else{
                    $lastUpdatedDate = $timestampDate = date('Y-m-d', ($vehData->timestamp / 1000));
                }
                
                /*echo "<br><br>";
                print_r($startDateTime);
                echo "<br>";
                print_r($endDateTime);
                continue;*/
                //die;
                
                $fromDateTimestamp = strtotime($startDateTime)*1000; 
                $toDateTimestamp = strtotime($endDateTime)*1000;
                
                ##################

                $url1 = "";
                // if($fromDateTimestamp <= $vehData->timestamp && $toDateTimestamp >= $vehData->timestamp){
                // if($todaysDate == $lastUpdatedDate){
                if(true){
                    // echo "Loop ". $i. "<br>";
                    
                    // $jsonDistanceData = array();
                    // $encodedVehicleNo = urlencode($vehicleNo);
                    $encodedVehicleNo = $vehicleNo;
                    $url1 = GEOTRACKER_LOCATION_API . "/getTotalDistance/".$encodedVehicleNo."/".$fromDateTimestamp."/".$toDateTimestamp;
                    // echo $url1."<br>"; die;
                    # http%3A%2F%2Fblazer7.geotrackers.co.in%2FGTWS%2FgtWs%2FLocationWs%2FgetTotalDistance%2FDBRC-NL%2501L%256888%2F1574930820000%2F1574931420000
                    # <br>http://blazer7.geotrackers.co.in/GTWS/gtWs/LocationWs/getTotalDistance/DBRC-NL%2501L%256888%2F1574931180000%2F1574931780000
                    // echo "<br>".$url; die;
                    // die;

                    $arrHeaders = array();
                    //$authorization =  "Authorization: Bearer d057711b90f428c0af61700c2e9193f3";
                    $arrHeaders[] = "Connection: keep-alive";

                    $objCurl1 = curl_init();
                    curl_setopt($objCurl1, CURLOPT_URL, $url1);
                    curl_setopt($objCurl1, CURLOPT_RETURNTRANSFER, 1); // return into a variable
                    curl_setopt($objCurl1, CURLOPT_TIMEOUT, 120);
                    // curl_setopt($objCurl1, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    curl_setopt($objCurl1, CURLOPT_USERPWD, $inputJsonData->vendorDetails->userName . ":" . $inputJsonData->vendorDetails->pwd);
                    /*curl_setopt($objCurl1, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:53.0) Gecko/20100101 Firefox/53.0");
                    curl_setopt($objCurl1, CURLOPT_ENCODING , "gzip"); 
                    curl_setopt($objCurl1, CURLOPT_ENCODING, '');
                    curl_setopt($objCurl1, CURLOPT_HTTPHEADER, $arrHeaders);
                    curl_setopt($objCurl1, CURLOPT_ENCODING, "");
                    curl_setopt($objCurl1, CURLOPT_MAXREDIRS, 10);
                    curl_setopt($objCurl1, CURLOPT_CUSTOMREQUEST, "GET");*/
                    //Get Response from resource
                    $jsonDistanceData = curl_exec($objCurl1);
                    // $jsonDistanceData = json_encode(array("totalDistKm"=>"4.3211"));

                    /*Tetsing Code Here*/
                        
                        /*if($jsonDistanceData===false){ 
                            continue;
                         }

                         if ( empty( $response ) ) {
                            // log your error & and try again
                            continue;
                        }*/

                    /*End Tetsing Code Here*/
                    if(!empty($jsonDistanceData)){
                        $vehicleDistance = json_decode($jsonDistanceData);

                        if (curl_error($objCurl1)) {
                            $errorMsg = 'error:' . curl_error($objCurl1);
                        }
                         
                        curl_close($objCurl1); 

                        $vehData->vehicleDistance = isset($vehicleDistance->totDistKm) ? $vehicleDistance->totDistKm : 0;
                        $vehData->ApiUrl = $url1;

                        $objVehDetail[] = $vehData; // Raw data
                        $objVehDetail[] = ParseData($vehData); // Parsed data

                        $finalArray[] = $objVehDetail;
                    }else{
                        continue;
                    }

                     
                }else{
                    ############# TO DO #############
                    #### For Historic Data ##########
                    #################################
                  // echo "<br> else called ";
                }    
        
                /*$vehData->vehicleDistance = isset($vehicleDistance->totDistKm) ? $vehicleDistance->totDistKm : 0;
                $vehData->ApiUrl = $url1;

                $objVehDetail[] = $vehData; // Raw data
                $objVehDetail[] = ParseData($vehData); // Parsed data

                $finalArray[] = $objVehDetail;*/


            }
           $i++; 
             
        }
        /*print("final Array");
        echo "<br>counter is ".$i;
        print("<br><pre>");
        print_r($finalArray); die;*/

        /*print("disatance -- ");
        print("<pre>");
        print_r($distanceData); 
        die;*/
        // print_r($finalArray); die;

        ############# END Parsing Data ################

        // $arrOutputData = $objApi->success("Fetched Data successfully", $arrOutput);
        $arrOutputData = $objApi->success("Fetched Data successfully", $finalArray);
    }
    else {
        $arrOutputData = $objApi->failure("Unable to fetch data - " . $errorMsg);
    }
   
}
else if ($action == "getSpecificVehicleData") {
    $inputJsonData = json_decode($jsonreq);
    if (isset($inputJsonData->vehRegNo)) {
        //Resource Address
        $url = GEOTRACKER_LOCATION_API . "/getLatestLocation/" . $inputJsonData->vehRegNo;

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
else if ($action == "getAllVehicleList") {
    $inputJsonData = json_decode($jsonreq);
    //Resource Address
    $url = GEOTRACKER_LOCATION_API . "/getUsrVehicles";

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


############################# Parsing Data ###########################

function ParseData($vehData) {
    $objVehDetail = new stdClass();
    // $objVehDetail->vehNo = str_replace(" ", "", str_replace(SAHARAROADLINES_CUST_ID_SR . "-", "", $vehData->regNo));
    $pos = strpos($vehData->regNo, "-");
    $rest = substr($vehData->regNo, $pos+1);
    $objVehDetail->vehNo = str_replace(" ", "", $rest);

    $objVehDetail->deviceLat = $vehData->lattitude;
    $objVehDetail->deviceLng = $vehData->longitude;
    $objVehDetail->altitude = $vehData->elevation;
    $objVehDetail->direction = $vehData->direction;
    $objVehDetail->distance = isset($vehData->distance) ? ROUND(($vehData->distance * 1000),0) : 0;
    $objVehDetail->odometer = isset($vehData->vehicleDistance) ? ROUND(($vehData->vehicleDistance * 1000),0) : 0;
    $objVehDetail->speed = $vehData->speed;
    $objVehDetail->analog1 = isset($vehData->Temperature) ? $vehData->Temperature * 100 : 0;
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

    $objVehDetail->stoppageOdometer = isset($vehData->stoppage_odometer) ? $vehData->stoppage_odometer : 0;
    $objVehDetail->stoppageFlag = isset($vehData->stoppage_flag) ? $vehData->stoppage_flag : 0;

    if (strpos(strtoupper($vehData->bmStr), 'AC ON') != false) {
        $objVehDetail->digitalio = 1;
    }
    else {
        $objVehDetail->digitalio = 0;
    }
    if (strpos(strtoupper($vehData->bmStr), 'KEY ON') != false) {
        $objVehDetail->ignition = 1;
    }
    else {
        $objVehDetail->ignition = 0;
    }
    $objVehDetail->lastUpdated = date('Y-m-d H:i:s', ($vehData->timestamp / 1000));
    //TODO
    $objVehDetail->stoppageTransitTime = $objVehDetail->lastUpdated;
    return $objVehDetail;
}


if (!isset($arrOutputData)) {
    $arrOutputData = $objApi->failure("Seems like you are playing around with incorrect actions or without any actions !!");
}
echo json_encode($arrOutputData);
?>