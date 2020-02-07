<?php

require_once '../../lib/system/DatabaseManager.php';
require_once '../../lib/system/Date.php';
require_once '../../lib/system/Sanitise.php';
require_once '../../lib/system/Validator.php';

class forgotpass {
    
}

date_default_timezone_set('Asia/Kolkata');
$db = new DatabaseManager();

$today = date('Y-m-d H:i:s');
$pdo = $db->CreatePDOConn();
$sp_params = "'" . $today . "'"
;

$QUERY = "CALL delete_outdated_forgotpass_user ($sp_params)";

$result = $pdo->query($QUERY)->fetch(PDO::FETCH_ASSOC);

