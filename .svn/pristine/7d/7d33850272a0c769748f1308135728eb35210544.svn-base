<?php
include_once("session.php");
include_once("../../lib/system/utilities.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/bo/CustomerManager.php");
include_once("../../lib/bo/VehicleManager.php");
include_once("../../lib/system/Date.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");

class VOBucket{};


//Datagtrid
$db = new DatabaseManager();

$apt_date = date("Y-m-d");

$SQL = sprintf("SELECT b.location, b.status, b.is_problem_of, r.reason, b.remarks, u.unitno, s.simcardno, b.bucketid, b.customerno, c.customercompany, v.vehicleno, b.purposeid, t.name, b.vehicleno as vehno, b.vehicleid
                FROM bucket b
                INNER JOIN ".DB_PARENT.".customer c ON c.customerno = b.customerno
                LEFT OUTER JOIN vehicle v ON v.vehicleid = b.vehicleid
                LEFT OUTER JOIN ".DB_PARENT.".team t ON t.teamid = b.fe_id
                LEFT OUTER JOIN unit u ON u.uid = b.unitid
                LEFT OUTER JOIN simcard s ON s.id = b.simcardid
                LEFT OUTER JOIN nc_reason r ON r.reasonid = b.reasonid
                WHERE b.apt_date = '%s' AND b.status <> 0 
                ORDER BY    b.fe_id,b.bucketid ASC", $apt_date);
$db->executeQuery($SQL);

$x = 0;
$bucket_list = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $x++;
        $bucket = new VOBucket();
        $bucket->bucketid = $row['bucketid'];
        $bucket->bbucketid = "B00".$row['bucketid'];
        $bucket->customerno = $row['customerno'];
        $bucket->customercompany = $row['customercompany'];
        if($row['vehicleid'] == 0)
        {
            $bucket->vehicleno = $row['vehno'];                    
        }
        else
        {
            $bucket->vehicleno = $row['vehicleno'];        
        }
        if($row['purposeid'] == 1) { $bucket->purposeid = "New Installation"; }        
        if($row['purposeid'] == 2) { $bucket->purposeid = "Repair"; }        
        if($row['purposeid'] == 3) { $bucket->purposeid = "Removal"; }        
        if($row['purposeid'] == 4) { $bucket->purposeid = "Replacement"; }    
        if($row['purposeid'] == 5) { $bucket->purposeid = "Reinstall"; } 
        $bucket->fe_name = $row['name'];
        $bucket->unitno = $row['unitno'];
        $bucket->simcardno = $row['simcardno'];
        if($row['status'] == 1) { $bucket->status = "Rescheduled"; $bucket->icon=""; }
        if($row['status'] == 2) { $bucket->status = "Successful"; $bucket->icon=""; }        
        if($row['status'] == 3) { $bucket->status = "Unsuccessful"; $bucket->icon=""; }
        if($row['status'] == 4) { $bucket->status = "FE Assigned"; $bucket->icon="<a href='modifycompliance.php?id=".$bucket->bucketid."'><img src='../../images/edit.png'></img></a>"; }                
        if($row['status'] == 5) { $bucket->status = "Cancelled"; $bucket->icon=""; }
        if($row['status'] == 6) { $bucket->status = "Incomplete"; $bucket->icon=""; }
        if($row["is_problem_of"] == 1) { $bucket->is_problem_of = "Elixir";}
        if($row["is_problem_of"] == 2) { $bucket->is_problem_of = "Customer";}        
        $bucket->reason = $row['reason'];
        $bucket->remarks = $row['remarks'];        
        $bucket->location = $row['location'];                
        $bucket->x = $x;
        $bucket_list[] = $bucket;
    }
}

$dg = new objectdatagrid( $bucket_list );
//$dg->AddAction("View/Edit", "../../images/edit.png", "modifycustomer.php?cid=%d");
$dg->AddColumn("View/Edit", "icon");
$dg->AddColumn("Sr No.", "x");
$dg->AddColumn("Bucket Id", "bbucketid");
$dg->AddColumn("Customer #", "customerno");
$dg->AddColumn("Company", "customercompany");
$dg->AddColumn("Vehicle No.", "vehicleno");
$dg->AddColumn("Purpose", "purposeid");
$dg->AddColumn("Location", "location");
$dg->AddColumn("Installer", "fe_name");
$dg->AddColumn("Unit No", "unitno");
$dg->AddColumn("Simcard No", "simcardno");
$dg->AddColumn("Status", "status");
$dg->AddColumn("Problem Creator", "is_problem_of");
$dg->AddColumn("Reason", "reason");
$dg->AddColumn("Remarks", "remarks");
$dg->SetNoDataMessage("Bucket Empty");
$dg->AddIdColumn("bucketid");

$_scripts[] = "../../scripts/trash/prototype.js";

include("header.php");


?>
<br/>
<div class="panel">
<div class="paneltitle" align="center">Search Compliance List</div>
<div class="panelcontents">    
<form method="post" action="compliance.php"  enctype="multipart/form-data">
<table>
        <?php
        $installation = date('d-m-Y');   
        if(!isset($_POST['fromdate']))
        {
            $_POST['fromdate'] = $installation;
        }if(!isset($_POST['todate']))
        {
            $_POST['todate'] = $installation;
        }
        ?>
        <tr>
        <td>From Date </td>
        <td> <input name="fromdate" id="fromdate" type="text" value="<?php echo $_POST['fromdate']; ?>"/><button id="trigger">...</button>
        </td>
       
        <td>To Date </td>
        <td> <input name="todate" id="todate" type="text" value="<?php echo $_POST['todate']; ?>"/><button id="trigger2">...</button>
        </td>
        <td>
            <input type="submit" name="search" value="Search" />
        </td>
        </tr>
