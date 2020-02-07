<?php
/**
 * Date: 5th oct 2014, ak added
 * Function for getting informatics reports 
 */

include_once '../../lib/bo/DeviceManager.php';
include_once '../../lib/bo/UserManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/UnitManager.php';
include_once '../../lib/comman_function/reports_func.php';

class VODatacap {}
class VODatacap_i {}

$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];


$groupwise_vehicles = groupBased_vehicles_cron($customerno, $userid);
$all_vehicles = array();
foreach($groupwise_vehicles as $single_veh){
    $all_vehicles[$single_veh->vehicleid] = array(
        'vehicleno' => $single_veh->vehicleno,
        'groupid' => $single_veh->groupid,
        'unitno' => $single_veh->unitno,
        'deviceid' => $single_veh->deviceid,
        'overspeed_limit' => $single_veh->overspeed_limit,
        'is_ac_opp' => $single_veh->is_ac_opp,
        'acsensor' => $single_veh->acsensor
    );
}

function validate_input($s_date,$e_date){
    if(strtotime("+3 months", strtotime($s_date)) < strtotime($e_date)){
        echo "Please Select Dates With Difference Of Not More Than 3 Months";return false;
    }
    elseif(strtotime($s_date) > strtotime($e_date)){
        echo "Please Check The Dates";return false;
    }
    return true;
}

function getduration($EndTime, $StartTime)
{
    $idleduration = strtotime($EndTime) - strtotime($StartTime);
    $years = floor($idleduration / (365 * 60 * 60 * 24));
    $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
    $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
    $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
    return $hours * 60 + $minutes;
}

function optimizerep($data)
{
    $datarows = array();
    $ArrayLength = count($data);
    $currentrow = $data[0];
    $a = 0;
    while ($currentrow != $data[$ArrayLength - 1]) {
        $i = 1;
        while (($i + $a) < $ArrayLength - 1 && $data[$i + $a]->duration < 3) {
            $i+=2;
        }
        $currentrow->endtime = $data[$i + $a - 1]->endtime;
        $currentrow->endlat = $data[$i + $a - 1]->endlat;
        $currentrow->endlong = $data[$i + $a - 1]->endlong;
        $currentrow->endodo = $data[$i + $a - 1]->endodo;
        $currentrow->duration = getduration($currentrow->endtime, $currentrow->starttime);
        $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;
        $datarows[] = $currentrow;
        if (($a + $i) <= $ArrayLength - 1) {
            $currentrow = $data[$i + $a];
        }
        $a += $i;
        if (($a) >= $ArrayLength - 1)
            $currentrow = $data[$ArrayLength - 1];
    }
    if($datarows!=NULL)
    {
        $checkop = end($datarows);
        $checkup = end($data);
        if ($checkop->endtime != $checkup->endtime) 
        {
            $currentrow->starttime = $checkop->endtime;
            $currentrow->startlat = $checkop->endlat;
            $currentrow->startlong = $checkop->endlong;
            $currentrow->startodo = $checkop->endodo;

            $currentrow->endtime = $checkup->endtime;
            $currentrow->endlat = $checkup->endlat;
            $currentrow->endlong = $checkup->endlong;
            $currentrow->endodo = $checkup->endodo;
            $currentrow->duration = getduration($currentrow->endtime, $currentrow->starttime);
            $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;

            $datarows[] = $currentrow;
        }
    }
    else
    {
        $currentrow = end($data);
        $currentrow->endlat = $currentrow->startlat;
        $currentrow->endlong = $currentrow->startlong;
        $currentrow->endtime = date('Y-m-d',  strtotime($currentrow->starttime));
        $currentrow->endtime .= " 23:59:59";
        $currentrow->endodo = $currentrow->startodo;;
        $currentrow->duration = getduration($currentrow->endtime, $currentrow->starttime);
        $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;
        $datarows[] = $currentrow;
    }
    return $datarows;
}

