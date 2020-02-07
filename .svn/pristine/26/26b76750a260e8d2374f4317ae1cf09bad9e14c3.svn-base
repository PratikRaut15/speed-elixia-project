<?PHP
require "../../lib/system/utilities.php";
require '../../lib/bo/CustomerManager.php';
require '../../lib/bo/CommunicationQueueManager.php';
require '../../lib/bo/UserManager.php';
require '../../lib/bo/VehicleManager.php';

$serverpath = $_SERVER['DOCUMENT_ROOT']."/speed";
$cm = new CustomerManager();
$customernos = $cm->getcustomernos();
if(isset($customernos))
{
    foreach($customernos as $thiscustomerno)
    {
        $um = new UserManager();
        $users = $um->getusersforcustomerfortravelhist($thiscustomerno);
        if(isset($users))
        {
            foreach($users as $user)
            {
                $message .= '<html><body>';
                $message .= 'Dear '.$user->username.' ,<br>';
                $message .= '<p></p></br>';
                $message .= "To unsubscribe email, please login to your account on <a href='http://www.elixiatech.com/speed'>elixiaspeed</a>.";
                $message .= "</body></html>";

                $to = $user->email;

                $subject = 'Travel History Report';
                $from = 'info@elixiatech.com';
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
                $vehiclemanager = new VehicleManager($thiscustomerno);
                $groups = $um->get_groups_fromuser($thiscustomerno, $user->userid);
                $vehicles = Array();
                if(isset($groups))
                {
                    foreach($groups as $thisgroup)
                    {
                        $vehicles = $vehiclemanager->get_all_groupedvehicles_with_drivers_for_pdf($thisgroup->groupid);            
                        $date = date("d-m-Y");
                        if(isset($vehicles))
                        {
                            foreach($vehicles as $vehicle )
                            {
                                $cat = $vehicle->vehicleno;
                            $location = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/travelhistory_".$cat."_".$date.".pdf";
                            $mail->AddAttachment($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/travelhistory_".$cat."_".$date.".pdf"); // attachment
                            }
                        }
                    }
                }
                else if($groups == null)
                {
                    $groupid = 0;
                    $vehicles = $vehiclemanager->get_all_groupedvehicles_with_drivers_for_pdf($groupid);            
                        $date = date("d-m-Y");
                        if(isset($vehicles))
                        {
                            foreach($vehicles as $vehicle )
                            {
                                $cat = $vehicle->vehicleno;
                            $location = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/travelhistory_".$cat."_".$date.".pdf";
                            $mail->AddAttachment($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/travelhistory_".$cat."_".$date.".pdf"); // attachment
                            }
                        }
                }

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
?>

