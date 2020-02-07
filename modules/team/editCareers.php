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


$career_id = isset($_GET['careerId'])?$_GET['careerId']:0;

if($career_id==0){
	header("Location: viewCareers.php");
}
include("header.php");


?>
<!DOCTYPE html>
<html>
	<style>
		.labelClass{
			margin-left: 5%;
			font-size: 16px;
		}
		#cke_editor1{
			margin-top: 10px;
		}
		#cke_editor2{
			margin-top: 10px;
		}
		#cke_editor3{
			margin-top: 10px;
		}
		#cke_editor4{
			margin-top: 10px;
		}
	</style>
	<head>
		<meta charset="utf-8">
		<script src="https://cdn.ckeditor.com/4.10.1/standard/ckeditor.js"></script>
	</head>

	<body>
		<link rel="stylesheet" href="../../css/invoicePayment.css">
		<div class="panel" style=''>
			<div class="paneltitle" align="center">Edit Career Details</div>		
			<form id="editCareer" name="editCareer" >
				<input type="hidden" name="careerId" id="careerId" value="<?php echo $career_id; ?>">
				<lable class="labelClass">Job Location<span style="color: #FF0000"> *</span></lable>
				<input type="text" name="location" id="location" placeholder="Example:- Mumbai" required>
				<lable class="labelClass">Experience<span style="color: #FF0000"> *</span></lable>
				<input type="text" name="experience" id="experience" placeholder="Example:- 5-7" required> (in years)
				<br>
				<lable class="labelClass">Department</lable>
                    <select name="departmentId" id="departmentId" required>
                        </select>
                    <lable class="labelClass">Comapny Role</lable>
                    <select name="company_role" id="company_role" required>
                           <option value=0>Select Role</option>
                        </select>
                <br><br>
				<label class="labelClass">What are we looking for ?<span style="color: #FF0000"> *</span></label>
				<br>
				<textarea name="editor1"></textarea>
				<script>
					CKEDITOR.replace( 'editor1' );
				</script>
				<br>
				<label class="labelClass">Preferred,Not Required
				<br>
				<textarea name="editor2"></textarea>
				<script>
					CKEDITOR.replace( 'editor2' );
				</script>
				<br>
				<label class="labelClass">What kinda challenges do we have ?
				<br>
				<textarea name="editor3"></textarea>
				<script>
					CKEDITOR.replace( 'editor3' );
				</script>
				<br>
				<label class="labelClass">What will you do ?</label>
				<br>
				<textarea name="editor4"></textarea>
				<script>
					CKEDITOR.replace( 'editor4' );
				</script>
				<br>

				<input type="button" value="Submit"  style="margin:0 40%;" onclick="submitContent();">
			</form>
		</div>
	</body>		
</html>

<script>
	var cId = $("#careerId").val();
	jQuery(document).ready(function () {  
		
        jQuery.ajax({
            type: "POST",
            url: "route_team.php",
            data: "fetch_jobOpeningDetails=1&careerId="+cId,
            success: function(data){
                var result=JSON.parse(data);
                $("#location").val(result.location);
                $("#experience").val(result.experience);
                jQuery.ajax({
		            type: "POST",
		            url: "route_team.php",
		            data: "get_department=1",
		            success: function(data){
		                var department_result=JSON.parse(data);
		                $('#departmentId').html("");
		                $('#departmentId').append('<option value = '+"0"+'>'+"Select Department"+'</option>');
		                $.each(department_result ,function(i,text){
		                	if(result.department_Id==text.department_id){
		                		$('#departmentId').append('<option value = '+text.department_id+' selected>'+text.department+'</option>');
		                	}else{
		                		$('#departmentId').append('<option value = '+text.department_id+'>'+text.department+'</option>');
		                	}
		                });
		            }
		 		});
				var departmentId = result.department_Id;
		 		jQuery.ajax({
                    type: "POST",
                    url: "route_ajax.php",
                    data: "get_company_role=1&departmentId="+departmentId,
                    success: function(data){
                        var data=JSON.parse(data);
                        $('#company_role').html("");
                        $('#company_role').append('<option value = '+"0"+'>'+"Select Role"+'</option>');
                        $.each(data ,function(i,text){
                        	if(result.company_role==text.c_roleId){
                        		$('#company_role').append('<option value = '+text.c_roleId+' selected>'+text.companyRole+'</option>');
                        	}else{
                        		$('#company_role').append('<option value = '+text.c_roleId+'>'+text.companyRole+'</option>');
                        	}
                            
                        });
                    }
                }); 


                CKEDITOR.instances['editor1'].setData(result.requirement);
                CKEDITOR.instances['editor2'].setData(result.preference);
                CKEDITOR.instances['editor3'].setData(result.challenges);
                CKEDITOR.instances['editor4'].setData(result.job_role);
            }
        });

    });

	$("#departmentId").change(function(){
            var departmentId = $("#departmentId").val();
            if(departmentId!='0'){
                $("#company_role").attr('readonly',false);
                jQuery.ajax({
                    type: "POST",
                    url: "route_ajax.php",
                    data: "get_company_role=1&departmentId="+departmentId,
                    success: function(data){
                        var data=JSON.parse(data);
                        $('#company_role').html("");
                        $('#company_role').append('<option value = '+"0"+'>'+"Select Role"+'</option>');
                        $.each(data ,function(i,text){
                            $('#company_role').append('<option value = '+text.c_roleId+'>'+text.companyRole+'</option>');
                        });
                    }
                });  
            }
            else{
                 $("#company_role").attr('readonly',true);
            }
	});

	function submitContent(){
      	var requirement = CKEDITOR.instances['editor1'].getData();
      	var preference = CKEDITOR.instances['editor2'].getData();
      	var challenges = CKEDITOR.instances['editor3'].getData();
      	var job_role = CKEDITOR.instances['editor4'].getData();
      	var loctn = $("#location").val();
      	var exp = $("#experience").val();
      	var department = $("#departmentId").val();
        var co_roleId = $("#company_role").val();

        if(loctn=='' || exp=='' || department=='' || co_roleId==''){
        	alert("Please Insert Mandatory Fields");
        	return false;
        }
   		else{

   			var formData = new FormData("#editCareer");
   			formData.append('update_jobOpening',1);
			formData.append('careerId',cId);
			formData.append('requirement',requirement);
			formData.append('pref',preference);
			formData.append('challenge',challenges);
			formData.append('jobRole',job_role);
			formData.append('location',loctn);
			formData.append('exp',exp);
			formData.append('dept',department);
			formData.append('compRole',co_roleId);

   			jQuery.ajax({
				type: "POST",
				url: "route_team.php",
				data: formData,
				processData: false,
				contentType: false,
				cache: false,
				success: function(data){
					var result = JSON.parse(data);
					if(result==1){
						alert("Job Opening Updated Successfully.");
					}
					else{
						alert("Please Try Again.");
					}
				}		        
			});
   		}
      	
 	}
</script>