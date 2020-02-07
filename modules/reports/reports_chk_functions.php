<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
if (!defined('RELATIVE_PATH_DOTS')) {
    define("RELATIVE_PATH_DOTS", $RELATIVE_PATH_DOTS);
}
include_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
include_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
include_once $RELATIVE_PATH_DOTS . 'lib/comman_function/reports_func.php';
include_once "reports_chk_comman_functions.php";
if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
}
function getvehicles() {
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $devices = $devicemanager->devicesformapping();
    return $devices;
}

function getunitdetailsfromvehid($deviceid) {
    $um = new UnitManager($_SESSION['customerno']);
    $unitno = $um->getunitdetailsfromvehid($deviceid);
    return $unitno;
}

function getunitdetailsfromvehid_pdf($deviceid, $customerno) {
    $um = new UnitManager($customerno);
    $unitno = $um->getunitdetailsfromvehid($deviceid);
    return $unitno;
}

function getunitno($deviceid) {
    $um = new UnitManager($_SESSION['customerno']);
    $unitno = $um->getunitnofromdeviceid($deviceid);
    return $unitno;
}

function get_all_checkpoint() {
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->getallcheckpoints();
    return $checkpoints;
}

function getCheckpointById($chkid) {
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoint = $checkpointmanager->get_checkpoint($chkid);
    return $checkpoint;
}

function getunitno_pdf($deviceid, $customerno) {
    $um = new UnitManager($customerno);
    $unitno = $um->getunitnofromdeviceid($deviceid);
    return $unitno;
}

function getcheckpoints($vehicleid, $routetype = NULL, $checkpointId = NULL, $chkTypeId = NULL) {
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->getchksforvehicle($vehicleid, $routetype, $checkpointId, $chkTypeId);
    return $checkpoints;
}

function getcheckpoints_cust($vehicleid, $customerno, $routetype = NULL) {
    $checkpointmanager = new CheckpointManager($customerno);
    $checkpoints = $checkpointmanager->getchksforvehicle($vehicleid, $routetype);
    return $checkpoints;
}

function getdate_IST() {
    $ServerDate_IST = strtotime(date("Y-m-d H:i:s"));
    return $ServerDate_IST;
}

function pullreport($STdate, $EDdate, $Shour, $Ehour, $vehicleid, $customerno, $chkPtId = NULL) {
    $STdate = date("Y-m-d", strtotime($STdate));
    $EDdate = date("Y-m-d", strtotime($EDdate));
    $path = "sqlite:" . RELATIVE_PATH_DOTS . "customer/$customerno/reports/chkreport.sqlite";
    if (isset($chkPtId) && $chkPtId != 0) {
        $Query = "select * from V" . $vehicleid . " WHERE chkid = $chkPtId AND  date BETWEEN '$STdate $Shour:00' AND '$EDdate $Ehour:59' GROUP BY date";
    } else {
        $Query = "select * from V" . $vehicleid . " WHERE date BETWEEN '$STdate $Shour:00' AND '$EDdate $Ehour:59' GROUP BY date";
    }
    $CHKMS = array();
    try {
        $db = new PDO($path);
        $result = $db->query($Query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $CHKM = new stdClass();
                $CHKM->chkid = $row["chkid"];
                $CHKM->status = $row["status"];
                $CHKM->date = $row["date"];
                $CHKMS[] = $CHKM;
            }
        }
    } catch (PDOException $e) {
        //prettyPrint($e);
        $CHKMS = 0;
    }
    return $CHKMS;
}

function gettemperature_fromsqlite($location, $time, $vehicle) {
    $Query = "select analog1,analog2,analog3,analog4 from unithistory WHERE lastupdated <= '$time' ORDER BY lastupdated DESC LIMIT 1";
    $tempconversion = new TempConversion();
    $tempconversion->unit_type = $vehicle->get_conversion;
    $CHKMS = array();
    try {
        $database = new PDO($location);
        $result = $database->query($Query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $analog1 = $row['analog1'];
                $analog2 = $row['analog2'];
                $analog3 = $row['analog3'];
                $analog4 = $row['analog4'];
                $result_temp = 'Not Active';
                $s = "analog" . $vehicle->tempsen1;
                if ($vehicle->tempsen1 != 0 && $$s != 0) {
                    $tempconversion->rawtemp = $$s;
                    $result_temp = getTempUtil($tempconversion);
                } else {
                    $result_temp = '0';
                }
            }
        }
    } catch (PDOException $e) {
        $result_temp = 0;
    }
    return $result_temp;
}

