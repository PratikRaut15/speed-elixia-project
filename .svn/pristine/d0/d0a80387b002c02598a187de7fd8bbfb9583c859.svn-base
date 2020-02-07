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
$departmentId = $_SESSION['department'];
if(checkUserType(speedConstants::TEAM_DEPARTMENT_MANAGEMENT)){
  $departmentId = 0;
}
include("header.php");
?>
<link rel="stylesheet" href="../../css/timesheet.css">
<div class="panel">
  <div class="paneltitle" align="center">Create Task</div> 
    <div class="panelcontents">
      <div class="center">
        <form name="addTask" id="addTask">
          <input type="hidden" id="department" name="department" value="<?php echo $departmentId?>">
              <table name="addTaskTable" id="addTaskTable">
                <tr>
                  <td>
                    <label>Customer</label>
                  </td>
                  <td>
                    <input type="text" name="customername" id="customername"  class="searchArea" placeholder="Search Customer" onkeypress="getCustomer();" />
                  </td>
                  <td>
                    <label>Product</label>
                  </td>
                  <td>
                    <input type="text" name="product" id="product" class="searchArea" placeholder="Search Products">
                  </td>
                  <td>
                    <label>Estimated Time</label>
                  </td>
                  <td>
                    <input type="text" name="estimated_time" id="estimated_time" placeholder="Enter Hours.Minutes" onkeypress="return onlyNumbersWithColon(event);"/>
                  </td>
                </tr>
                <tr>
                  <td>    
                    <label>Task Name</label>
                  </td>
                  <td>
                    <input type="text" name="task_name" id="task_name" placeholder="Enter Task Name" required/>
                  </td>
                  <td>
                    <label>Task Description</label>
                  </td>
                  <td>
                    <textarea name="task_description" id="task_description" placeholder="Enter Task Description"></textarea>
                  </td>
                </tr>
                <tr>
                    <td id="general" style="display:none">
                      <label>Assign to</label>
                    </td>
                    <td style="display:none">
                      <select name="assignTo" id="assignTo"  data-trid='0' placeholder="Assign to"></select>
                    </td>
                    <td id="sw">
                      <label>Developer</label>
                    </td>
                    <td id="sw">
                      <select name="developer" id="developer">
                        <option value="0" data-trid='0'>Select Developer</option>
                      </select>
                    </td>
                    <td id="sw">
                      <label>Migrator</label>
                    </td>
                    <td id="sw">
                      <select name="migrator" id="migrator">
                        <option value="0"  data-trid='0'>Select Migrator</option>
                      </select>
                    </td>
                    <td id="sw">
                      <label>Tester</label>
                    </td>
                    <td id="sw">
                      <select name="tester" id="tester">
                        <option value="0"  data-trid='0'>Select Tester</option>
                      </select>
                    </td>
                  </tr>
              </table>
          <input type="button" name="sumbit_task" id="sumbit_task" align="center" style="margin:5% 45%;" value="Submit" onclick="submitTask();">
        </form>
      </div>     
    </div>
</div>
<script src="../../scripts/team/addTask.js"></script>
<script>
function onlyNumbersWithColon(e) {
  var text = $("#estimated_time").val().split(':');
  if(text[0]<0){
    alert("Please enter time in a proper format. eg. 01:30 for 1 hour and 30 minutes.");
    return;
  }else if(typeof(text[3])!=='undefined'){
    alert("Please enter time in a proper format. eg. 01:30 for 1 hour and 30 minutes.");
    return;
  }
  var charCode;
  if (e.keyCode > 0) {
      charCode = e.which || e.keyCode;
  }
  else if (typeof (e.charCode) != "undefined") {
      charCode = e.which || e.keyCode;
  }
  if (charCode == 58)
      return true
  if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;
  return true;
}
</script>