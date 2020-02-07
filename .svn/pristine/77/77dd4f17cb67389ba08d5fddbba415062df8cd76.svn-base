<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include("../../lib/bo/TeamManager.php");
include_once("../../lib/system/Date.php");

date_default_timezone_set("Asia/Calcutta");
$today = date("d-m-Y");
$time= date("h:i");
$created_on=date("Y-m-d H:i:s");
$itemMasterId = $_GET['imId'];
include("header.php");
?>
<style>
  .titleClass{
    font-size:16px;
    font-weight:600;
    margin:2% 0 2% 0;
  }
</style>
<link rel="stylesheet" href="../../css/distributorDetails.css">
<div class="panel">
  <div class="paneltitle" align="center">Add Item Master</div> 
  <div class="panelcontents"> 
    <div class="center">  
      <form name="edit_item_master" id="edit_item_master">

        <input type="hidden" name="item_master_id" id="item_master_id" value="<?php echo $itemMasterId; ?>" /> 

        <label>Item Name</label>
        <input type="text" name="item_name" id="item_name" placeholder="Enter Item Name" required/> 
        <input type="hidden" name="temp_item_name" id="temp_item_name" placeholder="Enter Item Name" required/> 
        <label>Description</label>
        <textarea name="description" id="description" placeholder="Enter Description" required></textarea>
        
        <label>HSN Code</label>
        <input type="text" name="hsn_code" id="hsn_code" placeholder="Enter HSN Code"/>
        
        <input type="button" name="update_item_master" id="update_item_master" value="Update" onclick="editItemMaster();" style="margin-left:40%;"/>       
      </form>
    </div>
  </div>
</div> 

<script>
 var im_id = <?php echo $itemMasterId; ?>;
jQuery(document).ready(function () {  
  get_item_master();  
});
   
    function editItemMaster(){
        var item_name = $("#item_name").val();
        var description = $("#description").val();

        if(item_name==""){
            alert("Please Enter Item Name.");
            $("#item_name").focus();
            return false;
        }
        else if(description==""){
                alert("Please Enter Description.");
                $("#description").focus();
                return false;
        }
        else{
                var data = $("#edit_item_master").serialize();
                jQuery.ajax({
                  type: "POST",
                  url: "route_ajax.php",
                  data: "update_item_master=1&"+data,
                  success: function(data){
                    var result = JSON.parse(data);
                    if(result==1){
                      alert("Item Updated Successfully.");
                      window.location.href='item_master.php';
                    }
                    else if(result==0){
                      alert("Item Already Exists.");
                      $("#item_name").focus();
                      return false;
                    }
                    else{
                      alert("Please Try Again");
                      return false;
                    }
                  }
                });
        }
    }

    function get_item_master(){
      jQuery.ajax({
                    type: "POST",
                    url: "route_ajax.php",
                    data: "fetch_item_master=1&item_id="+im_id,
                    success: function(data){
                        var temp_result=JSON.parse(data);
                        var result = temp_result[0];

                        $("#item_name").val(result.item_name);
                        $("#temp_item_name").val(result.item_name);
                        $("#description").val(result.description);
                        $("#hsn_code").val(result.hsn_code);
                    }
        });
    }
</script>