function gettemp($rawtemp) {
    $temp = round((($rawtemp - 1150) / 4.45), 2);
    return $temp;
}

function processchkrep($reports) {
    $count = 0;
    $chkreport = array();
    if (isset($reports) && $reports != "") {
        foreach ($reports as $report) {
            if ($report->status == 0) {
                $chkreport[$count] = new stdClass;
                $chkreport[$count]->cname = retval_issetor($report->cname);
                $chkreport[$count]->chkid = $report->chkid;
                $chkreport[$count]->starttime = $report->date;
                foreach ($reports as $samechk) {
                    if (isset($samechk->cname) && $chkreport[$count]->cname == $samechk->cname && $samechk->status == 1) {
                        if (strtotime($chkreport[$count]->starttime) < strtotime($samechk->date)) {
                            $chkreport[$count]->endtime = $samechk->date;
                            $chkreport[$count]->timespent = getduration($chkreport[$count]->endtime, $chkreport[$count]->starttime);
                            break;
                        }
                    }
                }
                $count += 1;
            }
        }
    }
    return $chkreport;
}

function displayrep($reports, $objRequest, $customerDetails) {
    $unitno = isset($objRequest->unitno) ? $objRequest->unitno : getunitno_pdf($objRequest->vehicleId, $customerDetails->customerno);
    $vehicle = getunitdetailsfromvehid_pdf($objRequest->vehicleId, $customerDetails->customerno);
    $arrData = array();
    if (isset($reports) && $reports != "") {
        /* ak added, for calculating cumulative distance travelled */
        $post_date = $objRequest->startDate;
        $post_date_time = date("Y-m-d", strtotime($post_date)) . ' ' . $objRequest->startTime . ':00';
        $odo_init = (float) get_odometer_reading($objRequest->vehicleId, $post_date_time, $objRequest->customerNo, $unitno);
        $cumulative_odo = 0;
        /**/
        $firstRecordKey = key($reports);
        $lastChkOutTime = '';
        //prettyPrint($reports);die();
        foreach ($reports as $key => $report) {
            if ($report->cname != "") {
                $objChk = new stdClass();
                $objChk->inTemperature = "N/A";
                $objChk->endTime = "Not Left";
                $objChk->outTemperature = "N/A";
                $objChk->timeSpent = "";
                $objChk->overspeedCount = 0;
                $objChk->eta = 'N/A';
                $objChk->etaStatus = 'N/A';

                $chketa = checkETA($objRequest->customerNo, $report->chkid, $report->starttime);

                $objChk->checkpointName = $report->cname;

                $objChk->startTime = convertDateToFormat($report->starttime, speedConstants::DEFAULT_DATETIME);
                if ($lastChkOutTime != '') {
                    /* Get Overspeed Count  */
                    $STdate = convertDateToFormat($report->starttime, speedConstants::DATE_Ymd);
                    $Shour = convertDateToFormat($report->starttime, speedConstants::TIME_Hi);
                    $EDdate = convertDateToFormat($lastChkOutTime, speedConstants::DATE_Ymd);
                    $Ehour = convertDateToFormat($lastChkOutTime, speedConstants::TIME_Hi);
                    $cnt = (INT) getoverspeedreport($EDdate, $STdate, $vehicle->deviceid, $Ehour, $Shour, $vehicle->overspeed_limit, $customerDetails->customerno);
                    //$userkey = $_SESSION[''];
                    $objChk->overspeedCount = "<a href='javascript:void(0)' title='View Overspeed Report' onclick='getSpeedReport(\"" . $userkey . "\",\"" . $STdate . "\",\"" . $Shour . "\",\"" . $EDdate . "\",\"" . $Ehour . "\",\"" . $vehicle->deviceid . "\",\"" . $vehicle->vehicleno . "\");'>" . $cnt . " (" . $vehicle->overspeed_limit . ")" . "</a>";

                    //$objChk->overspeedCount = ;
                }

                if ($customerDetails->temp_sensors == 1) {
                    $temperature = 0;
                    $userdate = date("Y-m-d", strtotime($report->starttime));
                    $location = RELATIVE_PATH_DOTS . "customer/$objRequest->customerNo/unitno/$unitno/sqlite/$userdate.sqlite";
                    if (file_exists($location)) {
                        $location = "sqlite:" . $location;
                        $temperature = gettemperature_fromsqlite($location, $report->starttime, $vehicle);
                    }
                    $objChk->inTemperature = $temperature . " <sup>0</sup>C";
                }
                if (isset($report->endtime)) {
                    $objChk->endTime = convertDateToFormat($report->endtime, speedConstants::DEFAULT_DATETIME);
                    if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == '1') {
                        $temperature = 0;
                        $userdate = date("Y-m-d", strtotime($report->endtime));
                        $location = RELATIVE_PATH_DOTS . "customer/$objRequest->customerNo/unitno/$unitno/sqlite/$userdate.sqlite";
                        if (file_exists($location)) {
                            $location = "sqlite:" . $location;
                            $temperature = gettemperature_fromsqlite($location, $report->endtime, $vehicle);
                        }
                        $objChk->outTemperature = $temperature . " <sup>0</sup>C";
                    }
                    $objChk->timeSpent = m2h($report->timespent);

                    $lastChkOutTime = $objChk->endTime;
                } else {
                    $lastChkOutTime = '';
                }

                if (isset($chketa)) {
                    $timearrivel = convertDateToFormat($report->starttime, speedConstants::DEFAULT_TIME);
                    foreach ($chketa as $eta) {
                        if ($eta->endtime == "0000-00-00 00:00:00") {
                            if ($report->starttime >= $eta->starttime) {
                                if (strtotime($timearrivel) > strtotime($eta->eta) && $eta->eta != '0000-00-00 00:00:00') {
                                    $diff = round((strtotime($timearrivel) - strtotime($eta->eta)) / 60);
                                    $objChk->eta = $eta->eta;
                                    $objChk->etaStatus = "Delayed By " . m2h($diff);
                                } elseif (strtotime($timearrivel) < strtotime($eta->eta) && $eta->eta != '0000-00-00 00:00:00') {
                                    $diff = round((strtotime($eta->eta) - strtotime($timearrivel)) / 60);
                                    $objChk->eta = convertDateToFormat($eta->eta, speedConstants::DEFAULT_TIME);
                                    $objChk->etaStatus = "Early By : " . m2h($diff);
                                } else {
                                    $diff = round((strtotime($eta->eta) - strtotime($timearrivel)) / 60);
                                }
                            }
                        } elseif ($eta->endtime != "0000-00-00 00:00:00") {
                            if ($report->starttime >= $eta->starttime && $report->starttime <= $eta->endtime) {
                                if (strtotime($timearrivel) > strtotime($eta->eta)) {
                                    $diff = round((strtotime($timearrivel) - strtotime($eta->eta)) / 60);
                                    $objChk->eta = convertDateToFormat($eta->eta, speedConstants::DEFAULT_TIME);
                                    $objChk->etaStatus .= "Delayed By " . m2h($diff);
                                } else {
                                    $diff = round((strtotime($eta->eta) - strtotime($timearrivel)) / 60);
                                    $objChk->eta = convertDateToFormat($eta->eta, speedConstants::DEFAULT_TIME);
                                    $objChk->etaStatus = "Early By : " . m2h($diff);
                                }
                            }
                        }
                    }
                }

                if (isset($report->endtime) && $firstRecordKey != $key) {
                    $cur_odo = get_odometer_reading($objRequest->vehicleId, $report->endtime, $objRequest->customerNo, $unitno);
                    if (isset($cur_odo) && is_numeric($cur_odo)) {
                        $cumulative_odo += round(($cur_odo - $odo_init) / 1000, 2);
                    }

                    $odo_init = $cur_odo;
                } elseif (isset($report->endtime)) {
                    $cur_odo = get_odometer_reading($objRequest->vehicleId, $report->endtime, $objRequest->customerNo, $unitno);
                    $odo_init = $cur_odo;
                }
                $objChk->cumulativeDistance = $cumulative_odo;
                $arrData[] = $objChk;
            }
        }
    }
    return $arrData;
}

