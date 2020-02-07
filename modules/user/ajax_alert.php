<?php
if (!isset($_SESSION)) {
    session_start();
}
include_once '../../lib/comman_function/reports_func.php';
include_once '../../lib/autoload.php';
include_once "new_alerts_columns.php";
$data = $_POST;
$customerno = exit_issetor($_SESSION['customerno'], 'Please login');
$userid = isset($data['uid']) ? (int) $data['uid'] : $_SESSION['userid'];
$where = array('customerno' => $customerno, 'userid' => $userid);
$todo = retval_issetor($data['toDo']);
$AlertManager = new NewAlertsManager();
if ($todo === 'getAlerts') {
    $parent = exit_issetor($data['popAlertType'], 'No vehicle specific alert found');
    $rows = $AlertManager->get_vehicle_alert($parent, $userid);
    echo json_encode($rows);exit;
} elseif ($todo === 'specVehicles') {
    $parent = exit_issetor($data['popAlertType'], 'Please try again');
    $all_vehicles = exit_issetor($data['pop']['vehlist'], 'No Vehicles Found');
    $starttime = $data['pop']['stTime'];
    $endtime = $data['pop']['edTime'];
    $column_wise = array();
    foreach ($all_vehicles as $key => $vehid) {
        $start_c = $parent . 'stTime';
        $end_c = $parent . 'edTime';
        $active_c_name = $column_arr[$parent . 'active'][1];
        $s_val = isset($starttime[$key]) ? GetSafeValueString($starttime[$key], 'string') : $child_arr['stTime'];
        $e_val = isset($endtime[$key]) ? GetSafeValueString($endtime[$key], 'string') : $child_arr['edTime'];
        $column_wise['alert'][$vehid] = array(
            'customerno' => $customerno,
            'userid' => $userid,
            'vehicleid' => $vehid,
            $column_arr[$start_c][1] => "'$s_val'",
            $column_arr[$end_c][1] => "'$e_val'",
            $active_c_name => 1,
        );
    }
    //print_r($column_wise);die();
    $AlertManager->alerts_changes($column_wise, $where, 'spec', $active_c_name, $userid);
} elseif ($todo === 'addVehAlert' || $todo === 'addAdvancedAlert') {
    $vehicleManager = new VehicleManager($customerno);
    $usermanager = new UserManager();

    $isTempInrangeAlertRequired = isset($data['isTempInrangeAlertRequired']) ? $data['isTempInrangeAlertRequired'] : 1;
    $AlertManager->updateTempInrangeAlertRequired($userid, $isTempInrangeAlertRequired);

    $isAdvTempConfRange = isset($data['isAdvTempConfRange']) ? $data['isAdvTempConfRange'] : 0;
    $AlertManager->updateAdvTempConfRange($userid, $isAdvTempConfRange);

    if (isset($data['alertSTime']) && isset($data['alertETime'])) {
        $AlertManager->updateAlertStartStopTime($userid, $data['alertSTime'], $data['alertETime']);
    }
    $parent_arr = array();
    if ($todo == 'addAdvancedAlert') {
        $user = $usermanager->get_user($customerno, $userid);
        if ($user->use_advanced_alert != 0) {
            $parent_arr = array('harsh_break', 'high_acce', 'towing');
        }
        if ($user->use_immobiliser != 0) {
            $parent_arr[] = 'immob';
        }
        if ($user->use_panic != 0) {
            $parent_arr[] = 'panic';
        }
    } elseif ($todo == 'addVehAlert') {
        $parent_arr = array('temp', 'hum', 'ignition', 'speed', 'ac', 'powerc', 'tamper', 'door');
    }
    if (empty($parent_arr)) {
        die();
    }
    $set_data = array();
    foreach ($parent_arr as $parent) {
        foreach ($child_arr as $child => $val) {
            $set_data[$parent][$child] = isset($data[$parent][$child]) ? GetSafeValueString($data[$parent][$child], 'string') : $val;
        }
    }
    $column_wise = array();
    foreach ($set_data as $p => $c) {
        /* starts, Array for User-table */
        foreach ($c as $name => $v) {
            $c_key = $p . $name;
            if (isset($column_arr[$c_key])) {
                $c_data = $column_arr[$c_key];
                $t_name = $c_data[0];
                $c_name = $c_data[1];
                if ($t_name == 'user' && $name == 'interval') {
                    $column_wise[$t_name][$c_name] = (int) $v;
                } elseif ($t_name == 'user') {
                    $v = ($v === 'on') ? 1 : 0;
                    $column_wise[$t_name][$c_name] = $v;
                }
            }
        }
        /* ends, Array for User-table */
        //print_r($column_wise);
        if ($c['veh'] == 'spec') {
            continue;
        }
        /* Starts, Array for vehiclewise_alert-table */
        $all_vehicles = $vehicleManager->get_all_vehicles_with_unitno();
        foreach ($all_vehicles as $veh_details) {
            $vehicle_id = $veh_details->vehicleid;
            foreach ($c as $name => $v) {
                $c_key = $p . $name;
                if (isset($column_arr[$c_key])) {
                    $c_data = $column_arr[$c_key];
                    $t_name = $c_data[0];
                    $c_name = $c_data[1];
                    if ($t_name == 'alert') {
                        if ($c_name == 'vehicleid') {
                            $v = $vehicle_id;
                        }
                        if (strpos($v, ':')) {
                            $column_wise[$t_name][$vehicle_id][$c_name] = "'$v'";
                        } else {
                            $column_wise[$t_name][$vehicle_id][$c_name] = $v;
                        }
                    }
                }
            }
            $active_c = $column_arr[$p . 'active'][1];
            $column_wise['alert'][$vehicle_id]['customerno'] = $customerno;
            $column_wise['alert'][$vehicle_id]['userid'] = $userid;
            $column_wise['alert'][$vehicle_id][$active_c] = 1;
        }
        /* Ends, Array for vehiclewise_alert-table */
    }
    //echo count($column_wise['alert']);
    //print_r($column_wise['user']);die();
    $AlertManager->alerts_changes($column_wise, $where);
    $user = new stdClass();
    /* Checkpoint Exception User Alert Mapping */
    $user->userid = $userid;
    $user->customerno = $customerno;

     /*
        changes Made By : Pratik Raut
        Change :  Added isset check
        Date : 12-09-2019
     */   
    if(isset($data['chkExAlertMapping']))
        $user->chkExUserMapping = $data['chkExAlertMapping'];
    if(isset($data['prevChkExAlertMapping']))    
        $user->prevChkExUserMapping = $data['prevChkExAlertMapping'];
    /* Checkpoint Exception Alerts */
    if(isset($data['chkptExSms']))
        $user->chkptExSms = ret_issetor($data['chkptExSms']);
    if(isset($data['chkptExEmail']))    
        $user->chkptExEmail = ret_issetor($data['chkptExEmail']);
    if(isset($data['chkptExtelephone']))
        $user->chkptExTelephone = ret_issetor($data['chkptExtelephone']);
    if(isset($data['chkptExMobile']))    
        $user->chkptExMobile = ret_issetor($data['chkptExMobile']);
        /* Checkpoint Exception Alerts */

     /* 
        Changes Ends Here 
     */   
    $user->today = date('Y-m-d H:i:s');
    if ( (!empty($user->chkExUserMapping) && !empty($user->prevChkExUserMapping))  &&  $user->chkExUserMapping != $user->prevChkExUserMapping) {
        $usermanager->modifyUserExceptionMapping($user);
    }
    $isModify = 1;

    $isExceptionSet = $usermanager->getUserAlertMapping($user);
    
    if (isset($isExceptionSet) && !empty($isExceptionSet)) {
        $usermanager->setUserAlert($user, $isModify);
    } else {
        $usermanager->setUserAlert($user);
    }
}
?>
