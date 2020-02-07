<?php
include_once '../../lib/bo/RouteManager.php';
include_once '../../lib/bo/CheckpointManager.php';
include_once '../../lib/bo/DeviceManager.php';
include_once '../../lib/bo/UnitManager.php';
include_once '../../lib/bo/DriverManager.php';
include_once '../../lib/system/utilities.php';
include_once '../../lib/components/ajaxpage.inc.php';
include_once '../../lib/comman_function/reports_func.php';
include '../common/map_common_functions.php';

if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
}
class RouteOutput {}

class VODatacap {}
function getroutes() {
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routes = $routemanager->get_all_routes();
    return $routes;
}

function getroutes_enh() {
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routes = $routemanager->get_all_routes_enh();
    return $routes;
}

function getcheckpoints($routeid) {
    $routemanager = new RouteManager($_SESSION['customerno']);
    $checkpoints = $routemanager->getchksforroute($routeid);
    return $checkpoints;
}

function getcheckpointname($checkpointid) {
    $routemanager = new RouteManager($_SESSION['customerno']);
    $checkpoints = $routemanager->getchknameforroute($checkpointid);
    return $checkpoints;
}

function getcheckpointeta($checkpointid) {
    $routemanager = new RouteManager($_SESSION['customerno']);
    $checkpoints = $routemanager->getchketa($checkpointid);
    return $checkpoints;
}

function getvehicles($routeid) {
    $routemanager = new RouteManager($_SESSION['customerno']);
    $vehicles = $routemanager->getvehiclesforroute($routeid);
    return $vehicles;
}

function getvehicles_withroute() {
    $rm = new RouteManager($_SESSION['customerno']);
    $routedata = $rm->getvehiclesByRoute();
    return $routedata;
}

function getStartLocation($routeid) {
    $rm = new RouteManager($_SESSION['customerno']);
    $startlocation = $rm->getStartLocation($routeid);
    return $startlocation;
}

function getEndLocation($routeid) {
    $rm = new RouteManager($_SESSION['customerno']);
    $startlocation = $rm->getEndLocation($routeid);
    return $startlocation;
}

function getStartTime($vehicleid, $checkpointid) {
    // $REPORT = array();
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/reports/chkreport.sqlite";
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT * FROM V$vehicleid WHERE chkid = $checkpointid AND status = '1' Order By date DESC Limit 1";
    $result = $db->query($Query);
    if (isset($result) && $result != '') {
        foreach ($result as $row) {
            $Datacap = new VORoute();
            $Datacap->chkrepid = $row['chkrepid'];
            $Datacap->chkid = $row['chkid'];
            $Datacap->date = $row['date'];
            // $REPORT[] = $Datacap;
        }
        return $Datacap;
    }
    return null;
}

function getEndTime($vehicleid, $checkpointid, $date) {
    // $REPORT = array();
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/reports/chkreport.sqlite";
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT * FROM V$vehicleid WHERE chkid = $checkpointid AND status = '0' AND date > '$date' Order By date DESC Limit 1";
    $result = $db->query($Query);
    if (isset($result) && $result != '') {
        foreach ($result as $row) {
            $Datacap = new VORoute();
            $Datacap->chkrepid = $row['chkrepid'];
            $Datacap->chkid = $row['chkid'];
            $Datacap->date = $row['date'];
            // $REPORT[] = $Datacap;
        }
        return $Datacap;
    }
    return null;
}

function getStdTime($routeid) {
    $rm = new RouteManager($_SESSION['customerno']);
    $time = $rm->getStdTime($routeid);
    return $time;
}

function getLastCrossed($routeid, $vehicleid, $date) {
    $chk = array();
    $rm = new RouteManager($_SESSION['customerno']);
    $lastchk = $rm->getAllChkptForRoute($routeid);
    foreach ($lastchk as $checkpoint) {
        $chk[] = $checkpoint->checkpointid;
    }
    $chkpt = implode(',', $chk);
    //echo $chkpt;echo "</br>";
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/reports/chkreport.sqlite";
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT * FROM V$vehicleid WHERE chkid IN($chkpt) AND status = '1' Order By date DESC Limit 1";
    $result = $db->query($Query);
    if (isset($result) && $result != '') {
        foreach ($result as $row) {
            $Datacap = new VORoute();
            $Datacap->chkrepid = $row['chkrepid'];
            $Datacap->chkid = $row['chkid'];
            $Datacap->date = $row['date'];
            $Datacap->cname = getcheckpointname($row['chkid']);
            $Datacap->eta = getcheckpointeta($row['chkid']);
            // $REPORT[] = $Datacap;
        }
        return $Datacap;
    }
    return null;
}

