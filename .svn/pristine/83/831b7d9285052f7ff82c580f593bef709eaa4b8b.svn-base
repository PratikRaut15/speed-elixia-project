<?php
if (!isset($Mpath)) {
    $Mpath = '';
}
require_once '../../config.inc.php';
include_once $Mpath . '../../lib/bo/UserManager.php';
include_once $Mpath . '../../lib/bo/DeviceManager.php';
include_once $Mpath . '../../lib/bo/GeoCoder.php';
require_once $Mpath . '../../lib/system/Log.php';
require_once $Mpath . '../../lib/system/Sanitise.php';
require_once $Mpath . '../../lib/system/DatabaseManager.php';
require_once $Mpath . 'class/TripsManager.php';
require_once $Mpath . '../../lib/comman_function/reports_func.php';
//require_once $Mpath . '../../modules/reports/reports_route_fuctions.php';
if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
}
function getgroups() {
    $GroupManager = new GroupManager($_SESSION['customerno']);
    $groups = $GroupManager->getallgroups();
    return $groups;
}

function getusers() {
    $usermanager = new UserManager();
    $users = $usermanager->getusersforcustomer($_SESSION['customerno']);
    return $users;
}

function gettripreportdata($sdate = null, $edate = null) {
    $closedtriprecord = array();
    $mob = new Trips($_SESSION["customerno"], $_SESSION['userid']);
    $triprecordArray = $mob->get_closed_triprecord($sdate, $edate);
    if ($triprecordArray) {
        foreach ($triprecordArray as $row) {
            $closearr['tripid'] = $row['tripid'];
            $closearr['vehiclenos'] = $row['vehicleno'];
            $closearr['tripstatus'] = $row['tripstatus'];
            $deviceid = getDeviceid($row['vehicleno']);
            $tripid = $row['tripid'];
            $SDate = date('d-m-Y', strtotime($row['startdate']));
            $STime = date('H:i', strtotime($row['startdate']));
            $EDate = date('d-m-Y', strtotime($row['statusdate']));
            $ETime = date('H:i', strtotime($row['statusdate']));
            $interval = 30;
            $vehicleno = $row['vehicleno'];
            if ($_SESSION['customerno'] == '447') {
                $closearr['lrCreationTime'] = $row['lrCreationTime'];
                $closearr['yardCheckout'] = $row['yardCheckout'];
                $closearr['lrDelayTime'] = $row['lrDelayTime'];
                $closearr['yardCheckin'] = $row['yardCheckin'];
                $closearr['yardDetention'] = $row['yardDetention'];
                $closearr['emptyReturnDeviation'] = $row['emptyReturnDeviation'];
            } else {
                $closearr['routename'] = $row['routename'];
                $closearr['budgetedkms'] = $row['budgetedkms'];
                $closearr['budgetedhrs'] = $row['budgetedhrs'];
                $closearr['consignorname'] = $row['consignorname'];
                $closearr['consigneename'] = $row['consigneename'];
                $closearr['billingparty'] = $row['billingparty'];
                $closearr['mintemp'] = $row['mintemp'];
                $closearr['maxtemp'] = $row['maxtemp'];
                $closearr['actualkms'] = $row['actualkms'];
                $closearr['actualhrs'] = $row['actualhrs'];
                $closearr['drivername'] = $row['drivername'];
                $closearr['drivermobile1'] = $row['drivermobile1'];
                $closearr['drivermobile2'] = $row['drivermobile2'];
                $closearr['gensethrs'] = '';
            }
            $closearr['startdate'] = date('d-m-Y ', strtotime($row['startdate']));
            $closearr['statusdate'] = $row['statusdate'];
            $closearr['remark'] = $row['remark'];
            $closearr['updated_on'] = $row['updated_on'];
            $closearr['realname'] = $row['realname'];
            $closearr['triplogno'] = "<a href='javascript:void(0)' onclick='getLocationReport(\"" . $tripid . "\",\"" . $deviceid . "\",\"" . $SDate . "\",\"" . $STime . "\",\"" . $EDate . "\",\"" . $ETime . "\",\"" . $interval . "\",\"" . $vehicleno . "\");'>" . $row['triplogno'] . "</a>";
            $closearr['tripLogVal'] = BASE_PATH . "/modules/reports/reports.php?id=16&tripid=" . $row['tripid'] . "&deviceid=" . $deviceid . "&sdate=" . $SDate . "&stime=" . $STime . "&edate=" . $EDate . "&etime=" . $ETime . "&interval=" . $interval . "&vehicleno=" . $vehicleno . "";
            /*$closearr['historyview'] = "<img src=../images/history.png' style='cursor:pointer;' onclick = 'histview(" . $row['tripid'] . ");'  > ";*/
            $closedtriprecord[] = $closearr;
        }
        return $closedtriprecord;
    } //end if
}

function getmappedtripusers($tripid) {
    $groupmanager = new GroupManager($_SESSION['customerno']);
    $groups = $groupmanager->getusersbytripid($tripid);
    return $groups;
}

function tripstatusedit($customerno, $userid, $tripstatusid) {
    $mob = new Trips($customerno, $userid);
    $resulteditdata = $mob->get_tripstatusedit($tripstatusid);
    $edittripstatusdata = array();
    if (!empty($resulteditdata)) {
        foreach ($resulteditdata as $thisdata) {
            $edittripstatusdata[] = array(
                "statusid" => $thisdata->tripstatusid,
                "tripstatus" => $thisdata->tripstatus,
                "customerno" => $thisdata->customerno
            );
        }
        return $edittripstatusdata;
    }
    return null;
}

