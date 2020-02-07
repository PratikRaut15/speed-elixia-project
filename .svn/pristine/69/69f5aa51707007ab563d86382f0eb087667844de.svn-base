<?php

include_once 'Log.php';
include_once 'utilities.php';


$message = "Greetings from Elixia Tech. Vehicle Number 5 2 1 2 of Delhi branch has moved. Please check SMS for further details. Thank you.";

/*
  Exotel would pass following params:
 * 
  CallSid	string; unique identifier of the call
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