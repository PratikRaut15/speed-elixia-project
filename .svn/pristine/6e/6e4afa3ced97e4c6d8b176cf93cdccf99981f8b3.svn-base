<?php
$max_columns = 7;
$max_row = 2000;
$column_names = array(
   'A' => 'factory',
   'B' => 'sku',
   'C' => 'weight'
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
  #productionmaster_filter{display: none}
  .dataTables_length{display: none}
  .ajax_response_9{
    margin-left: 340px;
  }
  .ajax_response_5{
    margin-left: 135px;
  }
  .ajax_response_6{
    margin-left: 620px;
  }
</style>
<br/>
<div class='container' >
  <!--
  <div style="float:right;">
      <button class="btn-primary" style="margin:5px; width:auto; display: inline-block;" onclick="addVehicelType();">Add Vehicle <img src="../../images/show.png"></button>
  </div>
  -->
  <center>
    <form style='display:inline;width: 70%;' method="post" action="action.php?action=add-factoryproduction" >
      <div class="input-prepend ">

        <span class="add-on" style="text-align: ">Factory</span>
        <input type="text" name="factory_name" id="factory_name" value="" autocomplete="off" maxlength="50"/>
        <input type="hidden" name="factoryid" id="factoryid" value=""/>
        <span class="add-on" style="text-align: ">SKU</span>
        <input type="text" name="sku_code" id="sku_code" value="" autocomplete="off" maxlength="50"/>
        <input type="hidden" name="skuid" id="skuid" value=""/>

        <span class="add-on" style="text-align: left; width: 70px;">Weight</span>
        <input type="text"  name="weight" id="weight" value="" maxlength=""/>


        <input style='display:inline;' type='submit' class="btn  btn-primary" value='  Add Production  '/>
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
              <td><span>Import Factory Production<span class="mandatory">*</span></span></td>
              <td><input type="file" required="" id="locationFile" name="locationFile"></td>
              <td><button name="uploadFile" class="btn btn-primary" type="submit">Upload</button></td>
              <td><a href='factory_production.xls' target="_blank" title="Download Production Excel">Download</a></td>
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
            $record_status = upload_facroty_prduction($excel_data);
            //echo "<span id='success_status' style='font-weight:bold;color:green;'>Uploaded successfuly</span><br/>";
            //echo "<span style='font-weight:bold;color:blue;'>{$record_status['added']} Route Checkpoint added, {$record_status['skipped']} Route Checkpoint skipped.</span>";
            header('Location:tms.php?pg=view-factory-production');
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
    <input type='hidden' id='forTable' value='productionMaster'/>
    <table class='display table table-bordered table-striped table-condensed' id="productionmaster" >
      <thead>
        <tr>

          <td><input type="text" class='search_init' name='factory' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='sku' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='weight' style="width:90%;" autocomplete="off"/></td>
          <td colspan="2"></td>



        </tr>
        <tr class='dtblTh'>

          <th >Factory</th>
          <th >SKU </th>
          <th >Weight</th>
          <th>Edit</th>
          <th>Delete</th>

          <!--
          <th></th>
          -->
        </tr>
      </thead>
    </table>

  </center>
</div>
<script type='text/javascript'>
  var data = <?php echo json_encode($factory_production); ?>;
  var tableId = 'productionmaster';
  var tableCols = [
    {"mData": "factoryname"}
    , {"mData": "skucode"}
    , {"mData": "weight"}
    , {"mData": "edit"}
    , {"mData": "delete"}


  ];
</script>