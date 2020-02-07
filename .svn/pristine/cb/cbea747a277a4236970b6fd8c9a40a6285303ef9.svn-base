<?php
require "../../lib/system/utilities.php";
require '../../lib/bo/CustomerManager.php';
require '../../lib/bo/UserManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/UnitManager.php';
include_once '../../lib/bo/CommunicationQueueManager.php';
include_once 'files/dailyreport.php';


class VODatacap{}
$cm = new CustomerManager();
$customernos = $cm->getcustomernos_ForMaintenance();
$today = date('Y-m-d');
if(isset($customernos))
{
    foreach($customernos as $thiscustomerno)
    {
        $thiscustomerno;
        $vm = new VehicleManager($thiscustomerno);
        $vehicles =  $vm->getAlertVehiclesByCustomer();
        if($vehicles)
        {
            foreach($vehicles as $vehicle)
            {
               
                
                $pucday = date_SDiff_utility(date('Y-m-d', strtotime($vehicle->puc_expiry)), $today);
                $regday = date_SDiff_utility(date('Y-m-d', strtotime($vehicle->reg_expiry)), $today);
                $insuranceday = date_SDiff_utility(date('Y-m-d', strtotime($vehicle->insurance_expiry)), $today);
                $other1day = date_SDiff_utility(date('Y-m-d', strtotime($vehicle->other1_expiry)), $today);
                $other2day = date_SDiff_utility(date('Y-m-d', strtotime($vehicle->other2_expiry)), $today);
                $other3day = date_SDiff_utility(date('Y-m-d', strtotime($vehicle->other3_expiry)), $today);
                if(strtotime($vehicle->puc_expiry) >= strtotime($today) && $vehicle->puc_expiry!='0000-00-00 00:00:00' && $vehicle->puc_sms_email == $pucday)
                {
                        $um = new UserManager($thiscustomerno);
                        $users = $um->getusersforcustomerformaintenance($thiscustomerno, $vehicle->vehicleid);
                        
                        foreach($users as $user)
                        {
                            if($user->email != '' || $user->phone !='')
                            {
                                $mail = new VODatacap();
                                $mail->vehicleno = $vehicle->vehicleno;
                                $mail->expiry = $vehicle->puc_expiry;
                                $mail->document= "PUC ";
                                $mail->username = $user->username;
                                $mail->realname = $user->realname;
                                $mail->email = $user->email;
                                $mail->phone = $user->phone;
                                $mail->subject = "PUC Expiry Remainder For ".$vehicle->vehicleno;
                                $mail->message = "PUC Expire For ".$vehicle->vehicleno." On ".date('d-m-Y', strtotime($vehicle->puc_expiry))." \nPowerd By Elixia Tech";
                                $check = $um->checkGroupman($user->customerno,$user->userid);
                               if($check == 1)
                                {
                                   if($mail->email != '')
                                   {
                                    Send_Email($mail);
                                   }
                                   if($mail->phone != '')
                                   {
                                       sendSMS($mail->phone, $mail->message, $thiscustomerno, $vehicle->vehicleid);
                                   }
                                  
                                }
                                elseif($check == $vehicle->groupid && $check !=0)
                                {
                                    if($mail->email != '')
                                    {
                                    Send_Email($mail);
                                    }
                                   if($mail->phone != '')
                                    {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $vehicle->vehicleid);
                                    }
                                }
                                
                            }
                        }
                    }
               
                if(strtotime($vehicle->reg_expiry) >= strtotime($today) && $vehicle->reg_expiry!='0000-00-00 00:00:00' && $vehicle->reg_sms_email == $regday)
                {
                        $um = new UserManager($thiscustomerno);
                        $users = $um->getusersforcustomerformaintenance($thiscustomerno, $vehicle->vehicleid);
                        foreach($users as $user)
                        {
                            if($user->email != '' || $user->phone !='')
                            {
                                $mail = new VODatacap();
                                $mail->vehicleno = $vehicle->vehicleno;
                                $mail->expiry = $vehicle->reg_expiry;
                                $mail->document= "Registration ";
                                $mail->username = $user->username;
                                $mail->realname = $user->realname;
                                $mail->email = $user->email;
                                $mail->phone = $user->phone;
                                $mail->subject = "Registration Expiry Remainder For ".$vehicle->vehicleno;
                                $mail->message = "Registration Expire For ".$vehicle->vehicleno." On ".date('d-m-Y', strtotime($vehicle->reg_expiry))." \nPowerd By Elixia Tech";
                                $check = $um->checkGroupman($user->customerno,$user->userid);
                               if($check == 1)
                                {
                                   if($mail->email != '')
                                   {
                                    Send_Email($mail);
                                   }
                                   if($mail->phone != '')
                                   {
                                      sendSMS($mail->phone, $mail->message, $thiscustomerno, $vehicle->vehicleid);
                                   }
                                  
                                }
                                elseif($check == $vehicle->groupid && $check !=0)
                                {
                                    if($mail->email != '')
                                    {
                                    Send_Email($mail);
                                    }
                                   if($mail->phone != '')
                                    {
                                         sendSMS($mail->phone, $mail->message, $thiscustomerno, $vehicle->vehicleid);
                                    }
                                }
                                
                            }
                        }
                    }
                  
                if(strtotime($vehicle->insurance_expiry) >= strtotime($today) && $vehicle->insurance_expiry!='0000-00-00 00:00:00' && $vehicle->insurance_sms_email == $insuranceday)
                {
                        $um = new UserManager($thiscustomerno);
                        $users = $um->getusersforcustomerformaintenance($thiscustomerno, $vehicle->vehicleid);
                        foreach($users as $user)
                        {
                            if($user->email != '' || $user->phone !='')
                            {
                                $mail = new VODatacap();
                                $mail->vehicleno = $vehicle->vehicleno;
                                $mail->expiry = $vehicle->insurance_expiry;
                                $mail->document= "Insurance ";
                                $mail->username = $user->username;
                                $mail->realname = $user->realname;
                                $mail->email = $user->email;
                                $mail->phone = $user->phone;
                                $mail->subject = "Insurance Expiry Remainder For ".$vehicle->vehicleno;
                                $mail->message = "Insurance Expire For ".$vehicle->vehicleno." On ".date('d-m-Y', strtotime($vehicle->insurance_expiry))." \nPowerd By Elixia Tech";
                                $check = $um->checkGroupman($user->customerno,$user->userid);
                               if($check == 1)
                                {
                                   if($mail->email != '')
                                   {
                                    Send_Email($mail);
                                   }
                                   if($mail->phone != '')
                                   {
                                       sendSMS($mail->phone, $mail->message, $thiscustomerno, $vehicle->vehicleid);
                                   }
                                  
                                }
                                elseif($check == $vehicle->groupid && $check !=0)
                                {
                                    if($mail->email != '')
                                    {
                                    Send_Email($mail);
                                    }
                                   if($mail->phone != '')
                                    {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $vehicle->vehicleid);
                                    }
                                }
                                
                            }
                        }
                    }     
                   
                if(strtotime($vehicle->other1_expiry) >= strtotime($today) && $vehicle->other1_expiry!='0000-00-00 00:00:00' && $vehicle->other1_sms_email == $other1day)
                {
                        $um = new UserManager($thiscustomerno);
                        $users = $um->getusersforcustomerformaintenance($thiscustomerno, $vehicle->vehicleid);
                        foreach($users as $user)
                        {
                            if($user->email != '' || $user->phone !='')
                            {
                                $mail = new VODatacap();
                                $mail->vehicleno = $vehicle->vehicleno;
                                $mail->expiry = $vehicle->other1_expiry;
                                $mail->document= "Other Document 1 ";
                                $mail->username = $user->username;
                                $mail->realname = $user->realname;
                                $mail->email = $user->email;
                                $mail->phone = $user->phone;
                                $mail->subject = "Document 1 Expiry Remainder For ".$vehicle->vehicleno;
                                $mail->message = "Document 1 Expire For ".$vehicle->vehicleno." On ".date('d-m-Y', strtotime($vehicle->other3_expiry))." \nPowerd By Elixia Tech";
                                $check = $um->checkGroupman($user->customerno,$user->userid);
                               if($check == 1)
                                {
                                   if($mail->email != '')
                                   {
                                    Send_Email($mail);
                                   }
                                   if($mail->phone != '')
                                   {
                                       sendSMS($mail->phone, $mail->message, $thiscustomerno, $vehicle->vehicleid);
                                   }
                                  
                                }
                                elseif($check == $vehicle->groupid && $check !=0)
                                {
                                    if($mail->email != '')
                                    {
                                    Send_Email($mail);
                                    }
                                   if($mail->phone != '')
                                    {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $vehicle->vehicleid);
                                    }
                                }
                                
                            }
                        }
                    }  
                  
                if(strtotime($vehicle->other2_expiry) >= strtotime($today) && $vehicle->other2_expiry!='0000-00-00 00:00:00' && $vehicle->other2_sms_email == $other2day)
                {
                        $um = new UserManager($thiscustomerno);
                        $users = $um->getusersforcustomerformaintenance($thiscustomerno, $vehicle->vehicleid);
                        foreach($users as $user)
                        {
                            if($user->email != '' || $user->phone !='')
                            {
                                $mail = new VODatacap();
                                $mail->vehicleno = $vehicle->vehicleno;
                                $mail->expiry = $vehicle->other2_expiry;
                                $mail->document= "Other Document 2 ";
                                $mail->username = $user->username;
                                $mail->realname = $user->realname;
                                $mail->email = $user->email;
                                $mail->phone = $user->phone;
                                $mail->subject = "Document 2 Expiry Remainder For ".$vehicle->vehicleno;
                                $mail->message = "Document 2 Expire For ".$vehicle->vehicleno." On ".date('d-m-Y', strtotime($vehicle->other3_expiry))." \nPowerd By Elixia Tech";
                                $check = $um->checkGroupman($user->customerno,$user->userid);
                               if($check == 1)
                                {
                                   if($mail->email != '')
                                   {
                                     Send_Email($mail);
                                   }
                                   if($mail->phone != '')
                                   {
                                       sendSMS($mail->phone, $mail->message, $thiscustomerno, $vehicle->vehicleid);
                                   }
                                  
                                }
                                elseif($check == $vehicle->groupid && $check !=0)
                                {
                                    if($mail->email != '')
                                    {
                                    Send_Email($mail);
                                    }
                                   if($mail->phone != '')
                                    {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $vehicle->vehicleid);
                                    }
                                }
                                
                            }
                        }
                    }
                    
                if(strtotime($vehicle->other3_expiry) >= strtotime($today) && $vehicle->other3_expiry!='0000-00-00 00:00:00' && $vehicle->other3_sms_email == $other3day)
                {
                        $um = new UserManager($thiscustomerno);
                        $users = $um->getusersforcustomerformaintenance($thiscustomerno, $vehicle->vehicleid);
                        foreach($users as $user)
                        {
                            if($user->email != '' || $user->phone !='')
                            {
                                $mail = new VODatacap();
                                $mail->vehicleno = $vehicle->vehicleno;
                                $mail->expiry = $vehicle->other3_expiry;
                                $mail->document= "Other Document 3 ";
                                $mail->username = $user->username;
                                $mail->realname = $user->realname;
                                $mail->email = $user->email;
                                $mail->phone = $user->phone;
                                $mail->subject = "Document 3 Expiry Remainder For ".$vehicle->vehicleno;
                                $mail->message = "Document 3 Expire For ".$vehicle->vehicleno." On ".date('d-m-Y', strtotime($vehicle->other3_expiry))." \nPowerd By Elixia Tech";
                                $check = $um->checkGroupman($user->customerno,$user->userid);
                               if($check == 1)
                                {
                                   if($mail->email != '')
                                   {
                                     Send_Email($mail);
                                   }
                                   if($mail->phone != '')
                                   {
                                      sendSMS($mail->phone, $mail->message, $thiscustomerno, $vehicle->vehicleid);
                                   }
                                  
                                }
                                elseif($check == $vehicle->groupid && $check !=0)
                                {
                                    if($mail->email != '')
                                    {
                                    Send_Email($mail);
                                    }
                                   if($mail->phone != '')
                                    {
                                         sendSMS($mail->phone, $mail->message, $thiscustomerno, $vehicle->vehicleid);
                                    }
                                }
                                
                            }
                        }
                    }
                    
                    
                $taxalert = $vm->getTaxAlert($vehicle->vehicleid);
                if(isset($taxalert) && !empty($taxalert)){
                foreach($taxalert as $talert){
                    $taxday = date_SDiff_utility(date('Y-m-d', strtotime($talert->tax_expiry)), $today);
                    if(strtotime($talert->tax_expiry) >= strtotime($today) && $talert->tax_expiry!='0000-00-00' && $talert->tax_sms_email == $taxday){
                        
                        $um = new UserManager($thiscustomerno);
                        $users = $um->getusersforcustomerformaintenance($thiscustomerno, $vehicle->vehicleid);
                        foreach($users as $user)
                        {
                            if($user->email != '' || $user->phone !='')
                            {
                                $mail = new VODatacap();
                                $mail->vehicleno = $vehicle->vehicleno;
                                $mail->expiry = $talert->tax_expiry;
                                $mail->document= "Tax Renewal ";
                                $mail->username = $user->username;
                                $mail->realname = $user->realname;
                                $mail->email = $user->email;
                                $mail->phone = $user->phone;
                                $mail->subject = "Tax Renewals For ".$vehicle->vehicleno;
                                $mail->message = "Tax Renewals For ".$vehicle->vehicleno." On ".date('d-m-Y', strtotime($talert->tax_expiry))." \nPowerd By Elixia Tech";
                                $check = $um->checkGroupman($user->customerno,$user->userid);
                               if($check == 1)
                                {
                                   if($mail->email != '')
                                   {
                                     Send_Email($mail);
                                   }
                                   if($mail->phone != '')
                                   {
                                      sendSMS($mail->phone, $mail->message, $thiscustomerno, $vehicle->vehicleid);
                                   }
                                  
                                }
                                elseif($check == $vehicle->groupid && $check !=0)
                                {
                                    if($mail->email != '')
                                    {
                                    Send_Email($mail);
                                    }
                                   if($mail->phone != '')
                                    {
                                         sendSMS($mail->phone, $mail->message, $thiscustomerno, $vehicle->vehicleid);
                                    }
                                }
                                
                            }
                        }
                        
                    }
                }
                }    
             }
        }
    }
    
}

