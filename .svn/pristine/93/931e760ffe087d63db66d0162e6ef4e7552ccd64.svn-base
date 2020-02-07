<?php
// Requires config.inc.php - must be included as part of session or somewhere.
global $db_hostname;
global $db_loginname;
global $db_loginpassword;
global $db_databasename;
global $connection;
//connect to MySQL and select database to use
$connection = @mysql_connect($db_hostname,$db_loginname,$db_loginpassword) or die(mysql_error());
$db = @mysql_select_db($db_databasename,$connection) or die(mysql_error());
?>