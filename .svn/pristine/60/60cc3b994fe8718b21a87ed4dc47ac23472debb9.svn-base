<?php
set_time_limit(0);
//date_default_timezone_set("Asia/Calcutta"); 

require_once "../../lib/system/utilities.php";
require_once '../../lib/bo/CustomerManager.php';
require_once '../../lib/bo/UserManager.php';
include '../../lib/bo/simple_html_dom.php';
include_once '../../lib/bo/VehicleManager.php';

$excel = true;
$geocode = '1';

ob_start();
if($_REQUEST['report'] == 'xlsclosereport'){
    include_once 'trips_function.php';
    get_closetrip_report_excel($_REQUEST['customerno'],$_REQUEST['sdate'],$_REQUEST['edate']);
    $xls_filename = str_replace(' ', '', date("d-m-Y")."_close_report.xls");
}

$content = ob_get_clean();
$html = str_get_html($content);
if($excel){
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$xls_filename");
}
echo $html;


?>