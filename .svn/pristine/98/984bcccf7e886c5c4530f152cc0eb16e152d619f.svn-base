<?php
if($_REQUEST['report'] == 'transaction'){
include 'transaction_functions.php';
ob_start();
$cat=gettransactionpdf($_REQUEST['maintenanceid'],$_REQUEST['vehicleid']);    
$content = ob_get_clean();
require_once('html2pdf.php');
    try
    {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
       $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output($_REQUEST['maintenanceid']."_".date("d-m-Y")."_transdetails.pdf");
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}

if($_REQUEST['report'] == 'accident'){
include 'transaction_functions.php';
ob_start();
$cat=getaccidentpdf($_REQUEST['maintenanceid'],$_REQUEST['vehicleid']);    
$content = ob_get_clean();
require_once('html2pdf.php');
    try
    {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
       $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output($_REQUEST['maintenanceid']."_".date("d-m-Y")."_acc_transdetails.pdf");
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}

?>
