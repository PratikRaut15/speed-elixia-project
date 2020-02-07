<?php
$max_columns = 7;
$max_row = 2000;
$column_names = array(
   'A' => 'routecode',
   'B' => 'routedescription',
   'C' => 'fromplant',
   'D' => 'todepot',
   'E' => 'distance',
   'F' => 'time'
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
  #routemaster_filter{display: none}
  .dataTables_length{display: none}
  .ajax_response_4{
    margin-left: 130px;
  }
  .ajax_response_5{
    margin-left: 690px;
  }
  .ajax_response_6{
    margin-left: 200px;
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
    <form style='display:inline;width: 70%;'  method="post" action="action.php?action=add-route">
      <div class="input-prepend ">
        <span class="add-on" style="text-align: left;">Route Code</span>
        <input type="text"  name="routecode" id="routcode" value="" maxlength="20"/>
        <span class="add-on" style="text-align: left;">Description</span>
        <input type="text"  name="routedescription" id="routedescription" value="" maxlength="50"/>

        <span class="add-on" style="text-align: ">From Factory</span>
        <input type="text" style="width: 120px;" name="factory_name" id="factory_name" value="" maxlength="50" autocomplete="off"/>
        <input type="hidden" name="fromlocationid" id="factoryid" value="" maxlength="50"/>
        <div id="display" class="listlocation"></div>

        <br/>
        <br/>
        <span class="add-on" style="text-align: ">To Depot</span>
        <input type="text" style="width: 120px;" name="tolocation" id="depot_name" value="" maxlength="50" autocomplete="off"/>
        <input type="hidden" name="tolocationid" id="depotid" value="" maxlength="50"/>
        <div id="display" class="listlocation"></div>
        <span class="add-on" style="text-align: left;">Distance(km)</span>
        <input type="text" style="width:90px;" name="routedistance" id="routedistance" value="" maxlength="11"/>
        <span class="add-on" style="text-align: left;">Time In Days</span>
        <input type="text"  style="width:90px;" name="travellingtime" id="travellingtime" value="" maxlength="11"/>
        <input style='display:inline;' type='submit' class="btn  btn-primary" value='  Add Route  '/>
      </div>
    </form>

    <div>

      <form enctype="multipart/form-data" method="post" onsubmit='return validate_upload("locationFile");' style="width:75%;">

        <table class="table">
          <tbody>
            <tr>
              <td colspan="100%" style="display:none;text-align:center;" id='StatusTD'>
                <span id="loading" style='display:none;'>Loading....</span>
                <span id="Status" ></span>
              </td>
            </tr>
            <tr>
              <td><span>Import Route<span class="mandatory">*</span></span></td>
              <td><input type="file" required="" id="locationFile" name="locationFile"></td>
              <td><button name="uploadFile" class="btn btn-primary" type="submit">Upload</button></td>
              <td><a href='route.xls' target="_blank" title="Download Route Excel">Download</a></td>
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
            $record_status = upload_route($excel_data);
            //echo "<span id='success_status' style='font-weight:bold;color:green;'>Uploaded successfuly</span><br/>";
            //echo "<span style='font-weight:bold;color:blue;'>{$record_status['added']} route added, {$record_status['skipped']} route skipped.</span>";
            header('location:tms.php?pg=view-route');
            /*
              echo '<script type="text/javascript">
              $("#dtcontainer_route").load();
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
<br/>
<div class='container' id="dtcontainer_route" >
  <center>
    <input type='hidden' id='forTable' value='vehicleMaster'/>
    <table class='display table table-bordered table-striped table-condensed' id="routemaster" style="width: 90%;" >
      <thead>
        <tr>
          <td><input type="text" class='search_init' style="width:90%;" name='route'  autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='description' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='depot' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_dist' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='travellingtime' style="width:90%;" autocomplete="off"/></td>
          <td colspan="2"></td>



        </tr>
        <tr class='dtblTh'>
          <th>Route</th>
          <th >Description</th>
          <th >Factory</th>
          <th >Depot</th>
          <th >Distance</th>
          <th >Travelling Time</th>
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
  var data = <?php echo json_encode($routes); ?>;
    var tableId = 'routemaster';
  var tableCols = [
    {"mData": "routename"}
    , {"mData": "routedescription"}
    , {"mData": "factoryname"}
    , {"mData": "depotname"}
    , {"mData": "distance"}
    , {"mData": "travellingtime"}
    , {"mData": "edit"}
    , {"mData": "delete"}


  ];
</script>
