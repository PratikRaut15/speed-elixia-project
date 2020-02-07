<?php
//Error- Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '256M');
$RELATIVE_PATH_DOTS = "../../";
require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';
require_once 'files/calculatedist.php';
require_once 'files/push_sqlite.php';

echo "<br/> Cron Start On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";

$cm = new CronManager();
$cust = new CustomerManager();
$smsStatus = new SmsStatus();
$smsstart = date('Y-m-d') . " 04:00:00";
$smsend = date('Y-m-d') . " 14:00:00";
$curtime = date('Y-m-d H:i:s');
$moduleid = speedConstants::MODULE_VTS;

//fence
$fences = $cm->getalldeviceswithgeofencesforcrons();
$dailyreport = new DailyReportManager(null);
if (isset($fences)) {
    foreach ($fences as $fence) {
        //echo 'Fence'.$fence->fenceid.'<br>';
        $polygon = array();
        $pointLocation = new PointLocation($fence->customerno);
        $points = array($fence->devicelat . " " . $fence->devicelong);
        $conflictstatus = $fence->conflictstatus;
        //$geofence = $cm->get_geofence_from_fenceid($fence->fenceid);
       /*  if (isset($geofence)) { */
          if(isset($fence->polygonLatLongJson)){

          /*   foreach ($geofence as $thisgeofence) {
                $polygon[] = $thisgeofence->geolat . " " . $thisgeofence->geolong;
            } */
            
            foreach(json_decode($fence->polygonLatLongJson,true) AS $key=>$value)
            {
               // echo"<br>lat is: ".$value['cgeolat']." AND long is: ".$value['cgeolong'];
               $polygon[] = $value['cgeolat'] . " " . $value['cgeolong'];
            }
           /*  echo"Polygon data : <pre>";
            print_r($polygon);
            exit(); */
            foreach ($points as $point) {
                if ($pointLocation->checkPointStatus($point, $polygon) == "outside" && $conflictstatus == 0) {
                    // Insert in Queue
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $fence->customerno;
                    $cvo->lat = $fence->devicelat;
                    $cvo->long = $fence->devicelong;
                    $cvo->message = $fence->vehicleno . " was out of " . $fence->fencename;
                    $cvo->type = 3;
                    $cvo->status = 0;
                    $cvo->vehicleid = $fence->vehicleid;
                    $cvo->fenceid = $fence->fenceid;
                    $dailyreport->incre_fenceconflict($fence->vehicleid, $fence->customerno);
                    $cqm->InsertQFence($cvo);
                    $cm->markoutsidefence($fence->fenceid, $fence->vehicleid, $fence->customerno);
                } elseif ($pointLocation->checkPointStatus($point, $polygon) == "inside" && $conflictstatus == 1) {
                    // Insert in Queue
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $fence->customerno;
                    $cvo->lat = $fence->devicelat;
                    $cvo->long = $fence->devicelong;
                    $cvo->message = $fence->vehicleno . " was in " . $fence->fencename;
                    $cvo->type = 3;
                    $cvo->status = 1;
                    $cvo->vehicleid = $fence->vehicleid;
                    $cvo->fenceid = $fence->fenceid;
                    $cqm->InsertQFence($cvo);
                    $cm->markinsidefence($fence->fenceid, $fence->vehicleid, $fence->customerno);
                }
            }
        }
    }
}

function location($lat, $long, $customerno) {
    $address = "";
    try {
        $GeoCoder_Obj = new GeoCoder($customerno);
        $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
    } catch (Exception $ex) {
        throw $ex;
    }
    return $address;
}

echo "<br/> Cron Completed On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";
?>
