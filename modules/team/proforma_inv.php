<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");

//include_once("../../lib/system/Date.php");


class Invoice {
    
}

$db = new DatabaseManager();


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


if (isset($_POST['submitinv'])) {
    $invno = GetSafeValueString($_POST['invno'], "string");
    $invdate = GetSafeValueString(date("Y-m-d", strtotime($_POST['invdate'])), "string");
    $customerno = GetSafeValueString($_POST['cno'], "string");
    $customername = GetSafeValueString($_POST['cname'], "string");
    $invamt = GetSafeValueString($_POST['invamt'], "string");
    $status = GetSafeValueString($_POST['sid'], "string");
    $pending_amt = GetSafeValueString($_POST['pamt'], "string");
    $tax = GetSafeValueString($_POST['tid'], "string");
    $taxamt = GetSafeValueString($_POST['tamt'], "string");
    $product = GetSafeValueString($_POST['product'], "string");
    $ledgerid = GetSafeValueString($_POST['ledgerid'], "string");

    $SQL = sprintf("INSERT INTO     " . DB_PARENT . ".proforma_invoice(invoiceno
                            ,customerno
                            ,ledgerid
                            ,clientname
                            ,inv_date
                            ,inv_amt
                            ,status
                            ,pending_amt
                            ,tax
                            ,tax_amt
                            ,product_id
                            ,`is_taxed`)
            VALUES('%s', %d,%d,'%s', '%s', %d, '%s',%d,'%s',%d,%d,0)", Sanitise::String($invno), Sanitise::Long($customerno), Sanitise::Long($ledgerid), Sanitise::String($customername), Sanitise::Date($invdate), Sanitise::Long($invamt), Sanitise::Long($status), Sanitise::Long($pending_amt), Sanitise::String($tax), Sanitise::Long($taxamt), Sanitise::Long($product));

    $db->executeQuery($SQL);

    header("location:proforma_inv.php");
}
//QUERY for display
$x = 0;
$Display = array();
$SQL = "SELECT  *
        FROM    " . DB_PARENT . ".proforma_invoice 
        LEFT OUTER JOIN ledger ON proforma_invoice.ledgerid = ledger.ledgerid 
        WHERE   proforma_invoice.isdeleted = 0 
        ORDER BY proforma_invoice.pi_id DESC";
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $Datacap = new Invoice();
        $x++;
        $Datacap->pi_id = $row['pi_id'];
        $Datacap->ledgername = $row['ledgername'];
        $Datacap->invoiceno = $row['invoiceno'];
        $Datacap->customerno = $row['customerno'];
        $Datacap->invdate = date("d-m-Y", strtotime($row['inv_date']));
        $Datacap->invamt = $row['inv_amt'];
        $Datacap->status = $row['status'];
        $Datacap->pamt = $row['pending_amt'];
        $Datacap->tax = $row['tax'];
        $Datacap->taxamt = $row['tax_amt'];
        if ($row['start_date'] == '0000-00-00') {
            $Datacap->start_date = 'NA';
        } else {
            $Datacap->start_date = date("d-m-Y", strtotime($row['start_date']));
        }
        if ($row['end_date'] == '0000-00-00') {
            $Datacap->end_date = 'NA';
        } else {
            $Datacap->end_date = date("d-m-Y", strtotime($row['end_date']));
        }
        if ($row['payment_due_date'] == '0000-00-00') {
            $Datacap->payment_due_date = 'NA';
        } else {
            $Datacap->payment_due_date = date("d-m-Y", strtotime($row['payment_due_date']));
        }
        if (isset($row['paymentdate']) && $row['paymentdate'] != '0000-00-00') {// to provide empty date if not inserted
            $Datacap->paydate = date("d-m-Y", strtotime($row['paymentdate']));
        }
        $Datacap->tds_amt = $row['tds_amt'];
        $Datacap->upaid = $row['unpaid_amt'];
        if (isset($row['inv_expiry']) && $row['inv_expiry'] != '0000-00-00') {// to provide empty date if not inserted
            $Datacap->inv_expirydate = date("d-m-Y", strtotime($row['inv_expiry']));
        }
        $Datacap->comment = $row['comment'];
        foreach ($products as $data) {
            if ($row['product_id'] == $data['id']) {
                $Datacap->product = $data['name'];
            }
        }
        if ($row['approved'] == 0) {
            $Datacap->approved = '<button onclick="approveProforma(' . $row['pi_id'] . ');">Approve</button>';
        }
        if ($row['is_taxed'] == 1) {
            $checked = 'checked';
        } else {
            $checked = '';
        }
        if ($row['approved'] == '1') {
            $Datacap->generate = '<a href="invoice_pdf.php?gpdf=' . $row['pi_id'] . '&work=Proforma"><img src="../../images/pdf_icon.png"></img></a>';
            $Datacap->toggle = '<input id="istaxed" type="checkbox" onclick="istaxed(this,' . $row['pi_id'] . ');" ' . $checked . '/>';
        }
        $Datacap->x = $x;
        $Display[] = $Datacap;
    }
}
$dg = new objectdatagrid($Display);
$dg->AddColumn("Sr.No", "pi_id");
$dg->AddColumn("Invoice No", "invoiceno");
$dg->AddColumn("Invoice Date", "invdate");
$dg->AddColumn("Customer No", "customerno");
$dg->AddColumn("Ledger Name", "ledgername");

