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
$QUERY = "UPDATE ".WOWDB.".forgot_password_request SET isdeleted = 1  WHERE '".$today."' > validupto";
$result = $db->executeQuery($QUERY);
?>