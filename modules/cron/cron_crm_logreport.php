<?php
require "../../lib/system/utilities.php";
require '../../lib/bo/DeviceManager.php';
require '../../lib/bo/CustomerManager.php';
require '../../lib/bo/UserManager.php';
require '../../lib/bo/SupportManager.php';

$customerno = 2;
$cm = new CustomerManager();
$customernos = $cm->getcustomernos();
$support = new SupportManager($customerno);
$crmdata = $support->getcrmdata();

$message = "";
date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$from_date =date("Y-m-d H:i:s");
$mail_type='test';
$cust_no=2;

if(isset($crmdata)){
    $data = array();
    foreach($crmdata as $row){
        $teamid = $row['teamid'];
        $data[$row['name']] = get_crm_ticket_details($teamid,$today,$customerno);
    }
 ?>   
<?php
$testtoday = date('d-m-Y',strtotime($today));
$message .="<table border='1'>";
$message .="<tr><td colspan='100%' style='text-align:center;'> CRM Log Report - ".$testtoday." </td></tr>";        
?>
<?php   
 
    foreach($data as $key=>$val){
        $message .="<tr><td colspan='100%' style='text-align:center;'> CRM - ".$key."</td></tr>";
        if(!empty($val)){
            
            $message .="<tr>
            <th>Ticket Id</th>
            <th>Vehicle No</th>
            <th>Allot To</th>
            <th>Ticket Status</th>
            <th>Create On Date</th>
            <th>Title</th>
            <th>Ticket Type</th>
            <th>Description</th>
            <th>Priority</th>
            <th>Created Type</th>
            <th>Enclose Date</th>
            <th>Timeslot</th>
            <th>Customer Id</th>
        </tr>";
            foreach($val as $row){
                $message .="<tr>
                <td>".$row['ticketid']."</td>
                <td>".$row['vehicleno']."</td>
                <td>".$row['allot_to_name']."</td>
                <td>".$row['ticketstatus']."</td>
                <td>".$row['create_on_time']."</td>
                <td>".$row['title']."</td>
                <td>".$row['tickettypename']."</td>
                <td>".$row['description']."</td>
                <td>".$row['priority']."</td>
                <td>".$row['created_type']."</td>
                <td>".date('d-m-Y',strtotime($row['eclosedate']))."</td>
                <td>".$row['timeslot']."</td>
                <td>".$row['customername']."</td>
                </tr>";   
            }
        }else{
            $message .="<tr><td colspan='100%' style='text-align:center;'> No tickets</td></tr>";
        }
    }
       $message .="<table>"; 
}

function get_crm_ticket_details($teamid,$today,$customerno){
    $support = new SupportManager($customerno);
    $data = $support->get_crm_ticket_details($teamid,$today); 
    return $data;
}

function get_created_count($teamid,$from_date,$customerno){
    $support = new SupportManager($customerno);
    $count = $support->get_created_count($teamid,$from_date);     
    return $count;
}

function get_count($teamid,$from_date,$status,$customerno){
    $support = new SupportManager($customerno);
    $count = $support->get_count($teamid,$from_date,$status);     
    return $count;
}

function get_open_count($teamid,$from_date,$status,$customerno){
    $support = new SupportManager($customerno);
    $count = $support->get_open_count($teamid,$from_date,$status);     
    return $count;
}

function get_expiry_count($teamid,$from_date,$status,$customerno){
    $support = new SupportManager($customerno);
    $count = $support->get_expiry_count($teamid,$from_date,$status);     
    return $count;
}

function get_inprogress_count($teamid,$from_date,$status,$customerno){
    $support = new SupportManager($customerno);
    $count = $support->get_inprogress_count($teamid,$from_date,$status);     
    return $count;
}
//echo $message;  die();
$content = ob_get_clean();
$content = $message;

$dest1 = "../../customer";
$dest2 = "../../customer/$customerno/supportreport/";

if (!file_exists($dest1)) {
    mkdir($dest1, 0777, true);
}
if (!file_exists($dest2)) {
    mkdir($dest2, 0777, true);
}
$ext = ".xls";
$file_name = "daily_ticket_analysis_report_" . date('d-M-Y', strtotime($today)) . $ext;
$fullpath = $dest2 . "/" . $file_name;
save_xls($fullpath, $content);

function save_xls($full_path, $content) {
    include '../../lib/bo/simple_html_dom.php';
    $html = str_get_html($content);
    $fp = fopen($full_path, "w");
    fwrite($fp, $html);
    fclose($fp);
}

$subject = "Daily Ticket Analysis Report - " . date('d-M-Y', strtotime($today));
$toArr = array(
    'ganeshp@elixiatech.com', 'bindiv@elixiatech.com'
);
$CCEmail = '';
$BCCEmail = '';
$emailmessage = "Daily Ticket Analysis Report - " . date('d-M-Y', strtotime($today));

$emailstatus = sendMailPHPMAILER($toArr, $CCEmail, $BCCEmail, $subject, $emailmessage, $dest2, $file_name);

function sendMailPHPMAILER(array $arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName) {
    include_once("class.phpmailer.php");
    $isEmailSent = 0;
    $completeFilePath = '';
    if ($attachmentFilePath != '' && $attachmentFileName != '') {
        $completeFilePath = $attachmentFilePath . "/" . $attachmentFileName;
    }
    $mail = new PHPMailer();
    $mail->IsMail();
    /* Clear Email Addresses */
    $mail->ClearAddresses();
    $mail->ClearAllRecipients();
    $mail->ClearAttachments();
    $mail->ClearCustomHeaders();
    //unset($arrToMailIds);
    //$arrToMailIds = array('sshrikanth@elixiatech.com', 'mrudangvora@gmail.com', 'shrisurya24@gmail.com');
    //$strCCMailIds = '';
    if (!empty($arrToMailIds)) {
        foreach ($arrToMailIds as $mailto) {
            $mail->AddAddress($mailto);
        }
        if (!empty($strCCMailIds)) {
            $mail->AddCustomHeader("CC: " . $strCCMailIds);
        }
        if (!empty($strBCCMailIds)) {
            $mail->AddCustomHeader("BCC: " . $strBCCMailIds);
        }
    }
    $mail->From = "noreply@elixiatech.com";
    $mail->FromName = "Elixia Speed";
    $mail->Sender = "noreply@elixiatech.com";
    //$mail->AddReplyTo($from,"Elixia Speed");
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->IsHtml(true);
    if ($completeFilePath != '' && $attachmentFileName != '') {
        $mail->AddAttachment($completeFilePath, $attachmentFileName);
    }
    //SEND Mail
    if ($mail->Send()) {
        $isEmailSent = 1; // or use booleans here
    }
    /* Clear Email Addresses */
    $mail->ClearAddresses();
    $mail->ClearAllRecipients();
    $mail->ClearAttachments();
    $mail->ClearCustomHeaders();
    return $isEmailSent;
}

if ($emailstatus == 1) {
    echo "Mail sent";
} else {
    echo "Error sending mail ";
}