</table>

</form>
</div>
</div>
<br/>
<?php

if(isset($_POST["search"]))
{
    $fromdate = GetSafeValueString($_POST["fromdate"], "string");  
    $fromdate = date('Y-m-d', strtotime($fromdate));
    $todate = GetSafeValueString($_POST["todate"], "string");
    $todate = date('Y-m-d', strtotime($todate));

$SQL = sprintf("SELECT b.location, b.status, b.is_problem_of, r.reason, b.remarks, u.unitno, s.simcardno, b.bucketid, b.customerno, c.customercompany, v.vehicleno, b.purposeid, t.name, b.vehicleno as vehno, b.vehicleid
                FROM bucket b
                INNER JOIN ".DB_PARENT.".customer c ON c.customerno = b.customerno
                LEFT OUTER JOIN vehicle v ON v.vehicleid = b.vehicleid
                LEFT OUTER JOIN ".DB_PARENT.".team t ON t.teamid = b.fe_id
                LEFT OUTER JOIN unit u ON u.uid = b.unitid
                LEFT OUTER JOIN simcard s ON s.id = b.simcardid
                LEFT OUTER JOIN nc_reason r ON r.reasonid = b.reasonid
                WHERE b.apt_date BETWEEN '%s' AND '%s' AND b.status <> 0 ORDER BY b.fe_id,b.bucketid ASC", $fromdate, $todate);
$db->executeQuery($SQL);

$x = 0;
$bucket_list = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $x++;
        $bucket = new VOBucket();
        $bucket->bucketid = $row['bucketid'];
        $bucket->bbucketid = "B00".$row['bucketid'];
        $bucket->customerno = $row['customerno'];
        $bucket->customercompany = $row['customercompany'];
        if($row['vehicleid'] == 0)
        {
            $bucket->vehicleno = $row['vehno'];                    
        }
        else
        {
            $bucket->vehicleno = $row['vehicleno'];        
        }
        if($row['purposeid'] == 1) { $bucket->purposeid = "New Installation"; }        
        if($row['purposeid'] == 2) { $bucket->purposeid = "Repair"; }        
        if($row['purposeid'] == 3) { $bucket->purposeid = "Removal"; }        
        if($row['purposeid'] == 4) { $bucket->purposeid = "Replacement"; }  
        if($row['purposeid'] == 5) { $bucket->purposeid = "Reinstall"; } 
        $bucket->fe_name = $row['name'];
        $bucket->unitno = $row['unitno'];
        $bucket->simcardno = $row['simcardno'];
        if($row['status'] == 1) { $bucket->status = "Rescheduled";  $bucket->icon=" "; }
        if($row['status'] == 2) { $bucket->status = "Successful";  $bucket->icon=" "; }        
        if($row['status'] == 3) { $bucket->status = "Unsuccessful"; $bucket->icon=" "; }
        if($row['status'] == 4) { $bucket->status = "FE Assigned"; $bucket->icon="<a href='modifycompliance.php?id=".$bucket->bucketid."'><img src='../../images/edit.png'></img></a>"; }                
        if($row['status'] == 5) { $bucket->status = "Cancelled"; $bucket->icon=" "; }
        if($row['status'] == 6) { $bucket->status = "Incomplete"; $bucket->icon=" "; }
        if($row["is_problem_of"] == 1) { $bucket->is_problem_of = "Elixir";}
        if($row["is_problem_of"] == 2) { $bucket->is_problem_of = "Customer";}        
        $bucket->reason = $row['reason'];
        $bucket->remarks = $row['remarks'];        
        $bucket->location = $row['location'];                
        $bucket->x = $x;
        $bucket_list[] = $bucket;
    }
}

$df = new objectdatagrid( $bucket_list );
//$dg->AddAction("View/Edit", "../../images/edit.png", "modifycustomer.php?cid=%d");
$df->AddColumn("View/Edit", "icon");
$df->AddColumn("Sr No.", "x");
$df->AddColumn("Bucket Id", "bbucketid");
$df->AddColumn("Customer #", "customerno");
$df->AddColumn("Company", "customercompany");
$df->AddColumn("Vehicle No.", "vehicleno");
$df->AddColumn("Purpose", "purposeid");
$df->AddColumn("Location", "location");
$df->AddColumn("Installer", "fe_name");
$df->AddColumn("Unit No", "unitno");
$df->AddColumn("Simcard No", "simcardno");
$df->AddColumn("Status", "status");
$df->AddColumn("Problem Creator", "is_problem_of");
$df->AddColumn("Reason", "reason");
$df->AddColumn("Remarks", "remarks");
$df->SetNoDataMessage("Bucket Empty");
$df->AddIdColumn("bucketid");
    
        ?>
        <div class="panel">
        <div class="paneltitle" align="center">Compliance List</div>
        <div class="panelcontents">
        <?php $df->Render(); ?>
        </div>

        </div>
        <?php
}else{
    ?>

<br/>   
<div class="panel">
<div class="paneltitle" align="center">Compliance List</div>
<div class="panelcontents">
<?php $dg->Render(); ?>
</div>

</div>
<br/>

<?php
}

include("footer.php");
?>

<script>
<?php include_once '../../scripts/speedUtils.js'; ?>
    
Calendar.setup(
{
    inputField : "fromdate", // ID of the input field
    ifFormat : "%d-%m-%Y", // the date format
    button : "trigger" // ID of the button
});

Calendar.setup(
{
    inputField : "todate", // ID of the input field
    ifFormat : "%d-%m-%Y", // the date format
    button : "trigger2" // ID of the button
});

  jQuery(function() {
    var err = decodeURI(GetParameterValues('msg'));
    
    if(err !== '' && err !== "undefined"){
        alert(err);
    }
  });
</script>
