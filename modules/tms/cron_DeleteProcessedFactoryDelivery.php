<?php

include 'tms_function.php';

$customerno = 116;
$userid = 485;
$objDateParam = new DateTime();
$objDateParam->sub(new DateInterval('P1W'));
echo $daterequired = $objDateParam->format(DATEFORMAT_YMD);
$factory_delivery = array();
$objFact = new FactoryDeliveryDetails();
$objFact->customerno = $customerno;
$objFact->isprocessed = 1;
$objFact->date_required = $daterequired;
$objTms = new TMS($customerno, $userid);
$objTms->insert_factory_delivery_history($objFact);
//delete_factory_delivery_history($objFact);
?>
