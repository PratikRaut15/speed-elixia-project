<?php

//echo phpinfo();
//error_reporting(E_ALL ^E_STRICT);
//ini_set('display_errors', 'on');

require_once("class/config.inc.php");
require_once("class/class.api.php");
require_once '../lib/bo/PointLocationManager.php';
$apiobj = new api();
//$t = file_get_contents("datajson.json");
if (isset($_REQUEST['addreason'])) {
    $t = $_REQUEST['addreason'];
    $addreason = json_decode($t);
    $test = $apiobj->addReason($addreason);
    $testjson = json_encode($test);
    echo $testjson;
} else if (isset($_REQUEST['editreason'])) {
    $t = $_REQUEST['editreason'];
    $editreason = json_decode($t);
    $test = $apiobj->editReason($editreason);
    $testjson = json_encode($test);
    echo $testjson;
} 
else if (isset($_REQUEST['deletereason'])) {
    $t = $_REQUEST['deletereason'];
    $deletereason = json_decode($t);
    $test = $apiobj->deleteReason($deletereason);
   $testjson = json_encode($test);
    echo $testjson;
} 
?>
