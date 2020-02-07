<?php
include_once '../../lib/system/utilities.php';
include_once '../../lib/comman_function/reports_func.php';
include_once '../../lib/autoload.php';
include_once 'map_popup.php';
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
set_time_limit(0);
class VODatacap {}
if (!isset($_SESSION)) {
    session_start();
}
function getunitno($deviceid) {
    $um = new UnitManager($_SESSION['customerno']);
    $unitno = $um->getuidfromdeviceid($deviceid);
    return $unitno;
}

function getuid_all() {
    $um = new UnitManager($_SESSION['customerno']);
    $unitno = $um->getuid_all();
    return $unitno;
}

function getunitnopdf($customerno, $deviceid) {
    $um = new UnitManager($customerno);
    $unitno = $um->getuidfromdeviceid($deviceid);
    return $unitno;
}

function getvehicles_overspeed() {
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $devices = $devicemanager->devicesformapping();
    return $devices;
}

function getdate_IST() {
    $ServerDate_IST = strtotime(date("Y-m-d H:i:s"));
    return $ServerDate_IST;
}

function getstoppagereport($STdate, $EDdate, $deviceid, $Shour, $Ehour, $holdtime) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $customerno = $_SESSION['customerno'];
    $unitno = getunitno($deviceid);
    $count = count($totaldays);
    $endelement = end($totaldays);
    $totaldaysArray = array_values($totaldays);
    $firstelement = array_shift($totaldaysArray);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $data = null;
                $locationPath = "sqlite:" . $location;
                if ($count > 1 && $userdate != $endelement && $userdate == $firstelement) {
                    $data = getstoppage_fromsqlite($locationPath, $deviceid, $holdtime, $Shour, null, $userdate);
                } elseif ($count > 1 && $userdate == $endelement) {
                    $data = getstoppage_fromsqlite($locationPath, $deviceid, $holdtime, null, $Ehour, $userdate);
                } elseif ($count == 1) {
                    $data = getstoppage_fromsqlite($locationPath, $deviceid, $holdtime, $Shour, $Ehour, $userdate);
                } else {
                    $data = getstoppage_fromsqlite($locationPath, $deviceid, $holdtime, null, null, $userdate);
                }
                if ($data != null && count($data) > 0) {
                    $days = array_merge($days, $data);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $finalreport = create_stoppagehtml_from_report($days);
    } else {
        $finalreport = "<tr><td style='text-align:center' colspan='100%'>No Record</td></tr>";
    }
    echo $finalreport;
}

function getstoppagereportpdf($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $holdtime, $Shour, $Ehour, $type = null, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $count = count($totaldays);
    $endelement = end($totaldays);
    $dayelement = array_values($totaldays);
    $firstelement = array_shift($dayelement);
    $finalreport = '';
    $noData = '';
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $data = null;
                $locationPath = "sqlite:" . $location;
                if ($count > 1 && $userdate != $endelement && $userdate == $firstelement) {
                    $data = getstoppage_fromsqlite($locationPath, $deviceid, $holdtime, $Shour, null, $userdate);
                } elseif ($count > 1 && $userdate == $endelement) {
                    $data = getstoppage_fromsqlite($locationPath, $deviceid, $holdtime, null, $Ehour, $userdate);
                } elseif ($count == 1) {
                    $data = getstoppage_fromsqlite($locationPath, $deviceid, $holdtime, $Shour, $Ehour, $userdate);
                } else {
                    $data = getstoppage_fromsqlite($locationPath, $deviceid, $holdtime, null, null, $userdate);
                }
                if ($data != null && count($data) > 0) {
                    $days = array_merge($days, $data);
                } else {
                    $noData = "Data not available for stoppage with hold time of " . $holdtime . " mins";
                }
            } else {
                $noData = 'File does not exist';
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $fromdate = date(speedConstants::DEFAULT_DATE, strtotime($STdate));
        $todate = date(speedConstants::DEFAULT_DATE, strtotime($EDdate));
        $title = 'Stoppage Report';
        $subTitle = array("Vehicle No: $vehicleno", "Start Date: $fromdate $Shour", "End Date: $todate $Ehour ", "Min. Hold Time: <b>$holdtime</b>");
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        if (isset($type) && $type == "xls") {
            $finalreport = excel_header($title, $subTitle);
        } else {
            $finalreport = pdf_header($title, $subTitle);
        }
        $finalreport .= "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tbody>";
        if ($noData == "") {
            $finalreport .= create_stoppage_pdf_from_report($days, $customerno);
        } else {
            $finalreport .= $noData;
        }
    } else {
        $finalreport .= $noData;
    }
    echo $finalreport;
}

