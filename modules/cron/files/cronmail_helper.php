<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include_once "../../lib/system/utilities.php";
include_once '../../lib/autoload.php';
function sendMail($to, $subject, $content, $vehicleid) {
	$cm = new CustomerManager();
	/*
		    $headers = "From: noreply@elixiatech.com\r\n";
		    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		    if (!@mail($to, $subject, $content, $headers)) {
		    // message sending failed
		    return false;
		    }
	*/
	$isMailSent = 0;
	$arrTo = array($to);
	$strCCMailIds = "";
	$strBCCMailIds = "";
	$attachmentFilePath = "";
	$attachmentFileName = "";
	$isTemplatedMessage = 0;
	$isMailSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $content, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
	if (!$isMailSent) {
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

function location($lat, $long, $customerno, $usegeolocation) {
	$address = NULL;
	if ($lat != '0' && $long != '0') {
		if ($usegeolocation == 1) {
			$API = "http://speed.elixiatech.com/location.php?lat=" . $lat . "&long=" . $long . "";
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

function getComQueType($type) {
	$objComType = new stdClass();
	$objComType->sms = '';
	$objComType->email = '';
	$objComType->telephone = '';
	$objComType->mobile = '';
	$objComType->subject = '';

	switch ($type) {
	case '1':
		$objComType->sms = 'ac_email';
		$objComType->email = 'ac_sms';
		$objComType->telephone = 'ac_telephone';
		$objComType->mobile = 'ac_mobilenotification';
		$objComType->subject = 'Genset / AC Status';
		return $objComType;
		break;
	case '2':
		$objComType->sms = 'chk_email';
		$objComType->email = 'chk_sms';
		$objComType->telephone = 'chk_telephone';
		$objComType->mobile = 'chk_mobilenotification';
		$objComType->subject = 'Checkpoint Status';
		return $objComType;
		break;
	case '3':
		$objComType->sms = 'mess_email';
		$objComType->email = 'mess_sms';
		$objComType->telephone = 'mess_telephone';
		$objComType->mobile = 'mess_mobilenotification';
		$objComType->subject = 'Fence Conflict Status';
		return $objComType;
		break;
	case '4':
		$objComType->sms = 'ignition_email';
		$objComType->email = 'ignition_sms';
		$objComType->telephone = 'ignition_telephone';
		$objComType->mobile = 'ignition_mobilenotification';
		$objComType->subject = 'Ignition Status';
		return $objComType;
		break;
	case '5':
		$objComType->sms = 'speed_email';
		$objComType->email = 'speed_sms';
		$objComType->telephone = 'speed_telephone';
		$objComType->mobile = 'speed_mobilenotification';
		$objComType->subject = 'Over Speeding Status';
		return $objComType;
		break;
	case '6':
		$objComType->sms = 'power_email';
		$objComType->email = 'power_sms';
		$objComType->telephone = 'power_telephone';
		$objComType->mobile = 'power_mobilenotification';
		$objComType->subject = 'Power Cut Status';
		return $objComType;
		break;
	case '7':
		$objComType->sms = 'tamper_email';
		$objComType->email = 'tamper_sms';
		$objComType->telephone = 'tamper_telephone';
		$objComType->mobile = 'tamper_mobilenotification';
		$objComType->subject = 'Tamper Status';
		return $objComType;
		break;
	case '8':
		$objComType->sms = 'temp_email';
		$objComType->email = 'temp_sms';
		$objComType->telephone = 'temp_telephone';
		$objComType->mobile = 'temp_mobilenotification';
		$objComType->subject = 'Temperature Status';
		return $objComType;
		break;
	case '9':
		$objComType->sms = 'is_chk_email';
		$objComType->email = 'is_chk_sms';
		$objComType->telephone = 'is_chk_telephone';
		$objComType->mobile = 'is_chk_mobilenotification';
		$objComType->subject = 'Stoppage Status';
		return $objComType;
		break;
	case '10':
		$objComType->sms = 'is_trans_email';
		$objComType->email = 'is_trans_sms';
		$objComType->telephone = 'is_trans_telephone';
		$objComType->mobile = 'is_trans_mobilenotification';
		$objComType->subject = 'Stoppage Status';
		return $objComType;
		break;
	case '11':
		$objComType->sms = 'harsh_break_mail';
		$objComType->email = 'harsh_break_sms';
		$objComType->telephone = 'harsh_break_telephone';
		$objComType->mobile = 'harsh_break_mobilenotification';
		$objComType->subject = 'Harsh Break Status';
		return $objComType;
		break;
	case '12':
		$objComType->sms = 'high_acce_mail';
		$objComType->email = 'high_acce_sms';
		$objComType->telephone = 'high_acce_telephone';
		$objComType->mobile = 'high_acce_mobilenotification';
		$objComType->subject = 'Sudden Acceleration Status';
		return $objComType;
		break;
	case '13':
		$objComType->sms = 'sharp_turn_mail';
		$objComType->email = 'sharp_turn_sms';
		$objComType->telephone = 'sharp_turn_telephone';
		$objComType->mobile = 'sharp_turn_mobilenotification';
		$objComType->subject = 'Sharp Turn Status';
		return $objComType;
		break;
	case '14':
		$objComType->sms = 'towing_mail';
		$objComType->email = 'towing_sms';
		$objComType->telephone = 'towing_telephone';
		$objComType->mobile = 'towing_mobilenotification';
		$objComType->subject = 'Towing status';
		return $objComType;
		break;
	case '15':
		$objComType->sms = 'panic_email';
		$objComType->email = 'panic_sms';
		$objComType->telephone = 'panic_telephone';
		$objComType->mobile = 'panic_mobilenotification';
		$objComType->subject = 'Panic status';
		return $objComType;
		break;
	case '16':
		$objComType->sms = 'door_email';
		$objComType->email = 'door_sms';
		$objComType->telephone = 'door_telephone';
		$objComType->mobile = 'door_mobilenotification';
		$objComType->subject = 'Door status';
		return $objComType;
		break;
	case '17':
		$objComType->sms = 'freeze_email';
		$objComType->email = 'freeze_sms';
		$objComType->telephone = 'freeze_telephone';
		$objComType->mobile = 'freeze_mobilenotification';
		$objComType->subject = 'Freeze status';
		return $objComType;
		break;
	case '18':
		$objComType->sms = 'checkpointwise_email_alert';
		$objComType->email = 'checkpointwise_sms_alert';
		$objComType->telephone = 'checkpointwise_telephone_alert';
		$objComType->mobile = 'checkpointwise_mobilenotification_alert';
		$objComType->subject = 'Checkpoint stoppage alerts';
		return $objComType;
		break;
	}

}