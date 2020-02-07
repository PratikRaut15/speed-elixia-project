<?php
include_once("session.php");
include("loginorelse.php");
include_once("../constants/constants.php");
include_once("../db.php");
include_once("../lib/system/DatabaseManager.php");
include_once("../lib/components/gui/datagrid.php");
include_once("../lib/system/DatabaseManager.php");

//Datagtrid - Unresolved
$db = new DatabaseManager();
if(IsSales())
{
    $SQL = sprintf("SELECT *, h.email AS custemail, h.phone AS custphone, t.name as addedby, h.name as hname FROM helpdesk h INNER JOIN team t ON t.teamid = h.teamid WHERE h.resolved =0 AND h.allottedto='Sales'");
}
if(IsSourcing())
{
    $SQL = sprintf("SELECT *, h.email AS custemail, h.phone AS custphone, t.name as addedby, h.name as hname FROM helpdesk h INNER JOIN team t ON t.teamid = h.teamid WHERE h.resolved =0 AND h.allottedto='Sourcing'");
}
elseif(IsService())
{
    $SQL = sprintf("SELECT *, h.email AS custemail, h.phone AS custphone, t.name as addedby, h.name as hname FROM helpdesk h INNER JOIN team t ON t.teamid = h.teamid WHERE h.resolved =0 AND h.allottedto='Service'");    
}
elseif(IsAdmin() || IsHead())
{
    $SQL = sprintf("SELECT * , h.email AS custemail, h.phone AS custphone, t.name AS addedby, h.name AS hname FROM helpdesk h INNER JOIN team t ON t.teamid = h.teamid WHERE h.resolved =0");    
}
$db->executeQuery($SQL);

$dg = new datagrid( $db->getQueryResult() );
$dg->AddAction("View/Edit", "../images/edit.png", "modifyticket.php?tid=%d");
$dg->AddColumn("ID", "helpdeskid");
$dg->AddColumn("Type", "type");
$dg->AddColumn("Name", "hname");
$dg->AddColumn("Company", "company");
$dg->AddColumn("Phone", "custphone");
$dg->AddColumn("Email", "custemail");
$dg->AddColumn("Date Added (yyyy-mm-dd)", "dateadded");
$dg->AddColumn("Contacted Via", "contactvia");
$dg->AddColumn("Added by", "addedby");
$dg->AddColumn("Allotted to", "allottedto");
$dg->SetNoDataMessage("No Tickets");
$dg->AddIdColumn("helpdeskid");

//Datagtrid - Resolved
$db = new DatabaseManager();
if(IsSales())
{
    $SQL = sprintf("SELECT *, t.name as addedby, h.name as hname FROM helpdesk h INNER JOIN team t ON t.teamid = h.teamid WHERE h.resolved =1 AND h.allottedto='Sales'");
}
if(IsSourcing())
{
    $SQL = sprintf("SELECT *, h.email AS custemail, h.phone AS custphone, t.name as addedby, h.name as hname FROM helpdesk h INNER JOIN team t ON t.teamid = h.teamid WHERE h.resolved =1 AND h.allottedto='Sourcing'");
}
elseif(IsService())
{
    $SQL = sprintf("SELECT *, t.name as addedby, h.name as hname FROM helpdesk h INNER JOIN team t ON t.teamid = h.teamid WHERE h.resolved =1 AND h.allottedto='Service'");    
}
elseif(IsAdmin() || IsHead())
{
    $SQL = sprintf("SELECT *, t.name as addedby, h.name as hname FROM helpdesk h INNER JOIN team t ON t.teamid = h.teamid WHERE h.resolved =1");    
}
    
$db->executeQuery($SQL);

$dg1 = new datagrid( $db->getQueryResult() );
$dg1->AddAction("View/Edit", "../images/edit.png", "readticket.php?rid=%d");
$dg1->AddColumn("ID", "helpdeskid");
$dg1->AddColumn("Type", "type");
$dg1->AddColumn("Name", "hname");
$dg1->AddColumn("Company", "company");
$dg1->AddColumn("Phone", "custphone");
$dg1->AddColumn("Email", "custemail");
$dg1->AddColumn("Date Added (yyyy-mm-dd)", "dateadded");
$dg1->AddColumn("Contacted Via", "contactvia");
$dg1->AddColumn("Added by", "addedby");
$dg1->AddColumn("Allotted to", "allottedto");
$dg1->AddColumn("Resolved by", "resolvedby");
$dg1->SetNoDataMessage("No Tickets");
$dg1->AddIdColumn("helpdeskid");


$_scripts[] = "../js/jquery-1.5.1.min.js";
$_scripts[] = "../js/jquery-ui-1.8.13.custom.min.js";
$_scripts[] = "../js/jQueryRotate.2.1.js";

$teamnames = Array();
$SQL = sprintf("SELECT name from team");
$db->executeQuery($SQL);
while($row = $db->get_nextRow())
{
    $teamnames[]=$row["name"];
}

include("header.php");

