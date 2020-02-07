<?php
session_start();
//error_reporting("Error");

	//**************** include classes *************************
	
	require_once("database.inc.php");
	

	
	
	//**************** Database Configuration local development server ****************
	define("DATABASE_HOST","localhost",true);
	define("DATABASE_PORT","3306",true);
	define("DATABASE_USER","root",true);
	define("DATABASE_PASSWORD","",true);
	define("DATABASE_NAME","speed",true);
	
	//**************** Database Configuration online development server ****************
	
	/*define("DATABASE_HOST","localhost",true);
	define("DATABASE_PORT","3306",true);
	define("DATABASE_USER","elixiaspeed",true);
	define("DATABASE_PASSWORD","123456",true);
	define("DATABASE_NAME","epolice",true);*/
	
	//*************** Set Time Zone ***************************//
	
	date_default_timezone_set("Asia/Calcutta");
        $SMS_URL = "http://pacems.asia:8080/bulksms/bulksms?username=xzt-elixia&password=elixia&type=0&dlr=1&destination={{PHONENO}}&source=ELIXIA&message={{MESSAGETEXT}}";
        define('SMS_URL', $SMS_URL);
	
?>