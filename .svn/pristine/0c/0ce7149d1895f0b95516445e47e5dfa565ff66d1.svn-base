<?php

include_once("session.php");
include_once("../db.php");
include_once("../lib/system/utilities.php");
include("loginorelse.php");
include_once("../constants/constants.php");
include_once("../lib/system/DatabaseManager.php");
include_once("../lib/system/Date.php");
include_once("../lib/system/Sanitise.php");
$db = new DatabaseManager();

$teamid = GetLoggedInUserId();
// Based on details passed in, Create the invoice.
$customerno = GetSafeValueString($_POST["customerid"], "string");

$receiptno = GetSafeValueString($_POST["receiptno"], "string");
$receiptdate = GetSafeValueString($_POST["receiptdate"], "string");
$amountpaid = GetSafeValueString($_POST["amountpaid"], "string");
$type = GetSafeValueString($_POST["type"], "string");

$date = new Date();
$today = $date->add_hours(date("Y-m-d"), 0);

$SQL = sprintf( "INSERT INTO receipt (
`receiptno` ,`receiptdate`,`dateadded` ,`teamid`,`customerno`,`amount`,`approval`,`type`)
VALUES (%d, '%s', '%s', %d, %d, '%f', 0, '%s')" ,
$receiptno, $receiptdate, Sanitise::Date($today), $teamid, $customerno, $amountpaid, $type);
$db->executeQuery($SQL);

header("Location: checkpayment.php?cid=$customerno");
?>