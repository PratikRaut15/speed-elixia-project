<?php

include_once '../../lib/bo/DailyReportManager.php';
include_once '../../lib/bo/GeofenceManager.php';
include_once '../../lib/bo/PointLocationManager.php';
include_once 'files/dailyreport.php';
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);

$today                      = new DateTime();
$reportId                   = speedConstants::REPORT_NIGHT_TRAVELLING ;
$objReportUser              = new stdClass();
$objReportUser->reportId    = $reportId;
$objReportUser->reportTime  = $today->format('H:00:00');
//$objReportUser->reportTime  = '21:00:00';
$objDeviceManager           = new DeviceManager(0);
$nightDriverDetails         = $objDeviceManager->getNightDriveDetails($objReportUser->reportTime,$type='start_time');
$customerUserArray          = array();

if(isset($nightDriverDetails) && !empty($nightDriverDetails)){
  foreach($nightDriverDetails as $key=>$val){
    $customerno                 = $val->customerno;
    $start_time                 = $val->start_time;
    $objReportUser->reportTime  = $start_time;
    $objReportUser->customerno  = $val->customerno;
    $objUserManager             = new UserManager();
    $users[]                    = $objUserManager->getUsersForReportPerCustomer($objReportUser);
  }
}

if(isset($users) && !empty($users)){
  foreach($users as $userskey=>$usersval){
    $customerUserArray[]        = cronCustomerUsers($usersval);
  }
}
else{
  echo "No Data Found";exit;
}

if (isset($customerUserArray) && !empty($customerUserArray)){
  foreach ($customerUserArray as $customerarr => $customerDetailedArr) {
      if(isset($customerDetailedArr) && !empty($customerDetailedArr)){
      foreach($customerDetailedArr as $customer=>$customerDetails){
    $drm      = new DailyReportManager(0);
    $devices  = $drm->pulldevices_dailyreport_night_perCustomer($customer);
echo "<pre>";
      print_r($devices);
     echo "<br/>";
      if (isset($devices)) {
        $reports      = array();
        $update_arr   = array();
        $ids          = array();
        foreach($devices as $device) {
          $customerno = $device->customerno;
          $vehicleid  = $device->vehicleid;
          $date       = date('Y-m-d',strtotime('-1 day'));
          $Data       = $drm->get_daily_report_mysql_night_per_userTime($date, $customerno, $vehicleid);
        if($Data!= 0)
            $update_arr[$customerno][$vehicleid] = $Data;
        }
      }
        echo "<pre>";
      print_r($update_arr);
     echo "<br/>";exit;
        if(!empty($update_arr)) {
          try{
              $drm->set_daily_report_mysql_night_travel($update_arr, $date);
              echo "Successfull";
            }
          catch (PDOException $e){
            $Bad = 0;
          }
        }
        else{
           echo "No Data to update";
        }
      }
    }
  }
}