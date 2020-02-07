<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

include_once '../../lib/system/utilities.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/DeviceManager.php';

/*
include_once '../../lib/bo/UnitManager.php';
include_once '../../lib/bo/GeoCoder.php';
 */

//date_default_timezone_set("Asia/Calcutta");
session_start();
if (!isset($_SESSION['timezone'])) {
    $_SESSION['timezone'] = 'Asia/Kolkata';
}
date_default_timezone_set('' . $_SESSION['timezone'] . '');
class VODatacap {}

$vehiclemanager = new VehicleManager($_SESSION['customerno']);
$devicemanager = new DeviceManager($_SESSION['customerno']);
$VEHICLES = $vehiclemanager->Get_All_Vehicles_SQLite();
$def_chart_height = 200;
$single_chart_height = 30;

/**
 * To check if start-date is less then end-date
 * @param date $STdate
 * @param date $EDdate
 * @return int
 */

/**
 * To get difference in monthe between 2 dates
 * @param date $STdate
 * @param date $EDdate
 * @return int
 */
function get_month_diff($STdate, $EDdate) {
    $datetime1 = date_create($STdate);
    $datetime2 = date_create($EDdate);
    $interval = date_diff($datetime1, $datetime2);
    return $interval->format('%m');
}

/**
 * To get dates-diff in array
 * @param date $STdate
 * @param date $EDdate
 * @return array
 */
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

/**
 * To arrange data in proper format
 * @param array $result
 * @return array
 */
function get_report_array($result) {

    global $column_name;
    $data = array();
    foreach ($result as $row) {
        $veh_id = $row['vehicleid'];
        if ((time() >= strtotime("2014-12-01")) || $_SESSION['role_modal'] == 'elixir') {
            $data[$veh_id][$column_name] = isset($row[$column_name]) ? $row[$column_name] : 0;
        } else {
            $data[$veh_id][$column_name] = 0;
        }
    }
    return $data;
}

/**
 * To format data for graph display
 * @global array $VEHICLES
 * @param array $report
 * @return array
 */
function get_advanced_graph_data($report) {

    $veh_total = array();
    $veh_drill = array();
    $graph_report = array();
    $graph_report_drill = '';
    global $column_name;
    foreach ($report as $date => $custom_report) {
        foreach ($custom_report as $key => $key_data) {
            if (isset($veh_total[$key])) {
                $veh_total[$key] += $key_data[$column_name];
                $veh_drill[$key] .= "['$date', " . $key_data[$column_name] . "],";
            } else {
                $veh_total[$key] = $key_data[$column_name];
                $veh_drill[$key] = "['$date', " . $key_data[$column_name] . "],";
            }
        }
    }

    global $VEHICLES, $def_chart_height, $single_chart_height;
    $height = $def_chart_height;
    foreach ($veh_total as $veh_id => $key_total) {
        $veh_no = $VEHICLES[$veh_id]['vehicleno'];
        $graph_report[] = array('drilldown' => $veh_no, 'name' => $veh_no, 'y' => round($key_total));
        $graph_report_drill .= "{'id':'$veh_no', 'name':'$veh_no',  'data':[$veh_drill[$veh_id]]},";
        $height += $single_chart_height;
    }
    $graph_drill = "[$graph_report_drill]";

    return array($graph_report, $graph_drill, $height);
}

/**
 * To get daily-report data from sqlite
 * @global array $totaldays
 * @param date $STdate
 * @param date $EDdate
 * @return array
 */
function get_advanced_dailyreport_all($STdate, $EDdate, $type = 'default') {
    global $totaldays;

    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    $report = array();
    $main_report = '';
    $graph_report = '';
    $graph_report_drill = '';
    $graph_height = 10;

    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        foreach ($totaldays as $day) {
            $sqlday = date("dmy", strtotime($day));
            $query = "SELECT * from A$sqlday order by vehicleid ASC";
            $result = $db->query($query);

            if (isset($result) && $result != "") {
                $report[$day] = get_report_array($result, $day);
            }
        }
    }

    if (!empty($report)) {
        if ($type == 'default') {
            //echo "<pre>";print_r($report);echo "</pre>";die();
            $main_report = display_report($report);
            $graph = get_advanced_graph_data($report);
            $graph_report = $graph[0];
            $graph_report_drill = $graph[1];
            $graph_height = $graph[2];
        } else {
            $main_report = $report;
        }
    }

    return array($main_report, $graph_report, $graph_report_drill, $graph_height);
}

