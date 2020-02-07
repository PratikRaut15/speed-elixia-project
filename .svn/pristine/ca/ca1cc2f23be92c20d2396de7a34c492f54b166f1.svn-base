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

class CashMemoEdit {
    
}

$db = new DatabaseManager();
$cmid = $_GET['cmid'];
if (isset($_POST['editcm'])) {
    $cmno = GetSafeValueString($_POST['cmno'], "string");
    $cmdate = GetSafeValueString(date("Y-m-d", strtotime($_POST['cmdate'])), "string");
    $customerno = GetSafeValueString($_POST['customerno'], "string");
    $cm_amount = GetSafeValueString($_POST['cm_amount'], "string");
    $status = GetSafeValueString($_POST['sid'], "string");
    $pending_amt = GetSafeValueString($_POST['pamt'], "string");
    $paidamt = GetSafeValueString($_POST['paid_amt'], "string");
    $paydate = GetSafeValueString(date("Y-m-d", strtotime($_POST['paydate'])), "string");
    $cmid = GetSafeValueString($_POST['cmid'], "String");

    $SQL = "UPDATE  " . DB_PARENT . ".cash_memo 
            SET     cash_memo_no = '$cmno'
                    ,customerno = '$customerno'
                    , cm_date ='$cmdate'
                    , cm_amount ='$cm_amount'
                    , status ='$status'
                    , pending_amt ='$pending_amt'
                    , paymentdate ='$paydate'
                    , paid_amount ='$paidamt'
            WHERE   cmid ='$cmid'";

    $db->executeQuery($SQL);


    header("location:cashmemo.php");
}

$SQL = "SELECT * FROM " . DB_PARENT . ".cash_memo WHERE cmid ='$cmid' AND isdeleted = 0";
$db->executeQuery($SQL);

$row = $db->get_nextRow();

include("header.php");
?>
<div class="panel">
    <div class="paneltitle" align="center">Edit Cash Memo Data</div> 
    <div class="panelcontents">
        <form method="post" name="editcashmemo" id="editcashmemo" onsubmit="ValidateForm();
                return false;">
            <div style="height:20px;">
                <span id="general_success" style="display:none; color: #00FF00">Updated Data Successfully.</span> 
                <span id="invdate_error" style="display:none; color: #FF0000">Please Enter Cash Memo Date.</span> 
                <span id="cid_error" style="display:none; color: #FF0000">Please Select Customer No.</span> 
                <span id="invamt_error" style="display:none; color: #FF0000">Please Enter Cash Memo Total Amt.</span> 
                <span id="pamt_error" style="display:none; color: #FF0000">Please Enter Pending Amt.</span> 
                <span id="payamt_error" style="display:none; color: #FF0000">Please Enter Payment Amt.</span>
                <span id="paydate_error" style="display:none; color: #FF0000">Please Enter Payment Date.</span>
                <span id="paidamt_error" style="display:none; color: #FF0000">Please Enter Paid Amt.</span>
                <span id="amt_error" style="display:none; color: #FF0000">Pending and Paid Amount cannot be greater than Amount or Pending amount cannot be negative</span>
            </div>        
            <table width="80%">

                <tr>
                    <td>Cash Memo Number</td>
                    <td>
                        <input type="text" name ="cmno" id ="cmno" value ="<?php
                        if (isset($row['cash_memo_no'])) {
                            echo $row['cash_memo_no'];
                        }
                        ?>" readonly>
                    </td>           
                </tr>
                <tr>
                    <td>Cash Memo Date</td>
                    <td>
                        <input type="text" name ="cmdate" id ="cmdate" placeholder="dd-mm-yyyy" value ="<?php
                        if (isset($row['cm_date'])) {
                            echo date("d-m-Y", strtotime($row['cm_date']));
                        }
                        ?>"><button id="trigger1">...</button>
                    </td>           
                </tr>
                <tr>
                    <td>Customer No</td>
                    <td>    
                        <input type="text" name="customerno" id="customerno" onkeyup="getCust()" value = "<?php
                        if (isset($row['customerno'])) {
                            echo $row['customerno'];
                        }
                        ?>" />
                    </td>
                </tr>
                <tr>
                    <td>Amount</td>
                    <td>
                        <input type="text" name ="cm_amount" id ="cm_amount" value ="<?php
                        if (isset($row['cm_amount'])) {
                            echo $row['cm_amount'];
                        }
                        ?>">
                    </td>           
                </tr>
                <tr>
                    <td>Status</td>
                    <td>    
                        <select name ="sid" id="sid" onchange="changeStatus(this)">
                            <option value="1" <?php
                        if ($row['status'] == '1') {
                            echo "selected";
                        }
                        ?> >Pending</option>
                            <option value="2" <?php
                        if ($row['status'] == '2') {
                            echo "selected";
                        }
                        ?> >Paid</option>
                        </select>
                    </td>
                </tr>
                <tr><td>Paid Amount</td>
                    <td><input type="text" name ="paid_amt" id ="paid_amt" onblur="getPendingamt();" value="<?php
                        if (isset($row['paid_amount'])) {
                            echo $row['paid_amount'];
                        }
                        ?>"></td>
                </tr>
                <tr>
                    <td>Pending Amount</td>
                    <td>
                        <input type="text" name ="pamt" id ="pamt" value="<?php
                        if (isset($row['pending_amt'])) {
                            echo $row['pending_amt'];
                        }
                        ?>">
                    </td>
                </tr>
                <tr>
                    <td>Payment Date</td>
                    <td>
                        <input type="text" name ="paydate" id ="paydate" placeholder="dd-mm-yyyy" value="<?php