function getstoppagereportcsv($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $holdtime, $Shour, $Ehour, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $count = count($totaldays);
    $endelement = end($totaldays);
    $dayelement = array_values($totaldays);
    $firstelement = array_shift($dayelement);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $data = null;
                $locationPath = "sqlite:" . $location;
                if ($count > 1 && $userdate != $endelement && $userdate == $firstelement) {
                    $data = getstoppage_fromsqlite($locationPath, $deviceid, $holdtime, $Shour, null, $userdate);
                } elseif ($count > 1 && $userdate == $endelement) {
                    $data = getstoppage_fromsqlite($locationPath, $deviceid, $holdtime, null, $Ehour, $userdate);
                } elseif ($count == 1) {
                    $data = getstoppage_fromsqlite($locationPath, $deviceid, $holdtime, $Shour, $Ehour, $userdate);
                } else {
                    $data = getstoppage_fromsqlite($locationPath, $deviceid, $holdtime, null, null, $userdate);
                }
                if ($data != null && count($data) > 0) {
                    $days = array_merge($days, $data);
                } else {
                    $noData = "Data not available for stoppage with hold time of " . $holdtime . " mins";
                }
            } else {
                $noData = 'File does not exist';
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $fromdate = date(speedConstants::DEFAULT_DATE, strtotime($STdate));
        $todate = date(speedConstants::DEFAULT_DATE, strtotime($EDdate));
        $title = 'Stoppage Report';
        $subTitle = array("Vehicle No: $vehicleno", "Start Date: $fromdate $Shour", "End Date: $todate $Ehour ", "Min. Hold Time: <b>$holdtime</b>");
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        $finalreport = excel_header($title, $subTitle);
        $finalreport .= "
        <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
            <tbody>
            <tr style='background-color:#CCCCCC;font-weight:bold;'>
            <td style='width:50px;height:auto; text-align: center;'>Start Time</td>
            <td style='width:50px;height:auto; text-align: center;'>End Time</td>
            <td style='width:200px;height:auto; text-align: center;'>Location</td>
            <td style='width:100px;height:auto; text-align: center;'>Hold Time [HH:MM]</td>
            <td style='width:200px;height:auto; text-align: center;'>Stoppage Reason</td>
            </tr>";
        //$finalreport .= create_location_csv_from_report($days, $customerno);
        if ($noData == "") {
            $finalreport .= create_location_csv_from_report($days, $customerno);
        } else {
            $finalreport .= $noData;
        }
    } else {
        $finalreport .= $noData;
    }
    echo $finalreport;
}

function getCompleteStoppageReport($customerno, $STdate, $EDdate, $data, $vehicleno, $holdtime, $Shour, $Ehour, $type = null) {
    $totaldays = gendays($STdate, $EDdate);
    $count = count($totaldays);
    $endelement = end($totaldays);
    $dayelement = array_values($totaldays);
    $firstelement = array_shift($dayelement);
    $finalreport = '';
    $fromdate = date(speedConstants::DEFAULT_DATE, strtotime($STdate));
    $todate = date(speedConstants::DEFAULT_DATE, strtotime($EDdate));
    $title = 'Stoppage Report';
    $subTitle = array("Vehicle No: $vehicleno", "Start Date: $fromdate $Shour", "End Date: $todate $Ehour ", "Min. Hold Time: <b>$holdtime</b>");
    if (isset($type) && $type == "xls") {
        $finalreport .= excel_header($title, $subTitle);
    } else {
        $finalreport .= pdf_header($title, $subTitle);
    }
    $finalreport .= $data;
    echo $finalreport;
}

function getStoppageAnalysisReport($data, $STdate, $EDdate, $Shour, $Ehour, $holdtime, $groupid, $customerno, $userid, $reportType) {
    $arrAnalysisData = array();
    $title = 'Stoppage Report';
    $subTitle = array(
        "Start Date: $STdate " . $Shour,
        "End Date: $EDdate " . $Ehour,
        "Min. Hold Time: <b>" . $holdtime . " mins</b>"
    );
    if ($customerno == '563') {
        $columns = array(
            'Vehicle No',
            'Group',
            'Start Time',
            'End Time',
            'Location',
            'Hold Time [HH:MM]',
            'Add Reason',
            'Add As Checkpoint'
        );
    } else {
        $columns = array(
            'Vehicle No',
            'Group',
            'Start Time',
            'End Time',
            'Location',
            'Hold Time [HH:MM]',
            'Add As Checkpoint'
        );
    }
    $objCustomerManager = new CustomerManager();
    $customer_details = $objCustomerManager->getcustomerdetail_byid($customerno);
    $tableHeader = '';
    if ($reportType == speedConstants::REPORT_PDF) {
        $tableHeader .= pdf_header($title, $subTitle, $customer_details);
    } elseif ($reportType == speedConstants::REPORT_XLS) {
        $tableHeader .= excel_header($title, $subTitle, $customer_details);
    } else {
        $tableHeader .= table_header($title, $subTitle);
    }
    $tableColumns = '';
    $tableColumns .= "<tr>";
    $tableColumns .= "<td>Vehicle No</td>";
    $tableColumns .= "<td>Group</td>";
    $tableColumns .= "<td>Start Time</td>";
    $tableColumns .= "<td>End Time</td>";
    $tableColumns .= "<td>Location</td>";
    $tableColumns .= "<td>Hold Time [HH:MM]</td>";
    if ($customerno == '563') {
        $tableColumns .= "<td>Add Reason</td>";
    }
    $tableColumns .= "<td>Add As Checkpoint</td>";
    $tableColumns .= "</tr>";
    $arrAnalysisData['tableHeader'] = $tableHeader;
    $arrAnalysisData['tableColumns'] = $tableColumns;
    $arrAnalysisData['tableRows'] = $data;
    return $arrAnalysisData;
}

