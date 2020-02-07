<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");

// See if we need to save a new one.
if (IsHead() || IsSales()) {
    $teamid = $_SESSION['sessionteamid'];
    $today = date("Y-m-d H:i:s");
    $message = "";
    if (isset($_POST["mode"])) {
        $db = new DatabaseManager();
        $mode = GetSafeValueString($_POST["mode"], "string");
        $sql = sprintf("Select * from " . DB_PARENT . ".`sales_mode` where mode='%s'", $mode);
        $db->executeQuery($sql);
        if ($db->get_affectedRows() > 0) {
            $message = "That mode is already added please add another.";
        } else {
            $sql = sprintf("INSERT INTO " . DB_PARENT . ".`sales_mode` (
    `mode` ,
    `timestamp` ,
    `teamid_creator`
    )
    VALUES (
     '%s', '%s', '%d'
    );", $mode, $today, $teamid);
            $db->executeQuery($sql);
        }
    }

    $db = new DatabaseManager();
    $SQL = sprintf("SELECT sm.modeid,sm.mode, sm.timestamp,t.name FROM " . DB_PARENT . ".sales_mode as sm inner join team as t ON sm.teamid_creator = t.teamid where sm.isdeleted=0");
    $db->executeQuery($SQL);
    //$dg = new datagrid($db->getQueryResult());
    
    $details = array();
    if ($db->get_rowCount() > 0) {
        $x = 1;
        $delete_url = "";
        while ($row = $db->get_nextRow()) {
            $userdetails = new stdClass();
            $modeid = $row["modeid"];
            $userdetails->srno = $x;
            $userdetails->mode = $row["mode"];
            $userdetails->name = $row["name"];
            $userdetails->timestamp = $row["timestamp"];
            $userdetails->modeid = $row["modeid"];
            $teamid = $_SESSION['sessionteamid'];
            $delete_url = "<a href='javascript:void(0);' alt='Delete Mode ' title='Mode' onclick='deletemode(" . $modeid . ");'><img style='text-align:center; width:30px; height:30px;' src='../../images/delete.png'/></a>";
            $userdetails->delete_url = $delete_url;
            $details[] = $userdetails;
            $x++;
        }
    }
    $dg = new objectdatagrid($details);
    $dg->AddAction("View/Edit", "../../images/edit.png", "modify_mode.php?modeid=%d");
    $dg->AddColumn("Mode", "mode");
    $dg->AddColumn("Created By", "name");
    $dg->AddColumn("Created Time", "timestamp");
    $dg->AddColumn("Delete", "delete_url");
    $dg->SetNoDataMessage("No Mode Added");
    $dg->AddIdColumn("modeid");
    include("header.php");
    ?>
    <div class="panel">
        <div class="paneltitle" align="center">Mode Master</div>
        <div class="panelcontents">
            <form method="post" action="sales_mode.php" name="modeform" id="modeform" onsubmit="return ValidateForm();
                        return false;">
                <?php echo($message); ?>
                <table width="100%">
                    <tr>
                        <td>Mode <span style="color:red;">*</span></td><td><input id="mode" name = "mode" type="text"></td>
                    </tr>
                </table>
                <input type="submit" id="submit" name="modesubmit" value="Add New Mode"/>
            </form>
        </div>
    </div>
    <br/>
    <div class="panel">
        <div class="paneltitle" align="center">Mode List</div>
        <div class="panelcontents">
            <?php $dg->Render(); ?>
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
            $("#modeform").submit();
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
    
    
function deletemode(modeid) {
        jQuery.ajax({
            type: "POST",
            url: "user_ajax.php",
            cache: false,
            data: {
                modeid: modeid
                , action: 'deletemode'
            },
            success: function (res) {
                if (res == 'ok') {
                    window.location.reload();
                }
            }
        });
    }


</script>