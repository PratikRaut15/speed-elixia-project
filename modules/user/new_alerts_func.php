<?php
if(!isset($_SESSION)){
    session_start();
}

include_once '../../lib/bo/NewAlertsManager.php';

function add_vehicle_alerts($userid, $groups=0){
    
    include_once "new_alerts_columns.php";
    
    $data = $_POST;
    $customerno = $_SESSION['customerno'];
    $where = array('customerno'=>$customerno, 'userid'=>$userid);
    $vehicleManager = new VehicleManager($customerno);
    
    $set_data = array();
    foreach($parent_arr as $parent){
        foreach($child_arr as $child=>$val){
            $set_data[$parent][$child] = isset($data[$parent][$child]) ? GetSafeValueString($data[$parent][$child], 'string') : $val;
        }
    }
    
    $column_wise = array();
    foreach($set_data as $p=>$c){
        
        /*Starts, Array for vehiclewise_alert-table*/
        
        $all_vehicles = $vehicleManager->get_all_vehicles_by_group($groups);
        if(isset($all_vehicles)){
            foreach($all_vehicles as $veh_details){
            $vehicle_id = $veh_details->vehicleid;
            
            foreach($c as $name=>$v){
                $c_key = $p.$name; 
                if(isset($column_arr[$c_key])){
                    $c_data = $column_arr[$c_key];
                    $t_name = $c_data[0];
                    $c_name = $c_data[1];
                    if($t_name=='alert'){
                        
                        if($c_name=='vehicleid'){
                            $v = $vehicle_id;
                        }
                        if(strpos($v, ':')){
                            $column_wise[$t_name][$vehicle_id][$c_name] = "'$v'";
                        }
                        else{
                            $column_wise[$t_name][$vehicle_id][$c_name] = $v;
                        }
                    }
                }
            }
            
            $active_c = $column_arr[$p.'active'][1];
            $column_wise['alert'][$vehicle_id]['customerno'] = $customerno;
            $column_wise['alert'][$vehicle_id]['userid'] = $userid;
            $column_wise['alert'][$vehicle_id][$active_c] = 1;
            
        }
        }
        /*Ends, Array for vehiclewise_alert-table*/
        
        
    }
    
    //print_r($column_wise);die();
    $AlertManager = new NewAlertsManager();
    $AlertManager->alerts_changes($column_wise, $where);
    
}

?>