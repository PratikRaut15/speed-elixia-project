<!--<ul id="tabnav">-->
<?php

$checkpoint_sequence_check =isset($_SESSION['use_checkpoint_settings']) ? $_SESSION['use_checkpoint_settings'] : 1;   // If 0 then skip the sequence of checkpoints

//}
?>
<!--</ul>-->
<?php
if(!isset($_GET['id']) || $_GET['id']==1)
{
	include 'route_dashboard_functions.php';
	
    if($checkpoint_sequence_check==0){
   		include 'pages/viewvehicles_test2.php';
   	}
   	else{
    		include 'pages/viewvehicles_test.php';
 		}
}
else if($_GET['id']==2)
{
include 'route_dashboard_functions.php';
    include 'pages/approve_transaction.php';
}
else if($_GET['id']==3)
{
include 'route_dashboard_functions.php';
include 'pages/editvehicle_new.php';

}
?>
