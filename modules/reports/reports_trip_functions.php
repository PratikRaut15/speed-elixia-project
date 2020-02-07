<?php
include_once '../../lib/system/utilities.php';
include_once "../../lib/bo/DeviceManager.php";
include_once '../../lib/bo/CheckpointManager.php';
include_once "../../lib/bo/VehicleManager.php";
include_once '../../lib/comman_function/reports_func.php';

class VOCHKM {}
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
function getunitno($vid) {
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $unitno = $devicemanager->get_unitno_by_vehicle_id($vid);
    return $unitno;
}

function getvehicleno($vid) {
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $unitno = $devicemanager->get_vehicle_by_vehicle_id($vid);
    return $unitno;
}
function getcheckpoints($vehicleid) {
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->getchksforvehicle($vehicleid);
    return $checkpoints;
}

function getdate_IST() {
    $ServerDate_IST = strtotime(date("Y-m-d H:i:s"));
    return $ServerDate_IST;
}
function getchkrep($STdate, $EDdate, $Shour, $Ehour, $vehicleid, $checkpoints) {

    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicle = $vehiclemanager->get_vehicle_details($vehicleid);

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
    $unitno = getunitno($vehicleid);
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $Query = "select * from V" . $vehicleid . " WHERE date BETWEEN '$STdate $Shour:00' AND '$EDdate $Ehour:59'"; //echo"<br>Query is: ".$Query;
    $CHKMS = array();

    try
    {
        $db = new PDO($path);
        $result = $db->query($Query);
        $flag_in = true;
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                if($row["status"] == 1)
                { 
                    $flag_in = false;
                }
                if($flag_in == false)
                {
                    $CHKM = new VOCHKM();
                    $CHKM->tp_vid = $vehicleid;
                    $CHKM->unitno = $unitno;
                    $CHKM->chkrepid = $row["chkrepid"];
                    $CHKM->chkid = $row["chkid"];
                    $CHKM->status = $row["status"];
                    $CHKM->date = $row["date"];
                    $CHKM->odometer = get_lat_lng_for_trip($vehicleid, $row["date"]);
                    $CHKMS[] = $CHKM;
                }
            }
        }
    } catch (PDOException $e) {
        $CHKMS = 0;
    }
    return $CHKMS;
}

function processchkrep($reports) {

    $count = 0;
    $cnt = 0;
    $i = 0;
    $chkreport = array();

    if (isset($reports) && $reports != "") {

        $count = count($reports);
        for ($j = $i; $j < $count; $j = $j + 2) {

            $chkreport[$cnt] = new stdClass();
            $chkreport[$cnt]->tp_vid = retval_issetor($reports[$j]->tp_vid);
            $chkreport[$cnt]->s_cname = retval_issetor($reports[$j]->cname);
            $chkreport[$cnt]->starttime = $reports[$j]->date;
            $chkreport[$cnt]->s_odometer = $reports[$j]->odometer;
            $chkreport[$cnt]->e_cname = retval_issetor($reports[$j + 1]->cname);
            $chkreport[$cnt]->endtime = retval_issetor($reports[$j + 1]->date);
            $chkreport[$cnt]->e_odometer = retval_issetor($reports[$j + 1]->odometer);

            $chkreport[$cnt]->startCheckPointId = retval_issetor($reports[$j]->chkid);
            $chkreport[$cnt]->endCheckPointId = retval_issetor($reports[$j + 1]->chkid);

            if ($chkreport[$cnt]->e_odometer < $chkreport[$cnt]->s_odometer) {
                $max = GetOdometerMax($reports[$j]->date, $reports[$j]->unitno);
                $chkreport[$cnt]->e_odometer = $max + $chkreport[$cnt]->e_odometer;
            }
            $chkreport[$cnt]->total_dist = ($chkreport[$cnt]->e_odometer - $chkreport[$cnt]->s_odometer) / 1000;
            $cnt++;
        }
    } 
    return $chkreport;
}

function displayrep($reports) {
    $rows = "";
    if (isset($reports) && $reports != "") {
        foreach ($reports as $report) {
            //if ((($report->s_cname != $report->e_cname) && ($report->total_dist > 0)) || (($report->s_cname == $report->e_cname) && ($report->total_dist > 0.5))) {
            if (($report->s_cname != $report->e_cname) && ((($report->total_dist > 0)) || ($report->total_dist > 0.5))){
                $rows .= "<tr><td>" . $report->starttime . "</td>";
                if (isset($report->endtime)) {
                    $rows .= "<td>$report->endtime</td>";

                } else {
                    $rows .= "<td>End</td>";

                }
                if ($report->s_cname == "") {
                    $rows .= "<td>Checkpoint Deleted</td>";
                } else {
                    $rows .= "<td>$report->s_cname</td>";
                }
                if ($report->e_cname == "") {
                    $rows .= "<td>end</td>";
                } else {
                    $rows .= "<td>$report->e_cname</td>";
                }
                $rows .= "<td>$report->total_dist</td><td><a target='_blank' href='tripmap.php
			?SDate=" . date("d-m-Y", strtotime($report->starttime)) . "&EDate=" . date("d-m-Y", strtotime($report->endtime)) . "
			&Shour=" . date("H:i:s", strtotime($report->starttime)) . "&Ehour=" . date("H:i:s", strtotime($report->endtime)) . "
			&vehicleid=" . $report->tp_vid . "'><img src='../../images/mapit.png' /></a></td>";
                $rows .= "<td><a target='_blank' href='travelhist.php
			?SDate=" . date("d-m-Y", strtotime($report->starttime)) . "&EDate=" . date("d-m-Y", strtotime($report->endtime)) . "
			&Shour=" . date("H:i:s", strtotime($report->starttime)) . "&Ehour=" . date("H:i:s", strtotime($report->endtime)) . "
			&vehicleid=" . $report->tp_vid . "'><img src='../../images/table.png' /></a></td>";

                $rows .= "</tr>";
            }
        }
    }
    echo $rows;
}
function GetOdometerMax($date, $unitno) {
    $date = substr($date, 0, 11);
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
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

function get_lat_lng_for_trip($vid, $time) {

    $STdate = date("Y-m-d", strtotime($time));
    $customerno = $_SESSION['customerno'];
    $vid = getunitno($vid);
    $order = 'asc'; //ak added to resolve error
    $location = "../../customer/$customerno/unitno/$vid/sqlite/$STdate.sqlite";
    $ODOMETER = 0;

    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $query = "SELECT odometer from vehiclehistory where lastupdated='" . $time . "' ORDER BY vehiclehistory.lastupdated $order LIMIT 0,1";
        $result = $db->query($query);
        foreach ($result as $row) {
            $ODOMETER .= $row['odometer'];
        }
    }
    return $ODOMETER;

}

?>