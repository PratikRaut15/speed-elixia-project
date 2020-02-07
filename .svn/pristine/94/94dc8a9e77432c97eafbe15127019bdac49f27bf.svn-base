<?php
require "../../lib/system/utilities.php";
require '../../lib/bo/DeviceManager.php';
require '../../lib/bo/CustomerManager.php';
require '../../lib/bo/UserManager.php';
require '../../lib/bo/CommunicationQueueManager.php';

$cm = new CustomerManager();
$customernos = $cm->getcustomernos();
if(isset($customernos))
{
    foreach($customernos as $thiscustomerno)
    {
        $count = 0;
        $message = "";
        $message.="<table border = 1><tr><th>Business</th><th>Vehicle No.</th><th>Unit No.</th><th>Last Updated</th><th>Reason</th></tr>";
        $dm = new DeviceManager($thiscustomerno);
        $devices = $dm->getlastupdateddatefordevicesreason($thiscustomerno);
        if(isset($devices))
        {
            foreach ($devices as $device)
            {
                $reason="";
                $ServerDate = date(speedConstants::DEFAULT_DATETIME);
                $ServerDateIST_Less6 = add_hours($ServerDate, -6);
                $ServerDateIST_Less24 = add_hours($ServerDate, -24);
                $lastupdated = date(speedConstants::DEFAULT_DATETIME,strtotime($device->lastupdated));
                if((strtotime($device->lastupdated)<strtotime($ServerDateIST_Less6) && $device->nodata_alert == 0) || (strtotime($device->lastupdated)<strtotime($ServerDateIST_Less24) && $device->nodata_alert == 1))
                {
                    if($device->nodata_alert == 0)
                    {
                        $nodataalert = 1;
                        $dm->updatenodata_alert($device->vehicleid, $nodataalert);
                    }
                    elseif($device->nodata_alert == 1)
                    {
                        $nodataalert = 2;
                        $dm->updatenodata_alert($device->vehicleid, $nodataalert);
                        if($device->powercut == 0)
                        {
                            $reason = "Power Cut";
                        }
                        elseif(round($device->gsmstrength/31*100) < 30)
                        {
                            $reason = "Low Network";
                        }
                        elseif($device->gprsregister < 14)
                        {
                            $reason = "Unknown Reason";
                        }
                        elseif($device->tamper == 1)
                        {
                            $reason = "Tampered";
                        }
                        else
                        {
                            $reason = "Unknown Reason";
                        }
                        $count++;
                        $message.="<tr><td>$device->customercompany</td><td>$device->vehicleno</td><td>$device->unitno</td><td>$lastupdated</td><td>$reason*</td></tr>";
                    }

                }
            }
        }
        $message.="</table>";
        $message.="<br/><br/>";
        $message.="<font size=2>";
        $message.="* - Below are the meaning for <b>Reasons</b>:<br/>";
        $message.="</font>";
        $message.="<table border=1><th><font size=2>Reason</font></th><th><font size=2>Meaning</font></th>";
        $message.="<tr><td><font size=2>Data Pack Inactive</font></td><td><font size=2>The simcard in the device is inactive. Please get in touch with us for support.</font></td></tr>";
        $message.="<tr><td><font size=2>Low Network</font></td><td><font size=2>The vehicle is in low network zone. Please hold on for sometime and you will get all your data.</font></td></tr>";
        $message.="<tr><td><font size=2>Power Cut</font><font size=2 color='FF0000'>(critical)</font></td><td><font size=2>1. The device wire has been cut. Please get in touch with us for support, OR<br/>2. The battery of the vehicle has been removed. Put it back to receive the data, OR<br/>3. The vehicle is under maintenance. The device will automatically send data after maintenance.</font></td></tr>";
        $message.="<tr><td><font size=2>Tampered</font></td><td><font size=2>The vehicle has been tampered. Please get in touch with us for support.</font></td></tr>";
        $message.="<tr><td><font size=2>Unknown Reason</font><font size=2 color='FF0000'>(critical)</font></td><td><font size=2>The system is unable to know the exact reason. Please get in touch with us for support.</font></td></tr>";
        $message.="</table>";
        $message.="<br/><br/>";
        if($count > 0)
        {

            $um = new UserManager($thiscustomerno);
            $users = $um->getuseremailsforcustomer($thiscustomerno);
            if(isset($users))
            {
                foreach($users as $thisuser)
                {
                    if(isset($thisuser->email) && $thisuser->email != "")
                    {
                        $cqm = new CommunicationQueueManager();
                        $cvo = new VOCommunicationQueue();
                        $cvo->email = $thisuser->email;
                        $cvo->subject = "Vehicle Tracking Device Issues";
                        if(sendMail($cvo->email, $cvo->subject, $message) == false)
                        {
                        }

                        $cvo->email = 'sanketsheth1@gmail.com';
                        if(sendMail($cvo->email, $cvo->subject, $message) == false)
                        {

                            $cvo->phone = "";
                            $cvo->type = 0;
                            $cvo->customerno = $thiscustomerno;
                            $cvo->message = "Unable to Send Device Issues";
                            $cqm->InsertQ($cvo);
                        }
                        $cvo->email = 'ankitz@elixiatech.com';
                        if(sendMail($cvo->email, $cvo->subject, $message) == false)
                        {

                            $cvo->phone = "";
                            $cvo->type = 0;
                            $cvo->customerno = $thiscustomerno;
                            $cvo->message = "Unable to Send Device Issues";
                            $cqm->InsertQ($cvo);
                        }
                    }
                }
            }
        }
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
    ?>

