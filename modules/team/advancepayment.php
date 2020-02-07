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



class testing{};

$today = date("Y-m-d H:i:s");
$amount="";
$paidto="";
$remark="";
$given_by = $_SESSION['sessionteamid'];

if(isset($_POST["submitadvpay"]))
{
    $db = new DatabaseManager();
    $amount = GetSafeValueString($_POST["amount"], "string");
    $paidto = GetSafeValueString($_POST["uteamid"], "string");
    $remark = GetSafeValueString($_POST["remark"], "string");
   
    if($amount==""){
        $message="Please fill mandatory fields";
    }else if($paidto=='0'){
        $message="Please fill mandatory fields";
    }
    else
    {
        $sql = sprintf("INSERT INTO ".DB_PARENT.".`cash_received` (
        `amount` ,
        `given_by` ,
        `received_by` ,
        `remarks` ,
        `timestamp`,
        `advp_status`
        )
        VALUES (
        '%d','%d', '%d', '%s','%s','%d');",$amount,$given_by,$paidto,$remark,Sanitise::DateTime($today),'1');
        $db->executeQuery($sql);
    }
}
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT  t.name as givenname,t1.name as receivedname, cr.amount,cr.given_by,cr.received_by,cr.remarks,cr.timestamp FROM ".DB_PARENT.".cash_received cr INNER JOIN ".DB_PARENT.".team t ON cr.given_by = t.teamid INNER JOIN ".DB_PARENT.".team t1 ON cr.received_by = t1.teamid where cr.advp_status=1");
    $db->executeQuery($SQL);
  
   function teammembers_running_bal($id){
    $claimant = $id;
    $db = new DatabaseManager();
   $sql = sprintf("select sum(amount)as  vamt from ".DB_PARENT.".voucher where claimant=".$claimant);
    $db->executeQuery($sql);
      while ($row = $db->get_nextRow())
    {
         $vamt = $row["vamt"];
         
    }   
    $sql = sprintf("select distinct(voucherid) from ".DB_PARENT.".voucher where claimant=".$claimant." AND ispaid IN(1,2)");
    $db->executeQuery($sql);
    $vids = array();
    while ($row = $db->get_nextRow())
    {
         $vids[] = $row["voucherid"];
    }
    $vids = implode(",", $vids);
    if(!empty($vids)){
     $sql = sprintf("select sum(pay_amount) as paid from ".DB_PARENT.".voucher_payment where voucher_id IN(".$vids.")");
    $db->executeQuery($sql);
    $vids = array();
    while ($row = $db->get_nextRow())
    {
         $paid = $row["paid"];
    }
    }
    
   // echo $paid."-".$vamt;
    $running_bal = $paid-$vamt;
    
        return $running_bal;
    
} 
$x = 0;
$users = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $x++;
        $user = new testing();
        $user->x = $x;
        $user->amount = $row['amount'];
        $user->receivedname = $row['receivedname'];
        $user->running_bal = teammembers_running_bal($row['received_by']);
        $user->givenname = $row['givenname'];
        $user->remarks = $row['remarks'];
        $user->timestamp = $row['timestamp'];
        $user->received_by = $row['received_by'];
        $users[] = $user;
    }
}
$ad = new objectdatagrid( $users );
$ad->AddColumn("Sr.No", "x");
$ad->AddColumn("Advanced Paid", "amount");
$ad->AddColumn("Paid to","receivedname");
$ad->AddColumn("Running Balance","running_bal");
$ad->AddColumn("Given By", "givenname");
$ad->AddColumn("Remarks", "remarks");
$ad->AddColumn("Date / Time", "timestamp");
$ad->SetNoDataMessage("No Customer");
$ad->AddIdColumn("received_by");


include("header.php");

$SQL = sprintf("SELECT team.teamid, team.name FROM ".DB_PARENT.".team where team.member_type=1  ORDER BY name asc");
$db->executeQuery($SQL);
$team_allot_array = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $team = new testing();
        $team->teamid  = $row["teamid"];
        $team->name = $row["name"];        
        $team_allot_array[] = $team;        
    }    
}
?>
<div class="panel">
   
<div class="paneltitle" align="center">Advanced Payment</div>
<div class="panelcontents">
    <form method="post" action="advancepayment.php">
<?php if(!empty($message)){echo "<span style='color:red; font-size:11px;'>".$message."</span>";}?>

<?php //echo GetLoginUser(); ?>
        <table width="100%">
    <tr>
        <td>Amount <span style="color:red;">*</span></td><td><input id="amount" name="amount" value="<?php echo $amount;?>" type="text" onkeypress="return onlyNos(event,this);"></td>
    </tr>
    <tr>
        <td>Pay to <span style="color:red;">*</span> </td>
        <td><select name="uteamid" id="uteamid" onChange="pullunit();">
                <option value="0">Select an Elixir</option>
                <?php
                foreach($team_allot_array as $thisteam)
                {
                ?>
                <option value="<?php echo($thisteam->teamid); ?>"><?php echo($thisteam->name); ?></option>
                <?php
                }
                ?>
            </select>
        </td>
        </tr>
   
    <tr>
    <td>Remark </td><td><textarea name="remark" style="width:150px;"><?php echo $remark;?></textarea></td>
    </tr>
   
</table>
<input type="submit" id="submitpros" name="submitadvpay" value="Submit"/>
</form>
</div>
</div>
<br/>
<div class="panel">
<div class="paneltitle" align="center">Advanced Payment Details</div>
<div class="panelcontents">
<?php $ad->Render(); ?>
</div>
</div>
<br/>
<?php
include("footer.php");

?>
<script>
function onlyNos(e,t){
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