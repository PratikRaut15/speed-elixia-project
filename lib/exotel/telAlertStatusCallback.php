<?php

include_once "../../lib/system/Log.php";
include_once "../../lib/system/utilities.php";

$objLog = new Log();
$customerno = 1;
ob_start();
print_r($_REQUEST);
$data = ob_get_clean();
$username = "EXOTEL-STATUSCALLBACK";
$objLog->createlog($customerno, $data, $username);

/*
Update status against the callSid and call API again in case call status is not completed.
 * 
Greetings from Elixia Tech!! This is the telephonic alert to inform about the temperature conflict.
 */