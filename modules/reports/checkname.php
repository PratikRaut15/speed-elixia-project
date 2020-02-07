<?php
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/bo/CheckpointManager.php';

 $customer = $_POST['customer']; 
 $userid = $_POST['userid'];
 $cname = $_POST['cname'];
 $test = $_POST['test'];
 $_SESSION['customerno']=$customer;echo "<br/>";
 $_SESSION['userid']=$userid;echo "<br/>";
 $chkm = new CheckpointManager($_SESSION['customerno']);
 echo $chkm->CheckName($cname,$userid,$test);
    
 ?>