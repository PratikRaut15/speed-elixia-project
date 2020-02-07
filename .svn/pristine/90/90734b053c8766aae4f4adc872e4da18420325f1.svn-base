<?php

ini_set('memory_limit', '2048M');

if ($_REQUEST['report'] == 'analytic') {
    include 'route_dashboard_functions.php';
    set_time_limit(180);
    ob_start();
    $cat = get_data_fordashboard_pdf($_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['customerno'], $_REQUEST['userid'], $_REQUEST['groupid']);
    $content = ob_get_clean();
    require_once '../reports/html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        //$html2pdf->Output(date("d-m-Y")."analyticssummary.pdf");
        $html2pdf->Output(date("d-m-Y") . "analyticssummary.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}
?>