/**
 * To display table for harsh-break
 * @global array $VEHICLES
 * @global array $totaldays
 * @param html $data
 */
function display_report($data, $type = 'default') {
    $table = '';
    if (!empty($data)) {
        global $VEHICLES, $totaldays, $column_name;

        $table .= "<tr style='background:#d8d5d6'><td></td>";
        foreach ($totaldays as $single_day) {
            if ($type == 'excel') {
                $custom_date = date('Y-m-d', strtotime($single_day));
            } else {
                $custom_date = date('d-m', strtotime($single_day));
            }

            $table .= "<td style='text-align:center'><b>$custom_date</b></td>";
        }
        $table .= "<td></td></tr>";

        foreach ($VEHICLES as $veh_id => $veh_no) {
            if (!isset($data[$totaldays[0]][$veh_id])) {
                continue;
            }
            $table .= "<tr>";
            $table .= "<td><b>" . $veh_no['vehicleno'] . "</b></td>";
            $total = 0;
            foreach ($totaldays as $single_day) {
                if (!isset($data[$single_day][$veh_id][$column_name])) {
                    $table .= "<td style='text-align:center'>0</td>";
                    $total += 0;
                    continue;
                }
                $column_count = $data[$single_day][$veh_id][$column_name];
                $total += $column_count;
                $table .= "<td style='text-align:center'>" . $column_count . "</td>";
            }
            $table .= "<td style='text-align:center'>$total</td>";
            $table .= "</tr>";
        }
    } else {
        $table .= "<tr><td colspan='100%' style='text-align:center' >Data not available</td></tr>";
    }

    return $table;
}

function validate_set_vals($sdate, $edate) {
    $STdate = GetSafeValueString($sdate, 'string');
    $EDdate = GetSafeValueString($edate, 'string');
    $datecheck = datediff($sdate, $edate);

    if ($datecheck == 1) {
        $month_diff = get_month_diff($sdate, $edate);
        if ($month_diff == 0) {
            return array($STdate, $EDdate);
        } else {echo "Please Select Dates With Difference Of Not More Than 30 Days";exit;}
    } else if ($datecheck == 0) {
        echo "Please Check The Date";exit;
    } else {
        echo "Data Not Available";exit;
    }
}

function generate_pdf_harsh_break($STdate, $EDdate,$vgroupname = null) {
    global $totaldays;

    $data = get_advanced_dailyreport_all($STdate, $EDdate, 'pdf');
    $table_format = display_report($data[0]);
    $report_name = "Harsh Break Analysis";
    $table_header = "Date-wise Harsh Break Count";

    include 'pages/panels/advanced_alert_pdf.php';
}

function generate_excel_harsh_break($STdate, $EDdate, $vgroupname = null) {
    global $totaldays;

    $data = get_advanced_dailyreport_all($STdate, $EDdate, 'excel');
    $table_format = display_report($data[0], 'excel');
    $report_name = "Harsh Break Analysis";
    $table_header = "Date-wise Harsh Break Count";

    include 'pages/panels/advanced_alert_excel.php';
}

function generate_pdf_sudden_acc($STdate, $EDdate, $vgroupname = null) {
    global $totaldays;

    $data = get_advanced_dailyreport_all($STdate, $EDdate, 'pdf');
    $table_format = display_report($data[0]);
    $report_name = "Sudden Acceleration Analysis";
    $table_header = "Date-wise Sudden Acceleration Count";

    include 'pages/panels/advanced_alert_pdf.php';
}

function generate_excel_sudden_acc($STdate, $EDdate,$vgroupname = null) {
    global $totaldays;

    $data = get_advanced_dailyreport_all($STdate, $EDdate, 'excel');
    $table_format = display_report($data[0], 'excel');
    $report_name = "Sudden Acceleration Analysis";
    $table_header = "Date-wise Sudden Acceleration Count";

    include 'pages/panels/advanced_alert_excel.php';
}