function getCheckCompleteTrip($vehicleid, $date, $endchkpt) {
    $customerno = $_SESSION['customerno'];
    $locationchk = "../../customer/$customerno/reports/chkreport.sqlite";
    $pathnew = "sqlite:$locationchk";
    $dbchk = new PDO($pathnew);
    $Querychk = $dbchk->prepare("SELECT * FROM V$vehicleid WHERE chkid = $endchkpt AND status = '0' AND date > '$date' Limit 1");
    $Querychk->execute();
    $resultchk = $Querychk->fetchAll();
    if (isset($resultchk) && !empty($resultchk)) {
        foreach ($resultchk as $rowchk) {
            $rowchk['date'];
        }
    } else {
        return 0;
    }
}

function getRouteDistance($routeid) {

    $rm = new RouteManager($_SESSION['customerno']);
    $lastchk = $rm->getAllChkptForRoute($routeid);
    $distancelast = end($lastchk);
    return $distancelast->distance;
}

function getDistanceCovered($vehicleid, $date, $odometer, $endchkpt, $startchkpt) {
    $userdate = date('Y-m-d', strtotime($date));
    $customerno = $_SESSION['customerno'];
    $unitno = getunitnotemp($vehicleid);
    //echo $date;
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
    $locationchk = "../../customer/$customerno/reports/chkreport.sqlite";
    $pathnew = "sqlite:$locationchk";
    $dbchk = new PDO($pathnew);
    $Querychk = $dbchk->prepare("SELECT * FROM V$vehicleid WHERE chkid = $endchkpt AND status = '0' AND date > '$date' Limit 1");
    $Querychk->execute();
    $resultchk = $Querychk->fetchAll();
    if (isset($resultchk) && !empty($resultchk)) {
        foreach ($resultchk as $rowchk) {
            $time = $rowchk['date'];
            if (file_exists($location)) {
                $path = "sqlite:$location";
                $db = new PDO($path);
                $Query = "SELECT * FROM vehiclehistory where vehicleid = $vehicleid AND lastupdated >= '$time' Order By lastupdated ASC Limit 1 ";
                $result = $db->query($Query);
                if (isset($result) && $result != '') {
                    foreach ($result as $row) {
                        $lastodometer = $row['odometer'];
                    }
                } else {
                    $lastodometer = $odometer;
                }
            }
        }
    } else {
        $lastodometer = $odometer;
    }

    //echo "SELECT * FROM V$vehicleid WHERE chkid = $startchkpt AND status = '1' AND date >= '$date' Limit 1";
    $Querychkstart = $dbchk->prepare("SELECT * FROM V$vehicleid WHERE chkid = $startchkpt AND status = '1' AND date >= '$date' Limit 1");
    $Querychkstart->execute();
    $resultchkstart = $Querychkstart->fetchAll();
    if (isset($resultchkstart) && !empty($resultchkstart)) {
        foreach ($resultchkstart as $rowchkstart) {
            $timestart = $rowchkstart['date'];
            if (file_exists($location)) {
                $path = "sqlite:$location";
                $db = new PDO($path);
                $Query = "SELECT * FROM vehiclehistory where vehicleid = $vehicleid AND lastupdated >= '$timestart' Order By lastupdated ASC Limit 1 ";
                $result = $db->query($Query);
                if (isset($result) && $result != '') {
                    foreach ($result as $row) {
                        $firstodometer = $row['odometer'];
                    }
                }
            }
        }
    }

    return round(($lastodometer - $firstodometer) / 1000, 2);
}

function getunitnotemp($vehicleid) {
    $um = new UnitManager($_SESSION['customerno']);
    $unitno = $um->getuidfromvehicleid($vehicleid);
    return $unitno;
}

function getvehicleno($vehicleid) {
    $routemanager = new RouteManager($_SESSION['customerno']);
    $vehicles = $routemanager->getvehiclenoforroute($vehicleid);
    return $vehicles;
}

function getdate_IST() {
    $ServerDate_IST = strtotime(date("Y-m-d H:i:s"));
    return $ServerDate_IST;
}

