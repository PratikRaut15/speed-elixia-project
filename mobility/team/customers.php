<?php
include_once("session.php");
include("loginorelse.php");
include_once("../constants/constants.php");
include_once("../db.php");
include_once("../lib/components/gui/datagrid.php");
include_once("../lib/system/DatabaseManager.php");


//Datagtrid
$db = new DatabaseManager();
$SQL = sprintf("SELECT c.customerno, c.customername, c.customeremail, c.customerphone, c.customercell, c.dateadded, st.name as soldby FROM customer c INNER JOIN team st ON st.teamid = c.teamid");
$db->executeQuery($SQL);

$dg = new datagrid( $db->getQueryResult() );
$dg->AddAction("View/Edit", "../images/edit.png", "modifycustomer.php?cid=%d");
$dg->AddColumn("Customer #", "customerno");
$dg->AddColumn("Name", "customername");
$dg->AddColumn("Phone", "customerphone");
$dg->AddColumn("Cell", "customercell");
$dg->AddColumn("Date Added (yyyy-mm-dd)", "dateadded");
$dg->AddColumn("Email", "customeremail");
$dg->AddColumn("Sold by", "soldby");
$dg->SetNoDataMessage("No Customer");
$dg->AddIdColumn("customerno");

$_scripts[] = "../js/jquery-1.5.1.min.js";
$_scripts[] = "../js/jquery-ui-1.8.13.custom.min.js";
$_scripts[] = "../js/jQueryRotate.2.1.js";
 
include("header.php");

?>
<script type="text/javascript">
    function addcustomer()
    {
        if($("hidaddcust").value == 0)
        {
            jQuery("#customerlist").slideDown("slow");
            jQuery("#AddCustomer").rotate({ angle:0,animateTo:45,easing: jQuery.easing.easeInOutExpo })
            $("hidaddcust").value = 1;
        }
        else
        {
            jQuery("#customerlist").slideUp("slow");            
            $("hidaddcust").value = 0;
            jQuery("#AddCustomer").rotate({ angle:45,animateTo:0,easing: jQuery.easing.easeInOutExpo })
        }
    }
    
    function acknowledge()
    {
       if(valid())
       {
           if( $("confirm").getValue()=="on")
           {
               $("submit").enable();
           }
           else
           {
               $("submit").disable();
           }
       }
    }

    function valid()
    {
        if($("cname").getValue()=="" ||
            $("cprimaryname").getValue()=="" ||
            $("cprimaryusername").getValue()=="" ||
            $("cprimarypassword").getValue()=="" ||
            $("csupportusername").getValue()=="" ||
            $("csupportpassword").getValue()=="")
        {
            alert("Cannot Save Yet. Check Mandatory Fields");
            return false;
        }
        else
        {
            return true;
        }        
    }
