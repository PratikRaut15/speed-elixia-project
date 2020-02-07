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

$id = GetSafeValueString( isset($_GET["id"])?$_GET["id"]:$_POST["id"], "long");

include("header.php");
    
$db = new DatabaseManager();
$SQL = sprintf("SELECT th.customerno, th.allot_teamid, team_allot_teamid.name as allot_name, t.status, th.transaction, team_teamid.name, th.trans_time, th.thid FROM ".DB_PARENT.".trans_history th LEFT OUTER JOIN ".DB_PARENT.".trans_status t ON t.id = th.statusid INNER JOIN simcard ON simcard.id = th.simcard_id INNER JOIN ".DB_PARENT.".team team_teamid ON team_teamid.teamid = th.teamid LEFT OUTER JOIN ".DB_PARENT.".team team_allot_teamid ON team_allot_teamid.teamid = th.allot_teamid WHERE th.type=1 AND simcard_id = %d ORDER BY trans_time DESC",$id);

$db->executeQuery($SQL);
$history = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $device = new unit();        
        if($row["allot_teamid"] == 0)
        {
            $device->status = $row["status"];
        }
        else
        {
            $device->status = $row["status"].": ".$row["allot_name"];            
        }
        $device->customerno = $row["customerno"];
        $device->transaction = $row["transaction"];        
        $device->name = $row["name"];                
        $device->trans_time = date("d-m-Y H:i",strtotime($row["trans_time"]));                        
        $device->thid = $row["thid"];
        $history[] = $device;        
    }    
}

$dg = new objectdatagrid( $history );
$dg->AddColumn("Transaction", "transaction");
$dg->AddColumn("Status", "status");
$dg->AddColumn("Customer #", "customerno");
$dg->AddColumn("Date", "trans_time");
$dg->AddColumn("Done by", "name");
$dg->SetNoDataMessage("No History");
$dg->AddIdColumn("thid");

$SQL = sprintf("SELECT simcard.simcardno,simcard.trans_statusid,vendor.vendorname FROM simcard INNER JOIN vendor ON vendor.id = simcard.vendorid WHERE simcard.id = %d",$id);

$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $mobileno=$row['simcardno'];
        $simcardno = $row["simcardno"]. " [ ".$row["vendorname"]. " ]";
        $old_status=$row['trans_statusid'];
    }    
}

$SQL3 = "SELECT id,status FROM " . DB_PARENT . ".trans_status WHERE type=1";
$db->executeQuery($SQL3);
$status = array();
if ($db->get_rowCount() > 0) {
    $detail = array();
    while ($row3 = $db->get_nextRow()) {
        $detail['id'] = $row3['id'];
        $detail['status'] = $row3['status'];
        $status[] = $detail;
    }
}

if (isset($_POST['updateStatus'])) {
    $today=date("Y-m-d H:i:s");
    $teamid = $_SESSION['sessionteamid'];
    $uid = $_REQUEST['id'];
    $new_status = $_POST['status'];
//    echo $old_status."=>".$uid."=>".$mobileno; die();
    $SQL4 = sprintf("UPDATE " . DB_PARENT . ".simcard SET trans_statusid=%d WHERE simcardno=%d", $new_status, $mobileno);
    $db->executeQuery($SQL4);
    $SQL4 = sprintf("INSERT INTO " . DB_PARENT . ".status_change_log(id,old_status,new_status,type,updated_on,updated_by) VALUES(%d,%d,%d,1,'%s',%d)", $mobileno, $old_status,$new_status,$today,$teamid);
    $db->executeQuery($SQL4);
}

//-------Log history of status changed---------
$SQL6 = sprintf("SELECT scl_id,old_status,new_status,updated_on,updated_by FROM status_change_log WHERE id = %d AND type=1", $mobileno);
$db->executeQuery($SQL6);
$statuslog=array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $data=new stdClass();
        $data->scl_id=$row['scl_id'];
        $data->old_status=$row['old_status'];
        $data->new_status=$row['new_status'];
        $data->updated_on=$row['updated_on'];
        $data->updated_by=$row['updated_by'];
        $statuslog[] = $data;
    }
}

//-----datagrid parameters for display type of error-----------------

$dr = new objectdatagrid($statuslog);
$dr->AddColumn("Old Status", "old_status");
$dr->AddColumn("New Status", "new_status");
$dr->AddColumn("Updated On", "updated_on");
$dr->AddColumn("Updated By", "updated_by");
$dr->SetNoDataMessage("No Log Details");
$dr->AddIdColumn("scl_id");
?>
<br/>

<?php if ($old_status != 13 && $old_status != 14) { ?>
<div class="panel">
    <div class="paneltitle" align="center">Change Status of Simcard # <?php echo $simcardno; ?></div>
    <div class="panelcontents" align="center">
        <form method="POST" action="historysim.php?id=<?php echo $id; ?>">
            Status change to <select name="status">
                <?php
                foreach ($status as $data) {
                    echo "<option value=" . $data['id'] . ">" . $data['status'] . "</option>";
                }
                ?> </select><br>

            <input type="submit" value="Update" name="updateStatus">
            </select>
        </form>
    </div>
</div>
<?php
}
?>
<br/><br/>
<div class="panel">
<div class="paneltitle" align="center">History for Simcard # <?php echo $simcardno; ?></div>
<div class="panelcontents">
<?php $dg->Render(); ?>
</div>

</div>
<br/>
<div class="panel">
    <div class="paneltitle" align="center">History for Status change of # <?php echo $simcardno; ?></div>
    <div class="panelcontents">
        <?php $dr->Render(); ?>
    </div>

</div>
<?php
include("footer.php");
?>