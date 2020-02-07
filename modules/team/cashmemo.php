<?php
    
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");

class CashMemo {
    
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

function display($data) {
    echo $data;
}

//QUERY for display
$Display = array();
$SQL = "SELECT  cm.cmid
                ,cm.cash_memo_no
                ,cm.customerno
                ,c.customercompany
                ,cm.cm_date
                ,cm.cm_amount
                ,cm.status
                ,cm.pending_amt
                ,cm.paid_amount
                ,cm.paymentdate
                ,cm.product_id
                ,cm.approved
        FROM " . DB_PARENT . ".cash_memo cm 
        LEFT OUTER JOIN " . DB_PARENT . ".customer c ON c.customerno = cm.customerno 
        WHERE   cm.isdeleted = 0 
        ORDER BY cm.cmid DESC";

$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $Datacap = new CashMemo();
        //$x++;
        $Datacap->cmid = $row['cmid'];
        $Datacap->cash_memo_no = $row['cash_memo_no'];
        $Datacap->customerno = $row['customerno'];
        $Datacap->clientname = $row['customercompany'];
        $Datacap->cm_date = date("d-m-Y", strtotime($row['cm_date']));
        $Datacap->cm_amount = $row['cm_amount'];
        $Datacap->status = $row['status'];
        $Datacap->pamt = $row['pending_amt'];
        $Datacap->paid_amt = $row['paid_amt'];
        foreach ($products as $data) {
            if ($row['product_id'] == $data['id']) {
                $Datacap->product = $data['name'];
            }
        }
        if (isset($row['paymentdate']) && $row['paymentdate'] != '0000-00-00') {// to provide empty date if not inserted
            $Datacap->paydate = date("d-m-Y", strtotime($row['paymentdate']));
        }
        if ($row['approved'] == '0') {
            $Datacap->approve = '<button onclick="approveMemo(' . $row['cmid'] . ')">Approve</button>';
        } else if ($row['approved'] == '1') {
            $Datacap->approve = '';
        }
        if ($row['approved'] == '1') {
            $Datacap->generate = '<a href="route_ajax.php?generate_pdf=' . $row['cmid'] . '"><img src="../../images/pdf_icon.png"></img></a>';
        }
        $Display[] = $Datacap;
    }
}
$dg = new objectdatagrid($Display);
$dg->AddColumn("Sr.No", "cmid");
$dg->AddColumn("Cash Memo No", "cash_memo_no");
$dg->AddColumn("Customer No", "customerno");
$dg->AddColumn("Client Name", "clientname");
$dg->AddColumn("Product Name", "product");
$dg->AddColumn("Invoice Date", "cm_date");
$dg->AddColumn("Invoice Amt", "cm_amount");
$dg->AddColumn("Status", "status");
$dg->AddColumn("Paid Amt", "paid_amt");
$dg->AddColumn("Pending Amt", "pamt");
$dg->AddColumn("Payment Date", "paydate");
if (IsHead()) {
    $dg->AddColumn("Approve", "approve");
}
if (IsAdmin()) {
    $dg->AddColumn("", "generate");
}
$dg->AddRightAction("View", "../../images/edit.png", "cashmemo_edit.php?cmid=%d");

$dg->SetNoDataMessage("No Cash Memo");
$dg->AddIdColumn("cmid");



