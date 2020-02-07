<?php

set_time_limit(0);
require_once "../../lib/system/utilities.php";
require_once '../../lib/bo/CustomerManager.php';
require_once '../../lib/bo/UserManager.php';
include '../../lib/bo/simple_html_dom.php';
include_once '../../lib/bo/VehicleManager.php';

if ($_REQUEST['report'] == 'analytic') {
    include 'route_dashboard_functions.php';
    ob_start();
    //$geocode = '1';
    $cat = get_data_dashboard_excel($_REQUEST['sdate'],$_REQUEST['edate'], $_REQUEST['customerno'],$_REQUEST['userid'], $_REQUEST['groupid']);
    $content = ob_get_clean();
    $xls_filename = date("d-m-Y") . "_analyticssummary.xls";
    //$html = str_get_html($content);
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment;filename=$xls_filename");
    echo $content;
}
?>
