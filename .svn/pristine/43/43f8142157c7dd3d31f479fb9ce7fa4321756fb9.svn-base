<?php
$max_columns = 4;
$max_row = 2000;
$column_names = array(
   'A' => 'checkpointname',
   'B' => 'latitude',
   'C' => 'longitude',
   'D' => 'radius'
);
$valid_file = array('xls', 'xlsx');
$valid_size = 2000000;
$veh_dropdown = get_veh_drop_down();
?>
<script type='text/javascript' src='../../scripts/exception.js'></script>
<script>
  var valid_file_xls =<?php echo json_encode($valid_file); ?>;
  var valid_size_xls =<?php echo $valid_size; ?>;
</script>
<div class="container">

  <div class="row" >

    <div class="col-sm-12">
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
            $all_form = $_REQUEST;
            $vehicles = array();
            foreach ($all_form as $single_post_name => $single_post_value) {
              if (substr($single_post_name, 0, 11) == "to_vehicle_")
                $vehicles[] = substr($single_post_name, 11, 12);
            }
            if (empty($vehicles)) {
              echo "Please Select Vehicles";
            } else {
              $record_status = upload_checkpoint($excel_data, $vehicles);
              echo "<span id='success_status' style='font-weight:bold;color:green;'>Uploaded successfuly</span><br/>";
              echo "<span style='font-weight:bold;color:blue;'>{$record_status['added']} checkpoints added, {$record_status['skipped']} checkpoints skipped.</span>";
            }
          }
        }
      }
      ?>
    </div>

  </div>

  <div class="row" >

    <div class="col-sm-12">

      <form class="form-inline" id='uploadPeople'  action="" method="post" onsubmit='return validate_upload("checkpointFile");' enctype="multipart/form-data" >
        <table class="table table-bordered">
          <thead>
            <tr>
              <th colspan='100%' >Upload</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="100%" style="display:none;text-align:center;" id='StatusTD'>
                <span id="loading" style='display:none;'>Loading....</span>
                <span id="Status" ></span>
              </td>
            </tr>
            <tr>
              <td colspan='100%'><div id='vehicle_list_route'></div></td>
              <td style="display: none;">
                <select id="vehicleroute" name="vehicleroute" onChange="VehicleForRoute()" style="display: none;">
                  <?php echo $veh_dropdown; ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>Vehicle No.</td>
              <td>
                <input  type="text" name="vehicleno" id="vehicleno" size="17" value="" autocomplete="off" placeholder="Enter Vehicle No" >
                <input type="hidden" name="vehicleid" id="vehicleid" size="20" value=""/>
                <div id="display" class="listvehicle"></div><br/>
                <input type="button" class="g-button g-button-submit" style='float:left;margin-top:5px;' onclick="addallvehicleForRoute()" value="Add All">
              </td>
            </tr>
            <tr>
              <td>Upload File:<br/><a href="pages/upload_files/upload_checkpoint.xlsx">Download Example File</a></td>
              <td><input type="file" name="checkpointFile" id="checkpointFile"   required></td>
            </tr>
            <tr>
              <td colspan="100%" style="text-align:center;"><button type="submit" class="btn btn-primary" name="uploadFile">Upload</button></td>
            </tr>
          </tbody>
        </table>
      </form>

    </div>

  </div>

</div>
