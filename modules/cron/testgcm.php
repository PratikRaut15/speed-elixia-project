<?php

$message = array(
    "message" => "Test Message",
    "vehicleid" => "12454",
    "type" => 4,
);
//$this->insert_gcm_cron($registatoin_ids,$message,$customerno);
// Set POST variables
$url = 'https://android.googleapis.com/gcm/send';

$fields = array(
    'registration_ids' => array("c_tn7oI3kHk:APA91bEpwwcLZUoBecH2sumyPGmU_kQyIugjeUYcHEVXUU1KX-SiBAF2tKb9BBpMuNv4W2g-Ow8vEOg-1bhf7D6mOloRJyEyPp2tkkuROVsoiz7SQbhe9JGcrDUEuAr-EVWtgpEdAv-d"),
    'data' => $message,
);

$headers = array(
    'Authorization: key=' . GOOGLE_API_KEY,
    'Content-Type: application/json',
);
// Open connection
$ch = curl_init();

// Set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Disabling SSL Certificate support temporarly
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

// Execute post
$result = curl_exec($ch);
if ($result === FALSE) {
    die('Curl failed: ' . curl_error($ch));
}
// Close connection
curl_close($ch);
return $result;

?>