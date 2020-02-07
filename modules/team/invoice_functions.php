<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Date.php");
include_once("../../lib/bo/TeamManager.php");
include_once("../../lib/bo/invoiceManager.php");
include_once("../../lib/bo/DocketManager.php");

if (isset($_POST['get_ledger'])) {
	$tm=new TeamManager();
	$arrResult=$tm->getLedger($_POST['customerno']);
	echo json_encode($arrResult);
}


if(isset($_POST["submitScheduleInvoice"])){
	$obj = new stdClass();
	$obj->customerNo = $_POST["customerno"];
	$obj->productId = $_POST["product"];
	$obj->ledgerNo = $_POST["ledgerid"];
	$obj->remarks = $_POST["remarks"];
	$obj->reminderCount = $_POST["count"];
	$obj->invoiceType = $_POST["invoice"];
	if($obj->invoiceType == 'OT'){
		$obj->invoiceType = 1;
		$obj->otAmount = $_POST["OTAmount"];
		$obj->otAmc = $_POST["OTAmc"];
		$obj->cycle=4;
	}else if ($obj->invoiceType=='SAAS'){
		$obj->invoiceType = 3;
		$obj->SAASAmount = $_POST["SAASAmount"];
		$obj->cycle = $_POST["period"];
	}
	$reminder = array();
	$obj->reminders = array();
	for($i=0;$i<$obj->reminderCount;$i++){
		$reminder["invEid"] = $_POST["eid".$i];
		$reminder["invAmount"] = ($obj->invoiceType==1)? $obj->otAmount : $obj->SAASAmount;
		$reminder["invDesc"]=$_POST["desc".$i];
		$obj->reminders[] = $reminder;
	}
	$invMgr = new invoiceManager();
	$ret = $invMgr->scheduleInvoice($obj);
	echo "ok";
}

if(isset($_POST["editInvRem"])){
	$invMgr = new invoiceManager();
	$obj = new stdClass();
	//print_r($_POST);
	$obj->invId = $_POST['invRemId'];
	$obj->customerno = $_POST['customerno'];
	$obj->ledgerid = $_POST['ledgerno'];
	$obj->product = $_POST['product'];
	$obj->invAmount =$_POST['amount'];
	$obj->desc = $_POST['desc'];
	$ret = $invMgr->editInvoiceReminder($obj);
	if($ret==1){
		echo "ok";	
	}
}

if(isset($_POST['deleteInvoiceReminder'])){
	$invMgr = new invoiceManager();
	$ret = $invMgr->deleteInvoiceReminder($_POST['invId']);
	//echo $ret;
	if($ret==1){
		echo "ok";	
	}else{
		echo "not ok";
	}
}

if(isset($_POST["fetchSAASCycles"])){
	$invMgr = new invoiceManager();
	$ret = $invMgr->fetchSAASCycles();
	echo json_encode($ret);
}

if(isset($_POST["fetchInvoiceReminders"])){
	$invMgr =new invoiceManager();
	$ret = $invMgr->fetchInvoiceReminders($_POST["invId"]);
	echo json_encode($ret);
}

if(isset($_POST["rescheduleInvoice"])){
	$invMgr = new invoiceManager();
	$reschedule_date = $_POST["invDate"];
	$invId = $_POST["invId"];
	$ret = $invMgr->rescheduleInvoice($reschedule_date,$invId);
	echo "ok";
}

function addMonth($begin,$months,$mode) {
	$begin = new DateTime($begin);
    $end = clone $begin;
    $end->modify('+'.$months.' month');
    $flag = 0;
    while (($begin->format('m')+$months)%12 != $end->format('m')%12) {
        $end->modify('-1 day');
        $flag = 1;
    }
    if(!$flag&&$mode){
    	$end->modify('-1 day');
    }
    return $end;
}

