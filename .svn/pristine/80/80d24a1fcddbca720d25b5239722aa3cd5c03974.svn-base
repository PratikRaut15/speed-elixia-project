<?php
set_time_limit(0);
//date_default_timezone_set("Asia/Calcutta"); 

include '../../lib/bo/simple_html_dom.php';


$excel = true;
$geocode = '1';

ob_start();

if($_REQUEST['report'] == 'vehdataxls'){
    require_once 'vehicle_functions.php';
    $customerno = exit_issetor($_REQUEST['customerno']);
    $kind= $_REQUEST['kind'];
    $cat = getvehicles_xls($kind);
    $xls_filename = date("d-m-Y")."_vehicledata.xls";
}
else if($_REQUEST['report'] == 'veh_histxls'){
    require_once '../transactions/transaction_functions.php';
    $customerno = exit_issetor($_REQUEST['customerno']);
    $cat = getvehiclehistoryxls($_REQUEST['vehicleid']);
    
   // echo $cat;die();
    
    $xls_filename = date("d-m-Y")."_VehicleHistory.xls";
}


$content = ob_get_clean();
$html = str_get_html($content);
if($excel){
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$xls_filename");
}
echo $html;


?>