<?php

ini_set('display_errors', 'on');
set_time_limit(60);
$RELATIVE_PATH_DOTS = "../../../";
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . "lib/autoload.php";
require_once "class/clsTMSApi.php";

$objTMSApi = new clsTMSApi();
extract($_REQUEST);

if ($action == "login") {
    $outputJsonData = $objTMSApi->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    $inputJsonData = json_decode($jsonreq);
    if (isset($inputJsonData->username) && isset($inputJsonData->password)) {
        $objResult = $objTMSApi->login($inputJsonData);
        $message = "Login successful.";
        $outputJsonData = $objTMSApi->success($message, $objResult);
    }
    echo json_encode($outputJsonData);
}
