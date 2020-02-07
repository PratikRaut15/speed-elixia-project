<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$RELATIVE_PATH_DOTS = "../../";
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
require_once '../../modules/trips/class/TripsManager.php';
echo "<br/> Cron Start On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";
define("DATEFORMAT_YMD", "Y-m-d");
define("EFLEET_URL", "http://hfc.efleetsystems.in/HFCTriplogData/Service.asmx?WSDL");
define("EFLEET_TOKENNO", "12");
define("INTERVAL", "4");
define("DEBUG", "1");
$customerno = 206;
$userid = 1078;
try {
    $client = new SoapClient(EFLEET_URL);
    $toDateTime = new DateTime();
    $toDate = $toDateTime->format(DATEFORMAT_YMD);
    $fromDateTime = $toDateTime->sub(new DateInterval('P' . INTERVAL . 'D'));
    $fromDate = $fromDateTime->format(DATEFORMAT_YMD);
    $params = new stdClass();
    $params->FromDateTime = $fromDate;
    $params->ToDateTime = $toDate;
    $params->TokenNo = EFLEET_TOKENNO;
    $result = $client->eFleet_GetTripLogDetails($params)->eFleet_GetTripLogDetailsResult;
    $xml = simplexml_load_string($result->any);
    if (DEBUG == 1) {
        echo "<pre>";
    }
    if (isset($xml)) {
        if (DEBUG == 1) {
            $arrTripsAdded = array();
        }
        foreach ($xml->children() as $children) {
            $objTripDetails = GetTripDetailsFromXml($children);
            $objTripManager = new Trips($customerno, $userid);
            if (!$objTripManager->is_triplogno_existing($objTripDetails->triplogno)) {
                $objTripDetails->customerno = $customerno;
                $objTripDetails = GetAdditionalDetails($objTripDetails);
                /* Create Trips object and add trip */
                $objTripManager->add_tripdetails((array) $objTripDetails);
                if (DEBUG == 1) {
                    $arrTripsAdded[] = $objTripDetails->triplogno;
                }
            }
        }
        if (DEBUG == 1) {
            $strTripsAdded = implode("<br/>", $arrTripsAdded);
            echo (count($arrTripsAdded) . " trips added from $fromDate to $toDate: <br />" . $strTripsAdded);
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
    echo "Unknown Exception occured.<br/>";
}
//<editor-fold defaultstate="collapsed" desc="Helper functions">
function GetTripDetailsFromXml($xmlChild) {
    $arrMobileno = array();
    $objTripDetails = new stdClass();
    $objTripDetails->triplogno = (string) $xmlChild->TripNO;
    $objTripDetails->vehicleno = (string) $xmlChild->VehicleNo;
    $objTripDetails->routename = (string) $xmlChild->Route;
    $objTripDetails->from = (string) $xmlChild->From;
    $objTripDetails->to = (string) $xmlChild->To;
    $objTripDetails->loadend = (string) $xmlChild->LodingDate;
    $objTripDetails->loadingdate = (string) $xmlChild->ReportLoadingDate;
    $objTripDetails->budgetedkms = (string) $xmlChild->BudgetedKm;
    $objTripDetails->budgetedhrs = (string) $xmlChild->BudgetHours;
    $objTripDetails->drivername = (string) $xmlChild->DriverName;
    foreach ($xmlChild->DriverMobileNo as $drivermobno) {
        $arrMobileno[] = (string) $drivermobno;
    }
    if (isset($arrMobileno) && count($arrMobileno) > 0) {
        $objTripDetails->drivermobile1 = isset($arrMobileno[0]) ? $arrMobileno[0] : '';
        $objTripDetails->drivermobile2 = isset($arrMobileno[1]) ? $arrMobileno[1] : '';
    }
    $objTripDetails->billingparty = (string) $xmlChild->ClientName;
    return $objTripDetails;
}
function GetAdditionalDetails($objTripDetails) {
    $objVehicleManager = new VehicleManager($objTripDetails->customerno);
    $vehicleDetails = $objVehicleManager->get_all_vehicles_byId(trim($objTripDetails->vehicleno));
    $objTripDetails->vehicleid = isset($vehicleDetails[0]->vehicleid) ? $vehicleDetails[0]->vehicleid : 0;
    //Load end status date time
    $loadenddate = new DateTime($objTripDetails->loadend);
    $objTripDetails->statusdatetime = $loadenddate->format('Y-m-d H:i:s');
    if ($objTripDetails->vehicleid > 0) {
        $objTripDetails->statusodometer = GetLoadingOdometer($objTripDetails->statusdatetime, $objTripDetails->vehicleid, $objTripDetails->customerno);
    }
    else {
        $objTripDetails->statusodometer = '0';
    }
    //Default loadend status for customerno 206 is 3
    $objTripDetails->tripstatus = '3';
    //Set default values as discussed with client
    $objTripDetails->consignor = '';
    $objTripDetails->consignorid = '';
    $objTripDetails->consignee = '';
    $objTripDetails->consigneeid = '';
    $objTripDetails->mintemp = '1';
    $objTripDetails->maxtemp = '50';
    $objTripDetails->remark = '';
    $objTripDetails->perdaykm = '350';
    $objTripDetails->temp_sensors = '2';
    return $objTripDetails;
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
//</editor-fold>
?>