function getduration($EndTime, $StartTime) {
    $idleduration = strtotime($EndTime) - strtotime($StartTime);
    $years = floor($idleduration / (365 * 60 * 60 * 24));
    $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
    $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
    $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
    return $hours * 60 + $minutes;
}

function m2h($mins) {
    if ($mins < 0) {
        $min = Abs($mins);
    } else {
        $min = $mins;
    }
    $H = Floor($min / 60);
    $M = ($min - ($H * 60)) / 100;
    $hours = $H + $M;
    if ($mins < 0) {
        $hours = $hours * (-1);
    }
    $expl = explode(".", $hours);
    $H = $expl[0];
    if (empty($expl[1])) {
        $expl[1] = 00;
    }
    $M = $expl[1];
    if (strlen($M) < 2) {
        $M = $M . 0;
    }
    $hours = $H . ":" . $M;
    return $hours;
}

function checkETA($customerno, $checkpointid, $starttime) {
    $chk = new CheckpointManager($customerno);
    $checkmodify = $chk->checkmodifyETA($checkpointid, $starttime);
    return $checkmodify;
}

function getcheckpoints_routewise($vehicleid) {
    $chk = new CheckpointManager($_SESSION['customerno']);
    $checkpointids = $chk->getcheckpointids($vehicleid);
    $checkpointdetails = $chk->getcheckpoint_by_routewise($vehicleid);
    return $checkpointdetails;
}

