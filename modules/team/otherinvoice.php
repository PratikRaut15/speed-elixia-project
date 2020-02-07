<?php
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("session.php");
include_once("../../lib/system/Sanitise.php");

//error_reporting(0);
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

$db = new DatabaseManager();
if (isset($_POST['addinvoice'])) {
    $todaysdate = date('Y-m-d H:i:s');
    $customerno = GetSafeValueString($_POST['customerno'], "string");
    $ledgerid = GetSafeValueString($_POST['ledgerid'], "string");
    $desc = GetSafeValueString($_POST['desc'], "string");
    $product = GetSafeValueString($_POST['product'], "string");
    $amount = GetSafeValueString($_POST['amount'], "string");
    $tax_amount = GetSafeValueString($_POST['tax_amount'], "string");
    $tax_type = GetSafeValueString($_POST['tax_type'], "string");
    $inv_type = GetSafeValueString($_POST['inv_type'], "string");
    $expecteddate = GetSafeValueString($_POST['expecteddate'], "string");
    $remark = GetSafeValueString($_POST['remark'], "string");
    $expecteddate = date('Y-m-d', strtotime($expecteddate));
    $due_on = GetSafeValueString($_POST['duedate'], "string");
    $due_on = date('Y-m-d', strtotime($due_on));
    $expirydate = GetSafeValueString($_POST['expirydate'], "string");
    $expirydate = date('Y-m-d', strtotime($expirydate));

    $SQL = sprintf("INSERT INTO " . DB_PARENT . ".otherinvoices (`customerno` 
                            ,`ledgerid`
                            ,`description` 
                            , `amount` 
                            , `due_on`
                            ,`pay_expected_date`
                            ,`tax_type`
                            ,`inv_type`                            
                            ,`tax_amount`
                            ,`remark`
                            ,`expirydate`
                            ,`created_timestamp`
                            ,`product_id`) 
                    VALUES (%d,%d, '%s', '%s', '%s', '%s',%d,%d,%d,'%s','%s','%s',%d)", Sanitise::Long($customerno)
            , Sanitise::Long($ledgerid)
            , Sanitise::String($desc)
            , Sanitise::Long($amount)
            , Sanitise::Date($due_on)
            , Sanitise::Date($expecteddate)
            , Sanitise::Long($tax_type)
            , Sanitise::Long($inv_type)
            , Sanitise::Long($tax_amount)
            , Sanitise::String($remark)
            , Sanitise::Date($expirydate)
            , Sanitise::DateTime($todaysdate)
            , Sanitise::Long($product));

    $db->executeQuery($SQL);
}
$SQL = sprintf("SELECT  *
                FROM    product");
$db->executeQuery($SQL);

$x = 0;
$products = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $product1['id'] = $row['id'];
        $product1['name'] = $row['name'];
        $products[] = $product1;
    }
}


$SQL = sprintf("SELECT  o.id
                        ,o.customerno
                        ,o.ledgerid
                        ,o.description
                        ,o.amount
                        ,o.due_on
                        ,o.pay_expected_date
                        ,o.tax_type
                        ,o.inv_type                        
                        ,o.tax_amount
                        ,o.remark
                        ,o.created_timestamp
                        ,o.expirydate
                        ,o.product_id
                        ,customer.customercompany 
                FROM    otherinvoices o 
                LEFT JOIN customer ON customer.customerno=o.customerno 
                ORDER BY o.due_on ASC");
$db->executeQuery($SQL);

$x = 0;
$invoices = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $x++;
        $invoice = new stdClass();
        $invoice->id = $row['id'];
        $invoice->customerno = $row['customerno'];
        $invoice->ledgerid = $row['ledgerid'];
        $invoice->description = $row['description'];
        $invoice->amount = $row['amount'];
        $invoice->due_on = date("d-m-Y", strtotime($row['due_on']));
        $invoice->pay_expected_date = date("d-m-Y", strtotime($row['pay_expected_date']));
        $invoice->expirydate = date("d-m-Y", strtotime($row['expirydate']));
        if ($row['tax_type'] == 1) {
            $invoice->tax_type = 'ST';
        } elseif ($row['tax_type'] == 2) {
            $invoice->tax_type = 'VAT';
        }elseif ($row['tax_type'] == 4) {
            $invoice->tax_type = 'GST';
        }
        if ($row['inv_type'] == 1) {
            $invoice->inv_type = 'Taxed Invoice';
        } elseif ($row['inv_type'] == 2) {
            $invoice->inv_type = 'Proforma Invoice';
        } elseif ($row['inv_type'] == 3) {
            $invoice->inv_type = 'Cash Memo';
        } elseif ($row['inv_type'] == 4) {
            $invoice->inv_type = 'Credit Note';
        }
        $invoice->remark = $row['remark'];
        $invoice->tax_amount = $row['tax_amount'];
        $invoice->created_timestamp = $row['created_timestamp'];
        $invoice->customercompany = $row['customercompany'];
        foreach ($products as $data) {
            if ($row['product_id'] == $data['id']) {
                $invoice->product = $data['name'];
            }
        }

        $invoice->x = $x;
        $invoices[] = $invoice;
    }
}
//print_r($invoices); die();
$df = new objectdatagrid($invoices);
//$dg->AddAction("View/Edit", "../../images/edit.png", "modifycustomer.php?cid=%d");
$df->AddColumn("Sr No.", "x");
$df->AddColumn("Customer #", "customerno");
$df->AddColumn("ledgerid #", "ledgerid");
$df->AddColumn("Customer Name", "customercompany");
$df->AddColumn("Description", "description");
$df->AddColumn("Product", "product");
$df->AddColumn("Amount", "amount");
$df->AddColumn("Invoice Type", "inv_type");
$df->AddColumn("Tax", "tax_type");
$df->AddColumn("Tax Amount", "tax_amount");
$df->AddColumn("Invoice Generation Date", "due_on");
$df->AddColumn("Expected Payment Date", "pay_expected_date");
$df->AddColumn("Expiry Date", "expirydate");
$df->AddColumn("Remark", "remark");
$df->AddColumn("Created On", "created_timestamp");
$df->SetNoDataMessage("No invoices available");
$df->AddIdColumn("id");


