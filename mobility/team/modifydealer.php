<?php
include_once("session.php");
include("loginorelse.php");
include_once("../db.php");
include_once("../constants/constants.php");
include_once("../lib/system/DatabaseManager.php");
include_once("../lib/components/gui/datagrid.php");
$dealerid = GetSafeValueString(isset($_GET["did"])? $_GET["did"]:$_POST["did"], "long");

$db = new DatabaseManager();

if (IsSourcing() || IsHead()) {
	$msg = "<P>You are an authorized user</p>";
} else {
	header( "Location: index.php");
	exit;
}

if(isset($_POST["save"])  )
{
    $name = GetSafeValueString($_POST["name"], "string");
    $company = GetSafeValueString($_POST["company"], "string");    
    $phone = GetSafeValueString($_POST["phone"], "string");
    $email = GetSafeValueString($_POST["email"], "string");
    $add1 = GetSafeValueString($_POST["add1"], "string");
    $add2 = GetSafeValueString($_POST["add2"], "string");
    $city = GetSafeValueString($_POST["city"], "string");
    $state = GetSafeValueString($_POST["state"], "string");    
    $zip = GetSafeValueString($_POST["zip"], "string");    

    $sql = sprintf("UPDATE `dealer`
                    SET `name` = '%s' ,
                    `company` ='%s',
                    `phone` ='%s',
                    `email` ='%s',                            
                    `add1` ='%s',                            
                    `add2` ='%s',                            
                    `city` ='%s',                            
                    `state` ='%s',
                     `zip` ='%s'                                               
                    WHERE dealerid=%d LIMIT 1",$name,$company,$phone,$email,$add1,$add2,$city, $state, $zip,$dealerid);
    $db->executeQuery($sql);            
    header("Location: purchase.php");
    exit;             
}

$sql = sprintf("Select *
from `dealer`
where dealerid='%d'",$dealerid);
$db->executeQuery($sql);

if($db->get_rowCount()>0)
{
    while($row = $db->get_nextRow())
    {
        $name = $row["name"];
        $company = $row["company"];        
        $phone = $row["phone"];
        $email = $row["email"];
        $add1 = $row["add1"];
        $add2 = $row["add2"];
        $city = $row["city"];
        $state = $row["state"];
        $zip = $row["zip"];
    }
}
else
{
    header("Location: purchase.php");
    exit;
}

include("header.php");
?>

<script>
    function showcreatedealer()
    {
        if($("name").value == "" || $("company").value == "")
        {
            $("save").disabled = true;
        }
        else
        {
            $("save").disabled = false;        
        }
    }    
</script>    
<div class="panel">
<div class="paneltitle" align="center">Update Dealer</div>
<div class="panelcontents">
<form method="post" id="form1" action="modifydealer.php">
<input type="hidden" name = "did" value="<?php echo($dealerid) ?>"/>
<table width="100%">
    <tr>
    <td>Name*</td><td><input name = "name" id="name" type="text" onkeyup="showcreatedealer();" value="<?php echo($name); ?>"></td>
    </tr>
    <tr>
    <td>Company*</td><td><input name = "company" id="company" type="text" onkeyup="showcreatedealer();" value="<?php echo($company); ?>"></td>
    </tr>
    <tr>
    <td>Phone</td><td><input name = "phone" id="phone" type="text" value="<?php echo($phone); ?>"></td>
    </tr>
    <tr>
    <td>Email</td><td><input name = "email" id="email" type="text" value="<?php echo($email); ?>"></td>
    </tr>
    <tr>
    <td>Address</td><td><input name = "add1" id="add2" type="text" value="<?php echo($add1); ?>"></td>
    </tr>
    <tr>
    <td></td><td><input name = "add2" id="add2" type="text" value="<?php echo($add2); ?>"></td>
    </tr>
    <tr>
    <td>City</td><td><input name = "city" id="city" type="text" value="<?php echo($city); ?>"></td>
    </tr>
    <tr>
    <td>State</td><td><input name = "state" id="state" type="text" value="<?php echo($state); ?>"></td>
    </tr>
    <tr>
    <td>Zip</td><td><input name = "zip" id="zip" type="text" value="<?php echo($zip); ?>"></td>
    </tr>        
</table>
<input type="submit" name="save" id="save" value="Save Dealer" disabled/>
</form>
</div>
</div>

<?php
include("footer.php");
?>