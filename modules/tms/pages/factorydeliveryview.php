<?php
$max_columns = 7;
$max_row = 2000;
$column_names = array(
   'A' => 'factory',
   'B' => 'sku',
   'C' => 'depot',
   'D' => 'daterequired',
   'E' => 'weight'
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
  #deliverymaster_filter{display: none}
  .dataTables_length{display: none}
  .ajax_response_9{
    margin-left: 490px;
  }
  .ajax_response_5{
    margin-left: 130px;
  }
  .ajax_response_6{
    margin-left: 310px;
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
    <?php
    if (isset($_GET['msg'])) {
      $message = $_GET['msg'];
      echo '<span class="add-on" style="color:red;">' . $message . '</span>';
    }
    ?>
    <form style='display:inline;width: 70%;' id="factory_delivery" method="post" action="action.php?action=add-factory-delivery" >
      <div class="input-prepend">
        <span class="add-on" style="text-align: ">Factory</span>
        <?php
        if (isset($_SESSION['factoryid']) && !empty($_SESSION['factoryid'])) {
          $objFactoty = new Factory();
          $objFactoty->customerno = $_SESSION["customerno"];
          $objFactoty->factoryid = $_SESSION['factoryid'];
          $plant = get_factory($objFactoty);
          ?>
          <input type="text" style="width: 120px;" name="factory_name" id="factory_name" value="<?php echo $plant[0][factoryname] ?>" maxlength="50" autocomplete="off" readonly=""/>
          <input type="hidden" name="factoryid" id="factoryid" value="<?php echo $_SESSION['factoryid'] ?>" maxlength="50"/>
          <?php
        } else {
          ?>
          <input type="text" style="width: 120px;" name="factory_name" id="factory_name" value="" maxlength="50" autocomplete="off"/>
          <input type="hidden" name="factoryid" id="factoryid" value="" maxlength="50"/>
          <?php
        }
        ?>

        <span class="add-on" style="text-align: ">Depot</span>
        <input type="text" style="width: 120px;" name="depot_name" id="depot_name" value="" maxlength="50" autocomplete="off"/>
        <input type="hidden" name="depotid" id="depotid" value="" maxlength="50"/>



        <span class="add-on" style="text-align: ">SKU</span>
        <input type="text" style="width: 60px;" name="sku_code" id="sku_code" value="" maxlength="50" autocomplete="off"/>
        <input type="text" style="width: 300px;" name="sku_description" id="sku_description" value="" autocomplete="off" readonly=""/>
        <!--
        <textarea name="sku_description" id="sku_description"></textarea>
        -->
        <input type="hidden" name="skuid" id="skuid" value="" maxlength="50"/>


        <br/>
        <br/>

        <span class="add-on" style="text-align: left;">Date Required</span>
        <input type="text"  name="date_required" id="SDate" value="" maxlength="50"/>

        <span class="add-on" style="text-align: left; width: 150px;">Net Weight (In Tons)</span>
        <input type="text"  name="weight" id="weight" value="" maxlength="3"/>

        <input type="hidden" name="todaysdate" id="todaysdate" value="<?php echo date('d-m-Y'); ?>" />
        <input style='display:inline;' type='button' class="btn  btn-primary" value='Add Delivery' onclick="validateFactoryDelivery();" />
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
              <td><span>Import Factory Delivery<span class="mandatory">*</span></span></td>
              <td><input type="file" required="" id="locationFile" name="locationFile"></td>
              <td><button name="uploadFile" class="btn btn-primary" type="submit">Upload</button></td>
              <td><a href='factory_delivery.xls' target="_blank" title="Download Location Excel">Download</a></td>
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
            $record_status = upload_facroty_delivery($excel_data);
            //echo "<span id='success_status' style='font-weight:bold;color:green;'>Uploaded successfuly</span><br/>";
            //echo "<span style='font-weight:bold;color:blue;'>{$record_status['added']} Route Checkpoint added, {$record_status['skipped']} Route Checkpoint skipped.</span>";
            header('Location:tms.php?pg=view-factory-delivery');
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

<div class='entry' >
  <center>
    <input type='hidden' id='forTable' value='deliveryMaster'/>
    <table class='display table table-bordered table-striped table-condensed' id="deliverymaster" >
      <thead>
        <tr>
          <td><input type="text" class='search_init' name='factory' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='sku' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='depot' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='date' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='netWeight' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='grossWeight' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='createddate' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='createddate' style="width:90%;" autocomplete="off"/></td>
          <td></td>
        </tr>
        <tr class='dtblTh'>
          <th>Factory</th>
          <th>SKU </th>
          <th>SKU Description</th>
          <th>Depot</th>
          <th>Vehicle Requirement Date</th>
          <th>Net Weight</th>
          <th>Gross Weight</th>
          <th>Created On</th>
          <th>Edit</th>
          <th>Delete</th>
        </tr>
      </thead>
    </table>

  </center>
</div>

<script type='text/javascript'>
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