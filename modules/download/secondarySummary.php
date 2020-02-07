<?php
include_once '../sales/sales_function.php';
$today = $date; //date('d-m-Y')
$user_details = $umanager->get_user($customer_id, $user_id);
$_SESSION['report_gen_user'] = $user_details->username;
    require_once '../../lib/bo/simple_html_dom.php';
    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($user_key); // person data
    getSecondarySummary_excel($customer_id,$dpd,$today);
    $content = ob_get_clean();
    $xls_filename = $today . "_SecondaryCallReport.xls";
    $html = str_get_html($content);
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$xls_filename");
    echo $html;

?>
