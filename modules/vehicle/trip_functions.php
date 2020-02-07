<?php
include_once '../../lib/bo/CheckpointManager.php';
include_once '../../lib/comman_function/reports_func.php';

//date_default_timezone_set("Asia/Calcutta");
if(!isset($_SESSION)){
    session_start();
    if(!isset($_SESSION['timezone'])){
    $_SESSION['timezone'] = 'Asia/Kolkata';
}
    date_default_timezone_set(''.$_SESSION['timezone'].'');
}

class VODatacap{}

function display_trip_alerts(){
    $data = '<div class="container">
    <table class="table newTable" >
    <thead>
        <tr><th colspan="100%" style="font-weight:bold;font-size:14px;">Current Trips</th></tr>
        <tr class="tableSub">
            <td>#</td>
            <td>Vehicle No</td>
            <td>Start Date</td>
            <td>Start Hour</td>
            <td>Start Checkpoint</td>
            <td>End Checkpoint</td>
            <td>Driving Distance</td>
            <td>View</td>
            <td>Delete</td>
        </tr>
    </thead>
    <tbody>';
    $trips = trip_table();
    if($trips){
        $data .= $trips;
    }
    else{
        $data .= '<tr><td colspan="100%" style="text-align:center;">No Data</td></tr>';
    }
    $data .= '</tbody>
</table>
</div>';

    return $data;
}

function display_errors($text){
    echo "<script>displayError('$text');</script>";
    return false;
}
function display_success($text){
    echo "<span id='displaySuccess' style='font-weight:bold;color:green;'>$text</span>";
}

function validate_trip_inputs($vehicleid, $cp_start, $cp_end, $s_date, $s_time){
    if(!isset($_SESSION['customerno'])){
        return display_errors('Session expired. Please login');
    }
    if($vehicleid==''){
        return display_errors('Vehicle id not found');
    }
    if($cp_start=='' || $cp_end==''){
        return display_errors('Please select start-end checkpoint');
    }
    if($cp_start==$cp_end){
        return display_errors('Start-End checkpoint should be different');
    }
    
    if(check_if_trip_exists($vehicleid, $cp_start, $cp_end, $s_date, $s_time)){
        return display_errors('Same alert already exists');
    }
    
    return true;
}

function check_if_trip_exists($vehicleid, $cp_start, $cp_end, $s_date, $s_time){
    $cm = new CheckpointManager($_SESSION['customerno']);
    return $cm->check_trip_alert($vehicleid, $cp_start, $cp_end, $s_date, $s_time);
}

function insert_trip_alert($data){
    $cm = new CheckpointManager($_SESSION['customerno']);
    $cm->add_trip_alert($data, $_SESSION['userid']);
}

function trip_table(){
    $cm = new CheckpointManager($_SESSION['customerno']);
    $data = $cm->get_trip_alert();
    if($data){
        $records = '';
        $count = 1;
        foreach($data as $val){
            $records .= "<tr>";
            $records .= "<td>$count</td>";
            $records .= "<td>{$val['vehicleno']}</td>";
            $records .= "<td>{$val['start_date']}</td>";
            $records .= "<td>{$val['start_time']}</td>";
            $records .= "<td>{$val['s_checkpoint']}</td>";
            $records .= "<td>{$val['e_checkpoint']}</td>";
            $records .= "<td>{$val['driving_distance']}</td>";
            $records .= "<td><a href='javascript:void(0)' id='{$val['tripid']}' class='label label-info viewC' data-toggle='modal' data-target='#tripReportModal' >Report</a></td>";
            $records .= "<td><a href='javascript:void(0)' onclick='delete_trip({$val['tripid']});' class='label label-danger viewC' style='background-color:#d9534f' >Delete</a></td>";
            $records .= "</tr>";
            $count++;
        }
        return $records;
    }
    else{
        return false;
    }
}

function json_exit($status,$text, $endtime=null){
    $arr = array('status'=>$status, 'data'=>$text);
    if($endtime!=null){
        $arr['end_time'] = $endtime;
    }
    echo json_encode($arr);
    exit;
}

function get_trip_genset($tripid){
    $cm = new CheckpointManager($_SESSION['customerno']);
    $t = $cm->tripdetails_by_id($tripid);
    if(!$t){
        json_exit(0, "Trip alert not found");
    }
    $location = "../../customer/".$t['customerno']."/reports/chkreport.sqlite";
    $STdate = $t['start_date'].' '.$t['start_time'];
    $datas = Get_ac_dist_Report_trip($location, $STdate, $t['vehicleid'], $t['start_checkpoint_id'], $t['end_checkpoint_id']);
    if(!$datas['status']){
        json_exit(0, $datas['data']);
    }
    else{
        return($datas['data']);
    }
}

function trip_details_table($trip_data){
    $all = "<tr>";
    $all .= "<td style='text-align:center;'>{$trip_data['total_distance']}</td>";
    $all .= "<td style='text-align:center;'>{$trip_data['genset']}</td>";
    $all .= "</tr>";
    json_exit(1, $all, $trip_data['end_time']);
}

