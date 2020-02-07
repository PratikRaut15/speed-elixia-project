<?php
include_once '../reports/route_summary_functions.php';
$today = $date; //date('d-m-Y')
$start_date = date('d-m-Y', strtotime("-12 day " . $today));
$end_date = $today;
$user_details = $umanager->get_user($customer_id, $user_id);
$_SESSION['report_gen_user'] = $user_details->username;
if ($type == 'pdf') {
    require_once '../reports/html2pdf.php';
    getRouteSummary_pdf($start_date, $end_date, $customer_id);
    $content = ob_get_clean();
    //echo $content;die();
    try{
        $html2pdf = new HTML2PDF('L', 'LEGAL', 'en',true, 'UTF-8', array(15, 5, 15, 5));
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($content);
        $html2pdf->Output($today . "_RouteSummary.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else {
    require_once '../../lib/bo/simple_html_dom.php';
    getRouteSummary_excel($start_date, $end_date, $customer_id);
    $content = ob_get_clean();
    $xls_filename = $today . "_RouteSummary.xls";
    $html = str_get_html($content);
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$xls_filename");
    echo $html;
}
?>