function consignee_edit($customerno, $userid, $consid) {
    $mob = new Trips($customerno, $userid);
    $resulteditdata = $mob->get_consigneeedit($consid);
    $consdata = array();
    if (!empty($resulteditdata)) {
        foreach ($resulteditdata as $thisdata) {
            $consdata[] = array(
                "consid" => $thisdata->consid,
                "consigneename" => $thisdata->consigneename,
                "phone" => $thisdata->phone,
                "email" => $thisdata->email
            );
        }
        return $consdata;
    }
    return null;
}

function consignor_edit($customerno, $userid, $consid) {
    $mob = new Trips($customerno, $userid);
    $resulteditdata = $mob->get_consignoredit($consid);
    $consdata = array();
    if (!empty($resulteditdata)) {
        foreach ($resulteditdata as $thisdata) {
            $consdata[] = array(
                "consrid" => $thisdata->consrid,
                "consignorname" => $thisdata->consignorname,
                "phone" => $thisdata->phone,
                "email" => $thisdata->email
            );
        }
        return $consdata;
    }
    return null;
}

////get all data
function get_tripstatus($customerno, $userid) {
    $mob = new Trips($customerno, $userid);
    //$resulteditdata = $mob->get_tripstatusedit($tripstatusid);
    $resulteditdata = $mob->get_tripstatus();
    $edittripstatusdata = array();
    if (!empty($resulteditdata)) {
        foreach ($resulteditdata as $thisdata) {
            $edittripstatusdata[] = array(
                "statusid" => $thisdata->tripstatusid,
                "tripstatus" => $thisdata->tripstatus,
                "customerno" => $thisdata->customerno
            );
        }
        return $edittripstatusdata;
    }
    return null;
}

function get_consignee($customerno, $userid) {
    $mob = new Trips($customerno, $userid);
    $resulteditdata = $mob->getConsignee();
    $consdata = array();
    if (!empty($resulteditdata)) {
        foreach ($resulteditdata as $thisdata) {
            $consdata[] = array(
                "consid" => $thisdata->consid,
                "consigneename" => $thisdata->consigneename,
                "phone" => $thisdata->phone,
                "email" => $thisdata->email
            );
        }
        return $consdata;
    }
    return null;
}

function get_consignor($customerno, $userid) {
    $mob = new Trips($customerno, $userid);
    $resulteditdata = $mob->getConsignor();
    $consdata = array();
    if (!empty($resulteditdata)) {
        foreach ($resulteditdata as $thisdata) {
            $consdata[] = array(
                "consrid" => $thisdata->consrid,
                "consignorname" => $thisdata->consignorname,
                "phone" => $thisdata->phone,
                "email" => $thisdata->email
            );
        }
        return $consdata;
    }
    return null;
}

function tripdetailsedit($customerno, $userid, $tripid) {
    $date = date('Y-m-d');
    $today = date('Y-m-d H:i:s');
    $mob = new Trips($customerno, $userid);
    $resulteditdata = $mob->gettripdetailsedit($tripid);
    $tripdata = array();
    if (!empty($resulteditdata)) {
        foreach ($resulteditdata as $thisdata) {
            $firstodometer = 0;
            $lastodometer = 0;
            $lastodometer = $thisdata->vehicleodometer; // last odometer
            $firstodometer = $thisdata->loadingodometer; // first odometer
            if ($lastodometer < $firstodometer) {
                $lastodometermax = $mob->GetOdometerMax($date, $thisdata->unitno);
                $lastodometer = $lastodometermax + $lastodometer;
            }
            $totaldistance = 0;
            if (is_numeric($lastodometer) && is_numeric($firstodometer)) {
                $totaldistance = $lastodometer - $firstodometer;
            }

            $tripstartdata = $mob->closedtripdetails_start($tripid);
            $tripstart_date = $tripstartdata[0]['tripstart_date'];
            $actualhrs = round((strtotime($today) - strtotime($tripstart_date)) / (60 * 60));
            if ($totaldistance != 0) {
                $res = $totaldistance / 1000;
            } else {
                $res = 0;
            }
            if ($customerno == '447') {
                $estimated_time = $thisdata->yardETA;
            } else {
                ////////////////Estimated Time calculate///////////////////////
                $estimated_time = 0;
                //$actualhrs = $res * $thisdata->budgetedhrs / $thisdata->budgetedkms;
                if ($thisdata->budgetedhrs > $actualhrs) {
                    $estimated_time = $thisdata->budgetedhrs - $actualhrs;
                } else {
                    $estimated_time = $actualhrs;
                }
            }
            ///////////////////////////////////////////////////////////////
            $tripdata[] = array(
                "vehicleno" => $thisdata->vehicleno,
                "vehicleid" => $thisdata->vehicleid,
                "groupid" => $thisdata->groupid,
                "actuallodometer" => $lastodometer,
                "previousodometer" => $firstodometer,
                "actualkms" => $res,
                "actualhrs" => $actualhrs,
                "estimatedtime" => $estimated_time,
                "triplogno" => $thisdata->triplogno,
                "tripstatus" => $thisdata->tripstatus,
                "tripstatusid" => $thisdata->tripstatusid,
                "statusdate" => $thisdata->statusdate,
                "routename" => $thisdata->routename,
                "budgetedkms" => $thisdata->budgetedkms,
                "budgetedhrs" => $thisdata->budgetedhrs,
                "consignor" => $thisdata->consignor,
                "consignee" => $thisdata->consignee,
                "consignorid" => $thisdata->consignorid,
                "consigneeid" => $thisdata->consigneeid,
                "billingparty" => $thisdata->billingparty,
                "mintemp" => $thisdata->mintemp,
                "maxtemp" => $thisdata->maxtemp,
                "drivername" => $thisdata->drivername,
                "drivermobile1" => $thisdata->drivermobile1,
                "drivermobile2" => $thisdata->drivermobile2,
                "tripid" => $thisdata->tripid,
                "remark" => $thisdata->remark,
                "perdaykm" => $thisdata->perdaykm,
                "etarrivaldate" => $thisdata->etarrivaldate,
                "materialtype" => $thisdata->materialtype,
                "bags" => $thisdata->bags,
                "quantity" => $thisdata->quantity,
                "challanNo" => $thisdata->chlNo,
                "cname" => $thisdata->cname,
                "truckNo" => $thisdata->truckNo,
                "stockCode" => $thisdata->stockCode,
                "yardToCheckpointDistance" => $thisdata->yardToCheckpointDistance,
                "YardToYardDistance" => $thisdata->YardToYardDistance
            );
        }
        return $tripdata;
    }
    return null;
}

