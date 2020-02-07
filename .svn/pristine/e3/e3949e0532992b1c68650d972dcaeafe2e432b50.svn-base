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
$cm = new CronManager();
$cust = new CustomerManager();
$smsStatus = new SmsStatus();
$smsstart = date('Y-m-d') . " 04:00:00";
$smsend = date('Y-m-d') . " 14:00:00";
$curtime = date('Y-m-d H:i:s');
$moduleid = speedConstants::MODULE_VTS;
// Freeze
$freeze = $cm->getalldevices_freeze();
if (isset($freeze)) {
//    $data = '';
    foreach ($freeze as $thisfreeze) {
        $distance_freeze = 0;
        $lastodometer = 0;
        $maxodometer = 0;
        $devicelat = $thisfreeze->devicelat;
        $devicelong = $thisfreeze->devicelong;
        $vehicleid = $thisfreeze->vehicleid;
        $cradfreeze = FREEZE_RADIUS; // 40 Meter define in utility page
        $getdailyreportdata = $cm->getdailyreoport_freeze($vehicleid);
        $lastodometer = isset($getdailyreportdata['last_odometer']) ? $getdailyreportdata['last_odometer'] : "0";
        $maxodometer = isset($getdailyreportdata['max_odometer']) ? $getdailyreportdata['max_odometer'] : "0";
        $getfreezedata = $cm->getfreezedata($vehicleid);
        $getfreezeodometer = isset($getfreezedata['odometer']) ? $getfreezedata['odometer'] : "0";
        //$distance_freeze = calculate($devicelat, $devicelong, $getfreezedata['freezelat'], $getfreezedata['freezelong']);
        $distance_freeze = calculate_by_odometer($getfreezeodometer, $maxodometer, $lastodometer, $vehicleid);
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
$route_dashboard_customer = explode(",", speedConstants::ROUTE_DASHBOARD_CUSTNO);
$customerExceptionList = "161,59,100,328,63,520";
$chkpts = $cm->getalldeviceswithchkpforcron($customerExceptionList);
//print_r($chkpts);
if (isset($chkpts)) {
    echo count($chkpts);
    foreach ($chkpts as $thischkpt) {
        //echo 'CHK'.$thischkpt->checkpointid.'<br>';
        $devicelat = $thischkpt->devicelat;
        $devicelong = $thischkpt->devicelong;
        $cgeolat = $thischkpt->cgeolat;
        $cgeolong = $thischkpt->cgeolong;
        $crad = (float) $thischkpt->crad;
        $distance = calculate($devicelat, $devicelong, $cgeolat, $cgeolong);
        $currentSequence = 0;
        if ($distance >= $crad && $thischkpt->conflictstatus == 0) {
            // Insert in Queue
            $cqm = new ComQueueManager();
            $cvo = new VOComQueue();
            $objCustomer = new CustomerManager();
            $cvo->customerno = $thischkpt->customerno;
            $cvo->lat = $thischkpt->devicelat;
            $cvo->long = $thischkpt->devicelong;
            $cvo->message = $thischkpt->vehicleno . " left " . $thischkpt->cname;
            $cvo->type = 2;
            $cvo->status = 0;
            $cvo->vehicleid = $thischkpt->vehicleid;
            $cvo->chkid = $thischkpt->checkpointid;
            $cvo->lastupdated = $thischkpt->lastupdated;
            $isExistsRecord = $cqm->checkComQueExistance($cvo);
            if (!$isExistsRecord) {
                ChkSqlite($thischkpt->customerno, $thischkpt->checkpointid, 1, $thischkpt->lastupdated, $thischkpt->vehicleid, $thischkpt->chktype);
                vehicleChkSqlite($thischkpt->customerno, $thischkpt->checkpointid, 1, $thischkpt->lastupdated, $thischkpt->vehicleid, $thischkpt->chktype);
                $cm->markoutsidechk($thischkpt->cmid, $thischkpt->customerno, $thischkpt->lastupdated);
                $cm->markVehicleCheckpointOut($cvo);
                $cqm->InsertQChk($cvo);
                // Send Alert to next checkpoint
                if (in_array($thischkpt->customerno, $route_dashboard_customer)) {
                    $STdate = date("Y-m-d");
                    //$starttime = $STdate . " 00:00:00";
                    $starttime = date('Y-m-d 00:00:00', strtotime("-1 days"));
                    $endtime = $STdate . " 23:59:59";
                    $path = "sqlite:../../customer/$thischkpt->customerno/reports/chkreport.sqlite";
                    $interval = 5;
                    $result = $cm->checkpointupdate($path, $starttime, $endtime, $thischkpt->vehicleid, $thischkpt->checkpointid);
                    if (isset($result) && $result != "") {
                        foreach ($result as $row) {
                            if (isset($row["outstatus"]) && isset($row["instatus"]) && (round(abs(strtotime($row["outdate"]) - strtotime($row["indate"])) / 60, 2) > $interval) && (strtotime($smsstart) <= strtotime($curtime) && strtotime($smsend) >= strtotime($curtime))) {
                                $datarow = $cm->checkintoroute($thischkpt->checkpointid, $thischkpt->customerno, $thischkpt->vehicleid);
                                if (isset($datarow)) {
                                    $chkPtCount = count($datarow);
                                    if ($thischkpt->customerno == 73) {
                                        $isGoogleDistance = 1;
                                        foreach ($datarow as $data) {
                                            $message = "";
                                            $status = "";
                                            $realTimeETA = "NA";
                                            /*
                                            ETA, status should not be calculated when vehicle is leaving the first checkpoint of route
                                            Alert all Mumbai checkpoints if vehicle has left DC or  starting point of route
                                             */
                                            if ($chkPtCount > 1) {
                                                $message = "Vehicle[" . $thischkpt->vehicleno . "] has left previous point [" . $thischkpt->cname . "] at "
                                                . convertDateToFormat($thischkpt->lastupdated, speedConstants::DEFAULT_TIME);
                                            } else {
                                                $distance = calculate($data->cgeolat, $data->cgeolong, $thischkpt->cgeolat, $thischkpt->cgeolong, $isGoogleDistance, $thischkpt->customerno);
                                                if (isset($distance['min']) && $distance['min'] != -1) {
                                                    //Get current date in temp variable
                                                    $tempETA = new DateTime();
                                                    //Replace time part
                                                    $standardETA = $tempETA->format(speedConstants::DATE_Ymd) . " " . $thischkpt->eta;
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
                                                        if ($minutes == 0) {
                                                            $status = "On Time";
                                                        } else {
                                                            $status = "Delayed";
                                                            $etaStatus = "Delayed by " . $distance['min'] . " minutes";
                                                            $cm->updateRouteEtaStatus($data->rmid, $etaStatus, $thischkpt->customerno);
                                                        }
                                                    } else {
                                                        $status = "Early";
                                                        $etaStatus = $status;
                                                        $cm->updateRouteEtaStatus($data->rmid, $etaStatus, $thischkpt->customerno);
                                                    }
                                                }
                                                $message = "Vehicle[" . $thischkpt->vehicleno . "] has left previous point [" . $thischkpt->cname . "] at "
                                                . convertDateToFormat($thischkpt->lastupdated, speedConstants::DEFAULT_TIME) . PHP_EOL
                                                . "ETA: " . $thischkpt->eta . PHP_EOL
                                                    . "STA: " . $realTimeETA . PHP_EOL
                                                    . "Status: " . $status;
                                            }
                                            $objLog = new Log();
                                            $objLog->createlog($thischkpt->customerno, "ChkptMsg: " . $message, "CRON_ALL");
                                            if ($data->phoneno != '' && $data->isSms == 1) {
                                                $smsStatus->customerno = $thischkpt->customerno;
                                                $smsStatus->userid = 0;
                                                $smsStatus->vehicleid = $thischkpt->vehicleid;
                                                $smsStatus->mobileno = $data->phoneno;
                                                $smsStatus->message = $message;
                                                $smsStatus->cqid = 0;
                                                $smscount = $objCustomer->getSMSStatus($smsStatus);
                                                if ($smscount == 0) {
                                                    $response = '';
                                                    $isSMSSent = sendSMSUtil(array($data->phoneno), $message, $response);
                                                    if ($isSMSSent == 1) {
                                                        $objCustomer->sentSmsPostProcess($thischkpt->customerno, $data->phoneno, $message, $response, $isSMSSent, 0, $thischkpt->vehicleid, $moduleid);
                                                    }
                                                }
                                            }
                                            if ($data->email != '' && $data->isEmail == 1) {
                                                $strCCMailIds = '';
                                                $strBCCMailIds = '';
                                                $subject = "Checkpoint Alert";
                                                $attachmentFilePath = '';
                                                $attachmentFileName = '';
                                                $message = "Dear Customer, " . PHP_EOL . PHP_EOL . $message;
                                                $isEmailSent = sendMailUtil(array($data->email), $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName);
                                                $objEmail = new stdClass();
                                                $objEmail->email = $data->email;
                                                $objEmail->subject = $subject;
                                                $objEmail->message = $message;
                                                $objEmail->vehicleid = $thischkpt->vehicleid;
                                                $objEmail->userid = '';
                                                $objEmail->type = $cvo->type;
                                                $objEmail->moduleid = $moduleid;
                                                $objEmail->customerno = $thischkpt->customerno;
                                                $objEmail->isMailSent = $isEmailSent;
                                                $objEmail->today = $curtime;
                                                if ($isEmailSent == 1) {
                                                    $emailId = $objCustomer->insertCustomerEmailLog($objEmail);
                                                }
                                            }
                                            /* Store Log */
                                            if ((isset($smsId) && $smsId != 0) || (isset($emailId) && $emailId != 0)) {
                                                insertCheckpointOwnerLog($smsId, $emailId, $thischkpt, $curtime);
                                            }
                                        }
                                    } else {
                                        $message = "Van No. " . $thischkpt->vehicleno . " has left the previous destination at " . convertDateToFormat($thischkpt->lastupdated, speedConstants::DEFAULT_TIME) . " and will reach your place shortly. Kindly be ready to receive the material.";
                                        foreach ($datarow as $data) {
                                            if ($data->phoneno != '') {
                                                $smsStatus->customerno = $thischkpt->customerno;
                                                $smsStatus->userid = 0;
                                                $smsStatus->vehicleid = $thischkpt->vehicleid;
                                                $smsStatus->mobileno = $data->phoneno;
                                                $smsStatus->message = $message;
                                                $smsStatus->cqid = 0;
                                                $smsleft = $objCustomer->getSMSStatus($smsStatus);
                                                if ($smsleft == 0) {
                                                    $response = '';
                                                    $isSMSSent1 = sendSMSUtil($data->phoneno, $message, $response);
                                                    if ($isSMSSent1 == 1) {
                                                        $objCustomer->sentSmsPostProcess($thischkpt->customerno, $data->phoneno, $message, $response, $isSMSSent1, 0, $thischkpt->vehicleid, $moduleid);
                                                        $cm->storeSMS($data->phoneno, $message, $thischkpt->customerno, $thischkpt->checkpointid, $thischkpt->cname, $thischkpt->vehicleno);
                                                    }
                                                }
                                            }
                                        }

                                        if ($data[0]->sequence == 2 || ($thischkpt->routeDirection == 2 && $data[0]->sequence == 2)) {
                                            $objRouteDirection = new stdClass();
                                            $objRouteDirection->directionStatus = 1;
                                            $objRouteDirection->vehicleid = $thischkpt->vehicleid;
                                            $objRouteDirection->customerno = $thischkpt->customerno;
                                            $cm->updateVehicleRouteDirection($objRouteDirection);
                                        }
                                    }
                                } else {
                                    $routeCheckpoints = $cm->checkVehicleExistsInRoute($thischkpt->checkpointid, $thischkpt->customerno, $thischkpt->vehicleid);
                                    if (isset($routeCheckpoints) && !empty($routeCheckpoints)) {
                                        $isInSequence = $cm->checkRouteSequence($thischkpt->customerno, $routeCheckpoints);
                                        if (isset($isInSequence) && empty($isInSequence)) {
                                            $objRouteDirection = new stdClass();
                                            $objRouteDirection->directionStatus = 2;
                                            $objRouteDirection->vehicleid = $thischkpt->vehicleid;
                                            $objRouteDirection->customerno = $thischkpt->customerno;
                                            $cm->updateVehicleRouteDirection($objRouteDirection);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if ($thischkpt->customerno == speedConstants::CUSTNO_NESTLE) {
                    //Unmute all temperature sensors for that vehicle by not passing any sensors
                    $vehicledetails = new stdClass();
                    $vehicledetails->vehicleid = $thischkpt->vehicleid;
                    $vehicledetails->condition = "Unmute";
                    $vehiclemanager = new VehicleManager($thischkpt->customerno);
                    $vehiclemanager->muteVehicleTemperature($vehicledetails);
                }
                //Send chk pt status to 3rd party
                sendChkptInOutDataToThirdParty($thischkpt);
            }
        } elseif ($distance < $crad && $thischkpt->conflictstatus == 1) {
            // Insert in Queue
            $cqm = new ComQueueManager();
            $cvo = new VOComQueue();
            $objCustomer = new CustomerManager();
            $cvo->customerno = $thischkpt->customerno;
            $cvo->lat = $thischkpt->devicelat;
            $cvo->long = $thischkpt->devicelong;
            $cvo->message = $thischkpt->vehicleno . " entered " . $thischkpt->cname;
            $cvo->type = 2;
            $cvo->status = 1;
            $cvo->vehicleid = $thischkpt->vehicleid;
            $cvo->chkid = $thischkpt->checkpointid;
            $cvo->lastupdated = $thischkpt->lastupdated;
            $isExistsRecord = $cqm->checkComQueExistance($cvo);
            if (!$isExistsRecord) {
                ChkSqlite($thischkpt->customerno, $thischkpt->checkpointid, 0, $thischkpt->lastupdated, $thischkpt->vehicleid, $thischkpt->chktype);
                vehicleChkSqlite($thischkpt->customerno, $thischkpt->checkpointid, 0, $thischkpt->lastupdated, $thischkpt->vehicleid, $thischkpt->chktype);
                $cm->markinsidechk($thischkpt->cmid, $thischkpt->customerno, $thischkpt->lastupdated);
                $cm->markVehicleCheckpointIn($cvo);
                $cqm->InsertQChk($cvo);
                if ($thischkpt->customerno == 156 && $thischkpt->checkpointid == 1294) {
                    $cm->elixiacodeExpire($thischkpt->customerno, $thischkpt->vehicleid);
                }
                if ($thischkpt->customerno == speedConstants::CUSTNO_NESTLE) {
                    //Mute all temperature sensors for that vehicle by not passing any sensors
                    $vehicledetails = new stdClass();
                    $vehicledetails->vehicleid = $thischkpt->vehicleid;
                    $vehicledetails->condition = "Mute";
                    $vehiclemanager = new VehicleManager($thischkpt->customerno);
                    $vehiclemanager->muteVehicleTemperature($vehicledetails);
                }

                if (isset($thischkpt->customerno) && speedConstants::CUSTNO_APTINFRA) {
                    $routeCheckpoints = $cm->checkVehicleExistsInRoute($thischkpt->checkpointid, $thischkpt->customerno, $thischkpt->vehicleid);

                    if (is_array($routeCheckpoints) && count($routeCheckpoints) > 0 && !empty($routeCheckpoints)) {
                        foreach ($routeCheckpoints as $checkpoint) {
                            if ((isset($checkpoint) && !empty($checkpoint)) && (isset($checkpoint->routetype) && $checkpoint->routetype == 1)) {
                                $isInSequence = $cm->checkRouteSequence($thischkpt->customerno, $routeCheckpoints);
                                if (isset($isInSequence) && empty($isInSequence)) {
                                    
                                    //updateTripCount($thischkpt, $curtime);

                                    /*code here for next future Route*/
                                    $rm = new RouteManager($thischkpt->customerno);
                                    $nextRoute = $rm->getNextFutureRoute($checkpoint->routeid, $thischkpt->vehicleid);

                                    if (isset($nextRoute) && !empty($nextRoute) && $nextRoute != 0) {
                                        $fchkpts = $rm->get_all_checkpointid_forroute($nextRoute['nextRouteId']);
                                        $vehicles = $rm->getvehiclesforroute($nextRoute['nextRouteId']);

                                        $vCount = is_array($vehicles) ? count($vehicles) : 0;
                                        $vehicleArray = "";

                                        if (is_array($vehicles) && $vCount > 0 && !empty($vehicles)) {
                                            foreach ($vehicles as $vehicle) {
                                                $vehicleid = $vehicle->vehicleid;
                                                $vehicleArray .= "$vehicleid,";
                                            }
                                        }

                                        $vehicleArray .= $thischkpt->vehicleid;
                                        $froutearray = "";

                                        $c = count($fchkpts);
                                        $j = 0;
                                        foreach ($fchkpts as $fchkpt) {
                                            $j++;
                                            $chkptid = $fchkpt->checkpointid;
                                            $froutearray .= "$chkptid";
                                            if ($j < $c) {
                                                $froutearray .= ",";
                                            }
                                        }
                                        // print_r("<pre>"); print_r($vehicleArray);
                                        $nextRouteId = $nextRoute['nextRouteId'];
                                        $routename = "";
                                        $froutearray;
                                        // $vehicleid = $thischkpt->vehicleid;
                                        $userid = $nextRoute['userid'];

                                        $chkdetails = array();
                                        $routeTat = "api";
                                        $routetype = 1;
                                        $rm->edit_Route($nextRouteId, $routename, $froutearray, $vehicleArray, $userid, $chkdetails, $routeTat, $routetype);
                                    }else{
                                            updateTripCount($thischkpt, $curtime);
                                    }
                                }
                            }
                        }
                    } // end if routePoints
                }

                /* Third Party API */
                sendChkptInOutDataToThirdParty($thischkpt);
            }
        }
    }
}
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
function sendChkptInOutDataToThirdParty($objChkPt) {
    try {
        switch ($objChkPt->customerno) {
        case speedConstants::CUSTNO_SAFEANDSECURE:
            sendChkptDataSafeAndSecure($objChkPt);
            break;
        }
    } catch (Exception $ex) {
        $log = new Log();
        $log->createlog($objChkPt->customerno, $ex, "CRON_ALL", speedConstants::MODULE_VTS, __FUNCTION__);
    }
}

function sendChkptDataSafeAndSecure($objChkPt) {
    try {
        $todaysDate = new DateTime();
        $client = new SoapClient("http://115.124.100.86/GEODATA/Service.asmx?WSDL");
        $params = new stdClass();
        $params->VehicleNo = $objChkPt->vehicleno;
        $params->Location = location($objChkPt->devicelat, $objChkPt->devicelong, $objChkPt->customerno);
        $params->GEOFenceName = $objChkPt->cname;
        $params->Nature = ($objChkPt->conflictstatus == 1) ? "IN" : "OUT";
        $params->GEODate = $todaysDate->format(speedConstants::DATE_Ymd);
        $params->token = 'SGZDX0AjMjAxNQ--';
        $result = $client->TPSService_GEOData($params)->TPSService_GEODataResult;
        /* Temporary code for logging. Remove after client confirmation */
        ob_start();
        print_r($params);
        $strRequest = ob_get_clean();
        $objLog = new Log();
        $objLog->createlog($objChkPt->customerno, "Request: " . $strRequest, "CRON_ALL");
        if (isset($result)) {
            ob_start();
            print_r($result);
            $strResponse = ob_get_clean();
            $objLog->createlog($objChkPt->customerno, "Response: " . $strResponse, "CRON_ALL");
        }
    } catch (SoapFault $soapEx) {
        $log = new Log();
        $log->createlog($objChkPt->customerno, $soapEx, "CRON_ALL", speedConstants::MODULE_VTS, __FUNCTION__);
    } catch (Exception $ex) {
        throw $ex;
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

function updateTripCount($thischkpt, $curtime) {
    $objCustomer = new CustomerManager();
    $cm = new CronManager();
    $objLog = new stdClass();
    $daily_date = explode(" ", $curtime);
    $objLog->vehicleId = $thischkpt->vehicleid;
    $objLog->customerno = $thischkpt->customerno;
    $objLog->daily_date = $daily_date[0];
    $cm->updateTripCount($objLog);
}

?>