if(isset($_POST["approveInvoice"])){
	$today = date("Y-m-d H:i:s");
	$obj = new stdClass();
	$invMgr = new invoiceManager();
	$invDate = $_POST["invDate"];
	$obj->invId = $_POST["invId"];
	$obj->startDate=$_POST['startDate'];
	$obj->endDate=$_POST['endDate'];
	$invDate = $_POST["sDate"];
	if($_POST["inv_type"]==1||$_POST["inv_type"]==2){
		$newDate = addMonth($invDate,12,1);
	}elseif($_POST["inv_type"]==3){
		if(isset($_POST["saasPeriod"])&&($_POST["saasPeriod"]=="Yearly")){
		$newDate = addMonth($invDate,12,1);
		}if(isset($_POST["saasPeriod"])&&($_POST["saasPeriod"]=="Half Yearly")){
		$newDate = addMonth($invDate,6,1);
		}if(isset($_POST["saasPeriod"])&&($_POST["saasPeriod"]=="Monthly")){
		$newDate = addMonth($invDate,1,1);
		}if(isset($_POST["saasPeriod"])&&($_POST["saasPeriod"]=="Quarterly")){
		$newDate = addMonth($invDate,3,1);
		}
	}
	$obj->nextInvDate = $newDate->format('Y-m-d H:i:s');
	$ret = $invMgr->approveInvoice($obj);
}

if(isset($_POST["addMonths"])){
	$invDate = $_POST["sDate"];
	if($_POST["inv_type"]==1||$_POST["inv_type"]==2){
		$newDate = addMonth($invDate,12,1);
	}elseif($_POST["inv_type"]==3){
		if(isset($_POST["saasPeriod"])&&($_POST["saasPeriod"]=="Yearly")){
			$newDate = addMonth($invDate,12,1);
		}if(isset($_POST["saasPeriod"])&&($_POST["saasPeriod"]=="Half Yearly")){
			$newDate = addMonth($invDate,6,1);
		}if(isset($_POST["saasPeriod"])&&($_POST["saasPeriod"]=="Monthly")){
			$newDate = addMonth($invDate,1,1);
		}if(isset($_POST["saasPeriod"])&&($_POST["saasPeriod"]=="Quarterly")){
			$newDate = addMonth($invDate,3,1);
		}
	}
	echo $newDate = $newDate->format('Y-m-d');
}
if(isset($_POST["fetchProducts"])){
	$invMgr = new invoiceManager();
	$ret = $invMgr->fetchInvoiceProducts();
	echo json_encode($ret);
}

if (isset($_REQUEST['get_customer'])) {
	$docket=new DocketManager();
	$arrResult=$docket->getCustomers($_REQUEST['term']);
	echo json_encode($arrResult);
}


if(isset($_POST['get_invoices'])){
	
	$ledgerid = $_POST['ledgerid'];
	$tm=new TeamManager();
	$arrResult=$tm->get_Pending_Invoices($ledgerid);
	echo json_encode($arrResult);
}

if(isset($_POST['get_payment_invoices'])){

    $invoiceid = $_POST['invoiceid'];
    $tm=new TeamManager();
    $arrResult=$tm->getInvoice_Payments($invoiceid);
    echo json_encode($arrResult);
    die();
    
}
if(isset($_POST['get_payment_invoices_old'])){

    $invoiceid = $_POST['invoiceid'];
    $tm=new TeamManager();
    $arrResult=$tm->getInvoice_Payments_Old($invoiceid);
    echo json_encode($arrResult);
    
}

if(isset($_POST['get_paid_invoices'])){

	$ledgerid = $_POST['ledgerid'];
	$tm=new TeamManager();
	$arrResult=$tm->get_PaidInvoices($ledgerid);
	echo json_encode($arrResult);
    
}

if(isset($_POST['get_payment_paid_invoices'])){

    $paid_invoiceid = $_POST['paid_invoiceid'];
    $tm=new TeamManager();
    $arrResult=$tm->getInvoice_Payments($paid_invoiceid);
    echo json_encode($arrResult);
    
}


