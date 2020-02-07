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

$SQL = sprintf("SELECT u.unitno, s.simcardno, b.bucketid, b.customerno, c.customercompany, b.priority, v.vehicleno, b.location, b.purposeid, cp.person_name, cp.cp_phone1, t.name, b.vehicleno as vehno, b.vehicleid, sp.timeslot
                FROM bucket b
                INNER JOIN ".DB_PARENT.".customer c ON c.customerno = b.customerno
                LEFT OUTER JOIN vehicle v ON v.vehicleid = b.vehicleid
                LEFT OUTER JOIN ".DB_PARENT.".contactperson_details cp ON cp.cpdetailid = b.coordinatorid
                LEFT OUTER JOIN ".DB_PARENT.".team t ON t.teamid = b.fe_id
                LEFT OUTER JOIN ".DB_PARENT.".sp_timeslot sp ON sp.tsid = b.timeslotid                    
                LEFT OUTER JOIN unit u ON u.uid = b.unitid
                LEFT OUTER JOIN simcard s ON s.id = b.simcardid
                WHERE b.apt_date = '%s' AND b.status IN (0,4) ORDER BY sp.tsid", $apt_date);
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
        if($row['priority'] == 1) { $bucket->priority = "High"; }
        if($row['priority'] == 2) { $bucket->priority = "Medium"; }
        if($row['priority'] == 3) { $bucket->priority = "Low"; }        
        if($row['vehicleid'] == 0)
        {
            $bucket->vehicleno = $row['vehno'];                    
        }
        else
        {
            $bucket->vehicleno = $row['vehicleno'];        
        }
        $bucket->location = $row['location'];
        if($row['purposeid'] == 1) { $bucket->purposeid = "New Installation"; }        
        if($row['purposeid'] == 2) { $bucket->purposeid = "Repair"; }        
        if($row['purposeid'] == 3) { $bucket->purposeid = "Removal"; }        
        if($row['purposeid'] == 4) { $bucket->purposeid = "Replacement"; } 
        if($row['purposeid'] == 5) { $bucket->purposeid = "Reinstall"; } 
        $bucket->person_name = $row['person_name'];
        $bucket->person_phone = $row['cp_phone1'];
        $bucket->fe_name = $row['name'];
        $bucket->unitno = $row['unitno'];
        $bucket->simcardno = $row['simcardno'];
        $bucket->timeslot = $row['timeslot'];        
        $bucket->x = $x;
        $bucket_list[] = $bucket;
    }
}

$dg = new objectdatagrid( $bucket_list );
//$dg->AddAction("View/Edit", "../../images/edit.png", "modifycustomer.php?cid=%d");
$dg->AddColumn("Sr No.", "x");
$dg->AddColumn("Bucket Id", "bbucketid");
$dg->AddColumn("Customer #", "customerno");
$dg->AddColumn("Company", "customercompany");
$dg->AddColumn("Priority", "priority");
$dg->AddColumn("Vehicle No.", "vehicleno");
$dg->AddColumn("Location", "location");
$dg->AddColumn("Purpose", "purposeid");
$dg->AddColumn("Co-ordinator Name", "person_name");
$dg->AddColumn("Co-ordinator Phone", "person_phone");
$dg->AddColumn("Time Slot", "timeslot");
$dg->AddAction("View/Edit", "../../images/edit.png", "modifybucket_crm.php?id=%d");
$dg->SetNoDataMessage("Bucket Empty");
$dg->AddIdColumn("bucketid");

$_scripts[] = "../../scripts/trash/prototype.js";

include("header.php");


?>
<br/>
<div class="panel">
<div class="paneltitle" align="center">Search Bucket List</div>
<div class="panelcontents">    
<form method="post" action="bucketlist_crm.php"  enctype="multipart/form-data">
<table>
        <?php
        $installation = date("d-m-Y");
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

$SQL = sprintf("SELECT u.unitno, s.simcardno, b.bucketid, b.customerno, c.customercompany, b.priority, v.vehicleno, b.location, b.purposeid, cp.person_name, cp.cp_phone1, t.name, b.vehicleno as vehno, b.vehicleid, sp.timeslot
                FROM bucket b
                INNER JOIN ".DB_PARENT.".customer c ON c.customerno = b.customerno
                LEFT OUTER JOIN vehicle v ON v.vehicleid = b.vehicleid
                LEFT OUTER JOIN ".DB_PARENT.".contactperson_details cp ON cp.cpdetailid = b.coordinatorid
                LEFT OUTER JOIN ".DB_PARENT.".team t ON t.teamid = b.fe_id
                LEFT OUTER JOIN ".DB_PARENT.".sp_timeslot sp ON sp.tsid = b.timeslotid                                        
                LEFT OUTER JOIN unit u ON u.uid = b.unitid
                LEFT OUTER JOIN simcard s ON s.id = b.simcardid
                WHERE b.apt_date BETWEEN '%s' AND '%s' AND b.status IN (0,4,5) ORDER BY sp.tsid", $fromdate,$todate);
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
        if($row['priority'] == 1) { $bucket->priority = "High"; }
        if($row['priority'] == 2) { $bucket->priority = "Medium"; }
        if($row['priority'] == 3) { $bucket->priority = "Low"; }        
        if($row['vehicleid'] == 0)
        {
            $bucket->vehicleno = $row['vehno'];                    
        }
        else
        {
            $bucket->vehicleno = $row['vehicleno'];        
        }
        $bucket->location = $row['location'];
        if($row['purposeid'] == 1) { $bucket->purposeid = "New Installation"; }        
        if($row['purposeid'] == 2) { $bucket->purposeid = "Repair"; }        
        if($row['purposeid'] == 3) { $bucket->purposeid = "Removal"; }        
        if($row['purposeid'] == 4) { $bucket->purposeid = "Replacement"; }   
        if($row['purposeid'] == 5) { $bucket->purposeid = "Reinstall"; } 
        $bucket->person_name = $row['person_name'];
        $bucket->person_phone = $row['cp_phone1'];
        $bucket->fe_name = $row['name'];
        $bucket->unitno = $row['unitno'];
        $bucket->simcardno = $row['simcardno'];
        $bucket->timeslot = $row['timeslot'];        
        $bucket->x = $x;
        $bucket_list[] = $bucket;
    }
}

$df = new objectdatagrid( $bucket_list );
//$dg->AddAction("View/Edit", "../../images/edit.png", "modifycustomer.php?cid=%d");
$df->AddColumn("Sr No.", "x");
$df->AddColumn("Bucket Id", "bbucketid");
$df->AddColumn("Customer #", "customerno");
$df->AddColumn("Company", "customercompany");
$df->AddColumn("Priority", "priority");
$df->AddColumn("Vehicle No.", "vehicleno");
$df->AddColumn("Location", "location");
$df->AddColumn("Purpose", "purposeid");
$df->AddColumn("Co-ordinator Name", "person_name");
$df->AddColumn("Co-ordinator Phone", "person_phone");
$df->AddColumn("Time Slot", "timeslot");
$df->AddAction("View/Edit", "../../images/edit.png", "modifybucket_crm.php?id=%d");
$df->SetNoDataMessage("Bucket Empty");
$df->AddIdColumn("bucketid");
    
    
        ?>
        <div class="panel">
        <div class="paneltitle" align="center">Bucket List</div>
        <div class="panelcontents">
        <?php $df->Render(); ?>
        </div>

        </div>
        <?php
}else{
    ?>

<br/>   
<div class="panel">
<div class="paneltitle" align="center">Bucket List</div>
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

</script>