function processtraveldata($devicedata)
{
    $devices2 = $devicedata[0];
    $lastrow = $devicedata[1];
    $data = Array();
    $datalen = count($devices2);
    if (isset($devices2) && count($devices2)>1)
    {
        foreach ($devices2 as $device)
        {
            $datacap = new VODatacap();
            
            $datacap->ignition = $device->ignition;

            $ArrayLength = count($data);

            if ($ArrayLength == 0) 
            {
                $datacap->starttime = $device->lastupdated;
                $datacap->startlat = $device->devicelat;
                $datacap->startlong = $device->devicelong;
                $datacap->startodo = $device->odometer;
            }
            else if ($ArrayLength == 1) 
            {
                //Filling Up First Array --- Array[0]
                $data[0]->endlat = $device->devicelat;
                $data[0]->endlong = $device->devicelong;
                $data[0]->endtime = $device->lastupdated;
                $data[0]->endodo = $device->odometer;
                $data[0]->distancetravelled = $data[0]->endodo / 1000 - $data[0]->startodo / 1000;
                $data[0]->duration = getduration($data[0]->endtime, $data[0]->starttime);

                $datacap->starttime = $data[0]->endtime;
                $datacap->startlat = $data[0]->endlat;
                $datacap->startlong = $data[0]->endlong;
                $datacap->startodo = $data[0]->endodo;
                $datacap->endtime = $lastrow->lastupdated;
                $datacap->endlat = $lastrow->devicelat;
                $datacap->endlong = $lastrow->devicelong;
                $datacap->endodo = $lastrow->odometer;
            } 
            else 
            {
                $last = $ArrayLength - 1;
                $data[$last]->endtime = $device->lastupdated;
                $data[$last]->endlat = $device->devicelat;
                $data[$last]->endlong = $device->devicelong;
                $data[$last]->endodo = $device->odometer;

                $data[$last]->duration = getduration($data[$last]->endtime, $data[$last]->starttime);

                $data[$last]->distancetravelled = $data[$last]->endodo / 1000 - $data[$last]->startodo / 1000;


                $datacap->starttime = $data[$last]->endtime;
                $datacap->startlat = $data[$last]->endlat;
                $datacap->startlong = $data[$last]->endlong;
                $datacap->startodo = $data[$last]->endodo;

                if ($datalen - 1 == $ArrayLength)
                {
                    $datacap->endtime = $lastrow->lastupdated;
                    $datacap->endlat = $lastrow->devicelat;
                    $datacap->endlong = $lastrow->devicelong;
                    $datacap->endodo = $lastrow->odometer;
                    $datacap->duration = getduration($datacap->endtime, $datacap->starttime);
                    $datacap->distancetravelled = $datacap->endodo / 1000 - $datacap->startodo / 1000;
                    $datacap->ignition = $device->ignition;
                }
            }
            $data[] = $datacap;
        }
        if($data!=NULL && count($data)>0)
        {
            $optdata = optimizerep($data);
        }
        return $optdata;
    }
    else if(isset($devices2) && count($devices2)==1)
    {
        $datacap = new VODatacap();
        $datacap->starttime = $devices2[0]->lastupdated;
        $datacap->startlat = $devices2[0]->devicelat;
        $datacap->startlong = $devices2[0]->devicelong;
        $datacap->startodo = $devices2[0]->odometer;
        $datacap->endtime = $lastrow->lastupdated;
        $datacap->endlat = $lastrow->devicelat;
        $datacap->endlong = $lastrow->devicelong;
        $datacap->endodo = $lastrow->odometer;
        $datacap->duration = getduration($datacap->endtime, $datacap->starttime);
        $datacap->distancetravelled = $datacap->endodo / 1000 - $datacap->startodo / 1000;
        $datacap->ignition = $devices2[0]->ignition;
        $data[] = $datacap;
        
        return $data;
    }
}

function get_daily_informatics($location,$days){
    $path = "sqlite:$location";
    $db = new PDO($path);
    $REPORT = array();
    
    if(isset($days)){
        foreach ($days as $day){
            $sqlday = date("dmy",strtotime($day));
            $query = "SELECT * from A$sqlday order by vehicleid ASC";
            $result = $db->query($query);
            if(isset($result) && $result!=""){
                foreach ($result as $row){
                    $Datacap = new VODatacap_i();
                    $Datacap->date = strtotime($day);
                    $Datacap->info_date = $day;
                    $Datacap->uid = $row['uid'];
                    $Datacap->tamper = isset($row['tamper']) ? $row['tamper'] : '';
                    $Datacap->overspeed = $row['overspeed'];
                    $Datacap->totaldistance = $row['totaldistance'];
                    $Datacap->fenceconflict = $row['fenceconflict'];
                    $Datacap->idletime = $row['idletime'];
                    $Datacap->genset = $row['genset'];
                    $Datacap->runningtime = $row['runningtime'];
                    $Datacap->vehicleid = $row['vehicleid'];
                    $REPORT[] = $Datacap;
                }
            }
        }
    }
    return $REPORT;
}

function minutes_to_days($minutes){
    $day = floor ($minutes / 1440);
    $hour = floor (($minutes - $day * 1440) / 60);
    $min = $minutes - ($day * 1440) - ($hour * 60);
    $min = round($min);
    return "{$day} days {$hour} hours {$min} minutes";
}

