<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$RELATIVE_PATH_DOTS = "../../";
require $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require $RELATIVE_PATH_DOTS . "lib/comman_function/reports_func.php";
require $RELATIVE_PATH_DOTS . 'lib/autoload.php';

$objCronManager = new CronManager();

$arrFenceDevices = $objCronManager->getRouteFenceDevices();

if (isset($arrFenceDevices)) {
    foreach ($arrFenceDevices as $device) {
        $objGeoFenceManager = new GeofenceManager($device->customerno);
        $objComQueManager = new ComQueueManager();
        $objDailyReportManager = new DailyReportManager($device->customerno);
        $objComQue = new stdClass();
        $objComQue->customerno = $device->customerno;
        $objComQue->lat = $device->devicelat;
        $objComQue->long = $device->devicelong;
        $objComQue->type = 3;
        $objComQue->vehicleid = $device->vehicleid;
        $objComQue->fenceid = $device->fenceid;
        $status = $objGeoFenceManager->getRouteFenceConflictStatus($device->devicelat, $device->devicelong, $device->fenceid);
        if (isset($status) && $status == 1 && $device->conflictstatus == 1) {
            $objComQue->message = $device->vehicleno . " was in " . $device->fencename;
            $objComQue->status = 1;
            $objComQueManager->InsertQFence($objComQue);
            $objCronManager->markRouteFenceIn($device->fenceid, $device->vehicleid, $device->customerno);
        } elseif (isset($status) && $status == 0 && $device->conflictstatus == 0) {
            $objComQue->message = $device->vehicleno . " was out of " . $device->fencename;
            $objComQue->status = 0;
            $objDailyReportManager->incre_fenceconflict($device->vehicleid, $device->customerno);
            $objComQueManager->InsertQFence($objComQue);
            $objCronManager->markRouteFenceOut($device->fenceid, $device->vehicleid, $device->customerno);
        }
    }
}
