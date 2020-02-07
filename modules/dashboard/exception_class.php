<?php
/**
* Date: 12th december 2014, ak added
* Class for getting exception reports
*/

function validate_input($s_date,$e_date){
    if(strtotime("+3 months", strtotime($s_date)) < strtotime($e_date)){
        echo "Please Select Dates With Difference Of Not More Than 3 Months";return false;
    }
    elseif(strtotime($s_date) > strtotime($e_date)){
        echo "Please Check The Dates";return false;
    }
    return true;
}

function report_generation($report, $daily_data){

    global $veh_mangr;
    $dist = ($report->distance !='0') ? $report->distance : "N/A";
    $dist_m2h_get = ($report->distance !='0') ? m2h(getduration($report->enddate,$report->startdate)) : "N/A";
    $dist_m2h = ($report->distance !='0') ? m2h($report->idletime) : "N/A";
    $groupname = $veh_mangr->get_groupname_by_id($report->groupid);

    $group_dis = empty($groupname) ? 'N/A' : $groupname;

    $g_hours = floor($daily_data['genset']/60);
    $g_minute = $daily_data['genset']%60;
    if ($g_minute < 10) {
        $g_minute = '0'.$g_minute;
    }
    $genset = "$g_hours:$g_minute";
    $main_data = "<tr>
        <td>".$report->vehicleno."</td>
        <td>$group_dis</td>
        <td>".$dist."</td>
        <td>".$daily_data['avgspeed']."</td>
        <td>".$dist_m2h_get."</td>
        <td>".$dist_m2h."</td>
        <td>".convertDateToFormat($report->startdate,speedConstants::DEFAULT_DATETIME)."</td>
        <td>".convertDateToFormat($report->enddate,speedConstants::DEFAULT_DATETIME)."</td>
        <td>".$genset."</td>
    </tr>";
    return $main_data;
}

function GetExceptionDailyReport_Data($days, $veh_id){
    global $customerno;
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    $path = "sqlite:$location";
    $db = new PDO($path);
    $REPORT = array();
    $sqlday = date("dmy",strtotime($days));
    $query = "SELECT avgspeed,genset,overspeed,totaldistance,fenceconflict,idletime,runningtime,vehicleid,dev_lat,dev_long,first_dev_lat,first_dev_long from A$sqlday where vehicleid='$veh_id'";
    $result = $db->query($query);
    if(isset($result) && $result!=""){
        foreach ($result as $row){
            return array('avgspeed'=>$row['avgspeed'], 'genset'=>$row['genset']);
        }
    }
    return $REPORT;
}

?>
