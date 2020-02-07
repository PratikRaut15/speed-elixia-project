<?php

require_once '../../lib/bo/simple_html_dom.php';
require_once '../../lib/comman_function/reports_func.php';

$cm = new CronManager();
$vm = new VehicleManager(2);
$customerList = speedConstants::SP_ALLANASONS_TRANSPORTER;
$objCustomer = new stdClass();
$objCustomer->customerno = $customerList;
$customerArr = $cm->getCustomerList($objCustomer);


$message = '';
$subTitle = array(
    "Report Date: $date"
);
$header = excel_header('Active/Inactive Vehicle List', $subTitle);
if (isset($customerArr) && !empty($customerArr)) {
    foreach ($customerArr AS $customerno) {
        $message .= '<br><br><table><tr><th colspan="3">' . $customerno['customercompany'] . ' (' . $customerno['customerno'] . ')</th></tr>';
        $vehicleArr = $vm->getAllVehicleForCustomer($customerno['customerno']);
        $message .= '<tr><th>Sr No</th><th>Vehicle No</th><th>Status</th><th>Last Active On</th></tr>';
        if (isset($vehicleArr) && !empty($vehicleArr)) {
            $i = 1;
            foreach ($vehicleArr AS $vehicle) {
                if (isset($vehicle->lastupdated) && $vehicle->lastupdated != '') {
                    $lastupdated = date('d-m-Y H:i', strtotime($vehicle->lastupdated));
                } else {
                    $lastupdated = '';
                }
                $message .= '<tr><td>' . $i . '</td><td>' . $vehicle->vehicleno . '</td><td>' . $vehicle->status . '</td><td>' . $lastupdated . '</td></tr>';
                $i++;
            }
        } else {
            $message .= '<tr><td colspan="3">No Vehicle Inactive</td></tr>';
        }
        $message .= '</table>';
    }
}
echo $header . $message;

$content = ob_get_clean();
$xls_filename = "Active_Inactive_Vehicle_List_" . $date . ".xls";
$html = str_get_html($content);

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$xls_filename");
echo $html;