if (isset($row['paymentdate']) && $row['paymentdate'] != '0000-00-00') {
    echo date("d-m-Y", strtotime($row['paymentdate']));
}
?>"><button id="trigger2">...</button>
                    </td>
                </tr>

            </table>
            <input type="hidden" name="cmid" value="<?php echo $cmid ?>">           
            <input type="submit" id="editcm" name="editcm" class="btn btn-default" value="Edit Cash Memo">
        </form>
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
                    inputField: "cmdate", // ID of the input field
                    ifFormat: "%d-%m-%Y", // the date format

                    button: "trigger1" // ID of the button
                });

        Calendar.setup(
                {
                    inputField: "paydate", // ID of the input field
                    ifFormat: "%d-%m-%Y", // the date format;
                    button: "trigger2" // ID of the button
                });
    });

    function ValidateForm() {

        var invdate_id = jQuery('#cmdate').val();
        var cid_id = jQuery('#customerno').val();
        var invamt_id = jQuery('#cm_amount').val();
        var pamt_id = jQuery('#pamt').val();
        var paydate_id = jQuery('#paydate').val();
        var paidamt_id = jQuery('#paid_amt').val();

        if (invdate_id == "")
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

        else if (pamt_id == "")
        {
            jQuery("#pamt_error").show();
            jQuery("#pamt_error").fadeOut(6000);
        }
        else if (paidamt_id == "")
        {
            jQuery("#paidamt_error").show();
            jQuery("#paidamt_error").fadeOut(6000);
        }
        else if (paidamt_id != "0" && paydate_id == "")
        {
            jQuery("#paydate_error").show();
            jQuery("#paydate_error").fadeOut(6000);
        }
        else if (pamt_id > invamt_id || paidamt_id > invamt_id)
        {
            jQuery("#amt_error").show();
            jQuery("#amt_error").fadeOut(6000);
        }
        else {
            jQuery("#editcashmemo").submit();
        }
    }

    function getCust() {
        jQuery("#customerno").autocomplete({
            source: "route_ajax.php?customername=getcust",
            select: function (event, ui) {

                /*clear selected value */
//                jQuery(this).val(ui.item.value);
                jQuery('#customerno').val(ui.item.cid);
                return false;
            }
        });
    }
    function getPendingamt() {//
        var total_amt = jQuery("#cm_amount").val();
        var paid_amt = jQuery('#paid_amt').val();
        var sid_id = jQuery('#sid').val();
        if (paid_amt <= total_amt) {
            if (sid_id == 1) {
                jQuery("#pamt").val(total_amt - paid_amt);
            } else {
                jQuery("#pamt").val('0');
            }
        } else {
            jQuery('#paid_amt').val('0');
            jQuery('#pamt').val('0');
            jQuery("#amt_error").show();
            jQuery("#amt_error").fadeOut(6000);
        }
    }
    function changeStatus(status) {
        var total_amt = jQuery("#cm_amount").val();
        if (status.value == 2) {
            jQuery('#paid_amt').val(total_amt);
            jQuery("#pamt").val('0');
        } else {
            jQuery('#paid_amt').val('0');
            jQuery("#pamt").val('0');
        }
    }
</script>