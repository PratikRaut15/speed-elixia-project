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
    $stageid = $_GET['stageid'];
    $message = "";
    if($stageid==""){
      header("location:sales_stage.php");  
    }
        $sql1 = sprintf("Select * from " . DB_PARENT . ".`sales_stage` where isdeleted=0 AND  stageid=%d", $stageid);
        $db->executeQuery($sql1);
        if ($db->get_affectedRows() > 0) {
            while ($row = $db->get_nextRow()) {
                $stageidval = $row["stageid"];
                $stagenameval = $row["stage_name"];
                $timestamp = $row['timestamp'];
            }
        }
        
        if (isset($_POST["stagename"]) && $_POST["stagename"]!=""){
            $db = new DatabaseManager();
            $stagename1= GetSafeValueString($_POST["stagename"], "string");
            $stageid1 = GetSafeValueString($_POST["stageid"], "string");
            $sql = "update " . DB_PARENT . ".`sales_stage` set `stage_name`='".$stagename1."' where stageid=".$stageid1;
            $db->executeQuery($sql);
            header("location:sales_stage.php");
        }
        include("header.php");
        ?>
        <div class="panel">
            <div class="paneltitle" align="center">Update Stage</div>
            <div class="panelcontents">
                <form method="post" action="modify_stage.php?stageid=<?php echo $stageid;?>" name="modifystageform" id="modifystageform" onsubmit="return ValidateForm();
                        return false;">
        <?php echo($message); ?>
                    <table width="100%">
                        <tr>
                            <td>Stage <span style="color:red;">*</span></td><td><input id="stagename" name = "stagename" value="<?php echo $stagenameval;?>" type="text"></td>
                        </tr>
                    </table>
                    <input type="hidden" id="stageid" name="stageid" value="<?php echo $stageidval; ?>">
                    <input type="submit" id="submitpros" value="Update Stage"/>
                </form>
            </div>
        </div>
        <br/>
        <?php
    //} else {
        //header("location:sales_stage.php");
    //}
    include("footer.php");
}
?>
<script>
    function ValidateForm() {
        var stagename = $("#stagename").val();

        if (stagename == "") {
            alert("Please enter stagename");
            return false;
        } else {
            $("#stageform").submit();
        }
    }

    function checkEmail() {
        var email = $("#temail").val();
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


</script>