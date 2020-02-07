<?php
//http://uat-speed.elixiatech.com/ UAT EXTENSION
//http://speed.elixiatech.com/ LIVE EXTENSION
//../../speed_docket1/ LOCAL FILE EXTENTSION

require_once "global.config.php";
require_once "database.inc.php";
require_once "reports.api.php";

date_default_timezone_set('Asia/Kolkata');

class api {

    static $SMS_TEMPLATE_FOR_VEHICLE_USER_DRIVER_MAPPING = "Dear {{USERNAME}}, {{VEHICLENO}} has been allotted for your pickup. Driver Name: {{DRIVERNAME}} ({{DRIVERPHONE}})";
    //static $SMS_TEMPLATE_FOR_QUICK_SHARE = "{{USERNAME}} wants you to track the trip at {{URL}}";
    static $SMS_TEMPLATE_FOR_QUICK_SHARE = "Vehicle No: {{VEHICLENO}}\r\nLocation: {{LOCATION}}\r\nShared by: {{USERNAME}}";
    var $status;
    var $status_time;

    //<editor-fold defaultstate="collapsed" desc="Constructor">
    // construct
    function __construct() {
        $this->db = new database(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, SPEEDDB);
    }

    function insert_email_for_subscription($jsonRequest) {

            $um = new UserManager();
            $newsLetterId =  $um->insert_email_for_newsLetter($jsonRequest);
            $arrResult = array();

            if (isset($newsLetterId['newsLetterId']) && $newsLetterId['newsLetterId'] != '') {
                $arrResult['status'] = 1;
                $arrResult['message'] = "Email Id Stored";
                $arrResult['result'] =   array("newsLetterId" => $newsLetterId['newsLetterId']);
                $to = array();
                $to[] = $jsonRequest->email_id;

                $subject = "Subscription Successful - Elixia Tech Newsletter";

                $body = 'Dear Subscriber,';
                $body .= '<br/>Thank you for your interest and subscription to our newsletter.';
                $body .= '<br/><br/>We shall regularly send out our updated newsletters to keep you abreast with the latest developments, solutions, offerings and much more that happens at Elixia Tech.';
                $body .= '<br/>Meanwhile, here is a glimpse of <a href ="https://www.youtube.com/watch?v=sOVIQyrsRqM" title ="life at elixiatech" target  ="_blank">LIFE AT ELIXIA TECH</a>';
              
                $body .= '<br/><br/><br/>Thank You once again. We shall stay connected.<br/>';
                $body .= 'Regards,<br/>';
                $body .= 'Elixia Tech Solutions Ltd.<br/>';
                $body .= '<a href = "http://www.elixiatech.com" title = "elixiatech.com" target ="_blank">www.elixiatech.com</a><br/>';
                $body .= '<br/><img src = "http://uat-speed.elixiatech.com/images/logo.png" alt = "Elixia Tech Solutions Pvt. Ltd."  style="width:25%;height:10%;"/><br/>';
                $body.='<br/><br/>To unsubscribe from these mailings, you may opt-out <a href="http://uat-speed.elixiatech.com/modules/api/elixiatech/index.php?action=unsubscribe_newsLetter&gu_ID='.$jsonRequest->gu_id.'"\>here</a>';
               
                $isTemplatedMessage=1; //To change the footer of email
                $isElixiaTech = 1; //To change the sender name
               sendMailUtil($to, "", "", $subject, $body,"","",$isTemplatedMessage,$isElixiaTech);
            } 
            elseif (isset($newsLetterId['subscriptionId']) && $newsLetterId['subscriptionId'] == 2) {
                $arrResult['status'] = 2;
                $arrResult['message'] = "Email Id Already Exists";
                $arrResult['result'] =  array("subscriptionId" => $newsLetterId['subscriptionId']);
            }
            else{
                $arrResult['status'] = 0;
                $arrResult['message'] = "Could not store email";
            }
        
            return $arrResult;
    }

    function update_subscription($jsonRequest){
         $um = new UserManager();
         $newsLetterId =  $um->update_NewsLetter_Subscription($jsonRequest);

         return $newsLetterId;
    }

    function check_duplicate_GUID($guidText){
         $um = new UserManager();
         $newGUID =  $um->duplicate_guId($guidText);
         return $newGUID;
    }

    function blogsPreview(){
        $i=0;
        $FinalData = array();
        $db = new DatabaseManager(); 
        $pdo         = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_BLOGS_PREVIEW;
        $arrResult   = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
            foreach ($arrResult as $row) {
                $data['blogs_href'] = $row['blogs_href'];
                $data['blogs_title'] = $row['blogs_title'];
                $data['blogs_preview'] = $row['blogs_preview'];
                $data['blogs_description'] = $row['blogs_description'];
                $FinalData[$i]=$data;
                $i++;
            }
        return $FinalData;
    }
    function blogsDetails($obj){
        $i=0;
        $FinalData = array();
        $db = new DatabaseManager(); 
        $pdo         = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_BLOGS_DETAILS. "(" .$obj->blogLink . ")";
        $arrResult   = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $data['blogs_title'] = $arrResult['blogs_title'];
        $data['blogs_description'] = $arrResult['blogs_description'];
        return $data;
    }

    function    fetchElixiaCarrers($obj){
            $i=0;
            $FinalData = array();
            $db = new DatabaseManager(); 
            $pdo         = $db->CreatePDOConnForTech();
            $queryCallSP = "CALL " . speedConstants::SP_FETCH_ELIXIA_CAREERS. "(" .$obj->careerId . ")";
            $arrResult   = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
            foreach ($arrResult as $row) {
                    $data['ecId'] = $row['ecId'];
                    $data['location'] = $row['location'];
                    $data['experience'] = $row['experience'];
                    $data['requirement'] = $row['requirement'];
                    $data['department'] = $row['department'];
                    $data['companyRole'] = $row['companyRole'];
                    if($data['department']=='Others'){
                        $data['department'] = $data['companyRole'];
                    }
                    $data['preference'] = $row['preference'];
                    $data['challenges'] = $row['challenges'];
                    $data['job_role'] = $row['job_role'];
                    $FinalData[$i]=$data;
                    $i++;
                }
            return $FinalData;
    }

    function insertJobApplicantDetails($obj){
        $todaydatetime = date("Y-m-d H:i:s");
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $sp_params         =    "'" . $obj->careerId. "'" .
                                ",'" .$obj->name. "'" . 
                                ",'" .$obj->phone. "'" . 
                                ",'" .$obj->email. "'" . 
                                ",'" .$obj->coverLetter. "'" . 
                                ",'" .$todaydatetime. "'" . 
                                "," . "@lastInsertedOut";
        $queryCallSP = "CALL " . speedConstants::SP_INSERT_ELIXIA_JOB_APPLICATION . "(" . $sp_params . ")";
        $arrResult   = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @lastInsertedOut as lastInserted";
        $outputResult      = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        $result= $outputResult['lastInserted'];
        return $result; 
    }
    function updateJobApplicantDetails($obj){
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $sp_params         =    "'" . $obj->jobAppId. "'" .
                                ",'" .$obj->fileName. "'" . 
                                ",'" .$obj->filePath. "'" . 
                                "," . "@isExecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_UPDATE_ELIXIA_JOB_APPLICATION . "(" . $sp_params . ")";
        $arrResult   = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecuted";
        $outputResult      = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        $result= $outputResult['isExecuted'];
        return $result; 
    }
    
}
?>
