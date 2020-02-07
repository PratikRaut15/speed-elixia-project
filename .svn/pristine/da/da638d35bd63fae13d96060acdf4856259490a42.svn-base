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
$smsstart = date('Y-m-d') . " 00:00:00";
$smsend = date('Y-m-d') . " 23:59:00";
$curtime = date('Y-m-d H:i:s');
$moduleid = speedConstants::MODULE_VTS;
$customerExceptionList = null;
//Get array of customers who has route alert on
$route_dashboard_customer = explode(",", speedConstants::ROUTE_DASHBOARD_CUSTNO);
$arrCustomers = array("682");
$userId = 8604;
$customerList = implode(",", $arrCustomers);
$chkpts = $cm->getalldeviceswithchkpforcron($customerExceptionList, $customerList);
//print_r($chkpts);
if (isset($chkpts)) {
    echo count($chkpts);
    //prettyPrint($chkpts);
    foreach ($chkpts as $thischkpt) {
        //if ($thischkpt->vehicleid == 14894) {
            //echo 'CHK'.$thischkpt->checkpointid.'<br>';
            $devicelat = $thischkpt->devicelat;
            $devicelong = $thischkpt->devicelong;
            $cgeolat = $thischkpt->cgeolat;
            $cgeolong = $thischkpt->cgeolong;
            $crad = (float) $thischkpt->crad;
            $distance = calculate($devicelat, $devicelong, $cgeolat, $cgeolong);
            //echo "<br/>";
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
                    // Send Alert to next checkpoint
                    if (in_array($thischkpt->customerno, $route_dashboard_customer)) {
                        echo $STdate = date("Y-m-d");
                        $starttime = $STdate . " 00:00:00";
                        //$starttime = date('Y-m-d 00:00:00', strtotime("-1 days"));
                        $endtime = $STdate . " 23:59:59";
                        $path = "sqlite:../../customer/$thischkpt->customerno/reports/chkreport.sqlite";
                        $interval = 0;
                        //$result = $cm->checkpointupdate($path, $starttime, $endtime, $thischkpt->vehicleid, $thischkpt->checkpointid);
                        //if (isset($result) && $result != "") {
                        //foreach ($result as $row) {
                        //if (isset($row["outstatus"]) && isset($row["instatus"]) && (round(abs(strtotime($row["outdate"]) - strtotime($row["indate"])) / 60, 2) > $interval) && (strtotime($smsstart) <= strtotime($curtime) && strtotime($smsend) >= strtotime($curtime))) {
                        $datarow = $cm->checkintoroute($thischkpt->checkpointid, $thischkpt->customerno, $thischkpt->vehicleid);
                        print_r($datarow);
                        if (isset($datarow)) {
                            $chkPtCount = count($datarow);
                            $message = "Van No. " . $thischkpt->vehicleno . " has entered the previous destination (".$thischkpt->cname.") at " . convertDateToFormat($thischkpt->lastupdated, speedConstants::DEFAULT_TIME) . " and will reach your place shortly. Kindly be ready to receive the material.";
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
                                if ($data->sequence == 2 || ($thischkpt->routeDirection == 2 && $data->sequence == 2)) {
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
                        //}
                        //}
                        //}
                    }
                }
            }
        //}
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
