<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

define('Mpath', '../');
include_once '../mobility_function.php';
$action = exit_issetor($_REQUEST['action'], failure('Action not found')); //'Action not found'
$customerno = 174; //temp value, actual=174
$hist_limit = 3;

if($action=='mobile'){
    $mobile = exit_issetor($_REQUEST['mobile'], failure('Please send mobile number'));
    
    $mob = new Mobility($customerno,null);
    
    $client = $mob->get_client_by_mobile($mobile);
    
    if($client){
        echo success_json($client);exit;
    }
    else{
        echo failure('Mobile number not found'); exit;   
    }
    
}
/*Login*/
elseif($action=='login')
{
    $mobile = exit_issetor($_REQUEST['phone'], failure('Please send mobile number'));
    $password = exit_issetor($_REQUEST['password'], failure('Please send password'));
    $cpassword = ri($_REQUEST['cpassword'],'');
    $name = ri($_REQUEST['name'], 'name'); //if name is not send, we will insert blank
    $email = ri($_REQUEST['email']);
    
    $mob = new Mobility($customerno, null);
    
    if($cpassword!=''){
        $smscode = mt_rand(1000,9999);
        send_sms_code($mobile,$smscode);
        
        if($mob->is_mobile_exists($mobile)){
            $mob->update_api_client($mobile,$password,$smscode);
        }
        else{
            $mob->add_api_client($email,$mobile,$password,$smscode, $name);
        }
        
        $d = array('Status' => 'OTP', 'data'=>'OTP sent successfully');
                
        echo json_encode($d);exit;
        
    }
    else{
        
        $client = $mob->getdata_fromlogin($mobile,$password);
        
        if($client){
            /*if($client['otp_verified']==0){
                echo failure('OTP not verified'); exit;   
            }*/

            $client_history = $mob->client_history($client['clientid'], null, 'login');
            unset($client['otp_verified']);
            unset($client['clientid']);
            $alldata = array(
                'client' => $client,
                'history' => $client_history
            );
            echo success_json($alldata);exit;
        }
        else{
            echo failure('Mobile/Password mismatch'); exit;   
        }
    }
}
/*
else if($action=='register')
{
    $email = exit_issetor($_REQUEST['email'], failure('Please send email-id')); //'Please send Email-id'
    $phone = exit_issetor($_REQUEST['phone'], failure('Please send phone-number')); //'Please send Phone-number'
    $password = exit_issetor($_REQUEST['password'], failure('Please send password')); //'Please send password'
    $name = retval_issetor($_REQUEST['name'], 'name'); //if name is not send, we will insert blank
    
    $mob = new Mobility($customerno, null);
    
    if($mob->is_email_exists($email)){
        echo failure('Email already exists'); exit;   
    }
    
    $smscode = mt_rand(1000,9999);
    send_sms_code($phone,$smscode);
    $mob->add_api_client($email,$phone,$password,$smscode, $name);
    echo success_json('OTP sent successfully');exit;
}*/
else if($action=='checkotp'){
    $phone = exit_issetor($_REQUEST['phone'], failure('Please send phone-number'));
    $otp = exit_issetor($_REQUEST['otp'], failure('Please send OTP'));
    
    $mob = new Mobility($customerno,null);
    
    $user = $mob->checkotp($phone,$otp);
    
    if($user){
        $mob->set_otp_verfied($user['clientid']);
        
        $mob->update_password($user['clientid']);
        
        $d = array('userkey'=>$user['userkey']);
                
        echo success_json($d);exit;
    }
    else{
        echo failure('OTP mismatch'); exit;   
    }
}
elseif($action=='validate_discount'){
    $userkey = exit_issetor($_REQUEST['userkey'], failure('Please send userkey'));
    $discount_num = exit_issetor($_REQUEST['code'], failure('Please send discount code'));
    
    $mob = new Mobility($customerno,null);
    
    $client = $mob->client_by_key($userkey);
    
    //$discount = $mob->discount_data($discount_num);
    $discount = $mob->chkdiscountid($discount_num, $client);
    
    if($discount){
        if($discount['datestatus']=="error")
        {
            echo failure('Discount code expired.');
        }
        else{
            $data = array(
            'discount_value' => $discount['discvalue'],
            'isamount' => $discount['isamount'],
            );
            echo success_json($data);
        }
    }
    else{
        echo failure('Discount-code not found');
    }
    exit;
    
}
elseif($action=='appointment'){
    $userkey = exit_issetor($_REQUEST['userkey'], failure('Please send userkey'));
    $book_time = ri($_REQUEST['booking_time'], date('Y-m-d H:i:s'));
    $apt_time = exit_issetor($_REQUEST['ap_time'], failure('Please send appointment time.'));
    $addressid = exit_issetor($_REQUEST['address_id'], failure('Please send address id.'));
    $serviceids = exit_issetor($_REQUEST['menu_ids'], failure('Please send menu-id.'));
    $discount_num = ri($_REQUEST['code']);
    
    $svc_date = date('Y-m-d H:i',strtotime($apt_time));
    
    $mob = new Mobility($customerno, null);
    
    $client = $mob->client_by_key($userkey);
    
    if(is_null($client)){echo failure('Client details not found');exit;}
    
    $mob->set_customer_user($client['customerno'], $client['clientid']);
    
    $sq_arr = explode(',',$serviceids);
    $serviceids_arr = $quantity_arr = array();
    foreach($sq_arr as $sq){
        $sq_split = explode('-', $sq);
        $serviceids_arr[] = (int)$sq_split[0];
        $quantity_arr[] = isset($sq_split[1]) ? (int)$sq_split[1] : 1;
    }
     
    if(!$mob->chk_all_services_exists($serviceids_arr)){
        echo failure('Please recheck the menu. Some menu-items not found');exit;
    }
    
    $dsid = 0;
    if($discount_num){
        $discountid = $mob->chkdiscountid($discount_num, $client);
        if($discount['datestatus']=="error")
        {
            echo failure('Discount code expired.');exit;
        }
        elseif($discount['datestatus']!="error"){
            $dsid = $discountid['disccountid'];
        }
        else{
            echo failure('Discount-code not found');exit;
        }
    }
    
    $tkid = auto_allocate_trackie($mob,$apt_time); //need to make this dynamic and intilligent
    
    if(!$tkid){
        echo failure('Trackies are not available on the mentioned time.');exit;
    }
    $data = array(
        'clid' => $client['clientid'],
        'tkid' => $tkid,
        'svid' => $serviceids_arr,
        'quantity' => $quantity_arr,
        'svcdate' => $svc_date,
        'addid' => $addressid,
        'discid' => $dsid
    );
    
    $insert_id = $mob->insert_service_call($data);
    if($insert_id){
        $arr = array('request_no' => $insert_id);
        echo success_json($arr); exit;
    }
    else{
        echo failure('Unable to add call. Please contact Elixir.'); exit;
    }
    
}
else if($action=='addaddress'){
    $userkey = exit_issetor($_REQUEST['userkey'], failure('Please send userkey'));
    $d = array();
    $d['flatno'] = ri($_REQUEST['flatno']);
    $d['building'] = ri($_REQUEST['building']);
    $d['society'] = ri($_REQUEST['society']);
    $d['landmark'] = ri($_REQUEST['landmark']);
    $d['pincode'] = ri($_REQUEST['pincode']);
    $d['loc'] = ri($_REQUEST['loc']);
    $d['city'] = ri($_REQUEST['city']);
    $d['addressid'] = ri($_REQUEST['addressid']);
    
    $mob = new Mobility($customerno, null);
    
    $client = $mob->client_by_key($userkey);
    if(is_null($client)){echo failure('Client details not found');exit;}
    
    $addid = $mob->add_client_address($d,$client['clientid']);
    
    if($addid){
        echo success_json(array('addid'=>$addid));exit;
    }
    else{
        echo failure('Unable to add address.'); exit;   
    }
}
else if($action=='add_location'){
    $city = ri($_REQUEST['city']);
    $loc = ri($_REQUEST['location']);
    
    if($city=='' || $loc==''){
        echo failure('City or location not found');die();
    }
    
    $mob = new Mobility($customerno, null);
        
    $id = $mob->add_temp_cl($city,$loc);
    
    if($id){
        echo success_json(array('id'=>$id));exit;
    }
    else{
        echo failure('Unable to add location.'); exit;   
    }
}
elseif($action=='get_history'){
    $userkey = exit_issetor($_REQUEST['userkey'], failure('Please send userkey'));
    
    $mob = new Mobility($customerno, null);
    
    $client = $mob->client_by_key($userkey);
    
    if(!$client){
        echo failure('Client details not found.');
    }
    else{
        $client_history = $mob->client_history($client['clientid'], null, 'histApi');
        $alldata = array(
            'history' => $client_history
        );
        echo success_json($alldata);
    }
    exit;
}
elseif($action=='cancel_aptmnt'){
    $userkey = exit_issetor($_REQUEST['userkey'], failure('Please send userkey'));
    $request_no = (int)exit_issetor($_REQUEST['request_no'], failure('Please send request-no'));
    
    $mob = new Mobility($customerno, null);
    
    $client = $mob->client_by_key($userkey);
    
    if(!$client){
        echo failure('Client details not found.');
    }
    else{
        $mob->cancel_aptmnt($client['clientid'], $request_no);
        echo success_json('Appointment cancelled successfully');
    }
    exit;
}
else{
    echo failure('Action not listed');exit;
}

function send_sms_code($phone,$smscode){
    $message = "OTP: $smscode";
    $url = "http://pacems.asia:8080/bulksms/bulksms?username=xzt-elixia&password=elixia&type=0&dlr=1&destination=91".urlencode($phone)."&source=ELIXIA&message=".urlencode($message);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $result = curl_exec($ch);
    curl_close($ch);    
}
?>