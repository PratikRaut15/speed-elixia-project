<?php
$customerno = exit_issetor($_SESSION['customerno']);
$orderid = exit_issetor($_GET['oid']);

$dm = new DeliveryManager($customerno);
$details = $dm->getOrderDetails($orderid);
$paidamt = $dm->getPaidAmt($orderid);
$reamt = $dm->getRedeemUsed($orderid);
$getstatus = $dm->getstatus();
?>
<style type="text/css">
#ajaxstatus{text-align:center;font-weight:bold;display:none}
.mandatory{color:red;font-weight:bold;}
#addpayment table{width:50%;}
#addpayment .frmlblTd{text-align:center}    
#addpayment .cen{
    align:center;
}    
</style>

<br/>
<div class='container' >
    <center>
    <form id="addpayment" method="POST" action="" onsubmit="addpayment();return false;" enctype='application/json'>
    <table class='table table-condensed '>
        <thead><tr><th colspan="100%" >Payment </th></tr></thead>
        <tbody>
            <tr id="sel" style="display: none;"><td colspan="100%">Select Payment Option</td></tr>
            <tr id="pamount" style="display: none;"><td colspan="100%">Enter Amount</td></tr>
            <tr id="pamountnot" style="display: none;"><td colspan="100%">Amount not acceptable, Please select another type</td></tr>
            <tr id="pamountnotredeem" style="display: none;"><td colspan="100%">Amount Greater Than Redeem Limit</td></tr>
            <tr id="preason" style="display: none;"><td colspan="100%">Enter Reason</td></tr>
            <tr id="pchkno" style="display: none;"><td colspan="100%">Enter Check No</td></tr>
            <tr id="paccno" style="display: none;"><td colspan="100%">Enter Account No</td></tr>
            <tr id="pbranch" style="display: none;"><td colspan="100%">Enter Branch Name</td></tr>
            <tr id="pbank" style="display: none;"><td colspan="100%">Enter Bank Name</td></tr>
            <tr id="preason" style="display: none;"><td colspan="100%">Enter Reason</td></tr>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <input type="hidden" name="orderid" value='<?php echo $details->id;?>' >
            <input type="hidden" name="paymentby" value='0' >
            
            
            
            <tr >
                <td>Total Amount</td>
                <td> <input type="text" name="total_amount" id="total_amount" value='<?php echo $details->total_amount;?>' /></td>
            </tr>
            
            <tr >
                <td>Redeem Limit</td>
                <td> <input type="text" name="redeem_limit" id="redeem_limit" value='<?php echo $details->reedeem_limit;?>' /></td>
            </tr>
            
            <tr >
                <td>Paid Amount</td>
                <td> <input type="text" name="paid_amount" id="paid_amount" value='<?php echo $paidamt;?>' /></td>
            </tr>
            
            <tr >
                <td>Redeem Used</td>
                <td> <input type="text" name="re_amount" id="re_amount" value='<?php echo $reamt;?>' /></td>
            </tr>
            
            <tr>
                <td>Payment Type</td>
                <td>
                    <select id="payment" name="payment" onchange="Order_Payment();">
                        <option value="000">Select Payment</option>
                        <option value="0">Cash</option>
                        <option value="1">Card</option>
                        <option value="2">Redeem</option>
                        <option value="3">Cheque</option>
                        <option value="4">Skip With Reason</option>
                        
                    </select>
                </td>
            </tr> 
            
            <tr id="amount" style="display: none;">
                <td>Amount</td>
                <td> <input type="text" id="inp_amount" name="inp_amount" value="" /></td>
            </tr>
            <tr id="chk" style="display: none;">
                <td>Cheque No</td>
                <td><input type="text" id="inp_chkno" name="inp_chkno" value=""/></td>
            </tr>
            <tr id="chkacc" style="display: none;">
                <td>Account No</td>
                <td><input type="text" id="inp_accno" name="inp_accno" value=""/></td>
            </tr>
            <tr id="chkbank" style="display: none;" >
                <td>Bank Name</td>
                <td><input type="text" id="inp_bank" name="inp_bank" value=""/></td>
            </tr>
            <tr id="chkbranch" style="display: none;" >
                <td>Branch</td>
                <td><input type="text" id="inp_branch" name="inp_branch" value=""/></td>
            </tr>
            
            <tr id="reason" style="display: none;">
                <td>Reason</td>
                <td><input type="text" id="inp_reason" name="inp_reason" value=""/></td>
            </tr>
            
            
        <tr><td colspan="100%" class='frmlblTd cen' >
                <input type="submit" value="Modify" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
       
    </form>
    </center>
</div>

<script>
function Order_Payment(){
   var payment  = jQuery("#payment").val();
   if(payment == '0' || payment == '2'){
       jQuery("#reason").hide();
       jQuery("#chk").hide();
       jQuery("#chkacc").hide();
       jQuery("#chkbranch").hide();
       jQuery("#chkbank").hide();
       jQuery("#amount").show();
   }else if(payment == '3'){
       jQuery("#reason").hide();
       jQuery("#chk").show();
       jQuery("#chkacc").show();
       jQuery("#chkbranch").show();
       jQuery("#chkbank").show();
       jQuery("#amount").show();
   }else if(payment == '4'){
       jQuery("#reason").show();
       jQuery("#chk").hide();
       jQuery("#chkacc").hide();
       jQuery("#chkbranch").hide();
       jQuery("#chkbank").hide();
       jQuery("#amount").hide();
   }else if(payment == '1'){
       jQuery("#reason").hide();
       jQuery("#chk").hide();
       jQuery("#chkacc").hide();
       jQuery("#chkbranch").hide();
       jQuery("#chkbank").hide();
       jQuery("#amount").show();
   }else{
       jQuery("#reason").hide();
       jQuery("#chk").hide();
       jQuery("#chkacc").hide();
       jQuery("#chkbranch").hide();
       jQuery("#chkbank").hide();
       jQuery("#amount").hide();
   }
}
</script>