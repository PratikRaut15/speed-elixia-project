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

$db = new DatabaseManager();
$SQL = sprintf("select * from ".DB_PARENT.".team where member_type=1  order by  name asc");
$db->executeQuery($SQL);

function cash_received($teamid){
    $db = new DatabaseManager();
    $sql = sprintf("SELECT sum(cr.amount) as cash_recv FROM ".DB_PARENT.".cash_received cr where cr.advp_status=0  AND  cr.received_by=".$teamid);
    $db->executeQuery($sql);
    
    if ($db->get_rowCount() > 0) {
         while ($row = $db->get_nextRow())
        {
              $cash_recv1 = $row['cash_recv'];
              if($cash_recv1==""){
                  $cash_recv=0;
              }else{
                  $cash_recv=$cash_recv1;
              }
        }
    }
   return $cash_recv;
}

function advance_cash($teamid){
                $db = new DatabaseManager();
                $sql = sprintf("select sum(`amount`) as advance_paid from ".DB_PARENT.".`cash_received` where `advp_status`='1' AND `received_by`=".$teamid);
                $db->executeQuery($sql);
                while ($row = $db->get_nextRow())
                {
                     $advance_paid1 = $row["advance_paid"];
                }
                
                $sql = sprintf("select distinct(voucherid) from ".DB_PARENT.".voucher where claimant=".$teamid);
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
                $totaladvpaid = $advance_paid1-$paidamt;
            
            return $totaladvpaid;
}

function running_bal($teamid){
        $db = new DatabaseManager();
        $sql = sprintf("select sum(amount)as  vamt from ".DB_PARENT.".voucher where claimant=".$teamid);
        $db->executeQuery($sql);
        while ($row = $db->get_nextRow())
        {
            $vamt = $row["vamt"];
        }   
        $sql = sprintf("select distinct(voucherid) from ".DB_PARENT.".voucher where claimant=".$teamid." AND ispaid IN(1,2)");
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
    
    return $running_bal;
}
function ret_money($advcash,$teamid){
   if($advcash<0){
       $s = $retcash;
   }else if($advcash>0){
       $s = "<a href='advreturnmoney.php?tid=".$teamid."'>Click</a>";
   }
    return $s;
}
$x=0;
$dispdetails = Array();
 if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $x++;
        $user = new testing();
        $advcash = advance_cash($row['teamid']);
        $teamid = $row['teamid'];
        $user->name = $row['name'];
        $user->teamid = $row['teamid'];
        $user->cash_received = cash_received($row['teamid']);
        $user->adv_cash = advance_cash($row['teamid']);
        $user->running_bal = running_bal($row['teamid']);
        $user->ret_money = ret_money($advcash,$teamid);
        $user->x = $x;
        $dispdetails[] = $user;
    }

}
$dg = new objectdatagrid($dispdetails);
$dg->AddColumn("Sr.No", "x");
$dg->AddColumn("Name", "name");
$dg->AddColumn("Advance paid", "adv_cash");
$dg->AddColumn("Running Balance", "running_bal");
$dg->AddColumn("Return Money","ret_money");
$dg->SetNoDataMessage("No member.");
$dg->AddIdColumn("teamid");
include("header.php");
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



    <div class="paneltitle" align="center">Current Status <?php echo"<span style='float:right;'><b>Available Balance:".$balance_amt."</b></span>";?></div>
<div class="panelcontents">
<?php $dg->Render(); ?>
</div>
</div>
<br/>
<?php
include("footer.php");
?>
