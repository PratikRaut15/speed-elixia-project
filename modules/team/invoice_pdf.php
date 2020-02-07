<?php

require_once 'invoice_genfunc.php';

if (isset($_POST['gpdf'])) {
    ob_start();
    $cat = get_pdfdata($_POST);
    $content = ob_get_clean();
    $invno = GetSafeValueString($_POST['invno'], "string");
//die();
    require_once("../../vendor/autoload.php");
    try {
        $html2pdf = new HTML2PDF('P', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($invno . "_Invoice.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}
?>


