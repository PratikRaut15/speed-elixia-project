<?php

/*
  Name            -    cronConvertUnitStatusToReady.php
  Description     -    Change device status to ready
  but lesser odometer
  Parameters      -    unitno,vehicleno[optional],simcardno
  Module          -    VTS
  Created By      -    Arvind Thakur
  Created On      -    05 July, 2019
  URL             -    http://speed.elixiatech.com/modules/cron/cronConvertUnitStatusToReady.php?unitno=1740010014710&vehicleno=MH6T9211&simcardno=750695002
  Change details
  1)
  Updated By    -
  Updated On    -
  Reason        -
  2)
 */
require_once '../../lib/system/DatabaseManager.php';
require_once '../../lib/system/Date.php';
require_once '../../lib/system/Sanitise.php';
require_once '../../lib/system/Validator.php';

$unitno = isset($_REQUEST['unitno']) ? $_REQUEST['unitno'] : '';
$vehicleno = isset($_REQUEST['vehicleno']) ? $_REQUEST['vehicleno'] : '';
$simcardno = isset($_REQUEST['simcardno']) ? $_REQUEST['simcardno'] : '';
$customerno = 1;

date_default_timezone_set('Asia/Kolkata');
$db = new DatabaseManager();
$message = '';

$todaysDate = date('Y-m-d H:i:s');
$pdo = $db->CreatePDOConn();
$sp_params = "'" . $unitno . "'"
        . ",'" . $vehicleno . "'"
        . ",'" . $simcardno . "'"
        . ",'" . $customerno . "'"
        . ",'" . $todaysDate . "'"
        . ",@message";



$QUERY = "CALL convertUnitStatusToReady($sp_params)";
$result = $pdo->query($QUERY)->fetch(PDO::FETCH_ASSOC);

$OutQuery = "SELECT @message AS message";
if ($result = $pdo->query($OutQuery)) {
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $message = $row['message'];
    echo $message;
}
$db->ClosePDOConn($pdo);

?>
