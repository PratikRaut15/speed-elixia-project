<?php 
require_once 'driver_functions.php';
$action = $_REQUEST["action"];

if(isset($action)){
    if($action=="addamount"){
        $driverid = $_REQUEST['driverid'];
        $amount = $_REQUEST['amount'];
        $amountdetail = $_REQUEST['amountdetail'];
        $userid = $_REQUEST['userid'];
        $customerno = $_REQUEST['customerno'];
        $data = array(
         "driverid"=> $driverid,
         "amount"=> $amount,
         "amountdetail"=> $amountdetail,
         "userid" => $userid,
         "customerno"=> $customerno   
        );
        $test = addamount_driver($data);
        $data = array(
                "amount"=>$test
            );
     echo json_encode($data);   
    }
}
?>