function getstoppageanalysis($STdate, $EDdate, $Shour, $Ehour, $holdtime, $groupid, $customerno, $userid, $reportType) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $group_based_vehicle = vehicles_array(groupBased_vehicles_cron($customerno, $userid, $groupid));
    $uids = getuid_all();
    if (isset($uids)) {
        foreach ($uids as $unit) {
            if (!array_key_exists($unit->vehicleid, $group_based_vehicle)) {
                continue;
            }
            $unitno = $unit->unitno;
            $deviceid = $unit->deviceid;
            $count = count($totaldays);
            $endelement = end($totaldays);
            $totaldaysArray = array_values($totaldays);
            $firstelement = array_shift($totaldaysArray);
            if (isset($totaldays)) {
                foreach ($totaldays as $userdate) {
                    if ($count > 1 && $userdate != $endelement && $userdate == $firstelement) {
                        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                        if (file_exists($location)) {
                            //Date Check Operations
                            $data = null;
                            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                            if (file_exists($location)) {
                                $location = "sqlite:" . $location;
                                $data = getstoppage_fromsqlite($location, $deviceid, $holdtime, $Shour, null, $userdate);
                            }
                            if ($data != null && count($data) > 0) {
                                $days = array_merge($days, $data);
                            }
                        }
                    } elseif ($count > 1 && $userdate == $endelement) {
                        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                        if (file_exists($location)) {
                            //Date Check Operations
                            $data = null;
                            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                            if (file_exists($location)) {
                                $location = "sqlite:" . $location;
                                $data = getstoppage_fromsqlite($location, $deviceid, $holdtime, null, $Ehour, $userdate);
                            }
                            if ($data != null && count($data) > 0) {
                                $days = array_merge($days, $data);
                            }
                        }
                    } elseif ($count == 1) {
                        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                        if (file_exists($location)) {
                            //Date Check Operations
                            $data = null;
                            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                            if (file_exists($location)) {
                                $location = "sqlite:" . $location;
                                $data = getstoppage_fromsqlite($location, $deviceid, $holdtime, $Shour, $Ehour, $userdate);
                            }
                            if ($data != null && count($data) > 0) {
                                $days = array_merge($days, $data);
                            }
                        } else {
                            //echo 'File Does not exist';
                        }
                    } else {
                        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                        if (file_exists($location)) {
                            //Date Check Operations
                            $data = null;
                            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                            if (file_exists($location)) {
                                $location = "sqlite:" . $location;
                                $data = getstoppage_fromsqlite($location, $deviceid, $holdtime, null, null, $userdate);
                            }
                            if ($data != null && count($data) > 0) {
                                $days = array_merge($days, $data);
                            }
                        }
                    }
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $finalreport = create_stoppageanalysishtml_from_report($days, $reportType);
    } else {
        $finalreport = "<tr><td style='text-align:center' colspan='100%'>No Record</td></tr>";
    }
    $reportData = getStoppageAnalysisReport($finalreport, $STdate, $EDdate, $Shour, $Ehour, $holdtime, $groupid, $customerno, $userid, $reportType);
    return $reportData;
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

function getstoppage_fromsqlite($location, $deviceid, $holdtime, $Shour, $Ehour, $userdate) {
    $devices = array();
    $query = "SELECT devicehistory.lastupdated, vehiclehistory.odometer, vehiclehistory.vehicleno, devicehistory.devicelat, vehiclehistory.vehicleid,
            devicehistory.devicelong, unithistory.unitno
            from devicehistory
            INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
            INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.deviceid= $deviceid  AND devicehistory.devicelat <> '0.000000' AND devicehistory.devicelong <> '0.000000'";
    //if ($customerno != speedConstants::CUSTNO_APMT) {
    $query .= " AND COALESCE(devicehistory.status, '') != 'F'";
    //$query .= " AND devicehistory.status!='F'";
    //}
    if ($Shour != null) {
        $query .= " AND devicehistory.lastupdated > '$userdate $Shour'";
    }
    if ($Ehour != null) {
        $query .= " AND devicehistory.lastupdated < '$userdate $Ehour'";
    }
    $query .= "  ORDER BY devicehistory.lastupdated ASC";
    try
    {
        //echo $location;
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
                    $device = new VODatacap();
                    $device->vehicleid = $row['vehicleid'];
                    $device->vehicleno = $row['vehicleno'];
                    $device->starttime = $row['lastupdated'];
                    $device->deviceid = $row['vehicleid'];
                    $device->customerno = $_SESSION['customerno'];
                    $device->lat = $row['devicelat'];
                    $device->lng = $row['devicelong'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                }
                /* Condition For Odometer Reset */
                if ($row["odometer"] < $lastodometer) {
                    $max = GetOdometerMax($row['lastupdated'], $row["unitno"]);
                    $row["odometer"] = $max + $row["odometer"];
                }
                if (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) > $holdtime && $row["odometer"] - $lastodometer < 100 && $pusharray == 1) {
                    $device->deviceid = $row['vehicleid'];
                    $device->customerno = $_SESSION['customerno'];
                    $lastodometer = $row["odometer"];
                    $lastupdated = $row['lastupdated'];
                    $device->lat = $row['devicelat'];
                    $device->lng = $row['devicelong'];
                    $device->reason = getStoppageReasonPerLoc($device);
                    $pusharray = 0;
                } else {
                    if (($row["odometer"] - $lastodometer) > 25) {
                        if ($pusharray == 0) {
                            $device->endtime = $row['lastupdated'];
                            $devices[] = $device;
                        }
                        $lastodometer = $row["odometer"];
                        $lastupdated = $row['lastupdated'];
                        $device = new VODatacap();
                        $device->vehicleid = $row['vehicleid'];
                        $device->vehicleno = $row['vehicleno'];
                        $device->starttime = $row['lastupdated'];
                        $device->deviceid = $row['vehicleid'];
                        $device->customerno = $_SESSION['customerno'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $device->lat = $row['devicelat'];
                        $device->lng = $row['devicelong'];
                        $device->reason = getStoppageReasonPerLoc($device);
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
    //prettyPrint($devices);die();
    return $devices;
}

function getStoppageReason($objReason) {
    $objDeviceManager = new DeviceManager($objReason->customerno);
    $reason = $objDeviceManager->getStoppageReason($objReason);
    return $reason;
}

function getStoppageReasonPerLoc($objReason) {
    $objDeviceManager = new DeviceManager($objReason->customerno);
    $reason = $objDeviceManager->getStoppageReasonPerLoc($objReason);
    return $reason;
}

function getStoppageReasonMaster($objReason) {
    $objDeviceManager = new DeviceManager($objReason->customerno);
    $reason = $objDeviceManager->getStoppageReasonMaster($objReason);
    return $reason;
}

function create_stoppagehtml_from_report($datarows) {
    $display = '';
    $lastdate = '';
    $i = 0;
    $objReasonList = new stdClass();
    $objReasonList->customerno = $_SESSION['customerno'];
    $arrReasons = getStoppageReasonMaster($objReasonList);
    $selectString = '';
    if (isset($arrReasons) && !empty($arrReasons)) {
        foreach ($arrReasons as $reason) {
            $selectString .= "<option value='" . $reason->reasonid . "'>" . $reason->reason . "</option>";
        }
    }
    if (isset($datarows)) {
        $x = 0;
        $chkManager = new CheckpointManager($_SESSION['customerno']);
        $arrCheckpoints = $chkManager->getcheckpointsforcustomer();
        $use_geolocation = get_usegeolocation($_SESSION['customerno']);
        $today = date('d-m-Y', strtotime('Now'));
        foreach ($datarows as $change) {
            $x++;
            $comparedate = date('d-m-Y', strtotime($change->endtime));

            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $display .= "<tr><th align='center'  style='background:#d8d5d6' colspan = '100%'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $i++;
            }
            $timestamp = strtotime($change->starttime);
            $display .= "<tr><td>" . date(speedConstants::DEFAULT_TIME, strtotime($change->starttime)) . "</td><td>" . date(speedConstants::DEFAULT_TIME, strtotime($change->endtime)) . "</td>";
            $location = location($change->devicelat, $change->devicelong, $use_geolocation);
            $display .= "<td>$location</td>";
            $secdiff = strtotime($change->endtime) - strtotime($change->starttime);
            $minutesdiff = floor($secdiff / 60);
            if (floor($minutesdiff / 60) < 10) {
                $hourdiff = "0" . floor($minutesdiff / 60);
            } else {
                $hourdiff = floor($minutesdiff / 60);
            }
            if (floor($minutesdiff % 60) < 10) {
                $hourremainder = "0" . floor($minutesdiff % 60);
            } else {
                $hourremainder = floor($minutesdiff % 60);
            }
            $minutesdiff = $hourdiff . ":" . $hourremainder;
            $display .= "<td>$minutesdiff</td>";
            /* Add Stoppage Reason */
            $objReason = new stdClass();
            $objReason->vehicleid = $change->vehicleid;
            $objReason->starttime = date(speedConstants::DEFAULT_TIMESTAMP, strtotime($change->starttime));
            $objReason->endtime = date(speedConstants::DEFAULT_TIMESTAMP, strtotime($change->endtime));
            $objReason->lat = $change->devicelat;
            $objReason->lng = $change->devicelong;
            $objReason->customerno = $_SESSION['customerno'];
            $reason = getStoppageReason($objReason);
            $dataString = $change->vehicleid . "," . strtotime($change->starttime) . "," . strtotime($change->endtime) . "," . $change->devicelat . "," . $change->devicelong . "," . $timestamp . "," . $_SESSION['customerno'] . "," . $_SESSION['userid'];
            if (isset($reason) && !empty($reason)) {
                $editdataString = $_SESSION['customerno'] . "," . $_SESSION['userid'];
                if (isset($reason[0]->remarks) && !empty($reason[0]->remarks)) {
                    $display .= "<td style='cursor: pointer;' class='edit_td tooltip-right' title='Double Click To Edit' id='" . $reason[0]->sid . "'>
                        <span class='" . $reason[0]->sid . "'>" . $reason[0]->remarks . "</span>
                        <input type='hidden' id='reasonid_" . $reason[0]->sid . "' class='reasonEdit' value='" . $reason[0]->reasonid . "'/>
                        <select class='editbox' style='display:none;' name='reasonEdit_" . $reason[0]->sid . "' id='reasonEdit_" . $reason[0]->sid . "' onchange='updateStoppageReason($editdataString," . $reason[0]->sid . ");' >
                        <option id='0'>Select Reason</option>
                        " . $selectString . "
                        </select>
                            </td>";
                } else {
                    $display .= "<td style='cursor: pointer;' class='edit_td tooltip-right' title='Double Click To Edit' id='" . $reason[0]->sid . "'>
                        <span class='" . $reason[0]->sid . "'>" . $reason[0]->reason . "</span>
                        <input type='hidden' id='reasonid_" . $reason[0]->sid . "' class='reasonEdit' value='" . $reason[0]->reasonid . "'/>
                        <select class='editbox' style='display:none;' name='reasonEdit_" . $reason[0]->sid . "' id='reasonEdit_" . $reason[0]->sid . "' onchange='updateStoppageReason($editdataString," . $reason[0]->sid . ");' >
                        <option id='0'>Select Reason</option>
                        " . $selectString . "
                        </select>
                            </td>";
                }
            } else {
                $display .= "<td id='reason$timestamp'><select name='reason_$timestamp' id='reason_$timestamp' onchange='addStoppageReason($dataString);'>
                    <option id='0'>Select Reasons</option>
                    " . $selectString . "
                </select>
                </td>
                ";
            }
            $chk = getChkRealy($change->devicelat, $change->devicelong, $arrCheckpoints);
            //print_r($chk);
            if (!empty($chk)) {
                $display .= "<td ><a style='text-decoration:underline;cursor:pointer;' onclick='create_map_for_modal_report_onlymap(" . $chk[0]->cgeolat . "," . $chk[0]->cgeolong . ")'>" . $chk[0]->cname . "</a></td>";
            } else {
                $display .= "<td><a id='added_$timestamp' style='display:none;'><img src='../../images/added.png' alt='added as checkpoint' width='18' height='18'/></a>
                        <a href='#test_$timestamp' id='add_$timestamp' data-toggle='modal' class='add_button' ><img src='../../images/add.png' alt='add as checkpoint' width='18' height='18'/></a> </td>";
                $display .= '</tr>';
                $display .= "<div id='test_$timestamp' class='modal hide in' style='width:815px; height:650px; display:none; margin: -300px 0 0 -410px' >
                <form>
                <div class='modal-header'>
                <button class='close' data-dismiss='modal'>×</button>
                <h4 style='color:#0679c0'>Add Checkpoint</h4>
                </div>
                <div class='modal-body'>
                </br>
                <span class='add-on' style='color:#000000'>Enter Checkpoint Name</span>&nbsp;
                <input type='text' name='cname' id='cname_$timestamp' value='' onkeyup='checkname($timestamp)'/></br>
                <!-- ak added below inputs -->
                <span class='add-on' style='color:#000000'>ETA(HH:MM)</span>&nbsp;
                <input type='text' name='STime_pop' size='5' value='' class='STime_pop' id='STime_pop_$timestamp'/><br/>
                <!-- ak edit ends -->
                <span id='checkpointarray_$timestamp' style='display:none;'>Please Enter Checkpoint Name.</span>
                <span id='check_$timestamp'></span>
                <input type='hidden' name='cadd' id='cadd_$timestamp' value='$location'/>
                <input type='hidden' name='clat' id='lat_$timestamp' value='$change->devicelat'/>
                <input type='hidden' name='latlong' id='latlong$timestamp' value='$change->devicelat,$change->devicelong'/>
                <input type='hidden' name='clat' id='lat_$timestamp' value='$change->devicelat'/>
                <input type='hidden' name='clong' id='long_$timestamp' value='$change->devicelong'/>
                <input type='hidden' name='device' id='getdevice_$timestamp' value='$change->deviceid'/>
                <input type='hidden' name='last' id='lastup' value='$timestamp'/>
                <input type='hidden' name='customer' id='customer' value='$_SESSION[customerno]'/>
                <input type='hidden' name='usreid' id='userid' value='$_SESSION[userid]'/>
                </div>
                </form>
                  </div>";
            }
            $display .= '</tr>';
        }
    }
    $display .= '</tbody>';
    $display .= '</table></div><div class="clearfix"></div>';
    return $display;
}

function create_stoppageanalysishtml_from_report($datarows, $reportType) {
    $display = '';
    $lastdate = '';
    $i = 0;
    $objReasonList = new stdClass();
    $objReasonList->customerno = $_SESSION['customerno'];
    $arrReasons = getStoppageReasonMaster($objReasonList);
    $chkManager = new CheckpointManager($_SESSION['customerno']);
    $arrCheckpoints = $chkManager->getcheckpointsforcustomer();
    $selectString = '';
    if (isset($arrReasons) && !empty($arrReasons)) {
        foreach ($arrReasons as $reason) {
            $selectString .= "<option value='" . $reason->reasonid . "'>" . $reason->reason . "</option>";
        }
    }
    if (isset($datarows)) {
        $x = 0;
        foreach ($datarows as $change) {
            $x++;
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            $timestamp = strtotime($change->starttime);
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $display .= "<tr><th align='center'  style='background:#d8d5d6' colspan = '100%'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $i++;
            }
            $timestamp = strtotime($change->starttime);
            $groupname = getgroupnamebyvehicleid($change->vehicleid);
            $display .= "<tr><td>" . $change->vehicleno . "</td><td>" . $groupname . "</td><td>" . date(speedConstants::DEFAULT_TIME, strtotime($change->starttime)) . "</td><td>" . date(speedConstants::DEFAULT_TIME, strtotime($change->endtime)) . "</td>";
            $use_geolocation = get_usegeolocation($_SESSION['customerno']);
            $location = location($change->devicelat, $change->devicelong, $use_geolocation);
            $display .= "<td>$location</td>";
            $secdiff = strtotime($change->endtime) - strtotime($change->starttime);
            $dataString = $change->vehicleid . "," . strtotime($change->starttime) . "," . strtotime($change->endtime) . "," . $change->devicelat . "," . $change->devicelong . "," . $timestamp . "," . $_SESSION['customerno'] . "," . $_SESSION['userid'];
            $minutesdiff = floor($secdiff / 60);
            if (floor($minutesdiff / 60) < 10) {
                $hourdiff = "0" . floor($minutesdiff / 60);
            } else {
                $hourdiff = floor($minutesdiff / 60);
            }
            if (floor($minutesdiff % 60) < 10) {
                $hourremainder = "0" . floor($minutesdiff % 60);
            } else {
                $hourremainder = floor($minutesdiff % 60);
            }
            $minutesdiff = $hourdiff . ":" . $hourremainder;
            $display .= "<td>$minutesdiff</td>";
            if ($_SESSION['customerno'] == '563') {
                if (isset($change->reason) && !empty($change->reason)) {
                    foreach ($change->reason as $reasonval) {
                        /* //$editdataString = $_SESSION['customerno'].",".$_SESSION['userid'];
                        $display .= "<td style='cursor: pointer;' class='edit_td tooltip-right' title='Double Click To Edit' id='" . $reasonval->sid . "'>
                        <span class='" . $reasonval->sid . "'>" . $reasonval->reason . "</span></td>";*/
                        $editdataString = $_SESSION['customerno'] . "," . $_SESSION['userid'];
                        $display .= "<td style='cursor: pointer;' class='edit_td tooltip-right' title='Double Click To Edit' id='" . $reasonval->sid . "'>
                    <span class='" . $reasonval->sid . "'>" . $reasonval->reason . "</span>
                    <input type='hidden' id='reasonid_" . $reasonval->sid . "' class='reasonEdit' value='" . $reasonval->reasonid . "'/>
                    <select class='editbox' style='display:none;' name='reasonEdit_" . $reasonval->sid . "' id='reasonEdit_" . $reasonval->sid . "' onchange='updateStoppageReason($editdataString," . $reasonval->sid . ");' >
                    <option id='0'>Select Reason</option>
                    " . $selectString . "
                    </select>
                    </td>";
                    }
                } else {
                    $display .= "<td id='reason$timestamp'><select name='reason_$timestamp' id='reason_$timestamp' onchange='addStoppageReason($dataString);'>
                    <option id='0'>Select Reason</option>
                    " . $selectString . "
                    </select></td>";
                }
            }
            $chk = getChkRealy($change->devicelat, $change->devicelong, $arrCheckpoints);
            if (!empty($chk)) {
                $display .= "<td ><a style='text-decoration:underline;cursor:pointer;' onclick='create_map_for_modal_report_onlymap(" . $chk[0]->cgeolat . "," . $chk[0]->cgeolong . ")'>" . $chk[0]->cname . "</a></td>";
            } elseif ($reportType == speedConstants::REPORT_HTML) {
                $display .= "<td><a id='added_$timestamp' style='display:none;'><img src='../../images/added.png' alt='added as checkpoint' width='18' height='18'/></a>
                        <a href='#test_$timestamp' id='add_$timestamp' data-toggle='modal' class='add_button' ><img src='../../images/add.png' alt='add as checkpoint' width='18' height='18'/></a> </td>";
                $display .= '</tr>';
                $display .= "<div id='test_$timestamp' class='modal hide in' style='width:815px; height:650px; display:none; margin: -300px 0 0 -410px' >
                <form>
                <div class='modal-header'>
                <button class='close' data-dismiss='modal'>×</button>
                <h4 style='color:#0679c0'>Add Checkpoint</h4>
                </div>
                <div class='modal-body'>
                </br>
                <span class='add-on' style='color:#000000'>Enter Checkpoint Name</span>&nbsp;
                <input type='text' name='cname' id='cname_$timestamp' value='' onkeyup='checkname($timestamp)'/></br>
                <!-- ak added below inputs -->
                <span class='add-on' style='color:#000000'>ETA(HH:MM)</span>&nbsp;
                <input type='text' name='STime_pop' size='5' value='' class='STime_pop' id='STime_pop_$timestamp'/><br/>
                <!-- ak edit ends -->
                <span id='checkpointarray_$timestamp' style='display:none;'>Please Enter Checkpoint Name.</span>
                <span id='check_$timestamp'></span>
                <input type='hidden' name='cadd' id='cadd_$timestamp' value='$location'/>
                <input type='hidden' name='clat' id='lat_$timestamp' value='$change->devicelat'/>
                <input type='hidden' name='latlong' id='latlong$timestamp' value='$change->devicelat,$change->devicelong'/>
                <input type='hidden' name='clat' id='lat_$timestamp' value='$change->devicelat'/>
                <input type='hidden' name='clong' id='long_$timestamp' value='$change->devicelong'/>
                <input type='hidden' name='device' id='getdevice_$timestamp' value='$change->deviceid'/>
                <input type='hidden' name='last' id='lastup' value='$timestamp'/>
                <input type='hidden' name='customer' id='customer' value='$_SESSION[customerno]'/>
                <input type='hidden' name='usreid' id='userid' value='$_SESSION[userid]'/>
                </div>
                </form>
                  </div>";
            } else {
                $display .= "<td></td>";
            }
            $display .= '</tr>';
        }
    }
    return $display;
}

function getgroupnamebyvehicleid($vehicleid) {
    $objVehicle = new VehicleManager($_SESSION['customerno']);
    $groupname = $objVehicle->getgroupnamebyvehicleid($vehicleid);
    return $groupname;
}

function location($lat, $long, $usegeolocation, $customerno = null) {
    $address = null;
    $customerno = (!isset($customerno)) ? $_SESSION['customerno'] : $customerno;
    $GeoCoder_Obj = new GeoCoder($customerno);
    $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
    return $address;
}

function get_usegeolocation($customerno) {
    $GeoCoder_Obj = new GeoCoder($customerno);
    $geolocation = $GeoCoder_Obj->get_use_geolocation();
    return $geolocation;
}

function create_stoppage_pdf_from_report($datarows, $customerno) {
    $display = $lastdate = '';
    if (isset($datarows)) {
        $use_geolocation = get_usegeolocation($customerno);
        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                $display .= "</tbody></table>
                <hr  id='style-six' /><br/><table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse;'>
                <tbody>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $display .= "
                    <tr style='background-color:#CCCCCC;font-weight:bold;'>
                        <td style='width:100px;height:auto;' >Start Time</td>
                        <td style='width:100px;height:auto;'>End Time</td>
                        <td style='width:300px;height:auto;'>Location</td>
                        <td style='width:150px;height:auto;'>Hold Time [HH:MM]</td>
                        <td style='width:200px;height:auto;'>Stoppage Reason</td>
                    </tr>
                    <tr style='background-color:#CCCCCC;font-weight:bold;'><td colspan='5' >Date " . date('d-m-Y', strtotime($change->endtime)) . "</td></tr>
                    ";
            }
            $display .= "<tr><td style='width:100px;height:auto;'>" . date(speedConstants::DEFAULT_TIME, strtotime($change->starttime)) . "</td><td style='width:100px;height:auto;'>" . date(speedConstants::DEFAULT_TIME, strtotime($change->endtime)) . "</td>";
            $location = location($change->devicelat, $change->devicelong, $use_geolocation, $customerno);
            $display .= "<td style='width:400px;height:auto;'>" . $location . "</td>";
            $secdiff = strtotime($change->endtime) - strtotime($change->starttime);
            $minutesdiff = floor($secdiff / 60);
            if (floor($minutesdiff / 60) < 10) {
                $hourdiff = "0" . floor($minutesdiff / 60);
            } else {
                $hourdiff = floor($minutesdiff / 60);
            }
            if (floor($minutesdiff % 60) < 10) {
                $hourremainder = "0" . floor($minutesdiff % 60);
            } else {
                $hourremainder = floor($minutesdiff % 60);
            }
            $minutesdiff = $hourdiff . ":" . $hourremainder;
            $display .= "<td style='width:150px;height:auto;'>$minutesdiff</td>";
            /* Add Stoppage Reason */
            $objReason = new stdClass();
            $objReason->vehicleid = $change->vehicleid;
            $objReason->starttime = date(speedConstants::DEFAULT_TIMESTAMP, strtotime($change->starttime));
            $objReason->endtime = date(speedConstants::DEFAULT_TIMESTAMP, strtotime($change->endtime));
            $objReason->lat = $change->devicelat;
            $objReason->lng = $change->devicelong;
            $objReason->customerno = $customerno;
            $reason = getStoppageReason($objReason);
            if (isset($reason) && !empty($reason)) {
                $display .= "<td style='width:200px;height:auto;'>" . $reason[0]->reason . "</td>";
            } else {
                $display .= "<td style='width:200px;height:auto;'></td>";
            }
            $display .= '</tr>';
        }
    }
    $display .= '</tbody>';
    $display .= "</table>";
    $display .= "
    <page_footer>
                    [[page_cu]]/[[page_nb]]
    </page_footer>";
    return $display;
}

function create_location_csv_from_report($datarows, $customerno) {
    $display = $lastdate = '';
    if (isset($datarows)) {
        $use_geolocation = get_usegeolocation($customerno);
        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                $display .= "<tr><td style='width:335px;height:auto; text-align: center;background-color:#CCCCCC;font-weight:bold;' colspan='5'>$comparedate</td></tr>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
            } else {
                $display .= "<tr>";
            }
            $display .= "<td style='width:50px;height:auto; text-align: center;'>" . date(speedConstants::DEFAULT_TIME, strtotime($change->starttime)) . "</td>
                    <td style='width:50px;height:auto;text-align=center;'>" . date(speedConstants::DEFAULT_TIME, strtotime($change->endtime)) . "</td>";
            $location = location($change->devicelat, $change->devicelong, $use_geolocation, $customerno);
            $display .= "<td style='width:300px;height:auto; text-align: center;'>" . $location . "</td>";
            $secdiff = strtotime($change->endtime) - strtotime($change->starttime);
            $minutesdiff = floor($secdiff / 60);
            if (floor($minutesdiff / 60) < 10) {
                $hourdiff = "0" . floor($minutesdiff / 60);
            } else {
                $hourdiff = floor($minutesdiff / 60);
            }
            if (floor($minutesdiff % 60) < 10) {
                $hourremainder = "0" . floor($minutesdiff % 60);
            } else {
                $hourremainder = floor($minutesdiff % 60);
            }
            $minutesdiff = $hourdiff . ":" . $hourremainder;
            $display .= "<td style='width:100px;height:auto; text-align: center;'>" . $minutesdiff . "</td>";
            /* Add Stoppage Reason */
            $objReason = new stdClass();
            $objReason->vehicleid = $change->vehicleid;
            $objReason->starttime = date(speedConstants::DEFAULT_TIMESTAMP, strtotime($change->starttime));
            $objReason->endtime = date(speedConstants::DEFAULT_TIMESTAMP, strtotime($change->endtime));
            $objReason->lat = $change->devicelat;
            $objReason->lng = $change->devicelong;
            $objReason->customerno = $customerno;
            $reason = getStoppageReason($objReason);
            if (isset($reason) && !empty($reason)) {
                $display .= "<td style='width:200px;height:auto;'>" . $reason[0]->reason . "</td>";
            } else {
                $display .= "<td style='width:200px;height:auto;'></td>";
            }
            $display .= '</tr>';
        }
    }
    $display .= '</tbody>';
    $display .= "</table>";
    return $display;
}

function GetOdometerMax($date, $unitno) {
    $sqlitedate = date('Y-m-d', strtotime($date));
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$sqlitedate.sqlite";
    if ($_SESSION['role_modal'] == 'elixir') {
        //echo $location;
    }
    $ODOMETER = 0;
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path, "", "", array(PDO::ATTR_PERSISTENT => true));
        $query = "SELECT max(odometer) as odometerm from vehiclehistory where lastupdated < '" . $date . "' limit 1";
        //echo $query."<br/>";
        $result = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
        $ODOMETER = $result[0]['odometerm'];
    }
    return $ODOMETER;
}

function get_vehicle_details($Request) {
    $VehicleManager = new VehicleManager($Request->customerno);
    $arrVehicle = $VehicleManager->get_vehicle_details($Request->vehicleid);
    return (array) $arrVehicle;
}

?>