<?php
include_once("../../session/sessionhelper.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/utilities.php");
include_once("../../constants/constants.php");
require_once '../../lib/bo/ComQueueManager.php';
include_once("../../lib/bo/CustomerManager.php");
include_once("../../lib/bo/VehicleManager.php");
require_once '../../lib/bo/UserManager.php';
include_once("../../lib/model/VOComQueue.php");
include_once '../../lib/bo/GeoCoder.php';

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

function sendSMS($phone, $message, $customerno)
{
    $cm = new CustomerManager();
    $sms = $cm->pullsmsdetails($customerno);
    if($sms->smsleft > 0)
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
        $cm->updatesms($smsleft,$customerno);
//        $url = "http://india.msg91.com/sendhttp.php?user=sanketsheth&password=271257&mobiles=".urlencode($phone)."&message=".urlencode($message)."&sender=Elixia&route=4";
        $url = "http://pacems.asia:8080/bulksms/bulksms?username=xzt-elixia&password=elixia&type=0&dlr=1&destination=91".urlencode($phone)."&source=ELIXIA&message=".urlencode($message);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);
        return true;
    }
    else
    {
        return false;
    }

/*
    $urloutput=file_get_contents($url);
    echo $urloutput; //should print "N,-1,Cart id must be provided"
    return true;
 *
 */
}

function email($type)
{
    switch($type)
        {
            case '1':
                    $email = 'ac_email';
                    return $email;
                    break;
            case '2':
                    $email = 'chk_email';
                    return $email;
                    break;
            case '3':
                    $email = 'mess_email';
                    return $email;
                    break;
            case '4':
                    $email = 'ignition_email';
                    return $email;
                    break;
            case '5':
                    $email = 'speed_email';
                    return $email;
                    break;
            case '6':
                    $email = 'power_email';
                    return $email;
                    break;
            case '7':
                    $email = 'tamper_email';
                    return $email;
                    break;
            case '8':
                    $email = 'temp_email';
                    return $email;
                    break;
        }
}

function sms($type)
{
    switch($type)
        {
            case '1':
                    $sms = 'ac_sms';
                    return $sms;
                    break;
            case '2':
                    $sms = 'chk_sms';
                    return $sms;
                    break;
            case '3':
                    $sms = 'mess_sms';
                    return $sms;
                    break;
            case '4':
                    $sms = 'ignition_sms';
                    return $sms;
                    break;
            case '5':
                    $sms = 'speed_sms';
                    return $sms;
                    break;
            case '6':
                    $sms = 'power_sms';
                    return $sms;
                    break;
            case '7':
                    $sms = 'tamper_sms';
                    return $sms;
                    break;
            case '8':
                    $sms = 'temp_sms';
                    return $sms;
                    break;
        }
}

function subject($type)
{
    switch($type)
        {
            case '1':
                    $subject = 'AC Status';
                    return $subject;
                    break;
            case '2':
                    $subject = 'Checkpoint Status';
                    return $subject;
                    break;
            case '3':
                    $subject = 'Fence Conflict Status';
                    return $subject;
                    break;
            case '4':
                    $subject = 'Ignition Status';
                    return $subject;
                    break;
            case '5':
                    $subject = 'Over Speeding Status';
                    return $subject;
                    break;
            case '6':
                    $subject = 'Power Cut Status';
                    return $subject;
                    break;
            case '7':
                    $subject = 'Tamper Status';
                    return $subject;
                    break;
            case '8':
                    $subject = 'Temperature Status';
                    return $subject;
                    break;
        }
}

function location($lat,$long)
{
    $address = NULL;
    if($lat !='0' && $long!='0')
    {
        if($_SESSION['customerno']==33 ||$_SESSION['customerno']==43)
        {
            $API = "http://www.speed.elixiatech.com/location.php?lat=".$lat."&long=".$long."";
            $location = json_decode(file_get_contents("$API&sensor=false"));
            @$address = "Near ".$location->results[0]->formatted_address;
			if($location->results[0]->formatted_address==""){
					$GeoCoder_Obj = new GeoCoder($_SESSION['customerno']);
					$address=$GeoCoder_Obj->get_location_bylatlong($lat,$long);
			}
        }
        else
        {
            $GeoCoder_Obj = new GeoCoder($_SESSION['customerno']);
            $address=$GeoCoder_Obj->get_location_bylatlong($lat,$long);
        }
    }
    return $address;
}

