<?php
$max_columns = 7;
$max_row = 2000;
$column_names = array(
   'A' => 'locationname'
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
  #locationmaster_filter{display: none}
  .dataTables_length{display: none}
</style>

<br/>
<div class='container' >
  <center>
    <div style="float: left;">
      <form style='display:inline;' method="post" action="action.php?action=add-location">
        <div class="input-prepend ">
          <span class="add-on" style="width: 100px; text-align: left;">Location </span>
          <input type="text" name="location_name" id="locaion_name" style="width: 150px;" value="" maxlength="50"/>

          <input style='display:inline;' type='submit' class="btn  btn-primary" value='Add'/>
        </div>
      </form>
    </div>

    <div style="float: left;margin-left: 35px;"> OR </div>

    <div style="float: left;">

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
              <td><a href='location.xls' target="_blank" title="Download Location Excel">Download</a></td>
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

            $record_status = upload_locations($excel_data);
            //echo "<span id='success_status' style='font-weight:bold;color:green;'>Uploaded successfuly</span><br/>";
            //echo "<span style='font-weight:bold;color:blue;'>{$record_status['added']} location added, {$record_status['skipped']} location skipped.</span>";
            header('Location:tms.php?pg=view-location');
            /**
              echo '<script type="text/javascript">
              $("#dtcontainer").load();
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
<div class='container' id="dtcontainer" >
  <center>
    <input type='hidden' id='forTable' value='locationMaster'/>
    <table class='display table table-bordered table-striped table-condensed' id="locationmaster" style="width: 60%;" >
      <thead>
        <tr>
          <td><input type="text" class='search_init' name='loc_id' style="width:80%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='loc_name' style="width:90%;" autocomplete="off"/></td>
          <td></td>


        </tr>
        <tr class='dtblTh'>
          <th width="20%">Sr. No</th>
          <th width="60%">Location</th>
          <th>Edit</th>
          <th>Delete</th>

          <!--
          <th></th>
          -->
        </tr>
      </thead>
    </table>
    <import
</center>



</div>

<script type='text/javascript'>
  var data = <?php echo json_encode($locations); ?>;
  var tableId = 'locationmaster';
  var tableCols = [
    {"mData": "locationid"}
    , {"mData": "locationname"}
    , {"mData": "edit"}
    , {"mData": "delete"}


  ];
</script>