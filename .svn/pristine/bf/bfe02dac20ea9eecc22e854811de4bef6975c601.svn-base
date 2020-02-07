<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$RELATIVE_PATH_DOTS = "../../../../";
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
echo "<br/> Cron Start On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";
$customerno = 52;
$arrVehicles = array(4436, 1000, 4638, 1055, 4395, 4427, 4489, 4424, 5249, 7096, 7075);
//$arrVehicles = array(4436);
if (isset($arrVehicles) && !empty($arrVehicles)) {
    $vehicleIds = implode(',', $arrVehicles);
    $objDeviceManager = new DeviceManager($customerno);
    $arrDevices = $objDeviceManager->getDetailsForExpeditors($vehicleIds);
    if (isset($arrDevices) && !empty($arrDevices)) {
        foreach ($arrDevices as $device) {
            $arrCoc = explode(",", $device->triplogno);
            if (isset($arrCoc) && !empty($arrCoc)) {
                foreach ($arrCoc as $coc) {
                    $arrData = array();
                    $arrData['eventTimeUtc'] = gmdate('Y-m-d H:i:s.u', strtotime($device->dlastupdated));
                    $arrData['deviceId'] = $device->unitno;
                    $arrData['latitude'] = $device->devicelat;
                    $arrData['longitude'] = $device->devicelong;
                    $arrData['reference'] = $coc;
                    //$arrData['hepeValue'] = '';
                    //$arrData['fixType'] = '';
                    $arrData['token'] = speedConstants::API_EXPEDITORS_TOKEN;
                    $arrData['gci'] = speedConstants::API_EXPEDITORS_GCI;
                    $postData = json_encode($arrData);
                    //prettyPrint($arrData);
                    //$url = "https://api-dot-iot-expeditors-qa.appspot-preview.com/gsecloc/api/locations/shipment";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, speedConstants::API_EXPEDITORS_URL);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
                    curl_setopt($ch, CURLOPT_TIMEOUT, speedConstants::API_EXPEDITORS_TIMEOUT);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                    curl_setopt($ch, CURLOPT_CAINFO, $RELATIVE_PATH_DOTS . 'modules/cron/customer/52/GeoTrustGlobalCA.crt');
                    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                    curl_setopt($ch, CURLOPT_HEADER, true);
                    $response = curl_exec($ch);
                    $info = curl_getinfo($ch);
                    echo "<br/><br/>";
                    echo " Request Header";
                    echo "<br/><br/>";
                    print_r($info['request_header']);
                    echo "<br/><br/>";
                    echo " Response";
                    echo "<br/><br/>";
                    print_r($response);
                    if (curl_error($ch)) {
                        echo 'error:' . curl_error($ch);
                    }
                    curl_close($ch);
                    echo speedConstants::API_EXPEDITORS_URL;
                }
            }
        }
    }
}
echo "<br/> Cron Completed On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";
?>
