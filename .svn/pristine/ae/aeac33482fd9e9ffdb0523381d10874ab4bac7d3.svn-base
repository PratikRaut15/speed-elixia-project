<?php
//error_reporting(0);
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
require_once 'class/config.inc.php';
require_once'DriverManager.php';
require_once'constants.php';
//require_once "../../../lib/system/utilities.php";
// object of class driver
$apiobj = new DRIVER();

extract($_REQUEST);

if($action=='pullcredentials')
{
    $driver=$apiobj->check_login($username,$password);
}

if($action=='starttrip')
{
    $driver=$apiobj->start_trip($userkey,$startduration);
}

if($action=='endtrip')
{
    $driver=$apiobj->end_trip($userkey,$endduration);
}

?>