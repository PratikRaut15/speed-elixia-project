<?php

error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');

/*for testing*/
$testing = false;
$max_box = 13;
$skip_box = 0;
/**/

include_once 'pickup_functions.php';
include_once '../../lib/bo/AlgoManager.php';
        
$customerno = exit_issetor($_SESSION['customerno']);
$mapOrder = exit_issetor($_REQUEST['mapOrders']);
$dateI = isset($_REQUEST['mapDate']) ? $_REQUEST['mapDate'] : date('Y-m-d');


$mm = new MappingManager($customerno);
$allZones = $mm->getCustomerZones_pickup();
$allSlots = get_slots();


$am = new AlgoManager($customerno, $dateI, $allZones, $allSlots);
$am->testing_mode($testing,$max_box,$skip_box);

$am->run_route_algo_pickup();

    
?>
