<?php

$edate = date('Y-m-d');
$sdate = date('Y-m-d', strtotime('-7 days'));

if(empty($sdate) || empty($edate)){
    echo "Please check start date or end date"; die();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);
set_time_limit(0);
ini_set('memory_limit', '256M');

require "../../lib/system/utilities.php";
require '../../lib/bo/CustomerManager.php';
require '../../lib/bo/UserManager.php';
include '../../lib/bo/simple_html_dom.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../modules/reports/reports_common_functions.php';
$serverpath = "/var/www/html/speed";
$customerno = '577';
//$sdate = '2016-07-01'; //Jully  
//$edate = '2016-07-31';
$stime = '00:00';
$etime = '23:59';
$interval_p = '1';   // 1min 

$STdate = GetSafeValueString($sdate, 'string');
$EDdate = GetSafeValueString($edate, 'string');
$interval = GetSafeValueString($interval_p, 'long');
$newsdate = date("Y-m-d", strtotime($sdate));
$newedate = date("Y-m-d", strtotime($edate));

$cm = new CustomerManager();
$customernos = $cm->getcustomernos();
$grouplistdata = $cm->getgrouplistby_customer($customerno);
$overAllHeader = 'Monthly Compliance Report for ' . date('F Y', strtotime($sdate))."</br>";
$headerRow = '';
$groupContent = '';
$tableContent = '';
if (isset($grouplistdata)) {
    foreach ($grouplistdata as $row) {
        $groupName = $row['groupname'];
        $groupHeading = '<br/><span style="color:steelblue;padding:10px;"><h3>Group : '.$groupName.'</h3></span><br/>';
        $vehicleHeading = '';
        $deviceTableContent = '';
        $groupName = '';
        $deviceTable = '';
        $groupid = $row['groupid'];
        $veh = new VehicleManager($customerno);
        $vehicledata = $veh->get_all_vehicles_by_group(array($groupid), 1);
        $temperature_compliance_data = array();
        $noOfSensors = 0;
        foreach ($vehicledata as $row1) {
            if($row1->type=='Warehouse'){
                $switchTo = 3;
            }else{
                $switchTo = 0;
            }
            $noOfSensors = 0;
            if(isset($row1->tempsen1) && $row1->tempsen1 != 0){
                $noOfSensors++;
            }
            if(isset($row1->tempsen2) && $row1->tempsen2 != 0){
                $noOfSensors++;   
            }
            if(isset($row1->tempsen3) && $row1->tempsen3 != 0){
                $noOfSensors++;
            }
            if(isset($row1->tempsen4) && $row1->tempsen4 != 0){
                $noOfSensors++;
            }
            $temperature_compliance_data[] = getTemperatureData($STdate, $EDdate, $row1->deviceid, $interval, $stime, $etime, $customerno, $noOfSensors,$switchTo);
        }
        foreach($temperature_compliance_data as $k=>$device){
            $sensorNo = 1;
            if(isset($device['unit'])){
                $vehicleNo = $device['unit']->vehicleno;
                $deviceTableHeader = '<table border=1>';
                $deviceTableHeader .= '<tr><th> '.$vehicleNo.' </th>';
                $percentageRow = '<tr><td>Compliance % (Count) </td>';
                $statsRow = '<tr><td>Good / Bad / Muted / Total </td>';
                $rows = '';
                $data = 0;
                while ($sensorNo <= $noOfSensors){
                    if(isset($device['temp'.$sensorNo])){
                        $data = 1;
                        $deviceTableHeader .= '<th>'.$device['temp'.$sensorNo]['sensorName'].'</th>';
                        $percentageRow .= 
                                        '<td>'.$device['temp'.$sensorNo]['sensorStats']['compliancePercent'].'%('.$device['temp'.$sensorNo]['sensorStats']['complianceCount'].')
                                        </td>';
                        $statsRow .= 
                                    "<td>".
                                        $device['temp'.$sensorNo]['sensorStats']['goodCount']."/".
                                        $device['temp'.$sensorNo]['sensorStats']['badCount']."/".
                                        $device['temp'.$sensorNo]['sensorStats']['mutedCount']."/".
                                        $device['temp'.$sensorNo]['sensorStats']['totalCount']
                                    ."</td>";   
                    }
                    $sensorNo++;
                }
                if($data == 0 ){
                    $deviceTableHeader = 'Data not available.';
                    $percentageRow = 'No data';
                    $statsRow = 'No data';
                }else{
                    $deviceTableHeader .= '</tr>';
                    $percentageRow .= '</tr>';
                    $statsRow .= '</tr>';
                }
                $rows .= $deviceTableHeader;
                $rows .= $percentageRow;
                $rows .= $statsRow; 
                $deviceTableContent .= $rows;
            }
        }
        $deviceTableContent .= '';
        $groupContent .= '<br/>';
        $groupContent .= $groupHeading;
        $groupContent .= $deviceTableContent;
    }
}
//s$headerRow .= '</tr>';

