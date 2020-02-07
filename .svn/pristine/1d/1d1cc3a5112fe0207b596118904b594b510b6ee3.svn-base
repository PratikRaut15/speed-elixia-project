<?php
$max_columns = 7;
$max_row = 2000;
$column_names = array(
   'A' => 'depotcode',
   'B' => 'depotname',
   'C' => 'zonename',
   'D' => 'multidrop',
   'E' => 'multidepots',
   'F' => 'factoryname'
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
  #depotmaster_filter{display: none}
  .dataTables_length{display: none}
  .ajax_response_3{
    margin-left: 600px;
  }
  .ajax_response_4{
    margin-left: 540px;
  }
  .checkpointlist{
    margin-left: 320px;
  }
  .ajax_response_5{
    margin-left: 570px;
  }
</style>

<br/>
<div class='container' >
  <center>
    <form style='display:inline;width:100%;' method="post" action="action.php?action=add-depot">
      <div class="input-prepend ">
        <span class="add-on" style="text-align: left;">Depot Code</span>
        <input type="text" name="depotcode" id="depotcode" value="" maxlength="20"/>
        <span class="add-on" style="text-align: left;">Depot</span>
        <input type="text" name="depotname" id="depotname" value="" maxlength="50"/>
        <span class="add-on" style="text-align: left;"> Zone</span>
        <input type="text" name="zonename" id="zonename" value="" maxlength="50" autocomplete="off"/>
        <input type="hidden" name="zoneid" id="zoneid" value="" maxlength="50"/>
        <!--
        <input style='display:inline;' type='submit' class="btn  btn-primary" value='  Add Depot  '/>      -->
      </div>
      <div class="input-prepend">
        <span class="add-on" style="text-align: left;">MultiDrop</span>
        <input type="radio" name="multidrop" id="multidrop" value="1" onclick="getmultidrop();"/> Yes
        <input type="radio" name="multidrop" id="multidrop" value="0" onclick="getmultidrop();" checked=""/> No
        <input type="text" style="width: 170px; display: none;" name="multidepot_name" id="multidepot_name" value="" maxlength="50" autocomplete="off" placeholder="Enter Depot"/>

        <span class="add-on" id="factoryspan" style="text-align: left;display: none; ">Factory</span>
        <input type="text" id="factory_name" style="width: 120px; display: none;" name="factory_name" id="factory_name" value="" maxlength="50" autocomplete="off"  placeholder="Enter Factory"/>
        <input type="hidden" name="factoryid" id="factoryid" value="" maxlength="50"/>
        <div id="chkdisplay" class="checkpointlist" style="display: none;"></div>                             <input style='display:inline;' type='submit' class="btn  btn-primary" value='  Add Depot  '/><div id="multidepot_list"></div>
      </div>
    </form>


  </center>
  <br/>
  <br/>
  <center>
    <div>
      <form enctype="multipart/form-data" method="post" onsubmit='return validate_upload("locationFile");' style="width:85%;">
        <table class="table">
          <tbody>
            <tr>
              <td colspan="100%" style="display:none;text-align:center;" id='StatusTD'>
                <span id="loading" style='display:none;'>Loading....</span>
                <span id="Status" ></span>
              </td>
            </tr>
            <tr>
              <td><span>Import Locations<span class="mandatory">*</span></span></td>
              <td><input type="file" required="" id="locationFile" name="locationFile"></td>
              <td><button name="uploadFile" class="btn btn-primary" type="submit">Upload</button></td>
              <td><a href='depots.xls' target="_blank" title="Download Location Excel">Download</a></td>
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
            $record_status = upload_depots($excel_data);
            header('Location:tms.php?pg=view-depot');
          }
        }
      }
      ?>
    </div>
  </center>
</div>
<hr/>
<div class='container' >
  <center>
    <table class='display table table-bordered table-striped table-condensed' id="depotmaster" style="width: 100%;" >
      <thead>
        <tr>
          <td><input type="text" class='search_init' name='depotcode' autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='depotname' autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='depotzone' autocomplete="off"/></td>
        </tr>
        <tr class='dtblTh'>
          <th >Depot Code</th>
          <th >Depot</th>
          <th >Zone</th>
          <th >Edit</th>
          <th >Delete</th>
        </tr>
      </thead>
      <tbody id="prec">
        <tr >
          <td></td>
        </tr>
      </tbody>
      <tfoot>
      </tfoot>
    </table>
  </center>
</div>
<script type='text/javascript'>
  var data = <?php echo json_encode($depots); ?>;
  var tableId = 'depotmaster';
  var tableCols = [
    {"mData": "depotcode"}
    , {"mData": "depotname"}
    , {"mData": "zonename"}
    , {"mData": "edit"}
    , {"mData": "delete"}
  ];
</script>