function route_report_hist($routeid, $startdate, $enddate) {
    $routeid = GetSafeValueString($routeid, "string");
    $SDdate = GetSafeValueString($startdate, 'string');
    $EDdate = GetSafeValueString($enddate, 'string');
    $checkpoints = getcheckpoints($routeid);
    $vehicles = getvehicles($routeid);
    $lastelement = end($checkpoints);
    $firstelement = array_shift(array_values($checkpoints));

    $rows = "";
    $rows .= "<table>
                <thead>
                <tr>
                <th id='formheader' colspan='100%'>Route Report From $SDdate To $EDdate</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                <td colspan='100%'>
                <span id='error' name='error' style='display: none;'>Data Not Available</span>
                </td>
                </tr>
                <tr>
                <th>Vehicle No.</th>
                <th></th>";
    foreach ($checkpoints as $checkpoint) {
        $cname = getcheckpointname($checkpoint->checkpointid);
        $rows .= "<th id='$checkpoint->checkpointid'>$cname</th>";
    }
    $rows .= "</tr>";
    foreach ($vehicles as $vehicle) {
        $reportsequences = report_desc_seq($SDdate, $vehicle->vehicleid, $_SESSION['customerno'], $firstelement->checkpointid, $lastelement->checkpointid);
        $reportsequencesout = report_desc_out_seq($SDdate, $vehicle->vehicleid, $_SESSION['customerno'], $firstelement->checkpointid, $lastelement->checkpointid);
        $vehicleno = getvehicleno($vehicle->vehicleid);
        $rows .= "<tr>";
        $rows .= "<td rowspan='2' id='$vehicle->vehicleid'>$vehicleno</td>";
        $rows .= "<td>In</td>";
        foreach ($checkpoints as $checkpoint) {
            $breakrow = false;
            foreach ($reportsequences as $reportsequence) {
                if ($checkpoint->checkpointid == $reportsequence->chkiddesc) {
                    if ($oldinid != $reportsequence->chkiddesc . '_' . $vehicle->vehicleid) {
                        $reports = pullreportin($SDdate, $vehicle->vehicleid, $checkpoint->checkpointid, $_SESSION['customerno']);
                        $rows .= "<td id='$vehicle->vehicleid'>$reports->datein</td>";
                        $oldinid = $reportsequence->chkiddesc . '_' . $vehicle->vehicleid;
                        $breakrow = true;
                    }
                }
            }
            if ($breakrow == false) {
                $rows .= "<td></td>";
            }
        }
        $rows .= "</tr>";
        $rows .= "<tr>";
        $rows .= "<td>Out</td>";
        foreach ($checkpoints as $checkpoint) {
            $breakrow = false;
            foreach ($reportsequences as $reportsequence) {
                if ($checkpoint->checkpointid == $reportsequence->chkiddesc) {
                    if ($oldid != $reportsequence->chkiddesc . '_' . $vehicle->vehicleid) {
                        $report = pullreportout($SDdate, $vehicle->vehicleid, $checkpoint->checkpointid, $_SESSION['customerno']);
                        $reportss = pullreportin($SDdate, $vehicle->vehicleid, $checkpoint->checkpointid, $_SESSION['customerno']);

                        if (strtotime($reportss->datein) > strtotime($report->dateout)) {
                            $rows .= "<td id='$vehicle->vehicleid'>Not Left</td>";
                        } else {
                            $rows .= "<td id='$vehicle->vehicleid'>$report->dateout</td>";
                        }

                        $oldid = $reportsequence->chkiddesc . '_' . $vehicle->vehicleid;
                        $breakrow = true;
                    }
                }
            }
            if ($breakrow == false) {
                $reportss = pullreportin($SDdate, $vehicle->vehicleid, $checkpoint->checkpointid, $_SESSION['customerno']);
                $report = pullreportout($SDdate, $vehicle->vehicleid, $checkpoint->checkpointid, $_SESSION['customerno']);
                if (strtotime($reportss->datein) > strtotime($report->dateout)) {
                    $rows .= "<td id='$vehicle->vehicleid'>Not Left</td>";
                } else {
                    $rows .= "<td id='$vehicle->vehicleid'></td>";
                }
            }
        }
        $rows .= "</tr>";
    }
    $rows .= "</tbody></table>";
    echo $rows;
}

