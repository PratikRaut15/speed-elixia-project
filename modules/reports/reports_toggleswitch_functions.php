<?php

include_once '../../lib/system/utilities.php';
include_once '../../lib/bo/GeoCoder.php';
include_once "../../lib/bo/ToggleSwitchManager.php";
include_once "../../lib/bo/UnitManager.php";
include_once '../../lib/bo/CustomerManager.php';
include_once '../../lib/comman_function/reports_func.php';
include_once 'reports_travel_functions.php';

if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
}


function getToggleSwitchReport($STdate, $EDdate, $Shour, $Ehour, $vehicleid, $groupid, $customerno) {
    $reportdetails = array();
    $strStartDate = "0000-00-00 00:00:00";
    $strEndDate = "0000-00-00 00:00:00";
    if (isset($STdate) && $STdate != '' && isset($EDdate) && $EDdate != '') {
        $startDate = new DateTime($STdate . ' ' . $Shour);
        $endDate = new DateTime($EDdate . ' ' . $Ehour);
        $strStartDate = $startDate->format('Y-m-d H:i');
        $strEndDate = $endDate->format('Y-m-d H:i');
    }
    $groupid = isset($groupid) ? $groupid : 0;
    $toggleSwitchManager = new ToggleSwitchManager();
    $report = $toggleSwitchManager->get_toggleswitch_trips($customerno, $vehicleid, $strStartDate, $strEndDate, $groupid);
    if (isset($report)) {
        foreach ($report as $thisreport) {
            $objReportDetails = new stdClass();
            $objReportDetails->id = $thisreport['id'];
            $objReportDetails->vehicleno = $thisreport['vehicleno'];
            $objReportDetails->vehicleid = $thisreport['vehicleid'];
            $objReportDetails->unitid = $thisreport['uid'];
            $objReportDetails->deviceid = $thisreport['deviceid'];
            $objReportDetails->startdatetime = $thisreport['starttime'];
            $objReportDetails->enddatetime = $thisreport['endtime'];
            $objReportDetails->startlat = $thisreport['startlat'];
            $objReportDetails->startlong = $thisreport['startlong'];
            $objReportDetails->endlat = $thisreport['endlat'];
            $objReportDetails->endlong = $thisreport['endlong'];

            $reportdetails[] = $objReportDetails;
        }
    }
    return $reportdetails;
}

