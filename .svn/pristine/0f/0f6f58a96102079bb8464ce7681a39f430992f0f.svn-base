<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once '../../lib/system/utilities.php';
require_once '../../lib/autoload.php';
require_once '../../modules/trips/class/TripsManager.php';
echo "<br/> Cron Start On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";
define("DATEFORMAT_YMD", "Y-m-d");
define("EFLEET_URL", "http://hfc.efleetsystems.in/HFCTriplogData/Service.asmx?WSDL");
define("EFLEET_TOKENNO", "12");
define("INTERVAL", "4");
define("DEBUG", "1");
$customerno = 206;
$userid = 1078;
/* Get All Open Trips */
$objTripManager = new Trips($customerno, $userid);
$openTrips = $objTripManager->get_viewtriprecords();
//prettyPrint($openTrips);die();
if (isset($openTrips)) {
    foreach ($openTrips as $trip) {
        if (($trip['consignorname'] != '' && $trip['consigneename'] != '') ||
            ($trip['consignorname'] == '' && $trip['consigneename'] == '' && $trip['billingparty'] == "Empty")) {
            $unloadingendStatusId = 0;
            $unloadenddate = '';
            $unloadendtime = '';
            $client = new SoapClient(EFLEET_URL);
            $params = new stdClass();
            $params->Triplogno = $trip['triplogno'];
            $result = $client->eFleet_GetTripLogCloserDetails($params)->eFleet_GetTripLogCloserDetailsResult;
            $xml = simplexml_load_string($result->any);
            if (isset($xml) && $xml->children()->count() === 1) {
                foreach ($xml->children() as $children) {
                    $objTripDetails = GetTripDetailsFromXml($children);
                    if ($objTripDetails->unloadingstart != "" && $objTripDetails->unloadingend != "") {
                        $objTripDetails->customerno = $customerno;
                        $objTripDetails->userid = $userid;
                        $objTripDetails->triplogno = $trip['triplogno'];
                        $tripDetails = $objTripManager->gettripdetails(NULL, $objTripDetails->triplogno);
                        if (isset($tripDetails) && !empty($tripDetails)) {
                            $tripDetails['customerno'] = $customerno;
                            $tripDetails['userid'] = $userid;
                            $tripDetails['triplogno'] = $trip['triplogno'];
                            $tripDetails['unitno'] = $trip['unitno'];
                            $tripDetails['startdate'] = $trip['startdate'];
                            $tripDetails['unloadingstart'] = $objTripDetails->unloadingstart;
                            $tripDetails['unloadingend'] = $objTripDetails->unloadingend;
                            if ($objTripDetails->unloadingstart) {
                                $updateTripDetails = GetExistingTripDetails($tripDetails, 9);
                                //prettyPrint($updateTripDetails);
                                $objTripManager->edittripdetails((array) $updateTripDetails);
                            }
                            if ($objTripDetails->unloadingend) {
                                $updateTripDetails = GetExistingTripDetails($tripDetails, 10);
                                //prettyPrint($updateTripDetails);
                                $objTripManager->edittripdetails((array) $updateTripDetails);
                            }
                        }
                    }
                }
            }
        }
    }
}
function GetTripDetailsFromXml($xmlChild) {
    $objTripDetails = new stdClass();
    $objTripDetails->unloadingstart = (string) $xmlChild->ReportUnloadingDate;
    $objTripDetails->unloadingend = (string) $xmlChild->UnloadingDate;
    return $objTripDetails;
}

function GetExistingTripDetails($objTripDetails, $statusid) {
    $objTrip = new stdClass();
    //prettyPrint($objTripDetails);die();
    foreach ($objTripDetails as $key => $value) {
        $value = isset($value) ? $value : '';
        if ($key == 'is_tripend') {
            $objTrip->istripend = $value;
        }
        $objTrip->$key = $value;
        $objTrip->tripstatus = $statusid;
        $objTrip->statusodometer = '0';
        $objTrip->actualhrs = '0';
    }
    if ($objTrip->tripstatus == 9) {
        $objTrip->statusdate = $objTrip->unloadingstart;
        $unloadstartdate = new DateTime($objTrip->$key);
        $objTrip->statusdatetime = $unloadstartdate->format('Y-m-d H:i:s');
        $objTrip->statusodometer = GetLoadingOdometer($objTrip->statusdatetime, $objTrip->vehicleid, $objTrip->customerno);
    } elseif ($objTrip->tripstatus == 10) {
        $objTrip->istripend = 1;
        $objTrip->statusdate = $objTrip->unloadingend;
        $unloadenddate = new DateTime($objTrip->unloadingend);
        $objTrip->statusdatetime = $unloadenddate->format('Y-m-d H:i:s');
        $actualhrs = round((strtotime($objTrip->statusdatetime) - strtotime($objTrip->startdate)) / (60 * 60));
        $objTrip->actualhrs = $actualhrs;
        $objTrip->statusodometer = GetLoadingOdometer($objTrip->statusdatetime, $objTrip->vehicleid, $objTrip->customerno);
    }
    return $objTrip;
}

function GetLoadingOdometer($loadendDate, $vehicleid, $customerno) {
    //Get data from From Unit Sqlite
    $statusodometer = 0;
    $objLoadEndDate = new DateTime($loadendDate);
    $loadEndDay = $objLoadEndDate->format('Y-m-d');
    $loadEndTime = $objLoadEndDate->format('H:m:s');
    $objUnitManager = new UnitManager($customerno);
    $unitno = $objUnitManager->getunitno($vehicleid);
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$loadEndDay.sqlite";
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $statusodometerquery = "SELECT odometer FROM vehiclehistory WHERE time(lastupdated) >= '" . $loadEndTime . "' ORDER BY lastupdated ASC LIMIT 1;";
        $result = $db->query($statusodometerquery);
        if ($result !== false) {
            $arrQueryResult = $result->fetchAll();
            if (is_array($arrQueryResult) && !empty($arrQueryResult)) {
                $statusodometer = $arrQueryResult[0]['odometer'];
            }
        }
    }
    return $statusodometer;
}

echo "<br/> Cron Completed On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";
?>
