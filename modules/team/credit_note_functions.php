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

if (isset($_POST['get_invoices'])) {
	$tm=new TeamManager();
	if(!empty($_POST['ledgerid'])){
		$ledgerid=$_POST['ledgerid'];
		$invoiceid=0;
	}
	elseif (!empty($_POST['invoiceid'])) {
		$ledgerid=0;
		$invoiceid=$_POST['invoiceid'];
	}
	$arrResult=$tm->getInvoices($ledgerid,$invoiceid);

	
	echo json_encode($arrResult);
}


if (isset($_REQUEST['get_customer'])) {
	// print_r($_REQUEST['term']); exit;
	$docket=new DocketManager();
	$arrResult=$docket->getCustomers($_REQUEST['term']);
	echo json_encode($arrResult);
}


// if(isset($_POST['get_invoices'])){
// 	// print_r($_POST['customerno']); exit;
// 	$customerid = $_POST['customerno'];
// 	$tm=new TeamManager();
// 	$arrResult=$tm->get_Invoices($customerid);
// 	echo json_encode($arrResult);
// }


if(isset($_POST['new_creditNote'])){
	//echo $_POST['customerno']; exit;
	$tm=new TeamManager();
	$payment_obj = new stdClass();
	$payment_obj->customerno = $_POST['customerno'];
	$payment_obj->ledgerid = $_POST['ledger_name'];
	$payment_obj->invoiceno = $_POST['invoiceno'];
	$payment_obj->inv_amount = $_POST['inv_amount'];
	$payment_obj->credit_amount = $_POST['credit_amount'];
	$payment_obj->reason = $_POST['reason'];
	$payment_obj->status = $_POST['status'];
	$payment_obj->inv_date= $_POST['inv_date'];
	if($payment_obj->status=1)
	{
		$payment_obj->requested_date= date("Y-m-d H:i:s");
	}
	else if($payment_obj->status=2) {
		$payment_obj->approved_date= date("Y-m-d H:i:s");
	}
	
    $arrResult=$tm->insert_credit_note($payment_obj);
    
    echo json_encode($arrResult);
    
}

if(isset($_POST['update_creditNote'])){
	// echo $_POST['edit_status']; exit;
	$tm=new TeamManager();
	$payment_obj = new stdClass();
	$payment_obj->credit_note_id = $_POST['credit_note_id'];
	$payment_obj->customerno = $_POST['customerno'];
	$payment_obj->ledgerid = $_POST['ledger_name'];
	$payment_obj->invoiceno = $_POST['invoiceno'];
	$payment_obj->inv_amount = $_POST['inv_amount'];
	$payment_obj->credit_amount = $_POST['credit_amount'];
	$payment_obj->reason = $_POST['reason'];
	$payment_obj->edit_status = $_POST['edit_status'];
	$payment_obj->inv_date= $_POST['inv_date'];
	if($payment_obj->status=1)
	{
		$payment_obj->requested_date= date("Y-m-d H:i:s");
	}
	else if($payment_obj->status=2) {
		$payment_obj->approved_date= date("Y-m-d H:i:s");
	}
	
    $arrResult=$tm->update_credit_note($payment_obj);
    
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