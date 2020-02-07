<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$RELATIVE_PATH_DOTS = "../../";
require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';
require_once 'files/calculatedist.php';
require_once 'files/push_sqlite.php';
echo "<br/> Cron Start On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";
$objCronMgr = new CronManager();
$currentTimeStamp = date('Y-m-d H:i:s');
$STdate = date("Y-m-d");
//$startTime = $STdate . " 00:00:00";
$startTime = date('Y-m-d 00:00:00', strtotime("-1 days"));
$endTime = $STdate . " 23:59:59";
$interval = 5;
$threshhold = 50;
$moduleid = speedConstants::MODULE_VTS;
$customerExceptionList = null;
//Get array of customers who has route alert on
$arrCustomers = array("328");
$userId = 5506;
$customerList = implode(",", $arrCustomers);
$chkpts = $objCronMgr->getalldeviceswithchkpforcron($customerExceptionList, $customerList);
if (isset($chkpts)) {
    foreach ($chkpts as $thischkpt) {
        try {
            $objComQueue = new ComQueueManager();
            $objVOComQueue = new VOComQueue();
            $objCustomer = new CustomerManager();
            $objRouteMgr = new RouteManager($thischkpt->customerno);
            $objVOComQueue->customerno = $thischkpt->customerno;
            $objVOComQueue->lat = $thischkpt->devicelat;
            $objVOComQueue->long = $thischkpt->devicelong;
            $objVOComQueue->type = 2;
            $objVOComQueue->vehicleid = $thischkpt->vehicleid;
            $objVOComQueue->chkid = $thischkpt->checkpointid;
            $objVOComQueue->lastupdated = $thischkpt->lastupdated;
            $crad = (float) $thischkpt->crad;
            $path = "sqlite:../../customer/" . $thischkpt->customerno . "/reports/chkreport.sqlite";
            $distance = calculate($thischkpt->devicelat, $thischkpt->devicelong, $thischkpt->cgeolat, $thischkpt->cgeolong);
            /* Get All Checkpoint Details Of Route */
            $currentSequence = 0;
            $currentRouteid = 0;
            $currentRouteMaxSequence = 0;
            $checkpointInfo = $objCronMgr->getVehicleCheckpointRoute($thischkpt->checkpointid, $thischkpt->customerno, $thischkpt->vehicleid);
            if (isset($checkpointInfo)) {
                $currentSequence = $checkpointInfo[0]->sequence;
                $currentRouteid = $checkpointInfo[0]->routeid;
                $arrRouteChk = $objRouteMgr->getAllChkptForRoute($currentRouteid);
                if (isset($arrRouteChk) && !empty($arrRouteChk)) {
                    $currentRouteMaxSequence = count($arrRouteChk);
                }
            }
            if ($thischkpt->conflictstatus == 0) {
                if ($distance >= $crad) {
                    $objVOComQueue->message = $thischkpt->vehicleno . " left " . $thischkpt->cname;
                    $objVOComQueue->status = 0;
                    $objComQueue->InsertQChk($objVOComQueue);
                    $objCronMgr->markoutsidechk($thischkpt->cmid, $thischkpt->customerno, $thischkpt->lastupdated);
                    ChkSqlite($thischkpt->customerno, $thischkpt->checkpointid, 1, $thischkpt->lastupdated, $thischkpt->vehicleid);
                    $objCronMgr->markVehicleCheckpointOut($objVOComQueue);
                    /* Send Alert to next checkpoint */
                    $result = $objCronMgr->checkpointupdate($path, $startTime, $endTime, $thischkpt->vehicleid, $thischkpt->checkpointid);
                    if (isset($result) && $result != "") {
                        foreach ($result as $row) {
                            if (isset($row["outstatus"]) && isset($row["instatus"]) && round((strtotime($row["outdate"]) - strtotime($row["indate"])) / 60, 2) > $interval) {
                                $datarow = $objCronMgr->checkintoroute($thischkpt->checkpointid, $thischkpt->customerno, $thischkpt->vehicleid);
                                $isGoogleDistance = 1;
                                if (isset($datarow) && !empty($datarow)) {
                                    foreach ($datarow as $data) {
                                        $smsId = 0;
                                        $emailId = 0;
                                        $message = "";
                                        $status = "";
                                        $realTimeETA = "NA";
                                        /*
                                        ETA, status should not be calculated when vehicle is leaving the first checkpoint of route
                                        Alert all Mumbai checkpoints if vehicle has left DC or  starting point of route
                                         */
                                        if ($currentSequence == 1) {
                                            $message = "Vehicle[" . $thischkpt->vehicleno . "] has left from " . $thischkpt->cname . " at " . convertDateToFormat($thischkpt->lastupdated, speedConstants::DEFAULT_TIME);
                                        } elseif ($currentSequence != 0) {
                                            $distance = calculate($data->cgeolat, $data->cgeolong, $thischkpt->cgeolat, $thischkpt->cgeolong, $isGoogleDistance, $thischkpt->customerno);
                                            if (isset($distance['min']) && $distance['min'] != -1) {
                                                //Get current date in temp variable
                                                $tempETA = new DateTime();
                                                //Replace time part
                                                $standardETA = $tempETA->format(speedConstants::DATE_Ymd) . " " . $data->eta;
                                                //Create new date with above timestamp
                                                $standardETADateTime = new DateTime();
                                                $standardETADateTime->setTimestamp(strtotime($standardETA));
                                                //Get current date
                                                $currentDateTime = new DateTime();
                                                //Add google driving mode minutes between 2 places
                                                $currentDateTime->modify("+" . $distance['min'] . " minutes");
                                                $realTimeETA = $currentDateTime->format(speedConstants::TIME_hia);
                                                //Get the difference between in minutes and decide status accordingly.
                                                $interval = $standardETADateTime->diff($currentDateTime);
                                                if ($interval->invert == 0) {
                                                    $minutes = $interval->days * 24 * 60;
                                                    $minutes += $interval->h * 60;
                                                    $minutes += $interval->i;
                                                    //GRACE Period of 30 mins
                                                    if ($minutes <= 30) {
                                                        $status = "On Time";
                                                    } else {
                                                        $status = "Delayed";
                                                        $etaStatus = "Delayed by " . $minutes . " minutes";
                                                        $objCronMgr->updateRouteEtaStatus($data->rmid, $etaStatus, $thischkpt->customerno);
                                                    }
                                                } else {
                                                    $status = "Early";
                                                }
                                            }
                                            $message = "Vehicle[" . $thischkpt->vehicleno . "] has left previous store [" . $thischkpt->cname . "] at "
                                            . convertDateToFormat($thischkpt->lastupdated, speedConstants::DEFAULT_TIME) . PHP_EOL
                                            . "ETA: " . convertDateToFormat($data->eta, speedConstants::TIME_hia) . PHP_EOL
                                                . "STA: " . $realTimeETA . PHP_EOL
                                                . "Status: " . $status;
                                        }
                                        if ($message != '') {
                                            if ($data->phoneno != '' && $data->isSms == 1) {
                                                $smsId = sendCheckpointSms($thischkpt, $data, $message, $currentTimeStamp);
                                            }
                                            if ($data->email != '' && $data->isEmail == 1) {
                                                $emailId = sendCheckpointEmail($thischkpt, $data, $message, $currentTimeStamp);
                                            }
                                            /* Store Log */
                                            if ((isset($smsId) && $smsId != 0) || (isset($emailId) && $emailId != 0)) {
                                                insertCheckpointOwnerLog($smsId, $emailId, $thischkpt, $currentTimeStamp);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    /* Logic to alert user in case the vehicle is in the same checkpoint for more than 50 mins */
                    /* Check the difference between current time and inTime */
                    $etaDateTime = date('Y-m-d') . " " . $thischkpt->eta;
                    if (strtotime($thischkpt->inTime) < strtotime($etaDateTime)) {
                        $timeDiff = round((strtotime(date('Y-m-d H:i:s')) - strtotime($etaDateTime)) / 60, 2);
                    } else {
                        $timeDiff = round((strtotime(date('Y-m-d H:i:s')) - strtotime($thischkpt->inTime)) / 60, 2);
                    }
                    /*
                    If difference is greater than threshhold and isDelayExpected = 0,
                    send SMS and emails to all checkpoints
                     */
                    if ($timeDiff > $threshhold && $thischkpt->isDelayExpected == 0) {
                        if ($currentSequence != 1) {
                            $routeDetails = $objCronMgr->getVehicleRouteDetails($thischkpt->vehicleid, $thischkpt->customerno);
                            if (isset($routeDetails) && !empty($routeDetails)) {
                                /* Set isExpectedDelay to 1 */
                                $objCronMgr->markCheckpointDelay($thischkpt->cmid, $thischkpt->customerno);
                                $arrTransaction = array();
                                foreach ($routeDetails as $detail) {
                                    if ($detail->sequence <= $currentSequence) {
                                        continue;
                                    }
                                    $arrTransaction[] = $detail;
                                }
                                if (empty($arrTransaction)) {
                                    $arrTransaction = $routeDetails[0];
                                }
                                /* Send SMS and Email to all the checkpoints in the route */
                                $delayMinutes = (round($timeDiff) - 45);
                                $message = "Vehicle[" . $thischkpt->vehicleno . "] unloading in progress and delayed by " . $delayMinutes . " min at  [" . $thischkpt->cname . "]. STA to be updated post vehicle exits the store." . PHP_EOL;
                                foreach ($arrTransaction as $transaction) {
                                    $smsId = 0;
                                    $emailId = 0;
                                    if ($transaction->phoneno != '' && $transaction->isSms == 1) {
                                        $smsId = sendCheckpointSms($thischkpt, $transaction, $message, $currentTimeStamp);
                                    }
                                    if ($transaction->email != '' && $transaction->isEmail == 1) {
                                        $emailId = sendCheckpointEmail($thischkpt, $transaction, $message, $currentTimeStamp);
                                    }
                                    /* Store Log */
                                    if ((isset($smsId) && $smsId != 0) || (isset($emailId) && $emailId != 0)) {
                                        insertCheckpointOwnerLog($smsId, $emailId, $thischkpt, $currentTimeStamp);
                                    }
                                }
                            }
                        }
                    }
                }
            } elseif ($distance < $crad && $thischkpt->conflictstatus == 1) {
                $objVOComQueue->message = $thischkpt->vehicleno . " entered " . $thischkpt->cname;
                $objVOComQueue->status = 1;
                $objComQueue->InsertQChk($objVOComQueue);
                $objCronMgr->markinsidechk($thischkpt->cmid, $thischkpt->customerno, $thischkpt->lastupdated);
                ChkSqlite($thischkpt->customerno, $thischkpt->checkpointid, 0, $thischkpt->lastupdated, $thischkpt->vehicleid);
                $objCronMgr->markVehicleCheckpointIn($objVOComQueue);
                /* Don't alert to next checkpoint if vehicle has entered the 1st checkpoint or  starting point of route */
                if ($currentSequence > 1) {
                    $datarow = $objCronMgr->checkintoroute($thischkpt->checkpointid, $thischkpt->customerno, $thischkpt->vehicleid);
                    $isGoogleDistance = 1;
                    if (isset($datarow)) {
                        foreach ($datarow as $data) {
                            $smsId = 0;
                            $emailId = 0;
                            $message = "Vehicle[" . $thischkpt->vehicleno . "] has entered previous store [" . $thischkpt->cname . "] at "
                            . convertDateToFormat($thischkpt->lastupdated, speedConstants::DEFAULT_TIME) . PHP_EOL
                            . "ETA: " . convertDateToFormat($thischkpt->eta, speedConstants::TIME_hia);
                            if ($data->phoneno != '' && $data->isSms == 1) {
                                $smsId = sendCheckpointSms($thischkpt, $data, $message, $currentTimeStamp);
                            }
                            if ($data->email != '' && $data->isEmail == 1) {
                                $emailId = sendCheckpointEmail($thischkpt, $data, $message, $currentTimeStamp);
                            }
                            /* Store Log */
                            if ((isset($smsId) && $smsId != 0) || (isset($emailId) && $emailId != 0)) {
                                insertCheckpointOwnerLog($smsId, $emailId, $thischkpt, $currentTimeStamp);
                            }
                        }
                    }
                } elseif ($currentRouteid != 0) {
                    /* Set isExpectedDelay to 0 For Route Assigned To Vehicle */
                    $objCronMgr->unsetCheckpointDelay($thischkpt->customerno, $thischkpt->vehicleid, $currentRouteid);
                }
                /*
                De-map the vehicle from the current route once it enters in last checkpoint
                 */
                if ($currentRouteMaxSequence == $currentSequence && $currentRouteid != 0 && $currentRouteMaxSequence != 0) {
                    $objRouteMgr->demapVehicleRouteMapping($currentRouteid, $thischkpt->vehicleid, $thischkpt->customerno, $userId);
                }
            }
        } catch (Exception $ex) {
            $objLog = new Log();
            $objLog->createlog($thischkpt->customerno, $ex, 0, 1, 'CronRouteAlert-RKFoodLand');
        }
    }
}
function insertCheckpointOwnerLog($smsId, $emailId, $thischkpt, $curtime) {
    $smsId = isset($smsId) ? $smsId : 0;
    $emailId = isset($emailId) ? $emailId : 0;
    $objCustomer = new CustomerManager();
    $cm = new CronManager();
    $routeDetails = $cm->getVehicleCheckpointRoute($thischkpt->checkpointid, $thischkpt->customerno, $thischkpt->vehicleid);
    if (isset($routeDetails) && !empty($routeDetails)) {
        $objLog = new stdClass();
        $objLog->smsId = $smsId;
        $objLog->emailId = $emailId;
        $objLog->checkpointId = $thischkpt->checkpointid;
        $objLog->vehicleId = $thischkpt->vehicleid;
        $objLog->routeId = $routeDetails[0]->routeid;
        $objLog->customerno = $thischkpt->customerno;
        $objLog->today = $curtime;
        $objCustomer->insertCheckpointOwnerLog($objLog);
    }
}

function sendCheckpointSms($thischkpt, $data, $message, $curtime) {
    $smsId = 0;
    $userid = 0;
    $objCustomer = new CustomerManager();
    $objSmsStatus = new stdClass();
    $objSmsStatus->customerno = $thischkpt->customerno;
    $objSmsStatus->vehicleid = $thischkpt->vehicleid;
    $objSmsStatus->userid = $userid;
    $objSmsStatus->mobileno = $data->phoneno;
    $objSmsStatus->message = $message;
    $objSmsStatus->cqid = 0;
    $smsStatus = $objCustomer->getSMSStatus($objSmsStatus);
    if ($smsStatus == 0 || $smsStatus == -2) {
        $response = '';
        $isSMSSent = sendSMSUtil(array($data->phoneno), $message, $response);
        $moduleid = 1;
        if ($isSMSSent == 1) {
            $smsId = $objCustomer->sentSmsPostProcess($thischkpt->customerno, array($data->phoneno), $message, $response, $isSMSSent, $userid, $thischkpt->vehicleid, $moduleid);
        }
    }
    return $smsId;
}

function sendCheckpointEmail($thischkpt, $data, $message, $curtime) {
    $emailId = 0;
    $objCustomer = new CustomerManager();
    $strCCMailIds = '';
    $strBCCMailIds = '';
    $subject = "Checkpoint Alert";
    $attachmentFilePath = '';
    $attachmentFileName = '';
    $mailContent = '';
    $placehoders['{{EMAIL_BODY}}'] = $message;
    $html = file_get_contents('../emailtemplates/sendCheckpointEmail.html');
    foreach ($placehoders as $key => $val) {
        $html = str_replace($key, $val, $html);
    }
    $mailContent .= $html;
    $isEmailSent = sendMailUtil(array($data->email), $strCCMailIds, $strBCCMailIds, $subject, $mailContent, $attachmentFilePath, $attachmentFileName, 1);
    $objEmail = new stdClass();
    $objEmail->email = $data->email;
    $objEmail->subject = $subject;
    $objEmail->message = $message;
    $objEmail->vehicleid = $thischkpt->vehicleid;
    $objEmail->userid = '';
    $objEmail->type = 2;
    $objEmail->moduleid = speedConstants::MODULE_VTS;
    $objEmail->customerno = $thischkpt->customerno;
    $objEmail->isMailSent = $isEmailSent;
    $objEmail->today = $curtime;
    if ($isEmailSent == 1) {
        $emailId = $objCustomer->insertCustomerEmailLog($objEmail);
    }
    return $emailId;
}

echo "<br/> Cron Completed On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";
?>
