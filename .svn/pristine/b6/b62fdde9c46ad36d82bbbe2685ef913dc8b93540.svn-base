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
$teamdata = $support->getteamdata();
$message = "";
date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$from_date =date("Y-m-d H:i:s");
$message = "";
$mail_type='test';
$cust_no=2;

if(isset($teamdata)){
    //$message .="<table border='1'><tr><th>Team Member</th><th>Created Tickets</th><th>Open Tickets</th><th>In progress Tickets</th><th>Closed Tickets</th><th>Expiry Ticket</th></tr>";
    $message .="<table border='1'><tr><th>Team Member</th><th>Created Tickets</th><th>Open Tickets</th><th>In progress Tickets</th><th>Closed Tickets</th></tr>";
    foreach($teamdata as $row){
        $teamid = $row['teamid'];
        $created_count = get_created_count($teamid,$from_date,$customerno);
        $closed_count = get_count($teamid,$from_date,'2',$customerno);
        $inprogress_count = get_inprogress_count($teamid,$from_date,'1',$customerno);
        $opened_count = get_open_count($teamid,$from_date,'0',$customerno);
        //$expiry_count = get_expiry_count($teamid,$from_date,'0',$customerno);
        $message .= "<tr>";
        $message .= "<td>".$row['name']."</td>";
        $message .= "<td>". $created_count."</td>";
        $message .= "<td>". $opened_count."</td>";
        $message .= "<td>". $inprogress_count."</td>";
        $message .= "<td>". $closed_count."</td>";
        //$message .= "<td></td>";
        $message .= "</tr>";
    }
}
$message.="</table>";



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

//function sendMail( $to, $subject , $content)
//{
//    $subject = $subject;
//
//    $headers = "From: noreply@elixiatech.com\r\n";
//    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";    
//    if (!@mail($to, $subject, $content, $headers)) {
//        // message sending failed
//        return false;
//    }
//    return true;        
//

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