</script>    
<?php
if(IsHead())
{
?>
    <div class="panel">
    <div class="paneltitle" align="center">
    <input type="hidden" value="0" id="hidaddcust">    
    <input type="image" id="AddCustomer" alt="Add New Customer" onclick="addcustomer();" src="../images/add.png" />            
        New Customer</div>
    <div class="panelcontents">
    <div id="customerlist" style="display:none;">    
    <form method="post" action="createcustomer.php" enctype="multipart/form-data">
    <table width="100%">
        <tr><td colspan="2"><h3>Personal Details</h3></td></tr>    
        <tr>
        <td>Name*</td><td><input name = "cname" id="cname" type="text"></td>
        </tr>
        <tr>
        <td>Company</td><td><input name = "ccompany" type="text"></td>
        </tr>    
        <tr>
        <td>Address</td><td><input name = "cadd1" type="text"></td>
        </tr>
        <tr>
        <td></td><td><input name = "cadd2" type="text"></td>
        </tr>
        <tr>
        <td>City</td><td><input name = "ccity" type="text"></td>
        </tr>
        <tr>
        <td>State</td><td><input name = "cstate" type="text"></td>
        </tr>
        <tr>
        <td>Zip</td><td><input name = "czip" type="text"></td>
        </tr>
        <tr>
        <td>Phone Number</td><td><input name = "cphone" type="text"></td>
        </tr>
        <tr>
        <td>Cell</td><td><input name = "ccell" type="text"></td>
        </tr>
        <tr>
        <td>Email</td><td><input name = "cemail" type="text"></td>
        </tr>
        <tr>
        <td>Referral</td><td><input name = "ref" type="text"></td>
        </tr>

        <tr><td colspan="2"><h3>Business Files</h3></td></tr>    
        <tr>
        <td>Logo</td><td><input id="clogo" name = "clogo" type="file">Size Constraints</td>
        </tr>
        <tr>
        <td>Banner</td><td><input id="cbanner" name = "cbanner" type="file">Size Constraints</td>
        </tr>
        <tr>
        <td>Notes</td><td><textarea name="cnotes" cols="70" rows="6" ></textarea></td>
        </tr>

        <tr><td colspan="2"><h3>Operations</h3></td></tr>    
        <tr>
        <td>Item Delivery</td>
        <td>
            <input type="checkbox" name="item_del" id="item_del">
        </td>        
        </tr>
        <tr>
        <td>Geo Fencing</td>
        <td>
            <input type="checkbox" name="geofencing" id="geofencing">
        </td>        
        </tr>
        <tr>
        <td>Elixia Code</td>
        <td>
            <input type="checkbox" name="elixiacode" id="elixiacode">
        </td>        
        </tr>
        <tr>
        <td>Messaging System</td>
        <td>
            <input type="checkbox" name="messaging" id="messaging">
        </td>        
        </tr>            
        <tr>
        <td>Service Call</td>
        <td>
            <input type="checkbox" name="service" id="service">
        </td>        
        </tr>                    

        <tr><td colspan="2"><h3>Security</h3></td></tr>
        <tr>
        <td>Sales Representative</td><td>
    <?php
        $SQL = sprintf("SELECT st.* from team st order by st.name ASC");
        $db = new DatabaseManager();
        $db->executeQuery($SQL);
        echo("<select name='teamid'>");
        while($row = $db->get_nextRow())
        {
            $id=$row["teamid"];
            $name=$row["name"];
            echo("<option value='$id'>$name</option>");
        }
        echo("</select>");
    ?>
    </td>
        </tr>

        <tr>
        <td><strong>Primary User*</strong></td><td>Real Name<input id="cprimaryname" name="cprimaryname" type="text"/><br/>
    Username:<input id="cprimaryusername" name="cprimaryusername" type="text"/> Password:
    <input id="cprimarypassword" name="cprimarypassword" type="password"/></td>
        </tr>
        <tr>
        <td><strong>Support User*</strong></td><td>Username:<input id="csupportusername" name="csupportusername" type="text"/> Password:
        <input id="csupportpassword" name="csupportpassword" type="password"/>Required for support</td>
        </tr>  
		<tr>
        <td><strong>Module Track</strong></td><td>
        <input id="istrack" name="istrack" type="checkbox"/></td>
        </tr>
		<tr>
        <td><strong>Module Service</strong></td><td>
        <input id="isservice" name="isservice" type="checkbox"/></td>
        </tr>  
    </table>

    <hr/>
    <p><input id="confirm" name="confirm" type="checkbox" onchange="acknowledge();"/> I hereby confirm that this customer can now use Elixia Police.</p>    
    <input type="submit" id="submit" name="submit" value="Create new Customer" disabled/>
    </form>
    </div>            
    </div>
    </div>
<?php
}
?>

<br/>
<div class="panel">
<div class="paneltitle" align="center">Customer List</div>
<div class="panelcontents">
<?php $dg->Render(); ?>
</div>

</div>
<br/>

