<?php
error_reporting(E_ALL);
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Sanitise.php");

class UserView{
    
}
$db = new DatabaseManager();

$customerno = $_GET['cid'];
$split =array();
//-------------------------------to Add user----------------------------------------------
if(isset($_POST['addusr']))
{
    $custno =  GetSafeValueString($_POST['cust'],"string");
    $rname = GetSafeValueString($_POST['realname'],"string");
    $uname= GetSafeValueString($_POST['username'],"string");
    $pwd= GetSafeValueString($_POST['pass'],"string");
    $email= GetSafeValueString($_POST['emailid'],"string");
    $phone= GetSafeValueString($_POST['phone'],"string");
    $exp = GetSafeValueString($_POST['role'],"string");
    $split = explode(",",$exp);
    $role= $split[0];
    $roleid= $split[1];
    $delete= GetSafeValueString($_POST['del'],"string");
    $new_pwd=  sha1($pwd);
    $userkey1 = mt_rand();
    
    $SQL ="INSERT INTO user(customerno,realname,username,password,email,phone,role,roleid,userkey,isdeleted) 
           VALUES('$custno','$rname','$uname','$new_pwd','$email','$phone','$role','$roleid','$userkey1','$delete')";
    $db->executeQuery($SQL);
    
    header("Location:user_view.php?cid=$custno");
    //exit;
}
//---------------------------------------------------To display list of users--------------------------------
$SQL1 ="SELECT user.userid,user.realname,user.username,user.role,user.email,user.phone,user.isdeleted,customer.customercompany FROM user 
       INNER JOIN ".DB_PARENT.".customer ON customer.customerno=user.customerno
       WHERE user.customerno = $customerno ";
$db->executeQuery($SQL1);
$x=0;
if($db->get_rowCount()>0)
    {
        while($row=$db->get_nextRow())
        {
            $x++;
            $User = new UserView();
            $User->userid= $row['userid'];
            $User->realname= $row['realname'];
            $User->username= $row['username'];
            $User->role= $row['role'];
            $User->email= $row['email'];
            $User->phone= $row['phone'];
            $User->customercompany= $row['customercompany'];
            $User->deleted = $row['isdeleted'];
            $User->x = $x;
            if($row['isdeleted']==0)
            {
                $User->status ="Unlocked";
                $Display[]=$User;
            }
            else
            {
                $User->status ="Locked";
                $Report[]=$User;
            }
            
        }
    }
    $dg = new objectdatagrid($Display);
    $dg->AddColumn("Sr.No", "x");
    $dg->AddColumn("Name","realname");
    $dg->AddColumn("Username","username");
    $dg->AddColumn("Email","email");
    $dg->AddColumn("Phone","phone");
    $dg->AddColumn("Role","role");
    $dg->AddColumn("Status","status");
    $dg->AddRightAction("Click to Lock/Unlock","../../images/lock.png","user_edit.php?uid=%d&user=lock");
    $dg->AddRightAction("Edit User","../../images/edit_user.png","user_edit.php?uid=%d&user=edit");
    $dg->SetNoDataMessage("No User");
    $dg->AddIdColumn("userid");
    
    $df = new objectdatagrid($Report);
    $df->AddColumn("Sr.No", "x");
    $df->AddColumn("Name","realname");
    $df->AddColumn("Username","username");
    $df->AddColumn("Email","email");
    $df->AddColumn("Phone","phone");
    $df->AddColumn("Role","role");
    $df->AddColumn("Status","status");
    $df->AddRightAction("Click to Lock/Unlock","../../images/lock.png","user_edit.php?uid=%d&user=lock");
    $df->AddRightAction("Edit User","../../images/edit_user.png","user_edit.php?uid=%d&user=edit");
    $df->SetNoDataMessage("No User");
    $df->AddIdColumn("userid");
    
    $_scripts[] = "../../scripts/trash/prototype.js";
    include("header.php");
?>
<!--------------------------------------ADD User Panel---------------------------------------------------------------->
<div class="panel">
    <div class="paneltitle" align="center">Add User For <?php echo $User->customercompany?></div>
    <div class="panelcontents">
    </div>
    <form method="post" name="edituser" id="edituser" onsubmit="return ValidateForm(); return false;">
    <table width="100%" align="center">
        <input type="hidden" name="cust" id="cust" value="<?php echo $customerno;?>">
        <tr>
            <td>Name</td>
            <td>
                <input type="text" name="realname" id="realtime" value="<?php echo $realname;?>">
            </td>
        </tr>
        <tr>
            <td>Username</td>
            <td>
                <input type="text" name="username" id="username" value="<?php echo $username;?>" size="30" readonly>
            </td>
        </tr>
        <tr>
            <td>Password</td>
            <td>
                <input type="password" name="pass" id="pass" value="" >
            </td>
        </tr>
        <tr>
            <td>Email</td>
            <td>
                <input type="email" name="emailid" id="emailid" value="<?php echo $email;?>" onkeyup="setUsername();" size="30" required>
            </td>
        </tr>
        <tr>
            <td>Phone</td>
            <td>
                <input type="" name="phone" id="phone" value="<?php echo $phone;?>" size="10">
            </td>
        </tr>
        <tr>
            <td>Role</td>
            <td>
                <select id="role" name="role">
                <option id="Administrator" value="Administrator,5"<?php if($role=="Administrator"){ echo "selected";}?>>Administrator</option>
                <option id="Tracker" value="Tracker,7" <?php if($role=="Tracker"){ echo "selected";}?>>Tracker</option>
                <option id="Viewer" value="Viewer,9"<?php if($role=="Viewer"){ echo "selected";}?>>Viewer</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>User Status</td>
            <td>
                <input type="radio" name="del" id="del" value="0" <?php if($deleted==0) echo "checked";?>>Unlock
                <input type="radio" name="del" id="del" value="1" <?php if($deleted==1) echo "checked";?>>Lock
            </td>
        </tr>
        <tr>
            <td>
            <input type="submit" name="addusr" id="addusr" class="btn btn-default" value="Add User">
            </td>
        </tr>
        <br>
    </table>
        </form>
    </div>

<br>
<hr>
<!--<a href="customers.php"><input type="button" class="btn btn-primary" value="Back"></a>-->
<!-------------------------------------list of Users Panel------------------------------------------->
<div class="panel">
    <div class="paneltitle" align="center">Unlock Users of  <?php echo $User->customercompany?></div>
    <div class="panelcontents">
    
    <?php $dg->Render(); ?>
    </div>
</div>
<div class="panel">
    <div class="paneltitle" align="center">Lock Users of  <?php echo $User->customercompany?></div>
    <div class="panelcontents">
    
    <?php $df->Render(); ?>
   

<?php
include("footer.php");
?>
<script>
    function setUsername()
    {
        var email=jQuery("#emailid").val();
        jQuery("#username").val(email);
    }
</script>