<?php
$get_uid = $_SESSION['userid'];
if (isset($_GET['uid'])) {
    $get_uid = (int) $_GET['uid'];
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
.row:after {
  content: "";
  display: table;
  clear: both;
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
<form method="POST" id="userHistoryForm">
	<table>
    <thead>
	    <tr>
	        <th id="formheader" colspan="100%">USER LOGS</th>
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
		<input id="userId" name="userId" type="hidden" value="<?php echo $get_uid; ?>" required/>
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
	    <td><input type="button" class="g-button g-button-submit" value="Get Logs" name="GetReport"onclick="getUserHistoryLogs();"></td>
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
  <legend>USER TRAIL <a href='javascript:void(0)' onclick="basic_excelDownload();"><img src="../../images/xls.gif" alt="Export to Excel" name="basic_excelImg" id="basic_excelImg" class='exportIcons' title="Export to Excel" style="display:none;"/></a></legend>
	<div id="basicDetailsGrid" class="ag-theme-fresh" style="display:none;height:250px;width:1200px;"></div>
</fieldset>

<fieldset>
  <legend>ADVANCE ALERTS TRAIL <a href='javascript:void(0)' onclick="vehicle_excelDownload();"><img src="../../images/xls.gif" alt="Export to Excel" name="vehicle_excelImg" id="vehicle_excelImg" class='exportIcons' title="Export to Excel" style="display:none;"/></a></legend>
	<div id="vehicleAlertsGrid" class="ag-theme-fresh" style="display:none;height:250px;width:1200px;"></div>
</fieldset>
<fieldset>
  <legend>EVENT ALERTS TRAIL <a href='javascript:void(0)' onclick="event_excelDownload();"><img src="../../images/xls.gif" alt="Export to Excel" name="event_excelImg" id="event_excelImg" class='exportIcons' title="Export to Excel" style="display:none;"/></a></legend>
	<div id="eventAlertsGrid" class="ag-theme-fresh" style="display:none;height:250px;width:1200px;"></div>
</fieldset>
<fieldset>
  <legend>STOPPAGE ALERTS TRAIL <a href='javascript:void(0)' onclick="stoppage_excelDownload();"><img src="../../images/xls.gif" alt="Export to Excel" name="stoppage_excelImg" id="stoppage_excelImg" class='exportIcons' title="Export to Excel" style="display:none;"/></a></legend>
	<div id="stoppageAlertsGrid" class="ag-theme-fresh" style="display:none;height:250px;width:1200px;"></div>
</fieldset>
<fieldset>
  <legend>VEHICLE MAPPING TRAIL <a href='javascript:void(0)' onclick="vehicleUserMap_excelDownload();"><img src="../../images/xls.gif" alt="Export to Excel" name="veh_user_excelImg" id="veh_user_excelImg" class='exportIcons' title="Export to Excel" style="display:none;"/></a></legend>
	<div id="vehUserMappingGrid" class="ag-theme-fresh" style="display:none;height:250px;width:600px;"></div>
</fieldset>
<fieldset>
  <legend>GROUP MAPPING TRAIL <a href='javascript:void(0)' onclick="groupUserMap_excelDownload();"><img src="../../images/xls.gif" alt="Export to Excel" name="group_user_excelImg" id="group_user_excelImg" class='exportIcons' title="Export to Excel" style="display:none;"/></a></legend>
	<div id="groupUserMappingGrid" class="ag-theme-fresh" style="display:none;height:250px;width:600px;"></div>
</fieldset>


<script>
	function getUserHistoryLogs(){
	  $("#basicDetailsGrid").hide();
	  $("#eventAlertsGrid").hide();
	  $("#stoppageAlertsGrid").hide();
	  $("#vehicleAlertsGrid").hide();
	  $("#vehUserMappingGrid").hide();

	  var totalRecords  = $("#total_records").val();
	  if(totalRecords==0){
	  	alert("Please select total number of records.");
	  	return false;
	  }

      jQuery('#pageloaddiv').show();
      var data = jQuery("#userHistoryForm").serialize();
   	  var basic_details = [];
   	  var eventAlerts = [];
   	  var vehicleAlerts = [];
   	  
      	jQuery.ajax({
	        url: "user_historyAjax.php",
	        type: 'POST',
	        data: "user_history=1&"+data,
	        success: function (result) {
	           var data=JSON.parse(result);
	           	if(data!=undefined){
	           		$.each(data,function(i,text){
									basic_details.push(text.basic_details);
									// console.log(text.conflict);
		          		eventAlerts.push(text.conflict);
		          		vehicleAlerts.push(text.vehicle_alerts);	
	            	});
	           	}
	          	
							gridOptions_BasicDetails.api.setRowData(basic_details);
							console.log(eventAlerts);
	            gridOptions_EventAlerts.api.setRowData(eventAlerts);
	            gridOptions_VehicleAlerts.api.setRowData(vehicleAlerts);
	        },
	        complete: function () {
	          jQuery('#pageloaddiv').hide();
	        }
      	});
      	
      	$("#vehUserMappingGrid").show();
      	$("#basicDetailsGrid").show();
	  	$("#eventAlertsGrid").show();
	 	$("#vehicleAlertsGrid").show();
	  	$("#basic_excelImg").show();
	  	$("#event_excelImg").show();
	  	$("#vehicle_excelImg").show();
	  	$("#veh_user_excelImg").show();

      	jQuery.ajax({
	        url: "user_historyAjax.php",
	        type: 'POST',
	        data: "vehicleUserMapping_stoppage_alerts=1&"+data,
	        success: function (result) {
	           	var data1=JSON.parse(result);
	           	var stoppage_alerts = [];
	           	var veh_userMapping_logs = [];
	           	
	           	//console.log(data1);
	           	if(data1.stoppage_alerts!=undefined){
					$.each(data1.stoppage_alerts,function(i,text){
					
						stoppage_alerts.push(text);
					});
					gridOptions_StoppageAlerts.api.setRowData(stoppage_alerts);
				}
				
				
				if(data1.veh_userMapping!=undefined){
					$.each(data1.veh_userMapping,function(i,text){
						veh_userMapping_logs.push(text);
					});
					console.log(veh_userMapping_logs);
					gridOptions_VehUserMap.api.setRowData(veh_userMapping_logs);
				}	
				
	        },
	        complete: function () {
	          jQuery('#pageloaddiv').hide();
	        }
      	});

	  	 $("#stoppageAlertsGrid").show();
	  	 $("#stoppage_excelImg").show();
		
		jQuery.ajax({
	        url: "user_historyAjax.php",
	        type: 'POST',
	        data: "groupUserMapping=1&"+data,
	        success: function (result) {
	           var data=JSON.parse(result);
	            gridOptions_GroupDetails.api.setRowData(data);
	         
	        },
	        complete: function () {
	          jQuery('#pageloaddiv').hide();
	        }
      	});

		$("#groupUserMappingGrid").show();
		$("#group_user_excelImg").show();
	}
</script>
<script src="https://unpkg.com/ag-grid-enterprise@18.1.1/dist/ag-grid-enterprise.min.js"></script>
<script src="../../bootstrap/js/bootstrap-datepicker.js"></script>
<script>
    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
</script>
<script>
	columnDefs = [
		{headerName:'Name',field: 'realnameui',width:150,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'},
		{headerName:'Email',field: 'emailui',width:200,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'},
		{headerName:'Phone',field: 'phoneui',width:150,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'},
		{headerName:'Role',field: 'roleui',width:150,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'},
		{headerName:'Email',field: 'dailyemailui',width:100,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'},
		{headerName:'CSV',field: 'dailyemail_csvui',width:100,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'},
		{headerName:'Inserted By',field: 'insertedBy',width:190,filter:'agTextColumnFilter'},
		{headerName:'Inserted On',field: 'insertedOn',width:200,filter:'agTextColumnFilter'},
	    {headerName:'Updated By',field: 'updatedBy',width:150,filter: 'agTextColumnFilter'},
	    {headerName:'Updated On',field: 'updatedOn',width:150,filter: 'agTextColumnFilter'},
	    {headerName:'Created By',field: 'createdBy',width:150,filter: 'agTextColumnFilter'},
	    {headerName:'Created On',field: 'createdOn',width:150,filter: 'agTextColumnFilter'}
	];
                        
	var gridOptions_BasicDetails;
		gridOptions_BasicDetails = {
		enableFilter:true,
		enableSorting: true,
		rowData: [],
		floatingFilter:true,
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
</script>
<script>
	columnDefs = [

		{
			headerName: "Checkpoint",
			children: [
				
		{headerName:'Email',field: 'chk_emailui',width:140,filter:'agTextColumnFilter',columnGroupShow:'open',cellRenderer:'htmlRenderer'   },
		{headerName:'Mobile',field: 'chk_mobileui',width:140,filter:'agTextColumnFilter',columnGroupShow:'open',cellRenderer:'htmlRenderer'   },
		{headerName:'Sms',field: 'chk_smsui',width:140,filter:'agTextColumnFilter',columnGroupShow:'open',cellRenderer:'htmlRenderer'   }
		]
	},
	{
			headerName: "Fence",
			children: [
		{headerName:'Email',field: 'mess_emailui',width:140,filter:'agTextColumnFilter',columnGroupShow:'open',cellRenderer:'htmlRenderer'   },
		{headerName:'Mobile',field: 'mess_mobileui',width:140,filter:'agTextColumnFilter',columnGroupShow:'open',cellRenderer:'htmlRenderer'   },
		{headerName:'Sms',field: 'mess_smsui',width:140,filter:'agTextColumnFilter',columnGroupShow:'open',cellRenderer:'htmlRenderer'   }
		]
	},
	{headerName:'Inserted By',field: 'insertedBy',width:170,filter:'agTextColumnFilter',  },
	{headerName:'Inserted On',field: 'insertedOn',width:170,filter:'agTextColumnFilter',  }
	];
                        
	var gridOptions_EventAlerts;
		gridOptions_EventAlerts = {
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
                             
//gridOptions.rowHeight = 20;
var gridDiv = document.getElementById('eventAlertsGrid');
new agGrid.Grid(gridDiv,gridOptions_EventAlerts);                    
</script>
<script>
	columnDefs = [
		{
			headerName: "AC",
			children: [
				{headerName:'Email',field: 'ac_email',width:100,filter:'agTextColumnFilter',columnGroupShow: 'closed'},
				{headerName:'Mob',field: 'ac_mobile',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open'},
				{headerName:'Sms',field: 'ac_sms',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open'},
				{headerName:'Interval',field: 'acinterval',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open'},
			 ]
    	},
    	{
    		headerName: "Door",
    		children: [
		    	{headerName:'Email',field: 'door_emailui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'closed',cellRenderer:'htmlRenderer'   },
				{headerName:'Mob',field: 'door_mobileui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   },
				{headerName:'Sms',field: 'door_smsui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   },
				{headerName:'Interval',field: 'doorintervalui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   }
		
		 	]
    	},
		{
    		headerName: "Fuel",
    		children: [
    			{headerName:'Email',field: 'fuel_alert_emailui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'closed',cellRenderer:'htmlRenderer'   },
				{headerName:'Mob',field: 'fuel_alert_mobileui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   },
				{headerName:'Sms',field: 'fuel_alert_smsui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   },
				{headerName:'Percentage',field: 'fuel_alert_percentageui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   }
    		]
    	},

    	{
    		headerName: "Harsh Break",
    		children: [
				{headerName:'Email',field: 'harsh_break_mailui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'closed',cellRenderer:'htmlRenderer'   },
				{headerName:'Mob',field: 'harsh_break_mobileui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   },
				{headerName:'Sms',field: 'harsh_break_smsui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   }
    		]
    	},

		{
    		headerName: "Acceleration",
    		children: [
				{headerName:'Email',field: 'high_acce_mailui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'closed',cellRenderer:'htmlRenderer'   },
				{headerName:'Mobile',field: 'high_acce_mobileui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   },
				{headerName:'SMS',field: 'high_acce_smsui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   }
    		]
    	},
		{
    		headerName: "Humidity",
    		children: [
    			{headerName:'Email',field: 'hum_mobileui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'closed',cellRenderer:'htmlRenderer'   },
				{headerName:'Mobile',field: 'hum_mobileui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   },
				{headerName:'SMS',field: 'hum_smsui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   },
				{headerName:'Interval ',field: 'humintervalui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   }
    		]
    	},
	
		{
    		headerName: "Ignition",
    		children: [
    			{headerName:'Email',field: 'ignition_emailui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'closed',cellRenderer:'htmlRenderer'   },
				{headerName:'Interval',field: 'ignintervalui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   },
				{headerName:'Mob',field: 'ignition_mobileui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   },
				{headerName:'SMS',field: 'ignition_smsui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   }
    		]
    	},

    	{
    		headerName: "Immobilizer",
    		children: [
				{headerName:'Email',field: 'immob_emailui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'closed',cellRenderer:'htmlRenderer'   },
				{headerName:'Mob',field: 'immob_mobileui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   },
				{headerName:'SMS',field: 'immob_smsui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   }
    		]
    	},

    	{
    		headerName: "Panic",
    		children: [
				{headerName:'Email',field: 'panic_emailui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'closed',cellRenderer:'htmlRenderer'   },
				{headerName:'Mob',field: 'panic_mobileui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   },
				{headerName:'SMS',field: 'panic_smsui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   }
    		]
    	},
		
		{
    		headerName: "Power",
    		children: [
				{headerName:'Email',field: 'power_emailui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'closed',cellRenderer:'htmlRenderer'   },
				{headerName:'Mob',field: 'power_mobileui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   },
				{headerName:'SMS',field: 'power_smsui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   }
    		]
    	},
		
		{
    		headerName: "Sharp Turn",
    		children: [
				{headerName:'Email',field: 'sharp_turn_mailui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'closed',cellRenderer:'htmlRenderer'   },
				{headerName:'Mob',field: 'sharp_turn_mobileui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   },
				{headerName:'SMS',field: 'sharp_turn_smsui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   }
    		]
    	},
		
		{
    		headerName: "Over Speeding",
    		children: [
    			{headerName:'Email',field: 'speed_emailui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'closed',cellRenderer:'htmlRenderer' },
				{headerName:'Interval',field: 'speedintervalui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer' },
				{headerName:'Mob',field: 'speed_mobileui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer' },
				{headerName:'SMS',field: 'ignition_smsui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer' }
    		]
    	},

    	{
    		headerName: "Temperature",
    		children: [
    			{headerName:'Email',field: 'temp_emailui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'closed',cellRenderer:'htmlRenderer'   },
				{headerName:'Interval',field: 'tempintervalui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   },
				{headerName:'Mob',field: 'temp_mobileui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   },
				{headerName:'SMS',field: 'temp_smsui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   }
    		]
    	},

    	{
    		headerName: "Tamper",
    		children: [
				{headerName:'Email',field: 'tamper_emailui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'closed',cellRenderer:'htmlRenderer'   },
				{headerName:'Mob',field: 'tamper_mobileui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   },
				{headerName:'SMS',field: 'tamper_smsui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   }
    		]
    	},

    	{
    		headerName: "Towing",
    		children: [
				{headerName:'Email',field: 'towing_mailui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'closed',cellRenderer:'htmlRenderer'   },
				{headerName:'Mob',field: 'towing_mobileui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   },
				{headerName:'SMS',field: 'towing_smsui',width:100,filter:'agTextColumnFilter',columnGroupShow: 'open',cellRenderer:'htmlRenderer'   }
    		]
    	},
    	{headerName:'Inserted By',field: 'insertedBy',width:120,filter:'agTextColumnFilter',  },
		{headerName:'Inserted On',field: 'insertedOn',width:120,filter:'agTextColumnFilter',   }

	];
                        
	var gridOptions_VehicleAlerts;
		gridOptions_VehicleAlerts = {
		rowData: [],
		enableFilter:true,
		enableSorting: true,
		floatingFilter:true,
		animateRows:true,
		columnDefs: columnDefs,
		pagination: true,
			paginationPageSize: 20,
			components:{htmlRenderer:htmlRenderer},
 
		
	};
                             
//gridOptions.rowHeight = 20;
var gridDiv = document.getElementById('vehicleAlertsGrid');
new agGrid.Grid(gridDiv,gridOptions_VehicleAlerts);                    
</script>
<script>

	columnDefs = [
		{
			headerName: "CheckPoint",
			children: [
					{headerName:'SMS',field: 'is_chk_smsui',width:120,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'   },
					{headerName:'Email',field: 'is_chk_emailui',width:120,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'   },
					{headerName:'Mobile',field: 'is_chk_mobilenotificationui',width:120,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'   },
					{headerName:'Idle Time',field: 'chkminsui',width:120,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'   }
				]
		},
		{
			headerName: "Transit",
			children: [
					{headerName:'SMS',field: 'is_trans_smsui',width:120,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'   },
					{headerName:'Email',field: 'is_trans_emailui',width:120,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'   },
					{headerName:'Mobile',field: 'is_trans_mobilenotificationui',width:120,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'   },
					{headerName:'Idle Time',field: 'transminsui',width:120,filter:'agTextColumnFilter',cellRenderer:'htmlRenderer'   }
				]
		},
		{headerName:'Inserted By',field: 'insertedBy',width:120,filter:'agTextColumnFilter', },
		{headerName:'Inserted On',field: 'insertedOn',width:120,filter:'agTextColumnFilter', }
	];
                        
	var gridOptions_StoppageAlerts;
		gridOptions_StoppageAlerts = {
		enableFilter:true,
		enableSorting: true,
		floatingFilter:true,
		rowData: [],
		animateRows:true,
		columnDefs: columnDefs,
		pagination: true,
    	paginationPageSize: 20,
 
			components:{htmlRenderer:htmlRenderer},
		
	};
                             
 //gridOptions.rowHeight = 20;
 var gridDiv = document.getElementById('stoppageAlertsGrid');
 new agGrid.Grid(gridDiv,gridOptions_StoppageAlerts);                    
</script>
<script>

	columnDefs = [
		{headerName:'Vehicle No',field: 'vehiclenoui',width:200,filter:'agTextColumnFilter',cellRenderer:'vehRender',cellRenderer:'htmlRenderer'   },
		{headerName:'Inserted By',field: 'insertedBy',width:200,filter:'agTextColumnFilter',   },
		{headerName:'Inserted On',field: 'insertedOn',width:200,filter:'agTextColumnFilter',  }
	];
                        
	var gridOptions_VehUserMap;
		gridOptions_VehUserMap = {
		enableFilter:true,
		enableSorting: true,
		floatingFilter:true,
		rowData: [],
		animateRows:true,
		columnDefs: columnDefs,
		pagination: true,
    	paginationPageSize: 20,
 
		components: {vehRender : vehRender,htmlRenderer:htmlRenderer}
	};
                             
     //gridOptions.rowHeight = 20;
     var gridDiv = document.getElementById('vehUserMappingGrid');
     new agGrid.Grid(gridDiv,gridOptions_VehUserMap);     

    function vehRender(params){
      
            if(params.data.isdeleted == '1'){
                return "<div style='background-color:Red;color:white'><strong><center>"+params.data.vehicleno+"</center></strong></div>";
            }else{
                return "<div style='background-color:Green;color:white'><strong><center>"+params.data.vehicleno+"</center></strong></div>";
    		}  
    }                
</script>
<script>

	columnDefs = [
		{headerName:'Group Name',field: 'groupnameui',width:200,filter:'agTextColumnFilter',cellRenderer:'grpRender',cellRenderer:'htmlRenderer'   },
		{headerName:'Inserted By',field: 'insertedBy',width:200,filter:'agTextColumnFilter',  },
		{headerName:'Inserted On',field: 'insertedOn',width:200,filter:'agTextColumnFilter',  }
	];
                        
	var gridOptions_GroupDetails;
		gridOptions_GroupDetails = {
		enableFilter:true,
		enableSorting: true,
		floatingFilter:true,
		rowData: [],
		animateRows:true,
		columnDefs: columnDefs,pagination: true,
    paginationPageSize: 20,
 
		components: {grpRender : grpRender,htmlRenderer:htmlRenderer}
	};
                             
     //gridOptions.rowHeight = 20;
     var gridDiv = document.getElementById('groupUserMappingGrid');
     new agGrid.Grid(gridDiv,gridOptions_GroupDetails);    

    function grpRender(params){
      
            if(params.data.isdeleted == '1'){
                return "<div style='background-color:Red;color:white'><strong><center>"+params.data.groupname+"</center></strong></div>";
            }else{
                return "<div style='background-color:Green;color:white'><strong><center>"+params.data.groupname+"</center></strong></div>";
    		}  
    }            
</script>
<script>
	function stoppage_excelDownload(){
        gridOptions_StoppageAlerts.api.exportDataAsExcel();
    }
    function vehicle_excelDownload(){
        gridOptions_VehicleAlerts.api.exportDataAsExcel();
    }
    function event_excelDownload(){
        gridOptions_EventAlerts.api.exportDataAsExcel();
    }
    function basic_excelDownload(){
        gridOptions_BasicDetails.api.exportDataAsExcel();
    }
    function vehicleUserMap_excelDownload(){
    	gridOptions_VehUserMap.api.exportDataAsExcel();
    }
    function groupUserMap_excelDownload(){
    	gridOptions_GroupDetails.api.exportDataAsExcel();
    }
</script>
