<?php
$max_columns = 3;
$max_row = 2000;
$column_names = array(
   'A' => 'vehicleno',
   'B' => 'serialno',
   'C' => 'installedon',
);
$valid_file = array('xls', 'xlsx');
$valid_size = 2000000;
?>
<script type='text/javascript' src='../../scripts/exception.js'></script>
<script>
  jQuery(function () {
    jQuery('body').click(function () {
      jQuery('#success_status').hide();
    });
  });
  function show_error(text) {
    var conf = "<b style='color:red;font-weight:bold;'>" + text + "</b>";
    jQuery('#StatusTD').show();
    jQuery('#Status').html(conf);
    jQuery("#Status").fadeOut(3000);
  }

  function validate_upload() {
    var fileName = jQuery('#batteryFile').val();
    var validExtensions = <?php echo json_encode($valid_file); ?>;
    var fileExt = fileName.substr(fileName.lastIndexOf('.') + 1);

    if (jQuery.inArray(fileExt, validExtensions) == -1) {
      show_error("Invalid file type");
      return false;
    }
    var size = jQuery("#batteryFile")[0].files[0].size;
    var valid_size = <?php echo $valid_size; ?>//2 mb
    if (size > valid_size) {
      show_error("File Size cannot cannot exceed 2 MB");
      return false;
    }

    return true;
  }
</script>
<div class="container">

  <div class="row" >

    <div class="col-sm-12">
      <center>
        <?php
        if (isset($_POST['uploadFile'])) {

          $file_status = file_upload_validation($valid_file, $valid_size, 'batteryFile');
          if ($file_status != null) {
            echo $file_status;
          } else {
            include_once '../../lib/comman_function/PHPExcel.php';
            $excel_data = get_excel_data($_FILES['batteryFile']['tmp_name'], $max_columns, $max_row, $column_names);
            if (empty($excel_data)) {
              echo "No Data Found in Excel";
            } else {
              $record_status = upload_serialno($excel_data);

              echo "<span id='success_status' style='font-weight:bold;color:green;'>Uploaded successfuly</span><br/>";
              echo "<span style='font-weight:bold;color:blue;'>{$record_status['added']} Serial No. added, {$record_status['skipped']} Serial No. skipped.</span>";
            }
          }
        }
        ?>
        <br/>
        <form enctype="multipart/form-data" method="post" class="form-horizontal well "  style="width:70%;" onsubmit="return validate_upload();">

          <table class="table table-bordered">
            <thead>
              <tr>
                <th colspan="100%">Upload Serial Nos Details</th>
              </tr>
            </thead>
            <tbody>
              <tr id="StatusTD" style="display: none;">
                <td colspan="100%" id="Status" style="text-align: center;">
                </td>
              </tr>
              <tr>
                <td colspan="100%"><span class="add-on"> Upload Serial Nos Using Excel Sheet (Format - .xls, .xlsx).<span class="mandatory">*</span></span></td>

              </tr>
              <tr>
                <td><span class="add-on">Upload File <span class="mandatory">*</span></span></td>
                <td><input type="file" required="" id="batteryFile" name="batteryFile"></td>
              </tr>
              <tr>
                <td style="text-align:center;" colspan="100%"><button name="uploadFile" class="btn btn-primary" type="submit">Upload</button></td>
              </tr>

              <tr>
                <td colspan="100%"><span class="add-on"> Please Download The Sample Excel Sheet For Upload Serial Nos. <span class="mandatory">*</span>  <a href='pages/upload_files/Battery.xlsx' target="_blank">  Download Excel Sheet</a></span></td>

              </tr>

            </tbody>
          </table>
        </form>

      </center>
    </div>

  </div>

</div>