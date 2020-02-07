<?php
$get_vid = $_GET['vid'];
$reportDate = date("d-m-Y");

$isWareHouse = 0;
	if($_SESSION['switch_to']==3){
		$isWareHouse = 1;
	}
$isHumidity = 0;
	if($_SESSION['use_humidity']==1){
		$isHumidity = 1;
	}

$temp_sensors = $_SESSION['temp_sensors'];
?>
<style>
	/* Create three equal columns that floats next to each other */
	.column {
	  float: left;
	  padding: 10px;
	  height: 300px; /* Should be removed. Only for demonstration */
	}
	.small{

		 width: 33.33%;
	}
	.medium{
		 width: 50%;
	}
	.large{
		width: 100%;
	}
	.right{
		float: right !important;

	}
	.Reddot {
  height: 15px;
  width: 15px;
  background-color:red;
  border-radius: 50%;
  display: inline-block;
}
.Greendot {
  height: 15px;
  width: 15px;
  background-color:green;
  border-radius: 50%;
  display: inline-block;
}
</style>
<form method="POST" id="vehicleHistoryForm">
	<table>
	    <thead>
		    <tr>
		        <th id="formheader" colspan="100%">VEHICLE LOGS</th>
		    </tr>
	    </thead>
	    <tbody>
		    <tr>
		        <td colspan="100%">
		            <span id="error" name="error" style="color:red;display: none;">Data Not Available</span>
		            <span id="error1" name="error1" style="color:red;display: none;">End Date Cannot be Greater Today</span>
		            <span id="error2" name="error2" style="color:red;display: none;">Please Select Dates With Difference Of Not More Than 30 Days</span>
		            <span id="error3" name="error3" style="color:red;display: none;">Please Select a Vehicle</span>
		            <span id="error4" name="error4" style="color:red;display: none;">End Date should not be greater than today</span>
		            <span id="error5" name="error5" style="color:red;display: none;">End Date cannot be smaller than Start Date</span>
		        </td>
		    </tr>
			<tr>
				<td>Start Date</td>
				<td>End Date</td>
				<td>Total Records</td>
				<td>Generate Report</td>
			</tr>
			<tr>
				<input id="vehicleId" name="vehicleId" type="hidden" value="<?php echo $get_vid; ?>" required/>
				<!-- <input id="unitId" name="unitId" type="hidden" value="<?php echo $unitId; ?>" required/> -->
			    <td><input id="SDate" name="STdate" type="text" value="<?php echo $reportDate; ?>" required/></td>
			    <td><input id="EDate" name="EDdate" type="text" value="<?php echo $reportDate; ?>" required/></td>
			    <td>
			    	<select id="total_records" name="total_records">
				    	<option value="0">Select Total Records</option>
				    	<option value="10">10 Records</option>
				    	<option value="50">50 Records</option>
				    	<option value="100">100 Records</option>
				    	<option value="-1">All Records</option>
			    	</select>
			    </td>
			    <td><input type="button" class="g-button g-button-submit" value="Get Logs" name="GetReport"onclick="getVehicleHistoryLogs();"></td>
			</tr>
		</tbody>
	</table>
</form>
<table style="margin:0 90%;border:none;">
	<tr>
		<td><span class="Greendot"></span>Inserted Records</td>
		<td><span class="Reddot"></span>Deleted Records</td>
	</tr>
</table>
	<fieldset>
		<legend>VEHICLE TRAIL <a href='javascript:void(0)' onclick="basic_excelDownload();"><img src="../../images/xls.gif" alt="Export to Excel" name="basic_excelImg" id="basic_excelImg" class='exportIcons' title="Export to Excel" style="display:none;"/></a></legend>
		<div id="basicDetailsGrid" class="ag-theme-fresh" style="display:none;height:500px;width:1500px;"></div>
	</fieldset>
	<fieldset>
		<legend>CHECKPOINT TRAIL <a href='javascript:void(0)' onclick="chk_excelDownload();"><img src="../../images/xls.gif" alt="Export to Excel" name="chk_excelImg" id="chk_excelImg" class='exportIcons' title="Export to Excel" style="display:none;"/></a></legend>
		<div id="checkPointGrid" class="ag-theme-fresh" style="display:none;height:500px;width:600px;"></div>
	</fieldset>
	<fieldset>
		<legend>FENCE TRAIL <a href='javascript:void(0)' onclick="fence_excelDownload();"><img src="../../images/xls.gif" alt="Export to Excel" name="fence_excelImg" id="fence_excelImg" class='exportIcons' title="Export to Excel" style="display:none;"/></a></legend>
		<div id="fenceGrid" class="ag-theme-fresh" style="display:none;height:500px;width:600px;"></div>
	</fieldset>

