<?php

@session_start();
date_default_timezone_set('' . $_SESSION['timezone'] . '');
require_once '../../lib/system/Log.php';
$log = new Log();
$log->createlog($_SESSION['customerno'], "Logged Out", $_SESSION['userid']);
session_unset();
session_destroy();
header("location: ../../index.php");
?>
