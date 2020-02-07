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
	$ledgerid=0;
	$arrResult=$tm->getInvoices($ledgerid,$_POST['customerno']);
	echo json_encode($arrResult);
}


if (isset($_REQUEST['get_customer'])) {
	// print_r($_REQUEST['term']); exit;
	$docket=new DocketManager();
	$arrResult=$docket->getCustomers($_REQUEST['term']);
	echo json_encode($arrResult);
}

if (isset($_REQUEST['get_collectedby'])) {
	// print_r($_REQUEST['term']); exit;
	$tm=new TeamManager();
	$arrResult=$tm->getCollected($_REQUEST['term']);
	echo json_encode($arrResult);
}


// if(isset($_POST['get_invoices'])){
// 	// print_r($_POST['customerno']); exit;
// 	$customerid = $_POST['customerno'];
// 	$tm=new TeamManager();
// 	$arrResult=$tm->get_Invoices($customerid);
// 	echo json_encode($arrResult);
// }


if(isset($_POST['new_payment'])){
	$tm=new TeamManager();
	$payment_obj = new stdClass();
	$payment_obj->customerno = $_POST['customerno'];
	if(!empty($_POST['invoiceno'])) {
	$payment_obj->invoiceid = $_POST['invoiceno'];
	} else {
		$payment_obj->invoiceid=0;
	}
	$payment_obj->payment_mode = $_POST['payment_mode'];
	$payment_obj->payment_date = date("Y-m-d",strtotime($_POST['payment_date']));
	if($payment_obj->payment_mode==1){
		$payment_obj->cheque_number = str_pad($_POST['cheque_number'],6,"0",STR_PAD_LEFT);
		$payment_obj->bank_name = $_POST['bank_name'];
		$payment_obj->bank_branch = $_POST['bank_branch'];
		$payment_obj->cheque_status = $_POST['cheque_status'];
		$payment_obj->cheque_date = date("Y-m-d", strtotime($_POST['cheque_date']));
	}
	else{
		$payment_obj->cheque_no = '';
		$payment_obj->bank_name ='';
		$payment_obj->bank_branch = '';
		$payment_obj->cheque_date = '';
	}
	$payment_obj->paid_amount = $_POST['paid_amount'];
	
	// if($_POST['tds_amount']!=''){
	// 	$payment_obj->tds_amount = $_POST['tds_amount'];
	// }
	// else{
	// 	$payment_obj->tds_amount=0.00;
	// }
	// if($_POST['bad_debt']!=''){
	// 	$payment_obj->bad_debt = $_POST['bad_debt'];
	// }
	// else{
	// 	$payment_obj->bad_debt=0.00;
	// }
	$payment_obj->remark = $_POST['remark'];
	$payment_obj->status = $_POST['status'];
	$payment_obj->collectedby = $_POST['collectedby_id'];
	
    $arrResult=$tm->insert_payment_collection($payment_obj);
    
    echo json_encode($arrResult);
    
}

if(isset($_POST['update_creditNote'])){
	//echo $_POST['customerno']; exit;
	$tm=new TeamManager();
	$payment_obj = new stdClass();
	$payment_obj->credit_note_id = $_POST['credit_note_id'];
	$payment_obj->customerno = $_POST['customerno'];
	$payment_obj->invoiceno = $_POST['invoiceno'];
	$payment_obj->credit_amount = $_POST['credit_amount'];
	$payment_obj->reason = $_POST['reason'];
	$payment_obj->status = $_POST['edit_status'];
	if($payment_obj->status=1)
	{
		$payment_obj->status='requested';
		$payment_obj->requested_date= date("Y-m-d H:i:s");
	}
	else if($payment_obj->status=2) {
		$payment_obj->status='approved';
		$payment_obj->approved_date= date("Y-m-d H:i:s");
	}
	
    $arrResult=$tm->update_credit_note($payment_obj);
    
    echo json_encode($arrResult);
    
}

if(isset($_POST['edit_payment'])){
	// print_r($_POST);
	$tm=new TeamManager();
	$payment_obj = new stdClass();

	$payment_obj->payment_id = $_POST['payment_id'];
	$payment_obj->customerno = $_POST['customerno'];
	$payment_obj->invoiceno = $_POST['invoiceno'];
	$payment_obj->payment_date = date("Y-m-d",strtotime($_POST['payment_date']));
	$payment_obj->pay_mode = $_POST['payment_mode'];

	if($payment_obj->pay_mode==1){
		$payment_obj->cheque_no = str_pad($_POST['cheque_no'],6,"0",STR_PAD_LEFT);
		$payment_obj->bank_name = $_POST['bank_name'];
		$payment_obj->bank_branch = $_POST['bank_branch'];
		$payment_obj->cheque_status = $_POST['cheque_status'];
		$payment_obj->cheque_date = date("Y-m-d", strtotime($_POST['cheque_date']));
	}
	else{
		$payment_obj->cheque_no = '';
		$payment_obj->bank_name ='';
		$payment_obj->bank_branch = '';
		$payment_obj->cheque_date = '';
	}
	
	$payment_obj->paid_amount = $_POST['paid_amount'];
	// if($_POST['tds_amount']!=''){
	// 	$payment_obj->tds_amount = $_POST['tds_amount'];
	// }
	// else{
	// 	$payment_obj->tds_amount=0.00;
	// }
	
	// if($_POST['bad_debt']!=''){
	// 	$payment_obj->bad_debt = $_POST['bad_debt'];
	// }
	// else{
	// 	$payment_obj->bad_debt=0.00;
	// }
	$payment_obj->remark = $_POST['remark'];
	$payment_obj->status = $_POST['status'];
	$payment_obj->collectedby = $_POST['collectedby_id'];
    $arrResult=$tm->update_Payment_Collection($payment_obj);
    
    echo json_encode($arrResult);
    
}

// if(isset($_POST['delete_payment'])){
// 	// print_r($_POST);
// 	$tm=new TeamManager();
// 	$payment_obj = new stdClass();
// 	$payment_obj->payment_id = $_POST['payment_id'];
//     $arrResult=$tm->delete_Payment_Collection($payment_obj);
    
//     echo json_encode($arrResult);
    
// }

if(!empty($_GET['ip_id'])){
	$tm=new TeamManager();
	$payment_obj = new stdClass();
	$payment_obj->payment_id = $_GET['ip_id'];
	$arrResult=$tm->delete_Payment_Collection($payment_obj);
    if(!empty(json_encode($arrResult)))
    {
    	header("Location: http://localhost/speed/modules/team/payment_collection.php");
    }
    echo json_encode($arrResult);
    
}



?>