function Send_Email($mail)
{
                        
                        $message .= '<html><body>';
                        $message .= 'Dear '.$mail->realname.' ,<br>';
                        $message .= '<p></p></br>';
                        $message .= 'Greetings from Elixia Tech!<br/>';
                        $message .= 'Please find the Document detail which are about to expire. <br/></br>';      
                        $message .= '<table style="border:1px solid #ccc;" cellspacing="0" cellpadding="0">
                                        <tr style="background-color:#ccc;height:25px;">
                                            <td colspan="3" id="formheader" style="text-align:center; border:1px solid #ccc; padding:5px;">Document Expiry Remainder</td>
                                        </tr>
                                        <tr>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">Vehicle No</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">Document Name</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">Expiry Date</td>
                                        </tr>
                                        <tr>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">'.$mail->vehicleno.'</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">'.$mail->document.'</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">'.date('Y-m-d', strtotime($mail->expiry)).'</td>
                                        </tr>
                                     </table></br></br></br>';
                        $message .='</body></html>';
                        //sendMail($mail->email, $mail->subject, $message);
                        echo $message;
}


function sendMail( $to, $subject , $content)
{
    $headers = "From: noreply@elixiatech.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    include_once("class.phpmailer.php");
    $mail = new PHPMailer();
    $mail->IsMail();
    $mail->AddAddress($to);
    $mail->From     = "noreply@elixiatech.com\r\n";
    $mail->FromName = "Elixia Speed";
    $mail->Sender = "noreply@elixiatech.com\r\n";
    $mail->Subject = $subject;
    $mail->Body = $content;
    $mail->IsHTML(true); 
    $mail->AddReplyTo("From: noreply@elixiatech.com\r\n","Elixia Speed");
    if(!$mail->Send())
    {
     echo "Error sending: " . $mail->ErrorInfo;
     $content = '';
    }
    else
    {
      echo "Mail sent";
      $content = '';
    }        
}

