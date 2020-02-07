<?php

//error_reporting(0);
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
include_once '../../lib/system/DatabaseManager.php';
include_once '../cron/class.phpmailer.php';
include_once '../../lib/system/Log.php';

$module = 'knowledge base share email';
$realname = 'Elixia Tech';
$db = new DatabaseManager();
$pdo = $db->CreatePDOConn();

$sp_params = 'date("Y-m-d H:i")';
//$sp_params = '2016-01-16 11:40';
$QUERY = 'call team_cron_get_knowledgebase_email(' . "'" . $sp_params . "'" . ')';
$result = $pdo->query($QUERY);
$email_list = Array();
if ($result->rowCount() > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $cron = new stdClass();
        $cron->emailid = $row['kb_emailid'];
        $cron->to = $row['kb_to'];
        $cron->from = $row['kb_from'];
        $cron->subject = $row['kb_subject'];
        $cron->message = $row['kb_message'];
        $cron->customerno = $row['customerno'];
        $email_list[] = $cron;
    }
}
$db->ClosePDOConn($pdo);
if (!empty($email_list)) {
    foreach ($email_list as $email) {
        $mail = new PHPMailer();

        try {
            $mail->IsMail();
            $mail->ClearAddresses();
            $mail->ClearAllRecipients();
            $mail->AddAddress($email->to);
            $mail->From = $email->from;
            $mail->FromName = $realname;
            $mail->Sender = $email->from;
            $mail->Subject = $email->subject;
            $mail->Body = $email->message;
            $mail->IsHtml(true);
            if ($mail->Send()) {
                $sp_update_done = "call team_cron_update_knowledgebase_email ($email->emailid,'1');";
                $queryResult1 = $pdo->query($sp_update_done);
            }
        } catch (phpmailerException $e) {
            //echo $e->errorMessage();
            $log = new Log();
            $log->createlog($email->customerno, $e->errorMessage(), $module, __FUNCTION__);
        }
    }
}