<?php
/**
 * Date: 11th feb 2015
 * Ganesh added, for: Mail repair due date is today.
 **/
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Calcutta");

include_once("../../lib/system/DatabaseManager.php");
include_once '../../lib/bo/DeviceManager.php';
include_once '../../modules/realtimedata/rtd_functions.php';
include_once("class.phpmailer.php");

$today = date('Y-m-d H:i:s');
$today_date = date('Y-m-d');
$db = new DatabaseManager();
$SQL =("SELECT (@cnt := @cnt+1) as srno, u.uid, u.unitno, u.repairtat, u.comments , ts.status 
FROM unit as u INNER JOIN trans_status as ts ON u.trans_statusid = ts.id where u.trans_statusid = 7 AND u.repairtat='".$today_date."' ORDER BY `repairtat` DESC");
$db->executeQuery("set @cnt=0");
$db->executeQuery($SQL);
$count =$db->get_rowCount();
if($count>0)
{
       $message = "<table border='1'><tr><td colspan=6>Today repair due date is expired.</td></tr><tr><th>Sr.No.</th><th>Unit Id</th><th>Unit No</th><th>Due Date</th><th>Comments</th><th>Status</th></tr>";
       while($row = $db->get_nextRow()){
        $message .="<tr><td>".$row['srno']."</td><td>".$row['uid']."</td><td>".$row['unitno']."</td><td style='color:red;'>".$row['repairtat']."</td><td>".$row['comments']."</td><td>".$row['status']."</td></tr>";
       }
       $message .= "</table>";
        //echo $message;
        send_mail($message);
}    
        
/*mail header details*/
function send_mail($message){
    $cc = 'ganeshelixia@yahoo.com';
    $to = "sanketsankesheth1@gmail.com";
    $subject = 'Today repair due date is expired';
    $from = 'noreply@elixiatech.com';
    $headers = "From: ".$from."\r\n";
    $headers .= "CC:".$cc."\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $mail = new PHPMailer();                    
    $mail->IsMail();
    $mail->AddAddress($to);
    $mail->From = $from;
    $mail->FromName = "Elixia Speed";
    $mail->Sender = $from;
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->IsHTML(true); 
    $mail->AddBCC($cc);
    $mail->AddReplyTo($from,"Elixia Speed");

    if(!$mail->Send()){
        echo "Error sending: " . $mail->ErrorInfo;
    }
    else{
        echo "Mail sent successfully";  
    }
}



?>