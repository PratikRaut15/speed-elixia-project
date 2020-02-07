<?php
// Add the PHP include here also, not the best place perhaps
include_once("../config.inc.php");
// use variables defined in config
ini_set('include_path',ini_get('include_path'). PATH_SEPARATOR .$_SERVER['DOCUMENT_ROOT'] . $subdir . '/includes/');
$includeroot= $_SERVER['DOCUMENT_ROOT'] . $subdir ."/";
include_once("../lib/system/utilities.php");
session_start();
header("Cache-control: private");

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
function registerSession()
{
    if (!session_is_registered('sessionteamid'))
    {
        // The session variables have not been registered. Best do it then.
        session_register('sessionteamid');
    }
    if (!session_is_registered('sessionteamusername'))
    {
        session_register('sessionteamusername');
    }
    if (!session_is_registered('sessionteamloginname'))
    {
        session_register('sessionteamloginname');
    }
    if (!session_is_registered('db_teamloginpassword'))
    {
        session_register('db_teamloginpassword');
    }
    if (!session_is_registered('db_teamdatabasename'))
    {
        session_register('db_teamdatabasename');
    }

}

registerSession();
// Store the values from the config file.
Setdb_databasename($db_databasename);
Setdb_hostname($db_hostname);
Setdb_loginname($db_loginname);
Setdb_loginpassword($db_loginpassword);

// DB Stuff

function Getdb_hostname()
{
    return $_SESSION["db_resellerhostname"];
}
function Getdb_loginname()
{
    return $_SESSION["db_teamloginname"];
}
function Getdb_loginpassword()
{
    return $_SESSION["db_teamloginpassword"];
}
function Getdb_databasename()
{
    return $_SESSION["db_teamdatabasename"];
}

function Setdb_hostname( $value )
{
    $_SESSION["db_teamhostname"] = $value;
}
function Setdb_loginname($value)
{
    $_SESSION["db_teamloginname"] = $value;
}
function Setdb_loginpassword($value)
{
    $_SESSION["db_teamloginpassword"] = $value;
}
function Setdb_databasename($value)
{
    $_SESSION["db_teamdatabasename"] = $value;
}

// Regular settings


function GetLoginUser()
{
	return $_SESSION["sessionteamloginuser"];
}
function GetCustomerno()
{
	return false;
}
function SetLoginUser( $PassedLoginUser )
{
	$_SESSION["sessionteamloginuser"] = $PassedLoginUser;
}

function GetUsername()
{
	return $_SESSION["sessionteamusername"];
}
function SetUsername( $PassedUsername )
{
	$_SESSION["sessionteamusername"] = $PassedUsername;
}

function GetLoggedInUserId()
{
	return $_SESSION["sessionteamid"];
}
function SetLoggedInUserId( $PassedUserId )
{
	$_SESSION["sessionteamid"] = $PassedUserId;
}

function GetRole()
{
	return $_SESSION["sessionrole"];
}
function SetRole( $PassedUserId )
{
	$_SESSION["sessionrole"] = $PassedUserId;
}

function IsService()
{
    return (GetRole()=='Service');
}
function IsHead()
{
    return (GetRole()=='Head');
}
function IsAdmin()
{
    return (GetRole()=='Admin');
}
function IsSales()
{
    return (GetRole()=='Sales');
}
function IsSourcing()
{
    return (GetRole()=='Sourcing');
}


function IsLoggedIn()
{
	return (GetLoggedInUserId()!=NULL);
}

function ClearSession()
{
    unset($_SESSION["sessionteamid"]);    
    unset($_SESSION["sessionteamusername"]);
    unset($_SESSION["sessionteamloginuser"]);
    registerSession();
}
?>