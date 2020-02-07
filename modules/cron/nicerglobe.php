<?php
require '../../lib/bo/DeviceManager.php';
require '../../lib/bo/CustomerManager.php';
$cm = new CustomerManager();
$customernos = Array(201, 203, 110, 227, 74, 907, 87, 296, 20);
$message = "";
$gpsProviderKey = "MBlsgl";
if (isset($customernos)) {
    foreach ($customernos as $thiscustomerno) {
        $dm = new DeviceManager($thiscustomerno);
        $devices = $dm->getdetails_nicerglobe($thiscustomerno);
        if (isset($devices)) {
            foreach ($devices as $device) {
                $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
            <gpsDataElement>
            <DATAELEMENTS>
            <DATAELEMENTS>
            <LATITUDE>' . $device->devicelat . '</LATITUDE>
            <LONGITUDE>' . $device->devicelong . '</LONGITUDE>
            <SPEED>' . $device->curspeed . '</SPEED>
            <HEADING>' . $device->directionchange . '</HEADING>
            <DATETIME>' . $device->lastupdated . '</DATETIME>
            <IGNSTATUS>' . $device->ignition . '</IGNSTATUS>
            <LOCATION></LOCATION>
            </DATAELEMENTS>
            </DATAELEMENTS>
            <VEHICLENO>' . $device->vehicleno . '</VEHICLENO>
            <GPSPROVIDERKEY>' . $gpsProviderKey . '</GPSPROVIDERKEY>
            </gpsDataElement>';
                //$URL = "http://nicerglobe.org/nicerglobeparser/gpsdataservice";
                $URL = "http://nicerglobe.info/nicerglobeparser/gpsdataservice";
                //setting the curl parameters.
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $URL);
                curl_setopt($ch, CURLOPT_VERBOSE, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
                if (curl_errno($ch)) {
                    // moving to display page to display curl errors
                    echo curl_errno($ch);
                    echo curl_error($ch);
                } else {
                    //getting response from server
                    $response = curl_exec($ch);
                    /*
                    if ($device->vehicleno == 'MH46BM1239') {
                    $info = curl_getinfo($ch);
                    echo "<br/><br/>";
                    echo " Request Header";
                    echo "<br/><br/>";
                    print_r($info['request_header']);
                    echo "<br/><br/>";
                    echo " Response";
                    echo "<br/><br/>";
                    print_r($response);
                    }
                     */
                    echo "<br/>" . $device->vehicleno . "<br/>";
                    print_r($response);
                    curl_close($ch);
                }
            }
        }
    }
}
?>