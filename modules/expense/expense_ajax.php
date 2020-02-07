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
    
}
?>