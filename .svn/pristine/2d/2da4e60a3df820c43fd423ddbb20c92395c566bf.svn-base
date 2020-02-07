<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
set_time_limit(0);
$RELATIVE_PATH_DOTS = "../../";
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
if (!defined('DS')) {
    define('DS', "/");
}

$cm = new CronManager(2);

$result = $cm->insertAlertTempUserMapping();
prettyPrint($result);
$result1 = $cm->insertTempSensorSpecificAlert();
prettyPrint($result1);

?>
