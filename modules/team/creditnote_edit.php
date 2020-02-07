<?php
error_reporting(E_ALL);

include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/Date.php");
include_once("route_modifyCreditNote.php");
$_scripts[] = "../../scripts/jquery.min.js";
$_scripts_custom[] = "../../scripts/autocomplete/jquery-ui.min.js";
$_stylesheets[] = "../../scripts/autocomplete/jquery-ui.min.css";

class CreditNoteEdit {
    
}

$db = new DatabaseManager();
$credit_note_id = $_GET['credit_note_id'];
$creditNoteDetails = Array();
$creditNoteDetails = get_creditNote($credit_note_id);
// print_r($creditNoteDetails); exit;

include("header.php");
?>

<link rel="stylesheet" href="../../css/invoicePayment.css">
<div class="panel">
  <div class="paneltitle" align="center">Credit Note  <?php echo $row['invoiceno'];?> </div> 
  <div class="panelcontents">
    <div class="center">
      <form name="update_credit_note" id="update_credit_note">
     <label>Customer</label>
        <input type="text" name="customername" id="customername" size="30" value="<?php echo ($creditNoteDetails->customercompany)?>" readonly autocomplete="on" onkeypress="getCustomer();"/>
      <label>Ledger</label>
        <select name="ledger_name" id="ledger_name">
            <option value="<?php echo ($creditNoteDetails->ledgerid); ?>"
              <?php echo "selected";?> >
              <?php echo ($creditNoteDetails->ledgername); ?>
            </option>
        </select> 
        <label>Invoice Number </label>
            <select name="invoiceno" id="invoiceno" onchange="changeInvoiceNo(this)">
              <option value="<?php echo ($creditNoteDetails->invoiceid); ?>" 
                <?php echo "selected";?> >
                <?php echo ($creditNoteDetails->invoiceno); ?>
              </option>
            </select> 
          <label>Invoice Amount</label>
          <input type="text" name="inv_amount" id="inv_amount"  autocomplete="off" value="<?php echo ($creditNoteDetails->invoice_amount)?>" readonly />
          <div>
          <label>Invoice Date</label>
         <input type="text" name="inv_date" id="inv_date"  autocomplete="off" value="<?php echo ($creditNoteDetails->invoice_date); ?>" readonly />
          <label>Credit Amount</label>
          <input type="text" name="credit_amount" id="credit_amount" size="30" value="<?php echo ($creditNoteDetails->credit_amount);?>" autocomplete="off" />
          <label>Reason</label>
          <input type="text" name="reason" id="reason" size="30" value="<?php echo ($creditNoteDetails->reason);?>" autocomplete="off" placeholder="Enter Reason"/>
          <label>Status</label>
          <!-- onchange="resetStatus(this.value) -->
            <select name="edit_status" id="edit_status">
             
                    <?php if($creditNoteDetails->status=='requested') { ?>
                        <option value="1"> Requested </option>
                        <option value="2"> Approved </option>
                        <option value="3"> Reject </option>
                    <?php } else if($creditNoteDetails->status=='approved') { ?>
                         <option value="2"> Approved </option> 
                         <option value="1"> Requested </option>
                         <option value="3"> Reject </option>
                     <?php } else { ?>
                         <option value="1"> Requested </option>
                         <option value="2"> Approved </option>
                         <option value="3"> Reject </option>
                     <?php } ?>
                    
            
            </select> 
        </div>
        <input type="hidden" name="credit_note_id" id="credit_note_id" value="<?php echo $credit_note_id ?>"/>
        <input type="hidden" name="customerno" id="customerno" value="<?php echo $creditNoteDetails->customerno; ?>"/>
        <input type="button" name="editcn" id="editcn" value="Update" onclick="updateCreditNote();" style="margin-left:40%;"/>
           
      </form>
    </div>
  </div>
</div>

<br/>

<?php
include("footer.php");
?>
<script src='../../scripts/team/credit_note.js'></script>
<!--  <script>
    function resetStatus(status){
alert(status); return false;
      $("#edit_status option:selected").removeAttr("selected");
        // var data = $("#item_master_details").serialize();
        // jQuery.ajax({
        //     type: "POST",
        //     url: "route_ajax.php",
        //     data: "insert_item_masterDetails=1&"+data,
        //     success: function(result){
        //         var response = JSON.parse(result);
        //         if(response.includes("0")){
        //             alert("Please Try Again.");
        //         }else{
        //             alert("Data Saved Successfully.");
        //         }
        //     }
        // });
    }
</script> -->
