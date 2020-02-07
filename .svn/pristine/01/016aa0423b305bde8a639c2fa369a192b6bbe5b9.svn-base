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

$vid = $_REQUEST['vid'];
if(empty($vid)){
header("Location: payment.php");    
}
class testing{
    
}
include("header.php");
$db = new DatabaseManager();
$sql = sprintf("select vp.voucher_id, vp.voucher_amount,vp.pay_amount,t.name as doneby ,vp.voucher_date, vp.payment_date, vp.done_by,vp.remarks, vp.timestamp,(CASE WHEN vp.advpaid = 1 THEN 'Advanced paid' ELSE 'Company' END)as paidtype from ".DB_PARENT.".voucher_payment vp  left join ".DB_PARENT.".team t on t.teamid = vp.done_by where vp.voucher_id=".$vid);
$db->executeQuery($sql);

function check_paidstatus($voucherid){
$db = new DatabaseManager();
$sql = sprintf("select distinct (CASE WHEN ispaid = 0 THEN 'Unpaid' WHEN ispaid=2 THEN 'P-Paid' ELSE 'Paid' END)as paymentstatus from ".DB_PARENT.".voucher where voucherid=".$voucherid);

$db->executeQuery($sql);
   if ($db->get_rowCount() > 0) {
       while ($row = $db->get_nextRow())
        {
            $paidstatus = $row['paymentstatus'];
        } 
    }
    return $paidstatus;
}

$x=0;
$histdetails = Array();
 if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $x++;
        $user = new testing();
        $user->voucher_id = $row['voucher_id'];
        $user->voucher_amount = $row['voucher_amount'];
        $user->voucher_payamt = $row['pay_amount'];
        $user->voucher_date = $row['voucher_date'];
        $user->payment_date = $row['payment_date'];
        $user->remarks = $row['remarks'];
        $user->paidtype = $row['paidtype'];
        $user->paidstatus = check_paidstatus($row['voucher_id']);
        $user->timestamp = $row['timestamp'];
        $user->doneby = $row['doneby'];
        
        $user->x = $x;
        $histdetails[] = $user;
    }

}
$dg = new objectdatagrid($histdetails);
//$dg = new datagrid( $db->getQueryResult());
$dg->AddColumn("Sr No", "x");
$dg->AddColumn("Voucher id", "voucher_id");
$dg->AddColumn("Voucher Amt", "voucher_amount");
$dg->AddColumn("Paid Amount", "voucher_payamt");
$dg->AddColumn("Voucher Date", "voucher_date");
$dg->AddColumn("Paid Date", "payment_date");
$dg->AddColumn("Paid Type", "paidtype");
$dg->AddColumn("Status", "paidstatus");
$dg->AddColumn("Done By", "doneby");
$dg->AddColumn("Remarks", "remarks");
$dg->AddColumn("Timestamp", "timestamp");
$dg->SetNoDataMessage("No History.");
$dg->AddIdColumn("teamid");

?>
<div class="panel">
<div class="paneltitle" align="center">Voucher History  # <?php echo $vid;?></div>
<div class="panelcontents">
<?php $dg->Render(); ?>
</div>
</div>
<br/>
<?php
include("footer.php");
?>
