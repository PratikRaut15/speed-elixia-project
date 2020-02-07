<?php
/**
 * Date: 1st April 2015
 * Ak added, for: Links for sending Rout-Summary mail
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

//date_default_timezone_set("Asia/Calcutta");

require "../../lib/system/utilities.php";
require '../../lib/bo/CustomerManager.php';
require '../../lib/bo/UserManager.php';
include_once("class.phpmailer.php");

/*mail header details*/
$cc = '';
$subject = 'Route Summary Report';
$from = 'noreply@elixiatech.com';
$headers = "From: ".$from."\r\n";
$headers .= "CC:".$cc."\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
/**/

$serverpath = "http://www.speed.elixiatech.com";
//$serverpath = "http://localhost/elixiademo";
$download = $serverpath."/modules/download/report.php?q=";

$cm = new CustomerManager();
$um = new UserManager();

$customernos = array(132); //delex

$cur_date = date("d-m-Y");

if(isset($customernos)){
    $total_mail = 0;
    $not_sent_mail = 0;

    foreach($customernos as $thiscustomerno){
        $customer_details = $cm->getcustomerdetail_byid($thiscustomerno);
        $users = $um->getadminforcustomer($thiscustomerno);
        $timezone = $cm->timezone_name_cron('Asia/Kolkata', $thiscustomerno);
        date_default_timezone_set(''.$timezone.'');
        if(isset($users)){
            foreach($users as $user){

                $str_date = strtotime($cur_date);
                $routeSummary_pdf_url = $download."routeSummary-pdf-$thiscustomerno-$user->userkey-$str_date";
                $routeSummary_xls_url = $download."routeSummary-xls-$thiscustomerno-$user->userkey-$str_date";

                if($user->email != ''){

                    $encodekey = sha1($user->userkey);
                    $to = $user->email;
//                    $to = "sanketsheth1@gmail.com";

                /*starts, email body*/
                $message = '
<html>
    <body>
        Dear '.$user->realname.' ,<br><p></p></br>
        Greetings from Elixia Tech!<br/><br/>
        Customer No: <b>'.$user->customerno.'</b><br/><br/>
        Please find the auto-generated Route Summary Report for all your vehicles. In case of any queries, you may contact us at 25137470 / 71. Thank you for choosing Elixia Tech!<br/><br/>';


                $message.="<hr/><br/>";
                $message .= '
        <table border=1>
            <tr align=center><td colspan="2"><b>Route Summary Report</b></td></tr>
            <tr><td><b>PDF</b></td><td><b>CSV</b></td></tr>
            <tr>
                <td><a href="'.$routeSummary_pdf_url.'" target="_blank">Download</a></td>
                <td><a href="'.$routeSummary_xls_url.'" target="_blank">Download</a></td>
            </tr>
        </table><br/>';


                $message .= '
        <br/><font size="smaller">To unsubscribe, please uncheck your alerts <a href="http://www.speed.elixiatech.com/modules/api/?action=login_userkey_unsub&key='.$encodekey.'">here</a></font>
    </body>
</html>';
//                echo "<br/><br/>".$message;
                    /*email body ends here*/

                    /*email send process starts*/
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
                    /*email send process ends*/

                    if(!$mail->Send()){
                        $not_sent_mail++;//echo "Error sending: " . $mail->ErrorInfo;
                    }
                    $total_mail++;
                }
            }
        }
    }
}
echo "Total emails: $total_mail<br/>Total email sent: ".($total_mail-$not_sent_mail)."<br/>Total email not sent: $not_sent_mail<hr>";
// echo $message;
?>