function generate_pdf_sharp_turn($STdate, $EDdate, $vgroupname = null) {
    global $totaldays;

    $data = get_advanced_dailyreport_all($STdate, $EDdate, 'pdf');
    $table_format = display_report($data[0]);
    $report_name = "Sharp Turn Analysis";
    $table_header = "Date-wise Sharp Turn Count";

    include 'pages/panels/advanced_alert_pdf.php';
}

function generate_excel_sharp_turn($STdate, $EDdate, $vgroupname = null) {
    global $totaldays;

    $data = get_advanced_dailyreport_all($STdate, $EDdate, 'excel');
    $table_format = display_report($data[0], 'excel');
    $report_name = "Sharp Turn Analysis";
    $table_header = "Date-wise Sharp Turn Count";

    include 'pages/panels/advanced_alert_excel.php';
}
function generate_pdf_towing($STdate, $EDdate, $vgroupname = null) {
    global $totaldays;

    $data = get_unit_data($STdate, $EDdate, 'pdf');
    $table_format = display_report($data[0]);
    $report_name = "Towing Analysis";
    $table_header = "Date-wise Towing Count";

    include 'pages/panels/advanced_alert_pdf.php';
}

function generate_excel_towing($STdate, $EDdate, $vgroupname = null) {
    global $totaldays;

    $data = get_unit_data($STdate, $EDdate, 'excel');
    $table_format = display_report($data[0], 'excel');
    $report_name = "Towing Analysis";
    $table_header = "Date-wise Towing Count";

    include 'pages/panels/advanced_alert_excel.php';
}

function get_unit_data($start_date, $end_date, $type = "default") {
    global $devicemanager;

    $totaldays = gendays($start_date, $end_date);
    $deviceids = $devicemanager->get_all_devices();
    $customerno = $_SESSION['customerno'];
    $report = array();
    $graph_report = '';
    $graph_report_drill = '';
    $graph_height = 10;

    foreach ($deviceids as $data_for) {
        $deviceid = $data_for->deviceid;
        $unitno = $data_for->unitno;

        if (isset($totaldays)) {
            $full_data = array();
            foreach ($totaldays as $userdate) {
                $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                if (file_exists($location)) {

                    //Date Check Operations
                    $location = "sqlite:" . $location;
                    $data_all = get_advanced_data_fromsqlite($location, $deviceid, $userdate);

                    if ($data_all != null && time() >= strtotime("2014-12-01")) {
                        $report[$userdate][$data_all[0]]['towing'] = $data_all['towing'];
                    }
                }
            }
        }
    }

    if (!empty($report)) {
        if ($type == 'default') {
            $main_report = display_report($report);
            $graph = get_advanced_graph_data($report);
            $graph_report = $graph[0];
            $graph_report_drill = $graph[1];
            $graph_height = $graph[2];
        } else {
            $main_report = $report;
        }
    }

    return array($main_report, $graph_report, $graph_report_drill, $graph_height);

}

function get_advanced_data_fromsqlite($location, $deviceid, $userdate) {
    $Shour = "00:00:00";
    $Ehour = "23:59:59";
    $towing = 0;
    $return = null;
    $query = "SELECT devicehistory.ignition, vehiclehistory.curspeed, vehiclehistory.vehicleid
            from devicehistory INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
            INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.deviceid= $deviceid AND devicehistory.status!='F'";

    $query .= " AND devicehistory.lastupdated >= '$userdate $Shour'";
    $query .= " AND devicehistory.lastupdated <= '$userdate $Ehour'";
    $query .= "  ORDER BY devicehistory.lastupdated ASC";

    try {
        $database = new PDO($location);
        $result = $database->query($query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                if (($row['ignition'] == 0) && ($row['curspeed'] >= 5)) {
                    $towing += 1;
                }
            }
            $return = array($row['vehicleid'], 'towing' => $towing);
        }
    } catch (PDOException $e) {
        die($e);
    }

    return $return;
}

?>