<?php
include_once '../../lib/bo/CustomerManager.php';
include_once '../reports/reports_travel_functions.php';

$user_details = $umanager->get_user($customer_id, $user_id);
$_SESSION['report_gen_user'] = $user_details->username;

$geocode = '1';

if($type=='pdf'){
    require_once('../reports/html2pdf.php');
    
    $cat = getSMSConsumedDetails($customer_id); 
    $cat = trim($cat); 
    $content = ob_get_clean();

    try{
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output($cat."_".$date."_travelhistory.pdf");
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}
else{
    require_once '../../lib/bo/simple_html_dom.php';
    $cat = getSMSConsumedDetails($customer_id);
    $cat = trim($cat);
    $content = ob_get_clean();
    $xls_filename = $cat."_".$date.".xls";
    $html = str_get_html($content);
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$xls_filename");
    echo $html;
}

?>