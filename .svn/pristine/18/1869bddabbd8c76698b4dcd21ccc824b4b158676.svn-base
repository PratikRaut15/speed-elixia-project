<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
$teamid = GetSafeValueString(isset($_GET["sid"])? $_GET["sid"]:$_POST["sid"], "long");

if (IsDistributor()) {
	$msg = "<P>You are an authorized user</p>";
} else {
	header( "Location: index.php");
	exit;
}

// See if we need to save a new one.
$message="";
$db = new DatabaseManager();

if(isset($_POST["editaction"]) && $_POST["editaction"]=="delete")
{
    // Delete, OK..
        $sql = sprintf("DELETE FROM ".DB_PARENT.".`team` WHERE teamid=%d LIMIT 1",$teamid);
        $db->executeQuery($sql);
         header("Location: dealers.php");
        exit;
}

if(isset($_POST["save"]))
{
    $tname = GetSafeValueString($_POST["tname"], "string");
    $tphone = GetSafeValueString($_POST["tphone"], "string");
    $temail = GetSafeValueString($_POST["temail"], "string");
    $taddr = GetSafeValueString($_POST["taddr"], "string");
    $tpassword = GetSafeValueString($_POST["tpassword"], "string");
    $tpassword2 = GetSafeValueString($_POST["tpasswordconf"], "string");
    $tlogin = GetSafeValueString($_POST["tlogin"], "string");
    $role="Dealer";
    $change = GetSafeValueString($_POST["change"], "string");    
    // Change the password if it's set.
    if(!empty($tname)&&!empty($tphone)&&!empty($temail))
    {     
        switch($change)
        {
            case 'name':
            {
                $sql = sprintf("UPDATE ".DB_PARENT.".`team` SET `name` ='%s' WHERE teamid=%d LIMIT 1",$tname,$teamid);
                        $db->executeQuery($sql);            
                        header("Location: dealers.php");
            }
            case 'email':
            {
                $sql = sprintf("UPDATE ".DB_PARENT.".`team` SET `email` = '%s' WHERE teamid=%d LIMIT 1",$temail,$teamid);
                        $db->executeQuery($sql);            
                        header("Location: dealers.php");
             }
              case 'address':
            {
                $sql = sprintf("UPDATE ".DB_PARENT.".`team` SET `address` = '%s' WHERE teamid=%d LIMIT 1",$taddr,$teamid);
                        $db->executeQuery($sql);            
                        header("Location: dealers.php");
             }
            case 'phone':
            {
                $sql = sprintf("UPDATE ".DB_PARENT.".`team` SET `phone` ='%s' WHERE teamid=%d LIMIT 1",$tphone,$teamid);
                        $db->executeQuery($sql);            
                        header("Location: dealers.php");
            }
            case 'password':
            {
                if(!empty($tpassword2))
                {
                 $sql = sprintf("UPDATE ".DB_PARENT.".`team` SET `password` ='%s' WHERE teamid=%d LIMIT 1",$tpassword2,$teamid);
                        $db->executeQuery($sql);            
                        header("Location: dealers.php");
                }
                else
                {
                        header("Location: dealers.php");
                }
            }
        }
    }else{
        $message="Please fill mandatory fields";
    }
}
$sql = sprintf("Select *
from ".DB_PARENT.".`team`
where teamid='%d'",$teamid);
$db->executeQuery($sql);

if($db->get_rowCount()>0)
{
    while($row = $db->get_nextRow())
    {
        $tname = $row["name"];
        $tphone = $row["phone"];
        $temail = $row["email"];
        $taddr = $row["address"];
        $tlogin = $row["username"];
        $role = $row["role"];        
    }
}
else
{
    header("Location: dealers.php");
    exit;
}

include("header.php");
?>
<script>
    function deleteteam()
    {
        if(confirm("Delete this Dealer?"))
        {
            $("form1").submit();
        }
    }
</script>
<div class="panel">
<div class="paneltitle" align="center">Update Dealer </div>
<div class="panelcontents">
<form method="post" id="form1" onsubmit="return ValidateForm(); return false;" action="modifydealer.php">
<?php
            if(!empty($message)){
                echo"<span style='color:red; font-size:10px;'>".$message."</span>";
            }
?>
<input type="hidden" name = "sid" value="<?php echo($teamid) ?>"/>
<table width="100%">
    <tr>
        <td>Change</td>
        <td><select name ="change">
                <option value ="name">Name</option>
                <option value ="phone">Phone</option>
                <option value ="email">Email</option>
                <option value ="address">Address</option>
                <option value ="password">Password</option>
            </select>
        </td>
    </tr>
        <tr>
    <tr>
    <td>Name <span style="color:red;">*</span></td><td><input name = "tname" id="tname" type="text" value="<?php echo($tname); ?>"></td>
    </tr>
        <tr>
            <td>Phone <span style="color:red;">*</span></td><td><input name = "tphone" id="tphone" type="text" maxlength="10" onkeypress="return onlyNos(event,this);" value="<?php echo($tphone); ?>"></td>
    </tr>
    <tr>
    <td>Email <span style="color:red;">*</span></td><td><input name = "temail" id="temail" type="text" value="<?php echo($temail); ?>"></td>
    </tr>
    <tr>
        <td>Address</td>
        <td><textarea name="taddr" style="width:150px;"><?php echo($taddr);?></textarea></td>
    </tr>
    <tr>
    <td>Login</td><td><?php echo($tlogin); ?></td>
    </tr>
    <tr>
    <td>Password</td><td><input name = "tpassword" type="password">Populate only if you want to change the password.</td>
    </tr>
    <tr>
    <td>Confirm</td><td><input name = "tpasswordconf" type="password"></td>
    </tr>
</table>
<input type="submit" name="save" value="Save Dealer"/>
Action</td><td><input type="radio" name="editaction" value="edit" checked>Edit <input type="radio" name="editaction" value="delete">Delete

</form>
</div>
</div>

<?php 
    include("footer.php");
?>
<script>
    
    function ValidateForm(){
        var tname = $("#tname").val();
        var emailid = $("#temail").val();
        var tphone =$("#tphone").val();
        if(tname==""){
            alert("Please enter name");
            return false;
        }else if(emailid==""){
            alert("Please enter email id");
            return false;
        }else if(emailid!=""){
        var errmsg = checkEmail(emailid);
            if(errmsg==2){
              alert("Enter valid email id");
              return false;
            }
            if(errmsg==1){
              return true;
            }
        return false;
        }else if(tphone==""){
            alert("Please enter contact no.");
            return false;
        }else{
            $("#form1").submit();
        }
    }
    
    function checkEmail(email){	
        var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
        var msg ;
        if(pattern.test(email)){         
	 msg=1;
         }else{   
            msg=2;
        }
        return msg;
    }
    
    function onlyNos(e,t){
        try {
                if (window.event) {
                    var charCode = window.event.keyCode;
                }
                else if (e){
                    var charCode = e.which;
                }
                else { return true; }
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
