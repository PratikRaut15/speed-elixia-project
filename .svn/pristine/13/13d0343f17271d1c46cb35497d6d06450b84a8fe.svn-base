<?php
if (!isset($_SESSION)) {
    session_start();
}
include 'busrouteFunctions.php';
$customerno = 118;
$mapOrder = 1;
$dateI = isset($_REQUEST['mapDate']) ? $_REQUEST['mapDate'] : date('Y-m-d');
$mm = new MappingManager($customerno);
$allZones = getZones();
$allSlots = getSlots();
$am = new AlgoManager($customerno, $dateI, $allZones, $allSlots);
$am->runBusRouteAlgorithmByDistance();
//$am->busStopAssigningByDistance();