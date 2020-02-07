<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");

class testing{
    
}

$data = Array();
$srno = 1;
//Datagtrid
$db = new DatabaseManager();
$message="";
   
if(isset($_POST["scsearch"])){
    $startdate = GetSafeValueString($_POST["startdate"], "string");     
    $enddate = GetSafeValueString($_POST["enddate"], "string");  
    $startdate = date("Y-m-d", strtotime($startdate));
    $enddate = date("Y-m-d", strtotime($enddate));

    if($startdate=='' && $enddate==''){
        $message="From date or To Date should not be blank.";
    }else{
    $SQL= sprintf("select team.teamid,team.name,trans_history.trans_time, SUM(CASE WHEN sc.type = 0 THEN 1 ELSE 0 END) as Registered_Demo, SUM(CASE WHEN sc.type = 1 THEN 1 ELSE 0 END) as Registered_Invoiced, SUM(CASE WHEN sc.type = 2 THEN 1 ELSE 0 END) as Removed, SUM(CASE WHEN sc.type = 3 THEN 1 ELSE 0 END) as Repaired, SUM(CASE WHEN sc.type = 4 THEN 1 ELSE 0 END) as Replaced_Unit, SUM(CASE WHEN sc.type = 5 THEN 1 ELSE 0 END) as Replaced_Simcard FROM ".DB_PARENT.".servicecall sc INNER JOIN ".DB_PARENT.".trans_history ON trans_history.thid = sc.thid INNER JOIN ".DB_PARENT.".team ON team.teamid = sc.teamid  where DATE(trans_history.trans_time) BETWEEN '".$startdate."' AND '".$enddate."' group by team.teamid ORDER BY team.name ASC ");
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow())
        {
            $data = new testing();
            $data->srno = $srno;
            $data->teamid = $row["teamid"];
            $data->trans_date = date("d-m-Y", strtotime($row["trans_time"]));    
            $data->name = $row["name"];
            $data->Registered_Demo = $row["Registered_Demo"];
            $data->Registered_Invoiced = $row["Registered_Invoiced"];
            $data->Removed = $row["Removed"];
            $data->Repaired = $row["Repaired"];
            $data->Replaced_Unit = $row["Replaced_Unit"];
            $data->Replaced_Simcard = $row["Replaced_Simcard"];
                                  
            $devices[] = $data; 
            $srno++;
        }    
    }
}
}else{
        $todaydate = date("Y-m-d");
        $SQL= sprintf("select team.teamid,team.name,trans_history.trans_time, SUM(CASE WHEN sc.type = 0 THEN 1 ELSE 0 END) as Registered_Demo, SUM(CASE WHEN sc.type = 1 THEN 1 ELSE 0 END) as Registered_Invoiced, SUM(CASE WHEN sc.type = 2 THEN 1 ELSE 0 END) as Removed, SUM(CASE WHEN sc.type = 3 THEN 1 ELSE 0 END) as Repaired, SUM(CASE WHEN sc.type = 4 THEN 1 ELSE 0 END) as Replaced_Unit, SUM(CASE WHEN sc.type = 5 THEN 1 ELSE 0 END) as Replaced_Simcard FROM ".DB_PARENT.".servicecall sc INNER JOIN ".DB_PARENT.".trans_history ON trans_history.thid = sc.thid INNER JOIN ".DB_PARENT.".team ON team.teamid = sc.teamid  where DATE(trans_history.trans_time)= '".$todaydate."' group by team.teamid ORDER BY team.name ASC ");
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow())
        {
            $data = new testing();
            $data->srno = $srno;
            $data->teamid = $row["teamid"];
            $data->trans_date = date("d-m-Y", strtotime($row["trans_time"]));    
            $data->name = $row["name"];
            $data->Registered_Demo = $row["Registered_Demo"];
            $data->Registered_Invoiced = $row["Registered_Invoiced"];
            $data->Removed = $row["Removed"];
            $data->Repaired = $row["Repaired"];
            $data->Replaced_Unit = $row["Replaced_Unit"];
            $data->Replaced_Simcard = $row["Replaced_Simcard"];
                                  
            $devices[] = $data; 
            $srno++;
        } 
    
}
}
$dg = new objectdatagrid( $devices );
$dg->AddColumn("Sr.No.", "srno");
$dg->AddColumn("Elixir", "name");
$dg->AddColumn("Date", "trans_date");
$dg->AddColumn("Registered Demo", "Registered_Demo");
$dg->AddColumn("Registered Invoiced", "Registered_Invoiced");
$dg->AddColumn("Removed", "Removed");
$dg->AddColumn("Repaired", "Repaired");
$dg->AddColumn("Replaced Unit", "Replaced_Unit");
$dg->AddColumn("Replaced Simcard", "Replaced_Simcard");
$dg->SetNoDataMessage("No Details");
$dg->AddIdColumn("teamid");


