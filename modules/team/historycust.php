<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");

class unit{
    
}
//Datagtrid

$id = GetSafeValueString( isset($_GET["cid"])?$_GET["cid"]:$_POST["cid"], "long");

include("header.php");
    
$db = new DatabaseManager();
$SQL = sprintf("SELECT th.transaction, team.name, th.trans_time, th.thid FROM ".DB_PARENT.".trans_history th INNER JOIN ".DB_PARENT.".team ON team.teamid = th.teamid WHERE th.type=2 AND customerno = %d ORDER BY trans_time DESC",$id);

$db->executeQuery($SQL);
$history = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $device = new unit();
        $device->transaction = $row["transaction"];        
        $device->name = $row["name"];                
        $device->trans_time = date("d-m-Y H:i",strtotime($row["trans_time"]));                        
        $device->thid = $row["thid"];
        $history[] = $device;        
    }    
}

$dg = new objectdatagrid( $history );
$dg->AddColumn("Transaction", "transaction");
$dg->AddColumn("Date", "trans_time");
$dg->AddColumn("Done by", "name");
$dg->SetNoDataMessage("No History");
$dg->AddIdColumn("thid");

$SQL = sprintf("SELECT customername, customercompany FROM ".DB_PARENT.".customer WHERE customerno = %d",$id);

$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $customer = $row["customername"]. " [ ".$row["customercompany"]. " ]";
    }    
}


?>
<br/>
<div class="panel">
<div class="paneltitle" align="center">History for <?php echo $customer; ?></div>
<div class="panelcontents">
<?php $dg->Render(); ?>
</div>

</div>

<?php
include("footer.php");
?>