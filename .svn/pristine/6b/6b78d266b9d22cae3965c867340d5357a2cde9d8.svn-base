<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/Date.php");


class testing{
    
}
date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$todaydate =date("Y-m-d");


$db = new DatabaseManager();
$SQL = sprintf("SELECT team.teamid, team.name FROM ".DB_PARENT.".team");
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
$pending = $_GET['pending'];
if($pending=='1'){
$message="";    
$voucherid = $_GET['voucherid'];
    if(isset($_POST["amountsubmit"]))
    {
        $advancepayment = $_POST["advancepayment"];
        $adv_claimant = $_POST["claimant"];
        $advpaid = $_POST["advpaid"];
        $voucherid = $_POST["voucherid"];
        $vamount = $_POST["vamount"];
        $vamtremain = $_POST["vamtremain"];
        $pamount = $_POST["pamount"];
        $givenby = GetLoggedInUserId();
        $remark = $_POST["remark"];
        $voucherdate =$_POST["voucherdate"];
        $doneby = GetLoggedInUserId();
                $db = new DatabaseManager();
               $SQL = sprintf("select * from ".DB_PARENT.".cash_received where advp_status=0");
                $db->executeQuery($SQL);
                $cashrecv_count = $db->get_rowCount();
                
                $sql = sprintf("select * from ".DB_PARENT.".`cash_received` where `advp_status`='1' AND `received_by`=".$adv_claimant);  //adv payment count
                $db->executeQuery($sql);
                $adv_count = $db->get_rowCount();
                
                $SQL = sprintf("select * from ".DB_PARENT.".voucher_payment where voucher_id=".$voucherid);
                $db->executeQuery($SQL);
                $voucher_payment_count = $db->get_rowCount();
                
                if($adv_count =='0' && $advpaid=='1'){
                    $message="<span style='color:red; font-size:12px;'>User don't have advanced balance.</span>";  
                }else if($cashrecv_count =='0' && $advpaid!='1'){
                    $message="<span style='color:red; font-size:12px;'>Company don't have balance.</span>";  
                }else if(empty($pamount)){
                    $message ="<span style='color:red; font-size:12px;'>Payment should not be blank.</span>";
                }else if($pamount>$vamount){
                    $message ="<span style='color:red; font-size:12px;'>Amount should not be exceed than voucher amount.</span>";
                }else if($pamount>$vamtremain){
                    $message ="<span style='color:red; font-size:12px;'>Amount should not be exceed than remaining voucher amount.</span>";
                }else if(!empty($advancepayment) && $advancepayment < $pamount && $advpaid=='1'){
                    $message ="<span style='color:red; font-size:12px;'>Advance Payment is less than enter amount.</span>";
                }
                else if($givenby=="0")
                {
                    $message ="Please fill mandatory fields";
                }
                if(empty($message))
                {
                    if($pamount == $vamount && $advpaid!='1')
                    {
                        $sql = sprintf("INSERT INTO ".DB_PARENT.".`voucher_payment` (
                        `voucher_id`,
                        `voucher_amount`,
                        `pay_amount`,
                        `given_by`,
                        `voucher_date`,
                        `payment_date`,
                        `remarks`,
                        `done_by`,
                        `timestamp`
                          )
                          VALUES (
                          '%d','%d','%d', '%d', '%s','%s','%s','%d','%s');",$voucherid,$vamount,$pamount,$givenby,$voucherdate,$todaydate,$remark,$doneby,Sanitise::DateTime($today));
                        $db->executeQuery($sql);
                        if($vamount == $pamount)
                        {
                            $status=1; //compelete
                        }
                        $SQL = sprintf("UPDATE ".DB_PARENT.".voucher SET ispaid=".$status." where voucherid=$voucherid");    
                        $db->executeQuery($SQL);
                        header("Location: payment.php");
                    }
                    if($pamount < $vamount && $advpaid!='1')
                    {
                        if($pamount == $vamtremain){
                            $status=1; //complete paid
                            $sql = sprintf("INSERT INTO ".DB_PARENT.".`voucher_payment` (
                            `voucher_id`,
                            `voucher_amount`,
                            `pay_amount`,
                            `given_by`,
                            `voucher_date`,
                            `payment_date`,
                            `remarks`,
                            `done_by`,
                            `timestamp`
                              )
                              VALUES (
                              '%d','%d','%d', '%d', '%s','%s','%s','%d','%s');",$voucherid,$vamount,$pamount,$givenby,$voucherdate,$todaydate,$remark,$doneby,Sanitise::DateTime($today));
                            $db->executeQuery($sql);
                            $SQL = sprintf("UPDATE ".DB_PARENT.".voucher SET ispaid=".$status." where voucherid=$voucherid");    
                            $db->executeQuery($SQL);
                             header("Location: payment.php"); 
                        }
                        else
                        {
                            $status=2; //partial
                            $sql = sprintf("INSERT INTO ".DB_PARENT.".`voucher_payment` (
                            `voucher_id`,
                            `voucher_amount`,
                            `pay_amount`,
                            `given_by`,
                            `voucher_date`,
                            `payment_date`,
                            `remarks`,
                            `done_by`,
                            `timestamp`
                              )
                              VALUES (
                              '%d','%d','%d', '%d', '%s','%s','%s','%d','%s');",$voucherid,$vamount,$pamount,$givenby,$voucherdate,$todaydate,$remark,$doneby,Sanitise::DateTime($today));
                            $db->executeQuery($sql);
                            $SQL = sprintf("UPDATE ".DB_PARENT.".voucher SET ispaid=".$status." where voucherid=$voucherid");    
                            $db->executeQuery($SQL);
                             header("Location: payment.php");
                        }
                    }
                    if($pamount <= $advancepayment && $advpaid=='1' && $vamtremain<= $advancepayment)
                    {
                         if($pamount == $vamtremain ){
                            $status=1; //complete paid
                            $sql = sprintf("INSERT INTO ".DB_PARENT.".`voucher_payment` (
                            `voucher_id`,
                            `voucher_amount`,
                            `pay_amount`,
                            `given_by`,
                            `voucher_date`,
                            `payment_date`,
                            `remarks`,
                            `done_by`,
                            `timestamp`,
                            `advpaid`
                              )
                              VALUES (
                              '%d','%d','%d', '%d', '%s','%s','%s','%d','%s','%d');",$voucherid,$vamount,$pamount,$givenby,$voucherdate,$todaydate,$remark,$doneby,Sanitise::DateTime($today),'1');
                            $db->executeQuery($sql);
                            $SQL = sprintf("UPDATE ".DB_PARENT.".voucher SET ispaid=".$status." where voucherid=$voucherid");    
                            $db->executeQuery($SQL);
                             header("Location: payment.php"); 
                        }
                        else
                        {
                            $status=2; //partial
                            $sql = sprintf("INSERT INTO ".DB_PARENT.".`voucher_payment` (
                            `voucher_id`,
                            `voucher_amount`,
                            `pay_amount`,
                            `given_by`,
                            `voucher_date`,
                            `payment_date`,
                            `remarks`,
                            `done_by`,
                            `timestamp`,
                            `advpaid`
                              )
                              VALUES (
                              '%d','%d','%d', '%d', '%s','%s','%s','%d','%s','%d');",$voucherid,$vamount,$pamount,$givenby,$voucherdate,$todaydate,$remark,$doneby,Sanitise::DateTime($today),'1');
                             $db->executeQuery($sql);
                            $SQL = sprintf("UPDATE ".DB_PARENT.".voucher SET ispaid=".$status." where voucherid=$voucherid");    
                            $db->executeQuery($SQL);
                            header("Location: payment.php");
                        }
                    }
                    if($advancepayment = $pamount && $vamtremain > $advancepayment && $vamount > $advancepayment && $advpaid=='1')
                    {
                        $status=2; //partial
                        $sql = sprintf("INSERT INTO ".DB_PARENT.".`voucher_payment` (
                        `voucher_id`,
                        `voucher_amount`,
                        `pay_amount`,
                        `given_by`,
                        `voucher_date`,
                        `payment_date`,
                        `remarks`,
                        `done_by`,
                        `timestamp`,
                        `advpaid`
                          )
                          VALUES (
                          '%d','%d','%d', '%d', '%s','%s','%s','%d','%s','%d');",$voucherid,$vamount,$pamount,$givenby,$voucherdate,$todaydate,$remark,$doneby,Sanitise::DateTime($today),'1');
                         $db->executeQuery($sql);
                        $SQL = sprintf("UPDATE ".DB_PARENT.".voucher SET ispaid=".$status." where voucherid=$voucherid");    
                        $db->executeQuery($SQL);
                        header("Location: payment.php");
                    }
                
                }
    }
    include("header.php");
?>
<div class="panel">
    <div class="paneltitle" align="center">Payment</div>
    <div class="panelcontents">
            <?php
               $db = new DatabaseManager();
 $SQL = sprintf("select voucher.ispaid, voucher.remarks, sum(voucher.amount) as total_amount,team.name, voucher.claimdate, voucher.claimant 
from ".DB_PARENT.".voucher left join ".DB_PARENT.".team on team.teamid = voucher.claimant where voucher.voucherid=".$voucherid);    
                $db->executeQuery($SQL);
                while ($row = $db->get_nextRow())   
                {
                    $total_amt  = $row["total_amount"];
                    $voucherdate = $row["claimdate"];
                    $claimant_name = $row["name"];
                    $ispaid = $row["ispaid"];
                    $claimant =$row["claimant"];
                    $remarks = $row["remarks"];
                }
                $SQL = sprintf("select sum(pay_amount) as payamount from  ".DB_PARENT.".voucher_payment  where voucher_id=".$voucherid);    
                $db->executeQuery($SQL);
                while ($row = $db->get_nextRow())   
                {
                    $payamount =$row["payamount"];
                }
                if($payamount < $total_amt && $payamount!=0){
                    $pa = $total_amt -  $payamount;
                }
                elseif($payamount==$total_amt){
                    $pa = $total_amt -  $payamount;
                }else{
                    $pa = $total_amt;
                }
                
            ?>
       
        <?php
        if(!empty($message)){ echo "<span style='color:red; font-size:12px;'>".$message."</span><br>";}
        ?>
        <div class="span6" > 
            <form name="formpayment" id="formpayment" method="POST" onsubmit="return ValidateForm(); return false;">
            <h3>Payment</h3>
        <table cellpadding="5" cellspacing="5">
            <tr>
                <td>Voucher Id :</td><td><?php echo $voucherid;?> <input type="hidden" name="voucherdate" id="voucherdate" value="<?php echo $voucherdate;?>">
                <input type="hidden" name="voucherid" value="<?php echo $voucherid;?>"></td>
            </tr>
            <tr><td>Voucher Submitted by :</td><td><?php echo $claimant_name;?><input type="hidden" id="claimant" name="claimant" value="<?php echo $claimant;?>"></td></tr>
            <tr><td>Voucher Date :</td><td><?php echo $voucherdate;?></td></tr>
            <tr>
                <?php 
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
                $running_bal = $paid-$vamt;
                ?>
                <td>Claimant Running Balance:</td><td><?= $running_bal; ?></td></tr>
            </tr>
            <tr><td>Voucher Amount :</td><td><?php echo $total_amt;?><input type="hidden" name="vamount" id="vamount" value="<?php echo $total_amt;?>"></td></tr>
            <tr>
                <td>Remaining Voucher Amount :</td>
                <td><?php echo $pa;?><input type="hidden" id="vamtremain" name="vamtremain" value="<?php echo $pa;?>"></td></tr>
            <tr><td>Advanced Paid Balance :</td><td>
                <?php
                $sql = sprintf("select sum(`amount`) as advance_paid from ".DB_PARENT.".`cash_received` where `advp_status`='1' AND `received_by`=".$claimant);
                $db->executeQuery($sql);
                while ($row = $db->get_nextRow())
                {
                     $advance_paid1 = $row["advance_paid"];
                }
                
                $sql = sprintf("select distinct(voucherid) from ".DB_PARENT.".voucher where claimant=".$claimant);
                $db->executeQuery($sql);
                $voucherids = array();
                while ($row = $db->get_nextRow())
                {
                     $voucherids[] = $row["voucherid"];
                }
                $voucheridsall = implode(',', $voucherids);
                if(!empty($voucheridsall)){
                $sql = sprintf("select sum(pay_amount) as paidamt from ".DB_PARENT.".voucher_payment where voucher_id IN($voucheridsall) AND advpaid=1");
                $db->executeQuery($sql);
                    while ($row = $db->get_nextRow())
                    {
                         $paidamt = $row["paidamt"];
                    }
                }else{
                    $paidamt =0;
                }
                echo $totaladvpaid = $advance_paid1-$paidamt;
                ?>
                <input type="hidden" name="advancepayment" id="advancepayment" value="<?= $totaladvpaid;?>">   
                </td></tr>
            <tr><td>Paid From :</td><td>Advance Paid <input type="checkbox" id="advpaid" name="advpaid" value="1"/></td></tr>
            <tr><td>Payment Amount <span style='color:red;'>*</span> :</td><td><input type="text" name="pamount" id="pamount" <?php if($ispaid=='1'){echo "readonly"; }?> value="<?php echo $pa;?>"  onkeypress="return onlyNos(event,this);"></td></tr>
            <tr><td>Remark :</td><td><textarea name="remark" id="remark" <?php if($ispaid=='1'){echo "readonly"; }?>></textarea></td></tr>
            <tr>
                <td>
                    <?php if($ispaid!=='1'){ ?>
                    <input type="submit" name="amountsubmit" value="Submit"/>
                    <?php } ?>
                </td>
                <td>
                    <a href="payment.php">Back</a>
                </td>
            </tr>
        </table>
            </form>
        </div>   
         <div class="clearfix"></div>
         <?php
             $db = new DatabaseManager();
             $sql = sprintf("select v.voucherid,v.voucherdate, c.customercompany, v.amount, ah.headid, ah.headtype,(CASE WHEN v.ispaid = 0 THEN 'Unpaid' WHEN v.ispaid=2 THEN 'P-Paid' ELSE 'Paid' END) as paymentstatus from ".DB_PARENT.".voucher as v INNER JOIN ".DB_PARENT.".account_head as ah on ah.headid = v.headid left join ".DB_PARENT.".customer as c on c.customerno = v.customer where v.claimant = ".$claimant." AND v.voucherid = ".$voucherid." order by v.voucherid desc");
                $db->executeQuery($sql);
                $ds = new datagrid( $db->getQueryResult());
                $ds->AddColumn("Voucher Id","voucherid");
                $ds->AddColumn("Date","voucherdate");
                $ds->AddColumn("Company","customercompany");
                $ds->AddColumn("Head Type","headtype");
                $ds->AddColumn("Voucher Amt","amount");
                $ds->AddColumn("Status","paymentstatus");
                $ds->SetNoDataMessage("No voucher pending.");
                
                $ds->AddIdColumn("voucherid");
            ?>
   </div>
        
        <div class="paneltitle" align="center">Voucher Details </div>
        <div class="panelcontents">
                <?php $ds->Render(); ?>
        </div>     
        
    
</div>
<?php }
else
{ 
    include("header.php");
    $db = new DatabaseManager();
    $SQL = sprintf('SELECT v.voucherid, sum(v.amount)as vamt,t.name,v.claimdate,v.claimant,(CASE WHEN v.ispaid = 0 THEN "Unpaid" WHEN v.ispaid=2 THEN "P-Paid" ELSE "Paid" END)as paymentstatus FROM '.DB_PARENT.'.`voucher` as v left join '.DB_PARENT.'.team as t on t.teamid = v.claimant group by voucherid ORDER BY `uid` desc'); 
    $db->executeQuery($SQL);

    function paidbalance($vid){
        $db = new DatabaseManager();
          $SQL = sprintf("select sum(pay_amount) as payamount from  ".DB_PARENT.".voucher_payment  where voucher_id=".$vid);    
                    $db->executeQuery($SQL);
                    while ($row = $db->get_nextRow())   
                    {
                        $payamount =$row["payamount"];
                    }
                    return $payamount;
    }

$x=0;
$dispdetails = Array();
 if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $x++;
        $user = new testing();
        $user->voucherid = $row['voucherid'];
        $user->vamt = $row['vamt'];
        $user->name = $row['name'];
        $user->claimdate = $row['claimdate'];
        $user->claimant = $row['claimant'];
        $user->paymentstatus = $row['paymentstatus'];
        $user->pay_amount = paidbalance($row['voucherid']);
        $user->x = $x;
        $dispdetails[] = $user;
    }

}
    $dg = new objectdatagrid($dispdetails);
    $dg->AddRightAction("Pay Here", "../../images/edit.png", "payment.php?pending=1&voucherid=%d");
    $dg->AddColumn("Sr. No","x");
    $dg->AddColumn("Voucher Id","voucherid");
    $dg->AddColumn("Voucher Submit By", "name");
    $dg->AddColumn("Voucher Date", "claimdate");
    $dg->AddColumn("Voucher amount", "vamt");
    $dg->AddColumn("Paid Amount", "pay_amount");
    $dg->AddColumn("Paid Status", "paymentstatus");
    $dg->AddColumn("Paid Remarks", "remarks");
    $dg->AddAction("Voucher History", "../../images/history.png", "voucherhistory.php?vid=%d");
    $dg->SetNoDataMessage("No voucher pending.");
    $dg->AddIdColumn("voucherid");
    ?>
    <div class="panel">
        <?php 
            $loginid = GetLoggedInUserId();
            $SQL = sprintf('SELECT sum(amount) as cramt  FROM '.DB_PARENT.'.`cash_received` where advp_status=0'); 
            $db->executeQuery($SQL);
            while ($row = $db->get_nextRow())   
                {
                   $total_cashrecv  = $row["cramt"];
                }
                
            $SQL = sprintf('SELECT SUM(amount) as advpaid FROM '.DB_PARENT.'.`cash_received` where advp_status=1'); 
            $db->executeQuery($SQL);
                while ($row = $db->get_nextRow())   
                {
                    $total_adv_paid  = $row["advpaid"];
                }
                
            $SQL = sprintf('SELECT SUM(pay_amount) as paidamt FROM '.DB_PARENT.'.`voucher_payment` where advpaid=0'); 
            $db->executeQuery($SQL);
            while ($row = $db->get_nextRow())   
                {
                    $total_cash_paid  = $row["paidamt"];
                    
                }
                if($total_cash_paid==""){
                    $total_cash_paid=0;
                }
                if($total_adv_paid==""){
                    $total_adv_paid=0;
                }
                if($total_cashrecv==""){
                    $total_cashrecv=0;
                }
