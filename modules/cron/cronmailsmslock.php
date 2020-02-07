<?php
include_once("../../session/sessionhelper.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/utilities.php");
include_once("../../lib/bo/CustomerManager.php");
include_once("../../lib/bo/VehicleManager.php");
require_once '../../lib/bo/UserManager.php';
include_once("../../lib/model/VOComQueue.php");
require_once '../../lib/bo/CronManager.php';

function sendMail( $to, $subject , $content)
{
    $subject = $subject;

    $headers = "From: noreply@elixiatech.com\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";    
    if (!@mail($to, $subject, $content, $headers)) {
        // message sending failed
        return false;
    }
    return true;        
}

$cm = new CustomerManager();
$vehicle = $cm->getvehicles_unlocked();

if(isset($vehicle))
    {
    foreach($vehicle as $vehicles)
        {
        $vehicleid = $vehicles->vehicleid;
        $sms  = $cm->pullvehiclesmsmdetails($vehicleid, $vehicles->customerno);
        if($sms->smscount >= 10)
        {
            $cronm = new CronManager();
            $cm->setlock($vehicleid, $vehicles->customerno);
            $users = $cronm->getunfilteredusersforcustomer($vehicles->customerno);
            if(isset($users))
            {
                foreach($users as $thisuser)
                {
                    if(isset($thisuser->email) && $thisuser->email != "")
                    {
                        $dates = new Date();                            
                        $subject = 'SMS Limit Crossed';
                        $emailmessage = "Your SMS limit is crossed so we have locked the sms service for your account. If you want to activate the service please contact to administrator.<br/><br/> Powered by Elixia Tech.<br/><br/><br/>";
                        $cvo = new VOComQueue();            
                        sendMail($thisuser->email, $subject, $emailmessage);
                    }
                }
            }
        }
    }
}

?>