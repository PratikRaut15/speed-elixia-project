<?php
//file required
require_once("class/config.inc.php");
require_once("class/class.api.php");


$apiobj = new api();
$apiobj->event_alerts();


?>