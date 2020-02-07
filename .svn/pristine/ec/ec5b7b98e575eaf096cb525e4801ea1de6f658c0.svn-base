<?php
include_once "session.php";
include "loginorelse.php";
include_once "db.php";
include_once "../../constants/constants.php";
include_once "../../lib/system/Sanitise.php";
include_once "../../lib/components/gui/objectdatagrid.php";
include_once "../../lib/system/DatabaseManager.php";

include "header.php";
$db = new DatabaseManager();
$exportInput = '';
$todaysDate = date("Y-m-d H:i:s");
if (isset($_REQUEST['searchDue'])) {
    $customername = $_POST['customername'];
    $customerno = $_POST['customerno'];
    $hist_days = $_POST['days'];
    $days = date('Y-m-d', strtotime('-'.$hist_days.' days'));
    if (!empty($_POST['customerno'])) {
        $queryCust = sprintf(" AND customerno = %d", $customerno);
        $Display = array();
        $SQL = sprintf("SELECT  * 
                        FROM    " . DB_PARENT . ".invoice 
                        WHERE   isdeleted = 0 
                        %s
                        AND     inv_expiry < '%s'
                        AND     status LIKE 'pending'
                        AND     inv_expiry <> '0000-00-00'
                        ORDER BY customerno ASC,inv_expiry DESC", Sanitise::String($queryCust), Sanitise::Date($days));
        $db->executeQuery($SQL);
        $totalpayment_tax = 0;
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $Datacap = new stdClass();
                $Datacap->invoiceid = $row['invoiceid'];
                $Datacap->invoiceno = $row['invoiceno'];
                $Datacap->customerno = $row['customerno'];
                $Datacap->clientname = $row['clientname'];
                $Datacap->invdate = date("d-m-Y", strtotime($row['inv_date'])); //date('Y-m-d', strtotime("+30 days"));
//                $Datacap->invexpirydate = date("d-m-Y", strtotime("+30 days", strtotime($Datacap->invdate)));
                $Datacap->invamt = $row['inv_amt'];
                $Datacap->status = $row['status'];
                $Datacap->pamt = $row['pending_amt'];
                $totalpayment_tax+=$row['pending_amt'];
                $Datacap->tax = $row['tax'];
                $Datacap->taxamt = $row['tax_amt'];
                $Datacap->paid_amt = $row['paid_amt'];
                $Datacap->tds_amt = $row['tds_amt'];
                $Datacap->upaid = $row['unpaid_amt'];
                if (isset($row['inv_expiry']) && $row['inv_expiry'] != '0000-00-00') {
                    $Datacap->inv_expirydate = date("d-m-Y", strtotime($row['inv_expiry']));
                } else {
                    $Datacap->inv_expirydate = 'NA';
                }
                $Datacap->comment = $row['comment'];
                $Display[] = $Datacap;
            }
        }
        if (count($Display) > 0) {
            $dataString = "exportData(" . $customerno . ")";
            $exportInput = 'Export To Excel ' . ' <input type="button" title="Export Raw Data" id="dataQuery" name="dataQuery" value="" onclick="' . $dataString . '"/>';
        }

        $tax_dg = new objectdatagrid($Display);
        $tax_dg->AddColumn("Sr.No", "invoiceid");
        $tax_dg->AddColumn("Invoice No", "invoiceno");
        $tax_dg->AddColumn("Customer No", "customerno");
        $tax_dg->AddColumn("Client Name", "clientname");
        $tax_dg->AddColumn("Invoice Date", "invdate");
//        $tax_dg->AddColumn("Invoice Exp.Date", "invexpirydate");
        $tax_dg->AddColumn("Invoice Amt", "invamt");
        $tax_dg->AddColumn("Status", "status");
        $tax_dg->AddColumn("Paid Amt", "paid_amt");
        $tax_dg->AddColumn("Tax", "tax");
        $tax_dg->AddColumn("Tax Amt", "taxamt");
        $tax_dg->AddColumn("Pending Amt", "pamt");
        $tax_dg->AddColumn("TDS Amt", "tds_amt");
        $tax_dg->AddColumn("Unpaid Amt", "upaid");
        $tax_dg->AddColumn("Expiry Date", "inv_expirydate");
        $tax_dg->AddColumn("Comment", "comment");
        $tax_dg->AddRightAction("View", "../../images/edit.png", "invoice_edit.php?inid=%d");
        $tax_dg->SetNoDataMessage("No Invoices");
        $tax_dg->AddIdColumn("invoiceid");

        $Display = array();
        $SQL = sprintf("SELECT  * 
                        FROM    " . DB_PARENT . ".proforma_invoice 
                        WHERE   isdeleted = 0 
                        %s 
                        AND     status LIKE 'pending'
                        AND     payment_due_date < '%s'
                        AND     payment_due_date <> '0000-00-00'
                        ORDER BY customerno ASC,payment_due_date DESC", Sanitise::String($queryCust), Sanitise::Date($days));
        $db->executeQuery($SQL);
        $totalpayment_prof = 0;
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $Datacap = new stdClass();
                $Datacap->invoiceid = $row['pi_id'];
                $Datacap->invoiceno = $row['invoiceno'];
                $Datacap->customerno = $row['customerno'];
                $Datacap->clientname = $row['clientname'];
                $Datacap->invdate = date("d-m-Y", strtotime($row['inv_date'])); //date('Y-m-d', strtotime("+30 days"));
//                $Datacap->invexpirydate = date("d-m-Y", strtotime("+30 days", strtotime($Datacap->invdate)));
                $Datacap->invamt = $row['inv_amt'];
                $Datacap->status = $row['status'];
                $Datacap->pamt = $row['pending_amt'];
                $totalpayment_prof += $row['pending_amt'];
                $Datacap->tax = $row['tax'];
                $Datacap->taxamt = $row['tax_amt'];
                $Datacap->paid_amt = $row['paid_amt'];
                $Datacap->tds_amt = $row['tds_amt'];
                $Datacap->upaid = $row['unpaid_amt'];
                if (isset($row['payment_due_date']) && $row['payment_due_date'] != '0000-00-00') {
                    $Datacap->inv_expirydate = date("d-m-Y", strtotime($row['payment_due_date']));
                } else {
                    $Datacap->inv_expirydate = 'NA';
                }
                $Datacap->comment = $row['comment'];
                $Display[] = $Datacap;
            }
        }
        if (count($Display) > 0) {
            $dataString = "exportData(" . $customerno . ")";
            $exportInput = 'Export To Excel ' . ' <input type="button" title="Export Raw Data" id="dataQuery" name="dataQuery" value="" onclick="' . $dataString . '"/>';
        }
        $proforma_dg = new objectdatagrid($Display);
        $proforma_dg->AddColumn("Sr.No", "invoiceid");
        $proforma_dg->AddColumn("Invoice No", "invoiceno");
        $proforma_dg->AddColumn("Customer No", "customerno");
        $proforma_dg->AddColumn("Client Name", "clientname");
        $proforma_dg->AddColumn("Invoice Date", "invdate");
