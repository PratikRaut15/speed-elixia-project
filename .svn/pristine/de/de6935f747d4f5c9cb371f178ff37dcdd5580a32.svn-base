<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include("../../lib/bo/TeamManager.php");
include("../../lib/bo/TimeSheetManager.php");
include_once("../../lib/system/Date.php");
date_default_timezone_set("Asia/Calcutta");
$today = date("d-m-Y");
$time= date("h:i");
$created_on=date("Y-m-d H:i:s");
$isHead = checkUserType('',speedConstants::TEAM_ROLE_HEAD);
include("header.php");
?>

<div class="panel">
<?php
	include("timesheetList.php");
?>
<link rel="stylesheet" href="../../css/timesheet.css">
    <div class="panelcontents">
      <div class="center">
        <form name="updateTime" id="updateTime" style="margin:0 auto;display:block;">
          <input type="hidden" id="department" name="department" value="<?php echo $_SESSION['department']?>">
              <table name="addTaskTable" id="addTaskTable">
                <tr>
                  <input type="hidden" id="teamId" name="teamId" value="<?php echo GetLoggedInUserId();?>">
                  <td>
				    <div class="switch-field" >
				      	<div class="switch-title">Please select</div>
						<input type="radio" id="switch_left" name="dayType" value="LE" />
						<label for="switch_left">Leave</label>
						<input type="radio" id="switch_right" name="dayType" value="HO" />
						<label for="switch_right">Holiday</label>
			    	</div>
                  </td>
              	</tr>
              	<tr>
              		<td style="padding-left:100px;">
          				<input type="button" name="lock_timesheet" id="lock_timesheet" align="center" style="margin:0 auto;display:block;" value="Lock" onclick="lockTimeSheet();">
              		</td>
      		  	</tr>
          	</table>
        </form>
      </div>     
    </div>
</div>
<script>
	function lockTimeSheet(){
		var message = '';
		date = $("#dateTS").val();
		teamId = $("#teamId").val();
		var dayType = $("[name='dayType']:checked").val();
		var dayTypes = new Array();
		dayTypes = {
			"LE":"Leave",
			"HO":"Holiday",
			"HA":"Half Day",
			"FU":"Full Day",
			"OV":"Over Time",
		};
		if(teamId == '' || date == ''){
			alert("There was an error while locking your timesheet. Please refresh the page and try again.");
		}else{
			if(duration == 0){
				if(dayType==undefined){
					message = "Your total duration is 0 hours. Please select either Leave or Holiday and submit.";
					alert(message);
					return;
				}else{
					message = "Your total duration is 0 hours. Submitting will mark your day as a "+dayTypes[dayType];
				}
			}
			else if (duration < 8) {
				dayType = "HA";
				message = "Your total duration is less than 8 hours. Submitting will mark your day as a half day.";
			}else if(duration == 8){
				dayType = "FU";
				message = "Your total duration is "+duration+" hours. Submitting will mark your day as a full day.";
			}else{
				dayType = "OV";
				message = "Your total duration is more than 8 hours. Submitting will mark your day as over time.";
			}
		  	if(confirm(message)){
		  		jQuery.ajax({
				    type: "POST",
				    url: "timesheet_functions.php",
				    data: {
				    	lockTimesheet : 1,
				    	teamId : teamId,
				    	duration : duration,
				    	date : date,
				    	dayType : dayType,
				    },
				    success: function(data){
				    	refreshTimesheetGrid();
				    }
			  	});
		  	}
		}
	}
</script>