if ($_POST['submitcashmemo']) {
    $todaysdatetime = date("Y-m-d H:i:s");
    $cmno = GetSafeValueString($_POST['cashmemono'], "string");
    $cmdate = GetSafeValueString(date("Y-m-d", strtotime($_POST['cashmemodate'])), "string");
    $customerno = GetSafeValueString($_POST['cno'], "string");
    $cmamt = GetSafeValueString($_POST['cashmemoamt'], "string");
    $status = GetSafeValueString($_POST['sid'], "string");
    $pending_amt = GetSafeValueString($_POST['pamt'], "string");
    $product = GetSafeValueString($_POST['product'], "string");
    $productname = GetSafeValueString($_POST['productname'], "string");
    $address = GetSafeValueString($_POST['address'], "string");
    $paymentdate = GetSafeValueString($_POST['paydate'], "string");

    $SQL = sprintf("INSERT INTO " . DB_PARENT . ".cash_memo(`cash_memo_no`
                    ,`customerno`
                    ,`cm_date`
                    ,`cm_amount`
                    ,`status`
                    ,`pending_amt`
                    ,`product_id`
                    ,`address`
                    ,`paymentdate`)
                VALUES('%s',%d, '%s',%d,'%s',%d,%d,'%s','%s')", Sanitise::String($cmno), Sanitise::Long($customerno), Sanitise::Date($cmdate), Sanitise::Long($cmamt), Sanitise::String($status), Sanitise::Long($pending_amt), Sanitise::Long($product), Sanitise::String($address), Sanitise::Date($paymentdate));
    $db->executeQuery($SQL);

    $cmid = $db->get_insertedId();
    foreach ($_POST as $key => $value) {
        if (strstr($key, 'description')) {
            $description = str_replace('item', '', $key);
            $id = substr($description, -1);
            $quantity = 'quantity' . $id;
            $price = 'price' . $id;
            $SQL = sprintf("INSERT INTO " . DB_PARENT . ".cash_memo_desc(`desc`
                    ,`quantity`
                    ,`rate`
                    ,`cmid`
                    ,`created_by`
                    ,`created_on`)
                VALUES('%s',%d,%d,%d,%d,'%s')", Sanitise::String($_POST[$description]), Sanitise::Long($_POST[$quantity]), Sanitise::Long($_POST[$price]), Sanitise::Long($cmid), Sanitise::Long(GetLoggedInUserId()), Sanitise::String($todaysdatetime));
            $db->executeQuery($SQL);
        }
    }
}
include("header.php");
?>
<div class="panel">
    <div class="paneltitle" align="center">Add Cash Memo</div> 
    <div class="panelcontents">
        <form method="post" name="cashmemoform" id="cashmemoform" onsubmit="ValidateForm(); return false;" enctype="multipart/form-data">
            <div style="height:20px;">
                <span id="general_success" style="display:none; color: #00FF00">Data Added Successfully.</span> 
                <span id="invno_error" style="display:none; color: #FF0000">Please Enter Cash Memo No.</span> 
                <span id="invdate_error" style="display:none; color: #FF0000">Please Enter Cash Memo Date.</span> 
                <span id="cid_error" style="display:none; color: #FF0000">Please Select Customer No.</span> 
                <span id="invamt_error" style="display:none; color: #FF0000">Please Enter Cash Memo Amt.</span> 
                <span id="sid_error" style="display:none; color: #FF0000">Please Select Status.</span>           
                <span id="pamt_error" style="display:none; color: #FF0000">Please Enter Pending Amt.</span> 
            </div>
            <table width="80%">
                <tr><td>Cash Memo Number</td>
                    <td>
                        <input type="text" name ="cashmemono" id ="cashmemono" value ="">
                    </td>           
                </tr>
                <tr><td>Cash Memo Date</td>
                    <td>
                        <input type="text" name ="cashmemodate" id ="cashmemodate" placeholder="dd-mm-yyyy" value =""><button id="trigger1">...</button>
                    </td>           
                </tr>
                <tr>
                    <td>Customer</td>
                    <td>    
                        <input  type="text" name="icustomer" id="icustomer" size="25" value="<?php if (isset($_POST['icustomer'])) { echo $_POST['icustomer']; } ?>" autocomplete="off" placeholder="Enter Customer No Or Name" onkeyup="getCust()" />
                        <input type="hidden" name="cno" id="cno" value="<?php if (isset($_POST['cno'])) { echo $_POST['cno']; } ?>"/>
                        <input type="hidden" name="cname" id="cname" value="<?php if (isset($_POST['cname'])) { echo $_POST['cname']; } ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>Product :</td> 
                    <td>
                        <select id="product" name="product" onchange="productChng(this)"/>
                            <option value="0">Choose product</option>
                            <?php
                            if (!empty($products)) {
                                foreach ($products as $data) {
                                    echo '<option value="' . $data['id'] . '">' . $data['name'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <input type="hidden" name="productname" id="productname"/>
                    </td> 
                </tr>

                <tr><td>Address</td>
                    <td><textarea name ="address" id ="address"></textarea></td>
                </tr>

            </table>
            <table width="65%">
                <thead>
                    <tr style="background:#fff;">
                        <td>Description</td>
                        <td>Quantity</td>
                        <td>Price</td>
                        <td>
                            <span id="add" style="float: right;">
                                <a> <img src="../../images/show.png" alt="Add Row"/> </a>
                            </span>
                        </td>
                    </tr>
                </thead>
                <tbody class="detail">
                    <tr style="border:none;" id="rowCount1" class="rowadded">
                        <td style="border:none;"><textarea name="description1" rows="5" cols="30" size="60%" class="description" id="description1"></textarea></td>
                        <td style="border:none;"><input name="quantity1" id="quantity1" class="quantity" type="text" value="" size="25%"/></td>
                        <td style="border:none;"><input name="price1" id="price1" class="price" type="text" value="0" size="25%"/></td>
                        <td style="border:none;"><a id="remove" href="javascript:void(0);"><img src="../../images/hide.gif" alt="Delete"/></a></td>
                    </tr>
                <tbody>
            </table>
            <table  width="80%">
                <tr><td>Amount</td>
                    <td>
                        <input type="text" name ="cashmemoamt" id ="cashmemoamt" value ="">
                    </td>           
                </tr>
                <tr><td>Status</td>
                    <td>    
                        <select name ="sid" id="sid" onchange="changeStatus(this)">
                            <option value ="1">Pending</option>
                            <option value ="2">Paid</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Paid Amount</td>
                    <td><input type="text" name ="paid_amt" id ="paid_amt" value="" onblur="getPendingamt();"></td>
                </tr>
                <tr>
                    <td>Pending Amount</td>
                    <td><input type="text" name ="pamt" id ="pamt" value=""></td>
                </tr>
                <tr>
                    <td>Payment Date</td>
                    <td><input type="text" name ="paydate" id ="paydate" placeholder="dd-mm-yyyy" /><button id="trigger2">...</button></td>
                </tr>
            </table>
            <input type="submit" id="submitcashmemo" name="submitcashmemo" class="btn btn-default" value="Add Cash Memo">
        </form>
    </div>
</div>
<br/>
<div class="panel">
    <div class="paneltitle" align="center">Invoice Data List</div>
    <div class="panelcontents">
        <?php $dg->Render(); ?>
    </div>

</div>
<br/>
<?php include("footer.php"); ?>

<script>
    function approveMemo(cmid) {
        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: {cm_id: cmid},
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
    }

    $(document).ready(function () {
        Calendar.setup(
                {
                    inputField: "cashmemodate", // ID of the input field
                    ifFormat: "%d-%m-%Y", // the date format
                    button: "trigger1" // ID of the button
                });

        Calendar.setup(
                {
                    inputField: "paydate", // ID of the input field
                    ifFormat: "%d-%m-%Y", // the date format;
                    button: "trigger2" // ID of the button
                });

        jQuery('.price').on('blur', function () {
            var total = 0;
            jQuery('.price').each(function (i, obj) {
                var priceid = jQuery(this).attr("id");
                var row_id = priceid[priceid.length - 1];
                var quentity_id = "quantity" + row_id;
                var quantity = jQuery('#' + quentity_id).val();
                total = total + (quantity * parseInt(obj.value));
            });
            jQuery('#cashmemoamt').val(total);
        });

    });

    function priceOnBlur() {
        var total = 0;
        jQuery('.price').each(function (i, obj) {
            var priceid = jQuery(this).attr("id");
            var row_id = priceid[priceid.length - 1];
            var quentity_id = "quantity" + row_id;
            var quantity = jQuery('#' + quentity_id).val();
            total = total + (quantity * parseInt(obj.value));
        });
        jQuery('#cashmemoamt').val(total);
    }

    function productChng(value) {
        jQuery('#productname').val(value.options[value.selectedIndex].innerHTML);
    }

    jQuery(function ()
    {
        // Add Row
        jQuery("#add").click(function ()
        {
            addnewrow();
        });

        // Remove Row
        jQuery("body").delegate('#remove', 'click', function ()
        {
            jQuery(this).parent().parent().remove();
        });

    });

    var rowCount = 1;
    function addnewrow()
    {
        rowCount++;
        var tr = "<tr style='border:none;' class='rowadded'>" +
                "<td style='border:none;'><textarea rows='5' cols='30' size='60%' class='description' id='description" + rowCount + "' name='description" + rowCount + "'></textarea></td>" +
                "<td style='border:none;'><input name='quantity" + rowCount + "' id='quantity" + rowCount + "' class='quantity' type='text' value='' size='25%'/></td>" +
                "<td style='border:none;'><input name='price" + rowCount + "' id='price" + rowCount + "' class='price' onblur='priceOnBlur();' type='text' value='0' size='25%'/></td>" +
                "<td style='border:none;'><a id='remove' href='javascript:void(0);'><img src='../../images/hide.gif' alt='Delete'/></a><input name='inv_renewal1' type='hidden' id='inv_renewal1' class='inv_renewal'/></td>" +
                "</tr>";
        $(".detail").append(tr);
    }

    function ValidateForm() {

        var invno_id = jQuery('#cashmemono').val();
        var invdate_id = jQuery('#cashmemodate').val();
        var cid_id = jQuery('#cno').val();
        var invamt_id = jQuery('#cashmemoamt').val();
        var sid_id = jQuery('#sid').val();
        var pamt_id = jQuery('#pamt').val();
        if (invno_id == "") {
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
        } else {
            jQuery("#cashmemoform").submit()
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
    
    function getPendingamt() {//
        var total_amt = jQuery("#cashmemoamt").val();
        var paid_amt = jQuery('#paid_amt').val();
        var sid_id = jQuery('#sid').val();
        if (sid_id == 1) {
            jQuery("#pamt").val(total_amt - paid_amt);
        } else {
            jQuery("#pamt").val('0');
        }
    }
    
    function changeStatus(status) {
        var total_amt = jQuery("#cashmemoamt").val();
        if (status.value == 2) {
            jQuery('#paid_amt').val(total_amt);
            jQuery("#pamt").val('0');
        } else {
            jQuery('#paid_amt').val('0');
            jQuery("#pamt").val('0');
        }
    }
</script>