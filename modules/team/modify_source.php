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
    $sourceid = $_GET['sourceid'];
    $message = "";
    if($sourceid==""){
      header("location:sales_source.php");  
    }
        $sql1 = sprintf("Select * from " . DB_PARENT . ".`sales_source` where isdeleted=0 AND  sourceid=%d", $sourceid);
        $db->executeQuery($sql1);
        if ($db->get_affectedRows() > 0) {
            while ($row = $db->get_nextRow()) {
                $sourceidval = $row["sourceid"];
                $sourcenameval = $row["source_name"];
                $timestamp = $row['timestamp'];
            }
        }
        
        if (isset($_POST["sourcename"]) && $_POST["sourcename"]!=""){
            $db = new DatabaseManager();
            $sourcename1= GetSafeValueString($_POST["sourcename"], "string");
            $sourceid1 = GetSafeValueString($_POST["sourceid"], "string");
            $sql = "update " . DB_PARENT . ".`sales_source` set `source_name`='".$sourcename1."' where sourceid=".$sourceid1;
            $db->executeQuery($sql);
            header("location:sales_source.php");
        }
        include("header.php");
        ?>
        <div class="panel">
            <div class="paneltitle" align="center">Source Update</div>
            <div class="panelcontents">
                <form method="post" action="modify_source.php?sourceid=<?php echo $sourceid;?>" name="modifysourceform" id="modifysourceform" onsubmit="return ValidateForm();
                        return false;">
        <?php echo($message); ?>
                    <table width="100%">
                        <tr>
                            <td>Source <span style="color:red;">*</span></td><td><input id="sourcename" name = "sourcename" value="<?php echo $sourcenameval;?>" type="text"></td>
                        </tr>
                    </table>
                    <input type="hidden" id="sourceid" name="sourceid" value="<?php echo $sourceidval; ?>">
                    <input type="submit" name="updatesource" id="submitsourceupdate" value="Update Source"/>
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
        var sourcename = $("#sourcename").val();
        if (sourcename == "") {
            alert("Please enter sourcename");
            return false;
        } else {
            $("#modifysourceform").submit();
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