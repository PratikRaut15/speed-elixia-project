<?php
 $RELATIVE_PATH_DOTS = "../../../";
include_once $RELATIVE_PATH_DOTS.'lib/autoload.php';

if (isset($_POST['dataTest'])) {
    
    $emailText=$_POST['dataTest'];
    $customerno=$_POST['customerno'];
   
    $devicemanager = new DeviceManager($customerno);
    
    $devices = $devicemanager->sendEmailId($emailText,$customerno);
}
?>