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
    if (isset($_POST["industryname"])) {
        $db = new DatabaseManager();
        $industrytype = GetSafeValueString($_POST["industryname"], "string");
        $sql = sprintf("Select * from " . DB_PARENT . ".`sales_industry_type` where industry_type='%s'", $industrytype);
        $db->executeQuery($sql);
        if ($db->get_affectedRows() > 0) {
            $message = "That industry type is already added please add another.";
        } else {
            $sql = sprintf("INSERT INTO " . DB_PARENT . ".`sales_industry_type` (
    `industry_type` ,
    `timestamp` ,
    `teamid_creator`
    )
    VALUES (
     '%s', '%s', '%d'
    );", $industrytype, $today, $teamid);
            $db->executeQuery($sql);
        }
    }

    $db = new DatabaseManager();
    $SQL = sprintf("SELECT si.industryid,si.industry_type,si.timestamp,t.name FROM " . DB_PARENT . ".sales_industry_type as si inner join team as t ON si.teamid_creator = t.teamid where si.isdeleted=0");
    $db->executeQuery($SQL);
    //$dg = new datagrid($db->getQueryResult());
    
    $details = array();
    if ($db->get_rowCount() > 0) {
        $x = 1;
        $delete_url = "";
        while ($row = $db->get_nextRow()) {
            $userdetails = new stdClass();
            $industryid = $row["industryid"];
            $userdetails->srno = $x;
            $userdetails->industry_type = $row["industry_type"];
            $userdetails->name = $row["name"];
            $userdetails->timestamp = $row["timestamp"];
            $userdetails->industryid = $row["industryid"];
            $teamid = $_SESSION['sessionteamid'];
            $delete_url = "<a href='javascript:void(0);' alt='Delete Industry type ' title='Industry type' onclick='deleteindustry(" . $industryid . ");'><img style='text-align:center; width:30px; height:30px;' src='../../images/delete.png'/></a>";
            $userdetails->delete_url = $delete_url;
            $details[] = $userdetails;
            $x++;
        }
    }
    $dg = new objectdatagrid($details);
    $dg->AddAction("View/Edit", "../../images/edit.png", "modify_industry.php?industryid=%d");
    $dg->AddColumn("Product Name", "industry_type");
    $dg->AddColumn("Created By", "name");
    $dg->AddColumn("Created Time", "timestamp");
    $dg->AddColumn("Delete", "delete_url");
    $dg->SetNoDataMessage("No industry type Added");
    $dg->AddIdColumn("industryid");
    include("header.php");
    ?>
    <div class="panel">
        <div class="paneltitle" align="center">Industry Type Master</div>
        <div class="panelcontents">
            <form method="post" action="sales_industry.php" name="industryform" id="industryform" onsubmit="return ValidateForm();
                        return false;">
                      <?php echo($message); ?>
                <table width="100%">
                    <tr>
                        <td>Industry Name <span style="color:red;">*</span></td><td><input id="industryname" name = "industryname" type="text"></td>
                    </tr>
                </table>
                <input type="submit" id="submitindustry" name="submitindustry" value="Add New Industry Type"/>
            </form>
        </div>
    </div>
    <br/>
    <div class="panel">
        <div class="paneltitle" align="center">Industry Type List</div>
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
        var industryname = $("#industryname").val();
        if (industryname == "") {
            alert("Please enter industryname");
            return false;
        } else {
            $("#industryname").submit();
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
    
    function deleteindustry(industryid) {
        jQuery.ajax({
            type: "POST",
            url: "user_ajax.php",
            cache: false,
            data: {
                industryid: industryid
                , action: 'deleteindustry'
            },
            success: function (res){
                if (res == 'ok') {
                    window.location.reload();
                }
            }
        });
    }


</script>
