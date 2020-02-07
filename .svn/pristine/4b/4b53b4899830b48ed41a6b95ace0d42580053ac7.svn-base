<?php
include_once "session.php";
include "loginorelse.php";
include_once "db.php";
include_once "../../constants/constants.php";
include_once "../../lib/components/gui/objectdatagrid.php";
require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';
include "header.php";
$db = new DatabaseManager();
$cronm = new CronManager();
$array_data = array(
    '0' => array('x' => 1, 'text' => 'Monthly'),
    '1' => array('x' => 3, 'text' => 'Quartely'),
    '2' => array('x' => 6, 'text' => 'Half Yearly'),
    '3' => array('x' => 12, 'text' => 'Yearly')
);
$next_month = date('M Y', strtotime('+1 month'));
$month = date("Y-m");
$total_amt = 0;
$display = '';
$table = '';
foreach ($array_data as $data) {

    $customernos = $cronm->getMonthlySubscCust($data['x']);
    $ledgers = $cronm->getLedgerOfCustomer($customernos);
    if (isset($ledgers)) {
        $x = 1;
        $table = "<table border = 1><th>Sr. No.</th><th>Customerno</th><th>Ledger ID</th><th>Invoice No.</th><th>Start Date</th><th>End Date</th><th>Expiry Date</th><th>No of Devices</th><th>Subscription Price</th><th>Amount</th><th>Tax Amount</th><th>State</th><th></th>";
        foreach ($ledgers as $ledgerid) {
            $details = array();
            $details = $cronm->getInvoiceData($ledgerid, $month);
            $invoiceno = '';
            if ($details != NULL) {
                $datetime = date('Y-m-d H:i:s');
                $start_date = date('Y-m-d', strtotime($details['end_date'] . "+1 days"));
                $end_date = date("Y-m-t", strtotime('+' . ($data['x'] - 1) . ' month', strtotime($start_date)));
                $expiry_date = date('Y-m-d', strtotime('+1 months', strtotime($datetime)));
                $taxname = "4";

                if ($details['state_code'] == 27) {
                    $tax_percent = 0.09;
                    $tax_cgst = round($details['total'] * $data['x'] * $tax_percent);
                    $tax_sgst = round($details['total'] * $data['x'] * $tax_percent);
                    $tax_igst = 0;
                    $inv_amt = round(( $details['total'] * $data['x'] ) + $tax_cgst + $tax_sgst);
                    $gst = 'sgst';
                } else {
                    $tax_percent = 0.18;
                    $tax_cgst = 0;
                    $tax_sgst = 0;
                    $tax_igst = round($details['total'] * $data['x'] * $tax_percent);
                    $inv_amt = round(( $details['total'] * $data['x'] ) + $tax_igst);
                    $gst = 'igst';
                }
                $total_amt += $inv_amt;
                if ($details['customerno'] < 10) {
                    $invoiceno .= 'ES' . '0' . $details['customerno'];
                } else {
                    $invoiceno .= 'ES' . $details['customerno'];
                }
                if ($ledgerid < 10) {
                    $invoiceno .= '0' . $ledgerid . $details['invoiceid'];
                } else {
                    $invoiceno .= $ledgerid . $details['invoiceid'];
                }
                if ($details['renewal'] == -3) {
                    $lease = 'Lease';
                } else {
                    $lease = '';
                }
                $table.="<tr><td>" . $x . "</td><td>" . $details['customerno'] . "</td><td>" . $ledgerid . "</td><td>" . $invoiceno . "</td><td>" . $start_date . "</td><td>" . $end_date . "</td><td>" . $expiry_date . "</td><td>" . $details['count1'] . "</td><td>" . $details['unit_msp'] . "</td><td>" . $inv_amt . "</td><td>" . ( $tax_cgst + $tax_sgst + $tax_igst ) . "</td><td>" . $details['state'] . "</td><td>" . $lease . "</td></tr>";
                $x++;
            }
        }
        $table.="</table>";
    }
    $display.= '<br><br><span><h4>' . $data['text'] . ' Invoice</h4></span>';
    $display.='<div>' . $table . '</div>';
}
$message = '<center><span><h3>' . $next_month . ' Prediction</h3></span>&nbsp;&nbsp;&nbsp;<span style="margin-left:50px;"><h4>Predicted Amount : ' . $total_amt . '/-</h4></span>';
$message.=$display;
$message.='</center>';
echo $message;
?>
<style>

</style>
<footer>
    <?php
    include "footer.php";
    ?>
</footer>