//        $proforma_dg->AddColumn("Invoice Exp.Date", "invexpirydate");
        $proforma_dg->AddColumn("Invoice Amt", "invamt");
        $proforma_dg->AddColumn("Status", "status");
        $proforma_dg->AddColumn("Paid Amt", "paid_amt");
        $proforma_dg->AddColumn("Tax", "tax");
        $proforma_dg->AddColumn("Tax Amt", "taxamt");
        $proforma_dg->AddColumn("Pending Amt", "pamt");
        $proforma_dg->AddColumn("TDS Amt", "tds_amt");
        $proforma_dg->AddColumn("Unpaid Amt", "upaid");
        $proforma_dg->AddColumn("Expiry Date", "inv_expirydate");
        $proforma_dg->AddColumn("Comment", "comment");
        $proforma_dg->AddRightAction("View", "../../images/edit.png", "invoice_edit.php?inid=%d");
        $proforma_dg->SetNoDataMessage("No Invoices");
        $proforma_dg->AddIdColumn("invoiceid");


        /// Credit Note

        $Display = array();
        $SQL = sprintf("SELECT  * 
                        FROM    " . DB_PARENT . ".credit_note 
                        WHERE   isdeleted = 0 
                        %s
                        AND     inv_expiry < '%s'
                        AND     inv_expiry <> '0000-00-00'
                        ORDER BY customerno ASC,invoiceid DESC", Sanitise::String($queryCust), Sanitise::Date($days));
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $Datacap = new stdClass();
                $Datacap->invoiceid = $row['pi_id'];
                $Datacap->invoiceno = $row['invoiceno'];
                $Datacap->customerno = $row['customerno'];
                $Datacap->clientname = $row['clientname'];
                $Datacap->invdate = date("d-m-Y", strtotime($row['inv_date'])); //date('Y-m-d', strtotime("+30 days"));
//                $Datacap->invexpirydate = date("d-m-Y", strtotime("+30 days", strtotime($Datacap->invdate)));
                $Datacap->invamt = $row['inv_amt'];
                $Datacap->status = $row['status'];
                $Datacap->pamt = $row['pending_amt'];
                $Datacap->tax = $row['tax'];
                $Datacap->taxamt = $row['tax_amt'];
                $Datacap->paid_amt = $row['paid_amt'];
                $Datacap->tds_amt = $row['tds_amt'];
                $Datacap->upaid = $row['unpaid_amt'];
                if (isset($row['inv_expiry']) && $row['inv_expiry'] != '0000-00-00') {
                    $Datacap->inv_expirydate = date("d-m-Y", strtotime($row['inv_expiry']));
                } else {
                    $Datacap->inv_expirydate = 'NA';
                }
                $Datacap->comment = $row['comment'];
                $Display[] = $Datacap;
            }
        }
        if (count($Display) > 0) {
            $dataString = "exportData(" . $customerno . ")";
            $exportInput = 'Export To Excel ' . ' <input type="button" title="Export Raw Data" id="dataQuery" name="dataQuery" value="" onclick="' . $dataString . '"/>';
        }
        $credit_dg = new objectdatagrid($Display);
        $credit_dg->AddColumn("Sr.No", "invoiceid");
        $credit_dg->AddColumn("Invoice No", "invoiceno");
        $credit_dg->AddColumn("Customer No", "customerno");
        $credit_dg->AddColumn("Client Name", "clientname");
        $credit_dg->AddColumn("Invoice Date", "invdate");
