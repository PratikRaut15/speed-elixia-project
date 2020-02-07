<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
$teamid = GetSafeValueString(isset($_GET["sid"])? $_GET["sid"]:$_POST["sid"], "long");

if (IsHead()) {
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
         header("Location: team.php");
        exit;
}

if(isset($_POST["save"])  )
{
    $tname = GetSafeValueString($_POST["tname"], "string");
    $tphone = GetSafeValueString($_POST["tphone"], "string");
    $temail = GetSafeValueString($_POST["temail"], "string");
    $tpassword = GetSafeValueString($_POST["tpassword"], "string");
    $oldpassword = GetSafeValueString($_POST["oldpassword"], "string");
    $tpassword2 = GetSafeValueString($_POST["tpasswordconf"], "string");
    $tlogin = GetSafeValueString($_POST["tlogin"], "string");
    $role = GetSafeValueString($_POST["role"], "string");    
    $membertype = GetSafeValueString($_POST["membertype"], "string");    
    $change = GetSafeValueString($_POST["change"], "string");    
    
    if($change=="password")
    {
        $password=$tpassword2;
    }else{
        $password=$oldpassword;
    }
    
    // Change the password if it's set.
switch($change)
{
    case 'name':
    {
        $sql = sprintf("UPDATE ".DB_PARENT.".`team` SET `name` ='%s' WHERE teamid=%d LIMIT 1",$tname,$teamid);
                $db->executeQuery($sql);            
                header("Location: team.php");
    }
    case 'email':
    {
        $sql = sprintf("UPDATE ".DB_PARENT.".`team` SET `email` = '%s' WHERE teamid=%d LIMIT 1",$temail,$teamid);
                $db->executeQuery($sql);            
                header("Location: team.php");
     }
    case 'phone':
    {
        $sql = sprintf("UPDATE ".DB_PARENT.".`team` SET `phone` ='%s' WHERE teamid=%d LIMIT 1",$tphone,$teamid);
                $db->executeQuery($sql);            
                header("Location: team.php");
    }
    case 'role':
    {
        $sql = sprintf("UPDATE ".DB_PARENT.".`team` SET `role` ='%s' WHERE teamid=%d LIMIT 1",$role,$teamid);
                $db->executeQuery($sql);            
                header("Location: team.php");
    }
    case 'membertype':
    {
        $sql = sprintf("UPDATE ".DB_PARENT.".`team` SET `member_type` ='%d' WHERE teamid=%d LIMIT 1",$membertype,$teamid);
                $db->executeQuery($sql);            
                header("Location: team.php");
    }
     case 'password':
    {
       $sql = sprintf("UPDATE ".DB_PARENT.".`team` SET `password` ='%s' WHERE teamid=%d LIMIT 1",$password,$teamid);
                $db->executeQuery($sql);            
                header("Location: team.php");
    }
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
        $tlogin = $row["username"];
        $role = $row["role"];
        $member_type = $row["member_type"];
        $oldpassword = $row["password"];
    }
}
else
{
    header("Location: team.php");
    exit;
}

include("header.php");
?>
<script>
    function deleteteam()
    {
        if(confirm("Delete this Team Member?"))
        {
            $("form1").submit();
        }
    }
</script>
<div class="panel">
<div class="paneltitle" align="center">Update Team Member</div>
<div class="panelcontents">
<form method="post" id="form1" action="modifyteam.php" onsubmit="return ValidateForm(); return false;">
<?php echo($message); ?>
<input type="hidden" name = "sid" value="<?php echo($teamid) ?>"/>
<table width="100%">
    <tr>
        <td>Change</td>
        <td><select name ="change">
                <option value ="name">Name</option>
                <option value ="phone">Phone</option>
                <option value ="email">Email</option>
                <option value ="role">Role</option>
                <option value ="membertype">Member Type</option>
                <option value ="password">Password</option>
            </select>
        </td>
    </tr>
        <tr>
    <tr>
    <td>Name</td><td><input name = "tname" id="tname" type="text" value="<?php echo($tname); ?>"></td>
    </tr>
        <tr>
    <td>Phone</td><td><input name = "tphone" id="tphone" type="text" onkeypress="return onlyNos(event,this);" maxlength="12" value="<?php echo($tphone); ?>"></td>
    </tr>
    <tr>
    <td>Email</td><td><input name = "temail" id="temail" type="text" value="<?php echo($temail); ?>" onblur="checkEmail()" ></td>
    </tr>
      <tr>
        <td>Role:</td>
        <td> <select id="role" name="role">
                    <option id="<?php echo($role); ?>"value="<?php echo($role); ?>"><?php echo($role); ?></option>                                
        <?php
        if($role != "Head")
        {
        ?>
        <option id="Head" value="Head">Head</option>                
        <?php
        }
        if($role != "Admin")
        {
        ?>
        <option id="Admin" value="Admin">Admin</option>        
        <?php
        }
        if($role != "Data")
        {
        ?>        
        <option id="Sourcing" value="Data">Data</option>
        <?php
        }
        if($role != "Service")
        {
        ?>        
        <option id="Service" value="Service">Service</option>        
        <?php
        }
        if($role != "Sales")
        {
        ?>        
        <option id="Sales" value="Sales">Sales</option>        
        <?php
        }
        if($role != "CRM")
        {
        ?>        
        <option id="CRM" value="CRM">CRM</option>        
        <?php
        }
        if($role != "Distributor")
        {
        ?>        
        <option id="Distributor" value="Distributor">Distributor</option>        
        <?php
        }
        ?>
        <?php
        if($role != "Repair")
        {
        ?>
        <option id="Repair" value="Repair">Repair</option>        
        <?php
        }
        ?>
        </select>
      </tr> 
      <tr>
          <td>Member Type: </td><td><input type="radio" name="membertype" value="1" <?php if($member_type==1){echo "checked";}?>>Elixir <input type="radio" name="membertype" value="2"<?php if($member_type==2){echo "checked";}?>> Non - Elixir</td></tr>
    <tr>
    <td>Login</td><td><?php echo($tlogin); ?></td>
    </tr>
    <tr>
        <td>Password</td><td><input name = "tpassword" id="tpassword" type="password"><input type="hidden" name="oldpassword" id="oldpassword" value="<?php echo $oldpassword;?>">Populate only if you want to change the password.</td>
    </tr>
    <tr>
    <td>Confirm</td><td><input name = "tpasswordconf" type="password"></td>
    </tr>
</table>
<input type="submit" name="save" value="Save Team Member"/>
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
    var tphone =$("#tphone").val();
    var temail =$("#temail").val();
   
    if(tname==""){
        alert("Please enter name"); 
        return false;
    }else if(tphone==""){
        alert("Please enter contact number");
        return false;
    }else if(temail==""){
        alert("Please enter email id");
        return false;
    }else if($('input[name=membertype]:checked').length<=0)
    {
        alert("Please select member type");
        return false;
    }else{
        $("#form1").submit();
    }

}

 function checkEmail(){	
        var email = $("#temail").val();
        var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
        if(pattern.test(email)){         
	 return true;
         }else{   
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