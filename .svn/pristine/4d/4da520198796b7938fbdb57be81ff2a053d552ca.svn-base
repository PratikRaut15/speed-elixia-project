<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include_once "../../lib/system/utilities.php";
include_once '../../lib/autoload.php';
//include_once '../../lib/bo/CommunicationQueueManager.php';
include_once 'files/dailyreport.php';
//class VODRM{}
//class VODatacap{}
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
//ini_set('memory_limit', '-1');
$vm = new VehicleManager(0);
$vehicles = $vm->getFueledVehicle();
$Bad = 1;
//$cqm = new CommunicationQueueManager();
//$cvo = new VOCommunicationQueue();
//$email = 'sanketsheth@elixiatech.com';
//$subject = "Error CronDaily Report";

if (isset($vehicles)) {
    foreach ($vehicles as $vehicle) {
        $vehicleid = $vehicle->vehicleid;
        $unitno = $vehicle->unitno;
        $customerno = $vehicle->customerno;
        $fuel_balance = $vehicle->fuel_balance;
        echo "<br/>";
        $average = $vehicle->average;
        $date = date('Y-m-d');
        //$date = date('Y-m-d');
        $date2 = date('Y-m-d', strtotime('-1 day', strtotime($date)));
        $totaldistance = round(getdistance($unitno, $customerno, $date2), 2);
        $consumedfule = round($totaldistance / $average, 2);
        echo "<br/>";
        $balancefuel = round($fuel_balance - $consumedfule, 2);
        echo "<br/>";
        if ($balancefuel < 0) {
            $balancefuel = 0;
        }
        $vm->fuel_update($vehicleid, $balancefuel);
    }
}
function getdistance($unitno, $customerno, $date) {
    $date = date("Y-m-d", strtotime($date));
    $totaldistance = 0;
    $lastodometer = GetOdometer($date, $customerno, $unitno, 'DESC');
    $firstodometer = GetOdometer($date, $customerno, $unitno, 'ASC');
    $totaldistance = $lastodometer - $firstodometer;
    if ($totaldistance != 0) {
        return $totaldistance / 1000;
    }

    return $totaldistance;
}

function GetOdometer($date, $customerno, $unitno, $order) {
    $date = substr($date, 0, 11);
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
    $ODOMETER = 0;
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $query = "SELECT odometer from vehiclehistory ORDER BY vehiclehistory.lastupdated $order LIMIT 0,1";
        $result = $db->query($query);
        foreach ($result as $row) {
            $ODOMETER = $row['odometer'];
        }
    }
    return $ODOMETER;
}

/*
function sendMail( $to, $subject , $content)
{
$subject = $subject;
$headers = "From: noreply@elixiatech.com\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
if (!@mail($to, $subject, $content, $headers)) {
// message sending failed
return false;
}
return true;
}
 *
 */
?>
