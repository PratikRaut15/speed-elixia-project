<?php

$sendTo = array('support@elixiatech.com', 'sanketsheth@elixiatech.com');


error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../../lib/autoload.php';
require_once '../../lib/system/utilities.php';
$crnmanager = new CronManager();

$crnmanager->resetSmsCount();

?>