<?php

/* Generic Message would be sent in case of any exception occured. */
$message = "Greetings from Elixia Tech!! This is the telephonic alert to inform about the temperature conflict. "
	. "Please check the SMS sent to your registered mobile number.";

try {
	include_once "../../lib/system/utilities.php";
	include_once "../../lib/system/Log.php";
	include_once '../../lib/autoload.php';

	/*
		      Exotel would pass following params:
		      [CallSid] => e3faa911877645790c378807401ea356
		      [CallFrom] => <user mobileno>
		      [CallTo] => <exotel exophone>
		      [Direction] => outbound-dial
		      [Created] => Thu, 09 Feb 2017 15:46:53
		      [DialWhomNumber] =>
		      [From] => <user mobileno>
		      [To] => <exotel exophone>
		      [CustomField] => {"telAlertLogId"=1234}
		      [CurrentTime] => 2017-02-09 15:46:54
	*/

	$objRequestParams = (object) $_REQUEST;
	if (isset($objRequestParams->CustomField)) {
		$objCustomFields = json_decode($objRequestParams->CustomField);
		$objTelAlertsManager = new TelAlertsManager($objCustomFields->custNo, $objCustomFields->userId);
		$objTelAlert = new stdClass();

		if ($objCustomFields->customMessage != '') {
			$message = $objCustomFields->customMessage;
		}
		$objTelAlertsManager->updateTelAlert($objTelAlert);
		$objLog = new Log();
		$customerno = 1;
		ob_start();
		print_r($_REQUEST);
		$data = ob_get_clean();
		$username = "EXOTEL-TEXTCALL";
		$objLog->createlog($customerno, $data, $username);
	}
} catch (Exception $ex) {
	$objLog = new Log();
	$customerno = 0;
	ob_start();
	print_r($ex);
	$data = ob_get_clean();
	$username = "EXOTEL-TEXTCALL-EXCEPTION";
	$objLog->createlog($customerno, $data, $username);
}

header("Content-type: text/plain");
echo $message;
?>