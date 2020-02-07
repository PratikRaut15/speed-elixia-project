<?php

include_once("../../lib/system/utilities.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Date.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/model/VODevices.php");
include_once("../user/new_alerts_func.php");

$_scripts[] = "../../scripts/jquery.min.js";
$_scripts[] = "../../scripts/team/modifycustomer.js";
$_scripts_custom[] = "../../scripts/autocomplete/jquery-ui.min.js";
$_stylesheets[] = "../../scripts/autocomplete/jquery-ui.min.css";

include("header.php");

class users {
    
}

class testing {

}

$db = new DatabaseManager();
$userid = $_GET['userid'];
$SQL = sprintf("SELECT * FROM " . DB_PARENT . ".user where userid=".$userid);
$db->executeQuery($SQL);
$userdetails = Array();
if ($db->get_rowCount() > 0) {
    while($row = $db->get_nextRow()){
        $user = new testing();
        $user->userid = $row["userid"];
        $user->realname = $row["realname"];
        $user->username = $row["username"];
        $user->password = $row["password"];
        $user->role = $row["role"];
        $user->roleid = $row["roleid"];
        $user->customerno = $row["customerno"];
        $user->email = $row["email"];
        $user->phone = $row["phone"];
        $user->userkey = $row["userkey"];
        $userdetails[] = $user;
    }
}

$realname =isset($userdetails[0]->realname)?$userdetails[0]->realname:"";
$username =isset($userdetails[0]->username)?$userdetails[0]->username:"";
$email =isset($userdetails[0]->email)?$userdetails[0]->email:"";
//$password =isset($userdetails[0]->password)?$userdetails[0]->password:"";
$phoneno =isset($userdetails[0]->phone)?$userdetails[0]->phone:"";

if(isset($_POST['edituser'])){
    $name = isset($_POST['name'])?$_POST['name']:"";
    $username = isset($_POST['username'])?$_POST['username']:"";
    $email = isset($_POST['email'])?$_POST['email']:"";
    $password = isset($_POST['password'])?$_POST['password']:"";
    $phoneno = isset($_POST['phoneno'])?$_POST['phoneno']:"";
    $role = isset($_POST['role'])?$_POST['role']:"Administrator";
    $roleid = isset($_POST['roleid'])?$_POST['roleid']:5;
    $groupid = 0;
   // $db = new DatabaseManager();
    //if($name!="" && $username!="" && $email!="" && $password!= ""){
    $customerno = $_POST['customerno'];
    //$modified_by = getelixirid($customerno);
        $date = new Date();
        $today = $date->MySQLNow();
        $SQLU = sprintf("update user SET realname='".$name."', username='".$username."', email='".$email."',phone='".$phoneno."', password='sha1(".$password.")' where userid = ".$_GET['userid']."");
        $db->executeQuery($SQLU);
        $user->userid = $db->get_insertedId();
    header("Location: edituser.php?userid=$userid");
}

?>

<div class="panel">
    <div class="paneltitle" align="center">Edit User</div>
    <div class="panelcontents">
        <form method="post" name="edituserform" id="edituserform" onsubmit="return validform_updateuser(); return false;">
            <div style=" width:100%;">
                <table border="0">
                    <tr>
                        <td width="50%"><b>Name <span style="color: red;">*</span></b> </td> <td  width="50%"><input type="text" name="name" id="name" placeholder="Name" value="<?php echo $realname;?>" ></td>
                    </tr>
                    <tr>
                        <td width="50%"><b>User name <span style="color: red;">*</span></b></td> <td  width="50%"><input type="text" name="username" id="username" placeholder="Username" value="<?php echo $username; ?>" ></td>
                    </tr>
                    <tr>
                        <td width="50%"><b>Email <span style="color: red;">*</span></b> </td> <td  width="50%"><input type="text" name="email" id="email" placeholder="Email id" value="<?php echo $email; ?>"></td>
                    </tr>
                    <tr>
                        <td width="50%"><b>Password <span style="color: red;">*</span></b></td> <td  width="50%"><input type="password" name="password" id="password" ></td>
                    </tr>
                    <tr>
                        <td width="50%"><b>Phone No</b></td> <td  width="50%"> <b>+91</b><input type="text" name="phoneno" id="phoneno" placeholder="9870288657" value="<?php echo $phoneno; ?>"></td>
                    </tr>
                    <tr>
                        <td width="50%"><b>Role</b></td> 
                        <td width="50%">
                            <select name="role" id="role">
                                <option id="Administrator" rel="5" value="Administrator">Administrator</option>
                            </select>
                            <input type="hidden" name="roleid" id="roleid" value="5">
                            <input type="hidden" name="customerno" id="customerno" value="<?php echo $customerno;?>">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="100%"><input style="text-align: center;" type="submit" name="edituser" id="edituser" value="Edit User"></td> 
                    </tr>
                </table>
            </div>
        </form>
    </div>
</div>
<?php
include("footer.php");
?>


