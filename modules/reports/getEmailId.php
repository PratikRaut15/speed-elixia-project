<?php
   $customerno = $_REQUEST['customerno'];
   $term = $_REQUEST['term'];
    include_once '../../lib/bo/DeviceManager.php';
    $devicemanager = new DeviceManager($customerno);
    $mailIds = $devicemanager->getEmailList($term);
    echo $mailIds;
?>
