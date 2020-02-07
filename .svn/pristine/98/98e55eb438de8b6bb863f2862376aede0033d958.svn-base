$(document).ready(function(){
	if(team_Id==0){
		$("#estimated_time").attr('readonly',false);
		$("#estimated_date").attr('readonly',false);
	}
	jQuery('#estimated_date').datepicker({
		dateFormat: "dd-mm-yy",
		language: 'en',
		autoclose: 1,
		startDate: Date()
	});

	jQuery.ajax({
  		type: "POST",
  		url: "timesheet_functions.php",
  		data: "fetch_remarks_history=1&taskId="+task_ID,
  		success: function(data){
	        var data=JSON.parse(data);
	        console.log(data);
	  		if(data!=undefined){
              var str1='';
              str1="<table class='remarks_table' id ='remarks_table' border='1' align='center'><tr><th>Sr no.</th><th>Remarks</th><th>Name</th><th>Status</th></tr>";
                $.each(data,function(i,text){
                    var j = i+1;
                    str1+="<tr>";
                    str1+="<td>"+j+"</td>";
                    str1+="<td style='width:100px;'>"+text.remarks+"</td>";
                    str1+="<td style='width:100px;'>"+text.name+"</td>";
                    str1+="<td style='width:100px;'>"+text.StatusName+"</td>";
                    str1+="</tr>";
                });
                str1+="</table>";
                $("#remarks_table_div").after(str1);
            }
		}
	});
});


jQuery.ajax({
  type: "POST",
  url: "timesheet_functions.php",
  data: "fetchProducts=1",
  success: function(data){
        var data=JSON.parse(data);
  		$('#product').html("");
  		$('#product').append('<option value = '+"0"+'>'+"Select Product"+'</option>');
		$.each(data, function(i, text) {
				if (taskDetails.productId == text.prodId) {
				
				$('#product').append("<option value='" + text.prodId + "' selected>" + text.prodName + "</option>");
				} 
				else {
					$('#product').append("<option value='" + text.prodId + "'>" + text.prodName + "</option>");
				}
		});
	}
});

jQuery.ajax({
  type: "POST",
  url: "timesheet_functions.php",
  data: "fetchDevelopers=1",
  success: function(data){
        var data=JSON.parse(data);
  		$('#developer').html("");
  		$('#developer').append('<option value = '+"0"+'>'+"Select Developer"+'</option>');
		$.each(data, function(i, text) {
				if (taskDetails.developerId == text.teamid) {
				
				$('#developer').append("<option value='" + text.teamid + "' selected>" + text.name + "</option>");
				} 
				else {
					$('#developer').append("<option value='" + text.teamid + "'>" + text.name + "</option>");
				}
		});
	}
});

jQuery.ajax({
  type: "POST",
  url: "timesheet_functions.php",
  data: "fetchTesters=1",
  	success: function(data){
        var data=JSON.parse(data);
  		$('#tester').html("");
  		$('#tester').append('<option value = '+"0"+'>'+"Select Tester"+'</option>');
		$.each(data, function(i, text) {
				if (taskDetails.testerId == text.teamid) {
				$('#tester').append("<option value='" + text.teamid + "' selected>" + text.name + "</option>");
				} 
				else {
					$('#tester').append("<option value='" + text.teamid + "'>" + text.name + "</option>");
				}
		});
	}
});

jQuery.ajax({
  type: "POST",
  url: "timesheet_functions.php",
  data: "fetchMigrators=1",
  	success: function(data){
        var data=JSON.parse(data);
  		$('#migrator').html("");
  		$('#migrator').append('<option value = '+"0"+'>'+"Select Migrator"+'</option>');
		$.each(data, function(i, text) {
				if (taskDetails.migratorId == text.teamid) {
				
				$('#migrator').append("<option value='" + text.teamid + "' selected>" + text.name + "</option>");
				} 
				else {
					$('#migrator').append("<option value='" + text.teamid + "'>" + text.name + "</option>");
				}
		});
	}
});

jQuery.ajax({
  type: "POST",
  url: "timesheet_functions.php",
  data: "fetchStatus=1",
  	success: function(data){
        var data=JSON.parse(data);
  		$('#status').html("");
  		$('#status').append('<option value = '+"0"+'>'+"Select Status"+'</option>');
		$.each(data, function(i, text) {
				if (taskDetails.statusId == text.statusId) {
				$("#tempStatus").val(taskDetails.statusId);
				$('#status').append("<option value='" + text.statusId + "' selected>" + text.statusName + "</option>");
				} 
				else {
					$('#status').append("<option value='" + text.statusId + "'>" + text.statusName + "</option>");
				}
		});
	}
});

function editTask(){
	var data = $("#edit_task").serialize();
	var product = $("#product").val();
	var old_statusId = $("#tempStatus").val();
	var new_statusId = $("#status").val();
	var remarks = $("#remarks_task").val();

	
	if(product==0 || product==''){
		alert("Select Product");
		return false;
	}
	else if(old_statusId != new_statusId && remarks==''){
		alert("Please Enter Remarks");
		$("#remarks_task").focus();
		return false;
	}
	else{
		jQuery.ajax({
			type: "POST",
			url: "timesheet_functions.php",
			data: "&update_task=1&"+data,
				success: function(data){
					var result=JSON.parse(data);
					if(result==1){
						alert("Task Successfully Updated");
						if(view_task==1){
							window.location.href = 'view_task.php';
						}
						else{
							window.location.href = 'timesheet.php';
						}
					}
					else{
						alert("Task Not Updated.Please Try Again");
					}
				}
		});
	}
}

$('#estimated_time').on('input', function() {
this.value = this.value
.replace(/[^\d.]/g, '')             // numbers and decimals only
.replace(/(\..*)\./g, '$1')         // decimal can't exist more than once
.replace(/(\.[\d]{2})./g, '$1');    // not more than 2 digits after decimal
});
