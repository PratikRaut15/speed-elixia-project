<?php

include_once 'trip_functions.php';

if(!isset($_SESSION['customerno'])){
    echo('Session expired. Please login');exit;
}
    
/*To add new trip-alert*/
if(isset($_POST['addAlert'])){
    $today = date('d-m-Y');
    $customerno = $_SESSION['customerno'];
    $vehicleid = isset($_POST['vehicleid']) ? $_POST['vehicleid'] : '';
    $s_date = isset($_POST['SDate']) ? $_POST['SDate'] : date('d-m-Y');
    $s_time = isset($_POST['STime']) ? $_POST['STime'].':00' : "00:00:00";
    $cp_start = isset($_POST['checkpoint_start']) ? (int)$_POST['checkpoint_start'] : 0;
    $cp_end = isset($_POST['checkpoint_end']) ? (int)$_POST['checkpoint_end'] : 0;
    
    /*input validation*/
    if(validate_trip_inputs($vehicleid, $cp_start, $cp_end,$s_date, $s_time)){
        $data = array();
        $data['vehicleid'] = (int)$vehicleid;
        $data['start_date'] = date('Y-m-d', strtotime($s_date));
        $data['start_time'] = date('H:i', strtotime($s_time));
        $data['cp_start'] = $cp_start;
        $data['cp_end'] = $cp_end;
        $data['driving_dist'] = isset($_POST['driving_dist']) ? $_POST['driving_dist'] : 0;
        $data['driving_dist'] = round($data['driving_dist']/1000,2);
        
        insert_trip_alert($data);
        display_success('Trip added successfully');
    }
    echo display_trip_alerts();
}

/*To view trip-alert details*/
elseif(isset($_POST['todo']) && $_POST['todo']=='getGensetReport'){
    if(!isset($_POST['tripid'])){
        echo('Tripid not found');exit;
    }
    $tripid = (int)$_POST['tripid'];
    $trip_data = get_trip_genset($tripid);
    
    trip_details_table($trip_data);
}

/*To delete trip-alert details*/
elseif(isset($_POST['todo']) && $_POST['todo']=='deleteTrip'){
    if(!isset($_POST['tripid'])){
        echo('Tripid not found');exit;
    }
    $tripid = (int)$_POST['tripid'];
    
    $cm = new CheckpointManager($_SESSION['customerno']);
    $cm->delete_trip_alert($tripid);
    
    display_success('Trip deleted successfully');
    echo display_trip_alerts();
}

/*To get lat-long of checkpoints*/
elseif(isset($_POST['todo']) && $_POST['todo']=='getLatLong'){
    $start = (int)$_POST['start'];
    $end = (int)$_POST['end'];
    
    if($start==0 || $end==0){
        exit('Checkpoint not found');
    }
    $cm = new CheckpointManager($_SESSION['customerno']);
    $data = $cm->get_checkpoint_lat_long($start, $end);
    
    echo json_encode($data);exit;
}

?>

