<?php
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/bo/CheckpointManager.php';

 $customer = $_POST['customer']; 
 $userid = $_POST['userid'];
 $vehicleid = $_POST['vehicle'];
 $cname = $_POST['cname'];
 $clat = $_POST['clat'];
 $clong = $_POST['clong'];
 $cadd = $_POST['cadd'];
 $STime_pop = $_POST['STime_pop'];
 $_SESSION['customerno']=$customer;echo "<br/>";
 $_SESSION['userid']=$userid;echo "<br/>";
 
 $chkm = new CheckpointManager($_SESSION['customerno']);
 echo $chkm->CreateCheck($cname, $cadd, $clat, $clong, $userid, $vehicleid, $STime_pop);
    
 ?>