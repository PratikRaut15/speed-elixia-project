<?php
include_once("session.php");
include_once("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/bo/TeamManager.php");
include_once("../../lib/bo/TimeSheetManager.php");
include_once("../../lib/system/Date.php");
date_default_timezone_set("Asia/Calcutta");
$today = date("d-m-Y");
$time= date("h:i");
$created_on=date("Y-m-d H:i:s");
$isHead = checkUserType('',speedConstants::TEAM_ROLE_HEAD);
include("header.php");
$hours = array('00','01','02','03','04','05','06','07','08','09','10');
$minutes = array('00','15','30','45');
?>
<link rel="stylesheet" href="../../css/timesheet.css">
<div class="panel">
  <div class="paneltitle" align="center">Update Timesheet</div> 
    <div class="panelcontents">
      <div class="center">
        <form name="updateTime" id="updateTime">
          <input type="hidden" id="department" name="department" value="<?php echo $_SESSION['department']?>">
          <input type="hidden" name="tsId" id="tsId" value=0 >
        <iframe id="addMemberRole" name = "addMemberRole" src='' style="display:none;"></iframe>
              <table name="addTaskTable" id="addTaskTable">
                <tr>
                  <td>
                    <label>Select Date : </label>
                    <input type="text" name="dateTS" id="dateTS" value="<?php echo date('Y-m-d');?>">
                  </td>
                </tr>
                <tr>
                  <td>    
                    <label>Task Name</label>
                  </td>
                  <td>
                    <input type="text" name="task_name" id="task_name" placeholder="Enter Task Name"  onkeypress="taskAutoComplete();" required/>
                  </td>
                  <input type="hidden" id="assignTr" name="assignTr" value="<?php echo GetLoggedInUserId();?>">
                  <input type="hidden" id="isHead" name="isHead" value="<?php echo $isHead;?>">
                  <input type="hidden" id="assignTo" name="assignTo" value="<?php echo GetLoggedInUserId();?>">
                  <input type="hidden" id="taskId" name="taskId" value="">
                  <input type="hidden" id="tMapId" name="tMapId" value="">

                    <td id='customerLabel'>
                      <label>Customer</label>
                    </td>
                    <td id='customerText'>
                      <input type="text" name="customername" id="customername"  class="searchArea" placeholder="Search Customer" onkeypress="getCustomer();" />
                    </td>
                    <td id="productLabel">
                      <label >Product</label>
                    </td>
                    <td id="productText">
                      <input type="text" name="product" id="product" class="searchArea" placeholder="Search Products">
                    </td>
                  <td>
                    <label>Duration</label>
                  </td>
                  <td>
                    <b>Hours :</b><select id="estHrs">
                      <?php foreach($hours as $k=>$v){
                        echo "<option value='$v'>$v</option>";
                      };
                      ?>
                    </select>
                  </td>
                  <td>
                    <b>Minutes:</b><select id="estMin">
                      <?php foreach($minutes as $k=>$v){
                        echo "<option value='$v'>$v</option>";
                      };
                      ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label id="descLabel">Task Description</label>
                  </td>
                  <td  colspan="2">
                    <textarea name="task_description" id="task_description" placeholder="Enter Task Description"></textarea>
                  </td>
                
              </table>
          <input type="button" name="submit_task" id="submit_task" align="center" style="margin:5% 45%;" value="Submit" onclick="submitTimeSheet();">
        </form>
      </div>     
    </div>
</div>
<?php 
include("timesheetList.php");
if($_SESSION['department']==speedConstants::TEAM_DEPARTMENT_SOFTWARE){
  include("taskList.php");
} 
?>
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
  $("#timesheetList #selectDate").remove();
  $("#updateTime #dateTS").datepicker({
    dateFormat: "yy-mm-dd",
    language: 'en',
    autoclose: 1,
    startDate: Date(),
    maxDate:  0,
  });
  var departmentId = <?php echo $departmentId; ?>;
  var details = [];
  //console.log($("#updateTime #date"));
  var date = $("#updateTime #dateTS").val();
  $("#updateTime #dateTS").on('change',function(){
    refreshTimesheetGrid();
  });
</script>

<script src="../../scripts/team/timesheet.js"></script>