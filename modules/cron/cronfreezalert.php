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
// Freeze
$freeze = $cm->getalldevices_freeze();
if (isset($freeze)) {
    $data = '';
    foreach ($freeze as $thisfreeze) {
        $devicelat = $thisfreeze->devicelat;
        $devicelong = $thisfreeze->devicelong;
        $vehicleid = $thisfreeze->vehicleid;
        if ($vehicleid == 2710) {
            echo $cradfreeze = FREEZE_RADIUS; // 40 Meter define in utility page
            $getfreezedata = $cm->getfreezedata($vehicleid);
            echo $distance_freeze = calculate($devicelat, $devicelong, $getfreezedata['freezelat'], $getfreezedata['freezelong']);
            if ((float) $distance_freeze >= (float) $cradfreeze) {
                //unfreeze vehicle if distance beyond 40 meter
                $vehiclemanager = new VehicleManager($thisfreeze->customerno);
                $vehiclemanager->unfreezedvehicle($thisfreeze->unitno, $thisfreeze->customerno, $getfreezedata['userid']);
                // Insert in Queue
                $cqm = new ComQueueManager();
                $cvo = new VOComQueue();
                $cvo->customerno = $thisfreeze->customerno;
                $cvo->lat = $thisfreeze->devicelat;
                $cvo->long = $thisfreeze->devicelong;
                $cvo->message = "Vehicle " . $thisfreeze->vehicleno . " has moved";
                $cvo->type = 17;
                $cvo->status = 1;
                $cvo->chkid = 0;
                $cvo->vehicleid = $thisfreeze->vehicleid;
                $cqm->InsertQChk($cvo);
            }
        }
    }
}
?>