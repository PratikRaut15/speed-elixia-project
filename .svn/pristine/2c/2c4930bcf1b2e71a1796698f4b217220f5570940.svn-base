<?PHP
require_once "../../lib/system/utilities.php";
require_once '../../lib/bo/CustomerManager.php';
require_once '../../lib/bo/DeviceManager.php';

//$serverpath = $_SERVER['DOCUMENT_ROOT']."/speed";
//$serverpath = "/home/elixia5/public_html/elixiatech.com/speed";
$cm = new CustomerManager();
$customernos = $cm->get_distinct_customerno_for_devices_expirydate();
if(isset($customernos))
{
    foreach($customernos as $thiscustomerno)
    {
        //echo $thiscustomerno->customerno;
                        $devmanager = new DeviceManager($thiscustomerno->customerno);
                        $devices = $devmanager->get_all_devices_expirydate($thiscustomerno->customerno);
                        $i='1';
                        $message .= '<html><body>';
                        $message .= 'Dear '.$thiscustomerno->customername.' ,<br>';
                        $message .= '<p></p></br>';
                        $message .= 'Greetings from Elixia Tech!<br/>';
                        $message .= 'Please find the Device detail Which are about to expire. In case of any queries, you may contact us at 25137470 / 71. Thank you for choosing Elixia Tech!<br/><br/>';      
                        
                        $message .= '<table>
                                    <thead>
                                    <tr>
                                        <th colspan="6" id="formheader">Contract Information</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Sr #</td>
                                        <td>Unit #</td>
                                        <td>Date of Expiry</td>
                                    </tr>';
                        foreach($devices as $contract)
                        {
                        //$date = new DateTime($contract->registeredon);
                        $expirydate = date("d-M-Y", strtotime($contract->expirydate));
                        $message .= '<tr>
                                <td>'.$i++.'</td>
                                <td>'.$contract->unitno.'</td>
                                <td>'.$expirydate.'</td>
                            </tr>';
                        }
                        $message .= "</body></html>";

                        //$to = $thiscustomerno->customeremail;
                       $to = 'sanketsheth1@gmail.com';
                       $cc = '';
                        $subject = 'Device Expiry Alert';
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
                        }
    }
}
?>

