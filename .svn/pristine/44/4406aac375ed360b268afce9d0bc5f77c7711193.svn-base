<?php
include_once '../../lib/system/utilities.php';
include_once '../../lib/bo/CheckpointManager.php';
include_once "../../lib/bo/VehicleManager.php";
include_once "../../lib/bo/UnitManager.php";
include_once "reports_chk_comman_functions.php";
include_once '../../lib/comman_function/reports_func.php';
include_once '../../lib/model/TempConversion.php';
class VOCHKM {
}
if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
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

function getunitno_pdf($deviceid, $customerno) {
    $um = new UnitManager($customerno);
    $unitno = $um->getunitnofromdeviceid($deviceid);
    return $unitno;
}

function getcheckpoints($vehicleid) {
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->getchksforvehicle($vehicleid);
    return $checkpoints;
}

function getcheckpoints_cust($vehicleid, $customerno) {
    $checkpointmanager = new CheckpointManager($customerno);
    $checkpoints = $checkpointmanager->getchksforvehicle($vehicleid);
    return $checkpoints;
}

function getdate_IST() {
    $ServerDate_IST = strtotime(date("Y-m-d H:i:s"));
    return $ServerDate_IST;
}

function getchkrep($STdate, $EDdate, $Shour, $Ehour, $vehicleid, $checkpoints, $vehicle) {
    $report = pullreport($STdate, $EDdate, $Shour, $Ehour, $vehicleid, $_SESSION['customerno']);
    if (isset($report)) {
        foreach ($report as $thisreport) {
            $thisreport->vehicleno = $vehicle->vehicleno;
            if (isset($checkpoints)) {
                foreach ($checkpoints as $thischeckpoint) {
                    if ($thisreport->chkid == $thischeckpoint->checkpointid) {
                        $thisreport->cname = $thischeckpoint->cname;
                    }
                }
            }
        }
    }
    return $report;
}