//        $credit_dg->AddColumn("Invoice Exp.Date", "invexpirydate");
        $credit_dg->AddColumn("Invoice Amt", "invamt");
        $credit_dg->AddColumn("Status", "status");
        $credit_dg->AddColumn("Paid Amt", "paid_amt");
        $credit_dg->AddColumn("Tax", "tax");
        $credit_dg->AddColumn("Tax Amt", "taxamt");
        $credit_dg->AddColumn("Pending Amt", "pamt");
        $credit_dg->AddColumn("TDS Amt", "tds_amt");
        $credit_dg->AddColumn("Unpaid Amt", "upaid");
        $credit_dg->AddColumn("Expiry Date", "inv_expirydate");
        $credit_dg->AddColumn("Comment", "comment");
        $credit_dg->AddRightAction("View", "../../images/edit.png", "invoice_edit.php?inid=%d");
        $credit_dg->SetNoDataMessage("No Invoices");
        $credit_dg->AddIdColumn("invoiceid");
    } else {
        $SQL = sprintf("SELECT  customerno
                                ,customercompany 
                        FROM    " . DB_PARENT . ".customer 
                        WHERE   renewal NOT IN (-1,-2) 
                        ORDER BY customerno ASC");
        $db->executeQuery($SQL);
        $resultSet = $db->get_recordSet();
        if (isset($resultSet) && count($resultSet) > 0) {
            $display1 = '';
            $granttotal = 0;
            foreach ($resultSet as $row1) {
                $is_invoice = 0;
                $total = 0;
                $customerno1 = $row1['customerno'];
                $queryCust = sprintf(" AND customerno = %d", $customerno1);


                $SQL = sprintf("SELECT  * 
                        FROM    " . DB_PARENT . ".invoice 
                        WHERE   isdeleted = 0 
                        %s
                        AND     inv_expiry < '%s'
                        AND     status LIKE 'pending'
                        AND     inv_expiry <> '0000-00-00'
                        ORDER BY customerno ASC,inv_expiry DESC", Sanitise::String($queryCust), Sanitise::Date($days));
                $db->executeQuery($SQL);
                $totalpayment_tax = 0;
                if ($db->get_rowCount() > 0) {
                    $sr = 1;
                    $is_invoice++;
                    $totalpayment_tax = 0;
                    $tax_table = '<table width="100%" border="1">
                            <tr><th colspan="13" class="header">Taxed Invoice</th></tr>
                            <tr><th>Sr No</th><th>Invoice No</th><th>Date</th><th>Invoice Amount</th><th>Status</th><th>Paid Amount</th><th>Tax</th><th>Tax Amount</th><th>Pending Amount</th><th>Expiry Date</th><th>Comment</th></tr>';
                    while ($row = $db->get_nextRow()) {
                        if (isset($row['inv_expiry']) && $row['inv_expiry'] != '0000-00-00') {
                            $inv_expirydate = date("d-m-Y", strtotime($row['inv_expiry']));
                        } else {
                            $inv_expirydate = 'NA';
                        }
                        $tax_table.= '  <tr>
                                    <td>' . $sr . '</td>
                                    <td>' . $row['invoiceno'] . '</td>
                                    <td>' . date("d-m-Y", strtotime($row['inv_date'])) . '</td>
                                    <td>' . $row['inv_amt'] . '</td>
                                    <td>' . $row['status'] . '</td>
                                    <td>' . $row['paid_amt'] . '</td>
                                    <td>' . $row['tax'] . '</td>
                                    <td>' . $row['tax_amt'] . '</td>
                                    <td>' . $row['pending_amt'] . '</td>
                                    <td>' . $inv_expirydate . '</td>
                                    <td>' . $row['comment'] . '</td>
                                </tr>';
                        $total+=$row['pending_amt'];
                    }
                    $tax_table.= '</table>';
                }

                $SQL = sprintf("SELECT  * 
                        FROM    " . DB_PARENT . ".proforma_invoice 
                        WHERE   isdeleted = 0 
                        %s 
                        AND     status LIKE 'pending'
                        AND     payment_due_date < '%s'
                        AND     payment_due_date <> '0000-00-00'
                        ORDER BY customerno ASC,payment_due_date DESC", Sanitise::String($queryCust), Sanitise::Date($days));
                $db->executeQuery($SQL);
                $totalpayment_tax = 0;
                if ($db->get_rowCount() > 0) {
                    $sr = 1;
                    $is_invoice++;
                    $totalpayment_tax = 0;
                    $proforma_table = '<br><table width="100%" border="1">
                            <tr><th colspan="13" class="header">Proforma Invoice</th></tr>
                            <tr><th>Sr No</th><th>Invoice No</th><th>Date</th><th>Invoice Amount</th><th>Status</th><th>Paid Amount</th><th>Tax</th><th>Tax Amount</th><th>Pending Amount</th><th>Expiry Date</th><th>Comment</th></tr>';
                    while ($row = $db->get_nextRow()) {
                        if (isset($row['inv_expiry']) && $row['inv_expiry'] != '0000-00-00') {
                            $inv_expirydate = date("d-m-Y", strtotime($row['inv_expiry']));
                        } else {
                            $inv_expirydate = 'NA';
                        }
                        $proforma_table.= '  <tr>
                                    <td>' . $sr . '</td>
                                    <td>' . $row['invoiceno'] . '</td>
                                    <td>' . date("d-m-Y", strtotime($row['inv_date'])) . '</td>
                                    <td>' . $row['inv_amt'] . '</td>
                                    <td>' . $row['status'] . '</td>
                                    <td>' . $row['paid_amt'] . '</td>
                                    <td>' . $row['tax'] . '</td>
                                    <td>' . $row['tax_amt'] . '</td>
                                    <td>' . $row['pending_amt'] . '</td>
                                    <td>' . $inv_expirydate . '</td>
                                    <td>' . $row['comment'] . '</td>
                                </tr>';
                        $total+=$row['pending_amt'];
                    }
                    $proforma_table.= '</table>';
                }

                $SQL = sprintf("SELECT  * 
                        FROM    " . DB_PARENT . ".credit_note 
                        WHERE   isdeleted = 0 
                        %s
                        AND     inv_expiry < '%s'
                        AND     inv_expiry <> '0000-00-00'
                        ORDER BY customerno ASC,invoiceid DESC", Sanitise::String($queryCust), Sanitise::Date($days));
                $db->executeQuery($SQL);
                $totalpayment_tax = 0;
                if ($db->get_rowCount() > 0) {
                    $sr = 1;
                    $is_invoice++;
                    $totalpayment_tax = 0;
                    $credit_Table = '<br><table width="100%" border="1">
                            <tr><th colspan="13" class="header">Credit Note</th></tr>
                            <tr><th>Sr No</th><th>Invoice No</th><th>Date</th><th>Invoice Amount</th><th>Status</th><th>Paid Amount</th><th>Tax</th><th>Tax Amount</th><th>Pending Amount</th><th>Expiry Date</th><th>Comment</th></tr>';
                    while ($row = $db->get_nextRow()) {
                        if (isset($row['inv_expiry']) && $row['inv_expiry'] != '0000-00-00') {
                            $inv_expirydate = date("d-m-Y", strtotime($row['inv_expiry']));
                        } else {
                            $inv_expirydate = 'NA';
                        }
                        $credit_Table.= '  <tr>
                                    <td>' . $sr . '</td>
                                    <td>' . $row['invoiceno'] . '</td>
                                    <td>' . date("d-m-Y", strtotime($row['inv_date'])) . '</td>
                                    <td>' . $row['inv_amt'] . '</td>
                                    <td>' . $row['status'] . '</td>
                                    <td>' . $row['paid_amt'] . '</td>
                                    <td>' . $row['tax'] . '</td>
                                    <td>' . $row['tax_amt'] . '</td>
                                    <td>' . $row['pending_amt'] . '</td>
                                    <td>' . $inv_expirydate . '</td>
                                    <td>' . $row['comment'] . '</td>
                                </tr>';
                    }
                    $credit_Table.= '</table>';
                }

                if ($is_invoice > 0) {
                    $data_Table = '<br>';
                    $data_Table.= '<div class="panel">';
                    $data_Table.= '<div class="paneltitle" align="center">' . $row1['customercompany'] . ' (' . $customerno1 . ') <span style="float:right;">Pending Collection - Rs. ' . $total . ' /-</span></div>';
                    $data_Table.= '<div class="panelcontents">';
                    $display1.=$data_Table;
                    $display1.=$tax_table;
                    $display1.=$proforma_table;
                    $display1.=$credit_Table;
                    $display1.= '</div>';
                    $display1.= '</div>';
                }
                $granttotal +=$total;
            }
            $display2 = '<br><br><div style="text-align:right;margin-right:200px;font-weight:bold;">Invoice Due Date : '.date('d-m-Y',strtotime($days)).'<br><br>Total Pending Collection : Rs. <span style="color:red;">' . $granttotal . '</span> /-</div>';
            $display2.=$display1;
        }
    }
}
?>
<div class="panel">
    <div class="paneltitle" align="center">Search Pending Payment</div>
    <div class="panelcontents">
        <form method="post" id="frmPaymentDue" action="paymentDue.php" onsubmit="return frmValidate();">
            <table>
                <tr>
                    <td>Customer </td>
                    <td>
                        <input  type="text" name="customername" id="customername" size="20" value="<?php
                        if (isset($customername)) {
                            echo $customername;
                        }
                        ?>" autocomplete="off" placeholder="Enter Customer Name"  onkeypress="getCustomer();"/>
                        <input type="hidden" id="customerno" name="customerno" value="<?php
                        if (isset($customerno)) {
                            echo $customerno;
                        }
                        ?>"/>
                    </td>

                </tr>
                <tr>
                    <td>Payment Not Received Since </td>
                    <td>
                        <input name="days" id="days" type="text" size="2" value="<?php if(isset($hist_days)) echo $hist_days; else echo 45; ?>"/> days
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit" name="searchDue" value="Search"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<br/>
<br/>
<?php
if (!empty($_POST['customerno'])) {
    if (isset($tax_dg)) {

        echo '<div class="panel">';
        echo '<div class="paneltitle" align="center">Taxed Invoices<span style="float: right;">Pending Collection : Rs ' . $totalpayment_tax . ' /- </span></div>';
        echo '<div class="panelcontents">';


        $tax_dg->Render();

        echo '</div>';
        echo '</div>';
        echo '<br/>';
    }
    if (isset($proforma_dg)) {
        echo '<div class="panel">';
        echo '<div class="paneltitle" align="center">Proforma Invoices<span style="float: right;">Pending Collection : Rs ' . $totalpayment_prof . ' /- </span></div>';
        echo '<div class="panelcontents">';
        $proforma_dg->Render();
        echo '</div>';
        echo '</div>';
        echo '<br/>';
    }

    if (isset($credit_dg)) {
        echo '<div class="panel">';
        echo '<div class="paneltitle" align="center">Credit Notes</div>';
        echo '<div class="panelcontents">';
        $credit_dg->Render();
        echo '</div>';
        echo '</div>';
        echo '<br/>';
    }
} else {
    echo $display2;
}

include "footer.php";
?>
<script type="text/javascript">

    function frmValidate() {
        var days = jQuery("#days").val();
        if (days != '') {
            jQuery("#frmPaymentDue").submit();
        } else {
            alert("Enter days");
            return false;
        }
    }
    function getCustomer() {
        jQuery("#customername").autocomplete({
            source: "route_ajax.php?customername=getcustomer",
            select: function (event, ui) {
                jQuery(this).val(ui.item.value);
                jQuery('#customerno').val(ui.item.cid);
//                return false;
            }
        });
    }
</script>
<style>
    .header{
        background: black;
        color: white;
        font-size: 17px;
        padding: 5px;
    }
    td,tr{
        text-align: left;
    }
    #dataQuery{
        background: url(../../images/xls.gif);
        /*border: 1px solid black;*/

        height: 33px;
        width: 33px;
    }
</style>

