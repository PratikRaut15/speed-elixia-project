<?php
if (isset($_GET['did'])) {
   $group_id = $_GET['did'];
}

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
	/* Clear floats after the columns */
	/*.row:after {
	  content: "";
	}*/
</style>
<form method="POST" id="groupHistoryForm">
	<table>
	    <thead>
		    <tr>
		        <th id="formheader" colspan="100%">GROUP LOGS</th>
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
				<input id="groupId" name="groupId" type="hidden" value="<?php echo $group_id; ?>" required/>
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
			    <td><input type="button" class="g-button g-button-submit" value="Get Logs" name="GetReport"onclick="GroupHistoryLogs();"></td>
			</tr>
		</tbody>
	</table>
</form>
	<fieldset>
		<legend>BRANCH TRAIL <a href='javascript:void(0)' onclick="groupExcelExport();"><img src="../../images/xls.gif" alt="Export to Excel" name="group_excelImg" id="group_excelImg" class='exportIcons' title="Export to Excel" style="display:none;"/></a></legend>
		<div id="basicDetailsGrid" class="ag-theme-fresh" style="display:none;height:400px;width:1000px;"></div>
	</fieldset>

<script>
	function GroupHistoryLogs(){
	  $("#basicDetailsGrid").hide();
	  $("#group_excelImg").hide();

	  var totalRecords  = $("#total_records").val();
	  if(totalRecords==0){
	  	alert("Please select total number of records.");
	  	return false;
	  }
      jQuery('#pageloaddiv').show();
      var data = jQuery("#groupHistoryForm").serialize();
      	jQuery.ajax({
	        url: "group_ajax.php",
	        type: 'POST',
	        data: "group_history=1&"+data,
	        success: function (result) {
	           var data=JSON.parse(result);
	           console.log(data);
	            gridOptions_BasicDetails.api.setRowData(data);
	         
	        },
	        complete: function () {
	          jQuery('#pageloaddiv').hide();
	        }
      	});
      	$("#basicDetailsGrid").show();
      	$("#group_excelImg").show();
    }
</script>
 <script src="../../bootstrap/js/bootstrap-datepicker.js"></script>
<script src="https://unpkg.com/ag-grid-enterprise@18.1.1/dist/ag-grid-enterprise.min.js"></script>
<script>
    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
</script>
<script>

	columnDefs = [
		{headerName:'Branch Name',field: 'groupnameui',width:200,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'},
		{headerName:'Branch Code',field: 'codeui',width:200,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'},
		{headerName:'Branch Region',field: 'branch_regionui',width:200,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'},
		{headerName:'Inserted By',field: 'insertedBy',width:200,filter:'agTextColumnFilter'},
		{headerName:'Inserted On',field: 'insertedOn',width:200,filter:'agTextColumnFilter'}
		
	];
                        
	var gridOptions_BasicDetails;
		gridOptions_BasicDetails = {
		enableFilter:true,
		enableSorting: true,
		floatingFilter:true,
		rowData: [],
		animateRows:true,
		columnDefs: columnDefs,
		pagination: true,
		paginationPageSize: 20,
		components:{htmlRenderer:htmlRenderer}
	};
	function htmlRenderer(params){
		return params.value;
	}                  
     //gridOptions.rowHeight = 20;
     var gridDiv = document.getElementById('basicDetailsGrid');
     new agGrid.Grid(gridDiv,gridOptions_BasicDetails);      

      function groupExcelExport(){
    	gridOptions_BasicDetails.api.exportDataAsExcel();
    }              
</script> 