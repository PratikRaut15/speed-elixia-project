<?php
include_once("session.php");
include("loginorelse.php");
include_once("../constants/constants.php");
include_once("../db.php");
include_once("../lib/system/DatabaseManager.php");
include_once("../lib/components/gui/datagrid.php");
include_once("../lib/system/DatabaseManager.php");

if (IsSourcing() || IsHead()) {
	$msg = "<P>You are an authorized user</p>";
} else {
	header( "Location: index.php");
	exit;
}

//Datagtrid - Unapproved Purchases
$db = new DatabaseManager();
$SQL = sprintf("SELECT *, t.name as addedby, d.name as dealername FROM purchase p INNER JOIN team t ON t.teamid = p.teamid INNER JOIN dealer d ON d.dealerid = p.dealerid WHERE sold =0 AND approval = 0");
$db->executeQuery($SQL);

$dg = new datagrid( $db->getQueryResult() );
$dg->AddAction("View/Edit", "../images/edit.png", "modifypurchase.php?pid=%d");
$dg->AddColumn("Model", "model");
$dg->AddColumn("IMEI", "imei");
$dg->AddColumn("Color", "color");
$dg->AddColumn("Dealer", "dealername");
$dg->AddColumn("Date Added (yyyy-mm-dd)", "dateadded");
$dg->AddColumn("Added by", "addedby");
$dg->AddColumn("Select", "group","","grp","checkbox");
$dg->AddCustomFooter( "Select All<input type='checkbox' name='checkall' id='checkall' onclick=\"SetAllCheckBoxes('myform');\"><input type=\"submit\" name=\"groupdelete\" value=\"Delete\">");
$dg->SetNoDataMessage("No Purchases");
$dg->AddIdColumn("purchaseid");

//Datagtrid - Approved Purchases
$db = new DatabaseManager();
$SQL = sprintf("SELECT *, t.name as addedby, d.name as dealername FROM purchase p INNER JOIN team t ON t.teamid = p.teamid INNER JOIN dealer d ON d.dealerid = p.dealerid WHERE sold =0 AND approval = 1");
$db->executeQuery($SQL);

$dg2 = new datagrid( $db->getQueryResult() );
$dg2->AddAction("View/Edit", "../images/edit.png", "modifypurchaseAI.php?pid=%d");
$dg2->AddColumn("Model", "model");
$dg2->AddColumn("IMEI", "imei");
$dg2->AddColumn("Color", "color");
$dg2->AddColumn("Dealer", "dealername");
$dg2->AddColumn("Date Added (yyyy-mm-dd)", "dateadded");
$dg2->AddColumn("Added by", "addedby");
$dg2->SetNoDataMessage("No Purchases");
$dg2->AddIdColumn("purchaseid");


?>
<script>
function SetAllCheckBoxes(doc)
{
    var c = document.getElementsByTagName('input');
    var d = $("checkall").checked;
    if(d==0)
    {
        for (var i = 0; i < c.length; i++)
        {
            if (c[i].type == 'checkbox')
                c[i].checked = 0;
        }
    }
    if(d==1)
    {
        for (var i = 0; i < c.length; i++)
        {
            if (c[i].type == 'checkbox')
                c[i].checked = 1;
        }
    }
}
</script>

<?php
//Datagtrid - Dealers
$db = new DatabaseManager();
$SQL = sprintf("SELECT *,d.name as dealername, t.name as addedby FROM dealer d INNER JOIN team t ON t.teamid = d.teamid");
$db->executeQuery($SQL);

$dg1 = new datagrid( $db->getQueryResult() );
$dg1->AddAction("View/Edit", "../images/edit.png", "modifydealer.php?did=%d");
$dg1->AddColumn("Name", "dealername");
$dg1->AddColumn("Company", "company");
$dg1->AddColumn("Phone", "phone");
$dg1->AddColumn("Email", "email");
$dg1->AddColumn("Date Added (yyyy-mm-dd)", "dateadded");
$dg1->AddColumn("Added by", "addedby");
$dg1->SetNoDataMessage("No Dealers");
$dg1->AddIdColumn("dealerid");


