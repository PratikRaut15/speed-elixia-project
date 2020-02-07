<?php

include_once '../../lib/bo/VehicleManager.php';
include_once '../reports/reports_fuel_functions.php';

$vehiclemanager = new VehicleManager($customer_id);
$vehicle = $vehiclemanager->get_vehicle_details_by_id($veh_id);

if($vehicle==null){
    exit('Vehicle record is missing');
}

$startdate = $endate = $date;
$interval = 30;
$start_time = '00:00';
$end_time = '23:59';
$user_details = $umanager->get_user($customer_id, $user_id);
$_SESSION['report_gen_user'] = $user_details->username;

if($type=='pdf'){
    ob_start();
    
    $cat = get_fuelcomsumption_pdf($customer_id,$startdate,$start_time,$endate,$end_time,$veh_id,$vehicle->vehicleno,$interval,$user_details);
    $content = ob_get_clean();
    require_once('../reports/html2pdf.php');
    try{
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output($vehicle->vehicleno."_".date("d-m-Y")."_FuelConsumption.pdf");
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
    
}
else{
    require_once '../../lib/bo/simple_html_dom.php';
    $cat = get_fuelcomsumption_xls($customer_id,$startdate,$start_time,$endate,$end_time,$veh_id,$vehicle->vehicleno,$interval,$user_details);
    $xls_filename = str_replace(' ', '', $vehicle->vehicleno."_".date("d-m-Y")."_FuelConsumption.xls");
    $content = ob_get_clean();
    $html = str_get_html($content);
    
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$xls_filename");
    echo $html;
    
}
?>