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
$tm = new TimeSheetManager();
$isHead = checkUserType('',speedConstants::TEAM_ROLE_HEAD);
$teamId = GetLoggedInUserId();
$departmentId = $_SESSION["department"];
if(isset($_GET['tsId'])){
  $tsId = $_GET['tsId'];
  $taskDetails = $tm->getTimesheetDetails($tsId);
  //print_r($taskDetails);
  $time = explode(':',$taskDetails['time']);
  if($taskDetails){
  }else{
    echo "You are not authorized to use this page.";
    header("Location:timesheetList.php");
  }
}else{
  header("Location:timesheetList.php");
}

$hours = array('00','01','02','03','04','05','06','07','08','09','10');
$minutes = array('00','15','30','45');
include("header.php");
?>
<link rel="stylesheet" href="../../css/timesheet.css">
<div class="panel">
  <div class="paneltitle" align="center">Edit Timesheet</div> 
    <div class="panelcontents">
      <div class="center">
        <iframe id="addMemberRole" name = "addMemberRole" src='' style="display:none;"></iframe>
              <table name="addTaskTable" id="addTaskTable">
                <tr>
                  <td>
                    <label>Select Date : </label>
                    <input type="text" name="dateTS" id="dateTS" value="<?php echo $taskDetails['date']?>">
                  </td>
                </tr>
                <tr>
        <form name="updateTime" id="updateTime">
          <input type="hidden" id="department" name="department" value="<?php echo $_SESSION['department']?>">
          <input type="hidden" id="tsId" name="tsId" value="<?php echo $_GET['tsId']?>">
                  <td>    
                    <label>Task Name</label>
                  </td>
                  <td>
                    <input type="text" name="task_name" id="task_name" placeholder="Enter Task Name" required value="<?php echo $taskDetails['taskName']?>"/>
                  </td>
                  <input type="hidden" id="assignTo" name="assignTo" value="<?php echo GetLoggedInUserId();?>">
                  <input type="hidden" id="isHead" name="isHead" value="<?php echo $isHead;?>">
                  <input type="hidden" id="assignTr" name="assignTr" value="<?php echo $taskDetails['trId'];?>">
                  <input type="hidden" id="teamId" name="teamId" value="<?php echo GetLoggedInUserId();?>">
                  <input type="hidden" id="taskId" name="taskId" value="<?php echo $taskDetails['taskId'];?>">
                  <input type="hidden" id="tMapId" name="tMapId" value="<?php echo $taskDetails['tMapId'];?>">
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
                        if($v==$time[0]){
                          echo "<option value='$v' selected>$v</option>";
                        }else{

                          echo "<option value='$v'>$v</option>";
                        }
                      };
                      ?>
                    </select>
                  </td>
                  <td>
                    <b>Minutes:</b><select id="estMin">
                      <?php foreach($minutes as $k=>$v){
                        if($v==$time[1]){
                          echo "<option value='$v' selected>$v</option>";
                        }else{

                          echo "<option value='$v'>$v</option>";
                        }
                      };
                      ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label id="descLabel">Task Description</label>
                  </td>
                  <td>
                    <textarea name="task_description" id="task_description" placeholder="Enter Task Description"><?php echo $taskDetails['taskDesc']?></textarea>
                  </td>
                
              </table>
          <input type="button" name="edit_task" id="edit_task" align="center" style="margin:5% 45%;" value="Submit" onclick="submitTimeSheet();">
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
  var customerList=',';
  var productList=',';
  $("#timesheetList #selectDate").remove();
  $("#updateTime #dateTS").datepicker({
    dateFormat: "yy-mm-dd",
    language: 'en',
    autoclose: 1,
    startDate: Date(),
    maxDate : 0
  });
  var departmentId = <?php echo $departmentId; ?>;
  var details = [];
  var date = $("#updateTime #date").val();
  $("#updateTime #date").on('change',function(){
    refreshTimesheetGrid();
  });
  var tsId = <?php echo $taskDetails['tsId']?>;
   prodList = <?php echo json_encode(explode(',',$taskDetails['productIds']));?>;
   productList = ','+'<?php echo $taskDetails['productIds']?>';
  var productNames = <?php echo json_encode(explode(',',$taskDetails['products']));?>;
  custList = <?php echo json_encode(explode(',',$taskDetails['customerIds']));?>;
//console.log(custList);
   customerList =','+ <?php echo json_encode($taskDetails['customerIds']);?>;  
   //console.log(customerList);
  var customerNames = <?php echo json_encode(explode(',',$taskDetails['customers']));?>;
  $.each(prodList,function(k,v){
    $("#product").after("<div id=P"+v+" class='productContainer' onclick='removeProduct("+v+")'>"+productNames[k]+"<span class='removeCustomer' onclick='removeProduct("+v+")'>&times</span></div>");
  });
  $.each(custList,function(k,v){
      $("#customername").after("<div id=C"+v+" class='customerContainer' onclick='removeCustomer("+v+")'>"+v+" - "+customerNames[k]+"<span class='removeCustomer' onclick='removeCustomer("+v+")'>&times</span></div>");
  });
function removeCustomer(customerno){
  $("#customername").val('');
  $("#C"+customerno).filter(".customerContainer").remove();
  customerList = customerList.replace(','+customerno+'','');
}

function removeProduct(prodId){
  $("#product").val('');
  $("#P"+prodId).filter(".productContainer").remove();
  productList = productList.replace(','+prodId+'','');
}
var edit_timesheet = 1;
</script>

<script src="../../scripts/team/timesheet.js"></script>