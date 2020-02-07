<?php
include_once("session.php");
include("loginorelse.php");
include_once("../db.php");
include_once("../constants/constants.php");
include_once("../lib/system/DatabaseManager.php");
include_once("../lib/components/gui/datagrid.php");


// See if we need to save a new one.
$message="";
if(isset($_POST["tname"]) && isset($_POST["tlogin"]) && isset($_POST["tpassword"]))
{
    $db = new DatabaseManager();
    
    $tname = GetSafeValueString($_POST["tname"], "string");
    $tphone = GetSafeValueString($_POST["tphone"], "string");
    $temail = GetSafeValueString($_POST["temail"], "string");
    $tpassword = GetSafeValueString($_POST["tpassword"], "string");
    $tlogin = GetSafeValueString($_POST["tlogin"], "string");
    $role = GetSafeValueString($_POST["role"], "string");    
    
    $sql = sprintf("Select * from `team` where username='%s'",$tlogin);
    $db->executeQuery($sql);
    if($db->get_affectedRows() >0 )
    {
        $message="That Username is already taken, please choose another.";
    }
    else
    {
    
    $sql = sprintf("INSERT INTO `team` (
    `name` ,
    `phone` ,
    `email` ,
    `role` ,    
    `username` ,
    `password`
    )
    VALUES (
     '%s', '%s', '%s', '%s', '%s', '%s'
    );",$tname,$tphone,$temail,$role,$tlogin,$tpassword);
        $db->executeQuery($sql);
    }
}

include_once("../lib/system/DatabaseManager.php");

$db = new DatabaseManager();
$SQL = sprintf("SELECT * FROM team");
$db->executeQuery($SQL);

$dg = new datagrid( $db->getQueryResult() );
if(IsHead())
{
    $dg->AddAction("View/Edit", "../images/edit.png", "modifyteam.php?sid=%d");
}
$dg->AddColumn("Name", "name");
$dg->AddColumn("Phone", "phone");
$dg->AddColumn("Email", "email");
$dg->AddColumn("Role", "role");
$dg->SetNoDataMessage("No Team Member");
$dg->AddIdColumn("teamid");

include("header.php");
if(IsHead())
{
?>
<div class="panel">
<div class="paneltitle" align="center">New Team Member</div>
<div class="panelcontents">
<form method="post" action="team.php">
<?php echo($message); ?>
<table width="100%">
    <tr>
    <td>Name</td><td><input id="tname" name = "tname" type="text"></td>
    </tr>
        <tr>
    <td>Phone</td><td><input name = "tphone" type="text"></td>
    </tr>
    <tr>
    <td>Email</td><td><input name = "temail" type="text"></td>
    </tr>
      <tr>
        <td>Role:</td>
        <td> <select id="role" name="role">
        <option id="Head" value="Head">Head</option>                
        <option id="Admin" value="Admin">Admin</option>        
        <option id="Data" value="Data">Data</option>
        <option id="Sales" value="Sales">Sales</option>
        <option id="Service" value="Service">Service</option>        
        </select>
      </tr>        
    <tr>
    <td>Login</td><td><input name = "tlogin" type="text"></td>
    </tr>
    <tr>
    <td>Password</td><td><input name = "tpassword" type="password"></td>
    </tr>
</table>
<input type="submit" id="submitpros" value="Save New Team Member"/>
</form>
</div>
</div>
<br/>
<?php
}
?>
<div class="panel">
<div class="paneltitle" align="center">The Team</div>
<div class="panelcontents">
<?php $dg->Render(); ?>
</div>
</div>
<br/>
<?php
include("footer.php");
?>