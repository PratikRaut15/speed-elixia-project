<?php

include_once '../reports/reports_overspeed_functions.php';

$all_vehicles = groupBased_vehicles_cron($customer_id, $user_id);

if (empty($all_vehicles)) {
 exit('Vehilces not found');
}
$user_details = $umanager->get_user($customer_id, $user_id);
$_SESSION['report_gen_user'] = $user_details->username;

if ($type == 'pdf') {

 require_once('../reports/html2pdf.php');

 getoverspeedreportpdf_allveh($customer_id, $date, $all_vehicles, $user_details);
 $content = ob_get_clean();

 try {
  $html2pdf = new HTML2PDF('L', 'A4', 'en');
  $html2pdf->pdf->SetDisplayMode('fullpage');
  $html2pdf->writeHTML($content);
  $html2pdf->Output("AllVehilcles_" . $date . "_OverspeedReport.pdf");
 } catch (HTML2PDF_exception $e) {
  echo $e;
  exit;
 }
} else {
 require_once '../../lib/bo/simple_html_dom.php';
 getoverspeedreportcsv_allveh($customer_id, $date, $all_vehicles, $user_details);

 $content = ob_get_clean();
 $xls_filename = "AllVehilcles_" . $date . "_OverspeedReport.xls";
 $html = str_get_html($content);

 header("Content-type: application/vnd.ms-excel");
 header("Content-Disposition: attachment; filename=$xls_filename");
 echo $html;
}
?>