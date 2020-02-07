<?php
require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';

$cm = new CustomerManager();
//$customernos = $cm->getcustomernos();
$customernos =array(64);
if(isset($customernos))
{
    foreach($customernos as $thiscustomerno)
    {

        $count = 0;
        $message = "";

         $dm = new VehicleManager($thiscustomerno);
         $min = $dm->getTimezoneDiffInMin($thiscustomerno);
         if(!empty($min)){
         $date = date('Y-m-d H:i:s', time() + $min);
         $date = date('Y-m-d H:i:s', strtotime($date) - 3600);
         }else{
          $date = date('Y-m-d H:i:s', time() - 3600);
         }

        $devices = $dm->getvehiclesforrtd_all_inactive_bycustomer($thiscustomerno);
        $digital = $dm->getGensetName($thiscustomerno);
        $count_active=0;
        $count_notactive=0;        
        if(isset($devices)){
            foreach($devices as $active){
                if($active->lastupdated > $date){
                  $count_active++;
                }
                else
                {
                    $count_notactive++;
                }
            }
        }
        $message.="
            <table border=1 style='text-align:center;'>
            <tr>
            <th>Total Devices Installed</th>
            <th>Active Devices</th>
            <th>Inactive Devices</th>
            </tr>
            <tr>
            <td>".count($devices)."</td>
                <td>".$count_active."</td>
                <td>".$count_notactive."</td>
            </tr>

                    </table><br/>";

            $message.="<br/>";
            $message.="<table border=1 >
                    <tr ><th colspan='6' style='background-color:#ccc;' > Inactice Vehicles</th></tr>
                    <tr><th>Vehicle No.</th><th>Group</th><th>Inactive Since</th><th>Status</th></tr>";
            if(isset($devices))
            {
                foreach ($devices as $device)
                {
                    if($device->lastupdated < $date)
                    {
                        // Reason for Inactiveness
                        $reason="";
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

                        // Last Updated
                        $lastupdated = getduration($device->lastupdated);
                        
                        // Group Name
                        if($device->groupname == "")
                        {
                            $device->groupname = "Ungrouped";
                        }
                        $count++;
                        $message.="<tr><td>$device->vehicleno</td><td>$device->groupname</td><td>$lastupdated</td><td>$reason</td></tr>";
                    }
                }
                $message.="</table><br/>";
            }
        }


        //$message.="</table>";
        $message.="<br/><br/>";

        $message.="Regards,<br/>";
        $message.="Support Team<br/>";
        $message.="Elixia Tech";

      $message.="<br/><br/>";
        if($count > 0)
        {

            $um = new UserManager($thiscustomerno);
            $users = $um->getuseremailsforcustomeradmin($thiscustomerno);
            //print_r($users);echo "<br/>";
            if(isset($users))
            {
                foreach($users as $thisuser)
                {
                    if(isset($thisuser->email) && $thisuser->email != "")
                    {

                        $cqm = new CommunicationQueueManager();
                        $cvo = new VOCommunicationQueue();
                        $cvo->email = $thisuser->email;
                        $cvo->realname = $thisuser->realname;

                        $cvo->subject = "Vehicle Tracking Device Status";
                        $cvo->premessage = "Dear  ". $thisuser->realname.",<br/><br> The following is the status of installed devices in your vehicles. Feel free to mail us at support@elixiatech.com in case of any issues.<br></br/> ";
                        if(sendMail($cvo->email, $cvo->subject, $cvo->premessage, $message) == false)
                        {
 //                           echo $message;echo "<br/>";
                        }
                    }
                }
            }
        }
}

function sendMail( $to, $subject , $premessage, $content)
{
    $subject = $subject;
    $content = $premessage.$content;
    echo $content;

    $headers = "From: noreply@elixiatech.com\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
//    if (!@mail($to, $subject, $content, $headers)) {
        // message sending failed
//        return false;
//    }
    return true;

}

function getduration($StartTime)
{
    $EndTime = date('Y-m-d H:i:s');
//                echo $EndTime.'_'.$StartTime.'<br>';
                $idleduration = strtotime($EndTime) - strtotime($StartTime);
                $years = floor($idleduration / (365 * 60 * 60 * 24));
                $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
                $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
                $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                if($years >= '1' || $months >= '1'){
                    $diff = date('d-m-Y', strtotime($StartTime));
                }
                else if($days>0){
                    $diff = $days.' days '.$hours.' hrs ';
                }
                else if($hours>0){
                    $diff = $hours.' hrs and '.$minutes.' mins ';
                }
                else if($minutes>0){
                    $diff = $minutes.' mins ';
                }
                else{
                    $seconds = strtotime($EndTime) - strtotime($StartTime);
                    $diff = $seconds.' sec';
                }
                return $diff;
}
    ?>
