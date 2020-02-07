<?php
if (!isset($_SESSION)) {
    session_start();
}
//error_reporting("Error");

//**************** include classes *************************
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}

	//require_once $RELATIVE_PATH_DOTS . "deliveryapi/class/global.config.php";
	require_once $RELATIVE_PATH_DOTS . "deliveryapi/class/database.inc.php";
	
	//**************** Database Configuration local development server ****************
	define("DATABASE_HOST","localhost",true);
	define("DATABASE_PORT","3306",true);
	define("DATABASE_USER","UserSpeed",true);
	define("DATABASE_PASSWORD","el!365x!@",true);
	define("DATABASE_NAME","speed_delivery",true);
	define("DATABASE_PICKUP","speed_pickup",true);
	define("DATABASE_SPEED","speed",true);
        
	//**************** Database Configuration online development server ****************
	
	/*define("DATABASE_HOST","localhost",true);
	define("DATABASE_PORT","3306",true);
	define("DATABASE_USER","elixiaspeed",true);
	define("DATABASE_PASSWORD","123456",true);
	define("DATABASE_NAME","epolice",true);*/
	
	//*************** Set Time Zone ***************************//
	
	date_default_timezone_set("Asia/Calcutta");                     
	
?>
