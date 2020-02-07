<?php

require_once '../../lib/model/VOCustomer.php';
require_once '../../lib/system/Validator.php';
require_once '../../lib/system/DatabaseManager.php';
require_once '../../lib/system/Date.php';
require_once '../../lib/system/Sanitise.php';

class TeamManager {
    private $_databaseManager = null;

    public function __construct() {
        if ($this->_databaseManager == null) {
            $this->_databaseManager = new DatabaseManager();
        }
        $this->customerno = -1;
        $this->todaysdate = date("Y-m-d H:i:s");
    }

    // <editor-fold defaultstate="collapsed" desc="Support">
    public function getAllContactPerson($cust, $type) {
        $personlist = array();
        $pdo = $this->_databaseManager->CreatePDOConn();
        //Prepare parameters
        $sp_params = "'" . $cust . "'";
        if ($type == 1) {
            $queryCallSP = "CALL " . speedConstants::SP_GET_CONTACT_PERSON_OWNER . "($sp_params)";
        } elseif ($type == 2) {
            $queryCallSP = "CALL " . speedConstants::SP_GET_CONTACT_PERSON_ACCOUNT . "($sp_params)";
        } elseif ($type == 3) {
            $queryCallSP = "CALL " . speedConstants::SP_GET_CONTACT_PERSON_COORDINATOR . "($sp_params)";
        }

        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);

        if (!empty($arrResult)) {
            foreach ($arrResult as $data) {
                $personlist['person_name'] = $data['person_name'];
                $personlist['cp_email1'] = $data['cp_email1'];
                $personlist['cp_phone1'] = $data['cp_phone1'];
            }
            return $personlist;
        } else {
            $contact = NULL;
        }
    }

