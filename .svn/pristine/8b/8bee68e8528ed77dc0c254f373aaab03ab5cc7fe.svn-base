<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$RELATIVE_PATH_DOTS = "../../../../";
require_once "../../../../lib/system/utilities.php";
require_once '../../../../lib/autoload.php';
require_once '../../files/calculatedist.php';
require_once '../../files/push_sqlite.php';

$objCronMgr = new CronManager();

$currentTimeStamp = date('Y-m-d H:i:s');

$STdate = date("Y-m-d");
//$startTime = $STdate . " 00:00:00";
$startTime = date('Y-m-d 00:00:00', strtotime("-1 days"));
$endTime = $STdate . " 23:59:59";

$moduleid = speedConstants::MODULE_VTS;
$customerExceptionList = null;
//Get array of customers who has route alert on
$arrCustomers = array("520");
$userId = 7499;
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

            //prettyPrint($thischkpt);
            $crad = (float) $thischkpt->crad;
            $path = "sqlite:../../../../customer/" . $thischkpt->customerno . "/reports/chkreport.sqlite";

            echo $thischkpt->cname . " -- " . $thischkpt->conflictstatus;
            echo "<br>";
            echo $distance = calculate($thischkpt->devicelat, $thischkpt->devicelong, $thischkpt->cgeolat, $thischkpt->cgeolong);
            echo "<br>";
            echo "Radius:" . $crad;
            echo "<br>";

            /* Get sequence of previous checkpoint */
            if (!isset($previousChkPtInfo)) {
                $previousChkPtInfo = $objCronMgr->getVehicleCheckpointRoute($thischkpt->vehChkPtId, $thischkpt->customerno, $thischkpt->vehicleid);
                if (isset($previousChkPtInfo)) {
                    $previousChkPtSequence = $previousChkPtInfo[0]->sequence;
                }
            }

            /* Get All Checkpoint Details Of Route */
            $currentSequence = 0;
            $currentRouteid = 0;
            $currentRouteMaxSequence = 0;
            $checkpointInfo = $objCronMgr->getVehicleCheckpointRoute($thischkpt->checkpointid, $thischkpt->customerno, $thischkpt->vehicleid);
            if (isset($checkpointInfo)) {
                $currentSequence = $checkpointInfo[0]->sequence;
                $currentRouteid = $checkpointInfo[0]->routeid;
            }

            /*
              The alert would go only if the current checkpoint’s sequence in route is greater than previous checkpoint.
             */
            $sendAlert = 0;
            if (isset($previousChkPtSequence) && $currentSequence > $previousChkPtSequence) {
                $sendAlert = 1;
            }

            if ($thischkpt->conflictstatus == 0) {
                if ($distance >= $crad) {
                    echo $objVOComQueue->message = $thischkpt->vehicleno . " left " . $thischkpt->cname;
                    $objVOComQueue->status = 0;

                    $objComQueue->InsertQChk($objVOComQueue);
                    $objCronMgr->markoutsidechk($thischkpt->cmid, $thischkpt->customerno, $thischkpt->lastupdated);
                    ChkSqlite($thischkpt->customerno, $thischkpt->checkpointid, 1, $thischkpt->lastupdated, $thischkpt->vehicleid, $thischkpt->chktype);
                    $objCronMgr->markVehicleCheckpointOut($objVOComQueue);

                    /* Send Alert to next checkpoint */
                    $result = $objCronMgr->checkpointupdate($path, $startTime, $endTime, $thischkpt->vehicleid, $thischkpt->checkpointid);

                    if (isset($result) && $result != "") {
                        foreach ($result as $row) {
                            if (isset($row["outstatus"]) && isset($row["instatus"])) {
                                $datarow = $objCronMgr->checkintoroute($thischkpt->checkpointid, $thischkpt->customerno, $thischkpt->vehicleid);
                                if (isset($datarow) && !empty($datarow)) {
                                    foreach ($datarow as $data) {
                                        $smsId = 0;
                                        $emailId = 0;
                                        $message = "";
                                        $status = "";
                                        $realTimeETA = "NA";

                                        if ($currentSequence == 1) {
                                            $message = "Test SMS School Bus[" . $thischkpt->vehicleno . "] has left from " . $thischkpt->cname . " at " . convertDateToFormat($thischkpt->lastupdated, speedConstants::DEFAULT_TIME) . " It would reach shortly to your stop. Pragati Academy.";
                                        }
                                        if ($message != '') {
                                            $objRequest = new stdClass();
                                            $objRequest->routeid = $data->routeid;
                                            $objRequest->checkpointid = $data->checkpintid;
                                            $objRequest->customerno = $thischkpt->customerno;
                                            $arrPhoneNo = $objCronMgr->getPhoneNumbersForRouteCheckpoint($objRequest);
                                            //$arrTempPhoneNo = array('phoneNo'=>'9969941084');
                                            //array_push($arrPhoneNo,$arrTempPhoneNo);
                                            prettyPrint($arrPhoneNo);

                                            if (isset($arrPhoneNo) && count($arrPhoneNo) > 0 && $data->isSms == 1) {
                                                foreach ($arrPhoneNo as $phoneNo) {
                                                    if (isset($phoneNo['phoneNo']) && $phoneNo['phoneNo'] != "") {
                                                        $smsId = sendCheckpointSms($thischkpt, $phoneNo['phoneNo'], $message, $currentTimeStamp);
                                                    }
                                                }
                                            }
                                            if ($data->email != '' && $data->isEmail == 1) {
                                                $emailId = sendCheckpointEmail($thischkpt, $data, $message, $currentTimeStamp);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    break;
                }
            } elseif ($distance < $crad && $thischkpt->conflictstatus == 1) {
                echo $objVOComQueue->message = $thischkpt->vehicleno . " entered " . $thischkpt->cname;
                $objVOComQueue->status = 1;
                $objComQueue->InsertQChk($objVOComQueue);
                $objCronMgr->markinsidechk($thischkpt->cmid, $thischkpt->customerno, $thischkpt->lastupdated);
                ChkSqlite($thischkpt->customerno, $thischkpt->checkpointid, 0, $thischkpt->lastupdated, $thischkpt->vehicleid, $thischkpt->chktype);
                $objCronMgr->markVehicleCheckpointIn($objVOComQueue);

                /* Don't alert to next checkpoint if vehicle has entered the 1st checkpoint or  starting point of route */
                if ($currentSequence > 1) {
                    $datarow = $objCronMgr->checkintoroute($thischkpt->checkpointid, $thischkpt->customerno, $thischkpt->vehicleid);

                    $isGoogleDistance = 1;
                    if (isset($datarow) && !empty($datarow)) {
                        foreach ($datarow as $data) {
                            $smsId = 0;
                            $emailId = 0;

                            $message = "Test SMS School Bus[" . $thischkpt->vehicleno . "] has reached previous bus stop [" . $thischkpt->cname . "] at "
                                    . convertDateToFormat($thischkpt->lastupdated, speedConstants::DEFAULT_TIME) . " It would reach shortly to your stop. Pragati Academy";

                            $objRequest = new stdClass();
                            $objRequest->routeid = $data->routeid;
                            $objRequest->checkpointid = $data->checkpintid;
                            $objRequest->customerno = $thischkpt->customerno;
                            $arrPhoneNo = $objCronMgr->getPhoneNumbersForRouteCheckpoint($objRequest);
                            //$arrTempPhoneNo = array('phoneNo'=>'9969941084');
                            //array_push($arrPhoneNo,$arrTempPhoneNo);
                            prettyPrint($arrPhoneNo);
                            if ($sendAlert == 1) {
                                if (isset($arrPhoneNo) && count($arrPhoneNo) > 0 && $data->isSms == 1) {
                                    foreach ($arrPhoneNo as $phoneNo) {
                                        if (isset($phoneNo['phoneNo']) && $phoneNo['phoneNo'] != "") {
                                            $smsId = sendCheckpointSms($thischkpt, $phoneNo['phoneNo'], $message, $currentTimeStamp);
                                        }
                                    }
                                }

                                if ($data->email != '' && $data->isEmail == 1) {
                                    $emailId = sendCheckpointEmail($thischkpt, $data, $message, $currentTimeStamp);
                                }
                            }
                        }
                    }
                }
                break;
            }
        } catch (Exception $ex) {
            $objLog = new Log();
            $objLog->createlog($thischkpt->customerno, $ex, 0, 1, 'CronBusRouteAlert-TopScoreMET');
        }
    }
}

function sendCheckpointSms($thischkpt, $phoneNo, $message, $curtime) {
    /* Elixia SMS calling function */
    /*
      $smsId = 0;
      $userid = 0;
      $objCustomer = new CustomerManager();
      $objSmsStatus = new stdClass();
      $objSmsStatus->customerno = $thischkpt->customerno;
      $objSmsStatus->vehicleid = $thischkpt->vehicleid;
      $objSmsStatus->userid = $userid;
      $objSmsStatus->mobileno = $phoneNo;
      $objSmsStatus->message = $message;
      $objSmsStatus->cqid = 0;
      $smsStatus = $objCustomer->getSMSStatus($objSmsStatus);
      if ($smsStatus == 0 || $smsStatus == -2) {
      $response = '';
      $arrPhoneNo = array($phoneNo);
      $isSMSSent = sendSMSUtil($arrPhoneNo, $message, $response);
      $moduleid = 1;
      if ($isSMSSent == 1) {
      $smsId = $objCustomer->sentSmsPostProcess($thischkpt->customerno, $phoneNo, $message, $response, $isSMSSent, $userid, $thischkpt->vehicleid, $moduleid);
      }
      }
      return $smsId;
     */

    /* Topscore SMS calling function */
    $arrPhoneNo = array($phoneNo);
    $isSMSSent = sendSMS_Vone($arrPhoneNo, $message, $response);
    return $isSMSSent;
}

function sendCheckpointEmail($thischkpt, $data, $message, $curtime) {
    $emailId = 0;
    $objCustomer = new CustomerManager();

    $strCCMailIds = '';
    $strBCCMailIds = '';
    $subject = "School Bus Route Alert";
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

function sendSMS_Vone($phonearray, $message, &$response) {
    $smsUrl = "http://tx.vonesms.in/api/v4/?api_key=Af95f87749256633599fb2cb6b0e4e8e9&method=sms&message={{MESSAGETEXT}}&to={{PHONENO}}&sender=PRAACD";
    $isSMSSent = 0;
    $countryCode = "91";
    $arrPhone = array();
    if (is_array($phonearray)) {
        foreach ($phonearray as $phone) {
            if (preg_match('/^\d{10}$/', $phone)) {
                $arrPhone[] = $countryCode . $phone;
            }
        }
    } else {
        $arrPhone[] = $countryCode . $phonearray;
    }
    $phone = implode(",", $arrPhone);
    $url = str_replace("{{PHONENO}}", urlencode($phone), $smsUrl);
    $url = str_replace("{{MESSAGETEXT}}", urlencode($message), $url);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FAILONERROR, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    if ($response === false) {
        //echo 'Curl error: ' . curl_error($ch);
        $isSMSSent = 0;
    } else {
        $isSMSSent = 1;
    }
    curl_close($ch);
    return $isSMSSent;
}

?>
