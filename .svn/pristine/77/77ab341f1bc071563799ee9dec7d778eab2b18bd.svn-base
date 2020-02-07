<?php
    //error_reporting(E_ALL ^ E_STRICT);
    //ini_set('display_errors', 'On');
    include_once 'assign_function.php';
    $vehid = exit_issetor($_REQUEST['vehid']);
    //$slot = (int)retval_issetor($_REQUEST['slotid'], 1);
    $ipdate = retval_issetor($_REQUEST['odate'], date('Y-m-d'));
    $odate = date('Y-m-d', strtotime($ipdate));
    $zoneid = exit_issetor($_REQUEST['zoneid']);
    $slot = exit_issetor($_REQUEST['slotid']);
    
    $slot = explode(",", $slot);
    $zoneid = explode(",", $zoneid);
    
    
    if(empty($zoneid) || $zoneid==0){
        exit(json_encode(array('error'=>'Select Zone for vehicle')));
    }
    
    $startll = get_startll_byvehSlot($vehid, $slot,$odate,$zoneid);
    
    if(!$startll){
        exit(json_encode(array('error'=>'Vehicle not assigned for this Zone/Slot')));
    }
    
    $latlong_arr = latlong_arr($vehid, $slot, $odate,$zoneid);
    
    if(!$latlong_arr){
        exit(json_encode(array('error'=>'No Deliveries found for the Zone/Slot assigned')));
    }else{
        //if($slot==1 || $slot==4){
        if(in_array(1, $slot) || in_array(4,$slot)){
            $lastll = $startll;
        }else{
            $get_last_slot = get_last_slot_ll($vehid, $slot, $odate,$zoneid);
            $lastll = !is_null($get_last_slot) ? $get_last_slot : $startll;
        }
        
        $return = array(
            'finalSeq' => $latlong_arr,
            'start_point' => $lastll
        );
        $json = trim(json_encode($return));
        echo $json; exit;
    }
    ?>