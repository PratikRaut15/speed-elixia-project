<?php

require_once '../../lib/bo/CronManager.php';
include_once '../../lib/system/utilities.php';
$cronm = new CronManager();

$reminders = $cronm->sendRemindersForSR();
if (!empty($reminders)) {
    $sr = 1;
    $team = "";
    foreach ($reminders as $reminder) {

        $html = "Dear ".$reminder->reminder_send_to_name.",<br>
                    This mail is a reminder 
                    for Pipeline ID : ".$reminder->pipelineid." (".$reminder->company_name.") <br>
                    Set by : ".$reminder->creator." <br> 
                    To remind you to : '".$reminder->content."'";
        $subject = "Elixia Tech - Reminder :" . date('Y-m-d H:i:s');

        //$subject = "Elixia Tech - Reminder (Sales Pipeline):" . date('Y-m-d H:i:s');
        $to = array($reminder->emailId);
        $strCCMailIds = "";
        $strBCCMailIds = "";
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $isTemplatedMessage = 1;
        echo $html; echo PHP_EOL;
        if(!sendMailUtil($to, $strCCMailIds, $strBCCMailIds, $subject, $html, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage)){
            $cronm->setReminderComplete($reminder->reminderid);
        }
        $sr++;
    }

}

?>