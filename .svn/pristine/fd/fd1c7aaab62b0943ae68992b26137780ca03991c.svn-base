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
        <span class="add-on" style="text-align: left;">Product Code</span>
        <input type="text" style="width:105px;" name="skucode" id="skucode" value="" maxlength="100"/>
        <span class="add-on" style="text-align: ">Description</span>
        <input type="text" name="skudescription" id="skudescription" value="" maxlength="250"/>
        <span class="add-on" style="text-align: ">Type</span>
        <select id="type" onchange="gettype(this)">
            <option value="1">Dry</option>
            <option value="2" selected>Refer</option>
        </select>
        <br/>
        <br/>
        <span class="add-on" style="text-align: ">Vol(M3)</span>
        <input type="text" style="width:90px;" name="skuvolume" id="skuvolume" value="" maxlength="11"/>

        <span class="add-on" style="text-align: ">Weight (kgs)</span>
        <input type="text" style="width:90px;" name="skuweight" id="skuweight" value="" maxlength="11"/>
        <br><br>
        <div id="temp">
        <span class="add-on" style="text-align: ">Temp Start Range</span>
        <input type="text" style="width:90px;" name="netgrosspercent" id="netgrosspercent" value="" maxlength="6"/>
        <span class="add-on" style="text-align: ">&degC</span>

        <span class="add-on" style="text-align: ">Temp End Range</span>
        <input type="text" style="width:90px;" name="netgrosspercent" id="netgrosspercent" value="" maxlength="6"/>
        <span class="add-on" style="text-align: ">&degC</span>
        </div>
        <br><br>
        <input style='display:inline;' type='submit' class="btn  btn-secondary" value='  Add Product  '/>
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
          <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>

          <td colspan="2"></td>



        </tr>
        <tr class='dtblTh'>
          <th>Sr.No</th>
          <th>Product Code</th>
          <th >Description</th>
          <th >Type</th>
          <th >Volume (In M3)</th>
          <th >Weight (IN Kgs)</th>
          <th >Temp Start Range</th>
          <th >Temp End Range</th>
          <th >Created By</th>
          <th >Created On</th>
          <th >Edit</th>
          <th >Delete</th>

        </tr>
      </thead>
    </table>

  </center>
</div>

<script type='text/javascript'>


  var data = <?php

  $transporters = array(
        array('srno' => '1','skucode' => '324688', 'sku_description' => 'Caramello crumb','type'=>'DRY','volume'=>'0.07231','weight'=>'0.00900','tempSrange'=>'0&degC','tempErange'=>'0&degC',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-08 13:15:00','edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '2','skucode' => '324705', 'sku_description' => 'MANGO FLAVOUR','type'=>'REFER','volume'=>'0.04518','weight'=>'0.00925','tempSrange'=>'2&degC','tempErange'=>'8&degC',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-08 13:15:00','edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '3','skucode' => '324440', 'sku_description' => 'Cocoa Sugar Mix','type'=>'DRY','volume'=>'0.07231','weight'=>'0.00900','tempSrange'=>'4&degC','tempErange'=>'9&degC',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-08 13:15:00','edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '4','skucode' => '323564', 'sku_description' => 'CDM SILK RAORANGE PEEL145G COFE TRT PRMO','type'=>'DRY','volume'=>'0.04018','weight'=>'0.01021','tempSrange'=>'4&degC','tempErange'=>'8&degC',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-08 13:15:00','edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '4','skucode' => '323575', 'sku_description' => 'CADBURY DAIRY MILK SHOTS SMAL2GX4UX33+2U','type'=>'REFER','volume'=>'323165','weight'=>'0.01021','tempSrange'=>'2&degC','tempErange'=>'8&degC',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-08 13:15:00','edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '5','skucode' => '323165', 'sku_description' => 'Cocoa Sugar Mix','type'=>'DRY','volume'=>'0.04901','weight'=>'0.01021','tempSrange'=>'2&degC','tempErange'=>'8&degC',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-08 13:15:00','edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '6','skucode' => '323163', 'sku_description' => 'FIVE STAR 10.5G FLOWPACK X 54U','type'=>'DRY','volume'=>'0.04018','weight'=>'0.01440','tempSrange'=>'8&degC','tempErange'=>'25&degC',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-09 14:11:00','edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-tra,sh"></i></a>'),
        array('srno' => '7','skucode' => '323162', 'sku_description' => 'CDM CRACKLE 40GX40U FLOW PACK','type'=>'REFER','volume'=>'0.04018','weight'=>'0.01440','tempSrange'=>'2&degC','tempErange'=>'8&degC',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-09 14:13:00','edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '8','skucode' => '30001897 ', 'sku_description' => 'Paster RMC 26 % fat content ','type'=>'REFER','volume'=>'0.04018','weight'=>'0.03500','tempSrange'=>'2&degC','tempErange'=>'6&degC',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-09 14:19:00','edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '9','skucode' => '10001612', 'sku_description' => 'MANGO FLAVOUR','type'=>'DRY','volume'=>'0.04018','weight'=>'0.01500','tempSrange'=>'4&degC','tempErange'=>'6&degC',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-09 14:20:00','edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '10','skucode' => '10001604', 'sku_description' => '25% BOURNVILLE LIQUOR','type'=>'DRY','volume'=>'0.04018','weight'=>'0.02500','tempSrange'=>'22&degC','tempErange'=>'28&degC',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-09 14:24:00','edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>')
    );
  echo json_encode($transporters); ?>;
  var tableId = 'skumaster';
  var tableCols = [
    {"mData": "srno"}
    ,{"mData": "skucode"}
    , {"mData": "sku_description"}
    , {"mData": "type"}
    , {"mData": "volume"}
    , {"mData": "weight"}
    , {"mData": "tempSrange"}
    , {"mData": "tempErange"}
    , {"mData": "createdby"}
    , {"mData": "createdon"}
    , {"mData": "edit"}
    , {"mData": "delete"}


  ];

  function gettype(value){
      var data1=value.value;
      if(data1 == 1){
          jQuery('#temp').hide();
      }else{
          jQuery('#temp').show();
      }
  }
</script>
