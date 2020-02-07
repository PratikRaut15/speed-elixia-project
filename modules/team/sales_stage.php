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
    if (isset($_POST["stagename"])) {
        $db = new DatabaseManager();
        $stagename = GetSafeValueString($_POST["stagename"], "string");
        $sql = sprintf("Select * from " . DB_PARENT . ".`sales_stage` where stage_name='%s'", $stagename);
        $db->executeQuery($sql);
        if ($db->get_affectedRows() > 0) {
            $message = "That stagename is already added please add another.";
        } else {
            $sql = sprintf("INSERT INTO " . DB_PARENT . ".`sales_stage` (
    `stage_name` ,
    `timestamp` ,
    `teamid_creator`
    )
    VALUES (
     '%s', '%s', '%d'
    );", $stagename, $today, $teamid);
            $db->executeQuery($sql);
        }
    }

    $db = new DatabaseManager();
    $SQL = sprintf("SELECT ss.stageid,ss.stage_name,ss.timestamp,t.name FROM " . DB_PARENT . ".sales_stage as ss inner join team as t ON ss.teamid_creator = t.teamid where isdeleted=0");
    $db->executeQuery($SQL);
    // $dg = new datagrid($db->getQueryResult());
    $details = array();
    if ($db->get_rowCount() > 0) {
        $x = 1;
        $delete_url = "";
        while ($row = $db->get_nextRow()) {
            $userdetails = new stdClass();
            $stageid = $row["stageid"];
            $userdetails->srno = $x;
            $userdetails->stage_name = $row["stage_name"];
            $userdetails->name = $row["name"];
            $userdetails->timestamp = $row["timestamp"];
            $userdetails->stageid = $row["stageid"];
            $teamid = $_SESSION['sessionteamid'];
            $delete_url = "<a href='javascript:void(0);' alt='Delete Stage ' title='Stage' onclick='deletestage(" . $stageid . ");'><img style='text-align:center; width:30px; height:30px;' src='../../images/delete.png'/></a>";
            $userdetails->delete_url = $delete_url;
            $details[] = $userdetails;
            $x++;
        }
    }
    $dg = new objectdatagrid($details);
    $dg->AddColumn("Stage Name", "stage_name");
    $dg->AddColumn("Created By", "name");
    $dg->AddColumn("Created Time", "timestamp");
    $dg->AddColumn("Delete", "delete_url");
    $dg->AddAction("View/Edit", "../../images/edit.png", "modify_stage.php?stageid=%d");
    $dg->SetNoDataMessage("No Stages Added");
    $dg->AddIdColumn("stageid");
    // $dg->Render();
    include("header.php");
    ?>
    <div class="panel">
        <div class="paneltitle" align="center">Stage Master</div>
        <div class="panelcontents">
            <form method="post" action="sales_stage.php" name="stageform" id="stageform" onsubmit="return ValidateForm();
                        return false;">
                      <?php echo($message); ?>
                <table width="100%">
                    <tr>
                        <td>Stage <span style="color:red;">*</span></td><td><input id="stagename" name = "stagename" type="text"></td>
                    </tr>
                </table>
                <input type="submit" id="submitpros" value="Add New Stage"/>
            </form>
        </div>
    </div>
    <br/>
    <div class="panel">
        <div class="paneltitle" align="center">Stages List</div>
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

    function deletestage(stageid) {
        jQuery.ajax({
            type: "POST",
            url: "user_ajax.php",
            cache: false,
            data: {
                stageid: stageid
                , action: 'deletestage'
            },
            success: function (res) {
                if (res == 'ok') {
                    window.location.reload();
                }
            }
        });
    }


</script>