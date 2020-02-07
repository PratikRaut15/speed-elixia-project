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
$receiptid = GetSafeValueString(isset($_GET["rid"])? $_GET["rid"]:$_POST["rid"], "long");

$sql = sprintf("UPDATE `receipt`
                SET `approval` = 1                          
                WHERE receiptid=%d LIMIT 1",$receiptid);
$db->executeQuery($sql);            

$SQL = sprintf("SELECT customerno from receipt 
where receiptid = '%d' LIMIT 1 ",$receiptid);
$db->executeQuery($SQL);
while($row = $db->get_nextRow())
{
    $customerid = $row["customerno"];
}


$db->executeQuery($SQL);

header("Location: checkpayment.php?cid=$customerid");
?>