<?php
if(IsSales() || IsHead())
{
//Datagtrid
$db1 = new DatabaseManager();
$SQL1 = sprintf("SELECT p.prospectid, p.dateadded, p.name, p.company, p.phone, p.email, p.status, p.target, p.company, t.name as addedby FROM prospectives p INNER JOIN team t ON t.teamid = p.teamid");
$db1->executeQuery($SQL1);

$dg1 = new datagrid( $db1->getQueryResult() );
$dg1->AddAction("View/Edit", "../images/edit.png", "modifyprospectives.php?pid=%d");
$dg1->AddColumn("Name", "name");
$dg1->AddColumn("Company", "company");
$dg1->AddColumn("Phone", "phone");
$dg1->AddColumn("Email", "email");
$dg1->AddColumn("Date Added (yyyy-mm-dd)", "dateadded");
$dg1->AddColumn("Status", "status");
$dg1->AddColumn("Sales Target", "target");
$dg1->AddColumn("Added by", "addedby");
$dg1->SetNoDataMessage("No Prospective Customer");
$dg1->AddIdColumn("prospectid");

?>
<script type="text/javascript">
    function addcustomerp()
    {
        if($("hidaddcustp").value == 0)
        {
            jQuery("#customerlistp").slideDown("slow");
            jQuery("#AddCustomerp").rotate({ angle:0,animateTo:45,easing: jQuery.easing.easeInOutExpo })
            $("hidaddcustp").value = 1;
        }
        else
        {
            jQuery("#customerlistp").slideUp("slow");            
            $("hidaddcustp").value = 0;
            jQuery("#AddCustomerp").rotate({ angle:45,animateTo:0,easing: jQuery.easing.easeInOutExpo })
        }
    }
    
    function showcreatep()
    {
        if($("pname").value == "")
        {
            $("submitp").disabled = true;
        }
        else
        {
            $("submitp").disabled = false;        
        }
    }
</script>    
<div class="panel">
<div class="paneltitle" align="center">
<input type="hidden" value="0" id="hidaddcustp">    
<input type="image" id="AddCustomerp" alt="Add New Customer" onclick="addcustomerp();" src="../images/add.png" />            
    New Prospective Customer</div>
<div class="panelcontents">
<div id="customerlistp" style="display:none;">    
<form method="post" action="createprospective.php" enctype="multipart/form-data">
<table width="100%">
    <tr><td colspan="2"><h3>Personal Details</h3></td></tr>    
    <tr>
    <td>Name*</td><td><input name = "pname" id="pname" type="text" onkeyup="showcreatep();"></td>
    </tr>
    <tr>
    <td>Company</td><td><input name = "pcompany" type="text"></td>
    </tr>    
    <tr>
    <td>Phone</td><td><input name = "pphone" type="text"></td>
    </tr>
    <tr>
    <td>Email</td><td><input name = "pemail" type="text"></td>
    </tr>
    <tr>
        <td>Referral</td><td><input name = "ref" type="text"></td>
    </tr>
    <tr>
    <td>Sector</td><td><input name = "psector" type="text"></td>
    </tr>    
    
    <tr><td colspan="2"><h3>Sales Feedback</h3></td></tr>    
    <tr>
    <td>Sales Target</td><td><input name = "ptarget" type="text"></td>
    </tr>
    <tr>
    <td>Current Status</td><td><input name = "pstatus" type="text"></td>
    </tr>
    <tr>
    <td>Next Step</td><td><input name = "pnextstep" type="text"></td>
    </tr>
    <tr>
    <td>Sales Done?</td><td><input name = "psalesdone" type="checkbox"></td>    
    </tr>
    <tr>
    <td>Comment</td><td><textarea name="pcomment" id="pcomment" cols="32" rows="3"></textarea>
        </td>
    </tr>    
    
    
</table>
    
<hr/>
<input type="submit" id="submitp" name="submitp" value="Create new Prospective Customer" disabled/>
</form>
</div>            
</div>
</div>

<br/>
<div class="panel">
<div class="paneltitle" align="center">Prospective Customer List</div>
<div class="panelcontents">
<?php $dg1->Render(); ?>
</div>

</div>
<br/>
<?php
}
include("footer.php");
?>