$dg->AddColumn("Product Name", "product");
$dg->AddColumn("Invoice Amt", "invamt");
$dg->AddColumn("Tax", "tax");
$dg->AddColumn("Tax Amt", "taxamt");
$dg->AddColumn("Payment Due Date", "payment_due_date");
$dg->AddColumn("Start Date", "start_date");
$dg->AddColumn("End Date", "end_date");
$dg->AddColumn("Comment", "comment");
if (IsHead()) {
    $dg->AddColumn("Approve", "approved");
}if (IsAdmin() || IsHead()) {
    $dg->AddColumn("Taxed", "toggle");
    $dg->AddColumn("", "generate");
}
$dg->AddRightAction("View", "../../images/edit.png", "proforma_edit.php?inid=%d");

$dg->SetNoDataMessage("No Invoices");
$dg->AddIdColumn("pi_id");


include("header.php");
?> 
<div class="panel">
    <div class="paneltitle" align="center">Add Proforma Invoice Data</div> 
    <div class="panelcontents">
        <form method="post" name="invoiceform" id="invoiceform" onsubmit="ValidateForm();
                return false;" enctype="multipart/form-data">
            <div style="height:20px;">
                <span id="general_success" style="display:none; color: #00FF00">Data Added Successfully.</span> 
                <span id="invno_error" style="display:none; color: #FF0000">Please Enter Proforma Invoice No.</span> 
                <span id="invdate_error" style="display:none; color: #FF0000">Please Enter Proforma Invoice Date.</span> 
                <span id="cid_error" style="display:none; color: #FF0000">Please Select Customer No.</span> 
                <span id="invamt_error" style="display:none; color: #FF0000">Please Enter Proforma Invoice Amt.</span> 
                <span id="sid_error" style="display:none; color: #FF0000">Please Select Status.</span>           
                <span id="pamt_error" style="display:none; color: #FF0000">Please Enter Pending Amt.</span> 
                <span id="tid_error" style="display:none; color: #FF0000">Please Select Tax.</span> 
                <span id="tamt_error" style="display:none; color: #FF0000">Please Enter Tax Amt.</span>
            </div>
            <table width="100%">
                <tr>

                </tr>
                <tr><td>Proforma Invoice Number</td>
                    <td>
                        <input type="text" name ="invno" id ="invno" value ="">
                    </td>           
                </tr>
                <tr><td>Proforma Invoice Date</td>
                    <td>
                        <input type="text" name ="invdate" id ="invdate" placeholder="dd-mm-yyyy" value =""><button id="trigger1">...</button>
                    </td>           
                </tr>
                <tr><td>Client</td>
                    <td>    
                        <input  type="text" name="icustomer" id="icustomer" size="25" value="<?php
                        if (isset($_POST['icustomer'])) {
                            echo $_POST['icustomer'];
                        }
                        ?>" autocomplete="off" placeholder="Enter Customer No Or Name" onkeyup="getCust()" />
                        <input type="hidden" name="cno" id="cno" value="<?php
                        if (isset($_POST['cno'])) {
                            echo $_POST['cno'];
                        }
                        ?>"/>
                        <input type="hidden" name="cname" id="cname" value="<?php
                        if (isset($_POST['cname'])) {
                            echo $_POST['cname'];
                        }
                        ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>Ledger</td>
                    <td>
                        <input  type="text" name="iledger" id="iledger" size="25" value="<?php if (isset($_POST['iledger'])) {
                                   echo $_POST['iledger'];
                               } ?>" autocomplete="off" placeholder="Enter Ledger Name" onkeyup="getLedger()" />
                        <input type="hidden" name="ledgerid" id="ledgerid" value="<?php if (isset($_POST['ledgerid'])) {
                                   echo $_POST['ledgerid'];
                               } ?>" />
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
                <tr><td>Amount</td>
                    <td>
                        <input type="text" name ="invamt" id ="invamt" value ="" onblur="getPendingamt();">
                    </td>           
                </tr>
                <tr><td>Status</td>
                    <td>    
                        <select name ="sid" >
                            <option value ="pending">Pending</option>
                            <option value ="paid">Paid</option>
                        </select>
                    </td>
                </tr>
                <tr><td>Pending Amount</td>
                    <td><input type="text" name ="pamt" id ="pamt" value=""></td>
                </tr>
                <tr><td>Tax</td>
                    <td>    
                        <select name ="tid" id="tid" >
                            <option value ="0">Select Tax</option>
                            <option value ="1">ST</option>
                            <option value ="2">VAT</option>
                            <option value ="3">CST</option>
                        </select>
                    </td>
                </tr>
                <tr><td>Tax Amount</td>
                    <td><input type="text" name ="tamt" id ="tamt" value=""></td>
                </tr>
            </table>
            <input type="submit" id="submitinv" name="submitinv" class="btn btn-default" value="Create Proforma Invoice">
        </form>
    </div>
