<?php
session_start();
error_reporting("Error");

	//**************** include classes *************************
	require_once("global.config.php");
	require_once("database.inc.php");
	require_once("class.display.php");
	require_once("class.Authentication.php");
	require_once("ClsJSFormValidation.cls.php");
	require_once("class.FormValidation.php");
	require_once("class.Notification.php");
	require_once("class.user.php");
	require_once("liveX/PHPLiveX.php");
	require_once("class/Date.php");

	
	
	//**************** Database Configuration local development server ****************
	define("DATABASE_HOST","localhost",true);
	define("DATABASE_PORT","3306",true);
	define("DATABASE_USER","UserSpeed",true);
	define("DATABASE_PASSWORD","el!365x!@",true);
	define("DATABASE_NAME","service",true);
	//real_mobility emobility
	//**************** Database Configuration online development server ****************
	
	/*define("DATABASE_HOST","localhost",true);
	define("DATABASE_PORT","3306",true);
	define("DATABASE_USER","elixiaspeed",true);
	define("DATABASE_PASSWORD","123456",true);
	define("DATABASE_NAME","epolice",true);*/
	
	//*************** Set Time Zone ***************************//
	
	date_default_timezone_set("Asia/Calcutta");                     
	
?>