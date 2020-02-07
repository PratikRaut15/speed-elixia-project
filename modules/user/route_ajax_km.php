<?php
header("Access-Control-Allow-Origin: *");

include 'user_functions.php';

if(isset($_POST['kmTracked']) && isset($_POST['data1'])){
    $km_tracked = get_km_tracked($_POST['data1']);
    echo json_encode($km_tracked);
   
}
	
if(isset($_POST['alertGenerated'])){
    $alerts = get_alerts_generated();
    echo json_encode($alerts);
    
}
?>
