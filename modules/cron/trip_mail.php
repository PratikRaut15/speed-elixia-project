<?php

set_time_limit(0);
date_default_timezone_set("Asia/Calcutta");

error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');

include_once '../../lib/bo/UserManager.php';
include_once '../vehicle/trip_functions.php';

$subject = 'Trip Details';
$cm = new CheckpointManager(null);
$trip_alerts = $cm->get_trip_alerts_data();


foreach($trip_alerts as $t){

    $location = "../../customer/".$t['customerno']."/reports/chkreport.sqlite";
    $STdate = $t['start_date'].' '.$t['start_time'];
    $datas = Get_ac_dist_Report_trip($location, $STdate, $t['vehicleid'], $t['start_checkpoint_id'], $t['end_checkpoint_id']);

    if($datas['status']){
        $realname = $t['realname'];
        $encodekey = $t['userkey'];
//        $email = $t['email'];
        $email = 'sanketsheth1@gmail.com';
        $email_table = "<tr>";
        $email_table .= "<td>{$t['vehicleno']}</td>";
        $email_table .= "<td>{$t['startCheck']}</td>";
        $email_table .= "<td>{$t['endCheck']}</td>";
        $email_table .= "<td>$STdate</td>";
        $email_table .= "<td>{$datas['data']['end_time']}</td>";
        $email_table .= "<td>{$datas['data']['total_distance']}</td>";
        $email_table .= "<td>{$datas['data']['genset']}</td>";
        $email_table .= "</tr>";

        $email_disp = "
<html><body>
Dear $realname,<br><p></p></br>
Please find the auto-generated Trip Report for your vehicles.<br/><br/>

<table border=1>
<tr align=center ><td colspan=7><b>Trip Details</b></td></tr>
<tr><td><b>Vehicle No.</b></td><td><b>Start Checkpoint</b></td><td><b>End Checkpoint</b></td><td><b>Start Time</b></td><td><b>End Time</b></td><td><b>Distance Travelled[KM]</b></td><td><b>".getcustombyid(1)." Usage[HH:MM]</b></td></tr>
$email_table
</table><br/><br/>
<font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.speed.elixiatech.com/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a></font>
</body></html>";
        echo $email_disp;

        if($email!=''){
            sendMail($email, $subject, $email_disp);
            $cm->update_trip_mail($t['trip_alert_id']);
        }

    }

}


function sendMail( $to, $subject , $content){
    $headers = "From: noreply@elixiatech.com\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    if (!@mail($to, $subject, $content, $headers)) {
        // message sending failed
        return false;
    }
    return true;
}


?>
