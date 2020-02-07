<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../../";
}

require_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';

class invoiceManager extends DatabaseManager{
	function __construct(){
        // Constructor.
        parent::__construct();
    }

    public function scheduleInvoice($obj){
    	$db          = new DatabaseManager();
        $today = date('Y-m-d H:i:s');
        $currentUser = GetLoggedInUserId();

        $pdo         = $db->CreatePDOConnForTech();
        //print_r($obj->reminders);
        foreach($obj->reminders as $reminder){
        	$sp_params   = "'". $obj->customerNo ."'
        				,'".$obj->ledgerNo."'
        				,'".$obj->invoiceType."'
        				,'".$obj->productId."'
        				,'".$obj->remarks."'
        				,'".$reminder["invEid"]."'
        				,'".$reminder["invAmount"]."'
        				,'".$reminder["invDesc"]."'
        				,'".$reminder["invAmount"]."'
        				,'".$obj->otAmc."'
        				,'".$obj->cycle."'
        				,'".$today."'
        				,'".$currentUser."'";
	        $queryCallSP = "CALL " . speedConstants::SP_SCHEDULE_INVOICE . "(".$sp_params.")";
	        $arrResult   = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
	        //print_r($arrResult);
        }
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }
    public function editInvoiceReminder($obj){
        $db          = new DatabaseManager();
        $today = date('Y-m-d H:i:s');
        $currentUser = GetLoggedInUserId();

        $pdo         = $db->CreatePDOConnForTech();
        //print_r($obj->reminders);
         $sp_params = "'".$obj->invId."',
                      '".$obj->customerno."',
                      '".$obj->ledgerid."',
                      '".$obj->product."',
                      '".$obj->invAmount."',
                      '".$obj->desc."',@isexecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_EDIT_INVOICE_REMINDER . "(".$sp_params.")";
        $arrResult   = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isexecutedOut AS isexecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $outputResult['isexecutedOut'];
    }

    public function deleteInvoiceReminder($invId){
        $db = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $sp_params = "'".$invId."'
                      ,@isexecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_DELETE_INVOICE_REMINDER . "(".$sp_params.")";
        $arrResult   = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isexecutedOut AS isexecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        // print_r($outputResult);
        $db->ClosePDOConn($pdo);
        return $outputResult['isexecutedOut'];
    }

    public function fetchSAASCycles(){
        $db = new DatabaseManager();
	 	$pdo         = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_INVOICE_CYCLES . "()";
        $arrResult   = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        return $arrResult;
    }

    public function fetchInvoiceReminders($invId){
        $db = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_INVOICE_REMINDERS . "(".$invId.")";
        $arrResult   = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        return $arrResult;
    }

    public function rescheduleInvoice($recheduleDate,$invId){
        $db = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $sp_params = "'".$recheduleDate."',
                     '".$invId."'";
        $queryCallSP = "CALL " . speedConstants::SP_RESCHEDULE_INVOICE . "(".$sp_params.")";
        $arrResult   = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        return $arrResult;
    }

    public function approveInvoice($obj){
        $today = date('Y-m-d H:i:s');
        $db = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $sp_params = "'".$obj->invId."',
                     '".GetLoggedInUserId()."',
                     '".$today."',
                     '".$obj->nextInvDate."',
                     '".$obj->startDate."',
                     '".$obj->endDate."',@retInvIdOut";
        $queryCallSP = "CALL " . speedConstants::SP_APPROVE_INVOICE . "(".$sp_params.")";
        $arrResult   = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @retInvIdOut AS invoiceId";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        if (count($outputResult) > 0) {
            $invId = $outputResult["invoiceId"];
            $response =  array();
            $response['status']='ok';
            $response['invoiceId']=$invId;
            echo json_encode($response);
        }
        return $arrResult;
    }

    public function raiseInvoice($id){

    }

    public function fetchInvoiceProducts(){
        $db = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_INVOICE_PRODUCTS . "()";
        $arrResult   = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        return $arrResult;
    }

}
