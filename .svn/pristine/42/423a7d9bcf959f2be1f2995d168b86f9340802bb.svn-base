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
    if (isset($_POST["sourcename"])) {
        $db = new DatabaseManager();
        $sourcename = GetSafeValueString($_POST["sourcename"], "string");
        $sql = sprintf("Select * from " . DB_PARENT . ".`sales_source` where source_name='%s'", $sourcename);
        $db->executeQuery($sql);
        if ($db->get_affectedRows() > 0) {
            $message = "That source is already added please add another.";
        } else {
            $sql = sprintf("INSERT INTO " . DB_PARENT . ".`sales_source` (
    `source_name` ,
    `timestamp` ,
    `teamid_creator`
    )
    VALUES (
     '%s', '%s', '%d'
    );", $sourcename, $today, $teamid);
            $db->executeQuery($sql);
        }
    }

    $db = new DatabaseManager();
    $SQL = sprintf("SELECT ss.sourceid,ss.source_name,ss.timestamp,t.name FROM " . DB_PARENT . ".sales_source as ss inner join team as t ON ss.teamid_creator = t.teamid where isdeleted=0");
    $db->executeQuery($SQL);
    //$dg = new datagrid($db->getQueryResult());

    $details = array();
    if ($db->get_rowCount() > 0) {
        $x = 1;
        $delete_url = "";
        while ($row = $db->get_nextRow()) {
            $userdetails = new stdClass();
            $sourceid = $row["sourceid"];
            $userdetails->srno = $x;
            $userdetails->source_name = $row["source_name"];
            $userdetails->name = $row["name"];
            $userdetails->timestamp = $row["timestamp"];
            $userdetails->sourceid = $row["sourceid"];
            $teamid = $_SESSION['sessionteamid'];
            $delete_url = "<a href='javascript:void(0);' alt='Delete Stage ' title='Stage' onclick='deletesource(" . $sourceid . ");'><img style='text-align:center; width:30px; height:30px;' src='../../images/delete.png'/></a>";
            $userdetails->delete_url = $delete_url;
            $details[] = $userdetails;
            $x++;
        }
    }
    $dg = new objectdatagrid($details);
    $dg->AddAction("View/Edit", "../../images/edit.png", "modify_source.php?sourceid=%d");
    $dg->AddColumn("Source Name", "source_name");
    $dg->AddColumn("Created By", "name");
    $dg->AddColumn("Created Time", "timestamp");
    $dg->AddColumn("Delete", "delete_url");
    $dg->SetNoDataMessage("No Source Added");
    $dg->AddIdColumn("sourceid");
    include("header.php");
    ?>
    <div class="panel">
        <div class="paneltitle" align="center">Source Master</div>
        <div class="panelcontents">
            <form method="post" action="sales_source.php" name="sourceform" id="sourceform" onsubmit="return ValidateForm();
                        return false;">
                      <?php echo($message); ?>
                <table width="100%">
                    <tr>
                        <td>Source <span style="color:red;">*</span></td><td><input id="sourcename" name = "sourcename" type="text"></td>
                    </tr>
                </table>
                <input type="submit" id="submit" name="sourcesubmit" value="Add New Source"/>
            </form>
        </div>
    </div>
    <br/>
    <div class="panel">
        <div class="paneltitle" align="center">Source List</div>
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
        var sourcename = $("#sourcename").val();
        if (sourcename == "") {
            alert("Please enter sourcename");
            return false;
        } else {
            $("#sourceform").submit();
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

    function deletesource(sourceid) {
        jQuery.ajax({
            type: "POST",
            url: "user_ajax.php",
            cache: false,
            data: {
                sourceid: sourceid
                , action: 'deletesource'
            },
            success: function (res) {
                if (res == 'ok') {
                    window.location.reload();
                }
            }
        });
    }


</script>