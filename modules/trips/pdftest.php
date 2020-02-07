<?php
set_time_limit(0);
//date_default_timezone_set("Asia/Calcutta");

if($_REQUEST['report'] == 'closetripreport'){
    include 'trips_function.php';
    ob_start();
    $customerno = $_SESSION['customerno'];
    get_closetrip_report_pdf($_REQUEST['sdate'],$_REQUEST['edate'],$customerno);
    $content = ob_get_clean();
    require_once('../reports/html2pdf.php');
    try{
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y")."_closedtrip.pdf");
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}

?>
