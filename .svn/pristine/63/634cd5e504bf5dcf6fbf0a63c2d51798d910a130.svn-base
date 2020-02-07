<?php include '../panels/header.php'; ?>
<?php include 'pickup_functions.php'; ?>
<?php
$max_columns = 8;
$max_row = 2000;
$column_names = array(
   'A' => 'vendorname',
   'B' => 'vendorcompany',
   'C' => 'rediff',
   'D' => 'paytm',
   'E' => 'phone1',
   'F' => 'phone2',
   'G' => 'address',
   'H' => 'pincode'
);
$valid_file = array('xls', 'xlsx');
$valid_size = 2000000;
?>
<script type='text/javascript' src='../../scripts/exception.js'></script>
<script>
  var valid_file_xls =<?php echo json_encode($valid_file); ?>;
  var valid_size_xls =<?php echo $valid_size; ?>;
</script>
<div class="entry">
  <center>
    <?php
    if (isset($_POST['uploadFile'])) {

      $file_status = file_upload_validation($valid_file, $valid_size, 'checkpointFile');
      if ($file_status != null) {
        echo $file_status;
      } else {

        $excel_data = get_excel_data($_FILES['checkpointFile']['tmp_name'], $max_columns, $max_row, $column_names);
        if (empty($excel_data)) {
          echo "No Data Found in Excel";
        } else {

          //$record_status = upload_checkpoint($excel_data);
          $record_status = upload_vendors($excel_data);

          echo "<span id='success_status' style='font-weight:bold;color:green;'>Uploaded successfuly</span><br/>";
          echo "<span style='font-weight:bold;color:blue;'>{$record_status['added']} Orders added, {$record_status['skipped']} Orders skipped.</span>";
        }
      }
    }
    ?>
    <br/>
    <form enctype="multipart/form-data" method="post" class="form-horizontal well" onsubmit='return validate_upload("checkpointFile");' style="width:70%;">

      <table class="table table-bordered">
        <thead>
          <tr>
            <th colspan="100%">Import Vendors</th>
          </tr
        </thead>
        <tbody>
          <tr>
            <td colspan="100%">
              <span class="add-on"> Import Vendors Using Excel Sheet (Format - .xls, .xlsx).
                <span class="mandatory">*</span>

              </span>
            </td>

          </tr>
          <tr>
            <td colspan="100%" style="display:none;text-align:center;" id='StatusTD'>
              <span id="loading" style='display:none;'>Loading....</span>
              <span id="Status" ></span>
            </td>
          </tr>
          <tr>
            <td><span class="add-on">Upload File <span class="mandatory">*</span></span></td>
            <td><input type="file" required="" id="checkpointFile" name="checkpointFile"></td>
          </tr>
          <tr>
            <td style="text-align:center;" colspan="100%"><button name="uploadFile" class="btn btn-primary" type="submit">Upload</button></td>
          </tr>

          <tr>
            <td colspan="100%"><span class="add-on"> Please Download The Sample Excel Sheet For Import Vendors. <span class="mandatory">*</span>  <a href='Vendors.xls' target="_blank">  Download Excel Sheet</a></span></td>

          </tr>

        </tbody>
      </table>
    </form>

  </center>
</div>
<?php include '../panels/footer.php'; ?>
