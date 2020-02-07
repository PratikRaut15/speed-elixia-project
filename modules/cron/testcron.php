<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include_once "../../session/sessionhelper.php";
include_once "../../lib/system/utilities.php";
include_once '../../lib/autoload.php';
include_once "../../constants/constants.php";

function sendMail($to, $subject, $content, $vehicleid) {
    $cm = new CustomerManager();
    $headers = "From: noreply@elixiatech.com\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    if (!@mail($to, $subject, $content, $headers)) {
        // message sending failed
        return false;
    }
    $cm->updateDailyReportEmailCount($vehicleid);
    return true;
}

function sendSMS($phone, $message, $customerno, $vehicleid, $cqid = null, $userid = null) {
    $cm = new CustomerManager();
    $sms = $cm->pullsmsdetails($customerno);
    $smsStatus = new SmsStatus();
    $smsStatus->customerno = $customerno;
    $smsStatus->userid = $userid;
    $smsStatus->vehicleid = $vehicleid;
    $smsStatus->mobileno = $phone;
    $smsStatus->message = $message;
    $smsStatus->cqid = $cqid;
    $smsstat = $cm->getSMSStatus($smsStatus);
    if ($smsstat == 0) {
        if ($sms->smsleft == 20) {
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
        $response = '';
        $isSMSSent = sendSMSUtil($phone, $message, $response);
        if ($isSMSSent == 1) {
            $cm->sentSmsPostProcess($customerno, $phone, $message, $response, $isSMSSent, $userid, $vehicleid, 1, $cqid);
        }
        return true;
    } else {
        return false;
    }
    /*
$urloutput=file_get_contents($url);
echo $urloutput; //should print "N,-1,Cart id must be provided"
return true;
 *
 */
}

function telephonic_Alert($phone, $fileid, $customerno, $vehicleid) {
    $cm = new CustomerManager();
    $sms = $cm->pullsmsdetails($customerno);
    $vehiclesms = $cm->pullvehiclesmsmdetails($vehicleid, $customerno);
    $telephone = $cm->pulltelephonicdetails($customerno);
    $vehicletelephone = $cm->pullvehiclestelephonicdetails($vehicleid, $customerno);
    if ($vehicletelephone->tel_lock == 0 && $telephone->tel_alertleft > 0 && $fileid != '' && $vehiclesms->smslock == 0 && $sms->smsleft > 0) {
        if ($telephone->tel_alertleft == 20) {
            $cqm = new ComQueueManager();
            $cvo = new VOComQueue();
            $cvo->phone = $phone;
            $cvo->message = "Your Telephonic Alert pack is too low. Please contact an Elixir. Powered by Elixia Tech.";
            $cvo->subject = "";
            $cvo->email = "";
            $cvo->type = 2;
            $cvo->customerno = $customerno;
            $cqm->InsertQ($cvo);
        }
        //$url = "http://voice.bulksmsglobal.in/MOBILE_APPS_API/voicebroadcast_api.php?type=broadcast&user=elixiatech&pass=1234567&recorded_file=".urlencode($fileid)."&to_numbers=" . urlencode($phone) . "";
        $url = str_replace("{{FILEID}}", urlencode($fileid), TELEPHONIC_URL);
        $url = str_replace("{{PHONE}}", urlencode($phone), $url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result);
        if ($result->ErrorMsg == 'Success') {
            $tel_alertleft = $telephone->tel_alertleft - 1;
            $cm->updateTelephonicDetails($tel_alertleft, $customerno, $vehicleid);
        }
        return true;
    } else {
        return false;
    }
    /*
$urloutput=file_get_contents($url);
echo $urloutput; //should print "N,-1,Cart id must be provided"
return true;
 *
 */
}

function email($type) {
    switch ($type) {
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
    case '9':
        $email = 'is_chk_email';
        return $email;
        break;
    case '10':
        $email = 'is_trans_email';
        return $email;
        break;
    case '11':
        $email = 'harsh_break_mail';
        return $email;
        break;
    case '12':
        $email = 'high_acce_mail';
        return $email;
        break;
    case '13':
        $email = 'sharp_turn_mail';
        return $email;
        break;
    case '14':
        $email = 'towing_mail';
        return $email;
        break;
    case '15':
        $email = 'panic_email';
        return $email;
        break;
    case '16':
        $email = 'door_email';
        return $email;
        break;
    case '17':
        $email = 'freeze_email';
        return $email;
        break;
    }
}

function sms($type) {
    switch ($type) {
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
    case '9':
        $sms = 'is_chk_sms';
        return $sms;
        break;
    case '10':
        $sms = 'is_trans_sms';
        return $sms;
        break;
    case '11':
        $sms = 'harsh_break_sms';
        return $sms;
        break;
    case '12':
        $sms = 'high_acce_sms';
        return $sms;
        break;
    case '13':
        $sms = 'sharp_turn_sms';
        return $sms;
        break;
    case '14':
        $sms = 'towing_sms';
        return $sms;
        break;
    case '15':
        $sms = 'panic_sms';
        return $sms;
        break;
    case '16':
        $sms = 'door_sms';
        return $sms;
        break;
    case '17':
        $sms = 'freeze_sms';
        return $sms;
        break;
    }
}

function telephone($type) {
    switch ($type) {
    case '1':
        $telephone = 'ac_telephone';
        return $telephone;
        break;
    case '2':
        $telephone = 'chk_telephone';
        return $telephone;
        break;
    case '3':
        $telephone = 'mess_telephone';
        return $telephone;
        break;
    case '4':
        $telephone = 'ignition_telephone';
        return $telephone;
        break;
    case '5':
        $telephone = 'speed_telephone';
        return $telephone;
        break;
    case '6':
        $telephone = 'power_telephone';
        return $telephone;
        break;
    case '7':
        $telephone = 'tamper_telephone';
        return $telephone;
        break;
    case '8':
        $telephone = 'temp_telephone';
        return $telephone;
        break;
    case '9':
        $telephone = 'is_chk_telephone';
        return $telephone;
        break;
    case '10':
        $telephone = 'is_trans_telephone';
        return $telephone;
        break;
    case '11':
        $telephone = 'harsh_break_telephone';
        return $telephone;
        break;
    case '12':
        $telephone = 'high_acce_telephone';
        return $telephone;
        break;
    case '13':
        $telephone = 'sharp_turn_telephone';
        return $telephone;
        break;
    case '14':
        $telephone = 'towing_telephone';
        return $telephone;
        break;
    case '15':
        $telephone = 'panic_telephone';
        return $telephone;
        break;
    case '16':
        $telephone = 'door_telephone';
        return $telephone;
        break;
    case '17':
        $telephone = 'freeze_telephone';
        return $telephone;
        break;
    }
}

function mobile_notification($type) {
    switch ($type) {
    case '1':
        $telephone = 'ac_mobilenotification';
        return $telephone;
        break;
    case '2':
        $telephone = 'chk_mobilenotification';
        return $telephone;
        break;
    case '3':
        $telephone = 'mess_mobilenotification';
        return $telephone;
        break;
    case '4':
        $telephone = 'ignition_mobilenotification';
        return $telephone;
        break;
    case '5':
        $telephone = 'speed_mobilenotification';
        return $telephone;
        break;
    case '6':
        $telephone = 'power_mobilenotification';
        return $telephone;
        break;
    case '7':
        $telephone = 'tamper_mobilenotification';
        return $telephone;
        break;
    case '8':
        $telephone = 'temp_mobilenotification';
        return $telephone;
        break;
    case '9':
        $telephone = 'is_chk_mobilenotification';
        return $telephone;
        break;
    case '10':
        $telephone = 'is_trans_mobilenotification';
        return $telephone;
        break;
    case '11':
        $telephone = 'harsh_break_mobilenotification';
        return $telephone;
        break;
    case '12':
        $telephone = 'high_acce_mobilenotification';
        return $telephone;
        break;
    case '13':
        $telephone = 'sharp_turn_mobilenotification';
        return $telephone;
        break;
    case '14':
        $telephone = 'towing_mobilenotification';
        return $telephone;
        break;
    case '15':
        $telephone = 'panic_mobilenotification';
        return $telephone;
        break;
    case '16':
        $telephone = 'door_mobilenotification';
        return $telephone;
        break;
    case '17':
        $telephone = 'freeze_mobilenotification';
        return $telephone;
        break;
    }
}

function subject($type) {
    switch ($type) {
    case '1':
        $subject = 'Genset / AC Status';
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
    case '9':
        $subject = 'Stoppage Status';
        return $subject;
        break;
    case '10':
        $subject = 'Stoppage Status';
        return $subject;
        break;
    case '11':
        $subject = 'Harsh Break Status';
        return $subject;
        break;
    case '12':
        $subject = 'Sudden Acceleration Status';
        return $subject;
        break;
    case '13':
        $subject = 'Sharp Turn Status';
        return $subject;
        break;
    case '14':
        $subject = 'Towing status';
        return $subject;
        break;
    case '15':
        $subject = 'Panic status';
        return $subject;
        break;
    case '16':
        $subject = 'Door status';
        return $subject;
        break;
    case '17':
        $subject = 'Freeze status';
        return $subject;
        break;
    }
}

function location($lat, $long, $customerno, $usegeolocation) {
    $address = NULL;
    if ($lat != '0' && $long != '0') {
        if ($usegeolocation == 1) {
            $API = "http://www.speed.elixiatech.com/location.php?lat=" . $lat . "&long=" . $long . "";
            $location = json_decode(file_get_contents("$API&sensor=false"));
            @$address = "Near " . $location->results[0]->formatted_address;
            if ($location->results[0]->formatted_address == "") {
                $GeoCoder_Obj = new GeoCoder($customerno);
                $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
            }
        } else {
            $GeoCoder_Obj = new GeoCoder($customerno);
            $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
        }
    }
    return $address;
}

function sendEmailTologfreeze($data, $customerno) {
    $mpath = '';
    if (defined('Mpath')) {
        $mpath = Mpath;
    }
    if (isset($customerno)) {
        $date = new DateTime();
        $timestamp = $date->format('Y-m-d H:i:s');
        $file = $mpath . '../../customer/' . $customerno . '/freezelog/log_freeze_email_' . $date->format('Y-m-d') . '.htm';
        $current = "<br/>#Time: " . $timestamp . "<br/> #Message:" . $data;
        $current .= "<br/>-----------------------------------------------------------------------------------------------------------------<br/>";
        $filename = $mpath . '../../customer/' . $customerno . '/freezelog/';
        if (!file_exists($filename)) {
            mkdir($mpath . "../../customer/" . $customerno . "/freezelog/", 0777);
            if (file_exists($file)) {
                $fh = fopen($file, 'a');
                fwrite($fh, $current . "\r\n");
            } else {
                $fh = fopen($file, 'w');
                fwrite($fh, $current . "\r\n");
            }
        } else {
            if (file_exists($file)) {
                $fh = fopen($file, 'a');
                fwrite($fh, $current . "\r\n");
            } else {
                $fh = fopen($file, 'w');
                fwrite($fh, $current . "\r\n");
            }
        }
        fclose($fh);
        return true;
    } else {
        return false;
    }
}

function getTripdetails($vehicleid, $customerno) {
    $tripmsg = "";
    $vehman = new VehicleManager($customerno);
    $gettripdetails = $vehman->gettripdetails($vehicleid);
    if (!empty($gettripdetails)) {
        $tripmsg = "<table><tr><th>Trip Details</th></tr>"
        . "<tr><td>Trip Log No :</td><td>" . $gettripdetails[0]['triplogno'] . "</td></tr>"
        . "<tr><td>Trip Start Date :</td><td>" . date("Y-m-d H:i:s", strtotime($gettripdetails[0]['startdate'])) . "</td></tr>"
            . "<tr><td>Vehicle No  :</td><td>" . $gettripdetails[0]['vehicleno'] . "</td></tr>"
            . "<tr><td>Trip Status :</td><td>" . $gettripdetails[0]['tripstatus'] . "</td></tr>"
            . "<tr><td>Route Name  :</td><td>" . $gettripdetails[0]['routename'] . "</td></tr>"
            . "<tr><td>budgeted kms :</td><td>" . $gettripdetails[0]['budgetedkms'] . " KM</td></tr>"
            . "<tr><td>budgeted Hrs :</td><td>" . $gettripdetails[0]['budgetedhrs'] . " Hrs</td></tr>"
            . "<tr><td>Driver Name  :</td><td>" . $gettripdetails[0]['drivername'] . "</td></tr>"
            . "<tr><td>Driver Mobile1 :</td><td>" . $gettripdetails[0]['drivermobile1'] . "</td></tr>"
            . "<tr><td>Consignor Name :</td><td>" . $gettripdetails[0]['consignorname'] . "</td></tr>"
            . "<tr><td>Consignee Name :</td><td>" . $gettripdetails[0]['consigneename'] . "</td></tr>"
            . "<tr><td>Min Temprature :</td><td>" . $gettripdetails[0]['mintemp'] . "</td></tr>"
            . "<tr><td>Max Temprature :</td><td>" . $gettripdetails[0]['maxtemp'] . "</td></tr>"
            . "</table>";
    }
    return $tripmsg;
}

$cqm = new ComQueueManager();
$cronm = new CronManager();
$queues = $cqm->getcomqueuedata();

$cron_record_count = count($queues);
if (isset($queues)) {
    $hms = date("H:i:s");

    $firstObj = reset($queues);
    $lastObj = end($queues);
    prettyPrint($firstObj);
    prettyPrint($lastObj);

    if (isset($firstObj) && isset($lastObj)) {
        $cqm->markedQueued($firstObj->cqid, $lastObj->cqid);
        die();
    }

    foreach ($queues as $thisqueue) {
        $fileid = '';
        $tripdetails = '';
        $email = email($thisqueue->type);
        $sms = sms($thisqueue->type);
        $telephone = telephone($thisqueue->type);
        $mobile = mobile_notification($thisqueue->type);
        if ($thisqueue->use_trip == 1) {
            $tripdetails = getTripdetails($thisqueue->vehicleid, $thisqueue->customerno);
        }
        if ($thisqueue->type == 9 || $thisqueue->type == 10) {
            $thisuser = $cronm->get_all_details($thisqueue->userid);
            if ((strtotime($thisuser->start_alert_time) < strtotime($thisuser->stop_alert_time)) && (strtotime($thisuser->start_alert_time) <= strtotime($hms) && strtotime($hms) <= strtotime($thisuser->stop_alert_time))) {
                if (isset($thisuser->$email) && $thisuser->$email == 1) {
                    if (isset($thisuser->email) && $thisuser->email != "") {
                        $dates = new Date();
                        $subject = subject($thisqueue->type);
                        $encodekey = sha1($thisuser->userkey);
                        $location = location($thisqueue->lat, $thisqueue->long, $thisqueue->customerno, $thisqueue->use_geolocation);
                        $hourminutes = $dates->return_hours($thisqueue->timeadded);
                        $email_path = "../emailtemplates/customer/" . $thisqueue->customerno . "/alertMailTemplate.html";
                        if (file_exists($email_path)) {
                            $emailmessage = file_get_contents($email_path);
                            $emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message, $emailmessage);
                            $emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
                            $emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
                            $emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
                        } else {
                            $emailmessage = file_get_contents('../emailtemplates/alertMailTemplate.html');
                            $emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message . $tripdetails, $emailmessage);
                            $emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
                            $emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
                            $emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
                        }
                        $cvo = new VOComQueue();
                        if (sendMail($thisuser->email, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
                            $cvo->cqid = $thisqueue->cqid;
                            $cvo->customerno = $thisqueue->customerno;
                            $cvo->userid = $thisqueue->userid;
                            $cvo->type = 0;
                            $cvo->enh_checkpointid = 0;
                            $cqm->InsertComHistory($cvo);
                            $cqm->UpdateComQueue($thisqueue->cqid);
                        }
                    }
                }
                if (isset($thisuser->$sms) && $thisuser->$sms == 1) {
                    if (isset($thisuser->phone) && $thisuser->phone != "") {
                        $dates = new Date();
                        $hourminutes = $dates->return_hours($thisqueue->timeadded);
                        $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes;
                        $cvo = new VOComQueue();
                        if (sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisqueue->userid) == true) {
                            $cvo->cqid = $thisqueue->cqid;
                            $cvo->customerno = $thisqueue->customerno;
                            $cvo->userid = $thisqueue->userid;
                            $cvo->type = 1;
                            $cvo->enh_checkpointid = 0;
                            $cqm->InsertComHistory($cvo);
                            $cqm->UpdateComQueue($thisqueue->cqid);
                        }
                    }
                }
                if (isset($thisuser->$telephone) && $thisuser->$telephone == 1) {
                    if (isset($thisuser->phone) && $thisuser->phone != "") {
                        $dates = new Date();
                        $hourminutes = $dates->return_hours($thisqueue->timeadded);
                        $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes;
                        $cvo = new VOComQueue();
                        if (telephonic_Alert($thisuser->phone, $fileid, $thisqueue->customerno, $thisqueue->vehicleid) == true) {
                            $cvo->cqid = $thisqueue->cqid;
                            $cvo->customerno = $thisqueue->customerno;
                            $cvo->userid = $thisqueue->userid;
                            $cvo->type = 2;
                            $cvo->enh_checkpointid = 0;
                            $cqm->InsertComHistory($cvo);
                            $cqm->UpdateComQueue($thisqueue->cqid);
                        }
                    }
                }
                if (isset($thisuser->$mobile) && $thisuser->$mobile == 1) {
                    if ($thisuser->notification_status == 1 && $thisuser->gcmid != "") {
                        $dates = new Date();
                        $hourminutes = $dates->return_hours($thisqueue->timeadded);
                        $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes;
                        $vehicleid = $thisqueue->vehicleid;
                        $cqm = new ComQueueManager();
                        $gcmid = array($thisuser->gcmid);
                        $message = array(
                            "message" => $smsmessage,
                            "vehicleid" => $vehicleid,
                            "type" => $thisqueue->type,
                        );
                        $sendnotification = $cqm->send_notification($gcmid, $message, $thisqueue->customerno);
                    }
                }
            } else {
                if ((strtotime($thisuser->start_alert_time) > strtotime($thisuser->stop_alert_time)) && ((strtotime($thisuser->stop_alert_time) > strtotime($hms) && strtotime($hms) < strtotime($thisuser->start_alert_time)) || (strtotime($thisuser->stop_alert_time) < strtotime($hms) && strtotime($hms) > strtotime($thisuser->start_alert_time)))) {
                    if (isset($thisuser->$email) && $thisuser->$email == 1) {
                        if (isset($thisuser->email) && $thisuser->email != "") {
                            $dates = new Date();
                            $subject = subject($thisqueue->type);
                            $encodekey = sha1($thisuser->userkey);
                            $location = location($thisqueue->lat, $thisqueue->long, $thisqueue->customerno, $thisqueue->use_geolocation);
                            $hourminutes = $dates->return_hours($thisqueue->timeadded);
                            $email_path = "../emailtemplates/customer/" . $thisqueue->customerno . "/alertMailTemplate.html";
                            if (file_exists($email_path)) {
                                $emailmessage = file_get_contents($email_path);
                                $emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message, $emailmessage);
                                $emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
                                $emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
                                $emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
                            } else {
                                $emailmessage = file_get_contents('../emailtemplates/alertMailTemplate.html');
                                $emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message . $tripdetails, $emailmessage);
                                $emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
                                $emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
                                $emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
                            }
                            $cvo = new VOComQueue();
                            if (sendMail($thisuser->email, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
                                $cvo->cqid = $thisqueue->cqid;
                                $cvo->customerno = $thisqueue->customerno;
                                $cvo->userid = $thisqueue->userid;
                                $cvo->type = 0;
                                $cvo->enh_checkpointid = 0;
                                $cqm->InsertComHistory($cvo);
                                $cqm->UpdateComQueue($thisqueue->cqid);
                            }
                        }
                    }
                    if (isset($thisuser->$sms) && $thisuser->$sms == 1) {
                        if (isset($thisuser->phone) && $thisuser->phone != "") {
                            $dates = new Date();
                            $hourminutes = $dates->return_hours($thisqueue->timeadded);
                            $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes;
                            $cvo = new VOComQueue();
                            if (sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisqueue->userid) == true) {
                                $cvo->cqid = $thisqueue->cqid;
                                $cvo->customerno = $thisqueue->customerno;
                                $cvo->userid = $thisqueue->userid;
                                $cvo->type = 1;
                                $cvo->enh_checkpointid = 0;
                                $cqm->InsertComHistory($cvo);
                                $cqm->UpdateComQueue($thisqueue->cqid);
                            }
                        }
                    }
                    if (isset($thisuser->$telephone) && $thisuser->$telephone == 1) {
                        if (isset($thisuser->phone) && $thisuser->phone != "") {
                            $dates = new Date();
                            $hourminutes = $dates->return_hours($thisqueue->timeadded);
                            $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes;
                            $fileid = '';
                            $cvo = new VOComQueue();
                            if (telephonic_Alert($thisuser->phone, $fileid, $thisqueue->customerno, $thisqueue->vehicleid) == true) {
                                $cvo->cqid = $thisqueue->cqid;
                                $cvo->customerno = $thisqueue->customerno;
                                $cvo->userid = $thisqueue->userid;
                                $cvo->type = 2;
                                $cvo->enh_checkpointid = 0;
                                $cqm->InsertComHistory($cvo);
                                $cqm->UpdateComQueue($thisqueue->cqid);
                            }
                        }
                    }
                    if (isset($thisuser->$mobile) && $thisuser->$mobile == 1) {
                        if ($thisuser->notification_status == 1 && $thisuser->gcmid != "") {
                            $dates = new Date();
                            $hourminutes = $dates->return_hours($thisqueue->timeadded);
                            $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes;
                            $vehicleid = $thisqueue->vehicleid;
                            $cqm = new ComQueueManager();
                            $gcmid = array($thisuser->gcmid);
                            $message = array(
                                "message" => $smsmessage,
                                "vehicleid" => $vehicleid,
                                "type" => $thisqueue->type,
                            );
                            $sendnotification = $cqm->send_notification($gcmid, $message, $thisqueue->customerno);
                        }
                    }
                }
            }
        } elseif ($thisqueue->type == 2) {
            $chkpoints = $cqm->get_enh_checkpoints($thisqueue->chkid, $thisqueue->vehicleid, $thisqueue->customerno);
            $dates = new Date();
            if (isset($chkpoints)) {
                foreach ($chkpoints as $chkpoint) {
                    if ($chkpoint->com_type == 0) {
                        $dates = new Date();
                        $subject = subject($thisqueue->type);
                        $encodekey = sha1($thisuser->userkey);
                        $location = location($thisqueue->lat, $thisqueue->long, $thisqueue->customerno, $thisqueue->use_geolocation);
                        $hourminutes = $dates->return_hours($thisqueue->timeadded);
                        $email_path = "../emailtemplates/customer/" . $thisqueue->customerno . "/alertMailTemplate.html";
                        if (file_exists($email_path)) {
                            $emailmessage = file_get_contents($email_path);
                            $emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message, $emailmessage);
                            $emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
                            $emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
                            $emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
                        } else {
                            $emailmessage = file_get_contents('../emailtemplates/alertMailTemplate.html');
                            $emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message . $tripdetails, $emailmessage);
                            $emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
                            $emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
                            $emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
                        }
                        $cvo = new VOComQueue();
                        if (sendMail($chkpoint->com_det, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
                            $cvo->cqid = $thisqueue->cqid;
                            $cvo->customerno = $thisqueue->customerno;
                            $cvo->userid = 0;
                            $cvo->type = 0;
                            $cvo->enh_checkpointid = $chkpoint->enh_checkpointid;
                            $cqm->InsertComHistory($cvo);
                            $cqm->UpdateComQueue($thisqueue->cqid);
                        }
                    } elseif ($chkpoint->com_type == 1) {
                        $dates = new Date();
                        $hourminutes = $dates->return_hours($thisqueue->timeadded);
                        $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes;
                        $cvo = new VOComQueue();
                        if (sendSMS($chkpoint->com_det, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisqueue->userid) == true) {
                            $cvo->cqid = $thisqueue->cqid;
                            $cvo->customerno = $thisqueue->customerno;
                            $cvo->userid = 0;
                            $cvo->type = 1;
                            $cvo->enh_checkpointid = $chkpoint->enh_checkpointid;
                            $cqm->InsertComHistory($cvo);
                            $cqm->UpdateComQueue($thisqueue->cqid);
                        }
                    } elseif ($chkpoint->com_type == 2) {
                        $dates = new Date();
                        $hourminutes = $dates->return_hours($thisqueue->timeadded);
                        $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes;
                        $cvo = new VOComQueue();
                        if (telephonic_Alert($chkpoint->com_det, $fileid, $thisqueue->customerno, $thisqueue->vehicleid) == true) {
                            $cvo->cqid = $thisqueue->cqid;
                            $cvo->customerno = $thisqueue->customerno;
                            $cvo->userid = 0;
                            $cvo->type = 2;
                            $cvo->enh_checkpointid = $chkpoint->enh_checkpointid;
                            $cqm->InsertComHistory($cvo);
                            $cqm->UpdateComQueue($thisqueue->cqid);
                        }
                    }
                }
            } else {
                $um = new UserManager();
                $users = $um->getusersforcustomerbytype($thisqueue->customerno, $thisqueue->type);
                if (isset($users)) {
                    foreach ($users as $thisuser) {
                        $groups = $um->get_groups_fromuser($thisuser->customerno, $thisuser->id);
                        if (isset($groups)) {
                            foreach ($groups as $group) {
                                $vehiclemanager = new VehicleManager($thisuser->customerno);
                                $vehicles = $vehiclemanager->get_groupedvehicle_for_cron($group->groupid, $thisqueue->vehicleid);
                                if ($vehicles == true) {
                                    if ((strtotime($thisuser->start_alert_time) < strtotime($thisuser->stop_alert_time)) && (strtotime($thisuser->start_alert_time) <= strtotime($hms) && strtotime($hms) <= strtotime($thisuser->stop_alert_time))) {
                                        if (isset($thisuser->$email) && $thisuser->$email == 1) {
                                            if (isset($thisuser->email) && $thisuser->email != "") {
                                                $dates = new Date();
                                                $subject = subject($thisqueue->type);
                                                $encodekey = sha1($thisuser->userkey);
                                                $location = location($thisqueue->lat, $thisqueue->long, $thisqueue->customerno, $thisqueue->use_geolocation);
                                                $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                                $email_path = "../emailtemplates/customer/" . $thisqueue->customerno . "/alertMailTemplate.html";
                                                if (file_exists($email_path)) {
                                                    $emailmessage = file_get_contents($email_path);
                                                    $emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message, $emailmessage);
                                                    $emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
                                                    $emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
                                                    $emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
                                                } else {
                                                    $emailmessage = file_get_contents('../emailtemplates/alertMailTemplate.html');
                                                    $emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message . $tripdetails, $emailmessage);
                                                    $emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
                                                    $emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
                                                    $emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
                                                }
                                                $cvo = new VOComQueue();
                                                if (sendMail($thisuser->email, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
                                                    $cvo->cqid = $thisqueue->cqid;
                                                    $cvo->customerno = $thisqueue->customerno;
                                                    $cvo->userid = $thisuser->id;
                                                    $cvo->type = 0;
                                                    $cvo->enh_checkpointid = 0;
                                                    $cqm->InsertComHistory($cvo);
                                                    $cqm->UpdateComQueue($thisqueue->cqid);
                                                }
                                            }
                                        }
                                        if (isset($thisuser->$sms) && $thisuser->$sms == 1) {
                                            if (isset($thisuser->phone) && $thisuser->phone != "") {
                                                $dates = new Date();
                                                $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                                $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes;
                                                $cvo = new VOComQueue();
                                                if (sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisuser->id) == true) {
                                                    $cvo->cqid = $thisqueue->cqid;
                                                    $cvo->customerno = $thisqueue->customerno;
                                                    $cvo->userid = $thisuser->id;
                                                    $cvo->type = 1;
                                                    $cvo->enh_checkpointid = 0;
                                                    $cqm->InsertComHistory($cvo);
                                                    $cqm->UpdateComQueue($thisqueue->cqid);
                                                }
                                            }
                                        }
                                        if (isset($thisuser->$telephone) && $thisuser->$telephone == 1) {
                                            if (isset($thisuser->phone) && $thisuser->phone != "") {
                                                $dates = new Date();
                                                $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                                $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes;
                                                $cvo = new VOComQueue();
                                                if (telephonic_Alert($thisuser->phone, $fileid, $thisqueue->customerno, $thisqueue->vehicleid) == true) {
                                                    $cvo->cqid = $thisqueue->cqid;
                                                    $cvo->customerno = $thisqueue->customerno;
                                                    $cvo->userid = $thisuser->id;
                                                    $cvo->type = 2;
                                                    $cvo->enh_checkpointid = 0;
                                                    $cqm->InsertComHistory($cvo);
                                                    $cqm->UpdateComQueue($thisqueue->cqid);
                                                }
                                            }
                                        }
                                        if (isset($thisuser->$mobile) && $thisuser->$mobile == 1) {
                                            if ($thisuser->notification_status == 1 && $thisuser->gcmid != "") {
                                                $dates = new Date();
                                                $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                                $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes;
                                                $vehicleid = $thisqueue->vehicleid;
                                                $cqm = new ComQueueManager();
                                                $gcmid = array($thisuser->gcmid);
                                                $message = array(
                                                    "message" => $smsmessage,
                                                    "vehicleid" => $vehicleid,
                                                    "type" => $thisqueue->type,
                                                );
                                                $sendnotification = $cqm->send_notification($gcmid, $message, $thisqueue->customerno);
                                            }
                                        }
                                    } else {
                                        if ((strtotime($thisuser->start_alert_time) > strtotime($thisuser->stop_alert_time)) && ((strtotime($thisuser->stop_alert_time) > strtotime($hms) && strtotime($hms) < strtotime($thisuser->start_alert_time)) || (strtotime($thisuser->stop_alert_time) < strtotime($hms) && strtotime($hms) > strtotime($thisuser->start_alert_time)))) {
                                            if (isset($thisuser->$email) && $thisuser->$email == 1) {
                                                if (isset($thisuser->email) && $thisuser->email != "") {
                                                    $dates = new Date();
                                                    $subject = subject($thisqueue->type);
                                                    $encodekey = sha1($thisuser->userkey);
                                                    $location = location($thisqueue->lat, $thisqueue->long, $thisqueue->customerno, $thisqueue->use_geolocation);
                                                    $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                                    $email_path = "../emailtemplates/customer/" . $thisqueue->customerno . "/alertMailTemplate.html";
                                                    if (file_exists($email_path)) {
                                                        $emailmessage = file_get_contents($email_path);
                                                        $emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message, $emailmessage);
                                                        $emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
                                                        $emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
                                                        $emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
                                                    } else {
                                                        $emailmessage = file_get_contents('../emailtemplates/alertMailTemplate.html');
                                                        $emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message . $tripdetails, $emailmessage);
                                                        $emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
                                                        $emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
                                                        $emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
                                                    }
                                                    $cvo = new VOComQueue();
                                                    if (sendMail($thisuser->email, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
                                                        $cvo->cqid = $thisqueue->cqid;
                                                        $cvo->customerno = $thisqueue->customerno;
                                                        $cvo->userid = $thisuser->id;
                                                        $cvo->type = 0;
                                                        $cvo->enh_checkpointid = 0;
                                                        $cqm->InsertComHistory($cvo);
                                                        $cqm->UpdateComQueue($thisqueue->cqid);
                                                    }
                                                }
                                            }
                                            if (isset($thisuser->$sms) && $thisuser->$sms == 1) {
                                                if (isset($thisuser->phone) && $thisuser->phone != "") {
                                                    $dates = new Date();
                                                    $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                                    $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes;
                                                    $cvo = new VOComQueue();
                                                    if (sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisuser->id) == true) {
                                                        $cvo->cqid = $thisqueue->cqid;
                                                        $cvo->customerno = $thisqueue->customerno;
                                                        $cvo->userid = $thisuser->id;
                                                        $cvo->type = 1;
                                                        $cvo->enh_checkpointid = 0;
                                                        $cqm->InsertComHistory($cvo);
                                                        $cqm->UpdateComQueue($thisqueue->cqid);
                                                    }
                                                }
                                            }
                                            if (isset($thisuser->$telephone) && $thisuser->$telephone == 1) {
                                                if (isset($thisuser->phone) && $thisuser->phone != "") {
                                                    $dates = new Date();
                                                    $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                                    $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes;
                                                    $cvo = new VOComQueue();
                                                    if (telephonic_Alert($thisuser->phone, $fileid, $thisqueue->customerno, $thisqueue->vehicleid) == true) {
                                                        $cvo->cqid = $thisqueue->cqid;
                                                        $cvo->customerno = $thisqueue->customerno;
                                                        $cvo->userid = $thisuser->id;
                                                        $cvo->type = 2;
                                                        $cvo->enh_checkpointid = 0;
                                                        $cqm->InsertComHistory($cvo);
                                                        $cqm->UpdateComQueue($thisqueue->cqid);
                                                    }
                                                }
                                            }
                                            if (isset($thisuser->$mobile) && $thisuser->$mobile == 1) {
                                                if ($thisuser->notification_status == 1 && $thisuser->gcmid != "") {
                                                    $dates = new Date();
                                                    $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                                    $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes;
                                                    $vehicleid = $thisqueue->vehicleid;
                                                    $cqm = new ComQueueManager();
                                                    $gcmid = array($thisuser->gcmid);
                                                    $message = array(
                                                        "message" => $smsmessage,
                                                        "vehicleid" => $vehicleid,
                                                        "type" => $thisqueue->type,
                                                    );
                                                    $sendnotification = $cqm->send_notification($gcmid, $message, $thisqueue->customerno);
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
        } elseif ($thisqueue->type == 17) {
            //freeze send email or sms
            $um = new UserManager();
            $users = $um->getusersforcustomerbytype($thisqueue->customerno, $thisqueue->type, $thisqueue->vehicleid);
            if (!empty($users)) {
                $useradmin = $um->getadministrator($thisqueue->customerno);
                $users1 = array_merge($users, $useradmin);
                $arraylist = json_decode(json_encode($users1), True);
                $users2 = array_reduce($arraylist, function ($result, $currentItem) {
                    if (isset($result[$currentItem['id']])) {
                        $result[$currentItem['id']] = $currentItem;
                    } else {
                        $result[$currentItem['id']] = $currentItem;
                    }
                    return $result;
                });
                $userobject = json_decode(json_encode($users2), FALSE);
                if (isset($userobject)) {
                    foreach ($userobject as $thisuser) {
                        if (isset($thisuser->email) && $thisuser->email != "") {
                            $subject = subject($thisqueue->type);
                            $encodekey = sha1($thisuser->userkey);
                            $location = location($thisqueue->lat, $thisqueue->long, $thisqueue->customerno, $thisqueue->use_geolocation);
                            $dates = new Date();
                            $hourminutes = $dates->return_hours($thisqueue->timeadded);
                            $email_path = "../emailtemplates/customer/" . $thisqueue->customerno . "/alertMailTemplate.html";
                            if (file_exists($email_path)) {
                                $emailmessage = file_get_contents($email_path);
                                $emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message, $emailmessage);
                                $emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
                                $emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
                                $emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
                            } else {
                                $emailmessage = file_get_contents('../emailtemplates/alertMailTemplate.html');
                                $emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message . $tripdetails, $emailmessage);
                                $emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
                                $emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
                                $emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
                            }
                            $cvo = new VOComQueue();
                            if (sendMail($thisuser->email, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
                                $cvo->cqid = $thisqueue->cqid;
                                $cvo->customerno = $thisqueue->customerno;
                                $cvo->userid = $thisuser->id;
                                $cvo->type = 0;
                                $cvo->enh_checkpointid = 0;
                                $cqm->InsertComHistory($cvo);
                                $cqm->UpdateComQueue($thisqueue->cqid);
                            }
                            if (doLogging == true) {
                                sendEmailTologfreeze($emailmessage, $thisqueue->customerno);
                            }
                        }
                        if (isset($thisuser->phone) && $thisuser->phone != "") {
                            $dates = new Date();
                            $hourminutes = $dates->return_hours($thisqueue->timeadded);
                            $location = location($thisqueue->lat, $thisqueue->long, $thisqueue->customerno, $thisqueue->use_geolocation);
                            $smsmessage = $thisqueue->message . ' at ' . $hourminutes . ". Location: " . $location;
                            $cvo = new VOComQueue();
                            if ($thisqueue->type == '5' && $thisqueue->status == 1) {
                                //no sms
                            } else {
                                if (sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisuser->id)) {
                                    $cvo->cqid = $thisqueue->cqid;
                                    $cvo->customerno = $thisqueue->customerno;
                                    $cvo->userid = $thisuser->id;
                                    $cvo->type = 1;
                                    $cvo->enh_checkpointid = 0;
                                    $cqm->InsertComHistory($cvo);
                                    $cqm->UpdateComQueue($thisqueue->cqid);
                                }
                            }
                        }
                        if (isset($thisuser->$telephone) && $thisuser->$telephone == 1 && isset($thisuser->phone) && $thisuser->phone != "") {
                            $dates = new Date();
                            $hourminutes = $dates->return_hours($thisqueue->timeadded);
                            $location = location($thisqueue->lat, $thisqueue->long, $thisqueue->customerno, $thisqueue->use_geolocation);
                            $smsmessage = $thisqueue->message . ' at ' . $hourminutes . ". Location: " . $location;
                            $cvo = new VOComQueue();
                            if ($thisqueue->type == '5' && $thisqueue->status == 1) {
                                //no sms
                            } else {
                                $sendsms = telephonic_Alert($thisuser->phone, $fileid, $thisqueue->customerno, $thisqueue->vehicleid);
                                $cvo->cqid = $thisqueue->cqid;
                                $cvo->customerno = $thisqueue->customerno;
                                $cvo->userid = $thisuser->id;
                                $cvo->type = 2;
                                $cvo->enh_checkpointid = 0;
                                $cqm->InsertComHistory($cvo);
                                $cqm->UpdateComQueue($thisqueue->cqid);
                            }
                        }
                        if (isset($thisuser->$mobile) && $thisuser->$mobile == 1) {
                            if ($thisuser->notification_status == 1 && $thisuser->gcmid != "") {
                                $dates = new Date();
                                $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes;
                                $vehicleid = $thisqueue->vehicleid;
                                $cqm = new ComQueueManager();
                                $gcmid = array($thisuser->gcmid);
                                $message = array(
                                    "message" => $smsmessage,
                                    "vehicleid" => $vehicleid,
                                    "type" => $thisqueue->type,
                                );
                                $sendnotification = $cqm->send_notification($gcmid, $message, $thisqueue->customerno);
                            }
                        }
                    }
                }
            }
        } elseif ($thisqueue->type == 8) {
            //echo $thisqueue->customerno."--Start -- ".date("H:i:s")."<br/>";
            $um = new UserManager();
            $users = $um->getusersforcustomerbytype($thisqueue->customerno, $thisqueue->type, $thisqueue->vehicleid);
            if (isset($users)) {
                $arrUnitIdWithBuzzCmdSet = array();
                foreach ($users as $thisuser) {
                    $checkTempSensor = 0;
                    switch ($thisqueue->tempsensor) {
                    case 4:
                        if ($thisuser->tempSensor4 == 1) {
                            $checkTempSensor = 1;
                        }
                        break;
                    case 3:
                        if ($thisuser->tempSensor3 == 1) {
                            $checkTempSensor = 1;
                        }
                        break;
                    case 2:
                        if ($thisuser->tempSensor2 == 1) {
                            $checkTempSensor = 1;
                        }
                        break;
                    case 1:
                        if ($thisuser->tempSensor1 == 1) {
                            $checkTempSensor = 1;
                        }
                        break;
                    }
                    if ($checkTempSensor == 1) {
                        $isNomens = 0;
                        if ($thisqueue->customerno == speedConstants::CUSTNO_PERKINELMER) {
                            if ($thisqueue->n1 > 0) {
                                $isNomens = 1;
                            } elseif ($thisqueue->n2 > 0) {
                                $isNomens = 1;
                            } elseif ($thisqueue->n3 > 0) {
                                $isNomens = 1;
                            } elseif ($thisqueue->n4 > 0) {
                                $isNomens = 1;
                            }
                            //echo $isNomens;
                            $unitNomens = array(
                                $thisqueue->n1,
                                $thisqueue->n2,
                                $thisqueue->n3,
                                $thisqueue->n4,
                            );

                            if ($isNomens) {
                                $userNomens = $um->getUserNomenclatureMapping($thisuser->id, $thisqueue->customerno);
                                if (isset($userNomens) && !empty($userNomens)) {
                                    $data = array_map(function ($element) {
                                        return $element['nomenclatureid'];
                                    }, $userNomens);
                                    $arrIntersect = array_intersect($unitNomens, $data);
                                }
                            }
                        }

                        if (isset($thisuser->new_active_status)) {
                            if ($thisuser->new_active_status != 1) {
                                continue;
                            }
                            $start_alert_time = $thisuser->alert_start_time;
                            $stop_alert_time = $thisuser->alert_end_time;
                        } else {
                            $start_alert_time = $thisuser->start_alert_time;
                            $stop_alert_time = $thisuser->stop_alert_time;
                        }
                        $groups = $um->get_groups_fromuser($thisuser->customerno, $thisuser->id);
                        //echo $thisuser->id."--Groups -- ".date("H:i:s")."<br/>";
                        if (isset($groups)) {
                            foreach ($groups as $group) {
                                $vehiclemanager = new VehicleManager($thisuser->customerno);
                                $vehicles = $vehiclemanager->get_groupedvehicle_for_cron($group->groupid, $thisqueue->vehicleid);
                                if ($vehicles == true &&
                                    (
                                        ($isNomens && isset($arrIntersect) && !empty($arrIntersect))
                                        || (!$isNomens)
                                    )
                                ) {
                                    //echo "Vehicle -- ".date("H:i:s")."<br/>";die();

                                    if ((strtotime($start_alert_time) < strtotime($stop_alert_time)) && (strtotime($start_alert_time) <= strtotime($hms) && strtotime($hms) <= strtotime($stop_alert_time))) {
                                        if (isset($thisuser->$email) && $thisuser->$email == 1) {
                                            if (isset($thisuser->email) && $thisuser->email != "") {
                                                $subject = subject($thisqueue->type);
                                                $subject = $subject . " - " . $thisqueue->vehicleno;
                                                $encodekey = sha1($thisuser->userkey);
                                                $location = location($thisqueue->lat, $thisqueue->long, $thisqueue->customerno, $thisqueue->use_geolocation);
                                                $dates = new Date();
                                                $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                                $email_path = "../emailtemplates/customer/" . $thisqueue->customerno . "/alertMailTemplate.html";
                                                if (file_exists($email_path)) {
                                                    $emailmessage = file_get_contents($email_path);
                                                    $emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message, $emailmessage);
                                                    $emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
                                                    $emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
                                                    $emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
                                                } else {
                                                    $emailmessage = file_get_contents('../emailtemplates/alertMailTemplate.html');
                                                    $emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message . $tripdetails, $emailmessage);
                                                    $emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
                                                    $emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
                                                    $emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
                                                }
                                                $cvo = new VOComQueue();

                                                if (sendMail($thisuser->email, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
                                                    $cvo->cqid = $thisqueue->cqid;
                                                    $cvo->customerno = $thisqueue->customerno;
                                                    $cvo->userid = $thisuser->id;
                                                    $cvo->type = 0;
                                                    $cvo->enh_checkpointid = 0;
                                                    $cqm->InsertComHistory($cvo);
                                                    $cqm->UpdateComQueue($thisqueue->cqid);
                                                }
                                            }
                                        }
                                        if (isset($thisuser->$sms) && $thisuser->$sms == 1) {
                                            if (isset($thisuser->phone) && $thisuser->phone != "") {
                                                $dates = new Date();
                                                $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                                $location = location($thisqueue->lat, $thisqueue->long, $thisqueue->customerno, $thisqueue->use_geolocation);
                                                $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes . ". Location: " . $location;
                                                $cvo = new VOComQueue();
                                                if (sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisuser->id)) {
                                                    $cvo->cqid = $thisqueue->cqid;
                                                    $cvo->customerno = $thisqueue->customerno;
                                                    $cvo->userid = $thisuser->id;
                                                    $cvo->type = 1;
                                                    $cvo->enh_checkpointid = 0;
                                                    $cqm->InsertComHistory($cvo);
                                                    $cqm->UpdateComQueue($thisqueue->cqid);
                                                }
                                            }
                                        }
                                        if (isset($thisuser->$telephone) && $thisuser->$telephone == 1) {
                                            if (isset($thisuser->phone) && $thisuser->phone != "") {
                                                $dates = new Date();
                                                if ($thisqueue->type == '8') {
                                                    $fileid = '2807_4_20160422164452';
                                                }
                                                $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                                $location = location($thisqueue->lat, $thisqueue->long, $thisqueue->customerno, $thisqueue->use_geolocation);
                                                $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes . ". Location: " . $location;
                                                $cvo = new VOComQueue();
                                                $sendsms = telephonic_Alert($thisuser->phone, $fileid, $thisqueue->customerno, $thisqueue->vehicleid);
                                                $cvo->cqid = $thisqueue->cqid;
                                                $cvo->customerno = $thisqueue->customerno;
                                                $cvo->userid = $thisuser->id;
                                                $cvo->type = 2;
                                                $cvo->enh_checkpointid = 0;
                                                $cqm->InsertComHistory($cvo);
                                                $cqm->UpdateComQueue($thisqueue->cqid);
                                            }
                                        }
                                        if (isset($thisuser->$mobile) && $thisuser->$mobile == 1) {
                                            if ($thisuser->notification_status == 1 && $thisuser->gcmid != "") {
                                                $dates = new Date();
                                                $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                                $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes;
                                                $vehicleid = $thisqueue->vehicleid;
                                                $cqm = new ComQueueManager();
                                                $gcmid = array($thisuser->gcmid);
                                                $message = array(
                                                    "message" => $smsmessage,
                                                    "vehicleid" => $vehicleid,
                                                    "type" => $thisqueue->type,
                                                );
                                                $sendnotification = $cqm->send_notification($gcmid, $message, $thisqueue->customerno);
                                            }
                                        }
                                        //BUZZER ALERTS FOR Temperature Conflict
                                        /*
                                        Need to ring buzzer for the unique unit ids.
                                        Different users might be mapped to same units and buzzer should not ring multiple times.
                                         */
                                        if ((!in_array($thisqueue->uid, $arrUnitIdWithBuzzCmdSet)) && ($thisqueue->customerno == speedConstants::CUSTNO_PERKINELMER || $thisqueue->customerno == speedConstants::CUSTNO_NESTLE)) {
                                            $arrUnitIdWithBuzzCmdSet[] = $thisqueue->uid;
                                            $objUnitManager = new UnitManager($thisuser->customerno);
                                            $objCommandDetails = new stdClass();
                                            $objCommandDetails->uid = $thisqueue->uid;
                                            $objCommandDetails->command = "BUZZ";
                                            //Chaukas Device would have unitno with 7 digits or more
                                            if (isset($thisqueue->unitno) && strlen($thisqueue->unitno) >= 7) {
                                                $objCommandDetails->command .= "=30";
                                            }
                                            $objUnitManager->setCommand($objCommandDetails);
                                        }
                                    } else {
                                        if ((strtotime($start_alert_time) > strtotime($stop_alert_time)) && ((strtotime($stop_alert_time) > strtotime($hms) && strtotime($hms) < strtotime($start_alert_time)) || (strtotime($stop_alert_time) < strtotime($hms) && strtotime($hms) > strtotime($start_alert_time)))) {
                                            if (isset($thisuser->$email) && $thisuser->$email == 1) {
                                                if (isset($thisuser->email) && $thisuser->email != "") {
                                                    $subject = subject($thisqueue->type);
                                                    $subject = $subject . " - " . $thisqueue->vehicleno;
                                                    $encodekey = sha1($thisuser->userkey);
                                                    $location = location($thisqueue->lat, $thisqueue->long, $thisqueue->customerno, $thisqueue->use_geolocation);
                                                    $dates = new Date();
                                                    $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                                    $email_path = "../emailtemplates/customer/" . $thisqueue->customerno . "/alertMailTemplate.html";
                                                    if (file_exists($email_path)) {
                                                        $emailmessage = file_get_contents($email_path);
                                                        $emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message, $emailmessage);
                                                        $emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
                                                        $emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
                                                        $emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
                                                    } else {
                                                        $emailmessage = file_get_contents('../emailtemplates/alertMailTemplate.html');
                                                        $emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message . $tripdetails, $emailmessage);
                                                        $emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
                                                        $emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
                                                        $emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
                                                    }
                                                    $cvo = new VOComQueue();
                                                    if (sendMail($thisuser->email, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
                                                        $cvo->cqid = $thisqueue->cqid;
                                                        $cvo->customerno = $thisqueue->customerno;
                                                        $cvo->userid = $thisuser->id;
                                                        $cvo->type = 0;
                                                        $cvo->enh_checkpointid = 0;
                                                        $cqm->InsertComHistory($cvo);
                                                        $cqm->UpdateComQueue($thisqueue->cqid);
                                                    }
                                                }
                                            }
                                            if (isset($thisuser->$sms) && $thisuser->$sms == 1) {
                                                if (isset($thisuser->phone) && $thisuser->phone != "") {
                                                    $dates = new Date();
                                                    $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                                    $location = location($thisqueue->lat, $thisqueue->long, $thisqueue->customerno, $thisqueue->use_geolocation);
                                                    $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes . ". Location: " . $location;
                                                    $cvo = new VOComQueue();
                                                    if (sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisuser->id)) {
                                                        $cvo->cqid = $thisqueue->cqid;
                                                        $cvo->customerno = $thisqueue->customerno;
                                                        $cvo->userid = $thisuser->id;
                                                        $cvo->type = 1;
                                                        $cvo->enh_checkpointid = 0;
                                                        $cqm->InsertComHistory($cvo);
                                                        $cqm->UpdateComQueue($thisqueue->cqid);
                                                    }
                                                }
                                            }
                                            if (isset($thisuser->$telephone) && $thisuser->$telephone == 1) {
                                                if (isset($thisuser->phone) && $thisuser->phone != "") {
                                                    $dates = new Date();
                                                    if ($thisqueue->type == '8') {
                                                        $fileid = '2807_4_20160422164452';
                                                    }
                                                    $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                                    $location = location($thisqueue->lat, $thisqueue->long, $thisqueue->customerno, $thisqueue->use_geolocation);
                                                    $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes . ". Location: " . $location;
                                                    $cvo = new VOComQueue();
                                                    telephonic_Alert($thisuser->phone, $fileid, $thisqueue->customerno, $thisqueue->vehicleid);
                                                    $cvo->cqid = $thisqueue->cqid;
                                                    $cvo->customerno = $thisqueue->customerno;
                                                    $cvo->userid = $thisuser->id;
                                                    $cvo->type = 2;
                                                    $cvo->enh_checkpointid = 0;
                                                    $cqm->InsertComHistory($cvo);
                                                    $cqm->UpdateComQueue($thisqueue->cqid);
                                                }
                                            }
                                            if (isset($thisuser->$mobile) && $thisuser->$mobile == 1) {
                                                if ($thisuser->notification_status == 1 && $thisuser->gcmid != "") {
                                                    $dates = new Date();
                                                    $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                                    $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes;
                                                    $vehicleid = $thisqueue->vehicleid;
                                                    $cqm = new ComQueueManager();
                                                    $gcmid = array($thisuser->gcmid);
                                                    $message = array(
                                                        "message" => $smsmessage,
                                                        "vehicleid" => $vehicleid,
                                                        "type" => $thisqueue->type,
                                                    );
                                                    $sendnotification = $cqm->send_notification($gcmid, $message, $thisqueue->customerno);
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
            //echo "End  --".date("H:i:s");
        } else {
            $um = new UserManager();
            $users = $um->getusersforcustomerbytype($thisqueue->customerno, $thisqueue->type, $thisqueue->vehicleid);
            if (isset($users)) {
                foreach ($users as $thisuser) {
                    if (isset($thisuser->new_active_status)) {
                        if ($thisuser->new_active_status != 1) {
                            continue;
                        }
                        $start_alert_time = $thisuser->alert_start_time;
                        $stop_alert_time = $thisuser->alert_end_time;
                        //echo $thisuser->new_active_status."======$email---------$start_alert_time-$stop_alert_time<br/>";
                    } else {
                        $start_alert_time = $thisuser->start_alert_time;
                        $stop_alert_time = $thisuser->stop_alert_time;
                    }
                    $groups = $um->get_groups_fromuser($thisuser->customerno, $thisuser->id);
                    if (isset($groups)) {
                        foreach ($groups as $group) {
                            $vehiclemanager = new VehicleManager($thisuser->customerno);
                            $vehicles = $vehiclemanager->get_groupedvehicle_for_cron($group->groupid, $thisqueue->vehicleid);
                            if ($vehicles == true) {
                                if ((strtotime($start_alert_time) < strtotime($stop_alert_time)) && (strtotime($start_alert_time) <= strtotime($hms) && strtotime($hms) <= strtotime($stop_alert_time))) {
                                    if (isset($thisuser->$email) && $thisuser->$email == 1) {
                                        if (isset($thisuser->email) && $thisuser->email != "") {
                                            $subject = subject($thisqueue->type);
                                            $encodekey = sha1($thisuser->userkey);
                                            $location = location($thisqueue->lat, $thisqueue->long, $thisqueue->customerno, $thisqueue->use_geolocation);
                                            $dates = new Date();
                                            $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                            $email_path = "../emailtemplates/customer/" . $thisqueue->customerno . "/alertMailTemplate.html";
                                            if (file_exists($email_path)) {
                                                $emailmessage = file_get_contents($email_path);
                                                $emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message, $emailmessage);
                                                $emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
                                                $emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
                                                $emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
                                            } else {
                                                $emailmessage = file_get_contents('../emailtemplates/alertMailTemplate.html');
                                                $emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message . $tripdetails, $emailmessage);
                                                $emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
                                                $emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
                                                $emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
                                            }
                                            $cvo = new VOComQueue();
                                            if (sendMail($thisuser->email, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
                                                $cvo->cqid = $thisqueue->cqid;
                                                $cvo->customerno = $thisqueue->customerno;
                                                $cvo->userid = $thisuser->id;
                                                $cvo->type = 0;
                                                $cvo->enh_checkpointid = 0;
                                                $cqm->InsertComHistory($cvo);
                                                $cqm->UpdateComQueue($thisqueue->cqid);
                                            }
                                        }
                                    }
                                    if (isset($thisuser->$sms) && $thisuser->$sms == 1) {
                                        if (isset($thisuser->phone) && $thisuser->phone != "") {
                                            $dates = new Date();
                                            $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                            $location = location($thisqueue->lat, $thisqueue->long, $thisqueue->customerno, $thisqueue->use_geolocation);
                                            if ($thisqueue->type == '1' || $thisqueue->type == '4') {
                                                $smsmessage = $thisqueue->message . ". Location: " . $location;
                                            } else {
                                                $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes . ". Location: " . $location;
                                            }
                                            $cvo = new VOComQueue();
                                            if ($thisqueue->type == '5' && $thisqueue->status == 1) {
                                                //no sms
                                            } else {
                                                if (sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisuser->id)) {
                                                    $cvo->cqid = $thisqueue->cqid;
                                                    $cvo->customerno = $thisqueue->customerno;
                                                    $cvo->userid = $thisuser->id;
                                                    $cvo->type = 1;
                                                    $cvo->enh_checkpointid = 0;
                                                    $cqm->InsertComHistory($cvo);
                                                    $cqm->UpdateComQueue($thisqueue->cqid);
                                                }
                                            }
                                        }
                                    }
                                    if (isset($thisuser->$telephone) && $thisuser->$telephone == 1) {
                                        if (isset($thisuser->phone) && $thisuser->phone != "") {
                                            $dates = new Date();
                                            if ($thisqueue->type == '8') {
                                                $fileid = '2807_4_20160422164452';
                                            }
                                            $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                            $location = location($thisqueue->lat, $thisqueue->long, $thisqueue->customerno, $thisqueue->use_geolocation);
                                            if ($thisqueue->type == '1' || $thisqueue->type == '4') {
                                                $smsmessage = $thisqueue->message . ". Location: " . $location;
                                            } else {
                                                $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes . ". Location: " . $location;
                                            }
                                            $cvo = new VOComQueue();
                                            if ($thisqueue->type == '5' && $thisqueue->status == 1) {
                                                //no sms
                                            } else {
                                                $sendsms = telephonic_Alert($thisuser->phone, $fileid, $thisqueue->customerno, $thisqueue->vehicleid);
                                                $cvo->cqid = $thisqueue->cqid;
                                                $cvo->customerno = $thisqueue->customerno;
                                                $cvo->userid = $thisuser->id;
                                                $cvo->type = 2;
                                                $cvo->enh_checkpointid = 0;
                                                $cqm->InsertComHistory($cvo);
                                                $cqm->UpdateComQueue($thisqueue->cqid);
                                            }
                                        }
                                    }
                                    if (isset($thisuser->$mobile) && $thisuser->$mobile == 1) {
                                        if ($thisuser->notification_status == 1 && $thisuser->gcmid != "") {
                                            $dates = new Date();
                                            $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                            $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes;
                                            $vehicleid = $thisqueue->vehicleid;
                                            $cqm = new ComQueueManager();
                                            $gcmid = array($thisuser->gcmid);
                                            $message = array(
                                                "message" => $smsmessage,
                                                "vehicleid" => $vehicleid,
                                                "type" => $thisqueue->type,
                                            );
                                            $sendnotification = $cqm->send_notification($gcmid, $message, $thisqueue->customerno);
                                        }
                                    }
                                } else {
                                    if ((strtotime($start_alert_time) > strtotime($stop_alert_time)) && ((strtotime($stop_alert_time) > strtotime($hms) && strtotime($hms) < strtotime($start_alert_time)) || (strtotime($stop_alert_time) < strtotime($hms) && strtotime($hms) > strtotime($start_alert_time)))) {
                                        if (isset($thisuser->$email) && $thisuser->$email == 1) {
                                            if (isset($thisuser->email) && $thisuser->email != "") {
                                                $subject = subject($thisqueue->type);
                                                $encodekey = sha1($thisuser->userkey);
                                                $location = location($thisqueue->lat, $thisqueue->long, $thisqueue->customerno, $thisqueue->use_geolocation);
                                                $dates = new Date();
                                                $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                                $email_path = "../emailtemplates/customer/" . $thisqueue->customerno . "/alertMailTemplate.html";
                                                if (file_exists($email_path)) {
                                                    $emailmessage = file_get_contents($email_path);
                                                    $emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message, $emailmessage);
                                                    $emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
                                                    $emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
                                                    $emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
                                                } else {
                                                    $emailmessage = file_get_contents('../emailtemplates/alertMailTemplate.html');
                                                    $emailmessage = str_replace("{{MESSAGE}}", $thisqueue->message . $tripdetails, $emailmessage);
                                                    $emailmessage = str_replace("{{HOURMIN}}", $hourminutes, $emailmessage);
                                                    $emailmessage = str_replace("{{LOCATION}}", $location, $emailmessage);
                                                    $emailmessage = str_replace("{{ENCODEKEY}}", $encodekey, $emailmessage);
                                                }
                                                $cvo = new VOComQueue();
                                                if (sendMail($thisuser->email, $subject, $emailmessage, $thisqueue->vehicleid) == true) {
                                                    $cvo->cqid = $thisqueue->cqid;
                                                    $cvo->customerno = $thisqueue->customerno;
                                                    $cvo->userid = $thisuser->id;
                                                    $cvo->type = 0;
                                                    $cvo->enh_checkpointid = 0;
                                                    $cqm->InsertComHistory($cvo);
                                                    $cqm->UpdateComQueue($thisqueue->cqid);
                                                }
                                            }
                                        }
                                        if (isset($thisuser->$sms) && $thisuser->$sms == 1) {
                                            if (isset($thisuser->phone) && $thisuser->phone != "") {
                                                $dates = new Date();
                                                $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                                $location = location($thisqueue->lat, $thisqueue->long, $thisqueue->customerno, $thisqueue->use_geolocation);
                                                if ($thisqueue->type == '1' || $thisqueue->type == '4') {
                                                    $smsmessage = $thisqueue->message . ". Location: " . $location;
                                                } else {
                                                    $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes . ". Location: " . $location;
                                                }
                                                $cvo = new VOComQueue();
                                                if ($thisqueue->type == '5' && $thisqueue->status == 1) {
                                                    //no sms
                                                } else {
                                                    if (sendSMS($thisuser->phone, $smsmessage, $thisqueue->customerno, $thisqueue->vehicleid, $thisqueue->cqid, $thisuser->id)) {
                                                        $cvo->cqid = $thisqueue->cqid;
                                                        $cvo->customerno = $thisqueue->customerno;
                                                        $cvo->userid = $thisuser->id;
                                                        $cvo->type = 1;
                                                        $cvo->enh_checkpointid = 0;
                                                        $cqm->InsertComHistory($cvo);
                                                        $cqm->UpdateComQueue($thisqueue->cqid);
                                                    }
                                                }
                                            }
                                        }
                                        if (isset($thisuser->$telephone) && $thisuser->$telephone == 1) {
                                            if (isset($thisuser->phone) && $thisuser->phone != "") {
                                                $dates = new Date();
                                                if ($thisqueue->type == '8') {
                                                    $fileid = '2807_4_20160422164452';
                                                }
                                                $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                                $location = location($thisqueue->lat, $thisqueue->long, $thisqueue->customerno, $thisqueue->use_geolocation);
                                                if ($thisqueue->type == '1' || $thisqueue->type == '4') {
                                                    $smsmessage = $thisqueue->message . ". Location: " . $location;
                                                } else {
                                                    $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes . ". Location: " . $location;
                                                }
                                                $cvo = new VOComQueue();
                                                if ($thisqueue->type == '5' && $thisqueue->status == 1) {
                                                    //no sms
                                                } else {
                                                    telephonic_Alert($thisuser->phone, $fileid, $thisqueue->customerno, $thisqueue->vehicleid);
                                                    $cvo->cqid = $thisqueue->cqid;
                                                    $cvo->customerno = $thisqueue->customerno;
                                                    $cvo->userid = $thisuser->id;
                                                    $cvo->type = 2;
                                                    $cvo->enh_checkpointid = 0;
                                                    $cqm->InsertComHistory($cvo);
                                                    $cqm->UpdateComQueue($thisqueue->cqid);
                                                }
                                            }
                                        }
                                        if (isset($thisuser->$mobile) && $thisuser->$mobile == 1) {
                                            if ($thisuser->notification_status == 1 && $thisuser->gcmid != "") {
                                                $dates = new Date();
                                                $hourminutes = $dates->return_hours($thisqueue->timeadded);
                                                $smsmessage = $thisqueue->message . '. Message sent at ' . $hourminutes;
                                                $vehicleid = $thisqueue->vehicleid;
                                                $cqm = new ComQueueManager();
                                                $gcmid = array($thisuser->gcmid);
                                                $message = array(
                                                    "message" => $smsmessage,
                                                    "vehicleid" => $vehicleid,
                                                    "type" => $thisqueue->type,
                                                );
                                                $sendnotification = $cqm->send_notification($gcmid, $message, $thisqueue->customerno);
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
$cqm->insertcronrecords($cron_record_count);
?>
