<?php

error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'on');
$params = new stdClass();
try {
	
    $client = new SoapClient("http://115.124.100.86/GEODATA/Service.asmx?WSDL");
    $params->VehicleNo = 'MH20CT1789';
    $params->Location = 'Lonikand, Maharashtra, India';
    $params->GEOFenceName = 'Lonikand, Maharashtra, India';
    $params->Nature = 'OUT';
    $params->GEODate = '2016-10-03';
    $params->token = 'SGZDX0AjMjAxNQ--';
    $result = $client->TPSService_GEOData($params)->TPSService_GEODataResult;

    echo "<pre>";
    echo "Request:";
    print_r($params);
    echo "Response:";
    if (isset($result)) {
        print_r($result);
    }
    die();
	






    $client = new SoapClient("http://115.124.100.86/GetTripLogDetails/service.asmx?WSDL");
    $params->FromDateTime = '2016-11-27';
    $params->ToDateTime = '2016-11-29';
    $params->TokenNo = '12';
    $result = $client->eFleet_GetTripLogDetails($params)->eFleet_GetTripLogDetailsResult;
    $xml = simplexml_load_string($result->any);
    echo "<pre>";
    if (isset($xml)) {
        foreach ($xml->children() as $child) {
            print_r($child);
            /*
              echo $child->TripNO . "<br>";
              echo $child->VehicleNo . "<br>";
              echo $child->Route . "<br>";
              echo $child->From . "<br>";
              echo $child->To . "<br>";
              echo $child->LodingDate . "<br>";
              echo $child->ReportLoadingDate . "<br>";
              echo $child->BudgetedKm . "<br>";
              echo $child->BudgetHours . "<br>";
              echo $child->DriverName . "<br>";
              foreach ($child->DriverMobileNo as $mobileno) {
              echo $mobileno . "<br>";
              }
              echo $child->ClientName . "<br><br><br>";
             */
        }
    }
}
catch (SoapFault $e) {
    //echo "<pre>SoapFault: " . print_r($e, true) . "</pre>\n";
    echo "Soap Exception occured.<br/>";
    echo "Faultcode: " . $e->faultcode . "<br/>";
    echo "Faultstring: " . $e->getMessage() . "";
}
catch (Exception $e) {
    // handle PHP issues with the request
}


//print_r($xml);

/*
  require_once("../../../lib/nusoap/lib/nusoap.php");
  $params = new stdClass();
  $params->FromDateTime = '2015-12-01';
  $params->ToDateTime = '2015-12-09';
  $client = new nusoap_client('http://125.99.83.35/GetTripLogDetails/service.asmx?WSDL', 'wsdl');
  $client->soap_defencoding = 'utf-8';
  $result = $client->call('eFleet_GetTripLogDetails', array($params));

  echo "<pre>";
  print_r($result);
 * 
 */
?>
