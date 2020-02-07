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
$devrate = GetSafeValueString($_POST["devrate"], "string");
$devamount = GetSafeValueString($_POST["devamount"], "string");
$totalamount = GetSafeValueString($_POST["totalamount"], "string");
$inwords = GetSafeValueString($_POST["inwords"], "string");
$inwords = "Rupees ".$inwords." only"; 

$date = new Date();
$today = $date->add_hours(date("Y-m-d"), 0);

$SQL = sprintf( "INSERT INTO devinvoice (
`devrate` ,`devamount`,`totalamount` ,`inwords`,`teamid`,`customerno`,`dateadded`)
VALUES ('%s', '%s', '%s', '%s', %d, %d, '%s')" ,
$devrate, $devamount, $totalamount, $inwords, $teamid, $customerno, Sanitise::Date($today));
$db->executeQuery($SQL);
$invoiceid = $db->get_insertedId();

foreach($_POST as $single_post_name=>$single_post_value)
{
    if (substr($single_post_name, 0, 8) == "to_imei_")
    {
        $SQL1 = sprintf( "INSERT INTO devinvoicedetails (
        `value` ,`devinvoiceid`)
        VALUES ('%s', %d)" ,
         $single_post_value, $invoiceid);
        $db->executeQuery($SQL1);
        
        $SQLUpdate = sprintf( "UPDATE purchase SET
                `sold`=1
                WHERE imei = '%s'" ,
                    $single_post_value);
        $db->executeQuery($SQLUpdate);        
    }
}

header("Location: ../pdfinvoice.php?invoiceid=$invoiceid");
?>