$_scripts[] = "../../scripts/trash/prototype.js";
include("header.php");
?>

<div class="panel">
    <div class="paneltitle" align="center">Analysis Search</div>
<div class="panelcontents">    
    <form method="post" action="analysis.php"  enctype="multipart/form-data">
<?php
if(!empty($message)){
    echo"<span style='color:red; font-size:10px;'>".$message."</span>";
}
?>
        <table width="60%" align="center">
        <tr>
            <td>Start Date  <input name="startdate" id="startdate" type="text" value="<?php if(isset($startdate)) { echo(date("d-m-Y", strtotime($startdate))); } else { echo date("d-m-Y"); } ?>"/><button id="trigger2">...</button>
            </td>
            <td>End Date  <input name="enddate" id="enddate" type="text" value="<?php if(isset($enddate)) { echo(date("d-m-Y", strtotime($enddate))); } else { echo date("d-m-Y"); } ?>"/><button id="trigger">...</button>
            </td>        
        </tr>            
        </table>
<div align="center"><input type="submit" name="scsearch" value="Search" /></div>
</form>
    
</div>
</div>    

<br/>
<div class="panel">
    <div class="paneltitle" align="center">Analysis <?php if(!empty($todaydate) && empty($startdate)&&empty($enddate)){ echo"For Today"; }?></div>
    <div class="panelcontents" align="center">
        <?php
         if($startdate=="" && $enddate==""){
            $todaydate = date("Y-m-d");
            $sql = "select sum(totals.Registered_Demo) as total_regdemo, sum(totals.Registered_Invoiced) as total_reg_invoice, sum(totals.Removed)as total_remove, sum(totals.Repaired) as total_repaired, sum(totals.Replaced_Unit) as total_replaceunit, sum(totals.Replaced_Simcard) as total_replacesim FROM (select ".DB_PARENT.".team.teamid,".DB_PARENT.".team.name,".DB_PARENT.".trans_history.trans_time, SUM(CASE WHEN sc.type = 0 THEN 1 ELSE 0 END) as Registered_Demo, SUM(CASE WHEN sc.type = 1 THEN 1 ELSE 0 END) as Registered_Invoiced, SUM(CASE WHEN sc.type = 2 THEN 1 ELSE 0 END) as Removed, SUM(CASE WHEN sc.type = 3 THEN 1 ELSE 0 END) as Repaired, SUM(CASE WHEN sc.type = 4 THEN 1 ELSE 0 END) as Replaced_Unit, SUM(CASE WHEN sc.type = 5 THEN 1 ELSE 0 END) as Replaced_Simcard FROM ".DB_PARENT.".servicecall sc INNER JOIN ".DB_PARENT.".trans_history ON ".DB_PARENT.".trans_history.thid = ".DB_PARENT.".sc.thid INNER JOIN ".DB_PARENT.".team ON ".DB_PARENT.".team.teamid = ".DB_PARENT.".sc.teamid  where DATE(".DB_PARENT.".trans_history.trans_time) ='".$todaydate."' group by ".DB_PARENT.".team.teamid ORDER BY ".DB_PARENT.".trans_history.trans_time DESC )as totals";     
         }else{
            $sql = "select sum(totals.Registered_Demo) as total_regdemo, sum(totals.Registered_Invoiced) as total_reg_invoice, sum(totals.Removed)as total_remove, sum(totals.Repaired) as total_repaired, sum(totals.Replaced_Unit) as total_replaceunit, sum(totals.Replaced_Simcard) as total_replacesim FROM (select ".DB_PARENT.".team.teamid,".DB_PARENT.".team.name,".DB_PARENT.".trans_history.trans_time, SUM(CASE WHEN sc.type = 0 THEN 1 ELSE 0 END) as Registered_Demo, SUM(CASE WHEN sc.type = 1 THEN 1 ELSE 0 END) as Registered_Invoiced, SUM(CASE WHEN sc.type = 2 THEN 1 ELSE 0 END) as Removed, SUM(CASE WHEN sc.type = 3 THEN 1 ELSE 0 END) as Repaired, SUM(CASE WHEN sc.type = 4 THEN 1 ELSE 0 END) as Replaced_Unit, SUM(CASE WHEN sc.type = 5 THEN 1 ELSE 0 END) as Replaced_Simcard FROM ".DB_PARENT.".servicecall sc INNER JOIN ".DB_PARENT.".trans_history ON ".DB_PARENT.".trans_history.thid = ".DB_PARENT.".sc.thid INNER JOIN ".DB_PARENT.".team ON ".DB_PARENT.".team.teamid = ".DB_PARENT.".sc.teamid  where DATE(".DB_PARENT.".trans_history.trans_time) BETWEEN '".$startdate."' AND '".$enddate."' group by ".DB_PARENT.".team.teamid ORDER BY ".DB_PARENT.".trans_history.trans_time DESC )as totals";
         }
        $db->executeQuery($sql);
        if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow())
        {
             $total_regdemo= $row["total_regdemo"];
             $total_reg_invoice= $row["total_reg_invoice"];
             $total_remove= $row["total_remove"];
             $total_repaired= $row["total_repaired"];
             $total_replaceunit= $row["total_replaceunit"];
             $total_replacesim= $row["total_replacesim"];
        }
        }
        ?>
            <table border="1" style="font-size:12px;" cellpadding="6px;">
            <tr>
                <td><b>Total Registered Demo </b></td>
                <td><?php  if(empty($total_regdemo)){echo "0";}else{echo $total_regdemo;};?></td>
                <td><b>Registered Invoiced</b></td>
                <td><?php  if(empty($total_reg_invoice)){echo "0";}else{echo $total_reg_invoice;};?></td>
            </tr>
            <tr>
                <td><b>Removed</b></td>
                <td><?php  if(empty($total_remove)){echo "0";}else{echo $total_remove;};?></td>
                <td><b>Repaired</b></td>
                <td><?php  if(empty($total_repaired)){echo "0";}else{echo $total_repaired;};?></td>
            </tr>
            <tr>
                <td><b>Replaced Unit</b></td>
                <td><?php  if(empty($total_replaceunit)){echo "0";}else{echo $total_replaceunit;};?></td>
                <td><b>Replaced Simcard</b></td>
                <td><?php  if(empty($total_replacesim)){echo "0";}else{echo $total_replacesim;};?></td>    
            </tr> 
            </table>
    </div> 

<div class="panelcontents">
<?php $dg->Render(); ?>
</div>
</div>
<br/>

<?php
include("footer.php");
?>
<script>
Calendar.setup(
{
    inputField : "enddate", // ID of the input field
    ifFormat : "%d-%m-%Y", // the date format
    button : "trigger" // ID of the button
});


Calendar.setup(
{
    inputField : "startdate", // ID of the input field
    ifFormat : "%d-%m-%Y", // the date format
    button : "trigger2" // ID of the button
});

</script>    
