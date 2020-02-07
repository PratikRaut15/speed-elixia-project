<?php

error_reporting(0);
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');

include_once '../../lib/system/DatabaseManager.php';

class CronPendingInvoice {
    
}

$db = new DatabaseManager();
$today = date("Y-m-d");
//$today = "2015-12-25";

$SQL = "SELECT vehicle.vehicleno, unit.unitno, unit.uid,devices.deviceid, unit.customerno,devices.inv_device_priority,devices.inv_deferdate FROM vehicle 
            INNER JOIN devices ON devices.uid = vehicle.uid 
            INNER JOIN unit ON devices.uid = unit.uid 
            INNER JOIN ".DB_PARENT.".customer ON customer.customerno = unit.customerno
            WHERE devices.inv_device_priority = 0 AND vehicle.isdeleted= 0 AND devices.device_invoiceno='' AND unit.customerno NOT IN (-1,1) AND unit.trans_statusid NOT IN(23,22,10) AND unit.onlease = 0 AND devices.inv_deferdate <> ''";
$db->executeQuery($SQL);

if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        if ($row['inv_deferdate'] == $today) {
            $SQL1 = sprintf("UPDATE devices SET inv_device_priority = 1 WHERE deviceid = %d AND uid = %d", $row['deviceid'], $row['uid']);
            $db->executeQuery($SQL1);
        } else {
            continue;
        }
    }
}