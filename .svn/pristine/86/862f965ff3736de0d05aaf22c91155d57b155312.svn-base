<?php

include_once 'Log.php';
include_once 'utilities.php';

$objLog = new Log();
$customerno = 1;
ob_start();
print_r($_REQUEST);
$data = ob_get_clean();
$username = "EXOTEL-STATUSCALLBACK";
$objLog->createlog($customerno, $data, $username);
/*
if($_REQUEST["Status"] != "completed"){
	$post_data = array(
    'From' => "9421377403",
    'To' => "02230770273",
    'CallerId' => "02230770273",
    'Url' => "https://my.exotel.in/exoml/start/77700",
    'TimeLimit' => "30", //This is optional
    'TimeOut' => "60", //This is also optional
    'CallType' => "trans",
    'StatusCallback' => "http://speed.elixiatech.com/lib/system/testTelStatus.php" //This is also also optional
);

$exotel_sid = "elixiatech"; // Your Exotel SID - Get it here: http://my.exotel.in/Exotel/settings/site#exotel-settings
$exotel_token = "2a58829d5022e65a2a2e5cc2db7b23f4fb90a9e0"; // Your exotel token - Get it here: http://my.exotel.in/Exotel/settings/site#exotel-settings

$url = "https://" . $exotel_sid . ":" . $exotel_token . "@twilix.exotel.in/v1/Accounts/" . $exotel_sid . "/Calls/connect";

$ch = curl_init();
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FAILONERROR, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));

$http_result = curl_exec($ch);
$error = curl_error($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

//print "Response = " . print_r($http_result);
$xml = simplexml_load_string($http_result);
ob_start();
foreach ($xml->children() as $child) {
    print_r($child);
}

$data = ob_get_clean();
$username = "EXOTEL-STATUSCALLBACK-APICALL";
$objLog->createlog($customerno, $data, $username);
}
*/
/*
Update
 *
Greetings from Elixia Tech!! This is the telephonic alert to inform about the temperature conflict.
 */