function trip_route_report($routeid, $startdate, $enddate) {
    $routeid = GetSafeValueString($routeid, "string");
    $SDdate = GetSafeValueString($startdate, 'string');
    $EDdate = GetSafeValueString($enddate, 'string');
    $checkpoints = getcheckpoints($routeid);
    $vehicles = getvehicles($routeid);
    $chkElements = array_values($checkpoints);
    $firstelement = array_shift($chkElements);

    $title = 'Route Report';
    $subTitle = array(
        'Route: ' . $_POST['routeTxt'],
        "Start Date: $startdate",
        "End Date: $enddate",
    );
    $rows = table_header($title, $subTitle);

    $rows .= "<div style='width: 95%; overflow-x: scroll;'>
        <table>
        <tbody>
        <tr><td colspan='100%'><span id='error' name='error' style='display: none;'>Data Not Available</span></td></tr>
        <tr><th rowspan='2'>Vehicle No.</th>";

    foreach ($checkpoints as $checkpoint) {
        if ($checkpoint->checkpointid != $firstelement->checkpointid) {
            $rows .= "<th></th>";
        }

        $cname = getcheckpointname($checkpoint->checkpointid);
        $rows .= "<th colspan='3' id='$checkpoint->checkpointid'>$cname</th>";
    }
    $rows .= "</tr>";
    $rows .= "<tr>";

    foreach ($checkpoints as $checkpoint) {
        if ($checkpoint->checkpointid != $firstelement->checkpointid) {
            $rows .= "<th>In Transit(mins)</th>";
        }

        $rows .= "<th>In</th>";
        $rows .= "<th>Time Spent(mins)</th>";
        $rows .= "<th>Out</th>";
    }
    $rows .= "</tr>";
    $reportchks = Array();
    $tableRows = "";
    foreach ($vehicles as $vehicle) {
        $repids = getrepids($SDdate, $vehicle->vehicleid, $_SESSION['customerno'], $firstelement->checkpointid);
        if (isset($repids)) {
            $ocddtrans = 0;
            $lastReportId = end($repids);
            foreach ($repids as $key => $thisrepid) {
                $completeRow = '';
                $rowStart = '';
                $rowContent = '';
                $rowEnd = '';
                $repoldpkey = $thisrepid->repid - 1;
                if ($thisrepid->repid != $lastReportId->repid) {
                    $reponextkey = $repids[$key + 1]->repid;
                } else {
                    $reponextkey = 0;
                }
                $vehicleno = getvehicleno($vehicle->vehicleid);

                $rowStart .= "<tr>";
                $rowStart .= "<td id='$vehicle->vehicleid'>$vehicleno</td>";

                if (isset($checkpoints)) {
                    $firstObj = reset($checkpoints);
                    $lastObj = end($checkpoints);

                    for ($i = 0; $i < count($checkpoints); $i++) {
                        if (isset($checkpoints[$i + 1]->checkpointid) && $checkpoints[$i]->checkpointid == $firstObj->checkpointid) {
                            $chk_pulled = pulldetails_for_trip($SDdate, $vehicle->vehicleid, $_SESSION['customerno'], $repoldpkey, $checkpoints[$i]->checkpointid, $checkpoints[$i + 1]->checkpointid, null, $EDdate, $reponextkey);
                        } elseif ($checkpoints[$i]->checkpointid != $lastObj->checkpointid && $checkpoints[$i]->checkpointid != $firstObj->checkpointid) {
                            $chk_pulled = pulldetails_for_trip($SDdate, $vehicle->vehicleid, $_SESSION['customerno'], $repoldpkey, $checkpoints[$i]->checkpointid, $checkpoints[$i + 1]->checkpointid, $checkpoints[$i - 1]->checkpointid, $EDdate, $reponextkey);
                        } elseif ($checkpoints[$i]->checkpointid == $lastObj->checkpointid) {
                            $chk_pulled = pulldetails_for_trip($SDdate, $vehicle->vehicleid, $_SESSION['customerno'], $repoldpkey, $checkpoints[$i]->checkpointid, null, $checkpoints[$i - 1]->checkpointid, $EDdate, $reponextkey);
                        }

                        if (isset($chk_pulled)) {
                            $outchkdate = retval_issetor($chk_pulled->outchkdate);
                            $inchkdatedesc = isset($chk_pulled->inchkdatedesc) ? $chk_pulled->inchkdatedesc : '';
                            $inchkdate = isset($chk_pulled->inchkdate) ? $chk_pulled->inchkdate : '';

                            if ($thisrepid->repid == $lastReportId->repid && $checkpoints[$i]->checkpointid == $firstObj->checkpointid && $inchkdate == '') {
                                $rowStart = '';
                            } else {
                                $outchkdatedesc = isset($chk_pulled->outchkdatedesc) ? $chk_pulled->outchkdatedesc : -1;
                                $rowContent .= printdata($outchkdatedesc, $inchkdatedesc, $checkpoints[$i]->checkpointid, $firstelement->checkpointid, $ocddtrans, $inchkdate, $outchkdate);
                                $ocddtrans = $outchkdatedesc;
                                $repoldpkey = isset($chk_pulled->chkrepid) ? $chk_pulled->chkrepid : '';
                            }
                        }
                    }
                }

                if ($rowStart != '') {
                    $rowEnd .= "</tr>";
                    $completeRow .= $rowStart;
                    $completeRow .= $rowContent;
                    $completeRow .= $rowEnd;
                }
                $tableRows .= $completeRow;
            }
        }

        //die();
    }
    if($tableRows==''){
        $tableRows.="<tr><td colspan='100%'>Data Not Available</td></tr>";
    }
    $rows .=$tableRows;
    $rows .= "</tbody></table></div>";
    echo $rows;
}

class pulldetails {
}

function printdata($outdatedesc, $indatedesc, $chkptid, $firstelid, $ocddtrans, $indate, $outdate) {
    $rows = '';
    if (isset($outdatedesc) && $outdatedesc != 0) {
        if (isset($indatedesc) && $indatedesc != 0) {
            if ($outdatedesc == -1) {
                $timediff2 = "Not Left";
            } else {
                $timediff2 = calctime($outdatedesc, $indatedesc);
            }
        } else {
            $timediff2 = "";
        }
    } else {
        $timediff2 = "";
    }

    // First Element
    if ($chkptid != $firstelid) {
        if (isset($ocddtrans) && $ocddtrans != 0) {
            // In Transit
            if (isset($indatedesc) && $indatedesc != 0) {
                $timediff = calctime($ocddtrans, $indatedesc);
            } else {
                $timediff = "Not Left";
            }
        } else {
            $timediff = "";
        }
        $rows .= "<td><b>$timediff</b></td>";
    }
    if ($indatedesc <= $outdatedesc || $outdatedesc == -1) {
        if ($indate == '') {
            $outdate = '';
        }
        if ($outdate != -1) {
            $rows .= "<td>$indate</td><td>$timediff2</td><td>$outdate</td>";
        } else {
            $rows .= "<td>$indate</td><td>$timediff2</td><td></td>";
        }
    } else {
        $rows .= "<td></td><td></td><td>$outdate</td>";
    }

    return $rows;
}

