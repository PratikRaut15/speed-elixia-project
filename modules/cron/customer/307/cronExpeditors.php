<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$RELATIVE_PATH_DOTS = "../../../../";
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
$customerno = 307;
$arrVehicles = array(4741, 7462, 7992, 8865, 9510, 8233, 10129);
$customerId = "CS21521706";
$serviceProvidorId = "G2071784";
$authorization = "Authorization: Bearer eyJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJFeHBlZGl0b3JzIEludGVybmF0aW9uYWwiLCJzdWIiOiJHMjA3MTc4NCIsImNvbXBhbnlJZCI6IkcyMDcxNzg0IiwibmFtZSI6IlN1cHJlbWUgRnJlaWdodHdheSBDYXJyaWVycyIsInR5cGUiOiJTRVJWSUNFUFJPVklERVIiLCJyZXF1ZXN0b3IiOiJQYWxsYXZpLlNpbmdoQGV4cGVkaXRvcnMuY29tIiwicmVxdWVzdG9yRW1haWwiOiJQYWxsYXZpLlNpbmdoQGV4cGVkaXRvcnMuY29tIn0.Hue9dqIPk4ujh-cvsunWCWXia3YwpPf_Dv_yZ3-prUUybsR8K1lO0pygeiTHRRP_rRbvqiQlMBhnANZJjE_VpQ";
$arrHeaders = array();
$arrHeaders[] = "Content-Type: application/json";
$arrHeaders[] = $authorization;
if (isset($arrVehicles) && !empty($arrVehicles)) {
    $vehicleIds = implode(',', $arrVehicles);
    $objDeviceManager = new DeviceManager($customerno);
    $arrDevices = $objDeviceManager->getDetailsForExpeditors($vehicleIds);
    $arrCreateShipment = array();
    $arrTrackShipment = array();
    $arrCompleteShipment = array();
    if (isset($arrDevices) && !empty($arrDevices)) {
        foreach ($arrDevices as $device) {
            if (!$device->shipmentId) {
                $arrCreateShipment[] = $device;
            } elseif ($device->shipmentId && $device->shipmentInitiated == 1 && $device->is_tripend == 0) {
                $arrTrackShipment[] = $device;
            } elseif ($device->shipmentId && $device->is_tripend == 1) {
                $arrCompleteShipment[] = $device;
            }
        }
    }
    //die();
    //print_r($arrCreateShipment);
    //print_r($arrTrackShipment);
    //print_r($arrCompleteShipment);
    if (isset($arrCreateShipment) && !empty($arrCreateShipment)) {
        foreach ($arrCreateShipment as $shipment) {
            $arrCoc = explode(",", $shipment->triplogno);
            if (isset($arrCoc) && !empty($arrCoc)) {
                foreach ($arrCoc as $coc) {
                    $arrData = array();
                    $date1 = gmdate('Y-m-d', strtotime($shipment->statusdate));
                    $date2 = $date1 . "T";
                    $date3 = gmdate("H:i:s.z", strtotime($shipment->statusdate));
                    $date4 = $date2 . $date3 . "Z";
                    $objGeo = new GeoCoder($customerno);
                    $location = $objGeo->get_location_bylatlong($shipment->devicelat, $shipment->devicelong);
                    $arrData['referenceNumber'] = $coc;
                    $arrData['customerId'] = $customerId;
                    $arrData['serviceProviderId'] = $serviceProvidorId;
                    $arrData['scheduledPickupTime'] = $date4;
                    $arrData['pickupLocation'] = array("formattedAddress" => $location);
                    $arrData['deliveryLocation'] = array("formattedAddress" => $location);
                    $postData = json_encode($arrData);
                    $url = "https://api.cargosignal.com/shipments";
                    $curlResponse = executeCurl($url, $arrHeaders, $postData, "POST");
                    //print_r($curlResponse);
                    if (isset($curlResponse) && !empty($curlResponse) && isset($curlResponse->shipmentId)) {
                        $objShipment = new stdClass();
                        $objShipment->expId = $shipment->expId;
                        $objShipment->referenceNumber = $shipment->referenceNumber;
                        $objShipment->shipmentId = $curlResponse->shipmentId;
                        $objShipment->customerNo = $shipment->customerNo;
                        $objShipment->datetime = date('Y-m-d H:i:s');
                        $objDeviceManager->updateExpeditorTripDetails($objShipment);
                        $urlInitiate = "https://api.cargosignal.com/shipment/" . $objShipment->shipmentId . "/pickupTime";
                        $arrDataInitiate = array();
                        $arrDataInitiate['actualPickupDateTime'] = $date4;
                        $postDataInitiate = json_encode($arrDataInitiate);
                        $curlInitiateResponse = executeCurl($urlInitiate, $arrHeaders, $postDataInitiate, "PUT");
                        if (isset($curlInitiateResponse) && !empty($curlInitiateResponse)) {
                            if (isset($curlInitiateResponse->actualPickupDateTime)) {
                                $objShipment->shipmentInitiated = 1;
                                $objDeviceManager->updateExpeditorTripDetails($objShipment);
                            }
                        }
                    }
                }
            }
        }
    }
    if (isset($arrTrackShipment) && !empty($arrTrackShipment)) {
        foreach ($arrTrackShipment as $shipment) {
            $arrCoc = explode(",", $shipment->triplogno);
            if (isset($arrCoc) && !empty($arrCoc)) {
                foreach ($arrCoc as $coc) {
                    $arrData = array();
                    $date1 = date('Y-m-d', strtotime($shipment->dlastupdated));
                    $date2 = $date1 . "T";
                    $date3 = date("H:i:s.z", strtotime($shipment->dlastupdated));
                    $date4 = $date2 . $date3 . "Z";
                    $arrData['eventDateTime'] = $date4;
                    $arrData['geolocation'] = array("latitude" => $shipment->devicelat, "longitude" => $shipment->devicelong);
                    $postData = json_encode($arrData);
                    $url = "https://api.cargosignal.com/shipment/" . $shipment->shipmentId . "/locations";
                    $curlResponse = executeCurl($url, $arrHeaders, $postData, "PUT");
                }
            }
        }
    }
    if (isset($arrCompleteShipment) && !empty($arrCompleteShipment)) {
        foreach ($arrCompleteShipment as $shipment) {
            $arrCoc = explode(",", $shipment->triplogno);
            if (isset($arrCoc) && !empty($arrCoc)) {
                foreach ($arrCoc as $coc) {
                    $arrData = array();
                    $date1 = date('Y-m-d', strtotime($shipment->statusdate));
                    $date2 = $date1 . "T";
                    $date3 = date("H:i:s.z", strtotime($shipment->statusdate));
                    $date4 = $date2 . $date3 . "Z";
                    $arrData['actualDeliveryDateTime'] = $date4;
                    $postData = json_encode($arrData);
                    $url = "https://api.cargosignal.com/shipment/" . $shipment->shipmentId . "/deliveryTime";
                    $curlResponse = executeCurl($url, $arrHeaders, $postData, "PUT");
                    if (isset($curlResponse) && !empty($curlResponse) && isset($curlResponse->actualDeliveryDateTime)) {
                        $objShipment = new stdClass();
                        $objShipment->expId = $shipment->expId;
                        $objShipment->referenceNumber = $shipment->referenceNumber;
                        $objShipment->shipmentId = $shipment->shipmentId;
                        $objShipment->customerNo = $shipment->customerNo;
                        $objShipment->datetime = date('Y-m-d H:i:s');
                        $objShipment->shipmentCompleted = 1;
                        $objDeviceManager->updateExpeditorTripDetails($objShipment);
                    }
                }
            }
        }
    }
}

function executeCurl($url, $arrHeaders, $postData, $type) {
    echo "<pre>";
    echo "URL : ".$url."<br/>";
    echo "Request : ".$postData."<br/>";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeaders);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, speedConstants::API_EXPEDITORS_TIMEOUT);
    if ($type == "POST") {
        curl_setopt($ch, CURLOPT_POST, 1);
    } elseif ($type == "PUT") {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    }
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    //curl_setopt($ch, CURLOPT_CAINFO, $RELATIVE_PATH_DOTS . 'modules/cron/customer/307/GeoTrustGlobalCA.crt');
    //curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    //curl_setopt($ch, CURLOPT_HEADER, true);
    $response = curl_exec($ch);
    $info = curl_getinfo($ch);
    //echo "<br/><br/>";
    //echo " Request Header";
    //echo "<br/><br/>";
    //print_r($info['request_header']);
    //echo "<br/><br/>";
    //echo " Response";
    //echo "<br/><br/>";
    echo"Response :".($response)."<br/>";
    $response = json_decode($response);
    if (curl_error($ch)) {
        echo 'error:' . curl_error($ch);
    }
    return $response;
    curl_close($ch);
}
