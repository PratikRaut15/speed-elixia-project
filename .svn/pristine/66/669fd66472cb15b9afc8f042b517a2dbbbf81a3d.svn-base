<?php
//error_reporting(E_ALL);
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");

$db =new DatabaseManager();
$payid =$_GET['pid'];
$SQL="SELECT * FROM ".DB_PARENT.".invoice_payment WHERE payment_id='$payid' ";
$db->executeQuery($SQL);

$row = $db->get_nextRow();
$payment_amt =$row['payment_amt'];
$tds_amt=$row['tdsamt'];
$invoice_id=$row['invoiceid'];
//----------------------------to find if previous payment is available--------------------
$SQL1="SELECT * FROM ".DB_PARENT.".invoice_payment WHERE invoiceid='$invoice_id' AND payment_id NOT IN('$payid') AND isdeleted =0 ORDER BY payment_id DESC LIMIT 1";
$db->executeQuery($SQL1);
$row1 = $db->get_nextRow();
$paymode=$row1['payment'];
$pay_date =$row1['payment_date'];
//print_r($row1);

$SQL2 ="UPDATE ".DB_PARENT.".invoice_payment SET isdeleted=1 WHERE payment_id='$payid'";
$db->executeQuery($SQL2);

$SQL3="SELECT * FROM ".DB_PARENT.".invoice WHERE invoiceid ='$invoice_id' ";
$db->executeQuery($SQL3);

$row3 = $db->get_nextRow();
$pending=$row3['pending_amt'];
$status=$row3['status'];
$paidamt=$row3['paid_amt'];
$pay_mode=$row3['pay_mode'];
$paydate=$row3['paymentdate'];
$tds=$row3['tds_amt'];
//------------update the values in invoice table----------------------
$upending=$pending+$payment_amt+$tds_amt;
$ustatus="Pending";
$upaidamt=$paidamt-$payment_amt;

$utds=$tds-$tds_amt;
if($paymode ==""||$pay_date=="")
{
    $upay_mode="";
    $upaydate="";
    
}
else
{
    $upay_mode="$paymode";
    $upaydate="$pay_date";
}
$SQL4="UPDATE ".DB_PARENT.".invoice SET status='$ustatus', pending_amt='$upending',pay_mode='$upay_mode', paid_amt='$upaidamt', paymentdate='$upaydate', tds_amt='$utds' WHERE invoiceid='$invoice_id'";
    
    $db->executeQuery($SQL4);
    header("location:invoice_edit.php?inid=$invoice_id");
?>