<?php
$vehiclereps = array();
$counter = 0;
$vehiclename;
$total = 0;
$start = reset($reports);
$st = $start->odometer;
$fuel = $start->fuel_balance;
if(!isset($fuel)){
    $fuel = 0;
}
foreach($reports as $report)
{
    $sqlitedate = date("Y-m-d H:i:s",strtotime($report->lastupdated));
    list($date, $time) = explode(' ', $sqlitedate);
    list($hour, $min, $sec) = explode(':', $time);
    $date1 = explode('-', $date);
    $date1[1] = (int)$date1[1]-1;
    $date1[2] = (int)$date1[2];
    $vehiclereps[$counter][0] = "new Date($date1[0], $date1[1], $date1[2],$hour, $min, $sec)";
    $id = $report->vehicleid;
    $current= $report->odometer; 
    $fuelconsume = getFuel($id,$sqlitedate,$st,$current,$fuel,$fuelque);
    $vehiclereps[$counter][1] = $fuelconsume[0];
    $vehiclename = getVehicleName($id);
    $st= $report->odometer;     
    $fuel =  $fuelconsume[0];
    $fuelque= $fuelque."-".$fuelconsume[1];
    $counter += 1;
}
?>