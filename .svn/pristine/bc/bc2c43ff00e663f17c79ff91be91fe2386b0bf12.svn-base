<?php

//error_reporting(0);
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
include_once("../../../cron/class.phpmailer.php");
require_once __DIR__ . "/../../../../config.inc.php";
require_once("../class/database.inc.php");
require_once("../constants.php");

class ActiviyCron {
    
}

$db = new database_salesengage(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, DB_SALESENGAGE_NEW);

$today = date("Y-m-d H:i");
//$today = "2015-10-15 16:00";
// for email
$sp_email = "call get_emaillog_activity ('$today');";
if ($sp_email != null) {
    $datas = array();
    $queryResult = $db->query($sp_email, __FILE__, __LINE__);
    $row_count = $db->num_rows($queryResult);


    if ($row_count > 0) {
        while ($row = $db->fetch_array($queryResult)) {
            $data = new ActiviyCron();
            $data->emailid = $row['emailid'];
            $data->mailto = $row['to_emailid'];
            $data->fromemail = $row['from_emailid'];
            $data->subject = $row['subject'];
            $data->message = $row['messagebody'];
            $data->customerno = $row['customerno'];
            $datas[] = $data;
        }

        $queryResult->close();
        foreach ($datas as $dat) {
            $db->next_result();
            $mail = new PHPMailer();

            $mail->IsMail();
            $mail->AddAddress($dat->mailto);
            $mail->From = $dat->fromemail;
            //$mail->FromName = $realname;
            $mail->Sender = $dat->fromemail;
            $mail->Subject = $dat->subject;
            $mail->Body = $dat->message;
            $mail->IsHtml(true);
            //SEND Mail
            if ($mail->Send()) {

                $sp_update_done = "call update_emaillog_activity ($dat->emailid , '1');";
                $queryResult1 = $db->query($sp_update_done, __FILE__, __LINE__);
            } else {
                $sp_update_fail = "call update_emaillog_activity ($dat->emailid , '-1');";
                $queryResult2 = $db->query($sp_update_fail, __FILE__, __LINE__);
            }
        }
    }
}

// send sms
$db->next_result();
$sp_sms = "call get_smslog_activity ('$today');";
if ($sp_email != null) {
    $smsdatas = array();
    $queryResult3 = $db->query($sp_sms, __FILE__, __LINE__);
    $row_count3 = $db->num_rows($queryResult3);


    if ($row_count3 > 0) {
        while ($row3 = $db->fetch_array($queryResult3)) {
            $datasms = new ActiviyCron();
            $datasms->smsid = $row3['smsid'];
            $datasms->mobileno = $row3['mobileno'];
            $datasms->message = $row3['message'];
            $datasms->customerno = $row3['customerno'];
            $smsdatas[] = $datasms;
        }

        $queryResult3->close();
        foreach ($smsdatas as $smsdat) {
            $db->next_result();
            $existingSMSCount = 0;
            $existingSMSCount = getSMSCount($smsdat->customerno);
            if ($existingSMSCount != 0) {
                $countryCode = "91";
                $arrPhone = array();
                if (is_array($datasms->mobileno)) {
                    foreach ($datasms->mobileno as $phone) {
                        if (preg_match('/^\d{10}$/', $phone)) {
                            $arrPhone[] = $countryCode . $phone;
                        }
                    }
                } else {
                    $arrPhone[] = $countryCode . $datasms->mobileno;
                }
                $phone = implode(",", $arrPhone);
                $url = str_replace("{{PHONENO}}", urlencode($phone), SMS_URL);
                $url = str_replace("{{MESSAGETEXT}}", urlencode($smsdat->message), $url);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                $response = curl_exec($ch);
                if ($response === false) {
                    //echo 'Curl error: ' . curl_error($ch);
                    //$response="sending failed";
                    $sp_update_sms_fail = "call update_smslog_activity ($smsdat->smsid , '-1','$response');";
                    $queryResult5 = $db->query($sp_update_sms_fail, __FILE__, __LINE__);
                } else {

                    $sp_update_sms_done = "call update_smslog_activity ($smsdat->smsid , '1','$response');";
                    $queryResult4 = $db->query($sp_update_sms_done, __FILE__, __LINE__);
                    updateSMSCount($existingSMSCount, $smsdat->message, $datasms->customerno);
                }
                curl_close($ch);
            } else {
                $sp_update_sms_fail = "call update_smslog_activity ($smsdat->smsid , '-1','no sms left for customer');";
                $queryResult6 = $db->query($sp_update_sms_fail, __FILE__, __LINE__);
            }
        }
    }
}

function getSMSCount($customerno) {
    $db = new database_salesengage(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, DB_SALESENGAGE_NEW);
    $smscount = 0;
    $sqlSmsGet = sprintf("SELECT smsleft FROM " . SPEEDDB . ".customer WHERE customerno=%d", $customerno);
    $arrResult = $db->query($sqlSmsGet, __FILE__, __LINE__);
    $res = $db->fetch_array($arrResult);
    if (count($res) == 1) {
        $smscount = $res['smsleft'];
    }
    return $smscount;
}

function updateSMSCount($existingSMSCount, $smsmessage, $customerno) {
    $db = new database_salesengage(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, DB_SALESENGAGE_NEW);
    $smsconsumed = 0;
    $smsleft = 0;
    $smslength = strlen($smsmessage);
    $divide = floor($smslength / constants::PER_SMS_CHARACTERS);
    $mod = $smslength % constants::PER_SMS_CHARACTERS;
    if ($mod > 0) {
        $smsconsumed = $divide + 1;
    } else if ($mod == 0) {
        $smsconsumed = $divide;
    }
    $smsleft = $existingSMSCount - $smsconsumed;
    $sqlSmsUpdate = sprintf("UPDATE " . SPEEDDB . ".customer SET smsleft=%d WHERE customerno=%d", $smsleft, $customerno);
    $data = $db->query($sqlSmsUpdate, __FILE__, __LINE__);
    //$sqlSmsUpdate->close();
}