include("header.php");
?>
<div class="panel">
    <form id="otherInvoiceForm" name="otherInvoiceForm" action="otherinvoice.php" method="post">
        <div class="paneltitle" align="center">Other Invoices</div>
        <div class="panelcontents">
            <table width="60%">
                <tr>
                    <td>
                        <input type="hidden" id="statecode" name="statecode" />
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Enter customer number :</td>
                    <td>
                        <input  type="text" name="customerno" id="customerno" size="25" value="" autocomplete="off" placeholder="Enter Customer No Or Name" onkeyup="getCust()" />
                    </td> 
                </tr>
                <tr>
                    <td>Ledger</td>
                    <td>
                        <input  type="text" name="iledger" id="iledger" size="25" value="<?php if (isset($_POST['iledger'])) { echo $_POST['iledger']; } ?>" autocomplete="off" placeholder="Enter Ledger Name" onkeyup="getLedger()" />
                        <input type="hidden" name="ledgerid" id="ledgerid" value="<?php if (isset($_POST['ledgerid'])) { echo $_POST['ledgerid']; } ?>" />
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Enter Description :</td> 
                    <td>
                        <input id="desc" name="desc" type="text" value="" placeholder="Enter description"/> 
                    </td> 
                </tr>
                <tr>
                    <td>Product :</td> 
                    <td>
                        <select id="product" name="product"/>
                <option value="0">Choose product</option>
                <?php
                if (!empty($products)) {
                    foreach ($products as $data) {
                        echo '<option value="' . $data['id'] . '">' . $data['name'] . '</option>';
                    }
                }
                ?>
                </select>
                </td> 
                </tr>
                <tr>
                    <td>Invoice Type :</td> 
                    <td>
                        <select id="inv_type" name="inv_type">
                            <option value="1">Taxed Invoice</option>
                            <option value="3">Cash Memo</option>
                            <option value="4">Credit Note</option>                            
                        </select>
                    </td> 
                </tr>                
                <tr>
                    <td>Amount :</td> 
                    <td>
                        <input id="amount" name="amount" type="text" value="" placeholder="Enter amount"/> 
                    </td> 
                </tr>

                <tr>
                    <td>Tax :</td> 
                    <td>
                        <select id="tax_type" name="tax_type">
                            <option value="1">ST</option>
                            <option value="2">VAT</option>
                            <option value="4">GST</option>
                        </select>
                    </td> 
                </tr>
                <tr>
                    <td>Tax Amount :</td> 
                    <td>
                        <input id="tax_amount" name="tax_amount" type="text" value="" placeholder="Enter amount"/> 
                    </td> 
                </tr>
                <tr>
                    <td>Invoice Generation Date :</td>
                    <td> 
                        <input name="duedate" id="duedate" type="text" value=""/>
                    </td>
                </tr>
                <tr>
                    <td>Expected Payment Date :</td>
                    <td> 
                        <input name="expecteddate" id="expecteddate" type="text" value=""/>
                    </td>
                </tr>
                <tr>
                    <td>Expiry Date :</td>
                    <td> 
                        <input name="expirydate" id="expirydate" type="text" value=""/>
                    </td>
                </tr>
                <tr>
                    <td>Remark :</td>
                    <td> 
                        <input name="remark" id="remark" type="text" value=""/>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" name="addinvoice" id="addinvoice" value="ADD INVOICE"/></td>
                </tr>
            </table>
        </div>
    </form>
</div>
<br>
<div class="panel">
    <div class="paneltitle" align="center">Invoice List</div>
    <div class="panelcontents">
        <?php $df->Render(); ?>
    </div>

</div>

<script>
    function getCust() {
        jQuery("#customerno").autocomplete({
            source: "route_ajax.php?customername=getcust",
            select: function (event, ui) {

                /*clear selected value */
                jQuery(this).val(ui.item.value);
                jQuery('#customerno').val(ui.item.cid);
                return false;
            }
        });
    }

    function getLedger() {
        var customerno = jQuery('#customerno').val();
        if (customerno != '') {
            jQuery("#iledger").autocomplete({
                source: "route_ajax.php?ledgername=" + customerno,
                select: function (event, ui) {
                    /*clear selected value */
                    jQuery("#iledger").val(ui.item.value);
                    jQuery('#ledgerid').val(ui.item.lid);
                    jQuery('#statecode').val(ui.item.state_code);
                    jQuery("#amount").val('');
                    jQuery("#pamt").val('');
                    jQuery("#tamt").val('');
                    jQuery("#vehicle_list").html('');
                    return false;
                }
            });
        } else {
            alert("Please select correct customer no.");
            jQuery("#icustomer").focus();
        }
    }

    $('#duedate').datepicker({dateFormat: "dd-mm-yy"});
    $('#expecteddate').datepicker({dateFormat: "dd-mm-yy"});
    $('#expirydate').datepicker({dateFormat: "dd-mm-yy"});
</script>