//                echo"<br>total_cashrecv". $total_cashrecv;
//                echo "<br>total_adv_paid".$total_adv_paid;
//                echo "<br>total_cash_paid".$total_cash_paid;
                $balance_amt = $total_cashrecv-($total_adv_paid+$total_cash_paid);
        ?>
        <div class="paneltitle" align="center">Payment Details <?php echo"<span style='float:right;'><b>Available Balance:".$balance_amt."</b></span>";?></div>
        <div class="panelcontents">
                <?php $dg->Render(); ?>
        </div>
    </div>
<?php    
} ?>
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
    var pamount = $("#pamount").val();
    var vamount = $("#vamount").val();
    var vamtremain = $("#vamtremain").val();
    var advancepayment = $('#advancepayment').val();
    var advpaid = document.getElementById("advpaid").checked;
        if(advpaid==true){
          if(parseInt(pamount) > parseInt(advancepayment)){  
              alert("Advance amount should be greater than Paid amount.");
              return false;
          } 
        }else if(parseInt(pamount)==""){
            alert("Please enter Amount");
            return false;
        }else if(parseInt(pamount)>parseInt(vamount)){
            alert("Amount not be greater than voucher amount");
            return false;
        }else if(parseInt(pamount)>parseInt(vamtremain)){
            alert("Amount not be greater than remaining voucher amount");
            return false;
        }else{
            $("#formpayment").submit();
        }
    }
</script>