<?php
include_once '../../lib/bo/VehicleManager.php';
include_once '../reports/reports_travel_functions.php';

$vehiclemanager = new VehicleManager($customer_id);
$vehicle = $vehiclemanager->get_vehicle_details_by_id($veh_id);

if($vehicle==null){
    exit('Vehicle record is missing');
}

$user_details = $umanager->get_user($customer_id, $user_id);
$_SESSION['report_gen_user'] = $user_details->username;

$geocode = '1';

if($type=='pdf'){
    require_once('../reports/html2pdf.php');
    //$cat = substr(get_travel_history_report_pdf_cron($customer_id, $veh_id, $date, $date, $geocode),-4);  
    $cat = substr(get_travel_history_report_pdf($veh_id, $date, $date,'00:00','23:59',$geocode, $customer_id,$vehicle->groupname), -4);
    $cat = trim($cat);
    $content = ob_get_clean();

    try{
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output($cat."_".$date."_travelhistory.pdf");
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}
else{
    require_once '../../lib/bo/simple_html_dom.php';
    $cat = substr(get_travel_history_report_excel($customer_id, $veh_id,$date,$date,"00:00", "23:59", $geocode,$vehicle->groupname),-4);
    $cat = trim($cat);
    $content = ob_get_clean();
    $xls_filename = $cat."_".$date.".xls";
    $html = str_get_html($content);
    
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$xls_filename");
    echo $html;
    
}

?>