// </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Account">
    public function getLedgerMapCust($cust) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $sp_params = "'" . $cust . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_LEDGER_MAP_CUST . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
        $customer = array();
        if (!empty($arrResult)) {
            foreach ($arrResult as $data) {
                $customerno['ledgerid'] = $data['ledgerid'];

                $customer[] = $customerno;
            }
        }
        return $customer;
    }

    public function getAllVehicleidForCustomer($cust) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $sp_params = "'" . $cust . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_ALL_VEHICLEID_FOR_CUST . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
        $customer = array();
        if (!empty($arrResult)) {
            foreach ($arrResult as $data) {
                $customerno['vehicleid'] = $data['vehicleid'];
                $customerno['vehicleno'] = $data['vehicleno'];
                $customer[] = $customerno;
            }
        }
        return $customer;
    }

    public function isLedgerPerVehicleId($vehicleid, $cust) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $sp_params = "'" . $vehicleid . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_LEDGER_FOR_VEHICLEID . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
        $flag = TRUE;

        if (!empty($arrResult)) {
            $flag = TRUE;
        } else {
            $flag = FALSE;
        }
        return $flag;
    }

    public function getPendingInvoices($cust) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $sp_params = "'" . $cust . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_PENDING_INVOICES . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
        $data1 = array();
        if (!empty($arrResult)) {
            foreach ($arrResult as $data) {
                $data1['count'] = $data['pending_invoices'];
            }
        }
        return $data1;
    }

    // function getInvoices($id) {
    //     $pdo = $this->_databaseManager->CreatePDOConn();
    //     $sp_params = "'" . $id . "'";
    //     $queryCallSP = "CALL " . speedConstants::SP_GET_INVOICE . "($sp_params)";
    //     $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
    //     //  foreach ($arrResult as &$record) {
    //     //     $record['value'] = $record['invoiceid'] . " - " . $record['customercompany'];
    //     // }
    //     $this->_databaseManager->ClosePDOConn($pdo);
    //     return $arrResult;
    // }

    public function getInvoices($ledgerid, $invoiceid) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $sp_params = "'" . $ledgerid . "'"
            . ",'" . $invoiceid . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_INVOICE . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        //  foreach ($arrResult as &$record) {
        //     $record['value'] = $record['invoiceid'] . " - " . $record['customercompany'];
        // }
        $this->_databaseManager->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function getPendingRenewals($cust) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime("+15 days"));
        $sp_params = $cust . ",'" . $start_date . "'"
            . ",'" . $end_date . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_PENDING_RENEWAL . "($sp_params)";

        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);

        if (!empty($arrResult)) {
            $vehiclenos = array();
            $count = 0;
            foreach ($arrResult as $data) {
                array_push($vehiclenos, $data['vehicleno']);
                ++$count;
            }
            $vehiclenos['count'] = $count;
            return $vehiclenos;
        } else {
            return NULL;
        }
    }

    public function getExpiredDevices($cust) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $today = date('Y-m-d');
        $sp_params = $cust . ",'" . $today . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_EXPIRED_DEVICES . "($sp_params)";

        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);

        if (!empty($arrResult)) {
            $devices = array();
            $count = 0;
            foreach ($arrResult as $data) {
                array_push($devices, $data['unitno']);
                ++$count;
            }
            $devices['count'] = $count;
            return $devices;
        } else {
            return NULL;
        }
    }

    public function getWillExpireDevices($cust) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime("+15 days"));
        $sp_params = $cust . ",'" . $start_date . "'"
            . ",'" . $end_date . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_WILL_EXPIRE_DEVICES . "($sp_params)";

        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
        if (!empty($arrResult)) {
            $devices = array();
            $count = 0;
            foreach ($arrResult as $data) {
                array_push($devices, $data['unitno']);
                ++$count;
            }
            $devices['count'] = $count;
            return $devices;
        } else {
            return NULL;
        }
    }

    public function getComHist($cust) {
        $pdo = $this->_databaseManager->CreatePDOConn();

        $queryCallSP = "CALL " . speedConstants::SP_GET_SMS_DETAILS_CONSUME_YESTERDAY . "($cust);";
//        echo $queryCallSP;
        //        die();
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);

        if (!empty($arrResult)) {
            $detail = array();
            foreach ($arrResult as $data) {
                $detail['phone'] = $data['phone'];
                $detail['message'] = $data['message'];
                $detail['timesent'] = $data['timesent'];
                $array1[] = $detail;
            }
            return $array1;
        } else {
            return NULL;
        }
    }

    // </editor-fold>

    //<editor-fold defaultstate="collapsed" desc="CashFlow">
    public function insertCategory($objCategory) {
        $categotyId = 0;
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            /* Prepare SP Parameters */
            $sp_params = "'" . $objCategory->category . "'"
            . "," . $objCategory->teamid . ""
            . ",'" . $this->todaysdate . "'"
                . "," . "@categoryid";
            $query = $this->_databaseManager->PrepareSP(speedConstants::SP_INSERT_CATEGORY, $sp_params);
            $pdo->query($query);
            $outputParamsQuery = "SELECT @categoryid AS categoryid";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            if (count($outputResult) > 0) {
                $categotyId = $outputResult["categoryid"];
            }
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, speedConstants::MODULE_TEAM, __FUNCTION__);
        }
        return $categotyId;
    }

    public function updateCategory($objCategory) {
        $noOfAffectedRows = 0;
        try {
            $sp_params = "'" . $objCategory->categoryid . "'"
            . ",'" . $objCategory->category . "'"
            . "," . $objCategory->teamid . ""
            . ",'" . $this->todaysdate . "'";
            $query = $this->_databaseManager->PrepareSP(speedConstants::SP_UPDATE_CATEGORY, $sp_params);
            $queryResult = $this->_databaseManager->executeQuery($query);
            $noOfRowAffected = $this->_databaseManager->get_affectedRows();
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, speedConstants::MODULE_TEAM, __FUNCTION__);
        }
        return $noOfAffectedRows;
    }

    public function deleteCategory($objCategory) {
        $noOfAffectedRows = 0;
        try {
            $sp_params = "'" . $objCategory->categoryid . "'"
            . "," . $objCategory->teamid . ""
            . ",'" . $this->todaysdate . "'";
            $query = $this->_databaseManager->PrepareSP(speedConstants::SP_DELETE_CATEGORY, $sp_params);
            $queryResult = $this->_databaseManager->executeQuery($query);
            $noOfRowAffected = $this->_databaseManager->get_affectedRows();
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, speedConstants::MODULE_TEAM, __FUNCTION__);
        }
        return $noOfAffectedRows;
    }

    public function getCategory($objCategory) {
        $arrResult = null;
        $objCategory->categoryid = isset($objCategory->categoryid) ? $objCategory->categoryid : 0;
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            //Prepare parameters
            $sp_params = "" . $objCategory->categoryid . "";
            $query = $this->_databaseManager->PrepareSP(speedConstants::SP_GET_CATEGORY, $sp_params);
            $arrResult = $pdo->query($query)->fetchall(PDO::FETCH_ASSOC);
            $this->_databaseManager->ClosePDOConn($pdo);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
        }
        return $arrResult;
    }

    public function insertBankStatement($objStatement) {
        $statementId = 0;
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            /* Prepare SP Parameters */
            $sp_params = "'" . $objStatement->transaction_datetime . "'"
            . ",'" . $objStatement->details . "'"
            . ",'" . $objStatement->remarks . "'"
            . "," . $objStatement->transaction_type . ""
            . "," . $objStatement->categoryid . ""
            . ",'" . $objStatement->amount . "'"
            . "," . $objStatement->teamid . ""
            . ",'" . $this->todaysdate . "'"
                . "," . "@statementid";
            //$query = "CALL " . speedConstants::SP_GET_WILL_EXPIRE_DEVICES . "($sp_params)";
            $query = $this->_databaseManager->PrepareSP(speedConstants::SP_INSERT_BANK_STATEMENT, $sp_params);
            $pdo->query($query);
            $outputParamsQuery = "SELECT @statementid AS statementid";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            if (count($outputResult) > 0) {
                $statementId = $outputResult["statementid"];
            }
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, speedConstants::MODULE_TEAM, __FUNCTION__);
        }
        return $statementId;
    }

    public function updateBankStatement($objStatement) {
        $noOfAffectedRows = 0;
        try {
            $sp_params = "'" . $objStatement->statementid . "'"
            . ",'" . $objStatement->transaction_datetime . "'"
            . ",'" . $objStatement->details . "'"
            . ",'" . $objStatement->remarks . "'"
            . "," . $objStatement->transaction_type . ""
            . "," . $objStatement->categoryid . ""
            . "," . $objStatement->amount . ""
            . "," . $objStatement->teamid . ""
            . ",'" . $this->todaysdate . "'";
            $query = $this->_databaseManager->PrepareSP(speedConstants::SP_UPDATE_BANK_STATEMENT, $sp_params);
            $queryResult = $this->_databaseManager->executeQuery($query);
            $noOfRowAffected = $this->_databaseManager->get_affectedRows();
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, speedConstants::MODULE_TEAM, __FUNCTION__);
        }
        return $noOfAffectedRows;
    }

    public function deleteBankStatement($objStatement) {
        $noOfAffectedRows = 0;
        try {
            $sp_params = "'" . $objStatement->statementid . "'"
            . "," . $objStatement->teamid . ""
            . ",'" . $this->todaysdate . "'";
            echo $query = $this->_databaseManager->PrepareSP(speedConstants::SP_DELETE_BANK_STATEMENT, $sp_params);
            $queryResult = $this->_databaseManager->executeQuery($query);
            $noOfRowAffected = $this->_databaseManager->get_affectedRows();
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, speedConstants::MODULE_TEAM, __FUNCTION__);
        }
        return $noOfAffectedRows;
    }

    public function getBankStatement($objStatement) {
        $arrResult = null;
        $objStatement->statementid = isset($objStatement->statementid) ? $objStatement->statementid : 0;
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            //Prepare parameters
            $sp_params = "'" . $objStatement->statementid . "'"
            . ",'" . $objStatement->transaction_datetime_from . "'"
            . ",'" . $objStatement->transaction_datetime_to . "'"
            . ",'" . $objStatement->transaction_type . "'"
            . ",'" . $objStatement->categoryid . "'";
            //echo $this->_databaseManager->PrepareSP(speedConstants::SP_GET_BANK_STATEMENT, $sp_params);
            $arrResult = $pdo->query($this->_databaseManager->PrepareSP(speedConstants::SP_GET_BANK_STATEMENT, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
            $this->_databaseManager->ClosePDOConn($pdo);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, speedConstants::MODULE_TEAM, __FUNCTION__);
        }
        return $arrResult;
    }

    public function addStatementToTally($objStatement) {
        $noOfAffectedRows = 0;
        try {
            $sp_params = "'" . $objStatement->statementid . "'"
            . "," . $objStatement->teamid . ""
            . ",'" . $this->todaysdate . "'";
            $query = $this->_databaseManager->PrepareSP(speedConstants::SP_ADD_BANK_STATEMENT_TO_TALLY, $sp_params);
            $queryResult = $this->_databaseManager->executeQuery($query);
            $noOfRowAffected = $this->_databaseManager->get_affectedRows();
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, speedConstants::MODULE_TEAM, __FUNCTION__);
        }
        return $noOfAffectedRows;
    }

    public function add_ticket($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->ticket_title . "'"
        . ",'" . $obj->tickettype . "'"
        . ",'" . $obj->priority . "'"
        . ",'" . $obj->ticketdesc . "'"
        . ",'" . 0 . "'"
        . ",'" . $obj->ticketcust . "'"
        . ",'" . Sanitise::DateTime($obj->todaysdate) . "'"
        . ",'" . $obj->ticket_allot . "'"
        . ",'" . Sanitise::DateTime($obj->raiseondatetime) . "'"
        . ",'" . $obj->expectedCloseDate . "'"
        . ",'" . $obj->sendticketmail . "'"
        . ",'" . $obj->ticketmailid . "'"
        . ",'" . $obj->ccemailids . "'"
        . ",'" . $obj->createby . "'"
        . ",'" . $obj->ticket_allot . "'"
        . ",'" . $obj->platform . "'"
        . ",'" . GetLoggedInUserId() . "'"
        . ",'" . $obj->prodId . "'"
        . ",'" . $obj->docketId . "'"
        . ",'" . $obj->ecdToBeUpdatedBy . "'"
            . ",@is_executed,@ticketid,@tickettypename,@customercompany,@priorityname,@allottoemail,@createby,@departmentId";
        $queryCallSP = "CALL " . speedConstants::SP_INSERT_TICKET . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @is_executed AS is_executed,@ticketid AS ticketid,@tickettypename AS tickettypename,@priorityname AS priorityname,@allottoemail AS allottoemail";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $outputResult;
    }

    public function getTickets($createBy) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $createBy . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_TICKETS_TEAM . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        return $arrResult;
    }

    public function addNote($ticketid, $note, $create_by, $today) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $ticketid . "','" . $note . "','" . $create_by . "','" . $today . "',@currentNoteId";
        $queryCallSP = "CALL " . speedConstants::SP_INSERT_NOTE . "($sp_params)";

        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        return $arrResult;
    }

    public function getNotes($ticketid, $prodId) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $ticketid . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_NOTES . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        foreach ($arrResult as &$row) {
            $row['name'] = isset($row['name']) ? $row['name'] : $row['realname'];
        }
        return $arrResult;
    }

    public function updateTicket($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->customerid . "'
        ,'" . $obj->priorityid . "'
        ,'" . $obj->sendemailcust . "'
        ,'" . $obj->customeremailids . "'
        ,'" . $obj->ccemailids . "'
        ,'" . $obj->eclosedate . "'
        ,'" . $obj->addcount . "'
        ,'" . $obj->ticketid . "'
        ,'" . $obj->ticketdesc . "'
        ,'" . $obj->allotFrom . "'
        ,'" . $obj->allotTo . "'
        ,'" . $obj->ticketstatus . "'
        ,'" . $obj->createdby . "'
        ,'" . $obj->today . "'
        ,'" . $obj->created_type . "'
        ,'" . $obj->prodId . "'
        ,@ticketidOut,@is_executed,@tickettypename,@customercompany,@priorityname,@allottoemail,@createby";
        $queryCallSP = "CALL " . speedConstants::SP_UPDATE_TICKET_TEAM . "($sp_params)";

        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $outQuery = "SELECT @is_executed AS is_executed,@ticketidOut AS ticketid,@tickettypename AS tickettypename,@customercompany AS customercompany,@priorityname AS priorityname,@allottoemail AS allottoemail,@createby AS createby";

        $arrResult = $pdo->query($outQuery)->fetchall(PDO::FETCH_ASSOC);
    }

    //<editor-fold>

    public function getUnmappedvehicles($customerno) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();

        $queryCallSP = "CALL " . speedConstants::SP_GET_UNMAPPED_VEHICLE_ANALYSIS . "($customerno)";

        $result = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getLedger($customerno) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $queryCallSP = "CALL " . speedConstants::SP_GET_LEDGER . "('" . $customerno . "')";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        foreach ($arrResult as &$record) {
            $record['value'] = $record['ledgerid'] . " - " . $record['ledgername'];
        }
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function get_Pending_Invoices($ledgerid) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $queryCallSP = "CALL " . speedConstants::SP_GET_PENDING_INVOICE . "('" . $ledgerid . "')";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    /* for credit note starts*/

    public function get_Invoices($customerid) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $queryCallSP = "CALL " . speedConstants::SP_GET_INVOICE . "('" . $customerid . "')";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        // print_r($arrResult); exit;
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    /* credit note end */

    public function get_PaidInvoices($ledgerid) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $queryCallSP = "CALL " . speedConstants::SP_GET_PAID_INVOICE . "('" . $ledgerid . "')";

        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function getInvoice_Payments($invoiceid) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();

        $payments = array();

        $sp_params = "'" . $invoiceid . "'";

        $queryCallSP = "CALL " . speedConstants::SP_GET_INVOICE_PAYMENT . "($sp_params)";

        $result = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);

        foreach ($result as $data) {
            $data['ip_id'] = $data['ip_id'];
            $data['paid_amt'] = $data['paid_amt'];
            $data['payment_mode'] = $data['payment_mode'];
            $data['paymentdate'] = date("d-m-Y", strtotime($data['paymentdate']));
            $data['cheque_no'] = $data['cheque_no'];
            $data['bank_name'] = $data['bank_name'];
            $data['bank_branch'] = $data['bank_branch'];
            $data['cheque_date'] = date("d-m-Y", strtotime($data['cheque_date']));
            $payments[] = $data;
        }

        return $payments;
    }

    public function getInvoice_Payment_Subdetails($invoiceid) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $payments = array();
        $sp_params = "'" . $invoiceid . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_INVOICE_SUB_PAYMENT . "($sp_params)";
        $result = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getPayment_mode() {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();

        $queryCallSP = "CALL " . speedConstants::SP_GET_PAYMENT_MODE;
        $result = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);

        return $result;
    }

    public function insert_invoice_payment($payment_obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $created_on = date("Y-m-d H:i:s");

        $executed = 1;

        $sp_params = "'" . $payment_obj->customerno . "'" .
        ",'" . $payment_obj->invoiceid . "'" .
        ",'" . $payment_obj->invoiceno . "'" .
        ",'" . $payment_obj->payment_mode . "'" .
        ",'" . $payment_obj->payment_date . "'" .
        ",'" . $payment_obj->cheque_no . "'" .
        ",'" . $payment_obj->bank_name . "'" .
        ",'" . $payment_obj->bank_branch . "'" .
        ",'" . $payment_obj->cheque_date . "'" .
        ",'" . $payment_obj->new_payment_amount . "'" .
        ",'" . $payment_obj->new_tds . "'" .
        ",'" . $payment_obj->new_unpaid_amount . "'" .
        ",'" . GetLoggedInUserId() . "'" .
            ",'" . $created_on .
            "',@isExecutedOut";
        //print_r($obj);
        $queryCallSP = "CALL " . speedConstants::SP_INSERT_INVOICE_PAYMENT . "(" . $sp_params . ")";

        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        if ($outputResult['isExecutedOut'] == 1) {
            return $executed;
        } else {
            $executed = 0;
            return $executed;
        }
    }

    public function insert_payment_collection($payment_obj) {
        $db = new DatabaseManager();
        $pdo = $db->createPDOConn();
        $created_on = date("Y-m-d H:i:s");

        $executed = 1;

        $sp_params = "'" . $payment_obj->invoiceid . "'" .
        ",'" . $payment_obj->customerno . "'" .
        ",'" . $payment_obj->payment_mode . "'" .
        ",'" . $payment_obj->payment_date . "'" .
        ",'" . $payment_obj->cheque_number . "'" .
        ",'" . $payment_obj->bank_name . "'" .
        ",'" . $payment_obj->bank_branch . "'" .
        ",'" . $payment_obj->cheque_date . "'" .
        ",'" . $payment_obj->cheque_status . "'" .
        ",'" . $payment_obj->paid_amount . "'" .
        ",'" . $payment_obj->status . "'" .
        ",'" . $payment_obj->collectedby . "'" .
        ",'" . $payment_obj->remark . "'" .
        ",'" . GetLoggedInUserId() . "'" .
            ",'" . $created_on .
            "',@isExecutedOut";
        //print_r($obj);
        $queryCallSP = "CALL " . speedConstants::SP_INSERT_PAYMENT_COLLECTION . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        if ($outputResult['isExecutedOut'] == 1) {
            return $executed;
        } else {
            $executed = 0;
            return $executed;
        }
    }

    public function insert_credit_note($payment_obj) {
        $db = new DatabaseManager();
        $pdo = $db->createPDOConn();
        $created_on = date("Y-m-d H:i:s");
        $payment_obj->requested_date = !empty($payment_obj->requested_date) ? $payment_obj->requested_date : 0;
        $payment_obj->approved_date = !empty($payment_obj->approved_date) ? $payment_obj->approved_date : 0;
        $payment_obj->inv_date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $payment_obj->inv_date)));

        $executed = 1;
        $sp_params = "'" . $payment_obj->customerno . "'" .
        ",'" . $payment_obj->ledgerid . "'" .
        ",'" . $payment_obj->inv_amount . "'" .
        ",'" . $payment_obj->invoiceno . "'" .
        ",'" . $payment_obj->credit_amount . "'" .
        ",'" . $payment_obj->reason . "'" .
        ",'" . $payment_obj->status . "'" .
        ",'" . $payment_obj->inv_date . "'" .
        ",'" . $payment_obj->requested_date . "'" .
        ",'" . $payment_obj->approved_date . "'" .
        ",'" . GetLoggedInUserId() . "'" .
            ",@isExecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_INSERT_CREDIT_NOTE . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        if ($outputResult['isExecutedOut'] == 1) {
            return $executed;
        } else {
            $executed = 0;
            return $executed;
        }
    }

    public function update_credit_note($payment_obj) {
        $db = new DatabaseManager();
        $pdo = $db->createPDOConn();
        $updated_on = date("Y-m-d H:i:s");
        $payment_obj->requested_date = !empty($payment_obj->requested_date) ? $payment_obj->requested_date : 0;
        $payment_obj->approved_date = !empty($payment_obj->approved_date) ? $payment_obj->approved_date : 0;

        $executed = 1;
        $sp_params = "'" . $payment_obj->credit_note_id . "'" .
        ",'" . $payment_obj->customerno . "'" .
        ",'" . $payment_obj->ledgerid . "'" .
        ",'" . $payment_obj->invoiceno . "'" .
        ",'" . $payment_obj->credit_amount . "'" .
        ",'" . $payment_obj->reason . "'" .
        ",'" . $payment_obj->edit_status . "'" .
        ",'" . $payment_obj->requested_date . "'" .
        ",'" . $payment_obj->approved_date . "'" .
        ",'" . GetLoggedInUserId() . "'" .
            ",'" . $updated_on . "'" .
            ",@isExecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_UPDATE_CREDIT_NOTE . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        if ($outputResult['isExecutedOut'] == 1) {
            return $executed;
        } else {
            $executed = 0;
            return $executed;
        }
    }

    public function fetch_Payment_Mapping($invoice_payment_id) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $queryCallSP = "CALL " . speedConstants::SP_GET_INVOICE_PAYMENT_MAPPING . "('" . $invoice_payment_id . "')";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function fetch_Payment_Collection($payment_id) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $queryCallSP = "CALL " . speedConstants::SP_GET_PAYMENT_COLLECTION . "('" . $payment_id . "')";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function update_Invoice_Payment($payment_obj) {
        $updated_on = date("Y-m-d H:i:s");
        $executed = 1;

        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $payment_obj->invoice_payment_id . "'" .
        ",'" . $payment_obj->payment_date . "'" .
        ",'" . $payment_obj->cheque_no . "'" .
        ",'" . $payment_obj->bank_name . "'" .
        ",'" . $payment_obj->bank_branch . "'" .
        ",'" . $payment_obj->cheque_date . "'" .
        ",'" . $payment_obj->new_payment_amount . "'" .
        ",'" . $payment_obj->new_tds . "'" .
        ",'" . $payment_obj->new_unpaid_amount . "'" .
        ",'" . $payment_obj->cheque_status . "'" .
        ",'" . GetLoggedInUserId() . "'" .
            ",'" . $updated_on .
            "',@isExecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_EDIT_INVOICE_PAYMENT . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        if ($outputResult['isExecutedOut'] == 1) {
            return $executed;
        } else {
            $executed = 0;
            return $executed;
        }
        $db->ClosePDOConn($pdo);
    }

    public function update_Payment_Collection($payment_obj) {
        $updated_on = date("Y-m-d H:i:s");
        $executed = 1;
        // if (!empty($payment_obj->invoiceid) && $payment_obj->invoiceid==0 ){
        //    $payment_obj->invoiceid='';
        // }

        $db = new DatabaseManager();
        $pdo = $db->createPDOConn();
        $sp_params = "'" . $payment_obj->invoiceid . "'" .
        ",'" . $payment_obj->customerno . "'" .
        ",'" . $payment_obj->payment_id . "'" .
        ",'" . $payment_obj->pay_mode . "'" .
        ",'" . $payment_obj->payment_date . "'" .
        ",'" . $payment_obj->cheque_no . "'" .
        ",'" . $payment_obj->bank_name . "'" .
        ",'" . $payment_obj->bank_branch . "'" .
        ",'" . $payment_obj->cheque_date . "'" .
        ",'" . $payment_obj->cheque_status . "'" .
        ",'" . $payment_obj->paid_amount . "'" .
        ",'" . $payment_obj->status . "'" .
        ",'" . $payment_obj->collectedby . "'" .
        ",'" . $payment_obj->remark . "'" .
        ",'" . GetLoggedInUserId() . "'" .
            ",'" . $updated_on .
            "',@isExecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_EDIT_PAYMENT_COLLECTION . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        if ($outputResult['isExecutedOut'] == 1) {
            return $executed;
        } else {
            $executed = 0;
            return $executed;
        }
        $db->ClosePDOConn($pdo);
    }

    public function delete_Payment_Collection($obj) {
        $todaydatetime = date("Y-m-d H:i:s");
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $obj->payment_id . "'" .
        ",'" . $todaydatetime . "'" .
        ",'" . GetLoggedInUserId() . "'" .
            "," . "@isExecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_DELETE_PAYMENT_COLLECTION . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        $result = $outputResult['isExecutedOut'];
        return $result;
    }

    public function getInvoice_Payments_Old($invoiceid) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();

        $payments_old = array();

        $sp_params = "'" . $invoiceid . "'";

        $queryCallSP = "CALL " . speedConstants::SP_GET_INVOICE_PAYMENT_OLD . "($sp_params)";

        $result = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);

        foreach ($result as $data) {
            $data['invoiceid'] = $data['invoiceid'];
            $data['paid_amt'] = $data['paid_amt'];
            if ($data['cheque_no'] == '') {
                $data['cheque_no'] = 0;
            } else {
                $data['cheque_no'] = $data['cheque_no'];
            }
            $data['pay_mode'] = $data['pay_mode'];

            if ($data['paymentdate'] == '0000-00-00') {
                $data['paymentdate'] = '';
            } else {
                $data['paymentdate'] = date("d-m-Y", strtotime($data['paymentdate']));
            }
            $data['bank_name'] = $data['bank_name'];
            $data['branch'] = $data['branch'];
            $data['cheque_date'] = date("d-m-Y", strtotime($data['paymentdate']));
            $payments_old[] = $data;
        }
        return $payments_old;
    }

    public function fetch_DeviceLocation() {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();

        $queryCallSP = "CALL " . speedConstants::SP_GET_DEVICE_LOCATION;
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function fetch_unit_withCustomer($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();

        $sp_params = "'" . $obj->unit_no . "'"
        . ",'" . $obj->to_customer . "'"
            . "," . "@isExists,@vehicleNoVar";

        $queryCallSP = "CALL " . speedConstants::GET_UNIT_WITH_CUSTOMER . "($sp_params)";

        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        $outputParamsQuery = "SELECT @isExists AS is_exists,@vehiclenoVar AS vehicleNo";

        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        return $outputResult;
    }

    public function insert_duplicate_vehicles($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $created_on = date("Y-m-d H:i:s");

        $sp_params = "'" . $obj->unit_no . "'"
        . ",'" . $obj->from_customer . "'"
        . ",'" . $obj->to_customer . "'"
            . "," . "@isDuplicateUnitAdded,@vehiclenoVar";

        $queryCallSP = "CALL " . speedConstants::SP_INSERT_DUPLICATE_UNITS . "($sp_params)";

        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);

        $outputParamsQuery = "SELECT @isDuplicateUnitAdded AS is_added,@vehiclenoVar AS vehicleNo";

        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        if ($outputResult['is_added'] == 1) {
            $relativepath = "../..";
            if (!is_dir($relativepath . '/customer/' . $obj->to_customer . '/unitno/' . $obj->unit_no)) {
                // Directory doesn't exist.
                mkdir($relativepath . '/customer/' . $obj->to_customer . '/unitno/' . $obj->unit_no, 0777, true) or die("Could not create directory");
            }

            if (!is_dir($relativepath . '/customer/' . $obj->to_customer . '/unitno/' . $obj->unit_no . '/sqlite')) {
                // Directory doesn't exist.
                mkdir($relativepath . '/customer/' . $obj->to_customer . '/unitno/' . $obj->unit_no . '/sqlite', 0777, true) or die("Could not create directory");
            }

            $sp_params1 = "'" . $obj->from_customer . "'"
            . ",'" . $obj->to_customer . "'"
            . ",'" . $obj->unit_no . "'"
            . ",'" . GetLoggedInUserId() . "'"
                . ",'" . $created_on . "'";

            $queryCallSP1 = "CALL " . speedConstants::SP_INSERT_DUPLICATE_UNIT_LOG . "($sp_params1)";

            $arrResult = $pdo->query($queryCallSP1)->fetch(PDO::FETCH_ASSOC);

            return $outputResult['vehicleNo'];
        }
    }

    public function fetch_customer_details($customerid) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = $customerid;

        $queryCallSP = "CALL " . speedConstants::SP_VIEW_CUSTOMER_DETAILS . "($sp_params)";

        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);

        return $arrResult;
    }

    public function fetch_devices_info($customerid) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = $customerid;

        $queryCallSP = "CALL " . speedConstants::SP_GET_DEVICES_INFO . "($sp_params)";

        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);

        return $arrResult;
    }

    public function fetch_all_unitno($customerid) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = $customerid;

        $queryCallSP = "CALL " . speedConstants::SP_GET_ALL_UNIT_CUSTOMER . "($sp_params)";

        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);

        return $arrResult;
    }

    public function fetch_unit_details($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $obj->custno . "'"
        . ",'" . $obj->unitno . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_UNIT_DETAILS . "($sp_params)";

        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);

        return $arrResult;
    }

    public function getCustomer_Budgeting($inv_id) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();

        $sp_params = "'" . $inv_id . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_CUSTOMERS_BUDGETING . "($sp_params)";

        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);

        return $arrResult;
    }

    public function getFixedBudget($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $obj->customerno . "'"
        . ",'" . $obj->ledgerid . "'"
        . ",'" . $obj->max_inv_id . "'"
        . ",'" . $obj->start_date . "'"
        . ",'" . $obj->end_date . "'";

        $queryCallSP = "CALL " . speedConstants::SP_GET_FIXED_BUDGET . "($sp_params)";

        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);

        return $arrResult;
    }

    public function getMaxInvoiceId_Budgeting() {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $queryCallSP = "CALL " . speedConstants::SP_GET_MAX_INVOICE_ID;

        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        foreach ($arrResult as $result) {
            $invoiceid = $result['invoiceid'];
            $max_invoice_id[] = $invoiceid;
        }
        return $max_invoice_id;
    }

    public function purchase_Sim($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $today = date("Y-m-d H:i:s");

        $simList = array_filter(explode(',', $obj->simList));

        if (!empty($simList) && is_array($simList)) {
            foreach ($simList as $simNo) {
                $sp_params =
                "'" . $simNo . "'"
                . ",'" . $obj->vendorid . "'"
                . ",'" . $obj->comments . "'"
                . ",'" . GetLoggedInUserId() . "'"
                    . ",'" . $today . "'";

                $queryCallSP = "CALL " . speedConstants::SP_PURCHASE_SIM . "(" . $sp_params . ")";

                $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            }
        }

        return $arrResult;
    }

    public function insert_newsLetterContent($obj) {
        $today = date("Y-m-d H:i:s");
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->title . "'" .
        ",'" . $obj->email_subject . "'" .
        ",'" . $obj->email_body . "'" .
        ",'" . GetLoggedInUserId() . "'" .
            ",'" . $today . "'" .
            "," . "@isExecutedOut,@newsLetterId";
        $queryCallSP = "CALL " . speedConstants::SP_INSERT_NEWS_LETTER_CONTENT . "(" . $sp_params . ")";

        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);

        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut,@newsLetterId as newsLetterId";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        if ($outputResult['isExecutedOut'] == 1) {
            return $outputResult;
        } else {
            return 0;
        }
        $db->ClosePDOConn($pdo);
    }

    public function getnewsLetterContent($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->content_id . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_NEWS_LETTER_CONTENT . "(" . $sp_params . ")";

        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);

        return $arrResult;
    }

    public function update_newsLetterContent($obj) {
        $updated_on = date("Y-m-d H:i:s");

        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->newsLetter_Id . "'" .
        ",'" . $obj->file_path_newsLetter . "'" .
        ",'" . $obj->file_name . "'" .
            ",'" . $updated_on .
            "',@isUpdatedOut";
        $queryCallSP = "CALL " . speedConstants::SP_UPDATE_NEWS_LETTER_CONTENT . "(" . $sp_params . ")";

        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isUpdatedOut as isUpdatedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        return $outputResult;
        $db->ClosePDOConn($pdo);
    }

    public function getEmailContentName($term) {
        $pdo = $this->_databaseManager->CreatePDOConnForTech();
        //Prepare parameters
        $sp_params = "'" . $term . "'";
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_EMAIL_CONTENT_NAME . "($sp_params)";

        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        foreach ($arrResult as &$record) {
            $record['value'] = $record['contentTitle'];
            $record['contentId'] = $record['contentId'];
        }

        return $arrResult;
    }

    public function getNewsLetter_Subs_Details() {
        $pdo = $this->_databaseManager->CreatePDOConnForTech();
        //Prepare parameters
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_NEWS_LETTER_SUBS;

        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $emailArray = array();

        foreach ($arrResult as $result) {
            $result['guid'] = $result['guid'];
            $result['email_id'] = $result['email_id'];
            $emailArray[] = $result;
        }
        return $emailArray;
    }

    public function generate_invoice_links($obj) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        //Prepare parameters

        $sp_params = "'" . $obj->date . "'";
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_INVOICE_LINKS_DETAILS . "($sp_params)";

        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);

        return $arrResult;
    }

    public function fetch_customer_list() {
        $pdo = $this->_databaseManager->CreatePDOConn();
        //Prepare parameters

        $queryCallSP = "CALL " . speedConstants::SP_VIEW_CUSTOMER;

        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $totalunits = 0;
        $srno = 0;
        $customers = array();
        foreach ($arrResult as $row) {
            $customer = new stdClass();
            $srno++;
            $customer->srno = $srno;
            $customer->totalsms = $row["totalsms"];
            $customer->customerno = $row["customerno"];
            $customer->customername = $row["customername"];
            if ($row['isoffline'] == '1') {
                $lock = 'checked';
            } elseif ($row['isoffline'] == '0') {
                $lock = '';
            }
            $companyname = "'" . $row["customercompany"] . "'";
            $customer->lock = '<input type="checkbox" id="lock' . $row["customerno"] . '" onclick="lockCustomer(' . $row["customerno"] . ',' . $companyname . ');" ' . $lock . '/>
                             <input type="hidden" id="lockStatus' . $row["customerno"] . '" value="' . $row["isoffline"] . '"/>';
            $customer->smsleft = $row["smsleft"];
            $customer->customercompany = $row["customercompany"];

            $customer->cunit = $row["cunit"];
            $totalunits += $row["cunit"];
            if ($row["renewal"] == 0) {
                $customer->crenewal = "Not Assigned";
            }
            if ($row["renewal"] == -2) {
                $customer->crenewal = "Closed";
            }
            if ($row["renewal"] == -3) {
                $customer->crenewal = "Lease";
            }
            if ($row["renewal"] == -1) {
                $customer->crenewal = "Demo";
            }
            if ($row["renewal"] == 1) {
                $customer->crenewal = "Monthly";
            }
            if ($row["renewal"] == 3) {
                $customer->crenewal = "Quarterly";
            }
            if ($row["renewal"] == 6) {
                $customer->crenewal = "Six Monthly";
            }
            if ($row["renewal"] == 12) {
                $customer->crenewal = "Yearly";
            }
            if (($row["renewal"] == 0 || $row["renewal"] == 1 || $row["renewal"] == 3 || $row["renewal"] == 6 || $row["renewal"] == 12) && $row['unit_msp'] == 0) {
                $customer->renewalprice = "Not Assigned";
            } else {
                $customer->renewalprice = $row['unit_msp'];
            }
            if ($row["renewal"] == -3 && $row['lease_price'] == 0) {
                $customer->leaseprice = "Not Assigned";
            } elseif ($row["renewal"] == -3 && $row['lease_price'] != 0) {
                $customer->leaseprice = $row['lease_price'];
            } else {
                $customer->leaseprice = "NA";
            }
            if ($row["renewal"] == -3 && $row['lease_duration'] == 0) {
                $customer->leaseduration = "Not Assigned";
            } elseif ($row["renewal"] == -3 && $row['lease_duration'] != 0) {
                $customer->leaseduration = $row['lease_duration'];
            } else {
                $customer->leaseduration = "NA";
            }
            $customer->manager_name = $row['manager_name'];
            $customers[] = $customer;
        }
        return $customers;
    }

    public function get_timezone() {
        $pdo = $this->_databaseManager->CreatePDOConnForTech();
        //Prepare parameters
        $queryCallSP = "CALL " . speedConstants::SP_VIEW_TIMEZONE;
        //echo $queryCallSP;
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $timezoneArray = array();

        foreach ($arrResult as $row) {
            $time = new stdClass();
            $time->tid = $row['tid'];
            $time->zone = $row['timezone'];
            $timezoneArray[] = $time;
        }

        return $timezoneArray;
    }

    public function insert_customer($customerObj) {
        $today = date("Y-m-d");
        $todaydatetime = date("Y-m-d H:i:s");
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $created_on = date("Y-m-d H:i:s");

        $sp_params = "'" . $customerObj->primaryusername . "'" .
        ",'" . $customerObj->custcompany . "'" .
        ",'" . $today . "'" .
        ",'" . $customerObj->custsms . "'" .
        ",'" . $customerObj->custsms . "'" .
        ",'" . $customerObj->custtelephonicalert . "'" .
        ",'" . $customerObj->custtelephonicalert . "'" .
        ",'" . GetLoggedInUserId() . "'" .
        ",'" . $customerObj->cloading . "'" .
        ",'" . $customerObj->cgeolocation . "'" .
        ",'" . $customerObj->ctraking . "'" .
        ",'" . $customerObj->cmaintenance . "'" .
        ",'" . $customerObj->ctempsensor . "'" .
        ",'" . $customerObj->cportable . "'" .
        ",'" . $customerObj->cheirarchy . "'" .
        ",'" . $customerObj->advanced_alert . "'" .
        ",'" . $customerObj->cac . "'" .
        ",'" . $customerObj->cgenset . "'" .
        ",'" . $customerObj->cfuel . "'" .
        ",'" . $customerObj->cdoor . "'" .
        ",'" . $customerObj->crouting . "'" .
        ",'" . $customerObj->cpanic . "'" .
        ",'" . $customerObj->cbuzzer . "'" .
        ",'" . $customerObj->cimmo . "'" .
        ",'" . $customerObj->cimob . "'" .
        ",'" . $customerObj->cdelivery . "'" .
        ",'" . $customerObj->csalesengage . "'" .
        ",'" . $customerObj->cerp . "'" .
        ",'" . $customerObj->cwarehouse . "'" .
        ",'" . $customerObj->ctrace . "'" .
        ",'" . $customerObj->cct . "'" .
        ",'" . $customerObj->ccrm . "'" .
        ",'" . $customerObj->cbooks . "'" .
        ",'" . $customerObj->cradar . "'" .
        ",'" . $customerObj->timezone . "'" .
        ",'" . $customerObj->commercial_details . "'" .
            ",'" . $todaydatetime . "'" .
            "," . "@isExecutedOut,@customerIdOut";
        $queryCallSP = "CALL " . speedConstants::SP_ADD_CUSTOMER . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut,@customerIdOut as customerId";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        if ($outputResult['isExecutedOut'] == 1) {
            return $outputResult['customerId'];
        } else {
            return $outputResult['customerId'] = 0;
        }
    }

    public function insert_contactPerson_details($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $obj->userName . "'" .
        ",'" . $obj->email . "'" .
        ",'" . $obj->phone . "'" .
        ",'" . $obj->customerno . "'" .
            "," . "@isExecutedOut,@contactPersonIdOut";
        $queryCallSP = "CALL " . speedConstants::SP_INSERT_CONTACT_PERSON_DETAILS . "(" . $sp_params . ")";

        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut,@contactPersonIdOut as contactPersonId";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        if ($outputResult['isExecutedOut'] == 1) {
            return $outputResult['contactPersonId'];
        } else {
            return $outputResult['contactPersonId'] = 0;
        }

        $db->ClosePDOConn($pdo);
    }

    public function check_unique_userkey($userkey) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $userkey . "'" .
            "," . "@isExistOut";

        $queryCallSP = "CALL " . speedConstants::SP_CHECK_UNIQUE_USERKEY . "(" . $sp_params . ")";

        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExistOut as isExistOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        return $outputResult['isExistOut'];

        $db->ClosePDOConn($pdo);
    }

    public function insert_user($obj) {
        $todaydatetime = date("Y-m-d H:i:s");

        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $obj->customerno . "'" .
        ",'" . $obj->userName . "'" .
        ",'" . $obj->email . "'" .
        ",'" . $obj->password . "'" .
        ",'" . $obj->userkey1 . "'" .
        ",'" . $obj->userkey2 . "'" .
        ",'" . $obj->tracking . "'" .
        ",'" . $obj->maintenance . "'" .
        ",'" . $obj->hierarchy . "'" .
        ",'" . $obj->moduleId . "'" .
            ",'" . $todaydatetime . "'" .
            "," . "@isExecutedOut,@userIdOut";
        $queryCallSP = "CALL " . speedConstants::SP_ADD_USER . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut,@userIdOut as userIdOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        if ($outputResult['isExecutedOut'] == 1) {
            return $outputResult['userIdOut'];
        } else {
            return $outputResult['userIdOut'] = 0;
        }

        $db->ClosePDOConn($pdo);
    }

    public function getSalesUser($searchstr) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $searchstr . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_SALES_USER . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        return $arrResult;
    }

    public function getDailySalesReport($userid, $dateFrom, $dateTo) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "" . $userid
            . ",'" . $dateFrom
            . "','" . $dateTo . "'";
        $queryCallSP = " CALL " . speedConstants::SP_GET_DAILY_SALES_REPORT_DATA . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        return $arrResult;
    }

    public function getSalesPipelineContact($contactId) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        //Prepare parameters
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_CONTACT_PIPELINE . "(" . $contactId . ")";

        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);

        return $arrResult;
    }

    public function fetch_insertCustomer_URL() {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();

        $queryCallSP = "CALL " . speedConstants::SP_FETCH_PRODUCT_URL;

        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        $urlArray = array();

        foreach ($arrResult as $row) {
            $data['url_path'] = $row['customerCreate_path'];
            $urlArray[] = $data;
        }

        return $urlArray;
    }

    public function get_login_history($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $obj->customer_no . "'" .
        ",'" . $obj->date . "'";

        $queryCallSP = "CALL " . speedConstants::SP_VIEW_LOGIN_HISTORY . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        $historyArray = array();

        foreach ($arrResult as $row) {
            $data['date'] = date("d-m-Y", strtotime($row['created_on']));
            $data['time'] = date("H:i:s A", strtotime($row['created_on']));
            $data['user'] = $row['username'];
            $historyArray[] = $data;
        }
        return $historyArray;
    }

    public function get_login_trends($customerNo) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $customerNo . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_LOGIN_TREND . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        return $arrResult;
    }

    public function get_company_role($department_id) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $customerNo . "'";
        $queryCallSP = "CALL " . speedConstants::SP_COMPANY_ROLE . "(" . $department_id . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        return $arrResult;
    }

    public function insertTeamMember($obj) {
        $todaydatetime = date("Y-m-d H:i:s");

        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $obj->name . "'" .
        ",'" . $obj->phone . "'" .
        ",'" . $obj->email . "'" .
        ",'" . $obj->role . "'" .
        ",'" . $obj->department . "'" .
        ",'" . $obj->company_role . "'" .
        ",'" . $obj->membertype . "'" .
        ",'" . $obj->login . "'" .
        ",'" . $obj->password . "'" .
            "," . "@isExecutedOut,@teamIdOut";
        $queryCallSP = "CALL " . speedConstants::SP_ADD_TEAM_MEMBER . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut,@teamIdOut as teamIdOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        return $outputResult;
        $db->ClosePDOConn($pdo);
    }

    public function fetch_team_list($teamId) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $queryCallSP = "CALL " . speedConstants::SP_GET_TEAM_LIST . "('" . $teamId . "')";
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        $teamMemberArray = array();
        foreach ($arrResult as $row) {
            $data['teamid'] = $row['teamid'];
            $data['departmentId'] = $row['department_id'];
            $data['companyRoleId'] = $row['company_roleId'];
            $data['name'] = $row['name'];
            $data['phone'] = $row['phone'];
            $data['email'] = $row['email'];
            $data['role'] = $row['role'];
            $data['username'] = $row['username'];
            $data['department'] = $row['department'];
            $data['member'] = $row['member'];
            $teamMemberArray[] = $data;
        }
        return $teamMemberArray;
    }

    public function getDepartment() {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_DEPARTMENT;
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function insert_distributor_details($obj) {
        $todaydatetime = date("Y-m-d H:i:s");

        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->customerName . "'" .
        ",'" . $obj->companyName . "'" .
        ",'" . $obj->address . "'" .
        ",'" . $obj->phone . "'" .
        ",'" . $obj->email . "'" .
        ",'" . GetLoggedInUserId() . "'" .
            ",'" . $todaydatetime . "'" .
            "," . "@isExecutedOut,@distributorIdOut";
        $queryCallSP = "CALL " . speedConstants::SP_ADD_DISTRIBUTOR_CUSTOMER_DETAIL . "(" . $sp_params . ")";

        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut,@distributorIdOut as distributorIdOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        if ($outputResult['isExecutedOut'] == 1) {
            return $outputResult['distributorIdOut'];
        } else {
            return $outputResult['distributorIdOut'] = 0;
        }
    }

    public function insert_distributorFileName($obj) {
        $todaydatetime = date("Y-m-d H:i:s");

        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->distributor_id . "'" .
        ",'" . $obj->file_path . "'" .
        ",'" . $obj->file_name . "'" .
        ",'" . $obj->proof_type . "'" .
        ",'" . GetLoggedInUserId() . "'" .
            ",'" . $todaydatetime . "'" .
            "," . "@isExecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_INSERT_DISTRIBUTOR_FILENAME . "(" . $sp_params . ")";

        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        return $outputResult;
    }

    public function get_distributor_details($obj) {
        $db = new DatabaseManager();
        $dcID = '';
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->teamId . "'" .
        ",'" . $obj->dcId . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_DISTRIBUTOR_DETAILS . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        $distributorArray = array();

        foreach ($arrResult as $row) {
            $data['dcId'] = $row['dcId'];
            $data['distributor_name'] = $row['distributor_name'];
            $data['customer_name'] = $row['customer_name'];
            $data['customercompany'] = $row['customercompany'];
            $data['address'] = $row['address'];
            $data['email'] = $row['email'];
            $data['phone'] = $row['phone'];
            $data['addressPath'] = $row['address_proofPath'];
            $data['addressFileName'] = $row['address_proof_fileName'];
            $data['photoPath'] = $row['photo_proof_Path'];
            $data['photoFileName'] = $row['photo_proof_fileName'];
            $distributorArray[] = $data;
        }
        return $distributorArray;
    }

    public function update_distributor_details($obj) {
        $todaydatetime = date("Y-m-d H:i:s");

        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->dc_id . "'" .
        ",'" . $obj->customerName . "'" .
        ",'" . $obj->companyName . "'" .
        ",'" . $obj->address . "'" .
        ",'" . $obj->phone . "'" .
        ",'" . $obj->email . "'" .
        ",'" . GetLoggedInUserId() . "'" .
            ",'" . $todaydatetime . "'" .
            "," . "@isExecutedOut,@distributorIdOut";
        $queryCallSP = "CALL " . speedConstants::SP_EDIT_DISTRIBUTOR_CUSTOMER_DETAIL . "(" . $sp_params . ")";

        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut,@distributorIdOut as distributorIdOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        if ($outputResult['isExecutedOut'] == 1) {
            return $outputResult['distributorIdOut'];
        } else {
            return $outputResult['distributorIdOut'] = 0;
        }
    }

    public function insert_distributorVehicleDetails($obj) {
        $todaydatetime = date("Y-m-d H:i:s");

        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->dcId . "'" .
        ",'" . $obj->vehNo . "'" .
        ",'" . $obj->engNo . "'" .
        ",'" . $obj->chaNo . "'" .
        ",'" . GetLoggedInUserId() . "'" .
            ",'" . $todaydatetime . "'" .
            "," . "@isExecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_INSERT_DISTRIBUTOR_VEHICLE . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        $result = $outputResult['isExecutedOut'];
        return $result;
    }

    public function get_distributorVeh_details($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->teamId . "'" .
        ",'" . $obj->dcId . "'" .
        ",'" . $obj->dvId . "'";
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_DISTRIBUTOR_VEHICLES . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        $distributorVehArray = array();

        foreach ($arrResult as $row) {
            $data['dvId'] = $row['dvId'];
            $data['dcId'] = $row['dcId'];
            $data['vehicleNo'] = $row['vehicleNo'];
            $data['engineNo'] = $row['engineNo'];
            $data['chasisNo'] = $row['chasisNo'];
            $data['createdOn'] = date("d-m-Y", strtotime($row['createdOn']));
            $distributorVehArray[] = $data;
        }
        return $distributorVehArray;
    }

    public function update_distributorVehicleDetails($obj) {
        $todaydatetime = date("Y-m-d H:i:s");

        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->dv_id . "'" .
        ",'" . $obj->vehNo . "'" .
        ",'" . $obj->engNo . "'" .
        ",'" . $obj->chaNo . "'" .
        ",'" . GetLoggedInUserId() . "'" .
            ",'" . $todaydatetime . "'" .
            "," . "@isExecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_EDIT_DISTRIBUTOR_VEHICLE_DETAIL . "(" . $sp_params . ")";

        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        $result = $outputResult['isExecutedOut'];
        return $result;
    }

    public function updateTeamMember($obj) {
        $todaydatetime = date("Y-m-d H:i:s");

        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $obj->teamid . "'" .
        ",'" . $obj->name . "'" .
        ",'" . $obj->phone . "'" .
        ",'" . $obj->email . "'" .
        ",'" . $obj->role . "'" .
        ",'" . $obj->department . "'" .
        ",'" . $obj->company_role . "'" .
        ",'" . $obj->membertype . "'" .
            "," . "@isExecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_EDIT_TEAM_MEMBER . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $result = $outputResult['isExecutedOut'];
        $db->ClosePDOConn($pdo);

        return $result;
    }

    public function update_teamAccount_settings($obj) {
        $todaydatetime = date("Y-m-d H:i:s");

        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $obj->teamid . "'" .
        ",'" . $obj->loginId . "'" .
        ",'" . $obj->password . "'" .
            "," . "@isExecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_EDIT_TEAM_ACC_SETTING . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $result = $outputResult['isExecutedOut'];
        $db->ClosePDOConn($pdo);

        return $result;
    }

    public function insert_item_master($obj) {
        $todaydatetime = date("Y-m-d H:i:s");

        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->itemName . "'" .
        ",'" . $obj->description . "'" .
        ",'" . $obj->hsnCode . "'" .
        ",'" . GetLoggedInUserId() . "'" .
            ",'" . $todaydatetime . "'" .
            "," . "@isExecutedOut,@itemIdOut";
        $queryCallSP = "CALL " . speedConstants::SP_ADD_ITEM_MASTER . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut,@itemIdOut as itemId";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        $result = $outputResult['itemId'];
        return $result;
    }

    public function fetch_itemMaster($teamId) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_ITEM_MASTER . "('" . $teamId . "')";
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        $itemMasterArray = array();
        foreach ($arrResult as $row) {
            $data['imId'] = $row['imId'];
            $data['item_name'] = $row['item_name'];
            $data['description'] = $row['description'];
            $data['hsn_code'] = $row['hsn_code'];
            $data['createdOn'] = date("d-m-Y", strtotime($row['createdOn']));
            $itemMasterArray[] = $data;
        }
        return $itemMasterArray;
    }

    public function update_item_master($obj) {
        $todaydatetime = date("Y-m-d H:i:s");

        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->itemId . "'" .
        ",'" . $obj->itemName . "'" .
        ",'" . $obj->description . "'" .
        ",'" . $obj->hsnCode . "'" .
        ",'" . GetLoggedInUserId() . "'" .
            ",'" . $todaydatetime . "'" .
            "," . "@isExecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_EDIT_ITEM_MASTER . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        $result = $outputResult['isExecutedOut'];
        return $result;
    }

    public function insert_column_itemMasterDetails($obj) {
        $obj->itemName = strtolower($obj->itemName);
        $obj->itemName = str_replace(" ", "_", $obj->itemName);

        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->itemName . "'";
        $queryCallSP = "CALL " . speedConstants::SP_ADD_COLUMN_ITEM_MASTER . "(" . $sp_params . ")";

        $arrResult = $pdo->query($queryCallSP);
        $db->ClosePDOConn($pdo);
    }

    public function rename_column_itemMasterDetails($obj) {
        $obj->itemName = strtolower($obj->itemName);
        $obj->itemName = str_replace(" ", "_", $obj->itemName);

        $obj->temp_itemName = strtolower($obj->temp_itemName);
        $obj->temp_itemName = str_replace(" ", "_", $obj->temp_itemName);

        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->itemName . "'" .
        ",'" . $obj->temp_itemName . "'";
        $queryCallSP = "CALL " . speedConstants::SP_RENAME_COLUMN_ITEM_MASTER . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP);
        $db->ClosePDOConn($pdo);
    }

    public function fetch_itemMasterDetails($customerNo) {
        $db = new DatabaseManager();
        $sp_params = "'" . $customerNo . "'";
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_ITEM_MASTER_DETAILS . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function update_item_masterDetails($obj) {
        $todaydatetime = date("Y-m-d H:i:s");

        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->columnName . "'" .
        ",'" . $obj->value . "'" .
        ",'" . $obj->customerNo . "'" .
            "," . "@isExecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_EDIT_ITEM_MASTER_DETAILS . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        $result = $outputResult['isExecutedOut'];
        return $result;
    }

    public function getSalesUserList($term, $userLoginid) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $term . "','" . $userLoginid . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_SALES_USER_LIST . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        return $arrResult;
    }

    public function insertSalestarget($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $userLoginid = $_SESSION['sessionteamid'];
        $currentDate = date('Y-m-d h:i:s');
        $sp_params = "'" . $obj->teamid . "','"
        . $obj->salesuserloginid . "','"
        . $obj->target_amount . "','"
        . $obj->target_set_for_month . "','"
        . $obj->target_set_for_year . "','"
            . $userLoginid . "','"
            . $currentDate . "'"
        ;
        $queryCallSP = "CALL " . speedConstants::SP_INSERT_SALES_TARGET_AMOUNT . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        return $arrResult;
    }

    public function get_industry() {
        $pdo = $this->_databaseManager->CreatePDOConn();
        //Prepare parameters
        $queryCallSP = "CALL " . speedConstants::SP_VIEW_INDUSTRY;

        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $industryArray = array();

        foreach ($arrResult as $row) {
            $type = new stdClass();
            $type->industryid = $row['industryid'];
            $type->industry_type = $row['industry_type'];
            $industryArray[] = $type;
        }
        return $industryArray;
    }

    public function get_sales_person() {
        $pdo = $this->_databaseManager->CreatePDOConn();
        //Prepare parameters
        $queryCallSP = "CALL " . speedConstants::SP_VIEW_SALES_PERSON;

        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $salesArray = array();

        foreach ($arrResult as $row) {
            $sales = new stdClass();
            $sales->teamid = $row['teamid'];
            $sales->name = $row['name'];
            $salesArray[] = $sales;
        }
        return $salesArray;
    }

    public function get_contactTypeMaster() {
        $pdo = $this->_databaseManager->CreatePDOConn();
        //Prepare parameters
        $queryCallSP = "CALL " . speedConstants::SP_VIEW_CONTACT_TYPEMASTER;

        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $cpArray = array();

        foreach ($arrResult as $row) {
            $cpdata = new stdClass();
            $cpdata->typeid = $row['person_typeid'];
            $cpdata->persontype = $row['person_type'];
            $cpArray[] = $cpdata;
        }
        return $cpArray;
    }

    public function getBusyStatus($teamId) {
        $db = new DatabaseManager();
        $today = date("Y-m-d");
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $teamId . "'" .
            ",'" . $today . "'" .
            "," . "@isExistsOut,@latestTimeOut";
        $queryCallSP = "CALL " . speedConstants::SP_GET_TEAM_BUSY_STATUS . "(" . $sp_params . ")";

        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExistsOut as isExistsOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        $result = $outputResult['isExistsOut'];
        return $result;
    }

    public function insertTeamAttendance($obj) {
        $todaydatetime = date("Y-m-d H:i:s");
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->teamId . "'" .
        ",'" . $obj->check_value . "'" .
        ",'" . $obj->location . "'" .
            ",'" . $todaydatetime . "'" .
            "," . "@isExecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_INSERT_TEAM_ATTENDANCE . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        $result = $outputResult['isExecutedOut'];
        return $result;
    }

    public function fetchElixiaOfficeMembers($officeId) {
        $db = new DatabaseManager();
        $tm = new TeamManager();
        $today = date("Y-m-d");
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $officeId . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_ELIXIA_OFFICE_MEMBERS . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);

        $finalArray = array();
        $x = 1;
        foreach ($arrResult as $row) {
            $data['serial_no'] = $x;
            $data['teamId'] = $row['teamId'];
            $data['name'] = $row['name'];
            $data['phoneExtension'] = $row['phoneExtension'];
            $data['busy_status'] = $tm->getBusyStatus($row['teamId']);
            $x++;
            $finalArray[] = $data;
        }
        $db->ClosePDOConn($pdo);
        return $finalArray;
    }

    public function fetchTeamAttendanceLogs($date) {
        $db = new DatabaseManager();
        $tm = new TeamManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $date . "'";
        echo $queryCallSP = "CALL " . speedConstants::SP_GET_ATTENDANCE_LOGS . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);

        $finalArray = array();
        foreach ($arrResult as $row) {
            $data['name'] = $row['name'];
            $data['checkValue'] = $row['checkValue'];
            $data['createdOn'] = date("d-m-Y H:i:s", strtotime($row['createdOn']));
            $finalArray[] = $data;
        }
        $db->ClosePDOConn($pdo);
        return $finalArray;
    }

    public function get_AllCust_DeviceInfo($teamId) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $teamId . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_ALL_CUST_DEVICE_INFO . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);

        $finalArray = array();
        foreach ($arrResult as $row) {
            $data['customerno'] = $row['customerno'];
            $data['customercompany'] = $row['customercompany'];
            $data['crm_name'] = $row['crm_name'];
            $data['total'] = $row['total'];
            $data['activeCount'] = $row['activeCount'];
            $data['expiredCount'] = $row['expiredCount'];
            $data['expiredCount_soon'] = $row['expiredCount2'];
            $finalArray[] = $data;
        }
        $db->ClosePDOConn($pdo);
        return $finalArray;
    }

    public function get_ActiveDevice_info($customerNo) {
        $db = new DatabaseManager();
        $tm = new TeamManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $customerNo . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_ACTIVE_DEVICE_INFO . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);

        $finalArray = array();
        foreach ($arrResult as $row) {
            $data['unitno'] = $row['unitno'];
            $data['vehicleno'] = $row['vehicleno'];
            $data['status'] = $row['status'];
            $data['start_date'] = date("d-m-Y", strtotime($row['start_date']));
            $data['end_date'] = date("d-m-Y", strtotime($row['end_date']));
            $data['expirydate'] = date("d-m-Y", strtotime($row['expirydate']));
            $data['invoiceno'] = $row['invoiceno'];
            $data['device_invoiceno'] = $row['device_invoiceno'];
            $finalArray[] = $data;
        }
        $db->ClosePDOConn($pdo);
        return $finalArray;
    }

    public function get_ExpiredDevice_info($customerNo) {
        $db = new DatabaseManager();
        $tm = new TeamManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $customerNo . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_EXPIRED_DEVICE_INFO . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);

        $finalArray = array();
        foreach ($arrResult as $row) {
            $data['unitno'] = $row['unitno'];
            $data['vehicleno'] = $row['vehicleno'];
            $data['status'] = $row['status'];
            $data['start_date'] = date("d-m-Y", strtotime($row['start_date']));
            $data['end_date'] = date("d-m-Y", strtotime($row['end_date']));
            $data['expirydate'] = date("d-m-Y", strtotime($row['expirydate']));
            $data['invoiceno'] = $row['invoiceno'];
            $data['device_invoiceno'] = $row['device_invoiceno'];
            $finalArray[] = $data;
        }
        $db->ClosePDOConn($pdo);
        return $finalArray;
    }

    public function get_toBe_ExpDevice_info($customerNo) {
        $db = new DatabaseManager();
        $tm = new TeamManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $customerNo . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_EXPIRING_DEVICE_INFO . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);

        $finalArray = array();
        foreach ($arrResult as $row) {
            $data['unitno'] = $row['unitno'];
            $data['vehicleno'] = $row['vehicleno'];
            $data['sim_status'] = $row['sim_status'];
            $data['status'] = $row['status'];
            $data['start_date'] = date("d-m-Y", strtotime($row['start_date']));
            $data['end_date'] = date("d-m-Y", strtotime($row['end_date']));
            $data['expirydate'] = date("d-m-Y", strtotime($row['expirydate']));
            $data['invoiceno'] = $row['invoiceno'];
            $data['device_invoiceno'] = $row['device_invoiceno'];
            $finalArray[] = $data;
        }
        $db->ClosePDOConn($pdo);
        return $finalArray;
    }

    public function insertProspCustomer($obj) {
        $todaydatetime = date("Y-m-d H:i:s");
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->custName . "'" .
        ",'" . $obj->compName . "'" .
        ",'" . $obj->address . "'" .
        ",'" . $obj->phone . "'" .
        ",'" . $obj->email . "'" .
        ",'" . $obj->remarks . "'" .
        ",'" . $todaydatetime . "'" .
        ",'" . GetLoggedInUserId() . "'" .
            "," . "@lastInsertedOut";
        $queryCallSP = "CALL " . speedConstants::SP_INSERT_PROSP_CUST . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @lastInsertedOut as lastInsertedId";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        $result = $outputResult['lastInsertedId'];
        return $result;
    }

    public function getProspCustomer($prospId) {
        $db = new DatabaseManager();
        $tm = new TeamManager();
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_PROSP_CUSTOMERS . "(" . $prospId . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $finalArray = array();
        foreach ($arrResult as $row) {
            $data['prospectId'] = $row['pcid'];
            $data['customername'] = $row['customername'];
            $data['customercompany'] = $row['customercompany'];
            $data['company_address'] = $row['company_address'];
            $data['email'] = $row['email'];
            $data['phone'] = $row['phone'];
            $data['remarks'] = $row['remarks'];
            $finalArray[] = $data;
        }
        $db->ClosePDOConn($pdo);
        return $finalArray;
    }

    public function updateProspCustomer($obj) {
        $todaydatetime = date("Y-m-d H:i:s");
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->prospId . "'" .
        ",'" . $obj->custName . "'" .
        ",'" . $obj->compName . "'" .
        ",'" . $obj->address . "'" .
        ",'" . $obj->phone . "'" .
        ",'" . $obj->email . "'" .
        ",'" . $obj->remarks . "'" .
        ",'" . $todaydatetime . "'" .
        ",'" . GetLoggedInUserId() . "'" .
            "," . "@isExecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_UPDATE_PROSP_CUST . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        $result = $outputResult['isExecutedOut'];
        return $result;
    }

    public function deleteProspCustomer($obj) {
        $todaydatetime = date("Y-m-d H:i:s");
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->prospId . "'" .
        ",'" . $todaydatetime . "'" .
        ",'" . GetLoggedInUserId() . "'" .
            "," . "@isExecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_DELETE_PROSP_CUST . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        $result = $outputResult['isExecutedOut'];
        return $result;
    }

    public function updateInvoiceHoldStatus($obj) {
        $todaydatetime = date("Y-m-d H:i:s");
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $obj->statusId . "'" .
        ",'" . $obj->customerNo . "'" .
            "," . "@updatedOut";
        $queryCallSP = "CALL " . speedConstants::SP_UPDATE_INVOICE_HOLD_STATUS . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @updatedOut as updatedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        $result = $outputResult['updatedOut'];
        return $result;
    }

    public function getInvoiceHoldStatus($obj) {
        $db = new DatabaseManager();
        $sp_params = "'" . $obj->customerNo . "'" .
        ",'" . $obj->statusId . "'";
        $pdo = $db->CreatePDOConn();
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_INVOICE_HOLD_STATUS . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function getCustomInvStatus($obj) {
        $db = new DatabaseManager();
        $sp_params = "'" . $obj->customerNo . "'" .
        ",'" . $obj->statusId . "'";
        $pdo = $db->CreatePDOConn();
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_CUSTOM_INVOICE_STATUS . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function insertJobOpening($obj) {
        $todaydatetime = date("Y-m-d H:i:s");
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->location . "'" .
        ",'" . $obj->experience . "'" .
        ",'" . $obj->departement . "'" .
        ",'" . $obj->companyRole . "'" .
        ",'" . $obj->requirement . "'" .
        ",'" . $obj->preference . "'" .
        ",'" . $obj->challenges . "'" .
        ",'" . $obj->jobRole . "'" .
        ",'" . $todaydatetime . "'" .
        ",'" . GetLoggedInUserId() . "'" .
            "," . "@lastInsertedOut";
        $queryCallSP = "CALL " . speedConstants::SP_INSERT_JOB_OPENING . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @lastInsertedOut as lastInsertedId";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        $result = $outputResult['lastInsertedId'];
        return $result;
    }

    public function fetchElixiaCarrers($obj) {
        $FinalData = array();
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_ELIXIA_CAREERS . "(" . $obj->careerId . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        foreach ($arrResult as $row) {
            $data['ecId'] = $row['ecId'];
            $data['location'] = $row['location'];
            $data['experience'] = $row['experience'];
            $data['department_Id'] = $row['department_Id'];
            $data['company_role'] = $row['company_role'];
            $data['department'] = $row['department'];
            $data['companyRole'] = $row['companyRole'];
            $data['requirement'] = $row['requirement'];
            $data['preference'] = $row['preference'];
            $data['challenges'] = $row['challenges'];
            $data['job_role'] = $row['job_role'];
            $data['date'] = date("d-m-Y", strtotime($row['updatedOn']));
            $FinalData[] = $data;
        }
        return $FinalData;
    }

    public function updateJobOpening($obj) {
        $todaydatetime = date("Y-m-d H:i:s");
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->careerId . "'" .
        ",'" . $obj->location . "'" .
        ",'" . $obj->experience . "'" .
        ",'" . $obj->departement . "'" .
        ",'" . $obj->companyRole . "'" .
        ",'" . $obj->requirement . "'" .
        ",'" . $obj->preference . "'" .
        ",'" . $obj->challenges . "'" .
        ",'" . $obj->jobRole . "'" .
        ",'" . $todaydatetime . "'" .
        ",'" . GetLoggedInUserId() . "'" .
            "," . "@isExecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_UPDATE_JOB_OPENING . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        $result = $outputResult['isExecutedOut'];
        return $result;
    }

    public function deleteJobOpening($obj) {
        $todaydatetime = date("Y-m-d H:i:s");
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->careerId . "'" .
        ",'" . $todaydatetime . "'" .
        ",'" . GetLoggedInUserId() . "'" .
            "," . "@isExecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_DELETE_JOB_OPENING . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        $result = $outputResult['isExecutedOut'];
        return $result;
    }

    public function fetchJobApplications() {
        $FinalData = array();
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_JOB_APPLICANTS;
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        foreach ($arrResult as $row) {
            $data['jaId'] = $row['jaId'];
            $data['name'] = $row['name'];
            $data['mobile'] = $row['mobile'];
            $data['email'] = $row['email'];
            $data['cover_letter'] = $row['cover_letter'];
            $data['cv_filename'] = $row['cv_filename'];
            $data['cv_filepath'] = $data['cv_filepath'];
            $data['resume'] = $row['cv_filepath'] . $data['cv_filename'];
            $data['department'] = $row['department'];
            $data['companyRole'] = $row['companyRole'];
            $data['date'] = date("d-m-Y", strtotime($row['createdOn']));
            $FinalData[] = $data;
        }
        return $FinalData;
    }

    public function getCollected($term) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $queryCallSP = "CALL " . speedConstants::SP_GET_COLLECTEDBY . "('" . $term . "')";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        foreach ($arrResult as &$record) {
            $record['value'] = $record['teamid'] . " - " . $record['name'];
            $record['tid'] = $record["teamid"];
            $FinalData[] = $record;
        }
        $db->ClosePDOConn($pdo);
        return $FinalData;
    }
}