function calctime($outtime, $intime) {
    $to_time = strtotime($outtime);
    $from_time = strtotime($intime);
    $newdiff = round(abs($to_time - $from_time) / 60, 2) . " min";
    return $newdiff;
}

function pulldetails_for_trip($startdate, $vehicleid, $customerno, $oldpk, $chkptid, $nextchkid, $prevchkid, $enddate = null, $reponextkey) {
    $pulldetails = new pulldetails();
    $inreport = report_chk_seq_in($startdate, $vehicleid, $customerno, $oldpk, $chkptid, $enddate, $reponextkey);

    if ($inreport != null) {
        $pulldetails->inchkdate = $inreport->date;
        $pulldetails->inchkdatedesc = $inreport->datedesc;
    }
    // Pull In Value

    if (isset($nextchkid)) {
        $chkrepidnext = report_pull_chkrepid_next($startdate, $vehicleid, $customerno, $oldpk, $nextchkid, $enddate, $reponextkey);
    } else {
        $chkrepidnext = report_pull_chkrepid_next($startdate, $vehicleid, $customerno, $oldpk, $prevchkid, $enddate, $reponextkey);
    }
    if (isset($chkrepidnext)) {
        $outreport = report_chk_seq_out($startdate, $vehicleid, $customerno, $oldpk, $chkptid, $chkrepidnext, $enddate, $reponextkey);

        // Pull In Value
        if (isset($outreport)) {
            $pulldetails->chkrepid = $outreport->chkrepid;
            $pulldetails->outchkdate = $outreport->date;
            $pulldetails->outchkdatedesc = $outreport->datedesc;
        }
    } else {
        $chkrepid = report_get_last_chkrepid($startdate, $vehicleid, $customerno, $enddate);
        $outreport = report_chk_seq_out($startdate, $vehicleid, $customerno, $oldpk, $chkptid, $chkrepid, $enddate, $reponextkey);

        // Pull In Value
        if (isset($outreport)) {
            $pulldetails->chkrepid = $outreport->chkrepid;
            $pulldetails->outchkdate = $outreport->date;
            $pulldetails->outchkdatedesc = $outreport->datedesc;
        } else {
            $pulldetails->outchkdate = -1;
            $pulldetails->outchkdatedesc = -1;
        }
    }

    return $pulldetails;
}

function pullreportin($STdate, $vehicleid, $checkpointid, $customerno) {
    $STdate = date("Y-m-d", strtotime($STdate));
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $Query = "select * from V" . $vehicleid . " WHERE chkid='" . $checkpointid . "' AND status='0' AND DATE(date)= '" . $STdate . "' ORDER BY date DESC LIMIT 1";
    //$CHKMS = array();
    try
    {
        $db = new PDO($path);
        $result = $db->query($Query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $checkpoint = new VORoute();
                $checkpoint->datein = convertDateToFormat($row['date'], speedConstants::DEFAULT_TIME);
            }
        }
    } catch (PDOException $e) {
        $checkpoint = 0;
    }
    return $checkpoint;
}

function pullreportout($STdate, $vehicleid, $checkpointid, $customerno) {
    $STdate = date("Y-m-d", strtotime($STdate));
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $Query = "select * from V" . $vehicleid . " WHERE chkid='" . $checkpointid . "' AND status='1' AND DATE(date) = '" . $STdate . "' ORDER BY date DESC LIMIT 1";
    //$CHKMS = array();
    try
    {
        $db = new PDO($path);
        $result = $db->query($Query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $checkpoint = new VORoute();
                $checkpoint->dateout = convertDateToFormat($row['date'], speedConstants::DEFAULT_TIME);
            }
        }
    } catch (PDOException $e) {
        $checkpoint = 0;
    }
    return $checkpoint;
}

function report_desc_seq($STdate, $vehicleid, $customerno, $firstelement, $lastelement) {
    $STdate = date("Y-m-d", strtotime($STdate));
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $Query = "select * from V" . $vehicleid . " WHERE status='0' AND DATE(date) = '" . $STdate . "' ORDER BY date DESC";
    $reportdesc = array();
    try
    {
        $db = new PDO($path);
        $result = $db->query($Query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $checkpoint = new VORoute();
                $checkpoint->datedesc = $row["date"];
                $checkpoint->chkiddesc = $row["chkid"];
                $reportdesc[] = $checkpoint;
                if ($checkpoint->chkiddesc == $firstelement || $checkpoint->chkiddesc == $lastelement) {
                    return $reportdesc;
                }
            }
        }
    } catch (PDOException $e) {
        $reportdesc = 0;
    }
    return $reportdesc;
}