if (!function_exists('getlocation')) {
    function getlocation($lat, $long, $customerno) {
        $key = $lat . $long;
        if (!isset($GLOBALS[$key])) {
            $GeoCoder_Obj = new GeoCoder($customerno);
            $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
            $output = $address;
            $GLOBALS[$key] = $address;
        } else {
            $output = $GLOBALS[$key];
        }
        return $output;
    }
}
function gettriphistory_status($customerno, $userid, $tripid, $isTripEnd = 0) {
    $mob = new Trips($customerno, $userid);
    $resulteditdata = $mob->gettripdetailshistory($tripid);
    $tripdata = array();
    if (!empty($resulteditdata)) {
        foreach ($resulteditdata as $thisdata) {
            $location = "";
            if ($thisdata->devicelat != "" && $thisdata->devicelong != "") {
                $location = getlocation($thisdata->devicelat, $thisdata->devicelong, $customerno);
            }
            $getTripLrDetails = getTripLrDetails($customerno, $userid, $tripid, $isTripEnd);
            if (isset($getTripLrDetails[0]['varChitthiCreation']) && isset($getTripLrDetails[0]['varLrCreation'])) {
                $lrdelay = getTimeDiff($getTripLrDetails[0]['varLrCreation'], $getTripLrDetails[0]['varChitthiCreation']);
            } else {
                $lrdelay = 0;
            }
            $tripdata[] = array(
                "vehicleno" => $thisdata->vehicleno,
                "triplogno" => $thisdata->triplogno,
                "location" => $location,
                "tripstatus" => $thisdata->tripstatus,
                "tripstatusid" => $thisdata->tripstatusid,
                "statusdate" => $thisdata->statusdate,
                "routename" => $thisdata->routename,
                "budgetedkms" => $thisdata->budgetedkms,
                "budgetedhrs" => $thisdata->budgetedhrs,
                "consignor" => $thisdata->consignor,
                "consignee" => $thisdata->consignee,
                "billingparty" => $thisdata->billingparty,
                "mintemp" => $thisdata->mintemp,
                "maxtemp" => $thisdata->maxtemp,
                "drivername" => $thisdata->drivername,
                "drivermobile1" => $thisdata->drivermobile1,
                "drivermobile2" => $thisdata->drivermobile2,
                "tripid" => $thisdata->tripid,
                "entrytime" => $thisdata->entrytime,
                "lrdelay" => $lrdelay,
                "varLrCreation" => $getTripLrDetails[0]['varLrCreation'],
                "varYardCheckout" => $getTripLrDetails[0]['varYardCheckout'],
                "varYardCheckin" => $getTripLrDetails[0]['varYardCheckin'],
                "varYardDetention" => $getTripLrDetails[0]['varYardDetention'],
                "varEmptyReturnDeviation" => $getTripLrDetails[0]['varEmptyReturnDeviation']
            );
        }
        return $tripdata;
    }
    return null;
}

function getTripDetailsReport($reportType, $customerno, $userid) {
    $customerManager = new CustomerManager($customerno);
    $customer = $customerManager->getcustomerdetail_byid($customerno);
    $type = '';
    $headerData = getTripDetailsReportHeader($reportType);
    $reportData = getTripDetailsReportData($reportType, $customerno, $userid);
    if ($reportType == 'inTransitStoppage') {
        $reportData = getStoppageDetails($customerno, $userid);
    }
    $title = $headerData['title'];
    $subTitle = array(
        "Date: " . date('Y-m-d')
    );
    $columns = $headerData['columns'];
    $finalReport = '';
    if ($type == 'pdf') {
        $finalReport .= pdf_header($title, $subTitle, $customer);
    } elseif ($type == 'xls') {
        $finalReport .= excel_header($title, $subTitle, $customer);
    } else {
        $finalReport .= table_header($title, $subTitle, $columns);
    }
    $finalReport .= processTripDetailReportData($reportType, $reportData);
    if (isset($reportData) && empty($reportData)) {
        $finalReport .= "<tr><td colspan='100%' style='text-align:center;'> Data Not Available </td></tr></tbody></table>";
    } else {
        $finalReport .= "</tbody></table>";
    }
    return $finalReport;
}

function getStoppageDetails($customerno, $userid) {
    $arrStoppageDetails = array();
    $objTripManager = new Trips($customerno, $userid);
    $request = new stdClass();
    $request->customerno = $customerno;
    $request->currentDate = date('Y-m-d');
    $inTransitVehicles = $objTripManager->getInTransitVehicleDetails($request);
    if (isset($inTransitVehicles) && !empty($inTransitVehicles)) {
        foreach ($inTransitVehicles as $vehicle) {
            $userdate = date('Y-m-d');
            $vehicleno = $vehicle['vehicleno'];
            $unitno = $vehicle['unitno'];
            $deviceid = $vehicle['deviceid'];
            $holdtime = 30;
            $Shour = '00:00';
            $Ehour = '23:59';
            $k = 0;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getstoppage_fromsqlite_trips($location, $deviceid, $holdtime, $Shour, $Ehour, $userdate);
                if (isset($data) && is_array($data) && !empty($data)) {
                    $objData = new stdClass();
                    $objData->triplogno = $vehicle['triplogno'];
                    $objData->vehicleno = $vehicle['vehicleno'];
                    $objData->reportLink = "<a href='javascript:void(0)' title='View Stoppage Report' onclick='getStoppageReport(\"" . $userdate . "\",\"" . $Ehour . "\",\"" . $userdate . "\",\"" . $Shour . "\",\"" . $deviceid . "\",\"" . $holdtime . "\",\"" . $vehicleno . "\");'>ViewReport</a>";
                    $arrStoppageDetails[] = $objData;
                }
            }
        }
    }
    return $arrStoppageDetails;
}

