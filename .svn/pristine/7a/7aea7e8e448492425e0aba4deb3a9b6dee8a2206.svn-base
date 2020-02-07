<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/Date.php");


class testing{
    
}
date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$amount="";
$given_by="";
$remark="";
$received_by = $_SESSION['sessionteamid'];
$message="";
if(isset($_POST["submitpros"])){
//if(isset($_POST["amount"]) && isset($_POST["uteamid"]))
//{
  
    $db = new DatabaseManager();
    $amount = GetSafeValueString($_POST["amount"], "string");
    $given_by = GetSafeValueString($_POST["uteamid"], "string");
    $remark = GetSafeValueString($_POST["remark"], "string");
   
    if($given_by=="0" && $amount==""){
        $message="Please fill mandatory fields";
    }
    else
    {
        $sql = sprintf("INSERT INTO ".DB_PARENT.".`cash_received` (
        `amount` ,
        `given_by` ,
        `received_by` ,
        `remarks` ,
        `timestamp` 
        )
        VALUES (
        '%d','%d', '%d', '%s','%s');",$amount,$given_by,$received_by,$remark,Sanitise::DateTime($today));
        $db->executeQuery($sql);
    }
}
$db = new DatabaseManager();
$SQL = sprintf("SELECT t.name,cr.amount,cr.given_by,cr.received_by,cr.remarks,cr.timestamp FROM ".DB_PARENT.".cash_received cr INNER JOIN ".DB_PARENT.".team t ON cr.given_by = t.teamid where cr.advp_status=0");
$db->executeQuery($SQL);

$dg = new datagrid( $db->getQueryResult());
//$dg->AddAction("View/Edit", "../../images/edit.png", "modifydealer.php?sid=%d");
$dg->AddColumn("Amount", "amount");
$dg->AddColumn("Given By", "name");
$dg->AddColumn("Remarks", "remarks");
$dg->AddColumn("Date / Time", "timestamp");
$dg->SetNoDataMessage("No Cash Received.");
//$dg->AddIdColumn("uid");
include("header.php");

$SQL = sprintf("SELECT team.teamid, team.name FROM ".DB_PARENT.".team ORDER BY name asc");
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
   
<div class="paneltitle" align="center">Received Cash</div>
<div class="panelcontents">
    <form method="post" action="receivedcash.php" onsubmit="return ValidateForm(); return false;">
<?php if(!empty($message)){echo "<span style='color:red; font-size:11px;'>".$message."</span>";}?>

<?php //echo GetLoginUser(); ?>
        <table width="100%">
    <tr>
        <td>Amount <span style="color:red;">*</span></td><td><input id="amount" name="amount" value="<?php echo $amount;?>" type="text" onkeypress="return onlyNos(event,this);"></td>
    </tr>
    <tr>
        <td>Given By <span style="color:red;">*</span> </td>
        <td><select name="uteamid" id="uteamid" onChange="pullunit();">
                <option value="0">Select an Elixir</option>
                <option value="1">Sanket Sheth</option>
                <option value="2">Ankit Zatakia</option>
                <?php
                /*foreach($team_allot_array as $thisteam)
                {
                ?>
                <option value="<?php echo($thisteam->teamid);?>"><?php echo($thisteam->name); ?></option>
                <?php
                }*/
                ?>
            </select>
        </td>
        </tr>
   
    <tr>
    <td>Remark </td><td><textarea name="remark" style="width:150px;"><?php echo $remark;?></textarea></td>
    </tr>
   
</table>
<input type="submit" id="submitpros" name="submitpros" value="Received Cash"/>
</form>
</div>
</div>
<br/>
<div class="panel">
<div class="paneltitle" align="center">Received Cash Details</div>
<div class="panelcontents">
<?php $dg->Render(); ?>
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

function ValidateForm(){
    var pamount = $("#amount").val();
    var uteamid = $("#uteamid").val();
    
    if(pamount==""){
        alert("Please enter some amount.");
        return false;
    }else if(uteamid==0){
        alert("Please select name.");
        return false;
    }else{
    $("#formpayment").submit();
    }
}


</script>