function report_desc_out_seq($STdate, $vehicleid, $customerno, $firstelement, $lastelement) {
    $STdate = date("Y-m-d", strtotime($STdate));
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $Query = "select * from V" . $vehicleid . " WHERE status='1' AND DATE(date) = '" . $STdate . "' ORDER BY date DESC";
    $reportdescout = array();
    try
    {
        $db = new PDO($path);
        $result = $db->query($Query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $checkpoint = new VORoute();
                $checkpoint->datedesc = $row["date"];
                $checkpoint->chkiddesc = $row["chkid"];
                $reportdescout[] = $checkpoint;
                if ($checkpoint->chkiddesc == $firstelement || $checkpoint->chkiddesc == $lastelement) {
                    return $reportdescout;
                }
            }
        }
    } catch (PDOException $e) {
        $reportdescout = 0;
    }
    return $reportdescout;
}

class repid {
}

function getrepids($STdate, $vehicleid, $customerno, $firstelement) {
    $STdate = date("Y-m-d", strtotime($STdate));
    $reportdesc = Array();
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $Query = "select * from V" . $vehicleid . " WHERE DATE(date) >= '" . $STdate . "' ORDER BY date ASC";
    try
    {
        $prevchkid = 0;
        $prevstatus = 0;
        $db = new PDO($path);
        $result = $db->query($Query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                if ($row["status"] == 0 && $row["chkid"] == $firstelement) {
                    if ($prevchkid == $row["chkid"] && $prevstatus == 1) {
                        // Do Nothing
                    } else {
                        $checkpoint = new repid();
                        $checkpoint->repid = $row["chkrepid"];
                        $reportdesc[] = $checkpoint;
                    }
                }
                $prevchkid = $row["chkid"];
                $prevstatus = $row["status"];
            }
        }
    } catch (PDOException $e) {
        $reportdesc = 0;
    }
    return $reportdesc;
}

function report_chk_seq_in($STdate, $vehicleid, $customerno, $oldpk, $chkptid, $ETdate = null, $reponextkey) {
    $STdate = date("Y-m-d", strtotime($STdate));
    $ETdate = date("Y-m-d", strtotime($ETdate));
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $Query = "select * from V" . $vehicleid . " WHERE DATE(date) >= '" . $STdate . "' AND DATE(date) <= '" . $ETdate . "' AND chkrepid > " . $oldpk . " AND chkrepid < " . $reponextkey . " AND chkid = " . $chkptid . " AND status = 0 ORDER BY date ASC LIMIT 1";
    $checkpoint = null;
    try
    {
        $db = new PDO($path);
        $result = $db->query($Query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $checkpoint = new VORoute();
                $checkpoint->datedesc = $row["date"];
                $checkpoint->date = convertDateToFormat($row['date'], speedConstants::DEFAULT_TIME);
                $checkpoint->chkrepid = $row["chkrepid"];
                $checkpoint->chkiddesc = $row["chkid"];
                $checkpoint->status = $row["status"];
            }
            return $checkpoint;
        }
        return null;
    } catch (PDOException $e) {
        $reportdesc = 0;
    }
}

function report_pull_chkrepid_next($STdate, $vehicleid, $customerno, $oldpk, $chkptid, $ETdate = null, $reponextkey) {
    $STdate = date("Y-m-d", strtotime($STdate));
    $ETdate = date("Y-m-d", strtotime($ETdate));
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $Query = "select chkrepid from V" . $vehicleid . " WHERE DATE(date) >= '" . $STdate . "' AND DATE(date) <= '" . $ETdate . "' AND chkrepid > " . $oldpk . " AND chkrepid < " . $reponextkey . " AND chkid = " . $chkptid . " AND status = 1 ORDER BY date ASC LIMIT 1";
    try
    {
        $db = new PDO($path);
        $result = $db->query($Query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                return $row["chkrepid"];
            }
        }
        return null;
    } catch (PDOException $e) {
        $reportdesc = 0;
    }
}

