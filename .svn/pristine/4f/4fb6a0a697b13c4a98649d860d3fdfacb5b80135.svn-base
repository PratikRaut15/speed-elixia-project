<?php
//Error- Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '256M');
$RELATIVE_PATH_DOTS = "../../";
require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';
require_once 'files/calculatedist.php';
require_once 'files/push_sqlite.php';
$cm = new CronManager();
$cust = new CustomerManager();
$smsStatus = new SmsStatus();
$cqm = new ComQueueManager();
$cvo = new VOComQueue();
$smsstart = date('Y-m-d') . " 00:00:00";
$smsend = date('Y-m-d') . " 23:59:00";
$curtime = date('Y-m-d H:i:s');
$moduleid = speedConstants::MODULE_VTS;
$customerExceptionList = null;
$route_dashboard_customer = explode(",", speedConstants::ROUTE_DASHBOARD_CUSTNO);
$arrCustomers = array("63");
$userId = 213;
$customerList = implode(",", $arrCustomers);
$chkpts = $cm->getalldeviceswithchkpforcron($customerExceptionList, $customerList);
if (isset($chkpts)) {
	echo count($chkpts);
	foreach ($chkpts as $thischkpt) {
		$devicelat = $thischkpt->devicelat;
		$devicelong = $thischkpt->devicelong;
		$cgeolat = $thischkpt->cgeolat;
		$cgeolong = $thischkpt->cgeolong;
		$crad = (float) $thischkpt->crad;
		$distance = calculate($devicelat, $devicelong, $cgeolat, $cgeolong);
		$currentSequence = 0;
		if ($distance >= $crad && $thischkpt->conflictstatus == 0) {
			$cvo->customerno = $thischkpt->customerno;
			$cvo->lat = $thischkpt->devicelat;
			$cvo->long = $thischkpt->devicelong;
			$cvo->message = $thischkpt->vehicleno . " left " . $thischkpt->cname;
			$cvo->type = 2;
			$cvo->status = 0;
			$cvo->vehicleid = $thischkpt->vehicleid;
			$cvo->chkid = $thischkpt->checkpointid;
			$cvo->lastupdated = $thischkpt->lastupdated;
			$isExistsRecord = $cqm->checkComQueExistance($cvo);
			if (!$isExistsRecord) {
				ChkSqlite($thischkpt->customerno, $thischkpt->checkpointid, 1, $thischkpt->lastupdated, $thischkpt->vehicleid, $thischkpt->chktype);
				vehicleChkSqlite($thischkpt->customerno, $thischkpt->checkpointid, 1, $thischkpt->lastupdated, $thischkpt->vehicleid, $thischkpt->chktype);
				$cm->markoutsidechk($thischkpt->cmid, $thischkpt->customerno, $thischkpt->lastupdated);
				$cm->markVehicleCheckpointOut($cvo);
				$cqm->InsertQChk($cvo);
			}
		} elseif ($distance < $crad && $thischkpt->conflictstatus == 1) {
			$cvo->customerno = $thischkpt->customerno;
			$cvo->lat = $thischkpt->devicelat;
			$cvo->long = $thischkpt->devicelong;
			$cvo->message = $thischkpt->vehicleno . " entered " . $thischkpt->cname;
			$cvo->type = 2;
			$cvo->status = 1;
			$cvo->vehicleid = $thischkpt->vehicleid;
			$cvo->chkid = $thischkpt->checkpointid;
			$cvo->lastupdated = $thischkpt->lastupdated;
			$isExistsRecord = $cqm->checkComQueExistance($cvo);
			if (!$isExistsRecord) {
				ChkSqlite($thischkpt->customerno, $thischkpt->checkpointid, 0, $thischkpt->lastupdated, $thischkpt->vehicleid, $thischkpt->chktype);
				vehicleChkSqlite($thischkpt->customerno, $thischkpt->checkpointid, 0, $thischkpt->lastupdated, $thischkpt->vehicleid, $thischkpt->chktype);
				$cm->markinsidechk($thischkpt->cmid, $thischkpt->customerno, $thischkpt->lastupdated);
				$cm->markVehicleCheckpointIn($cvo);
				$cqm->InsertQChk($cvo);
			}
		}
	}
}
?>