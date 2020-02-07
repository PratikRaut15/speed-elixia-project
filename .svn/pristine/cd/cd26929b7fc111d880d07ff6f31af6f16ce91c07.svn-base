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
$tm = new TimesheetManager();
$result = $tm->fetchTeam($_SESSION['department'],GetLoggedInUserId());
$trId = 0;
if(count($result)>0){
  $trId = $result[0]['trId'];
} 
//print_r();
?>
<html>
  <div class="panel">
  <div class="paneltitle" align="center">Update Timesheet</div> 
    <div class="panelcontents">
      <div class="center">
        <form action="import_timesheet.php" method="post" enctype="multipart/form-data">
          <input type="hidden" id="dept" name="dept" value="<?php echo $_SESSION['department']?>">
          <input type="hidden" id="teamId" name="teamId" value="<?php echo GetLoggedInUserId();?>">
          <input type="hidden" id="trId" name="trId" value="<?php echo $trId?>">
          <input type="file" name="fileToUpload" id="fileToUpload">
          <input type="submit" value="Upload Timesheet" name="submit">
        </form>
     </div>
   </div>
  <script>
    if($("#trId").val()=='0'){
      alert("Please assign a role to this user before uploading timesheet.");
      window.location.href="addTeamRole.php";
    }
  </script>
</html>