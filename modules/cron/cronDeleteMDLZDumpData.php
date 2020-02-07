<?php

/*
  Name            -    cronDeleteMDLZDumpData.php
  Description     -    Delete realtime dump data of MDLZ
  but lesser odometer
  Parameters      -    days
  Module          -    VTS
  Created By      -    Arvind Thakur
  Created On      -    18 May, 2019
  URL             -    http://speed.elixiatech.com/modules/cron/cronDeleteMDLZDumpData.php?days=15
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

$days = isset($_REQUEST['days']) ? $_REQUEST['days'] : 0;

date_default_timezone_set('Asia/Kolkata');
$db = new DatabaseManager();
$isExecuted = 0;

$today = date('Y-m-d');
$pdo = $db->CreatePDOConn();
$sp_params = "'" . $days . "'"
        . ",'" . $today . "'"
        . ",@isExecuted";

$QUERY = "CALL delete_mdlz_dump_data($sp_params)";
$result = $pdo->query($QUERY)->fetch(PDO::FETCH_ASSOC);

$OutQuery = "SELECT @isExecuted AS isExecuted";
if ($result = $pdo->query($OutQuery)) {
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $isExecuted = $row['isExecuted'];
    if ($isExecuted == 1) {
        echo 'Successfully Deleted';
    } else {
        echo 'Failed';
    }
}
$db->ClosePDOConn($pdo);
?>


<?php
?>
