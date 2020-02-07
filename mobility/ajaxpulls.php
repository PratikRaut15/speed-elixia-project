<?php
require_once("class/config.inc.php");
if(isset($_SESSION['customerno'])){
require_once("class/class.homePage.php");
require_once("class/class.trackee.php");
require_once("class/class.servicelist.php");
require_once("class/Date.php");
require_once("class/class.servicecall.php");
require_once("class/class.user.php");
require_once("class/class.Notification.php");
require_once("class/class.discount.php");



$serviscelistobj=new servicelist();
$trackobj=new trackee();
$Userobj=new User();
$notifobj=new Notification();
$servicecallobj=new servicecall();
$discountobj=new discount();
extract($_REQUEST);
if($_REQUEST['work']==1 && isset($_POST['vehicleids']))
{
$trackobj->jsonpusher_selected($_POST['vehicleids']); 
    
}
if($_REQUEST['work']==2) {
$trackobj->jsonpusher_all();
} 
if($_REQUEST['work']==3){
$serviscelistobj->getservisedetailsbyid($sid);  
}
if($_REQUEST['work']==4){
    $Userobj->updatedata("sms",$msg,"",$alertname);
}

if($_REQUEST['work']==5){
    $Userobj->updatedata("email",$msg,$header,$alertname);
}
if($_REQUEST['work']==6){
  
   
    $notifobj->anyupdate($timestamp);
	
    
}
if($_REQUEST['work']==7){
  
   
    $servicecallobj->manageservices($manageid);
    
}
if($_REQUEST['work']==8){
  
   
    $notifobj->update_notification();
    
}
if($_REQUEST['work']==9){
    $Userobj->updatedata("sms",$msg,"",$alertname);
}

if($_REQUEST['work']==10){
    $Userobj->updatedata("email",$msg,$header,$alertname);
}
if($_REQUEST['work']==11){
    $servicecallobj->dashboard_body();
}
if($_REQUEST['work']==12){
    $servicecallobj->dashboard_header();
}
if($_REQUEST['work']==13){
    $servicecallobj->dashboard_servicecalls();
}
if($_REQUEST['work']==14){
    $servicecallobj->master_screen_json();
}
if($_REQUEST['work']==15){
	 $servicecallobj->master_screen_popup_data($sid,$tid,$name);
}

if($_REQUEST['work']==16){
	 $notifobj->isupdatedpanic();
}
if($_REQUEST['work']==17){
	 $Userobj->branchupdate($branchid);
	 $Userobj->branch_name($branchid);
}
if($_REQUEST['work']==18){
	 $discountobj->show_json($q);
}
if($_REQUEST['work']==19){
	 $discountobj->show_json_byclient($cid);
}
if($_REQUEST['work']==20){
	 $discountobj->validate_discount($code,$client,$expiry,$action,$sid);
}
if($_REQUEST['work']==21){
//clinet updation 
	 $servicecallobj->update_client_table_for_first_last_visist();
}
if($_REQUEST['work']==22){
 echo  $trackobj->getdevicekey($trackeeid);
}


















































































}
?>
