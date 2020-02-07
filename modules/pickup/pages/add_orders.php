<?php include_once 'pickup_functions.php'; ?>
<?php
echo "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/bootstrap/js/jquery.min.js' > </script>";
$pickup = getpickup();
$customer = getcustomers();
/**
 * City master form
 */
?>
<style>
  #ajaxstatus{text-align:center;font-weight:bold;display:none}
  .mandatory{color:red;font-weight:bold;}
  #addorders table{width:50%;}
  #addorders .frmlblTd{text-align:center}
</style>
<script>
jQuery(document).ready(function(){
    jQuery('#pickupdate').datepicker({format: "dd-mm-yyyy",autoclose:true});
});

</script>
<br/>
<div class='container' >
  <center>
     <form enctype="multipart/form-data" method="POST" id="addpickuporders">
        <span style="display: none;" id="name_error"> Please Select Pickup Boy </span>
        <span style="display: none;" id="fail_error"> Please check mandatory fields.</span>
        <span style="display: none;" id="fail_exists"> Already exists order.</span>
        
    <table class='table table-condensed' style="width: 50%;">
        <thead><tr><th colspan="100%" >Add Order</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <input type="hidden" name="userkey" value='<?php echo $_SESSION['userkey']; ?>' >
            <tr><td class='frmlblTd'>Order No <span class="mandatory">*</span></td><td><input type="text" name="orderid" id="orderid" placeholder="Order ID" ></td></tr>
            <tr><td class='frmlblTd'>Customer <span class="mandatory">*</span></td>
                <td>
                <select name="customer" id="customer">
                <option value="0">Select Customer</option>
                <?php
                if(isset($customer) && !empty($customer)){
                    foreach($customer as $cust){
                        ?>
                    <option value="<?php echo $cust->customerid;?>"><?php echo $cust->customername;?></option>
                    <?php
                    }
                }
                ?>
            </select>
                </td>
            </tr>
            <tr><td class='frmlblTd'>Vendor No</td><td> <input type="text" name="vendor" id="vendor" placeholder="Vendor"></td></tr>
            <tr><td class='frmlblTd'>Pickupboy</td>
            <td>
            <select name="pickupboyid" id="pickupboyid">
                <option value="00">Select Pickup Boy</option>
                <?php
                if(isset($pickup) && !empty($pickup)){
                    foreach($pickup as $pick){
                        ?>
                    <option value="<?php echo $pick->pid;?>"><?php echo $pick->name;?></option>
                    <?php
                    }
                }
                ?>
            </select>
            </td>
            </tr>
            <tr><td class='frmlblTd'>Order Status</td><td><select name="status" id="pickupboyid">
                <option value="0">Ongoing</option>
                <option value="1">Picked Up</option>
                <option value="2">Cancelled</option>
                
            </select></td></tr>
            <tr><td class='frmlblTd'>Pickup Date</td><td><input type="text" name="pickupdate" id="pickupdate" placeholder="dd-mm-yyyy"></td></tr>
            <tr><td class='frmlblTd'>Name</td><td><input type="text" name="oname" id="oname"></td></tr>
            <tr><td class='frmlblTd'>Address</td><td><input type="text" name="oaddress" id="oaddress"></td></tr>
            <tr><td class='frmlblTd'>Landmark </td><td><input type="text" name="olandmark" id="olandmark"></td></tr>
            <tr><td class='frmlblTd'>Pincode </td><td><input type="text" name="pincode" id="pincode"></td></tr>
            <tr><td class='frmlblTd'></td><td><input type="button" value="Add Order" id="adddealerbtn" class="btn btn-primary" onclick="pickupaddorder(); return false;"></td></tr>
        </tbody>
    </table>
        <input type="hidden" name="addpickup_orders" id="addpickup_orders" value="1">
    </form>
  </center>
</div>
<?php
$max_columns = 10;
$max_row = 2000;
$column_names = array(
   'A' => 'customerid',
   'B' => 'vendorno',
   'C' => 'orderid',
   'D' => 'pickupdate',
   'E' => 'name',
   'F' => 'address',
   'G' => 'landmark',
   'H' => 'pincode',
   'I' => 'shipperid',
   'J' => 'fieldmarshal'
);
$valid_file = array('xls', 'xlsx');
$valid_size = 2000000;
?>
<script type='text/javascript' src='../../scripts/exception.js'></script>
<script>
      var valid_file_xls =<?php echo json_encode($valid_file); ?>;
      var valid_size_xls =<?php echo $valid_size; ?>;
</script>
<div class="entry">
  <center>
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

          $record_status = upload_checkpoint($excel_data);
          echo "<span id='success_status' style='font-weight:bold;color:green;'>Uploaded successfuly</span><br/>";
          echo "<span style='font-weight:bold;color:blue;'>{$record_status['added']} Orders added, {$record_status['skipped']} Orders skipped ,  {$record_status['notexistsvendor']} Vendors not exists </span>";
        }
      }
    }
    ?>
    <br/>
    <form enctype="multipart/form-data" method="post" class="form-horizontal well" onsubmit='return validate_upload("checkpointFile");'  style="width:70%;">

      <table class="table table-bordered">
        <thead>
          <tr>
            <th colspan="100%">Import Orders</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="100%"><span class="add-on"> Import Orders Using Excel Sheet (Format - .xls, .xlsx).<span class="mandatory">*</span></span></td>

          </tr>
          <tr>
            <td colspan="100%" style="display:none;text-align:center;" id='StatusTD'>
              <span id="loading" style='display:none;'>Loading....</span>
              <span id="Status" ></span>
            </td>
          </tr>
          <tr>
            <td><span class="add-on">Upload File <span class="mandatory">*</span></span></td>
            <td><input type="file" required="" id="checkpointFile" name="checkpointFile"></td>
          </tr>
          <tr>
            <td style="text-align:center;" colspan="100%"><button name="uploadFile" class="btn btn-primary" type="submit">Upload</button></td>
          </tr>

          <tr>
            <td colspan="100%"><span class="add-on"> Please Download The Sample Excel Sheet For Import Orders. <span class="mandatory">*</span>  <a href='Orders.xls' target="_blank">  Download Excel Sheet</a></span></td>

          </tr>

        </tbody>
      </table>
    </form>

  </center>
</div>