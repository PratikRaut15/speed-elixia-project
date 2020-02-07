<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';
echo "<br/> Cron Start On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";

$cronManager = new CronManager();
$objUserManager = new UserManager();

$stoppage_users = $cronManager->get_stoppage_user();
if (isset($stoppage_users)) {
	foreach ($stoppage_users as $thisuser) {
		$objCustomerManager = new CustomerManager($thisuser->customerno);
		$timezone = $objCustomerManager->timezone_name_cron(speedConstants::IST_TIMEZONE, $thisuser->customerno);
		$timezone = isset($timezone) ? $timezone : speedConstants::IST_TIMEZONE;
		date_default_timezone_set('' . $timezone . '');
		$currentDateTime = new DateTime();
		$userGroups = $objUserManager->get_groups_fromuser($thisuser->customerno, $thisuser->userid);
		$arrGroups = array();
		if (isset($userGroups) && !empty($userGroups)) {
			foreach ($userGroups as $group) {
				$arrGroups[] = $group->groupid;
			}
		}

		$vehicles = $cronManager->getStoppageVehiclesByGroup($arrGroups, $thisuser);

		if (isset($vehicles) && !empty($vehicles)) {
			foreach ($vehicles as $vehicle) {

				$cqm = new ComQueueManager();
				$cvo = new VOComQueue();
				$cvo->customerno = $thisuser->customerno;
				$cvo->lat = $vehicle->devicelat;
				$cvo->long = $vehicle->devicelong;
				$cvo->status = 0;
				$cvo->chkid = $vehicle->checkpointId;
				$cvo->vehicleid = $vehicle->vehicleid;
				$cvo->userid = $thisuser->userid;
				$cvo->today = date(speedConstants::DEFAULT_TIMESTAMP);
				$stoppageMinuteDiff = minutediff($vehicle->stoppage_transit_time, $vehicle->lastupdated);

				if ($vehicle->checkpointId != 0 && $vehicle->chkpoint_status == 0 && $stoppageMinuteDiff > $thisuser->chkmins && $thisuser->chkmins > 0) {
					$cvo->message = $vehicle->vehicleno . " is not moving at " . $vehicle->checkpointName . " for more than " . $thisuser->chkmins . " mins";
					$cvo->type = 10;
				} elseif ($vehicle->checkpointId != 0 && $vehicle->chkpoint_status == 1 && $stoppageMinuteDiff > $thisuser->transmins && $thisuser->transmins > 0) {
					$cvo->message = $vehicle->vehicleno . " is not moving for more than " . $thisuser->transmins . " mins";
					$cvo->type = 10;
				}

				if (isset($cvo->message) && $cvo->message != '') {
					$cqm->InsertQChk($cvo);
					$cronManager->update_sent_alert($vehicle->vehicleid, $thisuser->userid, $thisuser->customerno, 1);
				}

			}
		}

	}
}
function minutediff($StartTime, $EndTime) {
	$idleduration = strtotime($EndTime) - strtotime($StartTime);
	$minutes = floor($idleduration / 60);
	return $minutes;
}
echo "<br/> Cron Completed On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";
?>
