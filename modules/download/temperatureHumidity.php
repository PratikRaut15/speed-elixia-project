<?php

include_once '../../lib/bo/VehicleManager.php';
include_once '../reports/reports_common_functions.php';

$vehiclemanager = new VehicleManager($customer_id);
$vehicle = $vehiclemanager->get_vehicle_details_by_id($veh_id);

if ($vehicle == null) {
    exit('Vehicle record is missing');
}
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
error_reporting(0);
$interval = 60;
$start_time = '00:00';
$end_time = '23:59';
$user_details = $umanager->get_user($customer_id, $user_id);
$_SESSION['report_gen_user'] = $user_details->username;

if ($type == 'pdf') {
    require_once '../reports/html2pdf.php';
    echo $cat = gettemphumidityreportpdf($customer_id, $view_data['report_date'], $view_data['report_date'], $vehicle->deviceid, $vehicle->vehicleno, $interval, $start_time, $end_time, $switchto, $vehicle->groupname);
    $content = ob_get_clean();
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output($vehicle->vehicleno . "_" . date("d-m-Y") . "_TemperatureHumidityReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }

} else {
    require_once '../../lib/bo/simple_html_dom.php';
    $cat = gettemphumidityreportxls($customer_id, $view_data['report_date'], $view_data['report_date'], $vehicle->deviceid, $vehicle->vehicleno, $interval, $start_time, $end_time, $switchto, $vehicle->groupname);
    $xls_filename = str_replace(' ', '', $vehicle->vehicleno . "_" . date("d-m-Y") . "_TemperatureHumidityReport.xls");
    $content = ob_get_clean();
    $html = str_get_html($content);

    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$xls_filename");
    echo $html;
}
?>