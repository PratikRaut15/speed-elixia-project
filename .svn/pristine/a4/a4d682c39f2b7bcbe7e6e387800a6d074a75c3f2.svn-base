<?php
require "../../lib/system/utilities.php";
require '../../lib/bo/DeviceManager.php';
require '../../lib/bo/CustomerManager.php';
require '../../lib/bo/CommunicationQueueManager.php';

$cm = new CustomerManager();
$customernos = $cm->getcustomernos();
$message = "";
if(isset($customernos))
{
    $message.="<table border = 1><tr><th>Customer #</th><th>Company</th><th>Last Updated</th><th>Vehicle #</th><th>Unit #</th><th>Reason</th><th>GPRS</th><th>Sim #</th></tr>";
    foreach($customernos as $thiscustomerno)
    {
        if($thiscustomerno != 1 && $thiscustomerno != 18 && $thiscustomerno != 16 && $thiscustomerno != 19)
        {
            $dm = new DeviceManager($thiscustomerno);
            $devices = $dm->getlastupdateddatefordevicesreason($thiscustomerno);
            if(isset($devices))
            {
                foreach ($devices as $device)
                {
                    if($device->gprsregister >= 14)
                    {
                        $gprsvalue = "Perfect";
                    }
                    else
                    {
                        $gprsvalue = "Data Packet Inactivated";
                    }                    
                    
                    //date_default_timezone_set("Asia/Calcutta");  
                    //ini_set('date.timezone', 'Asia/Calcutta');
                    $timezone = $cm->timezone_name_cron('Asia/Kolkata', $thiscustomerno);
                    date_default_timezone_set(''.$timezone.'');
                    ini_set('date.timezone', ''.$timezone.'');
                    $ServerDate = date("Y-m-d H:i:s"); 
                    
                    $ServerDateIST_Less3 = add_hours($ServerDate, -3);
                    $lastupdated = date("M j",strtotime($device->lastupdated));
                    if(strtotime($device->lastupdated)<strtotime($ServerDateIST_Less3))
                    {
                        if($device->powercut == 0)
                        {
                            $message.="<tr><td>$device->customerno</td><td>$device->customercompany</td><td>$lastupdated</td><td>$device->vehicleno</td><td>$device->unitno</td><td>Power Cut</td><td>$gprsvalue</td><td>$device->phone</td></tr>";                                        
                        }
                        elseif(round($device->gsmstrength/31*100) < 30)
                        {
                            $message.="<tr><td>$device->customerno</td><td>$device->customercompany</td><td>$lastupdated</td><td>$device->vehicleno</td><td>$device->unitno</td><td>Low Network</td><td>$gprsvalue</td><td>$device->phone</td></tr>";                                                                
                        }
                        else
                        {
                            $message.="<tr><td>$device->customerno</td><td>$device->customercompany</td><td>$lastupdated</td><td>$device->vehicleno</td><td>$device->unitno</td><td>Unknown Reason</td><td>$gprsvalue</td><td>$device->phone</td></tr>";                                                                                            
                        } 
                    }
                    elseif($device->tamper == 1)
                    {
                        $message.="<tr bgcolor='#00FF00'><td>$device->customerno</td><td>$device->customercompany</td><td>$lastupdated</td><td>$device->vehicleno</td><td>$device->unitno</td><td>Tampered</td><td>$gprsvalue</td><td>$device->phone</td></tr>";                                                                                    
                    }
                }
            }        
        }
    }
    $message.="</table>";

    $cqm = new CommunicationQueueManager();
    $cvo = new VOCommunicationQueue();
    $cvo->email = 'sanketsheth1@gmail.com';    
    $cvo->subject = "Device Issues";
    
    if(sendMail($cvo->email, $cvo->subject, $message) == false)
    {
        
        $cvo->phone = "";
        $cvo->type = 0;
        $cvo->customerno = $thiscustomerno;                    
        $cvo->message = "Unable to Send Device Issues";        
        $cqm->InsertQ($cvo);             
    }

    $cvo->email = 'zatakia.ankit@gmail.com';    
    if(sendMail($cvo->email, $cvo->subject, $message) == false)
    {
        
        $cvo->phone = "";
        $cvo->type = 0;
        $cvo->customerno = $thiscustomerno;                    
        $cvo->message = "Unable to Send Device Issues";        
        $cqm->InsertQ($cvo);             
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