function getCheckpointReport($objRequest) {
    $arrCheckpointData = array();
    $tableHeader = '';
    $dataTableHeader = '';
    $tableRows = '';
    $conditionalHeader = '';
    $totalCumulativeDistance = 0;
    $totaldays = gendays_cmn($objRequest->startDate, $objRequest->endDate);
    $objCustomerManager = new CustomerManager();
    $customer_details = $objCustomerManager->getcustomerdetail_byid($objRequest->customerNo);
    $title = 'Checkpoint Report';
    $subTitle = array(
        "Vehicle No: {$objRequest->vehicleNo}",
        "Start Date: {$objRequest->startDateTime}",
        "End Date: {$objRequest->endDateTime}",
        "Checkpoint Type: {$objRequest->chktypename}"
    );
    $arrCheckpointData['tableRows'] = "<tr ><td colspan='100%' style='text-align:center;'>File Not Exists</td></tr>";
    $location = "../../customer/" . $objRequest->customerNo . "/reports/chkreport.sqlite";
    if (file_exists($location)) {
        $objRequest->location = $location;
        $checkpointData = getCheckpointReportData($objRequest, $customer_details);
        if (isset($checkpointData)) {
            foreach ($checkpointData as $data) {
                //print_r($data);
                $tableRows .= "<tr>";
                $tableRows .= "<td>" . $data->checkpointName . "</td>";
                $tableRows .= "<td>" . $data->startTime . "</td>";
                if ($customer_details->temp_sensors == 1) {
                    $tableRows .= "<td>" . $data->inTemperature . "</td>";
                }
                $tableRows .= "<td>" . $data->endTime . "</td>";
                if ($customer_details->temp_sensors == 1) {
                    $tableRows .= "<td>" . $data->outTemperature . "</td>";
                }
                $tableRows .= "<td>" . $data->timeSpent . "</td>";
                $tableRows .= "<td>" . $data->eta . "</td>";
                $tableRows .= "<td>" . $data->etaStatus . "</td>";
                $tableRows .= "<td>" . $data->cumulativeDistance . "</td>";
                $tableRows .= "<td>" . $data->overspeedCount . "</td>";
                $tableRows .= "</tr>";
                $totalCumulativeDistance = $data->cumulativeDistance;
            }
            $arrCheckpointData['tableRows'] = $tableRows;
        } else {
            $arrCheckpointData['tableRows'] = "<tr ><td colspan='100%' style='text-align:center;'   >Data Not Available</td></tr>";
        }
    }
    $styleset2 = 'style="width:28%; text-align:left;"';
    $middlecolumn = '<div class="newTableSubHeader" ' . $styleset2 . '> <span style="text-align:center;"> <u>Cumulative Distance</u> </span>
                : ' . $totalCumulativeDistance . ' Km<br/> </div>';
    if ($objRequest->reportType == speedConstants::REPORT_PDF) {
        $tableHeader .= pdf_header($title, $subTitle, $customer_details);
    } elseif ($objRequest->reportType == speedConstants::REPORT_XLS) {
        $tableHeader .= excel_header($title, $subTitle, $customer_details);
    } else {
        $tableHeader .= table_header($title, $subTitle, null, FALSE, $middlecolumn);
    }
    $dataTableHeader .= processTableHeader($customer_details);
    $arrCheckpointData['tableHeader'] = $tableHeader;
    $arrCheckpointData['dataTableHeader'] = $dataTableHeader;
    return $arrCheckpointData;
}

