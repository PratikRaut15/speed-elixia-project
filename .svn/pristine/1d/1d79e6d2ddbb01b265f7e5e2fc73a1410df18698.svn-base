<?php
//Error- Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
/* ini_set('memory_limit', '256M'); */
ini_set('memory_limit', '-1');
$RELATIVE_PATH_DOTS = "../../";
require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';
require_once 'files/calculatedist.php';
require_once 'files/push_sqlite.php';
$cm = new CronManager();
$cust = new CustomerManager();
$curtime = date('Y-m-d H:i:s');
$moduleid = speedConstants::MODULE_VTS;

$customerno = 132;
$objRouteManager = new RouteManager($customerno);
$routeDetails = $objRouteManager->getRouteSequenceDetails();

//print_r($routeDetails);
if (isset($routeDetails) && !empty($routeDetails)) {
	$recordsToBeUpdated = array();
	foreach ($routeDetails as $key => $data) {
		$oldRecord = 0;
		foreach ($data as $row) {
			$objUpdatePair = new stdClass();
			if ($row['sequence'] == 1 && $oldRecord == 0) {
				$oldRecord = $row;
				prettyPrint($oldRecord);
				continue;
			} else {
				$objUpdatePair->rmid = $row['rmid'];
				$objUpdatePair->routeid = $row['routeid'];
				$objUpdatePair->originlat = $oldRecord['lat'];
				$objUpdatePair->originlng = $oldRecord['lng'];
				$objUpdatePair->destinationlat = $row['lat'];
				$objUpdatePair->destinationlng = $row['lng'];
				$objUpdatePair->distance = -1;
				$objUpdatePair->timeInMin = -1;

				$recordsToBeUpdated[] = $objUpdatePair;
				$oldRecord = $row;
			}

		}
	}
	prettyPrint($recordsToBeUpdated);
}

?>
