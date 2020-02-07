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

function showamt($cid,$frmdate,$todate){
        $db = new DatabaseManager();
       $sql=  sprintf("select sum(amount) as monthwise_cust_amt  from ".DB_PARENT.".voucher where customer=".$cid." AND voucherdate BETWEEN '".$frmdate."' AND '".$todate."'");
      $db->executeQuery($sql);
        while ($row = $db->get_nextRow())
            {
                 $monthwise_cust_amt = $row["monthwise_cust_amt"];
                 if($monthwise_cust_amt==""){
                     $mnthwisecust ="0";
                 }else{
                     $mnthwisecust=$monthwise_cust_amt;
                 }
            }
            return $mnthwisecust;
            
}
function total_showamt($totalcustid,$frmdate,$todate){
        $db = new DatabaseManager();
        $totalcustids = implode(",",$totalcustid);
       $sql=  sprintf("select sum(amount) as total_monthwise_cust_amt  from ".DB_PARENT.".voucher where customer IN (".$totalcustids.") AND voucherdate BETWEEN '".$frmdate."' AND '".$todate."'");
      $db->executeQuery($sql);
        while ($row = $db->get_nextRow())
            {
                 $total_monthwise_cust_amt = $row["total_monthwise_cust_amt"];
                 if($total_monthwise_cust_amt==""){
                     $total_mnthwisecust ="0";
                 }else{
                     $total_mnthwisecust=$total_monthwise_cust_amt;
                 }
            }
            return $total_mnthwisecust;
}








function showamt_elixir($teamid,$frmdate,$todate){
      $db = new DatabaseManager();
      $sql=  sprintf("select sum(amount) as monthwise_elixir_amt  from ".DB_PARENT.".voucher where claimant=".$teamid." AND voucherdate BETWEEN '".$frmdate."' AND '".$todate."'");
      $db->executeQuery($sql);
        while ($row = $db->get_nextRow())
            {
                 $monthwise_elixir_amt = $row["monthwise_elixir_amt"];
                 if($monthwise_elixir_amt==""){
                     $mnthwiseelix ="0";
                 }else{
                     $mnthwiseelix=$monthwise_elixir_amt;
                 }
            }
            return $mnthwiseelix;
}

function total_showamt_elixir($totalteamid,$frmdate,$todate){
      $db = new DatabaseManager();
      $totalteamids = implode(",",$totalteamid);
      $sql=  sprintf("select sum(amount) as monthwise_elixir__grandtotal  from ".DB_PARENT.".voucher where claimant IN(".$totalteamids.") AND voucherdate BETWEEN '".$frmdate."' AND '".$todate."'");
      $db->executeQuery($sql);
        while ($row = $db->get_nextRow())
            {
                 $monthwise_elixir__grandtotal = $row["monthwise_elixir__grandtotal"];
                 if($monthwise_elixir__grandtotal==""){
                     $mnthwiseelix_total ="0";
                 }else{
                     $mnthwiseelix_total=$monthwise_elixir__grandtotal;
                 }
            }
            return $mnthwiseelix_total;
}




include("header.php");
if(isset($_POST['gofun'])){
    $fromdate = $_POST["fromdate"];
    $fromdate1= date("Y-m-d", strtotime($fromdate));
    $from_month = date("n", strtotime($fromdate1));
    $from_year = date("Y",strtotime($fromdate1));
    
    $todate = $_POST["todate"];
    $todate1= date("Y-m-d", strtotime($todate));
    $to_month = date("n", strtotime($todate1));
    $to_year = date("Y", strtotime($todate1));
    
   // echo "Fromdate".$fromdate1 .",todate".$todate1."<br>";
    //echo "From month".$from_month .",to month".$to_month."<br>";
    //echo "From Year".$from_year.",to year".$to_year."<br>";
    
    if($fromdate==""){
        $message="Please select From date.";
    }else if($todate==""){
        $message="Please select To date.";
    }
    
    if($_POST["gofun"]=="Elixir"){
        $gofun="1";
    }
    if($_POST["gofun"]=="Customer"){
        $gofun="2";
    }
    
}
?>
<div class="panel">
<div class="paneltitle" align="center">Petty Cash Analysis</div>
<div class="panelcontents">
    <?php if(!empty($message)){ echo"<span style='color:red; font-size:12px;'>".$message."</span>"; };?>
    <form name="pettyanalysis" id="pettyanalysis" method="POST" action="pettycash_analysis.php">
    <table align="center" cellpadding="5">
        <tr>
            <td>From Date :</td>
            <td><input id="fromdate" name="fromdate" placeholder="dd-mm-yyyy" type="text"/></td>
            <td>To Date :</td>
            <td><input id="todate" name="todate" placeholder="dd-mm-yyyy" type="text"/></td>
            <td><input type="submit" name="gofun" value="Elixir"/></td>
            <td><input type="submit" name="gofun" value="Customer"></td>
        </tr>
    </table>
