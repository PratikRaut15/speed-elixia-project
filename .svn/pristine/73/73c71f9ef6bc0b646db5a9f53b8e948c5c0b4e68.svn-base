<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../../";
}
require_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
class docketManager extends DatabaseManager {
    public function __construct() {
        // Constructor.
        parent::__construct();
    }

    public function insertDocket($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->customerno . "'" .
        ",'" . $obj->raiseOnDateTime . "'" .
        ",'" . $obj->timestamp . "'" .
        ",'" . $obj->purposeId . "'" .
        ",'" . $obj->interactionId . "'" .
        ",'" . $obj->createBy . "'" .
        ",'" . $obj->teamId . "'" .
        ",'" . $obj->i_type . "'" .
        ",'" . $obj->r_type .
            "',@isExecutedOut,@docketId";
        //print_r($obj);
        $queryCallSP = "CALL " . speedConstants::SP_INSERT_DOCKET . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut, @docketId as docketId";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        if ($outputResult['isExecutedOut'] == 1) {
            return $outputResult['docketId'];
        }
        $db->ClosePDOConn($pdo);
    }

    public function editDocket($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->customerno . "'" . ",'" . $obj->raiseOnDateTime . "'" . ",'" . $obj->timestamp . "'" . ",'" . $obj->purposeId . "'" . ",'" . $obj->interactionId . "'" . ",'" . $obj->createBy . "'" . ",'" . $obj->teamId . "'" . ",'" . $obj->i_type . "'" . ",'" . $obj->r_type . "'" . ",'" . $obj->docketId . "'" . ",@isExecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_EDIT_DOCKET . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        if ($outputResult['isExecutedOut'] == 1) {
            echo "Success";
        }
        $db->ClosePDOConn($pdo);
    }

    public function add_ticket($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->ticket_title . "'" .
        ",'" . $obj->tickettype . "'" .
        ",'" . $obj->priority . "'" .
        ",'" . $obj->ticketdesc . "'" .
        ",'" . 0 . "'" .
        ",'" . $obj->ticketcust . "'" .
        ",'" . Sanitise::DateTime($obj->todaysdate) . "'" .
        ",'" . $obj->ticket_allot . "'" .
        ",'" . Sanitise::DateTime($obj->raiseondatetime) . "'" .
        ",'" . $obj->expectedCloseDate . "'" .
        ",'" . $obj->sendticketmail . "'" .
        ",'" . $obj->ticketmailid . "'" .
        ",'" . $obj->ccemailids . "'" .
        ",'" . $obj->createby . "'" .
        ",'" . $obj->ticket_allot . "'" .
        ",'" . $obj->platform . "'" .
        ",'" . GetLoggedInUserId() . "'" .
        ",'" . $obj->prodId . "'" .
        ",'" . $obj->docketId . "'" .
        ",'" . $obj->ecdToBeUpdatedBy . "'" .
            ",@is_executed,@ticketid,@tickettypename,@customercompany,@priorityname,@allottoemail,@createby,@departmentId";
        $queryCallSP = "CALL " . speedConstants::SP_INSERT_TICKET . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @is_executed AS is_executed,@ticketid AS ticketid,@tickettypename AS tickettypename,@customercompany AS customercompany,@priorityname AS priorityname,@allottoemail AS allottoemail,@createby AS createby,@departmentId AS departmentId";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        if ($outputResult['is_executed'] == 1) {
            $messageCust = "<table>
                            <tr><td>Ticket No : </td><td>ET00" . $outputResult['ticketid'] . "</td></tr>
                            <tr><td>Created By : </td><td>" . $outputResult['createby'] . "</td></tr>
                            <tr><td>Title : </td><td>" . $obj->ticket_title . "</td></tr>
                            <tr><td>Customer : </td><td>" . $outputResult['customercompany'] . "</td></tr>
                            <tr><td>Ticket Type : </td><td>" . $outputResult['tickettypename'] . "</td></tr>
                            <tr><td>Priority : </td><td>" . $outputResult['priorityname'] . "</td></tr>
                            <tr><td>Description :</td><td>" . $obj->ticketdesc . "</td></tr>
                            <tr><td>Status :</td><td>Open</td></tr>
                        </table>";
            //send Mail to customer
            //$mailids = new array();
            $strCCMailIds = $obj->ticketEmails_CC;
            $mailid = $obj->ticketEmails;
            $mailids[] = $mailid;
            if (!empty($mailids) && is_array($mailids)) {
                $sendmailidtoteam = $mailids;
                $to = $mailids;
                if ($outputResult['departmentId'] == 5) {
                    if (isset($strCCMailIds)) {
                        $strCCMailIds = $strCCMailIds ? "sanketsheth@elixiatech.com,software@elixiatech.com," . $strCCMailIds : "sanketsheth@elixiatech.com,software@elixiatech.com";
                    }
                } else {
                    if (isset($strCCMailIds)) {
                        $strCCMailIds = $strCCMailIds ? "sanketsheth@elixiatech.com,support@elixiatech.com," . $strCCMailIds : "sanketsheth@elixiatech.com,support@elixiatech.com";
                    }
                }
                $strBCCMailIds = "";
                $attachmentFilePath = "";
                $attachmentFileName = "";
                $subject = "Elixia Speed - Support Ticket No: ET00" . $ticketid1 . " (Customer No: " . $ticketcustid . ")";
                $message .= "<h4> A new support ticket has been created for you. Kindly interact with Elixia team providing the ticket number for further assistance. </h4>";
                $message .= $messageCust;
                sendMailUtil($to, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName);
            }
        }
        $db->ClosePDOConn($pdo);
        return $outputResult['ticketid'];
    }

    public function updateTicket($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->customerid . "'
        ,'" . $obj->priorityid . "'
        ,'" . $obj->sendMail . "'
        ,'" . $obj->ticketMailIds . "'
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
        $queryCallSP = "CALL " . speedConstants::SP_UPDATE_TICKET_TEAM . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @is_executed AS is_executed,@ticketidOut AS ticketid,@tickettypename AS tickettypename,@customercompany AS customercompany,@priorityname AS priorityname,@allottoemail AS allottoemail,@createby AS createby";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        // if($outputResult['is_executed']==1)
        // {
        // $messageCust = "<table>
        //                     <tr><td>Ticket No : </td><td>ET00".$outputResult['ticketid']."</td></tr>
        //                     <tr><td>Created By : </td><td>" . $outputResult['createby']."</td></tr>
        //                     <tr><td>Title : </td><td>" .$obj->ticket_title . "</td></tr>
        //                     <tr><td>Customer : </td><td>".$outputResult['customercompany']."</td></tr>
        //                     <tr><td>Ticket Type : </td><td>".$outputResult['tickettypename']."</td></tr>
        //                     <tr><td>Priority : </td><td>".$outputResult['priorityname']."</td></tr>
        //                     <tr><td>Description :</td><td>".$obj->ticketdesc."</td></tr>
        //                     <tr><td>Status :</td><td>Open</td></tr>
        //                 </table>";
        // }
        return $arrResult;
    }

    public function getTicketTypes($id) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $id . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_TICKET_TYPES . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function fetchDockets($teamid, $docketId) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $teamid . "','" . $docketId . "'";
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_DOCKETS . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        if (count($arrResult) == 0) {
            return null;
        }
        return $arrResult;
    }

    public function fetchTickets($docketId = 0, $ticketId = 0) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $docketId . "','" . $ticketId . "'";
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_TICKETS . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function getInteractionTypes() {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_INTERACTION_TYPES;
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function getPurposeTypes() {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_PURPOSE_TYPES;
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function getCRM() {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_CRM;
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function getCustomers($term) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_CUSTOMERS . "('" . $term . "')";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        foreach ($arrResult as &$record) {
            $record['value'] = $record['customerno'] . " - " . $record['customercompany'];
        }
        //print_r($arrResult);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function getTicketPriorities() {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_PRIORITIES;
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        foreach ($arrResult as &$record) {
            $record['prid'] = $record['prid'];
            $record['priority'] = $record['priority'];
        }
        //print_r($arrResult);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function fetchTeam() {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_TEAM_LIST;
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        //print_r($arrResult);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function getProducts() {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_PRODUCTS;
        $products = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        //print_r($products);
        $db->ClosePDOConn($pdo);
        return $products;
    }

    public function getStatus() {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_STATUS;
        $products = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        //print_r($products);
        $db->ClosePDOConn($pdo);
        return $products;
    }

    // public function getFieldEngineers()
    // {
    //     $db           = new DatabaseManager();
    //     $pdo          = $db->CreatePDOConnForTech();
    //     $queryCallSP     = "CALL " . speedConstants::SP_GET_TEAM_LIST."(2)";
    //     $arrResult       = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
    //     return $arrResult;
    // }
    public function getDetails() {
        $detailsArray = array();
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_PRIORITIES;
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        foreach ($arrResult as &$record) {
            $record['prid'] = $record['prid'];
            $record['priority'] = $record['priority'];
        }
        $detailsArray[0] = $arrResult;
        $queryCallSP = "CALL " . speedConstants::SP_GET_TEAM_LIST;
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $detailsArray[1] = $arrResult;
        $queryCallSP = "CALL " . speedConstants::SP_GET_PRODUCTS;
        $products = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $detailsArray[2] = $products;
        $queryCallSP = "CALL " . speedConstants::SP_GET_TICKET_TYPES . "(0)";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $detailsArray[3] = $arrResult;
        $queryCallSP = "CALL " . speedConstants::SP_GET_STATUS;
        $status = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $detailsArray[4] = $status;
        return $detailsArray;
    }

    public function getTimeslot() {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_VIEW_TIMESLOT;
        $timezones = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $timezones;
    }

    public function getCordinator($obj) {
        $db = new DatabaseManager();
        $sp_params = $obj->customerno;
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_VIEW_CORDINATOR . "(" . $sp_params . ")";
        $cordinators = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $cordinators;
    }

    public function get_vehicle($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->customerno . "'"
        . ",'%" . $obj->term . "%'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_VEHICLE_NUMBER . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        //print_r($arrResult);
        $ret = array();
        foreach ($arrResult as &$record) {
            $record['value'] = $record['vehicleno'];
            $record['uid'] = $record["uid"];
            $record['simcardid'] = $record["id"];
            $ret[] = $record;
        }
        return $ret;
    }

    public function add_r_bucket($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->comments . "'" .
        ",'" . $obj->deviceid . "'" .
        ",'" . $obj->simcardid . "'" .
        ",'" . $obj->bucketcust . "'" .
        ",'" . $obj->apt_date . "'" .
        ",'" . $obj->priority . "'" .
        ",'" . $obj->location . "'" .
        ",'" . $obj->timeslot . "'" .
        ",'" . $obj->operation . "'" .
        ",'" . $obj->details . "'" .
        ",'" . $obj->coordinator . "'" .
        ",'" . $obj->createby . "'" .
        ",'" . $obj->todaysdate . "'" .
        ",'" . $obj->docketId . "'" . ",@bucketid,@isexecuted,@vehicleno,@unitno,@simcardno,@username,@realname,@email,@elixir,@msg";
        $queryCallSP = "CALL " . speedConstants::SP_SUSPECT_UNIT . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP);
        $outputParamsQuery = "SELECT @bucketid AS bucketid
                                    ,@isexecuted AS is_executed
                                    ,@vehicleno AS vehicleno
                                    ,@unitno AS unitno
                                    ,@simcardno AS simcardno
                                    ,@username AS username
                                    ,@realname AS realname
                                    ,@email AS email
                                    ,@elixir AS elixir
                                    ,@msg AS msg";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        if ($outputResult['is_executed'] == 1) {
            $msg = "Suspect Successfully.";
            // Send Email to Customer
            if ($obj->email == 1) {
                $mail = new stdClass();
                $mail->username = $outputResult['username'];
                $mail->realname = $outputResult['realname'];
                $mail->email = $outputResult['email'];
                $mail->vehicleno = $outputResult['vehicleno'];
                $mail->unit = $outputResult['unitno'];
                $mail->sim = $outputResult['simcardno'];
                $mail->elixir = $outputResult['elixir'];
                $mail->comments = $obj->comments;
                $mail->subject = 'Suspect Device Details';
                $mail_status = $this->SendEmailSuspect($mail);
                if ($mail_status == 1) {
                    $msg = "Suspect Successfully.Mail Sent";
                } else {
                    $msg = "Suspect Successfully.Mail not sent";
                }
            }
        } else {
            $msg = "Vehicle not present.";
        }
        $db->ClosePDOConn($pdo);
        return $outputResult['bucketid'];
    }

    public function SendEmailSuspect($mail) {
        // send email
        $arrTo = array($mail->email);
        $strCCMailIds = "";
        $strBCCMailIds = "";
        $subject = $mail->subject;
        $message = file_get_contents('../emailtemplates/suspectDevice.html');
        $message = str_replace("{{REALNAME}}", $mail->realname, $message);
        $message = str_replace("{{VEHICLENO}}", $mail->vehicleno, $message);
        $message = str_replace("{{UNITNO}}", $mail->unitno, $message);
        $message = str_replace("{{SIMCARDNO}}", $mail->simcardno, $message);
        $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
        $message = str_replace("{{COMMENTS}}", $mail->comment, $message);
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $isTemplatedMessage = 1;
        $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
        return $isSmsSent;
    }

    public function add_bucket($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $no_inst = $obj->no_inst;
        for ($x = 1; $x <= $no_inst; $x++) {
            $sp_params = "'" . $obj->apt_date . "'" . ",'" . $obj->bucketcust . "'" . ",'" . $obj->createby . "'" . ",'" . $obj->priority . "'" . ",'" . $obj->location . "'" . ",'" . $obj->timeslot . "'" . ",'" . $obj->details . "'" . ",'" . $obj->coordinator . "'" . ",'" . $obj->todaysdate . "'" . ",'" . $obj->vehicleno . "'" . ",'" . $obj->docketId . "'" . ",@bucketid";
            $queryCallSP = "CALL " . speedConstants::SP_ADD_BUCKET_LIST . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            //print_r($arrResult);
            $outputParamsQuery = "SELECT @bucketid AS bucketid";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            //echo "ADDED bucket".$outputResult['bucketid'];;
            $db->ClosePDOConn($pdo);
        }
        return $outputResult;
    }

    public function updateBucket($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params =
        "'" . $obj->apt_date . "'" .
        ",'" . $obj->coordinator . "'" .
        ",'" . $obj->priorityid . "'" .
        ",'" . $obj->location . "'" .
        ",'" . $obj->timeslot . "'" .
        ",'" . $obj->operationtype . "'" .
        ",'" . $obj->details . "'" .
        ",'" . $obj->sstatus . "'" .
        ",'" . $obj->today . "'" .
        ",'" . $obj->creason . "'" .
        ",'" . $obj->reschedule_date . "'" .
        ",'" . $obj->bucketid . "'";
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_EDIT_BUCKET_LIST . "($sp_params)";
        $result = $pdo->query($queryCallSP);
    }

    public function add_e_bucket($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params =
        "'" . $obj->reschedule_date . "'" .
        ",'" . $obj->customerno . "'" .
        ",'" . $obj->deviceid . "'" .
        ",'" . $obj->simcardid . "'" .
        ",'" . $obj->createby . "'" .
        ",'" . $obj->priority . "'" .
        ",'" . $obj->operationtype . "'" .
        ",'" . $obj->location . "'" .
        ",'" . $obj->timeslot . "'" .
        ",'" . $obj->details . "'" .
        ",'" . $obj->coordinator . "'" .
        ",'" . $obj->todaysdate . "'" .
        ",'" . $obj->vehicleid . "'" .
        ",'" . $obj->docketId . "'" .
            ",@bucketid";
        $queryCallSP = "CALL " . speedConstants::SP_ADD_E_BUCKET_LIST . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        //print_r($arrResult);
        $outputParamsQuery = "SELECT @bucketid AS bucketid";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        //echo "ADDED bucket".$outputResult['bucketid'];;
        $db->ClosePDOConn($pdo);
        $newbucket = new stdClass();
        $newbucket->updateBucket = 1; //because bucketID in modal has to be updated
        $newbucket->bucketID = $outputResult['bucketid'];
        return $newbucket;
    }

    public function add_cordinator($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->coname . "'" . ",'" . $obj->cophone . "'" . ",'" . $obj->cordcust . "'" . ",'" . $obj->createby . "'" . ",'" . $obj->today . "'" . "," . "@lastInsertId";
        $queryCallSP = "CALL " . speedConstants::SP_ADD_CORDINATOR_DETAILS . "($sp_params)";
        $result = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @lastInsertId";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $outputResult = $outputResult['@lastInsertId'];
        return $outputResult;
    }

    public function getBuckets($docketId) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $docketId . "'";
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_BUCKETS . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        //print_r($arrResult);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function fetchDocketInfo($teamId) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $docket = $this->fetchDockets($teamId, 0);
        foreach ($docket as $record) {
            $docketIds[] = $record['docketid'];
            $retArray[$record['docketid']]['info'] = $record;
        }
        $docketIds = implode(",", $docketIds);
        $sp_params = "'" . $docketIds . "'";
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_VIEW_TICKETS . "(" . $sp_params . ")";
        $arrResult0 = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_VIEW_BUCKETS . "(" . $sp_params . ")";
        $arrResult1 = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        foreach ($arrResult0 as $ticket) {
            $retArray[$ticket['docketid']]['tickets'][] = $ticket;
        }
        foreach ($arrResult1 as $bucket) {
            $retArray[$bucket['docketid']]['buckets'][] = $bucket;
        }
        $db->ClosePDOConn($pdo);
        return $retArray;
    }

    public function fetchOverdueTickets() {
        $db = new DatabaseManager();
        $date = date('Y-m-d H:i:s');
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $date . "'";
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_OVERDUE_TICKETS . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        //print_r($arrResult);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }
}