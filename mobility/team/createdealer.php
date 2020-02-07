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
$name = GetSafeValueString($_POST["name"], "string");
$company = GetSafeValueString($_POST["company"], "string");
$phone = GetSafeValueString($_POST["phone"], "string");
$email = GetSafeValueString($_POST["email"], "string");
$add1 = GetSafeValueString($_POST["add1"], "string");
$add2 = GetSafeValueString($_POST["add2"], "string");
$city = GetSafeValueString($_POST["city"], "string");
$state = GetSafeValueString($_POST["state"], "string");
$zip = GetSafeValueString($_POST["zip"], "string");

$date = new Date();
$today = $date->add_hours(date("Y-m-d"), 0);

$SQL = sprintf( "INSERT INTO dealer (
`dateadded` ,`name` ,`company` ,`phone` ,`email` ,`add1` ,`add2` ,
`teamid` ,`city` ,`state` ,
`zip` )
VALUES (
'%s', '%s', '%s', '%s', '%s', '%s', '%s',
'%d', '%s', '%s',
 '%s')" ,
    Sanitise::Date($today), $name, $company, $phone, $email, $add1, $add2,
    $teamid, $city, $state,
    $zip);

$db->executeQuery($SQL);

header("Location: purchase.php");
?>