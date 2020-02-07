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
if(isset($customernos))
{
    foreach($customernos as $thiscustomerno)
    {
        $um = new UserManager($thiscustomerno);
        $users = $um->getusersforcustomer($thiscustomerno);
        $timezone = $cm->timezone_name_cron('Asia/Kolkata', $thiscustomerno);
        date_default_timezone_set(''.$timezone.'');
        if(isset($users))
        {
            foreach($users as $user)
            {
                if($user->thistpdf == '1' && $user->email != '' ){
                    //if($user->userkey != '')
                        $encodekey = sha1($user->userkey);
                        $message .= '<html><body>';
                        $message .= 'Dear '.$user->realname.' ,<br>';
                        $message .= '<p></p></br>';
                        $message .= 'Greetings from Elixia Tech!<br/>';
                        $message .= 'Please find the auto-generated Travel History Report for all your vehicles. In case of any queries, you may contact us at 25137470 / 71. Thank you for choosing Elixia Tech!<br/><br/>';                        
                        $message .= "<font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a></font>";
                        $message .= "</body></html>";

                        $to = $user->email;
//                       $to = 'sanketsheth1@gmail.com';

                        $subject = 'Travel History Report';
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
                        $vehiclemanager = new VehicleManager($thiscustomerno);
                        $groups = $um->get_groups_fromuser($thiscustomerno, $user->userid);
                        $vehicles = Array();
                        if(isset($groups))
                        {
                            foreach($groups as $thisgroup)
                            {
                                $vehicles = $vehiclemanager->get_all_groupedvehicles_with_drivers_for_pdf($thisgroup->groupid);            
                                //date_default_timezone_set("Asia/Calcutta");                              
                                $date1 = date("d-m-Y");
                                $date = date('d-m-Y',strtotime("-1 day".$date1));
                                if(isset($vehicles))
                                {
                                    foreach($vehicles as $vehicle )
                                    {
                                        $cat = substr($vehicle->vehicleno,-4);
                                    $location = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_travelhistory.pdf";
                                    $mail->AddAttachment($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_travelhistory.pdf"); // attachment
                                    }
                                }
                            }
                        }
                        else if($groups == null)
                        {
                            $groupid = 0;
                            $vehicles = $vehiclemanager->get_all_groupedvehicles_with_drivers_for_pdf($groupid);            
                                //date_default_timezone_set("Asia/Calcutta");                              
                                $date1 = date("d-m-Y");
                                $date = date('d-m-Y',strtotime("-1 day".$date1));
                                if(isset($vehicles))
                                {
                                    foreach($vehicles as $vehicle )
                                    {
                                        $cat = substr($vehicle->vehicleno,-4);
                                    $location = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_travelhistory.pdf";
                                    $mail->AddAttachment($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_travelhistory.pdf"); // attachment
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
                if($user->thistcsv == '1' && $user->email != '' ){
                        $encodekey = sha1($user->userkey);
                        $message .= '<html><body>';
                        $message .= 'Dear '.$user->realname.' ,<br>';
                        $message .= '<p></p></br>';
                        $message .= 'Greetings from Elixia Tech!<br/>';
                        $message .= 'Please find the auto-generated Travel History Report for all your vehicles. In case of any queries, you may contact us at 25137470 / 71. Thank you for choosing Elixia Tech!<br/><br/>';                        
                        $message .= "<font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a></font>";
                        $message .= "</body></html>";

                        $to = $user->email;
//                        $to = 'sanketsheth1@gmail.com';

                        $subject = 'Travel History Report';
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
                        $vehiclemanager = new VehicleManager($thiscustomerno);
                        $groups = $um->get_groups_fromuser($thiscustomerno, $user->userid);
                        $vehicles = Array();
                        if(isset($groups))
                        {
                            foreach($groups as $thisgroup)
                            {
                                $vehicles = $vehiclemanager->get_all_groupedvehicles_with_drivers_for_pdf($thisgroup->groupid);            
                                //date_default_timezone_set("Asia/Calcutta");                              
                                $date1 = date("d-m-Y");
                                $date = date('d-m-Y',strtotime("-1 day".$date1));
                                if(isset($vehicles))
                                {
                                    foreach($vehicles as $vehicle )
                                    {
                                        $cat = substr($vehicle->vehicleno,-4);
                                    $location = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_travelhistory.xls";
                                    $mail->AddAttachment($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_travelhistory.xls"); // attachment
                                    }
                                }
                            }
                        }
                        else if($groups == null)
                        {
                            $groupid = 0;
                            $vehicles = $vehiclemanager->get_all_groupedvehicles_with_drivers_for_pdf($groupid);            
                            //date_default_timezone_set("Asia/Calcutta");                              
                            $date1 = date("d-m-Y");
                            $date = date('d-m-Y',strtotime("-1 day".$date1));
                                if(isset($vehicles))
                                {
                                    foreach($vehicles as $vehicle )
                                    {
                                        $cat = substr($vehicle->vehicleno,-4);
                                    $location = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_travelhistory.xls";
                                    $mail->AddAttachment($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_travelhistory.xls"); // attachment
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
                if($user->gensetpdf == '1' && $user->email != '' ){
                        $encodekey = sha1($user->userkey);
                        $message .= '<html><body>';
                        $message .= 'Dear '.$user->realname.' ,<br>';
                        $message .= '<p></p></br>';
                        $message .= 'Greetings from Elixia Tech!<br/>';
                        $message .= 'Please find the auto-generated Genset Sensor Report for all your vehicles. In case of any queries, you may contact us at 25137470 / 71. Thank you for choosing Elixia Tech!<br/><br/>';                        
                        $message .= "<font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a></font>";
                        $message .= "</body></html>";

                        $to = $user->email;
 //                       $to = 'sanketsheth1@gmail.com';

                        $subject = 'Genset Sensor Report';
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
                        $vehiclemanager = new VehicleManager($thiscustomerno);
                        $groups = $um->get_groups_fromuser($thiscustomerno, $user->userid);
                        $vehicles = Array();
                        if(isset($groups))
                        {
                            foreach($groups as $thisgroup)
                            {
                                $vehicles = $vehiclemanager->get_all_groupedvehicles_with_drivers_for_pdf($thisgroup->groupid);            
                                //date_default_timezone_set("Asia/Calcutta");                              
                                $date1 = date("d-m-Y");
                                $date = date('d-m-Y',strtotime("-1 day".$date1));
                                if(isset($vehicles))
                                {
                                    foreach($vehicles as $vehicle )
                                    {
                                        $cat = substr($vehicle->vehicleno,-4);
                                    $location = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf";
                                    $mail->AddAttachment($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf"); // attachment
                                    }
                                }
                            }
                        }
                        else if($groups == null)
                        {
                            $groupid = 0;
                            $vehicles = $vehiclemanager->get_all_groupedvehicles_with_drivers_for_pdf($groupid);            
                            //date_default_timezone_set("Asia/Calcutta");                              
                            $date1 = date("d-m-Y");
                            $date = date('d-m-Y',strtotime("-1 day".$date1));
                                if(isset($vehicles))
                                {
                                    foreach($vehicles as $vehicle )
                                    {
                                        $cat = substr($vehicle->vehicleno,-4);
                                    $location = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf";
                                    $mail->AddAttachment($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf"); // attachment
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
                if($user->gensetcsv == '1' && $user->email != '' ){
                        $encodekey = sha1($user->userkey);
                        $message .= '<html><body>';
                        $message .= 'Dear '.$user->realname.' ,<br>';
                        $message .= '<p></p></br>';
                        $message .= 'Greetings from Elixia Tech!<br/>';
                        $message .= 'Please find the auto-generated Genset Sensor Report for all your vehicles. In case of any queries, you may contact us at 25137470 / 71. Thank you for choosing Elixia Tech!<br/><br/>';                        
                        $message .= "<font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a></font>";
                        $message .= "</body></html>";

                        $to = $user->email;
//                        $to = 'sanketsheth1@gmail.com';

                        $subject = 'Genset Sensor Report';
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
                        $vehiclemanager = new VehicleManager($thiscustomerno);
                        $groups = $um->get_groups_fromuser($thiscustomerno, $user->userid);
                        $vehicles = Array();
                        if(isset($groups))
                        {
                            foreach($groups as $thisgroup)
                            {
                                $vehicles = $vehiclemanager->get_all_groupedvehicles_with_drivers_for_pdf($thisgroup->groupid);            
                                //date_default_timezone_set("Asia/Calcutta");                              
                                $date1 = date("d-m-Y");
                                $date = date('d-m-Y',strtotime("-1 day".$date1));
                                if(isset($vehicles))
                                {
                                    foreach($vehicles as $vehicle )
                                    {
                                        $cat = substr($vehicle->vehicleno,-4);
                                    $location = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                    $mail->AddAttachment($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls"); // attachment
                                    }
                                }
                            }
                        }
                        else if($groups == null)
                        {
                            $groupid = 0;
                            $vehicles = $vehiclemanager->get_all_groupedvehicles_with_drivers_for_pdf($groupid);            
                           // date_default_timezone_set("Asia/Calcutta");                              
                            $date1 = date("d-m-Y");
                            $date = date('d-m-Y',strtotime("-1 day".$date1));
                                if(isset($vehicles))
                                {
                                    foreach($vehicles as $vehicle )
                                    {
                                        $cat = substr($vehicle->vehicleno,-4);
                                    $location = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                    $mail->AddAttachment($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls"); // attachment
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
}
?>

