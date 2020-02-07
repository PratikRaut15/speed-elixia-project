<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");

// See if we need to save a new one.
if(IsDistributor())
{

$message="";
$tname="";
$temail="";
$tphone="";
$taddr="";
if(isset($_POST["tname"]) && isset($_POST["tlogin"]) && isset($_POST["tpassword"]))
{
    
    $db = new DatabaseManager();
    $tname = GetSafeValueString($_POST["tname"], "string");
    $tphone = GetSafeValueString($_POST["tphone"], "string");
    $temail = GetSafeValueString($_POST["temail"], "string");
    $taddr = GetSafeValueString($_POST["taddr"], "string");
    $tpassword = GetSafeValueString($_POST["tpassword"], "string");
    $tlogin = GetSafeValueString($_POST["tlogin"], "string");
    $role ="Dealer";
    $dist_id = $_SESSION['sessionteamid'];
    $sql = sprintf("Select * from ".DB_PARENT.".`team` where username='%s'",$tlogin);
    $db->executeQuery($sql);
    if(empty($tname) || empty($temail) || empty($tphone) || empty($tlogin)|| empty($tpassword)){
        $message="Please filled all mandatory fields.";
    }elseif(!filter_var($temail, FILTER_VALIDATE_EMAIL))
    {
        $message="Please enter valid email.";
    }else{
            if($db->get_affectedRows()>0){
                $message="That Username is already taken, please choose another.";
            }
            else{
                $sql = sprintf("INSERT INTO ".DB_PARENT.".`team` (
                `name` ,
                `phone` ,
                `email` ,
                `address` ,
                `role` ,    
                `username` ,
                `password`,
                `distributor_id`
                )
                VALUES (
                 '%s', '%s', '%s','%s', '%s', '%s', '%s','%s'
                );",$tname,$tphone,$temail,$taddr,$role,$tlogin,$tpassword,$dist_id);
                $db->executeQuery($sql);
            }
        }
    }

$db = new DatabaseManager();
//echo $SQL = sprintf("SELECT team.teamid,team.name,team.phone,team.email,team.address,count(unit.teamid) as device_qty, count(simcard.teamid) as sim_qty,team.role
///FROM team left join unit on team.teamid = unit.teamid AND unit.trans_statusid IN (1,2,4,9,18,3,17,20) left join simcard on team.teamid = simcard.teamid AND
//simcard.trans_statusid IN (11,19,12,15,21) where team.`role`='Dealer' AND team.`distributor_id`= ".$_SESSION['sessionteamid']." group by team.teamid ORDER BY team.`name` asc");
$SQL= sprintf("SELECT team.teamid,team.name,team.phone,team.email,team.address,count(unit.teamid) as device_qty,team.role FROM ".DB_PARENT.".team left join unit on team.teamid = unit.teamid AND unit.trans_statusid IN (1,2,4,9,18,3,17,20) where team.`role`='Dealer' AND team.`distributor_id`= ".$_SESSION['sessionteamid']." group by team.teamid ORDER BY team.`name` asc");
$db->executeQuery($SQL);

$dg = new datagrid( $db->getQueryResult());
$dg->AddAction("View/Edit", "../../images/edit.png", "modifydealer.php?sid=%d");
$dg->AddColumn("Name", "name");
$dg->AddColumn("Phone", "phone");
$dg->AddColumn("Email", "email");
$dg->AddColumn("Address", "address");
$dg->AddColumn("Device Qty", "device_qty");
//$dg->AddColumn("Sim Qty", "sim_qty");
$dg->AddAction("View", "../../images/unit.png", "unitdetails.php?tid=%d");
$dg->SetNoDataMessage("No Dealer");
$dg->AddIdColumn("teamid");
include("header.php");
?>
<div class="panel">
   
<div class="paneltitle" align="center">Add New Dealer</div>
<div class="panelcontents">
    <form method="post" action="dealers.php">
<?php if(!empty($message)){echo "<span style='color:red; font-size:11px;'>".$message."</span>";}?>
<table width="100%">
    <tr>
        <td>Name <span style="color:red;">*</span></td><td><input id="tname" name = "tname" value="<?php echo $tname;?>" type="text"></td>
    </tr>
    <tr>
    <td>Phone <span style="color:red;">*</span></td><td><input name = "tphone" onkeypress="return onlyNos(event,this);" maxlength="12" value="<?php echo $tphone;?>"  type="text"></td>
    </tr>
    <tr>
        <td>Email <span style="color:red;">*</span></td><td><input name = "temail" value="<?php echo $temail;?>" type="text"></td>
    </tr>
    <tr>
    <td>Address</td><td><textarea name="taddr" style="width:150px;"><?php echo $addr;?></textarea></td>
    </tr>
    <tr>
    <td>Login <span style="color:red;">*</span></td><td><input name = "tlogin" type="text"></td>
    </tr>
    <tr>
    <td>Password <span style="color:red;">*</span></td><td><input name = "tpassword" type="password"></td>
    </tr>
</table>
<input type="submit" id="submitpros" value="Save New Dealer"/>
</form>
</div>
</div>
<br/>
<div class="panel">
<div class="paneltitle" align="center">Dealers</div>
<div class="panelcontents">
<?php $dg->Render(); ?>
</div>
</div>
<br/>
<?php
include("footer.php");
}
?>
<script>
function onlyNos(e, t) {
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
    catch (err) {
        alert(err.Description);
    }
} 
</script>