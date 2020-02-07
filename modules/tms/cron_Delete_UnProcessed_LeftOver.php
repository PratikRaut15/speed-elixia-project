<?php

include 'tms_function.php';

$customerno = 116;
$userid = 485;
$objDateParam = new DateTime();
$objDateParam->sub(new DateInterval('P1D'));
$daterequired = $objDateParam->format(DATEFORMAT_YMD);

$objLeftOverHistory = new stdClass();
$objLeftOverHistory->customerno = $customerno;
$objLeftOverHistory->date_required = $daterequired;
$objTms = new TMS($customerno, $userid);
$objTms->insert_leftover_unprocessed_history($objLeftOverHistory);



