<?php 
require_once 'expense_function.php';
$action = $_REQUEST["action"];

if(isset($action)){
    if($action=="addcategory"){
        $customerno = $_REQUEST['customerno'];
        $category = $_REQUEST['catname'];
        $userid = $_REQUEST['userid'];
        $data = array(
         "category"=> $category,
         "userid" => $userid,
         "customerno"=> $customerno   
        );
        $test = addcategory($data,$customerno);
        echo $test;
        exit;
    }
    
    if($action=="editcategory"){
        $customerno = $_REQUEST['customerno'];
        $category = $_REQUEST['catname'];
        $userid = $_REQUEST['userid'];
        $catid = $_REQUEST['catid'];
        $data = array(
         "category"=> $category,
         "userid" => $userid,
         "customerno"=> $customerno,   
         "catid"=> $catid   
        );
        $test = editcategory($data,$customerno);
        echo $test;
        exit;
    }
    
    if($action=="deletecategory"){
        $customerno = $_REQUEST['customerno'];
        $userid = $_REQUEST['userid'];
        $catid = $_REQUEST['catid'];
        $data = array(
         "userid" => $userid,
         "customerno"=> $customerno,   
         "catid"=> $catid   
        );
        $test = deletecategory($data,$customerno);
        echo $test;
        exit;
    }
    
    if($action=='editexpense'){
        $data=array();
        $customerno = $_REQUEST['customerno'];
        $userid = $_REQUEST['userid'];
        $expid = $_REQUEST['expid'];
        $driverid = $_REQUEST['driverid'];
        $categoryid = $_REQUEST['categoryid'];
        $amount = $_REQUEST['amount'];
        $expdate = $_REQUEST['expdate'];
        $data = array(
         "userid" => $userid,
         "customerno"=> $customerno,   
         "expid"=> $expid,   
         "driverid"=> $driverid,   
         "categoryid"=>$categoryid,
         "amount"=>$amount,
         "expdate"=>$expdate
        );
        $test = editexpense($data,$customerno);
        echo $test;
        exit;
    }
    
    
    if($action=='addexpense'){
        
        
        
        $data=array();
        $customerno = $_REQUEST['customerno'];
        $userid = $_REQUEST['userid'];
        $driverid = $_REQUEST['driverid'];
        $categoryid = $_REQUEST['categoryid'];
        $amount = $_REQUEST['amount'];
        $expdate = $_REQUEST['expdate'];
        $data = array(
         "userid" => $userid,
         "customerno"=> $customerno,   
         "driverid"=> $driverid,   
         "categoryid"=>$categoryid,
         "amount"=>$amount,
         "expdate"=>$expdate
        );
        $test = addexpense($data,$customerno);
        echo $test;
        exit;
    }
    
}
?>