function getCheckpointReportData($objRequest, $customerDetails) {
    $reportData = null;
    $vehiclemanager = new VehicleManager($objRequest->customerNo);
    $vehicle = $vehiclemanager->get_vehicle_details($objRequest->vehicleId);
    $report = pullreport($objRequest->startDate, $objRequest->endDate, $objRequest->startTime, $objRequest->endTime, $objRequest->vehicleId, $objRequest->customerNo, $objRequest->chkPtId);
    if (isset($report)) {
        foreach ($report as $thisreport) {
            $thisreport->vehicleno = $vehicle->vehicleno;
            //prettyPrint($objRequest->checkpointDetails);
            if (isset($objRequest->checkpointDetails)) {
                foreach ($objRequest->checkpointDetails as $thischeckpoint) {
                    if ($thisreport->chkid == $thischeckpoint->checkpointid) {
                        $thisreport->cname = $thischeckpoint->cname;
                    }
                }
            }
        }
    }
    if (isset($report) && !empty($report)) {
        $chkReport = processchkrep($report);
        $reportData = displayrep($chkReport, $objRequest, $customerDetails);
    }
    return $reportData;
}

function processTableHeader($customerDetails) {
    $tableHeader = "";
    $tableHeader .= "<tr>";
    $tableHeader .= "<th>Checkpoint Name</th>";
    $tableHeader .= "<th>In Time</th>";
    if ($customerDetails->temp_sensors == 1) {
        $tableHeader .= "<th>In Temperature</th>";
    }
    $tableHeader .= "<th>Out Time</th>";
    if ($customerDetails->temp_sensors == 1) {
        $tableHeader .= "<th>Out Temperature</th>";
    }
    $tableHeader .= "<th>Time Spent [Hours:Minutes]</th>";
    $tableHeader .= "<th>ETA</th>";
    $tableHeader .= "<th>Status(HH:MM)</th>";
    $tableHeader .= "<th>Cumulative Distance [KM]</th>";
    $tableHeader .= "<th>Overspeed Count</th>";
    $tableHeader .= "</tr>";
    return $tableHeader;
}

function getVehilceInOutReport($objRequest) {
    $arrCheckpointData = array();
    $tableHeader = '';
    $dataTableHeader = '';
    $tableRows = '';
    $conditionalHeader = '';
    $totalCumulativeDistance = 0;
    $totaldays = gendays_cmn($objRequest->startDate, $objRequest->endDate);
    $objCustomerManager = new CustomerManager();
    $customer_details = $objCustomerManager->getcustomerdetail_byid($objRequest->customerNo);
    $title = 'Vehicle In Out Report';
    $subTitle = array(
        "Checkpoint Name: {$objRequest->checkpointName}",
        "Start Date: {$objRequest->startDateTime}",
        "End Date: {$objRequest->endDateTime}"
    );
    $arrCheckpointData['tableRows'] = "<tr ><td colspan='100%' style='text-align:center;'>File Not Exists</td></tr>";
    $location = "../../customer/" . $objRequest->customerNo . "/reports/vehiclechkreport.sqlite";
    if (file_exists($location)) {
        $objRequest->location = $location;
        $checkpointData = getVehilceInOutReportData($objRequest, $customer_details);
        //print_r($checkpointData);die();
        if (isset($checkpointData)) {
            $count = 1;
            foreach ($checkpointData as $data) {
                $tableRows .= "<tr>";
                $tableRows .= "<td>" . $count . "</td>";

                $tableRows .= "<td>" . $data->checkpointName . "</td>";
                $tableRows .= "<td>" . $data->vehicleNo . "</td>";
                $tableRows .= "<td>" . $data->starttime . "</td>";
                $tableRows .= "<td>" . $data->endtime . "</td>";
                $tableRows .= "<td>" . m2h($data->timespent) . "</td>";
                $tableRows .= "</tr>";
                $count++;
            }
            $arrCheckpointData['tableRows'] = $tableRows;
        } else {
            $arrCheckpointData['tableRows'] = "<tr ><td colspan='100%' style='text-align:center;'   >Data Not Available</td></tr>";
        }
    }

    $styleset2 = 'style="width:28%; text-align:left;"';
    $middlecolumn = '';
    if ($objRequest->reportType == speedConstants::REPORT_PDF) {
        $tableHeader .= pdf_header($title, $subTitle, $customer_details);
    } elseif ($objRequest->reportType == speedConstants::REPORT_XLS) {
        $tableHeader .= excel_header($title, $subTitle, $customer_details);
    } else {
        $tableHeader .= table_header($title, $subTitle, null, FALSE, $middlecolumn);
    }

    $dataTableHeader .= processVehicleInOutTableHeader($customer_details);
    $arrCheckpointData['tableHeader'] = $tableHeader;
    $arrCheckpointData['dataTableHeader'] = $dataTableHeader;
    //print_r($arrCheckpointData);die();
    return $arrCheckpointData;
}

