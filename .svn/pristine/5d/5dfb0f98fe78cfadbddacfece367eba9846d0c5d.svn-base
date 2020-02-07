<?php
//error_reporting(E_ALL);

include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/Date.php");

class InvoiceEdit {
    
}

$db = new DatabaseManager();
$invoiceid = $_GET['inid'];
$from = $_GET['from'];
$lid = $_GET['lid'];

if (isset($_POST['editinv'])) {
    $invno = GetSafeValueString($_POST['invno'], "string");
    $invdate = GetSafeValueString(date("Y-m-d", strtotime($_POST['invdate'])), "string");
    $customer = explode('|', $_POST['cid']);
    $customerno = $customer[0];
    $customername = $customer[1];
    $ledgerid = GetSafeValueString($_POST['ledgerid'], "string");
    $invamt = GetSafeValueString($_POST['invamt'], "string");
    $status = GetSafeValueString($_POST['sid'], "string");
    $pending_amt = GetSafeValueString($_POST['pamt'], "string");
    $tax = GetSafeValueString($_POST['tid'], "string");
    $taxamt = GetSafeValueString($_POST['tamt'], "string");
    $cgst = GetSafeValueString($_POST['cgst'], "string");
    $sgst = GetSafeValueString($_POST['sgst'], "string");
    $igst = GetSafeValueString($_POST['igst'], "string");
    $paymode = GetSafeValueString($_POST['pid'], "string");
    $paidamt = GetSafeValueString($_POST['paidamt'], "string");
    $payamt = GetSafeValueString($_POST['payamt'], "string");
    $chequeno = GetSafeValueString($_POST['chequeno'], "string");
    $branch = GetSafeValueString($_POST['branch'], "string");
    $bank = GetSafeValueString($_POST['bank'], "string");
    $paydate = GetSafeValueString(date("Y-m-d", strtotime($_POST['paydate'])), "string");
    $deduct = GetSafeValueString($_POST['deduct'], "string");
    $upaid = GetSafeValueString($_POST['upaid'], "string");
    $finaldate = GetSafeValueString(date("Y-m-d", strtotime($_POST['finaldate'])), "string");
    $t_amt = GetSafeValueString($_POST['tdsamt'], "string");
    $invid = GetSafeValueString($_POST['invid'], "String");
    $product = GetSafeValueString($_POST['product'], "string");

    $SQL = sprintf("UPDATE  " . DB_PARENT . ".invoice 
                    SET     invoiceno= '%s'
                            ,customerno = %d
                            ,ledgerid = %d
                            ,clientname = '%s'
                            ,inv_date = '%s'
                            ,inv_amt = %d
                            ,status = '%s'
                            ,pending_amt = %d
                            ,tax = %d
                            ,tax_amt = %d
                            ,pay_mode = '%s'
                            ,paymentdate = '%s'
                            ,paid_amt = %d
                            ,tds_amt = %d
                            ,unpaid_amt = %d
                            ,product_id = %d 
                            ,cgst = %d
                            ,sgst = %d
                            ,igst = %d
                    WHERE   invoiceid = %d;", Sanitise::String($invno)
            , Sanitise::Long($customerno)
            , Sanitise::Long($ledgerid)
            , Sanitise::String($customername)
            , Sanitise::Date($invdate)
            , Sanitise::Long($invamt)
            , Sanitise::String($status)
            , Sanitise::Long($pending_amt)
            , Sanitise::Long($tax)
            , Sanitise::Long($taxamt)
            , Sanitise::String($paymode)
            , Sanitise::Date($finaldate)
            , Sanitise::Long($paidamt)
            , Sanitise::Long($deduct)
            , Sanitise::Long($upaid)
            , Sanitise::Long($product)
            , Sanitise::Long($cgst)
            , Sanitise::Long($sgst)
            , Sanitise::Long($igst)
            , Sanitise::Long($invid));

    $db->executeQuery($SQL);

    if ($_POST['pid'] != "0") {//don't insert if no payment mode is selected
        $SQLinsert = sprintf("INSERT INTO " . DB_PARENT . ".invoice_payment(invoiceid
                            , payment
                            , chequeno
                            , bank_name
                            , branch
                            , payment_amt
                            , payment_date
                            , tdsamt)
                      VALUES(%d,'%s', '%s', '%s', '%s',%d, '%s',%d)", Sanitise::Long($invid)
                , Sanitise::String($paymode)
                , Sanitise::String($chequeno)
                , Sanitise::String($bank)
                , Sanitise::String($branch)
                , Sanitise::Long($payamt)
                , Sanitise::Date($paydate)
                , Sanitise::Long($t_amt));
        $db->executeQuery($SQLinsert);
    }
    if ($from == 'payment') {
        header("location:ledger_hist.php?lid=" . $lid);
    } else {
        header("location:invoicedata.php");
    }
}

