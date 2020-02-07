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
  <div id="myGrid" class="ag-theme-blue" style="height:300px;width:60%;margin:0 auto;border: 1px solid gray"></div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
<script>
jQuery(document).ready(function () {  
	var result='';
	var columnDefs = '';
	get_item_master_details();  
});
   
	 function get_item_master_details(){
      	jQuery.ajax({
                    type: "POST",
                    url: "route_ajax.php",
                    data: "fetch_item_master_details=1",
                    success: function(data){
                        result=JSON.parse(data);
                        console.log(result);
                        columnDefs = gridOptions.columnDefs;

                        $.each(result,function(i,text){
                        	console.log(result.keys());
                        	//columnDefs = [ {field:i, headerName:i}];
                        });
                        
                        gridOptions.api.setColumnDefs(columnDefs);
                      	//gridOptions.api.setRowData(result);
                    }
        });
    }
</script>
<script>

    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
    var gridOptions;
    // columnDefs = [
    //     {headerName: 'Edit', cellRenderer:'editCellRenderer',width:70,suppressFilter:true},
    //     {headerName:'Item Name',field: 'item_name',width:150,filter: 'agTextColumnFilter'},
    //     {headerName:'Description',field: 'description',width:220,filter: 'agTextColumnFilter'},
    //     {headerName:'HSN Code',field: 'hsn_code',width:150,filter: 'agTextColumnFilter'},
    //     {headerName:'Created On',field: 'createdOn',width:150,filter: 'agTextColumnFilter'}
    // ];
    function editCellRenderer(params){
        return "<a href='edit_item_master.php?imId="+params.data.imId+"' alt='Edit Mode' title='Mode' ><img style='text-align:center; width:20px; height:20px;' src='../../images/edit.png'/></a>"
    }
    gridOptions = {
        enableFilter:true,
        enableSorting: true,
        floatingFilter:true,
        rowSelection:'multiple',
        animateRows:true,
        components: {editCellRenderer : editCellRenderer
        },
   

   
    	 };
    var gridDiv = document.getElementById('myGrid');
    new agGrid.Grid(gridDiv,gridOptions);
</script>