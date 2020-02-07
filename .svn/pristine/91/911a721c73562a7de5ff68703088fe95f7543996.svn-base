<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

include_once '../../lib/bo/DailyReportManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/UnitManager.php';
include_once 'files/dailyreport.php';

// Hardcoded Customer No and Unit No
$customerno = 20;
$unitno = '9800';

$location = "../../customer/$customerno/unitno/$unitno/sqlite/2016-12-17.sqlite";
$Data = CorrectOdometer($location);

if ($Data != 0) {
    if (count($Data) > 0) {
       $success = Odometer_Correction_Calculation($Data, $location);
       $Data2 = CorrectOdometer($location);
       $success2 = Odometer_Correction_Calculation2($Data2, $location);
       echo ("Correction 1: ".$success."<br/>");
       echo ("Correction 2: ".$success2);
    }
}

?>