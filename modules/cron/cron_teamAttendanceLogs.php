<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

require_once '../../lib/bo/TeamManager.php';
include_once '../../lib/system/utilities.php';
$team= new TeamManager();


$dailydate = date('Y-m-d');
$date = date("d-m-Y",strtotime($dailydate));

$Logs = $team->fetchTeamAttendanceLogs($dailydate);

$reducedLogs = array_reduce($Logs, function ($result, $currentItem) {
	 if (isset($result[$currentItem['name']])) {
	 	$attendanceArray = array();
	 	$attendanceArray['checkValue'] = $currentItem['checkValue'];
	 	$attendanceArray['createdOn'] = $currentItem['createdOn'];
	 	$result[$currentItem['name']][] = $attendanceArray;

	 }else{
	 	$attendanceArray = array();
	 	$attendanceArray['checkValue'] = $currentItem['checkValue'];
	 	$attendanceArray['createdOn'] = $currentItem['createdOn'];
	 	  $result[$currentItem['name']][] = $attendanceArray;
	 }
	 return $result;
});
$html = "";
$html .= "Find the Attendance Records for ".$date."<br/><br/>";
$html .= "The 'Green Cells' indicated the first 'Check In' of the day.<br/><br/>";

$html .= "<table border=1><th>Name</th><th>Activity</th><th>Time</th>";

if(isset($reducedLogs) && !empty($reducedLogs)){
	foreach($reducedLogs as $k=>$record){
		$old_name = $k;
		$nameCount = 0;
		foreach($record as $j=>$logs){

			if($old_name==$k && $nameCount<=0){
				$html .= "<tr><td>".$k."</td><td style='background-color:#3cba54;'>".$logs['checkValue']."</td><td>".date("H:i a",strtotime($logs['createdOn']))."</td></tr>";
			}else{
				$html .= "<tr><td></td><td>".$logs['checkValue']."</td><td>".date("H:i a",strtotime($logs['createdOn']))."</td></tr>";
			}
			
			$nameCount++;
		}	
	}
}


$html .= "</table>";

	

	$subject = "<< Team Attendance logs for ".$date." >>";
	


	$to = array("hr@elixiatech.com"); 
	$strCCMailIds = "";
	$strBCCMailIds = "kartikj@elixiatech.com,shreya.a@elixiatech.com";
	$attachmentFilePath = "";
	$attachmentFileName = "";
	$isTemplatedMessage = 1;
	if(isset($reducedLogs) && !empty($reducedLogs)){
		if(!sendMailUtil($to, $strCCMailIds, $strBCCMailIds, $subject, $html, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage)){
		    echo "Sending failed.";
		}else{
			echo "Email Sent.";
		}
	}else{
		echo "No records for ".$date;
	}
?>