function report_chk_seq_out($STdate, $vehicleid, $customerno, $oldpk, $chkptid, $nextchkrepid, $ETdate = null, $reponextkey) {
    $STdate = date("Y-m-d", strtotime($STdate));
    $ETdate = date("Y-m-d", strtotime($ETdate));
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $Query = "select * from V" . $vehicleid . " WHERE DATE(date) >= '" . $STdate . "' AND DATE(date) <= '" . $ETdate . "' AND chkrepid > " . $oldpk . " AND chkid = " . $chkptid . "  AND status = 1 ORDER BY date ASC LIMIT 1";
    //die();
    try
    {
        $db = new PDO($path);
        $result = $db->query($Query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $chekpointvalue = $row["chkid"];
                $checkpoint = new VORoute();
                $checkpoint->datedesc = $row["date"];
                $checkpoint->date = convertDateToFormat($row['date'], speedConstants::DEFAULT_TIME);
                $checkpoint->chkrepid = $row["chkrepid"];
                $checkpoint->chkiddesc = $row["chkid"];
                $checkpoint->status = $row["status"];
                return $checkpoint;
            }
        }
        return null;
    } catch (PDOException $e) {
        $reportdesc = 0;
    }
}

function report_get_last_chkrepid($STdate, $vehicleid, $customerno, $ETdate = null) {
    $STdate = date("Y-m-d", strtotime($STdate));
    $ETdate = date("Y-m-d", strtotime($ETdate));
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $Query = "select chkrepid from V" . $vehicleid . " WHERE DATE(date) >= '" . $STdate . "' AND DATE(date) <= '" . $ETdate . "' ORDER BY date ASC LIMIT 1";
    try
    {
        $db = new PDO($path);
        $result = $db->query($Query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                return $row["chkrepid"];
            }
        }
        return null;
    } catch (PDOException $e) {
        $reportdesc = 0;
    }
}

function m2h($mins) {
    if ($mins < 0) {$min = Abs($mins);} else { $min = $mins;}
    $H = Floor($min / 60);
    $M = round(($min - ($H * 60)) / 100, 2);
    //echo $M;
    if ($M == 0.6) {
        $H = $H + 1;
        $M = 0;
    }
    $hours = $H + $M;
    if ($mins < 0) {$hours = $hours * (-1);}
    $expl = explode(".", $hours);
    $H = $expl[0];
    if (empty($expl[1])) {$expl[1] = 00;}
    $M = $expl[1];
    if (strlen($M) < 2) {$M = $M . 0;}
    $hours = $H . ":" . $M;
    return $hours;
}

function route_trip_report($STdate, $ETdate, $chkpt_start, $chkpt_end, $routeid) {
    $DATAS = Array();
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/reports/chkreport.sqlite";

    $rm = new RouteManager($_SESSION['customerno']);
    $VEHICLES = $rm->getvehiclesByRoute_ById($routeid);

    foreach ($VEHICLES as $vehicle) {
        $DATAS[] = GetCheckpoint_Report($location, $STdate, $ETdate, $vehicle, $chkpt_start, $chkpt_end);
    }

    //print_r($DATAS); echo"<br/>";
    return $DATAS;
}

