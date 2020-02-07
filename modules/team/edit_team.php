<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");

$teamId = GetSafeValueString(isset($_GET["sid"])? $_GET["sid"]:$_POST["sid"], "long");


include("header.php");
?>
<style>
    table tr td{
        vertical-align: middle;
    }
</style>
<div class="panel">
    <div class="paneltitle" align="center">Update Team Member</div>
    <div class="panelcontents">
        <form method="post" name="edit_teamform" id="edit_teamform">
            <?php echo($message); ?>
            <table width="100%">
                <tr>
                <input type="hidden" name="team_id" id="team_id" value="<?php echo $teamId ?>">
                <td>Name <span style="color:red;">*</span></td><td><input id="tname" name = "tname" type="text" placeholder="Enter Name" required></td>
                <td>Phone <span style="color:red;">*</span></td><td><input name = "tphone" id="tphone" type="text" placeholder="Enter Phone" onkeypress="return onlyNos(event,this);" maxlength="12" required></td>
                </tr>
                <tr>
                <td>Email <span style="color:red;">*</span></td><td><input name = "temail" id="temail" type="text" placeholder="Enter Email" required></td>
                    <td>Login Role:</td>
                    <td> <select id="role" name="role">
                    <option id="Head" value="Head">Head</option>                
                    <option id="Admin" value="Admin">Admin</option>        
                    <option id="Service" value="Service">Service</option>  
                    <option id="Sales" value="Sales">Sales</option>  
                    <option id="CRM" value="CRM">CRM</option>  
                    <option id="Data" value="Data">Data</option> 
                    <option id="Distributor" value="Distributor">Distributor</option> 
                    <option id="Repair" value="Repair">Repair</option> 
                    </select>
                  </tr> 
                  <tr>
                    <td>Department</td>
                    <td><select name="departmentId" id="departmentId" required>
                        </select></td>
                    <td>Comapny Role</td>
                    <td><select name="company_role" id="company_role" required>
                           <option value=0>Select Role</option>
                        </select></td>
                </tr> 
                  <tr><td>Member Type: </td><td><input type="radio" name="membertype" value="1" id="elixir">Elixir <input type="radio" name="membertype" value="2" id="non_elixir"> Non - Elixir</td></tr>
                  
               <!--  <tr>
                <td>Login <span style="color:red;">*</span></td><td><input name = "tlogin" id="tlogin" type="text"  placeholder="Enter Username" required></td>
                <td>Password <span style="color:red;">*</span></td><td><input name = "tpassword" id="tpassword" type="password"  placeholder="Enter Password" required></td>
                </tr> -->
            </table>
            <?php
           if (IsHead()) {
            ?>
            <input style="margin: 0 45%;" type="button" id="submitpros" value="Update Team Member" onclick="editTeamMember();" />
            <?php
                }
            ?>
        </form>
    </div>
</div>
<script>
	jQuery(document).ready(function () {  
	 	var team_id = <?php echo $teamId; ?>;
	 	jQuery.ajax({
            type: "POST",
            url: "route_team.php",
            data: "get_team_members=1&team_id="+team_id,
            success: function(data){
                var temp_result=JSON.parse(data);
                var result = temp_result[0];
				$('#role option').each(function(){
					if($(this).attr('id') == result.role){
						$(this).attr('selected',true);
					}
				});
				$('#tname').val(result.name);
				$('#tphone').val(result.phone);
				$('#temail').val(result.email);
				$('#tname').val(result.name);
				if(result.member=='Elixir'){
					$("#elixir").attr('checked',true);
				}else{
					$("#non_elixir").attr('checked',true);
				}
				jQuery.ajax({
		            type: "POST",
		            url: "route_team.php",
		            data: "get_department=1",
		            success: function(data){
		                var department_result=JSON.parse(data);
		                $('#departmentId').html("");
		                $('#departmentId').append('<option value = '+"0"+'>'+"Select Department"+'</option>');
		                $.each(department_result ,function(i,text){
		                	if(result.departmentId==text.department_id){
		                		$('#departmentId').append('<option value = '+text.department_id+' selected>'+text.department+'</option>');
		                	}else{
		                		$('#departmentId').append('<option value = '+text.department_id+'>'+text.department+'</option>');
		                	}
		                });
		            }
		 		});
				var departmentId = result.departmentId;
		 		jQuery.ajax({
                    type: "POST",
                    url: "route_ajax.php",
                    data: "get_company_role=1&departmentId="+departmentId,
                    success: function(data){
                        var data=JSON.parse(data);
                        $('#company_role').html("");
                        $('#company_role').append('<option value = '+"0"+'>'+"Select Role"+'</option>');
                        $.each(data ,function(i,text){
                        	if(result.companyRoleId==text.c_roleId){
                        		$('#company_role').append('<option value = '+text.c_roleId+' selected>'+text.companyRole+'</option>');
                        	}else{
                        		$('#company_role').append('<option value = '+text.c_roleId+'>'+text.companyRole+'</option>');
                        	}
                            
                        });
                    }
                });  
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

</script>
<script>
	function editTeamMember(){
        var tname = $("#tname").val();
        var tphone =$("#tphone").val();
        var temail =$("#temail").val();
        var role = $("#role").val();
        var tlogin = $("#tlogin").val();
        var tpassword = $("#tpassword").val();
        var department = $("#purposeId").val();
        var co_roleId = $("#company_role").val();
        var email = $("#temail").val();
        var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
        if(!pattern.test(email)){         
             alert("Enter valid email id");
             $("#temail").focus();
             return false;
        }
        else if(tname==""){
            alert("Please enter name"); 
              $("#tname").focus();
            return false;
        }else if(tphone==""){
            alert("Please enter contact number");
              $("#tphone").focus();
            return false;
        }else if(department==0){
            alert("Please Select a department");
            return false;
        }
        else if(department!=0 && co_roleId==0){
            alert("Please Select a Company Role");
            return false;
        }
        else if(temail==""){
            alert("Please enter email id");
            return false;
        }
        else if($('input[name=membertype]:checked').length<=0){
         alert("Please select member type");
         return false;
        }
        else if(tlogin==""){
            alert("Please enter loginname");
            return false;
        }else if(tpassword==""){
            alert("Please enter password");
            return false;
        }else{
           var data = $("#edit_teamform").serialize();
           jQuery.ajax({
                    type: "POST",
                    url: "route_team.php",
                    data: "update_team_member=1&"+data,
                    success: function(data){
                        var result=JSON.parse(data);
                        console.log(result);
                        if(result==1){
                            alert("Team Member Updated Successfully");
                            window.location.reload();
                        }
                        else if(result==0){
                            alert("Username is already taken");
                        }
                        else{
                            alert("Please Try Again.");
                        }
                    }
            });
        }
    }
</script>