$message = $overAllHeader;
$message .= $groupContent;
// echo ($message);
// die();
$content = ob_get_clean();
$content = str_replace("()", "", $message);
$content = str_replace("//", "", $content);
echo $content;
$dest1 = "../../customer";
$dest2 = "../../customer/$customerno/temperaturereports";

if (!file_exists($dest1)) {
    mkdir($dest1, 0777, true);
}
if (!file_exists($dest2)) {
    mkdir($dest2, 0777, true);
}
$ext = ".xls";
$file_name = "citywisedata_fassos_monthly_report_" . date('F Y', strtotime($sdate)) . $ext;
$fullpath = $dest2 . "/" . $file_name;
save_xls($fullpath, $content);

$subject = 'Monthly Compliance Report for ' . date('F Y', strtotime($sdate));

function save_xls($full_path, $content) {
    include_once'../../lib/bo/simple_html_dom.php';
    $html = str_get_html($content);
    $fp = fopen($full_path, "w");
    fwrite($fp, $html);
    fclose($fp);
}

$toArr = array(
    'kartikj@elixiatech.com'
    // , 'prashant.nair@faasos.com'
    // , 'vipin@faasos.com'
    // , 'ashwini.patil@faasos.com'
    // , 'swapnil.perane@faasos.com'
);
$CCEmail = '';
$BCCEmail = 'mrudang.vora@elixiatech.com';
$emailmessage = "Monthly Compliance Report for " . date('F Y', strtotime($sdate));

$emailstatus = sendMailPHPMAILER($toArr, $CCEmail, $BCCEmail, $subject, $emailmessage, $dest2, $file_name);

function sendMailPHPMAILER(array $arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName) {
    include_once("class.phpmailer.php");
    $isEmailSent = 0;
    $completeFilePath = '';
    if ($attachmentFilePath != '' && $attachmentFileName != '') {
        $completeFilePath = $attachmentFilePath . "/" . $attachmentFileName;
    }
    $mail = new PHPMailer();
    $mail->IsMail();
    /* Clear Email Addresses */
    $mail->ClearAddresses();
    $mail->ClearAllRecipients();
    $mail->ClearAttachments();
    $mail->ClearCustomHeaders();
    //unset($arrToMailIds);
    //$arrToMailIds = array('ganeshp@elixiatech.com','sshrikanth@elixiatech.com', 'mrudangvora@gmail.com', 'shrisurya24@gmail.com');
    //$strCCMailIds = '';
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
    //$mail->AddReplyTo($from,"Elixia Speed");
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
    /* Clear Email Addresses */
    $mail->ClearAddresses();
    $mail->ClearAllRecipients();
    $mail->ClearAttachments();
    $mail->ClearCustomHeaders();
    return $isEmailSent;
}

if ($emailstatus == 1) {
    echo "Mail sent";
}
else {
    echo "Error sending mail ";
}
?>