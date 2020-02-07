<?php
/**
 * Date: 20th jan 2015
 * Ak added, for: Mail if inactive device grosses
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Calcutta");

include_once("../../lib/system/DatabaseManager.php");
include_once '../../lib/bo/DeviceManager.php';
include_once '../../modules/realtimedata/rtd_functions.php';
include_once("class.phpmailer.php");


$exception_count = 25; //if diiference is greater or equal to this count
$today = date('Y-m-d H:i:s');
$result = getvehicles_all_inactive("","",0);
$cnt = count($result); //get current inactive devices
$dm = new DeviceManager(null);
$od = $dm->get_last_inactive_count(); //get last inactive count

//$cnt = 1474; //for testing

if($od['last_count']>0){
    $diff = $cnt - $od['last_count'];
    if($diff>=$exception_count){
        $message = "Last inactive device count({$od['last_time']}): {$od['last_count']}<br/>
        Current count($today): $cnt<br/>
        Difference: $diff <br/><br/>Please take the necessary action";
        echo $message;
        send_mail($message);
    }
}

//add current count
$dm->add_inactive_count($cnt,$today);


/*mail header details*/
function send_mail($message){
    $cc = '';
    $to = "sanketsheth1@gmail.com";
    $subject = 'Inactive Device error';
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