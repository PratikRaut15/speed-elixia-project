<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';

$cm = new CronManager();

$stoppage_users = $cm->get_stoppage_user_veh_details();

if (isset($stoppage_users)) {
    foreach ($stoppage_users as $thisuser) {
        $stoppageMinuteDiff = minutediff($thisuser->stoppage_transit_time, $thisuser->lastupdated);
        $chkpts = $cm->get_chkptdet($thisuser->vehicleid, $thisuser->customerno);
        $inCheckpoint = 0;
        $inTransit = 1;
        $checkpointName = '';
        if (isset($chkpts) && !empty($chkpts)) {
            foreach ($chkpts as $checkpoint) {
                if ($checkpoint->conflictstatus == 0) {
                    $inCheckpoint = 1;
                    $inTransit = 0;
                    $checkpointName = $checkpoint->cname;
                    break;
                }
            }
        }
        if ((isset($inCheckpoint) && $inCheckpoint == 1) && ($thisuser->is_chk_sms == 1 || $thisuser->is_chk_email == 1)) {
            // Vehicle in checkpoint
            if ($stoppageMinuteDiff > $thisuser->chkmins && $thisuser->chkmins > 0) {
                $cqm = new ComQueueManager();
                $cvo = new VOComQueue();
                $cvo->customerno = $thisuser->customerno;
                $cvo->lat = $thisuser->devicelat;
                $cvo->long = $thisuser->devicelong;
                $cvo->message = $thisuser->vehicleno . " is not moving at " . $checkpointName . " for more than " . $thisuser->chkmins . " mins";
                $cvo->type = 9;
                $cvo->status = 0;
                $cvo->chkid = 0;
                $cvo->vehicleid = $thisuser->vehicleid;
                $cvo->userid = $thisuser->userid;
                $cqm->InsertQChk($cvo);

                $cm->update_sent_alert($thisuser->vehicleid, $thisuser->userid, $thisuser->customerno, 1);

            }
        }
        if ((isset($inTransit) && $inTransit == 1)  && ($thisuser->is_trans_sms == 1 || $thisuser->is_trans_email == 1)) {
            if ($stoppageMinuteDiff > $thisuser->transmins && $thisuser->transmins > 0) {
                // Send Alert
                $cqm = new ComQueueManager();
                $cvo = new VOComQueue();
                $cvo->customerno = $thisuser->customerno;
                $cvo->lat = $thisuser->devicelat;
                $cvo->long = $thisuser->devicelong;
                $cvo->message = $thisuser->vehicleno . " is not moving for more than " . $thisuser->transmins . " mins";
                $cvo->type = 10;
                $cvo->chkid = 0;
                $cvo->status = 0;
                $cvo->vehicleid = $thisuser->vehicleid;
                $cvo->userid = $thisuser->userid;
                $cqm->InsertQChk($cvo);

                $cm->update_sent_alert($thisuser->vehicleid, $thisuser->userid, $thisuser->customerno, 1);
            }
        }

    }
}

function minutediff($StartTime, $EndTime) {
    $idleduration = strtotime($EndTime) - strtotime($StartTime);
    $minutes = floor($idleduration / 60);
    return $minutes;
}

?>
