<?php
include_once("session.php");
include("loginorelse.php");
include_once("../db.php");
include_once("../constants/constants.php");
include_once("../lib/system/DatabaseManager.php");
include_once("../lib/components/gui/datagrid.php");
$purchaseid = GetSafeValueString(isset($_GET["pid"])? $_GET["pid"]:$_POST["pid"], "long");

$db = new DatabaseManager();

if (IsSourcing() || IsHead()) {
	$msg = "<P>You are an authorized user</p>";
} else {
	header( "Location: index.php");
	exit;
}

if(isset($_POST["save"])  )
{
    $approve = 0;
    if(isset($_POST["approve"]) && $_POST["approve"] == "on")
    {
        $approve = 1;
    }
    
    $sql = sprintf("UPDATE `purchase`
                    SET `approval` ='%d'                    
                    WHERE purchaseid=%d LIMIT 1",$approve,$purchaseid);
    $db->executeQuery($sql);            
    header("Location: purchase.php");
    exit;             
}

$sql = sprintf("Select *, d.name AS dealername
from `purchase` p INNER JOIN dealer d ON d.dealerid = p.dealerid
where p.purchaseid='%d' and approval = 1",$purchaseid);
$db->executeQuery($sql);

if($db->get_rowCount()>0)
{
    while($row = $db->get_nextRow())
    {
        $imei = $row["imei"];
        $model = $row["model"];        
        $dealerid = $row["dealerid"];
        $dealername = $row["dealername"];
        $cost = $row["cost"];        
        $color = $row["color"];        
        $details = $row["details"]; 
        $approve = $row["approval"];
    }
}
else
{
    header("Location: purchase.php");
    exit;
}

class dealer{
    // Empty Class
}

$dealernames = Array();
$SQL = sprintf("SELECT name, dealerid from dealer");
$db->executeQuery($SQL);
while($row = $db->get_nextRow())
{
    $dealer = new dealer();
    $dealer->name = $row["name"];
    $dealer->id = $row["dealerid"];    
    $dealernames[]=$dealer;
}

include("header.php");
?>

<script>
    function showcreatepurchase()
    {
        if($("imei").value == "")
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
<div class="paneltitle" align="center">Update Purchase</div>
<div class="panelcontents">
<form method="post" id="form1" action="modifypurchaseAI.php">
<input type="hidden" name = "pid" value="<?php echo($purchaseid) ?>"/>
<table width="100%">
    <tr>
    <td>IMEI</td><td><input name = "imei" id="imei" type="text" onkeyup="showcreatepurchase();" value="<?php echo($imei); ?>" disabled></td>
    </tr>
    <tr>
    <td>Model</td>
    <td>
        <select id="model" name="model" disabled>
            <option value="<?php echo($model); ?>"><?php echo($model); ?></option>            
            <?php
            if($model != "Samsung Galaxy Y")
            {
            ?>
                <option id="SMSGY" value="Samsung Galaxy Y">Samsung Galaxy Y</option>
            <?php
            }
            if($model != "Micromax A60")
            {
            ?>
                <option id="A60" value="Micromax A60">Micromax A60</option>
            <?php
            }
            ?>
        </select>                  
    </td>
    </tr>        
    <tr>    
    <td>Dealer</td>
    <td> <select id="dealerid" name="dealerid" disabled>
            <option id="<?php echo($dealerid); ?>" value="<?php echo($dealerid); ?>"><?php echo($dealername); ?></option>            
                <?php
                if(isset($dealernames))
                {
                    foreach($dealernames as $thisdealer)
                    {
                        if($thisdealer->name != $dealername)
                        {
                ?>
            <option id="<?php echo($thisdealer->id); ?>" value="<?php echo($thisdealer->id); ?>"><?php echo($thisdealer->name); ?></option>
            <?php
                        }
                    }
                }
            ?>
        </select>                  
    </td>
    </tr>
    <tr>
    <td>Actual Cost (in Rs)</td><td><input name = "cost" id="cost" type="text" size="7" maxlength="7" onkeypress='validate(event)' value="<?php echo($cost); ?>" disabled></td>
    </tr>        
    <tr>
    <td>Color</td><td><input name = "color" id="color" type="text" value="<?php echo($color); ?>"></td>
    </tr>            
    <tr>
        <td>Details</td>
        <td><textarea name="details" id="details"><?php echo($details); ?></textarea></td>
    </tr>
    <?php
    if(IsHead())
    {
    ?>
    <tr>
    <td>Approve?</td><td><input name = "approve" id="approve" checked type="checkbox"></td>
    </tr>                
    <?php
    }
    ?>
</table>
<script> 
function validate(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46) ){
    theEvent.returnValue = false;
    if (theEvent.preventDefault) theEvent.preventDefault();
  }
}
</script>    
<input type="submit" name="save" id="save" value="Save Purchase"/>
</form>
</div>
</div>

<?php
include("footer.php");
?>