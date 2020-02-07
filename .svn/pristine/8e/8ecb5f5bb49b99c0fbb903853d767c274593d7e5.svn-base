<?php
/**
 * Ajax page of mobility-module
 */

require_once 'mobility_function.php';

$customerno = exit_issetor($_SESSION['customerno'], 'Please login');
$userid = exit_issetor($_SESSION['userid'], 'Please login');
$action = ri($_REQUEST['action']);

if($action=='editdiscount'){
    $discountid = ri($_REQUEST['discountid']);
    $disccode = ri($_REQUEST['disccode']);
    $expdate = $_REQUEST['expdate'];
    $disctype = ri($_REQUEST['disctype']);
    $disctype_value = ri($_REQUEST['disctype_value']);
    $spec_type = ri($_REQUEST['spectype1']);
    $clid ="";
    $locid ="";
    $grpid ="";
    $cityid="";
    if($spec_type=='1'){
        $clid1 = trim(ri($_REQUEST['ind_client1']));
        $clid = rtrim($clid1,',');
    }elseif ($spec_type==2) {
        $locid = ri($_REQUEST['locid']);
    }elseif($spec_type==3){
        $grpid = ri($_REQUEST['grpid']);
    }elseif($spec_type==4){
        $cityid = ri($_REQUEST['cityid']);
    }
    
    if($disccode==""){
        echo failure('Please enter discount code'); exit;
    }
    else{
         if($clid!=""){
            $clientids = explode(',',$clid);
        }else{
            $clientids=null;
        }
        
        $mob = new Mobility($customerno, $userid);
        $mob->update_discdata($discountid,$disccode,$expdate,$disctype,$disctype_value,$clientids,$locid,$cityid,$grpid);
        echo success('Discount Updated sucessfully'); exit;
    }
}else if($action=='adddiscount'){
    $disccode = ri($_REQUEST['disccode']);
    $expdate = ri($_REQUEST['expdate']);
    $disctype = ri($_REQUEST['disctype']);
    $disctype_value = ri($_REQUEST['disctype_value']);
    $spec_type = ri($_REQUEST['spectype']);
    $clid = "";
    $locid = "";
    $grpid = "";
    $cityid="";
    $today = date('d-m-Y');
    if($spec_type=='1'){
        $clid1 = trim(ri($_REQUEST['ind_client']));
        $clid = rtrim($clid1,',');
        if($clid==""){
           echo failure('Please enter client name'); exit; 
        }
    }elseif($spec_type==2){
        $locid = ri($_REQUEST['locid']);
        if($locid==""){
           echo failure('Please enter Location name'); exit; 
        }
    }elseif($spec_type==3){
        $grpid = ri($_REQUEST['grpid']);
        if($grpid==""){
           echo failure('Please enter group name'); exit; 
        }
    }elseif($spec_type==4){
        $cityid = ri($_REQUEST['cityid']);
        if($cityid==""){
           echo failure('Please enter city'); exit; 
        }
    }
    if(strtotime($expdate) < strtotime($today)){
     echo failure('Please enter expiry date greater than todays date'); exit;
    }
    elseif($disccode==""){
        echo failure('Please enter discount code'); exit;
    }
    else{
        $mob = new Mobility($customerno, $userid);
        if($mob->is_disccode_exists($disccode)){
            echo failure('Discount Code already exists'); exit;   
        }
        if($clid!=""){
            $clientids = explode(',', $clid);
        }else{
            $clientids=null;
        }
        $mob->insert_discdata($disccode,$expdate,$disctype,$disctype_value,$spec_type,$clientids,$locid,$cityid,$grpid);
        echo success('Discount added sucessfully'); exit;
    }
}else if($action=='citymaster'){
    $cityname = ri($_REQUEST['cityname']);
    if($cityname==""){
        echo failure('Please enter city name'); exit;
    }
    else{
        $mob = new Mobility($customerno, $userid);
        if($mob->is_city_exists($cityname)){
            echo failure('City already exists'); exit;   
        }
        $mob->insert_citydata($cityname);
        echo success('City added sucessfully'); exit;
    }
}else if($action=='addcat'){
    $catname = ri($_REQUEST['catname']);
    if($catname==""){
        echo failure('Please enter category'); exit;
    }
    else{
        $mob = new Mobility($customerno, $userid);
        if($mob->is_category_exists($catname)){
            echo failure('Category already exists'); exit;   
        }
        $mob->insert_catdata($catname);
        echo success('Category added sucessfully'); exit;
    }
}else if($action=='editcat'){
    $catname = ri($_REQUEST['catname']);
    $catid = ri($_REQUEST['catid']);
    if($catname==""){
        echo failure('Please enter category'); exit;
    }
    else{
        $mob = new Mobility($customerno, $userid);
        
        $mob->update_catdata($catid,$catname);
        echo success('Category Updated sucessfully'); exit;
    }
}else if($action=='addFeedback'){
    
    $question = ri($_REQUEST['question']);
    $option =  $_REQUEST['option'];
    
    if($question==""){
        echo failure('Please enter Question'); exit;
    }
    if(empty($option)){
        echo failure('Please enter options'); exit;
    }
    else{
        $mob = new Mobility($customerno, $userid);
        if($mob->is_question_exists($question)){
            echo failure('Question already exists'); exit;   
        }
        $mob->insert_feedbackdata($question,$option);
        echo success('Question added sucessfully'); exit;
    }
}else if($action=='editFeedback'){
    $question = ri($_REQUEST['question']);
    $option =  $_REQUEST['option'];
    $fid = $_REQUEST['fid'];
    $optid = $_REQUEST['optid'];
    if($question==""){
        echo failure('Please enter Question'); exit;
    }
    if(empty($option)){
        echo failure('Please enter options'); exit;
    }
    else{
        $mob = new Mobility($customerno, $userid);
        $mob->update_feedbackdata($question,$option,$fid,$optid);
        echo success('Question Updated sucessfully'); exit;
    }
}
else if($action=='delcity'){  //delete city
    $id = (int)ri($_REQUEST['id']);
    $mob = new Mobility($customerno, $userid);
    $mob->delete_citydata($id);
    echo success('City deleted sucessfully'); exit;
}
else if($action=='delloc'){   //delete location
    $id = (int)ri($_REQUEST['id']);
    $mob = new Mobility($customerno, $userid);
    $mob->delete_locdata($id);
    echo success('Location deleted sucessfully'); exit;
}
else if($action=='delsc'){   //delete service call
    $id= (int)ri($_REQUEST['id']);
    $mob = new Mobility($customerno, $userid);
    $mob->delete_servicecaldata($id);
    echo success('servicecall deleted sucessfully'); exit;
}
else if($action=='delclient'){  //delete client
    $id = (int)ri($_REQUEST['id']);
    $mob = new Mobility($customerno, $userid);
    $mob->delete_clientdata($id);
    echo success('Client deleted sucessfully'); exit;
    
}
else if($action=='deltrack'){  //delete trackie
    $id = (int)ri($_REQUEST['id']);
    $mob = new Mobility($customerno, $userid);
    $mob->delete_trackdata($id);
    echo success('Therapist deleted sucessfully'); exit;
}
else if($action=='delcall'){  //delete call
    $id = (int)ri($_REQUEST['id']);
    $mob = new Mobility($customerno, $userid);
    $mob->delete_servicecalldata($id);
    echo success('Servicecall deleted sucessfully'); exit;
}
else if($action=='deldiscount'){  //delete Discount
    $id = (int)ri($_REQUEST['id']);
    $mob = new Mobility($customerno, $userid);
    $mob->delete_discountdata($id);
    echo success('Discount deleted sucessfully'); exit;
}
else if($action=='delfeedback'){  //delete Feedback
    $id = (int)ri($_REQUEST['id']);
    $mob = new Mobility($customerno, $userid);
    $mob->delete_feedbackdata($id);
    echo success('Feedback deleted sucessfully'); exit;
}
else if($action=='delcat'){  //delete Category
    $id = (int)ri($_REQUEST['id']);
    $mob = new Mobility($customerno, $userid);
    $mob->delete_categorydata($id);
    echo success('Category deleted sucessfully'); exit;
}
else if($action=='delpckg'){  //delete package
    $id = (int)ri($_REQUEST['id']);
    $mob = new Mobility($customerno, $userid);
    $mob->delete_packagedata($id);
    echo success('Package deleted sucessfully'); exit;
}
else if($action=='editcitymaster'){
    $cityname = ri($_REQUEST['cityname']);
    $cid = ri($_REQUEST['cid']);
    if($cityname==""){
        echo failure('Please enter city name'); exit;
    }
    else{
        $mob = new Mobility($customerno, $userid);
//        if($mob->is_city_exists($cityname)){
//            echo failure('City already exists'); exit;   
//        }
        $mob->update_citydata($cityname,$cid);
        echo success('City updated sucessfully'); exit;
    }
}
else if($action=='locationmaster'){
    $cityid = (int)ri($_REQUEST['cityid']);
    $lname = ri($_REQUEST['locationname']);
    if($cityid==0 || $lname==''){
        echo failure('Please fill mandatory fields.'); exit;
    }
    else{
        $mob = new Mobility($customerno, $userid);
        if(!$mob->is_cityid_exists($cityid)){
            echo failure('City not found'); exit;   
        }
        if($mob->is_location_exists($lname,$cityid)){
            echo failure('Location already exists.'); exit;   
        }
        $mob->insert_locationdata($cityid,$lname);
        echo success('Location added sucessfully'); exit;
    }
}
else if($action=='editlocationmaster'){
    $locid = (int)ri($_REQUEST['locid']);
    $cityid = (int)ri($_REQUEST['cityid']);
    $lname = ri($_REQUEST['locationname']);
    if($cityid==0 || $lname==''){
        echo failure('Please fill mandatory fields.'); exit;
    }
    else{
        $mob = new Mobility($customerno, $userid);
//        if(!$mob->is_cityid_exists($cityid)){
//            echo failure('City not found'); exit;   
//        }
//        if($mob->is_location_exists($lname,$cityid)){
//            echo failure('Location already exists.'); exit;   
//        }
        $mob->update_locationdata($cityid,$lname,$locid);
        echo success('Location updated sucessfully'); exit;
    }
}
else if($action=="addtrackie"){
    $trackiename =  ri($_REQUEST['trackiename']);
    $phone =  ri($_REQUEST['phone']);
    $email =  ri($_REQUEST['emailid']);
    $address =  ri($_REQUEST['address']);
    $weeklyoff1 =  $_REQUEST['weeklyoff'];
    $locid =  $_REQUEST['locid'];
    $uncheckids1 = $_REQUEST['uncheckids']; 
    $uncheckids2 = explode(",", $uncheckids1);
    $uncheckids = array_unique($uncheckids2);
    //print_r($uncheckids);//exit; 
    $weeklyoff = implode (",", $weeklyoff1);
    if($phone=="" || $trackiename=="" ||$email==""){
        echo failure('Please fill the mandatory fields.'); exit;
    }
    else{
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo failure("Invalid email Id"); exit;
        }
        $mob = new Mobility($customerno, $userid);
        if($mob->is_trackie_email_exists($email)){
            echo failure('Email already exists'); exit;   
        }
        $mob->insert_trackiedata($trackiename,$phone,$email,$address,$weeklyoff,$locid,$uncheckids);
        echo success($rename.' Added sucessfully'); exit;
    }   
}
else if($action=="edittrackie"){
    $trackid =  ri($_REQUEST['trackid']);
    $trackiename =  ri($_REQUEST['trackiename']);
    $phone =  ri($_REQUEST['phone']);
    $email =  ri($_REQUEST['emailid']);
    $address =  ri($_REQUEST['address']);
    $weeklyoff1 =  $_REQUEST['weeklyoff'];
    $uncheckids1 = $_REQUEST['uncheckids']; 
    $uncheckids2 = explode(",", $uncheckids1);
    $uncheckids = array_unique($uncheckids2);
    
    if($phone=="" || $trackiename=="" ||$email==""){
        echo failure('Please fill the mandatory fields.'); exit;
    }
    else{
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo failure("Invalid email Id"); exit;
        }
        $mob = new Mobility($customerno, $userid);
       
        $mob->update_trackiedata($trackid,$trackiename,$phone,$email,$address,$weeklyoff1,$uncheckids);
        echo success($rename.' Updated sucessfully'); exit;
    } 
}
elseif($action=='addService'){
    $sname = ri($_REQUEST['servicename']);
    $scost = ri($_REQUEST['cost']);
    $sexpctdTime = ri($_REQUEST['expTime']);
    $catid = ri($_REQUEST['servicecat']);
    $addnewcat = ri($_REQUEST['addnewcat']);
    $newcat = ri($_REQUEST['newcat']);
    if($addnewcat==1 && empty($newcat)){
        echo failure('Please enter new categoryname.'); exit;
    }
    
    if($sname==""||$scost==''||$sexpctdTime==''||$catid==""){
        echo failure('Please enter the mandatory fields.'); exit;
    }
    else{
        $mob = new Mobility($customerno, $userid);
        if($mob->is_service_exists($sname)){
            echo failure('Service name already exists'); exit;   
        }
        
        if($addnewcat==1 && !empty($newcat)){
            $mob = new Mobility($customerno,$userid);
            if($mob->is_category_exists($newcat)){
                echo failure('Category already exists.'); exit;   
            }
        }
        $sdata = array(
            'service_name'=>$sname,
            'cost'=> $scost,
            'expTime'=>(int)$sexpctdTime,
            'cid'=>$catid,
            'addnewcat'=>$addnewcat,
            'newcat'=>$newcat
        );
        $mob->add_service($sdata);
        echo success('Service added sucessfully'); exit;
    }
}
elseif($action=='editService'){
    $serviceid = ri($_REQUEST['serviceid']);
    $sname = ri($_REQUEST['servicename']);
    $scost = ri($_REQUEST['cost']);
    $sexpctdTime = ri($_REQUEST['expTime']);
    $catid =  ri($_REQUEST['servicecat']);
    $addnewcat = ri($_REQUEST['addnewcat']);
    $newcat = ri($_REQUEST['newcat']);
    if($addnewcat==1 && empty($newcat)){
        echo failure('Please enter new categoryname.'); exit;
    }
    if($sname==""||$scost==''||$sexpctdTime==''||$catid==""){
        echo failure('Please enter the mandatory fields.'); exit;
    }
    else{
        if($addnewcat==1 && !empty($newcat)){
            $mob = new Mobility($customerno,$userid);
            if($mob->is_category_exists($newcat)){
                echo failure('Category already exists.'); exit;   
            }
        }
        
        
        $mob = new Mobility($customerno, $userid);
        $sdata = array(
            'serviceid'=>$serviceid,
            'service_name'=>$sname,
            'cost'=> $scost,
            'expTime'=>(int)$sexpctdTime,
            'catid'=>$catid,
            'addnewcat'=>$addnewcat,
            'newcat'=>$newcat
        );
        $mob->update_service($sdata);
        echo success('Service updated sucessfully'); exit;
    }
}
elseif($action=='addclientpop'){
    $clientno = ri($_REQUEST['clno']);
    $cname = ri($_REQUEST['cname']);
    $mobile = ri($_REQUEST['mob']);
    $password = ri($_REQUEST['pass']);
    $cpassword1 = sha1($password);
     
    $mob = new Mobility($customerno, $userid);
    if($mob->is_clientno_exists($clientno)){
        $result = array('Status' => 'Failure','Error' =>'Clientno aleready exists'); echo json_encode($result); exit;
    }
    $userkey2 = mt_rand();
     $d = array(
        'clientno' => array('String',$clientno),
        'client_name' => array('String', $cname),
        'password' => array('String', $cpassword1),
        'userkey' => array('String', $userkey2),
        'mobile' => array('String', $mobile)
    );
    $address = array(
        'alternateaddress'=> NULL,
        'flatno' => NULL,
        'building' => NULL,
        'society' => NULL,
        'landmark' => NULL,
        'cityid' => NULL,
        'locationid' => NULL,
        'flatno1' => NULL,
        'building1' => NULL,
        'society1' => NULL,
        'landmark1' => NULL,
        'cityid1' => NULL,
        'locationid1' => NULL,
    ); 
    
    $mob->add_client($d,$address);
    $result = array('Status' => 'Sucess','Error' =>'Client Added Sucessfully'); 
    echo json_encode($result); exit;
    
}
elseif($action=='addClient'){
    $alternateaddress = ri($_REQUEST['alternateaddress2']);
    $clientno = ri($_REQUEST['clientno']);
    $cname = ri($_REQUEST['cname']);
    $notes = ri($_REQUEST['notes']);
    $cpassword = ri($_REQUEST['cpassword']);
    $cemail = $_REQUEST['cemail'];
    $dob = $_REQUEST['cdob'];
    $cannivrsry = $_REQUEST['cannivrsry'];
    $chkdate = date('d-m-Y');
    $memberstatus = ri($_REQUEST['membership']);
    $mobile = ri($_REQUEST['cmobile']);
    if($memberstatus==1){
        $membershipid = ri($_REQUEST['membershipcd']);
        if($membershipid==0){
           echo failure('Please select package code.'); exit;
        }
    }
    
    if($dob > $chkdate){
        echo failure('Please select DOB before todays date.'); exit;
    }
    if($cannivrsry > $chkdate){
        echo failure('Please select Anniversary before todays date.'); exit;
    }
    //if($clientno=='' || $cname==''|| $cpassword==""||$cemail==""){
    if($clientno=='' ||$mobile==""|| $cpassword==""){
        echo failure('Please fill the mandatory fields.'); exit;
    }
    $cpassword1 = sha1($cpassword);
    $userkey2 = mt_rand();
   
    
    $d = array(
        'clientno' => array('String',$clientno),
        'client_name' => array('String', $cname),
        'password' => array('String', $cpassword1),
        'userkey' => array('String', $userkey2),
        'dob' => array('Date', ri($_REQUEST['cdob'])),
        'groupid' => array('string',ri($_REQUEST['groupid'])),
        'anniversary' => array('Date', ri($_REQUEST['cannivrsry'])),
        'mobile' => array('String', $mobile),
        'phone' => array('String', ri($_REQUEST['cphoneno'])),
        'email' => array('String',$cemail),
        'min_billing' => array('String', ri($_REQUEST['cminimumbill'])),
        'is_member' => array('String', ri($memberstatus)),
        'pckgid'=> array('String', ri($membershipid)),
        'notes' => array('string', ri($notes))
    );
    
     $address = array(
        'alternateaddress'=> ri($alternateaddress),
        'flatno' => ri($_REQUEST['cflatno']),
        'building' => ri($_REQUEST['cbuilding']),
        'society' => ri($_REQUEST['csociety']),
        'landmark' => ri($_REQUEST['clandmark']),
        'cityid' => ri($_REQUEST['ccity']),
        'locationid' => ri($_REQUEST['clocation']),
        
        'flatno1' => ri($_REQUEST['cflatno1']),
        'building1' => ri($_REQUEST['cbuilding1']),
        'society1' => ri($_REQUEST['csociety1']),
        'landmark1' => ri($_REQUEST['clandmark1']),
        'cityid1' => ri($_REQUEST['ccity1']),
        'locationid1' => ri($_REQUEST['clocation1'])
    );
    
    $mob = new Mobility($customerno, $userid);
    if($mob->is_clientno_exists($clientno)){
        echo failure('Client no already exists'); exit;   
    }
    
    $mob->add_client($d,$address);
    echo success('Client added sucessfully'); exit;
}
else if($action=='editClient'){
    $alternateaddress = ri($_REQUEST['alternateaddress3']);
    $clientid = ri($_REQUEST['clientid']);
    $clientno = ri($_REQUEST['clientno']);
    $changepass = ri($_REQUEST['changepass']);
    $dob = $_REQUEST['cdob'];
    $notes = $_REQUEST['notes'];
    $cannivrsry = $_REQUEST['cannivrsry'];
    $mobile = ri($_REQUEST['cmobile']);
    $chkdate = date('d-m-Y');
    
    $memberstatus = ri($_REQUEST['membership']);
    if($memberstatus==1){
        $membershipid = ri($_REQUEST['membershipcd']);
//        $membershipamount = ri($_REQUEST['membershipamount']);
//        $membervalidity1 = ri($_REQUEST['membervalidity']);
//        $membervalidity = date('Y-m-d', strtotime($membervalidity1));
        if($membershipid==0){
            echo failure('Please select package code.'); exit;
        }
    }
    
    if($alternateaddress==1){
       $flatno1 = ri($_REQUEST['cflatno1']);
        $building1 = ri($_REQUEST['cbuilding1']);
        $society1 = ri($_REQUEST['csociety1']);
        $landmark1 = ri($_REQUEST['clandmark1']);
        $cityid1 = ri($_REQUEST['ccity1']);
        $locationid1 = ri($_REQUEST['clocation1']);
        
        if(empty($flatno1) && empty($building1) && empty($society1) && empty($landmark1)){
            echo failure('Please enter alternate address information.'); exit;    
        }
    }
    
    if($dob > $chkdate){
        echo failure('Please select DOB before todays date.'); exit;
    }
    if($cannivrsry > $chkdate){
        echo failure('Please select Anniversary before todays date.'); exit;
    }
    $cpassword1="";
    if($changepass=='1'){
        $cpassword = ri($_REQUEST['cpassword']);
        if($cpassword==""){
            echo failure('Please enter password.'); exit;
        }else{
            $cpassword1 = ri($_REQUEST['cpassword']);
        }
    }
    $cname = ri($_REQUEST['cname']);
    if($clientno=='' || $mobile==''){
        echo failure('Please fill the mandatory fields.'); exit;
    }
    $d = array(
        'password' =>$cpassword1,
        'clientid' =>$clientid,
        'clientno' =>$clientno,
        'client_name' =>$cname,
        'dob' => ri($_REQUEST['cdob']),
        'anniversary' =>ri($_REQUEST['cannivrsry']),
        'mobile' =>$mobile,
        'phone' => ri($_REQUEST['cphoneno']),
        'email' =>ri($_REQUEST['cemail']),
        'groupid' => ri($_REQUEST['groupid']),
        'min_billing' => ri($_REQUEST['cminimumbill']),
        'is_member' =>  ri($memberstatus),
        'notes' => ri($notes),
        'pckgid'=> ri($membershipid)
    );
    
     $address = array(
        'alternateaddress'=> ri($alternateaddress),
        'addressid'=>ri($_REQUEST['addressid']),
        'flatno' => ri($_REQUEST['cflatno']),
        'building' => ri($_REQUEST['cbuilding']),
        'society' => ri($_REQUEST['csociety']),
        'landmark' => ri($_REQUEST['clandmark']),
        'cityid' => ri($_REQUEST['ccity']),
        'locationid' => ri($_REQUEST['clocation']),
        'addressid1'=>ri($_REQUEST['addressid1']), 
        'flatno1' => ri($_REQUEST['cflatno1']),
        'building1' => ri($_REQUEST['cbuilding1']),
        'society1' => ri($_REQUEST['csociety1']),
        'landmark1' => ri($_REQUEST['clandmark1']),
        'cityid1' => ri($_REQUEST['ccity1']),
        'locationid1' => ri($_REQUEST['clocation1'])
    );
    $mob = new Mobility($customerno, $userid);
    $mob->update_client($d,$address);
    echo success('Client Updated sucessfully'); exit;
}
else if($action=='catauto'){
    $q = ri($_REQUEST['term']);
    $mob = new Mobility($customerno, $userid);
    $result = $mob->getcatdata($q);
    echo json_encode($result);exit;
}
else if($action=='cityauto'){
    $q = ri($_REQUEST['term']);
    $mob = new Mobility($customerno, $userid);
    $result = $mob->getcitydata($q);
    echo json_encode($result);exit;
}
else if($action=='locationauto'){
    $q = ri($_REQUEST['term']);
    $mob = new Mobility($customerno, $userid);
    $result = $mob->getlocationdata($q);
    echo json_encode($result);exit;
}
else if($action=='groupauto'){
    $q = ri($_REQUEST['term']);
    $mob = new Mobility($customerno, $userid);
    $result = $mob->getgroupdata($q);
    echo json_encode($result);exit;
}
else if($action=='clientauto'){
    $q = ri($_REQUEST['term']);
    if(preg_match('/,/', $q)){
        $test1 =  explode(',', $q);
        $q = trim(end($test1));
    }
    $mob = new Mobility($customerno, $userid);
    $result = $mob->getclients_auto($q);
    echo json_encode($result);exit;
}
else if($action=='cityauto'){
    $q = ri($_REQUEST['term']);
    $mob = new Mobility($customerno, $userid);
    $result = $mob->getcity_auto($q);
    echo json_encode($result);exit;
}
else if($action=='clientdetails'){
    $cid = (int)ri($_REQUEST['clientid']);
    if($cid==0){echo failure('Client not found');}
    
    $mob = new Mobility($customerno, $userid);
    $client_details = $mob->getclients_details($cid);
    $client_history = $mob->client_history($cid,2);
    
    if($client_details){
        $data = array();
        $data[] = $client_details;
        if($client_history){
            $data[] = $client_history;
        }
        echo json_encode($data);exit;
    }
    else{
        echo failure('Client data not found');exit;
    }
}
else if($action=='serviceauto'){
    $q = ri($_REQUEST['term']);
    $mob = new Mobility($customerno, $userid);
    $data = $mob->getservice_auto($q);
    echo json_encode($data);exit;
}
else if($action=='trackieauto'){
    $q = ri($_REQUEST['term']);
    $mob = new Mobility($customerno, $userid);
    $data = $mob->gettrackie_auto($q);
    echo json_encode($data);exit;
}
elseif($action=='addCall'){
    $call = exit_issetor($_REQUEST['call']);
    $call2 = json_decode($call);
    $clid = (int) ri($call2->clid);
    $sdte = ri($call2->sdte);
    $stme = ri($call2->stme);
    $tkid = (int)ri($call2->tkid);
    $statid = (int)ri($call2->statid);
    $sids = $call2->sdata;
    $addid = (int)$call2->addid;
    $disc_code = ri($call2->discountcode);
    if($clid==0){echo failure('Client details not found');exit;}
    if($sdte=='' || $stme==''){echo failure('Please fill expected start-date.');exit;}
    if($tkid==0){echo failure('Please enter Stylist details.');exit;}
    if(empty($sids)){echo failure('Service not added');exit;}
    
    $mob = new Mobility($customerno, $userid);
    $totaltime = $mob->chk_all_services_exists($sids);
    if(!$totaltime){
        echo failure('Please recheck the services');exit;
    }
    
    $clientdata = $mob->chk_getclients_details($clid);
    if(!$clientdata){
        echo failure('Client not found');exit;
    }
    
    $discid = 0;
    if($disc_code){
        
        $d = $mob->chkdiscountid($disc_code,$clientdata);
        if($d['datestatus']=="error")
        {
            echo failure('Discount code expired.');exit;
        }
        elseif($d){
            $discid = $d['disccountid'];
        }
        else{
            echo failure('Discount code not found');exit;
        }
        
    }
    $sdate = date('Y-m-d', strtotime($sdte));
    $stime = substr($stme, 0, 5);
    
    $trackie_holiday = $mob->chk_trackie_holiday($tkid,$sdte,$weeklyoff_arr);
    if($trackie_holiday==1){
        echo failure('Stylist today holiday');exit;
    }
    
    
    $exp_services = $mob->chk_trackie_exeption_services($sids,$tkid);   //check exceptional services
    if(!empty($exp_services)){
        $expservices = implode(",", $exp_services); 
        echo failure('Stylist cannot do these services : '.$expservices);exit;
    }
    
    if(!$mob->chk_trackie_availability($tkid,$sdate,$stme)){
        echo failure('Stylist is not available for mentioned timeline');exit;
    }
    
    $data = array(
        'clid' => $clid,
        'tkid' => $tkid,
        'svid' => $sids,
        'statusid' =>$statid,
        'quantity'=>array_count_values($sids),
        'svcdate' => "$sdate $stime",
        'addid' => $addid,
        'discid' => $discid
    );
    
    $status = $mob->insert_service_call($data);
    if($status){
        echo success('Call added successfully'); exit;
    }else{
        echo failure('Unable to add call. Please contact Elixir.'); exit;
    }
    
}
elseif($action=='editCall'){
    $call = exit_issetor($_REQUEST['call']);
    $call2 = json_decode($call);
    
    $callid = (int) ri($call2->callid);
    $clid = (int) ri($call2->clid);
    $sdte = ri($call2->sdte);
    $stme = ri($call2->stme);
    $tkid = (int)ri($call2->tkid);
    $statid = (int)ri($call2->statid);
    $sids = $call2->sdata;
    $addid = (int)$call2->addid;
    $disc_code = ri($call2->discountcode);
    
    if($callid==0){echo failure('Call id not found');exit;}
    if($clid==0){echo failure('Client details not found');exit;}
    if($sdte=='' || $stme==''){echo failure('Please fill expected start-date.');exit;}
    if($tkid==0){echo failure('Please enter Stylist details.');exit;}
    if(empty($sids)){echo failure('Service not added');exit;}
    
    $mob = new Mobility($customerno, $userid);
    $totaltime = $mob->chk_all_services_exists($sids);
    if(!$totaltime){
        echo failure('Please recheck the services');exit;
    }
    
    $clientdata = $mob->chk_getclients_details($clid);
    if(!$clientdata){
        echo failure('Client not found');exit;
    }
    
    $discid = 0;
    if($disc_code){
        
        $d = $mob->chkdiscountid($disc_code,$clientdata);
        if($d['datestatus']=="error")
        {
            echo failure('Discount code expired.');exit;
        }
        elseif($d){
            $discid = $d['disccountid'];
        }
        else{
            echo failure('Discount code not found');exit;
        }
        
    }
    $sdate = date('Y-m-d', strtotime($sdte));
    $stime = substr($stme, 0, 5);
    
    $trackie_holiday = $mob->chk_trackie_holiday($tkid,$sdte,$weeklyoff_arr);
    if($trackie_holiday==1){
        echo failure('Stylist today holiday');exit;
    }
    
    $exp_services = $mob->chk_trackie_exeption_services($sids,$tkid);   //check exceptional services
    if(!empty($exp_services)){
        $expservices = implode(",", $exp_services); 
        echo failure('Stylist cannot do these services : '.$expservices);exit;
    }
    
    if(!$mob->chk_trackie_availability($tkid,$sdate,$stme,$callid)){
        echo failure('Stylist is not available for mentioned timeline');exit;
    }
    
    $data = array(
        'clid' => $clid,
        'tkid' => $tkid,
        'svid' => $sids,
        'statusid' =>$statid,
        'quantity'=>array_count_values($sids),
        'svcdate' => "$sdate $stime",
        'addid' => $addid,
        'discid' => $discid
    );
    
    $status = $mob->update_service_call($callid,$data);
    
    if($status){
        echo success('Call edited successfully'); exit;
    }else{
        echo failure('Unable to edit call. Please contact Elixir.'); exit;
    }
    
}
else if($action=='getTimeline'){
    $inpdate = ri($_REQUEST['of']);
    $cdate = ($inpdate!='') ? date('Y-m-d', strtotime($inpdate)): date('Y-m-d');
    require_once 'pages/timeline/timeline_body.php';
    exit;
    
}
else if($action=='getlocations'){
    $cityid = ri($_REQUEST['cid']);
    $mob = new Mobility($customerno, $userid);
    $locationdata = $mob->get_locations_bycityid($cityid);
    if($locationdata){
        $option  = "";
        foreach ($locationdata as $ldata) {
           $option .= "<option value='".$ldata['locid']."'>".$ldata['location']."</option>";
        }
        echo $option;     
    }else{
        echo $option = "<option value='0'>No Location</option>";
    }
    exit;
}
else if($action=='getpck'){
    $cityid = ri($_REQUEST['pid']);
    $mob = new Mobility($customerno, $userid);
    $pckdata = $mob->get_package_bypid($cityid);
    echo json_encode($pckdata);exit;
}

