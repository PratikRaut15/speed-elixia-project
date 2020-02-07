<?PHP
require_once "../../lib/system/utilities.php";
require_once '../../lib/bo/DeviceManager.php';

//$serverpath = $_SERVER['DOCUMENT_ROOT']."/speed";
//$serverpath = "/home/elixia5/public_html/elixiatech.com/speed";
$dm = new DeviceManager();
$simcards = $dm->get_all_simcard_with_devices();
if(isset($simcards))
{
    foreach($simcards as $simcard)
    {
                        $message .= '<html><body>';
                        $message .= 'Dear '.$simcard->customername.' ,<br>';
                        $message .= '<p>You simcard number '.$simcard->simcardno.' is deactivated</p></br>';
                        $message .= 'Greetings from Elixia Tech!<br/>';
                        $message .= 'In case of any queries, you may contact us at 25137470 / 71. Thank you for choosing Elixia Tech!<br/><br/>';      
                        $message .= "</body></html>";
                        //$to = $customer->customeremail;
                        $to = 'sanketsheth1@gmail.com';
                        $cc = '';
                        $subject = "Simcard is De-activated";
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
                        if(!$mail->Send())
                        {
                           echo "Error sending: " . $mail->ErrorInfo;
                           $message = '';
                        }
                        else
                        {
                           echo "Mail sent";
                           $message = '';
                           //$cm->updateSmsMailsent($customer->customerno);
                        }
                        $dm->updateSimcardstatus($simcard->simcardid,$simcard->simcardno,$simcard->uid,$simcard->customerno);
    }
}
?>

