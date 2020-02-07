<?php

// Requires config.inc.php - must be included as part of session or somewhere.
global $db_hostname;
global $db_loginname;
global $db_loginpassword;
global $db_databasename;
global $connection;

//connect to MySQLi and select database to use
$connection = @mysqli_connect($db_hostname,$db_loginname,$db_loginpassword) or die(mysqli_error());
$db = @mysqli_select_db($connection,$db_databasename) or die(mysqli_error());

?>