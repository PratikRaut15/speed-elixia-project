<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//include_once '../../lib/bo/DeviceManager.php';
//include_once '../../lib/bo/VehicleManager.php';
//include_once '../../lib/bo/UnitManager.php';
$RELATIVE_PATH_DOTS = "../../";
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
include_once 'files/dailyreport.php';



$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d', strtotime('-1 day'));

$cm = new CustomerManager(2);

$customers = $cm->get_customer_settings();

foreach ($customers as $customer) {
    $vm = new VehicleManager($customer);
    $vehicleArray = $vm->get_all_vehicles();
    foreach ($vehicleArray as $vehicle) {
        $um = new UnitManager($customer);
        $unitno = $um->getunitno($vehicle->vehicleid);
        $location = "../../customer/$customer/unitno/$unitno/sqlite/$date.sqlite";
        if (file_exists($location)) {
            echo '<br>';
            echo $vehicle->vehicleid . '=>' . $date;
            $path = "sqlite:$location";
            $db = new PDO($path);
            $start_lat_long = " SELECT  d1.devicelat as start_lat
                                        ,d1.devicelong as start_long
                                        ,d1.lastupdated
                                FROM    devicehistory d1
                                ORDER BY d1.lastupdated  ASC LIMIT 1";
            $end_lat_long = " SELECT    d2.devicelat as end_lat
                                        ,d2.devicelong as end_long
                                        ,d2.lastupdated
                                FROM    devicehistory d2
                                ORDER BY d2.lastupdated  DESC LIMIT 1;";
            $db->exec('BEGIN IMMEDIATE TRANSACTION');
            $start_result = $db->query($start_lat_long);
            $end_result = $db->query($end_lat_long);
            $db->exec('COMMIT TRANSACTION');
            if ($start_result !== false && $end_result !== false) {

                $report = array();
                $report['date'] = $date;
                $report['vehicleid'] = $vehicle->vehicleid;
                $arrQueryResult = $start_result->fetchAll();
                $endQueryResult = $end_result->fetchAll();

                $gc = new GeoCoder($customer);
                if (!empty($arrQueryResult) && !empty($endQueryResult)) {
                    $report['start_location'] = ($gc->get_location_bylatlong($arrQueryResult[0]['start_lat'], $arrQueryResult[0]['start_long'])) .'('. date('H:i', strtotime($arrQueryResult[0]['lastupdated'])).')';
                    $report['end_location'] = ($gc->get_location_bylatlong($endQueryResult[0]['end_lat'], $endQueryResult[0]['end_long'])) .'('. date('H:i', strtotime($endQueryResult[0]['lastupdated'])).')';
//                    prettyPrint($report);
                    $location1 = "../../customer/$customer/reports/dailyreport.sqlite";
                    if (file_exists($location1)) {
                        $path1 = "sqlite:$location1";
                        $pdo = new PDO($path1);
                        $pdo->exec('BEGIN IMMEDIATE TRANSACTION');
                        TRANSACTION_UPDATE_START_END_LOCATION($report, $pdo, $path1);
                        $pdo->exec('COMMIT TRANSACTION');
                    }

                    unset($arrQueryResult);
                    unset($endQueryResult);
                }
            }
        }
    }
}
?>