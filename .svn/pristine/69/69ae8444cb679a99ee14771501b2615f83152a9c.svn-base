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
$filterdata = array(
    "txtorderid"=>$_REQUEST['txtorderid'],
    "txtpickupdate"=>$_REQUEST['txtpickupdate'],
    "txtawbno"=>$_REQUEST['txtawbno']
);

ob_start();
if($_REQUEST['report'] == 'excelexport'){
    include_once 'pickup_functions.php';
    get_order_list($_REQUEST['customerno'], $_REQUEST['userid'],$filterdata);
    $xls_filename = str_replace(' ', '', "Pickup_List.xls");
    $content = ob_get_clean();
    $html = str_get_html($content);
    if($excel){
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$xls_filename");
    }
    echo $html;
}
?>