$_scripts[] = "../js/jquery-1.5.1.min.js";
$_scripts[] = "../js/jquery-ui-1.8.13.custom.min.js";
$_scripts[] = "../js/jQueryRotate.2.1.js";

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
<script type="text/javascript">    
    function showcreatepurchase()
    {
        if($("imei").value == "")
        {
            $("submit").disabled = true;
        }
        else
        {
            $("submit").disabled = false;        
        }
    }
    
    function showcreatedealer()
    {
        if($("name").value == "" || $("company").value == "")
        {
            $("submit1").disabled = true;
        }
        else
        {
            $("submit1").disabled = false;        
        }
    }
    
    
</script>    
<div class="panel">
<div class="paneltitle" align="center">
    New Purchase</div>
<div class="panelcontents">
<div id="customerlist">    
<form method="post" action="createpurchase.php" enctype="multipart/form-data">
<table width="100%">
    <tr>
    <td>IMEI*</td><td><input name = "imei" id="imei" type="text" onkeyup="showcreatepurchase();" required></td>
    </tr>
    <tr>
    <td>Model</td>
    <td>
        <select id="model" name="model">
            <option id="SMSGY" value="Samsung Galaxy Y">Samsung Galaxy Y</option>
            <option id="A60" value="Micromax A60">Micromax A60</option>
        </select>                  
    </td>
    </tr>        
    <tr>    
    <td>Dealer</td>
    <td> <select id="dealerid" name="dealerid">
                <?php
                if(isset($dealernames))
                {
                    foreach($dealernames as $thisdealer)
                    {
                ?>
            <option id="<?php echo($thisdealer->id); ?>" value="<?php echo($thisdealer->id); ?>"><?php echo($thisdealer->name); ?></option>
            <?php
                    }
                }
            ?>
        </select>                  
    </td>
    </tr>
    <tr>
    <td>Actual Cost(in Rs) *</td><td><input name = "cost" id="cost" type="text" size="7" maxlength="7" onkeypress='validate(event)' required></td>
    </tr>
    <tr>
    <td>Color</td><td><input name = "color" id="color" type="text"></td>
    </tr>    
    <tr>
    <td>Details</td><td><textarea name="details" id="details"></textarea></td>
    </tr>        
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
<hr/>
<input type="submit" id="submit" name="submit" value="Create new Purchase" disabled/>
</form>
</div>            
</div>
</div>

<br/>

<div class="panel">
<div class="paneltitle" align="center">Not Approved Inventory</div>
<div class="panelcontents">
<form id="deleteform" name="form" method="post" action="deletepurchase.php">                    
<?php $dg->Render(); ?>
</form>
</div>
</div>
<br/>

<div class="panel">
<div class="paneltitle" align="center">Approved Inventory</div>
<div class="panelcontents">
<form id="deleteform" name="form" method="post" action="deletepurchase.php">                    
    <?php $dg2->Render(); ?>
</form>
</div>
</div>
<br/>

<div class="panel">
<div class="paneltitle" align="center">
    New Dealer</div>
<div class="panelcontents">
<div id="customerlist">    
<form method="post" action="createdealer.php" enctype="multipart/form-data">
<table width="100%">
    <tr>
    <td>Name*</td><td><input name = "name" id="name" type="text" onkeyup="showcreatedealer();"></td>
    </tr>
    <tr>
    <td>Company*</td><td><input name = "company" id="company" type="text" onkeyup="showcreatedealer();"></td>
    </tr>
    <tr>
    <td>Phone</td><td><input name = "phone" id="phone" type="text"></td>
    </tr>
    <tr>
    <td>Email</td><td><input name = "email" id="email" type="text"></td>
    </tr>
    <tr>
    <td>Address</td><td><input name = "add1" id="add2" type="text"></td>
    </tr>
    <tr>
    <td></td><td><input name = "add2" id="add2" type="text"></td>
    </tr>
    <tr>
    <td>City</td><td><input name = "city" id="city" type="text"></td>
    </tr>
    <tr>
    <td>State</td><td><input name = "state" id="state" type="text"></td>
    </tr>
    <tr>
    <td>Zip</td><td><input name = "zip" id="zip" type="text"></td>
    </tr>
</table>
    
<hr/>
<input type="submit" id="submit1" name="submit1" value="Create new Dealer" disabled/>
</form>
</div>            
</div>
</div>

<div class="panel">
<div class="paneltitle" align="center">My Dealers</div>
<div class="panelcontents">
<?php $dg1->Render(); ?>
</div>
</div>
<br/>

<?php
include("footer.php");
?>