function get_daily_report_data($start_date, $end_date){

    global $customerno, $all_vehicles;
    
    $totaldays = gendays_cmn($start_date, $end_date);
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if(!file_exists($location)){
        return array('total'=>0);
    }
    $report_data = get_daily_informatics($location,$totaldays);
    $tracked = 0;
    $highest_dis_trav_def = 0;
    $highest_dis_trav = array();
    $total_idle_time = $total_idle_time_final = 0;
    $total_running_time = 0;
    $idle_time_data = array();
    $idle_time_data_final = array();
        
    if(!empty($report_data)){
        
        $vehicles = array();
        foreach($report_data as $single_data){
            if(!array_key_exists($single_data->vehicleid, $all_vehicles)){
                continue;
            }
            $single_data->vehiclename = $vehiclename = $all_vehicles[$single_data->vehicleid]['vehicleno'];
            
            
            sunday_distance_data(null,$single_data->info_date, $single_data->totaldistance, $vehiclename);
            
            if($single_data->totaldistance > $highest_dis_trav_def){
                $highest_dis_trav_def = $single_data->totaldistance;
                $highest_dis_trav_data = $single_data;
            }
            
            $tracked += (int)$single_data->totaldistance;
            if(!isset($vehicles[$vehiclename])){
                $vehicles[$vehiclename] = $single_data->totaldistance;
            }
            else{
                $vehicles[$vehiclename] += $single_data->totaldistance;
            }
            
            if(isset($single_data->idletime)){
                $total_idle_time += $single_data->idletime;
                if(isset($idle_time_data[$vehiclename])){
                    $idle_time_data[$vehiclename] += $single_data->idletime;
                }
                else{
                    $idle_time_data[$vehiclename] = $single_data->idletime;
                }
            }
        }
        
        $vehicle_name = $distance_travelled = '';
        $bar_height = 30;$height = 0;
        foreach($vehicles as $veh_name=>$distance){
            $distance = round($distance/1000);
            $vehicle_name .= "'$veh_name',";
            $distance_travelled .= $distance.",";
            $height += $bar_height; 
        }
        
        $tracked = $tracked/1000;
        if(isset($highest_dis_trav_data)){
            $veh_name = $highest_dis_trav_data->vehiclename;
            $dis_tra = $highest_dis_trav_data->totaldistance/1000;
            $date_tra = date('dS M', strtotime($highest_dis_trav_data->info_date));
            $highest_dis_trav = array($veh_name,$dis_tra,$date_tra);
        }
        
        $datediff = floor((strtotime($end_date) - strtotime($start_date))/(60*60*24));
        $avg_dis_trav = ($datediff!=0)? $tracked/$datediff : $tracked;
        $veh_count = count($vehicles);
        $avg_dis_trav_veh = ($veh_count>0) ? $avg_dis_trav/$veh_count : '';
        
        
        
        if($total_idle_time!=0){
            $total_idle_time_sec =  $total_idle_time*60; //in-seconds
            $total_idle_time_final = minutes_to_days($total_idle_time);//$dtF->diff($dtT)->format('%d day %h hrs %i min');  
            $differenceInSeconds = (strtotime("$end_date 23:59:59") - strtotime("$start_date 00:00:00")); //in seconds
            $total_running_time_min = (($differenceInSeconds*count($idle_time_data))-$total_idle_time_sec)/60; //all-vehicle in minutes
            $total_running_time = minutes_to_days($total_running_time_min);
        }
        
        if(!empty($idle_time_data)){
            $idle_time_data_final['veh_names'] = $idle_time_data_final['veh_idle_time'] = $idle_time_data_final['veh_running_time'] = '';
            $differenceIn_hrs = $differenceInSeconds/(60*60);
            $bar_height2 = 30;$height2 = 0;
            foreach($idle_time_data as $veh_name=>$single_idle_time){
                $single_idle_time = number_format((float)($single_idle_time)/(60), 2, '.', '');
                $single_running_time = number_format((float)($differenceIn_hrs - $single_idle_time), 2, '.', '');
                $idle_time_data_final['veh_names'] .= "'$veh_name', ";
                $idle_time_data_final['veh_idle_time'] .= $single_idle_time.", ";
                $idle_time_data_final['veh_running_time'] .= $single_running_time.", ";
                $height2 += $bar_height2;
            }
            $idle_time_data_final['idle_height'] = $height2;
        }
        $sun_data = sunday_distance_data('get_data');
        
        return array(
            'total'=>$tracked,
            'data'=>array($vehicle_name, $distance_travelled, $height), 
            'highest_dis_trav' => $highest_dis_trav, 
            'avg_dis_trav' => $avg_dis_trav,
            'avg_dis_trav_veh' => $avg_dis_trav_veh,
            'sun_dis_trav' => $sun_data['total'],
            'sun_dis_trav_data' => $sun_data['data'],
            'unique_dates' => $sun_data['dates'],
            'total_idle_time' => $total_idle_time_final,
            'total_running_time' => $total_running_time,
            'idle_time_data_final' => $idle_time_data_final
        );
    }
    else{
        return array('total'=>0);
    }
}

function sunday_distance_data($def=null, $info_date=null, $totaldistance=null, $vehiclename=null){
    
    static $sun_dis_trav, $sun_dis_trav_data, $unique_dates=array();
    if($def == 'get_data'){
        $sun_dis_trav = $sun_dis_trav/1000;
        return array('total'=>$sun_dis_trav, 'data'=>$sun_dis_trav_data, 'dates'=>$unique_dates);
    }
    else{
        
        $day_var = date('D', strtotime($info_date));
        if($day_var == 'Sun'){
            $sun_dis_trav += (int)$totaldistance;
            $day_var_key = date('jSM', strtotime($info_date));
            $proper_dis = round($totaldistance/1000);
            if($proper_dis!=0){
                if(isset($sun_dis_trav_data[$vehiclename][$day_var_key])){
                    $sun_dis_trav_data['data'][$vehiclename][$day_var_key] += $proper_dis;
                }
                else{
                    $sun_dis_trav_data['data'][$vehiclename][$day_var_key] = $proper_dis;
                }
            }
            if(!in_array($day_var_key, $unique_dates)){
                $unique_dates[] = $day_var_key;
            }
        }
    }
    
}

