<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
  #billmaster_filter{display: none}
  .dataTables_length{display: none}
</style>
<br/>
<div class='container' >
  <div style="float:right;">
    <?php if (!isset($_SESSION['transporterid']) && empty($_SESSION['transporterid'])) { ?>
      <a href="tms.php?pg=bills">  <button class="btn-primary" style="margin:5px; width:auto; display: inline-block;">Add Bill <img src="../../images/show.png"></button></a>
    <?php } ?>
  </div>
</div>
<div class='entry' >
  <center>
    <table class='display table table-bordered table-striped table-condensed' id="billmaster" >
      <thead>
        <tr>
          <td><input type="text" class='search_init' style="width:80%;" name='delievry_id'  autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='factory' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='depot' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='transporter' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='vehicle' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='skumaping' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='weight' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='weight' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>

        </tr>
        <tr class='dtblTh'>
          <th >Bill Location</th>
          <th >Vendor</th>
          <th >Bill No</th>
          <th>Bill Date</th>
          <th>Bill Received Date</th>
          <th >Total Bill Amount</th>
          <th >Due Days</th>
          <th>Billing Status</th>
          <th>Bill Sent Date</th>
          <th >Total Custody</th>
          <th >Payment Status</th>
          <th >Payment Done In Days</th>
          <th >Payment Bucket</th>
          <th>MDLZ Remark</th>
          <th>Vendor Remark</th>
          <th>View</th>
          <th>Edit</th>
          <th>Delete</th>
        </tr>
      </thead>
    </table>
  </center>
</div>
<?php
//print_r($proposed_indents);
?>
<script type='text/javascript'>
  var data = <?php echo json_encode($billDetails); ?>;
  var tableId = 'billmaster';
  var tableCols = [{"mData": "invoice_location"}
    , {"mData": "vendor"}
    , {"mData": "bill_no"}
    , {"mData": "bill_date"}
    , {"mData": "bill_received_date"}
    , {"mData": "final_bill_amount"}
    , {"mData": "due_days"}
    , {"mData": "billing_status"}
    , {"mData": "bill_sent_date"}
    , {"mData": "total_custody"}
    , {"mData": "payment_status"}
    , {"mData": "payment_done"}
    , {"mData": "payment_bucket"}
    , {"mData": "mdlz_remark"}
    , {"mData": "vendor_remark"}
    , {"mData": "view"}
    , {"mData": "edit"}
    , {"mData": "delete"}
  ];
</script>