$SQL = sprintf("SELECT  *
                FROM    product");
$db->executeQuery($SQL);

$x = 0;
$products = Array();
if ($db->get_rowCount() > 0) {
    while ($row3 = $db->get_nextRow()) {
        $product1['id'] = $row3['id'];
        $product1['name'] = $row3['name'];
        $products[] = $product1;
    }
}

//----------to fetech payment details--------------------------------------- 

$SQLpay = sprintf(" SELECT  * 
                    FROM    " . DB_PARENT . ".invoice_payment 
                    WHERE   invoiceid = %d AND isdeleted = 0", Sanitise::Long($invoiceid));
$db->executeQuery($SQLpay);
if ($db->get_rowCount() > 0) {
    while ($row1 = $db->get_nextRow()) {
        $Pay = new InvoiceEdit();
        $Pay->paymentid = $row1['payment_id'];
        $Pay->paymode = $row1['payment'];
        $Pay->payment_amt = $row1['payment_amt'];
        $Pay->chno = $row1['chequeno'];
        $Pay->branch = $row1['branch'];
        $Pay->bank = $row1['bank_name'];
        $Pay->t_amt = $row1['tdsamt'];
        if (isset($row1['payment_date']) && $row1['payment_date'] != '0000-00-00') {// to provide empty date if not inserted
            $Pay->paydate = date("d-m-Y", strtotime($row1['payment_date']));
        }
        $Report[] = $Pay;
    }
}

$dg = new objectdatagrid($Report);
$dg->AddColumn("Payment Mode", "paymode");
$dg->AddColumn("Payment Amt", "payment_amt");
$dg->AddColumn("Payment Date", "paydate");
$dg->AddColumn("Cheque No", "chno");
$dg->AddColumn("Bank Name", "bank");
$dg->AddColumn("Branch", "branch");
$dg->AddColumn("TDS Amt", "t_amt");
$dg->AddRightAction("Delete", "../../images/delete.png", "invoicepay_delete.php?pid=%d");
$dg->SetNoDataMessage("No History");
$dg->AddIdColumn("paymentid");
//-----------------------------------sum of payment amt------------------------------

/* $SQLsum= "SELECT SUM(payment_amt) AS psum FROM invoice_payment WHERE invoiceid='$invoiceid'";
  $db->executeQuery($SQLsum);
  $sum = $db->get_nextRow();
  //print_r($sum); */
//----------to fetech data of add invoice data---------------------------------------
$SQL = "SELECT * FROM " . DB_PARENT . ".invoice WHERE invoiceid ='$invoiceid' AND isdeleted=0";
$db->executeQuery($SQL);
$row_invoice = $db->get_nextRow();

//-----------populate customerno list-------
function getcustomer_detail() {
    $db = new DatabaseManager();
    $customernos = Array();
    $SQL = sprintf("SELECT customerno,customername,customercompany FROM " . DB_PARENT . ".customer");
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row2 = $db->get_nextRow()) {
            $customer = new InvoiceEdit();
            $customer->customerno = $row2['customerno'];
            $customer->customername = $row2['customername'];
            $customer->customercompany = $row2['customercompany'];
            $customernos[] = $customer;
        }
        return $customernos;
    }
    return false;
}

