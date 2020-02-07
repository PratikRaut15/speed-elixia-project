<?php
include_once '../../lib/bo/SMSLogManager.php';
require_once('../../modules/reports/html2pdf.php');
$customerno = 161;
$moduleid = 3;
//$arrToMailIds = array('gps.fls@fortpoint.co.in','bomtransport@jetairways.com', 'imrank@jetairways.com', 'FAsundaria@jetairways.com','sanketsheth@elixiatech.com');
$arrToMailIds = array('gps.fls@fortpoint.co.in','sanketsheth@elixiatech.com');
//$arrToMailIds = array('mrudang.vora@elixiatech.com', 'sshrikanth@elixiatech.com', 'sanketsheth1@gmail.com');
$strCCMailIds = '';
$strBCCMailIds = '';
//<editor-fold defaultstate="collapsed" desc="Cron Body">
//
//<editor-fold defaultstate="collapsed" desc="Create Email Body">
$message = "<html>";
$message.= "<head>
            <style type='text/css'>
            body{
                font-family:Arial;
                font-size: 11pt;
            }
        </style>
        </head>
        <body>";
$yesterdayDate = new DateTime('yesterday');
$message .= "Greetings from Elixia Tech!";
$message .= "<br/><br/>";
$message .= "Please find the Tagging Summary attached for " . $yesterdayDate->format(speedConstants::DEFAULT_DATE);
$message .= "<br/><br/>";
$message .= "Regards,<br/>Elixia Speed Team!";
$message .= "</body></html>";
echo $message;
// </editor-fold>
//
//<editor-fold defaultstate="collapsed" desc="Create Attachment Body">
$attachmentData = GetSMSLogs($customerno, $moduleid, $yesterdayDate->format('Y-m-d 00:00:00'));
$title = "Tagging Summary for " . $yesterdayDate->format(speedConstants::DEFAULT_DATE);
$attachmentBody = "<html>";
$attachmentBody.= "<head>
            <style type='text/css'>
            body{
                font-family:Arial;
                font-size: 11pt;
            }
            table{
                text-align: center;
                border-right:1px solid black;
                border-bottom:1px solid black;
                font-family:Arial;
                font-size: 10pt;
            }
            td, th{
                border-left:1px solid black;
                border-top:1px solid black;
            }
            .colHeading{
                background-color: #D6D8EC;
            }
            hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
        </style>
        </head>
        <body>";
$header = '<div style="width:auto; height:30px;">
<table style="width: 100%; border:none;">
    <tr>
    <td style="width:25%; border:none;"><img style="width:50%; height: 50%;" src="../../images/elixiaspeed_logo.png" /></td>
    <td style="width:50%; border:none;"><h3 style="text-transform:uppercase;">' . $title . '</h3><br /> </td>
    <td style="width:25%; border:none;"><img src="../../images/elixia_logo_75.png"  /></td>
    </tr>
</table>
</div><hr />';
$attachmentBody .= $header;
$attachmentBody .= "<br/><br/>";
$attachmentBody .= "<table width='100%'>";
$attachmentBody .= "<tr>";
$attachmentBody .= "<th width='25%' class='colHeading'>Phone No</th>";
$attachmentBody .= "<th width='40%' class='colHeading'>Message</th>";
$attachmentBody .= "<th width='15%' class='colHeading'>Timestamp</th>";
$attachmentBody .= "<th width='20%' class='colHeading'>Response</th>";
$attachmentBody .= "</tr>";
if (isset($attachmentData)) {
 foreach ($attachmentData as $data) {
  $attachmentBody .= "<tr>";
  $attachmentBody .= "<td>" . wordwrap($data['mobileno'], 75, "<br>\n", true) . "</td>";
  $attachmentBody .= "<td>" . wordwrap($data['message'], 75, "<br>\n", true) . "</td>";
  $attachmentBody .= "<td>" . wordwrap($data['inserted_datetime'], 75, "<br>\n", true) . "</td>";
  $attachmentBody .= "<td>" . wordwrap($data['response'], 75, "<br>\n", true) . "</td>";
  $attachmentBody .= "</tr>";
 }
} else {
 $attachmentBody .= "<tr><td colspan='4'>No Data</td></tr>";
}
$attachmentBody .= "</table>";
$attachmentBody .= "</body></html>";
echo $attachmentBody;
// </editor-fold>
//
//<editor-fold defaultstate="collapsed" desc="Create PDF Attachment and Send Mail">
$attachmentFilePath = "../../customer/$customerno/drivermapping/";
if (!file_exists($attachmentFilePath)) {
 mkdir($attachmentFilePath, 0777, true);
}
$attachmentFileName = "smslog_" . $yesterdayDate->format('d_M_Y') . ".pdf";
try {
 $html2pdf = new HTML2PDF('L', 'A4', 'en');
 $html2pdf->pdf->SetDisplayMode('fullpage');
 $html2pdf->writeHTML($attachmentBody);
 $html2pdf->Output($attachmentFilePath . $attachmentFileName, 'F');
} catch (HTML2PDF_exception $e) {
 echo $e;
 exit;
}
$subject = "Tagging Summary for " . $yesterdayDate->format(speedConstants::DEFAULT_DATE);
sendMail($arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName);
// </editor-fold>
//
// </editor-fold>
//
//<editor-fold defaultstate="collapsed" desc="Helper functions">
function sendMail(array $arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName) {
 include_once("class.phpmailer.php");
 $isEmailSent = 0;
 $completeFilePath = '';
 if ($attachmentFilePath != '' && $attachmentFileName != '') {
  $completeFilePath = $attachmentFilePath . "/" . $attachmentFileName;
 }
 $mail = new PHPMailer();
 $mail->IsMail();
 $mail->ClearAddresses();
 $mail->ClearAllRecipients();
 $mail->ClearAttachments();
 $mail->ClearCustomHeaders();
 if (!empty($arrToMailIds)) {
  foreach ($arrToMailIds as $mailto) {
   $mail->AddAddress($mailto);
  }
  if (!empty($strCCMailIds)) {
   $mail->AddCustomHeader("CC: " . $strCCMailIds);
  }
  if (!empty($strBCCMailIds)) {
   $mail->AddCustomHeader("BCC: " . $strBCCMailIds);
  }
 }
 $mail->From = "noreply@elixiatech.com";
 $mail->FromName = "Elixia Speed";
 $mail->Sender = "noreply@elixiatech.com";
 $mail->Subject = $subject;
 $mail->Body = $message;
 $mail->IsHtml(true);
 if ($completeFilePath != '' && $attachmentFileName != '') {
  $mail->AddAttachment($completeFilePath, $attachmentFileName);
 }
 //SEND Mail
 if ($mail->Send()) {
  $isEmailSent = 1; // or use booleans here
 }
 return $isEmailSent;
}
function GetSMSLogs($customerno, $moduleid, $dateparam) {
 $arrResult = array();
 $objSMSLogManager = new SMSLogManager($customerno);
 $arrResult = $objSMSLogManager->getSMSLog($moduleid, $dateparam);
 return $arrResult;
}
// </editor-fold>
//
?>