?>
<script type="text/javascript">    
    function showcreate()
    {
        if($("name").value == "" || $("company").value == "")
        {
            $("submit").disabled = true;
        }
        else
        {
            $("submit").disabled = false;        
        }
    }
    
    function ShowCustNo()
    {
        if($("type").value =="Current")
        {
            $("custno").show();
        }
        else
        {
            $("custno").hide();
        }
    }
    
    function populatedetails()
    {
        if($("customerno").value > 0)
        {            
            var params = "customerno=" + encodeURIComponent($("customerno").value);
            new Ajax.Request('pullcustomerrecordAjax.php',
            {
                parameters: params,
                onSuccess: function(transport)
                {
                    var statuscheck = transport.responseText;
                    if(statuscheck == "notok")
                    {
                        $("name").value="";
                        $("company").value="";
                        $("phone").value="";
                        $("email").value="";                                               
                        alert("No Customer Record Found!!");                        
                    }
                    else
                    {
                        var cdata = transport.responseText.evalJSON();                        
                        $("name").value=cdata.name;
                        $("company").value=cdata.company;
                        $("phone").value=cdata.phone;
                        $("email").value=cdata.email;    
                        showcreate();
                    }
                },
                onComplete: function()
                {
                }
            });                    
        }
        else
        {
            $("name").value="";
            $("company").value="";
            $("phone").value="";
            $("email").value="";                                                       
            alert("No Customer Record Found!!");
        }
    }
</script>    
<div class="panel">
<div class="paneltitle" align="center">
    New Ticket</div>
<div class="panelcontents">
<div id="customerlist">    
<form method="post" action="createticket.php" enctype="multipart/form-data">
<table width="100%">
    <tr><td><b>Customer Type</b></td>
        <td>
        <select id="type" name="type" onChange="ShowCustNo();">
                        <option id="Current" value="Current">Current</option>
                        <option id="Prospective" value="Prospective">Prospective</option>
                        </select>    
            </td>
    </tr>    
    <tr>
    <td></td><td><div id="custno"> Customer No.<input name = "customerno" id="customerno" type="text" size="4" maxlength="4" onblur="populatedetails();"></div></td>
    </tr>
    <tr>
    <td>Name*</td><td><input name = "name" id="name" type="text" onkeyup="showcreate();"></td>
    </tr>
    <tr>
    <td>Company*</td><td><input name = "company" id="company" type="text" onkeyup="showcreate();"></td>
    </tr>    
    <tr>
    <td>Phone</td><td><input name = "phone" id="phone" type="text"></td>
    </tr>
    <tr>
    <td>Email</td><td><input name = "email" id="email" type="text"></td>
    </tr>
    <tr>
    <td>Contacted Via</td>
    <td>
        <select id="contactedvia" name="contactedvia">
            <option id="Call" value="Call">Call</option>
            <option id="Email" value="Email">Email</option>
            <option id="IM" value="IM">Instant Messaging</option>
            <option id="PV" value="PV">Personal Visit</option>
        </select>                  
    </td>
    </tr>    
    <tr>
    <td>Reason for Contact</td><td><textarea name="reason" id="reason" cols="32" rows="3"></textarea>
        </td>
    </tr>        
    <tr>
    <td>Allotted to</td>
    <td>
        <select id="allottedto" name="allottedto">
            <option id="Sales" value="Sales">Sales</option>
            <option id="Service" value="Service">Service</option>
            <option id="Development" value="Development">Development</option>
            <option id="Sourcing" value="Sourcing">Sourcing</option>
        </select>                  
    </td>
    </tr>    
    <tr>
    <td>Resolved?</td><td><input id="resolved" name="resolved" type="checkbox" onClick="showresby();"></td>    
    </tr>
    <tr>    
    <td></td>
    <td>    <div id="resby" style="display:none;">
            Resolved By:         
            <select id="resolvedby" name="resolvedby">
                <?php
                if(isset($teamnames))
                {
                    foreach($teamnames as $thisname)
                    {
                ?>
            <option id="<?php echo($thisname); ?>" value="<?php echo($thisname); ?>"><?php echo($thisname); ?></option>
            <?php
                    }
                }
            ?>
        </select>                  
    </td>
        </tr>
    <tr><td>Resolution Comments</td><td><textarea name="rescomments" id="rescomments" cols="32" rows="3"></textarea>
        </td>
    </tr>
    </div>
    <tr>
    <td>Bug?</td><td><input id="bug" name = "bug" type="checkbox" onClick="showbugid();"></td>    
    </tr>
    <tr>
        <td></td>
        <td>
            <div id="showbugid" style="display:none;">
            Bug ID: <input name = "bugid" id="bugid" type="text" size="5" maxlength="5">
            </div>
        </td>
    </tr>
</table>
    
<hr/>
<input type="submit" id="submit" name="submit" value="Create new Ticket" disabled/>
</form>
<script>
function showresby()
{
    if($("resolved").checked)
    {
        $("resby").show();
    }
    else
    {
        $("resby").hide();
    }
}

function showbugid()
{
    if($("bug").checked)
    {
        $("showbugid").show();
    }
    else
    {
        $("showbugid").hide();
    }
}

</script>    
</div>            
</div>
</div>

<br/>
<div class="panel">
<div class="paneltitle" align="center">My Unresolved Tickets</div>
<div class="panelcontents">
<?php $dg->Render(); ?>
</div>

</div>
<br/>
<div class="panel">
<div class="paneltitle" align="center">Resolved Tickets</div>
<div class="panelcontents">
<?php $dg1->Render(); ?>
</div>

</div>
<br/>

<?php
include("footer.php");
?>