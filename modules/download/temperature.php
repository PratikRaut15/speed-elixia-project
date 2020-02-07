<?php

include_once '../../lib/bo/VehicleManager.php';
include_once '../reports/reports_common_functions.php';

$vehiclemanager = new VehicleManager($customer_id);
if (isset($veh_id)) {
    $vehicle = $vehiclemanager->get_vehicle_details_by_id($veh_id);

    if ($vehicle == null) {
        exit('Vehicle record is missing');
    }
}
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
error_reporting(0);
if($forHour > 0){
    $start_time = date('H:00', strtotime("-".$forHour." Hours", strtotime($view_data['report_date'])));
    $end_time = date('H:00', strtotime($view_data['report_date']));
} else {
    $start_time = '00:00';
    $end_time = '23:59';
}
$user_details = $umanager->get_user($customer_id, $user_id);
$_SESSION['report_gen_user'] = $user_details->username;
if (isset($user_details->trinterval) && $user_details->trinterval > 0) {
    $interval = $user_details->trinterval;
} else {
    $interval = 60;
}
$fileName = '';
if ($type == 'pdf') {
    require_once('../reports/html2pdf.php');
    if (!isset($veh_id) && isset($group_id) && $group_id > 0) {
        $vehiclemanager = new VehicleManager($customer_id);
        $devices = $vehiclemanager->get_all_vehicles_of_groups($group_id);
        if (isset($devices) && !empty($devices)) {
            $cat = '';
            foreach ($devices AS $key=>$device) {
                $group_name = $vehiclemanager->getgroupnamebyvehicleid($device->vehicleid);
                $fileName = isset($group_name) ? $group_name : '';
                $cat .= gettempreportpdf($customer_id, $view_data['report_date'], $view_data['report_date'], $device->deviceid, $device->vehicleno, $interval, $start_time, $end_time, $switchto, $group_name, 'pdf');
            }
            if ($cat != '') {
                echo $cat;
            }
        }
    } else {
        $fileName = isset($vehicle->vehicleno) ? $vehicle->vehicleno : '';
        echo $cat = gettempreportpdf($customer_id, $view_data['report_date'], $view_data['report_date'], $vehicle->deviceid, $vehicle->vehicleno, $interval, $start_time, $end_time, $switchto, $vehicle->groupname, 'pdf');
    }
    $content = ob_get_clean();
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output($fileName . "_" . date("d-m-Y") . "_TemperatureReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else {
    require_once '../../lib/bo/simple_html_dom.php';
    
    if (!isset($veh_id) && isset($group_id) && $group_id > 0) {
        $vehiclemanager = new VehicleManager($customer_id);
        $devices = $vehiclemanager->get_all_vehicles_of_groups($group_id);
        if (isset($devices) && !empty($devices)) {
            $cat = '';
            foreach ($devices AS $key=>$device) {
                $group_name = $vehiclemanager->getgroupnamebyvehicleid($device->vehicleid);
                $fileName = isset($group_name) ? $group_name : '';
                $cat .= gettempreportpdf($customer_id, $view_data['report_date'], $view_data['report_date'], $device->deviceid, $device->vehicleno, $interval, $start_time, $end_time, $switchto, $group_name, 'xls');
            }
            if ($cat != '') {
                echo $cat;
            }
        }
    } else {
        $fileName = isset($vehicle->vehicleno) ? $vehicle->vehicleno : '';
        echo $cat = gettempreportpdf($customer_id, $view_data['report_date'], $view_data['report_date'], $vehicle->deviceid, $vehicle->vehicleno, $interval, $start_time, $end_time, $switchto, $vehicle->groupname, 'xls');
    }
    $xls_filename = str_replace(' ', '', $fileName . "_" . date("d-m-Y") . "_TemperatureReport.xls");
    $content = ob_get_clean();
    $html = str_get_html($content);

    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$xls_filename");
    echo $html;
}
?>