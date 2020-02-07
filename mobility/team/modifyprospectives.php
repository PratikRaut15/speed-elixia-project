<?php
include_once("session.php");
include_once("../lib/system/utilities.php");
include("loginorelse.php");
include_once("../constants/constants.php");
include_once("../db.php");
include_once("../lib/system/DatabaseManager.php");
include_once("../lib/system/Date.php");
include_once("../lib/system/Sanitise.php");

$prospectiveid = GetSafeValueString( isset($_GET["pid"])?$_GET["pid"]:$_POST["prospectiveid"], "long");
$db = new DatabaseManager();

if (IsSales() || IsHead()) {
	$msg = "<P>You are an authorized user</p>";
} else {
	header( "Location: index.php");
	exit;
}

if(isset($_POST["save"]))
{

    $prosname = GetSafeValueString($_POST["pname"], "string");
    $proscompany = GetSafeValueString($_POST["pcompany"], "string");
    $prosphone = GetSafeValueString($_POST["pphone"], "string");
    $prosemail = GetSafeValueString($_POST["pemail"], "string");
    $prossector = GetSafeValueString($_POST["psector"], "string");
    $prostarget = GetSafeValueString($_POST["ptarget"], "string");
    $prosstatus = GetSafeValueString($_POST["pstatus"], "string");
    $prosnextstep = GetSafeValueString($_POST["pnextstep"], "string");
    $proscomment = GetSafeValueString($_POST["pcomment"], "string");

    $prossalesdone = 0;
    if(isset($_POST["psalesdone"]) && $_POST["psalesdone"] == "on")
    {
        $prossalesdone = 1;
    }
    
    $SQL = sprintf( "UPDATE prospectives SET
            `name`='%s',
            `company`='%s',
            `phone`='%s',
            `email`='%s',
            `sector`='%s',
            `target`='%s',
            `status`='%s',
            `nextstep`='%s',
            `comment`='%s',
            `sold`='%d'
            WHERE prospectid = %d" ,
                $prosname,
                $proscompany,
                $prosphone,
                $prosemail,
                $prossector,
                $prostarget,
                $prosstatus,
                $prosnextstep,
                $proscomment,
                $prossalesdone,
                $prospectiveid);

    $db->executeQuery($SQL);

    header("Location: customers.php");
}

$SQL = sprintf("SELECT p.* from prospectives p 
where p.prospectid = '%d' LIMIT 1 ",$prospectiveid);
$db->executeQuery($SQL);
while($row = $db->get_nextRow())
{
    $prosname = $row["name"];
    $proscompany = $row["company"];
    $prosphone = $row["phone"];
    $prosemail = $row["email"];
    $prossector = $row["sector"];
    $prostarget = $row["target"];
    $prosstatus = $row["status"];
    $prosnextstep = $row["nextstep"];
    $prossalesdone = $row["sold"];
    $proscomment = $row["comment"];
}

include("header.php");
?>

<div class="panel">
<div class="paneltitle" align="center">Update Prospective Customer</div>
<div class="panelcontents">

<p>Please adjust any inaccurate details. Understand that changing these values will have a very real and immediate impact on the database.</p>
<form method="post" action="modifyprospectives.php"  enctype="multipart/form-data">
<input type="hidden" name="prospectiveid" value="<?php echo( $prospectiveid ); ?>"/>
<table width="100%">
    <tr>
    <td>Name</td><td><input name="pname" type="text" value="<?php echo($prosname); ?>" /> </td>
    </tr>
    <tr>
    <td>Company</td><td><input name="pcompany" type="text"  value="<?php echo($proscompany); ?>" /></td>
    </tr>
    <tr>
    <td>Phone</td><td><input name="pphone" type="text"  value="<?php echo($prosphone); ?>" /></td>
    </tr>
    <tr>
    <td>Email</td><td><input name="pemail" type="text"  value="<?php echo($prosemail); ?>" /></td>
    </tr>
    <tr>
    <td>Sector</td><td><input name="psector" type="text"  value="<?php echo($prossector); ?>" /></td>
    </tr>
    <tr>
    <td>Target</td><td><input name="ptarget" type="text"  value="<?php echo($prostarget); ?>" /></td>
    </tr>
    <tr>
    <td>Status</td><td><input name="pstatus" type="text"  value="<?php echo($prosstatus); ?>" /></td>
    </tr>
    <tr>
    <td>Next Step</td><td><input name="pnextstep" type="text"  value="<?php echo($prosnextstep); ?>" /></td>
    </tr>
    <tr>
    <td>Sales Done?</td>
    <td>
        <input type="checkbox" name="psalesdone" id="psalesdone" <?php if($prossalesdone == 1) echo("checked"); ?>>
    </td>        
    </tr>
    <tr>
    <td>Comment</td><td><textarea name="pcomment" id="pcomment" cols="32" rows="3"><?php echo($proscomment); ?></textarea>
        </td>
    </tr>        
    </table>
</div>
<br/>
<input type="submit" name="save" value="Save Changes" />
</form>
</div>

<?php
include("footer.php");
?>