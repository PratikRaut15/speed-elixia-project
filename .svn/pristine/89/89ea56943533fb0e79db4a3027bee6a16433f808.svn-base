<?php

set_time_limit(0);
include '../../lib/bo/simple_html_dom.php';
include 'tms_function.php';

$excel = true;
ob_start();
if ($_REQUEST['action'] == 'PlacementTracker') {
 export_Placement_Tracker_Excel($_REQUEST['customerno'], $_REQUEST['factoryid'], $_REQUEST['transporterid']);
 $xls_filename = str_replace(' ', '', "Placement_Tracker_" . date("d-m-Y") . ".xls");
}

$content = ob_get_clean();
$html = str_get_html($content);
if ($excel) {
 header("Content-type: application/vnd.ms-excel");
 header("Content-Disposition: attachment; filename=$xls_filename");
}
echo $content;
?>