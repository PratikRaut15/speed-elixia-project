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
$customerno = GetSafeValueString($_POST["customerno"], "string");
$pendingamount = GetSafeValueString($_POST["pndgamt"], "string");
$softamount = GetSafeValueString($_POST["softamount"], "string");
$licdiscount = GetSafeValueString($_POST["licdiscount"], "string");
$licsubtotal = GetSafeValueString($_POST["licsubtotal"], "string");
$servicetax = GetSafeValueString($_POST["servicetax"], "string");
$totalamount = GetSafeValueString($_POST["dtotalamount"], "string");
$inwords = GetSafeValueString($_POST["dinwords"], "string");
$month = GetSafeValueString($_POST["selmonth"], "string");
$year = GetSafeValueString($_POST["selyear"], "string");
$startdate = $year."-".$month."-01";
$inwords = "Rupees ".$inwords." only"; 

$date = new Date();
$today = $date->add_hours(date("Y-m-d"), 0);

$SQL = sprintf( "INSERT INTO licinvoice (
`teamid` ,`customerno`,`dateadded` ,`startdate`,`softamount`,`servicetax`,`totalamount`,`inwords`,`discount`,`subtotal`,`pendingamount`)
VALUES (%d, %d, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')" ,
$teamid, $customerno, Sanitise::Date($today), $startdate, $softamount, $servicetax, $totalamount, $inwords, $licdiscount, $licsubtotal, $pendingamount);
$db->executeQuery($SQL);
$licinvoiceid = $db->get_insertedId();

foreach($_POST as $single_post_name=>$single_post_value)
{
    if (substr($single_post_name, 0, 13) == "to_devicekey_")
    {
        $SQL1 = sprintf( "INSERT INTO licinvoicedet (
        `value` ,`licinvoiceid`)
        VALUES ('%s', %d)" ,
         $single_post_value, $licinvoiceid);
        $db->executeQuery($SQL1);        
    }
}

header("Location: ../pdflicinvoice.php?invoiceid=$licinvoiceid");
?>