function genmonths($start_date, $end_date){
    $months = array();
    $date1 = date(strtotime($start_date));
    $date2 = date(strtotime($end_date));
    $difference = $date2 - $date1;
    $months_difference = floor($difference / 86400 / 30 );
    
    if($months_difference==0){
        $s = date('M', strtotime($start_date));
        $months[] = $s;
    }
    else{
        for($i=0;$i<=$months_difference;$i++){
            $s = date('M', strtotime("+$i month ".$start_date));
            $months[] = $s;
        }
    }
    return $months;
}

function get_comqueue_count($start_date, $end_date){
    global $customerno, $all_vehicles;
    
    $months = genmonths($start_date, $end_date);
    $total = 0;
    $data = array();
    
    foreach($months as $month){

        $start_date_t = $start_date.' 00:00:00';
        $end_date_t = $end_date.' 23:59:59';
        $y = date('Y', strtotime($start_date));
        $location1 = "../../customer/$customerno/history/{$month}{$y}.sqlite";
        
        if(file_exists($location1)){
            $location = "sqlite:$location1";
            $query = "SELECT type, vehicleid from comqueue WHERE timeadded>= '$start_date_t' and timeadded<='$end_date_t'" ;
            $database = new PDO($location);
            $result = $database->query($query);
            
            if(isset($result) && $result!=""){
                foreach ($result as $row){
                    $veh_name = $all_vehicles[$row['vehicleid']]['vehicleno'];
                    if($row['type']==1){ if(isset($data[$veh_name]['ac_alert'])){ $data[$veh_name]['ac_alert'] += 1; } else{ $data[$veh_name]['ac_alert'] = 1; } }
                    if($row['type']==2){ if(isset($data[$veh_name]['ch_alert'])){ $data[$veh_name]['ch_alert'] += 1; } else{ $data[$veh_name]['ch_alert'] = 1; } }
                    if($row['type']==4){ if(isset($data[$veh_name]['ig_alert'])){   $data[$veh_name]['ig_alert'] += 1;} else{ $data[$veh_name]['ig_alert'] = 1; } }
                    if($row['type']==5){ if(isset($data[$veh_name]['overspd_alert'])){ $data[$veh_name]['overspd_alert'] += 1; } else{ $data[$veh_name]['overspd_alert'] = 1; } }
                    if($row['type']==7){ if(isset($data[$veh_name]['tamp_alert'])){ $data[$veh_name]['tamp_alert'] += 1;} else{  $data[$veh_name]['tamp_alert'] = 1; } }
                    if($row['type']==6){ if(isset($data[$veh_name]['pwrcut_alert'])){ $data[$veh_name]['pwrcut_alert'] += 1; }  else{  $data[$veh_name]['pwrcut_alert'] = 1; } }
                    if($row['type']==3){ if(isset($data[$veh_name]['fence_alert'])){ $data[$veh_name]['fence_alert'] += 1; } else{ $data[$veh_name]['fence_alert'] = 1; } }
                    $total += 1;
                }
            }
        }
    }
    
    return array('total'=>$total, 'data'=>$data);
}

function get_ac_hold_time($acinvertval,$digitalio){
    $is_ac_opp = $acinvertval['0'];
    $acsensor = $acinvertval['1'];
    if($acsensor==1){
        if($is_ac_opp == 1){
            if ($digitalio != 0){  return true; } 
        }
        else{
            if ($digitalio == 0){ return true; } 
        }
    }
    return false;
}


function generate_trip_report($trip_days, $veh_id, $todo='else', $type='graph'){
    
    static $vehicle_trips = array(), $vehicle_trips_st = array();
    
    if($todo!="else"){
        if($todo=='get_data_end'){
            $main_array = $vehicle_trips;
        }
        else{
            $main_array = $vehicle_trips_st;
        }
        $total = array_sum($main_array);
        if($type=='graph'){
            global $def_chart_height;
            $vehs = $counts = '';
            $trip_hgt = $def_chart_height;
            foreach($main_array as $veh_name=>$nums){
                if($nums!=0){
                    $vehs .= "'$veh_name', ";
                    $counts .= $nums.', ';
                    $trip_hgt += 30;
                }
            }
            return array('vehicles'=>$vehs,'count'=>$counts, 'trip_hght'=>$trip_hgt, 'total'=>$total);
        }
        else{
            return array('data'=>$main_array, 'total'=>$total);
        }
    }
    
    if(!empty($trip_days)){
        global $all_vehicles;
        $veh_name = $all_vehicles[$veh_id]['vehicleno'];

        foreach($trip_days as $single_trips){
            
            if($single_trips->ignition!=0){
                $date = date('Y-m-d', strtotime($single_trips->endtime));
                $end_time = strtotime("$date 22:00:00");
                $start_time = strtotime("$date 21:00:00");
                
                   if(strtotime($single_trips->endtime) > $end_time){
                    
                    if(isset($vehicle_trips[$veh_name])){
                        $vehicle_trips[$veh_name] += 1;
                    }
                    else{
                        $vehicle_trips[$veh_name] = 1;
                    }
                }
                if(strtotime($single_trips->starttime) > $start_time){
                    if(isset($vehicle_trips_st[$veh_name])){
                        $vehicle_trips_st[$veh_name] += 1;
                    }
                    else{
                        $vehicle_trips_st[$veh_name] = 1;
                    }
                }
            }
        }
        if(empty($vehicle_trips[$veh_name])){
            $vehicle_trips[$veh_name] = 0;
        }
        if(empty($vehicle_trips_st[$veh_name])){
            $vehicle_trips_st[$veh_name] = 0;
        }
    }
}