$cqm = new ComQueueManager();
$queues = $cqm->getcomqueuedata();
if(isset($queues))
{
    foreach($queues as $thisqueue)
    {
        $email = email($thisqueue->type);
        $sms = sms($thisqueue->type);
        $location = location($thisqueue->lat, $thisqueue->long);
        $dates = new Date();
        $hourminutes = $dates->return_hours($thisqueue->timeadded);
        $hms = date("H:i:s");
        $um = new UserManager();
        $users = $um->getusersforcustomerbytype($thisqueue->customerno,$thisqueue->type);
        if(isset($users))
        {
            foreach($users as $thisuser)
            {
            if(strtotime($thisuser->start_alert_time) < strtotime($thisuser->stop_alert_time))
            {
            if(strtotime($thisuser->start_alert_time) <= strtotime($hms) && strtotime($hms) <= strtotime($thisuser->stop_alert_time))
              {
                $groups = $um->get_groups_fromuser($thisuser->customerno, $thisuser->id);
                if(isset($groups))
                {
                    foreach($groups as $group)
                    {
                        $vehiclemanager = new VehicleManager($thisuser->customerno);
                        $vehicles = $vehiclemanager->get_groupedvehicle_for_cron($group->groupid,$thisqueue->vehicleid);
                        if($vehicles == true){
                                                        if(isset($thisuser->$email) && $thisuser->$email == 1)
                                                        {
                                                            if(isset($thisuser->email) && $thisuser->email != "")
                                                            {
                                                                $subject = subject($thisqueue->type);
                                                                $encodekey = sha1($thisuser->userkey);
                                                                $emailmessage = $thisqueue->message.' at '.$hourminutes.".<br/>Location: ".$location.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                                $cvo = new VOComQueue();
                                                                if(sendMail($thisuser->email, $subject, $emailmessage) == true)
                                                                {
                                                                    $cvo->cqid = $thisqueue->cqid;
                                                                    $cvo->customerno = $thisqueue->customerno;
                                                                    $cvo->userid = $thisuser->id;
                                                                    $cvo->type = 0;
                                                                    $cqm->InsertComHistory($cvo);
                                                                $cqm->UpdateComQueue($thisqueue->cqid);
                                                                }
                                                            }
                                                        }
                                                        else if(isset($thisuser->$sms) && $thisuser->$sms == 1)
                                                        {
                                                            if(isset($thisuser->phone) && $thisuser->phone != "")
                                                            {
                                                                $smsmessage = $thisqueue->message.' at '.$hourminutes.". Powered by Elixia Tech.";
                                                                $cvo = new VOComQueue();
                                                               if(sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno) == true)
                                                               {
                                                                    $cvo->cqid = $thisqueue->cqid;
                                                                    $cvo->customerno = $thisqueue->customerno;
                                                                    $cvo->userid = $thisuser->id;
                                                                    $cvo->type = 1;
                                                                   $cqm->InsertComHistory($cvo);
                                                               $cqm->UpdateComQueue($thisqueue->cqid);
                                                               }
                                                           }
                                                        }
                                            }
                    }
                }
              }
            }
            else if(strtotime($thisuser->start_alert_time) > strtotime($thisuser->stop_alert_time)){
              if(strtotime($thisuser->stop_alert_time) <= strtotime($hms) && strtotime($hms) <= strtotime($thisuser->start_alert_time))
              {
                //echo 'Start Time is Greater Than Stop Time<br>not valid'.$thisuser->id.'_'.$thisuser->realname.'_'.$thiscustomerno.'<br><br>';
              }
              else
              {
                $groups = $um->get_groups_fromuser($thisuser->customerno, $thisuser->id);
                if(isset($groups))
                {
                    foreach($groups as $group)
                    {
                        $vehiclemanager = new VehicleManager($thisuser->customerno);
                        $vehicles = $vehiclemanager->get_groupedvehicle_for_cron($group->groupid,$thisqueue->vehicleid);
                        if(isset($vehicles)){
                                                        if(isset($thisuser->$email) && $thisuser->$email == 1)
                                                        {
                                                            if(isset($thisuser->email) && $thisuser->email != "")
                                                            {
                                                                $subject = subject($thisqueue->type);
                                                                $encodekey = sha1($thisuser->userkey);
                                                                $emailmessage = $thisqueue->message.' at '.$hourminutes.".<br/>Location: ".$location.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                                $cvo = new VOComQueue();
                                                                if(sendMail($thisuser->email, $subject, $emailmessage) == true)
                                                                {
                                                                    $cvo->cqid = $thisqueue->cqid;
                                                                    $cvo->customerno = $thisqueue->customerno;
                                                                    $cvo->userid = $thisuser->id;
                                                                    $cvo->type = 0;
                                                                    $cqm->InsertComHistory($cvo);
                                                                $cqm->UpdateComQueue($thisqueue->cqid);
                                                                }
                                                            }
                                                        }
                                                        else if(isset($thisuser->$sms) && $thisuser->$sms == 1)
                                                        {
                                                            if(isset($thisuser->phone) && $thisuser->phone != "")
                                                            {
                                                                $smsmessage = $thisqueue->message.' at '.$hourminutes.". Powered by Elixia Tech.";
                                                                $cvo = new VOComQueue();
                                                               if(sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno) == true)
                                                               {
                                                                    $cvo->cqid = $thisqueue->cqid;
                                                                    $cvo->customerno = $thisqueue->customerno;
                                                                    $cvo->userid = $thisuser->id;
                                                                    $cvo->type = 1;
                                                                   $cqm->InsertComHistory($cvo);
                                                               $cqm->UpdateComQueue($thisqueue->cqid);
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
}
?>
