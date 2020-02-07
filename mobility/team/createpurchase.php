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

if (IsSourcing() || IsHead()) {
	$msg = "<P>You are an authorized user</p>";
} else {
	header( "Location: index.php");
	exit;
}

$teamid = GetLoggedInUserId();
// Based on details passed in, Create the dealer.
$imei = GetSafeValueString($_POST["imei"], "string");
$model = GetSafeValueString($_POST["model"], "string");
$dealerid = GetSafeValueString($_POST["dealerid"], "string");
$cost = GetSafeValueString($_POST["cost"], "string");
$color = GetSafeValueString($_POST["color"], "string");
$details = GetSafeValueString($_POST["details"], "string");
$sold=0;

$date = new Date();
$today = $date->add_hours(date("Y-m-d"), 0);

$SQL = sprintf( "INSERT INTO purchase (
`dateadded` ,`dealerid` ,`sold` ,`imei` ,`teamid` ,`model`,`cost`,`color`,`details`)
VALUES ('%s', '%d', '%d', '%s', '%d', '%s', '%d','%s', '%s')" ,
    Sanitise::Date($today), $dealerid, $sold, $imei, $teamid, $model, $cost, $color, $details);

$db->executeQuery($SQL);

header("Location: purchase.php");
?>