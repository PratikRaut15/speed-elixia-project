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
// Based on details passed in, Create the customer.
$type = GetSafeValueString($_POST["type"], "string");
$customerno = GetSafeValueString($_POST["customerno"], "string");
$name = GetSafeValueString($_POST["name"], "string");
$company = GetSafeValueString($_POST["company"], "string");
$phone = GetSafeValueString($_POST["phone"], "string");
$email = GetSafeValueString($_POST["email"], "string");
$contactedvia = GetSafeValueString($_POST["contactedvia"], "string");
$reason = GetSafeValueString($_POST["reason"], "string");
$allottedto = GetSafeValueString($_POST["allottedto"], "string");
$rescomments = GetSafeValueString($_POST["rescomments"], "string");

$resolved = 0;
if(isset($_POST["resolved"]) && $_POST["resolved"] == "on")
{
    $resolved = 1;
}
$resolvedby = GetSafeValueString($_POST["resolvedby"], "string");

$bug = 0;
if(isset($_POST["bug"]) && $_POST["bug"] == "on")
{
    $bug = 1;
}
$bugid = GetSafeValueString($_POST["bugid"], "string");


$date = new Date();
$today = $date->add_hours(date("Y-m-d"), 0);

$SQL = sprintf( "INSERT INTO helpdesk (
`dateadded` ,`type` ,`customerno` ,`name` ,`company` ,`phone` ,`email` ,
`contactvia` ,`reason` ,`resolved` ,`resolvedby` ,`allottedto` ,`bug` ,
`bugid` , `teamid`, rescomments)
VALUES (
'%s', '%s', '%d', '%s', '%s', '%s', '%s',
'%s', '%s', '%d','%s', '%s', '%d',
 '%d', '%d','%s')" ,
Sanitise::Date($today), $type, $customerno, $name, $company, $phone, $email,
$contactedvia, $reason, $resolved, $resolvedby, $allottedto, $bug,
$bugid, $teamid, $rescomments);

$db->executeQuery($SQL);

header("Location: helpdesk.php");
?>