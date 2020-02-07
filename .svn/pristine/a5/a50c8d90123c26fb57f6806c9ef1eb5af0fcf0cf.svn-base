<?php
$max_columns = 7;
$max_row = 2000;
$column_names = array(
   'A' => 'Delivery No',
   'B' => 'LR No',
   'C' => 'Shipment No',
   'D' => 'Cost Document No',
   'E' => 'Truck Type',
   'F' => 'Route',
   'G' => 'Vehicle No'
);
$column_names_payment = array(
   'A' => 'Vendor Code',
   'B' => 'Document No', // billno
   'C' => 'Clearing Document No',
   'D' => 'Clearing Date',
   'E' => 'Reference No',
   'F' => 'Payment Status'
);
$valid_file = array('xls', 'xlsx');
$valid_size = 2000000;
?>
<div class="entry">
  <center>
    <?php
    if (isset($_POST['uploadFile'])) {
      $file_status = file_upload_validation($valid_file, $valid_size, 'shipmentFile');
      if ($file_status != null) {
        echo $file_status;
      } else {
        $excel_data = get_excel_data($_FILES['shipmentFile']['tmp_name'], $max_columns, $max_row, $column_names);
        if (empty($excel_data)) {
          echo "No Data Found in Excel";
        } else {
          $record_status = upload_shipment_data($excel_data);
          //print_r($record_status);
          echo "<span id='success_status' style='font-weight:bold;color:green;'>Uploaded successfuly</span><br/>";
          echo "<span style='font-weight:bold;color:blue;'>{$record_status['added']} Records Added, {$record_status['skipped']} Records Skipped.</span>";
        }
      }
    }
    ?>
    <br/>
    <form enctype="multipart/form-data" method="post" class="form-horizontal well " onsubmit='return validate_upload("locationFile");'  style="width:70%;">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th colspan="100%">Import Shipment Data</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="100%"><span class="add-on"> Import Orders Using Excel Sheet (Format - .xls, .xlsx).<span class="mandatory">*</span></span></td>
          </tr>
          <tr>
            <td colspan="100%" style="display:none;text-align:center;" id='StatusTD'>
              <span id="loading" style='display:none;'>Loading....</span>
              <span id="Status" ></span>
            </td>
          </tr>
          <tr>
            <td><span class="add-on">Upload File <span class="mandatory">*</span></span></td>
            <td><input type="file" required="" id="locationFile" name="shipmentFile" size="2"></td>
          </tr>
          <tr>
            <td style="text-align:center;" colspan="100%"><button name="uploadFile" class="btn btn-primary" type="submit">Upload</button></td>
          </tr>
          <tr>
            <td colspan="100%"><span class="add-on"> Please Download The Sample Excel Sheet For Import Shipment Data. <span class="mandatory">*</span>  <a href='shipment_dump.xls' target="_blank">  Download Excel Sheet</a></span></td>
          </tr>
        </tbody>
      </table>
    </form>
  </center>
</div>
<div class="entry">
  <center>
    <?php
    if (isset($_POST['uploadPaymentFile'])) {
      echo $file_status = file_upload_validation($valid_file, $valid_size, 'paymentFile');
      if ($file_status != null) {
        echo $file_status;
      } else {
        $excel_data = get_excel_data($_FILES['paymentFile']['tmp_name'], $max_columns, $max_row, $column_names_payment);
        if (empty($excel_data)) {
          echo "No Data Found in Excel";
        } else {
          $record_status = upload_payment_data($excel_data);
          //print_r($record_status);
          echo "<span id='success_status' style='font-weight:bold;color:green;'>Uploaded successfuly</span><br/>";
          echo "<span style='font-weight:bold;color:blue;'>{$record_status['added']} Records Added, {$record_status['skipped']} Records Skipped.</span>";
        }
      }
    }
    ?>
    <br/>
    <form enctype="multipart/form-data" method="post" class="form-horizontal well " onsubmit='return validate_upload("paymentFile");'  style="width:70%;">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th colspan="100%">Import Payment Data</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="100%"><span class="add-on"> Import Orders Using Excel Sheet (Format - .xls, .xlsx).<span class="mandatory">*</span></span></td>
          </tr>
          <tr>
            <td colspan="100%" style="display:none;text-align:center;" id='StatusTD'>
              <span id="loading" style='display:none;'>Loading....</span>
              <span id="Status" ></span>
            </td>
          </tr>
          <tr>
            <td><span class="add-on">Upload File <span class="mandatory">*</span></span></td>
            <td><input type="file" required="" id="paymentFile" name="paymentFile" size="2"></td>
          </tr>
          <tr>
            <td style="text-align:center;" colspan="100%"><button name="uploadPaymentFile" class="btn btn-primary" type="submit">Upload</button></td>
          </tr>
          <tr>
            <td colspan="100%"><span class="add-on"> Please Download The Sample Excel Sheet For Import Payment Data. <span class="mandatory">*</span>  <a href='Payment_Dump.xls' target="_blank">  Download Excel Sheet</a></span></td>
          </tr>
        </tbody>
      </table>
    </form>
  </center>
</div>
<script type='text/javascript' src='../../scripts/exception.js'></script>
<script>
      var valid_file_xls =<?php echo json_encode($valid_file); ?>;
      var valid_size_xls =<?php echo $valid_size; ?>;
      var data = <?php echo json_encode($factory_delivery); ?>;
      var tableId = 'deliverymaster';
      var tableCols = [
        {"mData": "factoryname"}
        , {"mData": "skucode"}
        , {"mData": "sku_description"}
        , {"mData": "depotname"}
        , {"mData": "date_required"}
        , {"mData": "netWeight"}
        , {"mData": "grossWeight"}
        , {"mData": "created_on"}
        , {"mData": "edit"}
        , {"mData": "delete"}
      ];
</script>