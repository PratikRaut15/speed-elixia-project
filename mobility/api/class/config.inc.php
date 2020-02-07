<?php
session_start();
//error_reporting("Error");

	//**************** include classes *************************
	require_once("global.config.php");
	require_once("database.inc.php");
	

	
	
	//**************** Database Configuration local development server ****************
	define("DATABASE_HOST","localhost",true);
	define("DATABASE_PORT","3306",true);
	define("DATABASE_USER","speeduser",true);
	define("DATABASE_PASSWORD","123456",true);
	define("DATABASE_NAME","service",true);
	
	
	
	date_default_timezone_set("Asia/Calcutta");                     
	
?>