function displayToggleSwitchReport($reports, $type) {
    $rows = "";
    if (isset($reports) && $reports != "") {
        $customerno = $_SESSION["customerno"];
        $usegeolocation = 0;
        $objGeoCoder = new GeoCoder($customerno);
        $usegeolocation = $objGeoCoder->get_use_geolocation();
        foreach ($reports as $report) {
            $tripno = "T-" . $report->id;
            $vehid = $report->vehicleid;
            $deviceid = $report->deviceid;
            $vehno = $report->vehicleno;

            $strStartLocation = location_cmn($report->startlat, $report->startlong, $usegeolocation);
            $strEndLocation = location_cmn($report->endlat, $report->endlong, $usegeolocation);

            $startDateTime = new DateTime($report->startdatetime);
            $endDateTime = new DateTime();
            $tripStatus = "In transit";
            if (isset($report->enddatetime) && $report->enddatetime != "0000-00-00 00:00:00") {
                $endDateTime = new DateTime($report->enddatetime);
                $tripStatus = "Closed";
            }

            $strStartDate = $startDateTime->format('d-m-Y');
            $strStartTime = $startDateTime->format('H:i');
            $strEndDate = $endDateTime->format('d-m-Y');
            $strEndTime = $endDateTime->format('H:i');
            $strRouteHistoryLink = "<a href='javascript:void(0)' title='View Route' onclick='getRouteHistReport(\"\",\"" . $vehid . "\",\"" . $strEndDate . "\",\"" . $strEndTime . "\",\"" . $strStartDate . "\",\"" . $strStartTime . "\",\"" . $deviceid . "\",\"" . $vehno . "\");'><img src=\"../../images/play.png\" title=\"Route History\" alt=\"Route History\"></a>";


            $getdistance = gettoggle_distance($vehid, $strStartDate, $strEndDate, $strStartTime, $strEndTime);
            if ($type != '')
                $rows .= "<tr>";
            $rows .= "<td>" . $tripno . "</td>";
            $rows .= "<td>" . $vehno . "</td>";
            $rows .= "<td>" . $strStartDate . "</td>";
            $rows .= "<td>" . $strStartTime . "</td>";
            $rows .= "<td>" . wordwrap($strStartLocation, Location_Wrap, "<br>\n") . "</td>";
            $rows .= "<td>(" . $report->startlat . "," . $report->startlong . ")</td>";
            $rows .= "<td>" . $strEndDate . "</td>";
            $rows .= "<td>" . $strEndTime . "</td>";
            $rows .= "<td>" . wordwrap($strEndLocation, Location_Wrap, "<br>\n") . "</td>";
            $rows .= "<td>(" . $report->endlat . "," . $report->endlong . ")</td>";
            $rows .= "<td>". round($getdistance,2)."</td>";
            $rows .= "<td>" . $tripStatus . "</td>";
            if ($type == 'HTML') {
                $rows .= "<td>" . $strRouteHistoryLink . "</td>";
            }

            $rows .="</tr>";
        }
        if ($type != 'HTML') {
            $rows.= "</tbody></table></div>";
        }
    }
    echo $rows;
}

function gettoggle_distance($vehid, $SDate, $EDate, $STime, $ETime) {
    $distance=0;
    $customerno = $_SESSION['customerno'];
    $um = new UnitManager($customerno);
    $vehicleid = GetSafeValueString($vehid, 'string');
    $totaldays = gendays_cmn($SDate, $EDate);
    $count = count($totaldays); 
    $endelement = end($totaldays);
    $firstelement = $totaldays[0];
    $unitno = $um->getunitnofromdeviceid($vehicleid);
    $days = Array();
    //return $unitno; 
    if (isset($totaldays)){
        foreach ($totaldays as $userdate){
            $lastday = new_travel_data($customerno, $unitno, $userdate, $count, $firstelement, $endelement, $vehicleid, $STime, $ETime);
            if($lastday != NULL){
                $days = array_merge($days, $lastday);
            }
        }
        
        if(isset($days) && !empty($days)){
            foreach($days as $row){
               $distance += $row->distancetravelled; 
            }
        }
     
    }
    return $distance;
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

function getTitle($title) {
    return $title;
}

function getSubtitle($data) {
    $subTitle = null;
    $subTitle = array("Vehicle No: {$data['vehicleno']}", "Start Date: {$data['STdate']} {$data['STime']}", "End Date: {$data['EDdate']} {$data['ETime']}");
    return $subTitle;
}

function getColumns($type) {
    $columns = null;
    if ($type == 'HTML') {
        $columns = array('Trip No', 'Vehicle No', 'Start Date', 'Start Time', 'Start location', 'Start(lat,long)', 'End Date', 'End Time', 'End location', 'End(lat,long)', 'Distance[KM]', 'Trip Status', 'Route History');
    } else {
        $columns = array('Trip No', 'Vehicle No', 'Start Date', 'Start Time', 'Start location', 'Start(lat,long)', 'End Date', 'End Time', 'End location', 'End(lat,long)', 'Distance[KM]', 'Trip Status');
    }
    return $columns;
}

function toggleswitchhistory_html($title, $subTitle, $columns, $customer_details, $datarows, $type) {
    $subTitle[] = "Total Trips Count : " . count($datarows);
    echo report_header($type, $title, $subTitle, $columns, $customer_details);
    displayToggleSwitchReport($datarows, $type);
}


?>
