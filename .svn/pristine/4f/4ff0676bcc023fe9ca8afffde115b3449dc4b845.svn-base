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
$MDLZ_CUST_LIST = speedConstants::MDLZ_CUST_LIST;
$curtime = date('Y-m-d H:i:s');
$request = new stdClass();
$request->customerList = $MDLZ_CUST_LIST;
$request->todaysdate = $curtime;

$cm->getVehiclesforMondelezDump($request);
?>