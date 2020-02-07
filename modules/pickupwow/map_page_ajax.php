<?php
    error_reporting(E_ALL ^ E_STRICT);
    ini_set('display_errors', 'On');
    
    include_once 'pickup_functions.php';
    
    $vehid = exit_issetor($_REQUEST['vehid']); 
    $slot = (int)retval_issetor($_REQUEST['slotid'], 1);
    $ipdate = retval_issetor($_REQUEST['odate'], date('Y-m-d'));
    $odate = date('Y-m-d', strtotime($ipdate));
    
    $startll = get_startll_byvehSlot($vehid, $slot);
    
    if(!$startll){
        exit(json_encode(array('error'=>'Vehicle not assigned for this Zone/Slot')));
    }
    
    $latlong_arr = latlong_arr($vehid, $slot, $odate);
    
    if(!$latlong_arr){
        exit(json_encode(array('error'=>'No Deliveries found for the Zone/Slot assigned')));
    }
    else{
        if($slot==1 || $slot==4){
            $lastll = $startll;
        }
        else{
            $get_last_slot = get_last_slot_ll($vehid, $slot, $odate);
            $lastll = !is_null($get_last_slot) ? $get_last_slot : $startll;
        }
        
        $return = array(
            'finalSeq' => $latlong_arr,
            'start_point' => $lastll
        );
        
        echo json_encode($return);exit;
    }
    ?>