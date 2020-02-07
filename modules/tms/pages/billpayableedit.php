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
      <input type="hidden" name="transactionid" id="transactionid" value="<?php echo$billDetailsRecord['billid']; ?>"/>
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
            <input type="text" name="invoice_location" id="factory_name" value="<?php echo $billDetailsRecord['invoice_location']; ?>" placeholder="Invoice Location" autocomplete="off">
            <input type="hidden" name="invoice_location_id" id="factoryid" value="<?php echo $billDetailsRecord['invoice_location_id']; ?>">
          </td>
        </tr>
        <tr>
          <td>
            <span class="add-on">Depot / Warehouse </span>
          </td>
          <td>
            <input type="text" name="depot" id="depot_name" value="<?php echo $billDetailsRecord['depotname']; ?>" placeholder="Depot / Warehouse" autocomplete="off">
            <input type="hidden" name="depot_id" id="depotid" value="<?php echo $billDetailsRecord['depot_location_id']; ?>">
          </td>
          <td>
            <span class="add-on">Vendor </span>
          </td>
          <td>
            <input type="text" name="vendor" id="transporter_name" value="<?php echo $billDetailsRecord['vendorname']; ?>" placeholder="Vendor" autocomplete="off">
            <input type="hidden" name="vendor_id" id="transporterid" value="<?php echo $billDetailsRecord['vendor_id']; ?>">
          </td>
        </tr>
        <tr>
          <td>
            <span class="add-on">Bill No </span>
          </td>
          <td>
            <input type="text" name="bill_no" id="bill_no" value="<?php echo $billDetailsRecord['bill_no']; ?>" placeholder="Bill No">
          </td>
          <td>
            <span class="add-on">Bill Date </span>
          </td>
          <td>
            <input type="text"  name="bill_date" id="bill_date" value="<?php echo date('d-m-Y', strtotime($billDetailsRecord['bill_date'])); ?>" onChange="checkBillStatus();"/>
          </td>
        </tr>
        <tr>
          <td>
            <span class="add-on">Description If Any </span>
          </td>
          <td colspan="3">
            <textarea name="description" id="description" rows="2" cols='72' maxlength="500"  onkeyup="countChar(this)"><?php echo $billDetailsRecord['description'] ?></textarea>
            <div id="charNum"></div>
          </td>
        </tr>
        <tr>
          <td colspan="4">ADD LR No.
            <a href="javascript:void(0);" id="editLrNo">&nbsp;<img src='../../images/show.png' title="ADD LR NO" alt="ADD LR NO"/></a>
          </td>
        </tr>
        <tr>
          <td colspan="4">
            <div id="lrDetails" style="width:80%; margin-left:2%;">
              <div style="float: left;width: 20%;border: 1px solid #ccc;padding: 5px;">Delivery No</div>
              <div style="float: left;width: 20%;border: 1px solid #ccc;padding: 5px;">LR No</div>
              <div style="float: left;width: 25%;border: 1px solid #ccc;padding: 5px;">Total Delivery Amount</div>
              <div style="float: left;width: 9%;border: 1px solid #ccc;padding: 5px;" >Edit</div>
              <div style="float: left;width: 9%;border: 1px solid #ccc;padding: 5px;">Delete</div>
              <div style="clear: both;"></div>

              <?php
              // code for lr details
              if (isset($lrDetails) && !empty($lrDetails)) {
                $lrlist = '';
                foreach ($lrDetails as $lr) {
                  if ($lr['delivery_no'] == '') {
                    $lr['lr_no'] = 'N/A';
                  }
                  if ($lr['lr_no'] == '') {
                    $lr['lr_no'] = 'N/A';
                  }
                  $lrlist .='<div style="float: left;width: 20%;border: 1px solid #ccc;padding: 5px;" id="delivery_no_' . $lr['lrid'] . '">' . $lr['delivery_no'] . '</div>';
                  $lrlist.='<div style="float: left;width: 20%;border: 1px solid #ccc;padding: 5px;" id="lr_no_' . $lr['lrid'] . '">' . $lr['lr_no'] . '</div>';
                  $lrlist.='<div style="float: left;width: 25%;border: 1px solid #ccc;padding: 5px;" class="billamt" id="total_amount_' . $lr['lrid'] . '">' . $lr['total_delivery_amount'] . '</div>';
                  $lrlist.='<div style="float: left;width: 10%;border: 1px solid #ccc;padding: 2px;" class="edit_id" id="edit_' . $lr['lrid'] . '"><img src="../../images/edit_black.png" class="editimage" onclick="editLr(' . $lr['lrid'] . ');" /></div>';
                  $lrlist.='<div style="float: left;width: 10%;border: 1px solid #ccc;padding: 1px;" class="delete_id" id="delete_' . $lr['lrid'] . '"><img src="../../images/boxdelete.png" class="deleteimage" onclick="deleteLr(' . $lr['lrid'] . ');"/></div>';
                  $lrlist.='<div style="clear: both;"></div>';
                }
                echo $lrlist;
              }
              ?>


            </div>
          </td>
        </tr>
        <tr>
          <td colspan="4"></td>
        </tr>
        <tr>
          <td colspan="4"></td>
        </tr>
        <tr>
          <td>
            <span class="add-on">Final Bill Amount </span>
          </td>
          <td colspan="3">
            <input type="text"  name="final_bill_amt" id="final_bill_amt" value="<?php echo $billDetailsRecord['final_bill_amount'] ?>"/>
          </td>
        </tr>
        <tr>
          <td>
            <span class="add-on">Bill Received Date </span>
          </td>
          <td colspan="3">
            <input type="text"  name="bill_received_date" id="bill_received_date" value="<?php echo date('d-m-Y', strtotime($billDetailsRecord['bill_received_date'])); ?>" onChange="checkBillStatus();"/>
          </td>
        </tr>
        <tr>
          <td>
            <span class="add-on">Bill Processed Date </span>
          </td>
          <td>
            <input type="text"  name="bill_processed_date" id="bill_processed_date" value="<?php echo date('d-m-Y', strtotime($billDetailsRecord['bill_processed_date'])); ?>" onChange="checkBillStatus();"/>
          </td>
          <td>
            <span class="add-on">Bill Sent Date </span>
          </td>
          <td>
            <input type="text"  name="bill_sent_date" id="bill_sent_date" value="<?php echo date('d-m-Y', strtotime($billDetailsRecord['bill_sent_date'])); ?>" onChange="checkBillStatus();"/>
          </td>
        </tr>
        <tr>
          <td>
            <span class="add-on">GRN No. </span>
          </td>
          <td>
            <input type="text"  name="grn_no" id="grn_no" value="<?php echo $billDetailsRecord['grn_no']; ?>"/>
          </td>
          <td>
            <span class="add-on">PO No. </span>
          </td>
          <td>
            <input type="text"  name="po_no" id="po_no" value="<?php echo $billDetailsRecord['po_no']; ?>"/>
          </td>
        </tr>
        <tr>
          <td>
            <span class="add-on">Remarks Regarding Bill </span>
          </td>
          <td>
            <textarea name="remarks_regarding_bill" id="remarks_regarding_bill" rows="3" cols="30">
              <?php echo $billDetailsRecord['bill_remarks']; ?>
            </textarea>
          </td>
          <td>
            <span class="add-on">Remarks Regarding Settlement </span>
          </td>
          <td>
            <textarea name="remarks_regarding_settlement" id="remarks_regarding_settlement" rows="3" cols="30"><?php echo $billDetailsRecord['settelement_remarks']; ?>
            </textarea>
          </td>
        </tr>
        <tr>
          <td colspan="4"><hr/></td>
        </tr>
        <tr>
          <td>
            <span class="add-on">Due Days </span>
          </td>
          <td>
            <input type="text"  name="due_days" id="due_days" value="<?php echo $billDetailsRecord['due_days']; ?>" readonly=""/>
          </td>
          <td>
            <span class="add-on">Billing Status </span>
          </td>
          <td>
            <input type="text"  name="billing_status" id="billing_status" value="<?php echo $billDetailsRecord['billing_status']; ?>" readonly=""/>
          </td>
        </tr>
        <tr>
          <td>
            <span class="add-on">Due Status </span>
          </td>
          <td>
            <input type="text"  name="due_status" id="due_status" value="<?php echo $billDetailsRecord['due_status']; ?>" readonly=""/>
          </td>
          <td>
            <span class="add-on">Days For Receiving Bills </span>
          </td>
          <td>
            <input type="text"  name="day_for_receiving_bill" id="day_for_receiving_bill" value="<?php echo $billDetailsRecord['days_for_receiving_bills']; ?>" readonly=""/>
          </td>
        </tr>
        <tr>
          <td>
            <span class="add-on">Process Days</span>
          </td>
          <td>
            <input type="text"  name="process_day" id="process_day" value="<?php echo $billDetailsRecord['process_days']; ?>" readonly=""/>
          </td>
          <td>
            <span class="add-on">Custody </span>
          </td>
          <td>
            <input type="text"  name="custody" id="custody" value="<?php echo $billDetailsRecord['custody']; ?>" readonly=""/>
          </td>
        </tr>
        <tr>
          <td>
            <span class="add-on">Total Custody Of Bills</span>
          </td>
          <td>
            <input type="text"  name="total_custody" id="total_custody" value="<?php echo $billDetailsRecord['total_custody']; ?>" readonly=""/>
          </td>
          <td>
            <span class="add-on">Payment Done In Days </span>
          </td>
          <td>
            <input type="text"  name="payment_done" id="payment_done" value="<?php echo $billDetailsRecord['payment_done']; ?>" readonly=""/>
          </td>
        </tr>
        <tr>
          <td>
            <span class="add-on">Month Sent</span>
          </td>
          <td>
            <input type="text"  name="month_sent" id="month_sent" value="<?php echo $billDetailsRecord['month_sent']; ?>" readonly=""/>
          </td>
          <td>
            <span class="add-on">Year Sent </span>
          </td>
          <td>
            <input type="text"  name="year_sent" id="year_sent" value="<?php echo $billDetailsRecord['year_sent']; ?>" readonly=""/>
          </td>
        </tr>
        <tr>
          <td>
            <span class="add-on">Payment Bucket</span>
          </td>
          <td>
            <input type="text"  name="payment_bucket" id="payment_bucket" value="<?php echo $billDetailsRecord['payment_bucket']; ?>" readonly=""/>
          </td>
          <td>
            <span class="add-on">Payment Status </span>
          </td>
          <td>
            <input type="text"  name="payment_status" id="payment_status" value="<?php echo $billDetailsRecord['payment_status']; ?>" readonly=""/>
          </td>
        </tr>
        <tr>
          <td colspan="4" style="text-align: center;">
            <input type="button" class="btn btn-primary" id="save_bill" name="save_bill" value="Save Bill"/>
            <input type="reset" class="btn btn-danger" id="cancel_bill_payable_edit" name="cancel_bill_payable_edit" value="Cancel"/>
          </td>
        </tr>
      </table>
    </form>
  </center>
</div>
<?php
include 'editlrdetails.php';
?>
<script type="text/javascript" src="./../../scripts/validation/jquery.validate.js"></script>
