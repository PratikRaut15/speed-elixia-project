<?PHP
require "../../lib/system/utilities.php";
require '../../lib/bo/CustomerManager.php';
require '../../lib/bo/CommunicationQueueManager.php';
require '../../lib/bo/UserManager.php';
require '../../lib/bo/VehicleManager.php';

//$serverpath = $_SERVER['DOCUMENT_ROOT']."/speed";
//$serverpath = "/home/elixia5/public_html/elixiatech.com/speed";
$serverpath = "/var/www/html/speed";
$cm = new CustomerManager();
$customernos = $cm->getcustomernos();
$date1 = date("d-m-Y");
$date = date('d-m-Y',strtotime("-1 day".$date1));
if(isset($customernos))
{
    foreach($customernos as $thiscustomerno)
    {
        $um = new UserManager($thiscustomerno);
        $users = $um->getadminforcustomer($thiscustomerno);
        if(isset($users))
        {
            foreach($users as $user)
            {
                if($user->summarypdf == '1' && $user->email != '' ){
                    //if($user->userkey != '')
                        $encodekey = sha1($user->userkey);
                        $message = '';
                        $message .= '<html><body>';
                        $message .= 'Dear '.$user->realname.' ,<br>';
                        $message .= '<p></p></br>';
                        $message .= 'Greetings from Elixia Tech!<br/>';
                        $message .= 'Please find the auto-generated Summary Report for all your vehicles. In case of any queries, you may contact us at 25137470 / 71. Thank you for choosing Elixia Tech!<br/><br/>';
                        $message .= "<font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.speed.elixiatech.com/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a></font>";
                        $message .= "</body></html>";

                        $to = $user->email;
//                       $to = 'sanketsheth1@gmail.com';

                        $subject = 'Summary Report';
                        $from = 'noreply@elixiatech.com';
                        $headers .= "From: ".$from."\r\n";
                        $headers .= "CC:".$cc."\r\n";
                        $headers .= "MIME-Version: 1.0\r\n";
                        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                        include_once("class.phpmailer.php");
                        $mail = new PHPMailer();
                        $mail->IsMail();


                        $mail->AddAddress($to);
                        $mail->From     = $from;
                        $mail->FromName = "Elixia Speed";
                        $mail->Sender = $from;
                        $mail->Subject = $subject;
                        $mail->Body = $message;
                        $mail->IsHTML(true);
                        $mail->AddBCC($cc);
                        $mail->AddReplyTo($from,"Elixia Speed");
                        $location = $serverpath."/customer/".$user->customerno."/reports/pdf/".$date."_summaryreport.pdf";
                        $mail->AddAttachment($serverpath."/customer/".$user->customerno."/reports/pdf/".$date."_summaryreport.pdf"); // attachment

                        if(!$mail->Send())
                        {
                           echo "Error sending: " . $mail->ErrorInfo;
                           $message = '';

                        }
                        else
                        {
                           echo "Mail sent";
                           $message = '';
                        }
                }
                if($user->summarycsv == '1' && $user->email != '' ){
                        $encodekey = sha1($user->userkey);
                        $message = '';
                        $message .= '<html><body>';
                        $message .= 'Dear '.$user->realname.' ,<br>';
                        $message .= '<p></p></br>';
                        $message .= 'Greetings from Elixia Tech!<br/>';
                        $message .= 'Please find the auto-generated Summary Report for all your vehicles. In case of any queries, you may contact us at 25137470 / 71. Thank you for choosing Elixia Tech!<br/><br/>';
                        $message .= "<font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.speed.elixiatech.com/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a></font>";
                        $message .= "</body></html>";

                        $to = $user->email;
//                        $to = 'sanketsheth1@gmail.com';

                        $subject = 'Summary Report';
                        $from = 'noreply@elixiatech.com';
                        $headers .= "From: ".$from."\r\n";
                        $headers .= "CC:".$cc."\r\n";
                        $headers .= "MIME-Version: 1.0\r\n";
                        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                        include_once("class.phpmailer.php");
                        $mail = new PHPMailer();
                        $mail->IsMail();


                        $mail->AddAddress($to);
                        $mail->From     = $from;
                        $mail->FromName = "Elixia Speed";
                        $mail->Sender = $from;
                        $mail->Subject = $subject;
                        $mail->Body = $message;
                        $mail->IsHTML(true);
                        $mail->AddBCC($cc);
                        $mail->AddReplyTo($from,"Elixia Speed");
                        $location = $serverpath."/customer/".$user->customerno."/reports/csv/".$date."_summaryreport.xls";
                        $mail->AddAttachment($serverpath."/customer/".$user->customerno."/reports/csv/".$date."_summaryreport.xls"); // attachment

                        if(!$mail->Send())
                        {
                           echo "Error sending: " . $mail->ErrorInfo;
                           $message = '';
                        }
                        else
                        {
                           echo "Mail sent";
                           $message = '';
                        }
                }
            }
        }
    }
}
?>

