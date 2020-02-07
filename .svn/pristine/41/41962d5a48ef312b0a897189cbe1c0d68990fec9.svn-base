<style>
  #depotmaster_filter{display: none}
  .dataTables_length{display: none}
  .ajax_response_9{
    margin-left: 90px;
  }
  .error{
    color: red;
  }
</style>
<br/>
<div class='container' >
  <center>
    <form class="form-horizontal well" id="frmVendorPayable" name="frmVendorPayable" method="post" action="">
      <input type="hidden" name="transactionid" id="transactionid" value="0"/>
      <table style="width:80%; border: none;" >
        <tr>
          <td colspan="4">
            ADD BILLS
          </td>
        </tr>
        <tr>
          <td>
            <span class="add-on">Bill Type </span>
          </td>
          <td>
            <select name="bill_type" id="bill_type">
              <?php echo $billlist; ?>
            </select>
          </td>
          <td>
            <span class="add-on">Invoice Location </span>
          </td>
          <td>
            <!--
            <input type="text" name="invoice_location" id="factory_name" placeholder="Invoice Location" autocomplete="off">
            <input type="hidden" name="invoice_location_id" id="factoryid" value="">
            -->
            <?php
            if (isset($_SESSION['factoryid']) && !empty($_SESSION['factoryid'])) {
              $objFactoty = new Factory();
              $objFactoty->customerno = $_SESSION["customerno"];
              $objFactoty->factoryid = $_SESSION['factoryid'];
              $plant = get_factory($objFactoty);
              ?>
              <input type="text" style="width: 120px;" name="invoice_location" id="factory_name" value="<?php echo $plant[0][factoryname] ?>" autocomplete="off" readonly=""/>
              <input type="hidden" name="invoice_location_id" id="factoryid" value="<?php echo $_SESSION['factoryid'] ?>" maxlength="50"/>
              <?php
            } else {
              ?>
              <input type="text" style="width: 120px;" name="invoice_location" id="factory_name" value="" autocomplete="off"/>
              <input type="hidden" name="invoice_location_id" id="factoryid" value=""/>
              <?php
            }
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <span class = "add-on">Depot / Warehouse </span>
          </td>
          <td>
            <input type = "text" name = "depot" id = "depot_name" placeholder = "Depot / Warehouse" autocomplete = "off">
            <input type = "hidden" name = "depot_id" id = "depotid" value = "">
          </td>
          <td>
            <span class = "add-on">Vendor </span>
          </td>
          <td>
            <input type = "text" name = "vendor" id = "transporter_name" placeholder = "Vendor" autocomplete = "off">
            <input type = "hidden" name = "vendor_id" id = "transporterid" value = "">
          </td>
        </tr>
        <tr>
          <td>
            <span class = "add-on">Bill No </span>
          </td>
          <td>
            <input type = "text" name = "bill_no" id = "bill_no" placeholder = "Bill No">
          </td>
          <td>
            <span class = "add-on">Bill Date </span>
          </td>
          <td>
            <input type = "text" name = "bill_date" id = "bill_date" value = "" onChange = "checkBillStatus();"/>
          </td>
        </tr>
        <tr>
          <td>
            <span class = "add-on">Description If Any </span>
          </td>
          <td colspan = "3">
            <textarea name = "description" id = "description" rows = "2" cols = '72' maxlength = "500" onkeyup = "countChar(this)"></textarea>
            <div id = "charNum"></div>
          </td>
        </tr>
        <tr>
          <td colspan = "4">ADD LR No.
            <a href = "javascript:void(0);" id = "addLrNo">&nbsp;
              <img src = '../../images/show.png' title = "ADD LR NO" alt = "ADD LR NO"/></a>
          </td>
        </tr>
        <tr>
          <td colspan = "4">
            <div id = "lrDetails" style = "width:80%; margin-left:2%; display: none;">
              <div style = "float: left;width: 20%;border: 1px solid #ccc;padding: 5px;">Delivery No</div>
              <div style = "float: left;width: 20%;border: 1px solid #ccc;padding: 5px;">LR No</div>
              <div style = "float: left;width: 25%;border: 1px solid #ccc;padding: 5px;">Total Delivery Amount</div>
              <div style = "float: left;width: 9%;border: 1px solid #ccc;padding: 5px;" >Edit</div>
              <div style = "float: left;width: 9%;border: 1px solid #ccc;padding: 5px;">Delete</div>
              <div style = "clear: both;"></div>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan = "4"></td>
        </tr>
        <tr>
          <td colspan = "4"></td>
        </tr>
        <tr>
          <td>
            <span class = "add-on">Final Bill Amount </span>
          </td>
          <td>
            <input type = "text" name = "final_bill_amt" id = "final_bill_amt" value = ""/>
          </td>
          <td>
          </td>
          <td>
          </td>
        </tr>
        <tr>
          <td>
            <span class = "add-on">Bill Received Date </span>
          </td>
          <td>
            <input type = "text" name = "bill_received_date" id = "bill_received_date" value = "" onChange = "checkBillStatus();"/>
          </td>
          <td>
          </td>
          <td>
          </td>
        </tr>
        <tr>
          <td>
            <span class = "add-on">Bill Processed Date </span>
          </td>
          <td>
            <input type = "text" name = "bill_processed_date" id = "bill_processed_date" value = "" onChange = "checkBillStatus();"/>
          </td>
          <td>
            <span class = "add-on">Bill Sent Date </span>
          </td>
          <td>
            <input type = "text" name = "bill_sent_date" id = "bill_sent_date" value = "" onChange = "checkBillStatus();"/>
          </td>
        </tr>
        <tr>
          <td>
            <span class = "add-on">GRN No. </span>
          </td>
          <td>
            <input type = "text" name = "grn_no" id = "grn_no" value = ""/>
          </td>
          <td>
            <span class = "add-on">PO No. </span>
          </td>
          <td>
            <input type = "text" name = "po_no" id = "po_no" value = ""/>
          </td>
        </tr>
        <tr>
          <td>
            <span class = "add-on">Remarks Regarding Bill </span>
          </td>
          <td>
            <textarea name = "remarks_regarding_bill" id = "remarks_regarding_bill" rows = "3" cols = "30"></textarea>
          </td>
          <td>
            <span class = "add-on">Remarks Regarding Settlement </span>
          </td>
          <td>
            <textarea name = "remarks_regarding_settlement" id = "remarks_regarding_settlement" rows = "3" cols = "30"></textarea>
          </td>
        </tr>
        <tr>
          <td colspan = "4"><hr/></td>
        </tr>
        <tr>
          <td>
            <span class = "add-on">Due Days </span>
          </td>
          <td>
            <input type = "text" name = "due_days" id = "due_days" value = "" readonly = ""/>
          </td>
          <td>
            <span class = "add-on">Billing Status </span>
          </td>
          <td>
            <input type = "text" name = "billing_status" id = "billing_status" value = "" readonly = ""/>
          </td>
        </tr>
        <tr>
          <td>
            <span class = "add-on">Due Status </span>
          </td>
          <td>
            <input type = "text" name = "due_status" id = "due_status" value = "" readonly = ""/>
          </td>
          <td>
            <span class = "add-on">Days For Receiving Bills </span>
          </td>
          <td>
            <input type = "text" name = "day_for_receiving_bill" id = "day_for_receiving_bill" value = "" readonly = ""/>
          </td>
        </tr>
        <tr>
          <td>
            <span class = "add-on">Process Days</span>
          </td>
          <td>
            <input type = "text" name = "process_day" id = "process_day" value = "" readonly = ""/>
          </td>
          <td>
            <span class = "add-on">Custody </span>
          </td>
          <td>
            <input type = "text" name = "custody" id = "custody" value = "" readonly = ""/>
          </td>
        </tr>
        <tr>
          <td>
            <span class = "add-on">Total Custody Of Bills</span>
          </td>
          <td>
            <input type = "text" name = "total_custody" id = "total_custody" value = "" readonly = ""/>
          </td>
          <td>
            <span class = "add-on">Payment Done In Days </span>
          </td>
          <td>
            <input type = "text" name = "payment_done" id = "payment_done" value = "" readonly = ""/>
          </td>
        </tr>
        <tr>
          <td>
            <span class = "add-on">Month Sent</span>
          </td>
          <td>
            <input type = "text" name = "month_sent" id = "month_sent" value = "" readonly = ""/>
          </td>
          <td>
            <span class = "add-on">Year Sent </span>
          </td>
          <td>
            <input type = "text" name = "year_sent" id = "year_sent" value = "" readonly = ""/>
          </td>
        </tr>
        <tr>
          <td>
            <span class = "add-on">Payment Bucket</span>
          </td>
          <td>
            <input type = "text" name = "payment_bucket" id = "payment_bucket" value = "" readonly = ""/>
          </td>
          <td>
            <span class = "add-on">Payment Status </span>
          </td>
          <td>
            <input type = "text" name = "payment_status" id = "payment_status" value = "" readonly = ""/>
          </td>
        </tr>
        <tr>
          <td colspan = "4" style = "text-align: center;">
            <input type = "button" class = "btn btn-primary" disabled = "" id = "save_bill_payable" name = "save_bill_payable" value = "Save To Bill Tracker" onclick = "saveMainTransaction();" />
            <input type = "button" class = "btn btn-primary" id = "save_draft" name = "save_draft" value = "Save Draft"/>
            <input type = "reset" class = "btn btn-danger" id = "cancel_bill_payable" name = "cancel_bill_payable" value = "Cancel"/>
          </td>
        </tr>
      </table>
    </form>
  </center>
</div>
<?php
include 'addlrdetails.php';
?>
<script type="text/javascript" src="./../../scripts/validation/jquery.validate.js"></script>
