<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}

require $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require $RELATIVE_PATH_DOTS . "lib/autoload.php";

$customerno = 563;
$chm = new CheckpointManager($customerno);
$vm = new VehicleManager($customerno);
$location = "../../customer/$customerno/reports/chkreport.sqlite";

$vehiclelist = $vm->get_all_vehicles();
$path = "sqlite:$location";
$db = new PDO($path);
foreach ($vehiclelist as $vehicle) {
    $db->exec('BEGIN IMMEDIATE TRANSACTION');
    $vehicleid = 'V' . $vehicle->vehicleid;
    if (!IsColumnExistInSqlite($path, $vehicleid, 'chktype')) {
        $Query = "ALTER TABLE $vehicleid ADD COLUMN `chktype` INT DEFAULT NULL";
        $db->query($Query);
    }
    $Query1 = "SELECT `chkrepid`,`chkid` FROM `$vehicleid` WHERE `chktype` IS NULL;";

    $result = $db->query($Query1);
    if (isset($result) && $result != "") {
        foreach ($result as $row_Start) {
            $chktypeId = $chm->getCheckpointTypebyId($row_Start['chkid']);
            $chkid = $row_Start['chkid'];
            $Query2 = "UPDATE $vehicleid SET chktype = $chktypeId WHERE chkid = $chkid";
            $db->query($Query2);
        }
    }
    $db->query('COMMIT TRANSACTION');
    echo '<br>Success :' . $vehicleid;
}
?>