function getdata_fromsqlite($location,$deviceid,$overspeed_limit,$Shour,$Ehour,$userdate,$acinvertval){
    $devices = array();
    $idle_time_data = array();
    $ac_idle_time_data = array();
    $all_data = array();
    $trip_data = array();
    $trip = false;
    $trip_data_f = array();
    $last_trip = '';
    $holdtime = 15;
    $query = "SELECT devicehistory.deviceid, unithistory.digitalio, devicehistory.ignition, devicehistory.lastupdated, devicehistory.status, 
            vehiclehistory.curspeed, devicehistory.devicelat, devicehistory.devicelong, vehiclehistory.vehicleid, vehiclehistory.odometer
            from devicehistory INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
            INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.deviceid= $deviceid AND devicehistory.status!='F'" ;
    
    if($Shour != Null){
        $query .= " AND devicehistory.lastupdated >= '$userdate $Shour'";
    }
    if($Ehour != Null){
        $query .= " AND devicehistory.lastupdated <= '$userdate $Ehour'";        
    }
    $query .= "  ORDER BY devicehistory.lastupdated ASC";
    
    try
    {
        $database = new PDO($location);
        $result = $database->query($query);
        if(isset($result) && $result!=""){
            
            $overspeed_flag = $lastspeed = $pusharray = 0;
            //$lastupdated = "";
            //$lastodometer = "";
            //$pusharray_hold = 1;
            $loop = 1;
            
            $test_dist = 0;
            $disp = 0;
            $min_disp = 0;
            $last_ig = 'off';
            $hold_time_sec = 15*60;
            
            foreach ($result as $row){
                
                //if($row['vehicleid']==1155){
                    
                    //ignition on, idle and  for 15 mins starts
                    if($test_dist!=0){
                        $disp = $row['odometer'] - $test_dist;
                    }
                    
                    if($row['ignition']==1 && $disp==$min_disp){
                        
                        if($last_ig=='on'){
                            
                            $end_time = $row['lastupdated'];
                            //echo "**************ig: {$row['ignition']}, distance: $disp, time: {$row['lastupdated']}<br/>";
                        }
                        else{
                            $start_time = $row['lastupdated'];
                            //echo "==============ig: {$row['ignition']}, distance: $disp, time: {$row['lastupdated']}<br/>";
                        }
                        $last_ig = 'on';
                    }
                    else{
                        //$duration = '';
                        if(isset($end_time)){
                            $diff = strtotime($end_time) - strtotime($start_time);
                            if($diff >= $hold_time_sec){
                                $idle_time_data[] = $row['vehicleid'];
                                //$duration = '>>>>>>>>>>>>>>>>>>>>>>>>>>>>'.$diff;
                                //echo "ig: {$row['ignition']}, distance: $disp, time: {$row['lastupdated']} $duration<br/>";
                                
                                //starts, for AC-hold-time
                                if(get_ac_hold_time($acinvertval, $row['digitalio'])){
                                    $ac_idle_time_data[] = $row['vehicleid'];
                                }
                                //ends, for AC-hold-time
                            }
                            //else{
                              //  echo "ig: {$row['ignition']}, distance: $disp, time: {$row['lastupdated']} $duration<br/>";
                            //}
                            
                        }
                        //else{
                           // echo "ig: {$row['ignition']}, distance: $disp, time: {$row['lastupdated']} $duration<br/>";
                        //}
                        
                        $last_ig = 'off';
                        unset($end_time);
                    }
                    $test_dist = $row['odometer'];
                //}
                //ignition on, idle and  for 15 mins ends
                 
                
                $all_device = new VODatacap_i();
                $all_device->vehicleid = $row['vehicleid'];
                $all_device->status = $row['status'];
                $all_device->lastupdated = $row['lastupdated'];
                
                if (!isset($laststatus) || $laststatus != $row['ignition']){
                    $trip_device = new VODatacap_i();
                    $trip_device->deviceid = $row['deviceid'];
                    $trip_device->vehicleid = $row['vehicleid'];
                    $trip_device->devicelat = $row['devicelat'];
                    $trip_device->devicelong = $row['devicelong'];
                    $trip_device->ignition = $row['ignition'];
                    $trip_device->status = $row['status'];
                    $trip_device->lastupdated = $row['lastupdated'];
                    $trip_device->odometer = $row['odometer'];
                    $laststatus = $row['ignition'];
                    $trip_data[] = $trip_device;
                    $trip=true;
                }
                
                $all_device->deviceid = $row['deviceid'];
                $all_device->devicelat = $row['devicelat'];
                $all_device->devicelong = $row['devicelong'];
                $all_device->ignition = $row['ignition'];
                $all_device->odometer = $row['odometer'];
                $all_device->curspeed = $row['curspeed'];
                /**/
                
                $all_data[] = $all_device;
                
                /*if($row['curspeed'] >= $last_speed){
                    
                    $last_speed = $row['curspeed'];
                    $device->curspeed = $row['curspeed'];
                    $device->starttime = $row['lastupdated'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];  
                    $device->vehicleid = $row['vehicleid'];
                    $device->status = $row['status'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->maxspeed = $row['curspeed'];
                    $device->endtime = $row['lastupdated'];
                }*/
                
                
                /*starts, for overspeed report*/
                if($row['curspeed'] > $overspeed_limit && $overspeed_flag == 0){
                    $lastspeed = $row['curspeed'];                    
                    $overspeed_flag = 1;
                    $device = new VODatacap_i();
                    $device->curspeed = $row['curspeed'];
                    $device->starttime = $row['lastupdated'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];  
                    $device->vehicleid = $row['vehicleid'];
                    $device->status = $row['status'];
                    $device->lastupdated = $row['lastupdated'];
                }
                else if($overspeed_flag == 1 && $row['curspeed'] < $overspeed_limit){
                    $overspeed_flag = 0;
                    $device->endtime = $row['lastupdated'];
                    $pusharray = 1;
                }
                if($overspeed_flag == 1){
                    if($row['curspeed'] >= $lastspeed){
                        $device->maxspeed = $row['curspeed'];
                        $lastspeed = $row['curspeed'];                        
                    }
                }
                if($pusharray == 1){
                    $devices[] = $device;  
                    $pusharray = 0;                    
                }
                /*ends, for overspeed report*/
                
                
                /*starts, for hold-time*
                if($row['ignition']==1){
                    if($lastodometer == ""){
                        $lastodometer = $row["odometer"];
                        $lastupdated = $row['lastupdated'];                    
                    }

                    if(round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60,2) > $holdtime && $row["odometer"] - $lastodometer < 100 && $pusharray_hold == 1){
                        $lastodometer = $row["odometer"];
                        $lastupdated = $row['lastupdated'];    
                        $pusharray_hold = 0;
                    }
                    else{
                        if($row["odometer"] - $lastodometer > 25){
                            if($pusharray_hold == 0){
                                $idle_time_data[] = $row['vehicleid'];
                                //starts, for AC-hold-time
                                if(get_ac_hold_time($acinvertval, $row['digitalio'])){
                                    $ac_idle_time_data[] = $row['vehicleid'];
                                }
                                //ends, for AC-hold-time
                            }
                            $lastodometer = $row["odometer"];
                            $lastupdated = $row['lastupdated'];
                            $pusharray_hold = 1;
                        }
                    }
                }
                /*ends, for hold-time*/
                $loop++;
            }
            
            /*new top-speed condition*/
            /*if (!empty((array) $device)) {
                $devices[] = $device;
            }*/
            /**/
            if($trip){
                $trip_device = new VODatacap_i();
                $trip_device->deviceid = $row['deviceid'];
                $trip_device->vehicleid = $row['vehicleid'];
                $trip_device->devicelat = $row['devicelat'];
                $trip_device->devicelong = $row['devicelong'];
                $trip_device->ignition = $row['ignition'];
                $trip_device->status = $row['status'];
                $trip_device->lastupdated = $row['lastupdated'];
                $trip_device->odometer = $row['odometer'];
                $last_trip = $trip_device;
                $trip_data_f = array($trip_data, $last_trip);
            }
            
            if($overspeed_flag == 1){
                $device->endtime = $row['lastupdated'];                
                $devices[] = $device;                                                                
            }
            /*if($pusharray_hold == 0){
                $idle_time_data[] = $row['vehicleid'];
                if(get_ac_hold_time($acinvertval, $row['digitalio'])){
                    $ac_idle_time_data[] = $row['vehicleid'];
                }
            }*/
        }
    } 
    catch (PDOException $e){
        die($e);
    }
    
    //if($row['vehicleid']==1155){
        //global $all_vehicles;
        //echo $all_vehicles[$idle_time_data[0]]['vehicleno'];
        //echo "<pre>";print_r($idle_time_data);echo "</pre>";
        //die();
    //}
    return array(
        'data'=>$devices,
        'idle_time_data' => $idle_time_data,
        'ac_idle_time_data' => $ac_idle_time_data,
        'all_data'=>$all_data,
        'trip_data'=>$trip_data_f
    );
}

