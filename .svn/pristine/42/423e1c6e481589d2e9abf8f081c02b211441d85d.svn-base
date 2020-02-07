<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");

// See if we need to save a new one.
if (IsHead() || IsSales()) {
    $db = new DatabaseManager();
    $teamid = $_SESSION['sessionteamid'];
    $today = date("Y-m-d H:i:s");
    $reminderid = $_GET['reminderid'];
    $message = "";
    if($reminderid==""){
      header("location:sales_pipeline.php");  
    }
    $sql1 = sprintf("Select * from " . DB_PARENT . ".`sales_reminder` as sr where sr.isdeleted=0 AND sr.reminderid=%d", $reminderid);
    $db->executeQuery($sql1);
    if ($db->get_affectedRows() > 0) {
        while ($row = $db->get_nextRow()) {
            $reminderidval = $row["reminderid"];
            $reminder_datetimeval = $row["reminder_datetime"];
            $contentval = $row["content"];
            $timestamp = $row['timestamp'];
            $pipelineid = $row["pipelineid"];
        }
    }

    if (isset($_POST['reminderdetails'])){
        if($_POST['reminderdatetime'] != "" && $_POST['content'] != ""){
            $db = new DatabaseManager();
                $stime = GetSafeValueString($_POST['STime'], "string");
                $reminderdate = GetSafeValueString($_POST['reminderdatetime'], "string");
                $rdate = $reminderdate . " " . $stime . ":00";
                $rdatetime = date('Y-m-d H:i:s', strtotime($rdate));
            
            
            $content1 = GetSafeValueString($_POST['content'], "string");
            $reminderid1 = $_POST['reminderid'];
            $sql = "update " . DB_PARENT . ".`sales_reminder` set `reminder_datetime`='" . $rdatetime . "', `content`='" . $content1 . "' where `reminderid` =".$reminderid1;
            $db->executeQuery($sql);
            $message = "data updated sucessfully.";
          header("location:modify_pipeline.php?pipelineid=".$pipelineid);              
        }
    }
    include("header.php");
    ?>
    <div class="panel">
            <div class="paneltitle" align="center">Update Reminders</div>
            <div class="panelcontents">
                <form name="updatereminderform" id="updatereminderform" method="post" action="modify_reminder.php?reminderid=<?php echo $_REQUEST['reminderid']; ?>" onsubmit="return ValidateFormReminder();
                        return false;">
                    <table>
                        <?php
                        $reminder_date = date('d-m-Y',strtotime($reminder_datetimeval));
                        $reminder_time = date('H:i',strtotime($reminder_datetimeval));
                        ?>
                        <tr><td>Reminder Date & Time</td><td><input type="text" name="reminderdatetime" id="reminderdatetime" value="<?php echo $reminder_date; ?>">
                                <input id="STime" name="STime" type="text" class="input-mini" value="<?php echo $reminder_time;?>"/>
                            </td>
                        <td>Content </td><td><textarea name="content" id="content"><?php echo $contentval; ?></textarea></td>
                            <td><input type="hidden" id="reminderid" name="reminderid" value="<?php echo $_REQUEST['reminderid']; ?>"></td>
                            <td><input type="submit" value="Update Reminder" id="reminderdetails" style="background-color: green; color: white;" name="reminderdetails"></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    <br/>
    <?php
    include("footer.php");
}
?>
<script>
    
     function ValidateFormReminder() {
                var reminderdatetime = $("#reminderdatetime").val();
                var content = $("#content").val();
                if (reminderdatetime != "" && content != "" && contact != "") {
                    $("#updatereminderform").submit();
                } else {
                    alert("Please check all fields");
                    return false;
                }
            }
    
    function ValidateForm() {
        var username = $("#username").val();
        var userdesignation = $("#userdesignation").val();
        var userphone = $("#userphone").val();
        var useremail = $("#useremail").val();
        if (username == "") {
            alert("Please enter username");
            return false;
        } else if (userphone == "") {
            alert("Please enter phoneno");
            return false;
        } else if (useremail == "") {
            alert("Please enter emailaddress");
            return false;
        } else {
            $("#modifyuserform").submit();
        }
    }
    function checkEmail() {
        var email = $("#useremail").val();
        var pattern = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
        if (pattern.test(email)) {
            return true;
        } else {
            alert("Enter valid email id");
            return false;
        }
    }
    function onlyNos(e, t) {
        try {
            if (window.event) {
                var charCode = window.event.keyCode;
            }
            else if (e) {
                var charCode = e.which;
            }
            else {
                return true;
            }
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
        catch (err) {
            alert(err.Description);
        }
    }
       $(document).ready(function () {
             
                Calendar.setup(
                        {
                            inputField: "reminderdatetime", // ID of the input field
                            ifFormat: "%d-%m-%Y", // the date format
                            button: "trigger" // ID of the button
                        });
                $('#STime').timepicker({'timeFormat': 'H:i'});

            });
    


</script>