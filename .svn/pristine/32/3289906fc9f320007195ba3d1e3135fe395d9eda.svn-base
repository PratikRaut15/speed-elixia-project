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

    $Display = array();
    $dg = new objectdatagrid($Display);
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "getInvoices" && isset($_POST['customerno'])) {
        $customername = $_POST['customername'];
        $customerno = $_POST['customerno'];
        $SQL = "SELECT * FROM " . DB_PARENT . ".invoice WHERE customerno = " . $_POST['customerno'] . " AND isdeleted=0 ORDER BY invoiceid DESC";
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $Datacap = new stdClass();
                $Datacap->invoiceid = $row['invoiceid'];
                $Datacap->invoiceno = $row['invoiceno'];
                $Datacap->customerno = $row['customerno'];
                $Datacap->clientname = $row['clientname'];
                $Datacap->invdate = date("d-m-Y", strtotime($row['inv_date']));
                $Datacap->invamt = $row['inv_amt'];
                $Datacap->status = $row['status'];
                $Datacap->pamt = $row['pending_amt'];
                $Datacap->tax = $row['tax'];
                $Datacap->taxamt = $row['tax_amt'];
                $Datacap->paymode = $row['pay_mode'];
                $Datacap->paid_amt = $row['paid_amt'];
                if (isset($row['paymentdate']) && $row['paymentdate'] != '0000-00-00') {
                    $Datacap->paydate = date("d-m-Y", strtotime($row['paymentdate']));
                }
                $Datacap->tds_amt = $row['tds_amt'];
                $Datacap->upaid = $row['unpaid_amt'];
                if (isset($row['inv_expiry']) && $row['inv_expiry'] != '0000-00-00') {
                    $Datacap->inv_expirydate = date("d-m-Y", strtotime($row['inv_expiry']));
                }
                $Datacap->comment = $row['comment'];
                $Display[] = $Datacap;
            }
        }
        $dg = new objectdatagrid($Display);
        $dg->AddColumn("Sr.No", "invoiceid");
        $dg->AddColumn("Invoice No", "invoiceno");
        $dg->AddColumn("Customer No", "customerno");
        $dg->AddColumn("Client Name", "clientname");
        $dg->AddColumn("Invoice Date", "invdate");
        $dg->AddColumn("Invoice Amt", "invamt");
        $dg->AddColumn("Status", "status");
        $dg->AddColumn("Paid Amt", "paid_amt");
        $dg->AddColumn("Tax", "tax");
        $dg->AddColumn("Tax Amt", "taxamt");
        $dg->AddColumn("Payment Mode", "paymode");
        $dg->AddColumn("Pending Amt", "pamt");
        $dg->AddColumn("Payment Date", "paydate");
        $dg->AddColumn("TDS Amt", "tds_amt");
        $dg->AddColumn("Unpaid Amt", "upaid");
        $dg->AddColumn("Expiry Date", "inv_expirydate");
        $dg->AddColumn("Comment", "comment");
        $dg->AddRightAction("View", "../../images/edit.png", "invoice_edit.php?inid=%d");
        $dg->SetNoDataMessage("No Invoices");
        $dg->AddIdColumn("invoiceid");

    } else {
        $dg->SetNoDataMessage("");
    }

?>
<br/>
<div class="panel">
    <div class="paneltitle" align="center">Search Invoices</div>
    <div class="panelcontents">
        <form method="post" id='frmIncvoiceSearch' action="invoicesearch.php?action=getInvoices" >
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
                    <td>
                        <input type="button" name="search" value="Search" onclick='frmValidate();' />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<br/>
<br/>

<div class="panel">
    <div class="paneltitle" align="center">Invoices</div>
    <div class="panelcontents">
        <?php $dg->Render();?>
    </div>
</div>
<br/>

<?php
    include "footer.php";
?>
<script type="text/javascript">
function frmValidate() {
    var customername = jQuery("#customername").val();
    if(customername == '') {
        alert("Please Select Customer");
    } else {
        jQuery("#frmIncvoiceSearch").submit();
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
