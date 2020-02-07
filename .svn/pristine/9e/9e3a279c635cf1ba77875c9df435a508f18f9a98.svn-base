<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once '../../lib/bo/TeamManager.php';
include_once '../../lib/system/utilities.php';
$team = new TeamManager();

$yesterday = date('d-m-Y', strtotime('-1 day'));
$yesterdayDate = date('l, d-M-Y', strtotime('-1 day'));

$arrData = $team->fetchTeamAttendanceLogs($yesterday);
prettyPrint($arrData);die();

$objRequest = new stdClass();
$objRequest->yesterday = date('Y-m-d', strtotime('-1 day'));

//email body start
$absentStudents = getFilteredAttendanceData($arrData, 'attendanceId', '', 'personName', 'absents');
$halfDayStudents = getFilteredAttendanceData($arrData, 'minCheckInTime', Constants::ELIXIA_MAX_INTIME, 'personName', 'halfdays');

$absentsString = '<ol>';
foreach ($absentStudents as $key => $value) {
    //$absentsString.="<tr><td>$value</td></tr>";
    $absentsString .= "<li>$value</li>";
}
$absentsString .= "</ol>";

$halfdaysString = '<ol>';
foreach ($halfDayStudents as $key => $value) {
    //$absentsString.="<tr><td>$value</td></tr>";
    $halfdaysString .= "<li>$value</li>";
}
$halfdaysString .= "</ol>";

//$arrToMailIds = array('sonali.elixiatech@gmail.com');
$arrToMailIds = array('shreya.a@elixiatech.com', 'sanketsheth@elixiatech.com', 'mihir@elixiatech.com');
$strCCMailIds = 'mrudang.vora@elixiatech.com';
$strBCCMailIds = 'sonali.elixiatech@gmail.com';
$message = '';
$subject = "Attendance Report : $yesterday";

$downloadlink = "<a href='http://uat-attendance.elixiatech.com/attendance/getDailyAttendanceData/$customerNo/" . strtotime('-1 day') . "'>Click Here</a>";
$pdfdownloadlink = "<a href='http://uat-attendance.elixiatech.com/attendance/getDailyAttendanceDataPdf/$customerNo/" . strtotime('-1 day') . "' target='_blank'>Click Here</a>";

$placehoders['{{ATTDATE}}'] = $yesterdayDate;
$placehoders['{{EXCELLINK}}'] = $downloadlink;
$placehoders['{{PDFLINK}}'] = $pdfdownloadlink;
$placehoders['{{ABSENTSTUDENTS}}'] = $absentsString;
$placehoders['{{HALFDAYSTUDENTS}}'] = $halfdaysString;

$totalHalfDayStudents = "<b>Total : " . count($halfDayStudents) . "</b>";
$totalAbsentStudents = "<b>Total : " . count($absentStudents) . "</b>";
$placehoders['{{HALFDAYCOUNT}}'] = $totalHalfDayStudents;
$placehoders['{{ABSENTCOUNT}}'] = $totalAbsentStudents;

$message = file_get_contents('usercontrol/attendanceHistoryReport.php');
foreach ($placehoders as $key => $val) {
    $message = str_replace($key, $val, $message);
}

//email body end

echo $message;
$isMailSent = Utilities::sendMailUtil($arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $message, '', '', 0);
//echo "isMailSent => $isMailSent";
if ($isMailSent) {
    $arrResponse['code'] = 1;
    $arrResponse['message'] = 'Mail sent successfully';
} else {
    $arrResponse['code'] = 0;
    $arrResponse['message'] = 'Mail could not be send';
    $arrResponse['data'] = $isMailSent;
}

function getFilteredAttendanceData(array $data, $matchColumn, $excludeId, $getColumn, $action) {
    return array_reduce($data, function ($acc, $u) use ($matchColumn, $excludeId, $getColumn, $action) {
        if ($action == 'absents') {
            if ($u[$matchColumn] != $excludeId) {
                return $acc;
            }
        }
        if ($action == 'halfdays') {
            if ($u[$matchColumn] <= $excludeId) {
                return $acc;
            }
        }
        return array_merge($acc, [$u[$getColumn]]);
    }, []);
}

?>