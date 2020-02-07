<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/Date.php");

$vid = $_REQUEST["vid"];
class testing{
    
}
if(empty($vid)){  header("location:addvoucher.php"); }
$db = new DatabaseManager();
$sql = sprintf("select v.voucherid,v.claimdate,v.voucherdate,v.customer,v.amount,v.remarks,ah.headid,c.customercompany, ah.headtype,(CASE WHEN v.ispaid = 0 THEN 'NotPaid' ELSE 'Paid' END) as paymentstatus 
from ".DB_PARENT.".voucher as v INNER JOIN ".DB_PARENT.".account_head as ah on ah.headid = v.headid left join ".DB_PARENT.".customer as c on c.customerno = v.customer  where v.voucherid=".$vid);
$db->executeQuery($sql);
$voucher_details_array = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $details = new testing();
        $details->claimdate  = $row["claimdate"];
        $details->amount = $row["amount"]; 
        $details->voucherdate = $row["voucherdate"]; 
        $details->remarks = $row["remarks"]; 
        $details->headtype = $row["headtype"];
        $details->customer = $row["customer"];
        $details->customercompany = $row["customercompany"]; 
        $details->voucherid = $row["voucherid"]; 
        $voucher_details_array[] = $details;     
    }
}

$sql = sprintf("select v.remarks,v.claimdate,SUM(v.amount) as tamount,t.teamid, t.name from ".DB_PARENT.".voucher as v left join ".DB_PARENT.".team as t on t.teamid = v.claimant where v.voucherid=".$vid);
$db->executeQuery($sql);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        
        $claimdate  = $row["claimdate"];
        $tamount = $row["tamount"]; 
        $remarks = $row["remarks"];
        $name = $row["name"];
        $claimdate1 = date("d-m-Y", strtotime($claimdate));
        
    }
}

?>    

<table border="1" style=" font-size: 14px; border-color: 1px solid black;border-collapse: collapse;" cellpadding="3">
    <tr><td rowspan="4"><img src="../../images/elixia_logo.png"/></td><td colspan="4">Elixia Tech Solutions Pvt Ltd.</td></tr>
    <tr><td colspan="4">D, Neelkanth Business Park, Vidyavihar(W) Mumbai - 400086</td></tr>
    <tr><td colspan="2"><b>Petty Cash Voucher No.</b> <?= $vid;?></td><td colspan="2"><b>Date :</b> <?php echo $claimdate1;?></td></tr>
    <tr><td colspan="4"><b>Name of Claimant : </b><?php echo ucfirst($name);?></td></tr>
<!--    <tr><td colspan="4" style="text-align: center;"><b>Account Head</b></td></tr>-->
    <tr><td><b>Customers</b></td><td colspan="2"><b>Account Head</b></td><td><b>Date</b></td><td colspan="2"><b>Rs.</b></td></tr>
    <?php
foreach ($voucher_details_array as $row){
    ?>
    <tr><td><?php if($row->customer=='-1'){echo "Shrushti Repair";}else{echo $row->customercompany;}?></td><td colspan="2"><?php echo $row->headtype; ?></td><td><?php echo $row->voucherdate;?></td><td colspan="2"><?php echo $row->amount;?></td></tr>
<?php
    }
    ?>
    <tr><td>&nbsp;</td><td colspan="3"><b>Total</b></td><td colspan="2"><b><?php echo $tamount;?></b></td></tr>
    <tr><td colspan="5"><b>Remarks :</b> <?php echo $remarks;?></td></tr>
</table>
    
    

