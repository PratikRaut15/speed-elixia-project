<?php
require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';
$message = "";
$message.= "The following vehicles went inactive 24 hours ago: <br/><br/>";
$message.="<table border = 1 style='text-align:center;'><tr><th>Sr No.</th><th>Customer Name</th><th>Customer No</th><th>Vehicle No.</th><th>Group</th><th>Unit No.</th><th>Last Updated</th><th>Reason</th><th>Relationship Manager</th></tr>";
$x = 1;
$cm = new CronManager();
$devices = $cm->getlastupdateddatefordevicesreason(0);
if(isset($devices))
{
    foreach ($devices as $device)
    {
        $reason="";
        $ServerDate = date("Y-m-d H:i:s");
        $ServerDateIST_Less24 = add_hours($ServerDate, -24);
//        $ServerDateIST_Less96 = add_hours($ServerDate, -96);
        $lastupdated = date(speedConstants::DEFAULT_DATETIME,strtotime($device->lastupdated));
        if(strtotime($device->lastupdated)<strtotime($ServerDateIST_Less24))
        {
            $dm = new DeviceManager($device->customerno);
            $nodataalert = 1;
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

            $objGroup = new GroupManager($device->customerno);
            $groupDetails = $objGroup->getgroupname($device->groupid);
            $device->groupname = isset($groupDetails) ? $groupDetails->groupname : "";
            $message.="<tr><td>$x</td><td>$device->customercompany</td><td>$device->customerno</td><td>$device->vehicleno</td><td>$device->groupname</td><td>$device->unitno</td><td>$lastupdated</td><td>$reason</td><td>$device->relman</td></tr>";
            $x++;
        }
    }
}

if($x == 1)
{
    $message.= "<tr><td colspan=9>No Vehicles Found Inactive</td></tr>";
}

$message.="</table>";
$message.="<br/><br/>";
$message.="Be proactive and take necessary actions ASAP.";
$subject = "Inactive Vehicle List - 24 hours";
echo ($message);

if(sendMail('sanketsheth1@gmail.com', $subject, $message) == false)
{
    // Do nothing
}

if(sendMail('support@elixiatech.com', $subject, $message) == false)
{
    // Do nothing
}

if(sendMail('mihir@elixiatech.com', $subject, $message) == false)
{
    // Do nothing
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