function get_over_speed_data($start_date, $end_date){
    global $all_vehicles, $customerno;
    
    $totaldays = gendays_cmn($start_date,$end_date);
    //$deviceids =  $devicemanager->get_all_devices();
    
    $return_data = array();
    $top_speed_details = '';
    $def_speed = $harsh_break = $acc_day = $acc_night = $sharp_turn = $towing = 0;
    $all_vehicle_top_speed = array();
    $container_custom_report = array();
    $idle_hold_ig_off_data = $idle_hold_ig_off_veh = '';
    $idle_hold_ig_ac_data = $idle_hold_ig_ac_veh = '';
    $veh_count_idle = $veh_count_idle_ac = 0;
    $harsh_break_status = "S";
    $sudden_acc_status = "U";
    if(time()<strtotime("2014-12-01")){
        $sudden_acc_status = "D";
    }
    $sharp_turn_status = "W";
    //$towing_status = "V";
    $veh_id_trip = null;
    
    
    foreach($all_vehicles as $data_for){
        $deviceid = $data_for['deviceid'];
        $unitno = $data_for['unitno'];
        $overspeed_limit = $data_for['overspeed_limit'];//$vehiclemanager->getoverspeed_limit_deviceid($deviceid);
        $acinvertval =  array('0'=>$data_for['is_ac_opp'], '1'=>$data_for['acsensor']);//$unitmanager->getacinvertval($unitno);
        
        if(isset($totaldays))
        {
            $days = array();
            $days_hold = array();
            $days_hold_ac = array();
            $full_data = array();
            $trip_days = array();
            foreach($totaldays as $userdate) 
            {
                $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                
                if (file_exists($location)){
                    
                    //Date Check Operations
                    $location = "sqlite:".$location;
                    $data_all = getdata_fromsqlite($location,$deviceid,$overspeed_limit,"00:00:00", "23:59:59",$userdate,$acinvertval);
                    $data = $data_all['data'];
                    $data_hold = $data_all['idle_time_data'];
                    $data_hold_ac = $data_all['ac_idle_time_data'];
                    $main_data = $data_all['all_data'];
                    $trip_data = $data_all['trip_data'];
                    
                    if(count($data_hold)>0){
                        $days_hold = array_merge($days_hold,$data_hold);
                    }
                    if(count($data_hold_ac)>0){
                        $days_hold_ac = array_merge($days_hold_ac, $data_hold_ac);
                    }
                    if($data!=NULL && count($data)>0){
                        $days = array_merge($days,$data);
                    }
                    if($main_data!=NULL && count($main_data)>0){
                        $full_data = array_merge($full_data,$main_data);
                    }
                    if($trip_data!=NULL && count($trip_data)>0){
                        $veh_id_trip = $trip_data[1]->vehicleid;
                        $lastday = processtraveldata($trip_data);
                        $trip_days = array_merge($trip_days,$lastday);
                    }
                }
            }
            
            generate_trip_report($trip_days, $veh_id_trip);
            
            
            if(!empty($full_data)){
                foreach($full_data as $single_data){
                    
                    if($single_data->status==$harsh_break_status){
                        $harsh_break += 1;
                        if(isset($container_custom_report[$single_data->vehicleid]['harsh_break'])){
                            $container_custom_report[$single_data->vehicleid]['harsh_break'] += 1;
                        }
                        else{
                            $container_custom_report[$single_data->vehicleid]['harsh_break'] = 1;
                        }
                    }
                    if($single_data->status==$sudden_acc_status){
                        $str_to_time = strtotime($single_data->lastupdated);
                        $seven_am = date('Y-m-d 07:00:00', $str_to_time);
                        $eight_pm = date('Y-m-d 20:00:00', $str_to_time);
                        if($single_data->lastupdated >= $seven_am && $single_data->lastupdated <= $eight_pm){
                            $acc_day += 1;
                            if(isset($container_custom_report[$single_data->vehicleid]['acc_day'])){
                                $container_custom_report[$single_data->vehicleid]['acc_day'] += 1;
                            }
                            else{
                                $container_custom_report[$single_data->vehicleid]['acc_day'] = 1;
                            }
                        }
                        else{
                            if(isset($container_custom_report[$single_data->vehicleid]['acc_night'])){
                                $container_custom_report[$single_data->vehicleid]['acc_night'] += 1;
                            }
                            else{
                                $container_custom_report[$single_data->vehicleid]['acc_night'] = 1;
                            }
                            $acc_night += 1;
                        }
                    }
                    
                    if(time()>=strtotime("2014-12-01")){
                        if($single_data->status==$sharp_turn_status){
                            $sharp_turn += 1;
                            if(isset($container_custom_report[$single_data->vehicleid]['sharp_turn'])){
                                $container_custom_report[$single_data->vehicleid]['sharp_turn'] += 1;
                            }
                            else{
                                $container_custom_report[$single_data->vehicleid]['sharp_turn'] = 1;
                            }
                        }
                        if(($single_data->ignition==0) && ($single_data->curspeed>=5)){
                            $towing += 1;
                            if(isset($container_custom_report[$single_data->vehicleid]['towing'])){
                                $container_custom_report[$single_data->vehicleid]['towing'] += 1;
                            }
                            else{
                                $container_custom_report[$single_data->vehicleid]['towing'] = 1;
                            }
                        }
                    }
                }
            }
            if(!empty($days)){
                $i=0;
                foreach($days as $single_data){
                    $i++;
                    if($single_data->maxspeed>$def_speed){
                        $top_speed_details = $single_data;
                        $def_speed = $single_data->maxspeed;
                    }
                    
                    if(isset($all_vehicle_top_speed[$single_data->vehicleid])){
                        if($all_vehicle_top_speed[$single_data->vehicleid]->maxspeed < $single_data->maxspeed){
                            $all_vehicle_top_speed[$single_data->vehicleid] = $single_data;
                        }
                    }
                    else{
                        $all_vehicle_top_speed[$single_data->vehicleid] = $single_data;
                    }
                }
            }
            
            
            if(!empty($days_hold)){
                $veh_name = $all_vehicles[$days_hold[0]]['vehicleno'];
                $veh_count = count($days_hold);
                $idle_hold_ig_off_veh .= "'$veh_name',";
                $idle_hold_ig_off_data .= $veh_count.",";
                $veh_count_idle++;
            }
            
            if(!empty($days_hold_ac)){
                $veh_name = $all_vehicles[$days_hold_ac[0]]['vehicleno'];
                $veh_count = count($days_hold_ac);
                $idle_hold_ig_ac_veh .= "'$veh_name',";
                $idle_hold_ig_ac_data .= $veh_count.",";
                $veh_count_idle_ac++;
            }
        }
    }
    
    if($idle_hold_ig_off_data!=''){
        $idle_hold_ig_off_height = 30*$veh_count_idle;
        $return_data['idle_hold_ig_off_data'] = array('veh'=>$idle_hold_ig_off_veh, 'count'=>$idle_hold_ig_off_data, 'height'=>$idle_hold_ig_off_height);
    }
    
    if($idle_hold_ig_ac_data!=''){
        $idle_hold_ig_ac_height = 30*$veh_count_idle_ac;
        $return_data['idle_hold_ig_ac_data'] = array('veh'=>$idle_hold_ig_ac_veh, 'count'=>$idle_hold_ig_ac_data, 'height'=>$idle_hold_ig_ac_height);
    }
    
    if($top_speed_details!=''){
        $date = date('dS M', strtotime($top_speed_details->starttime));
        $duration = date('h:ia', strtotime($top_speed_details->starttime)).'-'.date('h:ia', strtotime($top_speed_details->endtime));
        $veh_name = $all_vehicles[$top_speed_details->vehicleid]['vehicleno'];
        $return_data['top_speed'] = array($veh_name, $top_speed_details->maxspeed, $date, $duration);
    }

    if(!empty($all_vehicle_top_speed)){
        
        $os_speed_veh = $os_speed_kmph = '';
        $height = 0;$def_height = 30;
        foreach($all_vehicle_top_speed as $single_top){
            $date = date('jS M', strtotime($single_top->starttime));
            $duration1 = strtotime($single_top->endtime)-strtotime($single_top->starttime);
            $duration2 = gmdate("i:s", ($duration1));
            $veh_name =   $all_vehicles[$single_top->vehicleid]['vehicleno'];
            $os_speed_veh .= "'$veh_name ($date) ($duration2)', ";
            $os_speed_kmph .= $single_top->maxspeed.',';
            $height += $def_height;
        }
        $return_data['top_speed_all'] = array($os_speed_veh, $os_speed_kmph, $height);
    }
    
    if(!empty($container_custom_report)){
        
        $return_data['custom_report']['vehicles'] = $return_data['custom_report']['harsh_b'] = '';
        $return_data['custom_report']['accl_night'] = $return_data['custom_report']['accl_day'] = '';
        $return_data['custom_report']['sharp_turn'] = $return_data['custom_report']['towing'] = '';
        $height = 0;$def_height = 30;
        foreach($container_custom_report as $veh_id=>$data){
            $veh_name = $all_vehicles[$veh_id]['vehicleno'];
            $h_b = isset($data['harsh_break']) ? $data['harsh_break']: 0;
            $c_d = isset($data['acc_day']) ? $data['acc_day']: 0;
            $c_n = isset($data['acc_night']) ? $data['acc_night']: 0;
            $s_t = isset($data['sharp_turn']) ? $data['sharp_turn']: 0;
            $tow = isset($data['towing']) ? $data['towing']: 0;
            
            $return_data['custom_report']['vehicles'] .= "'$veh_name',";
            $return_data['custom_report']['harsh_b'] .= $h_b.',';
            $return_data['custom_report']['accl_day'] .= $c_d.', ';
            $return_data['custom_report']['accl_night'] .= $c_n.', ';
            $return_data['custom_report']['sharp_turn'] .= $s_t.', ';
            $return_data['custom_report']['towing'] .= $tow.', ';
            $height += $def_height;
        }
        
        $return_data['custom_report']['height'] = $height;
    }
    
    $return_data['harsh_break'] = $harsh_break;
    $return_data['acc_day'] = $acc_day;
    $return_data['acc_night'] = $acc_night;
    $return_data['sharp_turn'] = $sharp_turn;
    $return_data['towing'] = $towing;
    
    return $return_data;
}

?>