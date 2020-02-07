<?php


if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../../";
}
require_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';


class docketManager extends DatabaseManager
{   
    function __construct()
    {
        // Constructor.
        parent::__construct();
    }



    public function insertDocket($obj)
    {
        $db                = new DatabaseManager();
        $pdo               = $db->CreatePDOConnForTech();
        $sp_params         = "'" . $obj->customerno . "'" . 
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
        $queryCallSP       = "CALL " . speedConstants::SP_INSERT_DOCKET . "(" . $sp_params . ")";
        $arrResult         = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut, @docketId as docketId";
        $outputResult      = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        if ($outputResult['isExecutedOut'] == 1) {
            return $outputResult['docketId'];
        }
        $db->ClosePDOConn($pdo);
    }
    public function editDocket($obj)
    {
        $db                = new DatabaseManager();
        $pdo               = $db->CreatePDOConnForTech();
        $sp_params         = "'" . $obj->customerno . "'" . ",'" . $obj->raiseOnDateTime . "'" . ",'" . $obj->timestamp . "'" . ",'" . $obj->purposeId . "'" . ",'" . $obj->interactionId . "'" . ",'" . $obj->createBy . "'" . ",'" . $obj->teamId . "'" . ",'" . $obj->i_type . "'" . ",'" . $obj->r_type . "'" . ",'" . $obj->docketId . "'" . ",@isExecutedOut";
        $queryCallSP       = "CALL " . speedConstants::SP_EDIT_DOCKET . "(" . $sp_params . ")";
        $arrResult         = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult      = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        if ($outputResult['isExecutedOut'] == 1) {
            echo "Success";
        }
        $db->ClosePDOConn($pdo);
    }
    public function add_ticket($obj)
    {
        $db                = new DatabaseManager();
        $pdo               = $db->CreatePDOConnForTech();
        $sp_params         ="'" . $obj->ticket_title . "'" .
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
                            ",@is_executed,@ticketid,@tickettypename,@customercompany,@priorityname,@allottoemail,@createby,@emaidId,@ccemailId";
        $queryCallSP       = "CALL " . speedConstants::SP_INSERT_TICKET . "($sp_params)";
        $arrResult         = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        
        $outputParamsQuery = "SELECT @is_executed AS is_executed,@ticketid AS ticketid,@tickettypename AS tickettypename,@customercompany AS customercompany,@priorityname AS priorityname,@allottoemail AS allottoemail,@createby AS createby,@emaidId AS emaidId,@ccemailId AS ccemailId";



        $outputResult      = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        if($outputResult['is_executed']==1)
        {
        $messageCust = "<table>
                            <tr><td>Ticket No : </td><td>ET00".$outputResult['ticketid']."</td></tr>
                            <tr><td>Created By : </td><td>" . $outputResult['createby']."</td></tr>
                            <tr><td>Title : </td><td>" .$obj->ticket_title . "</td></tr>
                            <tr><td>Customer : </td><td>".$outputResult['customercompany']."</td></tr>    
                            <tr><td>Ticket Type : </td><td>".$outputResult['tickettypename']."</td></tr> 
                            <tr><td>Priority : </td><td>".$outputResult['priorityname']."</td></tr>              
                            <tr><td>Description :</td><td>".$obj->ticketdesc."</td></tr>   
                            <tr><td>Status :</td><td>Open</td></tr>   
                        </table>";              
          $strCCMailIds =  $outputResult['ccemailId']; 
          $mailid = $outputResult['emaidId'];              
          $mailids[]= $mailid;           
        if (!empty($mailids) && is_array($mailids)) {
            $to = $mailids;
               if($obj->tickettype==1)
                {
                   

                    $strCCMailIds = $strCCMailIds ? speedConstants::accounts_email.",".speedConstants::adminemail.",".speedConstants::support_email.",".$strCCMailIds : speedConstants::accounts_email.",".speedConstants::adminemail.",".speedConstants::support_email;
                    
                    
                }
                else if($obj->tickettype==7){
                    $strCCMailIds = $strCCMailIds ? speedConstants::sales_email.",".speedConstants::adminemail.",".speedConstants::support_email.",".$strCCMailIds : speedConstants::sales_email.",".speedConstants::adminemail.",".speedConstants::support_email;
                }
                else if($obj->tickettype==8 || $obj->tickettype==9) 
                {
                 $strCCMailIds = $strCCMailIds ? speedConstants::software_email.",".speedConstants::adminemail.",".speedConstants::support_email.",".$strCCMailIds : speedConstants::software_email.",".speedConstants::adminemail.",".speedConstants::support_email; 

                }
                else{
                    $strCCMailIds = $strCCMailIds ? speedConstants::adminemail.",".speedConstants::support_email.",".$strCCMailIds : speedConstants::adminemail.",".speedConstants::support_email;
                }
                }
            $strBCCMailIds = $outputResult['allottoemail'];
            $attachmentFilePath = "";
            $attachmentFileName = "";
            $subject = "Elixia Speed - Support Ticket No: ET00" .$outputResult['ticketid']. " (Customer No: " . $obj->ticketcust . ")";
            $message .= "<h4> A new support ticket has been created for you. Kindly interact with Elixia team providing the ticket number for further assistance. </h4>";
            $message .= $messageCust;
            
            
            //sendMailUtil($to, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName);
        }

    
        $db->ClosePDOConn($pdo);
        return $outputResult['ticketid'];
    }
    public function editTicket($obj){
        $db = new DatabaseManager();
        $pdo= $db->CreatePDOConnForTech();
        $sp_params  = "'" .$obj->ticketid. "'
        ,'" .$obj->emailList. "'
        ,'" .$obj->ccList. "'
        ,'" .$obj->allotTo. "'
        ,'" .$obj->status."'
        ,'" .$obj->priority. "'
        ,'" .$obj->ecd. "'
        ,'" .$obj->additionalCharge."'
        ,'" .$obj->createdBy. "'
        ,'" .$obj->created_datetime. "'
        ,@ticketidOut,@is_executed,@tickettypename,@customercompany,@priorityname,@allottoemail,@createby,@emaidId,@ccemailId,@statusOut";
        $queryCallSP = "CALL " . speedConstants::SP_UPDATE_TICKET . "(" . $sp_params . ")";
        $arrResult         = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @is_executed AS is_executed,@ticketidOut AS ticketid,@tickettypename AS tickettypename,@customercompany AS customercompany,@priorityname AS priorityname,@allottoemail AS allottoemail,@createby AS createby,@emaidId AS emaidId,@ccemailId AS ccemailId,@statusOut as status";



        $outputResult      = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        //print_r($outputResult);
        if($outputResult['is_executed']==1){
        //send mail

            $outputResult1=$this->fetchTickets(0,$obj->ticketid);
            $outputResult1=$outputResult1[0];
            //print_r($outputResult1);
            $messageCust = "<table>
                                <tr><td>Ticket No : </td><td>ET00".$outputResult1['ticketid']."</td></tr>
                                <tr><td>Title : </td><td>" .$outputResult1['title']. "</td></tr>
                                <tr><td>Customer : </td><td>".$outputResult1['customercompany']."</td></tr>    
                                <tr><td>Ticket Type : </td><td>".$outputResult1['tickettype']."</td></tr> 
                                <tr><td>Priority : </td><td>".$outputResult1['prname']."</td></tr>              
                                <tr><td>Description :</td><td>".$outputResult1['description']."</td></tr>   
                                <tr><td>Status :</td><td>".$outputResult1['ticketStatus']."</td></tr>   
                            </table>";               
            $strCCMailIds =  $outputResult['ccemailId']; 
            $mailid = $outputResult['emaidId'];              
            $mailids[]= $mailid;       


            if (!empty($mailids) && is_array($mailids)) {
                    $to = $mailids;
                    if($obj->ticketstatus==2) { 
                        if($obj->tickettype==1){
                            $strCCMailIds = $strCCMailIds ? speedConstants::accounts_email.",".speedConstants::adminemail.",".speedConstants::support_email.",".$strCCMailIds : speedConstants::accounts_email.",".speedConstants::adminemail.",".speedConstants::support_email;
                        }
                    } 
                    else if($obj->tickettype==7){
                        $strCCMailIds = $strCCMailIds ? speedConstants::sales_email.",".speedConstants::adminemail.",".speedConstants::support_email.",".$strCCMailIds : speedConstants::sales_email.",".speedConstants::adminemail.",".speedConstants::support_email;
                    }
                    else if($obj->tickettype==8 || $obj->tickettype==9){
                     $strCCMailIds = $strCCMailIds ? speedConstants::software_email.",".speedConstants::adminemail.",".speedConstants::support_email.",".$strCCMailIds : speedConstants::software_email.",".speedConstants::adminemail.",".speedConstants::support_email; 
                    }
                    else{
                        $strCCMailIds = $strCCMailIds ? speedConstants::adminemail.",".speedConstants::support_email.",".$strCCMailIds : speedConstants::adminemail.",".speedConstants::support_email;
                    }
            }else{
                    if($obj->tickettype==1){
                        $strCCMailIds = $strCCMailIds ? speedConstants::accounts_email.",".speedConstants::support_email.",".$strCCMailIds : speedConstants::accounts_email.",".speedConstants::support_email; 
                    }
                    else if($obj->tickettype==7){
                        $strCCMailIds = $strCCMailIds ? speedConstants::sales_email.",".speedConstants::support_email.",".$strCCMailIds : speedConstants::sales_email.",".speedConstants::support_email;
                    }
                    else if($obj->tickettype==8 || $obj->tickettype==9){
                     $strCCMailIds = $strCCMailIds ? speedConstants::software_email.",".speedConstants::support_email.",".$strCCMailIds : speedConstants::software_email.",".speedConstants::support_email; 
                    }
                    else{
                        $strCCMailIds = $strCCMailIds ? speedConstants::support_email.",".$strCCMailIds : speedConstants::support_email;
                    }
            }

            $strBCCMailIds = $outputResult['allottoemail'];
            $attachmentFilePath = "";
            $attachmentFileName = "";
            $subject = "Elixia Speed - Support Ticket No: ET00" .$outputResult['ticketid']. " (Customer No: " . $obj->ticketcust . ")";
            $message .= "<h4> Your support ticket has been updated. Kindly interact with Elixia team for further assistance. </h4>";
            $message .= $messageCust;
            //sendMailUtil($to, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName);
            echo "ok";
    }
    return $arrResult;
}


