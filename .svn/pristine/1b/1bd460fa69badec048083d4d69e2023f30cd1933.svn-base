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
      <form name="item_master" id="item_master">
        <label>Item Name</label>
        <input type="text" name="item_name" id="item_name" placeholder="Enter Item Name" required/> 
        
        <label>Description</label>
        <textarea name="description" id="description" placeholder="Enter Description" required></textarea>
        
        <label>HSN Code</label>
        <input type="text" name="hsn_code" id="hsn_code" placeholder="Enter HSN Code"/>
        
        <input type="button" name="submit_item_master" id="submit_item_master" value="Submit" onclick="addItemMaster();" style="margin-left:40%;"/>       
      </form>
    </div>
  </div>
   <div align="center" class="titleClass">ITEM MASTER LIST</div> 
  <div id="myGrid" class="ag-theme-blue" style="height:300px;width:60%;margin:0 auto;border: 1px solid gray"></div>
</div> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src='../../scripts/team/item_master.js'></script>
<script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
<script>

    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
    var gridOptions;
    columnDefs = [
        {headerName: 'Edit', cellRenderer:'editCellRenderer',width:70,suppressFilter:true},
        {headerName:'Item Name',field: 'item_name',width:150,filter: 'agTextColumnFilter'},
        {headerName:'Description',field: 'description',width:220,filter: 'agTextColumnFilter'},
        {headerName:'HSN Code',field: 'hsn_code',width:150,filter: 'agTextColumnFilter'},
        {headerName:'Created On',field: 'createdOn',width:150,filter: 'agTextColumnFilter'}
    ];
    function editCellRenderer(params){
        return "<a href='edit_item_master.php?imId="+params.data.imId+"' alt='Edit Mode' title='Mode' ><img style='text-align:center; width:20px; height:20px;' src='../../images/edit.png'/></a>"
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