function pullreport($STdate, $EDdate, $Shour, $Ehour, $vehicleid, $customerno) {
    $STdate = date("Y-m-d", strtotime($STdate));
    $EDdate = date("Y-m-d", strtotime($EDdate));
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $Query = "select * from V" . $vehicleid . " WHERE date BETWEEN '$STdate $Shour:00' AND '$EDdate $Ehour:59'";
    $CHKMS = array();
    try {
        $db = new PDO($path);
        $result = $db->query($Query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $CHKM = new VOCHKM();
                $CHKM->chkid = $row["chkid"];
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

function gettemperature_fromsqlite($location, $time, $vehicle) {
    $Query = "select analog1,analog2,analog3,analog4 from unithistory WHERE lastupdated <= '$time' ORDER BY lastupdated DESC LIMIT 1";
    $CHKMS = array();
    $temp_coversion = new TempConversion();
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
                    $temp_coversion->rawtemp = $$s;
                    $temp_coversion->unit_type = $vehicle->get_conversion;
                    $result_temp = getTempUtil($temp_coversion);
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

function displayrep($reports, $vehicleid, $vehicle_number) {
    $unitno = getunitno($vehicleid);
    $vehicle = getunitdetailsfromvehid($vehicleid);
    $customerno = $_SESSION['customerno'];
    $rows = "";
    if (isset($reports) && $reports != "") {
        $post_date = $_REQUEST['STdate'];
        $post_date_time = date("Y-m-d", strtotime($post_date)) . ' ' . $_REQUEST['STime'] . ':00';
        $odo_init = (float) get_odometer_reading($vehicleid, $post_date_time, $_SESSION['customerno'], $unitno);
        $cumulative_odo = 0;
        foreach ($reports as $report) {
            if ($report->cname != "") {
                $chketa = checkETA($_SESSION['customerno'], $report->chkid, $report->starttime);
                $rows .= "<tr><td>$vehicle_number</td>";
                $rows .= "<td>$report->cname</td>";
                $rows .= "<td>" . convertDateToFormat($report->starttime, speedConstants::DEFAULT_DATETIME) . "</td>";
                if ($_SESSION['temp_sensors'] == '1') {
                    $temperature = 0;
                    $userdate = date('Y-m-d', strtotime($report->starttime));
                    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                    if (file_exists($location)) {
                        $location = "sqlite:" . $location;
                        $temperature = gettemperature_fromsqlite($location, $report->starttime, $vehicle);
                    }
                    $rows .= "<td>$temperature <sup>0</sup>C</td>";
                }
                if (isset($report->endtime) && isset($report->timespent)) {
                    $rows .= "<td>" . convertDateToFormat($report->endtime, speedConstants::DEFAULT_DATETIME) . "</td>";
                    if ($_SESSION['temp_sensors'] == '1') {
                        $temperature = 0;
                        $customerno = $_SESSION['customerno'];
                        $userdate = date('Y-m-d', strtotime($report->endtime));
                        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                        if (file_exists($location)) {
                            $location = "sqlite:" . $location;
                            $temperature = gettemperature_fromsqlite($location, $report->endtime, $vehicle);
                        }
                        $rows .= "<td>$temperature <sup>0</sup>C</td>";
                    }
                    $rows .= "<td>" . m2h($report->timespent) . "</td>";
                } else {
                    $rows .= "<td>Not Left</td>";
                    if ($_SESSION['temp_sensors'] == '1') {
                        $rows .= "<td>N/A</td>";
                    }
                    $rows .= "<td>N/A</td>";
                }
                //$rows .="<td>". f."</td>";
                if (isset($chketa)) {
                    $timearrivel = date("H:i:s", strtotime($report->starttime));
                    foreach ($chketa as $eta) {
                        if ($eta->endtime == "0000-00-00 00:00:00") {
                            if ($report->starttime >= $eta->starttime) {
                                if (strtotime($timearrivel) > strtotime($eta->eta)) {
                                    $diff = round((strtotime($timearrivel) - strtotime($eta->eta)) / 60);
                                    $rows .= "<td>" . convertDateToFormat($eta->eta, speedConstants::DEFAULT_TIME) . "</td>";
                                    $rows .= "<td>Delayed By " . m2h($diff) . " </td>";
                                } else {
                                    $diff = round((strtotime($eta->eta) - strtotime($timearrivel)) / 60);
                                    $rows .= "<td>" . convertDateToFormat($eta->eta, speedConstants::DEFAULT_TIME) . "</td>";
                                    $rows .= "<td>Early By : " . m2h($diff) . " </td>";
                                }
                            }
                        } else if ($eta->endtime != "0000-00-00 00:00:00") {
                            if ($report->starttime >= $eta->starttime && $report->starttime <= $eta->endtime) {
                                if (strtotime($timearrivel) > strtotime($eta->eta)) {
                                    $diff = round((strtotime($timearrivel) - strtotime($eta->eta)) / 60);
                                    $rows .= "<td>" . convertDateToFormat($eta->eta, speedConstants::DEFAULT_TIME) . "</td>";
                                    $rows .= "<td>Delayed By " . m2h($diff) . " </td>";
                                } else {
                                    $diff = round((strtotime($eta->eta) - strtotime($timearrivel)) / 60);
                                    $rows .= "<td>" . convertDateToFormat($eta->eta, speedConstants::DEFAULT_TIME) . "</td>";
                                    $rows .= "<td>Early By : " . m2h($diff) . " </td>";
                                }
                            }
                        } else {
                            $rows .= "<td>N/A</td>";
                            $rows .= "<td>N/A</td>";
                        }
                    }
                } else {
                    $rows .= "<td>N/A</td>";
                    $rows .= "<td>N/A</td>";
                }
                /* ak added, for calculating cumulative distance travelled */
                if (isset($report->endtime)) {
                    $cur_odo = get_odometer_reading($vehicleid, $report->endtime, $customerno, $unitno);
                    if ($cur_odo < $odo_init) {
                        $max = GetOdometer_Max($vehicleid, $report->endtime, $customerno, $unitno);
                        $cur_odo = $max + $odo_init;
                    }
                    if (is_numeric($cur_odo) && is_numeric($odo_init)) {
                        $cumulative_odo += round(($cur_odo - $odo_init) / 1000, 2);
                    }
                    //$rows .= "<td>" . $cumulative_odo . "-".$cur_odo."-".$odo_init."-".$report->endtime."</td>";
                    $odo_init = $cur_odo;
                }
                $rows .= "<td>" . $cumulative_odo . "</td>";
                /**/
                $rows .= "</tr>";
            }
        }
    }
    echo $rows;
}

function GetOdometer_Max($vehicleid, $s_date_time, $customerno, $unitno) {
    $s_date = date("Y-m-d", strtotime($s_date_time));
    $full_path = "../../customer/$customerno/unitno/$unitno/sqlite/$s_date.sqlite";
    if (file_exists($full_path)) {
        $path = "sqlite:../../customer/$customerno/unitno/$unitno/sqlite/$s_date.sqlite";
        $Query = "SELECT max(vehiclehistory.odometer) as odometerm from vehiclehistory
        WHERE vehiclehistory.vehicleid=$vehicleid AND vehiclehistory.lastupdated >= '$s_date_time'
        ORDER BY vehiclehistory.lastupdated asc Limit 1";
        try {
            $db = new PDO($path);
            $result = $db->query($Query);
            if (isset($result) && $result != "") {
                foreach ($result as $row) {
                    $odometer = $row['odometerm'];
                }
                return $odometer;
            }
        } catch (Exception $e) {
            return '';
        }
    } else {
        return "";
    }
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

function getchkreportpdf($customerno, $STdate, $EDdate, $Shour, $Ehour, $temp_sensors, $vgroupname = null) {
    $title = 'Checkpoint Report';
    $subTitle = array("Vehicle No: All Vehicles", "Start Date: $STdate $Shour", "End Date: $EDdate $Ehour");
    if (!is_null($vgroupname)) {
        $subTitle[] = "Group-name: $vgroupname";
    }
    $finalreport = pdf_header($title, $subTitle);
    $vehiclemanager = new VehicleManager($customerno);
    $um = new UnitManager($customerno);
    $checkpointmanager = new CheckpointManager($customerno);
    $VEHICLES = $vehiclemanager->Get_All_Vehicles_SQLite();
    foreach ($VEHICLES as $key_vehicleid => $vehicle_name) {
        $vehicleid = $key_vehicleid;
        $vehicleno = $vehicle_name['vehicleno'];
        $unitno = $um->getunitnofromdeviceid($vehicleid);
        $vehicle = $um->getunitdetailsfromvehid($vehicleid);
        $report = pullreport($STdate, $EDdate, $Shour, $Ehour, $vehicleid, $customerno);
        if (count($report) == 0) {
            continue;
        }
        if (isset($report)) {
            foreach ($report as $thisreport) {
                $thisreport->vehicleno = $vehicleno;
                $checkpoints = $checkpointmanager->getchksforvehicle($vehicleid);
                if (isset($checkpoints)) {
                    foreach ($checkpoints as $thischeckpoint) {
                        if ($thisreport->chkid == $thischeckpoint->checkpointid) {
                            $thisreport->cname = $thischeckpoint->cname;
                        }
                    }
                }
            }
        }
        if (isset($report) && count($report) > 0) {
            $chkreport = processchkrep($report);
        }
        $fromdate = date(speedConstants::DEFAULT_DATE, strtotime($STdate));
        $todate = date(speedConstants::DEFAULT_DATE, strtotime($EDdate));
        $finalreport .= "
        <hr/>
        <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
        <tbody>
        <tr><td colspan='9' style='background-color:#CCCCCC;font-weight:bold;'>Vehicle No. $vehicleno</td></tr>
        ";
        $finalreport .= "
            <tr style='background-color:#d8d5d6;font-weight:bold;'>
                <td style='width:300px;height:auto;'>Checkpoint Name</td>
                <td style='width:100px;height:auto;'>In Time</td>";
        if ($temp_sensors == '1') {
            $finalreport .= "<td style='width:50px;height:auto;'>In Temp.</td>";
        }
        $finalreport .= "<td style='width:100px;height:auto;'>Out Time</td>";
        if ($temp_sensors == '1') {
            $finalreport .= "<td style='width:50px;height:auto;'>Out Temp.</td>";
        }
        $finalreport .= " <td style='width:90px;height:auto;'>Time Spent [Hours: Minutes]</td>";
        $finalreport .= " <td style='width:50px;height:auto;'>ETA</td>";
        $finalreport .= " <td style='width:90px;height:auto;'>Status(HH:MM)</td>
             <td style='width:70px;height:auto;'>Cumulative Distance [KM]</td>
            </tr>";
        if (isset($chkreport) && $chkreport != "") {
            /* ak added, for calculating cumulative distance travelled */
            $post_date = $_REQUEST['sdate'];
            $post_date_time = date("Y-m-d", strtotime($post_date)) . ' ' . $_REQUEST['stime'] . ':00';
            $odo_init = (float) get_odometer_reading($vehicleid, $post_date_time, $customerno, $unitno);
            $cumulative_odo = 0;
            /**/
            foreach ($chkreport as $thisreport) {
                if ($thisreport->cname != "") {
                    $chketa = $checkpointmanager->checkmodifyETA($thisreport->chkid, $thisreport->starttime);
                    $finalreport .= "<tr><td style='width:300px;height:auto;'>$thisreport->cname</td>";
                    $finalreport .= "<td style='width:100px;height:auto;'>" . convertDateToFormat($thisreport->starttime, speedConstants::DEFAULT_DATETIME) . "</td>";
                    if ($temp_sensors == '1') {
                        $temperature = 0;
                        $userdate = date('Y-m-d', strtotime($report->starttime));
                        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                        if (file_exists($location)) {
                            $location = "sqlite:" . $location;
                            $temperature = gettemperature_fromsqlite($location, $thisreport->starttime, $vehicle);
                        }
                        $finalreport .= "<td style='width:50px;height:auto;'>$temperature <sup>0</sup>C</td>";
                    }
                    if (isset($thisreport->endtime) && isset($thisreport->timespent)) {
                        $finalreport .= "<td style='width:100px;height:auto;'>" . convertDateToFormat($thisreport->endtime, speedConstants::DEFAULT_DATETIME) . "</td>";
                        if ($temp_sensors == '1') {
                            $temperature = 0;
                            $userdate = date('Y-m-d', strtotime($report->endtime));
                            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                            if (file_exists($location)) {
                                $location = "sqlite:" . $location;
                                $temperature = gettemperature_fromsqlite($location, $thisreport->endtime, $vehicle);
                            }
                            $finalreport .= "<td style='width:50px;height:auto;'>$temperature <sup>0</sup>C</td>";
                        }
                        $finalreport .= "<td style='width:150px;height:auto;'>" . m2h($thisreport->timespent) . "</td>";
                    } else {
                        $finalreport .= "<td style='width:150px;height:auto;'>Not Left</td>";
                        if ($temp_sensors == '1') {
                            $finalreport .= "<td style='width:50px;height:auto;'>N/A</td>";
                        }
                        $finalreport .= "<td style='width:100px;height:auto;'>N/A</td>";
                    }
                    if (isset($chketa)) {
                        $timearrivel = convertDateToFormat($thisreport->starttime, speedConstants::DEFAULT_TIME);
                        foreach ($chketa as $eta) {
                            if ($eta->endtime == "0000-00-00 00:00:00") {
                                if ($thisreport->starttime >= $eta->starttime) {
                                    if (strtotime($timearrivel) > strtotime($eta->eta)) {
                                        $diff = round((strtotime($timearrivel) - strtotime($eta->eta)) / 60);
                                        $finalreport .= "<td>" . convertDateToFormat($eta->eta, speedConstants::DEFAULT_TIME) . "</td>";
                                        $finalreport .= "<td>Delayed By " . m2h($diff) . " </td>";
                                    } else {
                                        $diff = round((strtotime($eta->eta) - strtotime($timearrivel)) / 60);
                                        $finalreport .= "<td>" . convertDateToFormat($eta->eta, speedConstants::DEFAULT_TIME) . "</td>";
                                        $finalreport .= "<td>Early By : " . m2h($diff) . " </td>";
                                    }
                                }
                            } else if ($eta->endtime != "0000-00-00 00:00:00") {
                                if ($thisreport->starttime >= $eta->starttime && $thisreport->starttime <= $eta->endtime) {
                                    if (strtotime($timearrivel) > strtotime($eta->eta)) {
                                        $diff = round((strtotime($timearrivel) - strtotime($eta->eta)) / 60);
                                        $finalreport .= "<td>" . convertDateToFormat($eta->eta, speedConstants::DEFAULT_TIME) . "</td>";
                                        $finalreport .= "<td>Delayed By " . m2h($diff) . " </td>";
                                    } else {
                                        $diff = round((strtotime($eta->eta) - strtotime($timearrivel)) / 60);
                                        $finalreport .= "<td>" . convertDateToFormat($eta->eta, speedConstants::DEFAULT_TIME) . "</td>";
                                        $finalreport .= "<td>Early By : " . m2h($diff) . " </td>";
                                    }
                                }
                            } else {
                                $finalreport .= "<td>N/A</td>";
                                $finalreport .= "<td>N/A</td>";
                            }
                        }
                    } else {
                        $finalreport .= "<td>N/A</td>";
                        $finalreport .= "<td>N/A</td>";
                    }
                    /* ak added, for calculating cumulative distance travelled */
                    $cur_odo = get_odometer_reading($vehicleid, $thisreport->starttime, $customerno, $unitno);
                    if ($cur_odo < $odo_init) {
                        $max = GetOdometer_Max($vehicleid, $thisreport->starttime, $customerno, $unitno);
                        $cur_odo = $max + $odo_init;
                    }
                    $cumulative_odo += round(($cur_odo - $odo_init) / 1000, 2);
                    $odo_init = $cur_odo;
                    $finalreport .= "<td>$cumulative_odo</td>";
                    /**/
                    $finalreport .= "</tr>";
                }
            }
        }
        $finalreport .= '</tbody>';
        $finalreport .= "</table>";
    }
    $finalreport .= "
    <page_footer>
                    [[page_cu]]/[[page_nb]]
    </page_footer>";
    $finalreport .= "<hr style='margin-top:5px;'>";
    $formatdate1 = date(speedConstants::DEFAULT_DATETIME);
    $finalreport .= "<div align='right' style='text-align:center;'> Report Generated On: $formatdate1 </div>";
    echo $finalreport;
}

function getchkreportcsv($customerno, $STdate, $EDdate, $Shour, $Ehour, $temp_sensors, $vgroupname = null) {
    $title = 'Checkpoint Report';
    $subTitle = array("Vehicle No: All Vehicles", "Start Date: $STdate $Shour", "End Date: $EDdate $Ehour");
    if (!is_null($vgroupname)) {
        $subTitle[] = "Group-name: $vgroupname";
    }
    $finalreport = excel_header($title, $subTitle);
    $vehiclemanager = new VehicleManager($customerno);
    $VEHICLES = $vehiclemanager->Get_All_Vehicles_SQLite();
    foreach ($VEHICLES as $key_vehicleid => $vehicle_name) {
        $vehicleid = $key_vehicleid;
        $vehicleno = $vehicle_name['vehicleno'];
        $unitno = getunitno_pdf($vehicleid, $customerno);
        $vehicle = getunitdetailsfromvehid_pdf($vehicleid, $customerno);
        $report = pullreport($STdate, $EDdate, $Shour, $Ehour, $vehicleid, $customerno);
        if (count($report) == 0) {
            continue;
        }
        if (isset($report)) {
            foreach ($report as $thisreport) {
                $thisreport->vehicleno = $vehicleno;
                $checkpoints = getcheckpoints_cust($vehicleid, $customerno);
                if (isset($checkpoints)) {
                    foreach ($checkpoints as $thischeckpoint) {
                        if ($thisreport->chkid == $thischeckpoint->checkpointid) {
                            $thisreport->cname = $thischeckpoint->cname;
                        }
                    }
                }
            }
        }
        if (isset($report) && count($report) > 0) {
            $chkreport = processchkrep($report);
        }
        $finalreport .= "
                            <table id='search_table_2' style='width: 1050px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                <tbody>
                                    <tr><td colspan='10' style='background-color:#CCCCCC;text-align:center;'><b>Vehicle No. $vehicleno</b></td></tr>
                                    <tr style='background-color:#d8d5d6;font-weight:bold;'>
                                        <td style='width:100px;height:auto; text-align: center;'></td>
                                        <td style='width:450px;height:auto; text-align: center;'>Checkpoint Name</td>
                                        <td style='width:150px;height:auto; text-align: center;'>In Time</td>";
        if ($temp_sensors == '1') {
            $finalreport .= " <td style='width:150px;height:auto; text-align: center;'>In Temp</td> ";
        }
        $finalreport .= "<td style='width:150px;height:auto; text-align: center;'>Out Time</td>";
        if ($temp_sensors == '1') {
            $finalreport .= " <td style='width:150px;height:auto; text-align: center;'>Out Temp</td> ";
        }
        $finalreport .= " <td style='width:150px;height:auto; text-align: center;'>Time Spent [Hours:Minutes]</td>";
        $finalreport .= " <td style='width:50px;height:auto; text-align: center;'>ETA</td>";
        $finalreport .= " <td style='width:200px;height:auto; text-align: center;'>Status(HH:MM)</td>
                                   <td style='width:200px;height:auto; text-align: center;'>Cumulative Distance[KM]</td>
                                    </tr>";
        if (isset($chkreport) && $chkreport != "") {
            /* ak added, for calculating cumulative distance travelled */
            $post_date = $_REQUEST['sdate'];
            $post_date_time = date("Y-m-d", strtotime($post_date)) . ' ' . $_REQUEST['stime'] . ':00';
            $odo_init = (float) get_odometer_reading($vehicleid, $post_date_time, $customerno, $unitno);
            $cumulative_odo = 0;
            /**/
            foreach ($chkreport as $thisreport) {
                if ($thisreport->cname != "") {
                    $chketa = checkETA($customerno, $thisreport->chkid, $thisreport->starttime);

                    $finalreport .= "<tr><td style='width:100px;height:auto; text-align: center;'></td>";
                    $finalreport .= "<td style='width:450px;height:auto; text-align: center;'>$thisreport->cname</td>";

                    $finalreport .= "<td style='width:150px;height:auto; text-align: center;'>" . convertDateToFormat($thisreport->starttime, speedConstants::DEFAULT_DATETIME) . "</td>";
                    if ($temp_sensors == '1') {
                        $temperature = 0;
                        $userdate = date("Y-m-d", strtotime($thisreport->starttime));
                        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                        if (file_exists($location)) {
                            $location = "sqlite:" . $location;
                            $temperature = gettemperature_fromsqlite($location, $thisreport->starttime, $vehicle);
                        }
                        $finalreport .= "<td style='width:150px;height:auto; text-align: center;'>$temperature <sup>0</sup>C</td>";
                    }
                    if (isset($thisreport->endtime) && isset($thisreport->timespent)) {
                        $finalreport .= "<td style='width:150px;height:auto; text-align: center;'>" . convertDateToFormat($thisreport->endtime, speedConstants::DEFAULT_DATETIME) . "</td>";
                        if ($temp_sensors == '1') {
                            $temperature = 0;
                            $userdate = date("Y-m-d", strtotime($thisreport->endtime));
                            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                            if (file_exists($location)) {
                                $location = "sqlite:" . $location;
                                $temperature = gettemperature_fromsqlite($location, $thisreport->endtime, $vehicle);
                            }
                            $finalreport .= "<td style='width:150px;height:auto; text-align: center;'>$temperature <sup>0</sup>C</td>";
                        }
                        $finalreport .= "<td style='width:200px;height:auto; text-align: center;'>" . m2h($thisreport->timespent) . "</td>";
                    } else {
                        $finalreport .= "<td style='width:150px;height:auto; text-align: center;'>Not Left</td>";
                        if ($temp_sensors == '1') {
                            $finalreport .= "<td style='width:150px;height:auto; text-align: center;'>N/A</td>";
                        }
                        $finalreport .= "<td style='width:200px;height:auto; text-align: center;'>N/A</td>";
                    }
                    if (isset($chketa)) {
                        $timearrivel = convertDateToFormat($thisreport->starttime, speedConstants::DEFAULT_TIME);
                        foreach ($chketa as $eta) {
                            if ($eta->endtime == "0000-00-00 00:00:00") {
                                if ($thisreport->starttime >= $eta->starttime) {
                                    if (strtotime($timearrivel) > strtotime($eta->eta)) {
                                        $diff = round((strtotime($timearrivel) - strtotime($eta->eta)) / 60);
                                        $finalreport .= "<td>" . convertDateToFormat($eta->eta, speedConstants::DEFAULT_TIME) . "</td>";
                                        $finalreport .= "<td>Delayed By :" . m2h($diff) . " </td>";
                                    } else {
                                        $diff = round((strtotime($eta->eta) - strtotime($timearrivel)) / 60);
                                        $finalreport .= "<td>" . convertDateToFormat($eta->eta, speedConstants::DEFAULT_TIME) . "</td>";
                                        $finalreport .= "<td>Early By : " . m2h($diff) . " </td>";
                                    }
                                }
                            } else if ($eta->endtime != "0000-00-00 00:00:00") {
                                if ($thisreport->starttime >= $eta->starttime && $thisreport->starttime <= $eta->endtime) {
                                    if (strtotime($timearrivel) > strtotime($eta->eta)) {
                                        $diff = round((strtotime($timearrivel) - strtotime($eta->eta)) / 60);
                                        $finalreport .= "<td>" . convertDateToFormat($eta->eta, speedConstants::DEFAULT_TIME) . "</td>";
                                        $finalreport .= "<td>Delayed By :" . m2h($diff) . " </td>";
                                    } else {
                                        $diff = round((strtotime($eta->eta) - strtotime($timearrivel)) / 60);
                                        $finalreport .= "<td>" . convertDateToFormat($eta->eta, speedConstants::DEFAULT_TIME) . "</td>";
                                        $finalreport .= "<td>Early By : " . m2h($diff) . " </td>";
                                    }
                                }
                            } else {
                                $finalreport .= "<td>N/A</td>";
                                $finalreport .= "<td>N/A</td>";
                            }
                        }
                    } else {
                        $finalreport .= "<td>N/A</td>";
                        $finalreport .= "<td>N/A</td>";
                    }
                    /* ak added, for calculating cumulative distance travelled */
                    $cur_odo = get_odometer_reading($vehicleid, $thisreport->starttime, $customerno, $unitno);
                    if ($cur_odo < $odo_init) {
                        $max = GetOdometer_Max($vehicleid, $thisreport->starttime, $customerno, $unitno);
                        $cur_odo = $max + $odo_init;
                    }
                    $cumulative_odo += round(($cur_odo - $odo_init) / 1000, 2);
                    $odo_init = $cur_odo;
                    $finalreport .= "<td style='text-align: center;'>" . $cumulative_odo . "</td>";
                    /**/
                    $finalreport .= "</tr>";
                }
            }
        }
        $finalreport .= '</tbody>';
        $finalreport .= "</table><hr/><br/>";
    }
    echo $finalreport;
}

function checkETA($customerno, $checkpointid, $starttime) {
    $chk = new CheckpointManager($customerno);
    $checkmodify = $chk->checkmodifyETA($checkpointid, $starttime);
    return $checkmodify;
}

function table_html($vehicleno) {
    echo '
    <table class="table newTable">
    <thead>
    <tr><th id="formheader" colspan="100%">Vehicle No. ' . $vehicleno . '</th></tr>
    <tr style="background:#d8d5d6;font-weight:bold;">
        <td>Vehicle No</td>
        <td>Checkpoint Name</td>
        <td>In Time</td>';
    if ($_SESSION['temp_sensors'] == '1') {
        echo '<td>In Temperature</td>';
    }
    echo '<td>Out Time</td>';
    if ($_SESSION['temp_sensors'] == '1') {
        echo '<td>Out Temperature</td>';
    }
    echo '<td>Time Spent [Hours:Minutes]</td>
        <td>ETA</td>
        <td>Status(HH:MM)</td>
        <td>Cumulative Distance [KM]</td>
    </tr>
    </thead><tbody>';
}

?>