function getTripDetailsReportHeader($reportType) {
    $arrHeaders = array();
    $title = '';
    $columns = array(
    );
    if ($reportType == "volumeDispatched") {
        $title = "Total Volume Distached Today";
        $columns = array(
            'Sr.No',
            'Trip Log No',
            'Vehicle No',
            'Chitthi No',
            'Chitthi Creation Time',
            'No Of Bags'
        );
    } elseif ($reportType == "lrDelayed") {
        $title = "LR Delay Details";
        $columns = array(
            'Sr.No',
            'Trip Log No',
            'Vehicle No',
            'Chitthi Creation Time',
            'LR Creation Time',
            'Delay Status'
        );
    } elseif ($reportType == "yardDetentionDeviation") {
        $title = "Yard Detention Deviation Details";
        $columns = array(
            'Sr.No',
            'Trip Log No',
            'Vehicle No',
            'Yard Name',
            'Chitthi Creation Time',
            'Yard Checkout Time',
            'Delay Status'
        );
    } elseif ($reportType == "emptyReturnDeviation") {
        $title = "Empty Return Deviation Details";
        $columns = array(
            'Sr.No',
            'Trip Log No',
            'Vehicle No'
        );
    } elseif ($reportType == "inTransitStoppage") {
        $title = "In Transit Stoppage Details";
        $columns = array(
            'Sr.No',
            'Trip Log No',
            'Vehicle No',
            'Stoppage Report Link'
        );
    }
    $arrHeaders['title'] = $title;
    $arrHeaders['columns'] = $columns;
    return $arrHeaders;
}

function getTripDetailsReportData($reportType, $customerno, $userid) {
    $reportData = array();
    $objTrip = new stdClass();
    $objTrip->customerno = $customerno;
    $objTrip->reportType = $reportType;
    $objTrip->todaydate = date('Y-m-d');
    $objTripManager = new Trips($customerno, $userid);
    $arrData = $objTripManager->getTripDetailsReportData($objTrip);
    if (isset($arrData) && !empty($arrData)) {
        foreach ($arrData as $data) {
            $reportData[] = (object) $data;
        }
    }
    return $reportData;
}

function processTripDetailReportData($reportType, $reportData) {
    $rowData = '';
    if ($reportType == 'volumeDispatched') {
        $i = 1;
        foreach ($reportData as $row) {
            $rowData .= "<tr>";
            $rowData .= "<td>" . $i . "</td>";
            $rowData .= "<td>" . $row->triplogno . "</td>";
            $rowData .= "<td>" . $row->vehicleno . "</td>";
            $rowData .= "<td>" . $row->chitthiNo . "</td>";
            $rowData .= "<td>" . $row->chittiCreationDate . "</td>";
            $rowData .= "<td>" . $row->bags . "</td>";
            $rowData .= "</tr>";
            $i++;
        }
    } elseif ($reportType == 'lrDelayed') {
        $i = 1;
        foreach ($reportData as $row) {
            $delay = getTimeDiff(date('Y-m-d H:i:s'), $row->chittiCreationDate);
            $rowData .= "<tr>";
            $rowData .= "<td>" . $i . "</td>";
            $rowData .= "<td>" . $row->triplogno . "</td>";
            $rowData .= "<td>" . $row->vehicleno . "</td>";
            $rowData .= "<td>" . $row->chittiCreationDate . "</td>";
            $rowData .= "<td></td>";
            $rowData .= "<td>" . $delay . "</td>";
            $rowData .= "</tr>";
            $i++;
        }
    } elseif ($reportType == 'yardDetentionDeviation') {
        $i = 1;
        foreach ($reportData as $row) {
            $delay = getTimeDiff($row->yardOutTime, $row->chittiCreationDate);
            $rowData .= "<tr>";
            $rowData .= "<td>" . $i . "</td>";
            $rowData .= "<td>" . $row->triplogno . "</td>";
            $rowData .= "<td>" . $row->vehicleno . "</td>";
            $rowData .= "<td>" . $row->yardName . "</td>";
            $rowData .= "<td>" . $row->chittiCreationDate . "</td>";
            $rowData .= "<td>" . $row->yardOutTime . "</td>";
            $rowData .= "<td>" . $delay . "</td>";
            $rowData .= "</tr>";
            $i++;
        }
    } elseif ($reportType == 'emptyReturnDeviation') {
        $i = 1;
        foreach ($reportData as $row) {
            $rowData .= "<tr>";
            $rowData .= "<td>" . $i . "</td>";
            $rowData .= "<td>" . $row->triplogno . "</td>";
            $rowData .= "<td>" . $row->vehicleno . "</td>";
            $rowData .= "</tr>";
            $i++;
        }
    } elseif ($reportType == 'inTransitStoppage') {
        $i = 1;
        foreach ($reportData as $row) {
            $rowData .= "<tr>";
            $rowData .= "<td>" . $i . "</td>";
            $rowData .= "<td>" . $row->triplogno . "</td>";
            $rowData .= "<td>" . $row->vehicleno . "</td>";
            $rowData .= "<td>" . $row->reportLink . "</td>";
            $rowData .= "</tr>";
            $i++;
        }
    }
    //$rowData .= "</tbody></table>";
    return $rowData;
}