<!-- 
</div>

 -->


<script>
	function getVehicleHistoryLogs(){
	  	$("#basicDetailsGrid").hide();
	  	$("#fenceGrid").hide();
      	$("#checkPointGrid").hide();
	  	$("#basic_excelImg").hide();
	 	$("#chk_excelImg").hide();
      	$("#fence_excelImg").hide();
      	$("#unitGrid").hide();
      	$("#unit_excelImg").hide();

      	var totalRecords  = $("#total_records").val();
	  	if(totalRecords==0){
	  		alert("Please select total number of records.");
	  		return false;
	 	}

      	jQuery('#pageloaddiv').show();
	      	var data = jQuery("#vehicleHistoryForm").serialize();
	      	jQuery.ajax({
		        url: "vehicle_historyAjax.php",
		        type: 'POST',
		        data: "vehicle_history=1&"+data,
		        success: function (result) {
		           var data=JSON.parse(result);
		            gridOptions_BasicDetails.api.setRowData(data);
		         
		        },
		        complete: function () {
		          jQuery('#pageloaddiv').hide();
		        }
      	});
      	$("#basicDetailsGrid").show();
      	$("#basic_excelImg").show();
	  	
	 
	  
      	jQuery.ajax({
	        url: "vehicle_historyAjax.php",
	        type: 'POST',
	        data: "checkpoint_fence_mapping=1&"+data,
	        success: function (result) {
	        	var checkpoint_logs = [];
	        	var fence_logs = [];
	           	var data1=JSON.parse(result);

	           	if(data1.checkpoint!=undefined){
	           		$.each(data1.checkpoint,function(i,text){
	          			checkpoint_logs.push(text);
	            	});
	           	}
	           	
	           	if(data1.fence!=undefined){
		            $.each(data1.fence,function(i,text){
		          		fence_logs.push(text);
		            });
	            }
	            
	            gridOptions_CheckPoint.api.setRowData(checkpoint_logs);
	            gridOptions_Fence.api.setRowData(fence_logs);
	       		
	        },
	        complete: function () {
	          jQuery('#pageloaddiv').hide();
	        }
      	});

      	

      	$("#fenceGrid").show();
      	$("#checkPointGrid").show();
      	$("#chk_excelImg").show();
      	$("#fence_excelImg").show();
      	$("#unitGrid").show();
      	$("#unit_excelImg").show();
    }
</script>
<script src="../../bootstrap/js/bootstrap-datepicker.js"></script>
<script src="https://unpkg.com/ag-grid-enterprise@18.1.1/dist/ag-grid-enterprise.min.js"></script>
<script>
    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
