<?php
$max_columns = 7;
$max_row = 2000;
$column_names = array(
   'A' => 'skucode',
   'B' => 'description',
   'C' => 'type',
   'D' => 'volume',
   'E' => 'weight',
   'F' => 'netgross'
);
$valid_file = array('xls', 'xlsx');
$valid_size = 2000000;
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<script>
  var valid_file_xls =<?php echo json_encode($valid_file); ?>;
  var valid_size_xls =<?php echo $valid_size; ?>;
</script>
<style>
  #skumaster_filter{display: none}
  .dataTables_length{display: none}
  .ajax_response_8{
    margin-left: 640px;
  }
</style>
<br/>
<div class='container' >

  <center>
    <form style='display:inline;width: 70%;' method="post" action="action.php?action=add-sku" >
      <div class="input-prepend ">
        <span class="add-on" style="text-align: left;">Code</span>
        <input type="text" style="width:105px;" name="skucode" id="skucode" value="" maxlength="100"/>
        <span class="add-on" style="text-align: ">Description</span>
        <input type="text" name="skudescription" id="skudescription" value="" maxlength="250"/>
        <span class="add-on" style="text-align: ">Type</span>
        <input type="text" style="width: 120px;" name="type_name" id="type_name" value="" maxlength="50" autocomplete="off"/>
        <input type="hidden" name="typeid" id="typeid" value="" maxlength="50"/>
        <br/>
        <br/>
        <span class="add-on" style="text-align: ">Vol(M3)</span>
        <input type="text" style="width:90px;" name="skuvolume" id="skuvolume" value="" maxlength="11"/>

        <span class="add-on" style="text-align: ">Weight (kgs)</span>
        <input type="text" style="width:90px;" name="skuweight" id="skuweight" value="" maxlength="11"/>

        <span class="add-on" style="text-align: ">Net-Gross Percent</span>
        <input type="text" style="width:90px;" name="netgrosspercent" id="netgrosspercent" value="" maxlength="6"/>

        <input style='display:inline;' type='submit' class="btn  btn-primary" value='  Add SKU  '/>
      </div>
    </form>
    <div>

      <form enctype="multipart/form-data" method="post" onsubmit='return validate_upload("locationFile");' style="width:65%;">

        <table class="table">
          <tbody>
            <tr>
              <td colspan="100%" style="display:none;text-align:center;" id='StatusTD'>
                <span id="loading" style='display:none;'>Loading....</span>
                <span id="Status" ></span>
              </td>
            </tr>
            <tr>
              <td><span>Import SKU<span class="mandatory">*</span></span></td>
              <td><input type="file" required="" id="locationFile" name="locationFile"></td>
              <td><button name="uploadFile" class="btn btn-primary" type="submit">Upload</button></td>
              <td><a href='sku.xls' target="_blank" title="Download SKU Excel">Download</a></td>
            </tr>


          </tbody>
        </table>
      </form>
      <?php
      if (isset($_POST['uploadFile'])) {
        $file_status = file_upload_validation($valid_file, $valid_size, 'locationFile');
        if ($file_status != null) {
          echo $file_status;
        } else {

          $excel_data = get_excel_data($_FILES['locationFile']['tmp_name'], $max_columns, $max_row, $column_names);
          if (empty($excel_data)) {
            echo "No Data Found in Excel";
          } else {
            $record_status = upload_sku($excel_data);
            //echo "<span id='success_status' style='font-weight:bold;color:green;'>Uploaded successfuly</span><br/>";
            //echo "<span style='font-weight:bold;color:blue;'>{$record_status['added']} Route Checkpoint added, {$record_status['skipped']} Route Checkpoint skipped.</span>";
            header('Location:tms.php?pg=view-sku');
            /*
              echo '<script type="text/javascript">
              $("#dtcontainer_routecheckpoint").load();
              </script>';
             *
             */
          }
        }
      }
      ?>
    </div>
  </center>

</div>

<div class='container' >
  <center>
    <input type='hidden' id='forTable' value='vehicleMaster'/>
    <table class='display table table-bordered table-striped table-condensed' id="skumaster" style="width: 90%;" >
      <thead>
        <tr>
          <td><input type="text" class='search_init' style="width:90%;" name='plant_id'  autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_name' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
          <td colspan="2"></td>



        </tr>
        <tr class='dtblTh'>
          <th>SKU Code</th>
          <th >Description</th>
          <th >Type</th>
          <th >Volume</th>
          <th >Weight</th>
          <th >Net - Gross Percent</th>
          <th >Edit</th>
          <th >Delete</th>

          <!--
          <th></th>
          -->
        </tr>
      </thead>
    </table>

  </center>
</div>

<script type='text/javascript'>
  var data = <?php echo json_encode($skus); ?>;
  var tableId = 'skumaster';
  var tableCols = [
    {"mData": "skucode"}
    , {"mData": "sku_description"}
    , {"mData": "type"}
    , {"mData": "volume"}
    , {"mData": "weight"}
    , {"mData": "netgross"}
    , {"mData": "edit"}
    , {"mData": "delete"}


  ];
</script>