    public function updateTicket($obj){
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $sp_params = "'" .$obj->customerid. "'
        ,'" .$obj->priorityid. "'
        ,'" .$obj->sendMail. "'
        ,'" .$obj->ticketMailIds. "'
        ,'" .$obj->ccemailids."'
        ,'" .$obj->eclosedate. "'
        ,'" .$obj->addcount. "'
        ,'" .$obj->ticketid."'
        ,'" .$obj->ticketdesc. "'
        ,'" .$obj->allotFrom. "'
        ,'" .$obj->allotTo. "'
        ,'" .$obj->ticketstatus."'
        ,'" .$obj->createdby."'
        ,'" .$obj->today."'
        ,'" .$obj->created_type."'
        ,'". $obj->prodId."'
        ,@ticketidOut,@is_executed,@tickettypename,@customercompany,@priorityname,@allottoemail,@createby,@emaidId,@ccemailId";

        $queryCallSP = "CALL " . speedConstants::SP_UPDATE_TICKET_TEAM . "(" . $sp_params . ")";
        $arrResult         = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        
        $outputParamsQuery = "SELECT @is_executed AS is_executed,@ticketidOut AS ticketid,@tickettypename AS tickettypename,@customercompany AS customercompany,@priorityname AS priorityname,@allottoemail AS allottoemail,@createby AS createby,@emaidId AS emaidId,@ccemailId AS ccemailId";

        $outputResult      = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        //print_r($outputResult);
         $obj->tickettype;
        if($outputResult['is_executed']==1){
        $messageCust = "<table>
                            <tr><td>Ticket No : </td><td>ET00".$outputResult['ticketid']."</td></tr>
                            <tr><td>Created By : </td><td>" . $outputResult['createby']."</td></tr>
                            <tr><td>Title : </td><td>" .$obj->ticket_title. "</td></tr>
                            <tr><td>Customer : </td><td>".$outputResult['customercompany']."</td></tr>    
                            <tr><td>Ticket Type : </td><td>".$outputResult['tickettypename']."</td></tr> 
                            <tr><td>Priority : </td><td>".$outputResult['priorityname']."</td></tr>              
                            <tr><td>Description :</td><td>".$obj->ticketdesc."</td></tr>   
                            <tr><td>Status :</td><td>Open</td></tr>   
                        </table>";               
            $strCCMailIds =  $outputResult['ccemailId']; 
            $mailid = $outputResult['emaidId'];              
            $mailids[]= $mailid;       
            if (!empty($mailids) && is_array($mailids)) {
                $to = $mailids;
                if($obj->ticketstatus==2) 
                { 
                   if($obj->tickettype==1)
                {
                    $strCCMailIds = $strCCMailIds ? speedConstants::accounts_email.",".speedConstants::adminemail.",".speedConstants::support_email.",".$strCCMailIds : speedConstants::accounts_email.",".speedConstants::adminemail.",".speedConstants::support_email;
                }
                else if($obj->tickettype==7){
                    $strCCMailIds = $strCCMailIds ? speedConstants::sales_email.",".speedConstants::adminemail.",".speedConstants::support_email.",".$strCCMailIds : speedConstants::sales_email.",".speedConstants::adminemail.",".speedConstants::support_email;
                }
                else if($obj->tickettype==8 || $obj->tickettype==9) 
                {
                 $strCCMailIds = $strCCMailIds ? speedConstants::software_email.",".speedConstants::adminemail.",".speedConstants::support_email.",".$strCCMailIds : speedConstants::software_email.",".speedConstants::adminemail.",".speedConstants::support_email; 

                }
                else{
                    $strCCMailIds = $strCCMailIds ? speedConstants::adminemail.",".speedConstants::support_email.",".$strCCMailIds : speedConstants::adminemail.",".speedConstants::support_email;
                }
            }
            else{
                      if($obj->tickettype==1)
                {
                    $strCCMailIds = $strCCMailIds ? speedConstants::accounts_email.",".speedConstants::support_email.",".$strCCMailIds : speedConstants::accounts_email.",".speedConstants::support_email;  
                }
                else if($obj->tickettype==7){
                    $strCCMailIds = $strCCMailIds ? speedConstants::sales_email.",".speedConstants::support_email.",".$strCCMailIds : speedConstants::sales_email.",".speedConstants::support_email;
                }
                else if($obj->tickettype==8 || $obj->tickettype==9) 
                {
                 $strCCMailIds = $strCCMailIds ? speedConstants::software_email.",".speedConstants::support_email.",".$strCCMailIds : speedConstants::software_email.",".speedConstants::support_email; 
                }
                else{
                    $strCCMailIds = $strCCMailIds ? speedConstants::support_email.",".$strCCMailIds : speedConstants::support_email;
                }
            }
            }

            $strBCCMailIds = $outputResult['allottoemail'];
            $attachmentFilePath = "";
            $attachmentFileName = "";
            $subject = "Elixia Speed - Support Ticket No: ET00" .$outputResult['ticketid']. " (Customer No: " . $obj->ticketcust . ")";
            $message .= "<h4> A new support ticket has been created for you. Kindly interact with Elixia team providing the ticket number for further assistance. </h4>";
            $message .= $messageCust;
            sendMailUtil($to, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName);
        }

    
        return $arrResult;
    }
    