function getledger_detail($customerno) {
    $db = new DatabaseManager();
    $customernos = Array();
    $SQL = sprintf("SELECT  lc.ledgerid
                            ,l.ledgername 
                    FROM    " . DB_PARENT . ".ledger_cust_mapping lc
                    INNER JOIN ledger l ON l.ledgerid = lc.ledgerid
                    WHERE lc.customerno = %d AND lc.isdeleted = 0;", Sanitise::Long($customerno));
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row2 = $db->get_nextRow()) {
            $ledger = new stdClass();
            $ledger->ledgerid = $row2['ledgerid'];
            $ledger->ledgername = $row2['ledgername'];
            $ledgers[] = $ledger;
        }
        return $ledgers;
    }
    return false;
}

//$_scripts[] = "../../scripts/trash/prototype.js";
include("header.php");
?>
<div class="panel">
    <div class="paneltitle" align="center">Edit Invoice Data</div> 
    <div class="panelcontents">
        <form method="post" name="editinvoice" id="editinvoice" onsubmit="ValidateForm();
                return false;">
            <div style="height:20px;">
                <span id="general_success" style="display:none; color: #00FF00">Updated Data Successfully.</span> 
                <span id="invno_error" style="display:none; color: #FF0000">Please Enter Invoice No.</span> 
                <span id="invdate_error" style="display:none; color: #FF0000">Please Enter Invoice Date.</span> 
                <span id="cid_error" style="display:none; color: #FF0000">Please Select Customer No.</span> 
                <span id="invamt_error" style="display:none; color: #FF0000">Please Enter Invoice Amt.</span> 
                <span id="sid_error" style="display:none; color: #FF0000">Please Select Status.</span>           
                <span id="pamt_error" style="display:none; color: #FF0000">Please Enter Pending Amt.</span> 
                <span id="tid_error" style="display:none; color: #FF0000">Please Select Tax.</span> 
                <span id="tamt_error" style="display:none; color: #FF0000">Please Enter Tax Amt.</span>
               <!-- <span id="pid_error" style="display:none; color: #FF0000">Please Select Payment Mode.</span>-->
                <span id="chequeno_error" style="display:none; color: #FF0000">Please Enter Cheque No.</span>
                <span id="payamt_error" style="display:none; color: #FF0000">Please Enter Payment Amt.</span>
                <span id="branch_error" style="display:none; color: #FF0000">Please Enter Branch.</span>
                <span id="paydate_error" style="display:none; color: #FF0000">Please Enter Payment Date.</span>
                <span id="deduct_error" style="display:none; color: #FF0000">Please Enter TDS.</span>
                <span id="paidamt_error" style="display:none; color: #FF0000">Please Enter Paid Amt.</span>
                <span id="amt_error" style="display:none; color: #FF0000">Pending Amount cannot be greater than Amount or Pending amt cannot be negative</span>
                <span id="status_error" style="display:none; color: #FF0000">Status Cannot be Paid.</span>
                <!--<span id="upaid_error" style="display:none; color: #FF0000">Please Enter Unpaid.</span>-->
            </div>        
            <table width="80%">

                <tr><td>Invoice Number</td>
                    <td>
                        <input type="text" name ="invno" id ="invno" value ="<?php
                        if (isset($row_invoice['invoiceno'])) {
                            echo $row_invoice['invoiceno'];
                        }
                        ?>">
                    </td>           
                </tr>
                <tr><td>Invoice Date</td>
                    <td>
                        <input type="text" name ="invdate" id ="invdate" placeholder="dd-mm-yyyy" value ="<?php
                        if (isset($row_invoice['inv_date'])) {
                            echo date("d-m-Y", strtotime($row_invoice['inv_date']));
                        }
                        ?>"><button id="trigger1">...</button>
                    </td>           
                </tr>
                <tr><td>Client</td>
                    <td>    
                        <select name="cid" id="cid" style="width:200px;">
                            <option value="0">Select Client</option>
                            <?php
                            $cms = getcustomer_detail();
                            foreach ($cms as $customer) {
                                ?> 
                                <option <?php
                                if ($row_invoice['customerno'] == $customer->customerno) {
                                    echo "selected";
                                }
                                ?> value="<?php echo($customer->customerno); ?>|<?php echo $customer->customercompany ?>" >
                                    <?php echo $customer->customerno; ?> - <?php echo $customer->customercompany ?>
                                </option>
                                <?php
                            }
                            ?> 

                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Ledger</td>
                    <td>

                        <select name="ledgerid" id="ledgerid" style="width:200px;">
                            <option value="0">Select Client</option>
                            <?php
                            $led = getledger_detail($row_invoice['customerno']);
                            foreach ($led as $data) {
                                ?> 
                                <option <?php
                                if ($row_invoice['ledgerid'] == $data->ledgerid) {
                                    echo "selected";
                                }
                                ?> value="<?php echo($data->ledgerid); ?>" >
                                        <?php echo $data->ledgername; ?>
                                </option>
                                <?php
                            }
                            ?> 

                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Product </td> 
                    <td>
                        <select id="product" name="product"/>
                <option value="0">Choose product</option>
                <?php
                if (!empty($products)) {
                    foreach ($products as $data) {
                        if ($row_invoice['product_id'] == $data['id']) {
                            echo '<option value="' . $data['id'] . '" selected>' . $data['name'] . '</option>';
                        } else {
                            echo '<option value="' . $data['id'] . '" >' . $data['name'] . '</option>';
                        }
                    }
                }
                ?>
                </select>
                </td> 
                </tr>

                <tr><td>Amount</td>
                    <td>
                        <input type="text" name ="invamt" id ="invamt" value ="<?php
                        if (isset($row_invoice['inv_amt'])) {
                            echo $row_invoice['inv_amt'];
                        }
                        ?>">
                    </td>           
                </tr>

                <tr><td>Tax</td>
                    <td>    
                        <select name ="tid" id="tid" >
                            <option value ="0">Select Tax</option>
                            <option value ="1" <?php
                            if ($row_invoice['tax'] == '1') {
                                echo "selected";
                            }
                            ?> >ST</option>
                            <option value ="2" <?php
                            if ($row_invoice['tax'] == '2') {
                                echo "selected";
                            }
                            ?> >VAT</option>
                            <option value ="3" <?php
                            if ($row_invoice['tax'] == '3') {
                                echo "selected";
                            }
                            ?> >CST</option>
                            <option value ="4" <?php
                            if ($row_invoice['tax'] == '4') {
                                echo "selected";
                            }
                            ?> >GST</option>
                        </select>
                    </td>
                </tr>
                <?php
                if ($row_invoice['tax'] == 4) {
                    echo '<tr><td>CGST Amount</td>
                                    <td><input type="text" name ="cgst" id ="cgst" value="' . $row_invoice['cgst'] . '"></td></tr>';
                    echo '<tr><td>SGST Amount</td>
                                    <td><input type="text" name ="sgst" id ="sgst" value="' . $row_invoice['sgst'] . '"></td></tr>';
                    echo '<tr><td>IGST Amount</td>
                                    <td><input type="text" name ="igst" id ="igst" value="' . $row_invoice['igst'] . '">
                                    <input type="hidden" name ="tamt" id ="tamt" value="0"></td></tr>';
                } else {
                    echo '<tr><td>Tax Amount</td>
                                    <td><input type="text" name ="tamt" id ="tamt" value="' . $row_invoice['tax_amt'] . '">
                                    <input type="hidden" name ="cgst" id ="cgst" value="0">
                                    <input type="hidden" name ="sgst" id ="sgst" value="0">
                                    <input type="hidden" name ="igst" id ="igst" value="0"></td></tr>';
                }
                ?>

                <tr><td>Payment Mode</td>
                    <td>
                        <select name="pid" id="pid" onchange="getmode();" >
                            <option value ="0">Select Mode</option>
                            <option value="cash" <?php
                            if ($row_invoice['payment'] == 'cash') {
                                echo "selected";
                            }
                            ?> >Cash</option>
                            <option value="cheque" <?php
                            if ($row_invoice['payment'] == 'cheque') {
                                echo "selected";
                            }
                            ?> >Cheque</option>
                            <option value="online" <?php
                            if ($row_invoice['payment'] == 'online') {
                                echo "selected";
                            }
                            ?> >Online</option>
                        </select>
                    </td>
                </tr>

                <tr id="mode"  <?php
                if ($row_invoice['payment'] != 'cheque') {
                    echo 'style="display:none;"';
                }
                ?> ><td>Cheque No</td>
                    <td><input type="text" id="chequeno" name="chequeno" value=""/>
                        Bank Name
                        <input type="text" id="bank" name="bank" value=""/>
                        Branch
                        <input type="text" id="branch" name="branch" value=""/></td>
                </tr>

                <tr id="allmode"  <?php
                if ($row_invoice['payment'] != '0') {
                    echo 'style="display:none;"';
                }
                ?> ><td>Payment Amount</td>
                    <td><input type="text" id="payamt" name="payamt" value="" onblur="getPayamt();"/>
                        Payment Date 
                        <input type="text" id="paydate" name="paydate" value="<?php echo date('d-m-Y'); ?>" onchange="getPaydate();"/><button id="trigger2">...</button>
                        TDS Amount
                        <input type="text" id="tdsamt" name="tdsamt" value="" onblur="getTdsamt();"/></td>
                </tr>

                <tr><td>Paid Amount</td>
                    <td><input type="text" name ="paidamt" id ="paidamt" value="<?php
                        if (isset($row_invoice['paid_amt']) && $row_invoice['paid_amt'] != '') {
                            echo $row_invoice['paid_amt'];
                        } else {
                            echo "0";
                        }
                        ?>"/></td>

                </tr>
                <tr><td>Pending Amount</td>
                    <td><input type="text" name ="pamt" id ="pamt" value="<?php
                        if (isset($row_invoice['pending_amt'])) {
                            echo $row_invoice['pending_amt'];
                        }
                        ?>"></td>
                <input type="hidden" name ="pamt1" id ="pamt1" value="<?php
                if (isset($row_invoice['pending_amt'])) {
                    echo $row_invoice['pending_amt'];
                }
                ?>">
                </tr>
                <tr><td>Payment Date</td>
                    <td><input type="text" name ="finaldate" id ="finaldate" placeholder="dd-mm-yyyy" value="<?php
                        if (isset($row_invoice['paymentdate']) && $row_invoice['paymentdate'] != '0000-00-00' && $row_invoice['paymentdate'] != '1970-01-01') {
                            echo date("d-m-Y", strtotime($row_invoice['paymentdate']));
                        } else {
                            echo date('d-m-Y');
                        }
                        ?>"><button id="trigger3">...</button></td>
                </tr>
                <tr><td>Total TDS Deductions</td>
                    <td><input type="text" name ="deduct" id ="deduct" value="<?php
                        if (isset($row_invoice['tds_amt']) && $row_invoice['tds_amt'] != '') {
                            echo $row_invoice['tds_amt'];
                        } else {
                            echo "0";
                        }
                        ?>"></td>
                </tr>
                <tr><td>Unpaid Amount</td>
                    <td><input type="text" name ="upaid" id ="upaid" value="<?php
                        if (isset($row_invoice['unpaid_amt'])) {
                            echo $row_invoice['unpaid_amt'];
                        }
                        ?>" onkeyup="getPamt();"/></td>
                </tr>
                <tr><td>Status</td>
                    <td>    
                        <select name ="sid" id="sid">
                            <option value="Pending" <?php
                            if ($row_invoice['status'] == 'Pending') {
                                echo "selected";
                            }
                            ?> >Pending</option>
                            <option value="Paid" <?php
                            if ($row_invoice['status'] == 'Paid') {
                                echo "selected";
                            }
                            ?> >Paid</option>

                        </select>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="invid" value="<?php echo $invoiceid ?>">           
            <input type="submit" id="editinv" name="editinv" class="btn btn-default" value="Edit Invoice">
        </form>
    </div>