else if($action=='chkdiscount'){
    $clientid = (int)ri($_REQUEST['clientid']);
    if($clientid==0){echo failure('Client-id not found.'); exit;}
    $discountcode = ri($_REQUEST['discountcode']);
    $mob = new Mobility($customerno, $userid);
    $clientdata = $mob->chk_getclients_details($clientid);
    $dt = $mob->chkdiscountid($discountcode,$clientdata);
    
    if($dt['datestatus']=="error")
    {
        echo failure('Discount code expired.');
    }
    elseif($dt){
        echo success_json($dt);
    }
    else{
        echo failure('Discount code not found.');
    }
    exit;
}
else if($action=='changeStat'){
    $stat = (int)ri($_REQUEST['stat']);
    $scid = (int)ri($_REQUEST['scid']);
    if($scid==0){echo failure('Client not found');exit;}
    
    $mob = new Mobility($customerno,null);
    
    $status = $mob->update_service_status($scid,$stat);
    
    if($status){
        echo success('Status updated sucessfully'); exit;
    }
    else{
        echo failure('Unable to update status.');exit;
    }
}
else if($action=='deleteSC'){
    $scid = (int)ri($_REQUEST['scid']);
    
    $mob = new Mobility($customerno,$userid);
    
    $status = $mob->delete_service($scid);
    
    if($status){
        echo success('Deleted sucessfully'); exit;
    }
    else{
        echo failure('Unable to delete.');exit;
    }
}
elseif($action=='getsc_bycat'){
    $catid = (int)ri($_REQUEST['catid']);
    
    $mob = new Mobility($customerno,$userid);
    $data = $mob->getservice_bycat($catid);
    
    if($data){
        echo success_json($data);
    }
    else{
        echo failure('No service found in this category.');
    }
    
    exit;
}
else if($action=='addpackage'){
    $membershipcode = ri($_REQUEST['membershipcode']);
    $amount = ri($_REQUEST['amount']);
    $membervalidity = ri($_REQUEST['membervalidity']);
    if($membershipcode==""|| $amount==""||$membervalidity==""){
        echo failure('Please enter mandatory fields'); exit;
    }else{
        $mob = new Mobility($customerno, $userid);
        if($mob->is_membershipcode_exists($membershipcode)){
            echo failure('Package code already exists.'); exit;   
        }
        $mob->insert_membercodedata($membershipcode,$amount,$membervalidity);
        echo success('Package added sucessfully'); exit;
    }
}else if($action=='editpackage'){
    $pckgid = ri($_REQUEST['pckgid']);
    $membershipcode = ri($_REQUEST['membershipcode']);
    $amount = ri($_REQUEST['amount']);
    $membervalidity = ri($_REQUEST['membervalidity']);
    if($membershipcode==""|| $amount==""||$membervalidity==""){
        echo failure('Please enter mandatory fields'); exit;
    }else{
        $mob = new Mobility($customerno, $userid);
        $mob->update_membercodedata($pckgid,$membershipcode,$amount,$membervalidity);
        echo success('Package updated sucessfully'); exit;
    }
}
elseif($action=='getCallDetails'){
    $callid = (int)ri($_REQUEST['callid']);
    
    $mob = new Mobility($customerno,$userid);
    
    $cdata = $mob->get_service_details($callid);
    if($cdata){
        $client_details = $mob->getclients_details($cdata['clientid']);
        
        if($client_details){
            $data = array();
            $data[0] = $client_details;
            $data[0]['scid'] = $cdata['scid'];
            $data['cur_address'] = $cdata['cur_address'];
            $client_history = $mob->client_history($cdata['clientid'],2);
            if($client_history){
                $data[1] = $client_history;
            }
            $data[2] = $cdata; //current service data
            
            echo success_json($data);exit;
            
        }
        else{
            echo failure('Client-data not found');
        }
    }
    else{
        echo failure('Service call not found');
    }
    exit;
}

else{
    echo failure('No action found'); exit;
}

?>
