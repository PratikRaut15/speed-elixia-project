<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$RELATIVE_PATH_DOTS = "../../../../";
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
require_once $RELATIVE_PATH_DOTS . 'modules/trips/class/TripsManager.php';
echo "<br/> Cron Start On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";
define("DATEFORMAT_YMD", "Y-m-d");
define("EFLEET_URL", "http://tms.rkfoodland.in/RkfFoodlandTriplogData/service.asmx?WSDL");
define("EFLEET_TOKENNO", "12");
define("INTERVAL", "4");
define("DEBUG", "1");
$customerno = 328;
$userid = 5506;
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
                //prettyPrint($objTripDetails);die();
                /* Create Trips object and add trip */
                if ($objTripDetails->vehicleid > 0) {
                    $objTripManager->add_tripdetails((array) $objTripDetails);
                } else {
                    echo PHP_EOL . "Unable to find vehicle details for " . $objTripDetails->vehicleno;
                    prettyPrint($objTripDetails);
                }
                if (DEBUG == 1) {
                    $arrTripsAdded[] = $objTripDetails->triplogno;
                }
            }
        }
        if (DEBUG == 1) {
            $strTripsAdded = implode("<br/>", $arrTripsAdded);
            echo PHP_EOL . (count($arrTripsAdded) . " trips found from $fromDate to $toDate: <br />" . $strTripsAdded);
        }
    }
} catch (SoapFault $e) {
    //echo "<pre>SoapFault: " . print_r($e, true) . "</pre>\n";
    echo "Soap Exception occured.<br/>";
    echo "Faultcode: " . $e->faultcode . "<br/>";
    echo "Faultstring: " . $e->getMessage() . "";
} catch (Exception $e) {
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
    $objTripDetails->vehicleno = ParseVehicleNo($objTripDetails->vehicleno);
    return $objTripDetails;
}

function ParseVehicleNo($vehicleno) {
    $parsedVehicleNo = $vehicleno;
    if ($vehicleno != "") {
        try {
            $vehicleNoBeforeHyphen = trim(substr($vehicleno, 0, strpos($vehicleno, "-")));
            $arrVehicleNo = explode(" ", $vehicleNoBeforeHyphen);
            /*
             * vehicle number format in array:
             * 0 position - 4 digit vehicle number
             * Rest of vehicle number would be coming 1, 2 and 3 position in same sequence as required by ELIXIA
             * 1 postion would have state initials followed by number and so on
             */
            $last4digits = $arrVehicleNo[0];
            array_splice($arrVehicleNo, 0, 1);
            $vehicleNoWithoutLast4Digits = implode("", $arrVehicleNo);
            $parsedVehicleNo = $vehicleNoWithoutLast4Digits . $last4digits;
        } catch (Exception $ex) {
            echo $vehicleno . " - " . $ex;
            $parsedVehicleNo = $vehicleno;
        }
    }
    return $parsedVehicleNo;
}

function GetAdditionalDetails($objTripDetails) {
    $objVehicleManager = new VehicleManager($objTripDetails->customerno);
    $vehicleDetails = $objVehicleManager->get_vehicle_by_vehno($objTripDetails->vehicleno, 1);
    $objTripDetails->vehicleid = isset($vehicleDetails[0]->vehicleid) ? $vehicleDetails[0]->vehicleid : 0;
    //Load end status date time
    $loadenddate = new DateTime($objTripDetails->loadend);
    $objTripDetails->statusdatetime = $loadenddate->format('Y-m-d H:i:s');
    if ($objTripDetails->vehicleid > 0) {
        $objTripDetails->statusodometer = GetLoadingOdometer($objTripDetails->statusdatetime, $objTripDetails->vehicleid, $objTripDetails->customerno);
    } else {
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