function Get_ac_dist_Report_trip($location,$STdate,$vehid,$chkpt_start,$chkpt_end){
    $customerno = $_SESSION['customerno'];
    $path = "sqlite:$location"; 
    $db = new PDO($path);
    $Query_Start = "SELECT * FROM V$vehid WHERE date >= '$STdate' AND chkid IN ($chkpt_start, $chkpt_end)  and (CASE WHEN chkid=$chkpt_start THEN status=1 else status=0 end) ORDER BY date ASC limit 2 ";
    $result_Start = $db->query($Query_Start);
    if(isset($result_Start) && $result_Start !=''){
        $REPORT_Start = array();
        foreach($result_Start as $row_Start){
            $Datacap = new VODatacap;
            $Datacap->chkrepid = $row_Start['chkrepid'];
            $Datacap->chkid = $row_Start['chkid'];
            $Datacap->status = $row_Start['status'];
            $Datacap->date = $row_Start['date'];
            $REPORT_Start[] = $Datacap;
        }
        
        if(count($REPORT_Start)<2){
            return array('status'=>false, 'data'=>'Trip did not complete');
        }
        
        $start_chkppoint = $REPORT_Start[0];
        $end_chkppoint = $REPORT_Start[1];
        
        include_once '../../lib/bo/UnitManager.php';
        $um = new UnitManager($customerno);
        
        $unitno = $um->getuidfromvehicleid($vehid);
        if(isset($unitno) && $unitno !=''){
            
            include_once '../../lib/bo/DeviceManager.php';
            $dm = new DeviceManager($customerno);
            $did = $dm->GetDevice_byId($vehid);
            $invertval = $um->getacinvertval($unitno);
            $ret = get_unitno_genset($invertval[0], $start_chkppoint->date, $end_chkppoint->date, $unitno, $customerno, $did);
            
            return array(
                'status' => true,
                'data' => array(
                    'total_distance' => $ret['distance'],
                    'genset' => $ret['ac_usage'],
                    'end_time' => $end_chkppoint->date
                )
            );
        }
        else{
            return array('status'=>false, 'data'=>'Unit No not found');
        }
    }
    else{
        return array('status'=>false, 'data'=>'No records found');
    }
}

function get_unitno_genset($acinvertval, $start_datetime, $end_datetime, $unitno, $customerno, $deviceid){
    
    /*test*
    $acinvertval = 1;
    $start_datetime = "2015-01-01 00:00:00";
    $end_datetime = "2015-01-12 23:59:00";
    $customerno = 64;
    $unitno = 99606;
    $deviceid = 1045;
    /**/
    
    $start_date = $while_start = date('Y-m-d', strtotime($start_datetime));
    $end_date = date('Y-m-d', strtotime($end_datetime));
    $duration = 0;
    $loop = 0;
    $distance = 0;
    
    while(TRUE){
        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$while_start.sqlite";
        if(file_exists($location)){
            $location = "sqlite:".$location;
            $custom_query = '';
            if($start_date==$end_date){
                $custom_query = "AND devicehistory.lastupdated >= '$start_datetime' and devicehistory.lastupdated <= '$end_datetime'";
            }
            else{
                if($while_start==$start_date){
                    $custom_query = "AND devicehistory.lastupdated >= '$start_datetime'";
                }
                elseif($while_start==$end_date){
                    $custom_query = "AND devicehistory.lastupdated <= '$end_datetime'";
                }
            }
            $query = "SELECT devicehistory.lastupdated, unithistory.digitalio, vehiclehistory.odometer from devicehistory INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
                INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated WHERE devicehistory.deviceid= $deviceid AND devicehistory.status!='F' $custom_query  group by devicehistory.lastupdated" ;
            
            try{
                $database = new PDO($location);
                $result = $database->query($query);

                if(isset($result) && $result!=""){
                    $i = 0;
                    $changein = '';
                    $lastio = 'default';
                    $max_odo = 0;
                    $data = array();
                    foreach ($result as $row){
                        if(!isset($firstodometer)){
                            $firstodometer = $row['odometer'];
                        }
                        if($row['odometer']>$max_odo){
                            $max_odo = $row['odometer'];
                        }
                        if($lastio!=$row['digitalio']){
                            if(isset($data[$changein])){
                                $data[$changein]['enddate'] = $row['lastupdated'];
                            }
                            $data[$i] = array(
                                'startdate' => $row['lastupdated'],
                                'digitalio' => $row['digitalio']
                            );
                            $changein = $i;
                        }
                        $lastio = $row['digitalio'];
                        $i++;
                    }
                    $data[$changein]['enddate'] = $row['lastupdated'];
                    $lastodometer = $row['odometer'];
                    if($lastodometer < $firstodometer){
                        $lastodometer = $max_odo + $lastodometer;
                    }
                    $distance += round(($lastodometer - $firstodometer) / 1000 , 2);
                
                    foreach($data as $calc_d){
                        if(comman_ac_status($acinvertval, $calc_d['digitalio'])){
                            $diff = strtotime($calc_d['enddate']) - strtotime($calc_d['startdate']);
                            $duration += $diff;
                        }
                    }
                }
            } 
            catch (PDOException $e){
                die($e);
            }
        }
        if($while_start==$end_date){
            break;
        }
        else{
            $while_start = date('Y-m-d', strtotime($while_start.' +1 days'));
        }
        $loop++;
        if($loop>=31){
            break;
        }
    }
    $hh_mm = get_hh_mm($duration); //convert seconds to hh:mm
    return array(
        'ac_usage'=>$hh_mm,
        'distance'=>$distance
    );
}


?>
