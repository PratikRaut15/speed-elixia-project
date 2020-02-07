<?php
include_once("session.php");
include_once("../lib/system/utilities.php");
include("loginorelse.php");
include_once("../constants/constants.php");
include_once("../db.php");
include_once("../lib/system/DatabaseManager.php");
include_once("../lib/system/Date.php");
include_once("../lib/system/Sanitise.php");

$helpdeskid = GetSafeValueString( isset($_GET["rid"])?$_GET["rid"]:$_POST["helpdeskid"], "long");

$db = new DatabaseManager();


$SQL = sprintf("SELECT * from helpdesk 
where helpdeskid = '%d' LIMIT 1 ",$helpdeskid);
$db->executeQuery($SQL);
while($row = $db->get_nextRow())
{
    $type = $row["type"];
    $customerno = $row["customerno"];
    $name = $row["name"];
    $company = $row["company"];
    $phone = $row["phone"];
    $email = $row["email"];
    $contactedvia = $row["contactvia"];
    $reason = $row["reason"];
    $allottedto = $row["allottedto"];
    $resolved = $row["resolved"];
    $resolvedby = $row["resolvedby"];
    $rescomments = $row["rescomments"];
    $bug = $row["bug"];
    $bugid = $row["bugid"];
}

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

function loaded()
{
    showresby();
    showbugid();    
}
    
Event.observe(window,'load', function() {loaded();});
    
</script>    

<div class="panel">
<div class="paneltitle" align="center">Update Ticket</div>
<div class="panelcontents">

<table width="100%">
    <tr><td><b>Customer Type</b></td>
        <td>
            <input name = "type" id="type" type="text" readonly value="<?php echo($type); ?>">
        </td>
    </tr>    
    <tr>
    <td></td><td><div id="custno"> Customer No.<input name = "customerno" id="customerno" type="text" size="4" maxlength="4" readonly value="<?php echo($customerno); ?>"></div></td>
    </tr>
    <tr>
    <td>Name*</td><td><input name = "name" id="name" type="text" value="<?php echo($name); ?>" readonly></td>
    </tr>
    <tr>
    <td>Company*</td><td><input name = "company" id="company" type="text" value="<?php echo($company); ?>" readonly></td>
    </tr>    
    <tr>
    <td>Phone</td><td><input name = "phone" id="phone" type="text" value="<?php echo($phone); ?>" readonly></td>
    </tr>
    <tr>
    <td>Email</td><td><input name = "email" id="email" type="text" value="<?php echo($email); ?>" readonly></td>
    </tr>
    <tr>
    <td>Contacted Via</td>
    <td>
        <input name = "contactedvia" id="contactedvia" type="text" value="<?php echo($contactedvia); ?>" readonly>        
    </td>
    </tr>    
    <tr>
    <td>Reason for Contact</td><td><textarea name="reason" id="reason" cols="32" rows="3" readonly><?php echo($reason); ?></textarea>
        </td>
    </tr>        
    <tr>
    <td>Allotted to</td>
    <td>
        <input name = "allottedto" id="allottedto" type="text" value="<?php echo($allottedto); ?>" readonly>        
    </td>
    </tr>    
    <tr>
    <td>Resolved?</td><td><input id="resolved" name="resolved" type="checkbox" onClick="showresby();" <?php if($resolved == 1) echo("checked"); ?>></td>    
    </tr>
    <tr>
    <tr>
    <td>Resolution Comments</td><td><textarea name="rescomments" id="rescomments" cols="32" rows="3"><?php echo $rescomments; ?></textarea>
    </td>
    </tr>
    <td></td>
    <td>    <div id="resby" style="display:none;">
            Resolved By:         
                <input name = "resolvedby" id="resolvedby" type="text" value="<?php echo($resolvedby); ?>" readonly>            
    </td>
        </tr>
    </div>
    <tr>
    <td>Bug?</td><td><input id="bug" name = "bug" type="checkbox" onClick="showbugid();" <?php if($bug == 1) echo("checked"); ?>></td>    
    </tr>
    <tr>
        <td></td>
        <td>
            <div id="showbugid" style="display:none;">
            Bug ID: <input name = "bugid" id="bugid" type="text" size="5" maxlength="5" value="<?php echo($bugid); ?> " readonly>
            </div>
        </td>
    </tr>
</table>
</div>
<br/>
</div>

<?php
include("footer.php");
?>