function pullVehicleInOutreport($objRequest) {
    $path = "sqlite:" . RELATIVE_PATH_DOTS . "customer/$objRequest->customerNo/reports/vehiclechkreport.sqlite";
    $arrChkDetails = array();
    if (isset($objRequest->vehicles) && !empty($objRequest->vehicles)) {
        foreach ($objRequest->vehicles as $vehicle) {
            $vehicleid = $vehicle->vehicleid;
            $vehicleno = $vehicle->vehicleno;
            $arrChk = getVehicleChkRecords($objRequest, $vehicleid, $vehicleno, $path);
            if (isset($arrChk) && !empty($arrChk)) {
                $arrChkDetails = array_merge($arrChkDetails, $arrChk);
            }
        }
    }
    return $arrChkDetails;
}

function getVehicleChkRecords($objRequest, $vehicleId, $vehicleNo, $path) {
    $CHKMS = array();
    $Query = "select * from C" . $objRequest->checkpointId . " WHERE vehicleid = $vehicleId AND  date BETWEEN '$objRequest->startDateTime' AND '$objRequest->endDateTime' ";
    //echo $path;
    try {
        $db = new PDO($path);
        $result = $db->query($Query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                //print_r($row);
                $CHKM = new stdClass();
                $CHKM->checkpointId = $objRequest->checkpointId;
                $CHKM->checkpointName = $objRequest->checkpointName;
                $CHKM->vehicleId = $row["vehicleid"];
                $CHKM->vehicleNo = $vehicleNo;
                $CHKM->status = $row["status"];
                $CHKM->date = $row["date"];
                $CHKMS[] = $CHKM;
            }
        }
    } catch (PDOException $e) {
        $CHKMS = 0;
    }

    return $CHKMS;
}

function processchkrep1($reports) {
    $count = 0;
    $chkreport = array();
    if (isset($reports) && $reports != "") {
        foreach ($reports as $report) {
            if ($report->status == 0) {
                $chkreport[$count] = new stdClass;
                $chkreport[$count]->checkpointId = retval_issetor($report->checkpointId);
                $chkreport[$count]->checkpointName = retval_issetor($report->checkpointName);
                $chkreport[$count]->vehicleNo = retval_issetor($report->vehicleNo);
                $chkreport[$count]->vehicleNo = retval_issetor($report->vehicleNo);
                $chkreport[$count]->vehicleId = $report->vehicleId;
                $chkreport[$count]->starttime = $report->date;
                foreach ($reports as $samechk) {
                    if (isset($samechk->vehicleNo) && $chkreport[$count]->vehicleNo == $samechk->vehicleNo && $samechk->status == 1) {
                        if (strtotime($chkreport[$count]->starttime) < strtotime($samechk->date)) {
                            $chkreport[$count]->endtime = $samechk->date;
                            $chkreport[$count]->timespent = getduration($chkreport[$count]->endtime, $chkreport[$count]->starttime);
                            break;
                        }
                    }
                }
                $count += 1;
            }
        }
    }
    return $chkreport;
}

