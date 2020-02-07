<?php

set_time_limit(0);
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');

include_once '../../lib/bo/UserManager.php';
include_once '../../lib/bo/VehicleManager.php';

session_start();
$usermanager = new UserManager();
$vehicleManager = new VehicleManager(null);


$get_all_user = $usermanager->get_all_users();

$i=0;


foreach($get_all_user as $users){
    
    //echo "<pre>";print_r($users);
    $_SESSION['customerno'] = $users['customerno'];
    $_SESSION['userid'] = $users['userid'];
    $_SESSION['username'] = $users['username'];
    
    $users_vehicles = $vehicleManager->get_grouped_vehicles_by_userid($users['customerno'], $users['userid'], $users['groupid']);
    
    if(!empty($users_vehicles)){
        
        foreach($users_vehicles as $vehicleid){
            $temp_active = ($users['temp_email']==1 || $users['temp_sms']==1) ? 1 : 0;
            $ig_active = ($users['ignition_email']==1 || $users['ignition_sms']==1) ? 1 : 0;
            $speed_active = ($users['speed_email']==1 || $users['speed_sms']==1) ? 1 : 0;
            $ac_active = ($users['ac_email']==1 || $users['ac_sms']==1) ? 1 : 0;
            $powerc_active = ($users['power_email']==1 || $users['power_sms']==1) ? 1 : 0;
            $tamper_active = ($users['tamper_email']==1 || $users['tamper_sms']==1) ? 1 : 0;
            $harsh_active = ($users['harsh_break_mail']==1 || $users['harsh_break_sms']==1) ? 1 : 0;
            $high_active = ($users['high_acce_mail']==1 || $users['high_acce_sms']==1) ? 1 : 0;
            $towing_active = ($users['towing_mail']==1 || $users['towing_sms']==1) ? 1 : 0;
            
            $start_alert = $users['start_alert'];
            $end_alert = $users['stop_alert'];
            
            $insert_query = "insert into vehiclewise_alert values(null, {$users['customerno']}, {$users['userid']},
            $vehicleid, now(), 0, null, $temp_active, '$start_alert', '$end_alert', $ig_active, '$start_alert', '$end_alert', $speed_active, '$start_alert',
             '$end_alert', $ac_active, '$start_alert', '$end_alert', $powerc_active, '$start_alert', '$end_alert', $tamper_active, '$start_alert', '$end_alert', $harsh_active, '$start_alert', '$end_alert',
             $high_active, '$start_alert', '$end_alert', $towing_active, '$start_alert', '$end_alert')";
            
            $vehicleManager->vehicle_alert_insert($insert_query);
            //die();
            //break;
            $i++;
        }
        //echo $insert_query;die();
    }
}


echo $i.' records inserted';


