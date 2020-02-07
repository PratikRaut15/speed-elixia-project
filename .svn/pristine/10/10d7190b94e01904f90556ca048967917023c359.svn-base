<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Date.php");
include_once("../../lib/bo/TimeSheetManager.php");
include_once("header.php");
date_default_timezone_set("Asia/Calcutta");
$today = date("d-m-Y");
//print_r($_SESSION);
$departmentId = $_SESSION['department'];
if(checkUserType(speedConstants::TEAM_DEPARTMENT_MANAGEMENT)){
  $departmentId = 0;
}
$time= date("h:i");
$created_on=date("Y-m-d H:i:s");
$tm = new TimeSheetManager();
$name = $_SESSION['sessionteamloginuser'];
$teamId = GetLoggedInUserId();
if(!checkUserType('',speedConstants::TEAM_ROLE_HEAD)){
  $disabled = 'disabled';
}else{ 
  $disabled = '';
}
?>
<link rel="stylesheet" href="../../css/timesheet.css">
<div class="panel"> 
  <div class="paneltitle" align="center">Add Member Role</div> 
    <div class="panelcontents" >
      <div class="center" style='margin:0 auto;'>  
        <form id='teamRole' namme='teamRole'>
          <table>
            <tr>
              <td>
                <label>Team Member : </label>
              </td>
              <td>
                <input type="text" id='name' name='name' value='<?php echo $name?>' <?php echo  $disabled?>>
                <input type='hidden' id='teamId' name='teamId' value='<?php echo $teamId;?>'>
                <input type='hidden' id='departmentId' name='departmentId' value='<?php echo $_SESSION["department"]?>'>
              </td>
              <td>
                <label>Role : </label>
              </td>
              <td>
                <select id='role'>
                  <option value='0'>Select Role</option>
                </select>
              </td>
            </tr>
            <tr>
              <td>
              </td>
              <td>
                <input type='button' id='submitRole' name='submitRole' value='Submit'>
              </td>
            </tr>
          </table>
        </form>
      </div>
  </div>
</div>
<?php include('teamList.php');?>
<script>
  var teamId = <?php echo $teamId;?>;
  var departmentId = <?php echo $departmentId;?>;
  $(document).ready(function(){
    $.ajax({
      type: "POST",
      url: "timesheet_functions.php",
      data: {
        teamRoles:1,
        teamId : teamId,
        departmentId : departmentId,
      },
      success: function(data){
        var roles = JSON.parse(data);
        $.each(roles,function(k,v){
          //console.log(v);
          $("#role").append("<option value="+v.roleId+" data-dept="+v.departmentId+">"+v.roleName+"</option>");
        });
      }
    });
  });
    jQuery("#name").autocomplete({
      type:  "post",
      source: "timesheet_functions.php?teamList=1",
      select: function (event2, ui) {
        console.log(ui);
        $("#teamId").val(ui.item.teamid);
        $("#departmentId").val(ui.item.department_id);
      }
    });

    $("#submitRole").on('click',function(){
      var name = $("#name").val();
      var roleId = $("#role").val();
      var teamId = $("#teamId").val();
      var departmentId = $("#departmentId").val();
      if(name==''){
        alert("Please select team member");
      }else if (roleId==0){
        alert("Please select Role");
      }else{
      $.ajax({
          type: "POST",
          url: "timesheet_functions.php",
          data: {
            submitRole:1,
            teamId : teamId,
            roleId : roleId,
            departmentId : departmentId,
          },
          success: function(data){
            var data = JSON.parse(data);
            if(data.found == 1){
              alert("Already Exists");
            }else{
              refreshGrid();
              alert("Member added successfully");
            }
          }
        });
      }
    });
  $.ajax({
    type: "POST",
    url: "timesheet_functions.php",
    data: {
      fetchTeam:1,
      department : departmentId,
    },
    success: function(data){
      var teamList = JSON.parse(data);
    }
  });
</script>