if(isset($_POST['get_sub_payment_details'])){

    $invoiceid = $_POST['invoiceid'];
    $tm=new TeamManager();
    $arrResult=$tm->getInvoice_Payment_Subdetails($invoiceid);
    echo json_encode($arrResult);
    
}

if(isset($_POST['get_payment_mode'])){

    $tm=new TeamManager();
    $arrResult=$tm->getPayment_mode();
    echo json_encode($arrResult);
    
}
if(isset($_POST['new_payment'])){
	$tm=new TeamManager();
	$payment_obj = new stdClass();

	$payment_obj->customerno = $_POST['customerno'];
	$payment_obj->invoiceid = $_POST['payment_invoice_id'];
	$payment_obj->invoiceno = $_POST['payment_invoice_no'];
	$payment_obj->payment_mode = $_POST['payment_mode'];
	$payment_obj->payment_date = date("Y-m-d",strtotime($_POST['payment_date']));
	if($payment_obj->payment_mode==1){
		$payment_obj->cheque_no = str_pad($_POST['cheque_no'],6,"0",STR_PAD_LEFT);
		$payment_obj->bank_name = $_POST['bank_name'];
		$payment_obj->bank_branch = $_POST['bank_branch'];
		$payment_obj->cheque_date = date("Y-m-d", strtotime($_POST['cheque_date']));
	}
	else{
		$payment_obj->cheque_no = '';
		$payment_obj->bank_name ='';
		$payment_obj->bank_branch = '';
		$payment_obj->cheque_date = '';
	}
	$payment_obj->new_payment_amount = $_POST['new_payment_amount'];
	
	if($_POST['new_tds']!=''){
		$payment_obj->new_tds = $_POST['new_tds'];
	}
	else{
		$payment_obj->new_tds=0.00;
	}
	if($_POST['new_unpaid_amount']!=''){
		$payment_obj->new_unpaid_amount = $_POST['new_unpaid_amount'];
	}
	else{
		$payment_obj->new_unpaid_amount=0.00;
	}
	
    $arrResult=$tm->insert_invoice_payment($payment_obj);
    
    echo json_encode($arrResult);
    
}

if(isset($_POST['edit_payment'])){
	//print_r($_POST);
	$tm=new TeamManager();
	$payment_obj = new stdClass();

	$payment_obj->invoice_payment_id = $_POST['invoice_payment_id'];
	$payment_obj->payment_date = date("Y-m-d",strtotime($_POST['payment_date']));
	$payment_obj->pay_mode_check = $_POST['pay_mode_check'];

	if($payment_obj->pay_mode_check==1){
		$payment_obj->cheque_no = str_pad($_POST['cheque_no'],6,"0",STR_PAD_LEFT);
		$payment_obj->bank_name = $_POST['bank_name'];
		$payment_obj->bank_branch = $_POST['bank_branch'];
		$payment_obj->cheque_date = date("Y-m-d", strtotime($_POST['cheque_date']));
	}
	else{
		$payment_obj->cheque_no = '';
		$payment_obj->bank_name ='';
		$payment_obj->bank_branch = '';
		$payment_obj->cheque_date = '';
	}
	
	if(isset($_POST['cheque_status']))
	{
		$payment_obj->cheque_status =1;
	}
	else{
		$payment_obj->cheque_status =0;
	}

	$payment_obj->new_payment_amount = $_POST['new_payment_amount'];
	
	if($_POST['new_tds']!=''){
		$payment_obj->new_tds = $_POST['new_tds'];
	}
	else{
		$payment_obj->new_tds=0.00;
	}
	
	if($_POST['new_unpaid_amount']!=''){
		$payment_obj->new_unpaid_amount = $_POST['new_unpaid_amount'];
	}
	else{
		$payment_obj->new_unpaid_amount=0.00;
	}

    $arrResult=$tm->update_Invoice_Payment($payment_obj);
    
    echo json_encode($arrResult);
    
}

?>