<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TMSUtility
 *
 * @author user3
 */
//error_reporting(0);
//ini_set('display_errors', 'On');

class TMSUtility {

    //put your code here

    static function sendMail(array $arrToMailIds, array $arrCCMailIds, array $arrBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName) {
        include_once("../cron/class.phpmailer.php");

        $isEmailSent = false;
        $completeFilePath = '';
        if ($attachmentFilePath != '' && $attachmentFileName != '') {
            $completeFilePath = $attachmentFilePath . "/" . $attachmentFileName;
        }
        $mail = new PHPMailer();
        $mail->IsMail();
        if (!empty($arrToMailIds)) {
            foreach ($arrToMailIds as $mailto) {
                $mail->AddAddress($mailto);
            }
            
            if (!empty($arrCCMailIds)) {
                foreach ($arrCCMailIds as $mailCC) {
                    //$mail->AddCC($mailCC);
                    $mail->AddCustomHeader("Cc:".$mailCC);
                }
            }
             
            if (!empty($arrBCCMailIds)) {
                foreach ($arrBCCMailIds as $mailBCC) {
                    //$mail->AddBCC($mailBCC);
                    $mail->AddCustomHeader("Bcc:".$mailBCC);
                }
            }
        }
        $mail->From = "noreply@elixiatech.com";
        $mail->FromName = "Elixia Speed";
        $mail->Sender = "noreply@elixiatech.com";
        //$mail->AddReplyTo($from,"Elixia Speed");
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->IsHtml(true);

        if ($completeFilePath != '' && filename != '') {
            $mail->AddAttachment($completeFilePath, $attachmentFileName);
        }
        //SEND Mail
        if ($mail->Send()) {
            $isEmailSent = true; // or use booleans here
        }
        return $isEmailSent;
    }

    static function sendSMS($phoneArray, $message, &$response) {
        $isSMSSent = false;
        $countryCode = "91";
        $arrPhone = array();
        if (is_array($phoneArray)) {
            foreach ($phoneArray as $phone) {
                $arrPhone[] = $countryCode . $phone;
            }
        } else {
            $arrPhone[] = $countryCode . $phoneArray;
        }
        $phone = implode(",", $arrPhone);
        $url = str_replace("{{PHONENO}}", urlencode($phone), SMS_URL);
        $url = str_replace("{{MESSAGETEXT}}", urlencode($message), $url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        if ($response === false) {
            //echo 'Curl error: ' . curl_error($ch);
            $isSMSSent = false;
        } else {
            $isSMSSent = true;
        }
        curl_close($ch);
        return $isSMSSent;
    }

}
