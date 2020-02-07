<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/autoload.php");

class unit {
    
}

//Datagtrid
$db = new DatabaseManager();
$id = GetSafeValueString(isset($_GET["lid"]) ? $_GET["lid"] : $_POST["lid"], "long");

$customernos = Array();
$SQL_cust = sprintf("SELECT DISTINCT customerno FROM invoice
                WHERE ledgerid = %d", $id);
$db->executeQuery($SQL_cust);

if ($db->get_rowCount() > 0) {
  while ($row = $db->get_nextRow()) {
   $customerno = new stdClass();
   $customerno->customerno = $row['customerno'];
    $customernos[] = $customerno;
  }
}
include("header.php");


//      Proforma Invoice
$SQL = sprintf("SELECT  `invoiceno` 
                        ,`inv_date`
                        ,`inv_amt`
                        ,`tax`
                        ,`tax_amt`
                        ,`start_date`
                        ,`end_date`
                        ,`payment_due_date`
                        ,`product`.`name`                                                
                        ,`is_taxed`                                                                        
                FROM    `proforma_invoice`
                INNER JOIN product ON proforma_invoice.product_id = product.id                
                
                WHERE   isdeleted = 0 
                AND     ledgerid = %d ORDER BY inv_date DESC", $id);

$db->executeQuery($SQL);
$history = Array();
if ($db->get_rowCount() > 0) {
    $x = 1;
    while ($row = $db->get_nextRow()) {
        $device = new unit();
        $device->srno = $x;
        $device->ledgerid = $row['ledgerid'];
        $device->invoiceno = $row["invoiceno"];
        $device->inv_date = date("d-m-Y", strtotime($row["inv_date"]));
        $device->inv_amt = $row["inv_amt"];
        $is_taxed = $row["is_taxed"];
        if ($is_taxed == 1) {
            $device->is_taxed = "Taxed";
        }
        elseif ($is_taxed == 0) {
            $device->is_taxed = "Not Taxed";
        }
        if ($row["tax"] == 1) {
            $device->tax = 'ST';
        }
        elseif ($row['tax'] == 2) {
            $device->tax = 'VAT';
        }
        elseif ($tax['tax'] == 3) {
            $device->tax = 'CST';
        }
        $device->tax_amt = $row["tax_amt"];
        if ($row["start_date"] != '0000-00-00' && $row["start_date"] != NULL) {
            $device->start_date = date("d-m-Y", strtotime($row["start_date"]));
        }
        else {
            $device->start_date = 'NA';
        }
        if ($row["end_date"] != '0000-00-00' && $row["end_date"] != NULL) {
            $device->end_date = date("d-m-Y", strtotime($row["end_date"]));
        }
        else {
            $device->end_date = 'NA';
        }
        if ($row["payment_due_date"] != '0000-00-00' && $row["payment_due_date"] != NULL) {
            $device->payment_due_date = date("d-m-Y", strtotime($row["payment_due_date"]));
        }
        else {
            $device->payment_due_date = 'NA';
        }
        $device->productname = $row['name'];
        $proforma[] = $device;
        $x++;
    }
}

$dg = new objectdatagrid($proforma);
$dg->AddColumn("Sr No", "srno");
$dg->AddColumn("Invoice No", "invoiceno");
$dg->AddColumn("Product", "productname");
$dg->AddColumn("Invoice Date", "inv_date");
$dg->AddColumn("Amount", "inv_amt");
$dg->AddColumn("Tax", "tax");
$dg->AddColumn("Is Taxed?", "is_taxed");
$dg->AddColumn("Tax Amount", "tax_amt");
$dg->AddColumn("Start Date", "start_date");
$dg->AddColumn("End Date", "end_date");
$dg->AddColumn("Payment Due Date", "payment_due_date");
$dg->SetNoDataMessage("No Data Available");
$dg->AddIdColumn("ledgerid");

$SQL = sprintf("SELECT ledgername FROM " . DB_PARENT . ".ledger WHERE ledgerid = %d", $id);

$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $ledger = $row["ledgername"];
    }
}
?>
<br>
<div class="panel">
    <div class="paneltitle" align="center">Proforma Invoices</div>
    <div class="panelcontents">
        <?php $dg->Render(); ?>
    </div>

