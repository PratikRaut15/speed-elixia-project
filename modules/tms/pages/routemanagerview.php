<?php
$max_columns = 7;
$max_row = 2000;
$column_names = array(
   'A' => 'routedescription',
   'B' => 'fromplant',
   'C' => 'tolocation',
   'D' => 'distance'
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
  #routemanagermaster_filter{display: none}
  .dataTables_length{display: none}
  .ajax_response_4{
    margin-left: 510px;
  }
  .ajax_response_5{
    margin-left: 290px;
  }
  .ajax_response_6{
    margin-left: 500px;
  }
  .ajax_response_7{
    margin-left: 60px;
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
    <form style='display:inline;width: 70%;' method="post" action="action.php?action=add-routecheckpoint" >
      <div class="input-prepend ">
        <span class="add-on" style="text-align: left;">Route</span>
        <input type="text" style="width: 120px;" name="route_name" id="route_name" value="" maxlength="50" autocomplete="off"/>
        <input type="hidden" name="routemasterid" id="routemasterid" value="" maxlength="50"/>
        <span class="add-on" style="text-align: ">From Factory</span>
        <input type="text" style="width: 120px;" name="factory_name" id="factory_name" value="" maxlength="50" autocomplete="off"/>
        <input type="hidden" name="fromlocationid" id="factoryid" value="" maxlength="50"/>
        <div id="display" class="listlocation"></div>
        <span class="add-on" style="text-align: ">To Location</span>
        <input type="text" style="width: 120px;" name="tolocation" id="locationname" value="" maxlength="50" autocomplete="off"/>
        <input type="hidden" name="tolocationid" id="locationid" value="" maxlength="50"/>
        <div id="display" class="listlocation"></div>
        <span class="add-on" style="text-align: left;">Distance(Km)</span>
        <input type="text" style="width:90px;" name="routedistance" id="routedistance" value="" maxlength="11"/>
        <input style='display:inline;' type='submit' class="btn  btn-primary" value='  Add Route  '/>
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
              <td><span>Import Route Checkpoint<span class="mandatory">*</span></span></td>
              <td><input type="file" required="" id="locationFile" name="locationFile"></td>
              <td><button name="uploadFile" class="btn btn-primary" type="submit">Upload</button></td>
              <td><a href='route_checkpoint.xls' target="_blank" title="Download Route Checkpoint Excel">Download</a></td>
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
            $record_status = upload_route_checkpoint($excel_data);
            //echo "<span id='success_status' style='font-weight:bold;color:green;'>Uploaded successfuly</span><br/>";
            //echo "<span style='font-weight:bold;color:blue;'>{$record_status['added']} Route Checkpoint added, {$record_status['skipped']} Route Checkpoint skipped.</span>";
            header('Location:tms.php?pg=view-routemanager');
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

<div class='container' id="dtcontainer_routecheckpoint" >
  <center>
    <input type='hidden' id='forTable' value='vehicleMaster'/>
    <table class='display table table-bordered table-striped table-condensed' id="routemanagermaster" style="width: 90%;" >
      <thead>
        <tr>
          <td><input type="text" class='search_init' style="width:90%;" name='route'  autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='location' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='distance' style="width:90%;" autocomplete="off"/></td>
          <td colspan="2"></td>



        </tr>
        <tr class='dtblTh'>
          <th>Route</th>
          <th >Plant</th>
          <th >location</th>
          <th >Distance</th>
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
  var data = <?php echo json_encode($routechk); ?>;
  var tableId = 'routemanagermaster';
  var tableCols = [
    {"mData": "routename"}
    , {"mData": "factoryname"}
    , {"mData": "locationname"}
    , {"mData": "distance"}
    , {"mData": "edit"}
    , {"mData": "delete"}


  ];
</script>