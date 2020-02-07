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
$company_roleId = $_SESSION['company_roleId'];
$tm = new TeamManager();
$payment_id=$_GET['ip_id'];
// print_r($payment_id); exit;
$payment_Array=$tm->fetch_Payment_Collection($payment_id);
// print_r($payment_Array);exit;
$paymentdate=date("d-m-Y",strtotime($payment_Array['paymentdate']));
$cheque_date=date("d-m-Y",strtotime($payment_Array['cheque_date']));

include("header.php");
?>
<link rel="stylesheet" href="../../css/invoicePayment.css">
<div class="panel">
    <div class="paneltitle" align="center">Update Payment Collection</div>
    <div class="panelcontents">
    <div class="center">
    	<form name="payment_collection_edit" id="payment_collection_edit">
        <label>Customer</label>
        <input type="text" name="customername" id="customername" size="30" value="<?php echo ($payment_Array['customercompany'])?>" autocomplete="on" onkeypress="getCustomer();"/>

    		<label>Payment Mode </label>
            <select name="payment_mode" id="payment_mode">

            	 <?php if($payment_Array['pay_mode']=='cheque') { ?>
                        <option value="1"> Cheque </option>
                        <option value="2"> Cash </option>
                        <option value="3"> Online </option>
                    <?php } else if($payment_Array['pay_mode']=='cash') { ?>
                         <option value="2"> Cash </option> 
                         <option value="1"> Cheque </option>
                         <option value="3"> Online </option>
                    <?php } else if($payment_Array['pay_mode']=='online') { ?>
                         <option value="3"> Online </option> 
                         <option value="1"> Cheque </option>
                         <option value="2"> Cash </option>
                     <?php } else { ?>
                     	 <option value="1" > Cheque </option>
                         <option value="2" > Cash </option>
                         <option value="3" > Online </option>
                     <?php } ?>
            </select> 
			 <label>Paid Amount</label>
			<input type="text" name="paid_amount" id="paid_amount" value="<?php echo $payment_Array['paid_amt'] ?>" />
			<label>Payment Date</label>
			<input type="text" name="payment_date" id="payment_date" value="<?php echo $paymentdate ?>" />
			<!-- <label>TDS Amount</label>
			<input type="text" name="new_tds" id="new_tds" value="<?php echo $payment_Array['tds_amt'] ?>" /> -->
			<br/> 
			<label>Invoice Number</label>
			<input type="text" name="invoiceno" id="invoiceno" value="<?php echo $payment_Array['invoiceno'] ?>" disabled/>

			<label>Cheque Number</label>
			<input type="text" name="cheque_no" id="cheque_no" value="<?php echo $payment_Array['cheque_no'] ?>" maxlength="6"/>
			<label>Cheque Date</label>
			<input type="text" name="cheque_date" id="cheque_date" value="<?php echo $cheque_date; ?>"/>
			<label>Bank Name</label>
			<input type="text" name="bank_name" id="bank_name" style="text-transform:capitalize;" value="<?php echo $payment_Array['bank_name'] ?>" />
			<br/>
			<label>Bank Branch</label>
			<input type="text" name="bank_branch" id="bank_branch" style="text-transform:capitalize;" value="<?php echo $payment_Array['bank_branch'] ?>" />
			<!-- <label>Bad Debt</label>
			<input type="text" name="bad_debt" id="bad_debt" value="<?php echo $payment_Array['bad_debts'] ?>" /> -->
			<label>Cheque Status </label>
            <select name="cheque_status" id="cheque_status">

            	 <?php if($payment_Array['cheque_status']=='received') { ?>
                        <option value="1"> Received </option>
                        <?php if ($company_roleId != 5) { ?>
                        <option value="2"> Deposited</option>
              			<option value="3"> Cleared</option>
                    <?php } } else if($payment_Array['cheque_status']=='deposited') { ?>
                         <option value="2" > Deposited </option> 
                         <?php if ($company_roleId != 5) { ?>
                         <option value="1" > Received </option>
                         <option value="3" > Cleared </option>
                         
                    <?php } } else if($payment_Array['cheque_status']=='cleared') { ?>
                         <option value="3" > Cleared </option> 
                         <?php if ($company_roleId != 5) { ?>
                         <option value="1" > Received </option>
                         <option value="2" > Deposited </option> 
                         <?php } ?>
                     <?php } else { ?>
                     	<option value="1"> Received </option>
                        <?php if ($company_roleId != 5) { ?>
                        <option value="2"> Deposited</option>
              			<option value="3"> Cleared</option>
                       <?php } } ?>
            </select>
             <label>Collected By</label>
        	<input type="text" name="collectedby" id="collectedby" size="30" value="<?php echo ($payment_Array['name'])?>" autocomplete="on" onkeypress="getCollectedBy();"/>
            <label>Status </label>
            <select name="status" id="status">

            	 <?php if($payment_Array['status']=='collected') { ?>
                        <option value="1"> Collected  </option>
                        <?php if ($company_roleId != 5) { ?>
                        <option value="2"> Received</option>
              			<option value="3"> Realized</option>
                        <option value="4"> Rejected</option>
                    <?php } } else if($payment_Array['status']=='received') { ?>
                         <option value="2" > Received </option> 
                         <?php if ($company_roleId != 5) { ?>
                         <option value="1" > Collected </option>
                         <option value="3" > Realized </option>
                         <option value="4"> Rejected</option>
                    <?php } } else if($payment_Array['status']=='realized') { ?>
                         <option value="3" > Realized </option> 
                          <?php if ($company_roleId != 5) { ?>
                         <option value="1" > Collected </option>
                         <option value="2" > Received </option>
                         <option value="4"> Rejected</option>
                         </select>
                    <?php } } else if($payment_Array['status']=='rejected') { ?>
                        <option value="4"> Rejected</option>
                        <?php if ($company_roleId != 5) { ?>
                         <option value="1" > Collected </option>
                         <option value="2" > Received </option>
                         <option value="3" > Realized </option> 
                    <?php } } else { ?>
                     	 <option value="1"> Collected  </option>
                         <?php if ($company_roleId != 5) { ?>
                         <option value="2"> Received</option>
              			 <option value="3"> Realized</option>
                         <option value="4"> Rejected</option>
                     <?php } }?>
                     
            </select>
            <br/>
            <label>Remark</label>
			<input type="text" name="remark" id="remark" value="<?php echo $payment_Array['remark'] ?>" />
            <input type="hidden" name="invoiceid" id="invoiceid" value="<?php echo $payment_Array['invoiceid'] ?>" />
			
			<br/>

			 <input type="hidden" name="payment_id" id="payment_id" value="<?php echo $payment_id; ?>"/>
             <input type="hidden" name="collectedby_id" id="collectedby_id" value="<?php echo $payment_Array['teamid'] ?>"/>
             <input type="hidden" name="customerno" id="customerno" value="<?php echo $payment_Array['customerno'] ?>"/>
			 <input type="button" name="edit_payment" id="edit_payment" value="Update Payment" onclick="editPayment();" style="margin-left:40%"/>
		</form>
	</div>
	</div>
</div>
<!-- <script>
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
</script> -->
<script src='../../scripts/team/payment_collection.js'></script>
