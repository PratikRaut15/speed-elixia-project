<?php
error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
include_once '../../lib/bo/DeviceManager.php';
//
//<editor-fold defaultstate="collapsed" desc="Constants and initializations">
define("DATEFORMAT_DMY", "dmy");
define("DATEFORMAT_YMD", "Y-m-d");
define("NIGHT_STARTTIME", "21:00:00");
define("NIGHT_ENDTIME", "05:00:00");
//If vehicle crosses 500 meter threshold, then we conclude it is moving
define("DISTANCE_THRESHHOLD", "500");

$customernos = array(64);
if (isset($_REQUEST['date']) && !empty($_REQUEST['date'])) {
  $startDate = date('Y-m-d', strtotime($_REQUEST['date']));
  $endDate = date('Y-m-d', strtotime($_REQUEST['date']));
}
if (!isset($startDate) && !isset($endDate) || ($startDate == '1970-01-01' && $endDate == '1970-01-01')) {
  echo "Please Enter Proper Date";
  exit;
}


$dtStart = new DateTime($startDate);
$dtEnd = new DateTime($endDate);
/* Execute the previous day report as dailyreport is not generated for today. */
$dtStart->sub(new DateInterval('P1D'));
$dtEnd->sub(new DateInterval('P1D'));

$tempDate = $dtStart;
$tempFormattedDate = $dtStart->format(DATEFORMAT_YMD);
$formattedEndDate = $dtEnd->format(DATEFORMAT_YMD);


//</editor-fold>
//
//<editor-fold defaultstate="collapsed" desc="Cron Body">

foreach ($customernos as $custno) {
  //echo strtotime($tempFormattedDate);
  //echo strtotime($formattedEndDate);
  while (strtotime($tempFormattedDate) <= strtotime($formattedEndDate)) {
    //Initialize the dateparam to be passed to GetData function so that we don't affect the value of $tempdate
    $dateParam = new DateTime($tempFormattedDate);
    $tableName = 'A' . $dateParam->format(DATEFORMAT_DMY);
    echo $tableName . "<br>";
    $isSuccessful = AlterTable($tableName, $custno);
    if ($isSuccessful) {
      $arrResult = GetData($custno, $dateParam);
      if (isset($arrResult)) {
        UpdateTable($tableName, $custno, $arrResult);
      }
    }
    $tempDate->add(new DateInterval('P1D'));
    $tempFormattedDate = $tempDate->format(DATEFORMAT_YMD);
  }
}

