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
    $industryid = $_GET['industryid'];
    $message = "";
    if($industryid==""){
      header("location:sales_industry.php");  
    }
        $sql1 = sprintf("Select * from " . DB_PARENT . ".`sales_industry_type` where isdeleted=0 AND  industryid=%d", $industryid);
        $db->executeQuery($sql1);
        if ($db->get_affectedRows() > 0) {
            while ($row = $db->get_nextRow()) {
                $industryidval = $row["industryid"];
                $industrynameval = $row["industry_type"];
                $timestamp = $row['timestamp'];
            }
        }
        
        if (isset($_POST["industryname"]) && $_POST["industryname"]!=""){
            $db = new DatabaseManager();
            $industryname1= GetSafeValueString($_POST["industryname"], "string");
            $industryid1 = GetSafeValueString($_POST["industryid"], "string");
            $sql = "update " . DB_PARENT . ".`sales_industry_type` set `industry_type`='".$industryname1."' where industryid=".$industryid1;
            $db->executeQuery($sql);
            header("location:sales_industry.php");
        }
        include("header.php");
        ?>
        <div class="panel">
            <div class="paneltitle" align="center">Industry Type Update</div>
            <div class="panelcontents">
                <form method="post" action="modify_industry.php?industryid=<?php echo $industryid;?>" name="modifyindustryform" id="modifyindustryform" onsubmit="return ValidateForm();
                        return false;">
        <?php echo($message); ?>
                    <table width="100%">
                        <tr>
                            <td>Industry Type Name <span style="color:red;">*</span></td><td><input id="industryname" name = "industryname" value="<?php echo $industrynameval;?>" type="text"></td>
                        </tr>
                    </table>
                    <input type="hidden" id="industryid" name="industryid" value="<?php echo $industryidval; ?>">
                    <input type="submit" name="updateindustrytype" id="updateindustrytype" value="Update Industry Type"/>
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
        var industryname = $("#industryname").val();
        if (industryname == "") {
            alert("Please enter industryname");
            return false;
        } else {
            $("#modifyindustryform").submit();
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