<?php

$post_data = array(
    'VirtualNumber' => "02230770169",
    'Number' => "9969941084",
    'Language' => "en"
);

$exotel_sid = "elixiatech"; // Your Exotel SID - Get it here: http://my.exotel.in/Exotel/settings/site#exotel-settings
$exotel_token = "2a58829d5022e65a2a2e5cc2db7b23f4fb90a9e0"; // Your exotel token - Get it here: http://my.exotel.in/Exotel/settings/site#exotel-settings


$url = "https://" . $exotel_sid . ":" . $exotel_token . "@twilix.exotel.in/v1/Accounts/" . $exotel_sid . "/CustomerWhitelist/";
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
foreach ($xml->children() as $child) {
    print_r($child);
}
//Parse and store response
?>