</div>
<!------------------------------------upload excel---------------------------------------------------->
<!--</br>
<div class="panel">
     <div class="paneltitle" align="center">Upload Invoice File</div> 
     <div class="panelcontents">
         <form name="dataupload" id='uploadPeople' action="" method="post" onsubmit='return validate_upload();' enctype="multipart/form-data">
 <table width="80%">
             <tr>
                 <td colspan="100%" style="display:none;text-align:center;" id='StatusTD'>
                     <span id="loading" style='display:none;'>Loading....</span>
                     <span id="Status" ></span>
                 </td>
             </tr>
             <tr>
                 <td>Upload File:<br/><a href="upload_invoice.xlsx">Download Example File</a></td>
                 <td><input type="file" name="invoiceFile" id="invoiceFile"   required></td>
             </tr>
             <tr>
                 <td colspan="100%"><button type="submit" class="btn btn-default" name="uploadFile">Upload</button></td>
             </tr>
 </table>
     </form>
     </div>
</div>-->
<!-----------------------------------------invoice list--------------------------------------------->
<br/>
<div class="panel">
    <div class="paneltitle" align="center">Proforma Invoice Data List</div>
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
    });

    function getPendingamt() {//
        var inv_amt = jQuery("#invamt").val();
        jQuery("#pamt").val(inv_amt);

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
        if (invno_id == "")
        {
            jQuery("#invno_error").show();
            jQuery("#invno_error").fadeOut(6000);
        } else if (invdate_id == "")
        {
            jQuery("#invdate_error").show();
            jQuery("#invdate_error").fadeOut(6000);
        } else if (cid_id == "0")
        {
            jQuery("#cid_error").show();
            jQuery("#cid_error").fadeOut(6000);
        } else if (invamt_id == "")
        {
            jQuery("#invamt_error").show();
            jQuery("#invamt_error").fadeOut(6000);
        } else if (sid_id == "0")
        {
            jQuery("#sid_error").show();
            jQuery("#sid_error").fadeOut(6000);
        } else if (pamt_id == "")
        {
            jQuery("#pamt_error").show();
            jQuery("#pamt_error").fadeOut(6000);
        } else if (tid_id == "0")
        {
            jQuery("#tid_error").show();
            jQuery("#tid_error").fadeOut(6000);
        } else if (tamt_id == "")
        {
            jQuery("#tamt_error").show();
            jQuery("#tamt_error").fadeOut(6000);
        } else {
            jQuery("#invoiceform").submit()
        }
    }
    function getCust() {
        jQuery("#icustomer").autocomplete({
            source: "route_ajax.php?customername=getcust",
            select: function (event, ui) {

                /*clear selected value */
                jQuery(this).val(ui.item.value);
                jQuery('#cno').val(ui.item.cid);
                jQuery('#cname').val(ui.item.cname);
                return false;
            }
        });
    }
    function getLedger() {
        var customerno = jQuery('#cno').val();
        if (customerno != '') {
            jQuery("#iledger").autocomplete({
                source: "route_ajax.php?ledgername=" + customerno,
                select: function (event, ui) {
                    /*clear selected value */
                    jQuery("#iledger").val(ui.item.value);
                    jQuery('#ledgerid').val(ui.item.lid);
                    return false;
                }
            });
        } else {
            alert("Please select correct customer no.");
            jQuery("#icustomer").focus();
        }
    }

    function approveProforma(id) {
        if (confirm("Do you want to approve Proforma Invoice ?")) {
            jQuery.ajax({
                url: "route_ajax.php",
                type: 'POST',
                cache: false,
                data: {pf_id: id},
                dataType: 'html',
                success: function (html) {
                    if (html == 'success') {
                        alert("Successfully Approved");
                        window.location.reload(true);
                    } else {
                        alert("Failed");
                    }
                }
            });
        } else {
            return false;
        }
    }
    function istaxed(value, id) {
        if (value.checked == true) {
            if (confirm("Do you want to make invoice taxed?")) {
                jQuery.ajax({
                    url: "route_ajax.php",
                    type: 'POST',
                    cache: false,
                    data: {set_taxed_invoice: id},
                    dataType: 'text',
                    success: function (html) {
                        if (html == 'success') {
//                            window.location.reload(true);
                        } else {
                            alert("Failed");
                        }
                    }
                });
            }
        } else {
            return false;
        }
    }
</script>
<!-------Script for excel upload---------------------------------------->
<!--<script type='text/javascript' src='../../scripts/exception.js'></script>-->
<!--<script>
jQuery(function(){
    jQuery('body').click(function(){
        jQuery('#success_status').hide();
    });
});
function show_error(text){
    var conf = "<b style='color:red;font-weight:bold;'>"+text+"</b>";
    jQuery('#StatusTD').show();
    jQuery('#Status').html(conf);
}

function validate_upload(){
    var fileName = jQuery('#invoiceFile').val();
    var validExtensions = <?php //echo json_encode($valid_file);                ?>;
    var fileExt = fileName.substr(fileName.lastIndexOf('.') + 1);
    
    if(jQuery.inArray(fileExt, validExtensions) == -1){
       show_error("Invalid file type");return false;
    }
    var size = jQuery("#invoiceFile")[0].files[0].size;
    var valid_size = <?php //echo $valid_size;                ?>//2 mb
    if(size>valid_size){
        show_error("File Size cannot cannot exceed 2 MB");return false;
    }
    
    return true;
}
</script>-->