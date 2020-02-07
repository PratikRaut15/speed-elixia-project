<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(-1);


include_once '../../lib/bo/DailyReportManager.php';
include_once '../../lib/bo/GeofenceManager.php';
include_once '../../lib/bo/PointLocationManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/UnitManager.php';
//include_once '../../lib/bo/CommunicationQueueManager.php';
include_once 'files/dailyreport.php';

class VODRM {

}

class VODatacap {

}

$drm = new DailyReportManager(0);
$customerno = $_GET['customerno'];
$date = '2016-03-03';
$devices = $drm->GetDevicesForReport_by_limit($customerno);
$Bad = 1;

$email = 'sanketsheth@elixiatech.com';
$subject = "Error CronDaily Report";
set_time_limit(0);

if (isset($devices) && !empty($devices)) {


 $reports = array();
 $ids = array();
 foreach ($devices as $device) {


  if ($Bad != 0) {

   $unitno = $device->unitno;
   $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";

   if (file_exists($location)) {

    $Data = DataFromSqlite($location);
    if ($Data != 0) {
     if (count($Data) > 0) {
      $um = new UnitManager($customerno);
      $acdata = $um->getacinvertval($unitno);
      $acinvertval = $acdata['0'];
      $acsensor = $acdata['1'];
      //echo $unitno.'_'.$customerno.'_'.$acinvertval.'_'.$acsensor;
      $reports[$customerno][] = DailyReport($device, $date, $Data, $device->overspeed_limit, $acinvertval, $acsensor);
     }
    } else {
     $Bad = 0;
    }
   }
  } else {
   break;
  }
 }

//print_r($reports);

 if ($Bad != 0 && !empty($reports)) {
  foreach ($reports as $customer_report) {
   $customerno = $customer_report[0]['customerno'];
   $path = "sqlite:../../customer/$customerno/reports/dailyreport.sqlite";
   if ($Bad != 0) {
    try {
     foreach ($customer_report as $report) {
      $drm->update_mysql_dailyreport($report);
     }
     
    } catch (PDOException $e) {
     $Bad = 0;
    }
   } else {
    $message = "Error while Pushing Data into sqlite for report ids - " . implode(',', $ids);
    //sendMail($email, $subject, $message);
    break;
   }
  }
  

  echo "DailyReport Updated ";
 } else {
  $message = "Error while fetching data from sqlite for reportids - " . implode(',', $ids);
  //sendMail($email, $subject, $message);
 }
}

?>