</div>

<br/>
<!----------------history list---------------------------------------------->
<div class="panel">
    <div class="paneltitle" align="center">Payment History</div>
    <div class="panelcontents">
        <?php $dg->Render(); ?>
    </div>

</div>
<br/>
<?php
include("footer.php");
?>
<script>
    $(document).ready(function () {

        Calendar.setup(
                {
                    inputField: "invdate", // ID of the input field
                    ifFormat: "%d-%m-%Y", // the date format

                    button: "trigger1" // ID of the button
                });

        Calendar.setup(
                {
                    inputField: "paydate", // ID of the input field
                    ifFormat: "%d-%m-%Y", // the date format;
                    button: "trigger2" // ID of the button
                });
        Calendar.setup(
                {
                    inputField: "finaldate", // ID of the input field
                    ifFormat: "%d-%m-%Y", // the date format;
                    button: "trigger3" // ID of the button
                });
    });

    function getmode() {
        if (jQuery("#pid").val() == 'cheque') {
            jQuery("#mode").show();
            jQuery("#allmode").show();
        } else if (jQuery("#pid").val() == 'cash' || jQuery("#pid").val() == 'online') {
            jQuery("#allmode").show();
            jQuery("#mode").hide();
        }

        else {
            jQuery("#mode").hide();
            jQuery("#allmode").hide();
        }
    }
    function getPayamt() {
        var pay_amt = jQuery("#payamt").val();
        var paid_amt = jQuery("#paidamt").val();
        if (pay_amt != 0) {
            paid_amt = parseInt(paid_amt) + parseInt(pay_amt);
            jQuery("#paidamt").val(paid_amt);

//to get pending amount after payment amt
            var pending_amt = jQuery("#pamt").val();

            pending_amt = parseInt(pending_amt) - parseInt(pay_amt);
            jQuery("#pamt").val(pending_amt);
        }
    }
    function getTdsamt() {

        var deduct_amt = jQuery("#deduct").val();
        var tds_amt = jQuery("#tdsamt").val();
        var pdamt = jQuery("#pamt").val();
        //alert(pdamt);
        if (tds_amt != "" || deduct_amt != "0")
        {
            deduct_amt = parseInt(deduct_amt) + parseInt(tds_amt);
            jQuery("#deduct").val(deduct_amt);
        }
        if (tds_amt != "" && tds_amt != "0")
        {
            pdamt = parseInt(pdamt) - parseInt(tds_amt);
            jQuery("#pamt").val(pdamt);
        }
    }
    function getPaydate() {
        var pay_date = jQuery("#paydate").val();
        jQuery("#finaldate").val(pay_date);
    }

    /*function getPendingamt(){//to get pending amount after paid amt
     var pending_amt =jQuery("#pamt").val();
     var paid_amt =jQuery("#paidamt").val();
     
     pending_amt=parseInt(pending_amt)-parseInt(paid_amt);
     jQuery("#pamt").val(pending_amt);
     
     }*/

    function getPamt() {//to get pending amount after unpaid amt
        //alert("test");
        var pending_amt = jQuery("#pamt1").val();
        var unpaid_amt = jQuery("#upaid").val();
        if (unpaid_amt != "0")
        {
            pending_amt = parseInt(pending_amt) - parseInt(unpaid_amt);
            jQuery("#pamt").val(pending_amt);
        }
    }
    function ValidateForm() {

        var invno_id = jQuery('#invno').val();
        var invdate_id = jQuery('#invdate').val();
        var cid_id = jQuery('#cid').val();
        var invamt_id = jQuery('#invamt').val();
        var sid_id = jQuery('#sid').val();
        var pamt_id = jQuery('#pamt').val();
        var tid_id = jQuery('#tid').val();
        var tamt_id = jQuery('#tamt').val();
        var pid_id = jQuery('#pid').val();
        var chequeno_id = jQuery('#chequeno').val();
        var branch_id = jQuery('#branch').val();
        var paydate_id = jQuery('#paydate').val();
        var payamt_id = jQuery('#payamt').val();
        var deduct_id = jQuery('#deduct').val();
        var paidamt_id = jQuery('#paidamt').val();

        if (invno_id == "")
        {
            jQuery("#invno_error").show();
            jQuery("#invno_error").fadeOut(6000);
        }
        else if (invdate_id == "")
        {
            jQuery("#invdate_error").show();
            jQuery("#invdate_error").fadeOut(6000);
        }
        else if (cid_id == "0")
        {
            jQuery("#cid_error").show();
            jQuery("#cid_error").fadeOut(6000);
        }
        else if (invamt_id == "")
        {
            jQuery("#invamt_error").show();
            jQuery("#invamt_error").fadeOut(6000);
        }
        else if (sid_id == "0")
        {
            jQuery("#sid_error").show();
            jQuery("#sid_error").fadeOut(6000);
        }
        else if (pamt_id == "")
        {
            jQuery("#pamt_error").show();
            jQuery("#pamt_error").fadeOut(6000);
        }
        else if (tid_id == "0")
        {
            jQuery("#tid_error").show();
            jQuery("#tid_error").fadeOut(6000);
        }
        else if (tamt_id == "")
        {
            jQuery("#tamt_error").show();
            jQuery("#tamt_error").fadeOut(6000);
        }
        else if (paidamt_id == "")
        {
            jQuery("#paidamt_error").show();
            jQuery("#paidamt_error").fadeOut(6000);
        }
        /*else if(pid_id== "0")
         {
         jQuery("#pid_error").show();
         jQuery("#pid_error").fadeOut(6000);
         }*/
        else if (pid_id == "cheque" && chequeno_id == "")
        {
            jQuery("#chequeno_error").show();
            jQuery("#chequeno_error").fadeOut(6000);
        }
        else if (pid_id == "cheque" && branch_id == "")
        {
            jQuery("#branch_error").show();
            jQuery("#branch_error").fadeOut(6000);
        }
        else if (pid_id != "0" && payamt_id == "")
        {
            jQuery("#payamt_error").show();
            jQuery("#payamt_error").fadeOut(6000);
        }
        else if (pid_id != "0" && paydate_id == "")
        {
            jQuery("#paydate_error").show();
            jQuery("#paydate_error").fadeOut(6000);
        }
        else if (deduct_id == "")
        {
            jQuery("#deduct_error").show();
            jQuery("#deduct_error").fadeOut(6000);
        }
        else if (parseFloat(pamt_id) > parseFLoat(invamt_id))
        {
            jQuery("#amt_error").show();
            jQuery("#amt_error").fadeOut(6000);
        }
        else if ((invamt_id = paidamt_id + deduct_id) && pamt_id != 0 && sid_id == "Paid")
        {
            jQuery("#status_error").show();
            jQuery("#status_error").fadeOut(6000);
        }
        else {
            jQuery("#editinvoice").submit();
        }
    }

</script>