<?php

require_once("../../../lib/nusoap/lib/nusoap.php");
require_once("../../../config.inc.php");
require_once("class/class.api.php");
require_once ("../../../lib/system/Sanitise.php");

// <editor-fold defaultstate="collapsed" desc="Web Methods">
/**
 * VehicleLatestInfo method
 * @param1 string $userkey: user of webservice
 * @param2 string $vehicleno: vehicle number
 * @return array $getdata:
 * the required vehicle data with following details:
 * VehicleNo
 * Location
 * Datetime
 * Temperature
 * IgnitionStatus
 * Lat
 * Long
 * Distance
 */
function VehicleLatestInfo($userkey, $vehicleno) {
    $objapi = new api();
    $getdata = null;
    if (!empty($userkey) && !empty($vehicleno)) {
        $objGetPersonDetails = $objapi->get_person_details_by_key($userkey); // person data
        if (!empty($objGetPersonDetails)) {
            $customerno = $objGetPersonDetails['customerno'];
            $getdata = $objapi->getlatestvehicledata($vehicleno, $customerno);
        }
    }
    return $getdata;
}

/**
 * VehicleTripEndDetail method
 * @param1 string $userkey: user of webservice
 * @param2 string $vehicleno: vehicle number
 * @param3 string $reportloadingdate: reported loading date
 * @param4 string $loadingdate: actual loading date
 * @param5 string $reportunloadingdate: reported unloading date
 * @param6 string $unloadingdate: actual unloading date
 * @param7 string $maxidlespeed: max idle speed during transit
 * @return array $getdata:
 * the required data with following details:
 * VehicleNo
 * DateTime
 * LoadingKM
 * LoadingidleMinutes
 * TransitKM
 * TransitIdleMinutes
 * UnloadingKM
 * UnloadingIdleMinutes
 */
function VehicleTripEndDetail($userkey, $vehicleno, $reportloadingdate, $loadingdate, $reportunloadingdate, $unloadingdate, $maxidlespeed) {
    $objapi = new api();
    $getdata = null;
    if (!empty($userkey) && !empty($vehicleno)) {
        $objGetPersonDetails = $objapi->get_person_details_by_key($userkey); // person data
        if (!empty($objGetPersonDetails)) {
            $getdata = $objapi->gettripdataontripend($vehicleno, $reportloadingdate, $loadingdate, $reportunloadingdate, $unloadingdate, $maxidlespeed);
        }
    }
    return $getdata;
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="SOAP wrapper">
$namespace = "http://speed.elixiatech.com/services";
// create a new soap server
$server = new soap_server();
//configure response content-type
$server->soap_defencoding = 'UTF-8';
$server->decode_utf8 = false;
$server->encode_utf8 = true;
// configure our WSDL
$server->configureWSDL("ElixiaVTSService");
// set our namespace
$server->wsdl->schemaTargetNamespace = $namespace;
// <editor-fold defaultstate="collapsed" desc="Add Complex Types">
$server->wsdl->addComplexType(
        'VehicleResponse', 'complexType', 'struct', 'all', '', array(
    'VehicleNo' => array('name' => 'VehicleNo', 'type' => 'xsd:string'),
    'Location' => array('name' => 'Location', 'type' => 'xsd:string'),
    'Datetime' => array('name' => 'Datetime', 'type' => 'xsd:string'),
    'Temperature' => array('name' => 'Temperature', 'type' => 'xsd:decimal'),
    /*
      'Temperature2' => array('name' => 'Temperature2', 'type' => 'xsd:decimal'),
      'Temperature3' => array('name' => 'Temperature3', 'type' => 'xsd:decimal'),
      'Temperature4' => array('name' => 'Temperature4', 'type' => 'xsd:decimal'),
     */
    'IgnitionStatus' => array('name' => 'GensetStatus', 'type' => 'xsd:string'),
    'Lat' => array('name' => 'Lat', 'type' => 'xsd:decimal'),
    'Long' => array('name' => 'Long', 'type' => 'xsd:decimal'),
    'Distance' => array('name' => 'Distance', 'type' => 'xsd:decimal'),
    'innerXML' => array('name' => 'innerXML', 'type' => 'xsd:string')
        )
);
$server->wsdl->addComplexType(
        'TripDataObject', 'complexType', 'struct', 'all', '', array(
    'VehicleNo' => array('name' => 'VehicleNo', 'type' => 'xsd:string'),
    'DateTime' => array('name' => 'DateTime', 'type' => 'xsd:string'),
    'LoadingKM' => array('name' => 'LoadingKM', 'type' => 'xsd:decimal'),
    'LoadingIdleMinutes' => array('name' => 'LoadingIdleMinutes', 'type' => 'xsd:decimal'),
    'TransitKM' => array('name' => 'TransitKM', 'type' => 'xsd:decimal'),
    'TransitIdleMinutes' => array('name' => 'TransitIdleMinutes', 'type' => 'xsd:decimal'),
    'UnloadingKM' => array('name' => 'UnloadingKM', 'type' => 'xsd:decimal'),
    'UnloadingIdleMinutes' => array('name' => 'UnloadingIdleMinutes', 'type' => 'xsd:decimal'),
    'TotalCount' => array('name' => 'TotalCount', 'type' => 'xsd:int')
        )
);
//</editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="Register Web Methods">
// register our WebMethod
$server->register(
        // method name:
        'VehicleLatestInfo',
        // parameter list:
        array('userkey' => 'xsd:string',
    'vehicleno' => 'xsd:string'),
        // return value(s):
        array('return' => 'tns:VehicleResponse'),
        // namespace:
        $namespace,
        // soapaction: (use default)
        false,
        // style: rpc or document
        'rpc',
        // use: encoded or literal
        'encoded',
        // description: documentation for the method
        'A web method to get Vehicle Latest Information');
$server->register(
        // method name:
        'VehicleTripEndDetail',
        // parameter list:
        array('userkey' => 'xsd:string',
    'vehicleno' => 'xsd:string',
    'reportloadingdate' => 'xsd:string',
    'loadingdate' => 'xsd:string',
    'reportunloadingdate' => 'xsd:string',
    'unloadingdate' => 'xsd:string',
    'maxidlespeed' => 'xsd:string'
        ),
        // return value(s):
        array('return' => 'tns:TripDataObject'),
        // namespace:
        $namespace,
        // soapaction: (use default)
        false,
        // style: rpc or document
        'rpc',
        // use: encoded or literal
        'encoded',
        // description: documentation for the method
        'A web method to get vehicle trip end details');
// </editor-fold>
// Get our posted data if the service is being consumed
// otherwise leave this data blank.
$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';

// pass our posted data (or nothing) to the soap service
$server->service($POST_DATA);
exit();
// </editor-fold>
?>
