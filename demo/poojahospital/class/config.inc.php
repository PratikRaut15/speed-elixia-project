<?php

session_start();
//error_reporting("Error");
//**************** include classes *************************
require_once("database.inc.php");

//**************** Database Configuration local development server ****************
/*
define("DATABASE_HOST", "localhost", true);
define("DATABASE_PORT", "3306", true);
define("DATABASE_USER", "root", true);
define("DATABASE_PASSWORD", "", true);
define("DATABASE_NAME", "demo", true);
*/

//**************** Database Configuration Production Server ****************
	
	define("DATABASE_HOST","localhost",true);
	define("DATABASE_PORT","3306",true);
	define("DATABASE_USER","UserSpeed",true);
	define("DATABASE_PASSWORD","el!365x!@",true);
	define("DATABASE_NAME","demo",true);

//*************** Set Time Zone ***************************//

date_default_timezone_set("Asia/Calcutta");
?>