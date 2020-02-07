<?php
require_once "../../lib/system/utilities.php";
require_once '../../lib/bo/DeviceManager.php';
require_once '../../lib/bo/ComQueueManager.php';
require_once '../../lib/bo/CronManager.php';
require_once '../../lib/bo/VehicleManager.php';
require_once '../../lib/bo/DailyReportManager.php';
require_once '../../lib/bo/PointLocationManager.php';
require_once 'files/calculatedist.php';
require_once 'files/push_sqlite.php';
$cm = new CronManager();
//Checkpoint
class VODataCron {
}
$smsstart = date('Y-m-d') . " 04:00:00";
$smsend = date('Y-m-d') . " 14:00:00";
$curtime = date('Y-m-d H:i:s');
//fence
$fences = $cm->getalldeviceswithgeofencesforcrons();
$dailyreport = new DailyReportManager(null);
if (isset($fences)) {
    foreach ($fences as $fence) {
        //echo 'Fence'.$fence->fenceid.'<br>';
        $polygon = array();
        $pointLocation = new PointLocation();
        $points = array($fence->devicelat . " " . $fence->devicelong);
        $conflictstatus = $fence->conflictstatus;
        $geofence = $cm->get_geofence_from_fenceid($fence->fenceid);
        if (isset($geofence)) {
            foreach ($geofence as $thisgeofence) {
                $polygon[] = $thisgeofence->geolat . " " . $thisgeofence->geolong;
            }
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
//                    $gcms = $cm->getusergcm_fromcustomers($fence->customerno);
                    //                    if (isset($gcms)) {
                    //                        foreach ($gcms as $thisgcm) {
                    //                            $actual = $fence->vehicleno . " was out of " . $fence->fencename;
                    //                            $message = "scream";
                    //                            $registatoin_ids = array($thisgcm->gcmid);
                    //                            $message = array("price" => $message, "actual" => $actual);
                    //                            $result = send_notification($registatoin_ids, $message);
                    //                        }
                    //                    }
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
?>