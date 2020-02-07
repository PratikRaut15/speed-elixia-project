<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/Date.php");
include_once("../../lib/components/gui/objectdatagrid.php");


// See if we need to save a new one.
class repair{
    
}
$db = new DatabaseManager();
date_default_timezone_set("Asia/Calcutta");                             
$today = date("Y-m-d H:i:s");
if(IsRepair())
{
  $SQL = sprintf("SELECT  u.uid, u.unitno, u.repairtat, u.comments, u.issue, u.comments_repair, ts.status FROM unit as u INNER JOIN ".DB_PARENT.".trans_status as ts ON u.trans_statusid = ts.id where u.trans_statusid = 7 ORDER BY `repairtat` DESC");
  $db->executeQuery($SQL);
  $repairdetails= array();
   if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $x++;
        $units = new repair();
        $units->uid = $row['uid'];
        $units->unitno = $row['unitno'];
        $units->repairtat = $row['repairtat'];
        $units->comments = $row['comments'];
        $units->comments_repair = $row['comments_repair'];
        $units->issue = $row["issue"];
        
        $units->x = $x;
        $repairdetails[] = $units;
    }
    $count = count($repairdetails);

}

$dg = new objectdatagrid($repairdetails);
$dg->AddColumn("Sr.No.", "x");
$dg->AddColumn("Unit No.", "unitno");
$dg->AddColumn("Due Date", "repairtat");
$dg->AddColumn("Comments From Elixia", "comments");
$dg->AddColumn("Comments By repairer", "comments_repair");
$dg->AddColumn("Issue", "issue");
$dg->AddRightAction("Edit", "../../images/unit.png", "add_comment_repair.php?uid=%d");
$dg->SetNoDataMessage("No Repair");
$dg->AddIdColumn("uid");
include("header.php");
?>
<br/>

<div class="panel">
<div class="panelcontents" align="right"><b>Under Repair :</b><?php echo $count; ?></div>    
<div class="paneltitle" align="center">Repair Details</div>
<div class="panelcontents">
<?php $dg->Render(); ?>
</div>
</div>
<br/>
<?php
include("footer.php");
}
?>
