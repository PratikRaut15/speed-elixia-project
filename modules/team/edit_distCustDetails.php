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

$dcId=$_GET['dcId'];
$teamId = GetLoggedInUserId();
  if($teamId==1){
    $teamId = 0;
  }
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<div class="panel">
  <div class="paneltitle" align="center">Edit Customer Details</div> 
  <div class="panelcontents"> 
    <input type="button" name="back" id="back" value="Go Back" onclick="window.location.href='view_distCustDetails.php';" style="margin:0 0 0 90% !important;background-color:#FF8C00;"/> 
    <div class="center">  
      <form name="distributor_form_edit" id="distributor_form_edit">
                  <input type="hidden" name="dc_ID" id="dc_ID" value="<?php echo $dcId; ?>"/> 

                  <label>Customer Name</label>
                    <input type="text" name="c_name" id="c_name" placeholder="Enter Customer Name" required/> 
                  <label>Company Name</label>
                    <input type="text" name="comp_name" id="comp_name" placeholder="Enter Company Name" required/>
                  <label>Address</label>
                  <textarea name="address" id="address" placeholder="Enter Address" required></textarea>
                  <br>
                  <label>Phone</label>
                  <input type="text" name="phone" id="phone" placeholder="Enter Phone" required/> 
                  
                  <label>Email</label>
                  <input type="text" name="email" id="email" placeholder="Enter Email" required/> 
                  <br>
                  <label>Address Proof</label>
                  <input type="file" name="file_address" id="file_address" value=''/>
                  
                  <label>Photo Proof</label>
                 
                  <input type="file" name="file_photo" id="file_photo" value=''/>
                  <embed src="" type="application/pdf"  height="250px" width="45%" id="address_pdfPreviewLink"/>
                   <embed src="" type="application/pdf"  height="250px" width="45%" id="photo_pdfPreviewLink"/>
                  <input type="button" name="submit_distributor_form" id="submit_distributor_form" value="Update Details" onclick="editDistributorInfo();" style="margin-left:40%;"/>       
      </form>
    </div>
  </div>
  <div class="paneltitle" align="center">Add Vehicle Details</div> 
  <div class="panelcontents">
    <div class="center">  
      <form name="distributor_vehicle_form" id="distributor_vehicle_form">
        <div class="container1">
          <button class="add_form_field">Add New Vehicle &nbsp; <span style="font-size:16px; font-weight:bold;">+ </span></button>
          <div>
          <label>Vehicle No.</label><input type="text" name="vehicle_no[]" placeholder="Enter Vehicle No.">
          <label>Engine No.</label><input type="text" name="engine_no[]" placeholder="Enter Engine No.">
          <label>Chasis No.</label><input type="text" name="chasis_no[]" placeholder="Enter Chasis No."></div>
        </div>
        <input type="button" name="submit_vehicle_form" id="submit_vehicle_form" value="Submit" onclick="addVehicle_Details();" style="margin-left:40%;"/>       
      </form>

    </div>
  </div>
   <div align="center" class="titleClass">VEHICLE LIST</div> 
  <div id="myGrid" class="ag-theme-blue" style="height:300px;width:60%;margin:0 auto;border: 1px solid gray"></div>
</div> 

<script>
  var dc_id='';
  var teamId='';
  jQuery(document).ready(function () {  
    dc_id = <?php echo $dcId; ?>;
    teamId = <?php echo $teamId; ?>;
  });
</script>
<script src='../../scripts/team/edit_distCustDetails.js'></script>
<script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
<script>

    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
    var gridOptions;
    columnDefs = [
        {headerName: 'Edit', cellRenderer:'editCellRenderer',width:70,suppressFilter:true},
        {headerName:'Vehicle No',field: 'vehicleNo',width:150,filter: 'agTextColumnFilter'},
        {headerName:'Engine No',field: 'engineNo',width:120,filter: 'agTextColumnFilter'},
        {headerName:'Chasis No',field: 'chasisNo',width:200,filter: 'agTextColumnFilter'},
        {headerName:'Created On',field: 'createdOn',width:300,filter: 'agTextColumnFilter'}
    ];
    function editCellRenderer(params){
        return "<a href='edit_distVehDetails.php?dvId="+params.data.dvId+"&dcId="+params.data.dcId+"' alt='Edit Mode' title='Mode' ><img style='text-align:center; width:20px; height:20px;' src='../../images/edit.png'/></a>"
    }
    gridOptions = {
        enableFilter:true,
        enableSorting: true,
        floatingFilter:true,
        rowSelection:'multiple',
        animateRows:true,
        columnDefs: columnDefs,
        components: {editCellRenderer : editCellRenderer
        }
    };
    var gridDiv = document.getElementById('myGrid');
    new agGrid.Grid(gridDiv,gridOptions);
</script>