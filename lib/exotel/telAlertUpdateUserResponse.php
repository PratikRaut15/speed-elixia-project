<?php

include_once '../../lib/system/Log.php';
include_once '../../lib/system/utilities.php';


$message = "Greetings from Elixia Tech!! This is the telephonic alert to inform about the temperature conflict.";

/*
  Exotel would pass following params:
 * 
  CallSid	string; crontempseunique identifier of the call
  From	string; the number of the calling party
  To	string; your Exotel company number that is being called; this will be from your "Company Numbers" page
  DialWhomNumber	string; the number that is being called currently (This might be empty also)
 * 
  Need to identify the message text on the basis of CallSid and pass it as response.
 * 
 */

$objLog = new Log();
$customerno = 1;
ob_start();
print_r($_REQUEST);
$data = ob_get_clean();
$username = "EXOTEL-TEXTCALL";
$objLog->createlog($customerno, $data, $username);
header("Content-type: text/plain");
echo $message;
?>