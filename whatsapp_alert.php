<?php
$INSTANCE_ID = '14';  // TODO: Replace it with your gateway instance ID here
$CLIENT_ID = "sanketsheth@elixiatech.com";  // TODO: Replace it with your Forever Green client ID here
$CLIENT_SECRET = "0ef498304cc94944b237482597506f88";   // TODO: Replace it with your Forever Green client secret here
$postData = array(
  'number' => '919820278032',  // TODO: Specify the recipient's number here. NOT the gateway number
  'message' => 'Howdy! Is this exciting?'
);
$headers = array(
  'Content-Type: application/json',
  'X-WM-CLIENT-ID: '.$CLIENT_ID,
  'X-WM-CLIENT-SECRET: '.$CLIENT_SECRET
);
$url = 'http://api.whatsmate.net/v3/whatsapp/single/text/message/' . $INSTANCE_ID;
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
$response = curl_exec($ch);
echo "Response: ".$response;
curl_close($ch);
?>