<?php

set_time_limit(0);
//date_default_timezone_set("Asia/Calcutta");

include_once "../../modules/user/exception_functions.php";
include_once '../reports/reports_fuelefficiency_functions.php';
include_once '../dashboard/exception_class.php';
include_once("../../lib/bo/CustomerManager.php");
include_once '../../lib/comman_function/reports_func.php';

error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');

$days = 30;
$subject = "Exception Alert";
$mail_sending = true;
$sms_sending = true;
$start_date = date('Y-m-d', strtotime("-$days days"));
//$start_date = "2014-10-01";
$end_date = date('Y-m-d');
$e_time = $s_time = date('H:i:s');


$checkpointmanager = new CheckpointManager(null);
$class = new ExceptionManager(null);
$all_exception = $class->get_exception_userwise();

if(!empty($all_exception)){
    foreach($all_exception as $userid=>$data){

        $customerno = $data[0]['customerno'];
        $realname = $data[0]['realname'];
        $encodekey = $data[0]['userkey'];

        $cm = new CustomerManager();
        $timezone = $cm->timezone_name_cron('Asia/Kolkata', $customerno);
        date_default_timezone_set(''.$timezone.'');

        echo "<br/>========================Customerno: $customerno, Realname: $realname, UserId: $userid========================<br/>";
        $sms_data = array();
        $email_data = array();

        foreach($data as $single_data){

            $excep_id = $single_data['exception_id'];
            $email = $single_data['email'];
            $send_email = $single_data['send_email'];
            $phone = $single_data['phone'];
            $send_sms = $single_data['send_sms'];
            $update_flag = $single_data['trip_endtime_flag'];
            $cp_start = $single_data['start_checkpoint_id'];
            $cp_end = $single_data['end_checkpoint_id'];
            $report_type = $single_data['report_type'];
            $condition = $single_data['report_type_condition'];
            $report_type_input = $single_data['report_type_val'];
            $vehicleid = $single_data['vehicleid'];

            $reports = getdailyreport_check_exception($start_date, $end_date, $s_time, $e_time, $cp_start, $cp_end, $vehicleid, $customerno, $report_type);
            if(empty($reports)){continue;}

            $rep = display_units($report_type);
            $s_cpt_name = $checkpointmanager->getchkname_customerid($customerno, $cp_start);
            $e_cpt_name = $checkpointmanager->getchkname_customerid($customerno, $cp_end);

            foreach($reports as $report){

                if($report->enddate==$update_flag){
                    continue;
                }

                if($report_type === 'avg_speed' || $report_type === 'genset_avg'){
                    $daily_data = GetExceptionDailyReport_Data($report->startdate, $report->vehicleid);
                }

                $email_type = '';
                $sms_type = '';
                if($condition=='gt'){
                    if($report_type === 'distance' && $report->distance > $report_type_input){
                        $val = $report->distance;$email_type = $sms_type = array("Distance > ".(int)$report_type_input);
                    }
                    elseif($report_type === 'avg_speed' && $daily_data['avgspeed'] > $report_type_input){
                        $val = $daily_data['avgspeed']; $email_type = $sms_type = array("Average speed > $report_type_input");
                    }
                    elseif($report_type === 'idle_time' && ($report->idletime/60) > $report_type_input){
                        $val = round($report->idletime/60); $email_type = $sms_type = array("Idle time > ".(int)$report_type_input);
                    }
                    elseif($report_type === 'genset_avg' && $daily_data['genset'] > $report_type_input){
                        $val = $daily_data['genset']; $email_type = $sms_type = array("Genset usage > $report_type_input");
                    }
                }
                else{
                    if($report_type === 'distance' && $report->distance <= $report_type_input){
                        $val = $report->distance;$email_type = $sms_type = array('Distance <= '.(int)$report_type_input);
                    }
                    elseif($report_type === 'avg_speed' && $daily_data['avgspeed'] <= $report_type_input){
                        $val = $daily_data['avgspeed']; $email_type = $sms_type = array("Average speed <= $report_type_input");
                    }
                    elseif($report_type === 'idle_time' && ($report->idletime/60) <= $report_type_input){
                        $val = round($report->idletime/60); $email_type = $sms_type = array("Idle time <= ".(int)$report_type_input);
                    }
                    elseif($report_type === 'genset_avg' && $daily_data['genset'] <= $report_type_input){
                        $val = $daily_data['genset']; $email_type = $sms_type = array("Genset usage <= $report_type_input");
                    }
                }

                //update_trip_endtime_flag($excep_id, $report->enddate);
                if($email_type!='' && $send_email==1 && $email!=''){
                    $endtime = date('jS M y, H:i', strtotime($report->enddate));
                    $value = $val.' '.$rep[0];
                    $type = $email_type[0];
                    $email_details = array($report->vehicleno, $s_cpt_name, $e_cpt_name, $endtime, $email_type[0], $value);
                    $email_data[] = $email_details;
                }
                if($sms_type!='' && $send_sms==1 && $phone!=''){
                    $endtime = date('jS M y, H:i', strtotime($report->enddate));
                    $type = $sms_type[0];
                    //$sms_details = "Exception Alert: $report->vehicleno, Start: $s_cpt_name, End: $e_cpt_name, End Time: $endtime; $type: $report->distance {$rep[0]}";
                    $sms_details = "Exception Alert: $report->vehicleno, Start: $s_cpt_name, End: $e_cpt_name; $type: $report->distance {$rep[0]}";
                    echo "<br/>----------".strlen($sms_details).'------<br/>';
                    if($sms_sending){
                        //sendSMS($phone, $sms_details, $customerno, $vehicleid);
                        //sendSMS(9619521206, $sms_details, $customerno, $vehicleid);
                    }
                    $sms_data[] = $sms_details;
                }
            }
        }

        if(!empty($email_data)){
            $email_table = '';
            foreach($email_data as $data){
                $email_table .= '<tr>';
                foreach($data as $single){
                    $email_table .= "<td>$single</td>";

                }
                $email_table .= '</tr>';
            }

            $email_disp = "
<html><body>
Dear $realname,<br><p></p></br>
Please find the auto-generated Exception Report for your vehicles in the past 1 hour.<br/><br/>

<table border=1>
<tr align=center ><td colspan=6><b>Exception Alert</b></td></tr>
<tr><td><b>Vehicle No.</b></td><td><b>Start Checkpoint</b></td><td><b>End Checkpoint</b></td><td><b>Trip End Time</b></td><td><b>Type</b></td><td><b>Value</b></td></tr>
$email_table
</table><br/><br/>
<font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.speed.elixiatech.com/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a></font>
</body></html>";

            echo "<b>Email:</b><br/>".$email_disp.'<br/>';
            if($mail_sending){
                //sendMail($email, $subject, $email_disp);
                //sendMail("sanketsheth1@gmail.com", $subject, $email_disp);
            }
        }
        if(!empty($sms_data)){
            $sms_disp = implode(', <br/>', $sms_data);
            echo "<br/><b>SMS:</b><br/>".$sms_disp.'<br/>';
        }

    }
}
else{
    echo "No Exceptional Data";
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

function sendSMS($phone, $message, $customerno, $vehicleid){

    $cm = new CustomerManager();
    $sms = $cm->pullsmsdetails($customerno);
    $vehiclesms = $cm->pullvehiclesmsmdetails($vehicleid, $customerno);

    if($vehiclesms->smslock == 0 && $sms->smsleft > 0){
        if($sms->smsleft == 20){
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
        //$url = "http://india.msg91.com/sendhttp.php?user=sanketsheth&password=271257&mobiles=".urlencode($phone)."&message=".urlencode($message)."&sender=Elixia&route=4";
        $url = "http://pacems.asia:8080/bulksms/bulksms?username=xzt-elixia&password=elixia&type=0&dlr=1&destination=91".urlencode($phone)."&source=ELIXIA&message=".urlencode($message);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);
        return true;
    }
    else{
        return false;
    }

}

?>
