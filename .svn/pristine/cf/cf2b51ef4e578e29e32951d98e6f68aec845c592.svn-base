<?php
require "../../lib/system/utilities.php";
require '../../lib/bo/DeviceManager.php';
require '../../lib/bo/CustomerManager.php';

$cm = new CustomerManager();
$customernos = $cm->getcustomernos();
$message = "";
if(isset($customernos))
{
    $message.="<table border='1'><tr><th>Customer #</th><th># of units</th></tr>";
    foreach($customernos as $thiscustomerno)
    {
        $dm = new DeviceManager($thiscustomerno);
        $devices = $dm->getlastupdateddatefordevices($thiscustomerno);
        if(isset($devices))
        {
            $count = 0;
            $counttotal = 0;            
            foreach ($devices as $device)
            {
                $counttotal++;
                
                //date_default_timezone_set("Asia/Calcutta");  
                //ini_set('date.timezone', 'Asia/Calcutta');
                $timezone = $cm->timezone_name_cron('Asia/Kolkata', $thiscustomerno);
                date_default_timezone_set(''.$timezone.'');
                ini_set('date.timezone', ''.$timezone.'');
                $ServerDate = date("Y-m-d H:i:s"); 

                $ServerDateIST_Less3 = add_hours($ServerDate, -3);                
                if(strtotime($device->lastupdated)<strtotime($ServerDateIST_Less3))
                {
                    $count++;                    
                }
            }
            $message.="<tr><td>$device->customerno</td><td>$count / $counttotal</td></tr>";                                    
        }        
    }
    $message.="</table>";

    $emails = Array();
    $emails[] = 'sanketsheth1@gmail.com';
    $emails[] = 'zatakia.ankit@gmail.com';    
    $emails[] = 'dinesht@elixiatech.com';
    $emails[] = 'mansip@elixiatech.com';
    $emails[] = 'dharmendrar@elixiatech.com';
    
    $subject = "Unit Report";

    foreach($emails as $email)
    {
        sendMail($email, $subject, $message);
    }
}

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