</script>
<script>
	columnDefs = [
		{headerName:'Vehicle No.',field: 'vehiclenoui',width:150,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'},
		{headerName:'Type',field: 'typeui',width:100,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'},
		{headerName:'OverSpeed',field: 'overspeed_limitui',width:150,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'},
		{headerName:'Average',field: 'averageui',width:120,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'},
		{headerName:'Fuel Cap.',field: 'fuelcapacityui',width:120,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'},
		{headerName:'Group',field: 'groupnameui',width:120,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'},
		{headerName:'Status(Deleted)',field: 'groupnameui',width:120,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'},
		{
			headerName: "Temperature",
			children: [
					{headerName:'Temp1 Min',field: 'temp1_min',width:120,filter:'agTextColumnFilter',columnGroupShow:'closed'},
					{headerName:'Temp1 Max',field: 'temp1_max',width:120,filter:'agTextColumnFilter',columnGroupShow:'open'},
					{headerName:'Allowance',field: 'temp1_allowance',width:120,filter:'agTextColumnFilter',columnGroupShow:'open'},

					{headerName:'Temp2 Min',field: 'temp2_min',width:120,filter:'agTextColumnFilter',columnGroupShow:'open'},
					{headerName:'Temp2 Max',field: 'temp2_max',width:120,filter:'agTextColumnFilter',columnGroupShow:'open'},
					{headerName:'Allowance',field: 'temp2_allowance',width:120,filter:'agTextColumnFilter',columnGroupShow:'open'},

					{headerName:'Temp3 Min',field: 'temp3_min',width:120,filter:'agTextColumnFilter',columnGroupShow:'open'},
					{headerName:'Temp3 Max',field: 'temp3_max',width:120,filter:'agTextColumnFilter',columnGroupShow:'open'},
					{headerName:'Allowance',field: 'temp3_allowance',width:120,filter:'agTextColumnFilter',columnGroupShow:'open'},

					{headerName:'Temp4 Min',field: 'temp4_min',width:120,filter:'agTextColumnFilter',columnGroupShow:'open'},
					{headerName:'Temp4 Max',field: 'temp4_max',width:120,filter:'agTextColumnFilter',columnGroupShow:'open'},
					{headerName:'Allowance',field: 'temp4_allowance',width:120,filter:'agTextColumnFilter',columnGroupShow:'open'},
				]
		},
		{
			headerName: "Humidity",
			children: [
					{headerName:'Hum Min',field: 'hum_min',width:120,filter:'agTextColumnFilter',columnGroupShow:'closed'},
					{headerName:'Hum Max',field: 'hum_max',width:120,filter:'agTextColumnFilter',columnGroupShow:'open'}
				]
		},
		{headerName:'Inserted On',field: 'insertedOn',width:200,filter:'agTextColumnFilter'},
		{headerName:'Inserted By',field: 'insertedBy',width:120,filter:'agTextColumnFilter'}
	];
                        
	var gridOptions_BasicDetails = {
		enableFilter:true,
		enableSorting: true,
		floatingFilter:true,
		animateRows:true,
		enableHorizontalScrollbar: 1,
		enableVerticalScrollbar: 1,
		scrollbarWidth:8,
		columnDefs: columnDefs,
		pagination: true,
		paginationPageSize: 10,
		components:{htmlRenderer:htmlRenderer}
	};
	function htmlRenderer(params){
		return params.value;
	}

	var temp_sensors = <?php echo $temp_sensors ?>;
	var wareHouse = <?php echo $isWareHouse ?>;
	var humidity = <?php echo $isHumidity ?>;
	                         
	var gridDiv = document.getElementById('basicDetailsGrid');
	new agGrid.Grid(gridDiv,gridOptions_BasicDetails);      

	if(temp_sensors==3){
		$("#basicDetailsGrid").css("width", "1100px");
		gridOptions_BasicDetails.columnApi.setColumnsVisible(["temp4_min", "temp4_max"], false);
	}
	if(temp_sensors==2){
		$("#basicDetailsGrid").css("width", "1100px");
		gridOptions_BasicDetails.columnApi.setColumnsVisible(["temp3_min", "temp3_max"], false);
		gridOptions_BasicDetails.columnApi.setColumnsVisible(["temp4_min", "temp4_max"], false);
	}
	if(temp_sensors==1){
		$("#basicDetailsGrid").css("width", "1100px");
		gridOptions_BasicDetails.columnApi.setColumnsVisible(["temp2_min", "temp2_max"], false);
		gridOptions_BasicDetails.columnApi.setColumnsVisible(["temp3_min", "temp3_max"], false);
		gridOptions_BasicDetails.columnApi.setColumnsVisible(["temp4_min", "temp4_max"], false);
	}
	if(temp_sensors==0){
		$("#basicDetailsGrid").css("width", "1100px");
		gridOptions_BasicDetails.columnApi.setColumnsVisible(["temp1_min", "temp1_max"], false);
		gridOptions_BasicDetails.columnApi.setColumnsVisible(["temp2_min", "temp2_max"], false);
		gridOptions_BasicDetails.columnApi.setColumnsVisible(["temp3_min", "temp3_max"], false);
		gridOptions_BasicDetails.columnApi.setColumnsVisible(["temp4_min", "temp4_max"], false);
	}
	if(humidity==0){
		gridOptions_BasicDetails.columnApi.setColumnsVisible(["hum_min"], false);
		gridOptions_BasicDetails.columnApi.setColumnsVisible(["hum_max"], false);
	}
	if(wareHouse==0){
		gridOptions_BasicDetails.columnApi.setColumnsVisible(["temp1_allowance"], false);
		gridOptions_BasicDetails.columnApi.setColumnsVisible(["temp2_allowance"], false);
		gridOptions_BasicDetails.columnApi.setColumnsVisible(["temp3_allowance"], false);
		gridOptions_BasicDetails.columnApi.setColumnsVisible(["temp4_allowance"], false);
	}
</script>
<script>

	columnDefs = [
	
		{headerName:'Vehicle No',field: 'vehicleno',width:150,filter:'agTextColumnFilter'},
		{headerName:'Name',field: 'chk_name',width:120,filter:'agTextColumnFilter',cellRenderer:'chkRender'},
		{headerName:'Inserted On',field: 'insertedOn',width:200,filter:'agTextColumnFilter'},
		{headerName:'Inserted By',field: 'insertedBy',width:120,filter:'agTextColumnFilter'}

	];
                        
	var gridOptions_CheckPoint;
		gridOptions_CheckPoint = {
		enableFilter:true,
		enableSorting: true,
		floatingFilter:true,
		rowData: [],
		animateRows:true,
		columnDefs: columnDefs, 
		pagination: true,
    	paginationPageSize: 20,
 
		components: {chkRender : chkRender}
	};
                             
     //gridOptions.rowHeight = 20;
     var gridDiv = document.getElementById('checkPointGrid');
     new agGrid.Grid(gridDiv,gridOptions_CheckPoint); 
    function chkRender(params){
      
            if(params.data.isdeleted == '1'){
                return "<div style='background-color:Red;color:white'><strong><center>"+params.data.chk_name+"</center></strong></div>";
            }else{
                return "<div style='background-color:Green;color:white'><strong><center>"+params.data.chk_name+"</center></strong></div>";
    		}  
    }
</script>
<script>

	columnDefs = [
	
		{headerName:'Vehicle No',field: 'vehicleno',width:150,filter:'agTextColumnFilter'},
		{headerName:'Name',field: 'fencename',width:120,filter:'agTextColumnFilter',cellRenderer:'fenceRender'},
		{headerName:'Inserted On',field: 'insertedOn',width:200,filter:'agTextColumnFilter'},
		{headerName:'Inserted By',field: 'insertedBy',width:120,filter:'agTextColumnFilter'}

	];
                        
	var gridOptions_Fence;
		gridOptions_Fence = {
		enableFilter:true,
		enableSorting: true,
		floatingFilter:true,
		rowData: [],
		animateRows:true,
		columnDefs: columnDefs, 
		pagination: true,
    	paginationPageSize:20,
 
		components: {fenceRender : fenceRender}
	};
                             
     //gridOptions.rowHeight = 20;
     var gridDiv = document.getElementById('fenceGrid');
     new agGrid.Grid(gridDiv,gridOptions_Fence);

    function fenceRender(params){
      
            if(params.data.isdeleted == '1'){
                return "<div style='background-color:Red;color:white'><strong><center>"+params.data.fencename+"</center></strong></div>";
            }else{
                return "<div style='background-color:Green;color:white'><strong><center>"+params.data.fencename+"</center></strong></div>";
    		}  
    }                    
</script>

<script>

	function basic_excelDownload(){
        gridOptions_BasicDetails.api.exportDataAsExcel();
    }
    function chk_excelDownload(){
        gridOptions_CheckPoint.api.exportDataAsExcel();
    }
    function fence_excelDownload(){
        gridOptions_Fence.api.exportDataAsExcel();
    }
    
</script>