//</editor-fold>
//
//<editor-fold defaultstate="collapsed" desc="Cron Helper Sections">
//
//<editor-fold defaultstate="collapsed" desc="Alter Table">
function AlterTable($tableName, $customerno) {
  $isSuccessful = 0;
  try {
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if (file_exists($location)) {
      $path = "sqlite:$location";
      $db = new PDO($path);
      $db->beginTransaction();
      $query = "ALTER TABLE  $tableName ADD COLUMN topspeed_datetime TEXT DEFAULT ''";
      $db->exec($query);
      $query = "ALTER TABLE  $tableName ADD COLUMN night_distance FLOAT DEFAULT 0";
      $db->exec($query);
      $query = "ALTER TABLE  $tableName ADD COLUMN weekend_distance FLOAT DEFAULT 0";
      $db->exec($query);
      $query = "ALTER TABLE  $tableName ADD COLUMN is_night_drive INTEGER DEFAULT 0";
      $db->exec($query);
      $query = "ALTER TABLE  $tableName ADD COLUMN is_weekend_drive INTEGER DEFAULT 0";
      $db->exec($query);
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

//</editor-fold>
//
//<editor-fold defaultstate="collapsed" desc="GetData">
function GetData($custno, $dateParam) {
  $arrResult = array();
//<editor-fold defaultstate="collapsed" desc="Initializations">
  $currentDay = $dateParam->format(DATEFORMAT_YMD);
  $isCurrentDaySunday = ($dateParam->format('N') == 7) ? 1 : 0;
  $dateParam->sub(new DateInterval('P1D'));
  $previousDay = $dateParam->format(DATEFORMAT_YMD);
  $dateParam->add(new DateInterval('P2D'));
  $dayAfterCurrentDay = $dateParam->format(DATEFORMAT_YMD);
  //</editor-fold>
//
//<editor-fold defaultstate="collapsed" desc="Method Body">
  try {
//Get all the units for the customer
    $dm = new DeviceManager($custno);
    $devices = $dm->getlastupdateddatefordevices($custno);
    if (isset($devices)) {
      $i = 1;
      foreach ($devices as $device) {
        $weekend_firstodometer = 0;
        $weekend_lastodometer = 0;
        $weekend_maxodometer = 0;
        $weekend_lastmaxodometer = 0;

        $night_firstodometer = 0;
        $night_lastodometer = 0;
        $night_maxodometer = 0;
        $night_firstmaxodometer = 0;
        $night_lastmaxodometer = 0;

        $topspeed_time = '';
        $night_distance = 0;
        $weekend_distance = 0;
        $is_night_drive = 0;
        $is_weekend_drive = 0;
        $vehicleid = $device->vehicleid;
        echo $i . ") " . $unitno = $device->unitno;
        echo "<br/>";
        $uid = $device->uid;
        //Get data from From Unit Sqlite
        $location = "../../customer/$custno/unitno/$unitno/sqlite/$currentDay.sqlite";
        $previousdaylocation = "../../customer/$custno/unitno/$unitno/sqlite/$previousDay.sqlite";
        $dayaftercurrentdaylocation = "../../customer/$custno/unitno/$unitno/sqlite/$dayAfterCurrentDay.sqlite";
        if (file_exists($location)) {
          //
          //<editor-fold defaultstate="collapsed" desc="Current Day's readings">
          $path = "sqlite:$location";
          $db = new PDO($path);
          $topspeedtimequery = "SELECT lastupdated FROM vehiclehistory ORDER BY curspeed DESC LIMIT 1;";
          $result = $db->query($topspeedtimequery);
          if ($result !== false) {
              $arrQueryResult = $result->fetchAll();
              $topspeed_time = $arrQueryResult[0]['lastupdated'];
              //echo $topspeed_time;
              //echo "<br>";

              unset($arrQueryResult);
          }


          $nightlastodometer_query = "SELECT odometer FROM vehiclehistory WHERE time(lastupdated) <= '" . NIGHT_ENDTIME . "' ORDER BY lastupdated DESC LIMIT 1;";
          $result = $db->query($nightlastodometer_query);
          if ($result !== false) {
             $arrQueryResult = $result->fetchAll();
          $night_lastodometer = $arrQueryResult[0]['odometer'];
//                    echo $night_lastodometer;
//                    echo "<br>";
          unset($arrQueryResult);
          }

          $night_lastmaxodo_query = "SELECT max(odometer) as maxodometer FROM vehiclehistory WHERE time(lastupdated) <= '" . NIGHT_ENDTIME . "';";
          $result = $db->query($night_lastmaxodo_query);
          if ($result !== false) {
             $arrQueryResult = $result->fetchAll();
          $night_lastmaxodometer = $arrQueryResult[0]['maxodometer'];
//                    echo $night_lastmaxodometer;
//                    echo "<br>";
          unset($arrQueryResult);
          }

          unset($db);
          //</editor-fold>
          //
     //<editor-fold defaultstate="collapsed" desc="Get Previous Day's Readings">
          if (file_exists($previousdaylocation)) {
            $path = "sqlite:$previousdaylocation";
            $dbprevday = new PDO($path);
            $nightfirstodo_query = "SELECT odometer FROM vehiclehistory WHERE time(lastupdated) >= '" . NIGHT_STARTTIME . "' ORDER BY lastupdated ASC LIMIT 1;";
            $result = $dbprevday->query($nightfirstodo_query);
            if ($result !== false) {
              $arrQueryResult = $result->fetchAll();
            $night_firstodometer = $arrQueryResult[0]['odometer'];
//                        echo $night_firstodometer;
//                        echo "<br>";
            unset($arrQueryResult);
            }

            $night_firstmaxodo_query = "SELECT max(odometer) AS maxodometer FROM vehiclehistory WHERE time(lastupdated) >= '" . NIGHT_STARTTIME . "';";
            $result = $dbprevday->query($night_firstmaxodo_query);
            if ($result !== false) {
              $arrQueryResult = $result->fetchAll();
            $night_firstmaxodometer = $arrQueryResult[0]['maxodometer'];
//                        echo $night_lastodometer;
//                        echo "<br>";
            unset($arrQueryResult);
            }

            unset($dbprevday);
          }
          //</editor-fold>
          //
     //Get max odometer for night
          $night_maxodometer = ($night_lastmaxodometer > $night_firstmaxodometer) ? $night_lastmaxodometer : $night_firstmaxodometer;
          //
          //<editor-fold defaultstate="collapsed" desc="Is Sunday, weekend readings">
          if ($isCurrentDaySunday) {
            $weekend_firstodometer = $night_firstodometer;
            $weekend_lastodometer = $night_lastodometer;
            if (file_exists($dayaftercurrentdaylocation)) {
              $path = "sqlite:$dayaftercurrentdaylocation";
              $dbdayaftercurrday = new PDO($path);
              $weekend_lastodo_query = "SELECT odometer FROM vehiclehistory WHERE  time(lastupdated) <= '" . NIGHT_ENDTIME . "' ORDER BY lastupdated ASC LIMIT 1;";
              $result = $dbdayaftercurrday->query($weekend_lastodo_query);
              if ($result !== false) {
                $arrQueryResult = $result->fetchAll();
              $weekend_lastodometer = $arrQueryResult[0]['odometer'];
              unset($arrQueryResult);
            }

              $weekend_lastmaxodo_query = "SELECT max(odometer) AS maxodometer FROM vehiclehistory WHERE  time(lastupdated) <= '" . NIGHT_ENDTIME . "';";
              $result = $dbdayaftercurrday->query($weekend_lastmaxodo_query);
              if ($result !== false) {
                $arrQueryResult = $result->fetchAll();
              $weekend_lastmaxodometer = $arrQueryResult[0]['maxodometer'];
              unset($arrQueryResult);
            }

              unset($dbdayaftercurrday);
            }
          }
          //</editor-fold >
          //
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
          echo "UNIT FILE NOT FOUND <br/>";
        }

        $objUnitDetail = new stdClass();
        $objUnitDetail->uid = $uid;
        $objUnitDetail->vehicleid = $vehicleid;
        $objUnitDetail->topspeed_time = $topspeed_time;
        $objUnitDetail->night_distance = $night_distance;
        $objUnitDetail->weekend_distance = $weekend_distance;
        $objUnitDetail->is_night_drive = $is_night_drive;
        $objUnitDetail->is_weekend_drive = $is_weekend_drive;

        $arrResult[] = $objUnitDetail;
        $i++;
      }
    }
  }
  catch (Exception $ex) {
    echo $ex;
    exit;
  }
//</editor-fold>
  return $arrResult;
}

//</editor-fold>
//
//<editor-fold defaultstate="collapsed" desc="Update Table">
function UpdateTable($tableName, $customerno, $arrResult) {
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
         . ", weekend_distance = '" . $data->weekend_distance . "'"
         . ", is_night_drive = '" . $data->is_night_drive . "'"
         . ", is_weekend_drive = '" . $data->is_weekend_drive . "'"
         . " WHERE uid = '" . $data->uid . "'"
         . " AND vehicleid = '" . $data->vehicleid . "'"
        ;
        $db->exec($query);
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

//</editor-fold>
//
//</editor-fold>
//
?>

