<?php
$url = 'https://belitaindia:25f47ce1b0653fc384daf9baff8a0cfb279c56f4@twilix.exotel.in/v1/Accounts/belitaindia/Sms/send';
$fields = array(
            'From' => 'LM-BELITA',
            'To' => '9819334888',
            'Body' => 'Test Message');

foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

$ch = curl_init();

curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    
$result = curl_exec($ch);
curl_close($ch);    
return true;
?>