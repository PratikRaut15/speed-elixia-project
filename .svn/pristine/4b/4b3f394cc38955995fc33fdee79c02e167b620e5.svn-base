<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include("../../lib/bo/TeamManager.php");
include_once("../../lib/system/Date.php");
include_once("header.php");
$tm = new TeamManager();

$internetConnection = 0;
if($sock = @fsockopen('www.google.com', 80)){
 	$internetConnection=1; 
}

?>
<link rel="stylesheet" href="../../css/attendance_dashboard.css">

<div class="errorClass" id="no_internet" name="no_internet" style="display: none;">Please Check Internet Connection.</div>
<div id="correct_data" name="correct_data"></div>
<script>
	var internetConn = <?php echo $internetConnection; ?>;
	var startPage = 1;
	var endPage = 12;
	jQuery(document).ready(function () { 

		if(internetConn){
			fetchElixiaTeamMembers(startPage,endPage);
		}
		else{
			$("#no_internet").show();
			setInterval(function () {window.location.reload();},10000);
		}
	});

	function fetchElixiaTeamMembers(startPage,endPage){ 
		jQuery.ajax({
	      type: "POST",
	      url: "route_team.php",
	      data: "fetch_elixiaTeamMembers=1&office_Id=2",
	      success: function(data){
	        var result = JSON.parse(data);
	        attendance_dashboard(startPage,endPage,result);
	      }
	    });
	}

	function attendance_dashboard(startPage,endPage,result){
		var totalRecords = result.length;
		var str ="<div id='final_data'>";
        $.each(result,function(i,text){
        	
        	if(text.serial_no>=startPage && text.serial_no<=endPage){
        		if(text.busy_status==1){
	        			if(text.phoneExtension==null){
	        				text.phoneExtension='';
	        			}
        			str += "<div class='profile_grid busy'>";
        			str += "<span class='name_employee'>"+text.name+"</span><br/>";
        			str += "<br/><span class='phone_ext'><br/>"+text.phoneExtension+"</span></div>";
        		}
        		else if(text.busy_status==2){
	        			if(text.phoneExtension==null){
	        				text.phoneExtension='';
	        			}
        			str += "<div class='profile_grid check_in'>";
        			str += "<span class='name_employee'>"+text.name+"</span><br/>";
        			str += "<br/><span class='phone_ext'><br/>"+text.phoneExtension+"</span></div>";
        		}
        		else if(text.busy_status==3){
	        			if(text.phoneExtension==null){
	        				text.phoneExtension='';
	        			}
        			str += "<div class='profile_grid check_out'>";
        			str += "<span class='name_employee'>"+text.name+"</span><br/>";
        			str += "<br/><span class='phone_ext'><br/>"+text.phoneExtension+"</span></div>";
        		}
        		else{
        			if(text.phoneExtension==null){
	        				text.phoneExtension='';
	        			}
        			str += "<div class='profile_grid check_out'>";
        			str += "<span class='name_employee'>"+text.name+"</span><br/>";
        			str += "<br/><span class='phone_ext'><br/>"+text.phoneExtension+"</span></div>";
        		}
        		
        	}
        	
      	});
        str += "</div>";
      	$("#correct_data").after(str);

      	if(endPage>result.length){
			startPage=0;
			endPage=0;
			setTimeout(function () {window.location.reload();},10000);
		}
		
	
      	setTimeout(function () {pageIndexFunc(startPage,endPage,result);},15000);
	}
	
	function pageIndexFunc(startPage,endPage,result){
		$("#final_data").remove();
		var newStartPage = 1;
		var newEndPage = 12;

		if((endPage%12)==0){
			newStartPage = endPage+1;
			newEndPage = endPage+12;
		}
		attendance_dashboard(newStartPage,newEndPage,result);
	}
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