</div>
<br>
<?php
//      Taxed Invoice
$SQL = sprintf("SELECT  `invoiceid`
                        ,`invoiceno` 
                        ,`inv_date`
                        ,`inv_amt`
                        ,`status`
                        ,`tax`
                        ,`tax_amt`
                        ,`start_date`
                        ,`end_date`
                        ,`paymentdate`
                        ,`pending_amt`                        
                        ,`inv_expiry`                        
                        ,`product`.`name` 
                        , invoice.customerno
                FROM    `invoice`
                INNER JOIN product ON invoice.product_id = product.id                
                WHERE   isdeleted = 0 
                AND     ledgerid = %d 
                ORDER BY inv_date DESC", $id);
$db->executeQuery($SQL);
$customerno = "";
$elixirUserkey = "";
if ($db->get_rowCount() > 0) {
    $x = 1;
    while ($row1 = $db->get_nextRow()) {
        $invoice = new stdClass();
        $invoice->srno = $x;
        $invoice->ledgerid = $row1['ledgerid'];
        if ($customerno == "") {
            $invoice->customerno = $row1['customerno'];
            $customerno = $row1['customerno'];
        }
        $invoice->invoiceid = $row1["invoiceid"];
        $invoice->invoiceno = $row1["invoiceno"];
        $invoice->inv_date = date("d-m-Y", strtotime($row1["inv_date"]));
        $invoice->pending_amt = $row1["pending_amt"];
        $invoice->inv_amt = $row1["inv_amt"];
        $invoice->status = $row1["status"];
        if ($row1["tax"] == 1) {
            $invoice->tax = 'ST';
        }
        elseif ($row1['tax'] == 2) {
            $invoice->tax = 'VAT';
        }
        elseif ($tax['tax'] == 3) {
            $invoice->tax = 'CST';
        }
        $invoice->tax_amt = $row1["tax_amt"];
        if ($row1["start_date"] != '0000-00-00' && $row1["start_date"] != NULL) {
            $invoice->start_date = date("d-m-Y", strtotime($row1["start_date"]));
        }
        else {
            $invoice->start_date = 'NA';
        }
        if ($row1["end_date"] != '0000-00-00' && $row1["end_date"] != NULL) {
            $invoice->end_date = date("d-m-Y", strtotime($row1["end_date"]));
        }
        else {
            $invoice->end_date = 'NA';
        }
        if ($row1["inv_expiry"] != '0000-00-00' && $row1["inv_expiry"] != NULL) {
            $invoice->paymentduedate = date("d-m-Y", strtotime($row1["inv_expiry"]));
        }
        else {
            $invoice->paymentduedate = 'NA';
        }
        $invoice->productname = $row1['name'];
        $taxed[] = $invoice;

        $x++;
    }

}

$objUserManager = new UserManager();
$userDetails = $objUserManager->getAllElixir($customerno);
if (isset($userDetails) && $userDetails->userkey != "") {
    $elixirUserkey = $userDetails->userkey;
}

$dg = new objectdatagrid($taxed);
$dg->AddAction("Edit", "../../images/edit.png", "invoice_edit.php?lid=" . $id . "&from=payment&inid=%d");
$dg->AddColumn("Sr No", "srno");
$dg->AddColumn("Invoice No", "invoiceno");
$dg->AddColumn("Product", "productname");
$dg->AddColumn("Invoice Date", "inv_date");
$dg->AddColumn("Total Amount", "inv_amt");
$dg->AddColumn("Status", "status");
$dg->AddColumn("Pending Amount", "pending_amt");
$dg->AddColumn("Tax", "tax");
$dg->AddColumn("Tax Amount", "tax_amt");
$dg->AddColumn("Start Date", "start_date");
$dg->AddColumn("End Date", "end_date");
$dg->AddColumn("Payment Due Date", "paymentduedate");
$dg->AddRightAction("InvoicePDF", "../../images/pdf_icon.png", "../../modules/download/report.php?q=invoice-pdf-" . $customerno . "-" . $elixirUserkey . "-0&invoiceid=%d&userkey=".$elixirUserkey);


$dg->SetNoDataMessage("No Data Available");
$dg->AddIdColumn("invoiceid");
?>
<div class="panel">
    <div class="paneltitle" align="center">Taxed Invoices</div>
    <div class="panelcontents">
        <?php $dg->Render(); ?>
    </div>
</div>
<br>
<?php
//      Cash Memo
$SQL = sprintf("SELECT  `cash_memo_no` 
                        ,`cm_date`
                        ,`cm_amount`
                        ,`paymentdate`
                        , `product`.`name`                        
                        , `start_date`                        
                        , `end_date`     
                        , `status`                             
                FROM    `cash_memo`
                INNER JOIN product ON cash_memo.product_id = product.id
                WHERE   isdeleted = 0 
                AND     ledgerid = %d ORDER BY cm_date DESC", $id);
$db->executeQuery($SQL);

if ($db->get_rowCount() > 0) {
    $x = 1;
    while ($row1 = $db->get_nextRow()) {
        $invoice = new stdClass();
        $invoice->srno = $x;
        $invoice->ledgerid = $row1['ledgerid'];
        $invoice->start_date = date("d-m-Y", strtotime($row1['start_date']));
        $invoice->end_date = date("d-m-Y", strtotime($row1['end_date']));
        $invoice->productname = $row1['name'];
        $status = $row1['status'];
        if ($status == 1) {
            $invoice->status = "Pending";
        }
        elseif ($status == 2) {
            $invoice->status = "Paid";
        }
        $invoice->invoiceno = $row1["cash_memo_no"];
        if ($row1["cm_date"] != '0000-00-00') {
            $invoice->inv_date = date("d-m-Y", strtotime($row1["cm_date"]));
        }
        else {
            $invoice->inv_date = 'NA';
        }
        $invoice->inv_amt = $row1["cm_amount"];
        if ($row1["paymentdate"] != '0000-00-00' && $row1["paymentdate"] != NULL) {
            $invoice->paymentdate = date("d-m-Y", strtotime($row1["paymentdate"]));
        }
        else {
            $invoice->paymentdate = 'NA';
        }
        $cash_memo[] = $invoice;
        $x++;
    }
}

$dg = new objectdatagrid($cash_memo);
$dg->AddColumn("Sr No", "srno");
$dg->AddColumn("Cash Memo No", "invoiceno");
$dg->AddColumn("Product", "productname");
$dg->AddColumn("Cash Memo Date", "inv_date");
$dg->AddColumn("Amount", "inv_amt");
$dg->AddColumn("Status", "status");
$dg->AddColumn("Start Date", "start_date");
$dg->AddColumn("End Date", "end_date");
$dg->AddColumn("Payment Date", "paymentdate");
$dg->SetNoDataMessage("No Data Available");
$dg->AddIdColumn("ledgerid");
?>
<div class="panel">
    <div class="paneltitle" align="center">Cash Memo List</div>
    <div class="panelcontents">
        <?php $dg->Render(); ?>
    </div>
</div>
<br>
<?php
//      Credit Note
$SQL = sprintf("SELECT  `invoiceno` 
                        ,`inv_date`
                        ,`inv_amt`
                        ,`tax`
                        ,`tax_amt`
                        ,`start_date`
                        ,`end_date`
                        ,`inv_expiry`
                        , `product`.`name`
                FROM    `credit_note`
                INNER JOIN product ON credit_note.product_id = product.id                
                WHERE   isdeleted = 0 
                AND     ledgerid = %d ORDER BY inv_date DESC", $id);
$db->executeQuery($SQL);

if ($db->get_rowCount() > 0) {
    $x = 1;
    while ($row1 = $db->get_nextRow()) {
        $invoice = new stdClass();
        $invoice->srno = $x;
        $invoice->ledgerid = $row1['ledgerid'];
        $invoice->invoiceno = $row1["invoiceno"];
        if ($row1["inv_date"] != '0000-00-00' && $row1["inv_date"] != NULL) {
            $invoice->inv_date = date("d-m-Y", strtotime($row1["inv_date"]));
        }
        else {
            $invoice->inv_date = 'NA';
        }
        $invoice->inv_amt = $row1["inv_amt"];

        if ($row1["start_date"] != '0000-00-00' && $row1["start_date"] != NULL) {
            $invoice->start_date = date("d-m-Y", strtotime($row1["start_date"]));
        }
        else {
            $invoice->start_date = 'NA';
        }

        if ($row1["end_date"] != '0000-00-00' && $row1["end_date"] != NULL) {
            $invoice->end_date = date("d-m-Y", strtotime($row1["end_date"]));
        }
        else {
            $invoice->end_date = 'NA';
        }

        if ($row1["inv_expiry"] != '0000-00-00' && $row1["inv_expiry"] != NULL) {
            $invoice->inv_expiry = date("d-m-Y", strtotime($row1["inv_expiry"]));
        }
        else {
            $invoice->inv_expiry = 'NA';
        }
        $invoice->productname = $row1['name'];
        $credit_note[] = $invoice;
        $x++;
    }
}

$dg = new objectdatagrid($credit_note);
$dg->AddColumn("Sr No", "srno");
$dg->AddColumn("Credit Note No.", "invoiceno");
$dg->AddColumn("Product", "productname");
$dg->AddColumn("Date", "inv_date");
$dg->AddColumn("Amount", "inv_amt");
$dg->AddColumn("Start Date", "start_date");
$dg->AddColumn("End Date", "end_date");
$dg->AddColumn("Expiry Date", "inv_expiry");
$dg->SetNoDataMessage("No Data Available");
$dg->AddIdColumn("ledgerid");
?>
<div class="panel">
    <div class="paneltitle" align="center">Credit Note List</div>
    <div class="panelcontents">
        <?php $dg->Render(); ?>
    </div>
</div>

<br>
<?php
include("footer.php");
?>

