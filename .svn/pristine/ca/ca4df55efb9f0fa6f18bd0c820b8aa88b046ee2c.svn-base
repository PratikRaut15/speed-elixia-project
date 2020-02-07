<?php
include_once("config.inc.php");
if(isset($_SESSION))
{
exit;
}

// If objects are to go into session, they MUST be defined before the session is started.
include_once("lib/model/VOUser.php");

  ini_set('session.gc_maxlifetime',7200); // Timeout after two hours
  ini_set('session.gc_probability',1);
  ini_set('session.gc_divisor',1);
  
session_start();
header("Cache-control: private");

define('Session_User', 'sessionuser');
define('Session_Customer', 'sessioncustomer');
define('Session_SecurityURL', 'securityurl');
define('Session_LastURL', 'lasturl');
define('Session_SelectedTab', 'selectedtab');


$sessionvars = array(
Session_User,
Session_Customer,
'db_hostname',
'db_loginname',
'db_loginpassword',
'db_databasename',
Session_SecurityURL,
Session_LastURL,
Session_SelectedTab,);

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
function registerSession()
{
global $sessionvars;
    foreach($sessionvars as $i => $value )
    {
        if(!session_is_registered($sessionvars[$i]))
        {
            session_register($sessionvars[$i]);
        }
    }
}

registerSession();
 
Setdb_databasename($db_databasename);
Setdb_hostname($db_hostname);
Setdb_loginname($db_loginname);
Setdb_loginpassword($db_loginpassword);

// DB Stuff

// Get Session Variables
function Getdb_hostname()
{
    return $_SESSION["db_hostname"];
}
function Getdb_loginname()
{
    return $_SESSION["db_loginname"];
}
function Getdb_loginpassword()
{
    return $_SESSION["db_loginpassword"];
}
function Getdb_databasename()
{
    return $_SESSION["db_databasename"];
}
function GetUsername()
{
    return $_SESSION["username"];
}
function GetCustomerno()
{
    return $_SESSION["customerno"];
}
function GetLoggedInUserId()
{
    return $_SESSION["userid"];
}
function GetRealname()
{
    return $_SESSION["realname"];
}
function GetBanner()
{
    return $_SESSION["banner"];
}
function GetLogo()
{
    return $_SESSION["logo"];
}
function GetLoggedInUser()
{
	return $_SESSION[Session_User];
}
function GetLoggedInUserRole()
{
	return $_SESSION[Session_UserRole];
}
function IsLoggedInUser()
{
    return $_SESSION["isloggedin"];
}


// Set Session Variables
function Setdb_hostname( $value )
{
    $_SESSION["db_hostname"] = $value;
}
function Setdb_loginname($value)
{
    $_SESSION["db_loginname"] = $value;
}
function Setdb_loginpassword($value)
{
    $_SESSION["db_loginpassword"] = $value;
}
function Setdb_databasename($value)
{
    $_SESSION["db_databasename"] = $value;
}
function SetUsername( $value )
{
    $_SESSION["username"] = $value;
}
function SetCustomerno( $value )
{
    $_SESSION["customerno"] = $value;
}
function SetLoggedInUserId( $value )
{
    $_SESSION["userid"] = $value;
}
function SetRealname( $value )
{
    $_SESSION["realname"] = $value;
}
function SetIsLoggedIn( $value )
{
    $_SESSION["isloggedin"] = $value;
}
function SetBanner( $value )
{
    $_SESSION["banner"] = $value;
}
function SetLogo( $value )
{
    $_SESSION["logo"] = $value;
}
function SetLoggedInUserRole( $PassedUserVoRole )
{
    $_SESSION[Session_UserRole] = $PassedUserVoRole;
}
function SetLoggedInUser( $PassedUserVo )
{
    $_SESSION[Session_User] = $PassedUserVo;
}



// Security
function StorePageURL( $url)
{
    $_SESSION[Session_LastURL] = $url;
}
function GetLastURL()
{
    return $_SESSION[Session_LastURL];
}
function SetSecurityURL( $url )
{
	$_SESSION[Session_SecurityURL] = $url;
}
function GetSecurityURL()
{
	return $_SESSION[Session_SecurityURL];
}
function IsLoggedIn()
{
	return (GetLoggedInUser()!=NULL);
}
function GetCustomer()
{
	return $_SESSION[Session_Customer];
}

function SetCustomer( $PassedCustomerVo)
{
	$_SESSION[Session_Customer] = $PassedCustomerVo;
}

// Licensing
function SetUnlicensed()
{
    $_SESSION["sessionlicensed"] = "N";
}
function SetLicensed()
{
    $_SESSION["sessionlicensed"] = "Y";
}
function IsLicensed()
{
    return $_SESSION["sessionlicensed"]=="Y";
}

$includeroot= $_SERVER['DOCUMENT_ROOT'] . $subdir ."/";

// Clear Session
function ClearSession()
{
	/*global $sessionvars;

    foreach($sessionvars as $i => $value )
    {
        unset($_SESSION[$sessionvars[$i]]);
    }
    registerSession();*/
    unset($_SESSION["userid"]);
    unset($_SESSION["customerno"]);
}
?>