function sendSMS($phone, $message, $customerno, $vehicleid)
{
     
    $cm = new CustomerManager();
    $sms = $cm->pullsmsdetails($customerno);
    $vehiclesms = $cm->pullvehiclesmsmdetails($vehicleid, $customerno);
    if($vehiclesms->smslock == 0 && $sms->smsleft > 0)
    {
        if($sms->smsleft == 20)
        {
            $cqm = new ComQueueManager();
            $cvo = new VOComQueue();
            $cvo->phone = $phone;
            $cvo->message = "Your SMS pack is too low. Please contact an Elixir. Powered by Elixia Tech.";
            $cvo->subject = "";
            $cvo->email = "";
            $cvo->type = 1;
            $cvo->customerno = $customerno;                                    
            $cqm->InsertQ($cvo);            
        }
        $smsleft = $sms->smsleft-1;
        $cm->updatesms($smsleft,$customerno,$vehicleid);
//        $url = "http://india.msg91.com/sendhttp.php?user=sanketsheth&password=271257&mobiles=".urlencode($phone)."&message=".urlencode($message)."&sender=Elixia&route=4";
        echo $url = "http://pacems.asia:8080/bulksms/bulksms?username=xzt-elixia&password=elixia&type=0&dlr=1&destination=91".urlencode($phone)."&source=ELIXIA&message=".urlencode($message);        
       /*
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);    
        return true;
        */
        }
        else
        {
            return false;
        }


}