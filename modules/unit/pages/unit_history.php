<?php
if (isset($_GET['uid'])) {
   $unit_id = $_GET['uid'];
}

$reportDate = date("d-m-Y");
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
<form method="POST" id="unitHistoryForm">
	<table>
	    <thead>
		    <tr>
		        <th id="formheader" colspan="100%">UNIT LOGS</th>
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
				<input id="unitId" name="unitId" type="hidden" value="<?php echo $unit_id; ?>" required/>
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
			    <td><input type="button" class="g-button g-button-submit" value="Get Logs" name="GetReport"onclick="UnitHistoryLog();"></td>
			</tr>
		</tbody>
	</table>
</form>
	<fieldset>
		<legend>UNIT TRAIL <a href='javascript:void(0)' onclick="unit_excelDownload();"><img src="../../images/xls.gif" alt="Export to Excel" name="unit_excelImg" id="unit_excelImg" class='exportIcons' title="Export to Excel" style="display:none;"/></a></legend>
		<div id="unitGrid" class="ag-theme-fresh" style="display:none;height:300px;width:600px;"></div>
	</fieldset>


 <script src="../../bootstrap/js/bootstrap-datepicker.js"></script>
<script src="https://unpkg.com/ag-grid-enterprise@18.1.1/dist/ag-grid-enterprise.min.js"></script>

<script>
    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");

   
</script>
<script>

	function UnitHistoryLog(){

		   $("#unitGrid").hide();
	  $("#unit_excelImg").hide();

	   var totalRecords  = $("#total_records").val();
	  if(totalRecords==0){
	  	alert("Please select total number of records.");
	  	return false;
	  }

	  
		jQuery('#pageloaddiv').show();
	      	var data = jQuery("#unitHistoryForm").serialize();
	      	jQuery.ajax({
		        url: "../vehicle/vehicle_historyAjax.php",
		        type: 'POST',
		        data: "unit_history=1&"+data,
		        success: function (result) {
		           var data=JSON.parse(result);
		            gridOptions_UnitDetails.api.setRowData(data);
		         
		        },
		        complete: function () {
		          jQuery('#pageloaddiv').hide();
		    }
     	});

	      	$("#unitGrid").show();
	  $("#unit_excelImg").show();
	}
	

	columnDefs = [
		{headerName:'Unit No',field: 'unitno',width:120,filter:'agTextColumnFilter'},
		{headerName:'Vehicle No',field: 'vehicleno',width:150,filter:'agTextColumnFilter'},
		
		{headerName:'Inserted By',field: 'insertedBy',width:120,filter:'agTextColumnFilter'},
		{headerName:'Inserted On',field: 'insertedOn',width:200,filter:'agTextColumnFilter'}

	];
                        
	var gridOptions_UnitDetails;
		gridOptions_UnitDetails = {
		enableFilter:true,
		enableSorting: true,
		floatingFilter:true,
		rowData: [],
		animateRows:true,
		columnDefs: columnDefs,
		
	};
                             
     //gridOptions.rowHeight = 20;
     var gridDiv = document.getElementById('unitGrid');
     new agGrid.Grid(gridDiv,gridOptions_UnitDetails);            

      function unit_excelDownload(){
        gridOptions_UnitDetails.api.exportDataAsExcel();
    }        
</script>