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

if (IsSales() || IsHead()) {
	$msg = "<P>You are an authorized user</p>";
} else {
	header( "Location: index.php");
	exit;
}

$teamid = GetLoggedInUserId();
// Based on details passed in, Create the customer.
$prosname = GetSafeValueString($_POST["pname"], "string");
$proscompany = GetSafeValueString($_POST["pcompany"], "string");
$prosphone = GetSafeValueString($_POST["pphone"], "string");
$prosemail = GetSafeValueString($_POST["pemail"], "string");
$prossector = GetSafeValueString($_POST["psector"], "string");
$prostarget = GetSafeValueString($_POST["ptarget"], "string");
$prosstatus = GetSafeValueString($_POST["pstatus"], "string");
$prosnextstep = GetSafeValueString($_POST["pnextstep"], "string");
$proscomment = GetSafeValueString($_POST["pcomment"], "string");
$prosref = GetSafeValueString($_POST["ref"], "string");

$prossalesdone = 0;
if(isset($_POST["psalesdone"]) && $_POST["psalesdone"] == "on")
{
    $prossalesdone = 1;
}

$date = new Date();
$today = $date->add_hours(date("Y-m-d"), 0);

$SQL = sprintf( "INSERT INTO prospectives (
`dateadded` ,`name` ,`company` ,`phone` ,`email` ,`sector` ,`target` ,
`teamid` ,`status` ,`nextstep` , 
`sold` , `comment`, referral)
VALUES (
'%s', '%s', '%s', '%s', '%s', '%s', '%s',
'%d', '%s', '%s',
 '%d', '%s','%s')" ,
    Sanitise::Date($today), $prosname, $proscompany, $prosphone, $prosemail, $prossector, $prostarget,
    $teamid, $prosstatus, $prosnextstep,
    $prossalesdone, $proscomment, $prosref);

$db->executeQuery($SQL);

header("Location: customers.php");
?>