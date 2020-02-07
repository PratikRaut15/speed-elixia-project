<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once '../../lib/autoload.php';
require_once '../../lib/system/utilities.php';
echo "<br/> Cron Start On ".date(speedConstants::DEFAULT_TIMESTAMP)." <br/>";
$crnmanager = new CronManager();
$customermanager = new CustomerManager();
$smsStatus = new SmsStatus();
$details = $crnmanager->getSMSLockedVehicleUser();
//echo '<pre>';print_r($details); die();
$todaysdate = date("Y-m-d H:i:s");
$moduleid = 1;
$smsMsg = "SMS has been locked for {{USER-VEHICLE}} as it has exceeded hourly SMS cap. Contact support team for unlocking the same.";
if (isset($details)) {
    foreach ($details as $data) {
        $i = 0;
        $html = file_get_contents('../emailtemplates/smsLockReportTeam.html');
		$arrTo = array();
        if ($data['userid'] == 0) {
            $user = $crnmanager->getUserOfVehicle($data['vehicleid']);
			if(isset($user) && count($user) > 0){
				$smslog = $crnmanager->getSmsLogVehicle($data['vehicleid'], $data['createdon']);
				$logTable = "<table><tr><td colspan='4' class='colHeading'>SMS sent in past one hour</td></tr>
									<tr><th>Sr No</th><th>Mobile No</th><th>Message</th><th>Time</th></tr>";
				foreach ($smslog as $log) {
					$i++;
					$logTable.="<tr><td>" . $i . "</td><td>" . $log['mobileno'] . "</td><td>" . $log['message'] . "</td><td>" . $log['inserted_datetime'] . "</td></tr>";
				}
				$logTable.="</table>";
				$table = "<table><tr><td colspan='3' class='colHeading'>Details of vehicle</td></tr>
							<tr>
								<th class='colHeading'>Sr No</th>
								<th class='colHeading'>Vehicle No</th>
								<th class='colHeading'>Time</th>
							</tr>
							<tr><td>1</td><td>" . $data['vehicleno'] . "</td><td>" . $data['createdon'] . "</td></tr>
						</table>";
				$html = str_replace("{{REALNAME}}", $user['realname'], $html);
				$html = str_replace("{{USER-VEHICLE}}", $data['vehicleno'], $html);
				$html = str_replace("{{TABLE}}", $table, $html);
				$html = str_replace("{{CUSTOMER}}", $data['customerno'], $html);
				$html = str_replace("{{DATA}}", $logTable, $html);
				$arrTo = array($user['email']);
				$message = str_replace("{{USER-VEHICLE}}", $data['vehicleno'], $smsMsg);
				$subject = "SMS lock alert for " . $data['vehicleno'];
			}
        } elseif ($data['vehicleid'] == 0) {
            $user = $crnmanager->getUserDetails($data['userid'], $data['customerno']);
			if(isset($user) && count($user) > 0){
				$smslog = $crnmanager->getSmsLogUser($data['userid'], $data['createdon']);
				$logTable = "<table><tr><td colspan='4' class='colHeading'>SMS sent in past one hour</td></tr>
									<tr><th>Sr No</th><th>Mobile No</th><th>Message</th><th>Time</th></tr>";
				foreach ($smslog as $log) {
					$i++;
					$logTable.="<tr><td>" . $i . "</td><td>" . $log['mobileno'] . "</td><td>" . $log['message'] . "</td><td>" . $log['inserted_datetime'] . "</td></tr>";
				}
				$logTable.="</table>";
				$html = str_replace("{{REALNAME}}", $user['realname'], $html);
				$html = str_replace("{{USER-VEHICLE}}", $user['username'], $html);
				$html = str_replace("{{TABLE}}", "", $html);
				$html = str_replace("{{CUSTOMER}}", $data['customerno'], $html);
				$html = str_replace("{{DATA}}", $logTable, $html);
				$arrTo = array($user['email']);
				$message = str_replace("{{USER-VEHICLE}}", $user['username'], $smsMsg);
				$subject = "SMS lock alert for " . $user['username'];
			}
        }
		if(isset($arrTo) && count($arrTo) > 0){
			//      Send Mail to user
			$strCCMailIds = "support@elixiatech.com";
			$strBCCMailIds = "mihir@elixiatech.com,sanketsheth@elixiatech.com,mrudangvora@elixiatech.com";
			$attachmentFilePath = "";
			$attachmentFileName = "";
			$isTemplatedMessage = 1;
			$isMailSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $html, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
			if ($isMailSent == 1) {
				$crnmanager->isSmsLockMailSent($data['logid']);
			}
		}
//      Send SMS to user
        $response = '';
		if(isset($user) && count($user) > 0){
			if ($user['phone'] != '') {
				$smsStatus->customerno = $data['customerno'];
				$smsStatus->userid = $data['userid'];
				$smsStatus->vehicleid = $data['vehicleid'];
				$smsStatus->mobileno = $user['phone'];
				$smsStatus->message = $message;
				$smsStatus->cqid = 0;
				$smsStat = $cm->getSMSStatus($smsStatus);
				if ($smsStat == 0) {
					$response = '';
					$isSMSSent = sendSMSUtil($user['phone'], $message, $response);
					if ($isSMSSent == 1) {
						$crnmanager->isSmsLockSMSSent($data['logid']);
						$cm->sentSmsPostProcess($customerno, $userphone, $smsText, $response, $isSMSSent, $useridparam, $vehicleid, 1);
					}
				}
			}
		}
    }
}
echo "<br/> Cron Completed On ".date(speedConstants::DEFAULT_TIMESTAMP)." <br/>";
?>
