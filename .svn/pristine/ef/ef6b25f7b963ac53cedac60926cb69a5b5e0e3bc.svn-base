<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

if(isset($_POST['vehmap'])){
    
    include_once 'assign_function.php';
    
    $vehmap_data = $_POST['vehmap'];
    $customerno = exit_issetor($_SESSION['customerno']);
    $userid = exit_issetor($_SESSION['userid']);
    $date = date('Y-m-d');
    
    if(empty($vehmap_data)){
        exit('Vehicles not mapped');
    }
    
    $data = array();
    foreach($vehmap_data as $z_slot=>$vehicleid_arr){
        
        $ex = explode('_', $z_slot);
        foreach($vehicleid_arr as $vehicleid){
            $zoneid = filter_var($ex[0], FILTER_SANITIZE_NUMBER_INT);
            $slotid = filter_var($ex[1], FILTER_SANITIZE_NUMBER_INT);
            $vehid = (int)$vehicleid[0];
            if($vehid==0){
                continue;
            }
            $key = "$zoneid-$slotid-$vehid";
            $data[$key] = array(
                'customerno' => $customerno,
                'userid' => $userid,
                'entrytime' => date('Y-m-d h:i:s'),
                'mapdate' => $date,
                'vehicleid' => $vehid,
                'zoneid' => (int)$zoneid,
                'slotid' => (int)$slotid
            );
        }
    }
    
    $mm = new MappingManager($customerno);
    if($mm->mapVehZoneSlot($data)){
        echo "<span style='font-weight:bold;color:green;'>Mapped successfully</span>";exit;
    }
    else{
        echo "<span style='font-weight:bold;color:red;'>Mapping not successfully</span>";exit;
    }

}

if(isset($_POST['orderid'])){
    include_once 'assign_function.php';
    $customerno = exit_issetor($_SESSION['customerno']);
    $userid = exit_issetor($_SESSION['userid']);
    $date = date('Y-m-d');
    $data = array(
      'customerno'=>$customerno,
      'userid' =>$userid,
      'orderid'=>$_POST['orderid'],
      'total_amount' => $_POST['total_amount'],
      'redeem_limit' => $_POST['redeem_limit'],
      'type' => $_POST['payment'],
      'inp_amount' => $_POST['inp_amount'], 
      'inp_chkno' => $_POST['inp_chkno'],
      'inp_accno' => $_POST['inp_accno'],
      'inp_bank' => $_POST['inp_bank'],
      'inp_branch' => $_POST['inp_branch'],
      'inp_reason'=>$_POST['inp_reason'],  
      'paymentby'=>$_POST['paymentby']  
        
    );
    
    $dm = new DeliveryManager($customerno);
    $response = $dm->addPayment($data);
    echo $response;
}

?>