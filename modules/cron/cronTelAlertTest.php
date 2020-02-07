<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$RELATIVE_PATH_DOTS = "../../";
require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';

$objTelAlertDetails = new stdClass();
//$objTelAlertDetails->flowId = '234988';
$objTelAlertDetails->phoneNo = "9421377403";
$objTelAlertDetails->customerno = "64";
$objTelAlertDetails->telAlertLogId = "1";
$objTelAlertDetails->cqId = "199211676";
$objTelAlertDetails->customMessage = "Hi Shrikant";

sendTelAlertUtil($objTelAlertDetails);