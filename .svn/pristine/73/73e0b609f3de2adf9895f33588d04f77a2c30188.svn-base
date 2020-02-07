<?php
set_time_limit(60);
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';
require_once 'files/calculatedist.php';
require_once 'files/push_sqlite.php';
echo "<br/> Cron Start On ".date(speedConstants::DEFAULT_TIMESTAMP)." <br/>";
$today = date('Y-m-d H:i:s');
$currentTime = date('H:i:s');
$objCron = new CronManager();
$objCustomer = new CustomerManager();
$objUserManager = new UserManager();
$smsStatus = new SmsStatus();
$moduleid = speedConstants::MODULE_CHKPTEXCEPTION;
$arrException = $objCron->getCronCheckpointExceptions($currentTime);
if (isset($arrException)) {
    foreach ($arrException as $exception) {
        //<editor-fold defaultstate="collapsed" desc="Checkpoint Calculations">
        /* Checkpoint Radius */
        $checkpointRadius = (float) $exception->crad;
        /* Calculate Distance From Chgeckpoint */
        $distanceFromCheckpoint = calculate($exception->devicelat, $exception->devicelong, $exception->cgeolat, $exception->cgeolong);
        $message = '';
        if ($distanceFromCheckpoint < $checkpointRadius && $exception->exceptionType == 1) {
            $message = "Vehicle No. " . $exception->vehicleNo . " is inside " . $exception->checkpointName . " at " . convertDateToFormat($exception->lastupdated, speedConstants::DEFAULT_TIME) . " .";
        }
        if ($distanceFromCheckpoint >= $checkpointRadius && $exception->exceptionType == 2) {
            $message = "Vehicle No. " . $exception->vehicleNo . " is outside " . $exception->checkpointName . " at " . convertDateToFormat($exception->lastupdated, speedConstants::DEFAULT_TIME) . " .";
        }
        //</editor-fold>
        if ($message != '') {
            $logId = 0;
            $arrUsers = null;
            $exception->message = $message;
            $exception->today = $today;
            $objChk = new CheckpointManager($exception->customerno);
            $logId = $objChk->logCheckpointException($exception);
            if ($logId != 0) {
                /* Update Exception to isSend = 1 */
                $objChk->updateExceptionToSend($exception);
                /* SET chkptExAlertId  */
                $exception->chkptExAlertId = $logId;
                $exception->errorNo = 0;
                /* Get Exception User Mapping */
                $arrUsers = $objUserManager->getUserExceptionMapping($exception);
                if (isset($arrUsers)) {
                    $userAlertDetails = null;
                    foreach ($arrUsers as $user) {
                        $user->userid = $user->userId;
                        $exception->userid = $user->userid;
                        /* Get User Alert Mapping */
                        $userAlertDetails = $objUserManager->getUserAlertMapping($user);
                        if (isset($userAlertDetails)) {
                            foreach ($userAlertDetails as $alert) {
                                //$user->phone = 9421377403;
                                //$user->email = 'software@elixiatech.com';
                                //<editor-fold defaultstate="collapsed" desc="SMS ALERT">
                                if ($alert->alertTypeId == 1 && $alert->isActive == 1) {
                                    if ($user->phone != '') {
                                        $userid = 0;
                                        $smsStatus->customerno = $exception->customerno;
                                        $smsStatus->userid = $userid;
                                        $smsStatus->vehicleid = $exception->vehicleId;
                                        $smsStatus->mobileno = $user->phone;
                                        $smsStatus->message = $message;
                                        $smsStatus->cqid = 0;
                                        $smsCount = $objCustomer->getSMSStatus($smsStatus);
                                        if ($smsCount == 0) {
                                            $response = '';
                                            $isSMSSent = sendSMSUtil(array($user->phone), $message, $response);
                                            $moduleid = 1;
                                            if ($isSMSSent == 1) {
                                                $objCustomer->sentSmsPostProcess($exception->customerno, array($user->phone), $message, $response, $isSMSSent, $userid, $exception->vehicleId, $moduleid);
                                            } else {
                                                $exception->errorNo = speedConstants::ERRNO_SMS_API_ISSUE;
                                                $objCustomer->chkptExErrorLog($exception);
                                            }
                                        } elseif ($smscount == -3) {
                                            $exception->errorNo = speedConstants::ERRNO_INSUFFICIENT_SMS;
                                            $objCustomer->chkptExErrorLog($exception);
                                        }
                                    } else {
                                        $exception->errorNo = speedConstants::ERRNO_PHONE_NOT_AVAILABLE;
                                        $objCustomer->chkptExErrorLog($exception);
                                    }
                                }
                                //</editor-fold>
                                //<editor-fold defaultstate="collapsed" desc="Email Alert">
                                if ($alert->alertTypeId == 2 && $alert->isActive == 1) {
                                    if ($user->email != '') {
                                        $strCCMailIds = '';
                                        $strBCCMailIds = '';
                                        $subject = "Checkpoint Exception Alert";
                                        $attachmentFilePath = '';
                                        $attachmentFileName = '';
                                        $isEmailSent = sendMailUtil(array($user->email), $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName);
                                        $objEmail = new stdClass();
                                        $objEmail->email = $user->email;
                                        $objEmail->subject = $subject;
                                        $objEmail->message = $message;
                                        $objEmail->vehicleid = $exception->vehicleId;
                                        $objEmail->userid = $user->userid;
                                        $objEmail->type = 0;
                                        $objEmail->moduleid = $moduleid;
                                        $objEmail->customerno = $exception->customerno;
                                        $objEmail->isMailSent = $isEmailSent;
                                        $objEmail->today = $today;
                                        $objCustomer->insertCustomerEmailLog($objEmail);
                                    } else {
                                        $exception->errorNo = speedConstants::ERRNO_EMAIL_NOT_AVAILABLE;
                                        $objCustomer->chkptExErrorLog($exception);
                                    }
                                }
                                //</editor-fold>
                                //<editor-fold defaultstate="collapsed" desc="GCM/FCM Notofication">
                                if ($alert->alertTypeId == 4 && $alert->isActive == 1) {
                                    if ($user->gcmid != '') {
                                        $gcmMessage = array(
                                            "message" => $message,
                                            "options" => array(),
                                        );
                                        $isNotify = sendFCMUtil($user->gcmid, $gcmMessage, $exception->customerno);
                                    } else {
                                        $exception->errorNo = speedConstants::ERRNO_GCM_NOT_AVAILABLE;
                                        $objCustomer->chkptExErrorLog($exception);
                                    }
                                }
                                //</editor-fold>
                            }
                        }
                    }
                }
            }
        }
    }
}
echo "<br/> Cron Completed On ".date(speedConstants::DEFAULT_TIMESTAMP)." <br/>";
?>
