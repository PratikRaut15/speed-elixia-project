<?php
/*
Name:           cronignition.php
Schedule:       This cron is scheduled to run every minute
Description:    - It inserts the ignition on and off alerts in comqueue which in turn sends sms and email alerts to registered users.
- It also updates the daily report running time and idle ignition on time every 5 mins.
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
set_time_limit(240);
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
require_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
echo "<br/> Cron Start On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";
$dailyreport = new DailyReportManager(null);
$cm = new CronManager();
$devices = $cm->getalldevicesforignition();
if (isset($devices)) {
    foreach ($devices as $thisdevice) {
        $count = $thisdevice->ignition_count;
        $running_count = $thisdevice->running_count;
        $idleignon_count = $thisdevice->idleignon_count;
        /* Variables for counting current iteration flags */
        $varIgnitionCnt = 0;
        $varRunningCnt = 0;
        $varIdleIgnitionCnt = 0;
        if (strtotime($thisdevice->lastupdated) > strtotime($thisdevice->ignition_last_check)) {
            if ($thisdevice->ignition_last_status == $thisdevice->ignition && $count < 5) {
                $count += 1;
                $varIgnitionCnt = 1;
            } else {
                /* Reset all the counts */
                $count = 1;
                $running_count = 0;
                $idleignon_count = 0;
                $varIgnitionCnt = 1;
                $varRunningCnt = 0;
                $varIdleIgnitionCnt = 0;
                $cm->mark_ignchgtime($thisdevice->lastupdated, $thisdevice->vehicleid, $thisdevice->customerno);
            }
            if ($thisdevice->ignition == 1) {
                if ($thisdevice->prev_odometer_reading == $thisdevice->odometer) {
                    /* If odometer has not changed then vehicle is idle */
                    $idleignon_count += 1;
                    $varIdleIgnitionCnt = 1;
                } else {
                    /* If odometer has changed then vehicle is running */
                    $running_count += 1;
                    $varRunningCnt = 1;
                }
            }
            $cm->markignitionstatus($count, $idleignon_count, $running_count, $thisdevice->ignition, $thisdevice->vehicleid, $thisdevice->customerno);
        }
        $cm->changelastigcheck($thisdevice->lastupdated, $thisdevice->vehicleid, $thisdevice->customerno);
        /* Check if Ignition is on or off for 5 mins */
        if ($thisdevice->ignition == 1 && $count == 5) {
            if ($thisdevice->ignition_email_status == 0) {
                // Populate communication queue
                $cqm = new ComQueueManager();
                $cvo = new VOComQueue();
                $cvo->customerno = $thisdevice->customerno;
                $cvo->lat = $thisdevice->devicelat;
                $cvo->long = $thisdevice->devicelong;
                if ($thisdevice->ignchgtime != "0000-00-00 00:00:00") {
                    $cvo->message = $thisdevice->vehicleno . " turned Ignition On at " . convertDateToFormat($thisdevice->ignchgtime, speedConstants::DEFAULT_TIME);
                } else {
                    $cvo->message = $thisdevice->vehicleno . " turned Ignition On";
                }
                echo $cvo->message;
                $cvo->type = 4;
                $cvo->timeadded = $thisdevice->lastupdated;
                $cvo->status = 1;
                $cvo->vehicleid = $thisdevice->vehicleid;
                $cqm->InsertQ_ign($cvo);
                $cm->markignitionon($thisdevice->vehicleid, $thisdevice->customerno, $thisdevice->ignchgtime);
            }
        } else if ($thisdevice->ignition == 0 && $count == 5 && $thisdevice->ignition_email_status == 1) {
            // Populate communication queue
            $cqm = new ComQueueManager();
            $cvo = new VOComQueue();
            $cvo->customerno = $thisdevice->customerno;
            $cvo->lat = $thisdevice->devicelat;
            $cvo->long = $thisdevice->devicelong;
            if ($thisdevice->ignchgtime != "0000-00-00 00:00:00") {
                $cvo->message = $thisdevice->vehicleno . " turned Ignition Off at " . convertDateToFormat($thisdevice->ignchgtime, speedConstants::DEFAULT_TIME);
            } else {
                $cvo->message = $thisdevice->vehicleno . " turned Ignition Off";
            }
            echo $cvo->message;
            $cvo->type = 4;
            $cvo->timeadded = $thisdevice->lastupdated;
            $cvo->status = 0;
            $cvo->vehicleid = $thisdevice->vehicleid;
            $cqm->InsertQ_ign($cvo);
            $cm->markignitionoff($thisdevice->vehicleid, $thisdevice->customerno);
        }
        if ($varIdleIgnitionCnt > 0) {
            $dailyreport->incre_idleignition_time($thisdevice->vehicleid, $thisdevice->customerno, $varIdleIgnitionCnt, $thisdevice->isfreeze);
        }
        if ($varRunningCnt > 0) {
            $dailyreport->incre_running_time($thisdevice->vehicleid, $thisdevice->customerno, $varRunningCnt, $thisdevice->isfreeze);
        }
        /* Update time even if vehicle is idle with ignition on or off so that next time when vehicle runs, correct prev odometer  and time are captured */
        $dailyreport->update_previous_odometer($thisdevice->vehicleid, $thisdevice->customerno, $thisdevice->odometer, $thisdevice->lastupdated);
    }
}
echo "<br/> Cron Completed On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";
?>