function getTimeDiff($EndTime, $StartTime) {
    $timeDiff = 0;
    $idleduration = strtotime($EndTime) - strtotime($StartTime);
    $years = floor($idleduration / (365 * 60 * 60 * 24));
    $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
    $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
    $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
    if ($years >= '1' || $months >= '1') {
        $timeDiff = date('d-m-Y', strtotime($StartTime));
    } elseif ($days > 0) {
        $timeDiff = $days . ' days ' . $hours . ' hrs';
    } elseif ($hours > 0) {
        $timeDiff = $hours . ' hrs and ' . $minutes . ' mins';
    } elseif ($minutes > 0) {
        $timeDiff = $minutes . ' mins';
    } else {
        $seconds = strtotime($EndTime) - strtotime($StartTime);
        $timeDiff = $seconds . ' sec';
    }
    return $timeDiff;
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

function getstoppage_fromsqlite_trips($location, $deviceid, $holdtime, $Shour, $Ehour, $userdate) {
    $devices = array();
    $query = "SELECT devicehistory.lastupdated, vehiclehistory.odometer, vehiclehistory.vehicleno, devicehistory.devicelat, vehiclehistory.vehicleid,
            devicehistory.devicelong, unithistory.unitno
            from devicehistory
            INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
            INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.deviceid= $deviceid AND devicehistory.status!='F' AND devicehistory.devicelat <> '0.000000' AND devicehistory.devicelong <> '0.000000'";
    if ($Shour != null) {
        $query .= " AND devicehistory.lastupdated > '$userdate $Shour'";
    }
    if ($Ehour != null) {
        $query .= " AND devicehistory.lastupdated < '$userdate $Ehour'";
    }
    $query .= "  ORDER BY devicehistory.lastupdated ASC";
    try
    {
        $database = new PDO($location);
        $result = $database->query($query);
        if (isset($result) && $result != "") {
            $lastupdated = "";
            $lastodometer = "";
            $pusharray = 1;
            foreach ($result as $row) {
                if ($lastodometer == "") {
                    $lastodometer = $row["odometer"];
                    $lastupdated = $row['lastupdated'];
                    $device = new stdClass();
                    $device->vehicleid = $row['vehicleid'];
                    $device->vehicleno = $row['vehicleno'];
                    $device->starttime = $row['lastupdated'];
                    $device->deviceid = $row['vehicleid'];
                    $device->customerno = $_SESSION['customerno'];
                    $device->lat = $row['devicelat'];
                    $device->lng = $row['devicelong'];
                }
                /* Condition For Odometer Reset */
                if ($row["odometer"] < $lastodometer) {
                    $max = GetOdometerMax1($row['lastupdated'], $row["unitno"]);
                    $row["odometer"] = $max + $row["odometer"];
                }
                if (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) > $holdtime && $row["odometer"] - $lastodometer < 100 && $pusharray == 1) {
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->deviceid = $row['vehicleid'];
                    $device->customerno = $_SESSION['customerno'];
                    $lastodometer = $row["odometer"];
                    $lastupdated = $row['lastupdated'];
                    $device->lat = $row['devicelat'];
                    $device->lng = $row['devicelong'];
                    $pusharray = 0;
                } else {
                    if ($row["odometer"] - $lastodometer > 25) {
                        if ($pusharray == 0) {
                            $device->endtime = $row['lastupdated'];
                            $devices[] = $device;
                        }
                        $lastodometer = $row["odometer"];
                        $lastupdated = $row['lastupdated'];
                        $device = new stdClass();
                        $device->vehicleid = $row['vehicleid'];
                        $device->vehicleno = $row['vehicleno'];
                        $device->starttime = $row['lastupdated'];
                        $device->deviceid = $row['vehicleid'];
                        $device->customerno = $_SESSION['customerno'];
                        $device->lat = $row['devicelat'];
                        $device->lng = $row['devicelong'];
                        $pusharray = 1;
                    }
                }
            }
            if ($pusharray == 0) {
                $device->endtime = $row['lastupdated'];
                $device->deviceid = $row['vehicleid'];
                $devices[] = $device;
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    return $devices;
}

function getTripLrDetails($customerno, $userid, $tripid, $isTripEnd = 0) {
    $arrDetails = array();
    $obj = new stdClass();
    $obj->tripid = $tripid;
    $obj->customerno = $customerno;
    $objTrip = new Trips($customerno, $userid);
    $arrDetails = $objTrip->getTripLrDetails($obj, $isTripEnd);
    return $arrDetails;
}

function GetOdometerMax1($date, $unitno) {
    $sqlitedate = date('Y-m-d', strtotime($date));
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$sqlitedate.sqlite";
    if ($_SESSION['role_modal'] == 'elixir') {
        //echo $location;
    }
    $ODOMETER = 0;
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $query = "SELECT max(odometer) as odometerm from vehiclehistory where lastupdated < '" . $date . "' limit 1";
        //echo $query."<br/>";
        $result = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
        $ODOMETER = $result[0]['odometerm'];
    }
    return $ODOMETER;
}

function get_closetrip_report_excel($customerno, $sdate, $edate) {
    $mob = new Trips($_SESSION['customerno'], $_SESSION['userid']);
    $datarows = $mob->get_closed_triprecord($sdate, $edate);
    $title = 'Trip Closed Report';
    $subTitle = array(
        "Start Date: $sdate",
        "End Date: $edate"
    );
    $customer_details = null;
    if (!isset($_SESSION['customerno'])) {
        $cm = new CustomerManager($customerno);
        $customer_details = $cm->getcustomerdetail_byid($customerno);
    }
    echo pdf_header($title, $subTitle, $customer_details);
    if (isset($datarows)) {
        $start = 0;
        ?>
        <table  id='search_table_2' style="width:950px; font-size:10px; text-align:center;border-collapse:collapse; border:1px solid #000;">
            <tr style="background-color:#CCCCCC;font-weight:bold;">
                <td style='width:50px; text-align: center;'>Sr.No.</td>
                <td style='width:50px; text-align: center;'>Vehicle No.</td>
                <td style='width:50px;height:auto; text-align: center;'>Triplog No</td>
                <?php
if ($customerno == '447') {?>
                     <td style='width:50px;height:auto; text-align: center;'>LR Creation Time</td>
                    <td style='width:50px;height:auto; text-align: center;'>LR Delayed Time</td>
                    <td style='width:50px;height:auto; text-align: center;'>Yard Checkout Time</td>
                    <td style='width:50px;height:auto; text-align: center;'>Yard Detention Time</td>
                    <td style='width:50px;height:auto; text-align: center;'>Yard Checkin Time</td>
                    <td style='width:50px;height:auto; text-align: center;'>Empty Return Deviation</td>
                <?php } else {?>
                    <td style='width:50px;height:auto; text-align: center;'>Route Name</td>
                    <td style='width:50px;height:auto; text-align: center;'>Budgeted kms</td>
                    <td style='width:50px;height:auto; text-align: center;'>Budgeted Hrs</td>
                    <td style='width:50px;height:auto; text-align: center;'>Consignor</td>
                    <td style='width:50px;height:auto; text-align: center;'>Consignee</td>
                    <td style='width:50px;height:auto; text-align: center;'>Billing Party</td>
                    <td style='width:50px;height:auto; text-align: center;'>Min temp</td>
                    <td style='width:50px;height:auto; text-align: center;'>Max temp</td>
                    <td style='width:50px;height:auto; text-align: center;'>Actual Hrs</td>
                    <td style='width:50px;height:auto; text-align: center;'>Actual kms</td>
                    <td style='width:50px;height:auto; text-align: center;'>Driver Name</td>
                    <td style='width:50px;height:auto; text-align: center;'>Driver Mob.1</td>
                <?php }
        ?>
                <td style='width:50px;height:auto; text-align: center;'>Trip StartDate</td>
                <td style='width:50px;height:auto; text-align: center;'>Trip End Date</td>
                <td style='width:50px;height:auto; text-align: center;'>Remark</td>
            </tr>
            <?php
$i = 1;
        foreach ($datarows as $change) {
            echo "<tr>";
            echo "<td  width='20px' style=' text-align: center;' >" . $i++ . "</td>";
            echo "<td style='height:auto; text-align: center;'>" . $change['vehicleno'] . "</td>";
            echo "<td style='height:auto; text-align: center;'>" . $change['triplogno'] . "</td>";
            if ($customerno == '447') {
                echo "<td style='height:auto; text-align: center;'>" . $change['lrCreationTime'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['lrDelayTime'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['yardCheckout'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['yardDetention'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['yardCheckin'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['emptyReturnDeviation'] . "</td>";
            } else {
                echo "<td style='height:auto; text-align: center;'>" . $change['routename'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['budgetedkms'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['budgetedhrs'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['consignorname'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['consigneename'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['billingparty'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['mintemp'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['maxtemp'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['actualhrs'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['actualkms'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['drivername'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['drivermobile1'] . "</td>";
            }
            echo "<td style='height:auto; text-align: center;'>" . $change['startdate'] . "</td>";
            echo "<td style='height:auto; text-align: center;'>" . $change['statusdate'] . "</td>";
            echo "<td style='height:auto; text-align: center;'>" . $change['remark'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}

function get_closetrip_report_pdf($sdate, $edate, $customerno) {
    $mob = new Trips($_SESSION['customerno'], $_SESSION['userid']);
    $datarows = $mob->get_closed_triprecord($sdate, $edate);
    $title = 'Trip Closed Report';
    $subTitle = array(
        "Start Date: $sdate",
        "End Date: $edate"
    );
    $customer_details = null;
    if (!isset($_SESSION['customerno'])) {
        $cm = new CustomerManager($customerno);
        $customer_details = $cm->getcustomerdetail_byid($customerno);
    }
    echo pdf_header($title, $subTitle, $customer_details);
    if (isset($datarows)) {
        $start = 0;
        ?>
            <table  id='search_table_2' style="width:700px; font-size:55%; text-align:center;border-collapse:collapse; border:1px solid #000;">
                <tr style="background-color:#CCCCCC;font-weight:bold;">
                    <td style='word-wrap:normal; width:15px; text-align: center;'>Sr.<br>No.</td>
                    <td style='word-wrap:normal; width:50px; text-align: center;'>Vehicle<br> No.</td>
                    <td style='word-wrap:normal; width:30px;height:auto; text-align: center;'>Triplog<br> No</td>
                         <?php
if ($customerno == '447') {?>
                     <td style='width:50px;height:auto; text-align: center;'>LR Creation Time</td>
                    <td style='width:50px;height:auto; text-align: center;'>LR Delayed Time</td>
                    <td style='width:50px;height:auto; text-align: center;'>Yard Checkout Time</td>
                    <td style='width:50px;height:auto; text-align: center;'>Yard Checkin Time</td>
                    <td style='width:50px;height:auto; text-align: center;'>Empty Return Deviation</td>
                <?php } else {?>
                    <td style='width:50px;height:auto; text-align: center;'>Route Name</td>
                    <td style='width:50px;height:auto; text-align: center;'>Budgeted kms</td>
                    <td style='width:50px;height:auto; text-align: center;'>Budgeted Hrs</td>
                    <td style='width:50px;height:auto; text-align: center;'>Consignor</td>
                    <td style='width:50px;height:auto; text-align: center;'>Consignee</td>
                    <td style='width:50px;height:auto; text-align: center;'>Billing Party</td>
                    <td style='width:50px;height:auto; text-align: center;'>Min temp</td>
                    <td style='width:50px;height:auto; text-align: center;'>Max temp</td>
                    <td style='width:50px;height:auto; text-align: center;'>Actual Hrs</td>
                    <td style='width:50px;height:auto; text-align: center;'>Actual kms</td>
                    <td style='width:50px;height:auto; text-align: center;'>Driver Name</td>
                    <td style='width:50px;height:auto; text-align: center;'>Driver Mob.1</td>
                <?php }
        ?>
                    <td style='word-wrap:normal; width:50px;height:auto; text-align: center;'>Trip Start<br> Date</td>
                    <td style='word-wrap:normal; width:50px;height:auto; text-align: center;'>Trip End<br> Date</td>
                    <td style='word-wrap:normal; width:50px;height:auto; text-align: center;'>Remark</td>
                </tr>
                <?php
$i = 1;
        foreach ($datarows as $change) {
            echo "<tr>";
            echo "<td  width='20px' style=' text-align: center;' >" . $i++ . "</td>";
            echo "<td style='word-wrap:normal; height:auto; text-align: center;'>" . $change['vehicleno'] . "</td>";
            echo "<td style='word-wrap:normal; height:auto; text-align: center;'>" . $change['triplogno'] . "</td>";
            if ($customerno == '447') {
                echo "<td style='height:auto; text-align: center;'>" . $change['lrCreationTime'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['yardCheckout'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['lrDelayTime'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['yardCheckin'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['yardDetention'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['emptyReturnDeviation'] . "</td>";
            } else {
                echo "<td style='height:auto; text-align: center;'>" . $change['routename'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['budgetedkms'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['budgetedhrs'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['consignorname'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['consigneename'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['billingparty'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['mintemp'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['maxtemp'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['actualhrs'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['actualkms'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['drivername'] . "</td>";
                echo "<td style='height:auto; text-align: center;'>" . $change['drivermobile1'] . "</td>";
            }
            echo "<td style='word-wrap:normal; height:auto; text-align: center;'>" . $change['startdate'] . "</td>";
            echo "<td style='word-wrap:normal; height:auto; text-align: center;'>" . $change['statusdate'] . "</td>";
            echo "<td style='word-wrap:normal; height:auto; text-align: center;'>" . $change['remark'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}

function get_viewtriprecords_list($customerno, $userid) {
    $today = date('Y-m-d H:i:s');
    $EDate = date('d-m-Y', strtotime($today));
    $ETime = date('H:i', strtotime($today));
    $interval = 30;
    $tripdata = array();
    $triparr = array();
    $mob = new Trips($_SESSION['customerno'], $_SESSION['userid']);
    $triprecordArray = $mob->get_viewtriprecords();
    if (isset($triprecordArray)) {
        foreach ($triprecordArray as $row) {
            $deviceid = 0;
            $vehicleno = $row['vehicleno'];
            $deviceid = getDeviceid($vehicleno);
            $SDate = date('d-m-Y', strtotime($row['statusdate']));
            $STime = date('H:i', strtotime($row['statusdate']));
            if (isset($row['startdate']) && $row['startdate'] != '') {
                $startdate = date(speedConstants::DEFAULT_DATETIME, strtotime($row['startdate']));
            } else {
                $startdate = '-';
            }
            $tripid = $row['tripid'];
            $triparr['tripid'] = $row['tripid'];
            $triparr['vehicleno'] = $row['vehicleno'];
            //$triparr['triplogno'] = "<a href='javascript:void(0)' onclick='getLocationReport(\"" . $deviceid . "\",\"" . $row['mintemp'] . "\",\"" . $row['maxtemp'] . "\",\"" . $SDate . "\",\"" . $STime . "\",\"" . $EDate . "\",\"" . $ETime . "\",\"" . $interval . "\",\"" . $vehicleno . "\");'>" . $row['triplogno'] . "</a>";
            $triparr['triplogno'] = "<a href='javascript:void(0)' onclick='getLocationReport(\"" . $tripid . "\",\"" . $deviceid . "\",\"" . $SDate . "\",\"" . $STime . "\",\"" . $EDate . "\",\"" . $ETime . "\",\"" . $interval . "\",\"" . $vehicleno . "\");'>" . $row['triplogno'] . "</a>";
            $triparr['tripstatus'] = $row['tripstatus'];
            $triparr['routename'] = $row['routename'];
            $triparr['budgetedkms'] = $row['budgetedkms'];
            $triparr['budgetedhrs'] = $row['budgetedhrs'];
            $triparr['consignorname'] = $row['consignorname'];
            $triparr['consigneename'] = $row['consigneename'];
            $triparr['billingparty'] = $row['billingparty'];
            $triparr['mintemp'] = $row['mintemp'];
            $triparr['maxtemp'] = $row['maxtemp'];
            $triparr['drivername'] = $row['drivername'];
            $triparr['drivermobile1'] = $row['drivermobile1'];
            $triparr['drivermobile2'] = $row['drivermobile2'];
            $triparr['statusdate'] = date(speedConstants::DEFAULT_DATETIME, strtotime($row['statusdate']));
            $triparr['startdate'] = $startdate;
            $triparr['tripLogVal'] = BASE_PATH . "/modules/reports/reports.php?id=16&tripid=" . $row['tripid'] . "&deviceid=" . $deviceid . "&sdate=" . $SDate . "&stime=" . $STime . "&edate=" . $EDate . "&etime=" . $ETime . "&interval=" . $interval . "&vehicleno=" . $vehicleno . "";
            if ($_SESSION['customerno'] == speedConstants::CUSTNO_SAFEANDSECURE && $_SESSION['role_modal'] == 'Custom') {
                $triparr['edit'] = "";
            } else {
                $triparr['edit'] = "<a href='trips.php?pg=tripview&frm=edit&tripid=" . $row['tripid'] . "'><img src='../../images/edit_black.png'/></a>";
            }
            $tripLRDetails = getTripLrDetails($customerno, $userid, $tripid);
            $triparr['varChitthiCreation'] = isset($tripLRDetails[0]['varChitthiCreation']) ? $tripLRDetails[0]['varChitthiCreation'] : '';
            $triparr['varLrCreation'] = isset($tripLRDetails[0]['varLrCreation']) ? $tripLRDetails[0]['varLrCreation'] : '';
            $triparr['varYardCheckout'] = isset($tripLRDetails[0]['varYardCheckout']) ? $tripLRDetails[0]['varYardCheckout'] : '';
            $triparr['varYardCheckin'] = isset($tripLRDetails[0]['varYardCheckin']) ? $tripLRDetails[0]['varYardCheckin'] : '';
            if (isset($tripLRDetails[0]['varChitthiCreation']) && isset($tripLRDetails[0]['varLrCreation'])) {
                $triparr['lrdelay'] = getTimeDiff($triparr['varLrCreation'], $triparr['varChitthiCreation']);
            } else {
                $triparr['lrdelay'] = 0;
            }
            $triparr['varYardDetention'] = isset($tripLRDetails[0]['varYardDetention']) ? $tripLRDetails[0]['varYardDetention'] : '';
            $triparr['varEmptyReturnDeviation'] = isset($tripLRDetails[0]['varEmptyReturnDeviation']) ? $tripLRDetails[0]['varEmptyReturnDeviation'] : '';
            $tripdata[] = $triparr;
        }
    }
    return $tripdata;
}

function getViewtriprecordsList($customerno, $userid) {
    $today = date('Y-m-d H:i:s');
    $EDate = date('d-m-Y', strtotime($today));
    $ETime = date('H:i', strtotime($today));
    $interval = 30;
    $tripdata = array();
    $triparr = array();
    $mob = new Trips($_SESSION['customerno'], $_SESSION['userid']);
    $triprecordArray = $mob->get_viewtriprecords();
    if (isset($triprecordArray)) {
        foreach ($triprecordArray as $row) {
            $deviceid = 0;
            $vehicleno = $row['vehicleno'];
            $deviceid = getDeviceid($vehicleno);
            $SDate = date('d-m-Y', strtotime($row['statusdate']));
            $STime = date('H:i', strtotime($row['statusdate']));
            $startdate = date(speedConstants::DEFAULT_DATETIME, strtotime($row['startdate']));
            $tripid = $row['tripid'];
            $triparr['vehicleno'] = $row['vehicleno'];
            //$triparr['triplogno'] = "<a href='javascript:void(0)' onclick='getLocationReport(\"" . $deviceid . "\",\"" . $row['mintemp'] . "\",\"" . $row['maxtemp'] . "\",\"" . $SDate . "\",\"" . $STime . "\",\"" . $EDate . "\",\"" . $ETime . "\",\"" . $interval . "\",\"" . $vehicleno . "\");'>" . $row['triplogno'] . "</a>";
            $triparr['triplogno'] = "<a href='javascript:void(0)' onclick='getLocationReport(\"" . $tripid . "\",\"" . $deviceid . "\",\"" . $SDate . "\",\"" . $STime . "\",\"" . $EDate . "\",\"" . $ETime . "\",\"" . $interval . "\",\"" . $vehicleno . "\");'>" . $row['triplogno'] . "</a>";
            $triparr['tripstatus'] = $row['tripstatus'];
            $triparr['routename'] = $row['routename'];
            $triparr['budgetedkms'] = $row['budgetedkms'];
            $triparr['budgetedhrs'] = $row['budgetedhrs'];
            $triparr['consignorname'] = $row['consignorname'];
            $triparr['consigneename'] = $row['consigneename'];
            $triparr['billingparty'] = $row['billingparty'];
            $triparr['mintemp'] = $row['mintemp'];
            $triparr['maxtemp'] = $row['maxtemp'];
            $triparr['drivername'] = $row['drivername'];
            $triparr['drivermobile1'] = $row['drivermobile1'];
            $triparr['drivermobile2'] = $row['drivermobile2'];
            $triparr['statusdate'] = $row['statusdate'];
            $triparr['startdate'] = $startdate;
            if ($_SESSION['customerno'] == speedConstants::CUSTNO_SAFEANDSECURE && $_SESSION['role_modal'] == 'Custom') {
                $triparr['edit'] = "";
            } else {
                $triparr['edit'] = "<a href='trips.php?pg=tripview&frm=edit&tripid=" . $row['tripid'] . "'><img src='../../images/edit_black.png'/></a>";
            }
            $tripdata[] = $triparr;
        }
    }
    return $tripdata;
}

function getDeviceid($vehicleno) {
    $deviceid = 0;
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $devices = $devicemanager->devicesformapping_byId($vehicleno);
    if ($devices) {
        foreach ($devices as $row) {
            $deviceid = $row->deviceid;
        }
    }
    return $deviceid;
}

function get_trip_droppoints($tripid) {
    $tripdata = null;
    $tripMgr = new Trips($_SESSION['customerno'], $_SESSION['userid']);
    $objTrip = new stdClass();
    $objTrip->tripid = $tripid;
    $objTrip->customerno = $_SESSION['customerno'];
    $tripdata = $tripMgr->getTripDroppoints($objTrip);
    return $tripdata;
}

function getYardList($tripid) {
    $tripdata = null;
    $tripMgr = new Trips($_SESSION['customerno'], $_SESSION['userid']);
    $objTrip = new stdClass();
    $objTrip->customerno = $_SESSION['customerno'];
    $objTrip->tripid = $tripid;
    $resultYardList = $tripMgr->getYardList($objTrip);
    if ($resultYardList != '') {
        return $resultYardList;
    } else {
        return null;
    }
}

?>