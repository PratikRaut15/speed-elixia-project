<?php
include_once("session.php");
include_once("../lib/system/utilities.php");
include("loginorelse.php");
include_once("../constants/constants.php");
include_once("../db.php");
include_once("../lib/system/DatabaseManager.php");
include_once("../lib/system/Date.php");
include_once("../lib/system/Sanitise.php");

$helpdeskid = GetSafeValueString( isset($_GET["tid"])?$_GET["tid"]:$_POST["helpdeskid"], "long");

$db = new DatabaseManager();

if(isset($_POST["save"]))
{

    $type = GetSafeValueString($_POST["type"], "string");
    $customerno = GetSafeValueString($_POST["customerno"], "string");
    $name = GetSafeValueString($_POST["name"], "string");
    $company = GetSafeValueString($_POST["company"], "string");
    $phone = GetSafeValueString($_POST["phone"], "string");
    $email = GetSafeValueString($_POST["email"], "string");
    $contactedvia = GetSafeValueString($_POST["contactedvia"], "string");
    $reason = GetSafeValueString($_POST["reason"], "string");
    $allottedto = GetSafeValueString($_POST["allottedto"], "string");
    $rescomments = GetSafeValueString($_POST["rescomments"], "string");

    $resolved = 0;
    if(isset($_POST["resolved"]) && $_POST["resolved"] == "on")
    {
        $resolved = 1;
    }
    $resolvedby = GetSafeValueString($_POST["resolvedby"], "string");

    $bug = 0;
    if(isset($_POST["bug"]) && $_POST["bug"] == "on")
    {
        $bug = 1;
    }
    $bugid = GetSafeValueString($_POST["bugid"], "string");
    
    $SQL = sprintf( "UPDATE helpdesk SET
            `type`='%s',
            `customerno`='%d',
            `name`='%s',
            `company`='%s',
            `phone`='%s',
            `email`='%s',
            `contactvia`='%s',
            `reason`='%s',
            `resolved`='%d',
            `resolvedby`='%s',
            `allottedto`='%s',
            `bug`='%d',
            `bugid`='%d',
             rescomments = '%s'
            WHERE helpdeskid = %d" ,
                $type,
                $customerno,
                $name,
                $company,
                $phone,
                $email,
                $contactedvia,
                $reason,
                $resolved,
                $resolvedby,
                $allottedto,
                $bug,
                $bugid,
                $rescomments,
                $helpdeskid);    

    $db->executeQuery($SQL);

    header("Location: helpdesk.php");
}

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
    function showcreate()
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
    showcreate();
    ShowCustNo();
    showresby();
    showbugid();    
}

Event.observe(window,'load', function() {loaded();});
    
</script>    

<div class="panel">
<div class="paneltitle" align="center">Update Ticket</div>
<div class="panelcontents">

<form method="post" action="modifyticket.php"  enctype="multipart/form-data">
<input type="hidden" name="helpdeskid" value="<?php echo( $helpdeskid ); ?>"/>
<table width="100%">
    <tr><td><b>Customer Type</b></td>
        <td>
        <select id="type" name="type" onChange="ShowCustNo();">
                        <option id="<?php echo($type); ?>" value="<?php echo($type); ?>"><?php echo($type); ?></option>
                        <?php
                        if($type != "Current")
                        {
                        ?>
                            <option id="Current" value="Current">Current</option>                        
                        <?php
                        }
                        if($type != "Prospective")
                        {
                        ?>   
                            <option id="Prospective" value="Prospective">Prospective</option>
                        <?php
                        }
                        ?>
        </select>    
        </td>
    </tr>    
    <tr>
    <td></td><td><div id="custno"> Customer No.<input name = "customerno" id="customerno" type="text" size="4" maxlength="4" onblur="populatedetails();" value="<?php echo($customerno); ?>" ></div></td>
    </tr>
    <tr>
    <td>Name*</td><td><input name = "name" id="name" type="text" onkeyup="showcreate();" value="<?php echo($name); ?>" ></td>
    </tr>
    <tr>
    <td>Company*</td><td><input name = "company" id="company" type="text" onkeyup="showcreate();" value="<?php echo($company); ?>" ></td>
    </tr>    
    <tr>
    <td>Phone</td><td><input name = "phone" id="phone" type="text" value="<?php echo($phone); ?>" ></td>
    </tr>
    <tr>
    <td>Email</td><td><input name = "email" id="email" type="text" value="<?php echo($email); ?>" ></td>
    </tr>
    <tr>
    <td>Contacted Via</td>
    <td>
        <select id="contactedvia" name="contactedvia">
            <option id="<?php echo($contactedvia); ?>" value="<?php echo($contactedvia); ?>"><?php echo($contactedvia); ?></option>
            <?php            
            if($contactedvia != "Call")
            {
            ?>
                <option id="Call" value="Call">Call</option>
            <?php
            }
            if($contactedvia != "Email")
            {
            ?>   
                <option id="Email" value="Email">Email</option>
            <?php
            }
            if($contactedvia != "IM")
            {
            ?>
                <option id="IM" value="IM">Instant Messaging</option>
            <?php
            }
            if($contactedvia != "PV")
            {
            ?>   
                <option id="PV" value="PV">Personal Visit</option>
            <?php
            }
            ?>                
        </select>                  
    </td>
    </tr>    
    <tr>
    <td>Reason for Contact</td><td><textarea name="reason" id="reason" cols="32" rows="3"><?php echo($reason); ?></textarea>
        </td>
    </tr>        
    <tr>
    <td>Allotted to</td>
    <td>
        <select id="allottedto" name="allottedto" >
            <option id="<?php echo($allottedto); ?>" value="<?php echo($allottedto); ?>"><?php echo($allottedto); ?></option>            
            <?php            
            if($allottedto != "Sales")
            {
            ?>
            <option id="Sales" value="Sales">Sales</option>
            <?php
            }
            if($allottedto != "Service")
            {
            ?>   
            <option id="Service" value="Service">Service</option>
            <?php
            }
            if($allottedto != "Development")
            {
            ?>   
            <option id="Development" value="Development">Development</option>
            <?php
            }
            ?>                            
        </select>                  
    </td>
    </tr>    
    <tr>
    <td>Resolved?</td><td><input id="resolved" name="resolved" type="checkbox" onClick="showresby();" <?php if($resolved == 1) echo("checked"); ?>></td>    
    </tr>
    <tr>
    <td>Resolution Comments</td><td><textarea name="rescomments" id="rescomments" cols="32" rows="3"><?php echo $rescomments; ?></textarea>
        </td>
    </tr>
    <tr>    
    <td></td>
    <td>    <div id="resby" style="display:none;">
            Resolved By:         
            <select id="resolvedby" name="resolvedby">
            <option id="<?php echo($resolvedby); ?>" value="<?php echo($resolvedby); ?>"><?php echo($resolvedby); ?></option>                
                <?php
                if(isset($teamnames))
                {
                    foreach($teamnames as $thisname)
                    {
                        if($resolvedby != $thisname)
                        {
                    ?>
                <option id="<?php echo($thisname); ?>" value="<?php echo($thisname); ?>"><?php echo($thisname); ?></option>
                <?php
                        }
                    }
                }
            ?>
        </select>                  
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
            Bug ID: <input name = "bugid" id="bugid" type="text" size="5" maxlength="5" value="<?php echo($bugid); ?> ">
            </div>
        </td>
    </tr>
</table>
</div>
<br/>
<input type="submit" name="save" id="save" value="Save Changes" />
</form>
</div>

<?php
include("footer.php");
?>