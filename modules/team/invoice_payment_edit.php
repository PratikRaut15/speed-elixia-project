<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include("../../lib/bo/TeamManager.php");
include_once("../../lib/system/Date.php");
date_default_timezone_set("Asia/Calcutta");
$today = date("d-m-Y");
$time= date("h:i");
$created_on=date("Y-m-d H:i:s");

$tm = new TeamManager();
$invoice_payment_id=$_GET['ip_id'];

$payment_Array=$tm->fetch_Payment_Mapping($invoice_payment_id);

$paymentdate=date("d-m-Y",strtotime($payment_Array['paymentdate']));
$cheque_date=date("d-m-Y",strtotime($payment_Array['cheque_date']));

include("header.php");
?>
<link rel="stylesheet" href="../../css/invoicePayment.css">
<div class="panel">
    <div class="paneltitle" align="center">Update Invoice Payment</div>
    <div class="panelcontents">
    <div class="center">
    	<form name="invoice_payment_edit" id="invoice_payment_edit">
			<label>Customer</label>
			<input type="text" name="customerno" id="customerno" value="<?php echo $payment_Array['customerno'].'-'.$payment_Array['customercompany'] ?>" disabled/>
			<label>Invoice No.</label>
			<input type="text" name="invoiceno" id="invoiceno" value="<?php echo $payment_Array['invoiceno'] ?>" disabled/>
			<label>Payment Mode</label>
			<input type="text" name="payment_mode" id="payment_mode" value="<?php echo $payment_Array['payment_mode'] ?>" disabled/>
			<label>Invoice Amount</label>
			<input type="text" name="invoice_amount" id="invoice_amount" value="<?php echo $payment_Array['inv_amt'] ?>" disabled/>
			<br/>


		   <label>Paid Amount</label>
			<input type="text" name="new_payment_amount" id="new_payment_amount" value="<?php echo $payment_Array['paid_amt'] ?>" />
			<label>TDS</label>
			<input type="text" name="new_tds" id="new_tds" value="<?php echo $payment_Array['tds_amt'] ?>" />
			<label>Unpaid Amount</label>
			<input type="text" name="new_unpaid_amount" id="new_unpaid_amount" value="<?php echo $payment_Array['bad_debts'] ?>" />

			<input type="hidden" name="invoiceid" id="invoiceid" value="<?php echo $payment_Array['invoiceid'] ?>" />
			<label>Payment Date</label>
			<input type="text" name="payment_date" id="payment_date" value="<?php echo $paymentdate ?>" />
			<br/>


			<div class="check_payment" id="check_payment" style="display: none;">
				<label>Cheque No.</label>
				<input type="text" name="cheque_no" id="cheque_no" value="<?php echo $payment_Array['cheque_no'] ?>" maxlength="6"/>

				<label>Bank Name</label>
				<input type="text" name="bank_name" id="bank_name" style="text-transform:capitalize;" value="<?php echo $payment_Array['bank_name'] ?>" />

				<label>Bank Branch</label>
				<input type="text" name="bank_branch" id="bank_branch" style="text-transform:capitalize;" value="<?php echo $payment_Array['bank_branch'] ?>" />

				<label>Cheque Date</label>
				<input type="text" name="cheque_date" id="cheque_date" value="<?php echo $cheque_date; ?>"/>

				<!-- <label>Cheque Status</label>
				<input type="checkbox" name="cheque_status" id="cheque_status" value="1"/>  -->
			</div>
			 <input type="hidden" name="invoice_payment_id" id="invoice_payment_id" value="<?php echo $invoice_payment_id; ?>"/>

			 <input type="button" name="edit_payment" id="edit_payment" value="Update Payment" onclick="editPayment();" style="margin-left:40%"/>
		</form>
	</div>
	</div>
</div>
<script>
	var pay_mode =0;
	jQuery(document).ready(function () {
		 pay_mode = <?php echo $payment_Array['pay_mode'];?>;

		if(pay_mode==1){
			$("#check_payment").show();
		}
		else{
			$("#check_payment").hide();
		}


	});
</script>
<script src='../../scripts/team/invoice_payment_edit.js'></script>
