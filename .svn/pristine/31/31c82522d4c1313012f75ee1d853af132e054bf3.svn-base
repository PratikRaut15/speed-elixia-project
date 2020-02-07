<?php
set_time_limit(0);

include '../../lib/bo/simple_html_dom.php';

$excel = true;
$report = isset($_REQUEST['report']) ? $_REQUEST['report'] : '';

ob_start();
if($report == 'ordermapping'){
    $inp_date = isset($_REQUEST['d']) ? $_REQUEST['d'] : $today;
    
    include_once 'assign_function.php';
    include 'pages/order_map_table.php';
    $xls_filename = $inp_date."_orders_mapped.xls";
}
elseif($report == 'orderSequence'){
    require_once 'assign_function.php';
    $arr = array("Delivery date: {$_REQUEST['date']}");
    echo excel_header('Order Sequence', $arr);
    require_once "seq_ajax_new.php";
    $xls_filename = $_REQUEST['date']."_order_sequence.xls";
}


$content = ob_get_clean();
$html = str_get_html($content);
if($excel){
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$xls_filename");
}
echo $html;


?>