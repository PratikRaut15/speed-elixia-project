<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");
include("header.php");
class unit{

}
//Datagtrid


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
    $SQL = sprintf("SELECT simcard.id, unit.unitno, trans_status.status, simcard.simcardno FROM simcard INNER JOIN ".DB_PARENT.".trans_status ON simcard.trans_statusid = trans_status.id LEFT OUTER JOIN devices ON devices.simcardid = simcard.id LEFT OUTER JOIN unit ON unit.uid = devices.uid WHERE simcard.trans_statusid = 0 AND unit.teamid = 0 ORDER BY simcard.trans_statusid ASC");
}
elseif($teamid == 0)
{
    $SQL = sprintf("SELECT simcard.id, unit.unitno, trans_status.status, simcard.simcardno FROM simcard INNER JOIN ".DB_PARENT.".trans_status ON simcard.trans_statusid = trans_status.id LEFT OUTER JOIN devices ON devices.simcardid = simcard.id LEFT OUTER JOIN unit ON unit.uid = devices.uid WHERE simcard.trans_statusid IN (11,12,15,19,21) AND simcard.teamid = 0 ORDER BY simcard.trans_statusid ASC");
}
else
{
    $SQL = sprintf("SELECT simcard.id, unit.unitno, trans_status.status, simcard.simcardno FROM simcard INNER JOIN ".DB_PARENT.".trans_status ON simcard.trans_statusid = trans_status.id LEFT OUTER JOIN devices ON devices.simcardid = simcard.id LEFT OUTER JOIN unit ON unit.uid = devices.uid WHERE simcard.trans_statusid IN (19,21) AND simcard.teamid = %d ORDER BY simcard.trans_statusid ASC",$teamid);
}

$db->executeQuery($SQL);
$history = Array();
$x = 1;
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $device = new unit();
        $device->simcardno = $row["simcardno"];
        $device->status = $row["status"];
        $device->id = $row["id"];
        if($row["unitno"] == null)
        {
            $device->unitno = "No Unit";
        }
        else
        {
            $device->unitno = $row["unitno"];
        }
        $device->x = $x;
        $x++;
        $history[] = $device;
    }
}

$dg = new objectdatagrid( $history );
$dg->AddColumn("Sr No.", "x");
$dg->AddColumn("Simcard No.", "simcardno");
$dg->AddColumn("Status", "status");
$dg->AddColumn("Associated Unit No.", "unitno");
$dg->SetNoDataMessage("No Details");
$dg->AddIdColumn("uid");

?>
<br/>
<div class="panel">
<div class="paneltitle" align="center">Simcard Details for <?php echo ($teamname); ?></div>
<div class="panelcontents">
<?php $dg->Render(); ?>
</div>

</div>

<?php
include("footer.php");
?>