   public function insertNote($obj){
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" .$obj->ticketid."'".
                     ",'".$obj->note."'".
                     ",'".$obj->teamId."'".
                     ",'".$obj->today."',@isexecutedOut";
        $queryCallSP       = "CALL " . speedConstants::SP_ADD_NOTE . "($sp_params)";
        $result            = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isexecutedOut";
        $outputResult      = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $outputResult      = $outputResult['@isexecutedOut'];
        return $outputResult;
    }

    public function getTicketTypes($id){
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $sp_params   = "'" . $id . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_TICKET_TYPES . "(" . $sp_params . ")";
        $arrResult   = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }
    
    public function fetchDockets($teamid, $docketId){
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $sp_params   = "'" . $teamid . "','" . $docketId . "'";
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_DOCKETS . "(" . $sp_params . ")";
        $arrResult   = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        if (count($arrResult) == 0)
            return null;
        return $arrResult;
    }

    public function fetchTickets($docketId = 0, $ticketId = 0){
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $sp_params   = "'" . $docketId . "','" . $ticketId . "'";
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_TICKETS . "(" . $sp_params . ")";
        $arrResult   = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function getInteractionTypes(){
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_INTERACTION_TYPES;
        $arrResult   = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }
    
    public function getPurposeTypes(){
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_PURPOSE_TYPES;
        $arrResult   = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }
    
    public function getCRM(){
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_CRM;
        $arrResult   = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }
    
    public function getCustomers($term){
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_CUSTOMERS . "('" . $term . "')";
        $arrResult   = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        foreach ($arrResult as &$record) {
            $record['value'] = $record['customerno'] . " - " . $record['customercompany'];
        }
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    
    public function getTicketPriorities(){
        $db          = new DatabaseManager();
        $pdo         = $db->createPDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_PRIORITIES;
        $arrResult   = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        foreach ($arrResult as &$record) {
            $record['prid']     = $record['prid'];
            $record['priority'] = $record['priority'];
        }
        //print_r($arrResult);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }
    public function fetchTeam() {
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_TEAM_LIST;
        $arrResult   = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        //print_r($arrResult);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }
    public function getProducts(){
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_PRODUCTS;
        $products    = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        //print_r($products);
        $db->ClosePDOConn($pdo);
        return $products;
    }
    public function getTicketStatus(){
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_STATUS;
        $products    = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
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
        $db           = new DatabaseManager();
        $pdo          = $db->CreatePDOConnForTech();
        $queryCallSP  = "CALL " . speedConstants::SP_GET_PRIORITIES;
        $arrResult    = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        foreach ($arrResult as &$record) {
            $record['prid']     = $record['prid'];
            $record['priority'] = $record['priority'];
        }
        $detailsArray[0] = $arrResult;
        $queryCallSP     = "CALL " . speedConstants::SP_GET_TEAM_LIST;
        $arrResult       = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $detailsArray[1] = $arrResult;
        $queryCallSP     = "CALL " . speedConstants::SP_GET_PRODUCTS;
        $products        = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $detailsArray[2] = $products;
        $queryCallSP     = "CALL " . speedConstants::SP_GET_TICKET_TYPES . "(0)";
        $arrResult       = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $detailsArray[3] = $arrResult;
        $queryCallSP     = "CALL " . speedConstants::SP_GET_STATUS;
        $status          = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $detailsArray[4] = $status;
        return $detailsArray;
    }
    public function getTimeslot() {
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_VIEW_TIMESLOT;
        $timezones   = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $timezones;
    }
    public function getCordinator($obj){
        $db          = new DatabaseManager();
        $sp_params   = $obj->customerno;
        $pdo         = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_VIEW_CORDINATOR . "(" . $sp_params . ")";
        $cordinators = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $cordinators;
    }
    
    public function get_vehicle($obj){
    
        $db=new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'".$obj->customerno. "'"
                    .",'%".$obj->term."%'";
                   
        $queryCallSP = "CALL " . speedConstants::SP_GET_VEHICLE_NUMBER . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        //print_r($arrResult);
        $ret = array();
        foreach($arrResult as &$record){
            $record['value']=$record['vehicleno'];
            $record['uid'] = $record["uid"];
            $record['simcardid'] = $record["id"];
            $ret[]=$record;
        }
        
        return $ret;
    }
    
    public function add_r_bucket($obj){
        
        $db        = new DatabaseManager();
        $pdo       = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->comments . "'" . 
        ",'" . $obj->deviceid . "'" . 
        ",'" . $obj->simcardid . "'" . 
        ",'" . $obj->bucketcust . "'" . 
        ",'" . $obj->apt_date . "'" . 
        ",'" . $obj->priority . "'" . 
        ",'" . $obj->location . "'" . 
        ",'" . $obj->timeslot . "'" . 
        ",'" . $obj->operation. "'" . 
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
                $mail            = new stdClass();
                $mail->username  = $outputResult['username'];
                $mail->realname  = $outputResult['realname'];
                $mail->email     = $outputResult['email'];
                $mail->vehicleno = $outputResult['vehicleno'];
                $mail->unit      = $outputResult['unitno'];
                $mail->sim       = $outputResult['simcardno'];
                $mail->elixir    = $outputResult['elixir'];
                $mail->comments  = $obj->comments;
                $mail->subject   = 'Suspect Device Details';
                $mail_status     = $this->SendEmailSuspect($mail);
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

    public function SendEmailSuspect($mail){   
       
        // send email
        $arrTo              = array($mail->email);
        $strCCMailIds       = "";
        $strBCCMailIds      = "";
        $subject            = $mail->subject;
        $message            = file_get_contents('../emailtemplates/suspectDevice.html');
        $message            = str_replace("{{REALNAME}}", $mail->realname, $message);
        $message            = str_replace("{{VEHICLENO}}", $mail->vehicleno, $message);
        $message            = str_replace("{{UNITNO}}", $mail->unitno, $message);
        $message            = str_replace("{{SIMCARDNO}}", $mail->simcardno, $message);
        $message            = str_replace("{{ELIXIR}}", $mail->elixir, $message);
        $message            = str_replace("{{COMMENTS}}", $mail->comment, $message);
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $isTemplatedMessage = 1;
        $isSmsSent          = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
        return $isSmsSent;
    }

    public function add_bucket($obj){
        
        
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $no_inst =  $obj->no_inst;
        for ($x = 1; $x <=$no_inst; $x++) {
            
            $sp_params = "'" . $obj->apt_date . "'" . ",'" . $obj->bucketcust . "'" . ",'" . $obj->createby . "'" . ",'" . $obj->priority . "'" . ",'" . $obj->location . "'" . ",'" . $obj->timeslot . "'" . ",'" . $obj->details . "'" . ",'" . $obj->coordinator . "'" . ",'" . $obj->todaysdate . "'" . ",'" . $obj->vehicleno . "'" . ",'" . $obj->docketId . "'" . ",@bucketid";
           $queryCallSP = "CALL " . speedConstants::SP_ADD_BUCKET_LIST . "($sp_params)";
            
            $arrResult         = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            //print_r($arrResult);
            $outputParamsQuery = "SELECT @bucketid AS bucketid";
            $outputResult      = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            //echo "ADDED bucket".$outputResult['bucketid'];;
            $db->ClosePDOConn($pdo);
            
        }
        return $outputResult;
    }


    public function updateBucket($obj) {
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
       
         $sp_params = 
            "'".$obj->apt_date."'".
            ",'".$obj->coordinator ."'".
            ",'".$obj->priorityid ."'".    
            ",'".$obj->location."'".
            ",'".$obj->vehicleno."'".
            ",'".$obj->timeslot."'".
            ",'".$obj->operationtype."'".
            ",'".$obj->details."'".
            ",'".$obj->sstatus."'".
            ",'".$obj->today."'".
            ",'".$obj->creason."'".
            ",'".$obj->add_charge."'".
            ",'".$obj->reschedule_date."'".
            ",'".$obj->bucketid."'";

       
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_EDIT_BUCKET_LIST. "($sp_params)";
        $result = $pdo->query($queryCallSP);
    }


    public function add_e_bucket($obj){
        
        $db  = new DatabaseManager();
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
             ",'". $obj->vehicleid. "'" . 
             ",'" . $obj->docketId . "'" .
             ",'" .$obj->prevBucketId."'".
              ",@bucketid";
                $queryCallSP = "CALL " . speedConstants::SP_ADD_E_BUCKET_LIST . "($sp_params)";
           
            $arrResult         = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            //print_r($arrResult);
            $outputParamsQuery = "SELECT @bucketid AS bucketid";
            $outputResult      = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            //echo "ADDED bucket".$outputResult['bucketid'];;
            $db->ClosePDOConn($pdo);
            
            $newbucket = new stdClass();
            $newbucket->updateBucket = 1;                    //because bucketID in modal has to be updated
            $newbucket->bucketID = $outputResult['bucketid']; 
            
            return $newbucket;
    }


    public function add_cordinator($obj){
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->coname . "'" . ",'" . $obj->cophone . "'" . ",'" . $obj->cordcust . "'" . ",'" . $obj->createby . "'" . ",'" . $obj->today . "'" . "," . "@lastInsertId";
        
        
      $queryCallSP       = "CALL " . speedConstants::SP_ADD_CORDINATOR_DETAILS . "($sp_params)";

        $result            = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @lastInsertId";
        $outputResult      = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $outputResult      = $outputResult['@lastInsertId'];
        return $outputResult;
    }
    public function getBuckets($docketId){
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $sp_params   = "'".$docketId."'";
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_BUCKETS . "(".$sp_params.")";
        $arrResult   = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        //print_r($arrResult);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }
    


    public function fetchDocketInfo($teamId){
       $db          = new DatabaseManager();
       $pdo         = $db->CreatePDOConnForTech();
       $docket      = $this->fetchDockets($teamId,0);
       
       foreach($docket as $record){
           $docketIds[] = $record['docketid'];
           $retArray[$record['docketid']]['info']=$record;
       }
       
       $docketIds=implode(",",$docketIds);
       $sp_params   = "'". $docketIds ."'";
       $queryCallSP = "CALL " . speedConstants::SP_FETCH_VIEW_TICKETS . "(".$sp_params.")";
       $arrResult0  = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
       $queryCallSP = "CALL " . speedConstants::SP_FETCH_VIEW_BUCKETS . "(".$sp_params.")";
       $arrResult1  = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
       foreach($arrResult0 as $ticket){
           $retArray[$ticket['docketid']]['tickets'][]=$ticket;
       }
       foreach($arrResult1 as $bucket){
           $retArray[$bucket['docketid']]['buckets'][]=$bucket;
       }
       $db->ClosePDOConn($pdo);
       return $retArray;
    }


   public function fetchOverdueTickets(){
        $db          = new DatabaseManager();
        $date = date('Y-m-d H:i:s');
        $pdo         = $db->CreatePDOConnForTech();
        $sp_params   = "'". $date ."'";
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_OVERDUE_TICKETS . "(".$sp_params.")";
        $arrResult   = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        //print_r($arrResult);
        $db->ClosePDOConn($pdo);
        return $arrResult;
   }

   public function insertEmailIdforTech($obj) {

        $today = date('Y-m-d H:i:s');
        $userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : '0';
        
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'".$obj->customerno."'". 
        ",'" . $obj->emailText."'".
         ",'" .$today."'". 
         ",'" .$userid."'". 
         "," . "@lastInsertId";
        
        
        $queryCallSP       = "CALL " . speedConstants::SP_INSERT_EMAIL_ID_SUPPORT . "($sp_params)";
        $result            = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @lastInsertId";
        $outputResult      = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $outputResult      = $outputResult['@lastInsertId'];
        return $outputResult;
    }
    public function add_additional_charge($obj){
        $today = date('Y-m-d H:i:s');
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" .$obj->ticketid."'".
                     ",'".$obj->bucketid."'".
                     ",'".$obj->description."'".
                     ",'".$obj->amount."'".
                     ",'".GetLoggedInUserId()."'".
                     ",'".$today."'";
        $queryCallSP       = "CALL " . speedConstants::SP_INSERT_ADDITIONAL_AMOUNT . "($sp_params)";
        $result            = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        return $result;
    }
    public function pullBucketHistory($bucketid){
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" .$bucketid."'";
        $queryCallSP       = "CALL " . speedConstants::SP_PULL_BUCKET_HISTORY . "($sp_params)";
        $result            = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getTicket_Count($teamid) {

       
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'".$teamid."'";
        
        
        $queryCallSP       = "CALL " . speedConstants::SP_GET_TICKET_COUNT . "($sp_params)";
        $result            = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        return $result;
    }

    public  function getBucket_Count($teamid) {

       
        
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'".$teamid."'";
        
        
      $queryCallSP       = "CALL " . speedConstants::SP_GET_BUCKET_COUNT . "($sp_params)";
      $result            = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
      return $result;
    }
    public function getCustomer_Count($teamid) {

       
        
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'".$teamid."'";
        
        
      $queryCallSP       = "CALL " . speedConstants::SP_GET_CUSTOMER_COUNT . "($sp_params)";
      $result            = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
      return $result;
    }
    
    public function getTicketAnalysis($obj){
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'".$obj->startdate."'".
                     ",'".$obj->enddate."'".
                     ",'".$obj->teamId."'".
                     ",'".$obj->status."'";
        
       $queryCallSP       = "CALL " . speedConstants::SP_GET_TICKET_ANALYSIS . "($sp_params)";
      
        $result            = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getBucketStatus(){
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_STATUS_BUCKET;
        $status    = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        //print_r($products);
        $db->ClosePDOConn($pdo);
        return $status;
    }
    
    public function getBucketAnalysis($obj){
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'".$obj->startdate."'".
                     ",'".$obj->enddate."'".
                     ",'".$obj->teamId."'".
                     ",'".$obj->status."'".
                     ",'".$obj->purpose."'";
        $queryCallSP       = "CALL " . speedConstants::SP_GET_BUCKET_ANALYSIS . "($sp_params)";
           
        $result            = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        return $result;
    }
}