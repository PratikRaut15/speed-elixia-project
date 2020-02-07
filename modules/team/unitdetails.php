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
include("header.php");

$teamid = GetSafeValueString( isset($_GET["tid"])?$_GET["tid"]:$_POST["tid"], "long");


    
$db = new DatabaseManager();

if($teamid == 0)
{
    $teamname = "Elixia Tech";
}
elseif($teamid == -1)
{
    $teamname = "Repair";
}
else
{
    $SQL = sprintf("SELECT name FROM ".DB_PARENT.".team WHERE teamid = %d",$teamid);

    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow())
        {
            $teamname = $row["name"];
        }    
    }
}

if($teamid == -1)
{
    $SQL = sprintf("SELECT unit.uid, unit.unitno, trans_status.status, simcard.simcardno FROM unit INNER JOIN ".DB_PARENT.".trans_status ON unit.trans_statusid = trans_status.id LEFT OUTER JOIN devices ON devices.uid = unit.uid LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid WHERE unit.trans_statusid = 7 AND unit.teamid = 0 ORDER BY unit.trans_statusid ASC");    
}
elseif($teamid == 0)
{
    $SQL = sprintf("SELECT unit.uid, unit.unitno, trans_status.status, simcard.simcardno FROM unit INNER JOIN ".DB_PARENT.".trans_status ON unit.trans_statusid = trans_status.id LEFT OUTER JOIN devices ON devices.uid = unit.uid LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid WHERE unit.trans_statusid IN (1,2,4,9,3,17,20,18) AND unit.teamid = 0 ORDER BY unit.trans_statusid ASC");        
}
else
{
    $SQL = sprintf("SELECT unit.uid, unit.unitno, trans_status.status, simcard.simcardno FROM unit INNER JOIN ".DB_PARENT.".trans_status ON unit.trans_statusid = trans_status.id LEFT OUTER JOIN devices ON devices.uid = unit.uid LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid WHERE unit.teamid = %d ORDER BY unit.trans_statusid ASC",$teamid);
}
$db->executeQuery($SQL);
$history = Array();
$x = 1;
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $device = new unit();
        $device->unitno = $row["unitno"];
        $device->status = $row["status"];
        $device->uid = $row["uid"];
        if($row["simcardno"] == null)
        {
            $device->simcardno = "No Simcard";
        }
        else
        {
            $device->simcardno = $row["simcardno"];        
        }
        $device->x = $x;
        $x++;
        $history[] = $device;        
    }    
}

$dg = new objectdatagrid( $history );
$dg->AddColumn("Sr No.", "x");
$dg->AddColumn("Unit No.", "unitno");
$dg->AddColumn("Status", "status");
$dg->AddColumn("Associated Simcard No.", "simcardno");
$dg->SetNoDataMessage("No Details");
$dg->AddIdColumn("uid");

?>
<br/>
<div class="panel">
<div class="paneltitle" align="center">Unit Details for <?php echo ($teamname); ?></div>
<div class="panelcontents">
<?php $dg->Render(); ?>
</div>

</div>

<?php
include("footer.php");
?>