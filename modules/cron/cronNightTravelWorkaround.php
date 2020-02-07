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
define("DATEFORMAT_DMY", "dmy");
define("DATEFORMAT_YMD", "Y-m-d");
define("DISTANCE_THRESHHOLD", "500");
ini_set("max_execution_time","300");
$customerno = $_REQUEST['customerno'];
$unitno = $_REQUEST['unitno'];
$date = $_REQUEST['date'];

$today                      	= new DateTime();
$reportId                   	= speedConstants::REPORT_NIGHT_TRAVELLING ;
$objReportUser              	= new stdClass();
$objReportUser->reportId    	= $reportId;
$objReportUser->customerno    	= $customerno;
//$objReportUser->reportTime  	= $today->format('H:00:00');
$objReportUser->reportTime    = '21:00:00';
$objDeviceManager             = new DeviceManager(0);

$nightDriverDetails         = $objDeviceManager->getNightDriveDetails($objReportUser->reportTime,$type='start_time',$customerno);

$customerUserArray          = array();
/*Cron for First Reading*/
if(isset($nightDriverDetails) && !empty($nightDriverDetails)){
	foreach($nightDriverDetails as $key=>$val){
    $customerno                 = $val->customerno;
    $start_time                 = $val->start_time;
    $end_time                   = $val->end_time;
 }  
        $update_arr = array();
        $customerno = $_REQUEST['customerno'];
         // $date       = date('Y-m-d',strtotime('-1 day'));
         //$date       = $date;;
          $drm        = new DailyReportManager(0);
          $data = $drm->get_vehicle_unit_details($unitno,$customerno);

          $uid = $data['uid'];
          $vehicleid = $data['vehicleid'];
          $Data       = $drm->get_daily_report_mysql_night_per_userTime($date, $customerno,$vehicleid,$unitno);
         
        if($Data!= 0)
            $update_arr[$customerno][$vehicleid] = $Data;
        }
        if(!empty($update_arr)){
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
 
/*Cron for last Reading*/

$tablenameDate = substr(str_replace("-","",$date),2);
$date = $date;
$tableName    		 = 'A' . $tablenameDate;

//echo $tableName;die();
if(isset($nightDriverDetails) && !empty($nightDriverDetails)){
  foreach($nightDriverDetails as $key=>$value){
    $start_time  = $value->start_time;
    $end_time    = $value->end_time;
    $customerNo  = $value->customerno;
    $GeoCoder_Obj           = new GeoCoder($customerNo);
      //echo strtotime($tempFormattedDate);
     $isSuccessful = AlterTable($tableName, $customerNo);
      if ($isSuccessful) {
          $arrResult = GetData($customerNo, $date,$start_time,$end_time,$vehicleid,$unitno,$uid);
         
          if (isset($arrResult)) {
            $result = UpdateTable($tableName, $customerNo, $arrResult);
          }
        if($result == 1){
            foreach($arrResult as $key=>$val){
               $start_location   = $GeoCoder_Obj->get_location_bylatlong($val->night_first_lat,$val->night_first_long);
            $end_location       = $GeoCoder_Obj->get_location_bylatlong($val->night_end_lat,$val->night_end_long);
            $distance           = $val->night_distance/1000;
            echo "Start Location : ".$start_location." <br/> End Location : ".$end_location." <br/> Total Distance: ".$distance;
            }
        }
      }
/*      $tempDate->add(new DateInterval('P1D'));
      $tempFormattedDate = $tempDate->format(DATEFORMAT_YMD);*/
  }
}

function AlterTable($tableName, $customerno){
  $isSuccessful = 0;
  try{
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";

    if (file_exists($location)) {
      $path = "sqlite:$location";
      $db   = new PDO($path);
      $db->beginTransaction();
      $alterColumnArray = array('night_first_lat','night_first_long','night_end_lat','night_end_long','night_first_odometer','night_last_odometer');

              foreach($alterColumnArray as $val){
                   if (!IsColumnExistInSqlite($path, $tableName, $val)) {
                    $Query = "ALTER TABLE $tableName ADD COLUMN ".$val." INT DEFAULT NULL";
                    $db->query($Query);
                     $db->exec($Query);
                }
              }
              $db->commit();
              unset($db);
              $db = null;
              $isSuccessful = 1;
      }
    }
  catch (Exception $ex) {
    echo $ex;
    exit;
  }
  return $isSuccessful;
}

function GetData($custno,$date,$startTime,$endTime,$vehicleid,$unitno,$uid){
  $arrResult = array();

  	$currentDay 		 = $date;
 	$datetime 	  		 = new DateTime($date);
	$pDay  		 = $datetime->modify('-1 day');
	$previousDay = $pDay->format(DATEFORMAT_YMD);
//	$previousDay = "-","",$pDay);
	$dacDay = $datetime->modify('+1 day');
	  $isCurrentDaySunday = ($datetime->format('N') == 7) ? 1 : 0;
	$dayAfterCurrentDay  =  $dacDay->format(DATEFORMAT_YMD);
  try {
//Get all the units for the customer
    $dm = new DeviceManager($custno);
    $i = 1;
        $night_firstodometer = 0;
        $night_lastodometer = 0;
        $night_maxodometer = 0;
        $night_firstmaxodometer = 0;
        $night_lastmaxodometer = 0;
        $weekend_lastmaxodometer = 0;
        $weekend_lastodometer = 0;
        $weekend_firstodometer = 0;

        $topspeed_time = '';
        $night_distance = 0;
        $weekend_distance = 0;
        $is_night_drive = 0;
        $is_weekend_drive = 0;
        $vehicleid = $vehicleid;
         $i . ") " . $unitno;
        $uid = $uid;
        //Get data from From Unit Sqlite
        $location    		               = "../../customer/$custno/unitno/$unitno/sqlite/$currentDay.sqlite";
        $previousdaylocation           = "../../customer/$custno/unitno/$unitno/sqlite/$previousDay.sqlite";
        $dayaftercurrentdaylocation    = "../../customer/$custno/unitno/$unitno/sqlite/$dayAfterCurrentDay.sqlite";

        if (file_exists($location)) {
          //<editor-fold defaultstate="collapsed" desc="Current Day's readings">
          $path  = "sqlite:$location";
          $db    = new PDO($path);
          $topspeedtimequery = "SELECT lastupdated FROM vehiclehistory ORDER BY curspeed DESC LIMIT 1;";
          $result = $db->query($topspeedtimequery);
          if ($result !== false){
              $arrQueryResult = $result->fetchAll();
              $topspeed_time = $arrQueryResult[0]['lastupdated'];
          }
        
         $nightlastodometer_query = "SELECT vehiclehistory.odometer,devicehistory.devicelat,devicehistory.devicelong                              FROM vehiclehistory
                                        INNER JOIN devicehistory ON devicehistory.uid = vehiclehistory.uid
                                        WHERE time(vehiclehistory.lastupdated) <= '" . $endTime . "' AND devicelat!='0.000000' AND devicelong !='0.000000' ORDER BY vehiclehistory.lastupdated DESC LIMIT 1;";
                                        echo "<br>";
          $result = $db->query($nightlastodometer_query);
          if ($result !== false) {
             $arrQueryResult = $result->fetchAll();
              $night_lastodometer = $arrQueryResult[0]['odometer'];
              $night_end_lat      = $arrQueryResult[0]['devicelat'];
              $night_end_long     = $arrQueryResult[0]['devicelong'];
          }

          $night_lastmaxodo_query = "SELECT max(odometer) as maxodometer FROM vehiclehistory
                                    WHERE time(lastupdated) <= '" . $endTime . "';";
                                    echo "<br>";
          $result = $db->query($night_lastmaxodo_query);
          if ($result !== false) {
             $arrQueryResult = $result->fetchAll();
             $night_lastmaxodometer = $arrQueryResult[0]['maxodometer'];
          }
          if (file_exists($previousdaylocation)) {
            $path = "sqlite:$previousdaylocation";
            $dbprevday = new PDO($path);
            $nightfirstodo_query = "SELECT vehiclehistory.odometer,devicehistory.devicelat,devicehistory.devicelong                      FROM vehiclehistory
                                    INNER JOIN devicehistory ON devicehistory.uid = vehiclehistory.uid
                                    WHERE time(vehiclehistory.lastupdated) <= '" . $endTime . "'  AND devicelat!='0.000000' AND devicelong !='0.000000' ORDER BY vehiclehistory.lastupdated DESC LIMIT 1;";

            $result = $dbprevday->query($nightfirstodo_query);
            if ($result !== false) {
              $arrQueryResult      = $result->fetchAll();
              $night_firstodometer = $arrQueryResult[0]['odometer'];
              $night_first_lat     = $arrQueryResult[0]['devicelat'];
              $night_first_long    = $arrQueryResult[0]['devicelong'];
            }

           $night_firstmaxodo_query = "SELECT max(odometer) AS maxodometer FROM vehiclehistory WHERE time(lastupdated) >= '" . $startTime . "';";
            $result = $dbprevday->query($night_firstmaxodo_query);
            if ($result !== false) {
              $arrQueryResult = $result->fetchAll();
              $night_firstmaxodometer = $arrQueryResult[0]['maxodometer'];
            }
          }

     //Get max odometer for night
          $night_maxodometer = ($night_lastmaxodometer > $night_firstmaxodometer) ? $night_lastmaxodometer : $night_firstmaxodometer;
          //<editor-fold defaultstate="collapsed" desc="Is Sunday, weekend readings">
          if ($isCurrentDaySunday) {
            $weekend_firstodometer  = $night_firstodometer;
            $weekend_lastodometer   = $night_lastodometer;
            if (file_exists($dayaftercurrentdaylocation)) {
              $path = "sqlite:$dayaftercurrentdaylocation";
              $dbdayaftercurrday = new PDO($path);
            $weekend_lastodo_query = "SELECT odometer FROM vehiclehistory WHERE  time(lastupdated) <= '" . $endTime . "' ORDER BY lastupdated ASC LIMIT 1;";
              $result = $dbdayaftercurrday->query($weekend_lastodo_query);
              if ($result !== false) {
                $arrQueryResult = $result->fetchAll();
                              $weekend_lastodometer = $arrQueryResult[0]['odometer'];
            }
              $weekend_lastmaxodo_query = "SELECT max(odometer) AS maxodometer FROM vehiclehistory WHERE  time(lastupdated) <= '" . $endTime . "';";
              $result = $dbdayaftercurrday->query($weekend_lastmaxodo_query);
              if ($result !== false) {
                $arrQueryResult = $result->fetchAll();
              $weekend_lastmaxodometer = $arrQueryResult[0]['maxodometer'];
            }
            }
          }
                    //Get max odometer for weekend
          $weekend_maxodometer = ($night_maxodometer > $weekend_lastmaxodometer) ? $night_maxodometer : $weekend_lastmaxodometer;

          if ($night_lastodometer < $night_firstodometer) {
            $night_lastodometer = $night_lastodometer + $night_maxodometer - $night_firstodometer;
          }
          if ((($night_lastodometer - $night_firstodometer) > DISTANCE_THRESHHOLD) && ($night_firstodometer > 0 && $night_lastodometer > 0)) {
            $night_distance = $night_lastodometer - $night_firstodometer;
            $is_night_drive = 1;
          }

          if ($weekend_lastodometer < $weekend_firstodometer) {
            $weekend_lastodometer = $weekend_lastodometer + $weekend_maxodometer - $weekend_firstodometer;
          }
          if ((($weekend_lastodometer - $weekend_firstodometer) > DISTANCE_THRESHHOLD) && ($weekend_firstodometer > 0 && $weekend_lastodometer > 0)) {
            $weekend_distance = $weekend_lastodometer - $weekend_firstodometer;
            $is_weekend_drive = 1;
          }
        } else {
          //echo "UNIT FILE NOT FOUND <br/>";
        }

        $objUnitDetail                      = new stdClass();
        $objUnitDetail->uid                 = $uid;
        $objUnitDetail->vehicleid           = $vehicleid;
        $objUnitDetail->topspeed_time       = $topspeed_time;
        $objUnitDetail->night_distance      = $night_distance;
        $objUnitDetail->weekend_distance    = $weekend_distance;
        $objUnitDetail->is_night_drive      = $is_night_drive;
        $objUnitDetail->is_weekend_drive    = $is_weekend_drive;

        $objUnitDetail->night_first_lat    = isset($night_first_lat)?$night_first_lat:0;
        $night_first_lat=0;
        $objUnitDetail->night_first_long   = isset($night_first_long)?$night_first_long:0;
        $night_first_long=0;
        $objUnitDetail->night_last_odometer = isset($night_lastodometer)?$night_lastodometer:0;
        $objUnitDetail->night_end_lat       = isset($night_end_lat)?$night_end_lat:0;
        $night_end_lat=0;
        $objUnitDetail->night_end_long      = isset($night_end_long)?$night_end_long:0;
        $night_end_long=0;
        $objUnitDetail->night_last_odometer = isset($night_lastodometer)?$night_lastodometer:0;
        $objUnitDetail->night_first_odometer = isset($night_firstodometer)?$night_firstodometer:0;
        $arrResult[] = $objUnitDetail;
       
            $i++;
  
  }
  catch (Exception $ex) {
    echo $ex;
    exit;
  }
  return $arrResult;
}

//<editor-fold defaultstate="collapsed" desc="Update Table">
function UpdateTable($tableName, $customerno, $arrResult){
  $isSuccessful = 0;
  $location = "../../customer/$customerno/reports/dailyreport.sqlite";
  try {
    if (file_exists($location)) {
      foreach ($arrResult as $data) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $db->beginTransaction();
        $query = "UPDATE $tableName "
         . "SET topspeed_datetime = '" . $data->topspeed_time . "'"
         . ", night_distance = '" . $data->night_distance . "'"
        . ", night_first_odometer = '" . $data->night_first_odometer . "'"
            . ", night_first_lat = '" . $data->night_first_lat . "'"
         . ", night_first_long = '" . $data->night_first_long . "'"
          . ", night_end_lat = '" . $data->night_end_lat . "'"
         . ", night_end_long = '" . $data->night_end_long . "'"
         . ", night_last_odometer = '" . $data->night_last_odometer . "'"
         . ", weekend_distance = '" . $data->weekend_distance . "'"
         . ", is_night_drive = '" . $data->is_night_drive . "'"
         . ", is_weekend_drive = '" . $data->is_weekend_drive . "'"
         . " WHERE uid = '" . $data->uid . "'"
         . " AND vehicleid = '" . $data->vehicleid . "'";

        $db->exec($query);
       // echo $query;echo "<br/>";
        $db->commit();
        unset($db);
        $db = null;
        $isSuccessful = 1;
      }
    }
  }
  catch (Exception $ex) {
    echo $ex;
  }
  return $isSuccessful;
}
?>