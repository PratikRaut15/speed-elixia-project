<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include("../../lib/bo/TeamManager.php");
include_once("../../lib/system/Date.php");

date_default_timezone_set("Asia/Calcutta");
$today = date("d-m-Y");
$time= date("h:i");
$created_on=date("Y-m-d H:i:s");

include("header.php");
?>
<link rel="stylesheet" href="../../css/invoicePayment.css">
<div class="panel">
  <div class="paneltitle" align="center">Invoice Payment</div> 
  <div class="panelcontents">
    <div class="center">  
      <label>Customer</label>
        <input type="text" name="customername" id="customername" size="30" value="" autocomplete="off" placeholder="Enter Customer Name or number" onkeypress="getCustomer();"/> 
      <label>Ledger</label>
            <select name="ledger" id="ledger">
              <option value="0">Select Ledger</option>
            </select>
      <label>Pending Invoices</label>
            <select name="invoice" id="invoice">
              <option value="0">Select Pending Invoice</option>
            </select>
      <label>Paid Invoices</label>
            <select name="paid_invoice" id="paid_invoice">
              <option value="0">Select Paid Invoice</option>
            </select>
      <div class="Payment_Table" id="Payment_Table" style="display: none;">
        <?php 
        include('invoice_payment_grid.php');
        include('invoice_payment_grid_old.php');
        ?>
          <div class="payment_sub_details" id="payment_sub_details">
                    <label>Total Invoice Amount</label>
                    <input type="text" name="invoice_amount" id="invoice_amount" value="" readonly /> 
                    <label>Total Payment Amount</label>
                    <input type="text" name="payment_amount" id="payment_amount"  value="" readonly/> 
                    <label>Total Pending Amount</label>
                    <input type="text" name="pending_amount" id="pending_amount" value="" readonly/>
                    <br>
                    
                    <label>Total TDS</label>
                    <input type="text" name="tds_amount" id="tds_amount" value="" readonly/>
                    
                    <label>Total Unpaid Amount</label>
                    <input type="text" name="unpaid_amt" id="unpaid_amt" value="" readonly/> 
          </div> 
      </div>
      <form name="invoice_payment" id="invoice_payment">
                <div class="payment_fix" id="payment_fix">
                  <label>Payment Mode</label>
                    <select name="payment_mode" id="payment_mode">
                    </select>
                  <label>Payment Date</label>
                    <input type="text" name="payment_date" id="payment_date" value="<?php echo $today; ?>"/>
                  <br>
                </div>
                <div class="check_payment" id="check_payment" style="display: none;">
                  <label>Cheque No.</label>
                  <input type="text" name="cheque_no" id="cheque_no" placeholder="Enter Cheque No." maxlength="6"/> 
                  
                  <label>Bank Name</label>
                  <input type="text" name="bank_name" id="bank_name" style="text-transform:capitalize;" placeholder="Enter Bank's Name"/> 
                  
                  <label>Bank Branch</label>
                  <input type="text" name="bank_branch" id="bank_branch" style="text-transform:capitalize;" placeholder="Enter Bank's Branch"/> 

                  <label>Cheque Date</label>
                  <input type="text" name="cheque_date" id="cheque_date" value="<?php echo $today; ?>"/> 
                </div>
                <div class="payment_not_fix" id="payment_not_fix">
                  <label>New Amount</label>
                  <input type="text" name="new_payment_amount" id="new_payment_amount" placeholder="Enter Payment Amount" /> 

                  <label>New TDS</label>
                  <input type="text" name="new_tds" id="new_tds" placeholder="Enter TDS Amount" />
                  
                  <label>Unpaid Amount</label>
                  <input type="text" name="new_unpaid_amount" id="new_unpaid_amount" placeholder="Enter Unpaid Amount" />
                </div>
                <input type="hidden" name="customerno" id="customerno" value=""/>
                <input type="hidden" name="payment_invoice_id" id="payment_invoice_id" value=""/>
                <input type="hidden" name="payment_invoice_no" id="payment_invoice_no" value=""/>

                <input type="button" name="submit_payment" id="submit_payment" value="Submit Payment" onclick="submitPayment();" style="margin-left:40%;"/>       
      </form>
    </div>
  </div>
</div> 

<script src='../../scripts/team/invoice_payment.js'></script>
