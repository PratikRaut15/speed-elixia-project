<?php
$uid = GetSafeValueString($_POST["uid"], "string");
$customerno = GetSafeValueString($_POST["customerno"], "string");

$SQL = sprintf('SELECT vehicleid from vehicle where uid=%d and customerno=%d',$uid,$customerno);
$db->executeQuery($SQL);
$row = $db->get_nextRow();
$vehicleid = $row['vehicleid'];

$Query = sprintf('DELETE from unit where uid=%d and customerno=%d',$uid,$customerno);
$db->executeQuery($Query);
$Query = sprintf('DELETE from devices where uid=%d and customerno=%d',$uid,$customerno);
$db->executeQuery($Query);
$Query = sprintf('DELETE from vehicle where uid=%d and customerno=%d',$uid,$customerno);
$db->executeQuery($Query);
$Query = sprintf('DELETE from reportman where uid=%d and customerno=%d',$uid,$customerno);
$db->executeQuery($Query);
$Query = sprintf('DELETE from driver WHERE vehicleid = %d AND customerno = %d',$vehicleid,$customerno);
$db->executeQuery($Query);
$Query = sprintf('DELETE from `fenceman` where vehicleid = %d and customerno = %d',$vehicleid,$customerno);
$db->executeQuery($Query);
$Query = sprintf('DELETE from `checkpointmanage` where vehicleid = %d and customerno = %d',$vehicleid,$customerno);
$db->executeQuery($Query);
$Query = sprintf('DELETE from `ecodeman` where vehicleid = %d and customerno = %d',$vehicleid,$customerno);
$db->executeQuery($Query);
header("Location: modifycustomer.php?cid=$customerno");
?>