</div>
<div class="panelcontents">
    <?php 
   if($gofun==2 && $message==""){
    if($from_month!=="" && $to_month!="")
    {
  ?>
  <table border="1" align="center" cellpadding="5">
    <tr>
    <td style="text-align: center;"><b>ID</b></td>
    <td style="text-align: center;"><b>Customer</b></td>
    <?php
    
        
function getmonths_range($STdate,$EDdate){
    $TOTALDAYS = Array();
    
    $STdate = date("Y-m", strtotime($STdate)).'-01';
    $EDdate = date("Y-m", strtotime($EDdate)).'-31';  
    $last_month = null;
    
    while (strtotime($STdate) <= strtotime($EDdate)) 
    {
        $cur_month = date('m',strtotime($STdate));
        if($cur_month!=$last_month){
            $TOTALDAYS[] = $STdate;   //skipped other days
            $last_month = $cur_month;
        }
        
        $STdate = date("Y-m-d", strtotime($STdate . ' + 1 days'));
        
    }
    return $TOTALDAYS;
}
$months = getmonths_range($fromdate1, $todate1);
$months_count = count($months);
    for ($i=0; $i < $months_count; $i++){
        $date = $months[$i];
         $fmonth = date("M", strtotime($date));
         $fyear = date("Y", strtotime($date));
        
        echo"<td align='center'><b>".$fmonth."-".$fyear."</b></td>";
    }
?>
     <td><b>Total</b></td>
    </tr>
    <?php
    $db = new DatabaseManager();
    $sql = sprintf("SELECT c.customercompany,v.customer,sum(v.amount) FROM ".DB_PARENT.".`voucher` as v left join ".DB_PARENT.".customer c on c.customerno = v.customer group by v.customer");
    $db->executeQuery($sql);
    $trcount = $db->get_rowCount();
    if ($db->get_rowCount()>0) 
    {
        $chknotzero = array();
        while ($row = $db->get_nextRow())
        {
            $customercompany = $row["customercompany"];
            $customerid= $row["customer"];
            $totalcustid[] = $customerid;
            $totalcustomer_hr_chk = array();
              for ($i=0; $i < $months_count; $i++){
                   $fromdate = $months[$i];
                   $todate = date('Y-m',strtotime($fromdate))."-31";
                   $totalcustomerhrchk = showamt($customerid,$fromdate,$todate);  
                   $totalcustomer_hr_chk[] =$totalcustomerhrchk;
              }
             $chknotzero = array_sum($totalcustomer_hr_chk);
if($chknotzero!=0){
?>            
<tr>
        <td><b><?php echo $customerid;?></b></td>
        <td><b><?php echo $customercompany;?></b></td>
        <?php
            $totalcustomer_hr = array();
            for ($i=0; $i < $months_count; $i++){
            $fromdate = $months[$i];
            $todate = date('Y-m',strtotime($fromdate))."-31";
            ?>
                <td style='text-align:right;'><?php echo $totalcustomerhr = showamt($customerid,$fromdate,$todate);  $totalcustomer_hr[] =$totalcustomerhr; ?></td>
            <?php
            }
            ?>
            <td style="text-align: right;"><?php echo "<b>".array_sum($totalcustomer_hr)."</b>";?></td>
        <?php
        }
        ?>
    </tr>
 <?php
       }
 ?>   
     <tr><td>&nbsp;</td><td><b>Total</b></td>
         <?php
            $grand_cust_totals = array();
            for ($i=0; $i < $months_count; $i++){
            $fromdate = $months[$i];
            $todate = date('Y-m',strtotime($fromdate))."-31";
            ?>
            <td style='text-align:right;'><?php echo "<b>".$totalgrandcustomervr = total_showamt($totalcustid,$fromdate,$todate)."</b>";  
            $grand_cust_totals[] =$totalgrandcustomervr; ?></td>
            <?php
            }
            ?>
            <td style="text-align: right;"><?php echo "<b>".array_sum($grand_cust_totals)."</b>";?></td>
    <?php            
        }    
    }
    ?>
    </table> 
  <?php
    }
   
   ?>
    
    <?php 
   if($gofun==1 && $message==""){
    if($from_month!=="" && $to_month!="")
    {
    ?>
    <table border="1" align="center" cellpadding="5">
    <tr>
    <td style="text-align: center;"><b>ID</b></td>
    <td style="text-align: center;"><b>Elixir</b></td>
    <?php
       
         
function getmonths_range($STdate,$EDdate){
    $TOTALDAYS = Array();
    
    $STdate = date("Y-m", strtotime($STdate)).'-01';
    $EDdate = date("Y-m", strtotime($EDdate)).'-31';  
    $last_month = null;
    
    while (strtotime($STdate) <= strtotime($EDdate)) 
    {
        $cur_month = date('m',strtotime($STdate));
        if($cur_month!=$last_month){
            $TOTALDAYS[] = $STdate;   //skipped other days
            $last_month = $cur_month;
        }
        
        $STdate = date("Y-m-d", strtotime($STdate . ' + 1 days'));
        
    }
    return $TOTALDAYS;
}
$months = getmonths_range($fromdate1, $todate1);
$months_count = count($months);
    for ($i=0; $i < $months_count; $i++){
        $date = $months[$i];
         $fmonth = date("M", strtotime($date));
         $fyear = date("Y", strtotime($date));
        
        echo"<td align='center'><b>".$fmonth."-".$fyear."</b></td>";
    }
    ?>
    <td><b>Total</b></td>
    </tr>
    <?php
    $db = new DatabaseManager();
    $sql = sprintf("select v.claimant,t.name, sum(v.amount) as elixirvoucheramt from ".DB_PARENT.".voucher as v left join ".DB_PARENT.".team t on v.claimant = t.teamid group by v.claimant");
    $db->executeQuery($sql);
    $trcount = $db->get_rowCount();
    if ($db->get_rowCount()>0) 
    {
        $totalteamid = array();
        while ($row = $db->get_nextRow())
        {
            $elixir = $row["name"];
            $teamid= $row["claimant"];
            
            $totalteamid[] = $teamid;
    ?>   
    <tr>
        <td><b><?php echo $teamid;?></b></td>
        <td><b><?php echo $elixir;?></b></td>
        <?php
        $totalelixir_hr = array();
            for ($i=0; $i < $months_count; $i++){
            $fromdate = $months[$i];
            $todate = date('Y-m',strtotime($fromdate))."-31";
            ?>
            <td style='text-align:right;'><?php echo $totalelixirhr = showamt_elixir($teamid,$fromdate,$todate); $totalelixir_hr[] = $totalelixirhr; ?></td>
            <?php
            }
        ?>
        <td style="text-align: right;"><?php echo "<b>".array_sum($totalelixir_hr)."</b>";?></td>
    </tr>
    <?php            
             
            }  
    ?>
    <tr><td>&nbsp;</td><td><b>Total</b></td>
         <?php
        $grand_totals = array();
            for ($i=0; $i < $months_count; $i++){
            $fromdate = $months[$i];
            $todate = date('Y-m',strtotime($fromdate))."-31";
            ?>
        <td style='text-align:right;'>
            <?php
              echo "<b>".$grand_total = total_showamt_elixir($totalteamid,$fromdate,$todate)."</b>";
              $grand_totals[] = $grand_total;
            ?>
        </td>
            <?php
            }
        ?>
        <td style="text-align: right;"><?php echo "<b>".array_sum($grand_totals)."</b>";?></td>
    </tr>
    <?php    
    }
    ?>
    </table>
    <?php  

        }
   }
    ?>
    
</div>
</div>
<br/>
<?php
include("footer.php");
?>

<script>
 
 $(document).ready(function(){ 
        $('#fromdate').datepicker({
            format: "dd-mm-yyyy",
            language:  'en',
            autoclose: 1
        }); 
        
             $('#todate').datepicker({
            format: "dd-mm-yyyy",
            language:  'en',
            autoclose: 1
        }); 
    });
</script>   