function getVehilceInOutReportData($objRequest, $customerDetails) {
    $reportData = null;
    $report = pullVehicleInOutreport($objRequest);
    //print_r($report);die();
    if (isset($report) && !empty($report)) {
        $reportData = processchkrep1($report);
    }
    return $reportData;
}

function processVehicleInOutTableHeader($customerDetails) {
    $tableHeader = "";
    $tableHeader .= "<tr>";
    $tableHeader .= "<th>Sr.No</th>";
    $tableHeader .= "<th>Checkpoint Name</th>";
    $tableHeader .= "<th>Vehicle No</th>";
    $tableHeader .= "<th>In Time</th>";
    $tableHeader .= "<th>Out Time</th>";
    $tableHeader .= "<th>Time Spent [Hours:Minutes]</th>";
    $tableHeader .= "</tr>";
    return $tableHeader;
}

function getoverspeedreport($STdate, $EDdate, $deviceid, $Shour, $Ehour, $overspeed_limit, $customerno) {
    $overspeedCount = 0;
    $totaldays = gendays($STdate, $EDdate);
    $unitno = getunitnopdf($customerno, $deviceid);
    $count = count($totaldays);
    $endelement = end($totaldays);
    $reports_Days = array_values($totaldays);
    $firstelement = array_shift($reports_Days);
    //$overspeed_limit = 10;
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (!file_exists($location)) {
                continue;
            }

            $location = "sqlite:" . $location;
            if ($count > 1 && $userdate != $endelement && $userdate == $firstelement) {
                $overspeedCount .= getOverSpeedCount($location, $deviceid, $overspeed_limit, $Shour, Null, $userdate, $overspeed_limit);
            } else if ($count > 1 && $userdate == $endelement) {
                $overspeedCount .= getOverSpeedCount($location, $deviceid, $overspeed_limit, Null, $Ehour, $userdate, $overspeed_limit);
            } else if ($count == 1) {
                $overspeedCount .= getOverSpeedCount($location, $deviceid, $overspeed_limit, $Shour, $Ehour, $userdate, $overspeed_limit);
            } else {
                $overspeedCount .= getOverSpeedCount($location, $deviceid, $overspeed_limit, Null, Null, $userdate, $overspeed_limit);
            }
        }
    }

    //echo $overspeedCount;

    return $overspeedCount;
}

function getOverSpeedCount($location, $deviceid, $overspeed_limit, $Shour, $Ehour, $userdate, $overpseedLimit) {
    $overspeedCount = 0;

    $query = "SELECT vehiclehistory.curspeed
              from devicehistory INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.deviceid= $deviceid  AND devicehistory.status!='F'";
    if ($Shour != Null) {
        $query .= " AND devicehistory.lastupdated > '$userdate $Shour'";
    }
    if ($Ehour != Null) {
        $query .= " AND devicehistory.lastupdated < '$userdate $Ehour'";
    }
    $query .= "  ORDER BY devicehistory.lastupdated ASC";
    try
    {
        $database = new PDO($location);
        $result = $database->query($query);
        $speedCntFlag = 0;
        foreach ($result as $row) {
            if ($speedCntFlag == 0 && $row['curspeed'] > $overspeed_limit) {
                $speedCntFlag = 1;
                $overspeedCount += 1;
            } elseif ($speedCntFlag == 1 && $row['curspeed'] < $overspeed_limit) {
                $speedCntFlag = 0;
            }
        }
    } catch (PDOException $e) {
        die($e);
    }

    return $overspeedCount;
}

function gendays($STdate, $EDdate) {
    $TOTALDAYS = Array();

    $STdate = date("Y-m-d", strtotime($STdate));
    $EDdate = date("Y-m-d", strtotime($EDdate));
    while (strtotime($STdate) <= strtotime($EDdate)) {
        $TOTALDAYS[] = $STdate;
        $STdate = date("Y-m-d", strtotime($STdate . ' + 1 day'));
    }
    return $TOTALDAYS;
}

function getunitnopdf($customerno, $deviceid) {
    $um = new UnitManager($customerno);
    $unitno = $um->getuidfromdeviceid($deviceid);
    return $unitno;
}

?>
