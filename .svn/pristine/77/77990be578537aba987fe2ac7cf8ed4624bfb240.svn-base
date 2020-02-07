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
    $contactid = $_GET['contactid'];
    $message = "";
    if($contactid==""){
      header("location:sales_pipeline.php");  
    }
    $sql1 = sprintf("Select * from " . DB_PARENT . ".`sales_contact` where isdeleted=0 AND contactid=%d", $contactid);
    $db->executeQuery($sql1);
    if ($db->get_affectedRows() > 0) {
        while ($row = $db->get_nextRow()) {
            $contactidval = $row["contactid"];
            $designationval = $row["designation"];
            $nameval = $row["name"];
            $phoneval = $row["phone"];
            $emailval = $row["email"];
            $timestamp = $row['timestamp'];
            $pipelineid = $row['pipelineid'];            
        }
    }

    if (isset($_POST['userdetails'])) {
        if ($_POST['username'] != "" && $_POST['userphone'] != "" && $_POST['useremail']) {
            $db = new DatabaseManager();
            $username1 = $_POST['username'];
            $userdesignation1 = $_POST['userdesignation'];
            $userphone1 = $_POST['userphone'];
            $useremail1 = $_POST['useremail'];
            $contactid1 = $_POST['contactid'];
            $sql = "update " . DB_PARENT . ".`sales_contact` set `name`='" . $username1 . "',`designation`='" . $userdesignation1 . "',phone='" . $userphone1 . "', email='" . $useremail1 . "' where contactid=".$contactid1;
            $db->executeQuery($sql);
            $message = "data updated sucessfully.";
            header("location:modify_pipeline.php?pipelineid=".$pipelineid);                          
        }
    }
    include("header.php");
    ?>
    <div class="panel">
        <div class="paneltitle" align="center">Update User</div>
        <div class="panelcontents">
            <form method="post" action="modify_pipelineuser.php?contactid=<?php echo $contactid; ?>" name="modifyuserform" id="modifyuserform" onsubmit="return ValidateForm();
                    return false;">
                  <?php echo($message); ?>
                <table>
                    <tr><td>Name </td><td><input type="text" name="username" id="username" value="<?php echo $nameval; ?>"></td>
                    <td>Designation </td><td><input type="text" name="userdesignation" id="userdesignation" value="<?php echo $designationval; ?>"></td>
                    <td>Phone </td><td><input type="text" name="userphone" id="userphone" onkeyup="onlyNos();" value="<?php echo $phoneval; ?>"></td>
                    <td>Email </td><td><input type="text" name="useremail" id="useremail" onblur="checkEmail();" value="<?php echo $emailval; ?>"></td>
                    <input type="hidden" id="contactid" name="contactid" value="<?php echo $contactidval; ?>">
                    <td><input type="submit" value="Update User" id="userdetails" style="background-color: green; color: white;" name="userdetails"></td>
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
    
    


</script>