function GetCheckpoint_Report($location, $STdate, $ETdate, $vehicle, $chkpt_start, $chkpt_end) {
    $path = "sqlite:$location";
    $db = new PDO($path);

    $REPORT_Start = array();
    $REPORT_Final = Array();
    $flag = 0;
    $arr_start = Array();
    $arr_end = Array();
    $i = 0;
    $Query_Start = "SELECT * FROM V$vehicle->vehicleid WHERE date BETWEEN '$STdate' AND '$ETdate' AND chkid IN ($chkpt_start, $chkpt_end) ORDER BY date ASC";
    $result_Start = $db->query($Query_Start);
    if (isset($result_Start) && $result_Start != '') {
        foreach ($result_Start as $row_Start) {
            $Datacap = new VODatacap;
            $Datacap->chkrepid = $row_Start['chkrepid'];
            $Datacap->chkid = $row_Start['chkid'];
            $Datacap->status = $row_Start['status'];
            $Datacap->date = $row_Start['date'];
            $REPORT_Start[] = $Datacap;
        }

        //print_r($REPORT_Start);

        if (isset($REPORT_Start)) {
            foreach ($REPORT_Start as $chkreport) {
                if ($chkreport->status == '1' && $chkreport->chkid == $chkpt_start) {
                    if ($flag == 1) {
                        $arr_start = array_slice($arr_start, 0, -1, true);
                        $arr_start[] = $chkreport->chkrepid;
                        //print_r($arr_start);
                    } elseif ($flag == 0) {
                        $flag = 1;
                        $arr_start[] = $chkreport->chkrepid;
                        //print_r($arr_start);
                    }
                } elseif ($chkreport->status == '0' && $chkreport->chkid == $chkpt_end && $flag == 1) {
                    $flag = 0;
                    $arr_end[] = $chkreport->chkrepid;
                    //print_r($arr_end);
                }
            }
            if (!empty($arr_start)) {
                $arr_startlen = count($arr_start);
                $arr_endlen = count($arr_end);
                if ($arr_startlen > $arr_endlen) {
                    $arr_start = array_slice($arr_start, 0, -1, true);
                    //print_r($arr_start);
                }
                for ($i = 0; $i < count($arr_start); $i++) {
                    //echo $arr_start[$i] ." AND ". $arr_end[$i];echo "</br>";
                    $startelement = $arr_start[$i];
                    $endelement = $arr_end[$i];
                    $start_chkppoint = getChkReport($location, $vehicle->vehicleid, $startelement, $chkpt_start, $STdate, $ETdate, 1);
                    //print_r($start_chkppoint);echo "</br>";
                    $end_chkppoint = getChkReport($location, $vehicle->vehicleid, $endelement, $chkpt_end, $STdate, $ETdate, 0);
                    //print_r($end_chkppoint);echo "</br>";
                    $unitno = getunitnotemp($vehicle->vehicleid);
                    $did = GetDevice_byId($vehicle->vehicleid, $_SESSION['customerno']); // echo"<br/>";
                    if (isset($unitno) && $unitno != '') {
                        $userdatestart = date('Y-m-d', strtotime($start_chkppoint->date));
                        $userdatestart = substr($userdatestart, 0, 11);
                        $userdateend = date('Y-m-d', strtotime($end_chkppoint->date));
                        $userdateend = substr($userdateend, 0, 11);
                        $customerno = $_SESSION['customerno'];
                        $locationstart = "../../customer/$customerno/unitno/$unitno/sqlite/$userdatestart.sqlite";
                        $locationend = "../../customer/$customerno/unitno/$unitno/sqlite/$userdateend.sqlite";

                        if (file_exists($locationstart) && file_exists($locationend)) {
                            $firstodometer = getOdometer($vehicle->vehicleid, $locationstart, $start_chkppoint->date);
                            $lastodometer = getOdometer($vehicle->vehicleid, $locationend, $end_chkppoint->date);
                            $distance = getRouteDistance($vehicle->routeid);
                            $timetaken = getStdTime($vehicle->routeid);
                            $crad = getcheckpointcrad($chkpt_end);
                            //$distancetravelled = round((($lastodometer + ($crad * 1000)) - $firstodometer) / 1000 , 2);
                            if ($lastodometer < $firstodometer) {
                                $max = GetOdometerMax($locationend);
                                $lastodometer = $max + $lastodometer;
                            }
                            $distancetravelled = round((($lastodometer - $firstodometer) / 1000) + $crad, 2);
                            if ($firstodometer != '0' && $lastodometer != '0') {
                                $routerep = new VODatacap();
                                $routerep->vehicleid = $vehicle->vehicleid;
                                $routerep->vehicleno = $vehicle->vehicleno;
                                $routerep->routename = $vehicle->routename;
                                $routerep->routedistance = $distance;
                                $routerep->distancetravelled = $distancetravelled;
                                $routerep->startdate = $start_chkppoint->date;
                                $routerep->enddate = $end_chkppoint->date;
                                $routerep->stdtime = $timetaken;
                                $routerep->actualtime = round((strtotime($end_chkppoint->date) - strtotime($start_chkppoint->date)) / 60);
                                $REPORT_Final[] = $routerep;
                            }
                            //print_r($REPORT_Final);echo "</br>";
                        }
                    }
                }
                return $REPORT_Final;
            }
        } else {
            echo "not ok";
        }
    }
}

function GetOdometerMax($location) {

    $ODOMETER = 0;
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $query = "SELECT max(odometer) as odometerm from vehiclehistory";
        $result = $db->query($query);
        foreach ($result as $row) {
            $ODOMETER = $row['odometerm'];
        }
    }
    return $ODOMETER;
}

function getcheckpointcrad($checkpointid) {
    $routemanager = new RouteManager($_SESSION['customerno']);
    $checkpoints = $routemanager->getchkcradforroute($checkpointid);
    return $checkpoints;
}

function getChkReport($location, $id, $chkrep, $chkid, $STdate, $ETdate, $status) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query_Start = "SELECT * FROM V$id WHERE chkid=$chkid AND status = $status AND date BETWEEN '$STdate' AND '$ETdate' AND chkrepid= $chkrep";
    $result_Start = $db->query($Query_Start);
    if (isset($result_Start) && $result_Start != '') {
        foreach ($result_Start as $row_Start) {
            $Datacap = new VODatacap;
            $Datacap->chkrepid = $row_Start['chkrepid'];
            $Datacap->chkid = $row_Start['chkid'];
            $Datacap->status = $row_Start['status'];
            $Datacap->date = $row_Start['date'];
            return $Datacap;
        }
    }
}

function getOdometer($vehicleid, $location, $date) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT * FROM vehiclehistory where vehicleid = $vehicleid AND lastupdated >= '$date' ";
    $result = $db->query($Query);
    if (isset($result) && $result != '') {
        foreach ($result as $row) {
            return $row['odometer'];
        }
    } else {
        return 0;
    }
}

function GetDevice_byId($id, $customerno) {
    $dm = new DeviceManager($customerno);
    $result = $dm->GetDevice_byId($id);
    return $result;
}

?>