<?php

include_once '../reports/reports_common_functions.php';

$cm = new CustomerManager();
$vehiclemanager = new VehicleManager($customer_id);

$vehicle = $vehiclemanager->get_vehicle_details_by_id($veh_id);

if($vehicle==null){
    exit('Vehicle record is missing');
}

if($type=='pdf'){
    require_once('../reports/html2pdf.php');
    $html = getgensetreportpdfMultipleDays($customer_id, $date, $date, $vehicle->deviceid, $vehicle->vehicleno, $vehicle->groupname);
    $content = ob_get_clean();

    try{
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $cat = substr($vehicle->vehicleno,-4);
        $html2pdf->Output($cat."_".$date."_gensetreport.pdf");
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}
else{
    require_once '../../lib/bo/simple_html_dom.php';
    getgensetreportexcel($customer_id, $date, $date, $vehicle->deviceid, $vehicle->vehicleno, $vehicle->groupname);
    $content = ob_get_clean();
    $cat = substr($vehicle->vehicleno,-4);
    $xls_filename = $cat."_".$date.".xls";
    $html = str_get_html($content);
    
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$xls_filename");
    echo $html;
}

?>