<?php
include_once("session.php");
include("loginorelse.php");
include_once("../db.php");
include_once("../constants/constants.php");
include_once("../lib/system/DatabaseManager.php");
include_once("../lib/components/gui/datagrid.php");
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
        $sql = sprintf("DELETE FROM `team` WHERE teamid=%d LIMIT 1",$teamid);
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
    $tpassword2 = GetSafeValueString($_POST["tpasswordconf"], "string");
    $tlogin = GetSafeValueString($_POST["tlogin"], "string");
    $role = GetSafeValueString($_POST["role"], "string");    
    $change = GetSafeValueString($_POST["change"], "string");    
    // Change the password if it's set.
switch($change)
{
    case 'name':
    {
        $sql = sprintf("UPDATE `team` SET `name` ='%s' WHERE teamid=%d LIMIT 1",$tname,$teamid);
                $db->executeQuery($sql);            
                header("Location: team.php");
    }
    case 'email':
    {
        $sql = sprintf("UPDATE `team` SET `email` = '%s' WHERE teamid=%d LIMIT 1",$temail,$teamid);
                $db->executeQuery($sql);            
                header("Location: team.php");
     }
    case 'phone':
    {
        $sql = sprintf("UPDATE `team` SET `phone` ='%s' WHERE teamid=%d LIMIT 1",$tphone,$teamid);
                $db->executeQuery($sql);            
                header("Location: team.php");
    }
    case 'role':
    {
        $sql = sprintf("UPDATE `team` SET `role` ='%s' WHERE teamid=%d LIMIT 1",$role,$teamid);
                $db->executeQuery($sql);            
                header("Location: team.php");
    }
}}

$sql = sprintf("Select *
from `team`
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
<form method="post" id="form1" action="modifyteam.php">
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
                <option value ="password">Password</option>
            </select>
        </td>
    </tr>
        <tr>
    <tr>
    <td>Name</td><td><input name = "tname" type="text" value="<?php echo($tname); ?>"></td>
    </tr>
        <tr>
    <td>Phone</td><td><input name = "tphone" type="text" value="<?php echo($tphone); ?>"></td>
    </tr>
    <tr>
    <td>Email</td><td><input name = "temail" type="text" value="<?php echo($temail); ?>"></td>
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
        if($role != "Sourcing")
        {
        ?>        
        <option id="Sourcing" value="Sourcing">Sourcing</option>
        <?php
        }
        if($role != "Sales")
        {
        ?>        
        <option id="Sales" value="Sales">Sales</option>
        <?php
        }
        if($role != "Service")
        {
        ?>        
        <option id="Service" value="Service">Service</option>        
        <?php
        }
        ?>
        </select>
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
<input type="submit" name="save" value="Save Team Member"/>
Action</td><td><input type="radio" name="editaction" value="edit" checked>Edit <input type="radio" name="editaction" value="delete">Delete

</form>
</div>
</div>

<?php 
    include("footer.php");
?>