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
    $modeid = $_GET['modeid'];
    $message = "";
    if($modeid==""){
      header("location:sales_mode.php");  
    }
        $sql1 = sprintf("Select * from " . DB_PARENT . ".`sales_mode` where isdeleted=0 AND  modeid=%d", $modeid);
        $db->executeQuery($sql1);
        if ($db->get_affectedRows() > 0) {
            while ($row = $db->get_nextRow()) {
                $modeidval = $row["modeid"];
                $modeval = $row["mode"];
                $timestamp = $row['timestamp'];
            }
        }
        
        if (isset($_POST["mode"]) && $_POST["mode"]!=""){
            $db = new DatabaseManager();
            $mode1= GetSafeValueString($_POST["mode"], "string");
            $modeid1 = GetSafeValueString($_POST["modeid"], "string");
            $sql = "update " . DB_PARENT . ".`sales_mode` set `mode`='".$mode1."' where modeid=".$modeid1;
            $db->executeQuery($sql);
            header("location:sales_mode.php");
        }
        include("header.php");
        ?>
        <div class="panel">
            <div class="paneltitle" align="center">Mode Update</div>
            <div class="panelcontents">
                <form method="post" action="modify_mode.php?modeid=<?php echo $modeid;?>" name="modifymodeform" id="modifymodeform" onsubmit="return ValidateForm();
                        return false;">
        <?php echo($message); ?>
                    <table width="100%">
                        <tr>
                            <td>Mode <span style="color:red;">*</span></td><td><input id="mode" name = "mode" value="<?php echo $modeval;?>" type="text"></td>
                        </tr>
                    </table>
                    <input type="hidden" id="modeid" name="modeid" value="<?php echo $modeidval; ?>">
                    <input type="submit" name="updatemode" id="updatemode" value="Update Mode"/>
                </form>
            </div>
        </div>
        <br/>
        <?php
    include("footer.php");
}
?>
<script>
    function ValidateForm() {
        var mode = $("#mode").val();
        if (mode == "") {
            alert("Please enter mode");
            return false;
        } else {
            $("#modifymodeform").submit();
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