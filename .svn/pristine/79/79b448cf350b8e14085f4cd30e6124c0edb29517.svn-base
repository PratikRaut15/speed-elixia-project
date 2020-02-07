<?php

if ($_REQUEST['report'] == 'vehicle') {
    include 'vehicle_functions.php';
    ob_start();
    $cat = getvehiclepdf($_REQUEST['customerno'], $_REQUEST['vehicleid']);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'Legal', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->setTestTdInOnePage(false);
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output($_REQUEST['vehicleid'] . "_" . date("d-m-Y") . "_details.pdf");
        //$cat."_".date("d-m-Y")."_travelhistory.xls";
    }
    catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}

if ($_REQUEST['report'] == 'vehiclehistory') {
    include '../transactions/transaction_functions.php';
    ob_start();
//$customerno = exit_issetor($_REQUEST['customerno']);
    $cat = getvehiclehistorypdf($_REQUEST['vehicleid']);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'Legal', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->setTestTdInOnePage(false);
        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleid'] . "_" . date("d-m-Y") . "_details.pdf");
        //$cat."_".date("d-m-Y")."_travelhistory.xls";
    }
    catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}
?>
