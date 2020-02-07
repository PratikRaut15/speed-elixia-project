<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$RELATIVE_PATH_DOTS = "../../../../";
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
$customerno = 42;
$objDeviceManager = new DeviceManager($customerno);

$objToken = new stdClass();
$objToken->customerno = $customerno;
$objToken->todaysdate = date(speedConstants::DATE_Ymd);

$arrTokenDetails = $objDeviceManager->getApiToken($objToken);
//print_r($arrTokenDetails);
if (isset($arrTokenDetails) && empty($arrTokenDetails)) {
    //echo "sdgfdasgf";
    $arrDetails = getUserAuthToken();
    if (isset($arrDetails) && !empty($arrDetails)) {
        $objDetails = new stdClass();
        $objDetails->authToken = $arrDetails['WWW-Authenticate'];
        $objDetails->clientSecretKey = $arrDetails['CLIENT_SECRET_KEY'];
        $objDetails->customerno = $customerno;
        $objDetails->validityDate = date(speedConstants::DATE_Ymd, strtotime('+365 days'));
        $objDetails->todaysdate = date(speedConstants::DEFAULT_TIMESTAMP);
        //print_r($objDetails);
        $logId = $objDeviceManager->insertApiToken($objDetails);
        if ($logId) {
            $arrTokenDetails = $objDeviceManager->getApiToken($objToken);
        }
    }
}
//print_r($arrTokenDetails);
if (isset($arrTokenDetails) && !empty($arrTokenDetails)) {
    $objDevice = new stdClass();
    $objDevice->pageindex = 1;
    $objDevice->pagesize = -1;
    $objDevice->iswarehouse = 0;
    $objDevice->searchstring = '';
    $objDevice->groupid = 0;
    $objDevice->userkey = '';
    $objDevice->isRequiredThirdParty = 1;
    $objDevice->customerno = $customerno;
    $arrDevices = $objDeviceManager->getDeviceDetails($objDevice);
    //print_r($arrDevices);
    $arrDeviceList = array();
    if (isset($arrDevices) && !empty($arrDevices)) {
        foreach ($arrDevices as $device) {
            $date1 = gmdate('Y-m-d', strtotime($device['lastupdated']));
            $date2 = $date1 . "T";
            $date3 = gmdate("H:i:s.z", strtotime($device['lastupdated']));
            $date4 = $date2 . $date3 . "Z";

            $objDevice = new stdClass();
            $objDevice->trackerId = $device['unitno'];
            $objDevice->latitude = $device['devicelat'];
            $objDevice->longitude = $device['devicelong'];
            $objDevice->time = $date4;
            $objDevice->batteryPerc = '100';
            $objDevice->speed = $device['curspeed'];
            $objDevice->messageType = 'REG';
            $objDevice->temperature = '';
            $arrDeviceList[] = $objDevice;
        }
    }
    //print_r($arrDeviceList);
    if (isset($arrDeviceList) && !empty($arrDeviceList)) {
        createTrackingRrecord($arrDeviceList, $arrTokenDetails);
    }
}

function getUserAuthToken() {
    $arrData = array();
    $arrData['userName'] = "ysharma@deepaknitrite.com";
    $arrData['password'] = "Dpl@123";
    $arrData['sessionExpiryTimeout'] = "8760";
    $postData = json_encode($arrData);
    //prettyPrint($arrData);
    //echo json_encode($arrData);
    $url = "https://api.loginextsolutions.com/LoginApp/login/authenticate";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, speedConstants::REQUEST_TIMEOUT);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    //curl_setopt($ch, CURLOPT_CAINFO, $RELATIVE_PATH_DOTS . 'modules/cron/customer/307/GeoTrustGlobalCA.crt');

    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_HEADER, true);

    $response = curl_exec($ch);

    $info = curl_getinfo($ch);
    echo "<br/><br/>";
    echo " Request Header";
    echo "<br/><br/>";
    print_r($info);
    echo "<br/><br/>";
    echo " Response";
    echo "<br/><br/>";
    $headers = get_headers_from_curl_response($response);
    //print_r($headers['WWW-Authenticate']);
    if (curl_error($ch)) {
        echo 'error:' . curl_error($ch);
    }
    curl_close($ch);
    return $headers;
}

function createTrackingRrecord($arrDeviceList, $arrTokenDetails) {
    $postData = json_encode($arrDeviceList);
    echo "<pre>"; //.$postData;
    //echo  json_encode($postData);die();
    $url = "https://api.loginextsolutions.com/TrackingApp/haul/v1/tracking/put";
    $wwwauth = "www-authenticate: " . $arrTokenDetails[0]->authToken;
    $clientkey = "client_secret_key: " . $arrTokenDetails[0]->clientSecretKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $wwwauth, $clientkey));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, speedConstants::REQUEST_TIMEOUT);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    //curl_setopt($ch, CURLOPT_CAINFO, $RELATIVE_PATH_DOTS . 'modules/cron/customer/307/GeoTrustGlobalCA.crt');

    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_HEADER, true);

    $response = curl_exec($ch);

    $info = curl_getinfo($ch);
    //echo "<br/><br/>";
    //echo " Request Header";
    //echo "<br/><br/>";
    //print_r($info);
    //echo "<br/><br/>";
    //echo " Response";
    //echo "<br/><br/>";
    //$headers = get_headers_from_curl_response($response);
    print_r($response);
    if (curl_error($ch)) {
        echo 'error:' . curl_error($ch);
    }
    curl_close($ch);
    //return $headers;
}

function get_headers_from_curl_response($response) {
    $headers = array();

    $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));

    foreach (explode("\r\n", $header_text) as $i => $line) {
        if ($i === 0) {
            $headers['http_code'] = $line;
        } else {
            list($key, $value) = explode(': ', $line);

            $headers[$key] = $value;
        }
    }

    return $headers;
}
